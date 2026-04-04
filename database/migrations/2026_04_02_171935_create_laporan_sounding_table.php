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
        Schema::create('laporan_sounding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_pengisian_id')->constrained('laporan_sebelum_pengisians')->cascadeOnDelete();
            $table->foreignId('sounding_id')->constrained('soundings')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_sounding');
    }
};
