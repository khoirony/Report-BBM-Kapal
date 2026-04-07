<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_surat_permohonans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel utama
            $table->foreignId('surat_permohonan_id')
                  ->constrained('surat_permohonan_pengisians')
                  ->cascadeOnDelete();
                  
            $table->string('nama_file');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_surat_permohonans');
    }
};