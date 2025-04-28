<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CameraGiamSatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cam_giam_soats')->delete();
        DB::table('cam_giam_soats')->truncate();

        $data = [
            ['id_vi_tri' => 1, 'id_bai_xe' => 1, 'trang_thai' => 1],
            ['id_vi_tri' => 2, 'id_bai_xe' => 1, 'trang_thai' => 1],
            ['id_vi_tri' => 3, 'id_bai_xe' => 2, 'trang_thai' => 0],
            ['id_vi_tri' => 4, 'id_bai_xe' => 2, 'trang_thai' => 1],
            ['id_vi_tri' => 5, 'id_bai_xe' => 3, 'trang_thai' => 1],
        ];

        foreach ($data as $item) {
            DB::table('cam_giam_soats')->insert([
                'id_vi_tri'   => $item['id_vi_tri'],
                'id_bai_xe'   => $item['id_bai_xe'],
                'trang_thai'  => $item['trang_thai'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
