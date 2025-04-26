<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatLoaiXeRequest;
use App\Http\Requests\ThemLoaiXeRequest;
use App\Models\LoaiXe;
use Illuminate\Http\Request;

class LoaiXeController extends Controller
{
    public function getData()
    {
        $loaiXe = LoaiXe::all();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $loaiXe
        ]);
    }
    public function themLoaiXe(ThemLoaiXeRequest $request)
    {
        $loaiXe = LoaiXe::create([
            'ten_loai_xe' => $request->ten_loai_xe,
            'tien_thu_giu_xe' => $request->tien_thu_giu_xe,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thêm loại xe thành công',
            'data' => $loaiXe
        ]);
    }
    public function capnhatLoaiXe(CapNhatLoaiXeRequest $request)
    {
        $loaiXe = LoaiXe::find($request->id);
        $loaiXe->update([
            'ten_loai_xe' => $request->ten_loai_xe,
            'tien_thu_giu_xe' => $request->tien_thu_giu_xe,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật loại xe thành công',
            'data' => $loaiXe
        ]);
    }

    public function doiTrangThaiLoaiXe(Request $request)
    {
        $loaiXe = LoaiXe::find($request->id);
        $loaiXe->trang_thai = $request->trang_thai;
        $loaiXe->save();
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật trạng thái loại xe thành công',
            'data' => $loaiXe
        ]);
    }
    public function xoaLoaiXe(Request $request)
    {
        $loaiXe = LoaiXe::find($request->id);
        if ($loaiXe) {
            $loaiXe->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa loại xe thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Loại xe không tồn tại',
            ]);
        }
    }
}
