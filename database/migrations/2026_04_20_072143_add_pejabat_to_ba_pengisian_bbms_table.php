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
        Schema::table('ba_pengisian_bbms', function (Blueprint $table) {
            $table->string('nama_pptk')->nullable()->after('tanggal_pks');
            $table->string('id_pptk')->nullable()->after('nama_pptk');
            $table->string('nama_kepala_ukpd')->nullable()->after('id_pptk');
            $table->string('id_kepala_ukpd')->nullable()->after('nama_kepala_ukpd');
            $table->string('nama_nakhoda')->nullable()->after('id_kepala_ukpd');
            $table->string('id_nakhoda')->nullable()->after('nama_nakhoda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ba_pengisian_bbms', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pptk', 
                'id_pptk', 
                'nama_kepala_ukpd', 
                'id_kepala_ukpd', 
                'nama_nakhoda', 
                'id_nakhoda'
            ]);
        });
    }
};