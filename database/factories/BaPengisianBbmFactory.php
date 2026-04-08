<?php

namespace Database\Factories;

use App\Models\LaporanPengisian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaporanPengisian>
 */
class BaPengisianBbmFactory extends Factory
{
    public function definition(): array
    {
        $petugas = [];
        for ($i = 0; $i < 7; $i++) {
            $petugas[] = [
                'nama' => $this->faker->name(),
                'jabatan' => $this->faker->jobTitle(),
            ];
        }

        return [
            // sounding_id, tanggal, dan hari tidak perlu diisi di sini karena sudah di-passing dari Seeder
            'dasar_hukum' => 'Surat Perintah No. ' . $this->faker->numerify('###/SP/2026'),
            'petugas_list' => $petugas,
            'kegiatan' => 'Pengisian BBM Kapal di Pelabuhan Sunda Kelapa',
            'tujuan' => 'Meningkatkan Ketersediaan BBM Kapal untuk Menunjang Kegiatan Operasional',
            'lokasi' => 'Dermaga ' . $this->faker->randomLetter(),
        ];
    }
}
