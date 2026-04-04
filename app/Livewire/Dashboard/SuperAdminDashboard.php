<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Kapal;
use App\Models\LaporanSebelumPengisian;
use App\Models\SuratTugasPengisian;
use App\Models\SuratPermohonanPengisian; // Model baru
use App\Models\Sounding; // Model baru

class SuperAdminDashboard extends Component
{
    public function render()
    {
        // 1. Menghitung ringkasan statistik (KPI Global)
        $stats = [
            'total_users'       => User::count(),
            'total_kapal'       => Kapal::count(),
            'total_laporan'     => LaporanSebelumPengisian::count(),
            'surat_tugas'       => SuratTugasPengisian::count(),
            'surat_permohonan'  => SuratPermohonanPengisian::count(),
            'total_sounding'    => Sounding::count(),
        ];

        // 2. Mengambil 5 Laporan Pengisian terbaru
        $recent_laporans = LaporanSebelumPengisian::with('kapal')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // 3. Mengambil 5 Data Sounding terbaru (Sesuai migration baru)
        $recent_soundings = Sounding::with('kapal')
            ->latest()
            ->take(5)
            ->get();

        // 4. Mengambil 4 Surat Permohonan terbaru untuk Widget di Sidebar
        $recent_permohonan = SuratPermohonanPengisian::with('suratTugas')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.dashboard.super-admin-dashboard', [
            'stats'             => $stats,
            'recent_laporans'   => $recent_laporans,
            'recent_soundings'  => $recent_soundings,
            'recent_permohonan' => $recent_permohonan
        ])->layout('layouts.app');
    }
}