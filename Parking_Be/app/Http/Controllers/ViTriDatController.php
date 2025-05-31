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
        $id_chuc_nang = 8;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $vitri = ViTriDat::where('trang_thai', 1)->get();
        return response()->json([
            'data' => $vitri,
        ]);
    }
    public function themViTriDat(themViTriDatRequest $request)
    {
        $id_chuc_nang = 8;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $vitri = ViTriDat::create([
            'vi_tri_dat' => $request->vi_tri_dat,
            'trang_thai' => 1,
        ]);
        return response()->json([
            'message' => 'Thêm vị trí đặt thành công',
            'data' => $vitri,
            'status' => true,
        ]);
    }
    public function capnhatViTriDat(capnhatViTriDat $request)
    {
        $id_chuc_nang = 8;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $vitri = ViTriDat::find($request->id);
        $vitri->update([
            'vi_tri_dat' => $request->vi_tri_dat,
        ]);
        return response()->json([
            'message' => 'Đổi trạng thái thành công',
            'status' => true,
        ]);

    }
    public function xoaViTriDat($id)
    {
        $id_chuc_nang = 8;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data = ViTriDat::find($id)->delete();

        return response()->json([
            'message' => 'Xóa vị trí đặt thành công',
            'status' => true,
        ]);
    }

}
