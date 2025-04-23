<?php

namespace App\Http\Controllers;

use App\Models\CanHo;
use Illuminate\Http\Request;

class CanHoController extends Controller
{
    public function getData()
    {
        $canho = CanHo::all();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $canho
        ]);
    }
    public function themCuDan(Request $request)
    {
        CanHo::create([
           'tang'       => $request->tang,
           'so_can_ho'  => $request->so_can_ho,
           'chu_ho'     => $request->chu_ho,
        ]);
        return response()->json([
            'status'   => true,
            'message'  => 'Bạn thêm Căn Hộ thành công!',
        ]);
    }
    public function capnhatCuDan(Request $request)
    {
        $canho = CanHo::find($request->id);
        if ($canho) {
            $canho->update([
                'tang'       => $request->tang,
                'so_can_ho'  => $request->so_can_ho,
                'chu_ho'     => $request->chu_ho,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn cập nhật Căn Hộ thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Căn Hộ không tồn tại!',
            ]);
        }
    }
    public function xoaCuDan(Request $request)
    {
        $canho = CanHo::find($request->id);
        if ($canho) {
            $canho->delete();
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn đã xóa Căn Hộ thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Căn Hộ không tồn tại!',
            ]);
        }
    }
}
