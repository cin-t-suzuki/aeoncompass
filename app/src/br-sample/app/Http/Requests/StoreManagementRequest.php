<?php

namespace App\Http\Requests;

use App\Models\{
    HotelAccount,
    HotelStatus,
};
use App\Rules\{
    EmailSingle,
    PhoneNumber,
    WithoutHalfWidthKatakana
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreManagementRequest extends FormRequest
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
        $inputAccountIdBegin = $this->input('Hotel_Account.account_id_begin');
        return [
            'Hotel_Account.account_id_begin'    => [
                'required',
                'regex:/\A[0-9a-zA-Z]{1,10}\z/',
                new WithoutHalfWidthKatakana(),
                'max:10',
                // 独自バリデーション hotel_account.account_id で一意
                'unique:App\Models\HotelAccount,account_id_begin',
            ],
            'Hotel_Account.password'            => [
                'required',
                'regex:/\A[A-Z0-9]{1,10}\z/',
                new WithoutHalfWidthKatakana(),
                // ↓ 独自バリデーション(ID と password が一致の場合エラー)
                function ($attribute, $value, $fail) use ($inputAccountIdBegin) {
                    if (strtolower($value) === strtolower($inputAccountIdBegin)) {
                        $fail('アカウントIDと:attributeは異なる文字列を入力してください。');
                    }
                },
            ],
            'Hotel_Account.accept_status'       => ['required', Rule::in([
                HotelAccount::ACCEPT_STATUS_NG,
                HotelAccount::ACCEPT_STATUS_OK,
            ])],

            // hotel_person すべて、半角カナ禁止バリデーション
            'Hotel_Person.person_post'  => [new WithoutHalfWidthKatakana(), 'between:0,32'],
            'Hotel_Person.person_nm'    => [new WithoutHalfWidthKatakana(), 'required', 'between:0,32'],
            'Hotel_Person.person_tel'   => ['required', new PhoneNumber()],
            'Hotel_Person.person_fax'   => ['nullable', new PhoneNumber()],
            'Hotel_Person.person_email' => [new EmailSingle(), 'between:0,128'],

            // ↓ 登録画面では、hidden で 1 (登録作業中) 固定
            'Hotel_Status.entry_status' => ['required', Rule::in([
                HotelStatus::ENTRY_STATUS_PUBLIC,
                HotelStatus::ENTRY_STATUS_REGISTERING,
                HotelStatus::ENTRY_STATUS_CANCELLED,
            ])],
            'Hotel_Status.contract_ymd' => [],
            'Hotel_Status.open_ymd'     => [],
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
            'Hotel_Account.account_id_begin' => '入力アカウントID',
            'Hotel_Account.password'         => 'パスワード',
            'Hotel_Account.accept_status'    => 'ステータス',

            'Hotel_Person.person_post'  => '担当者役職',
            'Hotel_Person.person_nm'    => '担当者名称',
            'Hotel_Person.person_tel'   => '担当者電話番号',
            'Hotel_Person.person_fax'   => '担当者ファックス番号',
            'Hotel_Person.person_email' => '担当者電子メールアドレス',

            'Hotel_Status.entry_status' => '登録状態',
            'Hotel_Status.contract_ymd' => '契約日',
            'Hotel_Status.open_ymd'     => '公開日',
        ];
    }
}
