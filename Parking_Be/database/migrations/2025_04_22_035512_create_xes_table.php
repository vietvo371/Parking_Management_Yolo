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
        Schema::create('xes', function (Blueprint $table) {
            $table->integer('id_cu_dan');
            $table->string('bien_so_xe');
            $table->integer('id_loai_xe');
            $table->integer('trang_thai_duyet');
            $table->integer('is_con_han');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xes');
    }
};
