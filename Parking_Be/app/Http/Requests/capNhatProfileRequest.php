<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class capNhatProfileRequest extends FormRequest
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
            'so_dien_thoai' => 'required|string|min:10|max:11',
        ];
    }
    public function messages(): array
    {
        return [
            'ho_va_ten.required' => 'Tên cư dân là bắt buộc',
            'ho_va_ten.string' => 'Tên cư dân phải là chữ',
            'ho_va_ten.max' => 'Tên cư dân phải có tối đa 255 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc',
            'so_dien_thoai.min' => 'Số điện thoại phải có ít nhất 10 số',
            'so_dien_thoai.max' => 'Số điện thoại phải có tối đa 11 số',
        ];
    }
}
