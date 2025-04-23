<?php

namespace App\Http\Controllers;

use App\Models\ChiTietChucVu;
use Illuminate\Http\Request;

class ChiTietChucVuController extends Controller
{
    public function getData($id_chuc_vu)
    {
        $data = ChiTietChucVu::where('id_chuc_vu', $id_chuc_vu)
            ->join('chuc_nangs', 'phan_quyens.id_chuc_nang', 'chuc_nangs.id')
            ->join('chuc_vus', 'phan_quyens.id_chuc_vu', 'chuc_vus.id')
            ->select('phan_quyens.*', 'chuc_nangs.ten_chuc_nang', 'chuc_vus.ten_chuc_vu')
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = ChiTietChucVu::firstOrCreate([
            'id_chuc_vu'  => $request->id_chuc_vu,
            'id_chuc_nang'  => $request->id_chuc_nang,
        ]);
        return response()->json([
            'status'    => true,
            'message'   => 'Đã thêm phân quyền thành công!',
            'data'     => $data
        ]);
    }

    public function destroy(Request $request)
    {
        $data = ChiTietChucVu::where('id_chuc_vu', $request->id_chuc_vu)
            ->where('id_chuc_nang', $request->id_chuc_nang)
            ->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'Đã xóa phân quyền thành công!',
        ]);
    }
}
