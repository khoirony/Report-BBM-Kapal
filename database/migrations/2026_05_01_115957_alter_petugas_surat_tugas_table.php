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
        Schema::table('petugas_surat_tugas', function (Blueprint $table) {
            // 1. Hapus foreign key lama terlebih dahulu
            $table->dropForeign(['surat_tugas_pengisian_id']);
            
            // 2. Ubah kolom menjadi nullable
            $table->unsignedBigInteger('surat_tugas_pengisian_id')->nullable()->change();
            
            // 3. Buat ulang foreign key dengan aturan nullOnDelete()
            $table->foreign('surat_tugas_pengisian_id')
                  ->references('id')
                  ->on('surat_tugas_pengisians')
                  ->nullOnDelete();

            // 4. Tambahkan kolom baru untuk Surat Permohonan
            $table->foreignId('surat_permohonan_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('surat_permohonan_pengisians')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petugas_surat_tugas', function (Blueprint $table) {
            // Rollback penambahan kolom surat_permohonan_id
            $table->dropForeign(['surat_permohonan_id']);
            $table->dropColumn('surat_permohonan_id');

            // Rollback perubahan surat_tugas_pengisian_id
            $table->dropForeign(['surat_tugas_pengisian_id']);
            $table->unsignedBigInteger('surat_tugas_pengisian_id')->nullable(false)->change();
            
            $table->foreign('surat_tugas_pengisian_id')
                  ->references('id')
                  ->on('surat_tugas_pengisians')
                  ->cascadeOnDelete();
        });
    }
};