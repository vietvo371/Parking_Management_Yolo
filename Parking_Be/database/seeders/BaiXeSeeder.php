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
            ['ten_bai' => 'Bãi xe A', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 1],
            ['ten_bai' => 'Bãi xe B', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 1],
            ['ten_bai' => 'Bãi xe C', 'suc_chua_o_to' => 50, 'suc_chua_khac' => 50, 'trang_thai' => 0],
        ];

        DB::table('bai_xes')->insert($baiXes);
    }
}
