<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucNangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chuc_nangs')->delete();
        DB::table('chuc_nangs')->truncate();
        $chucNangs = [
            ['ten_chuc_nang' => 'Trang chủ', 'tinh_trang' => 1],//1
            ['ten_chuc_nang' => 'Quản lý xe', 'tinh_trang' => 1],//2
            ['ten_chuc_nang' => 'Cư dân', 'tinh_trang' => 1],//3
            ['ten_chuc_nang' => 'Khách vãng lai', 'tinh_trang' => 1],//4
            ['ten_chuc_nang' => 'Giao dịch', 'tinh_trang' => 1],//5
            ['ten_chuc_nang' => 'Báo cáo sự cố', 'tinh_trang' => 1],//6
            ['ten_chuc_nang' => 'Thông báo', 'tinh_trang' => 1],//7
            ['ten_chuc_nang' => 'Camera & AI', 'tinh_trang' => 1],//8
            ['ten_chuc_nang' => 'Ra vào bãi', 'tinh_trang' => 1],//9
            ['ten_chuc_nang' => 'Báo cáo', 'tinh_trang' => 1],//10
            ['ten_chuc_nang' => 'Lịch sử ra vào', 'tinh_trang' => 1],//11
            ['ten_chuc_nang' => 'Phân quyền', 'tinh_trang' => 1],//12
            ['ten_chuc_nang' => 'Cài đặt', 'tinh_trang' => 1],//13
        ];

        foreach ($chucNangs as $chucNang) {
            DB::table('chuc_nangs')->insert([
                'ten_chuc_nang' => $chucNang['ten_chuc_nang'],
                'tinh_trang' => $chucNang['tinh_trang'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
