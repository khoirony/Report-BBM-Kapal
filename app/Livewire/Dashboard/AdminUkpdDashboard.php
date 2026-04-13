<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Tambahkan facade Auth
use App\Models\PaguAnggaran;

class AdminUkpdDashboard extends Component
{
    // --- Properti Filter & Data Grafik ---
    public $startDate;
    public $endDate;
    public $chartParams = [];
    public $reportData = [];

    // --- Properti CRUD Pagu Anggaran ---
    public $isPaguModalOpen = false;
    public $pagu_id, $ukpd_id, $tahun, $nominal;

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
            'anggaranUkpd'   => $this->generateAnggaranUkpdData(),
            'anggaranKapal'  => $this->generateAnggaranKapalData(),
            'konsumsiHarian' => $this->generateKonsumsiHarianData(), 
            'konsumsiKapal'  => $this->generateKonsumsiKapalData(),  
            'pembelian'      => $this->generatePembelianData(),      
            'jenisBbm'       => $this->generateJenisBbmData(),
        ];

        $this->reportData = $this->fetchQuarterlyReport();
        $this->dispatch('chartsUpdated', $this->chartParams);
    }

    public function openPaguModal()
    {
        $this->resetPaguFields();
        $this->isPaguModalOpen = true;
    }

    public function closePaguModal()
    {
        $this->isPaguModalOpen = false;
        $this->resetPaguFields();
    }

    public function editPagu($id)
    {
        $pagu = PaguAnggaran::findOrFail($id);
        $this->pagu_id = $pagu->id;
        $this->ukpd_id = $pagu->ukpd_id;
        $this->tahun = $pagu->tahun;
        $this->nominal = $pagu->nominal;
        
        $this->isPaguModalOpen = true;
    }

    public function storePagu()
    {
        $this->validate([
            'tahun' => 'required|digits:4',
            'nominal' => 'required|numeric|min:0',
        ]);

        PaguAnggaran::updateOrCreate(
            ['id' => $this->pagu_id],
            [
                'ukpd_id' => Auth::user()->ukpd_id,
                'tahun' => $this->tahun,
                'nominal' => $this->nominal
            ]
        );

        $this->closePaguModal();
        $this->updateAllData();
    }

    public function deletePagu($id)
    {
        PaguAnggaran::find($id)->delete();
        $this->updateAllData();
    }

    private function resetPaguFields()
    {
        $this->pagu_id = null;
        // Jika user memiliki ukpd_id, otomatis pilih UKPD tersebut di form
        $this->ukpd_id = Auth::user()->ukpd_id ?? ''; 
        $this->tahun = date('Y');
        $this->nominal = '';
    }

    private function generateAnggaranUkpdData()
    {
        $tahunFilter = Carbon::parse($this->startDate)->format('Y');
        $userUkpdId = Auth::user()->ukpd_id;

        $data = DB::table('ukpds')
            ->when($userUkpdId, fn($q) => $q->where('ukpds.id', $userUkpdId))
            ->leftJoin('rekonsiliasi_invoices', function($join) {
                $join->on('ukpds.id', '=', 'rekonsiliasi_invoices.ukpd_id')
                     ->whereBetween('rekonsiliasi_invoices.tanggal_invoice', [$this->startDate, $this->endDate]);
            })
            ->leftJoin('pagu_anggarans', function($join) use ($tahunFilter) {
                $join->on('ukpds.id', '=', 'pagu_anggarans.ukpd_id')
                     ->where('pagu_anggarans.tahun', '=', $tahunFilter);
            })
            ->selectRaw('ukpds.singkatan, SUM(rekonsiliasi_invoices.total_tagihan) as realisasi, MAX(pagu_anggarans.nominal) as pagu')
            ->groupBy('ukpds.id', 'ukpds.singkatan')->get();

        return [
            'labels' => $data->pluck('singkatan'),
            'series' => [
                ['name' => 'Pagu Anggaran', 'data' => $data->pluck('pagu')->map(fn($v) => (float)$v ?: 0)], 
                ['name' => 'Penggunaan (Realisasi)', 'data' => $data->pluck('realisasi')->map(fn($v) => (float)$v ?: 0)]
            ]
        ];
    }

    private function generateAnggaranKapalData()
    {
        $userUkpdId = Auth::user()->ukpd_id;

        $data = DB::table('pencatatan_hasils')
            ->join('kapals', 'pencatatan_hasils.kapal_id', '=', 'kapals.id')
            ->leftJoin('proses_penyedia_bbms', 'pencatatan_hasils.id', '=', 'proses_penyedia_bbms.id') 
            ->when($userUkpdId, fn($q) => $q->where('kapals.ukpd_id', $userUkpdId))
            ->whereBetween('pencatatan_hasils.tanggal_pengisian', [$this->startDate, $this->endDate])
            ->selectRaw('kapals.nama_kapal, SUM(proses_penyedia_bbms.total_harga) as total_biaya')
            ->groupBy('kapals.id', 'kapals.nama_kapal')
            ->orderByDesc('total_biaya')->get();

        return [
            'labels' => $data->pluck('nama_kapal')->toArray(),
            'series' => [['name' => 'Total Rupiah', 'data' => $data->pluck('total_biaya')->map(fn($v) => (float)$v)->toArray()]]
        ];
    }

    private function generateKonsumsiHarianData()
    {
        $userUkpdId = Auth::user()->ukpd_id;

        $dbData = DB::table('soundings')
            ->join('kapals', 'soundings.kapal_id', '=', 'kapals.id') // Tambah Join untuk filter UKPD
            ->when($userUkpdId, fn($q) => $q->where('kapals.ukpd_id', $userUkpdId))
            ->whereBetween('soundings.tanggal_sounding', [$this->startDate, $this->endDate])
            ->selectRaw('DATE(soundings.tanggal_sounding) as tanggal, SUM(soundings.pemakaian) as total_pemakaian')
            ->groupBy('tanggal')
            ->pluck('total_pemakaian', 'tanggal')->toArray();

        $labels = []; $seriesData = [];
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

    private function generateKonsumsiKapalData()
    {
        $userUkpdId = Auth::user()->ukpd_id;

        $data = DB::table('soundings')
            ->join('kapals', 'soundings.kapal_id', '=', 'kapals.id')
            ->join('ukpds', 'kapals.ukpd_id', '=', 'ukpds.id')
            ->when($userUkpdId, fn($q) => $q->where('ukpds.id', $userUkpdId))
            ->whereBetween('soundings.tanggal_sounding', [$this->startDate, $this->endDate])
            ->selectRaw('ukpds.singkatan as ukpd, kapals.nama_kapal, SUM(soundings.pemakaian) as total_liter')
            ->groupBy('ukpd', 'kapals.nama_kapal')->get();

        $ukpds = $data->pluck('ukpd')->unique()->values()->toArray();
        $kapals = $data->pluck('nama_kapal')->unique()->values()->toArray();
        $series = [];

        foreach ($kapals as $kapal) {
            $kapalData = [];
            foreach ($ukpds as $ukpd) {
                $record = $data->where('ukpd', $ukpd)->where('nama_kapal', $kapal)->first();
                $kapalData[] = $record ? (float) $record->total_liter : 0;
            }
            $series[] = ['name' => $kapal, 'data' => $kapalData];
        }

        return ['labels' => $ukpds, 'series' => $series];
    }

    private function generatePembelianData()
    {
        $userUkpdId = Auth::user()->ukpd_id;

        $data = DB::table('pencatatan_hasils')
            ->join('kapals', 'pencatatan_hasils.kapal_id', '=', 'kapals.id')
            ->join('ukpds', 'kapals.ukpd_id', '=', 'ukpds.id')
            ->when($userUkpdId, fn($q) => $q->where('ukpds.id', $userUkpdId))
            ->whereBetween('pencatatan_hasils.tanggal_pengisian', [$this->startDate, $this->endDate])
            ->selectRaw('ukpds.singkatan as ukpd, kapals.nama_kapal, SUM(pencatatan_hasils.jumlah_pengisian) as total_liter')
            ->groupBy('ukpd', 'kapals.nama_kapal')->get();

        $ukpds = $data->pluck('ukpd')->unique()->values()->toArray();
        $kapals = $data->pluck('nama_kapal')->unique()->values()->toArray();
        $series = [];

        foreach ($kapals as $kapal) {
            $kapalData = [];
            foreach ($ukpds as $ukpd) {
                $record = $data->where('ukpd', $ukpd)->where('nama_kapal', $kapal)->first();
                $kapalData[] = $record ? (float) $record->total_liter : 0;
            }
            $series[] = ['name' => $kapal, 'data' => $kapalData];
        }

        return ['labels' => $ukpds, 'series' => $series];
    }

    private function generateJenisBbmData()
    {
        $userUkpdId = Auth::user()->ukpd_id;

        $data = DB::table('surat_permohonan_pengisians')
            ->when($userUkpdId, function($q) use ($userUkpdId) {
                $q->where('ukpd_id', $userUkpdId);
            })
            ->whereBetween('tanggal_surat', [$this->startDate, $this->endDate])
            ->selectRaw('jenis_bbm, SUM(jumlah_bbm) as total')
            ->groupBy('jenis_bbm')->get();

        if ($data->isEmpty()) return ['labels' => ['Belum Ada Data'], 'data' => [0]];

        return [
            'labels' => $data->pluck('jenis_bbm'),
            'data'   => $data->pluck('total')->map(fn($v) => (float)$v)
        ];
    }

    public function fetchQuarterlyReport()
    {
        $userUkpdId = Auth::user()->ukpd_id;

        return DB::table('pencatatan_hasils')
            ->join('kapals', 'pencatatan_hasils.kapal_id', '=', 'kapals.id')
            ->join('ukpds', 'kapals.ukpd_id', '=', 'ukpds.id')
            ->leftJoin('proses_penyedia_bbms', 'pencatatan_hasils.id', '=', 'proses_penyedia_bbms.id') 
            ->when($userUkpdId, fn($q) => $q->where('ukpds.id', $userUkpdId))
            ->whereBetween('pencatatan_hasils.tanggal_pengisian', [$this->startDate, $this->endDate])
            ->select('ukpds.singkatan as ukpd', 'kapals.nama_kapal', 'pencatatan_hasils.tanggal_pengisian', 'pencatatan_hasils.jumlah_pengisian as liter', 'proses_penyedia_bbms.harga_satuan', 'proses_penyedia_bbms.total_harga')
            ->orderBy('ukpd')->orderBy('nama_kapal')->orderBy('tanggal_pengisian')
            ->get();
    }

    public function render()
    {
        $tahunFilter = Carbon::parse($this->startDate)->format('Y');
        $userUkpdId = Auth::user()->ukpd_id;

        $stats = [
            'pagu' => DB::table('pagu_anggarans')
                        ->where('tahun', $tahunFilter)
                        ->when($userUkpdId, fn($q) => $q->where('ukpd_id', $userUkpdId))
                        ->sum('nominal') ?: 0,

            'realisasi' => DB::table('rekonsiliasi_invoices')
                        ->whereBetween('tanggal_invoice', [$this->startDate, $this->endDate])
                        ->when($userUkpdId, fn($q) => $q->where('ukpd_id', $userUkpdId))
                        ->sum('total_tagihan'),

            'armada' => DB::table('kapals')
                        ->when($userUkpdId, fn($q) => $q->where('ukpd_id', $userUkpdId))
                        ->count(),

            'liter' => DB::table('pencatatan_hasils')
                        ->join('kapals', 'pencatatan_hasils.kapal_id', '=', 'kapals.id')
                        ->whereBetween('pencatatan_hasils.tanggal_pengisian', [$this->startDate, $this->endDate])
                        ->when($userUkpdId, fn($q) => $q->where('kapals.ukpd_id', $userUkpdId))
                        ->sum('pencatatan_hasils.jumlah_pengisian'),
        ];

        // Fetch data untuk tabel CRUD Pagu
        $pagus = PaguAnggaran::join('ukpds', 'pagu_anggarans.ukpd_id', '=', 'ukpds.id')
            ->select('pagu_anggarans.*', 'ukpds.singkatan as nama_ukpd')
            ->when($userUkpdId, fn($q) => $q->where('pagu_anggarans.ukpd_id', $userUkpdId))
            ->orderBy('tahun', 'desc')
            ->orderBy('nama_ukpd', 'asc')
            ->get();
            
        // Fetch UKPD untuk Dropdown modal
        $ukpds = DB::table('ukpds')
            ->when($userUkpdId, fn($q) => $q->where('id', $userUkpdId))
            ->get();

        return view('livewire.dashboard.admin-ukpd-dashboard', [
            'stats' => $stats,
            'pagus' => $pagus,
            'ukpds' => $ukpds
        ])->layout('layouts.app');
    }
}