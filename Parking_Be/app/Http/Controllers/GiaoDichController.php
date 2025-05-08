<?php

namespace App\Http\Controllers;

use App\Models\GiaoDich;
use Illuminate\Http\Request;

class GiaoDichController extends Controller
{
    public function getData()
    {
        $giaodichs = GiaoDich::join('xes', 'giao_dichs.id_xe', '=', 'xes.id')
            ->join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
            ->select('giao_dichs.*', 'xes.bien_so', 'cu_dans.ho_va_ten as ten_cu_dan', 'cu_dans.so_du')
            ->where('giao_dichs.trang_thai', 1)
            ->orderBy('giao_dichs.created_at', 'desc')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $giaodichs,
        ]);
    }
    public function themGiaoDich(Request $request)
    {
        $giaodich = GiaoDich::create([
            'so_tien_giao_dich'         => $request->so_tien_giao_dich,
            'ma_giao_dich'              => $request->ma_giao_dich,
            'id_xe'                     => $request->id_xe,
            'phuong_thuc_thanh_toan'    => $request->phuong_thuc_thanh_toan,
            'trang_thai_giao_dich'      => $request->trang_thai_giao_dich,
            'ngay_het_han'              => $request->ngay_het_han,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thêm giao dịch thành công',
        ]);
    }

    
}