<?php

namespace Database\Seeders;

use App\Models\BaPengisianBbm;
use App\Models\LaporanPengisianBbm;
use App\Models\User;
use Illuminate\Database\Seeder;

class BaPengisianBBMSeeder extends Seeder
{
    public function run(): void
    {
        $laporanBbm = LaporanPengisianBbm::first();
        $admin = User::whereHas('role', fn($q) => $q->where('slug', 'superadmin'))->first();

        if ($laporanBbm && $admin) {
            BaPengisianBbm::create([
                'laporan_pengisian_bbm_id' => $laporanBbm->id,
                'kapal_id' => $laporanBbm->suratTugas->LaporanSisaBbm->sounding->kapal_id,
                'nomor_pks' => 'PKS/2026/001',
                'tanggal_pks' => now()->subMonths(6),
                'tgl_ba' => now(),
                'dasar_hukum' => 'Peraturan Gubernur No. 123 Tahun 2024',
                'kegiatan' => 'Pengisian BBM Kapal Patroli',
                'tujuan' => 'Mendukung operasional wilayah perairan',
                'lokasi' => 'Pelabuhan Sunda Kelapa',
                'user_id' => $admin->id,
            ]);
        }
    }
}