<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HtlHotelCancelRateRequest extends FormRequest
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
            'days.*'          => ['required','between:0,100', 'integer'],
            'cancel_rate.*'   => ['required','between:1,100', 'integer'],
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
            'days.*' => '日数',
            'cancel_rate.*' => '料率'
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
            'required' => ':attributeは必ず入力してください。',
            'unique' => '同じ:attributeが既に存在します。'
        ];
    }
}
