<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemCuDanRequest extends FormRequest
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
            'ho_va_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:cu_dans,email',
            'password' => 'required|string|min:8|confirmed',
            'so_dien_thoai' => 'required|string|max:15',
            'so_cccd' => 'required|string|max:20|unique:cu_dans,so_cccd',
            'id_can_ho' => 'required|integer|exists:can_ho,id',
            'so_du' => 'required|numeric|min:0',
        ];
    }
    public function messages()
    {
        return [
            'ho_va_ten.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_cccd.required' => 'Số CCCD là bắt buộc.',
            'so_cccd.unique' => 'Số CCCD đã tồn tại.',
            'id_can_ho.required' => 'ID căn hộ là bắt buộc.',
            'id_can_ho.exists' => 'ID căn hộ không tồn tại.',
            'so_du.required' => 'Số dư là bắt buộc.',
        ];
    }
}
