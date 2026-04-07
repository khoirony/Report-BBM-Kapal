<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'superadmin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sounding@gmail.com'],
            [
                'name' => 'Sounding Man',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'sounding',
            ]
        );

        User::updateOrCreate(
            ['email' => 'satgas@gmail.com'],
            [
                'name' => 'Satgas BBM',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'satgas',
            ]
        );

        User::updateOrCreate(
            ['email' => 'penyedia@gmail.com'],
            [
                'name' => 'Penyedia BBM',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'penyedia',
            ]
        );

        User::updateOrCreate(
            ['email' => 'nahkoda@gmail.com'],
            [
                'name' => 'Nahkoda',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'nahkoda',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pengawas@gmail.com'],
            [
                'name' => 'Pengawas',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'pengawas',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sounding02@gmail.com'],
            [
                'name' => 'Sounding Man V2',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'sounding',
            ]
        );

        User::updateOrCreate(
            ['email' => 'satgas02@gmail.com'],
            [
                'name' => 'Satgas BBM V2',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role' => 'satgas',
            ]
        );

        $this->call([
            KapalSeeder::class,
            SoundingSeeder::class,
            LaporanSisaBBMSeeder::class,
            LaporanSebelumPengisianSeeder::class,
            SuratTugasPengisianSeeder::class,
            SuratPermohonanPengisianSeeder::class,
        ]);
    }
}
