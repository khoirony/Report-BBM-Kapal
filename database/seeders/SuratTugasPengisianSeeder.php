<?php

namespace Database\Seeders;

use App\Models\SuratTugasPengisian;
use App\Models\SuratPermohonanPengisian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class SuratTugasPengisianSeeder extends Seeder
{
    public function run(): void
    {
        $permohonans = SuratPermohonanPengisian::orderBy('tanggal_surat', 'asc')->get();

        if ($permohonans->isEmpty()) {
            $this->command->info('Tidak ada data Surat Permohonan. Buat Surat Permohonan terlebih dahulu sebelum menjalankan seeder ini.');
            return;
        }

        $faker = Faker::create('id_ID');
        $nomorUrut = 1;

        $listPakaian = ['PDH', 'PDL', 'Baju Dinas Lapangan', 'Bebas Rapi'];

        foreach ($permohonans as $permohonan) {
            
            $tanggalPelaksanaan = Carbon::parse($permohonan->tanggal_pelaksanaan ?? $permohonan->tanggal_surat);
            $tanggalDikeluarkan = $tanggalPelaksanaan->copy()->subDay();
            
            if ($tanggalDikeluarkan->dayOfWeek === Carbon::SUNDAY) {
                $tanggalDikeluarkan->subDays(2);
            }

            $nomorSurat = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT) . '/PH.12.00/' . $tanggalDikeluarkan->format('Y');

            $suratTugas = SuratTugasPengisian::create([
                'surat_permohonan_id' => $permohonan->id,
                'ukpd_id'             => $permohonan->ukpd_id,
                'nomor_surat'         => $nomorSurat,
                'lokasi'              => $permohonan->lokasi_pengisian ?? '-',
                'tanggal_pelaksanaan' => $permohonan->tanggal_pelaksanaan,
                'waktu_pelaksanaan'   => $permohonan->waktu_pelaksanaan,
                'pakaian'             => $faker->randomElement($listPakaian), 
                'tanggal_surat'       => $tanggalDikeluarkan->format('Y-m-d'),
                'user_id'             => 3,
            ]);

            DB::table('petugas_surat_tugas')
                ->where('surat_permohonan_id', $permohonan->id)
                ->update([
                    'surat_tugas_pengisian_id' => $suratTugas->id,
                    'updated_at' => now(),
                ]);

            $nomorUrut++;
        }
        
        $this->command->info('Data Surat Tugas Pengisian (dan sinkronisasi Petugas) berhasil di-seed!');
    }
}