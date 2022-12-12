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

    protected $table = "hotel_control";
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
     * @var bool
     */
    public $timestamps = true;
    public const CREATED_AT = 'entry_ts';
    public const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'stock_type',
        'checksheet_send',
        'charge_round',
        'stay_cap',
        'management_status',
        'akafu_status',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_STOCK_TYPE = "stock_type";
	public string $COL_CHECKSHEET_SEND = "checksheet_send";
	public string $COL_CHARGE_ROUND = "charge_round";
	public string $COL_STAY_CAP = "stay_cap";
	public string $COL_MANAGEMENT_STATUS = "management_status";

    // カラム定数
    /*
        仕入タイプ
        MEMO: 移植元ソースのテーブル定義書を参照。
        実際に検証データに登録されている値に「-2」はなし。
        DB にはほかに「3」が見られるが、定義書に記載されていない。

        (追記）3 が特定施設（三普）であることは、ハードコーディングからの推測（以下などを参照）
            public\app\ctl\controllers\BrhotelController.php L.740 あたりの if 文
            public\app\ctl\views\brhotel\_input_hotel_form.tpl L.168 あたりの 「仕入タイプ」の選択肢
            「特別対応のためのもの」なので、使用しない。
    */
    public const STOCK_TYPE_CONTRACT_SALE = 0;             //受託販売
    public const STOCK_TYPE_PURCHASE_SALE = 1;             //買取販売
    public const STOCK_TYPE_BULK_CONTRACT_TOYOKO_INN = 2;  //一括受託（東横イン）
    public const STOCK_TYPE_BULK_CONTRACT_OLD_YADO_PLAZA = -2; //一括受託（旧宿ぷらざ）
    // public const STOCK_TYPE_SANPU = 3; // 特定施設(三普)

    // 送客リスト送信可否
    private const CHECKSHEET_SEND_FALSE = 0; // 送付しない
    private const CHECKSHEET_SEND_TRUE  = 1; // 送付する
    // 利用方法（複数選択可）
    // MEMO: 実質ビット演算
    private const MANAGEMENT_STATUS_FAX             = 1; // ファックス管理
    private const MANAGEMENT_STATUS_INTERNET        = 2; // インターネット管理
    private const MANAGEMENT_STATUS_FAX_INTERNET    = 3; // ファックス管理＋インターネット管理
    // 赤い風船在庫利用施設
    private const AKAFU_STATUS_FALSE    = 0; // 利用否
    private const AKAFU_STATUS_TRUE     = 1; // 利用施設

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
