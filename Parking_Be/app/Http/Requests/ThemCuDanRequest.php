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
            'so_dien_thoai' => 'required|string|max:15',
            'so_cccd' => 'required|string|max:20|unique:cu_dans,so_cccd',
            'id_can_ho' => 'required|integer|exists:can_hos,id',
        ];
    }
    public function messages()
    {
        return [
            'ho_va_ten.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_cccd.required' => 'Số CCCD là bắt buộc.',
            'so_cccd.unique' => 'Số CCCD đã tồn tại.',
            'id_can_ho.required' => 'ID căn hộ là bắt buộc.',
            'id_can_ho.exists' => 'ID căn hộ không tồn tại.',
        ];
    }
}
