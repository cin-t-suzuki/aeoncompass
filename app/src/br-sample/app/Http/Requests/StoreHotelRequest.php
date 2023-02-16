<?php

namespace App\Http\Requests;

use App\Models\{
    Hotel,
    HotelControl,
};
use App\Rules\{
    OnlyFullWidthKatakana,
    PhoneNumber,
    PostalCode,
    WithoutHalfWidthKatakana
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHotelRequest extends FormRequest
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
        $inputCheckInStart = $this->input('Hotel.check_in');
        return [
            'Hotel.hotel_category'    => [Rule::in([
                Hotel::CATEGORY_CAPSULE_HOTEL,
                Hotel::CATEGORY_BUSINESS_HOTEL,
                Hotel::CATEGORY_CITY_HOTEL,
                Hotel::CATEGORY_JAPANESE_INN,
            ])],
            'Hotel.hotel_nm'          => ['required', new WithoutHalfWidthKatakana(), 'between:0,50'],
            'Hotel.hotel_kn'          => ['required', new OnlyFullWidthKatakana(), 'between:0,150'],
            'Hotel.hotel_old_nm'      => [new WithoutHalfWidthKatakana(), 'between:0,50'],
            'Hotel.postal_cd'         => ['required', new PostalCode()],
            'Hotel.pref_id'           => ['required', 'integer', 'numeric', 'digits_between:0,2'],
            'Hotel.city_id'           => ['required', 'integer', 'numeric', 'digits_between:0,20'],
            'Hotel.ward_id'           => ['integer', 'numeric', 'digits_between:0,20'],
            'Hotel.address'           => ['required', new WithoutHalfWidthKatakana(), 'between:0,100'],

            'Hotel.tel'               => ['required', new PhoneNumber()],
            'Hotel.fax'               => ['nullable', new PhoneNumber()],
            'Hotel.room_count'        => ['nullable', 'integer', 'numeric', 'min:0', 'max:9999'],
            'Hotel.check_in'          => [
                'required',
                // 24時以降を許容した H:i の時刻形式であることをバリデーション
                // MEMO: 'date_format:H:i' -> 24時以降の時刻に対応できない
                'regex:/\A\d{2}:\d{2}\z/',
            ],
            'Hotel.check_in_end'      => [
                'nullable',
                // 24時以降を許容した H:i の時刻形式であることをバリデーション
                // MEMO: 'date_format:H:i' -> 24時以降の時刻に対応できない
                'regex:/\A\d{2}:\d{2}\z/',

                // 独自チェック check_in より後であることをバリデーション（同じではダメ）
                // MEMO: 'after:Hotel.check_in' -> 24時以降の時刻に対応できない
                // MEMO: 'gt:check_in' -> 文字列の場合、長さで判定される
                function ($attribute, $value, $fail) use ($inputCheckInStart) {
                    // 辞書順で比較
                    if (strcmp($inputCheckInStart, $value) >= 0) {
                        $fail(':attributeはチェックインより後の時刻を設定してください。');
                    }
                },
            ],
            'Hotel.check_in_info'     => [new WithoutHalfWidthKatakana(), 'between:0,75'],
            'Hotel.check_out'         => ['required', 'date_format:H:i'],
            'Hotel.midnight_status'   => ['required', Rule::in([
                Hotel::MIDNIGHT_STATUS_STOP,
                Hotel::MIDNIGHT_STATUS_ACCEPT,
            ])],

            'Hotel_Control.stock_type'  => [Rule::in([
                HotelControl::STOCK_TYPE_CONTRACT_SALE,
                HotelControl::STOCK_TYPE_PURCHASE_SALE,
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
            'Hotel.order_no'        => '表示順序',
            'Hotel.hotel_category'  => '施設区分',
            'Hotel.hotel_nm'        => '施設名称',
            'Hotel.hotel_kn'        => '施設名称カナ',
            'Hotel.hotel_old_nm'    => '旧施設名称',
            'Hotel.postal_cd'       => '郵便番号',
            'Hotel.pref_id'         => '都道府県',
            'Hotel.city_id'         => '市',
            'Hotel.ward_id'         => '区',
            'Hotel.address'         => '住所',
            'Hotel.tel'             => '電話番号',
            'Hotel.fax'             => 'ＦＡＸ番号',
            'Hotel.room_count'      => '保有部屋数',
            'Hotel.check_in'        => 'チェックイン開始時刻',
            'Hotel.check_in_end'    => 'チェックイン終了時刻',
            'Hotel.check_in_info'   => 'チェックイン時刻コメント',
            'Hotel.check_out'       => 'チェックアウト時刻',
            'Hotel.midnight_status' => '深夜受付状態',
            'Hotel.accept_status'   => '予約受付状態',
            'Hotel.accept_auto'     => '予約受付状態自動更新有無',
            'Hotel.accept_dtm'      => '予約受付状態更新日時',

            'Hotel_Control.stock_type' => '仕入タイプ',
        ];
    }
}
