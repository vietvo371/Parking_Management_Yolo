<?php

namespace App\Http\Controllers;

use App\Http\Requests\capNhatProfileRequest;
use App\Http\Requests\DoiPassAdminReuqest;
use App\Http\Requests\DoiPassNhanSuReuqest;
use App\Http\Requests\themAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\ChucVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

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
                $chuc_vu = ChucVu::join('admins', 'chuc_vus.id', '=', 'admins.id_chuc_vu')
                    ->where('admins.id', $user->id)
                    ->select('admins.ho_va_ten', 'chuc_vus.ten_chuc_vu')
                    ->first();
                return response()->json([
                    'status' => true,
                    'message' => "Đăng nhập thành công!",
                    'user' => $chuc_vu,
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

            $chuc_vu = ChucVu::join('admins', 'chuc_vus.id', '=', 'admins.id_chuc_vu')
                ->where('admins.id', $user->id)
                ->select('admins.ho_va_ten', 'chuc_vus.ten_chuc_vu')
                ->first();
            return response()->json([
                'message'   => 'Token còn hiệu lực',
                'status'    => true,
                'user'      => $chuc_vu,
            ]);
        }
        return response()->json([
            'message'   => 'Token không còn hiệu lực',
            'status'    => false
        ]);
    }
    public function getData()
    {
        $data = Admin::join('chuc_vus', 'admins.id_chuc_vu', '=', 'chuc_vus.id')
            ->select('admins.*', 'chuc_vus.ten_chuc_vu')
            ->where('admins.is_block', 0)
            ->get();
        if ($data) {
            return response()->json([
                'message'   => 'Lấy dữ liệu thành công',
                'status'    => true,
                'data'      => $data
            ]);
        }
        return response()->json([
            'message'   => 'Lấy dữ liệu không thành công',
            'status'    => false
        ]);
    }
    public function themAdmin(themAdminRequest $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
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
    public function xoaAdmin($id)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $admin = Admin::find($id);
        if ($admin->is_master == 1) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không thể cập nhật Tài Khoản Có Quyền Hạn Cao'
            ]);
        }
        Admin::where('id', $id)->delete();

        return response()->json([
            'status'     => true,
            'message'    => 'xoá Admin thành công!!'
        ]);
    }
    public function capnhatAdmin(UpdateAdminRequest $request)
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $dangLogin = $this->isAdmin();

        $admin = Admin::find($request->id);

        if ($admin->is_master == 1 && $admin->id != $dangLogin->id) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không thể cập nhật Tài Khoản Có Quyền Hạn Cao'
            ]);
        }
        Admin::where('id', $request->id)
            ->update([
                'email'         => $request->email,
                'ho_va_ten'     => $request->ho_va_ten,
                'id_chuc_vu'    => $request->id_chuc_vu,
                'so_dien_thoai' => $request->so_dien_thoai,
                'is_block'      => $request->is_block,
            ]);
        return response()->json([
            'status'     => true,
            'message'    => 'Cập nhật thành công!!'
        ]);
    }
    public function doiTrangThaiAdmin(Request $request)
    {

        $admin = Admin::find($request->id);
        if ($admin->is_master == 1) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không thể cập nhật Tài Khoản Có Quyền Hạn Cao'
            ]);
        }
        $is_block_moi = !$request->is_block;
        //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
        Admin::where('id', $request->id)
            ->update([
                'is_block'    => $is_block_moi
            ]);

        return response()->json([
            'status'     => true,
            'message'    => 'Cập Nhật Trạng Thái thành công!! '
        ]);
    }
    public function doiPass(DoiPassNhanSuReuqest $request)
    {
        $dangLogin = $this->isAdmin();
        $admin = Admin::find($request->id);
        if ($admin->is_master == 1 && $admin->id != $dangLogin->id) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không thể cập nhật Tài Khoản Có Quyền Hạn Cao'
            ]);
        }
        if ($admin->id == $dangLogin->id) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không thể đổi mật khẩu cho tài khoản của mình ở đây'
            ]);
        }
        Admin::where('id', $request->id)
            ->update([
                'password'   => bcrypt($request->password),
            ]);
        return response()->json([
            'status'  =>  true,
            'message' =>  'Đổi mật khẩu thành công'
        ]);
    }

    public function timAdmin(Request $request)
    {
        $key    = '%' . $request->key . '%';
        $chuc_vu_admin   = ChucVu::select('chuc_vus.*')
            ->get(); // get là ra 1  sách
        $data = Admin::join('chuc_vus', 'admins.id_chuc_vu', '=', 'chuc_vus.id')
            ->select('admins.*', 'chuc_vus.ten_chuc_vu')
            ->where('ho_va_ten', 'like', $key)
            ->where('admins.is_block', 0)
            ->get();

        return response()->json([
            'admin'          =>  $data,
            'chuc_vu_admin'  =>  $chuc_vu_admin,
        ]);
    }

    public function doiPassProfile(DoiPassAdminReuqest $request)
    {

        $dangLogin = $this->isAdmin();

        Admin::where('id', $dangLogin->id)
            ->update([
                'password'   => bcrypt($request->password),
            ]);
        return response()->json([
            'status'  =>  true,
            'message' =>  'Đổi mật khẩu thành công'
        ]);
    }
    public function capnhatProfile(capNhatProfileRequest $request)
    {
        $user = $this->isAdmin();
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
    public function getDataProfile()
    {
        $user = $this->isAdmin();
        $profile = Admin::join('chuc_vus', 'admins.id_chuc_vu', '=', 'chuc_vus.id')
            ->select('admins.*', 'chuc_vus.ten_chuc_vu')
            ->where('admins.id', $user->id)
            ->first();
        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Lấy dữ liệu thành công',
                'data' => $profile
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Lấy dữ liệu không thành công'
        ]);
    }
}
