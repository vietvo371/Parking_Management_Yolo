<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoiPassNhanSuReuqest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id'                    => 'required|exists:admins,id',
            'password'              => 'required|min:6|max:30',
            'password_confirmation' => 'required|same:password',
        ];
    }
    public function messages()
    {
        return [
            'id.required'                     => 'ID là bắt buộc',
            'id.exists'                       => 'ID không tồn tại',
            'password.required'                 => 'Mật khẩu là bắt buộc',
            'password.min'                      => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max'                      => 'Mật khẩu phải có tối đa 30 ký tự',
            'password_confirmation.required'    => 'Mật khẩu xác nhận là bắt buộc',
            'password_confirmation.same'        => 'Mật khẩu xác nhận không khớp',
        ];
    }
}
