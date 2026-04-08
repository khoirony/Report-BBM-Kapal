<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Kapal;
use App\Models\LaporanSisaBbm;
use App\Models\Sounding;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboard extends Component
{
    // Properti Filter
    public $filterBulan;
    public $filterTahun;

    // Properti untuk menyimpan data grafik (dikirim ke JS)
    public $chartParams = [];

    public function mount()
    {
        // Set default filter ke bulan dan tahun saat ini
        $this->filterBulan = Carbon::now()->format('m');
        $this->filterTahun = Carbon::now()->format('Y');

        // Ambil data awal saat halaman dimuat
        $this->updateAllCharts();
    }

    // Lifecycle hook: dijalankan otomatis saat properti filter berubah
    public function updatedFilterBulan() { $this->updateAllCharts(); }
    public function updatedFilterTahun() { $this->updateAllCharts(); }

    public function updateAllCharts()
    {
        // Kumpulkan semua data grafik ke dalam satu array
        $this->chartParams = [
            'anggaran'      => $this->generateAnggaranData(),
            'biayaKapal'    => $this->generateBiayaKapalData(),
            'konsumsi'      => $this->generateKonsumsiData(),
            'pembelian'     => $this->generatePembelianData(),
            'jenisBbm'      => $this->generateJenisBbmData(),
        ];

        // Emit event browser agar JavaScript tahu data sudah update dan grafik perlu dirender ulang
        $this->dispatch('chartsUpdated', $this->chartParams);
    }

    public function render()
    {
        // KPI Ringkas (Tetap menggunakan data asli database)
        $stats = [
            'total_kapal'       => Kapal::count(),
            'total_laporan'     => LaporanSisaBbm::count(),
            'total_sounding'    => Sounding::count(),
            'pagu_anggaran'     => 10000000000, // Dummy: 10 Milyar
            'anggaran_terpakai' => 4500000000,  // Dummy: 4.5 Milyar
        ];

        return view('livewire.dashboard.super-admin-dashboard', [
            'stats' => $stats,
        ])->layout('layouts.app');
    }

    // =========================================================================
    // LOGIKA GENERATE DATA DUMMY (Ganti dengan Query DB Asli nanti)
    // =========================================================================
    
    // 1. Pagu vs Realisasi per UKPD (Bar Chart)
    private function generateAnggaranData()
    {
        // Contoh Query Asli: 
        // return DB::table('ukpd')->select('nama', 'pagu', 'realisasi')->where('tahun', $this->filterTahun)->get();

        $labels = ['Sudinhub Jabar', 'Sudinhub Jatim', 'Sudinhub DKI', 'UP Angkutan Perairan'];
        $pagu = [];
        $realisasi = [];

        // Buat data acak berdasarkan filter agar grafik terlihat berubah saat difilter
        $seed = $this->filterBulan + $this->filterTahun;
        mt_srand($seed); 

        foreach ($labels as $l) {
            $p = mt_rand(20, 50) * 100000000; // 2 - 5 Milyar
            $pagu[] = $p;
            $realisasi[] = $p * (mt_rand(30, 90) / 100); // 30-90% dari pagu
        }

        return [
            'labels' => $labels,
            'series' => [
                ['name' => 'Pagu Anggaran', 'data' => $pagu],
                ['name' => 'Penggunaan', 'data' => $realisasi],
            ]
        ];
    }

    // 2. Biaya BBM Per Kapal (Horizontal Bar)
    private function generateBiayaKapalData()
    {
        $kapal = ['KMC Trunojoyo', 'KMC Antasena', 'KMC Sangaji', 'Catamaran 01', 'Catamaran 02'];
        $biaya = [];
        mt_srand((int)$this->filterBulan * 2 + $this->filterTahun);

        foreach ($kapal as $k) {
            $biaya[] = mt_rand(50, 300) * 1000000; // 50 - 300 Juta
        }

        return [
            'labels' => $kapal,
            'data' => $biaya
        ];
    }

    // 3. Konsumsi Sounding (Line Chart over Time)
    private function generateKonsumsiData()
    {
        $daysInMonth = Carbon::create($this->filterTahun, $this->filterBulan)->daysInMonth;
        $labels = [];
        $totalCons = [];
        $kapalA = [];

        mt_srand((int)$this->filterBulan + $this->filterTahun);

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = "Tgl $i";
            $ka = mt_rand(100, 300);
            $kapalA[] = $ka;
            $totalCons[] = $ka + mt_rand(200, 500); // Total = Kapal A + Lainnya
        }

        return [
            'labels' => $labels,
            'series' => [
                ['name' => 'Total Liter (Semua)', 'data' => $totalCons],
                ['name' => 'KMC Trunojoyo', 'data' => $kapalA],
            ]
        ];
    }

    // 4. Pembelian Rekon (Column Chart)
    private function generatePembelianData()
    {
        $ukpd = ['Sudin DKI', 'UP Perairan'];
        mt_srand((int)$this->filterBulan * 3 + $this->filterTahun);

        return [
            'labels' => $ukpd,
            'series' => [
                ['name' => 'Sudin DKI', 'data' => [mt_rand(5000, 15000), mt_rand(7000, 12000), mt_rand(9000, 20000)]], // 3 Kapal
                ['name' => 'UP Perairan', 'data' => [mt_rand(3000, 8000), mt_rand(4000, 10000)]], // 2 Kapal
            ],
            'kapal_labels' => [['Kapal A1', 'Kapal A2', 'Kapal A3'], ['Kapal B1', 'Kapal B2']]
        ];
    }

    // 5. Jenis BBM (Donut Chart - Total Keseluruhan)
    private function generateJenisBbmData()
    {
        mt_srand((int)$this->filterBulan + $this->filterTahun);
        
        return [
            'labels' => ['Pertamax / Sekelas', 'Pertamina Dex / Sekelas'],
            'data' => [mt_rand(50000, 100000), mt_rand(30000, 80000)]
        ];
    }
}