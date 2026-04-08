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
                'nama' => 'Bidang Pelayaran dan Penerbangan',
                'singkatan' => 'Bidpelpen',
            ],
            [
                'nama' => 'Unit Pengelola Angkutan Perairan',
                'singkatan' => 'UPAP',
            ],
            [
                'nama' => 'Unit Penyelenggara Pelabuhan Daerah',
                'singkatan' => 'UPPD',
            ],
            [
                'nama' => 'Suku Dinas Perhubungan Kabupaten Administrasi Kepulauan Seribu',
                'singkatan' => 'Sudinhub Kep. 1000',
            ],
        ];

        foreach ($data as $item) {
            DB::table('ukpds')->insert([
                'nama' => $item['nama'],
                'singkatan' => $item['singkatan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
