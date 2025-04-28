<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViTriDatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('vi_tri_dats')->delete();
        DB::table('vi_tri_dats')->truncate();

        $viTriDats = [
            ['vi_tri_dat' => 'Cổng A', 'trang_thai' => 1],
            ['vi_tri_dat' => 'Cổng B', 'trang_thai' => 1],
            ['vi_tri_dat' => 'Cổng C', 'trang_thai' => 0],
            ['vi_tri_dat' => 'Cổng D', 'trang_thai' => 0],

        ];
        DB::table('vi_tri_dats')->insert($viTriDats);
    }
}
