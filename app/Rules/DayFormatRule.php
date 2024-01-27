<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;


class DayFormatRule implements Rule
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
        return preg_match('/^\d{2}-\d{2}-\d{4}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Giá trị ngày ' . $this->customValue . ' chưa theo đúng định dạng  "dd-mm-YYYY"';
    }
}
