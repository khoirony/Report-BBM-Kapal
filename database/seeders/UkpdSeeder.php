<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UkpdSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama'      => 'Bidang Pelayaran dan Penerbangan',
                'singkatan' => 'Bidpelpen',
                'alamat'    => 'Gedung Graha Lestari, Jalan Kesehatan No.48, Kelurahan Petojo Selatan, Kecamatan Gambir, Kota Administrasi Jakarta Pusat',
                'email'     => 'dishub@jakarta.go.id',
                'kode_pos'  => '10160',
            ],
            [
                'nama'      => 'Unit Pengelola Angkutan Perairan',
                'singkatan' => 'UPAP',
                'alamat'    => 'Jalan Dermaga Ujung, Kecamatan Penjaringan, Pelabuhan Muara Angke, Kota Administrasi Jakarta Utara',
                'email'     => 'upapkdishubdki@gmail.com',
                'kode_pos'  => '14450',
            ],
            [
                'nama'      => 'Unit Penyelenggara Pelabuhan Daerah',
                'singkatan' => 'UPPD',
                'alamat'    => 'Jalan Dermaga Ujung, Kecamatan Penjaringan, Pelabuhan Muara Angke, Kota Administrasi Jakarta Utara',
                'email'     => 'uppd.dishub@jakarta.go.id',
                'kode_pos'  => '14450',
            ],
            [
                'nama'      => 'Suku Dinas Perhubungan Kabupaten Administrasi Kepulauan Seribu',
                'singkatan' => 'Sudinhub Kep. 1000',
                'alamat'    => null,
                'email'     => null,
                'kode_pos'  => null,
            ],
        ];

        foreach ($data as $item) {
            DB::table('ukpds')->insert([
                'nama'       => $item['nama'],
                'singkatan'  => $item['singkatan'],
                'alamat'     => $item['alamat'],
                'email'      => $item['email'],
                'kode_pos'   => $item['kode_pos'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
