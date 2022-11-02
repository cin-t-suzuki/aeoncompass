<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/** 
 * 施設状況
 */
class HotelStatus extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_status";
    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'hotel_cd';
    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_ENTRY_STATUS = "entry_status";
	public string $COL_CONTRACT_YMD = "contract_ymd";
	public string $COL_OPEN_YMD = "open_ymd";
	public string $COL_CLOSE_DTM = "close_dtm";

	public string $COL_ENTRY_TS = "entry_ts";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = new ValidationColumn();
		$colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
		$colEntryStatus = new ValidationColumn();
		$colEntryStatus->setColumnName($this->COL_ENTRY_STATUS, "登録状態")->require(); // パターンチェックは画面で制限しているため未実装
		$colContractYmd = new ValidationColumn();
		$colContractYmd->setColumnName($this->COL_CONTRACT_YMD, "契約日")->hyphenOrSlashDate(); 
		$colOpenYmd = new ValidationColumn();
		$colOpenYmd->setColumnName($this->COL_OPEN_YMD, "公開日")->hyphenOrSlashDate(); 
		$colCloseDtm = new ValidationColumn();
		$colCloseDtm->setColumnName($this->COL_CLOSE_DTM, "解約日次")->hyphenOrSlashDate(); 

		parent::setColumnDataArray([$colHotelCd, $colEntryStatus, $colContractYmd, $colOpenYmd, $colCloseDtm]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_ENTRY_STATUS => $data[0]->entry_status,
				$this->COL_CONTRACT_YMD => $data[0]->contract_ymd,
				$this->COL_OPEN_YMD => $data[0]->open_ymd,
				$this->COL_CLOSE_DTM => $data[0]->close_dtm,
				$this->COL_ENTRY_TS => $data[0]->entry_ts
			);
		}
		return null;
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
		$result = $con->table($this->table)->where($this->COL_HOTEL_CD, $data[$this->COL_HOTEL_CD])->update($data);

		return $result;
}



}
