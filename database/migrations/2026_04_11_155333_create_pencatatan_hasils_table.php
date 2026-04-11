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
        Schema::create('pencatatan_hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_permohonan_id')
                  ->constrained('surat_permohonan_pengisians')
                  ->onDelete('cascade');
            $table->foreignId('kapal_id')->constrained('kapals')->onDelete('cascade');
            $table->date('tanggal_pengisian');
            $table->decimal('jumlah_pengisian', 10, 2);
            $table->string('foto_proses');
            $table->string('foto_flow_meter');
            $table->string('foto_struk');
            $table->timestamp('disetujui_pengawas_at')->nullable();
            $table->timestamp('disetujui_penyedia_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencatatan_hasils');
    }
};
