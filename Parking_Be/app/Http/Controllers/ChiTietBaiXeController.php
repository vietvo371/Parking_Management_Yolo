<?php

namespace App\Http\Controllers;

use App\Models\ChiTietBaiXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChiTietBaiXeController extends Controller
{
   public function getData()
   {
      $chiTietBaiXe = DB::table('chi_tiet_bai_xes')
         ->join('bai_xes', 'chi_tiet_bai_xes.id_bai_xe', '=', 'bai_xes.id')
         ->leftJoin('lich_su_ra_vao_bai_xes', function($join) {
            $join->on('chi_tiet_bai_xes.id', '=', 'lich_su_ra_vao_bai_xes.id_vi_tri_trong_bai');
         })
         ->leftJoin('bao_cao_khach_vang_lais', function($join) {
            $join->on('chi_tiet_bai_xes.id', '=', 'bao_cao_khach_vang_lais.id_vi_tri_trong_bai');
         })
         ->select(
            'chi_tiet_bai_xes.*',
            'bai_xes.ten_bai',
            // Nếu có khách vãng lai thì ưu tiên lấy thông tin khách vãng lai
            DB::raw('CASE WHEN chi_tiet_bai_xes.trang_thai = 1 AND bao_cao_khach_vang_lais.id IS NOT NULL THEN bao_cao_khach_vang_lais.ho_va_ten ELSE NULL END as ten_khach_vang_lai'),
            DB::raw('CASE WHEN chi_tiet_bai_xes.trang_thai = 1 AND bao_cao_khach_vang_lais.id IS NOT NULL THEN bao_cao_khach_vang_lais.so_dien_thoai ELSE NULL END as sdt_khach_vang_lai'),
            // Nếu không có khách vãng lai thì lấy thông tin lịch sử ra vào (cư dân)
            DB::raw('CASE WHEN chi_tiet_bai_xes.trang_thai = 1 AND bao_cao_khach_vang_lais.id IS NULL THEN lich_su_ra_vao_bai_xes.id_xe_cu_dan ELSE NULL END as id_xe_cu_dan'),
            DB::raw('CASE WHEN chi_tiet_bai_xes.trang_thai = 1 AND bao_cao_khach_vang_lais.id IS NULL THEN lich_su_ra_vao_bai_xes.thoi_gian_vao ELSE NULL END as thoi_gian_vao_cu_dan')
         )
         ->get();
      return response()->json([
         'data' => $chiTietBaiXe,
      ]);
   }
}
