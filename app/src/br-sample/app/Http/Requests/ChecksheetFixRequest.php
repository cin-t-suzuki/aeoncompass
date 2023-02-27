<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    WithoutHalfWidthKatakana
};

class ChecksheetFixRequest extends FormRequest
{

    // エラー時はindexに戻す
    protected $redirectRoute = 'ctl.brReserveCk.index';

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
            'checksheet_fix.checksheet_ym' => ['required', 'date'], //日付チェックはdateでいいか？
            'checksheet_fix.hotel_cd'      => ['required', new WithoutHalfWidthKatakana(), 'between:0,10'],
            'checksheet_fix.fix_status'    => ['required', 'integer', 'digits_between:0,1'], // パターンチェック、カラムの説明
            // コントローラ側で設定されるので以下は不要？？
            // 'fix_dtm'       => ['date'], //日付チェックはdateでいいか？
            // 'fixed_dtm'     => []
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
            'checksheet_fix.checksheet_ym' => '処理年月',
            'checksheet_fix.hotel_cd'      => '施設コード',
            'checksheet_fix.fix_status'    => '確定テータス',
            // 'fix_dtm'       => '確定日時',
            // 'fixed_dtm'     => '検収確定日'
        ];
    }
}
