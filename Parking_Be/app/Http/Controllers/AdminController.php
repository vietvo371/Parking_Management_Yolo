<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $check = Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password,]);
        if ($check) {
            $user = Auth::guard('admin')->user();
            if ($user->is_block == 1) {
                return response()->json([
                    'status' => false,
                    'message' => "Tài khoản của bạn đã bị khóa!"
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => "Đăng nhập thành công!",
                    'user' => $user,
                    'token' => $user->createToken('admin_token')->plainTextToken
                ]);
            }
        } else {
            return response()->json([
                'message'   => 'Thông tin đăng nhập không chính xác!!',
                'status'    => false
            ]);
        }
    }
    public function register(Request $request)
    {
        Admin::create([
            'email'         => $request->email,
            'ho_va_ten'     => $request->ho_va_ten,
            'id_chuc_vu'    => $request->id_chuc_vu,
            'so_dien_thoai' => $request->so_dien_thoai,
            'password'      => bcrypt($request->password),
        ]);
        return response()->json([
            'message'   => 'Tạo tài khoản thành công!!',
            'status'    =>  true
        ]);
    }
    public function logout()
    {
        $user = $this->isAdmin();
        if ($user) {
            DB::table('personal_access_tokens')
                ->where('id', $user->currentAccessToken()->id)->delete();

            return response()->json([
                'message'   => 'Đăng xuất thành công!!',
                'status'    => true
            ]);
        }
        return response()->json([
            'message'   => 'Bạn chưa đăng nhập tài khoản admin!',
            'status'    => false
        ]);
    }
    public function checkToken()
    {

        $user = $this->isAdmin();

        if ($user) {
            return response()->json([
                'message'   => 'Token còn hiệu lực',
                'status'    => true,
                'user'      => $user
            ]);
        }
        return response()->json([
            'message'   => 'Token không còn hiệu lực',
            'status'    => false
        ]);
    }

}
