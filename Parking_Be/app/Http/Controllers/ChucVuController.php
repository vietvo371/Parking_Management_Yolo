<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaoChucVuRequest;
use App\Models\ChucVu;
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function getData()
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $chucvu = ChucVu::all();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $chucvu
        ]);
    }
    public function themChucVu(TaoChucVuRequest $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        ChucVu::create([
            'ten_chuc_vu'   => $request->ten_chuc_vu,
            'slug_chuc_vu'  => $request->slug_chuc_vu,
            'tinh_trang'    => $request->tinh_trang,
        ]);
        return response()->json([
            'status'   => true,
            'message'  => 'Bạn thêm Chức Vụ thành công!',
        ]);
    }
    public function timChucVu(Request $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $key    = '%' . $request->key . '%';
        $dataAdmin   = ChucVu::select('chuc_vus.*')
            ->where('ten_chuc_vu', 'like', $key)
            ->get(); // get là ra 1  sách


        return response()->json([
            'chuc_vu_admin'  =>  $dataAdmin,
        ]);
    }
    public function capnhatChucVu(Request $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $chucvu = ChucVu::find($request->id);
        if ($chucvu) {
            $chucvu->update([
                'ten_chuc_vu'   => $request->ten_chuc_vu,
                'tinh_trang'    => $request->tinh_trang,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn cập nhật Chức Vụ thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Chức Vụ không tồn tại!',
            ]);
        }
    }
    public function doiTrangThaiChucVu(Request $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $chucvu = ChucVu::find($request->id);
        if ($chucvu) {
            $chucvu->update([
                'tinh_trang' => !$request->tinh_trang,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn cập nhật trạng thái thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Chức Vụ không tồn tại!',
            ]);
        }
    }
    public function xoaChucVu(Request $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $chucvu = ChucVu::find($request->id);
        if ($chucvu) {
            $chucvu->delete();
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn đã xóa Chức Vụ thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Chức Vụ không tồn tại!',
            ]);
        }
    }
}
