<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class capNhatCuDanRequest extends FormRequest
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
            'id' => 'required|integer|exists:cu_dans,id',
            'ho_va_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:cu_dans,email,' . $this->id,
            'so_dien_thoai' => 'required|string|max:15',
            'so_cccd' => 'required|string|max:20|unique:cu_dans,so_cccd,' . $this->id,
            'id_can_ho' => 'required|integer|exists:can_hos,id',
            'so_du' => 'required|numeric|min:0'
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID là bắt buộc.',
            'id.integer' => 'ID phải là số nguyên.',
            'id.exists' => 'ID không tồn tại.',
            'ho_va_ten.required' => 'Họ và tên là bắt buộc.',
            'ho_va_ten.string' => 'Họ và tên phải là chuỗi.',
            'ho_va_ten.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.string' => 'Số điện thoại phải là chuỗi.',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'so_cccd.required' => 'Số căn cước công dân là bắt buộc.',
            'so_cccd.string' => 'Số căn cước công dân phải là chuỗi.',
            'so_cccd.max' => 'Số căn cước công dân không được vượt quá 20 ký tự.',
            'so_cccd.unique' => 'Số căn cước công dân đã tồn tại.',
            'id_can_ho.required' => 'ID căn hộ là bắt buộc.',
            'id_can_ho.integer' => 'ID căn hộ phải là số nguyên.',
            'id_can_ho.exists' => 'ID căn hộ không tồn tại.',

        ];
    }
}
