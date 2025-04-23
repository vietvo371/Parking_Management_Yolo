<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class themBaiXeRequest extends FormRequest
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
            'ten_bai_xe' => 'required|string|max:255',
            'dia_chi' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'ten_bai_xe.required' => 'Tên bãi xe là bắt buộc.',
            'ten_bai_xe.string' => 'Tên bãi xe phải là một chuỗi.',
            'ten_bai_xe.max' => 'Tên bãi xe không được vượt quá 255 ký tự.',
            'dia_chi.required' => 'Địa chỉ là bắt buộc.',
            'dia_chi.string' => 'Địa chỉ phải là một chuỗi.',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ];
    }
}
