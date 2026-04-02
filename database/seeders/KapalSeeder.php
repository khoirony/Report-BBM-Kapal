<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kapal;

class KapalSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(base_path("database/seeders/kapal.csv"), "r");
  
        $row = 0;
        
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $row++;
            
            // Skip baris 1 (Judul) dan baris 2 (Header Tabel)
            if ($row <= 2) {
                continue;
            }

            // PERBAIKAN DI SINI:
            // Cek apakah kolom 'No' (index 1) adalah angka. 
            // Baris keterangan di bawah biasanya berisi huruf seperti 'a)', 'b)', atau kosong.
            // Jika bukan angka (atau kosong), berarti itu bukan baris data kapal, jadi kita skip.
            if (!isset($data[1]) || !is_numeric(trim($data[1]))) {
                continue;
            }

            // Validasi aman untuk tahun pembuatan
            $tahunPembuatan = (isset($data[6]) && is_numeric(trim($data[6]))) ? (int) trim($data[6]) : null;

            // Memasukkan data ke DB
            Kapal::create([
                'nama_kapal'            => trim($data[2] ?? ''),
                'skpd_ukpd'             => trim($data[3] ?? ''),
                'jenis_dan_tipe'        => trim($data[4] ?? ''),
                'material'              => trim($data[5] ?? ''),
                'tahun_pembuatan'       => $tahunPembuatan,
                'ukuran'                => trim($data[7] ?? ''),
                'tonase_kotor_gt'       => trim($data[8] ?? ''),
                'tenaga_penggerak_kw'   => trim($data[9] ?? ''),
                'daerah_pelayaran'      => trim($data[10] ?? ''),
                'list_sertifikat_kapal' => trim($data[11] ?? '')
            ]);
        }
  
        fclose($csvFile);
    }
}