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
        Schema::create('petugas_surat_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_tugas_pengisian_id')
                  ->constrained('surat_tugas_pengisians')
                  ->cascadeOnDelete();
                  
            $table->string('nama_petugas'); 
            $table->string('jabatan');
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
