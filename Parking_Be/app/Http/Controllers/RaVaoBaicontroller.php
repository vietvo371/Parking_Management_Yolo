<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemKhachVangLaiRequest;
use App\Models\BaoCaoKhachVangLai;
use App\Models\ChiTietBaiXe;
use App\Models\LichSuRaVaoBaiXe;
use App\Models\Xe;
use Illuminate\Http\Request;

class RaVaoBaicontroller extends Controller
{
    public function ghiNhanXeVao(Request $request)
    {
        $id_chuc_nang = 9;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $user = $this->isAdmin();

        $vitri = ChiTietBaiXe::find($request->id_vi_tri_trong_bai);
        $xe = Xe::where('bien_so_xe', $request->bien_so_xe)->first();
        $data = LichSuRaVaoBaiXe::create([
            'id_xe_cu_dan' => $xe->id,
            'id_camera_quet' => 1,
            'thoi_gian_vao' => now()->setTimezone('Asia/Ho_Chi_Minh'),
            'thoi_gian_ra' => null,
            'id_vi_tri_trong_bai' => $vitri->id
        ]);
        $vitri->trang_thai = ChiTietBaiXe::TRANG_THAI_DA_CO_XE;
        $vitri->is_cu_dan = 1;
        $vitri->save();
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Thêm dữ liệu thành công'
        ]);
    }
    public function datTruoc(Request $request)
    {
        $id_chuc_nang = 9;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data = ChiTietBaiXe::find($request->id);
        if ($data->trang_thai == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Vị trí này đã được đặt trước',
            ]);
        }
        $data->trang_thai = ChiTietBaiXe::TRANG_THAI_DA_DAT_TRUOC;
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Đặt trước thành công',
        ]);
    }
    public function ghiNhanXeRa(Request $request)
    {
        $id_chuc_nang = 9;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        if ($request->is_khach_hang === 1) {
            return response()->json([
                'status' => false,
                'message' => 'Khách vãng không được ghi nhận ở đây!',
            ]);
        }

        $lichSuRaVao = LichSuRaVaoBaiXe::where('id_vi_tri_trong_bai', $request->id_vi_tri_trong_bai)
            ->whereNull('thoi_gian_ra')
            ->first();

        if (!$lichSuRaVao) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy dữ liệu',
            ]);
        }
        $data = ChiTietBaiXe::find($request->id_vi_tri_trong_bai);
        $data->trang_thai = ChiTietBaiXe::TRANG_THAI_TRONG;
        $data->save();


        $lichSuRaVao->thoi_gian_ra = now()->setTimezone('Asia/Ho_Chi_Minh');
        $lichSuRaVao->save();
        return response()->json([
            'status' => true,
            'message' => 'Ghi nhận xe ra thành công',
        ]);
    }
}
