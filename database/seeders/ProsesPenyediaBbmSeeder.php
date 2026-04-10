<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPermohonanPengisian;
use App\Models\ProsesPenyediaBbm;

class ProsesPenyediaBbmSeeder extends Seeder
{
    public function run(): void
    {
        $permohonans = SuratPermohonanPengisian::whereIn('progress', ['on progress', 'done'])->get();

        if ($permohonans->isEmpty()) {
            $this->command->info('Tidak ada permohonan On Progress/Done untuk di-seed.');
            return;
        }

        foreach ($permohonans as $permohonan) {
            $hargaSatuan = 14500.00; // Contoh Harga Pertamax/Dexlite
            $jumlahLiter = $permohonan->jumlah_bbm ?? 1000;

            ProsesPenyediaBbm::create([
                'surat_permohonan_id'  => $permohonan->id,
                'user_id'              => 4, // Asumsi ID 4 adalah user Penyedia
                'tempat_pengambilan'   => 'SPBU 31.102.02 Muara Angke',
                'nomor_izin_penyedia'  => 'SPBU-3110202-MA',
                'harga_satuan'         => $hargaSatuan,
                'total_harga'          => $hargaSatuan * $jumlahLiter,
                'file_evidence'        => 'evidences/dummy_surat_jalan.pdf',
            ]);
        }

        $this->command->info('Data Proses Penyedia BBM berhasil ditambahkan!');
    }
}