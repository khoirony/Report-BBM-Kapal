<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPermohonanPengisian;
use App\Models\SuratTugasPengisian;

class SuratPermohonanPengisianSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan sudah ada data di tabel surat_tugas sebelumnya
        $suratTugas = SuratTugasPengisian::first(); 

        if ($suratTugas) {
            SuratPermohonanPengisian::create([
                'surat_tugas_id' => $suratTugas->id,
                'nomor_surat'    => '001/PH.12.00',
                'tanggal_surat'  => '2026-04-04',
                'klasifikasi'    => 'Biasa',
                'lampiran'       => '1 (satu) berkas',
            ]);
        }
    }
}