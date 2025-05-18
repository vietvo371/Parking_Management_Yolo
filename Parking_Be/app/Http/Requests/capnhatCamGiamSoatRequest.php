<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class capnhatCamGiamSoatRequest extends FormRequest
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
            'id' => 'required|integer|exists:cam_giam_soats,id',
            'id_vi_tri' => 'required|integer|exists:vi_tri_dats,id',
            'id_bai_xe' => 'required|integer|exists:bai_xes,id',
            'trang_thai' => 'required|integer|in:0,1',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID là bắt buộc',
            'id.integer' => 'ID phải là số',
            'id.exists' => 'ID không tồn tại',
            'id_vi_tri.required' => 'Vị trí là bắt buộc',
            'id_vi_tri.integer' => 'Vị trí phải là số',
            'id_vi_tri.exists' => 'Vị trí không tồn tại',
            'id_bai_xe.required' => 'Bãi xe là bắt buộc',
            'id_bai_xe.integer' => 'Bãi xe phải là số',
            'id_bai_xe.exists' => 'Bãi xe không tồn tại',
            'trang_thai.required' => 'Trạng thái là bắt buộc',
            'trang_thai.integer' => 'Trạng thái phải là số',
            'trang_thai.in' => 'Trạng thái phải là 0 hoặc 1',
        ];
    }
}