<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Sounding;
use App\Models\Kapal;

class SoundingDashboard extends Component
{
    public function render()
    {
        $userId = auth()->id(); 
        $recent_soundings = Sounding::with('kapal')
                                    ->where('user_id', $userId)
                                    ->latest()
                                    ->take(5)
                                    ->get();

        $stats = [
            'total_transaksi' => Sounding::where('user_id', $userId)->count(),
            'total_pengisian' => Sounding::where('user_id', $userId)->sum('pengisian'),
            'total_pemakaian' => Sounding::where('user_id', $userId)->sum('pemakaian'),
            'kapal_aktif'     => Sounding::where('user_id', $userId)->distinct('kapal_id')->count('kapal_id'),
        ];

        return view('livewire.dashboard.sounding-dashboard', [
            'recent_soundings' => $recent_soundings,
            'stats'            => $stats
        ])->layout('layouts.app');
    }
}