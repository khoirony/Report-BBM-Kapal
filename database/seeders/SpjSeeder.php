<?php

namespace Database\Seeders;

use App\Models\Spj;
use App\Models\Kapal;
use App\Models\User;
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

        // Ambil kapal pertama yang ada di database
        $kapal = Kapal::first();

        if ($satgas && $kapal) {
            $spjs = [
                [
                    'nomor_spj'   => 'SPJ/001/BBM/2026',
                    'kapal_id'    => $kapal->id,
                    'tanggal_spj' => Carbon::now()->subDays(5)->format('Y-m-d'),
                    'total_biaya' => 7500000,
                    'file_spj'    => 'uploads/spj/dummy_spj_1.pdf',
                    'disetujui_pptk_by'        => null,
                    'disetujui_pptk_at'        => null,
                    'disetujui_kepala_ukpd_by' => null,
                    'disetujui_kepala_ukpd_at' => null,
                    'created_by'  => $satgas->id,
                    'ukpd_id'     => $satgas->ukpd_id,
                ],
                [
                    'nomor_spj'   => 'SPJ/002/BBM/2026',
                    'kapal_id'    => $kapal->id,
                    'tanggal_spj' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'total_biaya' => 12450000,
                    'file_spj'    => 'uploads/spj/dummy_spj_2.pdf',
                    'disetujui_pptk_by'        => $pptk ? $pptk->id : $satgas->id,
                    'disetujui_pptk_at'        => Carbon::now()->subDay(),
                    'disetujui_kepala_ukpd_by' => null,
                    'disetujui_kepala_ukpd_at' => null,
                    'created_by'  => $satgas->id,
                    'ukpd_id'     => $satgas->ukpd_id,
                ]
            ];

            foreach ($spjs as $spj) {
                Spj::create($spj);
            }
        }
    }
}