<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WithoutHalfWidthKatakana implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 半角カタカナを全角カタカナに変換したものが、元の文字列と一致するかを判定
        return $value == mb_convert_kana($value, "KV");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.without_half_width_katakana');
    }
}
