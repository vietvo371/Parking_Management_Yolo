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
        Schema::create('loai_xes', function (Blueprint $table) {
            $table->id();
            $table->string('ten_loai_xe');
            $table->integer('trang_thai')->default(1);
            $table->integer('tien_thu_giu_xe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_xes');
    }
};
