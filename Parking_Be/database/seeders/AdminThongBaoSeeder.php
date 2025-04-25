<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminThongBaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_thong_baos')->delete();
        DB::table('admin_thong_baos')->truncate();

        $thongBaos = [
            [
                'id_admin' => 1,
                'id_cu_dan' => 2,
                'noi_dung_thong_bao' => 'Thông báo bảo trì hệ thống vào cuối tuần này.',
                'ngay_tao' => Carbon::now(),
                'trang_thai' => 1,
            ],
            [
                'id_admin' => 1,
                'id_cu_dan' => 3,
                'noi_dung_thong_bao' => 'Nhắc nhở thanh toán phí dịch vụ tháng 4.',
                'ngay_tao' => Carbon::now()->subDays(2),
                'trang_thai' => 1,
            ],
            [
                'id_admin' => 1,
                'id_cu_dan' => 4,
                'noi_dung_thong_bao' => 'Cư dân vui lòng cập nhật thông tin cá nhân.',
                'ngay_tao' => Carbon::now()->subDays(5),
                'trang_thai' => 0,
            ],
            [
                'id_admin' => 1,
                'id_cu_dan' => 6,
                'noi_dung_thong_bao' => 'Cảnh báo: phát hiện hành vi đậu xe sai quy định.',
                'ngay_tao' => Carbon::now()->subDays(3),
                'trang_thai' => 1,
            ],
            [
                'id_admin' => 1,
                'id_cu_dan' => 2,
                'noi_dung_thong_bao' => 'Lịch vệ sinh khu vực bãi xe ngày mai.',
                'ngay_tao' => Carbon::now()->subHours(12),
                'trang_thai' => 1,
            ],
            [
                'id_admin' => 1,
                'id_cu_dan' => 4,
                'noi_dung_thong_bao' => 'Thay đổi nội quy tòa nhà từ tháng sau.',
                'ngay_tao' => Carbon::now()->subDays(4),
                'trang_thai' => 1,
            ],

        ];

        foreach ($thongBaos as $thongBao) {
            DB::table('admin_thong_baos')->insert([
                'id_admin' => $thongBao['id_admin'],
                'id_cu_dan' => $thongBao['id_cu_dan'],
                'noi_dung_thong_bao' => $thongBao['noi_dung_thong_bao'],
                'ngay_tao' => $thongBao['ngay_tao'],
                'trang_thai' => $thongBao['trang_thai'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
