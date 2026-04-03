<?php

namespace Database\Seeders;

use App\Models\SuratTugas;
use App\Models\LaporanPengisian;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SuratTugasSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua Laporan BBM yang sudah ada
        $laporans = LaporanPengisian::orderBy('tanggal', 'asc')->get();

        if ($laporans->isEmpty()) {
            $this->command->info('Tidak ada data Laporan BBM. Buat Laporan BBM terlebih dahulu sebelum menjalankan seeder ini.');
            return;
        }

        $nomorUrut = 1; // Mulai nomor surat dari 1

        foreach ($laporans as $laporan) {
            // Format nomor surat menjadi 3 digit (contoh: 001, 002)
            $nomorSurat = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

            // Tanggal dikeluarkan biasanya H-1 atau sama dengan tanggal pelaksanaan
            $tanggalDikeluarkan = Carbon::parse($laporan->tanggal)->subDay();
            
            // Jika H-1 jatuh di hari Minggu (0), mundurkan ke hari Jumat
            if ($tanggalDikeluarkan->dayOfWeek === Carbon::SUNDAY) {
                $tanggalDikeluarkan->subDays(2);
            }

            SuratTugas::create([
                'laporan_pengisian_id' => $laporan->id,
                'nomor_surat' => $nomorSurat,
                'waktu_pelaksanaan' => '08:00 - Selesai',
                'tanggal_dikeluarkan' => $tanggalDikeluarkan->format('Y-m-d'),
            ]);

            $nomorUrut++;
        }
    }
}