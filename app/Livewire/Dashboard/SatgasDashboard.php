<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Kapal;
use App\Models\LaporanSebelumPengisian;
use App\Models\SuratTugasPengisian;
use App\Models\SuratPermohonanPengisian;
use Illuminate\Support\Facades\Auth;

class SatgasDashboard extends Component
{
    public function render()
    {
        // Ambil ID user yang sedang login
        $userId = auth()->id();

        // 1. Menghitung ringkasan statistik (KPI)
        $stats = [
            'total_kapal'        => Kapal::count(), // Dikecualikan, tetap menghitung seluruh kapal
            'total_laporan'      => LaporanSebelumPengisian::where('user_id', $userId)->count(),
            'total_surat_tugas'  => SuratTugasPengisian::where('user_id', $userId)->count(),
            'total_permohonan'   => SuratPermohonanPengisian::where('user_id', $userId)->count(), 
        ];

        // 2. Mengambil Laporan Pengisian terbaru berdasarkan user_id
        $recent_laporans = LaporanSebelumPengisian::with('kapal')
                                           ->where('user_id', $userId)
                                           ->orderBy('tanggal', 'desc')
                                           ->take(3)
                                           ->get();

        // 3. Mengambil Data Surat Permohonan terbaru berdasarkan user_id
        $recent_permohonans = SuratPermohonanPengisian::with('suratTugas.laporanSebelumPengisianBbm.kapal')
                                    ->where('user_id', $userId)
                                    ->latest('tanggal_surat') 
                                    ->take(3)
                                    ->get();

        // 4. Mengambil Surat Tugas terbaru untuk Widget berdasarkan user_id
        $recent_surat_tugas = SuratTugasPengisian::where('user_id', $userId)
                                    ->latest()
                                    ->take(4)
                                    ->get();

        return view('livewire.dashboard.satgas-dashboard', [
            'stats'              => $stats,
            'recent_laporans'    => $recent_laporans,
            'recent_permohonans' => $recent_permohonans, 
            'recent_surat_tugas' => $recent_surat_tugas
        ])->layout('layouts.app');
    }
}