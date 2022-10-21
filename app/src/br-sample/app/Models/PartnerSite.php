<?php

namespace App\Models;

use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PartnerSite extends Model
{
    // use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'partner_site';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'flight_id';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

    // カラム
    const COL_SITE_CD       = 'site_cd';
    const COL_SITE_NM       = 'site_nm';
    const COL_EMAIL         = 'email';
    const COL_PERSON_POST   = 'person_post';
    const COL_PERSON_NM     = 'person_nm';
    const COL_MAIL_SEND     = 'mail_send';
    const COL_PARTNER_CD    = 'partner_cd';
    const COL_AFFILIATE_CD  = 'affiliate_cd';

    function __construct()
    {
        // カラム情報の設定
        $col_site_cd = new ValidationColumn();
        $col_site_cd->setColumnName(self::COL_SITE_CD, 'サイトコード');
        $col_site_nm = new ValidationColumn();
        $col_site_nm->setColumnName(self::COL_SITE_NM, '提携先サイト名称');
        $col_email = new ValidationColumn();
        $col_email->setColumnName(self::COL_EMAIL, 'チャネル別支払先');
        $col_person_post = new ValidationColumn();
        $col_person_post->setColumnName(self::COL_PERSON_POST, '担当者役職');
        $col_person_nm = new ValidationColumn();
        $col_person_nm->setColumnName(self::COL_PERSON_NM, '担当者名称');
        $col_mail_send = new ValidationColumn();
        $col_mail_send->setColumnName(self::COL_MAIL_SEND, 'メール送信可否');
        $col_partner_cd = new ValidationColumn();
        $col_partner_cd->setColumnName(self::COL_PARTNER_CD, '提携先コード');
        $col_affiliate_cd = new ValidationColumn();
        $col_affiliate_cd->setColumnName(self::COL_AFFILIATE_CD, 'アフィリエイトコード');

        // バリデーション追加
        // サイトコード
        $col_site_cd->require(); // 必須入力チェック
        $col_site_cd->notHalfKana(); // 半角カナチェック
        $col_site_cd->length(0, 10); // 長さチェック

        // 提携先サイト名称
        $col_site_nm->notHalfKana(); // 半角カナチェック
        $col_site_nm->length(0, 65); // 長さチェック

        // チャネル別支払先
        $col_email->notHalfKana(); // 半角カナチェック
        $col_email->emails(); // メールアドレスチェック
        $col_email->length(0, 200); // 長さチェック

        // 担当者役職
        $col_person_post->notHalfKana(); // 半角カナチェック
        $col_person_post->length(0, 96); // 長さチェック

        // 担当者名称
        $col_person_nm->notHalfKana(); // 半角カナチェック
        $col_person_nm->length(0, 32); // 長さチェック

        // メール送信可否
        $col_mail_send->length(0, 1); // 長さチェック
        $col_mail_send->intOnly(); // 数字：数値チェック

        // 提携先コード
        $col_partner_cd->notHalfKana(); // 半角カナチェック
        $col_partner_cd->length(0, 10); // 長さチェック

        // アフィリエイトコード
        $col_affiliate_cd->notHalfKana(); // 半角カナチェック
        $col_affiliate_cd->length(0, 10); // 長さチェック

        parent::setColumnDataArray([
            $col_site_cd  , $col_site_nm   , $col_email       , $col_person_post, $col_person_nm,
            $col_mail_send, $col_partner_cd, $col_affiliate_cd,
        ]);
    }

    /**
     * TODO: phpdoc
     *
     * @param string $keyword
     * @param string $customer_id
     * @param $customer_off // HACK: Naming (customer_exclude seems better)
     */
    public function getPartnerSiteByKeywords($keywords, $customer_id, $customer_off)
    {
        // keywords を分割し、単語ごとにフォーマットで検索対象カラムを判定
        $a_keywords = explode(' ', str_replace('　', ' ', $keywords));
        $a_conditions = [];
        foreach ($a_keywords as $keyword) {
            if (!empty($keyword)) {
                if (preg_match('/[A-Z0-9][0-9]{9}/', $keyword)) {
                    $a_conditions['partner_cd'] = $keyword;
                    $a_conditions['affiliate_cd'] = $keyword;
                }
                if (preg_match('/[0-9]{10}/', $keyword)) {
                    $a_conditions['site_cd'] = $keyword;
                }
                if (is_numeric($keyword)) {
                    if (strlen($keyword) < 10) {
                        $a_conditions['customer_id'] = $keyword;
                    }
                } else {
                    $a_conditions['customer_nm'] = $keyword;
                    $a_conditions['site_nm'] = $keyword;
                }
            }
        }
        if (!empty($customer_id) && empty($customer_off)) {
            $a_conditions['customer_id'] = $customer_id;
        }

        return $this->_get_sites($a_conditions);
    }

    /**
     * TODO: phpdoc
     */
    public function _get_sites($aa_conditions)
    {
        $s_customer_id  = '';
        $s_customer_nm  = '';
        $s_nm           = '';
        $s_site_cd      = '';
        $s_site_nm      = '';
        $s_partner_cd   = '';
        $s_affiliate_cd = '';

        // バインドパラメータ設定
        $a_conditions = [];
        if (!empty($aa_conditions['customer_id'])) {
            $s_customer_id = 'and partner_customer.customer_id = :customer_id';
            $a_conditions['customer_id'] = $aa_conditions['customer_id'];
        }

        if (!empty($aa_conditions['customer_nm']) && !empty($aa_conditions['site_nm'])) {
            $s_nm = 'and (partner_customer.customer_nm like concat(\'%\', :customer_nm, \'%\') or partner_site.site_nm like concat(\'%\', :site_nm, \'%\'))';
            $a_conditions['customer_nm']    = $aa_conditions['customer_nm'];
            $a_conditions['site_nm']        = $aa_conditions['site_nm'];
        }elseif (!empty($aa_conditions['customer_nm'])) {
            $s_customer_nm = 'and partner_customer.customer_nm like concat(\'%\', :customer_nm, \'%\')';
            $a_conditions['customer_nm']    = $aa_conditions['customer_nm'];
        }elseif (!empty($aa_conditions['site_nm'])) {
            $s_site_nm = 'and partner_site.site_nm like concat(\'%\', :site_nm, \'%\')';
            $a_conditions['site_nm']        = $aa_conditions['site_nm'];
        }

        if (!empty($aa_conditions['partner_cd']) && !empty($aa_conditions['affiliate_cd']) && !empty($aa_conditions['site_cd'])) {
            $s_partner_cd = 'and (partner_site.partner_cd = :partner_cd or partner_site.affiliate_cd = :affiliate_cd or partner_site.site_cd = :site_cd)';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
            $a_conditions['site_cd']        = $aa_conditions['site_cd'];
        }elseif (!empty($aa_conditions['partner_cd']) && !empty($aa_conditions['affiliate_cd'])) {
            $s_partner_cd = 'and (partner_site.partner_cd = :partner_cd or partner_site.affiliate_cd = :affiliate_cd)';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
        }elseif (!empty($aa_conditions['partner_cd'])) {
            $s_affiliate_cd = 'and partner_site.partner_cd = :partner_cd';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
        }elseif (!empty($aa_conditions['affiliate_cd'])) {
            $s_affiliate_cd = 'and partner_site.affiliate_cd = :affiliate_cd';
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
        }elseif (!empty($aa_conditions['site_cd'])) {
            $s_site_cd = 'and partner_site.site_cd = :site_cd';
            $a_conditions['site_cd']        = $aa_conditions['site_cd'];
        }


        // HACK: sql 直書きはどうにかしたい？
        $sql = <<<SQL
            select
                site_cd,
                max(sales_customer_id) as sales_customer_id,
                max(sales_customer_nm) as sales_customer_nm,
                max(stock_customer_id) as stock_customer_id,
                max(stock_customer_nm) as stock_customer_nm,
                site_nm,
                email,
                person_post,
                person_nm,
                mail_send,
                partner_cd,
                affiliate_cd,
                partner_nm,
                affiliate_nm
            from
                (
                    select
                        partner_site.site_cd,
                        case
                            when partner_customer_site.fee_type = 1 then partner_customer.customer_id
                            else null
                        end as sales_customer_id,
                        case
                            when partner_customer_site.fee_type = 1 then partner_customer.customer_nm
                            else null
                        end as sales_customer_nm,
                        case
                            when partner_customer_site.fee_type = 2 then partner_customer.customer_id
                            else null
                        end as stock_customer_id,
                        case
                            when partner_customer_site.fee_type = 2 then partner_customer.customer_nm
                            else null
                        end as stock_customer_nm,
                        partner_site.site_nm,
                        partner_site.email,
                        partner_site.person_post,
                        partner_site.person_nm,
                        partner_site.mail_send,
                        partner_site.partner_cd,
                        partner_site.affiliate_cd,
                        partner.system_nm as partner_nm,
                        affiliate_program.program_nm as affiliate_nm
                    from
                        partner_site
                        left outer join partner_customer_site
                            on partner_site.site_cd = partner_customer_site.site_cd
                        left outer join partner_customer
                            on partner_customer_site.customer_id = partner_customer.customer_id
                        left outer join partner
                            on partner_site.partner_cd = partner.partner_cd
                        left outer join affiliate_program
                            on partner_site.affiliate_cd = affiliate_program.affiliate_cd
                    where 1 = 1
                        {$s_customer_id}
                        {$s_customer_nm}
                        {$s_nm}
                        {$s_site_cd}
                        {$s_site_nm}
                        {$s_partner_cd}
                        {$s_affiliate_cd}
                ) q
            group by
                site_cd,
                site_nm,
                email,
                person_post,
                person_nm,
                mail_send,
                partner_cd,
                affiliate_cd,
                partner_nm,
                affiliate_nm
            order by
                site_cd,
                ifnull(partner_cd, affiliate_cd)
        SQL;

        $result = DB::select($sql, $a_conditions);

        $cipher = new Models_Cipher(config('settings.cipher_key'));
        foreach($result as $key => $value) {
            if (!empty($value->email)) {
                $result[$key]->email_decrypt = $cipher->decrypt($value->email);
            } else {
                $result[$key]->email_decrypt = '';
            }
        }

        return $result;
    }

    /**
     * パートナー精算サイト手数料率検索
     *
     * TODO: テーブルごとに分かれるなら、 partner_site_rate に対応するモデルクラスにあるほうが適切に思われる
     *
     * TODO: phpdoc
     */
    public function _get_rates($aa_conditions)
    {
        // バインドパラメータ設定
        $sql_parameters = [];
        $s_site_cd = 'and site_cd = :site_cd';
        $sql_parameters['site_cd'] = $aa_conditions['site_cd'];

        // HACK: かなりのハードコーディング？を含んでいて、危なっかしく感じられる
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
                        {$s_site_cd}
                    group by
                        site_cd,
                        accept_s_ymd
                ) q
            order by
                site_cd,
                accept_s_ymd desc
        SQL;

        $result = DB::select($sql, $sql_parameters);
        return $result;
    }

    /**
     * PK を取得
     *
     * yyyymm0000 の形式。
     * 各月 yyyymm0001 からはじめて1ずつ増やす。
     *
     * @return string
     */
    public function _get_sequence_no(){
        $ym = date('Ym');

        $sql = <<<SQL
            select	max(site_cd) as site_cd
            from	partner_site
            where	site_cd >= concat(:ym, '0000')
        SQL;

        $result = DB::select($sql, ['ym' => $ym]);

        if (count($result) === 0) {
            $new_site_cd = sprintf('%s%04d', $ym, 1);
        } else {
            $new_site_cd = sprintf('%s%04d', $ym, (int)substr($result[0]->site_cd, 6, 4) + 1);
        }
        return $new_site_cd;
    }

}
