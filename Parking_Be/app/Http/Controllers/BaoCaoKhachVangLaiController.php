<?php

namespace App\Http\Controllers;

use App\Models\BaoCaoKhachVangLai;
use Illuminate\Http\Request;

class BaoCaoKhachVangLaiController extends Controller
{
    public function getData()
    {
        $data = BaoCaoKhachVangLai::join('admins', 'admins.id','bao_cao_khach_vang_lais.id_admin')
                                ->join('chi_tiet_bai_xes', 'chi_tiet_bai_xes.id','bao_cao_khach_vang_lais.id_vi_tri_trong_bai')
                                ->join('bai_xes', 'bai_xes.id','chi_tiet_bai_xes.id_bai_xe')
                                ->select('bao_cao_khach_vang_lais.*', 'admins.ho_va_ten as ten_admin', 'chi_tiet_bai_xes.thu_tu', 'bai_xes.ten_bai_xe','bai_xes.ten_bai')
                                ->get();


        return response()->json([
            'data' => $data,
            'message' => 'Lấy dữ liệu thành công'
        ]);
    }

    public function themBaoCaoKhachVangLai(Request $request)
    {
        $user = $this->isAdmin();
        $data = BaoCaoKhachVangLai::create([
                    'id_admin'              => $user->id,
                    'ho_va_ten'             => $request->ho_va_ten,
                    'so_dien_thoai'         => $request->so_dien_thoai,
                    'thoi_gian_vao'         => $request->thoi_gian_vao,
                    'thoi_gian_ra'          => $request->thoi_gian_ra,
                    'tien_thanh_toan'       => $request->tien_thanh_toan,
                    'is_thanh_toan'         => $request->is_thanh_toan,
                    'id_vi_tri_trong_bai'   => $request->id_vi_tri_trong_bai
        ]);
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Thêm dữ liệu thành công'
        ]);
    }

    public function capnhatBaoCaoKhachVangLai(Request $request)
    {
        $user = $this->isAdmin();
        $data = BaoCaoKhachVangLai::find($request->id);
        $data->update([
            'id_admin' => $user->id,
            'ho_va_ten' => $request->ho_va_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'thoi_gian_vao' => $request->thoi_gian_vao,
            'thoi_gian_ra' => $request->thoi_gian_ra,
            'tien_thanh_toan' => $request->tien_thanh_toan,
            'is_thanh_toan' => $request->is_thanh_toan,
            'id_vi_tri_trong_bai' => $request->id_vi_tri_trong_bai
        ]);
        $data->save();
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Cập nhật dữ liệu thành công'
        ]);
    }

    public function xoaBaoCaoKhachVangLai(Request $request)
    {
        $data = BaoCaoKhachVangLai::find($request->id);
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Xóa dữ liệu thành công'
        ]);
    }

    public function thanhToanBaoCaoKhachVangLai(Request $request)
    {
        $data = BaoCaoKhachVangLai::find($request->id);
        $data->update([
            'is_thanh_toan' => 1
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thanh toán dữ liệu thành công'
        ]);
    }
}
