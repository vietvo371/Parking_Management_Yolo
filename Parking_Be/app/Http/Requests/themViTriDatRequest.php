<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class themViTriDatRequest extends FormRequest
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
            'vi_tri_dat' => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'vi_tri_dat.required' => 'Vị trí đặt không được để trống',
            'vi_tri_dat.string' => 'Vị trí đặt phải là chuỗi',
            'vi_tri_dat.max' => 'Vị trí đặt không được vượt quá 255 ký tự',
        ];
    }
}
