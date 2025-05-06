<?php

namespace App\Http\Controllers;

use App\Http\Requests\capnhatBaiXeRequest;
use App\Http\Requests\themBaiXeRequest;
use App\Models\BaiXe;
use App\Models\ChiTietBaiXe;
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
        $lastChar = mb_substr(trim($request->ten_bai), -1);
        for ($i = 1; $i <= $request->suc_chua_o_to; $i++) {
            ChiTietBaiXe::create([
                'id_bai_xe' => $baiXe->id,
                'thu_tu' => $lastChar . $i,
                'loai_xe' => 'O-To',
            ]);
        }
        for ($i = 1; $i <= $request->suc_chua_khac; $i++) {
            ChiTietBaiXe::create([
                'id_bai_xe' => $baiXe->id,
                'thu_tu' => $lastChar . $i,
                'loai_xe' => 'Khác',
            ]);
        }

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

            // Xóa tất cả chi tiết bãi xe cũ
            ChiTietBaiXe::where('id_bai_xe', $baiXe->id)->delete();

            // Tạo lại chi tiết bãi xe mới
            $lastChar = mb_substr(trim($request->ten_bai), -1);

            // Tạo chi tiết cho ô tô
            for ($i = 1; $i <= $request->suc_chua_o_to; $i++) {
                ChiTietBaiXe::create([
                    'id_bai_xe' => $baiXe->id,
                    'thu_tu' => $lastChar . $i,
                    'loai_xe' => 'O-To',
                    'trang_thai' => 1
                ]);
            }

            // Tạo chi tiết cho xe khác
            for ($i = 1; $i <= $request->suc_chua_khac; $i++) {
                ChiTietBaiXe::create([
                    'id_bai_xe' => $baiXe->id,
                    'thu_tu' => $lastChar . $i,
                    'loai_xe' => 'Khác',
                    'trang_thai' => 1
                ]);
            }

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

            // Cập nhật trạng thái của tất cả chi tiết bãi xe
            ChiTietBaiXe::where('id_bai_xe', $baiXe->id)
                ->update(['trang_thai' => $request->trang_thai]);

            return response()->json([
                'message' => 'Đổi trạng thái bãi xe thành công',
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
    public function xoaBaiXe($id)
    {
        // Xóa chi tiết bãi xe trước
        ChiTietBaiXe::where('id_bai_xe', $id)->delete();

        // Sau đó xóa bãi xe
        $baiXe = BaiXe::find($id);
        if ($baiXe) {
            $baiXe->delete();
            return response()->json([
                'message' => 'Xóa bãi xe thành công',
                'status' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'Bãi xe không tồn tại',
                'status' => false,
            ], 404);
        }
    }
}
