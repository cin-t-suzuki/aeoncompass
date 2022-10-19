<?php

namespace App\Models;

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
    private function _get_sites($aa_conditions)
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
}
