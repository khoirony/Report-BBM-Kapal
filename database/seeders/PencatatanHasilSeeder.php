<?php

namespace Database\Seeders;

use App\Models\PencatatanHasil;
use App\Models\Kapal;
use App\Models\User;
use App\Models\SuratPermohonanPengisian;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PencatatanHasilSeeder extends Seeder
{
    public function run(): void
    {
        // Cari user Nakhoda (sebagai pembuat/penginput data)
        $nakhoda = User::whereHas('role', function ($query) {
            $query->where('slug', 'nakhoda');
        })->first();

        // Jika tidak ada nakhoda, ambil user satgas atau superadmin
        if (!$nakhoda) {
            $nakhoda = User::first(); 
        }
        
        // UPDATE: Gunakan eager loading (with) agar relasi terdalamnya ikut ditarik ke memory
        $permohonans = SuratPermohonanPengisian::with('suratTugas.LaporanSisaBbm.sounding')->take(3)->get();

        if ($permohonans->count() > 0) {
            
            // Tentukan objek permohonan untuk masing-masing skenario dengan aman
            $p1 = $permohonans[0];
            $p2 = $permohonans->count() > 1 ? $permohonans[1] : $permohonans[0];
            $p3 = $permohonans->count() > 2 ? $permohonans[2] : $permohonans[0];

            $data = [
                // 1. Skenario: Baru diinput, belum ada yang menyetujui
                [
                    'surat_permohonan_id'      => $p1->id,
                    // UPDATE: Tarik kapal_id langsung dari sounding milik permohonan ke-1
                    'kapal_id'                 => $p1->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id,
                    'tanggal_pengisian'        => Carbon::now()->subDays(1)->format('Y-m-d'),
                    'jumlah_pengisian'         => 5000.00,
                    'foto_proses'              => 'uploads/evidence/dummy_proses.jpg',
                    'foto_flow_meter'          => 'uploads/evidence/dummy_flow.jpg',
                    'foto_struk'               => 'uploads/evidence/dummy_struk.jpg',
                    'disetujui_pengawas_at'    => null,
                    'disetujui_penyedia_at'    => null,
                    'created_by'               => $nakhoda->id,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ],
                // 2. Skenario: Baru disetujui Pengawas
                [
                    'surat_permohonan_id'      => $p2->id,
                    // UPDATE: Tarik kapal_id langsung dari sounding milik permohonan ke-2
                    'kapal_id'                 => $p2->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id,
                    'tanggal_pengisian'        => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'jumlah_pengisian'         => 4500.50,
                    'foto_proses'              => 'uploads/evidence/dummy_proses.jpg',
                    'foto_flow_meter'          => 'uploads/evidence/dummy_flow.jpg',
                    'foto_struk'               => 'uploads/evidence/dummy_struk.jpg',
                    'disetujui_pengawas_at'    => now(),
                    'disetujui_penyedia_at'    => null,
                    'created_by'               => $nakhoda->id,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ],
                // 3. Skenario: Sudah disetujui/diketahui semua pihak (Lengkap)
                [
                    'surat_permohonan_id'      => $p3->id,
                    // UPDATE: Tarik kapal_id langsung dari sounding milik permohonan ke-3
                    'kapal_id'                 => $p3->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id,
                    'tanggal_pengisian'        => Carbon::now()->subDays(7)->format('Y-m-d'),
                    'jumlah_pengisian'         => 8000.00,
                    'foto_proses'              => 'uploads/evidence/dummy_proses.jpg',
                    'foto_flow_meter'          => 'uploads/evidence/dummy_flow.jpg',
                    'foto_struk'               => 'uploads/evidence/dummy_struk.jpg',
                    'disetujui_pengawas_at'    => Carbon::now()->subDays(6),
                    'disetujui_penyedia_at'    => Carbon::now()->subDays(5),
                    'created_by'               => $nakhoda->id,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ],
            ];

            foreach ($data as $item) {
                // Skip pencatatan jika relasi kapal_id ternyata kosong (data tidak valid)
                if ($item['kapal_id']) {
                    PencatatanHasil::create($item);
                }
            }
        }
    }
}