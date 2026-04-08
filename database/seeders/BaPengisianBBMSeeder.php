<?php

namespace Database\Seeders;

use App\Models\BaPengisianBbm;
use App\Models\Sounding;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BaPengisianBBMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kelompokkan semua sounding berdasarkan Kapal_ID dan Tanggal (Y-m-d)
        $groupedSoundings = Sounding::all()->groupBy(function($item) {
            return $item->kapal_id . '_' . Carbon::parse($item->created_at)->format('Y-m-d');
        });

        foreach ($groupedSoundings as $groupKey => $soundings) {
            $firstSounding = $soundings->first();
            $tanggal = Carbon::parse($firstSounding->created_at);

            // Buat 1 Laporan untuk 1 Kapal di Hari yang sama
            $laporan = BaPengisianBbm::factory()->create([
                'kapal_id' => $firstSounding->kapal_id,
                'tanggal' => $tanggal->format('Y-m-d'),
                'user_id' => 3,
            ]);
        }
    }
}
