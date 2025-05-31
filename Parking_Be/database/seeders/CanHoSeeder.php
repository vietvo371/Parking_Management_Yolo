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
            ['tang' => 1, 'so_can_ho' => '101', 'chu_ho' => 'Nguyễn Huy', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '102', 'chu_ho' => 'Trần Hùng', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '103', 'chu_ho' => 'Phạm Đạt', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '104', 'chu_ho' => 'Lê Bảo', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '105', 'chu_ho' => 'Nguyễn Lan', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '106', 'chu_ho' => 'Trần Mai', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '107', 'chu_ho' => 'Phạm Quang', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '108', 'chu_ho' => 'Lê Hải', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '109', 'chu_ho' => 'Nguyễn Tùng', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 1, 'so_can_ho' => '110', 'chu_ho' => 'Trần Sơn', 'ten_toa_nha' => 'Tòa Nhà ABC'],

            ['tang' => 2, 'so_can_ho' => '201', 'chu_ho' => 'Phạm Tuấn', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '202', 'chu_ho' => 'Lê Kiên', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '203', 'chu_ho' => 'Nguyễn Minh', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '204', 'chu_ho' => 'Vũ Hòa', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '205', 'chu_ho' => 'Nguyễn Chiến', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '206', 'chu_ho' => 'Trần Văn', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '207', 'chu_ho' => 'Phạm Quân', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '208', 'chu_ho' => 'Lê Thảo', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '209', 'chu_ho' => 'Nguyễn Quý', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 2, 'so_can_ho' => '210', 'chu_ho' => 'Trần Lê', 'ten_toa_nha' => 'Tòa Nhà ABC'],

            ['tang' => 3, 'so_can_ho' => '301', 'chu_ho' => 'Phạm Kiên', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '302', 'chu_ho' => 'Lê Bình', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '303', 'chu_ho' => 'Nguyễn Thọ', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '304', 'chu_ho' => 'Trần Cường', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '305', 'chu_ho' => 'Phạm Thành', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '306', 'chu_ho' => 'Lê Mạnh', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '307', 'chu_ho' => 'Nguyễn Hải', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '308', 'chu_ho' => 'Trần Duy', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '309', 'chu_ho' => 'Phạm Nam', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 3, 'so_can_ho' => '310', 'chu_ho' => 'Lê Tấn', 'ten_toa_nha' => 'Tòa Nhà ABC'],

            ['tang' => 4, 'so_can_ho' => '401', 'chu_ho' => 'Nguyễn Tiến', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '402', 'chu_ho' => 'Trần Quyết', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '403', 'chu_ho' => 'Phạm Khánh', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '404', 'chu_ho' => 'Lê Hưng', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '405', 'chu_ho' => 'Nguyễn An', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '406', 'chu_ho' => 'Trần Bảo', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '407', 'chu_ho' => 'Phạm Khoa', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '408', 'chu_ho' => 'Lê Anh', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '409', 'chu_ho' => 'Nguyễn Quyền', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 4, 'so_can_ho' => '410', 'chu_ho' => 'Trần Lâm', 'ten_toa_nha' => 'Tòa Nhà ABC'],

            ['tang' => 5, 'so_can_ho' => '501', 'chu_ho' => 'Phạm Hậu', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '502', 'chu_ho' => 'Lê Thành', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '503', 'chu_ho' => 'Nguyễn Hòa', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '504', 'chu_ho' => 'Trần Phú', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '505', 'chu_ho' => 'Phạm Hải', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '506', 'chu_ho' => 'Lê Nam', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '507', 'chu_ho' => 'Nguyễn Cường', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '508', 'chu_ho' => 'Trần Đức', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '509', 'chu_ho' => 'Phạm Hưng', 'ten_toa_nha' => 'Tòa Nhà ABC'],
            ['tang' => 5, 'so_can_ho' => '510', 'chu_ho' => 'Lê Trung', 'ten_toa_nha' => 'Tòa Nhà ABC'],
        ];


        DB::table('can_hos')->insert($canHos);
    }
}
