<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Util\Models_Cipher;

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
    const COL_BILL_REQUIRED_MONTH  = "bill_required_month";
    const COL_PAYMENT_REQUIRED_MONTH  = "payment_required_month";
    const COL_BILL_CHARGE_MIN   = "bill_charge_min";
    const COL_PAYMENT_CHARGE_MIN   = "payment_charge_min";
    const COL_BILL_WAY    = "bill_way";
    const COL_FACTORING_BANK_CD  = "factoring_bank_cd";
    const COL_FACTORING_BANK_ACCOUNT_TYPE  = "factoring_bank_account_type";
    const COL_FACTORING_BANK_ACCOUNT_NO  = "factoring_bank_account_no";
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
    const COL_OPTIONAL_FAX = 'optional_fax';
    const COL_BILL_ADD_MONTH  = "bill_add_month";
    const COL_BILL_DAY  = "bill_day";
    const COL_PERSON_POST  = "person_post";


    /** コンストラクタ
     */
    public function __construct() //publicでいいか？使用しないが削除するとエラー
    {
        // カラム情報の設定
        $colCustomerId = new ValidationColumn();
        $colCustomerId->setColumnName(self::COL_CUSTOMER_ID, '請求先・支払先ID');
        $colCustomerNm = new ValidationColumn();
        $colCustomerNm->setColumnName(self::COL_CUSTOMER_NM, '請求先・支払先名称');
        $colSectionNm = new ValidationColumn();
        $colSectionNm->setColumnName(self::COL_SECTION_NM, '部署名');
        $colPersonNm = new ValidationColumn();
        $colPersonNm->setColumnName(self::COL_PERSON_NM, '担当者名称');
        $colPostalCd = new ValidationColumn();
        $colPostalCd->setColumnName(self::COL_POSTAL_CD, '郵便番号');
        $colPrefId = new ValidationColumn();
        $colPrefId->setColumnName(self::COL_PREF_ID, '都道府県ID');
        $colAddress = new ValidationColumn();
        $colAddress->setColumnName(self::COL_ADDRESS, '住所');
        $colTel = new ValidationColumn();
        $colTel->setColumnName(self::COL_TEL, '電話番号');
        $colFax = new ValidationColumn();
        $colFax->setColumnName(self::COL_FAX, 'ファックス番号');
        $colEmail = new ValidationColumn();
        $colEmail->setColumnName(self::COL_EMAIL, '電子メールアドレス');
        $colBillBankNm = new ValidationColumn();
        $colBillBankNm->setColumnName(self::COL_BILL_BANK_NM, '請求振込銀行'); //名称はフォームとどっちに合わせる？（振込銀行と支店）
        $colBillBankAccountNo = new ValidationColumn();
        $colBillBankAccountNo->setColumnName(self::COL_BILL_BANK_ACCOUNT_NO, '請求振込口座');
        $colPaymentBankCd = new ValidationColumn();
        $colPaymentBankCd->setColumnName(self::COL_PAYMENT_BANK_CD, '支払銀行コード');
        $colPaymentBankBranchCd = new ValidationColumn();
        $colPaymentBankBranchCd->setColumnName(self::COL_PAYMENT_BANK_BRANCH_CD, '支払支店コード');
        $colPaymentBankAccountType = new ValidationColumn();
        $colPaymentBankAccountType->setColumnName(self::COL_PAYMENT_BANK_ACCOUNT_TYPE, '支払口座種別');
        $colPaymentBankAccountNo = new ValidationColumn();
        $colPaymentBankAccountNo->setColumnName(self::COL_PAYMENT_BANK_ACCOUNT_NO, '支払口座番号');
        $colPaymentBankAccountKn = new ValidationColumn();
        $colPaymentBankAccountKn->setColumnName(self::COL_PAYMENT_BANK_ACCOUNT_KN, '支払口座名義（カナ）');
        $colbillRequiredMonth = new ValidationColumn();
        $colbillRequiredMonth->setColumnName(self::COL_BILL_REQUIRED_MONTH, '請求必須月');
        $colPaymentRequiredMonth = new ValidationColumn();
        $colPaymentRequiredMonth->setColumnName(self::COL_PAYMENT_REQUIRED_MONTH, '支払必須月');
        $colBillChargeMin = new ValidationColumn();
        $colBillChargeMin->setColumnName(self::COL_BILL_CHARGE_MIN, '請求最低金額');
        $colPaymentChargeMin = new ValidationColumn();
        $colPaymentChargeMin->setColumnName(self::COL_PAYMENT_CHARGE_MIN, '支払最低金額');
        $colBillWay = new ValidationColumn();
        $colBillWay->setColumnName(self::COL_BILL_WAY, '請求方法');
        $colFactoringBankCd = new ValidationColumn();
        $colFactoringBankCd->setColumnName(self::COL_FACTORING_BANK_CD, '引落銀行コード');
        $colFactoringBankAccountType = new ValidationColumn();
        $colFactoringBankAccountType->setColumnName(self::COL_FACTORING_BANK_ACCOUNT_TYPE, '引落口座種別');
        $colFactoringBankAccountNo = new ValidationColumn();
        $colFactoringBankAccountNo->setColumnName(self::COL_FACTORING_BANK_ACCOUNT_NO, '引落口座番号');
        $colFactoringBankAccountKn = new ValidationColumn();
        $colFactoringBankAccountKn->setColumnName(self::COL_FACTORING_BANK_ACCOUNT_KN, '引落口座名義（カナ）');
        $colFactoringBankBranchCd = new ValidationColumn();
        $colFactoringBankBranchCd->setColumnName(self::COL_FACTORING_BANK_BRANCH_CD, '引落支店コード');
        $colFactoringCd = new ValidationColumn();
        $colFactoringCd->setColumnName(self::COL_FACTORING_CD, '引落顧客番号');
        $colBillSend = new ValidationColumn();
        $colBillSend->setColumnName(self::COL_BILL_SEND, '発送方法（請求書）');
        $colPaymentSend = new ValidationColumn();
        $colPaymentSend->setColumnName(self::COL_PAYMENT_SEND, '発送方法（支払通知書）');
        $colFactoringSend = new ValidationColumn();
        $colFactoringSend->setColumnName(self::COL_FACTORING_SEND, '発送方法（引落通知書）');
        $colFaxRecipientCd = new ValidationColumn();
        $colFaxRecipientCd->setColumnName(self::COL_FAX_RECIPIENT_CD, 'FAX通知先');
        $colOptionalNm = new ValidationColumn();
        $colOptionalNm->setColumnName(self::COL_OPTIONAL_NM, '任意宛先名称（施設・会社名）');
        $colOptionalSectionNm = new ValidationColumn();
        $colOptionalSectionNm->setColumnName(self::COL_OPTIONAL_SECTION_NM, '任意役職（部署名）');
        $colOptionalPersonNm = new ValidationColumn();
        $colOptionalPersonNm->setColumnName(self::COL_OPTIONAL_PERSON_NM, '任意担当者名称');
        $colOptionalFax = new ValidationColumn();
        $colOptionalFax->setColumnName(self::COL_OPTIONAL_FAX, '任意宛先名称（施設・会社名）');
        $colBillAddMonth = new ValidationColumn();
        $colBillAddMonth->setColumnName(self::COL_BILL_ADD_MONTH, '振込予定月');
        $colBillDay = new ValidationColumn();
        $colBillDay->setColumnName(self::COL_BILL_DAY, '振込予定日');
        $colPersonPost = new ValidationColumn();
        $colPersonPost->setColumnName(self::COL_PERSON_POST, '担当者部署名');




        // バリデーション追加
        // 支払先ID
        $colCustomerId->require();      // 必須入力チェック
        $colCustomerId->length(0, 10);  // 長さチェック
        $colCustomerId->intOnly();         // 数字：数値チェック

        // 請求先・支払先名称
        $colCustomerNm->require();      // 必須入力チェック
        $colCustomerNm->notHalfKana();  // 半角カナチェック
        $colCustomerNm->length(0, 50);  // 長さチェック

        // 部署名
        $colSectionNm->notHalfKana();  // 半角カナチェック
        $colSectionNm->length(0, 50);  // 長さチェック

        // 担当者名称
        $colPersonNm->notHalfKana();  // 半角カナチェック
        $colPersonNm->length(0, 32);  // 長さチェック

        // 郵便番号
        $colPostalCd->require();      // 必須入力チェック
        $colPostalCd->notHalfKana();  // 半角カナチェック
        $colPostalCd->length(0, 8);  // 長さチェック
        $colPostalCd->postal(); // 郵便番号チェック

        // 都道府県ID
        $colPrefId->require();      // 必須入力チェック
        $colPrefId->length(0, 2);  // 長さチェック
        $colPrefId->intOnly();         // 数字：数値チェック

        // 住所
        $colAddress->require();      // 必須入力チェック
        $colAddress->notHalfKana();  // 半角カナチェック
        $colAddress->length(0, 100);  // 長さチェック

        // 電話番号
        $colTel->require();      // 必須入力チェック
        $colTel->notHalfKana();  // 半角カナチェック
        $colTel->length(0, 15);  // 長さチェック
        $colTel->phoneNumber(); // 電話番号チェック

        // ファックス番号
        $colFax->notHalfKana();  // 半角カナチェック
        $colFax->length(0, 15);  // 長さチェック
        $colFax->phoneNumber(); // 電話番号チェック

        // 電子メールアドレス
        $colEmail->notHalfKana();  // 半角カナチェック
        $colEmail->length(0, 200);  // 長さチェック
        $colEmail->emails(); // メールアドレスチェック

        // 請求方法
        $colBillWay->length(0, 1);  // 長さチェック
        $colBillWay->intOnly();         // 数字：数値チェック

        // 請求振込銀行
        $colBillBankNm->require();      // 必須入力チェック
        $colBillBankNm->notHalfKana();  // 半角カナチェック
        $colBillBankNm->length(0, 50);  // 長さチェック

        // 請求振込口座
        $colBillBankAccountNo->notHalfKana();  // 半角カナチェック
        $colBillBankAccountNo->length(0, 20);  // 長さチェック
            //要追加 // 独自チェック

        // 引落銀行コード
        $colFactoringBankCd->require();      // 必須入力チェック
        $colFactoringBankCd->length(0, 4);  // 長さチェック
        $colFactoringBankCd->intOnly();         // 数字：数値チェック

        // 引落支店コード
        $colFactoringBankBranchCd->require();      // 必須入力チェック
        $colFactoringBankBranchCd->length(0, 3);  // 長さチェック
        $colFactoringBankBranchCd->intOnly();         // 数字：数値チェック

        // 引落口座種別
        $colFactoringBankAccountType->length(0, 1);  // 長さチェック
        $colFactoringBankAccountType->intOnly();         // 数字：数値チェック

        // 引落口座番号
        $colFactoringBankAccountNo->require();      // 必須入力チェック
        $colFactoringBankAccountNo->length(0, 7);  // 長さチェック
        $colFactoringBankAccountNo->intOnly();         // 数字：数値チェック

        // 引落口座名義（カナ）
        $colFactoringBankAccountKn->notHalfKana();  // 半角カナチェック
        $colFactoringBankAccountKn->length(0, 30);  // 長さチェック

        // 引落顧客番号
        $colFactoringCd->notHalfKana();  // 半角カナチェック
        $colFactoringCd->length(0, 12);  // 長さチェック

        // 支払銀行コード
        $colPaymentBankCd->notHalfKana();  // 半角カナチェック
        $colPaymentBankCd->length(0, 4);  // 長さチェック
        $colPaymentBankCd->intOnly();         // 数字：数値チェック

        // 支払支店コード
        $colPaymentBankBranchCd->notHalfKana();  // 半角カナチェック
        $colPaymentBankBranchCd->length(0, 3);  // 長さチェック
        $colPaymentBankBranchCd->intOnly();         // 数字：数値チェック

        // 支払口座種別
        $colPaymentBankAccountType->length(0, 1);  // 長さチェック
        $colPaymentBankAccountType->intOnly();         // 数字：数値チェック

        // 支払口座番号
        $colPaymentBankAccountNo->notHalfKana();  // 半角カナチェック
        $colPaymentBankAccountNo->length(0, 7);  // 長さチェック
        $colPaymentBankAccountNo->intOnly();         // 数字：数値チェック

        // 支払口座名義（カナ）
        $colPaymentBankAccountKn->notHalfKana();  // 半角カナチェック
        $colPaymentBankAccountKn->length(0, 30);  // 長さチェック

        // 請求必須月
        $colbillRequiredMonth->require();      // 必須入力チェック（コントローラ側で設定後に登録なので未入力はなし）
        $colbillRequiredMonth->notHalfKana();  // 半角カナチェック
        $colbillRequiredMonth->length(0, 12);  // 長さチェック

        // 支払必須月
        $colPaymentRequiredMonth->require();      // 必須入力チェック（コントローラ側で設定後に登録なので未入力はなし）
        $colPaymentRequiredMonth->notHalfKana();  // 半角カナチェック
        $colPaymentRequiredMonth->length(0, 12);  // 長さチェック

        // 請求最低金額
        $colBillChargeMin->length(0, 5);  // 長さチェック
        $colBillChargeMin->intOnly();     // 数字：数値チェック
            //要追加 // 独自チェック

        // 支払最低金額
        $colPaymentChargeMin->length(0, 5);  // 長さチェック
        $colPaymentChargeMin->intOnly();     // 数字：数値チェック
            //要追加 // 独自チェック

        // 発送方法（請求書）
        $colBillSend->length(0, 1);  // 長さチェック
        $colBillSend->intOnly();     // 数字：数値チェック

        // 発送方法（支払通知書）
        $colPaymentSend->length(0, 1);  // 長さチェック
        $colPaymentSend->intOnly();     // 数字：数値チェック

        // 発送方法（引落通知書）
        $colFactoringSend->length(0, 1);  // 長さチェック
        $colFactoringSend->intOnly();     // 数字：数値チェック

        // FAX通知先
        $colFaxRecipientCd->length(0, 1);  // 長さチェック
        $colFaxRecipientCd->intOnly();     // 数字：数値チェック

        // 任意宛先名称（施設・会社名）
        $colOptionalNm->notHalfKana();  // 半角カナチェック
        $colOptionalNm->length(0, 50);  // 長さチェック
            //要追加 // 独自チェック

        // 任意役職（部署名）
        $colOptionalSectionNm->notHalfKana();  // 半角カナチェック
        $colOptionalSectionNm->length(0, 25);  // 長さチェック

        // 任意担当者名称
        $colOptionalPersonNm->notHalfKana();  // 半角カナチェック
        $colOptionalPersonNm->length(0, 32);  // 長さチェック

        // 任意ファックス番号
        $colOptionalFax->notHalfKana();  // 半角カナチェック
        $colOptionalFax->length(0, 15);  // 長さチェック
        $colOptionalFax->phoneNumber(); // 電話番号チェック
            //要追加 // 独自チェック

        // 振込予定月
        $colBillAddMonth->length(0, 1);  // 長さチェック
        $colBillAddMonth->intOnly();     // 数字：数値チェック

        // 振込予定日
        $colBillDay->length(0, 2);  // 長さチェック
        $colBillDay->intOnly();     // 数字：数値チェック

        // 担当者部署名
        $colPersonPost->notHalfKana();  // 半角カナチェック
        $colPersonPost->length(0, 90);  // 長さチェック

        parent::setColumnDataArray([
            $colCustomerId,$colCustomerNm,$colSectionNm,$colPersonNm,$colPostalCd,$colPrefId,$colAddress,$colTel,
            $colFax,$colEmail,$colBillWay,$colBillBankNm,$colBillBankAccountNo,$colFactoringBankCd,
            $colFactoringBankBranchCd,$colFactoringBankAccountType,$colFactoringBankAccountNo,
            $colFactoringBankAccountKn,$colFactoringCd,$colPaymentBankCd,$colPaymentBankBranchCd,
            $colPaymentBankAccountType,$colPaymentBankAccountNo,$colPaymentBankAccountKn,$colbillRequiredMonth,
            $colPaymentRequiredMonth,$colBillChargeMin,$colPaymentChargeMin,$colBillSend,$colPaymentSend,
            $colFactoringSend,$colFaxRecipientCd,$colOptionalNm,$colOptionalSectionNm,$colOptionalPersonNm,
            $colOptionalFax,$colBillAddMonth,$colBillDay,$colPersonPost
        ]);
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
                self::COL_BILL_REQUIRED_MONTH  => $data[0]->bill_required_month,
                self::COL_PAYMENT_REQUIRED_MONTH  => $data[0]->payment_required_month,
                self::COL_BILL_CHARGE_MIN   => $data[0]->bill_charge_min,
                self::COL_PAYMENT_CHARGE_MIN   => $data[0]->payment_charge_min,
                self::COL_BILL_WAY    => $data[0]->bill_way,
                self::COL_FACTORING_BANK_CD  => $data[0]->factoring_bank_cd,
                self::COL_FACTORING_BANK_ACCOUNT_TYPE  => $data[0]->factoring_bank_account_type,
                self::COL_FACTORING_BANK_ACCOUNT_NO  => $data[0]->factoring_bank_account_no,
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
                self::COL_OPTIONAL_FAX  => $data[0]->optional_fax,
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

    // 請求先・支払先を検索
    //
    //  aa_conditions
    //    pref_id      都道府県ID
    //    keywords     キーワード
    public function search($aa_conditions = null)
    {
        try {
            // キーワード
            // if(is_empty($aa_conditions['keywords'])){
            //  throw new Exception('キーワードを設定してください。');
            // }
            $a_conditions = [];

            //初期化
            $s_customer_id = '';
            $s_pref_id = '';
            $s_keyword = '';

            // キーワードの設定　（keyword1は完全一致、keyword2は全後方一致、keyword3は電話＆FAX対応）
            if (!$this->is_empty($aa_conditions['keywords'] ?? null)) { //null追記でいいか
                $a_conditions['keyword11'] = trim($aa_conditions['keywords']);
                $a_conditions['keyword12'] = trim($aa_conditions['keywords']);
                $a_conditions['keyword13'] = trim($aa_conditions['keywords']);
                $a_conditions['keyword2'] = '%' . trim($aa_conditions['keywords']) . '%';
                $a_conditions['keyword31'] = '%' . trim(str_replace('-', '', $aa_conditions['keywords'])) . '%';
                $a_conditions['keyword32'] = '%' . trim(str_replace('-', '', $aa_conditions['keywords'])) . '%';

                // keywordsが数値だった場合のみcustomer_idを検索対象へ
                if (preg_match('/^[0-9]+$/', trim($aa_conditions['keywords']))) {
                    $s_customer_id = 'or customer_id = :keyword4';
                    $a_conditions['keyword4'] = trim($aa_conditions['keywords']);
                }

                $s_keyword =  //同じパラメータ使えないため:keyword部分修正
                <<<SQL
					 		and	(customer_nm                    like :keyword2 or
								bill_bank_account_no            =    :keyword11 or
								factoring_bank_account_no       =    :keyword12 or
								factoring_cd                    =    :keyword13 or
								replace(tel,       '-', '')     like :keyword31 or
								replace(fax,       '-', '')     like :keyword32
								{$s_customer_id})
SQL;
            }

            // 都道府県ID
            if (!($this->is_empty($aa_conditions['pref_id'] ?? null))) { //null追記でいいか
                $s_pref_id = '	and	pref_id = :pref_id';
                $a_conditions['pref_id'] = $aa_conditions['pref_id'];
            }

            $s_sql =
            <<<SQL
					select	customer.customer_id,
							customer.customer_nm,
							customer.section_nm,
							customer.person_nm,
							customer.postal_cd,
							customer.pref_id,
							mast_pref.pref_cd,
							mast_pref.pref_nm,
							customer.address,
							customer.tel,
							customer.fax,
							customer.email,
							customer.bill_way,
							case when customer.bill_way = 0 then '振込'
							     when customer.bill_way = 1 then '引落'
							end as bill_way_nm,
							customer.bill_bank_nm,
							customer.bill_bank_account_no,
							customer.factoring_bank_cd,
							factoring_bank.bank_nm as factoring_bank_nm,
							factoring_bank.bank_kn as factoring_bank_kn ,
							customer.factoring_bank_branch_cd,
							factoring_bank_branch.bank_branch_nm as factoring_bank_branch_nm,
							factoring_bank_branch.bank_branch_kn as factoring_bank_branch_kn,
							customer.factoring_bank_account_type,
							case when customer.factoring_bank_account_type = 1 then '普通'
								 when customer.factoring_bank_account_type = 2 then '当座'
							end as factoring_bank_account_type_nm,
							customer.factoring_bank_account_no,
							customer.factoring_bank_account_kn,
							customer.factoring_cd,
							customer.payment_bank_cd,
							payment_bank.bank_nm as payment_bank_nm,
							payment_bank.bank_kn as payment_bank_kn,
							customer.payment_bank_branch_cd,
							payment_bank_branch.bank_branch_nm as payment_bank_branch_nm,
							payment_bank_branch.bank_branch_kn as payment_bank_branch_kn,
							customer.payment_bank_account_type,
							case when customer.payment_bank_account_type = 1 then '普通'
								 when customer.payment_bank_account_type = 2 then '当座'
							end as payment_bank_account_type_nm,
							customer.payment_bank_account_no,
							customer.payment_bank_account_kn,
							customer.bill_required_month,
							case when substr(customer.bill_required_month,  1, 1) = 1 then '1' else '0' end as bill_01,
							case when substr(customer.bill_required_month,  2, 1) = 1 then '1' else '0' end as bill_02,
							case when substr(customer.bill_required_month,  3, 1) = 1 then '1' else '0' end as bill_03,
							case when substr(customer.bill_required_month,  4, 1) = 1 then '1' else '0' end as bill_04,
							case when substr(customer.bill_required_month,  5, 1) = 1 then '1' else '0' end as bill_05,
							case when substr(customer.bill_required_month,  6, 1) = 1 then '1' else '0' end as bill_06,
							case when substr(customer.bill_required_month,  7, 1) = 1 then '1' else '0' end as bill_07,
							case when substr(customer.bill_required_month,  8, 1) = 1 then '1' else '0' end as bill_08,
							case when substr(customer.bill_required_month,  9, 1) = 1 then '1' else '0' end as bill_09,
							case when substr(customer.bill_required_month, 10, 1) = 1 then '1' else '0' end as bill_10,
							case when substr(customer.bill_required_month, 11, 1) = 1 then '1' else '0' end as bill_11,
							case when substr(customer.bill_required_month, 12, 1) = 1 then '1' else '0' end as bill_12,
							customer.payment_required_month,
							case when substr(customer.payment_required_month,  1, 1) = 1 then '1' else '0' end as payment_01,
							case when substr(customer.payment_required_month,  2, 1) = 1 then '1' else '0' end as payment_02,
							case when substr(customer.payment_required_month,  3, 1) = 1 then '1' else '0' end as payment_03,
							case when substr(customer.payment_required_month,  4, 1) = 1 then '1' else '0' end as payment_04,
							case when substr(customer.payment_required_month,  5, 1) = 1 then '1' else '0' end as payment_05,
							case when substr(customer.payment_required_month,  6, 1) = 1 then '1' else '0' end as payment_06,
							case when substr(customer.payment_required_month,  7, 1) = 1 then '1' else '0' end as payment_07,
							case when substr(customer.payment_required_month,  8, 1) = 1 then '1' else '0' end as payment_08,
							case when substr(customer.payment_required_month,  9, 1) = 1 then '1' else '0' end as payment_09,
							case when substr(customer.payment_required_month, 10, 1) = 1 then '1' else '0' end as payment_10,
							case when substr(customer.payment_required_month, 11, 1) = 1 then '1' else '0' end as payment_11,
							case when substr(customer.payment_required_month, 12, 1) = 1 then '1' else '0' end as payment_12,
							customer.bill_charge_min,
							customer.payment_charge_min,
							ifNull(customer.bill_add_month,0) as bill_add_month,
							ifNull(customer.bill_day,99) as bill_day,
							customer.entry_ts as entry_ts, -- 書き換えあっているか
							customer.modify_ts as modify_ts -- 書き換えあっているか
					from	customer,
							mast_bank        payment_bank,
							mast_bank_branch payment_bank_branch,
							mast_bank        factoring_bank,
							mast_bank_branch factoring_bank_branch,
							mast_pref
					where	customer.payment_bank_cd          = payment_bank.bank_cd -- (+)は削除でいい？
						and	customer.payment_bank_cd          = payment_bank_branch.bank_cd -- (+)は削除でいい？
						and	customer.payment_bank_branch_cd   = payment_bank_branch.bank_branch_cd -- (+)は削除でいい？
						and	customer.factoring_bank_cd        = factoring_bank.bank_cd -- (+)は削除でいい？
						and	customer.factoring_bank_cd        = factoring_bank_branch.bank_cd -- (+)は削除でいい？
						and	customer.factoring_bank_branch_cd = factoring_bank_branch.bank_branch_cd -- (+)は削除でいい？
						and	customer.pref_id = mast_pref.pref_id -- (+)は削除でいい？
						{$s_keyword}
						{$s_pref_id}
					order by customer_id
SQL;

            // $o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);
            $cipher = new Models_Cipher(config('settings.cipher_key'));

            // データの取得
            $a_result = DB::select($s_sql, $a_conditions);

            for ($n_cnt = 0; $n_cnt < count($a_result); $n_cnt++) {
                // メールアドレス暗号化解除
                if (!$this->is_empty($a_result[$n_cnt]->email)) {
                    try {
                        $a_result[$n_cnt]->email = $cipher->decrypt($a_result[$n_cnt]->email);
                        // 各メソッドで Exception が投げられた場合
                    } catch (Exception $e) {
                        true;
                    }
                }
            }
            return [
                'values'    => $a_result,
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 請求先・支払先の 請求先・支払先IDを取得
    public function getSequenceNo()
    {
        try {
            $s_sql =
            <<<SQL
					select	ifNull((max(customer_id) + 1), 1) as customer_id
					from	customer
SQL;

            $a_customer = DB::select($s_sql, []);

            return $a_customer[0]->customer_id;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**  新規登録
     *
     * @param [type] $con
     * @param [type] $data
     * @return
     */
    public function insert($con, $data)
    {
        $result = $con->table($this->table)->insert($data);
        return  $result;
    }

    /**  キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByKey($con, $data)
    {
        $result = $con->table($this->table)->where(self::COL_CUSTOMER_ID, $data[self::COL_CUSTOMER_ID])->update($data);
        if (!$result) {
            return "更新に失敗しました";
        }
        return "";
    }

    //======================================================================
    // CSVヘッダー設定 ※モデルへの記述でいい？
    //======================================================================
    public function setCsvHeader($customer)
    {
        $header = [
            "請求連番","精算先名称","役職（部署名）",
            "担当者","郵便番号",
            "都道府県",
            "住所","電話番号","ファックス番号","E-Mail",
            "請求方法",
            "請求銀行 振込銀行と支店","請求銀行 振込口座",
            "引落銀行 銀行コード","引落銀行 銀行名称",
            "引落銀行 銀行名称かな","引落銀行 支店コード","引落銀行 支店名称",
            "引落銀行 支店名称かな",
            "引落口座種別",
            "引落口座番号","引落口座名義",
            "引落顧客番号","支払銀行 銀行コード","支払銀行 銀行名称",
            "支払銀行 銀行名称かな","支払銀行 支店コード","支払銀行 支店名称",
            "支払銀行 支店名称かな",
            "支払口座種別",
            "支払口座番号","支払口座名義",
            "請求必須月 ４月(1:必須）","請求必須月 ５月(1:必須）","請求必須月 ６月(1:必須）","請求必須月 ７月(1:必須）",
            "請求必須月 ８月(1:必須）","請求必須月 ９月(1:必須）","請求必須月 10月(1:必須）","請求必須月 11月(1:必須）",
            "請求必須月 12月(1:必須）","請求必須月 １月(1:必須）","請求必須月 ２月(1:必須）","請求必須月 ３月(1:必須）",
            "支払必須月 ４月(1:必須）","支払必須月 ５月(1:必須）","支払必須月 ６月(1:必須）","支払必須月 ７月(1:必須）",
            "支払必須月 ８月(1:必須）","支払必須月 ９月(1:必須）","支払必須月 10月(1:必須）","支払必須月 11月(1:必須）",
            "支払必須月 12月(1:必須）","支払必須月 １月(1:必須）","支払必須月 ２月(1:必須）","支払必須月 ３月(1:必須）",
            "請求最低金額","支払最低金額","振込予定月(0:請求書発行月　1:請求書発行月の翌月　2:請求書発行月の翌々月)",
            "振込予定日(5:5日　10:10日　15:15日　20:20日　25:25日　99:月末)"
        ];

        return $header;
    }

    //======================================================================
    // CSVデータ設定 ※モデルへの記述でいい？
    //======================================================================
    public function setCsvData($customers)
    {
        $data = [];
        foreach ($customers['values'] as $customer) {
            //初期化
            $string = [
                $customer->customer_id, $customer->customer_nm, $customer->section_nm,
                $customer->person_nm, $customer->postal_cd,
                $customer->pref_cd . ' ' . $customer->pref_nm,
                $customer->address, $customer->tel, $customer->fax, $customer->email,
                $customer->bill_way . ' ' . $customer->bill_way_nm,
                $customer->bill_bank_nm, $customer->bill_bank_account_no,
                $customer->factoring_bank_cd, $customer->factoring_bank_nm,
                $customer->factoring_bank_kn, $customer->factoring_bank_branch_cd, $customer->factoring_bank_branch_nm,
                $customer->factoring_bank_branch_kn,
                $customer->factoring_bank_account_type . ' ' . $customer->factoring_bank_account_type_nm,
                $customer->factoring_bank_account_no, $customer->factoring_bank_account_kn,
                $customer->factoring_cd, $customer->payment_bank_cd, $customer->payment_bank_nm,
                $customer->payment_bank_kn, $customer->payment_bank_branch_cd, $customer->payment_bank_branch_nm,
                $customer->payment_bank_branch_kn,
                $customer->payment_bank_account_type . ' ' . $customer->payment_bank_account_type_nm,
                $customer->payment_bank_account_no, $customer->payment_bank_account_kn,
                $customer->bill_04, $customer->bill_05, $customer->bill_06, $customer->bill_07,
                $customer->bill_08, $customer->bill_09, $customer->bill_10, $customer->bill_11,
                $customer->bill_12, $customer->bill_01, $customer->bill_02, $customer->bill_03,
                $customer->payment_04, $customer->payment_05, $customer->payment_06, $customer->payment_07,
                $customer->payment_08, $customer->payment_09, $customer->payment_10, $customer->payment_11,
                $customer->payment_12, $customer->payment_01, $customer->payment_02, $customer->payment_03,
                $customer->bill_charge_min, $customer->payment_charge_min, $customer->bill_add_month,
                $customer->bill_day
            ];

            $data[] = $string;
        }

        return $data;
    }
}
