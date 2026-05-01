<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPermohonanPengisian;
use App\Models\LaporanSisaBbm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SuratPermohonanPengisianSeeder extends Seeder
{
    public function run(): void
    {
        $laporanList = LaporanSisaBbm::all(); 

        if ($laporanList->isEmpty()) {
            $this->command->info('Tidak ada data Laporan Sisa BBM. Seeder dibatalkan.');
            return;
        }

        // Ambil data User yang memiliki role penyedia
        $rolePenyediaId = DB::table('roles')->where('slug', 'penyedia')->value('id');
        $penyediaList = User::where('role_id', $rolePenyediaId)->get();

        if ($penyediaList->isEmpty()) {
            $this->command->info('Tidak ada data User Penyedia. Seeder dibatalkan.');
            return;
        }

        $adminUser = User::whereHas('role', function($q) {
            $q->whereIn('slug', ['superadmin', 'admin_ukpd', 'satgas']);
        })->first();
        $defaultUserId = $adminUser ? $adminUser->id : 1;
        
        $faker = Faker::create('id_ID');

        $dataPermohonan = [
            [
                'nomor_surat'              => '001/PH.12.00',
                'tanggal_surat'            => '2026-04-01',
                'tanggal_pelaksanaan'      => '2026-04-02',
                'waktu_pelaksanaan'        => '08:00 - Selesai',
                'klasifikasi'              => 'Biasa',
                'lampiran'                 => '1 (satu) berkas',
                'jenis_penyedia_bbm'       => 'Stasiun Pengisian Bahan Bakar Umum (SPBU)',
                'tempat_pengambilan_bbm'   => 'SPBU 31.102.02 Muara Angke',
                'lokasi_pengisian'         => 'Dermaga Kali Adem',
                'metode_pengiriman'        => 'Ambil ditempat',
                'jenis_bbm'                => 'Dexlite/sekelas',
                'jumlah_bbm'               => 1500.00,
                'user_id'                  => $defaultUserId,
                'progress'                 => 'on progress',
            ],
            [
                'nomor_surat'              => '002/PH.12.00',
                'tanggal_surat'            => '2026-04-02',
                'tanggal_pelaksanaan'      => '2026-04-03',
                'waktu_pelaksanaan'        => '09:00 - Selesai',
                'klasifikasi'              => 'Penting',
                'lampiran'                 => '1 (satu) berkas',
                'jenis_penyedia_bbm'       => 'Agen BBM',
                'tempat_pengambilan_bbm'   => 'Depot Pertamina Plumpang',
                'lokasi_pengisian'         => 'Pelabuhan Sunda Kelapa',
                'metode_pengiriman'        => 'Pengiriman Jalur Darat',
                'jenis_bbm'                => 'Pertamina Dex/sekelas',
                'jumlah_bbm'               => 2500.50,
                'user_id'                  => $defaultUserId,
                'progress'                 => 'on progress',
            ],
            [
                'nomor_surat'              => '003/PH.12.00',
                'tanggal_surat'            => '2026-04-03',
                'tanggal_pelaksanaan'      => '2026-04-04',
                'waktu_pelaksanaan'        => '10:00 - Selesai',
                'klasifikasi'              => 'Segera',
                'lampiran'                 => '2 (dua) berkas',
                'jenis_penyedia_bbm'       => 'Agen BBM',
                'tempat_pengambilan_bbm'   => 'Pelabuhan Tanjung Priok',
                'lokasi_pengisian'         => 'Dermaga Marina Ancol',
                'metode_pengiriman'        => 'Pengiriman Jalur Laut',
                'jenis_bbm'                => 'Biosolar',
                'jumlah_bbm'               => 5000.00,
                'user_id'                  => $defaultUserId,
                'progress'                 => 'on progress',
            ],
            [
                'nomor_surat'              => '004/PH.12.00',
                'tanggal_surat'            => '2026-04-04',
                'tanggal_pelaksanaan'      => '2026-04-05',
                'waktu_pelaksanaan'        => '08:00 - 12:00',
                'klasifikasi'              => 'Biasa',
                'lampiran'                 => '1 (satu) berkas',
                'jenis_penyedia_bbm'       => 'Stasiun Pengisian Bahan Bakar Umum (SPBU)',
                'tempat_pengambilan_bbm'   => 'SPBU 34.144.15 Pluit',
                'lokasi_pengisian'         => 'Dermaga Kali Adem',
                'metode_pengiriman'        => 'Ambil ditempat',
                'jenis_bbm'                => 'Pertamax/sekelas',
                'jumlah_bbm'               => 200.00,
                'user_id'                  => $defaultUserId,
                'progress'                 => 'on progress',
            ],
            [
                'nomor_surat'              => '005/PH.12.00',
                'tanggal_surat'            => '2026-04-05',
                'tanggal_pelaksanaan'      => '2026-04-06',
                'waktu_pelaksanaan'        => '13:00 - Selesai',
                'klasifikasi'              => 'Penting',
                'lampiran'                 => '1 (satu) berkas',
                'jenis_penyedia_bbm'       => 'Lainnya',
                'tempat_pengambilan_bbm'   => 'Terminal BBM Jakarta Group',
                'lokasi_pengisian'         => 'Dermaga Timur',
                'metode_pengiriman'        => 'Pengiriman Jalur Darat',
                'jenis_bbm'                => 'Dexlite/sekelas',
                'jumlah_bbm'               => 1000.00,
                'user_id'                  => $defaultUserId,
                'progress'                 => 'on progress',
            ],
            [
                'nomor_surat'              => '006/PH.12.00',
                'tanggal_surat'            => '2026-04-06',
                'tanggal_pelaksanaan'      => '2026-04-07',
                'waktu_pelaksanaan'        => '08:30 - Selesai',
                'klasifikasi'              => 'Biasa',
                'lampiran'                 => '1 (satu) berkas',
                'jenis_penyedia_bbm'       => 'Agen BBM',
                'tempat_pengambilan_bbm'   => 'Dermaga Marina Ancol',
                'lokasi_pengisian'         => 'Dermaga Marina Ancol',
                'metode_pengiriman'        => 'Pengiriman Jalur Laut',
                'jenis_bbm'                => 'Pertamina Dex/sekelas',
                'jumlah_bbm'               => 3000.00,
                'user_id'                  => $defaultUserId,
                'progress'                 => 'on progress',
            ]
        ];

        foreach ($dataPermohonan as $item) {
            $randomLaporan = $laporanList->random();
            $randomPenyedia = $penyediaList->random();
            
            $item['laporan_sisa_bbm_id'] = $randomLaporan->id;
            $item['ukpd_id']             = $randomLaporan->ukpd_id;
            $item['penyedia_id']         = $randomPenyedia->id; 
            
            $permohonan = SuratPermohonanPengisian::create($item);

            // Buat Data Petugas yang menempel ke Surat Permohonan
            $dataPetugas = [
                [
                    'surat_permohonan_id' => $permohonan->id,
                    'surat_tugas_pengisian_id' => null, // Belum ada surat tugas
                    'nama_petugas' => $faker->name('male'),
                    'jabatan' => 'Nakhoda',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'surat_permohonan_id' => $permohonan->id,
                    'surat_tugas_pengisian_id' => null, // Belum ada surat tugas
                    'nama_petugas' => $faker->name('male'),
                    'jabatan' => 'KKM',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];
            
            DB::table('petugas_surat_tugas')->insert($dataPetugas);
        }

        $this->command->info('Data Surat Permohonan Pengisian (beserta Petugas) berhasil di-seed!');
    }
}