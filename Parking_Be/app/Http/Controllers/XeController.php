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
        $xe = Xe::join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
        ->join('loai_xes', 'xes.id_loai_xe', '=', 'loai_xes.id')
        ->join('can_hos', 'cu_dans.id_can_ho', '=', 'can_hos.id')
        ->select('xes.*', 'cu_dans.ho_va_ten', 'loai_xes.ten_loai_xe', 'can_hos.so_can_ho', 'can_hos.ten_toa_nha')
        ->get();
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
    public function duyetXe(Request $request)
    {
        $xe = Xe::find($request->id);
        if ($xe) {
            $xe->update([
                'trang_thai_duyet' => !$request->trang_thai_duyet,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn đã duyệt Xe thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Xe không tồn tại!',
            ]);
        }
    }

}
