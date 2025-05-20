<?php

namespace App\Http\Controllers;

use App\Http\Requests\capNhatCuDanRequest;
use App\Http\Requests\capNhatProfileRequest;
use App\Http\Requests\DoiPassAdminReuqest;
use App\Http\Requests\ThemCuDanRequest;
use App\Models\CuDan;
use App\Models\Xe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CuDanController extends Controller
{
    public function getData()
    {
        $cudan = CuDan::join('can_hos', 'can_hos.id', '=', 'cu_dans.id_can_ho')
            ->leftJoin('xes', function($join) {
                $join->on('xes.id_cu_dan', '=', 'cu_dans.id');
            })
            ->select(
                'cu_dans.id',
                'cu_dans.ho_va_ten',
                'cu_dans.email',
                'cu_dans.password',
                'cu_dans.so_dien_thoai',
                'cu_dans.so_cccd',
                'cu_dans.id_can_ho',
                'cu_dans.so_du',
                'cu_dans.phe_duyet',
                'cu_dans.trang_thai',
                'can_hos.ten_toa_nha',
                'can_hos.tang',
                'can_hos.so_can_ho',
                'cu_dans.created_at',
                DB::raw('MIN(xes.bien_so_xe) as bien_so_xe'),
            )
            ->groupBy(
                'cu_dans.id',
                'cu_dans.ho_va_ten',
                'cu_dans.email',
                'cu_dans.password',
                'cu_dans.so_dien_thoai',
                'cu_dans.so_cccd',
                'cu_dans.id_can_ho',
                'cu_dans.so_du',
                'cu_dans.phe_duyet',
                'cu_dans.trang_thai',
                'can_hos.ten_toa_nha',
                'can_hos.tang',
                'can_hos.so_can_ho',
                'cu_dans.created_at'
            )
            ->orderBy('cu_dans.created_at', 'desc')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $cudan
        ]);
    }
    public function themCuDan(ThemCuDanRequest $request)
    {
         $id_chuc_nang = 3;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        CuDan::create([
            'ho_va_ten' => $request->ho_va_ten,
            'email' => $request->email,
            'password' => bcrypt(123456),
            'so_dien_thoai' => $request->so_dien_thoai,
            'so_cccd' => $request->so_cccd,
            'id_can_ho' => $request->id_can_ho,
        ]);
        return response()->json([
            'status'   => true,
            'message'  => 'Bạn thêm Cư Dân thành công!',
        ]);
    }
    public function capnhatCuDan(capNhatCuDanRequest $request)
    {
        $id_chuc_nang = 3;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $cudan = CuDan::find($request->id);
        if ($cudan) {
            $cudan->update([
                'ho_va_ten' => $request->ho_va_ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'so_cccd' => $request->so_cccd,
                'id_can_ho' => $request->id_can_ho,
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

        $id_chuc_nang = 3;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
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
        $dangLogin = $this->isCuDan();
        if (!$dangLogin) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn chưa đăng nhập tài khoản cư dân'
            ]);
        }

        $check = Auth::guard('cu_dan')->attempt(['email' => $dangLogin->email, 'password' => $request->current_password,]);
        if (!$check) {
            return response()->json([
                'status' => false,
                'message' => 'Mật khẩu hiện tại không đúng'
            ]);
        }

        $cudan = CuDan::find($dangLogin->id);
        if (!$cudan) {
            return response()->json([
                'status' => false,
                'message' => 'Cư dân không tồn tại'
            ]);
        }
        $cudan->update([
            'password'   => bcrypt($request->password),
        ]);
        return response()->json([
            'status'  =>  true,
            'message' =>  'Đổi mật khẩu thành công'
        ]);
    }
    public function login(Request $request)
    {
        $check = Auth::guard('cu_dan')->attempt(['email' => $request->email, 'password' => $request->password,]);
        if ($check) {
            $user = Auth::guard('cu_dan')->user();
            if ($user->trang_thai == 0) {
                return response()->json([
                    'status' => false,
                    'message' => "Tài khoản của bạn đã bị khóa!"
                ]);
            } elseif ($user->phe_duyet == 0) {
                return response()->json([
                    'status' => false,
                    'message' => "Tài khoản của bạn đang chờ duyệt!"
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => "Đăng nhập thành công!",
                    'token' => $user->createToken('cu_dan_token')->plainTextToken,
                    'user' => $user
                ]);
            }
        } else {
            return response()->json([
                'message'   => 'Thông tin đăng nhập không chính xác!!',
                'status'    => false
            ]);
        }
    }
    public function register(ThemCuDanRequest $request)
    {
        // if ($request->password != $request->password_confirm) {
        //     return response()->json([
        //         'message'   => 'Mật khẩu không khớp!!',
        //         'status'    => false
        //     ]);
        // }
        CuDan::create([
            'ho_va_ten' => $request->ho_va_ten,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'so_dien_thoai' => $request->so_dien_thoai,
            'so_cccd' => $request->so_cccd,
            'id_can_ho' => $request->id_can_ho,
        ]);
        return response()->json([
            'message'   => 'Tạo tài khoản thành công!!',
            'status'    =>  true
        ]);
    }
    public function checkToken()
    {

        $user = $this->isCuDan();

        if ($user) {
            return response()->json([
                'message'   => 'Token còn hiệu lực',
                'status'    => true,
                'user'      => $user,
            ]);
        }
        return response()->json([
            'message'   => 'Token không còn hiệu lực',
            'status'    => false
        ]);
    }
    public function logout()
    {
        $user = $this->isCuDan();
        if ($user) {
            DB::table('personal_access_tokens')
                ->where('id', $user->currentAccessToken()->id)->delete();

            return response()->json([
                'message'   => 'Đăng xuất thành công!!',
                'status'    => true
            ]);
        }
        return response()->json([
            'message'   => 'Bạn chưa đăng nhập tài khoản cư dân!',
            'status'    => false
        ]);
    }
    public function duyetCuDan(Request $request)
    {

        $id_chuc_nang = 6;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }

        $cudan = CuDan::find($request->id);
        if (!$cudan) {
            return response()->json([
                'status' => false,
                'message' => 'Cư dân không tồn tại'
            ]);
        }

        $cudan->update([
            'phe_duyet' => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cư dân đã được duyệt'
        ]);
    }
    public function getProfile()
    {
        $user = $this->isCuDan();
        $cudan = CuDan::leftJoin('can_hos', 'can_hos.id', '=', 'cu_dans.id_can_ho')
            ->leftJoin('xes', 'xes.id_cu_dan', '=', 'cu_dans.id')
            ->leftJoin('loai_xes', 'loai_xes.id', '=', 'xes.id_loai_xe')
            ->leftJoin('giao_diches', 'giao_diches.id_xe', '=', 'xes.id')
            ->where('cu_dans.id', $user->id)
            ->select('cu_dans.*', 'can_hos.ten_toa_nha','can_hos.so_can_ho', 'xes.bien_so_xe', 'loai_xes.ten_loai_xe', 'giao_diches.ngay_het_han')
            ->orderBy('giao_diches.ngay_het_han', 'desc')
            ->first();
        // $lich_su_thanh_toan = DB::table('giao_diches')->where('id_xe', $user->id)->orderBy('created_at', 'desc')->get();
        $xe = Xe::join('giao_diches', 'giao_diches.id_xe', '=', 'xes.id')
            ->where('xes.id_cu_dan', $user->id)
            ->whereNotNull('giao_diches.ngay_het_han')
            ->select('xes.bien_so_xe', 'giao_diches.ngay_het_han','giao_diches.ma_giao_dich')
            ->orderBy('xes.created_at', 'desc')
            ->get();
        $lich_su_login = DB::table('personal_access_tokens')->where('name', "cu_dan_token")->select('id', 'created_at', 'updated_at')->get();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy dữ liệu',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Lấy thông tin cư dân thành công',
            'data' => $cudan,
            'lich_su_login' => $lich_su_login,
            'lich_su_thanh_toan' => $xe
        ]);
    }
    public function capnhatProfile(capNhatProfileRequest $request)
    {
        $user = $this->isCuDan();
        $user->update([
            'ho_va_ten' => $request->ho_va_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            // 'id_can_ho' => $request->id_can_ho,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thông tin cư dân thành công',
            'data' => $user
        ]);
    }
}
