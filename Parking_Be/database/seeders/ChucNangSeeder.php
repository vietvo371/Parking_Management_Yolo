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
            ['ten_chuc_nang' => 'Quản lý người dùng', 'tinh_trang' => 1],
            ['ten_chuc_nang' => 'Quản lý sản phẩm', 'tinh_trang' => 1],
            ['ten_chuc_nang' => 'Quản lý đơn hàng', 'tinh_trang' => 1],
            ['ten_chuc_nang' => 'Quản lý danh mục', 'tinh_trang' => 1],
            ['ten_chuc_nang' => 'Quản lý khuyến mãi', 'tinh_trang' => 1],
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
