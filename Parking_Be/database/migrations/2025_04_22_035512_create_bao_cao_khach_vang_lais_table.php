<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bao_cao_khach_vang_lais', function (Blueprint $table) {
            $table->id();
            $table->integer('id_admin');
            $table->string('ho_va_ten');
            $table->string('so_dien_thoai')->nullable();
            $table->dateTime('thoi_gian_vao');
            $table->dateTime('thoi_gian_ra')->nullable();
            $table->integer('tien_thanh_toan')->default(0);
            $table->integer('is_thanh_toan')->default(0);
            $table->integer('id_vi_tri_trong_bai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bao_cao_khach_vang_lais');
    }
};
