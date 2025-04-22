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
        Schema::create('lich_su_ra_vao_bai_xes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_xe_cu_dan');
            $table->integer('id_camera_quet');
            $table->dateTime('thoi_gian_vao');
            $table->dateTime('thoi_gian_ra');
            $table->integer('id_vi_tri_trong_bai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_ra_vao_bai_xes');
    }
};
