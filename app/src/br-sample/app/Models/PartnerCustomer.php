<?php

namespace App\Models;

use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;

class PartnerCustomer
{
    public function getPartnerCustomers($keywords = '') {

        // keywords を分割し、単語ごとにフォーマットで検索対象カラムを判定
        $a_keywords = explode(' ', str_replace('　', ' ', $keywords));
        $aa_conditions = [];
        foreach ($a_keywords as $value) {
            if (!empty($value)) {
                if (preg_match('/[A-Z0-9][0-9]{9}/', $value)) {
                    $aa_conditions['partner_cd'] = $value;
                    $aa_conditions['affiliate_cd'] = $value;
                }
                if (preg_match('/[0-9]{10}/', $value)) {
                    $aa_conditions['site_cd'] = $value;
                }
                if (is_numeric($value)) {
                    if (strlen($value) < 10) {
                        $aa_conditions['customer_id'] = $value;
                    }
                } else {
                    $aa_conditions['customer_nm'] = $value;
                    $aa_conditions['site_nm'] = $value;
                }
            }
        }

        // 検索対象カラム => 検索値 から、where 句に追加する条件と プレースホルダに代入する配列を生成
        $a_conditions = []; // SQL へのプレースホルダに代入するもの HACK: naming

        $s_customer_nm  = '';
        $s_site_cd      = '';
        $s_site_nm      = '';
        $s_affiliate_cd = '';
        $s_customer_id  = '';
        $s_nm           = '';
        $s_partner_cd   = '';

        if (!empty($aa_conditions['customer_id'])) {
			$s_customer_id = 'and partner_customer.customer_id = :customer_id';
            $a_conditions['customer_id']    = $aa_conditions['customer_id'];
        }

        if (!empty($aa_conditions['customer_nm']) && !empty($aa_conditions['site_nm'])) {
            $s_nm = 'and (partner_customer.customer_nm like concat(\'%\', :customer_nm, \'%\') or partner_site.site_nm like concat(\'%\', :site_nm, \'%\')) ';
            $a_conditions['customer_nm']    = $aa_conditions['customer_nm'];
            $a_conditions['site_nm']        = $aa_conditions['site_nm'];
        } elseif (!empty($aa_conditions['customer_nm'])) {
            $s_customer_nm = 'and partner_customer.customer_nm like concat(\'%\', :customer_nm, \'%\')';
            $a_conditions['customer_nm']    = $aa_conditions['customer_nm'];
        } elseif (!empty($aa_conditions['site_nm'])) {
            $s_site_nm = 'and partner_site.site_nm like concat(\'%\', :site_nm, \'%\')';
            $a_conditions['site_nm']        = $aa_conditions['site_nm'];
        }

        if (!empty($aa_conditions['partner_cd']) && !empty($aa_conditions['affiliate_cd']) && !empty($aa_conditions['site_cd'])) {
            $s_partner_cd = 'and (partner_site.partner_cd = :partner_cd or partner_site.affiliate_cd = :affiliate_cd or partner_site.site_cd = :site_cd)';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
            $a_conditions['site_cd']        = $aa_conditions['site_cd'];
        } elseif (!empty($aa_conditions['partner_cd']) && !empty($aa_conditions['affiliate_cd'])) {
            $s_partner_cd = 'and (partner_site.partner_cd = :partner_cd or partner_site.affiliate_cd = :affiliate_cd)';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
        } elseif (!empty($aa_conditions['partner_cd'])) {
            $s_affiliate_cd = 'and partner_site.partner_cd = :partner_cd';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
        } elseif (!empty($aa_conditions['affiliate_cd'])) {
            $s_affiliate_cd = 'and partner_site.affiliate_cd = :affiliate_cd';
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
        } elseif (!empty($aa_conditions['site_cd'])) {
            $s_site_cd = 'and partner_site.site_cd = :site_cd';
            $a_conditions['site_cd']        = $aa_conditions['site_cd'];
        }

        // SQL の生成と実行
        $sql = <<<SQL
            select
                q1.customer_id,
                q1.customer_nm,
                q1.postal_cd,
                q1.pref_id,
                mast_pref.pref_nm,
                q1.address,
                q1.tel,
                q1.fax,
                q1.email,
                q1.person_post,
                q1.person_nm,
                q1.mail_send,
                q1.cancel_status,
                q1.tax_unit,
                q1.detail_status,
                q1.billpay_day,
                q1.billpay_required_month,
                q1.billpay_charge_min,
                q1.site_cd,         -- 最少サイトコード
                q2.site_nm,         -- 最少サイトコードのサイト名
                q2.partner_cd,      -- 最少サイトコードのパートナーコード
                q2.affiliate_cd,    -- 最少サイトコードのアフィリエイトコード
                q1.sales_cnt,
                q1.stock_cnt,
                q1.ptn_cnt,
                q1.aft_cnt
            from
                (
                    select
                        partner_customer.customer_id,
                        partner_customer.customer_nm,
                        partner_customer.postal_cd,
                        partner_customer.pref_id,
                        partner_customer.address,
                        partner_customer.tel,
                        partner_customer.fax,
                        partner_customer.email,
                        partner_customer.person_post,
                        partner_customer.person_nm,
                        partner_customer.mail_send,
                        partner_customer.cancel_status,
                        partner_customer.tax_unit,
                        partner_customer.detail_status,
                        partner_customer.billpay_day,
                        partner_customer.billpay_required_month,
                        partner_customer.billpay_charge_min,
                        min(partner_customer_site.site_cd) as site_cd,
                        sum(
                            case
                                when partner_customer_site.fee_type = 1 then 1
                                else 0
                            end
                        ) as sales_cnt,
                        sum(
                            case
                                when partner_customer_site.fee_type = 2 then 1
                                else 0
                            end
                        ) as stock_cnt,
                        sum(
                            case
                                when partner_site.partner_cd is not null then 1
                                else 0
                            end
                        ) as ptn_cnt,
                        sum(
                            case
                                when partner_site.affiliate_cd is not null then 1
                                else 0
                            end
                        ) as aft_cnt
                    from
                        partner_customer
                        left outer join partner_customer_site
                            on partner_customer.customer_id = partner_customer_site.customer_id
                        left outer join partner_site
                            on partner_customer_site.site_cd = partner_site.site_cd
                    group by
                        partner_customer.customer_id,
                        partner_customer.customer_nm,
                        partner_customer.postal_cd,
                        partner_customer.pref_id,
                        partner_customer.address,
                        partner_customer.tel,
                        partner_customer.fax,
                        partner_customer.email,
                        partner_customer.person_post,
                        partner_customer.person_nm,
                        partner_customer.mail_send,
                        partner_customer.cancel_status,
                        partner_customer.tax_unit,
                        partner_customer.detail_status,
                        partner_customer.billpay_day,
                        partner_customer.billpay_required_month,
                        partner_customer.billpay_charge_min
                ) q1
                left outer join mast_pref
                    on q1.pref_id = mast_pref.pref_id
                left outer join
                (
                    select
                        site_cd,
                        site_nm,
                        partner_cd,
                        affiliate_cd
                    from
                        partner_site
                    where 1 = 1
                ) q2
                    on q1.site_cd = q2.site_cd
            where 1 = 1
                and q1.customer_id in (
                    select
                        partner_customer.customer_id
                    from 
                        partner_customer
                        left outer join partner_customer_site
                            on partner_customer.customer_id = partner_customer_site.customer_id
                        left outer join partner_site
                            on partner_customer_site.site_cd = partner_site.site_cd
                    where 1 = 1
                        {$s_customer_id}
                        {$s_customer_nm}
                        {$s_site_cd}
                        {$s_site_nm}
                        {$s_nm}
                        {$s_partner_cd}
                        {$s_affiliate_cd}
                )
            order by
                q1.customer_id
        SQL;
        $result = DB::select($sql, $a_conditions);

        // メールアドレスを復号
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        foreach($result as $key => $value) {
            if (!empty($value->email)) {
                $result[$key]->email_decrypt = $cipher->decrypt($value->email);
            } else {
                $result[$key]->email_decrypt = null; // TODO: 確認、空文字じゃダメ？
            }
        }

        return $result;
    }

}
