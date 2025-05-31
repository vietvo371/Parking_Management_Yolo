<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietBaiXeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chi_tiet_bai_xes')->delete();
        DB::table('chi_tiet_bai_xes')->truncate();

        // Lấy danh sách bãi xe
        $baiXes = DB::table('bai_xes')->get();

        foreach ($baiXes as $baiXe) {
            $lastChar = mb_substr(trim($baiXe->ten_bai), -1); // Lấy ký tự cuối của tên bãi

            // Tạo chi tiết cho ô tô
            for ($i = 1; $i <= $baiXe->suc_chua_o_to; $i++) {
                DB::table('chi_tiet_bai_xes')->insert([
                    'id_bai_xe' => $baiXe->id,
                    'thu_tu' => $lastChar . $i,
                    'loai_xe' => 'O-To',
                    'trang_thai' => 0
                ]);
            }

            // Tạo chi tiết cho xe khác
            for ($i = 1; $i <= $baiXe->suc_chua_khac; $i++) {
                DB::table('chi_tiet_bai_xes')->insert([
                    'id_bai_xe' => $baiXe->id,
                    'thu_tu' => $lastChar . $i,
                    'loai_xe' => 'Khác',
                    'trang_thai' => 0
                ]);
            }
        }
    }
}
