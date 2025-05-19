<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chuc_vus')->delete();
        DB::table('chuc_vus')->truncate();
        $chucVus = [
            ['ten_chuc_vu' => 'Admin', 'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Nhân viên bảo vệ', 'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Quản lý cư dân', 'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Kế toán', 'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Kỹ thuật', 'tinh_trang' => 1],
        ];

        foreach ($chucVus as $chucVu) {
            DB::table('chuc_vus')->insert([
                'ten_chuc_vu' => $chucVu['ten_chuc_vu'],
                'tinh_trang' => $chucVu['tinh_trang'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
