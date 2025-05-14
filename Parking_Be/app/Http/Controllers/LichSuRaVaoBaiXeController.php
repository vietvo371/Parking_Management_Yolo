<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemKhachVangLaiRequest;
use App\Models\BaoCaoKhachVangLai;
use App\Models\ChiTietBaiXe;
use App\Models\LichSuRaVaoBaiXe;
use Illuminate\Http\Request;

class LichSuRaVaoBaiXeController extends Controller
{
    public function getLichSuRaVao(Request $request)
    {

        $lichSuRaVao = LichSuRaVaoBaiXe::join('xes', 'xes.id', '=', 'lich_su_ra_vao_bai_xes.id_xe')
            ->join('cu_dans', 'cu_dans.id', '=', 'xes.id_cu_dan')
            ->select(
                'lich_su_ra_vao_bai_xes.*',
                'xes.bien_so',
                'cu_dans.ho_ten',
                'cu_dans.so_dien_thoai'
            )
            ->orderBy('lich_su_ra_vao_bai_xes.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lịch sử ra vào bãi xe',
            'data' => $lichSuRaVao
        ]);
    }

    public function ghiNhanXeVao(ThemKhachVangLaiRequest $request)
    {
        $user = $this->isAdmin();
        $vitri = ChiTietBaiXe::find($request->id_vi_tri_trong_bai);
        $data = BaoCaoKhachVangLai::create([
                    'id_admin'              => $user->id,
                    'ho_va_ten'             => $request->ho_va_ten,
                    'so_dien_thoai'         => $request->so_dien_thoai,
                    'thoi_gian_vao'         => now()->setTimezone('Asia/Ho_Chi_Minh'),
                    'id_vi_tri_trong_bai'   => $vitri->id
        ]);
        $vitri->trang_thai = ChiTietBaiXe::TRANG_THAI_DA_CO_XE;
        $vitri->is_khach_hang = 1;
        $vitri->save();
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Thêm dữ liệu thành công'
        ]);
    }
    public function datTruoc(Request $request)
    {
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
        $data = ChiTietBaiXe::find($request->id_vi_tri_trong_bai);
        $data->trang_thai = ChiTietBaiXe::TRANG_THAI_TRONG;
        $data->save();
        return response()->json([
            'status' => true,
            'message' => 'Ghi nhận xe ra thành công',
        ]);
    }
}
