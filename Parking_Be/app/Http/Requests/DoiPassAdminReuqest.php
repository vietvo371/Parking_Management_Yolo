<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoiPassAdminReuqest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password'  =>  'required|min:6|max:30',
            'password'          =>  'required|min:6|max:30',
            're_password'       =>  'required|same:password',
        ];
    }
    public function messages()
    {
        return [
            'current_password.*'  =>  'Mật khẩu hiện tại không đúng',
            'password.*'          =>  'Mật khẩu mới yêu cầu phải từ 6 đến 30 ký tự',
            're_password.*'       =>  'Mật khẩu mới không trùng khớp',
        ];
    }
}
