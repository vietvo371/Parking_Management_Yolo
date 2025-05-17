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
        Schema::create('giao_diches', function (Blueprint $table) {
            $table->id();
            $table->integer('so_tien_giao_dich');
            $table->integer('id_xe');
            $table->integer('phuong_thuc_thanh_toan');
            $table->integer('trang_thai_giao_dich');
            $table->dateTime('ngay_het_han')->nullable();
            $table->string('ma_giao_dich');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giao_diches');
    }
};
