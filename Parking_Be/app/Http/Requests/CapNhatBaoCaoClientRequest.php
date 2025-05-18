<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapNhatBaoCaoClientRequest extends FormRequest
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
            'id' => 'required|integer|exists:bao_cao_su_cos,id',
            'noi_dung_bao_cao' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID báo cáo là bắt buộc',
            'id.integer' => 'ID báo cáo phải là số nguyên',
            'id.exists' => 'ID báo cáo không tồn tại',
            'noi_dung_bao_cao.required' => 'Nội dung báo cáo là bắt buộc',
            'noi_dung_bao_cao.string' => 'Nội dung báo cáo phải là chuỗi',
            'noi_dung_bao_cao.max' => 'Nội dung báo cáo không được vượt quá 255 ký tự',
        ];
    }
}
