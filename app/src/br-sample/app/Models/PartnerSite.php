<?php

namespace App\Models;

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
     */
    public function getPartnerSiteByKeywords()
    {
        $s_customer_id = '';
        $s_customer_nm = '';
        $s_nm = '';
        $s_site_cd = '';
        $s_site_nm = '';
        $s_partner_cd = '';
        $s_affiliate_cd = '';

        // HACK: sql 直書きはどうにかしたい
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

        $result = DB::select($sql);
        return $result;
    }
}
