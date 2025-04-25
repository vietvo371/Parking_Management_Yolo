<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class themAdminRequest extends FormRequest
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
            'email'          => 'required|email|unique:admin_animes,email',
            // 'email'          => 'required|email|unique:admins,email',
            'ho_va_ten'      => 'required|string|max:255',
            'so_dien_thoai'  => 'required|numeric|digits_between:10,15',
            'id_chuc_vu'     => 'required|integer|exists:chuc_vus,id',
            'password'       => 'required|string|min:6',
        ];
    }
    public function messages(): array
    {
        return [
            'email.required'         => 'Email không được để trống',
            'email.email'           => 'Email không đúng định dạng',
            'email.unique'          => 'Email đã tồn tại',
            'ho_va_ten.required'    => 'Họ và tên không được để trống',
            'ho_va_ten.string'      => 'Họ và tên không đúng định dạng',
            'ho_va_ten.max'         => 'Họ và tên không được quá 255 ký tự',
            'so_dien_thoai.required'=> 'Số điện thoại không được để trống',
            'so_dien_thoai.numeric' => 'Số điện thoại không đúng định dạng',
            'so_dien_thoai.digits_between' => 'Số điện thoại phải từ 10 đến 15 chữ số',
            'id_chuc_vu.required'   => 'Chức vụ không được để trống',
            'id_chuc_vu.integer'    => 'Chức vụ không đúng định dạng',
            'id_chuc_vu.exists'     => 'Chức vụ không tồn tại trong hệ thống',
            'password.required'     => 'Mật khẩu không được để trống',
            'password.string'       => 'Mật khẩu không đúng định dạng',
            'password.min'          => 'Mật khẩu phải có ít nhất 6 ký tự',
        ];
    }

}
