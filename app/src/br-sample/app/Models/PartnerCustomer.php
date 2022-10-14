<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;

class PartnerCustomer extends CommonDBModel
{
    use Traits;
    protected $table = 'partner_customer';
    // カラム
    // static のほうがよい？
    public string $COL_CUSTOMER_ID              = 'customer_id';
    public string $COL_CUSTOMER_NM              = 'customer_nm';
    public string $COL_POSTAL_CD                = 'postal_cd';
    public string $COL_PREF_ID                  = 'pref_id';
    public string $COL_ADDRESS                  = 'address';
    public string $COL_TEL                      = 'tel';
    public string $COL_FAX                      = 'fax';
    public string $COL_EMAIL                    = 'email';
    public string $COL_PERSON_POST              = 'person_post';
    public string $COL_PERSON_NM                = 'person_nm';
    public string $COL_MAIL_SEND                = 'mail_send';
    public string $COL_CANCEL_STATUS            = 'cancel_status';
    public string $COL_TAX_UNIT                 = 'tax_unit';
    public string $COL_DETAIL_STATUS            = 'detail_status';
    public string $COL_BILLPAY_DAY              = 'billpay_day';
    public string $COL_BILLPAY_REQUIRED_MONTH   = 'billpay_required_month';
    public string $COL_BILLPAY_CHARGE_MIN       = 'billpay_charge_min';

    function __construct()
    {
        // カラム情報の設定
        $col_customer_id = new ValidationColumn();
        $col_customer_id->setColumnName($this->COL_CUSTOMER_ID, '支払先ID');
        $col_customer_nm = new ValidationColumn();
        $col_customer_nm->setColumnName($this->COL_CUSTOMER_NM, '支払先名称');
        $col_postal_cd = new ValidationColumn();
        $col_postal_cd->setColumnName($this->COL_POSTAL_CD, '郵便番号');
        $col_pref_id = new ValidationColumn();
        $col_pref_id->setColumnName($this->COL_PREF_ID, '都道府県ID');
        $col_address = new ValidationColumn();
        $col_address->setColumnName($this->COL_ADDRESS, '住所');
        $col_tel = new ValidationColumn();
        $col_tel->setColumnName($this->COL_TEL, '電話番号');
        $col_fax = new ValidationColumn();
        $col_fax->setColumnName($this->COL_FAX, 'ファックス番号');
        $col_email = new ValidationColumn();
        $col_email->setColumnName($this->COL_EMAIL, 'チャネル合算支払先');
        $col_person_post = new ValidationColumn();
        $col_person_post->setColumnName($this->COL_PERSON_POST, '担当者役職');
        $col_person_nm = new ValidationColumn();
        $col_person_nm->setColumnName($this->COL_PERSON_NM, '担当者名称');
        $col_mail_send = new ValidationColumn();
        $col_mail_send->setColumnName($this->COL_MAIL_SEND, 'メール送信可否');
        $col_cancel_status = new ValidationColumn();
        $col_cancel_status->setColumnName($this->COL_CANCEL_STATUS, '精算キャンセル対象状態');
        $col_tax_unit = new ValidationColumn();
        $col_tax_unit->setColumnName($this->COL_TAX_UNIT, '消費税単位');
        $col_detail_status = new ValidationColumn();
        $col_detail_status->setColumnName($this->COL_DETAIL_STATUS, '明細書有無');
        $col_billpay_day = new ValidationColumn();
        $col_billpay_day->setColumnName($this->COL_BILLPAY_DAY, '精算日');
        $col_billpay_required_month = new ValidationColumn();
        $col_billpay_required_month->setColumnName($this->COL_BILLPAY_REQUIRED_MONTH, '精算必須月');
        $col_billpay_charge_min = new ValidationColumn();
        $col_billpay_charge_min->setColumnName($this->COL_BILLPAY_CHARGE_MIN, '精算最低金額');

        // バリデーション追加
        // HACK: メソッドチェーンにする。
        // 支払先ID
        $col_customer_id->require(); // 必須入力チェック
        $col_customer_id->length(0, 10); // 長さチェック
        $col_customer_id->intOnly(); // 数字：数値チェック

        // 支払先名称
        $col_customer_nm->notHalfKana(); // 半角カナチェック
        $col_customer_nm->length(0, 50); // 長さチェック

        // 郵便番号
        $col_postal_cd->notHalfKana(); // 半角カナチェック
        $col_postal_cd->postal(); // 郵便番号チェック
        $col_postal_cd->length(0, 8); // 長さチェック

        // 都道府県ID
        $col_pref_id->length(0, 2); // 長さチェック
        $col_pref_id->intOnly(); // 数字：数値チェック

        // 住所
        $col_address->require(); // 必須入力チェック
        $col_address->notHalfKana(); // 半角カナチェック
        $col_address->length(0, 100); // 長さチェック

        // 電話番号
        $col_tel->notHalfKana(); // 半角カナチェック
        $col_tel->phoneNumber(); // 電話番号チェック
        $col_tel->length(0, 15); // 長さチェック

        // ファックス番号
        $col_fax->notHalfKana(); // 半角カナチェック
        $col_fax->phoneNumber(); // 電話番号チェック
        $col_fax->length(0, 15); // 長さチェック

        // チャネル合算支払先
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

        // 精算キャンセル対象状態
        $col_cancel_status->length(0, 1); // 長さチェック
        $col_cancel_status->intOnly(); // 数字：数値チェック

        // 消費税単位
        $col_tax_unit->length(0, 9); // 長さチェック
        $col_tax_unit->intOnly(); // 数字：数値チェック

        // 明細書有無
        $col_detail_status->length(0, 1); // 長さチェック
        $col_detail_status->intOnly(); // 数字：数値チェック

        // 精算日
        $col_billpay_day->length(0, 2); // 長さチェック
        $col_billpay_day->intOnly(); // 数字：数値チェック

        // 精算必須月
        $col_billpay_required_month->notHalfKana(); // 半角カナチェック
        $col_billpay_required_month->length(0, 12); // 長さチェック

        // 精算最低金額
        $col_billpay_charge_min->length(0, 5); // 長さチェック
        $col_billpay_charge_min->intOnly(); // 数字：数値チェック

        parent::setColumnDataArray([
            $col_customer_id           , $col_customer_nm       , $col_postal_cd, $col_pref_id      , $col_address,
            $col_tel                   , $col_fax               , $col_email    , $col_person_post  , $col_person_nm,
            $col_mail_send             , $col_cancel_status     , $col_tax_unit , $col_detail_status, $col_billpay_day,
            $col_billpay_required_month, $col_billpay_charge_min,
        ]);
    }

