<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerSiteRate extends CommonDBModel
{
    // use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'partner_site_rate';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     * (site_cd, accept_s_ymd, fee_type, stock_class) で PK になっているが、
     * Laravel では複合キーに対応していない
     */
    protected $primaryKey = 'site_cd';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        self::COL_SITE_CD,
        self::COL_ACCEPT_S_YMD,
        self::COL_FEE_TYPE,
        self::COL_STOCK_CLASS,
        self::COL_RATE,
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];

    // カラム
    const COL_SITE_CD       = 'site_cd';
    const COL_ACCEPT_S_YMD  = 'accept_s_ymd';
    const COL_FEE_TYPE      = 'fee_type';
    const COL_STOCK_CLASS   = 'stock_class';
    const COL_RATE          = 'rate';

    public function __construct()
    {
        // カラム情報の設定
        $colSiteCd = new ValidationColumn();
        $colSiteCd->setColumnName(self::COL_SITE_CD, 'サイトコード');
        $colAcceptSYmd = new ValidationColumn();
        $colAcceptSYmd->setColumnName(self::COL_ACCEPT_S_YMD, '開始日');
        $colFeeType = new ValidationColumn();
        $colFeeType->setColumnName(self::COL_FEE_TYPE, '手数料タイプ');
        $colStockClass = new ValidationColumn();
        $colStockClass->setColumnName(self::COL_STOCK_CLASS, '在庫種類');
        $colRate = new ValidationColumn();
        $colRate->setColumnName(self::COL_RATE, '手数料率');

        // バリデーション追加
        // サイトコード
        $colSiteCd->require();          // 必須入力チェック
        $colSiteCd->notHalfKana();      // 半角カナチェック
        $colSiteCd->length(0, 10);      // 長さチェック

        // 開始日
        $colAcceptSYmd->require();      // 必須入力チェック
        $colAcceptSYmd->correctDate();  // 日付チェック

        // 手数料タイプ
        $colFeeType->require();         // 必須入力チェック
        $colFeeType->length(0, 1);      // 長さチェック
        $colFeeType->intOnly();         // 数字：数値チェック

        // 在庫種類
        $colStockClass->require();      // 必須入力チェック
        $colStockClass->length(0, 1);   // 長さチェック
        $colStockClass->intOnly();      // 数字：数値チェック

        // 手数料率
        $colRate->length(0, 4);         // 長さチェック
        $colRate->intOnly();            // 数字：数値チェック

        parent::setColumnDataArray([
            $colSiteCd,
            $colAcceptSYmd,
            $colFeeType,
            $colStockClass,
            $colRate,
        ]);
    }
}
