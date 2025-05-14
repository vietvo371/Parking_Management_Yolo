<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemKhachVangLaiRequest;
use App\Models\BaoCaoKhachVangLai;
use App\Models\ChiTietBaiXe;
use App\Models\LichSuRaVaoBaiXe;
use Illuminate\Http\Request;

class LichSuRaVaoBaiXeController extends Controller
{
    public function getData(Request $request)
    {

        $lichSuRaVao = LichSuRaVaoBaiXe::join('xes', 'xes.id', '=', 'lich_su_ra_vao_bai_xes.id_xe_cu_dan')
            ->join('cu_dans', 'cu_dans.id', '=', 'xes.id_cu_dan')
            ->select(
                'lich_su_ra_vao_bai_xes.*',
                'xes.bien_so_xe',
                'cu_dans.ho_va_ten',
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
}
