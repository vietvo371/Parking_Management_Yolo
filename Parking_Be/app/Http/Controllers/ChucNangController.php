<?php

namespace App\Http\Controllers;

use App\Models\ChiTietChucVu;
use App\Models\ChucNang;
use Illuminate\Http\Request;

class ChucNangController extends Controller
{
    public function getData(Request $request)
    {
        $data = ChucNang::all();
        return response()->json([
            'data' => $data,
        ]);
    }




}
