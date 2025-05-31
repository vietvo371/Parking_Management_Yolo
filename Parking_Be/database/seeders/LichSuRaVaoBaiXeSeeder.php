<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LichSuRaVaoBaiXeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lich_su_ra_vao_bai_xes')->delete();
        DB::table('lich_su_ra_vao_bai_xes')->truncate();

        DB::table('lich_su_ra_vao_bai_xes')->insert([
            'id_xe_cu_dan' => 1,
            'id_camera_quet' => 2,
            'thoi_gian_vao' => Carbon::now()->subHours(3),
            'thoi_gian_ra' => Carbon::now()->subHours(1),
            'id_vi_tri_trong_bai' => 3,
        ]);

        DB::table('lich_su_ra_vao_bai_xes')->insert([
            'id_xe_cu_dan' => 2,
            'id_camera_quet' => 1,
            'thoi_gian_vao' => Carbon::now()->subHours(4),
            'thoi_gian_ra' => Carbon::now()->subHours(2),
            'id_vi_tri_trong_bai' => 1,
        ]);

        DB::table('lich_su_ra_vao_bai_xes')->insert([
            'id_xe_cu_dan' => 3,
            'id_camera_quet' => 3,
            'thoi_gian_vao' => Carbon::now()->subDays(1)->addHours(2),
            'thoi_gian_ra' => Carbon::now()->subDays(1)->addHours(5),
            'id_vi_tri_trong_bai' => 2,
        ]);
    }
}
