<?php

namespace App\Http\Controllers;

use App\Http\Requests\themCanHoRequest;
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
    public function getDataClient()
    {
        $canho = CanHo::all();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $canho
        ]);
    }

    public function themCanHo(themCanHoRequest $request)
    {
        CanHo::create([
           'tang'       => $request->tang,
           'so_can_ho'  => $request->so_can_ho,
           'chu_ho'     => $request->chu_ho,
           'ten_toa_nha'     => $request->ten_toa_nha,
        ]);
        return response()->json([
            'status'   => true,
            'message'  => 'Bạn thêm Căn Hộ thành công!',
        ]);
    }
    public function capnhatCanHo(Request $request)
    {
        $canho = CanHo::find($request->id);
        if ($canho) {
            $canho->update([
                'tang'       => $request->tang,
                'so_can_ho'  => $request->so_can_ho,
                'chu_ho'     => $request->chu_ho,
                 'ten_toa_nha'     => $request->ten_toa_nha,

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
    public function xoaCanHo(Request $request)
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
