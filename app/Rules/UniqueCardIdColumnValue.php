<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UniqueCardIdColumnValue implements Rule
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
        //chỉ dùng đế gán cho thông báo thôi
        $this->customValue = $value;

        // Lấy dữ liệu từ request
        $file = request()->file('file_list_staff');
        $excelData = \Excel::toArray([], $file);

        $columnData =[];

        foreach ($excelData[0] as $key=>$item){
            if($key<6) continue;
            // Lấy các giá trị trong cột mã nhân viên
           $columnData[] = (string)$item[1];
        }
//       dd($columnData);
        $list_repeat = $this->check_repeat_value_in_array($columnData);
//        dd($columnData,$list_repeat);
        if(in_array($value,$list_repeat))
            return false;
        else
            return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mã thẻ :'.$this->customValue.' không được trùng nhau trong file excel.';
    }
    /*
   * chức năng : kiểm tra giá trị có trùng hay không trong 1 mảng , của 1 cột
   */
    private function check_repeat_value_in_array($myArray)
    {

        // Tạo một mảng tạm để lưu các giá trị đã xuất hiện
        $seenValues = array();
        //mảng lưu giá trị trùng lặp
        $repeat_values = [];

        // Tên cột mà bạn muốn kiểm tra trùng lặp


        // Kiểm tra từng phần tử của cột
        foreach ($myArray as $item) {
            $value = $item;

            // Kiểm tra nếu giá trị đã xuất hiện trong mảng tạm thì đó là giá trị trùng lặp
            if (in_array($value, $seenValues)) {
                //  echo "Giá trị trùng lặp: " . $value . "<br>";
                //  exit;
                $repeat_values[] = $value;
            } else {
                // Nếu giá trị chưa xuất hiện thì lưu vào mảng tạm
                $seenValues[] = $value;
            }
        }
        return $repeat_values;
    }
}
