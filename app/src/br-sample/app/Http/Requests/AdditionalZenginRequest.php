<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{
    OnlyFullWidthKatakana,
    PhoneNumber,
    PostalCode,
    WithoutHalfWidthKatakana
};
use App\Common\DateUtil;

class AdditionalZenginRequest extends FormRequest
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
            'zengin_ym' => ['required', new WithoutHalfWidthKatakana(), 'between:0,6'],
            'branch_id' => ['integer', new WithoutHalfWidthKatakana(), 'digits_between:0,10'],
            'hotel_cd' => [new WithoutHalfWidthKatakana(), 'between:0,10'],
            'reason' => [new WithoutHalfWidthKatakana(), 'between:0,333'],
            'reason_internal' => [new WithoutHalfWidthKatakana(), 'between:0,333'],
            'additional_charge' => ['integer', 'digits_between:0,10']
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
            'zengin_ym'                  => '処理年月コード',
            'branch_id'                  => '連番ID',
            'reason'                  => '理由',
            'reason_internal'                  => '備考（内部のみ）',
            'additional_charge'     => '追加金額'
        ];
    }

    protected function prepareForValidation()
    {
        if (is_null($this->input('zengin_ym'))) {
            $year = $this->input('year');
            $month = $this->input('month');
            $zengin_ym = new DateUtil($year . '-' . $month . '-23'); //zengin_ymは必須のため登録データを先に成型;
            $data['zengin_ym'] = $zengin_ym->to_format('Ym');
            $this->merge($data);
        }
    }
}
