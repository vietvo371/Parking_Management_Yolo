<?php

namespace App\Http\Controllers;

use App\Models\BaoCaoKhachVangLai;
use App\Models\ChiTietBaiXe;
use App\Models\BaiXe;
use Illuminate\Http\Request;
use App\Http\Requests\ThemKhachVangLaiRequest;
class BaoCaoKhachVangLaiController extends Controller
{
    public function getData()
    {
        $data = BaoCaoKhachVangLai::join('admins', 'admins.id','bao_cao_khach_vang_lais.id_admin')
                                ->join('chi_tiet_bai_xes', 'chi_tiet_bai_xes.id','bao_cao_khach_vang_lais.id_vi_tri_trong_bai')
                                ->join('bai_xes', 'bai_xes.id','chi_tiet_bai_xes.id_bai_xe')
                                ->select('bao_cao_khach_vang_lais.*', 'admins.ho_va_ten as ten_admin', 'chi_tiet_bai_xes.thu_tu','chi_tiet_bai_xes.loai_xe', 'bai_xes.ten_bai','bai_xes.ten_bai')
                                ->get();
        $chiTietBaiXes = ChiTietBaiXe::where('trang_thai', 0)->get();
        $baiXes = BaiXe::where('trang_thai', 1)->get();
        return response()->json([
            'data' => $data,
            'chiTietBaiXes' => $chiTietBaiXes,
            'message' => 'Lấy dữ liệu thành công'
        ]);
    }

    public function themBaoCaoKhachVangLai(ThemKhachVangLaiRequest $request)
    {

        $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $user = $this->isAdmin();
        $data = BaoCaoKhachVangLai::create([
                    'id_admin'              => $user->id,
                    'ho_va_ten'             => $request->ho_va_ten,
                    'so_dien_thoai'         => $request->so_dien_thoai,
                    'thoi_gian_vao'         => now()->setTimezone('Asia/Ho_Chi_Minh'),
                    'id_vi_tri_trong_bai'   => $request->id_vi_tri_trong_bai
        ]);
        $viTri = ChiTietBaiXe::find($request->id_vi_tri_trong_bai);
        $viTri->update([
            'trang_thai' => ChiTietBaiXe::TRANG_THAI_KHACH_VANG_LAI
        ]);
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Thêm dữ liệu thành công'
        ]);
    }

    public function capnhatBaoCaoKhachVangLai(Request $request)
    {
         $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
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
         $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
         $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $user = $this->isAdmin();
        if($user->is_master == 0){
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này'
            ]);
        }

        $data = BaoCaoKhachVangLai::where('id', $request->id)->first();
        if($data->is_thanh_toan == 1){
            return response()->json([
                'status' => false,
                'message' => 'Khách hàng đã thanh toán, không thể xóa dữ liệu'
            ]);
        }
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Xóa dữ liệu thành công'
        ]);
    }

    public function thanhToanBaoCaoKhachVangLai(Request $request)
    {
         $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data = BaoCaoKhachVangLai::find($request->id);
        $data->update([
            'is_thanh_toan' => 1,
            'thoi_gian_ra' => \Carbon\Carbon::parse($request->thoi_gian_ra)->setTimezone('Asia/Ho_Chi_Minh'),
            'tien_thanh_toan' => $request->tien_thanh_toan
        ]);
        $viTri = ChiTietBaiXe::find($data->id_vi_tri_trong_bai);
        $viTri->update([
            'trang_thai' => ChiTietBaiXe::TRANG_THAI_TRONG
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thanh toán dữ liệu thành công'
        ]);
    }
}
