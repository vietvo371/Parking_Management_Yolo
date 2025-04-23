<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemXeRequest extends FormRequest
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
            'id_cu_dan'          => 'required|integer',
            'bien_so_xe'         => 'required|string|max:255',
            'id_loai_xe'         => 'required|integer',
            'trang_thai_duyet'   => 'required|integer',
            'is_con_han'         => 'required|integer',
        ];
    }
    public function messages(): array
    {
        return [
            'id_cu_dan.required'         => 'ID cư dân là bắt buộc.',
            'id_cu_dan.integer'         => 'ID cư dân là là integer.',
            'bien_so_xe.required'        => 'Biển số xe là bắt buộc.',
            'bien_so_xe.string'        => 'Biển số là ký tự nhỏ hơn 225.',
            'id_loai_xe.required'        => 'ID loại xe là bắt buộc.',
            'trang_thai_duyet.required'  => 'Trạng thái duyệt là bắt buộc.',
            'is_con_han.required'        => 'Còn hạn là bắt buộc.',

        ];
    }
}
