<?php

namespace App\Http\Controllers;

use App\Http\Requests\capnhatViTriDat;
use App\Http\Requests\themViTriDatRequest;
use App\Models\ViTriDat;
use Illuminate\Http\Request;

class ViTriDatController extends Controller
{
    public function getData()
    {
        $vitri = ViTriDat::where('trang_thai', 1)->get();
        return response()->json([
            'data' => $vitri,
        ]);
    }
    public function themViTriDat(themViTriDatRequest $request)
    {
        $vitri = ViTriDat::create([
            'vi_tri_dat' => $request->vi_tri_dat,
            'trang_thai' => 1,
        ]);
        return response()->json([
            'message' => 'Thêm vị trí đặt thành công',
            'data' => $vitri,
        ]);
    }
    public function capnhatViTriDat(capnhatViTriDat $request)
    {
        $vitri = ViTriDat::find($request->id);
        $vitri->update([
            'vi_tri_dat' => $request->vi_tri_dat,
        ]);
        return response()->json([
            'message' => 'Đổi trạng thái thành công',
        ]);

    }
    public function xoaViTriDat($id)
    {
        $data = ViTriDat::find($id)->delete();

        return response()->json([
            'message' => 'Xóa vị trí đặt thành công',
        ]);
    }

}
