<?php

namespace App\Services;

use App\Models\DenyList;
use App\Models\Hotel;
use App\Models\HotelAccount;
use App\Models\HotelControl;
use App\Models\HotelInsuranceWeather;
use App\Models\HotelNotify;
use App\Models\HotelPerson;
use App\Models\HotelStatus;
use App\Models\HotelSystemVersion;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;

class BrCustomerService
{
    /**
     * customer に登録するデータを整形
     * billpay登録のために整形後のデータ設定が必要なため
     * @param array $a_customer
     * @return array
     */
    public function makeCustomerData($a_customer) //結局整形は必要そうだが、service新規追加でいいか？
    {
        return [
            'customer_id' => $a_customer['customer_id'],
            'customer_nm' => $a_customer['customer_nm'],
            'section_nm' => $a_customer['section_nm'] ?? null,
            'person_post' => $a_customer['person_post'] ?? null,
            'person_nm' => $a_customer['person_nm'] ?? null,
            'postal_cd' => $a_customer['postal_cd'],
            'pref_id' => $a_customer['pref_id'],
            'address' => $a_customer['address'],
            'tel' => $a_customer['tel'],
            'fax' => $a_customer['fax'] ?? null,
            'email' => $a_customer['email'] ?? null,
            'bill_bank_nm' => $a_customer['bill_bank_nm'],
            'bill_bank_account_no' => $a_customer['bill_bank_account_no'] ?? null,
            'payment_bank_cd' => $a_customer['payment_bank_cd'] ?? null,
            'payment_bank_branch_cd' => $a_customer['payment_bank_branch_cd'] ?? null,
            'payment_bank_account_type' => $a_customer['payment_bank_account_type'] ?? null,
            'payment_bank_account_no' => $a_customer['payment_bank_account_no'] ?? null,
            'payment_bank_account_kn' => $a_customer['payment_bank_account_kn'] ?? null,
            'bill_required_month' => $a_customer['bill_required_month'],
            'payment_required_month' => $a_customer['payment_required_month'],
            'bill_charge_min' => $a_customer['bill_charge_min'] ?? null,
            'payment_charge_min' => $a_customer['payment_charge_min'] ?? null,
            'bill_send' => $a_customer['bill_send'] ?? null,//追記
            'payment_send' => $a_customer['payment_send'] ?? null,//追記
            'factoring_send' => $a_customer['factoring_send'] ?? null,//追記
            'bill_way' => $a_customer['bill_way'] ?? null,
            'factoring_cd' => $a_customer['factoring_cd'] ?? null,//追記
            'factoring_bank_cd' => $a_customer['factoring_bank_cd'],
            'factoring_bank_branch_cd' => $a_customer['factoring_bank_branch_cd'],
            'factoring_bank_account_type' => $a_customer['factoring_bank_account_type'] ?? null,
            'factoring_bank_account_no' => $a_customer['factoring_bank_account_no'],
            'factoring_bank_account_kn' => $a_customer['factoring_bank_account_kn'] ?? null,
            'fax_recipient_cd' => $a_customer['fax_recipient_cd'] ?? null,
            'optional_nm' => $a_customer['optional_nm'] ?? null,
            'optional_section_nm' => $a_customer['optional_section_nm'] ?? null,
            'optional_person_nm' => $a_customer['optional_person_nm'] ?? null,
            'optional_fax' => $a_customer['optional_fax'] ?? null,
            'bill_day' => $a_customer['bill_day'] ?? null, //追記
            'bill_add_month' => $a_customer['bill_add_month'] ?? null, //追記
        ];
    }
}
