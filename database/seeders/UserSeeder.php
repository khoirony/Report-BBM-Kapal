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
        // 1. Ambil ID UKPD dan masukkan ke dalam array
        $ukpds = [
            'bpp'      => DB::table('ukpds')->where('singkatan', 'BPP')->value('id'),
            'upap'     => DB::table('ukpds')->where('singkatan', 'UPAP')->value('id'),
            'uppd'     => DB::table('ukpds')->where('singkatan', 'UPPD')->value('id'),
            'sudinhub' => DB::table('ukpds')->where('singkatan', 'Sudinhub Kep. 1000')->value('id'),
        ];

        // 2. Ambil ID Roles berdasarkan slug (agar query lebih efisien)
        // Formatnya akan menjadi array: ['superadmin' => 1, 'admin_ukpd' => 2, ...]
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
                'role_id'           => $roleIds['superadmin'] ?? null, // GANTI: role menjadi role_id
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
                    'role_id'           => $roleIds['penyedia'] ?? null, // GANTI: role menjadi role_id
                    'ukpd_id'           => null, 
                ]
            );
        }

        // =========================================================================
        // AKUN PER UKPD (Di-looping otomatis)
        // =========================================================================

        // Daftar role (pastikan string ini sama dengan kolom "slug" di tabel roles)
        $rolesPerUkpd = [
            'admin_ukpd', 
            'sounding', 
            'satgas', 
            'pengawas', 
            'pptk', 
            'kepala_ukpd'
        ];

        // Lakukan perulangan untuk setiap UKPD
        foreach ($ukpds as $ukpdName => $ukpdId) {
            
            if (!$ukpdId) continue; 

            // Lakukan perulangan untuk setiap role di dalam UKPD tersebut
            foreach ($rolesPerUkpd as $roleSlug) {
                
                $email = "{$roleSlug}_{$ukpdName}@gmail.com"; 
                $name  = ucwords(str_replace('_', ' ', $roleSlug)) . ' ' . strtoupper($ukpdName); 

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'              => $name,
                        'email_verified_at' => now(),
                        'password'          => $defaultPassword,
                        'role_id'           => $roleIds[$roleSlug] ?? null, // GANTI: role menjadi role_id
                        'ukpd_id'           => $ukpdId,
                    ]
                );
            }
        }
    }
}