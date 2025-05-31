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
        Schema::create('admin_thong_baos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_admin')->nullable();
            $table->integer('id_cu_dan')->nullable();
            $table->longText('noi_dung_thong_bao');
            $table->dateTime('ngay_tao');
            $table->integer('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_thong_baos');
    }
};
