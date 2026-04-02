<?php

namespace Database\Seeders;

use App\Models\Sounding;
use App\Models\Kapal;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SoundingSeeder extends Seeder
{
    public function run(): void
    {
        // Mengambil maksimal 3 data kapal
        $kapals = Kapal::take(3)->get();

        if ($kapals->isEmpty()) {
            $this->command->info('Tidak ada data kapal. Buat data kapal terlebih dahulu.');
            return;
        }

        // Variasi data dasar pengisian dan pemakaian
        $skenarioBBM = [
            // Skenario Kapal 1
            [
                ['isi' => 1000, 'pakai' => 200],
                ['isi' => 0, 'pakai' => 150],
                ['isi' => 200, 'pakai' => 300],
            ],
            // Skenario Kapal 2
            [
                ['isi' => 800, 'pakai' => 150],
                ['isi' => 0, 'pakai' => 200],
                ['isi' => 0, 'pakai' => 100],
            ],
            // Skenario Kapal 3
            [
                ['isi' => 500, 'pakai' => 400],
                ['isi' => 0, 'pakai' => 300],
                ['isi' => 500, 'pakai' => 200],
            ]
        ];

        // Setup hari pencatatan (Kemarin dan Hari Ini)
        $hariPencatatan = [
            Carbon::now()->subDays(1), // Kemarin
            Carbon::now(),             // Hari Ini
        ];

        foreach ($kapals as $index => $kapal) {
            $skenario = $skenarioBBM[$index % 3]; 
            
            // Modal BBM Awal untuk hari pertama
            $modalBbmAwal = [500, 200, 1000][$index % 3]; 

            foreach ($hariPencatatan as $tanggal) {
                // 1. Titik Awal (Pom Bensin)
                $awal1 = $modalBbmAwal;
                $isi1 = $skenario[0]['isi'];
                $pakai1 = $skenario[0]['pakai'];
                $akhir1 = ($awal1 + $isi1) - $pakai1;

                Sounding::create([
                    'kapal_id' => $kapal->id,
                    'lokasi' => 'Pom Bensin (Titik Awal)',
                    'bbm_awal' => $awal1,
                    'pengisian' => $isi1,
                    'pemakaian' => $pakai1,
                    'bbm_akhir' => $akhir1, 
                    'jam_berangkat' => Carbon::createFromTime(8, 0, 0),
                    'jam_kembali' => Carbon::createFromTime(11, 30, 0),
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                // 2. Titik A
                $awal2 = $akhir1;
                $isi2 = $skenario[1]['isi'];
                $pakai2 = $skenario[1]['pakai'];
                $akhir2 = ($awal2 + $isi2) - $pakai2;

                Sounding::create([
                    'kapal_id' => $kapal->id,
                    'lokasi' => 'Titik A',
                    'bbm_awal' => $awal2,
                    'pengisian' => $isi2,
                    'pemakaian' => $pakai2,
                    'bbm_akhir' => $akhir2,
                    'jam_berangkat' => Carbon::createFromTime(12, 30, 0),
                    'jam_kembali' => Carbon::createFromTime(15, 0, 0),
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                // 3. Titik B
                $awal3 = $akhir2;
                $isi3 = $skenario[2]['isi'];
                $pakai3 = $skenario[2]['pakai'];
                $akhir3 = ($awal3 + $isi3) - $pakai3;

                Sounding::create([
                    'kapal_id' => $kapal->id,
                    'lokasi' => 'Titik B',
                    'bbm_awal' => $awal3,
                    'pengisian' => $isi3,
                    'pemakaian' => $pakai3,
                    'bbm_akhir' => $akhir3,
                    'jam_berangkat' => Carbon::createFromTime(15, 30, 0),
                    'jam_kembali' => Carbon::createFromTime(18, 0, 0),
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                // Update modal BBM Awal untuk hari esoknya menggunakan BBM Akhir dari Titik B hari ini
                $modalBbmAwal = $akhir3;
            }
        }
    }
}