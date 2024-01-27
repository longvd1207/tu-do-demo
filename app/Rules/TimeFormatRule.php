<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class TimeFormatRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $customValue;

    public function __construct( $customValue = null)
    {
        $this->customValue = $customValue;
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
        // Kiểm tra xem giá trị có phù hợp với định dạng G:i không
        return preg_match('/^(0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value);
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Giá trị thời gian ' . $this->customValue . ' chưa theo đúng định dạng  "G:i"';
    }
}
