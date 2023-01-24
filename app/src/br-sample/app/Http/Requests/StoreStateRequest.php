<?php

namespace App\Http\Requests;

use App\Models\{
    HotelControl,
    HotelNotify,
};
use App\Rules\{
    EmailSingle,
    PhoneNumber,
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStateRequest extends FormRequest
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
        $inputNotifyDevices = $this->input('notify_device', []);
        return [
            'notify_device'     => ['required', 'array'],
            'notify_device.*'   => [Rule::in([
                1 << HotelNotify::NOTIFY_DEVICE_FAX,
                1 << HotelNotify::NOTIFY_DEVICE_EMAIL,
                1 << HotelNotify::NOTIFY_DEVICE_LINCOLN,
            ])],

            'Hotel_Notify.neppan_status'    => ['nullable', Rule::in([
                HotelNotify::NEPPAN_STATUS_FALSE,
                HotelNotify::NEPPAN_STATUS_TRUE,
            ])],
            'Hotel_Notify.notify_status'    => ['required', Rule::in([
                HotelNotify::NOTIFY_STATUS_FALSE,
                HotelNotify::NOTIFY_STATUS_TRUE,
            ])],
            'Hotel_Notify.notify_email'     => [
                // 独自チェック 2(Email) にチェックがある場合: 'notify_email' が必須
                Rule::requiredIf(in_array(1 << HotelNotify::NOTIFY_DEVICE_EMAIL, $inputNotifyDevices)),
                new EmailSingle(),
                'between:0,500', // 過剰だが残している
            ],
            'Hotel_Notify.notify_fax'       => [
                // 独自チェック 1(FAX) にチェックある場合: 'notify_fax' が必須
                Rule::requiredIf(in_array(1 << HotelNotify::NOTIFY_DEVICE_FAX, $inputNotifyDevices)),
                new PhoneNumber(),
            ],
            'Hotel_Notify.faxpr_status'     => ['required', Rule::in([
                HotelNotify::FAXPR_STATUS_FALSE,
                HotelNotify::FAXPR_STATUS_TRUE,
            ])],

            'Hotel_Control.stock_type'          => ['required', Rule::in([
                HotelControl::STOCK_TYPE_CONTRACT_SALE,
                HotelControl::STOCK_TYPE_PURCHASE_SALE,
            ])],
            'Hotel_Control.checksheet_send'     => ['required', Rule::in([
                HotelControl::CHECKSHEET_SEND_TRUE,
                HotelControl::CHECKSHEET_SEND_FALSE,
            ])],
            'Hotel_Control.charge_round'        => ['integer', 'numeric', Rule::in([1, 10, 100])],
            'Hotel_Control.stay_cap'            => ['nullable', 'integer', 'numeric', 'min:1', 'max:99'],
            'Hotel_Control.management_status'   => ['required', Rule::in([
                HotelControl::MANAGEMENT_STATUS_FAX,
                HotelControl::MANAGEMENT_STATUS_INTERNET,
                HotelControl::MANAGEMENT_STATUS_FAX_INTERNET,
            ])],
            // MEMO: ↓ 移植元ではバリデーションされていなかった。ラジオボタンだから不具合にはならなかったのだろう。
            'Hotel_Control.akafu_status'        => [Rule::in([
                HotelControl::AKAFU_STATUS_TRUE,
                HotelControl::AKAFU_STATUS_FALSE,
            ])],
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
            'notify_device' => '通知媒体',

            'Hotel_Notify.neppan_status'    => 'ねっぱん通知ステータス',
            'Hotel_Notify.notify_status'    => '通知ステータス',
            'Hotel_Notify.notify_email'     => '通知電子メールアドレス',
            'Hotel_Notify.notify_fax'       => '通知ファックス番号',
            'Hotel_Notify.faxpr_status'     => 'FAXPR可否',

            'Hotel_Control.stock_type'          => '仕入形態',
            'Hotel_Control.checksheet_send'     => '送客リスト送付可否',
            'Hotel_Control.charge_round'        => '金額切り捨て桁',
            'Hotel_Control.stay_cap'            => '連泊限界数',
            'Hotel_Control.management_status'   => '利用方法',
            'Hotel_Control.akafu_status'        => '日本旅行在庫連携',
        ];
    }
}
