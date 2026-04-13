<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Roles berdasarkan slug
        $roleIds = DB::table('roles')->pluck('id', 'slug')->toArray();

        $defaultPassword = Hash::make('password1234');

        // =========================================================================
        // AKUN PENGECUALIAN (Tidak di-looping per UKPD)
        // =========================================================================

        // 1. Superadmin (Tanpa UKPD/null)
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'              => 'Super Administrator',
                'email_verified_at' => now(),
                'password'          => $defaultPassword,
                'role_id'           => $roleIds['superadmin'] ?? null,
                'ukpd_id'           => null, 
            ]
        );

        // 2. Nahkoda sudah di-handle di KapalSeeder

        // 3. Penyedia BBM (Daftar Perusahaan)
        $daftarPenyedia = [
            'PT Pertamina Patra Niaga',
            'PT AKR Corporindo',
            'PT Elnusa Petrofin',
            'PT Sumber Migas Jakarta'
        ];

        foreach ($daftarPenyedia as $perusahaan) {
            $emailPenyedia = Str::slug($perusahaan, '_') . '@gmail.com';

            User::updateOrCreate(
                ['email' => $emailPenyedia],
                [
                    'name'              => $perusahaan,
                    'email_verified_at' => now(),
                    'password'          => $defaultPassword,
                    'role_id'           => $roleIds['penyedia'] ?? null,
                    'ukpd_id'           => null, 
                ]
            );
        }

        // =========================================================================
        // AKUN PER UKPD (Di-looping otomatis)
        // =========================================================================

        // Pemetaan data spesifik UKPD (ID, Nama Kepala, NIP, Email Kantor)
        $ukpdData = [
            'bidpelpen' => [
                'id'           => DB::table('ukpds')->where('singkatan', 'Bidpelpen')->value('id'),
                'kepala_nama'  => 'Fatchuri',
                'kepala_nip'   => '197604301997031003',
                'email_kantor' => 'dishub@jakarta.go.id',
            ],
            'upap' => [
                'id'           => DB::table('ukpds')->where('singkatan', 'UPAP')->value('id'),
                'kepala_nama'  => 'Muhamad Widan Anwar',
                'kepala_nip'   => '197509201998031004',
                'email_kantor' => 'upapkdishubdki@gmail.com',
            ],
            'uppd' => [
                'id'           => DB::table('ukpds')->where('singkatan', 'UPPD')->value('id'),
                'kepala_nama'  => 'Sutanto',
                'kepala_nip'   => '196904041999031008',
                'email_kantor' => 'uppd.dishub@jakarta.go.id',
            ],
            'sudinhub' => [
                'id'           => DB::table('ukpds')->where('singkatan', 'Sudinhub Kep. 1000')->value('id'),
                'kepala_nama'  => 'Leo Amstrong Manalu',
                'kepala_nip'   => null,
                'email_kantor' => null,
            ],
        ];

        $rolesPerUkpd = [
            'admin_ukpd', 
            'sounding', 
            'satgas', 
            'pengawas', 
            'pptk', 
            'kepala_ukpd'
        ];

        // Lakukan perulangan untuk setiap UKPD
        foreach ($ukpdData as $ukpdKey => $data) {
            
            if (!$data['id']) continue; 

            // Lakukan perulangan untuk setiap role di dalam UKPD tersebut
            foreach ($rolesPerUkpd as $roleSlug) {
                
                // Format default
                $email = "{$roleSlug}_{$ukpdKey}@gmail.com"; 
                $name  = ucwords(str_replace('_', ' ', $roleSlug)) . ' ' . strtoupper($ukpdKey); 
                $nip   = null;

                // [Kustomisasi] Gunakan Email Kantor resmi untuk Admin UKPD jika tersedia
                if ($roleSlug === 'admin_ukpd' && !empty($data['email_kantor'])) {
                    $email = $data['email_kantor'];
                }

                // [Kustomisasi] Gunakan Nama Asli dan NIP untuk Kepala UKPD
                if ($roleSlug === 'kepala_ukpd') {
                    $name = $data['kepala_nama'];
                    $nip  = $data['kepala_nip'];
                }

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'              => $name,
                        'email_verified_at' => now(),
                        'password'          => $defaultPassword,
                        'role_id'           => $roleIds[$roleSlug] ?? null,
                        'ukpd_id'           => $data['id'],
                        'nip'               => $nip,
                    ]
                );
            }
        }
    }
}