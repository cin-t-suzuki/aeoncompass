<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    WithoutHalfWidthKatakana
};

class VoiceReplyRequest extends FormRequest
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
            'target_cd'   => ['required', new WithoutHalfWidthKatakana(), 'between:0,10'], //カラムはhotel_cdだが、渡されてくる値はtarget_cdの方を使用すべきのはず
            'voice_cd'   => ['required', 'integer', 'digits_between:0,13'],
            'reply_type' => ['required'], //パターンチェック、カラムの説明は不要？
            'answer'     => ['required', new WithoutHalfWidthKatakana(), 'between:0,1000'],
            // 'reply_dtm'  => ['required', 'date'], // createの際はnow()を指定するので、ここは非表示でいいか？
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
            'hotel_cd'   => '施設コード',
            'voice_cd'   => '投稿コード',
            'reply_type' => '返答者',
            'answer'     => '返答内容',
            'reply_dtm'  => '返答日時'
        ];
    }
}
