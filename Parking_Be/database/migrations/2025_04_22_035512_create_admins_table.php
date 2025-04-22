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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('ho_va_ten');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('id_chuc_vu');
            $table->string('so_dien_thoai')->nullable();
            $table->integer('is_block')->default(0)->comment('0: không bị khóa, 1: bị khóa');
            $table->integer('is_master')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
