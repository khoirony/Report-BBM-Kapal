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
        Schema::create('surat_tugas_pengisians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_sisa_bbm_id')->constrained('laporan_sisa_bbms')->cascadeOnDelete();
            $table->foreignId('ukpd_id')
                  ->nullable()
                  ->constrained('ukpds')
                  ->onDelete('set null');
            $table->string('nomor_surat');
            $table->string('lokasi');
            $table->string('waktu_pelaksanaan')->default('08:00 - Selesai');
            $table->date('tanggal_dikeluarkan');
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_pengisians');
    }
};
