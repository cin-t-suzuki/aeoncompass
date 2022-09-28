<?php

namespace App\Models;

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

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = new ValidationColumn();
		$colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
		$colBranchNo = new ValidationColumn();
		
		$colBranchNo->setColumnName($this->COL_BRANCH_NO, "枝番")->require()->length(0, 2)->currencyOnly(); 
		$colAcceptSYmd = new ValidationColumn();
		$colAcceptSYmd->setColumnName($this->COL_ACCEPT_S_YMD, "開始日")->require()->hyphenOrSlashDate(); //TODO 独自チェック
		$colSystemRate = new ValidationColumn();
		$colSystemRate->setColumnName($this->COL_SYSTEM_RATE, "システム利用料率")->require()->length(0, 3)->currencyOnly()->rateOnly();//TODO 登録機能で要確認
		$colSystemRateOut = new ValidationColumn();
		$colSystemRateOut->setColumnName($this->COL_SYSTEM_RATE_OUT, "システム利用料率（他サイト）")->require()->length(0, 3)->currencyOnly()->rateOnly();//TODO 登録機能で要確認

		parent::setColumnDataArray([$colHotelCd, $colBranchNo, $colAcceptSYmd, $colSystemRate, $colSystemRateOut]);
	}

	//TODO 独自のバリデーション 使用箇所がある場合に実装
	public function accept_s_ymd_validate(){
	}

	/** 主キーで取得
	 */
	public function selectByHotelCd($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();

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
