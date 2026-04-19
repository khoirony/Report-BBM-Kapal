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
        Schema::table('surat_tugas_pengisians', function (Blueprint $table) {
            $table->string('nama_kepala_ukpd')->nullable()->after('pakaian');
            $table->string('id_kepala_ukpd')->nullable()->after('nama_kepala_ukpd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_tugas_pengisians', function (Blueprint $table) {
            $table->dropColumn(['nama_kepala_ukpd', 'id_kepala_ukpd']);
        });
    }
};