<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soundings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapal_id')->constrained('kapals')->cascadeOnDelete();
            $table->foreignId('laporan_pengisian_id')->nullable()->constrained('laporan_sebelum_pengisians')->nullOnDelete();
            $table->string('lokasi')->comment('Contoh: Pom Bensin (Awal), Titik A, Titik B');
            $table->decimal('bbm_awal', 10, 2)->default(0);
            $table->decimal('pengisian', 10, 2)->default(0);
            $table->decimal('pemakaian', 10, 2)->default(0);
            $table->decimal('bbm_akhir', 10, 2)->default(0);
            $table->time('jam_berangkat')->nullable();
            $table->time('jam_kembali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soundings');
    }
};