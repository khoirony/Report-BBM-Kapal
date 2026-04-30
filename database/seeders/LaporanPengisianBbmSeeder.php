<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPermohonanPengisian;
use Illuminate\Support\Facades\DB;

class LaporanPengisianBbmSeeder extends Seeder
{
    public function run(): void
    {
        $permohonans = SuratPermohonanPengisian::with('suratTugas')
                            ->where('progress', 'done')
                            ->orWhere('progress', 'on progress')
                            ->get();

        if ($permohonans->isEmpty()) {
            $this->command->info('Tidak ada data Surat Permohonan. Buat Seeder Permohonan terlebih dahulu.');
            return;
        }

        $dataLaporan = [];

        foreach ($permohonans as $index => $permohonan) {
            
            if (!$permohonan->suratTugas) {
                continue;
            }

            // Simulasi kalkulasi BBM
            $bbmAwal = 500.00; // Asumsi angka dummy dari sounding awal
            $pengisian = $permohonan->jumlah_bbm ?? 1000.00;
            
            // Simulasi: Jika diambil di tempat (SPBU) ada pemakaian, jika dikirim tidak ada
            $pemakaian = ($permohonan->metode_pengiriman === 'Ambil ditempat') ? 50.00 : 0;
            
            $bbmAkhir = ($bbmAwal + $pengisian) - $pemakaian;

            $dataLaporan[] = [
                'ukpd_id'              => $permohonan->ukpd_id,
                'surat_tugas_id'       => $permohonan->suratTugas->id, 
                'surat_permohonan_id'  => $permohonan->id,
                'tanggal'              => \Carbon\Carbon::parse($permohonan->tanggal_surat)->addDays(1)->format('Y-m-d'),
                'dasar_hukum'          => '1. Undang-Undang Nomor 17 Tahun 2008 tentang Pelayaran; '.PHP_EOL.'2. DPA Dinas Perhubungan Provinsi DKI Jakarta Tahun 2026;',
                'lokasi_pengisian'     => $permohonan->tempat_pengambilan_bbm ?? 'SPBU Terdekat',
                'kegiatan'             => 'Pengisian BBM KDO Khusus',
                'tujuan'               => 'Memastikan ketersediaan BBM Kapal untuk menunjang kegiatan Operasional',
                'sounding_awal_id'     => null, // Diisi ID riil jika sudah ada data sounding
                'jumlah_bbm_awal'      => $bbmAwal,
                'jumlah_bbm_pengisian' => $pengisian,
                'pemakaian_bbm'        => $pemakaian,
                'sounding_akhir_id'    => null, // Diisi ID riil jika sudah ada data sounding
                'jumlah_bbm_akhir'     => $bbmAkhir,
                'jam_berangkat'        => '08:00:00',
                'jam_kembali'          => '13:30:00',
                'user_id'              => 3,
                'created_at'           => now(),
                'updated_at'           => now(),
            ];
        }

        DB::table('laporan_pengisian_bbms')->insert($dataLaporan);

        $this->command->info('Data Laporan Hasil Pengisian BBM berhasil di-seed!');
    }
}