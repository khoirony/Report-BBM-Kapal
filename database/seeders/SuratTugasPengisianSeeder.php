<?php

namespace Database\Seeders;

use App\Models\SuratTugasPengisian;
use App\Models\LaporanSisaBbm;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SuratTugasPengisianSeeder extends Seeder
{
    public function run(): void
    {
        $laporans = LaporanSisaBbm::orderBy('tanggal_surat', 'asc')->get();

        if ($laporans->isEmpty()) {
            $this->command->info('Tidak ada data Laporan BBM. Buat Laporan BBM terlebih dahulu sebelum menjalankan seeder ini.');
            return;
        }

        $nomorUrut = 1; // Mulai nomor surat dari 1

        foreach ($laporans as $laporan) {
            // Tanggal dikeluarkan biasanya H-1 atau sama dengan tanggal pelaksanaan
            $tanggalDikeluarkan = Carbon::parse($laporan->tanggal_surat)->subDay();
            
            // Jika H-1 jatuh di hari Minggu (0), mundurkan ke hari Jumat
            if ($tanggalDikeluarkan->dayOfWeek === Carbon::SUNDAY) {
                $tanggalDikeluarkan->subDays(2);
            }

            // Format nomor surat menjadi full (Contoh: 001/PH.12.00/2026)
            $nomorSurat = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT) . '/PH.12.00/' . $tanggalDikeluarkan->format('Y');

            SuratTugasPengisian::create([
                'laporan_sisa_bbm_id' => $laporan->id,
                'ukpd_id'             => $laporan->ukpd_id, // Menyalin ukpd_id dari Laporan BBM
                'nomor_surat'         => $nomorSurat,
                'waktu_pelaksanaan'   => '08:00 - Selesai',
                'tanggal_dikeluarkan' => $tanggalDikeluarkan->format('Y-m-d'),
                'user_id'             => 3,
            ]);

            $nomorUrut++;
        }

        $this->command->info('Data Surat Tugas Pengisian berhasil di-seed dengan nomor surat penuh!');
    }
}