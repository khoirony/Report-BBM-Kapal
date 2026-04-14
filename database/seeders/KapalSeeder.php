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
        $ukpds = DB::table('ukpds')->get();
        $nakhodaRoleId = DB::table('roles')->where('slug', 'nakhoda')->value('id');

        if (!$nakhodaRoleId) {
            $this->command->error("Role 'nakhoda' tidak ditemukan. Pastikan RoleSeeder sudah dijalankan.");
            return;
        }


        $localFilePath = database_path('seeders/kapal.csv');
        
        if (!file_exists($localFilePath)) {
            $this->command->error("Gagal! File CSV tidak ditemukan di: {$localFilePath}");
            $this->command->warn("Pastikan Anda sudah meletakkan file kapal.csv di dalam folder database/seeders/");
            return; 
        }

        $csvFile = fopen($localFilePath, "r");
        $this->command->info("Membaca data dari file lokal: {$localFilePath}");

        $row = 0;
        $defaultPassword = Hash::make('password1234');
        
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $row++;
            
            if ($row <= 2) continue;

            if (!isset($data[1]) || !is_numeric(trim($data[1]))) continue;

            $tahunPembuatan = (isset($data[6]) && is_numeric(trim($data[6]))) ? (int) trim($data[6]) : null;

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
            
            $safeKapalName = Str::slug($namaKapal, '_');
            $nakhodaEmail = "nakhoda_{$safeKapalName}@gmail.com";

            $nakhodaUser = User::updateOrCreate(
                ['email' => $nakhodaEmail], 
                [
                    'name'              => 'Nakhoda ' . $namaKapal,
                    'email_verified_at' => now(),
                    'password'          => $defaultPassword,
                    'role_id'           => $nakhodaRoleId,
                    'ukpd_id'           => $ukpdIdFinal,
                ]
            );

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
                'nakhoda_id'            => $nakhodaUser->id,
            ]);
        }
  
        fclose($csvFile);
        
        $this->command->info('Data kapal beserta akun Nakhoda-nya berhasil di-seed!');
    }
}