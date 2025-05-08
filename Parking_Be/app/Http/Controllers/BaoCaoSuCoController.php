<?php

namespace App\Http\Controllers;

use App\Models\BaoCaoSuCo;
use Illuminate\Http\Request;

class BaoCaoSuCoController extends Controller
{
    public function getData(Request $request)
    {
        $baoCaoSuCo = BaoCaoSuCo::join('cu_dans', 'bao_cao_su_cos.id_cu_dan','cu_dans.id')
            ->join('admins', 'bao_cao_su_cos.id_admin_xu_ly', 'admins.id')
            ->select('bao_cao_su_cos.*', 'cu_dans.ho_va_ten as ten_cu_dan', 'admins.ho_va_ten as ten_admin_xu_ly')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $baoCaoSuCo
        ]);
    }

    public function themBaoCaoSuCo(Request $request)
    {
        $admin = $this->isAdmin();
        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này'
            ]);
        }

        $data = BaoCaoSuCo::create([
            'id_cu_dan_bao_cao'     => $request->id_cu_dan_bao_cao,
            'noi_dung_bao_cao'      => $request->noi_dung_bao_cao,
            'trang_thai_xu_ly'      => 0,
            'ngay_tao'              => now(),
            'id_admin_xu_ly'        => $admin->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Thêm báo cáo sự cố thành công',
            'data' => $data
        ]);
    }

    public function capnhatBaoCaoSuCo(Request $request)
    {
        $data = BaoCaoSuCo::find($request->id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Báo cáo sự cố không tồn tại'
            ]);
        }

        $data->update([
            'noi_dung_bao_cao' => $request->noi_dung_bao_cao,
            'trang_thai_xu_ly' => $request->trang_thai_xu_ly,
        ]);
    }

    public function xoaBaoCaoSuCo(Request $request)
    {
        $data = BaoCaoSuCo::find($request->id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Báo cáo sự cố không tồn tại'
            ]);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa báo cáo sự cố thành công'
        ]);
    }

}
