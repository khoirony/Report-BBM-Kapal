<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->string('nama_nakhoda')->nullable()->after('jumlah_bbm');
            $table->string('id_nakhoda')->nullable()->after('nama_nakhoda');
            $table->string('nama_pptk')->nullable()->after('id_nakhoda');
            $table->string('id_pptk')->nullable()->after('nama_pptk');
        });
    }

    public function down(): void
    {
        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->dropColumn(['nama_nakhoda', 'id_nakhoda', 'nama_pptk', 'id_pptk']);
        });
    }
};