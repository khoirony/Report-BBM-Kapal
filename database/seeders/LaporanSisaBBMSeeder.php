<?php

namespace Database\Seeders;

use App\Models\LaporanSisaBbm;
use App\Models\Sounding;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LaporanSisaBBMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soundings = Sounding::with('kapal')->orderBy('created_at', 'asc')->get();
        
        $groupedSoundings = $soundings->groupBy(function($item) {
            return $item->kapal_id . '_' . Carbon::parse($item->created_at)->format('Y-m-d');
        });

        $nomorUrutSisaBbm = 1;

        foreach ($groupedSoundings as $groupKey => $soundingsInGroup) {
            $firstSounding = $soundingsInGroup->first();
            $lastSounding = $soundingsInGroup->last(); 
            $tanggal = Carbon::parse($firstSounding->created_at);
            
            $nomorSurat = str_pad($nomorUrutSisaBbm, 3, '0', STR_PAD_LEFT) . '/PH.12.00/' . $tanggal->format('Y');

            $ukpdId = $lastSounding->kapal ? $lastSounding->kapal->ukpd_id : null;

            LaporanSisaBbm::create([
                'nomor'         => $nomorSurat,
                'ukpd_id'       => $ukpdId,
                'sounding_id'   => $lastSounding->id,
                'tanggal_surat' => $tanggal->format('Y-m-d'),
                'klasifikasi'   => 'Biasa',
                'lampiran'      => '-',
                'perihal'       => 'Laporan Perhitungan Jumlah Sisa BBM Kapal Sebelum Pengisian',
                'nama_nakhoda'  => 'Nakhoda Kapal ' . $lastSounding->kapal_id,
                'nama_pengawas' => 'Pengawas Lapangan',
                'user_id'       => 1,
                'created_at'    => $tanggal,
                'updated_at'    => $tanggal,
            ]);

            $nomorUrutSisaBbm++;
        }
    }
}