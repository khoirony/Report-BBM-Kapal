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
        Schema::create('ba_pengisian_bbms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapal_id')->constrained('kapals')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('dasar_hukum')->nullable();
            $table->json('petugas_list')->nullable(); 
            $table->string('kegiatan')->default('Pengisian BBM Kapal di Pelabuhan Sunda Kelapa');
            $table->text('tujuan')->nullable();
            $table->string('lokasi')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ba_pengisian_bbms');
    }
};
