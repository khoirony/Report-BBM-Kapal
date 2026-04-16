<?php

namespace Database\Seeders;

use App\Models\Spj;
use App\Models\Kapal;
use App\Models\User;
use App\Models\ProsesPenyediaBbm;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SpjSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data user satgas dan admin ukpd untuk dijadikan pembuat SPJ
        $satgas = User::whereHas('role', function($q) {
            $q->where('slug', 'satgas');
        })->first();

        // Ambil data user PPTK untuk simulasi persetujuan di SPJ kedua
        $pptk = User::whereHas('role', function($q) {
            $q->where('slug', 'pptk');
        })->first();

        // Fallback kapal jika data penyedia kosong / relasi terputus
        $fallbackKapal = Kapal::first();

        // Ambil 2 data Proses Penyedia BBM terbaru beserta rantai relasinya ke kapal
        $prosesPenyedia1 = ProsesPenyediaBbm::with('suratPermohonan.suratTugas.LaporanSisaBbm.sounding')->first();
        $prosesPenyedia2 = ProsesPenyediaBbm::with('suratPermohonan.suratTugas.LaporanSisaBbm.sounding')->skip(1)->first();

        // Ekstrak ID Kapal dari relasi Penyedia BBM (Gunakan fallback jika null)
        $kapal_id_1 = $prosesPenyedia1?->suratPermohonan?->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id ?? $fallbackKapal?->id;
        $kapal_id_2 = $prosesPenyedia2?->suratPermohonan?->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id ?? $fallbackKapal?->id;

        if ($satgas && ($kapal_id_1 || $kapal_id_2)) {
            $spjs = [
                [
                    'nomor_spj'                => 'SPJ/001/BBM/2026',
                    'kapal_id'                 => $kapal_id_1, // Ditarik dari Proses Penyedia 1
                    'proses_penyedia_bbm_id'   => $prosesPenyedia1 ? $prosesPenyedia1->id : null,
                    'tanggal_spj'              => Carbon::now()->subDays(5)->format('Y-m-d'),
                    'total_biaya'              => $prosesPenyedia1 ? $prosesPenyedia1->total_harga : 7500000,
                    'file_spj'                 => 'uploads/spj/dummy_spj_1.pdf',
                    'disetujui_pptk_by'        => null,
                    'disetujui_pptk_at'        => null,
                    'disetujui_kepala_ukpd_by' => null,
                    'disetujui_kepala_ukpd_at' => null,
                    'created_by'               => $satgas->id,
                    'ukpd_id'                  => $satgas->ukpd_id,
                ],
                [
                    'nomor_spj'                => 'SPJ/002/BBM/2026',
                    'kapal_id'                 => $kapal_id_2, // Ditarik dari Proses Penyedia 2
                    'proses_penyedia_bbm_id'   => $prosesPenyedia2 ? $prosesPenyedia2->id : null,
                    'tanggal_spj'              => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'total_biaya'              => $prosesPenyedia2 ? $prosesPenyedia2->total_harga : 12450000,
                    'file_spj'                 => 'uploads/spj/dummy_spj_2.pdf',
                    'disetujui_pptk_by'        => $pptk ? $pptk->id : $satgas->id,
                    'disetujui_pptk_at'        => Carbon::now()->subDay(),
                    'disetujui_kepala_ukpd_by' => null,
                    'disetujui_kepala_ukpd_at' => null,
                    'created_by'               => $satgas->id,
                    'ukpd_id'                  => $satgas->ukpd_id,
                ]
            ];

            foreach ($spjs as $spjData) {
                // Buat data SPJ
                $spj = Spj::create($spjData);

                // Update Surat Permohonan ke status 'done' jika ada penyedia yang ditautkan
                if ($spj->proses_penyedia_bbm_id) {
                    $proses = ProsesPenyediaBbm::with('suratPermohonan')->find($spj->proses_penyedia_bbm_id);
                    
                    if ($proses && $proses->suratPermohonan) {
                        $proses->suratPermohonan->update([
                            'progress' => 'done'
                        ]);
                    }
                }
            }
        }
    }
}