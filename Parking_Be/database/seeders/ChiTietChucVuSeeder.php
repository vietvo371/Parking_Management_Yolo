<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietChucVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chi_tiet_chuc_vus')->delete();
        DB::table('chi_tiet_chuc_vus')->truncate();
        // Admin: toàn quyền
        for ($i = 1; $i <= 13; $i++) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 1, // Admin
                'id_chuc_nang' => $i,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Nhân viên bảo vệ: Quản lý xe, Ra vào bãi, Lịch sử ra vào, Báo cáo sự cố
        $baoVeFunctions = [2, 6, 9, 11];
        foreach ($baoVeFunctions as $function) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 2, // Nhân viên bảo vệ
                'id_chuc_nang' => $function,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Quản lý cư dân: Cư dân, Khách vãng lai, Giao dịch, Thông báo
        $cuDanFunctions = [3, 4, 5, 7];
        foreach ($cuDanFunctions as $function) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 3, // Quản lý cư dân
                'id_chuc_nang' => $function,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Kế toán: Giao dịch, Báo cáo
        $keToanFunctions = [5, 10];
        foreach ($keToanFunctions as $function) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 4, // Kế toán
                'id_chuc_nang' => $function,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Kỹ thuật: Camera & AI, Báo cáo sự cố, Cài đặt
        $kyThuatFunctions = [6, 8, 13];
        foreach ($kyThuatFunctions as $function) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 5, // Kỹ thuật
                'id_chuc_nang' => $function,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
