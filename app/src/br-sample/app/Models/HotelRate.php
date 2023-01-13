<?php

namespace App\Models;

use App\Common\DateUtil;
use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/** 
 * システム利用料率マスタ
 */
class HotelRate extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_rate";

    // カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_BRANCH_NO = "branch_no";
	public string $COL_ACCEPT_S_YMD = "accept_s_ymd";
	public string $COL_SYSTEM_RATE = "system_rate";
	public string $COL_SYSTEM_RATE_OUT = "system_rate_out";

	public string $METHOD_SAVE = "save";
	public string $METHOD_UPDATE = "update";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
		$colBranchNo = (new ValidationColumn())->setColumnName($this->COL_BRANCH_NO, "枝番")->require()->length(0, 2)->currencyOnly(); 
		$colAcceptSYmd = (new ValidationColumn())->setColumnName($this->COL_ACCEPT_S_YMD, "開始日")->require()->hyphenOrSlashDate(); // 独自チェック checkAcceptSYmd
		$colSystemRate = (new ValidationColumn())->setColumnName($this->COL_SYSTEM_RATE, "システム利用料率")->require()->length(0, 3)->rateOnly()->currencyOnly();
		$colSystemRateOut = (new ValidationColumn())->setColumnName($this->COL_SYSTEM_RATE_OUT, "システム利用料率（他サイト）")->require()->length(0, 3)->rateOnly()->currencyOnly();

		parent::setColumnDataArray([$colHotelCd, $colBranchNo, $colAcceptSYmd, $colSystemRate, $colSystemRateOut]);
	}

	/** 開始日の独自のバリデーション
	 *
	 * @param [type] $errorList
	 * @param [type] $hotelRateData
	 * @param [type] $method
	 * @return List
	 */
	public function checkAcceptSYmd(&$errorList, $hotelRateData, $method){
		// 現在日より後ろであること

		if (date('Y-m-d', strtotime($hotelRateData['accept_s_ymd'])) < date('Y-m-d')){
			$errorList[] = '開始日は'. date('Y年m月d日'). 'より後の日付を入力してください。';
			return $errorList;
		}

		// DBに同じ日がないこと 開始日が既に登録されているかのチェック
			if ($method == $this->METHOD_SAVE){

				$s_sql =<<<SQL
					select	date_format(accept_s_ymd + 1, '%Y/%m/%d') as accept_s_ymd
					from	hotel_rate
					where	hotel_cd = :hotel_cd
						and	accept_s_ymd = str_to_date(:accept_s_ymd, '%Y-%m-%d %H:%i:%s')
					order by accept_s_ymd desc
		SQL;

				$a_conditions['hotel_cd']     = $hotelRateData['hotel_cd'];
				$a_conditions['accept_s_ymd'] = date('Y-m-d H:i:s', strtotime($hotelRateData['accept_s_ymd']));


			} elseif ($method == 'update') {
				$s_sql =<<<SQL
		select	date_format(accept_s_ymd + 1, '%Y/%m/%d') as accept_s_ymd
					from	hotel_rate
					where	hotel_cd   = :hotel_cd
						and	branch_no  != :branch_no
						and	accept_s_ymd = str_to_date(:accept_s_ymd, '%Y-%m-%d %H:%i:%s')
					order by accept_s_ymd desc
		SQL;

				$a_conditions['hotel_cd']     = $hotelRateData['hotel_cd'];
				$a_conditions['accept_s_ymd'] = date('Y-m-d H:i:s', strtotime($hotelRateData['accept_s_ymd']));
				$a_conditions['branch_no']    = $hotelRateData['branch_no'];
			}

			// データの取得
			$data = DB::select($s_sql, $a_conditions);

			if (count($data) > 0){
				$errorList[] = '開始日は既に設定されています。';
				return $errorList;
			}
	}

	/** 主キーで取得
	 * 
	 * @return array
	 */
	public function selectByKey($hotelCd, $branchNo){
		$data = $this->where(array($this->COL_HOTEL_CD=>$hotelCd, $this->COL_BRANCH_NO=>$branchNo))->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_BRANCH_NO => $data[0]->branch_no,
				$this->COL_ACCEPT_S_YMD => $data[0]->accept_s_ymd,
				$this->COL_SYSTEM_RATE => $data[0]->system_rate,
				$this->COL_SYSTEM_RATE_OUT => $data[0]->system_rate_out
			);
		}
		return [];
	}

	/** 新規登録(1件)
	 */
	public function singleInsert($con, $data){

		$result = $con->table($this->table)->insert($data);
		if(!$result){
			return "登録に失敗しました";
		}
		return "";
	}
	
	/**  キーで更新
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return エラーメッセージ
	 */
	public function updateByKey($con, $data){
		$result = $con->table($this->table)
			->where(array($this->COL_HOTEL_CD=>$data[$this->COL_HOTEL_CD],$this->COL_BRANCH_NO=>$data[$this->COL_BRANCH_NO]))->update($data);
		return $result;
	}

	/** キーで削除
	 * 
	 * @param [type] $con
	 * @param [type] $hotelCd
	 * @param [type] $branchNo
	 * @return void
	 */
	public function deleteByKey($con, $hotelCd, $branchNo){
		$result = $con->table($this->table)->where(array($this->COL_HOTEL_CD=>$hotelCd, $this->COL_BRANCH_NO=>$branchNo))->delete();
		return $result;
	}

	/** システム利用料一覧を取得
	 *  開始日の昇順でソート
	 */
	public function selectByHotelCd($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->orderBy($this->COL_ACCEPT_S_YMD,'desc')->get();

		$result = null;

		if(!empty($data) && count($data) > 0){
			foreach($data as $row){
				$result[] = [
					$this->COL_HOTEL_CD => $row->hotel_cd
					,$this->COL_BRANCH_NO => $row->branch_no
					,$this->COL_ACCEPT_S_YMD => $row->accept_s_ymd
					,$this->COL_SYSTEM_RATE => $row->system_rate
					,$this->COL_SYSTEM_RATE_OUT => $row->system_rate_out
				];
			}
		}
		return $result;
	}


}
