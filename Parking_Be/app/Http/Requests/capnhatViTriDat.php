<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class capnhatViTriDat extends FormRequest
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
            'id' => 'required|integer|exists:vi_tri_dats,id',
            'vi_tri_dat' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID là trường bắt buộc.',
            'id.integer' => 'ID phải là một số nguyên.',
            'id.exists' => 'ID không tồn tại trong cơ sở dữ liệu.',
            'vi_tri_dat.required' => 'Vị trí đặt là trường bắt buộc.',
            'vi_tri_dat.string' => 'Vị trí đặt phải là một chuỗi.',
            'vi_tri_dat.max' => 'Vị trí đặt không được vượt quá 255 ký tự.',
        ];
    }
}
