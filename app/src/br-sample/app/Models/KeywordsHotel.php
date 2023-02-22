<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

class KeywordsHotel extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'keywords_hotel';
    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = ['hotel_cd', 'field_nm'];
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
        'field_nm',
        'keyword',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];

    // カラム
    public string $COL_HOTEL_CD         = "hotel_cd";
    public string $COL_FIELD_NM         = "field_nm";
    public string $COL_KEYWORD         = "keyword";

    /** コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

        $colFieldNm = new ValidationColumn();
        $colFieldNm->setColumnName($this->COL_FIELD_NM, "フィールド名称")->require()->length(0, 30)->notHalfKana();

        $colKeyword = new ValidationColumn();
        $colKeyword->setColumnName($this->COL_KEYWORD, "キーワード")->length(0, 1333)->notHalfKana();

        parent::setColumnDataArray([$colHotelCd, $colFieldNm, $colKeyword]);
    }
}
