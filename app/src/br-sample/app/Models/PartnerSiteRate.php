<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     *
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

    // カラムの定数
    // 手数料タイプ (fee_type)
    // TODO: AC で同じ値を使うかは要確認
    const FEE_TYPE_SALE  = 1; // 販売
    const FEE_TYPE_STOCK = 2; // 在庫

    // 在庫種類 (stock_class)
    // TODO: AC で同じ値を使うかは要確認
    const STOCK_CLASS_GENERAL_ONLINE_STOCK = 1; // 一般ネット在庫
    const STOCK_CLASS_LINKED_STOCK_NORMAL  = 2; // 連動在庫（通常）
    const STOCK_CLASS_LINKED_STOCK_VISUAL  = 3; // 連動在庫（ヴィジュアル）
    const STOCK_CLASS_LINKED_STOCK_PREMIUM = 4; // 連動在庫（プレミアム）
    const STOCK_CLASS_TOYOKO_INN_STOCK     = 5; // 東横イン在庫

    // 料率タイプ
    const RATE_TYPE_UNSPECIFIED                 = 0;  // 0:指定なし
    const RATE_TYPE_SPECIAL_ALLIANCE_0_PERCENT  = 1;  // 1:特別提携    0% ベストリザーブオリジナルサイト・光通信等
    const RATE_TYPE_NORMAL_ALLIANCE_1_PERCENT   = 2;  // 2:通常提携    1%
    const RATE_TYPE_SPECIAL_ALLIANCE_2_PERCENT  = 3;  // 3:特別提携    2% アークスリー等
    const RATE_TYPE_NTA_BTM                     = 4;  // 4:日本旅行ビジネストラベルマネージメント（BTM）
    const RATE_TYPE_YAHOO_TRAVEL                = 5;  // 5:Yahoo!トラベル
    const RATE_TYPE_NTA_2_PERCENT               = 6;  // 6:日本旅行    2%
    const RATE_TYPE_NTA_3_PERCENT               = 7;  // 7:日本旅行    3% MSD等
    const RATE_TYPE_NTA_4_PERCENT               = 8;  // 8:日本旅行    4% JRおでかけネット
    const RATE_TYPE_NTA_RELO_CLUB               = 9;  // 9:日本旅行    リロクラブ
    const RATE_TYPE_GBTNTA                      = 10; // 10:GBTNTA 1%(在庫手数料0%)

    // 料率パターンテーブル
    // 画面で選択された料率タイプから、 (fee_type, stock_class) の 2*5 パターンそれぞれに対応する rate の値をもっている。
    const RATE_PATTERN_TABLE = [
        self::RATE_TYPE_SPECIAL_ALLIANCE_0_PERCENT => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 0,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
            ],
        ],
        self::RATE_TYPE_NORMAL_ALLIANCE_1_PERCENT => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 1,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 1,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 1,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
            ],
        ],
        self::RATE_TYPE_SPECIAL_ALLIANCE_2_PERCENT => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 2,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 2,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
            ],
        ],
        self::RATE_TYPE_NTA_BTM => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 2,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 2,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 1,
            ],
        ],
        self::RATE_TYPE_YAHOO_TRAVEL => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 0,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0.3,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 1.3,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 1.8,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 2,
            ],
        ],
        self::RATE_TYPE_NTA_2_PERCENT => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 2,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
            ],
        ],
        self::RATE_TYPE_NTA_3_PERCENT => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 3,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 3,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 3,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
            ],
        ],
        self::RATE_TYPE_NTA_4_PERCENT => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 4,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 3,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 4,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 4,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
            ],
        ],
        self::RATE_TYPE_NTA_RELO_CLUB => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 2,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 3,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 5,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
            ],
        ],
        self::RATE_TYPE_GBTNTA => [
            self::FEE_TYPE_SALE  => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 1,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 1,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 1,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 1,
            ],
            self::FEE_TYPE_STOCK => [
                self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                self::STOCK_CLASS_TOYOKO_INN_STOCK     => 0,
            ],
        ],
    ];

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


    /**
     * パートナー精算サイト手数料率検索
     *
     * @param  array $aa_conditions
     * @return stdClass[]
     */
    public function _get_rates($aa_conditions)
    {
        // バインドパラメータ設定
        $parameters = [];
        $whereSql = 'and site_cd = :site_cd';
        $parameters['site_cd'] = $aa_conditions['site_cd'];

        // HACK: かなりのハードコーディング？を含んでいる。
        $sql = <<<SQL
            select
                site_cd,
                date_format(accept_s_ymd, '%Y-%m-%d') as accept_s_ymd,
                case
                    when sales_1_rate = 0 and stock_1_rate = 0                      then '1:BR 0%'
                    when sales_1_rate = 1 and stock_1_rate = 0 and stock_2_rate = 1 then '2:BR 1%'
                    when sales_1_rate = 2 and stock_1_rate = 0 and stock_5_rate = 3 then '3:BR 2%'
                    when sales_1_rate = 2 and stock_1_rate = 0 and stock_5_rate = 1 then '4:BTM'
                    when stock_4_rate = 1.95                                        then '5:Yahoo!トラベル'
                    when stock_4_rate = 1.8                                         then '5:Yahoo!トラベル'
                    when stock_1_rate = 2                                           then '6:NTA 2%'
                    when stock_1_rate = 3                                           then '7:NTA 3% MSD等'
                    when stock_1_rate = 4                                           then '8:NTA 4% JRおでかけネット'
                    when stock_2_rate = 2 and stock_3_rate = 3 and stock_4_rate = 5 then '9:NTA リロクラブ'
                    when sales_1_rate = 1 and stock_1_rate = 0 and stock_2_rate = 0 then '10:GBTNTA 1%'
                end as rate_type,
                case
                    when sales_1_rate = 0 and stock_1_rate = 0                      then 1
                    when sales_1_rate = 1 and stock_1_rate = 0 and stock_2_rate = 1 then 2
                    when sales_1_rate = 2 and stock_1_rate = 0 and stock_5_rate = 3 then 3
                    when sales_1_rate = 2 and stock_1_rate = 0 and stock_5_rate = 1 then 4
                    when stock_4_rate = 1.95                                        then 5
                    when stock_4_rate = 1.8                                         then 5
                    when stock_1_rate = 2                                           then 6
                    when stock_1_rate = 3                                           then 7
                    when stock_1_rate = 4                                           then 8
                    when stock_2_rate = 2 and stock_3_rate = 3 and stock_4_rate = 5 then 9
                    when sales_1_rate = 1 and stock_1_rate = 0 and stock_2_rate = 0 then 10
                end as select_rate_index,
                sales_1_rate, -- 一般ネット在庫
                sales_2_rate, -- 連動在庫一般
                sales_3_rate, -- 連動在庫ヴィジュアル
                sales_4_rate, -- 連動在庫プレミアム
                sales_5_rate, -- 東横イン在庫
                stock_1_rate, -- 一般ネット在庫
                stock_2_rate, -- 連動在庫一般
                stock_3_rate, -- 連動在庫ヴィジュアル
                stock_4_rate, -- 連動在庫プレミアム
                stock_5_rate -- 東横イン在庫
            from
                (
                    select
                        site_cd,
                        accept_s_ymd,
                        max(case when fee_type = 1 and stock_class = 1 then rate else null end) as sales_1_rate, -- 一般ネット在庫
                        max(case when fee_type = 1 and stock_class = 2 then rate else null end) as sales_2_rate, -- 連動在庫一般
                        max(case when fee_type = 1 and stock_class = 3 then rate else null end) as sales_3_rate, -- 連動在庫ヴィジュアル
                        max(case when fee_type = 1 and stock_class = 4 then rate else null end) as sales_4_rate, -- 連動在庫プレミアム
                        max(case when fee_type = 1 and stock_class = 5 then rate else null end) as sales_5_rate, -- 東横イン在庫
                        max(case when fee_type = 2 and stock_class = 1 then rate else null end) as stock_1_rate, -- 一般ネット在庫
                        max(case when fee_type = 2 and stock_class = 2 then rate else null end) as stock_2_rate, -- 連動在庫一般
                        max(case when fee_type = 2 and stock_class = 3 then rate else null end) as stock_3_rate, -- 連動在庫ヴィジュアル
                        max(case when fee_type = 2 and stock_class = 4 then rate else null end) as stock_4_rate, -- 連動在庫プレミアム
                        max(case when fee_type = 2 and stock_class = 5 then rate else null end) as stock_5_rate -- 東横イン在庫
                    from
                        partner_site_rate
                    where 1 = 1
                        {$whereSql}
                    group by
                        site_cd,
                        accept_s_ymd
                ) q
            order by
                site_cd,
                accept_s_ymd desc
        SQL;

        $result = DB::select($sql, $parameters);
        return $result;
    }

    /**
     * パートナー精算サイト手数料率重複確認
     *
     * @param array $partnerSite
     * @return bool
     */
    public function _exists_rate($partnerSite)
    {
        // バインドパラメータ設定
        $whereSql = '';
        $parameters = [];
        $parameters['site_cd'] = $partnerSite['site_cd'];
        if (array_key_exists('partner_cd', $partnerSite) && !is_null($partnerSite['partner_cd']) && strlen($partnerSite['partner_cd']) > 0) {
            $parameters['partner_cd'] = $partnerSite['partner_cd'];
            $whereSql .= ' and partner_site.partner_cd = :partner_cd';
        }
        if (array_key_exists('affiliate_cd', $partnerSite) && !is_null($partnerSite['affiliate_cd']) && strlen($partnerSite['affiliate_cd']) > 0) {
            $parameters['affiliate_cd'] = $partnerSite['affiliate_cd'];
            $whereSql .= ' and partner_site.affiliate_cd = :affiliate_cd';
        }

        $sql = <<<SQL
            select distinct
                partner_site.site_cd,
                partner_site.site_nm
            from
                partner_site_rate
                inner join partner_site
                    on partner_site_rate.site_cd = partner_site.site_cd
            where 1 = 1
                and partner_site_rate.site_cd != :site_cd
                and partner_site_rate.fee_type = 2 -- self::FEE_TYPE_STOCK
                {$whereSql}
        SQL;

        $result = DB::select($sql, $parameters);

        return count($result) > 0;
    }

}
