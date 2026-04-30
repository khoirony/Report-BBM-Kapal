<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->dropForeign(['surat_tugas_id']);
            $table->dropColumn('surat_tugas_id');
            $table->foreignId('laporan_sisa_bbm_id')->nullable()->after('id')->constrained('laporan_sisa_bbms')->cascadeOnDelete();
        });

        Schema::table('surat_tugas_pengisians', function (Blueprint $table) {
            $table->dropForeign(['laporan_sisa_bbm_id']);
            $table->dropColumn('laporan_sisa_bbm_id');
            $table->foreignId('surat_permohonan_id')->nullable()->after('id')->constrained('surat_permohonan_pengisians')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_tugas_pengisians', function (Blueprint $table) {
            $table->dropForeign(['surat_permohonan_id']);
            $table->dropColumn('surat_permohonan_id');
            $table->foreignId('laporan_sisa_bbm_id')->nullable()->constrained('laporan_sisa_bbms')->cascadeOnDelete();
        });

        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->dropForeign(['laporan_sisa_bbm_id']);
            $table->dropColumn('laporan_sisa_bbm_id');
            $table->foreignId('surat_tugas_id')->nullable()->constrained('surat_tugas_pengisians')->cascadeOnDelete();
        });
    }
};