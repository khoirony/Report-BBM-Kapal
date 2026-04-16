<?php

namespace Database\Seeders;

use App\Models\BaPengisianBbm;
use App\Models\LaporanPengisianBbm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BaPengisianBBMSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('role', fn($q) => $q->where('slug', 'superadmin'))->first();

        $laporans = LaporanPengisianBbm::with('suratTugas.LaporanSisaBbm.sounding')->get();

        if ($admin && $laporans->isNotEmpty()) {
            foreach ($laporans as $index => $laporan) {
                
                $kapal_id = $laporan->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id;

                if ($kapal_id) {
                    $tanggal = Carbon::parse($laporan->tanggal ?? now())->locale('id');

                    BaPengisianBbm::create([
                        'laporan_pengisian_bbm_id' => $laporan->id,
                        'kapal_id' => $kapal_id,
                        'nomor_pks' => 'PKS/2026/' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'nomor_ba' => 'BA/2026/' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'tanggal_pks' => now()->subMonths(6),
                        'tgl_ba' => now(),
                        'tgl_pelaksanaan' => now()->addDay(),
                        'dasar_hukum' => 'Peraturan Gubernur No. 123 Tahun 2024',
                        'kegiatan' => 'Pengisian BBM Kapal Patroli',
                        'tujuan' => 'Mendukung operasional wilayah perairan',
                        'lokasi' => 'Pelabuhan Sunda Kelapa',
                        'user_id' => $admin->id,
                    ]);
                }
            }
        }
    }
}