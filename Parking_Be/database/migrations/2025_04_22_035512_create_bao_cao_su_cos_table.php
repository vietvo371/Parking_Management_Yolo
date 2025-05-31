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
        Schema::create('bao_cao_su_cos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cu_dan_bao_cao');
            $table->longText('noi_dung_bao_cao')->nullable();
            $table->integer('trang_thai_xu_ly')->default(0);
            $table->dateTime('ngay_tao');
            $table->integer('id_admin_xu_ly')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bao_cao_su_cos');
    }
};
