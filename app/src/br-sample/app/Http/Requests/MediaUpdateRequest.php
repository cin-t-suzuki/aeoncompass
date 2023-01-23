<?php

namespace App\Http\Requests;

use App\Rules\WithoutHalfWidthKatakana;
use Illuminate\Foundation\Http\FormRequest;

class MediaUpdateRequest extends FormRequest
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
            'target_cd' => ['required', 'exists:media,hotel_cd'],
            'media_no'  => ['required', 'exists:media,media_no'],
            'title'     => ['required', 'max:30', new WithoutHalfWidthKatakana()],
            'label_cd'  => ['nullable', 'array'],
            'label_cd.*' => ['integer', 'between:0,1'],
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
            'title' => 'タイトル',
        ];
    }
}
