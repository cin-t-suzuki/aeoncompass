<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    EmailSingle,
    PhoneNumber,
    PostalCode,
    WithoutHalfWidthKatakana
};
use App\Common\Traits;

class CustomerRequest extends FormRequest
{
    use Traits;

    protected function prepareForValidation()
    {
        // バリデーション用に請求月を整形
        $bill_required_month = ''; //初期値追記でいいか
        for ($i = 1; $i <= 12; $i++) {
            // 1月から１２月分 の１２桁の01の文字列、1が立ってる桁が請求月になります。（例 ４月請求 = 000100000000)
            $bill_required_month .= ($this->customer['bill_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] ?? '0');
        }

        // バリデーション用に支払月を整形
        $payment_required_month = ''; //初期値追記でいいか
        for ($i = 1; $i <= 12; $i++) {
            // 1月から１２月分 の１２桁の01の文字列、1が立ってる桁が支払月になります。（例 ４月支払 = 000100000000)
            $payment_required_month .= ($this->customer['payment_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] ?? '0');
        }

        $this->merge([
            'bill_required_month' => $bill_required_month,
            'payment_required_month' => $payment_required_month
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $inputBillWay = $this->input('customer.bill_way');
        $inputbBillRequiredMonth = $this->input('bill_required_month');
        $inputpaymentRequiredMonth = $this->input('payment_required_month');
        $inputFaxRecipientCd = $this->input('customer.fax_recipient_cd');
        return [ //intger指定の場合はbetweenは桁数ではなく文字の大きさ自体になる
            'customer.customer_id'                 => ['required', 'integer', 'digits_between:0,10'],
            'customer.customer_nm'                 => ['required', 'between:0,50', new WithoutHalfWidthKatakana()],
            'customer.section_nm'                  => ['between:0,25', new WithoutHalfWidthKatakana()],
            'customer.person_nm'                   => ['between:0,32', new WithoutHalfWidthKatakana()],
            'customer.postal_cd'                   => ['required','between:0,8', new PostalCode(), new WithoutHalfWidthKatakana()],
            'customer.pref_id'                     => ['required', 'integer', 'digits_between:0,2'],
            'customer.address'                     => ['required','between:0,100', new WithoutHalfWidthKatakana()],
            'customer.tel'                         => ['required','between:0,15', new PhoneNumber(), new WithoutHalfWidthKatakana()],
            'customer.fax'                         => ['between:0,15', new PhoneNumber(), new WithoutHalfWidthKatakana()],
            'customer.email'                       => ['between:0,200', new WithoutHalfWidthKatakana(), new EmailSingle()],
            'customer.bill_way'                    => ['integer', 'digits_between:0,1'],
            'customer.bill_bank_nm'                => ['required','between:0,50', new WithoutHalfWidthKatakana()],
            'customer.bill_bank_account_no'        => ['between:0,20', new WithoutHalfWidthKatakana(),
                // 請求振込口座
                function ($attribute, $value, $fail) use ($inputBillWay) {
                    if ($inputBillWay == 0 && $this->is_empty($value)) {
                        $fail('請求方法が振込の場合は、:attributeを必ず入力してください。');
                    }
                }
            ],
            'customer.factoring_bank_cd'           => ['numeric', 'digits_between:0,4', new WithoutHalfWidthKatakana()], //integerだとバリデーションエラー
            'customer.factoring_bank_branch_cd'    => ['numeric', 'digits_between:0,3', new WithoutHalfWidthKatakana()], //integerだとバリデーションエラー
            'customer.factoring_bank_account_type' => ['integer', 'digits_between:0,1'],
            'customer.factoring_bank_account_no'   => ['integer', 'digits_between:0,7', new WithoutHalfWidthKatakana()],
            'customer.factoring_bank_account_kn'   => ['between:0,30', new WithoutHalfWidthKatakana()],
            'customer.factoring_cd'                => ['between:0,12', new WithoutHalfWidthKatakana()],
            'customer.payment_bank_cd'             => ['numeric', 'digits_between:0,4', new WithoutHalfWidthKatakana()], //integerだとバリデーションエラー
            'customer.payment_bank_branch_cd'      => ['numeric', 'digits_between:0,3', new WithoutHalfWidthKatakana()], //integerだとバリデーションエラー
            'customer.payment_bank_account_type'   => ['between:0,1'],
            'customer.payment_bank_account_no'     => ['integer', 'digits_between:0,7', new WithoutHalfWidthKatakana()],
            'customer.payment_bank_account_kn'     => ['between:0,30', new WithoutHalfWidthKatakana()],
            'bill_required_month'                  => ['required','between:0,12', new WithoutHalfWidthKatakana()], // 整形済
            'payment_required_month'               => ['required','between:0,12', new WithoutHalfWidthKatakana()], // 整形済
            'customer.bill_charge_min'             => [
                'integer', 'digits_between:0,5',
                // 請求最低金額
                function ($attribute, $value, $fail) use ($inputbBillRequiredMonth) {
                    if ($this->is_empty($value) && $inputbBillRequiredMonth == '000000000000') {
                        $fail(':attributeが空欄の場合は、請求必須月を必ず指定してください。');
                    }
                }
            ],
            'customer.payment_charge_min'          => [
                'integer', 'digits_between:0,5',
                // 支払最低金額
                function ($attribute, $value, $fail) use ($inputpaymentRequiredMonth) {
                    if ($this->is_empty($value) && $inputpaymentRequiredMonth == '000000000000') {
                        $fail(':attributeが空欄の場合は、支払必須月を必ず指定してください。');
                    }
                }

            ],
            'customer.bill_send'                   => ['integer', 'digits_between:0,1'],
            'customer.payment_send'                => ['integer', 'digits_between:0,1'],
            'customer.factoring_send'              => ['integer', 'digits_between:0,1'],
            'customer.fax_recipient_cd'            => ['integer', 'digits_between:0,1'],
            'customer.optional_nm'                 => [
                'between:0,50', new WithoutHalfWidthKatakana(),
                // 任意宛先名称（施設・会社名）
                function ($attribute, $value, $fail) use ($inputFaxRecipientCd) {
                    if ($inputFaxRecipientCd == 2 && $this->is_empty($value)) {
                        $fail('FAX通知先が任意宛先の場合は、:attributeを必ず入力してください。');
                    }
                }
            ],
            'customer.optional_section_nm'         => ['between:0,25', new WithoutHalfWidthKatakana()],
            'customer.optional_person_nm'          => ['between:0,32', new WithoutHalfWidthKatakana()],
            'customer.optional_fax'                => [
                'between:0,15', new PhoneNumber(), new WithoutHalfWidthKatakana(),
                // 任意ファックス番号
                function ($attribute, $value, $fail) use ($inputFaxRecipientCd) {
                    if ($inputFaxRecipientCd == 2 && $this->is_empty($value)) {
                        $fail('FAX通知先が任意宛先の場合は、:attributeを必ず入力してください。');
                    }
                }
            ],
            'customer.bill_add_month'              => ['integer', 'digits_between:0,1'],
            'customer.bill_day'                    => ['integer', 'digits_between:0,2'],
            'customer.person_post'                 => ['between:0,90', new WithoutHalfWidthKatakana()]
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'customer.customer_id'                 => '請求連番', // 請求先・支払先IDから変更
            'customer.customer_nm'                 => '精算先名称', // 請求先・支払先名称から変更
            'customer.section_nm'                  => '請求書宛名', // 部署名から変更
            'customer.person_post'                 => '担当者役職・部署名', // 担当者部署名から変更
            'customer.person_nm'                   => '担当者', // 担当者名称から変更
            'customer.postal_cd'                   => '郵便番号',
            'customer.pref_id'                     => '都道府県', // 都道府県IDから変更
            'customer.address'                     => '住所',
            'customer.tel'                         => '電話番号',
            'customer.fax'                         => 'ファックス番号',
            'customer.email'                       => 'E-Mail', // 電子メールアドレスから変更
            'customer.bill_way'                    => '請求方法',
            'customer.bill_bank_nm'                => '請求振込銀行と支店', // 請求振込銀行から変更
            'customer.bill_bank_account_no'        => '請求振込口座',
            'customer.factoring_bank_cd'           => '引落銀行コード',
            'customer.factoring_bank_branch_cd'    => '引落支店コード',
            'customer.factoring_bank_account_type' => '引落口座種別',
            'customer.factoring_bank_account_no'   => '引落口座番号',
            'customer.factoring_bank_account_kn'   => '引落口座名義（カナ）',
            'customer.factoring_cd'                => '引落顧客番号',
            'customer.payment_bank_cd'             => '支払銀行コード',
            'customer.payment_bank_branch_cd'      => '支払支店コード',
            'customer.payment_bank_account_type'   => '支払口座種別',
            'customer.payment_bank_account_no'     => '支払口座番号',
            'customer.payment_bank_account_kn'     => '支払口座名義（カナ）',
            'customer.bill_required_month'         => '請求必須月',
            'customer.payment_required_month'      => '支払必須月',
            'customer.bill_charge_min'             => '請求最低金額',
            'customer.payment_charge_min'          => '支払最低金額',
            'customer.bill_add_month'              => '振込予定月',
            'customer.bill_day'                    => '振込予定日',
            'customer.bill_send'                   => '発送方法（請求書）',
            'customer.payment_send'                => '発送方法（支払通知書）',
            'customer.factoring_send'              => '発送方法（引落通知書）',
            'customer.fax_recipient_cd'            => 'FAX通知先',
            'customer.optional_nm'                 => 'FAX通知任意宛先 施設・会社名', // 任意宛先名称（施設・会社名）から変更
            'customer.optional_section_nm'         => 'FAX通知任意宛先 役職（部署名）', //任意役職（部署名）から変更
            'customer.optional_person_nm'          => 'FAX通知任意宛先 担当者', // 任意担当者名称から変更
            'customer.optional_fax'                => 'FAX通知任意宛先 ファックス番号', // 任意ファックス番号から変更
        ];
    }
}
