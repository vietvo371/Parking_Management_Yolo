<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
            'email' => 'required|email|unique:admins,email,' . $this->id,
            'ho_va_ten'      => 'required|string|max:255',
            'so_dien_thoai'  => 'required|numeric|digits_between:10,15',
            'id_chuc_vu'     => 'required|integer|exists:chuc_vus,id',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'ho_va_ten.required' => 'Họ và tên không được để trống',
            'ho_va_ten.string' => 'Họ và tên phải là chuỗi',
            'ho_va_ten.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống',
            'so_dien_thoai.numeric' => 'Số điện thoại phải là số',
            'so_dien_thoai.digits_between' => 'Số điện thoại phải có từ 10 đến 15 chữ số',
            'id_chuc_vu.required' => 'Chức vụ không được để trống',
            'id_chuc_vu.integer' => 'Chức vụ phải là số nguyên',
            'id_chuc_vu.exists' => 'Chức vụ không tồn tại trong cơ sở dữ liệu',
        ];
    }
}
