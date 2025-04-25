<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanHoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('can_hos')->delete();
        DB::table('can_hos')->truncate();

        $canHos = [
            ['tang' => 1, 'so_can_ho' => '101', 'chu_ho' => 'Nguyễn Huy'],
            ['tang' => 1, 'so_can_ho' => '102', 'chu_ho' => 'Trần Hùng'],
            ['tang' => 1, 'so_can_ho' => '103', 'chu_ho' => 'Phạm Đạt'],
            ['tang' => 1, 'so_can_ho' => '104', 'chu_ho' => 'Lê Bảo'],
            ['tang' => 1, 'so_can_ho' => '105', 'chu_ho' => 'Nguyễn Lan'],
            ['tang' => 1, 'so_can_ho' => '106', 'chu_ho' => 'Trần Mai'],
            ['tang' => 1, 'so_can_ho' => '107', 'chu_ho' => 'Phạm Quang'],
            ['tang' => 1, 'so_can_ho' => '108', 'chu_ho' => 'Lê Hải'],
            ['tang' => 1, 'so_can_ho' => '109', 'chu_ho' => 'Nguyễn Tùng'],
            ['tang' => 1, 'so_can_ho' => '110', 'chu_ho' => 'Trần Sơn'],

            ['tang' => 2, 'so_can_ho' => '201', 'chu_ho' => 'Phạm Tuấn'],
            ['tang' => 2, 'so_can_ho' => '202', 'chu_ho' => 'Lê Kiên'],
            ['tang' => 2, 'so_can_ho' => '203', 'chu_ho' => 'Nguyễn Minh'],
            ['tang' => 2, 'so_can_ho' => '204', 'chu_ho' => 'Vũ Hòa'],
            ['tang' => 2, 'so_can_ho' => '205', 'chu_ho' => 'Nguyễn Chiến'],
            ['tang' => 2, 'so_can_ho' => '206', 'chu_ho' => 'Trần Văn'],
            ['tang' => 2, 'so_can_ho' => '207', 'chu_ho' => 'Phạm Quân'],
            ['tang' => 2, 'so_can_ho' => '208', 'chu_ho' => 'Lê Thảo'],
            ['tang' => 2, 'so_can_ho' => '209', 'chu_ho' => 'Nguyễn Quý'],
            ['tang' => 2, 'so_can_ho' => '210', 'chu_ho' => 'Trần Lê'],

            ['tang' => 3, 'so_can_ho' => '301', 'chu_ho' => 'Phạm Kiên'],
            ['tang' => 3, 'so_can_ho' => '302', 'chu_ho' => 'Lê Bình'],
            ['tang' => 3, 'so_can_ho' => '303', 'chu_ho' => 'Nguyễn Thọ'],
            ['tang' => 3, 'so_can_ho' => '304', 'chu_ho' => 'Trần Cường'],
            ['tang' => 3, 'so_can_ho' => '305', 'chu_ho' => 'Phạm Thành'],
            ['tang' => 3, 'so_can_ho' => '306', 'chu_ho' => 'Lê Mạnh'],
            ['tang' => 3, 'so_can_ho' => '307', 'chu_ho' => 'Nguyễn Hải'],
            ['tang' => 3, 'so_can_ho' => '308', 'chu_ho' => 'Trần Duy'],
            ['tang' => 3, 'so_can_ho' => '309', 'chu_ho' => 'Phạm Nam'],
            ['tang' => 3, 'so_can_ho' => '310', 'chu_ho' => 'Lê Tấn'],

            ['tang' => 4, 'so_can_ho' => '401', 'chu_ho' => 'Nguyễn Tiến'],
            ['tang' => 4, 'so_can_ho' => '402', 'chu_ho' => 'Trần Quyết'],
            ['tang' => 4, 'so_can_ho' => '403', 'chu_ho' => 'Phạm Khánh'],
            ['tang' => 4, 'so_can_ho' => '404', 'chu_ho' => 'Lê Hưng'],
            ['tang' => 4, 'so_can_ho' => '405', 'chu_ho' => 'Nguyễn An'],
            ['tang' => 4, 'so_can_ho' => '406', 'chu_ho' => 'Trần Bảo'],
            ['tang' => 4, 'so_can_ho' => '407', 'chu_ho' => 'Phạm Khoa'],
            ['tang' => 4, 'so_can_ho' => '408', 'chu_ho' => 'Lê Anh'],
            ['tang' => 4, 'so_can_ho' => '409', 'chu_ho' => 'Nguyễn Quyền'],
            ['tang' => 4, 'so_can_ho' => '410', 'chu_ho' => 'Trần Lâm'],

            ['tang' => 5, 'so_can_ho' => '501', 'chu_ho' => 'Phạm Hậu'],
            ['tang' => 5, 'so_can_ho' => '502', 'chu_ho' => 'Lê Thành'],
            ['tang' => 5, 'so_can_ho' => '503', 'chu_ho' => 'Nguyễn Hòa'],
            ['tang' => 5, 'so_can_ho' => '504', 'chu_ho' => 'Trần Phú'],
            ['tang' => 5, 'so_can_ho' => '505', 'chu_ho' => 'Phạm Hải'],
            ['tang' => 5, 'so_can_ho' => '506', 'chu_ho' => 'Lê Nam'],
            ['tang' => 5, 'so_can_ho' => '507', 'chu_ho' => 'Nguyễn Cường'],
            ['tang' => 5, 'so_can_ho' => '508', 'chu_ho' => 'Trần Đức'],
            ['tang' => 5, 'so_can_ho' => '509', 'chu_ho' => 'Phạm Hưng'],
            ['tang' => 5, 'so_can_ho' => '510', 'chu_ho' => 'Lê Trung'],
        ];

        DB::table('can_hos')->insert($canHos);
    }
}
