<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/** 施設管理サイト担当者
 * 
 */
class Customer extends CommonDBModel
{
    use Traits;

    protected $table = "customer";

    // カラム
    const COL_CUSTOMER_ID  = "customer_id";
    const COL_CUSTOMER_NM   = "customer_nm";
    const COL_SECTION_NM   = "section_nm";
    const COL_PERSON_NM = "person_nm";
    const COL_POSTAL_CD = "postal_cd";
    const COL_PREF_ID    = "pref_id";
    const COL_ADDRESS    = "address";
    const COL_TEL  = "tel";
    const COL_FAX  = "fax";
    const COL_EMAIL  = "email";
    const COL_BILL_BANK_NM   = "bill_bank_nm";
    const COL_BILL_BANK_ACCOUNT_NO   = "bill_bank_account_no";
    const COL_PAYMENT_BANK_CD = "payment_bank_cd";
    const COL_PAYMENT_BANK_BRANCH_CD = "payment_bank_branch_cd";
    const COL_PAYMENT_BANK_ACCOUNT_TYPE    = "payment_bank_account_type";
    const COL_PAYMENT_BANK_ACCOUNT_NO    = "payment_bank_account_no";
    const COL_PAYMENT_BANK_ACCOUNT_KN  = "payment_bank_account_kn";
    const COL_PAYMENT_REQUIRED_MONTH  = "payment_required_month";
    const COL_BILL_CHARGE_MIN   = "bill_charge_min";
    const COL_PAYMENT_CHARGE_MIN   = "payment_charge_min";
    const COL_BILL_WAY    = "bill_way";
    const COL_FACTORING_BANK_CD  = "factoring_bank_cd";
    const COL_FACTORING_ACCOUNT_TYPE  = "factoring_bank_account_type";
    const COL_FACTORING_ACCOUNT_NO  = "factoring_bank_account_no";
    const COL_FACTORING_BANK_ACCOUNT_KN  = "factoring_bank_account_kn";
    const COL_FACTORING_BANK_BRANCH_CD = "factoring_bank_branch_cd";
    const COL_FACTORING_CD = "factoring_cd";
    const COL_BILL_SEND    = "bill_send";
    const COL_PAYMENT_SEND    = "payment_send";
    const COL_FACTORING_SEND  = "factoring_send";
    const COL_FAX_RECIPIENT_CD  = "fax_recipient_cd";
    const COL_OPTIONAL_NM  = "optional_nm";
    const COL_OPTIONAL_SECTION_NM  = "optional_section_nm";
    const COL_OPTIONAL_PERSON_NM  = "optional_person_nm";
    const COL_BILL_ADD_MONTH  = "bill_add_month";
    const COL_BILL_DAY  = "bill_day";
    const COL_PERSON_POST  = "person_post";


