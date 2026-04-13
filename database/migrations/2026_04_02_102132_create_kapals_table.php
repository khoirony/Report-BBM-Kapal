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
        Schema::create('kapals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kapal')->nullable();
            $table->foreignId('ukpd_id')
                  ->nullable()
                  ->constrained('ukpds')
                  ->onDelete('set null');
            $table->string('jenis_dan_tipe')->nullable();
            $table->string('material')->nullable();
            $table->integer('tahun_pembuatan')->nullable();
            $table->string('ukuran')->nullable(); // PxLxD
            $table->string('tonase_kotor_gt')->nullable();
            $table->string('tenaga_penggerak_kw')->nullable();
            $table->text('daerah_pelayaran')->nullable();
            $table->text('list_sertifikat_kapal')->nullable();
            $table->string('foto_kapal')->nullable();
            $table->integer('user_id')->nullable();
            $table->foreignId('nahkoda_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapals');
    }
};
