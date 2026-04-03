<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Sounding;
use App\Models\Kapal;

class SoundingDashboard extends Component
{
    public function render()
    {
        // Ambil 5 data sounding (transaksi BBM) terbaru beserta relasi kapalnya
        $recent_soundings = Sounding::with('kapal')
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // Hitung ringkasan statistik (KPI) untuk Dashboard
        $stats = [
            'total_transaksi' => Sounding::count(),
            'total_pengisian' => Sounding::sum('pengisian'),
            'total_pemakaian' => Sounding::sum('pemakaian'),
            'kapal_aktif'     => Sounding::distinct('kapal_id')->count('kapal_id'),
        ];

        return view('livewire.dashboard.sounding-dashboard', [
            'recent_soundings' => $recent_soundings,
            'stats'            => $stats
        ])->layout('layouts.app');
    }
}