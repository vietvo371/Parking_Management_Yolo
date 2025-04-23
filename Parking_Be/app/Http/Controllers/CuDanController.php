<?php

namespace App\Http\Controllers;

use App\Http\Requests\capNhatCuDanRequest;
use App\Http\Requests\DoiPassAdminReuqest;
use App\Http\Requests\ThemCuDanRequest;
use App\Models\CuDan;
use Illuminate\Http\Request;

class CuDanController extends Controller
{
    public function getData()
    {
        $cudan = CuDan::all();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $cudan
        ]);
    }
    public function themCuDan(ThemCuDanRequest $request)
    {
        CuDan::create([
           'ho_va_ten' => $request->ho_va_ten,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'so_dien_thoai' => $request->so_dien_thoai,
            'so_cccd' => $request->so_cccd,
            'id_can_ho' => $request->id_can_ho,
            'so_du' => $request->so_du,
        ]);
        return response()->json([
            'status'   => true,
            'message'  => 'Bạn thêm Cư Dân thành công!',
        ]);
    }
    public function capnhatCuDan(capNhatCuDanRequest $request)
    {
        $cudan = CuDan::find($request->id);
        if ($cudan) {
            $cudan->update([
                'ho_va_ten' => $request->ho_va_ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'so_cccd' => $request->so_cccd,
                'id_can_ho' => $request->id_can_ho,
                'so_du' => $request->so_du,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn cập nhật Cư Dân thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Cư Dân không tồn tại!',
            ]);
        }
    }
    public function doiTrangThaiCuDan(Request $request)
    {
        $cudan = CuDan::find($request->id);
        if ($cudan) {
            $cudan->update([
                'trang_thai' => !$request->trang_thai,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn cập nhật trạng thái thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Cư Dân không tồn tại!',
            ]);
        }
    }
    public function xoaCuDan(Request $request)
    {
        $cudan = CuDan::find($request->id);
        if ($cudan) {
            $cudan->delete();
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn đã xóa Cư Dân thành công!',
            ]);
        } else {
            return response()->json([
                'status'   => false,
                'message'  => 'Cư Dân không tồn tại!',
            ]);
        }
    }
    public function doiPass(DoiPassAdminReuqest $request)
    {
        $dangLogin = $this->isAdmin();

        $cudan = CuDan::find($request->id);

        CuDan::where('id', $request->id)
            ->update([
                'password'   => bcrypt($request->password),
            ]);
        return response()->json([
            'status'  =>  true,
            'message' =>  'Đổi mật khẩu thành công'
        ]);
    }
}
