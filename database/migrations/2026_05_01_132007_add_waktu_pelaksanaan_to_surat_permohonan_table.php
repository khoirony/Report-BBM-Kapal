<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->date('tanggal_pelaksanaan')->nullable()->after('tanggal_surat');
            $table->string('waktu_pelaksanaan')->nullable()->after('tanggal_pelaksanaan');
        });
    }

    public function down(): void
    {
        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pelaksanaan', 'waktu_pelaksanaan']);
        });
    }
};