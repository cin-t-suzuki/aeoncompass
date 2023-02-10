<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailMultiple extends EmailCommon implements Rule
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
        if (strlen($value) === 0) {
            return true;
        }

        $allValid = true;
        $emails = explode(',', $value);
        foreach ($emails as $email) {
            if (!$this->isEmail($email)) {
                $allValid = false;
            }
        }
        return $allValid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom_email_multiple');
    }
}
