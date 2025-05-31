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
         ->leftJoin('lich_su_ra_vao_bai_xes', 'chi_tiet_bai_xes.id', '=', 'lich_su_ra_vao_bai_xes.id_vi_tri_trong_bai')
         ->leftJoin('xes', 'lich_su_ra_vao_bai_xes.id_xe_cu_dan', '=', 'xes.id')
         ->leftJoin('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')

         ->select(
            'chi_tiet_bai_xes.*',
            'bai_xes.ten_bai',
            'xes.bien_so_xe',
            'cu_dans.ho_va_ten',
            'cu_dans.so_dien_thoai',
            'lich_su_ra_vao_bai_xes.thoi_gian_vao',
            'lich_su_ra_vao_bai_xes.thoi_gian_ra'
         )
         ->get();
      return response()->json([
         'data' => $chiTietBaiXe,
      ]);
   }
}
