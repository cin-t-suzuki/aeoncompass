<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    WithoutHalfWidthKatakana
};

class VoiceStayRequest extends FormRequest
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
        $inputStatus = $this->input('status');
        return [
            'voice_cd'       => ['required', 'integer', 'digits_between:0,13'],
            //BrVoiceControllerのswitchで使用するのはvoice_cdのみ（voice_cdで既存データを取得して書き替える）
            //逆に他の値はbladeから渡されないため、以下があるとバリデーションエラーになる
            //おそらくユーザ側での登録の際に以下も必要になると思うが、どうするか？

            // 'reserve_cd'     => ['required', new WithoutHalfWidthKatakana(), 'between:0,14'],
            // 'target_cd'       => ['required', new WithoutHalfWidthKatakana(), 'between:0,10'], //カラムはhotel_cdだがtarget_cdで渡されているためtarget_cdで設定
            // 'member_cd'      => ['required', new WithoutHalfWidthKatakana(), 'between:0,128'],
            // 'title'          => [new WithoutHalfWidthKatakana(), 'between:0,30'],
            // 'explain'        => ['required', new WithoutHalfWidthKatakana(), 'between:0,250'],
            // 'experience_dtm' => ['required', 'date'],
            // 'gender'         => ['required'], //パターンチェック、カラムの説明は不要？
            // 'age'            => ['required', 'integer', 'digits_between:0,3'], //カラムの説明は不要？
            // 'limit_dtm'      => ['required', 'date'],
            // 'status'         => ['required'], //パターンチェック、カラムの説明は不要？
            // 'cancel_dtm'     => ['date',
            //     // 状態が「 1:本人取り消し 2:強制取り消し」の時、「取り消し日時」必須
            //     function ($attribute, $value, $fail) use ($inputStatus) {
            //         if (($inputStatus == 1) || ($inputStatus == 2)) {
            //             // return $o_validator->is_presence_of($this->get_column_object('cancel_dtm'), $this->_a_attributes['cancel_dtm']);
            //             return 'required'; // 書き換えあっているか？
            //         }
            //     }
            // ],
            // 'note'           => [new WithoutHalfWidthKatakana(), 'between:0,1000']
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
            'voice_cd'       => '投稿コード',
            'reserve_cd'     => '予約コード',
            'hotel_cd'       => '施設コード',
            'member_cd'      => '会員コード',
            'title'          => 'タイトル',
            'explain'        => '投稿内容',
            'experience_dtm' => '投稿日時',
            'gender'         => '性別',
            'age'            => '年齢',
            'limit_dtm'      => '有効期限',
            'status'         => '状態',
            'cancel_dtm'     => '取り消し日時',
            'note'           => '備考'
        ];
    }
}
