<?php

namespace Database\Seeders;

use App\Models\Spj;
use App\Models\Kapal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpjSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data user satgas dan admin ukpd untuk dijadikan pembuat SPJ
        $satgas = User::whereHas('role', function($q) {
            $q->where('slug', 'satgas');
        })->first();

        // Ambil kapal pertama yang ada di database
        $kapal = Kapal::first();

        if ($satgas && $kapal) {
            $spjs = [
                [
                    'nomor_spj'   => 'SPJ/001/BBM/2026',
                    'kapal_id'    => $kapal->id,
                    'tanggal_spj' => Carbon::now()->subDays(5)->format('Y-m-d'),
                    'file_spj'    => 'uploads/spj/dummy_spj_1.pdf',
                    'status'      => 'menunggu_pptk',
                    'created_by'  => $satgas->id,
                    'ukpd_id'     => $satgas->ukpd_id,
                ],
                [
                    'nomor_spj'   => 'SPJ/002/BBM/2026',
                    'kapal_id'    => $kapal->id,
                    'tanggal_spj' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'file_spj'    => 'uploads/spj/dummy_spj_2.pdf',
                    'status'      => 'menunggu_kepala_ukpd', // Sudah di-acc PPTK
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