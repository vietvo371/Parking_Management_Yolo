<?php

namespace App\Http\Controllers;

use App\Models\AdminThongBao;
use Illuminate\Http\Request;

class AdminThongBaoController extends Controller
{
    public function getData()
    {
        $thongbaos = AdminThongBao::join('admins', 'admin_thong_baos.admin_id', '=', 'admins.id')
            ->join('admin_thong_baos.*', 'admin_thong_baos.id_cu_dan', '=', 'cu_dans.id')
            ->select('admin_thong_baos.*', 'admins.ho_va_ten','cu_dans.ho_va_ten as ten_cu_dan' , 'cu_dans.so_du')
            ->where('admin_thong_baos.trang_thai', 1)
            ->orderBy('admin_thong_baos.created_at', 'desc')
            ->get();
        return response()->json([
            'data' => $thongbaos,
        ]);
    }

}
