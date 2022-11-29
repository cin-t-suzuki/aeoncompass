<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/** 
 * 施設管理
 */
class HotelControl extends CommonDBModel
{
    use Traits;

    protected $table = 'hotel_control';
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
    /**
     * モデルにタイムスタンプを付けるか
     *
     * MEMO: 独自実装でタイムスタンプを設定しているため、Laravel 側では設定しない。
     * HACK: (工数次第) Laravel の機能を使ったほうがよい気もする。
     *
     * @var bool
     */
    public $timestamps = false;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';

    // カラム
    public string $COL_HOTEL_CD             = 'hotel_cd';
    public string $COL_STOCK_TYPE           = 'stock_type';
    public string $COL_CHECKSHEET_SEND      = 'checksheet_send';
    public string $COL_CHARGE_ROUND         = 'charge_round';
    public string $COL_STAY_CAP             = 'stay_cap';
    public string $COL_MANAGEMENT_STATUS    = 'management_status';

    // カラム定数
    /*
        MEMO: 移植元ソースのテーブル定義書を参照。
        実際に検証データに登録されている値に「-2」はなし。
        DB にはほかに「3」が見られるが、定義書に記載されていない。
    */
    const STOCK_TYPE_CONTRACT_SALE = 0;             //受託販売
    const STOCK_TYPE_PURCHASE_SALE = 1;             //買取販売
    const STOCK_TYPE_BULK_CONTRACT_TOYOKO_INN = 2;  //一括受託（東横イン）
    const STOCK_TYPE_BULK_CONTRACT_OLD_YADO_PLAZA = -2; //一括受託（旧宿ぷらざ）

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = new ValidationColumn();
		$colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

		$colStockType = new ValidationColumn();
		$colStockType->setColumnName($this->COL_STOCK_TYPE, "仕入形態")->require(); // TODO パターンチェックを使用する機能で実装

		$colCheckSheetSend = new ValidationColumn();
		$colCheckSheetSend->setColumnName($this->COL_CHECKSHEET_SEND, "送客リスト送付可否")->require(); //TODO パターンチェックを使用する機能で実装

		$colChargeRound = new ValidationColumn();
		$colChargeRound->setColumnName($this->COL_CHARGE_ROUND, "金額切り捨て桁")->length(0, 3)->currencyOnly(); //TODO 登録処理で要確認

		$colStayCap = new ValidationColumn();
		$colStayCap->setColumnName($this->COL_STAY_CAP, "連泊限界数")->length(0, 2)->currencyOnly(); //TODO 登録処理で要確認、 独自チェック

		$colManagementStatus = new ValidationColumn();
		$colManagementStatus->setColumnName($this->COL_MANAGEMENT_STATUS, "利用方法")->require(); //TODO パターンチェックを使用する機能で実装

		parent::setColumnDataArray([$colHotelCd, $colStockType, $colCheckSheetSend, $colChargeRound, $colStayCap, $colManagementStatus]);
	}

	//TODO stay_cap_validate 使用機能で実装

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();

		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd
				,$this->COL_STOCK_TYPE => $data[0]->stock_type
				,$this->COL_CHECKSHEET_SEND => $data[0]->checksheet_send
				,$this->COL_CHARGE_ROUND => $data[0]->charge_round
				,$this->COL_STAY_CAP => $data[0]->stay_cap
				,$this->COL_MANAGEMENT_STATUS => $data[0]->management_status
			);
		}
		return null;

	}

}
