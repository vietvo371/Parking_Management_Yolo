<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiXeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loai_xes')->delete();
        DB::table('loai_xes')->truncate();

        $data = [
            [
                'ten_loai_xe' => 'Xe ô tô',
                'trang_thai' => 1,
                'tien_thu_giu_xe' => 500000,
            ],
            [
                'ten_loai_xe' => 'Xe máy',
                'trang_thai' => 1,
                'tien_thu_giu_xe' => 200000,
            ],
            [
                'ten_loai_xe' => 'Xe Khác',
                'trang_thai' => 1,
                'tien_thu_giu_xe' => 100000,
            ],
        ];

        foreach ($data as $item) {
            DB::table('loai_xes')->insert([
                'ten_loai_xe' => $item['ten_loai_xe'],
                'trang_thai' => $item['trang_thai'],
                'tien_thu_giu_xe' => $item['tien_thu_giu_xe'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
