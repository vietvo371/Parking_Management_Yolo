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
        Schema::create('chi_tiet_bai_xes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bai_xe');
            $table->string('thu_tu');
            $table->string('loai_xe')->nullable();
            $table->integer('is_cu_dan')->default(0)->comment('0: không phải cư dân, 1: cư dân');
            $table->integer('is_khach_hang')->default(0)->comment('0: không phải khách hàng, 1: khách hàng');
            $table->integer('trang_thai')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_bai_xes');
    }
};
