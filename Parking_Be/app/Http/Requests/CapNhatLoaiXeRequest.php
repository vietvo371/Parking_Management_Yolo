<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapNhatLoaiXeRequest extends FormRequest
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
            'ten_loai_xe' => 'required|string|max:255',
            'tien_thu_giu_xe' => 'required|numeric|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'ten_loai_xe.required' => 'Tên loại xe là bắt buộc',
            'tien_thu_giu_xe.required' => 'Tiền thu giữ xe là bắt buộc',
            'tien_thu_giu_xe.numeric' => 'Tiền thu giữ xe phải là số',
            'tien_thu_giu_xe.min' => 'Tiền thu giữ xe phải lớn hơn 0',
        ];
    }
}