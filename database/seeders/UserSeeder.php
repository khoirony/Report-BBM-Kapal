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
                'username'          => 'superadmin', // Penambahan Username
                'email_verified_at' => now(),
                'password'          => $defaultPassword,
                'role_id'           => $roleIds['superadmin'] ?? null,
                'ukpd_id'           => null, 
            ]
        );

        // 2. Nakhoda sudah di-handle di KapalSeeder

        // 3. Penyedia BBM (Daftar Perusahaan)
        $daftarPenyedia = [
            'PT Pertamina Patra Niaga',
            'PT AKR Corporindo',
            'PT Elnusa Petrofin',
            'PT Sumber Migas Jakarta'
        ];

        foreach ($daftarPenyedia as $perusahaan) {
            $username = Str::slug($perusahaan, '_');
            $emailPenyedia = $username . '@gmail.com';

            User::updateOrCreate(
                ['email' => $emailPenyedia],
                [
                    'name'              => $perusahaan,
                    'username'          => $username, // Penambahan Username
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

        // Pemetaan data spesifik UKPD (ID, Nama Kepala, PPTK, Pengawas, NIP, Email Kantor)
        $ukpdData = [
            'bidpelpen' => [
                'id'            => DB::table('ukpds')->where('singkatan', 'Bidpelpen')->value('id'),
                'kepala_nama'   => 'Dr. Raden Fatchuri Hidayat, M.Si.',
                'kepala_nip'    => '197604301997031003',
                'pptk_nama'     => 'Ir. Ahmad Maulana Saputra, M.T.',
                'pptk_nip'      => '198005202005011001',
                'pengawas_nama' => 'Andi Kurniawan Wicaksono, S.T.',
                'pengawas_nip'  => '198508172010121002',
                'email_kantor'  => 'dishub@jakarta.go.id',
            ],
            'upap' => [
                'id'            => DB::table('ukpds')->where('singkatan', 'UPAP')->value('id'),
                'kepala_nama'   => 'Muhamad Widan Anwar, S.T., M.Sc.',
                'kepala_nip'    => '197509201998031004',
                'pptk_nama'     => 'Dr. Bayu Kusuma Negara, M.H.',
                'pptk_nip'      => '198211152006041005',
                'pengawas_nama' => 'Rahmat Hidayatullah Siregar, S.Kom.',
                'pengawas_nip'  => '199003122014021003',
                'email_kantor'  => 'upapkdishubdki@gmail.com',
            ],
            'uppd' => [
                'id'            => DB::table('ukpds')->where('singkatan', 'UPPD')->value('id'),
                'kepala_nama'   => 'Drs. Sutanto Purnomo Jati, M.M.',
                'kepala_nip'    => '196904041999031008',
                'pptk_nama'     => 'Ir. Eko Prasetyo Wibowo, S.H.',
                'pptk_nip'      => '197801252002121006',
                'pengawas_nama' => 'Dimas Anggara Putra, S.Mn.',
                'pengawas_nip'  => '198807082012101004',
                'email_kantor'  => 'uppd.dishub@jakarta.go.id',
            ],
            'sudinhub' => [
                'id'            => DB::table('ukpds')->where('singkatan', 'Sudinhub Kep. 1000')->value('id'),
                'kepala_nama'   => 'Leo Amstrong Manalu, S.IP., M.Si.',
                'kepala_nip'    => '197305101998021007',
                'pptk_nama'     => 'H. Dedi Syahputra Tarigan, M.T.',
                'pptk_nip'      => '198109302008011009',
                'pengawas_nama' => 'Rizky Pratama Ramadhan, A.Md.',
                'pengawas_nip'  => '199512122019031008',
                'email_kantor'  => null,
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
                $username = "{$roleSlug}_{$ukpdKey}"; // Penambahan base Username
                $email = "{$username}@gmail.com"; 
                $name  = ucwords(str_replace('_', ' ', $roleSlug)) . ' ' . strtoupper($ukpdKey); 
                $nip   = null;

                // [Kustomisasi] Gunakan Email Kantor resmi untuk Admin UKPD jika tersedia
                if ($roleSlug === 'admin_ukpd' && !empty($data['email_kantor'])) {
                    $email = $data['email_kantor'];
                }

                // [Kustomisasi] Gunakan Nama Asli dan NIP sesuai dengan Role
                if ($roleSlug === 'kepala_ukpd') {
                    $name = $data['kepala_nama'];
                    $nip  = $data['kepala_nip'];
                } elseif ($roleSlug === 'pptk') {
                    $name = $data['pptk_nama'];
                    $nip  = $data['pptk_nip'];
                } elseif ($roleSlug === 'pengawas') {
                    $name = $data['pengawas_nama'];
                    $nip  = $data['pengawas_nip'];
                }

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'              => $name,
                        'username'          => $username, // Insert Username ke DB
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