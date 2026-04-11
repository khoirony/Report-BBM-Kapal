<?php

namespace Database\Seeders;

use App\Models\PencatatanHasil;
use App\Models\Kapal;
use App\Models\User;
use App\Models\SuratPermohonanPengisian; // <-- Tambahkan import ini
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PencatatanHasilSeeder extends Seeder
{
    public function run(): void
    {
        // Cari user Nahkoda (sebagai pembuat/penginput data)
        $nahkoda = User::whereHas('role', function ($query) {
            $query->where('slug', 'nahkoda');
        })->first();

        // Jika tidak ada nahkoda, ambil user satgas atau superadmin
        if (!$nahkoda) {
            $nahkoda = User::first(); 
        }

        // Ambil beberapa kapal
        $kapals = Kapal::take(3)->get();
        
        // Ambil beberapa surat permohonan yang ada di database
        $permohonans = SuratPermohonanPengisian::take(3)->get();

        // Pastikan ada data kapal DAN surat permohonan di database untuk diikat
        if ($kapals->count() > 0 && $permohonans->count() > 0) {
            $data = [
                // 1. Skenario: Baru diinput, belum ada yang menyetujui
                [
                    'surat_permohonan_id'      => $permohonans[0]->id, // <-- Tautkan ke permohonan
                    'kapal_id'                 => $kapals[0]->id,
                    'tanggal_pengisian'        => Carbon::now()->subDays(1)->format('Y-m-d'),
                    'jumlah_pengisian'         => 5000.00,
                    'foto_proses'              => 'uploads/evidence/dummy_proses.jpg',
                    'foto_flow_meter'          => 'uploads/evidence/dummy_flow.jpg',
                    'foto_struk'               => 'uploads/evidence/dummy_struk.jpg',
                    'disetujui_pengawas_at'    => null,
                    'disetujui_penyedia_at'    => null,
                    'created_by'               => $nahkoda->id,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ],
                // 2. Skenario: Baru disetujui Pengawas
                [
                    'surat_permohonan_id'      => $permohonans->count() > 1 ? $permohonans[1]->id : $permohonans[0]->id,
                    'kapal_id'                 => $kapals->count() > 1 ? $kapals[1]->id : $kapals[0]->id,
                    'tanggal_pengisian'        => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'jumlah_pengisian'         => 4500.50,
                    'foto_proses'              => 'uploads/evidence/dummy_proses.jpg',
                    'foto_flow_meter'          => 'uploads/evidence/dummy_flow.jpg',
                    'foto_struk'               => 'uploads/evidence/dummy_struk.jpg',
                    'disetujui_pengawas_at'    => now(),
                    'disetujui_penyedia_at'    => null,
                    'created_by'               => $nahkoda->id,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ],
                // 3. Skenario: Sudah disetujui/diketahui semua pihak (Lengkap)
                [
                    'surat_permohonan_id'      => $permohonans->count() > 2 ? $permohonans[2]->id : $permohonans[0]->id,
                    'kapal_id'                 => $kapals->count() > 2 ? $kapals[2]->id : $kapals[0]->id,
                    'tanggal_pengisian'        => Carbon::now()->subDays(7)->format('Y-m-d'),
                    'jumlah_pengisian'         => 8000.00,
                    'foto_proses'              => 'uploads/evidence/dummy_proses.jpg',
                    'foto_flow_meter'          => 'uploads/evidence/dummy_flow.jpg',
                    'foto_struk'               => 'uploads/evidence/dummy_struk.jpg',
                    'disetujui_pengawas_at'    => Carbon::now()->subDays(6),
                    'disetujui_penyedia_at'    => Carbon::now()->subDays(5),
                    'created_by'               => $nahkoda->id,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ],
            ];

            foreach ($data as $item) {
                PencatatanHasil::create($item);
            }
        }
    }
}