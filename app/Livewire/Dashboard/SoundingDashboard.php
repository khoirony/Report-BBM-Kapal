<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PaguAnggaran;

class SoundingDashboard extends Component
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

    private function generateAnggaranUkpdData()
    {
        $tahunFilter = Carbon::parse($this->startDate)->format('Y');
        $userUkpdId = Auth::user()->ukpd_id;

        $data = DB::table('ukpds')
            ->where('ukpds.id', $userUkpdId)
            ->leftJoin('rekonsiliasi_invoices', function($join) {
                $join->on('ukpds.id', '=', 'rekonsiliasi_invoices.ukpd_id')
                     ->whereBetween('rekonsiliasi_invoices.tanggal_invoice', [$this->startDate, $this->endDate])
                     ->where('rekonsiliasi_invoices.status', '!=', 'rejected');
            })
            ->leftJoin('surat_permohonan_pengisians', 'rekonsiliasi_invoices.id', '=', 'surat_permohonan_pengisians.rekonsiliasi_invoice_id')
            ->leftJoin('proses_penyedia_bbms', 'surat_permohonan_pengisians.id', '=', 'proses_penyedia_bbms.surat_permohonan_id')
            ->leftJoin('pagu_anggarans', function($join) use ($tahunFilter) {
                $join->on('ukpds.id', '=', 'pagu_anggarans.ukpd_id')
                     ->where('pagu_anggarans.tahun', '=', $tahunFilter);
            })
            ->selectRaw('ukpds.singkatan, SUM(proses_penyedia_bbms.total_harga) as realisasi, MAX(pagu_anggarans.nominal) as pagu')
            ->groupBy('ukpds.id', 'ukpds.singkatan')
            ->get();

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

        $data = DB::table('rekonsiliasi_invoices')
            ->whereBetween('rekonsiliasi_invoices.tanggal_invoice', [$this->startDate, $this->endDate])
            ->where('rekonsiliasi_invoices.status', '!=', 'rejected')
            ->join('surat_permohonan_pengisians', 'rekonsiliasi_invoices.id', '=', 'surat_permohonan_pengisians.rekonsiliasi_invoice_id')
            ->join('proses_penyedia_bbms', 'surat_permohonan_pengisians.id', '=', 'proses_penyedia_bbms.surat_permohonan_id')
            ->join('laporan_sisa_bbms', 'surat_permohonan_pengisians.laporan_sisa_bbm_id', '=', 'laporan_sisa_bbms.id')
            ->join('soundings', 'laporan_sisa_bbms.sounding_id', '=', 'soundings.id')
            ->join('kapals', 'soundings.kapal_id', '=', 'kapals.id')
            ->where('kapals.ukpd_id', $userUkpdId)
            ->selectRaw('kapals.nama_kapal, SUM(proses_penyedia_bbms.total_harga) as total_biaya')
            ->groupBy('kapals.id', 'kapals.nama_kapal')
            ->orderByDesc('total_biaya')
            ->get();

        return [
            'labels' => $data->pluck('nama_kapal')->toArray(),
            'series' => [['name' => 'Total Rupiah', 'data' => $data->pluck('total_biaya')->map(fn($v) => (float)$v)->toArray()]]
        ];
    }

    private function generateKonsumsiHarianData()
    {
        $userUkpdId = Auth::user()->ukpd_id;

        $dbData = DB::table('soundings')
            ->join('kapals', 'soundings.kapal_id', '=', 'kapals.id')
            ->where('kapals.ukpd_id', $userUkpdId)
            ->whereBetween('soundings.tanggal_sounding', [$this->startDate, $this->endDate])
            ->selectRaw('DATE(soundings.tanggal_sounding) as tanggal, CAST(SUM(soundings.pemakaian) AS DECIMAL(10,2)) as total_pemakaian')
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
            ->where('ukpds.id', $userUkpdId)
            ->whereBetween('soundings.tanggal_sounding', [$this->startDate, $this->endDate])
            ->selectRaw('ukpds.singkatan as ukpd, kapals.nama_kapal, CAST(SUM(soundings.pemakaian) AS DECIMAL(10,2)) as total_liter')
            ->groupBy('ukpd', 'kapals.nama_kapal')
            ->get();

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
            ->where('ukpds.id', $userUkpdId)
            ->whereBetween('pencatatan_hasils.tanggal_pengisian', [$this->startDate, $this->endDate])
            ->selectRaw('ukpds.singkatan as ukpd, kapals.nama_kapal, CAST(SUM(pencatatan_hasils.jumlah_pengisian) AS DECIMAL(10,2)) as total_liter')
            ->groupBy('ukpd', 'kapals.nama_kapal')
            ->get();

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
            ->join('laporan_sisa_bbms', 'surat_permohonan_pengisians.laporan_sisa_bbm_id', '=', 'laporan_sisa_bbms.id')
            ->join('soundings', 'laporan_sisa_bbms.sounding_id', '=', 'soundings.id')
            ->join('kapals', 'soundings.kapal_id', '=', 'kapals.id')
            ->where('kapals.ukpd_id', $userUkpdId)
            ->whereBetween('surat_permohonan_pengisians.tanggal_surat', [$this->startDate, $this->endDate])
            ->selectRaw('surat_permohonan_pengisians.jenis_bbm, CAST(SUM(surat_permohonan_pengisians.jumlah_bbm) AS DECIMAL(10,2)) as total')
            ->groupBy('surat_permohonan_pengisians.jenis_bbm')
            ->get();

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
            ->leftJoin('proses_penyedia_bbms', 'pencatatan_hasils.surat_permohonan_id', '=', 'proses_penyedia_bbms.surat_permohonan_id') 
            ->where('ukpds.id', $userUkpdId)
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
                        ->where('ukpd_id', $userUkpdId)
                        ->sum('nominal') ?: 0,

            'realisasi' => DB::table('rekonsiliasi_invoices')
                        ->whereBetween('rekonsiliasi_invoices.tanggal_invoice', [$this->startDate, $this->endDate])
                        ->where('rekonsiliasi_invoices.status', '!=', 'rejected')
                        ->where('rekonsiliasi_invoices.ukpd_id', $userUkpdId)
                        ->join('surat_permohonan_pengisians', 'rekonsiliasi_invoices.id', '=', 'surat_permohonan_pengisians.rekonsiliasi_invoice_id')
                        ->join('proses_penyedia_bbms', 'surat_permohonan_pengisians.id', '=', 'proses_penyedia_bbms.surat_permohonan_id')
                        ->sum('proses_penyedia_bbms.total_harga') ?: 0,

            'armada' => DB::table('kapals')
                        ->where('ukpd_id', $userUkpdId)
                        ->count(),

            'liter' => DB::table('pencatatan_hasils')
                        ->join('kapals', 'pencatatan_hasils.kapal_id', '=', 'kapals.id')
                        ->whereBetween('pencatatan_hasils.tanggal_pengisian', [$this->startDate, $this->endDate])
                        ->where('kapals.ukpd_id', $userUkpdId)
                        ->sum('pencatatan_hasils.jumlah_pengisian'),
        ];

        $pagus = PaguAnggaran::join('ukpds', 'pagu_anggarans.ukpd_id', '=', 'ukpds.id')
            ->select('pagu_anggarans.*', 'ukpds.singkatan as nama_ukpd')
            ->where('pagu_anggarans.ukpd_id', $userUkpdId)
            ->orderBy('tahun', 'desc')
            ->orderBy('nama_ukpd', 'asc')
            ->get();
            
        $ukpds = DB::table('ukpds')
            ->where('id', $userUkpdId)
            ->get();

        return view('livewire.dashboard.sounding-dashboard', [
            'stats' => $stats,
            'pagus' => $pagus,
            'ukpds' => $ukpds
        ])->layout('layouts.app');
    }
}