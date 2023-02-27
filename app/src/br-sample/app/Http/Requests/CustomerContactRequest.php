<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    EmailSingle,
    PhoneNumber,
    WithoutHalfWidthKatakana
};
use App\Common\Traits;
use Illuminate\Validation\Validator;

class CustomerContactRequest extends FormRequest
{
    use Traits;

    //エラー時の戻り先ページの指定
    protected $redirect = '/contact/customer';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function withValidator(Validator $validator)
    {
        // 独自バリデーション
        // 問い合わせ区分がご予約についての場合、予約コードまたは施設名＋宿泊日＋宿泊代表者が必須
        $inputCategory = $this->input('category');
        if (($inputCategory == "01")) { //($a_page == "customer") and は削除（ここでは$a_page渡されている気配がない、別処理で必要に応じて実装）
            //必須項目のチェック
            if (
                ($this->is_empty($this->input('rsv_cd')))
                && ($this->is_empty($this->input('hotel_nm'))
                    || $this->is_empty($this->input('date_ymd'))
                    || $this->is_empty($this->input('guest_nm')))
            ) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('full_nm', '予約コード　または　ご予約された施設名と宿泊日と宿泊代表者名の３つ　を必ず入力してください。');
                });
            } else {
                $validator->sometimes('rsv_cd', 'numeric | digits:14', function () {
                    //予約コードが入力されていた場合数字のみの１４桁か確認
                    return !$this->is_empty($this->input('rsv_cd'));
                });
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $inputAccountId = $this->input('account_id');
        $inputRsvCd = $this->input('rsv_cd');

        return [
            'category' => [
                'required', new WithoutHalfWidthKatakana()],
            'full_nm'    => ['required', new WithoutHalfWidthKatakana()],
            'account_id'    => [new WithoutHalfWidthKatakana(), 'max:100',
                        // ↓ 独自バリデーション
                        function ($attribute, $value, $fail) use ($inputAccountId) {
                            if (!$this->is_empty($inputAccountId)) {
                                $s_pattern = null;
                                $s_pattern .= "/0|1|2|3|4|5|6|7|8|9|";
                                $s_pattern .= "a|b|c|d|e|f|g|";
                                $s_pattern .= "h|i|j|k|l|m|n|";
                                $s_pattern .= "o|p|q|r|s|t|u|";
                                $s_pattern .= "v|w|x|y|z|";
                                $s_pattern .= "A|B|C|D|E|F|G|";
                                $s_pattern .= "H|I|J|K|L|M|N|";
                                $s_pattern .= "O|P|Q|R|S|T|U|";
                                $s_pattern .= "V|W|X|Y|Z|@|-|\.|-|_|/";

                                if (!preg_replace($s_pattern, "", $inputAccountId) == "") {
                                    $fail(':attributeには半角アルファベットまたは半角数字のみ入力いただけます。');
                                }
                            }
                        }],
            'email' => ['required', new EmailSingle()],
            'note' => ['required', new WithoutHalfWidthKatakana()],
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
            'category' => '問い合わせ区分',
            'full_nm' => 'ご氏名',
            'account_id' => '会員コード',
            'email' => 'メールアドレス',
            'note' => '本文',
            'rsv_cd' => '予約コード'
        ];
    }
}
