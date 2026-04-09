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

        // Mengubah URL Google Sheet menjadi URL Export CSV
        $sheetId = "1FTqKmUxb4afNysuppr4yZF76txwkjYffOkLs7xYmIYE";
        $gid = "1075867526";
        $csvUrl = "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv&gid={$gid}";

        // Mencoba membuka file langsung dari URL Google Sheets
        // Menggunakan @ untuk menekan error/warning bawaan PHP jika koneksi internet terputus
        $csvFile = @fopen($csvUrl, "r");
  
        // Validasi jika gagal membuka URL, gunakan fallback ke file lokal
        if ($csvFile === FALSE) {
            $this->command->warn("Koneksi ke Google Sheets gagal/timeout. Mencoba membaca file lokal...");
            
            // Tentukan lokasi file CSV lokal Anda (misal kita taruh di folder database/data/)
            $localFilePath = database_path('seeders/kapal.csv');
            
            // Cek apakah file lokalnya ada
            if (file_exists($localFilePath)) {
                $csvFile = fopen($localFilePath, "r");
                $this->command->info("Berhasil! Menggunakan data dari file lokal: database/seeders/kapal.csv");
            } else {
                $this->command->error("Gagal! URL Google Sheets tidak dapat diakses dan file lokal tidak ditemukan di: {$localFilePath}");
                return; // Hentikan eksekusi jika keduanya gagal
            }
        } else {
            $this->command->info("Koneksi sukses! Menggunakan data langsung dari Google Sheets.");
        }

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
                'ukpd_id'               => $ukpdId ?? 4,
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
        
        $this->command->info('Data kapal berhasil di-seed!');
    }
}