    public function getPartnerCustomers($keywords = '')
    {

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
        // HACK: SQL 直書きはどうにかしたい
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
        foreach ($result as $key => $value) {
            if (!empty($value->email)) {
                $result[$key]->email_decrypt = $cipher->decrypt($value->email);
            } else {
                $result[$key]->email_decrypt = '';
            }
        }

        return $result;
    }

    public function getPartnerCustomerById($customer_id)
    {
        // HACK: SQL 直書きはどうにかしたい
        $sql = <<<SQL
            select
                customer_id,
                customer_nm,
                postal_cd,
                pref_id,
                address,
                tel,
                fax,
                email,
                person_post,
                person_nm,
                mail_send,
                cancel_status,
                tax_unit,
                detail_status,
                billpay_day,
                billpay_required_month,
                billpay_charge_min
            from
                partner_customer
            where
                customer_id = :customer_id
        SQL;
        $result = DB::select($sql, ['customer_id' => $customer_id]);

        if (count($result) < 1) {
            // TODO: error
            // 暫定的に新規登録扱いとして実装している。ダメ。
            return (object)[
                'customer_id' => $this->_get_sequence_no(),
                'customer_nm' => '',
                'postal_cd' => '',
                'pref_id' => '',
                'address' => '',
                'tel' => '',
                'fax' => '',
                'email' => '',
                'person_post' => '',
                'person_nm' => '',
                'mail_send' => '0',
                'cancel_status' => '0',
                'tax_unit' => '',
                'detail_status' => '0',
                'billpay_day' => '8',
                'billpay_required_month' => '000000000000',
                'billpay_charge_min' => '',
            ];
        }
        // HACK: 1件だけでいい。もしくは共通化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        foreach($result as $key => $value) {
            if (!empty($value->email)) {
                $result[$key]->email = $cipher->decrypt($value->email);
            } else {
                $result[$key]->email = '';
            }
        }

        return $result[0];
    }

    /**  キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByKey($con, $data)
    {
        $result = $con->table($this->table)
                    ->where($this->COL_CUSTOMER_ID, $data[$this->COL_CUSTOMER_ID])
                    ->update($data);
        if (!$result) {
            return "更新に失敗しました";
        }
        return "";
    }

    private function _get_sequence_no() {
        $sql =
        <<<SQL
            select	ifnull((max(customer_id) + 1), 1) as customer_id
            from	partner_customer
        SQL;
        $result = DB::select($sql);
        return $result[0]->customer_id;
    }

}
