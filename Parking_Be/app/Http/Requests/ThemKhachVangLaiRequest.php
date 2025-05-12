<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemKhachVangLaiRequest extends FormRequest
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
            'ho_va_ten'             => 'required|string|max:255',
            'so_dien_thoai'         => 'required|numeric|digits:10',
            'id_vi_tri_trong_bai'   => 'required|exists:chi_tiet_bai_xes,id'
        ];
    }
    public function messages(): array
    {
        return [
            'ho_va_ten.required' => 'Họ và tên khách hàng là bắt buộc',
            'ho_va_ten.string' => 'Họ và tên khách hàng phải là chuỗi',
            'ho_va_ten.max' => 'Họ và tên khách hàng không được vượt quá 255 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại khách hàng là bắt buộc',
            'so_dien_thoai.numeric' => 'Số điện thoại khách hàng phải là số',
            'so_dien_thoai.digits' => 'Số điện thoại khách hàng phải có 10 chữ số',
            'id_vi_tri_trong_bai.required' => 'Vị trí trong bãi là bắt buộc',
            'id_vi_tri_trong_bai.exists' => 'Vị trí trong bãi không tồn tại'
        ];
    }
}
