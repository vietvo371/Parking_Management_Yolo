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
        Schema::create('can_hos', function (Blueprint $table) {
            $table->id();
            $table->integer('tang');
            $table->integer('so_can_ho');
            $table->string('chu_ho');
            $table->string('ten_toa_nha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('can_hos');
    }
};
