<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
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
        $id = $this->request->get('id');
        $user_id = $this->request->get('user_id');
        $arr = [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:5',
//                Rule::unique("tbl_staff",'name')->ignore($id)->where(function ($q) {
//                    $q->where('is_delete', '=', 0);
//                })
            ],
//            'card_id' => [
//                'required',
//                'string',
//                'max:255',
//                Rule::unique("tbl_staff",'card_id')->ignore($id)->where(function ($q) {
//                    $q->where('is_delete', '=', 0);
//                })
//            ],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique("tbl_staff", 'code')->ignore($id)->where(function ($q) {
                    $q->where('is_delete', '=', 0);
                })
            ],
            //   'gender' => ['required', 'numeric', 'in:0,1'],
            'image_link' => ['nullable', 'string', 'max:1000'],
            'company_id' => ['required', 'string', 'max:50', 'min:1'],
//            'department_name'=>['nullable', 'string', 'max:500'],
//            'position_name'=>['nullable', 'string', 'max:500'],

            'phone' => 'nullable|numeric|regex:/^([0-9]{10,11})$/',

            'user_name' => [
                'required',
                'string',
                'min:1',
                'max:255',
                Rule::unique("user", 'user_name')->ignore($user_id)->where(function ($q) {
                    $q->where('is_delete', '=', 0);
                })
            ],
            'password' => [
                'nullable',
                'string',
                'min:1',
                'max:255',
            ],

        ];

        //nếu là update thì ko đòi hỏi p hải nhập password
        if (isset($id) and $id != "") {
            $arr['password'] = [
                'nullable',
                'string',
                'min:1',
                'max:255',
            ];
        } else {
            //là thêm mới thì cần password
            $arr['password'] = [
                'required',
                'string',
                'min:1',
                'max:255',
            ];
        }

        //một phòng chỉ có 1 trưởng phòng thôi------------------
        if(!empty($this->request->get("is_manager")) and $this->request->get("is_manager") =="on" and !empty($this->request->get("company_id")) ){

            $is_exit_manager =  Staff::where("company_id",$this->request->get("company_id"))->where("is_manager",1)->where('is_delete',0);

            //nếu sửa thì ko tính trưởng phòng của chính bản ghi này
            if (isset($id) and $id != "") {
                $is_exit_manager =  $is_exit_manager->where("id","!=",$id);
            }
            $is_exit_manager =$is_exit_manager->first();


            if(!empty($is_exit_manager)){
                $arr['is_manager'] = [
                    function ($attribute, $value, $fail) {

                      $fail("Phòng này đã có trưởng phòng rồi !");

                    }
                ];
            }

        }
        //một phòng chỉ có 1 trưởng phòng thôi------------------

        return $arr;

    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống',
            'name.max' => 'Tên tối đa 255 ký tự',
            'name.min' => 'Tên tối thiểu 5 ký tự',
//            'name.unique' => 'Tên đã tồn tại',

//            'card_id.required' => 'mã thẻ không được để trống',
//            'card_id.max' => 'mã thẻ tối đa 255 ký tự',
//            'card_id.min' => 'mã thẻ tối thiểu 1 ký tự',
//            'card_id.unique' => 'mã thẻ đã tồn tại',

            'code.required' => 'mã nhân viên không được để trống',
            'code.max' => 'mã nhân viên tối đa 255 ký tự',
            'code.min' => 'mã nhân viên tối thiểu 1 ký tự',
            'code.unique' => 'mã nhân viên đã tồn tại',

            'user_name.required' => 'Username không được để trống',
            'user_name.max' => 'Username tối đa 255 ký tự',
            'user_name.min' => 'Username tối thiểu 1 ký tự',
            'user_name.unique' => 'Username đã tồn tại',


            'password.max' => 'password tối đa 255 ký tự',
            'password.min' => 'password tối thiểu 1 ký tự',
            'password.required' => 'password không được để trống',
//            'name.unique' => 'Tên đã tồn tại',
//
//            'gender.required' => 'Giới tính không được để trống',
//            'gender.in' => 'Giới tính chỉ là nam(1) hoặc nữ(0)',

            'image_link.max' => 'Đường dẫn lưu ảnh tối đa 1000 ký tự',

            'company_id.required' => 'Phòng ban không được để trống',
//            'company_id.max' => 'company_id tối đa 255 ký tự',

            'phone.numeric' => 'Số điện thoại phòng ban phải là số',
            'phone.regex' => 'Số điện thoại phòng ban không hợp lệ',

//            'department_id.required' => 'department_id không được để trống',
//            'department_name.max' => 'tên phòng ban tối đa 500 ký tự',
//
//            'position_id.required' => 'position_id không được để trống',
//            'position_name.max' => 'tên chức vụ tối đa 500 ký tự',

        ];
    }
}
