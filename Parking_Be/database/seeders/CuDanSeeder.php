<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuDanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cu_dans')->delete();
        DB::table('cu_dans')->truncate();

        $cuDans = [
            ['ho_va_ten' => 'Nguyễn Huy', 'email' => 'nguyenhuy@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0901234567', 'so_cccd' => '012345678901', 'id_can_ho' => 1, 'so_du' => 500000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Hùng', 'email' => 'tranhung@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0912345678', 'so_cccd' => '112345678901', 'id_can_ho' => 2, 'so_du' => 600000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Đạt', 'email' => 'phamdat@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0923456789', 'so_cccd' => '212345678911', 'id_can_ho' => 3, 'so_du' => 700000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Bảo', 'email' => 'lebao@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0934567890', 'so_cccd' => '312345678901', 'id_can_ho' => 4, 'so_du' => 800000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Lan', 'email' => 'nguyenlan@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0945678901', 'so_cccd' => '412345678906', 'id_can_ho' => 5, 'so_du' => 900000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Mai', 'email' => 'tranmai@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0956789012', 'so_cccd' => '512345678901', 'id_can_ho' => 6, 'so_du' => 450000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Quang', 'email' => 'phamquang@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0967890123', 'so_cccd' => '612345678901', 'id_can_ho' => 7, 'so_du' => 550000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Hải', 'email' => 'lehai@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0978901234', 'so_cccd' => '712345678901', 'id_can_ho' => 8, 'so_du' => 650000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Tùng', 'email' => 'nguyentung@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0989012345', 'so_cccd' => '812345678901', 'id_can_ho' => 9, 'so_du' => 750000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Sơn', 'email' => 'transon@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0990123456', 'so_cccd' => '912345678901', 'id_can_ho' => 10, 'so_du' => 850000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Tuấn', 'email' => 'phamtuan@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0901234567', 'so_cccd' => '022345678901', 'id_can_ho' => 11, 'so_du' => 500000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Kiên', 'email' => 'lekien@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0911234567', 'so_cccd' => '132345678901', 'id_can_ho' => 12, 'so_du' => 600000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Minh', 'email' => 'nguyenminh@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0922234567', 'so_cccd' => '142345678901', 'id_can_ho' => 13, 'so_du' => 700000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Vũ Hòa', 'email' => 'vuhòa@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0933345678', 'so_cccd' => '152345678901', 'id_can_ho' => 14, 'so_du' => 800000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Chiến', 'email' => 'nguyenchien@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0944456789', 'so_cccd' => '162345678901', 'id_can_ho' => 15, 'so_du' => 900000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Văn', 'email' => 'tranvan@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0955567890', 'so_cccd' => '172345678901', 'id_can_ho' => 16, 'so_du' => 450000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Quân', 'email' => 'phamquan@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0966678901', 'so_cccd' => '182345678901', 'id_can_ho' => 17, 'so_du' => 550000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Thảo', 'email' => 'lethao@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0977789012', 'so_cccd' => '192345678901', 'id_can_ho' => 18, 'so_du' => 650000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Quý', 'email' => 'nguyenquy@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0988890123', 'so_cccd' => '202345678901', 'id_can_ho' => 19, 'so_du' => 750000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Lê', 'email' => 'tranle@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0999901234', 'so_cccd' => '212345678901', 'id_can_ho' => 20, 'so_du' => 850000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Kiên', 'email' => 'phamkien@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0901234567', 'so_cccd' => '222345678901', 'id_can_ho' => 21, 'so_du' => 500000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Bình', 'email' => 'lebinh@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0912345678', 'so_cccd' => '232345678901', 'id_can_ho' => 22, 'so_du' => 600000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Thọ', 'email' => 'nguyentho@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0901234567', 'so_cccd' => '242345678901', 'id_can_ho' => 23, 'so_du' => 500000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Cường', 'email' => 'trancuong@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0912345678', 'so_cccd' => '252345678901', 'id_can_ho' => 24, 'so_du' => 600000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Thành', 'email' => 'phamthanh@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0923456789', 'so_cccd' => '262345678901', 'id_can_ho' => 25, 'so_du' => 700000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Mạnh', 'email' => 'lemanh@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0934567890', 'so_cccd' => '272345678901', 'id_can_ho' => 26, 'so_du' => 800000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Hải', 'email' => 'nguyenhai@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0945678901', 'so_cccd' => '282345678901', 'id_can_ho' => 27, 'so_du' => 900000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Duy', 'email' => 'tranduy@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0956789012', 'so_cccd' => '292345678901', 'id_can_ho' => 28, 'so_du' => 450000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Nam', 'email' => 'phamnam@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0967890123', 'so_cccd' => '302345678901', 'id_can_ho' => 29, 'so_du' => 550000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Tấn', 'email' => 'letan@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0978901234', 'so_cccd' => '312345678909', 'id_can_ho' => 30, 'so_du' => 650000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Tiến', 'email' => 'nguyentiens@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0989012345', 'so_cccd' => '322345678901', 'id_can_ho' => 31, 'so_du' => 750000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Quyết', 'email' => 'tranquet@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0990123456', 'so_cccd' => '332345678901', 'id_can_ho' => 32, 'so_du' => 850000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Khánh', 'email' => 'phamkhanh@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0901234567', 'so_cccd' => '342345678901', 'id_can_ho' => 33, 'so_du' => 500000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Hưng', 'email' => 'lehung@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0912345678', 'so_cccd' => '352345678901', 'id_can_ho' => 34, 'so_du' => 600000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn An', 'email' => 'nguyenan@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0923456789', 'so_cccd' => '362345678901', 'id_can_ho' => 35, 'so_du' => 700000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Bảo', 'email' => 'tranbao@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0934567890', 'so_cccd' => '372345678901', 'id_can_ho' => 36, 'so_du' => 800000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Khoa', 'email' => 'phamkhoa@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0945678901', 'so_cccd' => '382345678901', 'id_can_ho' => 37, 'so_du' => 900000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Anh', 'email' => 'leanh@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0956789012', 'so_cccd' => '392345678901', 'id_can_ho' => 38, 'so_du' => 450000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Quyền', 'email' => 'nguyenquyen@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0967890123', 'so_cccd' => '402345678901', 'id_can_ho' => 39, 'so_du' => 550000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Lâm', 'email' => 'tranlam@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0978901234', 'so_cccd' => '412345678901', 'id_can_ho' => 40, 'so_du' => 650000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Hậu', 'email' => 'phamhau@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0989012345', 'so_cccd' => '422345678901', 'id_can_ho' => 41, 'so_du' => 750000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Thành', 'email' => 'lethanh@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0990123456', 'so_cccd' => '432345678901', 'id_can_ho' => 42, 'so_du' => 850000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Hòa', 'email' => 'nguyenhoa@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0901234567', 'so_cccd' => '442345678901', 'id_can_ho' => 43, 'so_du' => 500000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Phú', 'email' => 'tranphu@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0912345678', 'so_cccd' => '452345678901', 'id_can_ho' => 44, 'so_du' => 600000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Hải', 'email' => 'phamhai@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0923456789', 'so_cccd' => '462345678901', 'id_can_ho' => 45, 'so_du' => 700000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Nam', 'email' => 'lenam@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0934567890', 'so_cccd' => '472345678901', 'id_can_ho' => 46, 'so_du' => 800000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Nguyễn Cường', 'email' => 'nguyencuong@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0945678901', 'so_cccd' => '482345678901', 'id_can_ho' => 47, 'so_du' => 900000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Trần Đức', 'email' => 'tranduc@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0956789012', 'so_cccd' => '492345678901', 'id_can_ho' => 48, 'so_du' => 450000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Phạm Hưng', 'email' => 'phamhung@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0967890123', 'so_cccd' => '502345678901', 'id_can_ho' => 49, 'so_du' => 550000, 'phe_duyet' => 1, 'trang_thai' => 1],
            ['ho_va_ten' => 'Lê Trung', 'email' => 'letrung@example.com', 'password' => bcrypt('123456'), 'so_dien_thoai' => '0967890124', 'so_cccd' => '502345678945', 'id_can_ho' => 50, 'so_du' => 550000, 'phe_duyet' => 1, 'trang_thai' => 1],

        ];
        foreach ($cuDans as $cuDan) {
            DB::table('cu_dans')->insert($cuDan);
        }
    }
}
