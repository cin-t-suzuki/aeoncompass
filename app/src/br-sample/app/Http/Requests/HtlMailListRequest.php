<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\{
    PhoneNumber,
    WithoutHalfWidthKatakana
};

class HtlMailListRequest extends FormRequest
{
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
        return [
            'Hotel_Person.person_email' => ['required', 'email'],
            'Hotel_Person.person_post' => ['required', new WithoutHalfWidthKatakana()],
            'Hotel_Person.person_nm' => ['required', new WithoutHalfWidthKatakana(), 'between:0,32'],
            'Hotel_Person.person_tel' => ['required', new PhoneNumber()],
            'Hotel_Person.person_fax' => ['required', new PhoneNumber()],
            'customer.email' => ['required', 'email'],
            'customer.person_post' => ['required', new WithoutHalfWidthKatakana()],
            'customer.person_nm' => ['required', 'between:0,32'],
            'customer.tel' => ['required', new PhoneNumber()],
            'customer.fax' => ['required', new PhoneNumber()],
            'customer.customer_nm' => ['required', new WithoutHalfWidthKatakana()],
        ];
    }

    public function withValidator(Validator $validator)
    {
        // 自動延長機能が設定されていれば、「自動延長確認 メールアドレス」の必須且つメールアドレス形式チェックを行う
        $validator->sometimes('extend_setting.email', 'required | email', function () {
            return isset($this->extend_setting['email_notify']) && $this->extend_setting['email_notify'] == 1;
        });

        // 自動延長機能が設定されていれば、「自動延長確認 文章タイプ」の必須チェックを行う
        $validator->sometimes('extend_setting.email_type', 'required', function () {
            return isset($this->extend_setting['email_notify']) && $this->extend_setting['email_notify'] == 1;
        });
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'Hotel_Person.person_email' => '施設担当者様 メールアドレス',
            'Hotel_Person.person_post' => '施設担当者様 部署・役職',
            'Hotel_Person.person_nm' => '施設担当者様 氏名',
            'Hotel_Person.person_tel' => '施設担当者様 電話番号',
            'Hotel_Person.person_fax' => '施設担当者様 FAX番号',
            'customer.email' => '請求担当者様 メールアドレス',
            'customer.person_post' => '請求担当者様 部署・役職',
            'customer.person_nm' => '請求担当者様 氏名',
            'customer.tel' => '請求担当者様 電話番号',
            'customer.fax' => '請求担当者様 FAX番号',
            'customer.customer_nm' => '請求担当者様 請求書発送先',
            'extend_setting.email' => '自動延長確認 メールアドレス',
            'extend_setting.email_type' => '自動延長確認 文章タイプ',
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email' => ':attributeはメールアドレス形式で入力してください。',
        ];
    }
}