    /** コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
    }


    /**
     * 主キーで取得
     */
    public function selectByKey($customer_id)
    {
        $data = $this->where("customer_id", $customer_id)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_CUSTOMER_ID  => $data[0]->customer_id,
                self::COL_CUSTOMER_NM   => $data[0]->customer_nm,
                self::COL_SECTION_NM   => $data[0]->section_nm,
                self::COL_PERSON_NM => $data[0]->person_nm,
                self::COL_POSTAL_CD => $data[0]->postal_cd,
                self::COL_PREF_ID    => $data[0]->pref_id,
                self::COL_ADDRESS    => $data[0]->address,
                self::COL_TEL  => $data[0]->tel,
                self::COL_FAX  => $data[0]->fax,
                self::COL_EMAIL  => $data[0]->email,
                self::COL_BILL_BANK_NM   => $data[0]->bill_bank_nm,
                self::COL_BILL_BANK_ACCOUNT_NO   => $data[0]->bill_bank_account_no,
                self::COL_PAYMENT_BANK_CD => $data[0]->payment_bank_cd,
                self::COL_PAYMENT_BANK_BRANCH_CD => $data[0]->payment_bank_branch_cd,
                self::COL_PAYMENT_BANK_ACCOUNT_TYPE    => $data[0]->payment_bank_account_type,
                self::COL_PAYMENT_BANK_ACCOUNT_NO    => $data[0]->payment_bank_account_no,
                self::COL_PAYMENT_BANK_ACCOUNT_KN  => $data[0]->payment_bank_account_kn,
                self::COL_PAYMENT_REQUIRED_MONTH  => $data[0]->payment_required_month,
                self::COL_BILL_CHARGE_MIN   => $data[0]->bill_charge_min,
                self::COL_PAYMENT_CHARGE_MIN   => $data[0]->payment_charge_min,
                self::COL_BILL_WAY    => $data[0]->bill_way,
                self::COL_FACTORING_BANK_CD  => $data[0]->factoring_bank_cd,
                self::COL_FACTORING_ACCOUNT_TYPE  => $data[0]->factoring_bank_account_type,
                self::COL_FACTORING_ACCOUNT_NO  => $data[0]->factoring_bank_account_no,
                self::COL_FACTORING_BANK_ACCOUNT_KN  => $data[0]->factoring_bank_account_kn,
                self::COL_FACTORING_BANK_BRANCH_CD => $data[0]->factoring_bank_branch_cd,
                self::COL_FACTORING_CD => $data[0]->factoring_cd,
                self::COL_BILL_SEND    => $data[0]->bill_send,
                self::COL_PAYMENT_SEND    => $data[0]->payment_send,
                self::COL_FACTORING_SEND  => $data[0]->factoring_send,
                self::COL_FAX_RECIPIENT_CD  => $data[0]->fax_recipient_cd,
                self::COL_OPTIONAL_NM  => $data[0]->optional_nm,
                self::COL_OPTIONAL_SECTION_NM  => $data[0]->optional_section_nm,
                self::COL_OPTIONAL_PERSON_NM  => $data[0]->optional_person_nm,
                self::COL_BILL_ADD_MONTH  => $data[0]->bill_add_month,
                self::COL_BILL_DAY  => $data[0]->bill_day,
                self::COL_PERSON_POST  => $data[0]->person_post
            ];
        }
        return null;
    }

    // プライマリキーにてデータの取得を行います。
    public function find($aa_conditions)
    {
        // $a_row = parent::find($aa_conditions);
        //親クラスの呼出し？？ではなく、selectByKeyでの取得で問題ないか？
        $a_row = $this->selectByKey($aa_conditions);

        // 支払口座名義（カナ）半角カナ３１文字以降を切り捨てる
        // null追記
        if (!$this->is_empty($a_row['payment_bank_account_kn'] ?? null)) {
            $n_len = mb_strlen(mb_convert_kana(mb_substr(mb_convert_kana($a_row['payment_bank_account_kn'], 'hkas'), 0, 30), 'KVAS'));
            //書き換えあっている？（下も同様） $this->_a_attributes['payment_bank_account_kn'] = mb_substr($a_row['payment_bank_account_kn'], 0, $n_len);
            $a_row['payment_bank_account_kn'] = mb_substr($a_row['payment_bank_account_kn'], 0, $n_len);
        }

        // 引落口座名義（カナ）半角カナ３１文字以降を切り捨てる
        // null追記
        if (!$this->is_empty($a_row['factoring_bank_account_kn'] ?? null)) {
            $n_len = mb_strlen(mb_convert_kana(mb_substr(mb_convert_kana($a_row['factoring_bank_account_kn'], 'hkas'), 0, 30), 'KVAS'));
            $a_row['factoring_bank_account_kn'] = mb_substr($a_row['factoring_bank_account_kn'], 0, $n_len);
        }

        return $a_row;
    }

    // 請求先・支払先施設データ
    //   as_hotel_cd 請求先・支払先施設データの施設番号
    public function getCustomer($as_hotel_cd)
    {
        try {
            $s_sql =
            <<<SQL
					select	customer.customer_id,
							customer.customer_nm
					from	customer,
						(
							select	customer_id
							from	customer_hotel
							where	hotel_cd = :hotel_cd
						) q1
					where	customer.customer_id = q1.customer_id
SQL;

            // データの取得
            $a_row = DB::select($s_sql, ['hotel_cd' => $as_hotel_cd]);

            return [
                'values'     => $a_row,
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
