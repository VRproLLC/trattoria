<?php

namespace App\Rules;

use App\Libs\Str;
use App\Models\Auth\RegisterNewUsers;
use App\Models\RegisterUsers;
use Illuminate\Contracts\Validation\Rule;

class SmsCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (bool)RegisterUsers::where('code', $value)->where('phone', request('phone'))->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('auth.sms_code_error');
    }
}
