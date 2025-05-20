<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ChiTietChucVu;
use App\Models\ChucNang;
use Illuminate\Http\Request;

class ChucNangController extends Controller
{
    public function getData(Request $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data = ChucNang::all();
        return response()->json([
            'data' => $data,
        ]);
    }




}
