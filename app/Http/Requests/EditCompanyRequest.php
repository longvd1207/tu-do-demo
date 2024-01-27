<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCompanyRequest extends FormRequest
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
            'code' => 'required',
            'name' => 'required|string',
            'phone' => 'numeric|digits:10'
        ];
    }
    public function messages()
    {
        return [
            'code.required' => 'Mã công ty không được để trống',
            'name.required' => 'Tên công ty không được để trống',
            'name.string' => 'Tên công ty không lệ',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.digits' => 'Số điện thoại không hợp lệ',
        ];
    }
}
