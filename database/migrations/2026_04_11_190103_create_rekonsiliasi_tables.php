<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Surat Berita Acara Rekonsiliasi (Dibuat Dishub per bulan)
        Schema::create('rekonsiliasi_bas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukpd_id')->constrained('ukpds')->cascadeOnDelete();
            $table->foreignId('penyedia_id')->constrained('users')->cascadeOnDelete();
            $table->string('bulan_tahun', 7); // Format: YYYY-MM
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->decimal('total_liter_disetujui', 12, 2)->default(0);
            $table->decimal('total_harga_disetujui', 15, 2)->default(0);
            $table->string('file_surat_ttd')->nullable()->comment('PDF final yang ditandatangani');
            $table->enum('status', ['draft', 'menunggu_ttd', 'selesai'])->default('draft');
            $table->timestamps();
        });

        // 2. Tabel Invoice/Tagihan (Diunggah oleh Penyedia)
        Schema::create('rekonsiliasi_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyedia_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ukpd_id')->constrained('ukpds')->cascadeOnDelete();
            $table->foreignId('rekonsiliasi_ba_id')->nullable()->constrained('rekonsiliasi_bas')->nullOnDelete();
            $table->string('nomor_invoice');
            $table->date('tanggal_invoice');
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->decimal('total_tagihan', 15, 2);
            $table->string('file_evidence')->comment('Invoice/Faktur/Bukti Bayar');
            $table->enum('status', ['pending', 'satgas_approved', 'pptk_approved', 'rejected'])->default('pending');
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
        });

        // 3. Update tabel surat_permohonan_pengisians (Menambahkan relasi ke Invoice)
        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->foreignId('rekonsiliasi_invoice_id')
                  ->nullable()
                  ->after('progress')
                  ->constrained('rekonsiliasi_invoices')
                  ->nullOnDelete()
                  ->comment('Penanda bahwa surat ini sudah masuk tagihan penyedia');
        });
    }

    public function down(): void
    {
        Schema::table('surat_permohonan_pengisians', function (Blueprint $table) {
            $table->dropForeign(['rekonsiliasi_invoice_id']);
            $table->dropColumn('rekonsiliasi_invoice_id');
        });
        Schema::dropIfExists('rekonsiliasi_invoices');
        Schema::dropIfExists('rekonsiliasi_bas');
    }
};