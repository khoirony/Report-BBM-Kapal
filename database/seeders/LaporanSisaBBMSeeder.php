<?php

namespace Database\Seeders;

use App\Models\LaporanSebelumPengisian;
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
        // 1. Grouping Sounding berdasarkan Kapal dan Tanggal
        // Kita urutkan berdasarkan created_at agar sounding terakhir ada di urutan paling bawah
        $soundings = Sounding::orderBy('created_at', 'asc')->get();
        
        $groupedSoundings = $soundings->groupBy(function($item) {
            return $item->kapal_id . '_' . Carbon::parse($item->created_at)->format('Y-m-d');
        });

        $nomorUrutSisaBbm = 1;

        foreach ($groupedSoundings as $groupKey => $soundingsInGroup) {
            $firstSounding = $soundingsInGroup->first();
            $lastSounding = $soundingsInGroup->last(); 
            $tanggal = Carbon::parse($firstSounding->created_at);
            
            $nomorSurat = str_pad($nomorUrutSisaBbm, 3, '0', STR_PAD_LEFT) . '/PH.12.00/' . $tanggal->format('Y');

            LaporanSisaBbm::create([
                'nomor' => $nomorSurat,
                'sounding_id' => $lastSounding->id, // Menggunakan sounding terakhir di hari itu
                'tanggal_surat' => $tanggal->format('Y-m-d'),
                'klasifikasi' => 'Biasa',
                'lampiran' => '-',
                'perihal' => 'Laporan Perhitungan Jumlah Sisa BBM Kapal Sebelum Pengisian',
                'nama_nakhoda' => 'Nakhoda Kapal ' . $lastSounding->kapal_id, // Dummy nama nakhoda
                'nama_pengawas' => 'Pengawas UPAP', // Dummy nama pengawas
                'user_id' => 1,
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            $nomorUrutSisaBbm++;
        }
    }
}