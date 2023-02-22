<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

class HotelSearchWord extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_search_words';
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
    public $timestamps = false;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';
    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'hotel_nm',
        'hotel_kn',
        'hotel_old_nm',
        'info',
        'address',
        'tel',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];

    // カラム
    public string $COL_HOTEL_CD         = "hotel_cd";
    public string $COL_HOTEL_NM         = "hotel_nm";
    public string $COL_HOTEL_KN         = "hotel_kn";
    public string $COL_HOTEL_OLD_NM     = "hotel_old_nm";
    public string $COL_INFO             = "info";
    public string $COL_ADDRESS          = "address";
    public string $COL_TEL              = "tel";

    /** コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

        $colHotelNm = new ValidationColumn();
        $colHotelNm->setColumnName($this->COL_HOTEL_NM, "施設名称")->length(0, 50)->notHalfKana();

        $colHotelKn = new ValidationColumn();
        $colHotelKn->setColumnName($this->COL_HOTEL_KN, "施設名称カナ")->length(0, 100)->notHalfKana();

        $colHotelOldNm = new ValidationColumn();
        $colHotelOldNm->setColumnName($this->COL_HOTEL_OLD_NM, "旧施設名称")->length(0, 50)->notHalfKana();

        $colInfo = new ValidationColumn();
        $colInfo->setColumnName($this->COL_INFO, "特色")->length(0, 1000)->notHalfKana();

        $colAddress = new ValidationColumn();
        $colAddress->setColumnName($this->COL_ADDRESS, "住所")->length(0, 105)->notHalfKana();

        $colTel = new ValidationColumn();
        $colTel->setColumnName($this->COL_TEL, "電話番号")->length(0, 15)->notHalfKana();

        parent::setColumnDataArray([$colHotelCd, $colHotelNm, $colHotelKn, $colHotelOldNm, $colInfo, $colAddress, $colTel]);
    }
}
