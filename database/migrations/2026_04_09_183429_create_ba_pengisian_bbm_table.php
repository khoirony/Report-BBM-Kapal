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
        Schema::create('ba_pengisian_bbms', function (Blueprint $table) {
            $table->id();
            // Relasi ke Laporan Pengisian sebelumnya
            $table->foreignId('laporan_pengisian_bbm_id')->nullable()->constrained('laporan_pengisian_bbms');
            $table->foreignId('kapal_id')->nullable()->constrained('kapals');
            
            // Field Manual/Dropdown Memory
            $table->string('nomor_pks')->nullable();
            $table->date('tanggal_pks')->nullable();
            $table->date('tgl_ba')->nullable();
            $table->string('dasar_hukum')->nullable();
            $table->string('kegiatan')->nullable();
            $table->text('tujuan')->nullable();
            $table->string('lokasi')->nullable();
            
            $table->string('file_ba_pengisian')->nullable()->comment('Path penyimpanan file BA Pengisian BBM');
            
            // Sistem Persetujuan Bertingkat
            $table->unsignedBigInteger('disetujui_penyedia_by')->nullable();
            $table->timestamp('disetujui_penyedia_at')->nullable();
            $table->unsignedBigInteger('disetujui_pptk_by')->nullable();
            $table->timestamp('disetujui_pptk_at')->nullable();
            $table->unsignedBigInteger('disetujui_kepala_ukpd_by')->nullable();
            $table->timestamp('disetujui_kepala_ukpd_at')->nullable();
            
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ba_pengisian_bbms');
    }
};
