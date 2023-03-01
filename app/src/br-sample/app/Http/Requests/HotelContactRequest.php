<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    EmailSingle,
    PhoneNumber,
    PostalCode,
    WithoutHalfWidthKatakana
};
use App\Common\Traits;
use Illuminate\Validation\Validator;

class HotelContactRequest extends FormRequest
{
    use Traits;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // リクエストを取得
        $input = $this->all();

        // 半角カナを全角カナに変換（配列はないので考慮しない）
        foreach ($input as $key => $value) {
            if ($value == null) {
                $request[$key] = null; //nullの時はnullをそのまま入れたいので分岐追加（ないと空文字）
            } else {
                $request[$key] = mb_convert_kana($value, 'KV');

                // 全角を半角に変換
                if (
                    $key == 'postal_cd' || $key == 'postal_cd2' ||
                    $key == 'tel'       || $key == 'tel2'       ||
                    $key == 'fax'       ||
                    $key == 'email'     || $key == 'email2'     ||
                    $key == 'url'
                ) {
                    $request[$key] = mb_convert_kana($value, 'a');
                }
            }
        }

        // リクエストデータを上書き
        $this->merge($request);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //メッセージはどこまで元ソースに合わせるべきか？伝わればいい？
            'hotel_nm'    => ['required', new WithoutHalfWidthKatakana()],
            'person_post'    => [new WithoutHalfWidthKatakana()],
            'person_nm'    => ['required', new WithoutHalfWidthKatakana()],
            'person_nm_kana'    => ['required', new WithoutHalfWidthKatakana()],
            'postal_cd'    => ['required', new PostalCode()], //郵便番号を半角で正しく入力してください。（ 999-9999 ）
            'pref_id'    => ['not_in:0'], //未選択（0）
            'address'    => ['required', new WithoutHalfWidthKatakana()],
            'tel'    => ['required', new PhoneNumber()], //TEL を半角で正しく入力してください。（ 9999-9999-9999 ）
            'fax'    => [new PhoneNumber()],//FAX を半角で正しく入力してください。（ 9999-9999-9999 ）
            'email'    => [new EmailSingle()], //メールアドレスを半角で正しく入力してください。
            'travel_trade'    => ['required'],
            'estimate_dtm'    => ['required_if:travel_trade,2'],
            'note'    => ['max:3000', new WithoutHalfWidthKatakana()],

        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('postal_cd2', 'required', function ($input) {
            return $input->send_status == 1;
        });
        $validator->sometimes('postal_cd2', new PostalCode(), function ($input) {
            return $input->send_status == 1;
        });//else 送付先 郵便番号を半角で正しく入力してください。（ 999-9999 ）

        $validator->sometimes('pref_id2', 'not_in:0', function ($input) {
            return ($input->send_status == 1 && $input->pref_id2 == 0);
        }); //未選択（0）

        $validator->sometimes('address2', 'required', function ($input) {
            return $input->send_status == 1;
        });
        $validator->sometimes('address2', new WithoutHalfWidthKatakana(), function ($input) {
            return $input->send_status == 1;
        }); //else

        $validator->sometimes('hotel_nm2', 'required', function ($input) {
            return $input->send_status == 1;
        });
        $validator->sometimes('hotel_nm2', new WithoutHalfWidthKatakana(), function ($input) {
            return $input->send_status == 1;
        });//else

        $validator->sometimes('person_post2', new WithoutHalfWidthKatakana(), function ($input) {
            return $input->send_status == 1;
        });

        $validator->sometimes('person_nm2', 'required', function ($input) {
            return $input->send_status == 1;
        });
        $validator->sometimes('person_nm2', new WithoutHalfWidthKatakana(), function ($input) {
            return $input->send_status == 1;
        }); //else

        $validator->sometimes('person_nm_kana2', 'required', function ($input) {
            return $input->send_status == 1;
        });
        $validator->sometimes('person_nm_kana2', new WithoutHalfWidthKatakana(), function ($input) {
            return $input->send_status == 1;
        });//else

        $validator->sometimes('tel2', 'required', function ($input) {
            return $input->send_status == 1;
        });
        $validator->sometimes('tel2', new PhoneNumber(), function ($input) {
            return $input->send_status == 1;
        });//else 送付先 TEL を半角で正しく入力してください。（ 9999-9999-9999 ）

        $validator->sometimes('email2', new EmailSingle(), function ($input) {
            return $input->send_status == 1;
        });//else 送付先 メールアドレスを半角で正しく入力してください。
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'hotel_nm' => '宿泊施設名',
            'person_post' => '部署・役職',
            'person_nm' => '氏名',
            'person_nm_kana' => '氏名（ふりがな）',
            'postal_cd' => '郵便番号',
            'pref_id' => '都道府県',
            'address' => '住所',
            'tel' => 'TEL',
            'fax' => 'FAX',
            'email' => 'メールアドレス',
            'travel_trade' => '旅館業登録の有無',
            'estimate_dtm' => '旅館業登録 取得予定日',
            'note' => 'ご質問等',

            'postal_cd2' => '送付先 郵便番号',
            'pref_id2' => '送付先 都道府県',
            'address2' => '送付先 住所',
            'hotel_nm2' => '送付先 宿泊施設名または会社名',
            'person_post2' => '送付先 部署・役職',
            'person_nm2' => '送付先 氏名',
            'tel2' => '送付先 TEL',
            'email2' => '送付先 メールアドレス',
        ];
    }
}
