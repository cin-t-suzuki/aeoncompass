<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 施設マルチサイトコントローラログイン状況（将来実装）
 * 
 */
class HotelMscLogin extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_msc_login";
	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_MSC_TYPE = "msc_type";
	public string $COL_LOGIN_DTM = "login_dtm";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana(); 
		$colMscType = (new ValidationColumn())->setColumnName($this->COL_msc_type, "マルチサイトコントローラタイプ")->length(0,2)->intOnly();
		$colLoginDtm = (new ValidationColumn())->setColumnName($this->COL_login_dtm, "最終ログイン日時")->correctDate(); 

		parent::setColumnDataArray([$colHotelCd, $colMscType, $colLoginDtm]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd, $mscType){
		$data = $this->where(array($this->COL_HOTEL_CD=>$hotelCd, $this->COL_MSC_TYPE=>$mscType))->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_MSC_TYPE => $data[0]->msc_type,
				$this->COL_LOGIN_DTM => $data[0]->login_dtm
			);
		}
		return null;
	}

	/** ホテルコードで検索
	 * 
	 *
	 * @param [type] $hotelCd
	 * @return array
	 */
	public function selectByHotelCd($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		$result = [];

		if(!empty($data) && count($data) > 0){
			foreach($data as $row){
				$result[] = [
					$this->COL_HOTEL_CD => $row->hotel_cd
					,$this->COL_MSC_TYPE => $row->msc_type
					,$this->COL_LOGIN_DTM => $row->login_dtm
				];
			}
		}
		return $result;
	}

	/** ホテルコードで検索し、MSC名称固定付与
	 * 
	 *
	 * @param [type] $hotelCd
	 * @return void
	 */
	public function getMscUsageSituation($hotelCd){

		$s_sql =<<< SQL
			select	msc_type,
				case
					when msc_type = 1 then '手間いらず'
					when msc_type = 2 then 'らくじゃん、らく通'
					when msc_type = 3 then 'リンカーン'
					when msc_type = 4 then '宿研'
					when msc_type = 5 then 'ねっぱん！'
					when msc_type = 6 then 'ねっぱん！（らくじゃんI/F）'
					when msc_type = 7 then 'かんざしクラウド'
					when msc_type = 8 then 'BRっぱん！'
					when msc_type = 9 then '三ぱん！'
				end as msc_nm,
				date_format(login_dtm, '%Y-%m-%d %H:%i:%s') as login_dtm
			from	hotel_msc_login
			where	hotel_cd = :hotel_cd
			order by	login_dtm desc
		SQL;

		$data = DB::select($s_sql, array('hotel_cd' => $hotelCd));

		$result = [];

		if(!empty($data) && count($data) > 0){
			foreach($data as $row){
				$result[] = [
					$this->COL_MSC_TYPE => $row->msc_type
					,'msc_nm' => $row->msc_nm
					,$this->COL_LOGIN_DTM => $row->login_dtm
				];
			}
		}
		return $result;
	}



}
