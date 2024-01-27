<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class DateFormatRule implements Rule
{
    public function passes($attribute, $value)
    {
        $format = 'G:i:s d-m-Y';
        $parsed = date_parse_from_format($format, $value);

        return $parsed['error_count'] === 0 && checkdate($parsed['month'], $parsed['day'], $parsed['year']);
    }

    public function message()
    {
        return "Ngày chưa đúng định dạng giờ:phút:giây ngày-tháng-Năm";
    }
}
