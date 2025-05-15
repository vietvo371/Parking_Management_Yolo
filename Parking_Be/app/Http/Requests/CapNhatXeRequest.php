<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapNhatXeRequest extends FormRequest
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
            'id' => 'required|exists:xes,id',
            'bien_so_xe' => 'required|string|max:255|unique:xes,bien_so_xe,' . $this->id,
            'id_loai_xe' => 'required|exists:loai_xes,id',
        ];
    }
    public function messages(): array
    {
        return [
            'bien_so_xe.required' => 'Biển số xe là bắt buộc',
            'id_loai_xe.required' => 'Loại xe là bắt buộc',
            'id_loai_xe.exists' => 'Loại xe không tồn tại',
            'bien_so_xe.max' => 'Biển số xe không được vượt quá 255 ký tự',
            'bien_so_xe.unique' => 'Biển số xe đã tồn tại',
        ];
    }
}
