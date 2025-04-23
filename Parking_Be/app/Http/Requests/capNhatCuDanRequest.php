<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class capNhatCuDanRequest extends FormRequest
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
            'id' => 'required|integer|exists:cu_dans,id',
            'ho_va_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:cu_dans,email,' . $this->id,
            'password' => 'required|string|min:8|confirmed',
            'so_dien_thoai' => 'required|string|max:15',
            'so_cccd' => 'required|string|max:20|unique:cu_dans,so_cccd,' . $this->id,
            'id_can_ho' => 'required|integer|exists:can_ho,id',
            'so_du' => 'required|numeric|min:0'
        ];
    }
}
