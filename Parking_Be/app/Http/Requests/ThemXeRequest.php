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
            'bien_so_xe'         => 'required|string|max:255|unique:xes,bien_so_xe|regex:/^[0-9]{2}-[A-Z][0-9][ ][0-9]{3}\.[0-9]{2}$/',
            'id_loai_xe'         => 'required|integer',
        ];
    }
    public function messages(): array
    {
        return [
            'id_cu_dan.required'         => 'cư dân là bắt buộc.',
            'id_cu_dan.integer'         => 'cư dân là là integer.',
            'bien_so_xe.required'        => 'Biển số xe là bắt buộc.',
            'bien_so_xe.string'        => 'Biển số là ký tự nhỏ hơn 225.',
            'id_loai_xe.required'        => 'loại xe là bắt buộc.',
            'id_loai_xe.integer'        => 'loại xe là integer.',
            'bien_so_xe.regex'        => 'Biển số xe không hợp lệ.',
            'bien_so_xe.unique'        => 'Biển số xe đã tồn tại.',
        ];
    }
}
