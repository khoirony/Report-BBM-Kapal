<?php

namespace Database\Seeders;

use App\Models\Role;
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
            RoleSeeder::class,
            UkpdSeeder::class,
            UserSeeder::class,
            KapalSeeder::class,
            SoundingSeeder::class,
            LaporanSisaBBMSeeder::class,
            SuratPermohonanPengisianSeeder::class,
            SuratTugasPengisianSeeder::class,
            ProsesPenyediaBbmSeeder::class,
            PencatatanHasilSeeder::class,
            LaporanPengisianBbmSeeder::class,
            BaPengisianBBMSeeder::class,
            SpjSeeder::class,
            RekonsiliasiSeeder::class,
        ]);
    }
}
