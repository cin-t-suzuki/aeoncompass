<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\WithoutHalfWidthKatakana;

class HtlAlertMailHotelRequest extends FormRequest
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
            'AlertMailHotel.email'      => ['required', 'email'],
            'AlertMailHotel.email_type' => ['required'],
            'AlertMailHotel.note'       => ['max:1000', new WithoutHalfWidthKatakana()],
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
            'AlertMailHotel.email'      => 'メールアドレス',
            'AlertMailHotel.email_type' => '文章タイプ',
            'AlertMailHotel.note'       => '備考'
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
