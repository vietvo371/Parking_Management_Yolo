<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BaoCaoKhachVangLaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bao_cao_khach_vang_lais')->delete();
        DB::table('bao_cao_khach_vang_lais')->truncate();
        DB::table('bao_cao_khach_vang_lais')->insert([
            'id_admin' => 1,
            'ho_va_ten' => 'Nguyen Van A',
            'so_dien_thoai' => '0909123456',
            'thoi_gian_vao' => Carbon::now()->subHours(5),
            'thoi_gian_ra' => Carbon::now()->subHours(2),
            'tien_thanh_toan' => 50000,
            'is_thanh_toan' => true,
            'id_vi_tri_trong_bai' => 1,
        ]);

        DB::table('bao_cao_khach_vang_lais')->insert([
            'id_admin' => 2,
            'ho_va_ten' => 'Tran Thi B',
            'so_dien_thoai' => '0911222333',
            'thoi_gian_vao' => Carbon::now()->subDays(1)->addHours(3),
            'thoi_gian_ra' => Carbon::now()->subDays(1)->addHours(5),
            'tien_thanh_toan' => 70000,
            'is_thanh_toan' => false,
            'id_vi_tri_trong_bai' => 2,
        ]);

        DB::table('bao_cao_khach_vang_lais')->insert([
            'id_admin' => 3,
            'ho_va_ten' => 'Le Van C',
            'so_dien_thoai' => '0988777666',
            'thoi_gian_vao' => Carbon::now()->subHours(1),
            'thoi_gian_ra' => Carbon::now()->subHours(6),
            'tien_thanh_toan' => 60000,
            'is_thanh_toan' => true,
            'id_vi_tri_trong_bai' => 3,
        ]);
    }
}
