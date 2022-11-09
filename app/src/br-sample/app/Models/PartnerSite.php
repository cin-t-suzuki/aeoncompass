<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PartnerSite extends CommonDBModel
{
    use Traits;
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
        self::COL_SITE_NM,
        self::COL_EMAIL,
        self::COL_PERSON_POST,
        self::COL_PERSON_NM,
        self::COL_MAIL_SEND,
        self::COL_PARTNER_CD,
        self::COL_AFFILIATE_CD,
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];

    // カラム
    const COL_SITE_CD       = 'site_cd';
    const COL_SITE_NM       = 'site_nm';
    const COL_EMAIL         = 'email';
    const COL_PERSON_POST   = 'person_post';
    const COL_PERSON_NM     = 'person_nm';
    const COL_MAIL_SEND     = 'mail_send';
    const COL_PARTNER_CD    = 'partner_cd';
    const COL_AFFILIATE_CD  = 'affiliate_cd';

    public function __construct()
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
        $col_site_cd->require();            // 必須入力チェック
        $col_site_cd->notHalfKana();        // 半角カナチェック
        $col_site_cd->length(0, 10);        // 長さチェック

        // 提携先サイト名称
        $col_site_nm->notHalfKana();        // 半角カナチェック
        $col_site_nm->length(0, 65);        // 長さチェック

        // チャネル別支払先
        $col_email->notHalfKana();          // 半角カナチェック
        $col_email->emails();               // メールアドレスチェック
        $col_email->length(0, 200);         // 長さチェック

        // 担当者役職
        $col_person_post->notHalfKana();    // 半角カナチェック
        $col_person_post->length(0, 96);    // 長さチェック

        // 担当者名称
        $col_person_nm->notHalfKana();      // 半角カナチェック
        $col_person_nm->length(0, 32);      // 長さチェック

        // メール送信可否
        $col_mail_send->length(0, 1);       // 長さチェック
        $col_mail_send->intOnly();          // 数字：数値チェック

        // 提携先コード
        $col_partner_cd->notHalfKana();     // 半角カナチェック
        $col_partner_cd->length(0, 10);     // 長さチェック

        // アフィリエイトコード
        $col_affiliate_cd->notHalfKana();   // 半角カナチェック
        $col_affiliate_cd->length(0, 10);   // 長さチェック

        parent::setColumnDataArray([
            $col_site_cd  , $col_site_nm   , $col_email       , $col_person_post, $col_person_nm,
            $col_mail_send, $col_partner_cd, $col_affiliate_cd,
        ]);
    }

    /**
     * パートナー精算先サイトを検索 (検索ワード)
     *
     * @param string? $keyword
     * @param string? $customer_id
     * @param string? $customer_off // HACK: Naming (customer_exclude seems better)
     * @param string? $site_cd // HACK: unused
     * 
     * @return stdClass[]
     */
    public function getPartnerSiteByKeywords($keywords, $customer_id, $customer_off, $site_cd)
    {
        // keywords を分割し、単語ごとにフォーマットで検索対象カラムを判定
        $a_keywords = explode(' ', str_replace('　', ' ', $keywords));

        $a_conditions = [];
        foreach ($a_keywords as $keyword) {
            if (!$this->is_empty($keyword)) {
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
        if (!is_null($customer_id) && is_null($customer_off)) {
            $a_conditions['customer_id'] = $customer_id;
        }

        return $this->_get_sites($a_conditions);
    }

    /**
     * パートナー精算サイト 検索
     *
     * @param array $aa_conditions
     * @return stdClass[]
     */
    public function _get_sites($aa_conditions)
    {
        // キーが存在しない場合に初期化
        if (!array_key_exists('customer_id', $aa_conditions)) {
            $aa_conditions['customer_id'] = '';
        }
        if (!array_key_exists('customer_nm', $aa_conditions)) {
            $aa_conditions['customer_nm'] = '';
        }
        if (!array_key_exists('site_cd', $aa_conditions)) {
            $aa_conditions['site_cd'] = '';
        }
        if (!array_key_exists('site_nm', $aa_conditions)) {
            $aa_conditions['site_nm'] = '';
        }
        if (!array_key_exists('partner_cd', $aa_conditions)) {
            $aa_conditions['partner_cd'] = '';
        }
        if (!array_key_exists('affiliate_cd', $aa_conditions)) {
            $aa_conditions['affiliate_cd'] = '';
        }

        $s_customer_id  = '';
        $s_customer_nm  = '';
        $s_nm           = '';
        $s_site_cd      = '';
        $s_site_nm      = '';
        $s_partner_cd   = '';
        $s_affiliate_cd = '';

        // バインドパラメータ設定
        $a_conditions = [];
        if (!$this->is_empty($aa_conditions['customer_id'])) {
            $s_customer_id = 'and partner_customer.customer_id = :customer_id';
            $a_conditions['customer_id'] = $aa_conditions['customer_id'];
        }

        if (!$this->is_empty($aa_conditions['customer_nm']) && !$this->is_empty($aa_conditions['site_nm'])) {
            $s_nm = 'and (partner_customer.customer_nm like concat(\'%\', :customer_nm, \'%\') or partner_site.site_nm like concat(\'%\', :site_nm, \'%\'))';
            $a_conditions['customer_nm']    = $aa_conditions['customer_nm'];
            $a_conditions['site_nm']        = $aa_conditions['site_nm'];
        }elseif (!$this->is_empty($aa_conditions['customer_nm'])) {
            $s_customer_nm = 'and partner_customer.customer_nm like concat(\'%\', :customer_nm, \'%\')';
            $a_conditions['customer_nm']    = $aa_conditions['customer_nm'];
        }elseif (!$this->is_empty($aa_conditions['site_nm'])) {
            $s_site_nm = 'and partner_site.site_nm like concat(\'%\', :site_nm, \'%\')';
            $a_conditions['site_nm']        = $aa_conditions['site_nm'];
        }

        if (!$this->is_empty($aa_conditions['partner_cd']) && !$this->is_empty($aa_conditions['affiliate_cd']) && !$this->is_empty($aa_conditions['site_cd'])) {
            $s_partner_cd = 'and (partner_site.partner_cd = :partner_cd or partner_site.affiliate_cd = :affiliate_cd or partner_site.site_cd = :site_cd)';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
            $a_conditions['site_cd']        = $aa_conditions['site_cd'];
        }elseif (!$this->is_empty($aa_conditions['partner_cd']) && !$this->is_empty($aa_conditions['affiliate_cd'])) {
            $s_partner_cd = 'and (partner_site.partner_cd = :partner_cd or partner_site.affiliate_cd = :affiliate_cd)';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
        }elseif (!$this->is_empty($aa_conditions['partner_cd'])) {
            $s_affiliate_cd = 'and partner_site.partner_cd = :partner_cd';
            $a_conditions['partner_cd']     = $aa_conditions['partner_cd'];
        }elseif (!$this->is_empty($aa_conditions['affiliate_cd'])) {
            $s_affiliate_cd = 'and partner_site.affiliate_cd = :affiliate_cd';
            $a_conditions['affiliate_cd']   = $aa_conditions['affiliate_cd'];
        }elseif (!$this->is_empty($aa_conditions['site_cd'])) {
            $s_site_cd = 'and partner_site.site_cd = :site_cd';
            $a_conditions['site_cd']        = $aa_conditions['site_cd'];
        }


        // MEMO: sql 直書きはどうにかしたい？
        //  → 大きな SQL を読み解くほどの工数はないので、そのまま移植する方針とする。
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
            if (!$this->is_empty($value->email)) {
                $result[$key]->email_decrypt = $cipher->decrypt($value->email);
            } else {
                $result[$key]->email_decrypt = '';
            }
        }

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
    public function _get_sequence_no()
    {
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
