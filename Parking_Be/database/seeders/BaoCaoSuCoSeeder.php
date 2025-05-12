<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BaoCaoSuCoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bao_cao_su_cos')->delete();
        DB::table('bao_cao_su_cos')->truncate();

        DB::table('bao_cao_su_cos')->insert([
            [
                'id_cu_dan_bao_cao' => 1,
                'noi_dung_bao_cao' => 'Khu vực cổng vào bãi giữ xe có nhiều rác, gây mùi khó chịu.',
                'trang_thai_xu_ly' => 0,
                'ngay_tao' => Carbon::now(),
                'id_admin_xu_ly' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_cu_dan_bao_cao' => 2,
                'noi_dung_bao_cao' => 'Xe của cư dân khác đậu sai vị trí, chiếm chỗ đậu xe của tôi.',
                'trang_thai_xu_ly' => 1,
                'ngay_tao' => Carbon::now()->subDays(1),
                'id_admin_xu_ly' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_cu_dan_bao_cao' => 3,
                'noi_dung_bao_cao' => 'Đèn chiếu sáng khu B của bãi giữ xe bị hỏng, gây nguy hiểm vào buổi tối.',
                'trang_thai_xu_ly' => 0,
                'ngay_tao' => Carbon::now()->subDays(2),
                'id_admin_xu_ly' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_cu_dan_bao_cao' => 4,
                'noi_dung_bao_cao' => 'Camera giám sát ở khu vực lối ra bãi xe không hoạt động.',
                'trang_thai_xu_ly' => 1,
                'ngay_tao' => Carbon::now()->subDays(3),
                'id_admin_xu_ly' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_cu_dan_bao_cao' => 5,
                'noi_dung_bao_cao' => 'Mặt sàn khu đậu xe tầng hầm bị trơn trượt sau mưa, dễ té ngã.',
                'trang_thai_xu_ly' => 0,
                'ngay_tao' => Carbon::now()->subDays(1),
                'id_admin_xu_ly' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_cu_dan_bao_cao' => 7,
                'noi_dung_bao_cao' => 'Xe máy đậu lấn sang khu vực dành cho ô tô.',
                'trang_thai_xu_ly' => 0,
                'ngay_tao' => Carbon::now()->subDays(1),
                'id_admin_xu_ly' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_cu_dan_bao_cao' => 8,
                'noi_dung_bao_cao' => 'Phản ánh bị mất mũ bảo hiểm hoặc đồ đạc trên xe.',
                'trang_thai_xu_ly' => 0,
                'ngay_tao' => Carbon::now()->subDays(1),
                'id_admin_xu_ly' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

    }
}
