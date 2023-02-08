<?php

namespace App\Http\Requests;

use App\Models\HotelAccount;
use App\Rules\WithoutHalfWidthKatakana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateManagementRequest extends FormRequest
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
            'Hotel_Account.account_id_begin' => [
                'required',
                'regex:/\A[0-9a-zA-Z]{1,10}\z/',
                new WithoutHalfWidthKatakana(),
                'max:10',
                // 独自バリデーション hotel_account.account_id で一意
                Rule::unique('hotel_account')->ignore($this->target_cd, 'hotel_cd'),
            ],
            'Hotel_Account.accept_status' => ['required', Rule::in([
                HotelAccount::ACCEPT_STATUS_NG,
                HotelAccount::ACCEPT_STATUS_OK,
            ])],
        ];
    }
}
