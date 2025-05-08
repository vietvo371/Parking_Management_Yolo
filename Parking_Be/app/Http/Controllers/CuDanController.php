<?php

namespace App\Http\Controllers;

use App\Http\Requests\capNhatCuDanRequest;
use App\Http\Requests\DoiPassAdminReuqest;
use App\Http\Requests\ThemCuDanRequest;
use App\Models\CuDan;
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
                DB::raw('MIN(xes.bien_so_xe) as bien_so_xe')
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
                'can_hos.so_can_ho'
            )
            ->get();
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
}
