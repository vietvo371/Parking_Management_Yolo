<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            ChucNangSeeder::class,
            ChucVuSeeder::class,
            ChiTietChucVuSeeder::class,
            AdminSeeder::class,
            CanHoSeeder::class,
            CuDanSeeder::class,
            ViTriDatSeeder::class,
            BaiXeSeeder::class,
            ChiTietBaiXeSeeder::class,
            CameraGiamSatSeeder::class,
            BaoCaoSuCoSeeder::class,
            BaoCaoKhachVangLaiSeeder::class,
            // AdminThongBaoSeeder::class,
            XeSeeder::class,
            LoaiXeSeeder::class,
            GiaoDichSeeder::class,
            LichSuRaVaoBaiXeSeeder::class,
            LoaiXeSeeder::class,

        ]);
    }
}
