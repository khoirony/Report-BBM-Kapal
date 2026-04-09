<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPermohonanPengisian;
use App\Models\SuratTugasPengisian;

class SuratPermohonanPengisianSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua data surat tugas agar bisa mengakses ukpd_id-nya
        $suratTugasList = SuratTugasPengisian::all(); 

        // Jika tabel surat tugas kosong, hentikan seeder agar tidak error
        if ($suratTugasList->isEmpty()) {
            $this->command->info('Tidak ada data Surat Tugas. Seeder dibatalkan.');
            return;
        }

        // Siapkan 6 data dummy
        $dataPermohonan = [
            [
                'nomor_surat'   => '001/PH.12.00',
                'tanggal_surat' => '2026-04-01',
                'klasifikasi'   => 'Biasa',
                'lampiran'      => '1 (satu) berkas',
                'user_id'       => 3,
                'progress'      => 'done',
            ],
            [
                'nomor_surat'   => '002/PH.12.00',
                'tanggal_surat' => '2026-04-02',
                'klasifikasi'   => 'Penting',
                'lampiran'      => '1 (satu) berkas',
                'user_id'       => 3,
                'progress'      => 'on progress',
            ],
            [
                'nomor_surat'   => '003/PH.12.00',
                'tanggal_surat' => '2026-04-03',
                'klasifikasi'   => 'Segera',
                'lampiran'      => '2 (dua) berkas',
                'user_id'       => 3,
                'progress'      => 'not started',
            ],
            [
                'nomor_surat'   => '004/PH.12.00',
                'tanggal_surat' => '2026-04-04',
                'klasifikasi'   => 'Biasa',
                'lampiran'      => '1 (satu) berkas',
                'user_id'       => 3,
                'progress'      => 'on progress',
            ],
            [
                'nomor_surat'   => '005/PH.12.00',
                'tanggal_surat' => '2026-04-05',
                'klasifikasi'   => 'Penting',
                'lampiran'      => '1 (satu) berkas',
                'user_id'       => 3,
                'progress'      => 'done',
            ],
            [
                'nomor_surat'   => '006/PH.12.00',
                'tanggal_surat' => '2026-04-06',
                'klasifikasi'   => 'Biasa',
                'lampiran'      => '1 (satu) berkas',
                'user_id'       => 3,
                'progress'      => 'not started',
            ]
        ];

        // Lakukan perulangan untuk menyimpan ke database
        foreach ($dataPermohonan as $item) {
            // Pilih satu object Surat Tugas secara acak dari collection
            $randomSuratTugas = $suratTugasList->random();
            
            // Masukkan id dan ukpd_id dari Surat Tugas yang terpilih
            $item['surat_tugas_id'] = $randomSuratTugas->id;
            $item['ukpd_id']        = $randomSuratTugas->ukpd_id;
            
            SuratPermohonanPengisian::create($item);
        }

        $this->command->info('Data Surat Permohonan Pengisian berhasil di-seed beserta UKPD ID!');
    }
}