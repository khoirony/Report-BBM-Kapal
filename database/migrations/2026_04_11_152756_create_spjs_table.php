<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spjs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_spj')->unique();
            
            // Relasi ke tabel kapals
            $table->foreignId('kapal_id')
                  ->constrained('kapals')
                  ->onDelete('cascade');
                  
            $table->date('tanggal_spj');
            $table->string('file_spj'); // Menyimpan path file PDF/dokumen SPJ
            
            // Status persetujuan bertingkat
            $table->unsignedBigInteger('disetujui_pptk_by')->nullable();
            $table->timestamp('disetujui_pptk_at')->nullable();
            $table->unsignedBigInteger('disetujui_kepala_ukpd_by')->nullable();
            $table->timestamp('disetujui_kepala_ukpd_at')->nullable();
            
            // Relasi ke User yang mengupload (Satgas / Admin UKPD)
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('cascade');
                  
            // Untuk membatasi akses per UKPD (diambil dari user yang create)
            $table->foreignId('ukpd_id')
                  ->nullable()
                  ->constrained('ukpds')
                  ->onDelete('cascade');
                  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};