<?php

namespace Database\Seeders;

use App\Models\Kapal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KapalSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil data master ke memori (UKPD & Role Nahkoda)
        $ukpds = DB::table('ukpds')->get();
        $nahkodaRoleId = DB::table('roles')->where('slug', 'nahkoda')->value('id'); // AMBIL ID ROLE NAHKODA

        // Cek jika role nahkoda belum ada di DB
        if (!$nahkodaRoleId) {
            $this->command->error("Role 'nahkoda' tidak ditemukan. Pastikan RoleSeeder sudah dijalankan.");
            return;
        }

        // Mengubah URL Google Sheet menjadi URL Export CSV
        $sheetId = "1FTqKmUxb4afNysuppr4yZF76txwkjYffOkLs7xYmIYE";
        $gid = "1075867526";
        $csvUrl = "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv&gid={$gid}";

        $csvFile = @fopen($csvUrl, "r");
  
        if ($csvFile === FALSE) {
            $this->command->warn("Koneksi ke Google Sheets gagal/timeout. Mencoba membaca file lokal...");
            
            $localFilePath = database_path('seeders/kapal.csv');
            
            if (file_exists($localFilePath)) {
                $csvFile = fopen($localFilePath, "r");
                $this->command->info("Berhasil! Menggunakan data dari file lokal: database/seeders/kapal.csv");
            } else {
                $this->command->error("Gagal! URL Google Sheets tidak dapat diakses dan file lokal tidak ditemukan di: {$localFilePath}");
                return; 
            }
        } else {
            $this->command->info("Koneksi sukses! Menggunakan data langsung dari Google Sheets.");
        }

        $row = 0;
        $defaultPassword = Hash::make('password1234');
        
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $row++;
            
            if ($row <= 2) continue;

            if (!isset($data[1]) || !is_numeric(trim($data[1]))) continue;

            $tahunPembuatan = (isset($data[6]) && is_numeric(trim($data[6]))) ? (int) trim($data[6]) : null;

            // 2. Logika Pencocokan UKPD
            $namaUkpdCsv = trim($data[3] ?? '');
            $ukpdId = null;

            if (!empty($namaUkpdCsv)) {
                $matchedUkpd = $ukpds->first(function ($ukpd) use ($namaUkpdCsv) {
                    return (strcasecmp($ukpd->singkatan, $namaUkpdCsv) === 0) || 
                           (strcasecmp($ukpd->nama, $namaUkpdCsv) === 0) ||
                           (stripos($namaUkpdCsv, $ukpd->singkatan) !== false); 
                });

                $ukpdId = $matchedUkpd ? $matchedUkpd->id : null;
            }

            $namaKapal = trim($data[2] ?? '');
            $ukpdIdFinal = $ukpdId ?? 4; 

            // =====================================================================
            // 3. BUAT AKUN NAHKODA UNTUK KAPAL INI
            // =====================================================================
            
            $safeKapalName = Str::slug($namaKapal, '_');
            $nahkodaEmail = "nahkoda_{$safeKapalName}@gmail.com";

            $nahkodaUser = User::updateOrCreate(
                ['email' => $nahkodaEmail], 
                [
                    'name'              => 'Nahkoda ' . $namaKapal,
                    'email_verified_at' => now(),
                    'password'          => $defaultPassword,
                    'role_id'           => $nahkodaRoleId, // GANTI: role menjadi role_id
                    'ukpd_id'           => $ukpdIdFinal,
                ]
            );

            // =====================================================================
            // 4. MEMASUKKAN DATA KAPAL KE DB & HUBUNGKAN DENGAN NAHKODA
            // =====================================================================
            Kapal::create([
                'nama_kapal'            => $namaKapal,
                'ukpd_id'               => $ukpdIdFinal,
                'jenis_dan_tipe'        => trim($data[4] ?? ''),
                'material'              => trim($data[5] ?? ''),
                'tahun_pembuatan'       => $tahunPembuatan,
                'ukuran'                => trim($data[7] ?? ''),
                'tonase_kotor_gt'       => trim($data[8] ?? ''),
                'tenaga_penggerak_kw'   => trim($data[9] ?? ''),
                'daerah_pelayaran'      => trim($data[10] ?? ''),
                'list_sertifikat_kapal' => trim($data[11] ?? ''),
                'user_id'               => 1,
                'nahkoda_id'            => $nahkodaUser->id,
            ]);
        }
  
        fclose($csvFile);
        
        $this->command->info('Data kapal beserta akun Nahkoda-nya berhasil di-seed!');
    }
}