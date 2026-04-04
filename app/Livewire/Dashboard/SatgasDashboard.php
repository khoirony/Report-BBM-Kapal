<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Kapal;
use App\Models\LaporanSebelumPengisian;
use App\Models\SuratTugasPengisian;
use App\Models\Sounding; // Tambahkan Model Sounding

class SatgasDashboard extends Component
{
    public function render()
    {
        // 1. Menghitung ringkasan statistik (KPI)
        $stats = [
            'total_kapal'        => Kapal::count(),
            'total_laporan'      => LaporanSebelumPengisian::count(),
            'total_surat_tugas'  => SuratTugasPengisian::count(),
            'sounding' => Sounding::count(),
        ];

        // 2. Mengambil 5 Laporan Pengisian terbaru
        $recent_laporans = LaporanSebelumPengisian::with('kapal')
                                           ->orderBy('tanggal', 'desc')
                                           ->take(5)
                                           ->get();

        // 3. Mengambil 5 Data Sounding terbaru
        $recent_soundings = Sounding::with('kapal')
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // 4. Mengambil 4 Surat Tugas terbaru untuk Widget
        $recent_surat_tugas = SuratTugasPengisian::latest()->take(4)->get();

        return view('livewire.dashboard.satgas-dashboard', [
            'stats'              => $stats,
            'recent_laporans'    => $recent_laporans,
            'recent_soundings'   => $recent_soundings,
            'recent_surat_tugas' => $recent_surat_tugas
        ])->layout('layouts.app');
    }
}