<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/**
 * 施設通知
 */
class HotelNotify extends CommonDBModel
{
    use Traits;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_notify';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     *
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
        'notify_status',
        'notify_device',
        'neppan_status',
        'notify_no',
        'notify_email',
        'notify_fax',
        'faxpr_status',
        'entry_cd',
        'modify_cd',
    ];

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_NOTIFY_DEVICE = "notify_device";
	public string $COL_NEPPAN_STATUS = "neppan_status";
	public string $COL_NOTIFY_STATUS = "notify_status";
	public string $COL_NOTIFY_NO = "notify_no";
	public string $COL_NOTIFY_EMAIL = "notify_email";
	public string $COL_NOTIFY_FAX = "notify_fax";
	public string $COL_FAXPR_STATUS = "faxpr_status";

    // カラム定数
    // 通知ステータス
    public const NOTIFY_STATUS_FALSE    = 0; // 通知しない
    public const NOTIFY_STATUS_TRUE     = 1; // 通知する

    /*
        通知媒体 (複数選択可)
            複数選択をビット列による集合表現で管理

        notify_device カラムの値を2進数で表したとき、
        下から（右から） NOTIFY_DEVICE_XXX 桁目(0はじまり) のフラグが立っている（1である）ならば、
        その通知方法が指定されている。
        例: 11 -> 8 + 2 + 1 -> 1011(2) -> [fax, 電子メール, リンカーン]

        cf. https://qiita.com/drken/items/7c6ff2aa4d8fce1c9361
     */
    public const NOTIFY_DEVICE_FAX          = 0; // fax
    public const NOTIFY_DEVICE_EMAIL        = 1; // 電子メール
    // public const NOTIFY_DEVICE_OPERATOR    = 2; // オペレータ連絡
    // MEMO: ↑ 移植元で、特殊な要件のために作ったもの、当システムでは利用しない
    public const NOTIFY_DEVICE_LINCOLN      = 3; // リンカーン

    // ねっぱん通知ステータス
    public const NEPPAN_STATUS_FALSE        = 0;    // 否通知
    public const NEPPAN_STATUS_TRUE         = 1;    // 通知
    public const NEPPAN_STATUS_PENDING      = null; // 通知しない(※連動時に「通知する」に自動切替)

    // FAXPR可否
    public const FAXPR_STATUS_FALSE     = 0; // 非表示
    public const FAXPR_STATUS_TRUE      = 1; // 表示

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana(); //TODO チェック
		$colNotifyDevice = (new ValidationColumn())->setColumnName($this->COL_notify_device, "通知媒体");//TODO チェック
		$colNeppanStatus = (new ValidationColumn())->setColumnName($this->COL_neppan_status, "ねっぱん通知ステータス");//TODO チェック
		$colNotifyStatus = (new ValidationColumn())->setColumnName($this->COL_notify_status, "通知ステータス");//TODO チェック
		$colNotifyNo = (new ValidationColumn())->setColumnName($this->COL_notify_no, "通知No");//TODO チェック
		$colNotifyEmail = (new ValidationColumn())->setColumnName($this->COL_notify_email, "通知電子メールアドレス");//TODO チェック
		$colNotifyFax = (new ValidationColumn())->setColumnName($this->COL_notify_fax, "通知ファックス番号");//TODO チェック
		$colFaxprStatus = (new ValidationColumn())->setColumnName($this->COL_faxpr_status, "FAXPR可否");//TODO チェック

		parent::setColumnDataArray([$colHotelCd, $colNotifyDevice, $colNeppanStatus, $colNotifyStatus, $colNotifyNo, $colNotifyEmail, $colNotifyFax, $colFaxprStatus]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_NOTIFY_DEVICE => $data[0]->notify_device,
				$this->COL_NEPPAN_STATUS => $data[0]->neppan_status,
				$this->COL_NOTIFY_STATUS => $data[0]->notify_status,
				$this->COL_NOTIFY_NO => $data[0]->notify_no,
				$this->COL_NOTIFY_EMAIL => $data[0]->notify_email,
				$this->COL_NOTIFY_FAX => $data[0]->notify_fax,
				$this->COL_FAXPR_STATUS => $data[0]->faxpr_status
			);
		}
		return null;
	}

}
