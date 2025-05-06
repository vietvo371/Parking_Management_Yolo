<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaiXeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('bai_xes')->delete();
        DB::table('bai_xes')->truncate();

        $baiXes = [
            ['ten_bai' => 'Bãi Xe A', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 1],
            ['ten_bai' => 'Bãi Xe B', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 1],
            ['ten_bai' => 'Bãi Xe C', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 1],
            ['ten_bai' => 'Bãi Xe D', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 1],
            ['ten_bai' => 'Bãi Xe E', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 1],
        ];

        DB::table('bai_xes')->insert($baiXes);
    }
}
