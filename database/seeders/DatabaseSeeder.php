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
        $this->call([
            UkpdSeeder::class,
            UserSeeder::class,
            KapalSeeder::class,
            SoundingSeeder::class,
            LaporanSisaBBMSeeder::class,
            LaporanSebelumPengisianSeeder::class,
            SuratTugasPengisianSeeder::class,
            SuratPermohonanPengisianSeeder::class,
        ]);
    }
}
