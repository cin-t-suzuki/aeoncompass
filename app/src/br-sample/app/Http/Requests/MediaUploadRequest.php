<?php

namespace App\Http\Requests;

use App\Rules\WithoutHalfWidthKatakana;
use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
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
            'target_cd'     => ['required'],
            'title'         => ['required', 'max:30', new WithoutHalfWidthKatakana()],
            'select'        => ['required'],
            'label_cd'      => ['nullable', 'array'],
            'label_cd.*'    => ['nullable', 'integer', 'between:0,1'],

            'file' => ['required', 'file', 'image', 'mimes:jpeg,jpg,gif', 'max:10240'], // サイズ上限 10MB
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
            'title' => '画像タイトル',
            'file' => '画像ファイル',
        ];
    }
}
