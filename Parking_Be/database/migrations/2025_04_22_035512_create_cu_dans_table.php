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
        Schema::create('cu_dans', function (Blueprint $table) {
            $table->id();
            $table->string('ho_va_ten');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('so_dien_thoai')->nullable();
            $table->string('so_cccd')->unique();
            $table->integer('id_can_ho');
            $table->integer('so_du')->default(0);
            $table->integer('phe_duyet')->default(0);
            $table->integer('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cu_dans');
    }
};
