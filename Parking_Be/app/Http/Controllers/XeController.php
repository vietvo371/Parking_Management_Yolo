<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhapXeRequest;
use App\Http\Requests\CapNhatXeRequest;
use App\Http\Requests\DangKyXeRequest;
use App\Http\Requests\ThemXeRequest;
use App\Models\Xe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XeController extends Controller
{
    public function getData()
    {
        $xe = Xe::join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
        ->join('loai_xes', 'xes.id_loai_xe', '=', 'loai_xes.id')
        ->join('can_hos', 'cu_dans.id_can_ho', '=', 'can_hos.id')
        ->select('xes.*', 'cu_dans.ho_va_ten', 'loai_xes.ten_loai_xe', 'can_hos.so_can_ho', 'can_hos.ten_toa_nha')
        ->orderBy('xes.created_at', 'desc')
        ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $xe
        ]);
    }
    public function getDataClient()
    {
        $user = $this->isCuDan();
        $xe = Xe::join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
        ->join('loai_xes', 'xes.id_loai_xe', '=', 'loai_xes.id')
        ->join('can_hos', 'cu_dans.id_can_ho', '=', 'can_hos.id')
        ->leftJoin('giao_diches', 'xes.id', '=', 'giao_diches.id_xe')
        ->where('xes.id_cu_dan', $user->id)
        ->select('xes.*', 'cu_dans.ho_va_ten', 'loai_xes.ten_loai_xe', 'can_hos.so_can_ho', 'can_hos.ten_toa_nha',
            DB::raw('MAX(giao_diches.ngay_het_han) as ngay_het_han'))
        ->groupBy('xes.id', 'cu_dans.ho_va_ten', 'loai_xes.ten_loai_xe', 'can_hos.so_can_ho', 'can_hos.ten_toa_nha',
            'xes.id_cu_dan', 'xes.bien_so_xe', 'xes.id_loai_xe', 'xes.trang_thai_duyet', 'xes.is_con_han', 'xes.created_at', 'xes.updated_at')
        ->orderBy('xes.created_at', 'desc')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $xe
        ]);
    }
    public function xoaXeClient($id)
    {
        $user = $this->isCuDan();
        $xe = Xe::where('id_cu_dan', $user->id)->where('id', $id)->first();
        if (!$xe->is_con_han) {
            $xe->delete();
            return response()->json([
                'status' => true,
                'message' => 'Bạn đã xóa xe thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Xe vẫn còn hạn sử dụng không thể xoá',
            ]);
        }
    }

    public function dangKyXe(DangKyXeRequest $request)
    {
        $user = $this->isCuDan();
        $xe = Xe::create([
            'id_cu_dan' => $user->id,
            'bien_so_xe' => $request->bien_so_xe,
            'id_loai_xe' => $request->id_loai_xe,
            'trang_thai_duyet' => 0,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Bạn đã đăng ký xe thành công',
        ]);
    }
    public function capNhatXeClient(CapNhatXeRequest $request)
    {
        $user = $this->isCuDan();
        $xe = Xe::where('id_cu_dan', $user->id)->where('id', $request->id)->first();
        if ($xe) {
            $xe->update([
                'bien_so_xe' => $request->bien_so_xe,
                'id_loai_xe' => $request->id_loai_xe,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Bạn đã cập nhật xe thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Xe không tồn tại',
            ]);
        }
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
