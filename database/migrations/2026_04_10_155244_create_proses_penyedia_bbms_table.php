<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proses_penyedia_bbms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_permohonan_id')
                  ->constrained('surat_permohonan_pengisians')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tempat_pengambilan');
            $table->string('nomor_izin_penyedia')->comment('Nomor SPBU / Lembaga / Izin');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->string('file_evidence');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proses_penyedia_bbms');
    }
};