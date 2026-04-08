<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kapal;
use Illuminate\Support\Facades\DB;

class KapalSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil semua data UKPD ke dalam memori agar tidak query berulang kali di dalam loop
        $ukpds = DB::table('ukpds')->get();

        $csvFile = fopen(base_path("database/seeders/kapal.csv"), "r");
  
        $row = 0;
        
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $row++;
            
            // Skip baris 1 (Judul) dan baris 2 (Header Tabel)
            if ($row <= 2) {
                continue;
            }

            // Skip jika bukan nomor (biasanya keterangan a, b, c)
            if (!isset($data[1]) || !is_numeric(trim($data[1]))) {
                continue;
            }

            // Validasi tahun pembuatan
            $tahunPembuatan = (isset($data[6]) && is_numeric(trim($data[6]))) ? (int) trim($data[6]) : null;

            // 2. Logika Pencocokan UKPD
            $namaUkpdCsv = trim($data[3] ?? '');
            $ukpdId = null;

            if (!empty($namaUkpdCsv)) {
                // Mencari UKPD yang cocok (berdasarkan singkatan atau nama dari tabel ukpds)
                $matchedUkpd = $ukpds->first(function ($ukpd) use ($namaUkpdCsv) {
                    return (strcasecmp($ukpd->singkatan, $namaUkpdCsv) === 0) || 
                           (strcasecmp($ukpd->nama, $namaUkpdCsv) === 0) ||
                           (stripos($namaUkpdCsv, $ukpd->singkatan) !== false); // Cek jika singkatan ada di dalam teks CSV
                });

                // Jika ketemu, ambil ID-nya. Jika tidak, biarkan null.
                $ukpdId = $matchedUkpd ? $matchedUkpd->id : null;
            }

            // 3. Memasukkan data ke DB
            Kapal::create([
                'nama_kapal'            => trim($data[2] ?? ''),
                'ukpd_id'               => $ukpdId, // Ganti skpd_ukpd menjadi ukpd_id
                'jenis_dan_tipe'        => trim($data[4] ?? ''),
                'material'              => trim($data[5] ?? ''),
                'tahun_pembuatan'       => $tahunPembuatan,
                'ukuran'                => trim($data[7] ?? ''),
                'tonase_kotor_gt'       => trim($data[8] ?? ''),
                'tenaga_penggerak_kw'   => trim($data[9] ?? ''),
                'daerah_pelayaran'      => trim($data[10] ?? ''),
                'list_sertifikat_kapal' => trim($data[11] ?? ''),
                'user_id'               => 1, // Pastikan user dengan ID 1 sudah terbuat dari UserSeeder
            ]);
        }
  
        fclose($csvFile);
    }
}