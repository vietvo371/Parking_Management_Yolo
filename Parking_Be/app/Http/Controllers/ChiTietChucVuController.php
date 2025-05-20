<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ChiTietChucVu;
use App\Models\ChucNang;
use App\Models\ChucVu;
use App\Models\NhanVien;
use Illuminate\Http\Request;

class ChiTietChucVuController extends Controller
{
    public function getData()
    {
         $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $nhan_vien = Admin::join('chuc_vus', 'admins.id_chuc_vu', 'chuc_vus.id')
            ->select('admins.*', 'chuc_vus.ten_chuc_vu')
            ->get();

        $chuc_nang = ChucNang::all();

        $chuc_vu = ChucVu::all();

        $chi_tiet_chuc_vu = ChiTietChucVu::all();
        return response()->json([
            'data' => $nhan_vien,
            'chuc_nang' => $chuc_nang,
            'chuc_vu' => $chuc_vu,
            'chi_tiet_chuc_vu' => $chi_tiet_chuc_vu
        ]);
    }

    public function store(Request $request)
    {
         $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $idChucVu = $request->id_chuc_vu;
        $danhSachQuyenMoi = $request->danh_sach_quyen;

        // Lấy danh sách quyền cũ
        $quyenCu = ChiTietChucVu::where('id_chuc_vu', $idChucVu)->pluck('id_chuc_nang')->toArray();

        // Xác định quyền cần xóa (có trong cũ nhưng không có trong mới)
        $quyenCanXoa = array_diff($quyenCu, $danhSachQuyenMoi);
        if (!empty($quyenCanXoa)) {
            ChiTietChucVu::where('id_chuc_vu', $idChucVu)
                ->whereIn('id_chuc_nang', $quyenCanXoa)
                ->delete();
        }

        // Xác định quyền cần thêm (có trong mới nhưng không có trong cũ)
        $quyenCanThem = array_diff($danhSachQuyenMoi, $quyenCu);
        $data = [];
        foreach ($quyenCanThem as $idChucNang) {
            $data[] = ChiTietChucVu::create([
                'id_chuc_vu'  => $idChucVu,
                'id_chuc_nang'  => $idChucNang,
            ]);
        }

        return response()->json([
            'status'    => true,
            'message'   => 'Cập nhật phân quyền thành công!',
            'data'      => $data
        ]);
    }

    public function destroy(Request $request)
    {
         $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data = ChiTietChucVu::where('id_chuc_vu', $request->id_chuc_vu)
            ->where('id_chuc_nang', $request->id_chuc_nang)
            ->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'Đã xóa phân quyền thành công!',
        ]);
    }
}
