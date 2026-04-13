<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NahkodaDashboard extends Component
{
    // --- Properti Filter & Data Grafik ---
    public $startDate;
    public $endDate;
    public $chartParams = [];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfQuarter()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->updateAllData();
    }

    public function updatedStartDate() { $this->updateAllData(); }
    public function updatedEndDate() { $this->updateAllData(); }

    public function updateAllData()
    {
        $this->chartParams = [
            'konsumsiHarian' => $this->generateKonsumsiHarianData(), 
        ];

        $this->dispatch('chartsUpdated', $this->chartParams);
    }

    private function generateKonsumsiHarianData()
    {
        // 1. Ambil ID Nahkoda yang sedang login
        $userId = Auth::id();

        $dbData = DB::table('soundings')
            ->join('kapals', 'soundings.kapal_id', '=', 'kapals.id')
            ->where('kapals.nahkoda_id', $userId) 
            ->whereBetween('soundings.tanggal_sounding', [$this->startDate, $this->endDate])
            ->selectRaw('DATE(soundings.tanggal_sounding) as tanggal, SUM(soundings.pemakaian) as total_pemakaian')
            ->groupBy('tanggal')
            ->pluck('total_pemakaian', 'tanggal')->toArray();

        $labels = []; 
        $seriesData = [];
        $currentDate = Carbon::parse($this->startDate);
        $endDateObj = Carbon::parse($this->endDate);

        while ($currentDate->lte($endDateObj)) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d M');
            $seriesData[] = isset($dbData[$dateString]) ? (float)$dbData[$dateString] : 0;
            $currentDate->addDay();
        }

        return ['labels' => $labels, 'series' => [['name' => 'Total Liter', 'data' => $seriesData]]];
    }

    public function render()
    {
        // Semua fetching data statistik dan Pagu Anggaran yang tidak perlu sudah dihilangkan
        return view('livewire.dashboard.nahkoda-dashboard')->layout('layouts.app');
    }
}