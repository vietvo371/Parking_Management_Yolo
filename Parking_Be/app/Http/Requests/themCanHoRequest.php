<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class themCanHoRequest extends FormRequest
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
            'ten_toa_nha' => 'required|string|max:255',
            'tang'    => 'required|integer',
            'so_can_ho'  => 'required|string|max:255',
            'chu_ho'     => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'ten_toa_nha.required' => 'Tên tòa nhà không được để trống',
            'ten_toa_nha.string'  => 'Tên tòa nhà phải là chuỗi',
            'ten_toa_nha.max'     => 'Tên tòa nhà không được vượt quá 255 ký tự',
            'id_tang.required' => 'ID tầng không được để trống',
            'id_tang.integer'  => 'ID tầng phải là số nguyên',
            'so_can_ho.required' => 'Số căn hộ không được để trống',
            'so_can_ho.string'   => 'Số căn hộ phải là chuỗi',
            'so_can_ho.max'      => 'Số căn hộ không được vượt quá 255 ký tự',
            'chu_ho.required'    => 'Chủ hộ không được để trống',
            'chu_ho.string'      => 'Chủ hộ phải là chuỗi',
            'chu_ho.max'         => 'Chủ hộ không được vượt quá 255 ký tự',
        ];
    }
}
