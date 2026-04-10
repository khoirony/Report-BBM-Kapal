<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pengisian_bbms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukpd_id')->nullable()->constrained('ukpds')->onDelete('set null');
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas_pengisians')->cascadeOnDelete();
            $table->foreignId('surat_permohonan_id')->constrained('surat_permohonan_pengisians')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('dasar_hukum')->nullable();
            $table->string('lokasi_pengisian');
            $table->string('kegiatan')->default('Pengisian BBM KDO Khusus');
            $table->string('tujuan')->default('Memastikan ketersediaan BBM Kapal untuk menunjang kegiatan Operasional');
            $table->unsignedBigInteger('sounding_awal_id')->nullable(); 
            $table->decimal('jumlah_bbm_awal', 10, 2)->default(0);
            $table->decimal('jumlah_bbm_pengisian', 10, 2)->default(0);
            $table->decimal('pemakaian_bbm', 10, 2)->nullable()->default(0)->comment('Kosongkan jika BBM dikirim ke pelabuhan');
            $table->unsignedBigInteger('sounding_akhir_id')->nullable();
            $table->decimal('jumlah_bbm_akhir', 10, 2)->default(0);
            $table->time('jam_berangkat')->nullable();
            $table->time('jam_kembali')->nullable();
            $table->json('dokumentasi_foto')->nullable();
            $table->integer('user_id')->nullable();
            $table->unsignedBigInteger('disetujui_nakhoda_by')->nullable();
            $table->timestamp('disetujui_nakhoda_at')->nullable();
            $table->unsignedBigInteger('disetujui_penyedia_by')->nullable();
            $table->timestamp('disetujui_penyedia_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pengisian_bbms');
    }
};