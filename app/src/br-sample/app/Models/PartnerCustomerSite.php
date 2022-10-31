<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerCustomerSite extends CommonDBModel
{
    // use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'partner_customer_site';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     *
     * MEMO: (customer_id, site_cd, fee_type) で PK になっているが、
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


    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        self::COL_CUSTOMER_ID,
        self::COL_SITE_CD,
        self::COL_FEE_TYPE,
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];

    const COL_CUSTOMER_ID = 'customer_id';
    const COL_SITE_CD     = 'site_cd';
    const COL_FEE_TYPE    = 'fee_type';

    public function __construct()
    {
        // カラム情報の設定
        $colCustomerId = new ValidationColumn();
        $colCustomerId->setColumnName(self::COL_CUSTOMER_ID, '支払先ID');
        $colSiteCd = new ValidationColumn();
        $colSiteCd->setColumnName(self::COL_SITE_CD, 'サイトコード');
        $colFeeType = new ValidationColumn();
        $colFeeType->setColumnName(self::COL_FEE_TYPE, '手数料タイプ');

        // バリデーション追加
        // 支払先ID
        $colCustomerId->require();      // 必須入力チェック
        $colCustomerId->notHalfKana();  // 半角カナチェック
        $colCustomerId->length(0, 20);  // 長さチェック

        // サイトコード
        $colSiteCd->require();          // 必須入力チェック
        $colSiteCd->notHalfKana();      // 半角カナチェック
        $colSiteCd->length(0, 10);      // 長さチェック

        // 手数料区分
        $colFeeType->require();         // 必須入力チェック
        $colFeeType->length(0, 1);      // 長さチェック
        $colFeeType->intOnly();         // 数字：数値チェック

        parent::setColumnDataArray([
            $colCustomerId,
            $colSiteCd,
            $colFeeType,
        ]);
    }
}
