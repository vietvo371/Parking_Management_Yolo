<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhapXeRequest;
use App\Http\Requests\ThemXeRequest;
use App\Models\Xe;
use Illuminate\Http\Request;

class XeController extends Controller
{
    public function getData()
    {
        $xe = Xe::all();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $xe
        ]);
    }
    public function themXe(ThemXeRequest $request)
    {
        Xe::create([
           'id_cu_dan'          => $request->id_cu_dan,
           'bien_so_xe'         => $request->bien_so_xe,
           'id_loai_xe'         => $request->id_loai_xe,
           'trang_thai_duyet'   => $request->trang_thai_duyet,
           'is_con_han'         => $request->is_con_han,
        ]);
        return response()->json([
            'status'   => true,
            'message'  => 'Bạn thêm Xe thành công!',
        ]);
    }
    public function capnhatXe(CapNhapXeRequest $request)
    {
        $xe = Xe::find($request->id);
        if ($xe) {
            $xe->update([
                'id_cu_dan'          => $request->id_cu_dan,
                'bien_so_xe'         => $request->bien_so_xe,
                'id_loai_xe'         => $request->id_loai_xe,
                'trang_thai_duyet'   => $request->trang_thai_duyet,
                'is_con_han'         => $request->is_con_han,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn cập nhật Xe thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Xe không tồn tại!',
            ]);
        }
    }
    public function doiTrangThaiXe(Request $request)
    {
        $xe = Xe::find($request->id);
        if ($xe) {
            $xe->update([
                'trang_thai_duyet' => !$request->trang_thai_duyet,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn cập nhật trạng thái thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Xe không tồn tại!',
            ]);
        }
    }
    public function xoaXe(Request $request)
    {
        $xe = Xe::find($request->id);
        if ($xe) {
            $xe->delete();
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn đã xóa Xe thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Xe không tồn tại!',
            ]);
        }
    }
}
