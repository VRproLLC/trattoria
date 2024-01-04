<?php

namespace App\Rules;

use App\Models\RegisterUsers;
use Illuminate\Contracts\Validation\Rule;

class TimeOrderRule implements Rule
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
        if (request('time_issue') == 2 && request('time') === null) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Укажите время доставки.';
    }
}
