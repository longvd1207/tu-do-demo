<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class checkGenderRule implements Rule
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
    public function passes($attribute, $value)
    {
        return in_array((int)$value, [0, 1]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Giới tính chỉ được điện giá trị 0 hoặc 1';
    }
}
