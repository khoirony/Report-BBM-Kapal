<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID UKPD untuk memudahkan mapping
        $bidpelpen = DB::table('ukpds')->where('singkatan', 'Bidpelpen')->value('id');
        $upap      = DB::table('ukpds')->where('singkatan', 'UPAP')->value('id');
        $uppd      = DB::table('ukpds')->where('singkatan', 'UPPD')->value('id');
        $sudinhub  = DB::table('ukpds')->where('singkatan', 'Sudinhub Kep. 1000')->value('id');

        // 1. Superadmin (Biasanya tanpa UKPD/null)
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'superadmin',
                'ukpd_id' => null, 
            ]
        );

        // 2. Sounding Man (Contoh di Bidpelpen)
        User::updateOrCreate(
            ['email' => 'sounding@gmail.com'],
            [
                'name' => 'Sounding Man',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'sounding',
                'ukpd_id' => $bidpelpen,
            ]
        );

        // 3. Satgas BBM (Contoh di UPAP)
        User::updateOrCreate(
            ['email' => 'satgas@gmail.com'],
            [
                'name' => 'Satgas BBM',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'satgas',
                'ukpd_id' => $upap,
            ]
        );

        // 4. Penyedia BBM (Contoh di UPPD)
        User::updateOrCreate(
            ['email' => 'penyedia@gmail.com'],
            [
                'name' => 'Penyedia BBM',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'penyedia',
                'ukpd_id' => $uppd,
            ]
        );

        // 5. Nahkoda (Contoh di Sudinhub)
        User::updateOrCreate(
            ['email' => 'nahkoda@gmail.com'],
            [
                'name' => 'Nahkoda',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'nahkoda',
                'ukpd_id' => $sudinhub,
            ]
        );

        // 6. Pengawas (Contoh di Bidpelpen)
        User::updateOrCreate(
            ['email' => 'pengawas@gmail.com'],
            [
                'name' => 'Pengawas',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'pengawas',
                'ukpd_id' => $bidpelpen,
            ]
        );

        // 7. Sounding Man V2 (Contoh di UPAP)
        User::updateOrCreate(
            ['email' => 'sounding02@gmail.com'],
            [
                'name' => 'Sounding Man V2',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'sounding',
                'ukpd_id' => $upap,
            ]
        );

        // 8. Satgas BBM V2 (Contoh di UPPD)
        User::updateOrCreate(
            ['email' => 'satgas02@gmail.com'],
            [
                'name' => 'Satgas BBM V2',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'satgas',
                'ukpd_id' => $uppd,
            ]
        );
    }
}