<?php

namespace App\Http\Controllers;

use App\Models\ChiTietChucVu;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    public function isAdmin()
    {
        $user = Auth::guard('sanctum')->user();

        if ($user instanceof \App\Models\Admin) {
            return $user;
        }
        return false;
    }
    public function checkQuyen($id_chuc_nang)
    {
        $user = $this->isAdmin();
        if ($user) {
            if ($user->is_master == 1) {
                return true;
            }

            if ($user->id_chuc_vu) {
                $check = ChiTietChucVu::where('id_chuc_vu', $user->id_chuc_vu)
                    ->where('id_chuc_nang', $id_chuc_nang)
                    ->first();
                if ($check) {
                    return true;
                }

                return false;
            }
        }

        return false;
    }
}
