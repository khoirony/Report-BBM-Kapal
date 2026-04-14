<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\ProsesPenyediaBbm;
use App\Models\User;

class PenyediaDashboard extends Component
{
    // Filter Properti
    public $startDate;
    public $endDate;
    public $filterPenyedia = ''; 

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedStartDate() { $this->dispatchCharts(); }
    public function updatedEndDate() { $this->dispatchCharts(); }
    public function updatedFilterPenyedia() { $this->dispatchCharts(); }

    private function dispatchCharts()
    {
        $this->dispatch('chartsUpdated', $this->getChartParams());
    }

    private function applyFilters($query)
    {
        $query->whereBetween('proses_penyedia_bbms.created_at', [
            $this->startDate . ' 00:00:00', 
            $this->endDate . ' 23:59:59'
        ]);

        if (auth()->user()?->role?->slug === 'penyedia') {
            $query->where('surat_permohonan_pengisians.penyedia_id', auth()->id());
        } elseif (!empty($this->filterPenyedia)) {
            $query->where('surat_permohonan_pengisians.penyedia_id', $this->filterPenyedia);
        }

        return $query;
    }

    private function getStats()
    {
        $query = ProsesPenyediaBbm::join('surat_permohonan_pengisians', 'proses_penyedia_bbms.surat_permohonan_id', '=', 'surat_permohonan_pengisians.id')
            ->selectRaw('SUM(proses_penyedia_bbms.total_harga) as total_penjualan, SUM(surat_permohonan_pengisians.jumlah_bbm) as total_liter');

        $totals = $this->applyFilters($query)->first();

        return [
            'total_penjualan' => $totals->total_penjualan ?? 0,
            'total_liter' => $totals->total_liter ?? 0,
        ];
    }

    private function getReportData()
    {
        $query = ProsesPenyediaBbm::select(
                'proses_penyedia_bbms.*', 
                'surat_permohonan_pengisians.jumlah_bbm as liter' 
            )
            ->join('surat_permohonan_pengisians', 'proses_penyedia_bbms.surat_permohonan_id', '=', 'surat_permohonan_pengisians.id')
            ->orderBy('proses_penyedia_bbms.created_at', 'desc');

        return $this->applyFilters($query)->get();
    }

    private function getChartParams()
    {
        $queryTanggal = ProsesPenyediaBbm::join('surat_permohonan_pengisians', 'proses_penyedia_bbms.surat_permohonan_id', '=', 'surat_permohonan_pengisians.id')
            ->selectRaw('DATE(proses_penyedia_bbms.created_at) as tgl, SUM(proses_penyedia_bbms.total_harga) as total_rp, SUM(surat_permohonan_pengisians.jumlah_bbm) as total_lt')
            ->groupBy('tgl')
            ->orderBy('tgl', 'asc');

        $dataTanggal = $this->applyFilters($queryTanggal)->get();

        $labelsTgl = [];
        $seriesPenjualan = [];
        $seriesPembelian = [];

        foreach ($dataTanggal as $row) {
            $labelsTgl[] = Carbon::parse($row->tgl)->format('d M'); 
            $seriesPenjualan[] = (int) $row->total_rp;
            $seriesPembelian[] = (float) $row->total_lt;
        }

        // Query untuk Grafik Donut (Per Jenis BBM)
        $queryJenisBbm = ProsesPenyediaBbm::join('surat_permohonan_pengisians', 'proses_penyedia_bbms.surat_permohonan_id', '=', 'surat_permohonan_pengisians.id')
            ->selectRaw('surat_permohonan_pengisians.jenis_bbm, SUM(surat_permohonan_pengisians.jumlah_bbm) as total_lt')
            ->groupBy('surat_permohonan_pengisians.jenis_bbm');

        $dataJenis = $this->applyFilters($queryJenisBbm)->get();

        $labelsJenis = [];
        $jumlahJenis = [];

        foreach ($dataJenis as $row) {
            $labelsJenis[] = $row->jenis_bbm ?? 'Tidak Diketahui';
            $jumlahJenis[] = (float) $row->total_lt;
        }

        return [
            'penjualan' => [
                'labels' => $labelsTgl,
                'series' => $seriesPenjualan
            ],
            'pembelian' => [
                'labels' => $labelsTgl,
                'series' => $seriesPembelian
            ],
            'jenisBbm' => [
                'labels' => $labelsJenis,
                'data'   => $jumlahJenis
            ]
        ];
    }

    public function render()
    {
        $penyedias = collect();
        if (auth()->user()?->role?->slug !== 'penyedia') {
            $penyedias = User::whereHas('role', function($q) {
                $q->where('slug', 'penyedia');
            })->get();
        }

        return view('livewire.dashboard.penyedia-dashboard', [
            'stats'       => $this->getStats(),
            'reportData'  => $this->getReportData(),
            'chartParams' => $this->getChartParams(),
            'penyedias'   => $penyedias
        ])->layout('layouts.app');
    }
}