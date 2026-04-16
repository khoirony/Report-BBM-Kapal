<?php

namespace Database\Seeders;

use App\Models\SuratTugasPengisian;
use App\Models\LaporanSisaBbm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class SuratTugasPengisianSeeder extends Seeder
{
    public function run(): void
    {
        $laporans = LaporanSisaBbm::orderBy('tanggal_surat', 'asc')->get();

        if ($laporans->isEmpty()) {
            $this->command->info('Tidak ada data Laporan BBM. Buat Laporan BBM terlebih dahulu sebelum menjalankan seeder ini.');
            return;
        }

        $faker = Faker::create('id_ID');
        $nomorUrut = 1;

        $listLokasi = [
            'SPBU Pertamina 31.102.02 MT Haryono',
            'SPBU Pertamina 34.105.04 Cempaka Putih',
            'SPBU Shell Gatot Subroto',
            'Pool Kendaraan Dinas Operasional'
        ];

        foreach ($laporans as $laporan) {
            $tanggalPelaksanaan = Carbon::parse($laporan->tanggal_surat);
            
            $tanggalDikeluarkan = $tanggalPelaksanaan->copy()->subDay();
            
            // Jika H-1 jatuh di hari Minggu (0), mundurkan ke hari Jumat
            if ($tanggalDikeluarkan->dayOfWeek === Carbon::SUNDAY) {
                $tanggalDikeluarkan->subDays(2);
            }

            // Format nomor surat menjadi full (Contoh: 001/PH.12.00/2026)
            $nomorSurat = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT) . '/PH.12.00/' . $tanggalDikeluarkan->format('Y');

            // 1. Simpan data Surat Tugas ke dalam variabel $suratTugas
            $suratTugas = SuratTugasPengisian::create([
                'laporan_sisa_bbm_id' => $laporan->id,
                'ukpd_id'             => $laporan->ukpd_id,
                'nomor_surat'         => $nomorSurat,
                'lokasi'              => $faker->randomElement($listLokasi),
                'tanggal_pelaksanaan' => $tanggalPelaksanaan->format('Y-m-d'),
                'waktu_pelaksanaan'   => '08:00 - Selesai',
                'tanggal_surat' => $tanggalDikeluarkan->format('Y-m-d'),
                'user_id'             => 3,
            ]);

            // 2. Siapkan data petugas untuk surat tugas ini (Misal 2 petugas per surat)
            $dataPetugas = [
                [
                    'surat_tugas_pengisian_id' => $suratTugas->id,
                    'nama_petugas'             => $faker->name('male'),
                    'jabatan'                  => 'Supir',
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ],
                [
                    'surat_tugas_pengisian_id' => $suratTugas->id,
                    'nama_petugas'             => $faker->name(),
                    'jabatan'                  => 'Pendamping',
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]
            ];

            // 3. Insert data ke tabel relasi petugas_surat_tugas
            DB::table('petugas_surat_tugas')->insert($dataPetugas);

            $nomorUrut++;
        }
    }
}