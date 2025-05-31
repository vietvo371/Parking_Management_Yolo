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
            'ten_bai' => 'required|string|max:255',
            'suc_chua_o_to' => 'required|integer',
            'suc_chua_khac' => 'required|integer',
        ];
    }
    public function messages(): array
    {
        return [
            'ten_bai.required' => 'Tên bãi xe là bắt buộc.',
            'ten_bai.string' => 'Tên bãi xe phải là một chuỗi.',
            'ten_bai.max' => 'Tên bãi xe không được vượt quá 255 ký tự.',
            'suc_chua_o_to.required' => 'Sức chứa ô tô là bắt buộc.',
            'suc_chua_o_to.integer' => 'Sức chứa ô tô phải là một số nguyên.',
            'suc_chua_khac.required' => 'Sức chứa khác là bắt buộc.',
            'suc_chua_khac.integer' => 'Sức chứa khác phải là một số nguyên.',
        ];
    }
}
