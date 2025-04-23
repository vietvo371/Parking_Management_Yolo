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
            'data' => $giaodichs,
        ]);
    }
}
