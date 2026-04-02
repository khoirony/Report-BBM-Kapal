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

        $this->call([
            KapalSeeder::class,
            SoundingSeeder::class,
        ]);
    }
}
