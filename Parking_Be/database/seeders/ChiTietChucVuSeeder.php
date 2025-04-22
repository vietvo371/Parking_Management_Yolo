<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietChucVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chi_tiet_chuc_vus')->delete();
        DB::table('chi_tiet_chuc_vus')->truncate();
        for ($i = 1; $i <= 5; $i++) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 1, // Admin
                'id_chuc_nang' => $i,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Sales staff has product, order, and promotion management
        $salesFunctions = [2, 3, 5]; // Product, Order, Promotion
        foreach ($salesFunctions as $function) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 2, // Sales staff
                'id_chuc_nang' => $function,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Inventory manager has product and category management
        $inventoryFunctions = [2, 4]; // Product, Category
        foreach ($inventoryFunctions as $function) {
            DB::table('chi_tiet_chuc_vus')->insert([
                'id_chuc_vu' => 3, // Inventory manager
                'id_chuc_nang' => $function,
                'tinh_trang' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Accountant has order management
        DB::table('chi_tiet_chuc_vus')->insert([
            'id_chuc_vu' => 4, // Accountant
            'id_chuc_nang' => 3, // Order
            'tinh_trang' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
