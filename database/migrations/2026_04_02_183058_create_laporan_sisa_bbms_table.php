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
        Schema::create('laporan_sisa_bbms', function (Blueprint $table) {
            $table->id();$table->string('nomor');
            $table->foreignId('sounding_id')->constrained('soundings')->cascadeOnDelete();
            $table->foreignId('ukpd_id')
                  ->nullable()
                  ->constrained('ukpds')
                  ->onDelete('set null');
            $table->date('tanggal_surat');
            $table->string('klasifikasi')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('perihal')->default('Laporan Perhitungan Jumlah Sisa BBM Kapal Sebelum Pengisian');
            $table->string('file_laporan')->nullable()->comment('Path penyimpanan file dokumen laporan');
            $table->string('nama_nakhoda');
            $table->string('id_nakhoda')->nullable()->comment('Menyimpan Nomor Pegawai/ID Nakhoda');
            $table->string('nama_pengawas');
            $table->string('id_pengawas')->nullable()->comment('Menyimpan Nomor Pegawai/NIP/NRK Pengawas');
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_sisa_bbms');
    }
};
