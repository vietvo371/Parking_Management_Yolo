<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->delete();
        DB::table('admins')->truncate();
         // Create master admin
         DB::table('admins')->insert([
            'ho_va_ten' => 'Admin Master',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'id_chuc_vu' => 1, // Admin role
            'so_dien_thoai' => '0987654321',
            'is_block' => 0,
            'is_master' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create regular staff users
        $staffData = [
            [
                'ho_va_ten' => 'Nhân viên Bán hàng',
                'email' => 'sales@example.com',
                'id_chuc_vu' => 2,
                'so_dien_thoai' => '0912345678',
            ],
            [
                'ho_va_ten' => 'Quản lý Kho',
                'email' => 'inventory@example.com',
                'id_chuc_vu' => 3,
                'so_dien_thoai' => '0923456789',
            ],
            [
                'ho_va_ten' => 'Kế toán',
                'email' => 'accountant@example.com',
                'id_chuc_vu' => 4,
                'so_dien_thoai' => '0934567890',
            ],
        ];

        foreach ($staffData as $staff) {
            DB::table('admins')->insert([
                'ho_va_ten' => $staff['ho_va_ten'],
                'email' => $staff['email'],
                'password' => Hash::make('password123'),
                'id_chuc_vu' => $staff['id_chuc_vu'],
                'so_dien_thoai' => $staff['so_dien_thoai'],
                'is_block' => 0,
                'is_master' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
