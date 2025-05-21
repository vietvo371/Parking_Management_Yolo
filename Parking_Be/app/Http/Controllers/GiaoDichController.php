<?php

namespace App\Http\Controllers;

use App\Models\CuDan;
use App\Models\GiaoDich;
use App\Models\Xe;
use Illuminate\Http\Request;

class GiaoDichController extends Controller
{
    public function getData()
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $giaodichs = GiaoDich::join('xes', 'giao_diches.id_xe', '=', 'xes.id')
            ->join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
            ->select('giao_diches.*', 'xes.bien_so_xe', 'cu_dans.ho_va_ten as ten_cu_dan', 'cu_dans.so_du')
            ->orderBy('giao_diches.created_at', 'asc')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $giaodichs,
        ]);
    }
    public function getDataXe()
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $xe = Xe::join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
            ->join('loai_xes', 'xes.id_loai_xe', '=', 'loai_xes.id')
            ->join('can_hos', 'cu_dans.id_can_ho', '=', 'can_hos.id')
            ->select('xes.*', 'cu_dans.ho_va_ten', 'loai_xes.ten_loai_xe', 'can_hos.so_can_ho', 'can_hos.ten_toa_nha')
            ->orderBy('xes.created_at', 'desc')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $xe,

        ]);
    }
    public function themGiaoDich(Request $request)
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $magiaodich = 'GD' . date('Ymd') . str_pad(GiaoDich::count() + 1, 4, '0', STR_PAD_LEFT);

        $giaodich = GiaoDich::create([
            'so_tien_giao_dich'         => $request->so_tien_giao_dich,
            'ma_giao_dich'              => $magiaodich,
            'id_xe'                     => $request->id_xe,
            'phuong_thuc_thanh_toan'    => $request->phuong_thuc_thanh_toan,
            'trang_thai_giao_dich'      => $request->is_thanh_toan == true ? 1 : 0,
            'ngay_het_han'              => now()->addMonth(),
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thêm giao dịch thành công',
        ]);
    }


}
