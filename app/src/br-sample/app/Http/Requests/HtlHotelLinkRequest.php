<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\WithoutHalfWidthKatakana;

class HtlHotelLinkRequest extends FormRequest
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
            'HotelLink.title' => ['required', new WithoutHalfWidthKatakana(), 'between:0,100'],
            'HotelLink.url' => ['required', new WithoutHalfWidthKatakana(), 'url', 'between:0,128'],
        ];
    }
    public function attributes()
    {
        return [
            'HotelLink.title' => 'タイトル',
            'HotelLink.url' => 'Webサイトアドレス'
        ];
    }
}