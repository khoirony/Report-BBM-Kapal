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
        Schema::table('soundings', function (Blueprint $table) {
            // Menambahkan kolom baru setelah bbm_akhir agar rapi
            $table->time('jam_pemeriksaan')->nullable()->after('bbm_akhir');
            
            // Menghapus dua kolom yang lama
            $table->dropColumn(['jam_berangkat', 'jam_kembali']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soundings', function (Blueprint $table) {
            // Mengembalikan dua kolom yang lama (untuk rollback)
            $table->time('jam_berangkat')->nullable()->after('bbm_akhir');
            $table->time('jam_kembali')->nullable()->after('jam_berangkat');
            
            // Menghapus kolom yang baru
            $table->dropColumn('jam_pemeriksaan');
        });
    }
};