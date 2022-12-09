<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use App\Util\Models_Cipher;
use Exception;
use Carbon\Carbon;

/**
 * パートナー精算マスタ
 */
class BillPayPtn extends CommonDBModel
{
    use Traits;

    protected $table = "mast_city";
    // カラム
    public string $COL_CITY_ID = "city_id";
    public string $COL_PREF_ID = "pref_id";
    public string $COL_CITY_NM = "city_nm";
    public string $COL_PREF_CITY_NM = "pref_city_nm";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_CITY_CD = "city_cd";
    public string $COL_DELETE_YMD = "delete_ymd";

    /**
     * コンストラクタ
     */
    public function __construct() //public追記でいいか？（function~だけだとphpcsエラー）
    {
        // カラム情報の設定
    }
 
    //======================================================================
    // 精算先サイトの取得
    // aa_conditions
    //    partner_cd    パートナーコード
    //    affiliate_cd  アフィリエイトコード
    //    site_cd       アフィリエイトコード
    //    fee_type      手数料タイプ(1:販売 2:在庫（NTA）3:補助金)
    //    billpay_ym    精算月（YYYY-MM)
    //======================================================================
    public function getSite($aa_conditions)
    {
        try {
            //初期化
            $s_partner_cd = '';
            $s_affiliate_cd = '';
            $s_site_cd = '';
            $s_fee_type = '';

            $a_condition['billpay_ym'] = $aa_conditions['billpay_ym'];
            if (!$this->is_empty($aa_conditions['partner_cd'] ?? null)) { //??null追記
                $s_partner_cd = 'and	billpay_ptn_site.partner_cd    = :partner_cd';
                $a_condition['partner_cd'] = $aa_conditions['partner_cd'];
            }
            if (!$this->is_empty($aa_conditions['affiliate_cd'] ?? null)) { //??null追記
                $s_affiliate_cd = 'and	billpay_ptn_site.affiliate_cd    = :affiliate_cd';
                $a_condition['affiliate_cd'] = $aa_conditions['affiliate_cd'];
            }
            if (!$this->is_empty($aa_conditions['site_cd'] ?? null)) { //??null追記
                $s_site_cd = 'and	billpay_ptn_site.site_cd    = :site_cd';
                $a_condition['site_cd'] = $aa_conditions['site_cd'];
            }
            if (!$this->is_empty($aa_conditions['fee_type'] ?? null)) { //??null追記
                $s_fee_type = 'and	billpay_ptn_cstmrsite.fee_type    = :fee_type';
                $a_condition['fee_type'] = $aa_conditions['fee_type'];
            }

            //カラム名のみに変更 to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.bill_ymd , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.bill_ymd , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as bill_ymd,
            //LEFT OUTER JOINへの書き換えであっているか
            $s_sql =
            <<< SQL
            select	billpay_ptn_cstmrsite.customer_id,
                    billpay_ptn_cstmr.customer_nm,
                    billpay_ptn_cstmrsite.site_cd,
                    billpay_ptn_cstmrsite.fee_type,
                    billpay_ptn_site.partner_cd,
                    billpay_ptn_site.affiliate_cd,
                    billpay_ptn_site.site_cd,
                    billpay_ptn_site.site_nm,
                    billpay_ptn.billpay_ptn_cd,
                    billpay_ptn_cstmr.document_type,
                    billpay_ptn_cstmr.billpay_day,
                    billpay_ptn_book.billpay_type,
                    billpay_ptn_book.book_path,
                    billpay_ptn_book.bill_ymd as bill_ymd, -- 書き換え問題ないか
                    partner_control.extension_state,
                    partner_control.connect_type
            from	billpay_ptn_cstmrsite,
                    billpay_ptn_cstmr,
                    billpay_ptn LEFT OUTER JOIN billpay_ptn_book as billpay_ptn_book2 -- ここからのfrom分書き換え問題ないか(whereにあった(+)をLEFTOUTERJOINに書き替えている)
                                    ON (date_format(billpay_ptn.billpay_ym, '%Y-%m') = date_format(billpay_ptn_book2.billpay_ym, '%Y-%m'))
                                LEFT OUTER JOIN billpay_ptn_book as billpay_ptn_book3
                                    ON (billpay_ptn.billpay_ptn_cd = billpay_ptn_book3.billpay_ptn_cd),
                    billpay_ptn_site LEFT OUTER JOIN partner_control as partner_control2
                                    ON (billpay_ptn_site.partner_cd = partner_control2.partner_cd),
                    billpay_ptn_book,
					partner_control
            where   date_format(billpay_ptn_site.billpay_ym, '%Y-%m')    = :billpay_ym -- 書き換え問題ないか
                {$s_partner_cd}
                {$s_affiliate_cd}
                {$s_site_cd}
                {$s_fee_type}
                and	date_format(billpay_ptn_cstmrsite.billpay_ym, '%Y-%m') 
                    = date_format(billpay_ptn_cstmr.billpay_ym, '%Y-%m') -- 書き換え問題ないか
                and	billpay_ptn_cstmrsite.customer_id   = billpay_ptn_cstmr.customer_id
                and	date_format(billpay_ptn_cstmrsite.billpay_ym, '%Y-%m')
                    = date_format(billpay_ptn_site.billpay_ym, '%Y-%m') -- 書き換え問題ないか
                and	billpay_ptn_cstmrsite.site_cd       = billpay_ptn_site.site_cd
                and	date_format(billpay_ptn_site.billpay_ym, '%Y-%m') -- 書き換え問題ないか
                    = date_format(billpay_ptn.billpay_ym, '%Y-%m') -- 書き換え問題ないか
                and	billpay_ptn_cstmrsite.site_cd       = billpay_ptn.site_cd
                and	billpay_ptn_cstmrsite.fee_type      = billpay_ptn.fee_type
            order by billpay_ptn_cstmrsite.customer_id desc
SQL;
            $a_row = DB::select($s_sql, $a_condition);
            $a_row = json_decode(json_encode($a_row), true); //json~追記しないとviewでエラー

            // ファイル名調整
            if (($a_row[0]['customer_id'] ?? null) == 1 && !$this->is_empty($a_row[0]['book_path'])) { //??null追記
                $a_row[0]['book_path'] = str_replace('.pdf', '-' . $a_row[0]['site_cd'] . '-' . ($a_row[0]['partner_cd'] ?? $a_row[0]['affiliate_cd']) . '.pdf', $a_row[0]['book_path']);
            }
            // ファイル名の暗号化
            // $o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);
            $cipher = new Models_Cipher(config('settings.cipher_key')); //書き換えあっている？
            $a_row[0]['book_path_encrypt'] = $cipher->encrypt($a_row[0]['book_path'] ?? null); //??null追記

            return $a_row[0];

        // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 月単位の精算状況
    // aa_conditions
    //    billpay_ptn_cd  パートナー精算コード
    //    customer_id     パートナー精算先コード
    //    site_cd        精算サイトコード
    //    billpay_ym     精算月（YYYY-MM)
    //======================================================================
    public function getBook($aa_conditions)
    {
        try {
            $s_customer_id = ''; //追記
            $s_customer_id2 = '';
            $s_customer_id3 = '';
            $s_customer_id4 = '';
            $s_customer_id5 = '';
            $s_customer_id6 = '';
            $s_site_cd = ''; //追記
            $s_site_cd2 = '';
            $s_site_cd3 = '';
            $s_site_cd4 = '';
            $s_site_cd5 = '';
            $s_site_cd6 = '';
            $s_billpay_ym = ''; //追記
            $s_billpay_ym2 = '';
            $s_billpay_ym3 = '';
            $s_billpay_ym4 = '';
            $s_billpay_ym5 = '';
            $s_billpay_ym6 = '';


            if ($this->is_empty($aa_conditions['billpay_ptn_cd'] ?? null)) { //null追記
                $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  is null';
            } else {
                $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd';
                $a_condition['billpay_ptn_cd'] = $aa_conditions['billpay_ptn_cd'];
                $s_billpay_ptn_cd2 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd2';
                $a_condition['billpay_ptn_cd2'] = $aa_conditions['billpay_ptn_cd'];
                $s_billpay_ptn_cd3 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd3';
                $a_condition['billpay_ptn_cd3'] = $aa_conditions['billpay_ptn_cd'];
                $s_billpay_ptn_cd4 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd4';
                $a_condition['billpay_ptn_cd4'] = $aa_conditions['billpay_ptn_cd'];
                $s_billpay_ptn_cd5 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd5';
                $a_condition['billpay_ptn_cd5'] = $aa_conditions['billpay_ptn_cd'];
                $s_billpay_ptn_cd6 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd6';
                $a_condition['billpay_ptn_cd6'] = $aa_conditions['billpay_ptn_cd'];
            }
            if (!$this->is_empty($aa_conditions['customer_id'] ?? null)) { //null追記
                $s_customer_id = 'and q03.customer_id  = :customer_id';
                $a_condition['customer_id'] = $aa_conditions['customer_id'];
                $s_customer_id2 = 'and q03.customer_id  = :customer_id2';
                $a_condition['customer_id2'] = $aa_conditions['customer_id'];
                $s_customer_id3 = 'and q03.customer_id  = :customer_id3';
                $a_condition['customer_id3'] = $aa_conditions['customer_id'];
                $s_customer_id4 = 'and q03.customer_id  = :customer_id4';
                $a_condition['customer_id4'] = $aa_conditions['customer_id'];
                $s_customer_id5 = 'and q03.customer_id  = :customer_id5';
                $a_condition['customer_id5'] = $aa_conditions['customer_id'];
                $s_customer_id6 = 'and q03.customer_id  = :customer_id6';
                $a_condition['customer_id6'] = $aa_conditions['customer_id'];
            }
            if (!$this->is_empty($aa_conditions['site_cd'] ?? null)) { //null追記
                $s_site_cd = 'and q01.site_cd  = :site_cd';
                $a_condition['site_cd'] = $aa_conditions['site_cd'];
                $s_site_cd2 = 'and q01.site_cd  = :site_cd2';
                $a_condition['site_cd2'] = $aa_conditions['site_cd'];
                $s_site_cd3 = 'and q01.site_cd  = :site_cd3';
                $a_condition['site_cd3'] = $aa_conditions['site_cd'];
                $s_site_cd4 = 'and q01.site_cd  = :site_cd4';
                $a_condition['site_cd4'] = $aa_conditions['site_cd'];
                $s_site_cd5 = 'and q01.site_cd  = :site_cd5';
                $a_condition['site_cd5'] = $aa_conditions['site_cd'];
                $s_site_cd6 = 'and q01.site_cd  = :site_cd6';
                $a_condition['site_cd6'] = $aa_conditions['site_cd'];
            }
            if (!$this->is_empty($aa_conditions['billpay_ym'] ?? null)) { //null追記
                $s_billpay_ym = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym'; //dateは不要？
                $a_condition['billpay_ym'] = $aa_conditions['billpay_ym'];
                $s_billpay_ym2 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym2';
                $a_condition['billpay_ym2'] = $aa_conditions['billpay_ym'];
                $s_billpay_ym3 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym3';
                $a_condition['billpay_ym3'] = $aa_conditions['billpay_ym'];
                $s_billpay_ym4 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym4';
                $a_condition['billpay_ym4'] = $aa_conditions['billpay_ym'];
                $s_billpay_ym5 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym5';
                $a_condition['billpay_ym5'] = $aa_conditions['billpay_ym'];
                $s_billpay_ym6 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym6';
                $a_condition['billpay_ym6'] = $aa_conditions['billpay_ym'];
            }

            $s_sql =
                <<<SQL
                    select	billpay_ym, -- 書き換え問題ないか
                            customer_id,
                            site_cd                          as site_cd,
                            site_nm                          as site_nm,
                            partner_cd                       as partner_cd,
                            affiliate_cd                     as affiliate_cd,
                            rate                             as rate,
                            sum(IfNull(bill_sales_count, 0))    as bill_sales_count,
                            sum(IfNull(bill_cancel_count, 0))   as bill_cancel_count,
                            sum(IfNull(bill_charge, 0))         as bill_charge,
                            sum(IfNull(bill_charge_tax, 0))     as bill_charge_tax,
                            sum(IfNull(later_sales_count, 0))   as later_sales_count,
                            sum(IfNull(later_cancel_count, 0))  as later_cancel_count,
                            sum(IfNull(later_sales_charge, 0))  as later_sales_charge,
                            sum(IfNull(later_cancel_charge, 0)) as later_cancel_charge,
                            sum(IfNull(sales_fee, 0))           as sales_fee,
                            sum(IfNull(sales_fee_tax, 0))       as sales_fee_tax,
                            sum(IfNull(stock_fee, 0))           as stock_fee,
                            sum(IfNull(stock_fee_tax, 0))       as stock_fee_tax,
                            sum(IfNull(use_grants_total, 0))    as use_grants_total
                    from	(
                                select	billpay_ym,
                                        customer_id,
                                        site_cd,
                                        site_nm,
                                        partner_cd,
                                        affiliate_cd,
                                        rate                     as rate,
                                        sum(bill_sales_count)    as bill_sales_count,
                                        sum(bill_cancel_count)   as bill_cancel_count,
                                        sum(bill_charge)         as bill_charge,
                                        sum(bill_charge_tax)     as bill_charge_tax,
                                        sum(later_sales_count)   as later_sales_count,
                                        sum(later_cancel_count)  as later_cancel_count,
                                        sum(later_sales_charge)  as later_sales_charge,
                                        sum(later_cancel_charge) as later_cancel_charge,
                                        sum(sales_fee)           as sales_fee,
                                        sum(sales_fee_tax)       as sales_fee_tax,
                                        sum(stock_fee)           as stock_fee,
                                        sum(stock_fee_tax)       as stock_fee_tax,
                                        sum(use_grants_total)    as use_grants_total
                                from
                                    (
                                        select	q01.billpay_ym,
                                                q03.customer_id,
                                                q01.site_cd,
                                                q01.site_nm,
                                                q01.partner_cd,
                                                q01.affiliate_cd,
                                                q02.bill_sales_count,
                                                q02.bill_cancel_count,
                                                q02.bill_charge,
                                                q02.bill_charge_tax,
                                                q02.sales_rate as rate,
                                                q02.later_sales_count,
                                                q02.later_cancel_count,
                                                q02.later_sales_charge,
                                                q02.later_cancel_charge,
                                                q02.sales_fee,
                                                q02.sales_fee_tax,
                                                0 as stock_fee,
                                                0 as stock_fee_tax,
                                                0 as use_grants_total
                                        from	billpay_ptn q01,
                                                billpay_ptn_sales q02,
                                                billpay_ptn_cstmrsite q03
                                        where	null is null
                                            {$s_billpay_ptn_cd}
                                            {$s_customer_id}
                                            {$s_site_cd}
                                            {$s_billpay_ym}
                                            and	q01.fee_type        = 1
                                            and	q01.billpay_ym = q02.billpay_ym
                                            and	q01.site_cd         = q02.site_cd
                                            and	q01.billpay_ym = q03.billpay_ym
                                            and	q01.site_cd         = q03.site_cd
                                            and	q01.fee_type        = q03.fee_type
                                        union all
                                        select	q01.billpay_ym,
                                                q03.customer_id,
                                                q01.site_cd,
                                                q01.site_nm,
                                                q01.partner_cd,
                                                q01.affiliate_cd,
                                                q02.bill_sales_count,
                                                q02.bill_cancel_count,
                                                q02.bill_charge,
                                                q02.bill_charge_tax,
                                                q02.stock_rate as rate,
                                                0 as later_sales_count,
                                                0 as later_cancel_count,
                                                0 as later_sales_charge,
                                                0 as later_cancel_charge,
                                                0 as sales_fee,
                                                0 as sales_fee_tax,
                                                q02.stock_fee,
                                                q02.stock_fee_tax,
                                                0 as use_grants_total
                                        from	billpay_ptn q01,
                                                billpay_ptn_stock q02,
                                                billpay_ptn_cstmrsite q03
                                        where	null is null
                                            {$s_billpay_ptn_cd2}
                                            {$s_customer_id2}
                                            {$s_site_cd2}
                                            {$s_billpay_ym2}
                                            and	q01.fee_type        = 2
                                            and	q01.billpay_ym      = q02.billpay_ym
                                            and	q01.site_cd         = q02.site_cd
                                            and	q01.billpay_ym      = q03.billpay_ym
                                            and	q01.site_cd         = q03.site_cd
                                            and	q01.fee_type        = q03.fee_type
                                        union all
                                        select	q01.billpay_ym,
                                                q03.customer_id,
                                                q01.site_cd,
                                                q01.site_nm,
                                                q01.partner_cd,
                                                q01.affiliate_cd,
                                                q02.bill_sales_count,
                                                q02.bill_cancel_count,
                                                q02.bill_charge,
                                                q02.bill_charge_tax,
                                                0 as rate,
                                                0 as later_sales_count,
                                                0 as later_cancel_count,
                                                0 as later_sales_charge,
                                                0 as later_cancel_charge,
                                                0 as sales_fee,
                                                0 as sales_fee_tax,
                                                0 as stock_fee,
                                                0 as stock_fee_tax,
                                                q02.use_grants_total as use_grants_total
                                        from	billpay_ptn q01,
                                                billpay_ptn_grants q02,
                                                billpay_ptn_cstmrsite q03
                                        where	null is null
                                            {$s_billpay_ptn_cd3}
                                            {$s_customer_id3}
                                            {$s_site_cd3}
                                            {$s_billpay_ym3}
                                            and	q01.fee_type        = 3
                                            and	q01.billpay_ym      = q02.billpay_ym
                                            and	q01.site_cd         = q02.site_cd
                                            and	q01.billpay_ym      = q03.billpay_ym
                                            and	q01.site_cd         = q03.site_cd
                                            and	q01.fee_type        = q03.fee_type
                                    ) as from_small1
                                group by	billpay_ym,
                                            customer_id,
                                            site_cd,
                                            site_nm,
                                            partner_cd,
                                            affiliate_cd,
                                            rate
                            union all
                                select	null as billpay_ym,
                                        customer_id,
                                        site_cd,
                                        site_nm,
                                        partner_cd,
                                        affiliate_cd,
                                        rate,
                                        sum(bill_sales_count)    as bill_sales_count,
                                        sum(bill_cancel_count)   as bill_cancel_count,
                                        sum(bill_charge)         as bill_charge,
                                        sum(bill_charge_tax)     as bill_charge_tax,
                                        sum(later_sales_count)   as later_sales_count,
                                        sum(later_cancel_count)  as later_cancel_count,
                                        sum(later_sales_charge)  as later_sales_charge,
                                        sum(later_cancel_charge) as later_cancel_charge,
                                        sum(sales_fee)           as sales_fee,
                                        sum(sales_fee_tax)       as sales_fee_tax,
                                        sum(stock_fee)           as stock_fee,
                                        sum(stock_fee_tax)       as stock_fee_tax,
                                        sum(use_grants_total)    as use_grants_total
                                from
                                    (
                                        select	q01.billpay_ym,
                                                q03.customer_id,
                                                q01.site_cd,
                                                q01.site_nm,
                                                q01.partner_cd,
                                                q01.affiliate_cd,
                                                q02.bill_sales_count         as bill_sales_count,
                                                q02.bill_cancel_count        as bill_cancel_count,
                                                q02.bill_charge_diff         as bill_charge,
                                                q02.bill_charge_tax_diff     as bill_charge_tax,
                                                q02.sales_rate               as rate,
                                                q02.later_sales_count        as later_sales_count,
                                                q02.later_cancel_count       as later_cancel_count,
                                                q02.later_sales_charge_diff  as later_sales_charge,
                                                q02.later_cancel_charge_diff as later_cancel_charge,
                                                q02.sales_fee_diff           as sales_fee,
                                                q02.sales_fee_tax_diff       as sales_fee_tax,
                                                0 as stock_fee,
                                                0 as stock_fee_tax,
                                                0 as use_grants_total
                                        from	billpayed_ptn q01,
                                                billpayed_ptn_sales q02,
                                                billpay_ptn_cstmrsite q03
                                        where	null is null
                                            {$s_billpay_ptn_cd4}
                                            {$s_customer_id4}
                                            {$s_site_cd4}
                                            {$s_billpay_ym4}
                                            and	q01.fee_type        = 1
                                            and	q01.billpay_ym      = q02.billpay_ym
                                            and	q01.site_cd         = q02.site_cd
                                            and	q01.billpay_ym      = q03.billpay_ym
                                            and	q01.site_cd         = q03.site_cd
                                            and	q01.fee_type        = q03.fee_type
                                        union all
                                        select	q01.billpay_ym,
                                                q03.customer_id,
                                                q01.site_cd,
                                                q01.site_nm,
                                                q01.partner_cd,
                                                q01.affiliate_cd,
                                                q02.bill_sales_count     as bill_sales_count,
                                                q02.bill_cancel_count    as bill_cancel_count,
                                                q02.bill_charge_diff     as bill_charge,
                                                q02.bill_charge_tax_diff as bill_charge_tax,
                                                q02.stock_rate           as rate,
                                                0 as later_sales_count,
                                                0 as later_cancel_count,
                                                0 as later_sales_charge,
                                                0 as later_cancel_charge,
                                                0 as sales_fee,
                                                0 as sales_fee_tax,
                                                q02.stock_fee_diff as stock_fee,
                                                q02.stock_fee_tax_diff as stock_fee_tax,
                                                0 as use_grants_total
                                        from	billpayed_ptn q01,
                                                billpayed_ptn_stock q02,
                                                billpay_ptn_cstmrsite q03
                                        where	null is null
                                            {$s_billpay_ptn_cd5}
                                            {$s_customer_id5}
                                            {$s_site_cd5}
                                            {$s_billpay_ym5}
                                            and	q01.fee_type        = 2
                                            and	q01.billpay_ym      = q02.billpay_ym
                                            and	q01.site_cd         = q02.site_cd
                                            and	q01.billpay_ym      = q03.billpay_ym
                                            and	q01.site_cd         = q03.site_cd
                                            and	q01.fee_type        = q03.fee_type
                                        union all
                                        select	q01.billpay_ym,
                                                q03.customer_id,
                                                q01.site_cd,
                                                q01.site_nm,
                                                q01.partner_cd,
                                                q01.affiliate_cd,
                                                q02.bill_sales_count     as bill_sales_count,
                                                q02.bill_cancel_count    as bill_cancel_count,
                                                q02.bill_charge_diff     as bill_charge,
                                                q02.bill_charge_tax_diff as bill_charge_tax,
                                                0 as  rate,
                                                0 as later_sales_count,
                                                0 as later_cancel_count,
                                                0 as later_sales_charge,
                                                0 as later_cancel_charge,
                                                0 as sales_fee,
                                                0 as sales_fee_tax,
                                                0 as  stock_fee,
                                                0 as  stock_fee_tax,
                                                q02.use_grants_total_diff as use_grants_total
                                        from	billpayed_ptn q01,
                                                billpayed_ptn_grants q02,
                                                billpay_ptn_cstmrsite q03
                                        where	null is null
                                            {$s_billpay_ptn_cd6}
                                            {$s_customer_id6}
                                            {$s_site_cd6}
                                            {$s_billpay_ym6}
                                            and	q01.fee_type        = 3
                                            and	q01.billpay_ym      = q02.billpay_ym
                                            and	q01.site_cd         = q02.site_cd
                                            and	q01.billpay_ym      = q03.billpay_ym
                                            and	q01.site_cd         = q03.site_cd
                                            and	q01.fee_type        = q03.fee_type
                                    ) as from_small2
                                group by	customer_id,
                                            site_cd,
                                            site_nm,
                                            partner_cd,
                                            affiliate_cd,
                                            billpay_ym,
                                            rate
                            ) as from_big
                    group by	billpay_ym,
                                customer_id,
                                site_cd,
                                site_nm,
                                partner_cd,
                                affiliate_cd,
                                rate
                    order by	customer_id,
                                site_cd,
                                site_nm,
                                billpay_ym,
                                partner_cd,
                                affiliate_cd,
                                rate
SQL;

                $a_site = DB::select($s_sql, $a_condition);
                $a_site = json_decode(json_encode($a_site), true); //json~追記しないとviewでエラー

                return $a_site;

        // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 支払・請求に対応する予約情報を取得します。
    //
    //   aa_conditions
    //     billpay_ptn_cd  パートナー精算コード
    //     customer_id     パートナー精算先コード
    //     site_cd        精算サイトコード
    //     billpay_ym     精算月（YYYY-MM)
    //     stock_type     在庫タイプ（1:一般ネット在庫、 2:連動在庫 3:東横イン在庫）
    //     rate           料率
    //   aa_options
    //     billpay        通常（1:抽出する 0: 抽出しない）
    //     billpayed      赤伝（1:抽出する 0: 抽出しない）
    //   aa_offsets
    //     page           ページ
    //     size           レコード数(1から) ページ数を指定した場合必須
    //======================================================================
    public function getDetailStatement($aa_conditions, $aa_options, $aa_offsets = null)
    {
        try { //パラメータ？（:~）がSQL文内にいくつかあるため、すべて定義（かつ下の方で分岐用にも再定義）
            $a_condition = [];//追記
            $s_site_cd = ''; //追記
            $s_site_cd2 = '';
            $s_site_cd3 = '';
            $s_site_cd4 = '';
            $s_site_cd5 = '';
            $s_site_cd6 = '';
            $s_stock_type = '';
            $s_stock_type2 = '';
            $s_stock_type3 = '';
            $s_stock_type4 = '';
            $s_billpay_ym = '';
            $s_billpay_ym2 = '';
            $s_billpay_ym3 = '';
            $s_billpay_ym4 = '';
            $s_billpay_ym5 = '';
            $s_billpay_ym6 = '';
            $s_customer_id = '';
            $s_customer_id2 = '';
            $s_customer_id3 = '';
            $s_customer_id4 = '';
            $s_customer_id5 = '';
            $s_customer_id6 = '';
            $s_stock_rate = '';
            $s_stock_rate2 = '';
            $s_sales_rate = '';
            $s_sales_rate2 = '';

            if ($this->is_empty($aa_conditions['billpay_ptn_cd'] ?? null)) { //null追記、下記も同様
                $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  is null';
                $s_billpay_ptn_cd2 = 'and q01.billpay_ptn_cd  is null';
                $s_billpay_ptn_cd3 = 'and q01.billpay_ptn_cd  is null';
                $s_billpay_ptn_cd4 = 'and q01.billpay_ptn_cd  is null';
                $s_billpay_ptn_cd5 = 'and q01.billpay_ptn_cd  is null';
                $s_billpay_ptn_cd6 = 'and q01.billpay_ptn_cd  is null';
            } else {
                $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd';
                $s_billpay_ptn_cd2 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd2';
                $s_billpay_ptn_cd3 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd3';
                $s_billpay_ptn_cd4 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd4';
                $s_billpay_ptn_cd5 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd5';
                $s_billpay_ptn_cd6 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd6';
            }
            if (!$this->is_empty($aa_conditions['site_cd'] ?? null)) {
                $s_site_cd = 'and q01.site_cd  = :site_cd';
                $s_site_cd2 = 'and q01.site_cd  = :site_cd2';
                $s_site_cd3 = 'and q01.site_cd  = :site_cd3';
                $s_site_cd4 = 'and q01.site_cd  = :site_cd4';
                $s_site_cd5 = 'and q01.site_cd  = :site_cd5';
                $s_site_cd6 = 'and q01.site_cd  = :site_cd6';
            }
            if (!$this->is_empty($aa_conditions['stock_type'] ?? null)) {
                $s_stock_type = 'and q02.stock_type  = :stock_type';
                $s_stock_type2 = 'and q02.stock_type  = :stock_type2';
                $s_stock_type3 = 'and q02.stock_type  = :stock_type3';
                $s_stock_type4 = 'and q02.stock_type  = :stock_type4';
            }
            if (!$this->is_empty($aa_conditions['billpay_ym'] ?? null)) {
                // $s_billpay_ym = 'and q01.billpay_ym = to_date(:billpay_ym, \'yyyy-mm\')';
                $s_billpay_ym = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym'; //書き換えあっている？
                $s_billpay_ym2 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym2';
                $s_billpay_ym3 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym3';
                $s_billpay_ym4 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym4';
                $s_billpay_ym5 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym5';
                $s_billpay_ym6 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym6';
            }
            if (!$this->is_empty($aa_conditions['customer_id'])) {
                $s_customer_id = 'and q03.customer_id  = :customer_id';
                $s_customer_id2 = 'and q03.customer_id  = :customer_id2';
                $s_customer_id3 = 'and q03.customer_id  = :customer_id3';
                $s_customer_id4 = 'and q03.customer_id  = :customer_id4';
                $s_customer_id5 = 'and q03.customer_id  = :customer_id5';
                $s_customer_id6 = 'and q03.customer_id  = :customer_id6';
            }
            if (!$this->is_empty($aa_conditions['rate'] ?? null)) {
                $s_stock_rate = 'and q02.stock_rate  = :st_rate';
                $s_sales_rate = 'and q02.sales_rate  = :sa_rate';
                $s_stock_rate2 = 'and q02.stock_rate  = :st_rate2';
                $s_sales_rate2 = 'and q02.sales_rate  = :sa_rate2';
            }
            if (!$this->is_empty($aa_conditions['msd_rate'] ?? null)) {
                $s_msd_rate = ':msd_rate'; //to_number削除して問題ないか（以下同様）
                $s_msd_rate2 = ':msd_rate2';
                $s_msd_rate3 = ':msd_rate3';
                $s_msd_rate4 = ':msd_rate4';
            } else {
                $s_msd_rate = '0';
                $s_msd_rate2 = '0';
                $s_msd_rate3 = '0';
                $s_msd_rate4 = '0';
            }

            // 通常精算分
            $s_billpay_sql =
            <<< SQL
                                    select	q02.reserve_cd,
                                            q02.date_ymd,
                                            q03.customer_id,
                                            q02.site_cd,
                                            q04.site_nm,
                                            q02.billpay_ym,
                                            q01.partner_cd,
                                            q01.affiliate_cd,
                                            case when q03.customer_id = 1 and q01.affiliate_cd is not null then q02.affiliate_cd_sub else null end as affiliate_cd_sub,
                                            q02.hotel_cd,
                                            q02.later_payment,
                                            q02.stock_type,
                                            q02.bill_type,
                                            q02.bill_charge,
                                            q02.bill_charge_tax,
                                            q02.sales_rate as rate,
                                            {$s_msd_rate} as msd_rate,
                                            q02.sales_fee as fee,
                                            case when q02.later_payment = 1 and bill_type = 0 then q02.bill_charge else 0 end later_sales_charge,
                                            case when q02.later_payment = 1 and bill_type = 1 then q02.bill_charge else 0 end later_cancel_charge,
                                            null as operation_ymd,
                                            0 as use_grants
                                    from	billpay_ptn q01,
                                            billpay_sales q02,
                                            billpay_ptn_cstmrsite q03,
                                            billpay_ptn_site q04
                                    where	null is null
                                        {$s_billpay_ptn_cd}
                                        {$s_customer_id}
                                        {$s_site_cd}
                                        {$s_billpay_ym}
                                        {$s_sales_rate}
                                        {$s_stock_type}
                                        and	q01.fee_type        = 1
                                        and	q01.billpay_ym      = q02.billpay_ym
                                        and	q01.site_cd         = q02.site_cd
                                        and	q01.billpay_ym      = q03.billpay_ym
                                        and	q01.site_cd         = q03.site_cd
                                        and	q01.fee_type        = q03.fee_type
                                        and	q01.billpay_ym      = q04.billpay_ym
                                        and	q01.site_cd         = q04.site_cd
                                    union
                                    select	q02.reserve_cd,
                                            q02.date_ymd,
                                            q03.customer_id,
                                            q02.site_cd,
                                            q04.site_nm,
                                            q02.billpay_ym,
                                            q01.partner_cd,
                                            q01.affiliate_cd,
                                            case when q03.customer_id = 1 and q01.affiliate_cd is not null then q02.affiliate_cd_sub else null end as affiliate_cd_sub,
                                            q02.hotel_cd,
                                            q02.later_payment,
                                            q02.stock_type,
                                            q02.bill_type,
                                            q02.bill_charge,
                                            q02.bill_charge_tax,
                                            q02.stock_rate as rate,
                                            {$s_msd_rate2} as msd_rate,
                                            q02.stock_fee as fee,
                                            0 as later_sales_charge,
                                            0 as later_cancel_charge,
                                            null as operation_ymd,
                                            0 as use_grants
                                    from	billpay_ptn q01,
                                            billpay_stock q02,
                                            billpay_ptn_cstmrsite q03,
                                            billpay_ptn_site q04
                                    where	null is null
                                        {$s_billpay_ptn_cd2}
                                        {$s_customer_id2}
                                        {$s_site_cd2}
                                        {$s_billpay_ym2}
                                        {$s_stock_rate}
                                        {$s_stock_type2}
                                        and	q01.fee_type        = 2
                                        and	q01.billpay_ym      = q02.billpay_ym
                                        and	q01.site_cd         = q02.site_cd
                                        and	q01.billpay_ym      = q03.billpay_ym
                                        and	q01.site_cd         = q03.site_cd
                                        and	q01.fee_type        = q03.fee_type
                                        and	q01.billpay_ym      = q04.billpay_ym
                                        and	q01.site_cd         = q04.site_cd
                                    union
                                    select	q02.reserve_cd,
                                            q02.date_ymd,
                                            q03.customer_id,
                                            q02.site_cd,
                                            q04.site_nm,
                                            q02.billpay_ym,
                                            q01.partner_cd,
                                            q01.affiliate_cd,
                                            case when q03.customer_id = 1 and q01.affiliate_cd is not null then q02.affiliate_cd_sub else null end as affiliate_cd_sub,
                                            q02.hotel_cd,
                                            0 as later_payment,
                                            0 as stock_type,
                                            q02.bill_type,
                                            0 as bill_charge,
                                            0 as bill_charge_tax,
                                            0 as rate,
                                            0 as msd_rate,
                                            0 as fee,
                                            0 as later_sales_charge,
                                            0 as later_cancel_charge,
                                            null as operation_ymd,
                                            q02.use_grants
                                    from	billpay_ptn q01,
                                            billpay_pr_grants q02,
                                            billpay_ptn_cstmrsite q03,
                                            billpay_ptn_site q04
                                    where	null is null
                                        {$s_billpay_ptn_cd3}
                                        {$s_customer_id3}
                                        {$s_site_cd3}
                                        {$s_billpay_ym3}
                                        and	q01.fee_type        = 3
                                        and	q01.billpay_ym      = q02.billpay_ym
                                        and	q01.site_cd         = q02.site_cd
                                        and	q01.billpay_ym      = q03.billpay_ym
                                        and	q01.site_cd         = q03.site_cd
                                        and	q01.fee_type        = q03.fee_type
                                        and	q01.billpay_ym      = q04.billpay_ym
                                        and	q01.site_cd         = q04.site_cd
SQL;

            // 赤伝算分
            $s_billpayed_sql =
            <<< SQL
                                    select	q02.reserve_cd,
                                            q02.date_ymd,
                                            q03.customer_id,
                                            q02.site_cd,
                                            q04.site_nm,
                                            q02.billpay_ym,
                                            q01.partner_cd,
                                            q01.affiliate_cd,
                                            case when q03.customer_id = 1 and q01.affiliate_cd is not null then q02.affiliate_cd_sub else null end as affiliate_cd_sub,
                                            q02.hotel_cd,
                                            q02.later_payment,
                                            q02.stock_type,
                                            q02.bill_type,
                                            q02.bill_charge,
                                            q02.bill_charge_tax,
                                            q02.sales_rate as rate,
                                            {$s_msd_rate3} as msd_rate,
                                            q02.sales_fee_diff as fee,
                                            case
                                                    when q02.later_payment = 1 and q02.bill_type = 0 and q02.bill_type_diff = 0  then q02.bill_charge_diff                       -- 変更なし
                                                    when q02.later_payment = 1 and q02.bill_type = 1 and q02.bill_type_diff = 1  then (q02.bill_charge - q02.bill_charge_diff) * -1  -- キャンセルになった
                                                    when q02.later_payment = 1 and q02.bill_type = 0 and q02.bill_type_diff = -1 then q02.bill_charge                           -- 宿泊に復活
                                                    else 0
                                                end
                                            as later_sales_charge,
                                            case
                                                    when q02.later_payment = 1 and q02.bill_type = 1 and q02.bill_type_diff = 0  then q02.bill_charge_diff                       -- 変更なし
                                                    when q02.later_payment = 1 and q02.bill_type = 1 and q02.bill_type_diff = 1  then q02.bill_charge                            -- キャンセルになった
                                                    when q02.later_payment = 1 and q02.bill_type = 0 and q02.bill_type_diff = -1 then (q02.bill_charge - q02.bill_charge_diff) * -1  -- 宿泊に復活
                                                    else 0
                                                end
                                            as later_cancel_charge,
                                            q02.operation_ymd,
                                            0 as use_grants
                                    from	billpayed_ptn q01,
                                            billpayed_sales q02,
                                            billpay_ptn_cstmrsite q03,
                                            billpay_ptn_site q04
                                    where	null is null
                                        {$s_billpay_ptn_cd4}
                                        {$s_customer_id4}
                                        {$s_site_cd4}
                                        {$s_billpay_ym4}
                                        {$s_sales_rate2}
                                        {$s_stock_type3}
                                        and	q01.fee_type        = 1
                                        and	q01.billpay_ym      = q02.billpay_ym
                                        and	q01.site_cd         = q02.site_cd
                                        and	q01.billpay_ym      = q03.billpay_ym
                                        and	q01.site_cd         = q03.site_cd
                                        and	q01.fee_type        = q03.fee_type
                                        and	q01.billpay_ym      = q04.billpay_ym
                                        and	q01.site_cd         = q04.site_cd
                                    union all
                                    select	q02.reserve_cd,
                                            q02.date_ymd,
                                            q03.customer_id,
                                            q02.site_cd,
                                            q04.site_nm,
                                            q02.billpay_ym,
                                            q01.partner_cd,
                                            q01.affiliate_cd,
                                            case when q03.customer_id = 1 and q01.affiliate_cd is not null then q02.affiliate_cd_sub else null end as affiliate_cd_sub,
                                            q02.hotel_cd,
                                            q02.later_payment,
                                            q02.stock_type,
                                            q02.bill_type,
                                            q02.bill_charge,
                                            q02.bill_charge_tax,
                                            q02.stock_rate as rate,
                                            {$s_msd_rate4} as msd_rate,
                                            q02.stock_fee_diff as fee,
                                            0 as later_sales_charge,
                                            0 as later_cancel_charge,
                                            q02.operation_ymd,
                                            0 as use_grants
                                    from	billpayed_ptn q01,
                                            billpayed_stock q02,
                                            billpay_ptn_cstmrsite q03,
                                            billpay_ptn_site q04
                                    where	null is null
                                        {$s_billpay_ptn_cd5}
                                        {$s_customer_id5}
                                        {$s_site_cd5}
                                        {$s_billpay_ym5}
                                        {$s_stock_rate2}
                                        {$s_stock_type4}
                                        and	q01.fee_type        = 2
                                        and	q01.billpay_ym      = q02.billpay_ym
                                        and	q01.site_cd         = q02.site_cd
                                        and	q01.billpay_ym      = q03.billpay_ym
                                        and	q01.site_cd         = q03.site_cd
                                        and	q01.fee_type        = q03.fee_type
                                        and	q01.billpay_ym      = q04.billpay_ym
                                        and	q01.site_cd         = q04.site_cd
                                    union all
                                    select	q02.reserve_cd,
                                            q02.date_ymd,
                                            q03.customer_id,
                                            q02.site_cd,
                                            q04.site_nm,
                                            q02.billpay_ym,
                                            q01.partner_cd,
                                            q01.affiliate_cd,
                                            case when q03.customer_id = 1 and q01.affiliate_cd is not null then q02.affiliate_cd_sub else null end as affiliate_cd_sub,
                                            q02.hotel_cd,
                                            0 as later_payment,
                                            0 as stock_type,
                                            q02.bill_type,
                                            0 as bill_charge,
                                            0 as bill_charge_tax,
                                            0 as rate,
                                            0 as msd_rate,
                                            0 as fee,
                                            0 as later_sales_charge,
                                            0 as later_cancel_charge,
                                            q02.operation_ymd,
                                            q02.use_grants_diff as use_grants
                                    from	billpayed_ptn q01,
                                            billpayed_pr_grants q02,
                                            billpay_ptn_cstmrsite q03,
                                            billpay_ptn_site q04
                                    where	null is null
                                        {$s_billpay_ptn_cd6}
                                        {$s_customer_id6}
                                        {$s_site_cd6}
                                        {$s_billpay_ym6}
                                        and	q01.fee_type        = 2
                                        and	q01.billpay_ym      = q02.billpay_ym
                                        and	q01.site_cd         = q02.site_cd
                                        and	q01.billpay_ym      = q03.billpay_ym
                                        and	q01.site_cd         = q03.site_cd
                                        and	q01.fee_type        = q03.fee_type
                                        and	q01.billpay_ym      = q04.billpay_ym
                                        and	q01.site_cd         = q04.site_cd
SQL;

            // 抽出範囲調整(値も追加で変更済,もう少し綺麗にかけないものか…)
            // 以下非表示元ソース
            // if ($aa_options['billpay'] == 0) {
            //     $s_billpay_sql = null;
            // }
            // if ($aa_options['billpayed'] == 0) {
            //     $s_billpayed_sql = null;
            // }
            // if ($aa_options['billpay'] == 1 and $aa_options['billpayed'] == 1) {
            //     $s_union = 'union';
            // }

            if ($aa_options['billpay'] == 0) {
                $s_union = 'from(' . $s_billpayed_sql . ') as union_table';
                $s_billpay_sql = null;
                $s_site_cd4 = '';
                $s_site_cd5 = '';
                $s_site_cd6 = '';
                $s_stock_type3 = '';
                $s_stock_type4 = '';
                $s_billpay_ym4 = '';
                $s_billpay_ym5 = '';
                $s_billpay_ym6 = '';
                $s_customer_id4 = '';
                $s_customer_id5 = '';
                $s_customer_id6 = '';
                $s_stock_rate2 = '';
                $s_sales_rate2 = '';

                if ($this->is_empty($aa_conditions['billpay_ptn_cd'])) {
                    $s_billpay_ptn_cd4 = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd5 = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd6 = 'and q01.billpay_ptn_cd  is null';
                } else {
                    $s_billpay_ptn_cd4 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd4';
                    $a_condition['billpay_ptn_cd4'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd5 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd5';
                    $a_condition['billpay_ptn_cd5'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd6 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd6';
                    $a_condition['billpay_ptn_cd6'] = $aa_conditions['billpay_ptn_cd'];
                }
                if (!$this->is_empty($aa_conditions['site_cd'])) {
                    $s_site_cd4 = 'and q01.site_cd  = :site_cd4';
                    $a_condition['site_cd4'] = $aa_conditions['site_cd'];
                    $s_site_cd5 = 'and q01.site_cd  = :site_cd5';
                    $a_condition['site_cd5'] = $aa_conditions['site_cd'];
                    $s_site_cd6 = 'and q01.site_cd  = :site_cd6';
                    $a_condition['site_cd6'] = $aa_conditions['site_cd'];
                }
                if (!$this->is_empty($aa_conditions['stock_type'])) {
                    $s_stock_type3 = 'and q02.stock_type  = :stock_type3';
                    $a_condition['stock_type3'] = $aa_conditions['stock_type'];
                    $s_stock_type4 = 'and q02.stock_type  = :stock_type4';
                    $a_condition['stock_type4'] = $aa_conditions['stock_type'];
                }
                if (!$this->is_empty($aa_conditions['billpay_ym'])) {
                    // $s_billpay_ym = 'and q01.billpay_ym = to_date(:billpay_ym, \'yyyy-mm\')';
                    $s_billpay_ym4 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym4';
                    $a_condition['billpay_ym4'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym5 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym5';
                    $a_condition['billpay_ym5'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym6 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym6';
                    $a_condition['billpay_ym6'] = $aa_conditions['billpay_ym'];
                }
                if (!$this->is_empty($aa_conditions['customer_id'])) {
                    $s_customer_id4 = 'and q03.customer_id  = :customer_id4';
                    $a_condition['customer_id4'] = $aa_conditions['customer_id'];
                    $s_customer_id5 = 'and q03.customer_id  = :customer_id5';
                    $a_condition['customer_id5'] = $aa_conditions['customer_id'];
                    $s_customer_id6 = 'and q03.customer_id  = :customer_id6';
                    $a_condition['customer_id6'] = $aa_conditions['customer_id'];
                }
                if (!$this->is_empty($aa_conditions['rate'])) {
                    $s_stock_rate2 = 'and q02.stock_rate  = :st_rate2';
                    $s_sales_rate2 = 'and q02.sales_rate  = :sa_rate2';
                    $a_condition['st_rate2'] = $aa_conditions['rate'];
                    $a_condition['sa_rate2'] = $aa_conditions['rate'];
                }
                if (!$this->is_empty($aa_conditions['msd_rate'])) {
                    $a_condition['msd_rate3'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate3 = ':msd_rate3';
                    $a_condition['msd_rate4'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate4 = ':msd_rate4';
                } else {
                    $s_msd_rate3 = '0';
                    $s_msd_rate4 = '0';
                }
            }
            if ($aa_options['billpayed'] == 0) {
                $s_union = 'from(' . $s_billpay_sql . ') as union_table';
                $s_billpayed_sql = null;
                $s_site_cd = ''; //追記
                $s_site_cd2 = '';
                $s_site_cd3 = '';
                $s_stock_type = ''; //追記
                $s_stock_type2 = '';
                $s_billpay_ym = ''; //追記
                $s_billpay_ym2 = '';
                $s_billpay_ym3 = '';
                $s_customer_id = ''; //追記
                $s_customer_id2 = '';
                $s_customer_id3 = '';
                $s_stock_rate = ''; //追記
                $s_sales_rate = ''; //追記

                if ($this->is_empty($aa_conditions['billpay_ptn_cd'])) {
                    $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd2 = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd3 = 'and q01.billpay_ptn_cd  is null';
                } else {
                    $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd';
                    $a_condition['billpay_ptn_cd'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd2 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd2';
                    $a_condition['billpay_ptn_cd2'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd3 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd3';
                    $a_condition['billpay_ptn_cd3'] = $aa_conditions['billpay_ptn_cd'];
                }
                if (!$this->is_empty($aa_conditions['site_cd'])) {
                    $s_site_cd = 'and q01.site_cd  = :site_cd';
                    $a_condition['site_cd'] = $aa_conditions['site_cd'];
                    $s_site_cd2 = 'and q01.site_cd  = :site_cd2';
                    $a_condition['site_cd2'] = $aa_conditions['site_cd'];
                    $s_site_cd3 = 'and q01.site_cd  = :site_cd3';
                    $a_condition['site_cd3'] = $aa_conditions['site_cd'];
                }
                if (!$this->is_empty($aa_conditions['stock_type'])) {
                    $s_stock_type = 'and q02.stock_type  = :stock_type';
                    $a_condition['stock_type'] = $aa_conditions['stock_type'];
                    $s_stock_type2 = 'and q02.stock_type  = :stock_type2';
                    $a_condition['stock_type2'] = $aa_conditions['stock_type'];
                }
                if (!$this->is_empty($aa_conditions['billpay_ym'])) {
                    // $s_billpay_ym = 'and q01.billpay_ym = to_date(:billpay_ym, \'yyyy-mm\')';
                    $s_billpay_ym = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym'; //dateは不要？
                    $a_condition['billpay_ym'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym2 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym2';
                    $a_condition['billpay_ym2'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym3 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym3';
                    $a_condition['billpay_ym3'] = $aa_conditions['billpay_ym'];
                }
                if (!$this->is_empty($aa_conditions['customer_id'])) {
                    $s_customer_id = 'and q03.customer_id  = :customer_id';
                    $a_condition['customer_id'] = $aa_conditions['customer_id'];
                    $s_customer_id2 = 'and q03.customer_id  = :customer_id2';
                    $a_condition['customer_id2'] = $aa_conditions['customer_id'];
                    $s_customer_id3 = 'and q03.customer_id  = :customer_id3';
                    $a_condition['customer_id3'] = $aa_conditions['customer_id'];
                }
                if (!$this->is_empty($aa_conditions['rate'])) {
                    $s_stock_rate = 'and q02.stock_rate  = :st_rate';
                    $s_sales_rate = 'and q02.sales_rate  = :sa_rate';
                    $a_condition['st_rate'] = $aa_conditions['rate'];
                    $a_condition['sa_rate'] = $aa_conditions['rate'];
                }
                if (!$this->is_empty($aa_conditions['msd_rate'])) {
                    $a_condition['msd_rate'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate = ':msd_rate'; //to_number削除（以下同様）
                    $a_condition['msd_rate2'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate2 = ':msd_rate2';
                } else {
                    $s_msd_rate = '0';
                    $s_msd_rate2 = '0';
                }
            }

            if ($aa_options['billpay'] == 1 && $aa_options['billpayed'] == 1) {
                $s_union = 'from(' . $s_billpay_sql . ' union ' . $s_billpayed_sql . ') as union_table';
                $s_site_cd = ''; //追記
                $s_site_cd2 = '';
                $s_site_cd3 = '';
                $s_site_cd4 = '';
                $s_site_cd5 = '';
                $s_site_cd6 = '';
                $s_stock_type = ''; //追記
                $s_stock_type2 = '';
                $s_stock_type3 = '';
                $s_stock_type4 = '';
                $s_billpay_ym = ''; //追記
                $s_billpay_ym2 = '';
                $s_billpay_ym3 = '';
                $s_billpay_ym4 = '';
                $s_billpay_ym5 = '';
                $s_billpay_ym6 = '';
                $s_customer_id = ''; //追記
                $s_customer_id2 = '';
                $s_customer_id3 = '';
                $s_customer_id4 = '';
                $s_customer_id5 = '';
                $s_customer_id6 = '';
                $s_stock_rate = ''; //追記
                $s_sales_rate = ''; //追記
                $s_stock_rate2 = '';
                $s_sales_rate2 = '';

                if ($this->is_empty($aa_conditions['billpay_ptn_cd'] ?? null)) { //null追記、下記同様
                    $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd2 = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd3 = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd4 = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd5 = 'and q01.billpay_ptn_cd  is null';
                    $s_billpay_ptn_cd6 = 'and q01.billpay_ptn_cd  is null';
                } else {
                    $s_billpay_ptn_cd = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd';
                    $a_condition['billpay_ptn_cd'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd2 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd2';
                    $a_condition['billpay_ptn_cd2'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd3 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd3';
                    $a_condition['billpay_ptn_cd3'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd4 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd4';
                    $a_condition['billpay_ptn_cd4'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd5 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd5';
                    $a_condition['billpay_ptn_cd5'] = $aa_conditions['billpay_ptn_cd'];
                    $s_billpay_ptn_cd6 = 'and q01.billpay_ptn_cd  = :billpay_ptn_cd6';
                    $a_condition['billpay_ptn_cd6'] = $aa_conditions['billpay_ptn_cd'];
                }
                if (!$this->is_empty($aa_conditions['site_cd'] ?? null)) {
                    $s_site_cd = 'and q01.site_cd  = :site_cd';
                    $a_condition['site_cd'] = $aa_conditions['site_cd'];
                    $s_site_cd2 = 'and q01.site_cd  = :site_cd2';
                    $a_condition['site_cd2'] = $aa_conditions['site_cd'];
                    $s_site_cd3 = 'and q01.site_cd  = :site_cd3';
                    $a_condition['site_cd3'] = $aa_conditions['site_cd'];
                    $s_site_cd4 = 'and q01.site_cd  = :site_cd4';
                    $a_condition['site_cd4'] = $aa_conditions['site_cd'];
                    $s_site_cd5 = 'and q01.site_cd  = :site_cd5';
                    $a_condition['site_cd5'] = $aa_conditions['site_cd'];
                    $s_site_cd6 = 'and q01.site_cd  = :site_cd6';
                    $a_condition['site_cd6'] = $aa_conditions['site_cd'];
                }
                if (!$this->is_empty($aa_conditions['stock_type'] ?? null)) {
                    $s_stock_type = 'and q02.stock_type  = :stock_type';
                    $a_condition['stock_type'] = $aa_conditions['stock_type'];
                    $s_stock_type2 = 'and q02.stock_type  = :stock_type2';
                    $a_condition['stock_type2'] = $aa_conditions['stock_type'];
                    $s_stock_type3 = 'and q02.stock_type  = :stock_type3';
                    $a_condition['stock_type3'] = $aa_conditions['stock_type'];
                    $s_stock_type4 = 'and q02.stock_type  = :stock_type4';
                    $a_condition['stock_type4'] = $aa_conditions['stock_type'];
                }
                if (!$this->is_empty($aa_conditions['billpay_ym'] ?? null)) {
                    // $s_billpay_ym = 'and q01.billpay_ym = to_date(:billpay_ym, \'yyyy-mm\')';
                    $s_billpay_ym = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym'; //dateは不要？
                    $a_condition['billpay_ym'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym2 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym2';
                    $a_condition['billpay_ym2'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym3 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym3';
                    $a_condition['billpay_ym3'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym4 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym4';
                    $a_condition['billpay_ym4'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym5 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym5';
                    $a_condition['billpay_ym5'] = $aa_conditions['billpay_ym'];
                    $s_billpay_ym6 = 'and date_format(q01.billpay_ym, "%Y-%m") <= :billpay_ym6';
                    $a_condition['billpay_ym6'] = $aa_conditions['billpay_ym'];
                }
                if (!$this->is_empty($aa_conditions['customer_id'] ?? null)) {
                    $s_customer_id = 'and q03.customer_id  = :customer_id';
                    $a_condition['customer_id'] = $aa_conditions['customer_id'];
                    $s_customer_id2 = 'and q03.customer_id  = :customer_id2';
                    $a_condition['customer_id2'] = $aa_conditions['customer_id'];
                    $s_customer_id3 = 'and q03.customer_id  = :customer_id3';
                    $a_condition['customer_id3'] = $aa_conditions['customer_id'];
                    $s_customer_id4 = 'and q03.customer_id  = :customer_id4';
                    $a_condition['customer_id4'] = $aa_conditions['customer_id'];
                    $s_customer_id5 = 'and q03.customer_id  = :customer_id5';
                    $a_condition['customer_id5'] = $aa_conditions['customer_id'];
                    $s_customer_id6 = 'and q03.customer_id  = :customer_id6';
                    $a_condition['customer_id6'] = $aa_conditions['customer_id'];
                }
                if (!$this->is_empty($aa_conditions['rate'] ?? null)) {
                    $s_stock_rate = 'and q02.stock_rate  = :st_rate';
                    $s_sales_rate = 'and q02.sales_rate  = :sa_rate';
                    $a_condition['st_rate'] = $aa_conditions['rate'];
                    $a_condition['sa_rate'] = $aa_conditions['rate'];
                    $s_stock_rate2 = 'and q02.stock_rate  = :st_rate2';
                    $s_sales_rate2 = 'and q02.sales_rate  = :sa_rate2';
                    $a_condition['st_rate2'] = $aa_conditions['rate'];
                    $a_condition['sa_rate2'] = $aa_conditions['rate'];
                }
                if (!$this->is_empty($aa_conditions['msd_rate'] ?? null)) {
                    $a_condition['msd_rate'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate = ':msd_rate'; //to_number削除（以下同様）
                    $a_condition['msd_rate2'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate2 = ':msd_rate2';
                    $a_condition['msd_rate3'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate3 = ':msd_rate3';
                    $a_condition['msd_rate4'] = number_format($aa_conditions['msd_rate'], 2);
                    $s_msd_rate4 = ':msd_rate4';
                } else {
                    $s_msd_rate = '0';
                    $s_msd_rate2 = '0';
                    $s_msd_rate3 = '0';
                    $s_msd_rate4 = '0';
                }
            }


            //
            $s_sql =

            <<< SQL
                    select	q10.customer_id,
                            q10.site_cd,
                            q10.site_nm,
                            q10.partner_cd,
                            q10.affiliate_cd,
                            q10.affiliate_cd_sub,
                            q10.reserve_cd,
                            q10.bill_type,
                            q10.bill_charge,
                            q10.bill_charge_tax,
                            q10.later_sales_charge,
                            q10.later_cancel_charge,
                            q10.use_grants,
                            case when q10.msd_rate = 0 then q10.rate else q10.msd_rate end as rate,
                            case
                                when q10.msd_rate = '0' or q10.fee = 0 then q10.fee -- MSD でないか 手数料が０の場合は、そのまま
                                when q10.msd_rate = '1'                then floor((q10.bill_charge - q10.bill_charge_tax) * 1 / 100 )           -- MSD手数料１%の場合
                                when q10.msd_rate > '1'                then q10.fee - floor((q10.bill_charge - q10.bill_charge_tax) * 1 / 100 ) -- MSD手数料１%以外の場合
                                else q10.fee
                            end as fee,
                            reserve.order_cd,
                            q10.date_ymd as date_ymd, -- 書き換え問題ないか
                            reserve.partner_ref,
                            reserve.reserve_dtm as reserve_dtm, -- 書き換え問題ないか
                            reserve.cancel_dtm as cancel_dtm, -- 書き換え問題ないか
                            q10.operation_ymd as operation_ymd, -- 書き換え問題ないか
                            reserve.reserve_system,
                            reserve.reserve_status,
                            reserve.member_cd,
                            reserve.auth_type,
                            reserve.guests,
                            reserve_guest.guest_nm,
                            reserve_plan.hotel_cd,
                            reserve_plan.hotel_nm,
                            reserve_plan.room_id,
                            reserve_plan.plan_id,
                            reserve_plan.room_cd,
                            reserve_plan.plan_cd,
                            reserve_plan.room_nl as room_nm,
                            reserve_plan.plan_nm,
                            reserve_extension.extension_value,
                            hikari_account.account_id,
                            hotel.pref_id,
                            (select pref_nm from mast_pref where pref_id = hotel.pref_id) as pref_nm,
                            hotel.address
                    from	reserve,
                            reserve_guest,
                            reserve_plan,
                            reserve_extension,
                            hikari_account,
                            hotel,
                        (
                            select	customer_id,
                                    site_cd,
                                    site_nm,
                                    partner_cd,
                                    affiliate_cd,
                                    affiliate_cd_sub,
                                    reserve_cd,
                                    date_ymd,
                                    operation_ymd,
                                    hotel_cd,
                                    stock_type,
                                    bill_type,
                                    bill_charge,
                                    bill_charge_tax,
                                    rate,
                                    msd_rate,
                                    sum(ifNull(fee, 0)) as fee, -- nvl→ifNullで問題ないか（以下同様）
                                    sum(ifNull(later_sales_charge, 0)) as later_sales_charge,
                                    sum(ifNull(later_cancel_charge, 0)) as later_cancel_charge,
                                    sum(ifNull(use_grants, 0)) as use_grants

                                    {$s_union} -- nullの時エラーになるのでfrom()も変数内に格納

                            group by customer_id,
                                    site_cd,
                                    site_nm,
                                    partner_cd,
                                    affiliate_cd,
                                    affiliate_cd_sub,
                                    reserve_cd,
                                    date_ymd,
                                    operation_ymd,
                                    hotel_cd,
                                    stock_type,
                                    bill_type,
                                    bill_charge,
                                    bill_charge_tax,
                                    rate,
                                    msd_rate
                        ) q10
                    where	q10.reserve_cd  = reserve.reserve_cd
                        and	q10.date_ymd    = reserve.date_ymd
                        and	q10.reserve_cd  = reserve_plan.reserve_cd
                        and	q10.reserve_cd  = reserve_guest.reserve_cd
                        and	q10.reserve_cd  = reserve_extension.reserve_cd 
                        and	reserve.member_cd   = hikari_account.id -- to_char削除してOK？
                        and	reserve_plan.hotel_cd    = hotel.hotel_cd 
                        -- 下記3つの書き換え方不明（上記はとりあえず(+)のみとってデータはとれている）
                        -- and	q10.reserve_cd  = reserve_extension.reserve_cd(+)
                        -- and	reserve.member_cd   = to_char(hikari_account.id(+))
                        -- and	reserve_plan.hotel_cd    = hotel.hotel_cd(+)
                        and	(
                                    q10.fee != 0
                                or q10.later_sales_charge != 0
                                or q10.later_cancel_charge != 0
                                or q10.use_grants != 0
                            )
                    order by	q10.date_ymd,
                                q10.reserve_cd
SQL;

            // return  $_oracle->find_by_sql_statement($s_sql, $a_condition, $aa_offsets);
            $result = DB::select($s_sql, $a_condition, $aa_offsets);
            $result = json_decode(json_encode($result), true); //json~追記しないとviewでエラー
            return $result;

        // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 支払・請求に対応する予約情報を取得します。
    //
    //   aa_conditions
    //     billpay_ptn_cd  パートナー精算コード
    //     customer_id     パートナー精算先コード
    //     site_cd        精算サイトコード
    //     billpay_ym     精算月（YYYY-MM)
    //     stock_type     在庫タイプ（1:一般ネット在庫、 2:連動在庫 3:東横イン在庫）
    //     rate           料率
    //   aa_options
    //     billpay        通常（1:抽出する 0: 抽出しない）
    //     billpayed      赤伝（1:抽出する 0: 抽出しない）
    //   aa_offsets
    //     page           ページ
    //     size           レコード数(1から) ページ数を指定した場合必須
    //======================================================================
    public function getDetail($aa_conditions, $aa_options, $aa_offsets = null)
    {
        try {
            $a_row = array();
            $o_data = $this->getDetailStatement($aa_conditions, $aa_options, $aa_offsets);

            // while ($a_data = $o_data->fetch()) {
            //     if (!$this->is_empty($a_data)) {
            //         $a_row[] = $a_data;
            //     }
            // }
            //上記書き換え、これではダメか？
            foreach ($o_data as $a_data) {
                if (!$this->is_empty($a_data)) {
                    $a_row[] = $a_data;
                }
            }

            return array(
                            'values'     => $a_row,
                            // 'reference'  => $this->set_reference('支払・請求に対応する予約情報を取得します。', __METHOD__)
                        );

        // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 検索に利用するパラメータでURIを作成　※private→publicへ変更したが大丈夫？
    //======================================================================
    public function setSearchParams($requestBrBillPayPtn)
    {
        try {
            // 初期化
            $_a_pager_params = array();

            // 精算年月
            if (!$this->is_empty($requestBrBillPayPtn['billpay_ym'])) {
                $_a_pager_params['billpay_ym']  = $requestBrBillPayPtn['billpay_ym'];
            }

            return $_a_pager_params;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 明細を表示するパラメータでURIを作成 ※private->publicへ変更
    //======================================================================
    public function setDetailParams($requestBrBillPayPtn)
    {
        try {
            // 初期化
            $_a_pager_params = array();

            // 精算年月
            if (!$this->is_empty($requestBrBillPayPtn['billpay_ym'] ?? null)) { //??null追記
                $_a_pager_params['billpay_ym']  = $requestBrBillPayPtn['billpay_ym'];
            }

            // 対象精算年月
            if (!$this->is_empty($requestBrBillPayPtn['target_ym'] ?? null)) { //??null追記
                $_a_pager_params['target_ym']  = $requestBrBillPayPtn['target_ym'];
            }
            // サイトコード
            if (!$this->is_empty($requestBrBillPayPtn['site_cd'] ?? null)) { //??null追記
                $_a_pager_params['site_cd']  = $requestBrBillPayPtn['site_cd'];
            }
            // パートナー精算先コード
            if (!$this->is_empty($requestBrBillPayPtn['customer_id'] ?? null)) { //??null追記
                $_a_pager_params['customer_id']  = $requestBrBillPayPtn['customer_id'];
            }
            // 精算コード
            if (!$this->is_empty($requestBrBillPayPtn['billpay_ptn_cd'] ?? null)) { //??null追記
                $_a_pager_params['billpay_ptn_cd']  = $requestBrBillPayPtn['billpay_ptn_cd'];
            }
            // 赤伝予約
            if (!$this->is_empty($requestBrBillPayPtn['billpayed'] ?? null)) { //??null追記
                $_a_pager_params['billpayed']  = $requestBrBillPayPtn['billpayed'];
            }
            // 通常予約
            if (!$this->is_empty($requestBrBillPayPtn['billpay'] ?? null)) { //??null追記
                $_a_pager_params['billpay']  = $requestBrBillPayPtn['billpay'];
            }
            // 在庫種類
            if (!$this->is_empty($requestBrBillPayPtn['stock_type'] ?? null)) { //??null追記
                $_a_pager_params['stock_type']  = $requestBrBillPayPtn['stock_type'];
            }
            // 料率
            if (!$this->is_empty($requestBrBillPayPtn['rate'] ?? null)) { //??null追記
                $_a_pager_params['rate']  = $requestBrBillPayPtn['rate'];
            }
            // MSD料率
            if (!$this->is_empty($requestBrBillPayPtn['msd_rate'] ?? null)) { //??null追記
                $_a_pager_params['msd_rate']  = $requestBrBillPayPtn['msd_rate'];
            }

            return $_a_pager_params;
        } catch (Exception $e) {
            throw $e;
        }
    }
 
    //======================================================================
    // パートナー精算対象月の精算データを検索 ※private->publicへ変更
    //======================================================================
    // aa_conditions
    //   as_billpay_ym   精算年月(YYYY-MM)
    //   as_customer_id  パートナー精算先ID
    public function getBillPayPtn($aa_conditions)
    {
        try {
            $s_customer_id = null; //初期化 追記

            // バインドパラメータ設定
            $a_conditions['billpay_ym'] = $aa_conditions['billpay_ym'];
            if (!$this->is_empty($aa_conditions['customer_id'] ?? null)) { //null追記
                $s_customer_id = 'and billpay_ptn_book.customer_id = :customer_id';
                $a_conditions['customer_id'] = $aa_conditions['customer_id'];
            }

            $s_sql =
                <<< SQL
				select	billpay_ptn_book.billpay_ptn_cd,
						billpay_ptn_cstmr.customer_id,
						billpay_ptn_cstmr.customer_nm,
						billpay_ptn_cstmr.person_post,
						billpay_ptn_cstmr.person_nm,
						billpay_ptn_cstmr.document_type,
						billpay_ptn_book.billpay_type,
						case
							when billpay_ptn_book.billpay_type = 0 then '請求'
							when billpay_ptn_book.billpay_type = 1 then '支払'
							else '繰越'
						end billpay_type_nm,
						billpay_ptn_book.billpay_charge_total,
						billpay_ptn_book.book_path,
						billpay_ptn_book.book_create_dtm as book_create_dtm, -- 書き換え問題ないか
						date_format(billpay_ptn_book.billpay_ym, '%Y-%m')  as billpay_ym, -- 書き換え問題ないか
						billpay_ptn_book.bill_ymd as bill_ymd, -- 書き換え問題ないか
						billpay_ptn_cstmr.billpay_day
				from	billpay_ptn_book,
						billpay_ptn_cstmr
				where	date_format(billpay_ptn_book.billpay_ym, '%Y-%m') = :billpay_ym -- 書き換え問題ないか
					and	date_format(billpay_ptn_book.billpay_ym, '%Y-%m') 
                        = date_format(billpay_ptn_cstmr.billpay_ym, '%Y-%m') -- 書き換え問題ないか
					and	billpay_ptn_book.customer_id = billpay_ptn_cstmr.customer_id
					{$s_customer_id}
					order by billpay_ptn_cd
SQL;

            $a_book = DB::select($s_sql, $a_conditions);
            $a_book = json_decode(json_encode($a_book), true); //json~追記しないとviewでエラー

            $o_cipher = new Models_Cipher(config('settings.cipher_key'));
            for ($n_cnt = 0; $n_cnt < count($a_book); $n_cnt++) {
                // 原稿ファイルパスの暗号化
                if (!$this->is_empty($a_book[$n_cnt]['book_path'])) {
                    $a_book[$n_cnt]['book_path_encrypt'] = $o_cipher->encrypt($a_book[$n_cnt]['book_path']);
                } else {
                    $a_book[$n_cnt]['book_path_encrypt'] = null;
                }

                // 付属情報設定、予約情報取得
                if (!$this->is_empty($aa_conditions['customer_id'] ?? null)) { //null追記
                    // ＮＴＡの場合
                    if ($aa_conditions['customer_id'] == 1) {
                        $a_book[$n_cnt]['extension_state'] = 0;
                        $a_book[$n_cnt]['connect_type'] = 'pool';

                    // 出張なびの場合
                    } elseif ($aa_conditions['customer_id'] == 2) {
                        $a_book[$n_cnt]['extension_state'] = 0;
                        $a_book[$n_cnt]['connect_type'] = 'clone';
                    } else {
                        $s_sql =
                            <<< SQL
						select	max(partner_control.extension_state) as extension_state, -- nullでとってきている？
								max(partner_control.connect_type) as connect_type
						from	billpay_ptn_cstmrsite,
								billpay_ptn_site LEFT OUTER JOIN partner_control -- (+)→LEFT OUTER JOIN～ONの書き換えこれで大丈夫か
                                    ON billpay_ptn_site.partner_cd = partner_control.partner_cd
						where   date_format(billpay_ptn_cstmrsite.billpay_ym, '%Y-%m') = :billpay_ym -- 書き換え問題ないか
							and	billpay_ptn_cstmrsite.customer_id   = :customer_id
							and	billpay_ptn_cstmrsite.billpay_ym    = billpay_ptn_site.billpay_ym
							and	billpay_ptn_cstmrsite.site_cd       = billpay_ptn_site.site_cd
                            -- and	billpay_ptn_site.partner_cd = partner_control.partner_cd(+) 上記LEFTOUTERJOINに書き換え
SQL;

                        $a_row = DB::select($s_sql, $a_conditions);
                        $a_row = json_decode(json_encode($a_row), true); //json~追記しないとviewでエラー

                        $a_book[$n_cnt]['extension_state'] = $a_row[0]['extension_state']; //どこで使っているかわからない
                        $a_book[$n_cnt]['connect_type']    = $a_row[0]['connect_type'];
                    }
                }
            }

            if ($this->is_empty($a_book)) {
                $error[] = 'NotFound';
            }

            return $a_book ?? array();
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // CSV表示 ※private->publicへ変更
    //======================================================================
    public function csv($requestBrBillPayPtn)
    {
        try {
            //初期化
            // $o_models_BillpayPtn = new models_BillpayPtn();
            $a_conditions     = [];
            $o_data = []; //追記


            // ページ数設定
            // $this->_a_request_params['page'] = $this->params('page');

            if ($requestBrBillPayPtn['page'] == 1) {
                $a_conditions['billpay_ym'] =  $requestBrBillPayPtn['billpay_ym'];
                $a_conditions['customer_id'] = $requestBrBillPayPtn['customer_id'];

                // サイト未指定
                if ($this->is_empty($requestBrBillPayPtn['site_cd'] ?? null)) { //??null追記
                    $a_customer = $this->getBillpayptn($a_conditions);
                    $a_customer = $a_customer[0];

                    // 通常・赤伝集出設定
                    $a_options['billpay']   = 1;
                    $a_options['billpayed'] = 1;

                // サイト指定
                } else {
                    $a_conditions['site_cd']         = $requestBrBillPayPtn['site_cd'];

                    $a_customer = $this->getSite($a_conditions);

                    // 通常・赤伝集出設定
                    $a_options['billpay']   = $requestBrBillPayPtn['billpay'] ?? 0; //nvl→??へ
                    $a_options['billpayed'] = $requestBrBillPayPtn['billpayed'] ?? 0; //nvl→??へ
                }

                // 指定月の精算状況
                $a_conditions['billpay_ym']      = $requestBrBillPayPtn['target_ym'] ?? null; //null追記
                $a_conditions['billpay_ptn_cd']  = $requestBrBillPayPtn['billpay_ptn_cd'] ?? null; //null追記
                $a_conditions['customer_id']     = $requestBrBillPayPtn['customer_id'] ?? null; //null追記
                $a_conditions['stock_type']      = $requestBrBillPayPtn['stock_type'] ?? null; //null追記
                $a_conditions['rate']            = $requestBrBillPayPtn['rate'] ?? null; //null追記
                $a_conditions['msd_rate']        = $requestBrBillPayPtn['msd_rate'] ?? 0; //nvl→??へ

                $o_data = $this->getDetailStatement($a_conditions, $a_options);

                // $this->_assign->customer                = $a_customer;
            }

            $a_offset =  array('page' => $requestBrBillPayPtn['page'], 'size' => 1000 );//TODO 1000から要変更？ページャーと合わせる？
            $a_offset['firstItemNumber'] =  $a_offset['size'] * ($a_offset['page'] - 1 ) + 1;

            $a_row = [];
            // while (($a_data = $this->_o_data->fetch()) !== FALSE) {
            //     if (!is_empty($a_data)) {
            //         $a_row[] = $a_data;
            //     }
            //     if (count($a_row) == $a_offset['size']) {
            //         break;
            //     }
            // }
            //上記書き換え、これではダメか？
            foreach ($o_data as $data) {
                if (!$this->is_empty($data)) {
                    $a_row[] = $data;
                }
                if (count($a_row) == $a_offset['size']) {
                    break;
                }
            }


            // $this->_assign->offset = $a_offset;
            // $this->_assign->detail = $a_row;

            // 検索結果が0件のときは表示終了
            // if (count($a_row) < $a_offset['size']) {
            //     return false;
            // }
            //上でsizeに1000を指定しているのに、1000以上になることはないのでは…？下記書き換えでOK？
            if (count($a_row) == 0) {
                return false;
            }

            return ["offset" => $a_offset, "detail" => $a_row, "customer" => $a_customer];
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // CSVヘッダー設定 ※モデルへの記述でいい？
    //======================================================================
    public function setCsvHeader($customer)
    {
        $header = ["No.", "サイトコード"];
        if ($customer['customer_id'] == 1) {
            $header = array_merge($header, ["枝番"]);
        }
        $header = array_merge($header, ["サイト名", "予約参照コード", "予約日", "宿泊日", "都道府県コード", "都道府県"]);
        if ($customer['customer_id'] == 126) {
            $header = array_merge($header, ["住所"]);
        }
        $header = array_merge($header, ["施設コード","ホテル名","部屋名称","部屋コード","プラン名称","プランコード","宿泊代表者","予約状態","確認状態","確認日"]);
        if ($customer['billpay_ptn_cd'] == 'P20140400001P') {
            $header = array_merge($header, ["成約料金(税抜)"]);
        } else {
            $header = array_merge($header, ["成約料金(税込)"]);
        }
        $header = array_merge($header, ["消費税"]);
        if ($customer['document_type'] == 2 || $customer['document_type'] == 3) {
            $header = array_merge($header, ["率（％）", "広告宣伝料（税抜）"]);
        }
        if ($customer['document_type'] == 1 || $customer['document_type'] == 3) {
            $header = array_merge($header, ["宿泊料（税込）", "取消料"]);
        }
        if ($customer['extension_state']) {
            $header = array_merge($header, ["付随情報"]);
        }
        if ($customer['customer_id'] == 126) { //光通信
            $header = array_merge($header, ["ログインアカウント"]);
        }
        if ($customer['connect_type'] == 'pool') {
            $header = array_merge($header, ["予約グループコード"]);
        }

        return $header;
    }

    //======================================================================
    // CSVデータ設定 ※モデルへの記述でいい？
    //======================================================================
    public function setCsvData($customer, $offset, $detail)
    {
        $data = [];
        $counter = 1; //No.部分のループ回数の取得用

        foreach ($detail as $index => $detail) {
            //初期化
            $string = [];
            /* No.            */
            $string = array_merge($string, [($offset['firstItemNumber'] + $counter - 1)]);
            /* サイトコード   */
            $string = array_merge($string, [$detail['partner_cd'] . $detail['affiliate_cd']]);
            /* 枝番           */
            if ($customer['customer_id'] == '1') {
                $string = array_merge($string, [$detail['affiliate_cd_sub']]);
            };
            /* サイト名       */
            $string = array_merge($string, [$detail['site_nm']]);
            /* 予約参照コード */
            $string = array_merge($string, [$detail['reserve_cd']]);
            /* 予約日         */
                //日付をフォーマット
                $date = new Carbon($detail['reserve_dtm']);
                $detail['reserve_dtm'] = "$date->year" . "/" . sprintf('%02d', $date->month) . "/" . sprintf('%02d', $date->day);
            $string = array_merge($string, [$detail['reserve_dtm']]);
            /* 宿泊日         */
                //日付をフォーマット
                $date = new Carbon($detail['date_ymd']);
                $detail['date_ymd'] = "$date->year" . "/" . sprintf('%02d', $date->month) . "/" . sprintf('%02d', $date->day);
            $string = array_merge($string, [$detail['date_ymd']]);
            /* 都道府県コード */
            $string = array_merge($string, [$detail['pref_id']]);
            /* 都道府県       */
            $string = array_merge($string, [$detail['pref_nm']]);
            /* 住所           */
            if ($customer['customer_id'] == '126') {
                $string = array_merge($string, [$detail['address']]);
            };
            /* 施設コード     */
            $string = array_merge($string, [$detail['hotel_cd']]);
            /* ホテル名       */
            $string = array_merge($string, [$detail['hotel_nm']]);
            /* 部屋名称       */
            $string = array_merge($string, [$detail['room_nm']]);
            /* 部屋コード     */
            $string = array_merge($string, [$detail['room_cd']]);
            /* プラン名称     */
            $string = array_merge($string, [$detail['plan_nm']]);
            /* プランコード   */
            $string = array_merge($string, [$detail['plan_cd']]);
            /* 宿泊代表者     */
            $string = array_merge($string, [$detail['guest_nm']]);
            /* 予約状態       */
            if ($detail['bill_type'] == 0) {
                $string = array_merge($string, ["予約"]);
            } else {
                $string = array_merge($string, ["キャンセル"]);
            };
            /* 確認状態       */
            if ($detail['bill_type'] == 0 && !$this->is_empty($detail['operation_ymd'])) {
                $string = array_merge($string, ["料金変更"]);
            } elseif ($detail['bill_type'] != 0 && $detail['reserve_status'] == 2) {
                $string = array_merge($string, ["電話キャンセル"]);
            } elseif ($detail['bill_type'] != 0 && $detail['reserve_status'] == 4) {
                $string = array_merge($string, ["無断不泊"]);
            } else {
                $string = array_merge($string, [""]);
            }
            /* 確認日         */
            if ($detail['bill_type'] == 0 && !$this->is_empty($detail['operation_ymd'])) {
                //日付をフォーマット
                $date = new Carbon($detail['operation_ymd']);
                $detail['operation_ymd'] = "$date->year" . "/" . sprintf('%02d', $date->month) . "/" . sprintf('%02d', $date->day);
                $string = array_merge($string, [$detail['operation_ymd']]);
            } elseif ($detail['bill_type'] != 0) {
                //日付をフォーマット
                $date = new Carbon($detail['cancel_dtm']);
                $detail['cancel_dtm'] = "$date->year" . "/" . sprintf('%02d', $date->month) . "/" . sprintf('%02d', $date->day);
                $string = array_merge($string, [$detail['cancel_dtm']]);
            } else {
                $string = array_merge($string, [""]);
            }
            /* 成約料金       */
            if ($customer['billpay_ptn_cd'] == 'P20140400001P') {
                $string = array_merge($string, [$detail['bill_charge'] - $detail['bill_charge_tax']]);
            } else {
                $string = array_merge($string, [$detail['bill_charge']]);
            }
            /* 消費税         */
            $string = array_merge($string, [$detail['bill_charge_tax']]);
            /* 支払           */
            if ($customer['document_type'] == 2 || $customer['document_type'] == 3) {
                /*   率（％）     */
                /*   広告宣伝料     */
                $string = array_merge($string, [number_format($detail['rate'], 2), $detail['fee']]);
            };
            /* 請求           */
            if ($customer['document_type'] == 1 || $customer['document_type'] == 3) {
                /*   宿泊料       */
                /*   取消料       */
                $string = array_merge($string, [$detail['later_sales_charge'], $detail['later_cancel_charge']]);
            }
            /* 付属情報       */
            if ($customer['extension_state']) {
                $string = array_merge($string, [$detail['extension_value']]);
            };
            /* 光通信         */
            if ($customer['customer_id'] == '126') {
                $string = array_merge($string, [$detail['account_id']]);
            };
            /* 連携在庫(POOL) */
            if ($customer['connect_type'] == 'pool') {
                $string = array_merge($string, [$detail['order_cd']]);
            };

            $data[] = $string;
            $counter++;
        }

        return $data;
    }
}
