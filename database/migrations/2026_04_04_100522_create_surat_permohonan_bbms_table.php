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
        Schema::create('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas_pengisians')->cascadeOnDelete();
            $table->foreignId('ukpd_id')
                  ->nullable()
                  ->constrained('ukpds')
                  ->onDelete('set null');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat');
            $table->string('klasifikasi')->nullable();
            $table->string('lampiran')->default('1 (satu) berkas');
            $table->foreignId('penyedia_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Relasi ke tabel users dengan role penyedia');
            $table->string('jenis_penyedia_bbm')->nullable(); 
            $table->string('tempat_pengambilan_bbm')->nullable();
            $table->enum('metode_pengiriman', [
                'Ambil ditempat', 
                'Pengiriman Jalur Darat', 
                'Pengiriman Jalur Laut'
            ])->nullable();
            $table->string('jenis_bbm')->nullable();
            $table->decimal('jumlah_bbm', 10, 2)->nullable()->comment('Dalam satuan liter');
            $table->string('file_surat_permohonan')->nullable()->comment('Path penyimpanan file surat permohonan');
            $table->integer('user_id')->nullable();
            $table->enum('progress', ['not started', 'on progress', 'done'])->default('not started');
            $table->unsignedBigInteger('disetujui_pptk_by')->nullable();
            $table->timestamp('disetujui_pptk_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_permohonan_pengisians');
    }
};
