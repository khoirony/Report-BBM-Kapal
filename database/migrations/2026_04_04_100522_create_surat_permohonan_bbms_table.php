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
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat');
            $table->string('klasifikasi')->nullable();
            $table->string('lampiran')->default('1 (satu) berkas');
            $table->integer('user_id')->nullable();
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
