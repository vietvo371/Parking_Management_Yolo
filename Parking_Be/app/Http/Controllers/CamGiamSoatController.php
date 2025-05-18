<?php

namespace App\Http\Controllers;

use App\Http\Requests\capnhatCamGiamSoatRequest;
use App\Http\Requests\themCamGiamSoatRequest;
use App\Models\CamGiamSoat;
use Illuminate\Http\Request;

class CamGiamSoatController extends Controller
{
    public function getData()
    {
        $data = CamGiamSoat::join('bai_xes', 'bai_xes.id', 'cam_giam_soats.id_bai_xe')
            ->join('vi_tri_dats', 'vi_tri_dats.id', 'cam_giam_soats.id_vi_tri')
            ->select('cam_giam_soats.*', 'bai_xes.ten_bai', 'vi_tri_dats.vi_tri_dat')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $data
        ]);
    }
    public function themCamGiamSoat(themCamGiamSoatRequest $request)
    {
        $camGiamSoat = CamGiamSoat::create([
            'id_bai_xe' => $request->id_bai_xe,
            'id_vi_tri' => $request->id_vi_tri,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thêm dữ liệu thành công',
            'data' => $camGiamSoat
        ]);
    }
    public function capnhatCamGiamSoat(capnhatCamGiamSoatRequest $request)
    {
        $camGiamSoat = CamGiamSoat::find($request->id);
        if ($camGiamSoat) {
            $camGiamSoat->update([
                'id_bai_xe' => $request->id_bai_xe,
                'id_vi_tri' => $request->id_vi_tri,
                'trang_thai' => $request->trang_thai,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật dữ liệu thành công',
                'data' => $camGiamSoat
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại',
            ], 404);
        }
    }
    public function xoaCamGiamSoat(Request $request)
    {
        $camGiamSoat = CamGiamSoat::find($request->id);
        $camGiamSoat->delete();
        return response()->json([
            'status' => true,
            'message' => 'Xóa dữ liệu thành công',
        ]);
    }
}
