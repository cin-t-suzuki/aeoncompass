<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\HotelAccount;
use App\Util\Models_Cipher;

class HtlChangePassRequest extends FormRequest
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
     * バリーデーションのためにデータを準備
     *
     * IDのアルファベットは大文字で登録するため大文字に加工
     * PWとIDの同値不可チェックも必要なため、一時的にPW入力値も大文字に加工
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        // PWは、passedValidationで入力値へ戻すため、一旦退避
        $this['input_pass1'] = $this->pass1;
        $this['input_pass2'] = $this->pass2;

        $upper_id1 = strtoupper($this->id1);
        $upper_id2 = strtoupper($this->id2);
        $upper_pass1 = strtoupper($this->pass1);
        $upper_pass2 = strtoupper($this->pass2);
        $this->merge([
            'id1' => $upper_id1,
            'id2' => $upper_id2,
            'pass1' => $upper_pass1,
            'pass2' => $upper_pass2,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // IDPWどちらも更新時
        if (isset($this->both)) {
            return [
                'id1'     => [
                    'same:id2',
                    'regex:/^[A-Za-z0-9]+$/',
                    // 他人が既に登録しているIDと同じ文字列には登録不可
                    Rule::unique('hotel_account', 'account_id')->ignore($this->target_cd, 'hotel_cd')
                ],
                'pass1'   => ['same:pass2', 'different:id1', 'regex:/^[A-Za-z0-9]+$/'],
            ];
        } else {
            return [
                'id1'     => [
                    'same:id2',
                    'regex:/^[A-Za-z0-9]+$/',
                    // 他人が既に登録しているIDと同じ文字列には登録不可
                    Rule::unique('hotel_account', 'account_id')->ignore($this->target_cd, 'hotel_cd')
                ],
                'pass1'   => [
                    'same:pass2',
                    'different:id1',
                    'regex:/^[A-Za-z0-9]+$/',
                    // 自分のIDと同じ文字列には登録不可
                    Rule::unique('hotel_account', 'account_id')->where('hotel_cd', $this->target_cd)
                ],
            ];
        }
    }

    /**
     * バリデータインスタンスの設定
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        // idのみ更新 or 両方更新時は、id1は必須
        $validator->sometimes('id1', 'required', function () {
            return isset($this->id) || isset($this->both);
        });

        // idのみ更新 or 両方更新時は、id2は必須
        $validator->sometimes('id2', 'required', function () {
            return isset($this->id) || isset($this->both);
        });

        // PWのみ更新 or 両方更新時は、pass1は必須
        $validator->sometimes('pass1', 'required', function () {
            return isset($this->pass) || isset($this->both);
        });

        // PWのみ更新 or 両方更新時は、pass2は必須
        $validator->sometimes('pass2', 'required', function () {
            return isset($this->pass) || isset($this->both);
        });

        // idのみ更新時は、既に登録されているpassと同じ文字列は不可
        $validator->after(function ($validator) {
            if (isset($this->id) && !isset($this->pass) && !isset($this->both)) {
                $o_hotel_account = new HotelAccount();
                $current_pass = $o_hotel_account->where([
                    'hotel_cd' => $this->target_cd
                ])->value('password');

                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $current_pass = $cipher->decrypt($current_pass);

                if (strtolower($current_pass) === strtolower($this->id1)) {
                    $validator->errors()->add('errors', 'IDとパスワードは異なる文字列を入力してください。');
                }
            };
        });
    }

    /**
     * Handle a passed validation attempt.
     *
     * PWは、バリデーション終了後に入力した値に戻す
     * @return void
     */
    protected function passedValidation()
    {
        $input_pass1 = $this->input_pass1;
        $input_pass2 = $this->input_pass2;

        $this->merge(['pass1' => $input_pass1, 'pass2' => $input_pass2]);
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id1' => '新ログインＩＤ',
            'id2' => '新ログインＩＤ(再入力)',
            'pass1' => '新パスワード',
            'pass2' => '新パスワード(再入力)'
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
            'different' => 'IDとパスワードは、異なった文字列を指定してください。',
            'pass1.unique' => 'パスワードは、登録済のIDとは異なる文字列を指定してください。',
            'unique' => '入力した:attributeは、既に他の箇所で使用されています。',
            'regex' => ':attribute は、半角英数字のみで登録してください。'
        ];
    }
}
