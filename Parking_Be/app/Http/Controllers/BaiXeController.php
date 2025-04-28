<?php

namespace App\Http\Controllers;

use App\Http\Requests\capnhatBaiXeRequest;
use App\Http\Requests\themBaiXeRequest;
use App\Models\BaiXe;
use Illuminate\Http\Request;

class BaiXeController extends Controller
{
    public function getData()
    {
        $baiXe = BaiXe::where('trang_thai', 1)->get();
        return response()->json([
            'data' => $baiXe,
        ]);
    }
    public function themBaiXe(themBaiXeRequest $request)
    {
        $baiXe = BaiXe::create([
            'ten_bai' => $request->ten_bai,
            'suc_chua_o_to' => $request->suc_chua_o_to,
            'suc_chua_khac' => $request->suc_chua_khac,
            'trang_thai' => 1,
        ]);
        return response()->json([
            'message' => 'Thêm bãi xe thành công',
            'data' => $baiXe,
            'status' => true,
        ]);
    }
    public function capnhatBaiXe(capnhatBaiXeRequest $request)
    {
        $baiXe = BaiXe::find($request->id);
        if ($baiXe) {
            $baiXe->update([
                'ten_bai' => $request->ten_bai,
                'suc_chua_o_to' => $request->suc_chua_o_to,
                'suc_chua_khac' => $request->suc_chua_khac,
            ]);
            return response()->json([
                'message' => 'Cập nhật bãi xe thành công',
                'data' => $baiXe,
                'status' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'Bãi xe không tồn tại',
                'status' => false,
            ], 404);
        }
    }
    public function doiTrangThaiBaiXe(Request $request)
    {
        $baiXe = BaiXe::find($request->id);
        if ($baiXe) {
            $baiXe->update([
                'trang_thai' => $request->trang_thai,
            ]);
            return response()->json([
                'message' => 'Đổi trạng thái bãi xe thành công',
                'data' => $baiXe,
                'status' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'Bãi xe không tồn tại',
            ], 404);
        }
    }
    public function xoaBaiXe($id)
    {
        $baiXe = BaiXe::find($id)->delete();

        return response()->json([
            'message' => 'Xóa bãi xe thành công',
            'status' => true,
        ]);
    }
}
