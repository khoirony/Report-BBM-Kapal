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
        // Menggunakan tanggal_sounding untuk sorting agar konsisten dengan perubahan sebelumnya
        $soundings = Sounding::with('kapal')->orderBy('tanggal_sounding', 'asc')->get();
        
        $groupedSoundings = $soundings->groupBy(function($item) {
            // Grouping berdasarkan tanggal_sounding
            return $item->kapal_id . '_' . Carbon::parse($item->tanggal_sounding)->format('Y-m-d');
        });

        $nomorUrutSisaBbm = 1;

        foreach ($groupedSoundings as $groupKey => $soundingsInGroup) {
            $firstSounding = $soundingsInGroup->first();
            $lastSounding = $soundingsInGroup->last(); 
            $tanggal = Carbon::parse($firstSounding->tanggal_sounding);
            
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
                
                // Data Nama dan ID Nakhoda
                'nama_nakhoda'  => 'Nakhoda Kapal ' . $lastSounding->kapal_id,
                'id_nakhoda'    => '19800101201001100' . $lastSounding->kapal_id, // Contoh NIP/NRK dinamis
                
                // Data Nama dan ID Pengawas
                'nama_pengawas' => 'Pengawas Lapangan',
                'id_pengawas'   => '19900202201502100' . $ukpdId, // Contoh NIP/NRK dinamis
                
                'user_id'       => 1,
                'created_at'    => now(), // Sebaiknya created_at diset now() untuk penanda waktu record dibuat di DB
                'updated_at'    => now(),
            ]);

            $nomorUrutSisaBbm++;
        }
    }
}