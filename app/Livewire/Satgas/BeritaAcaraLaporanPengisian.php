<?php

namespace App\Livewire\Satgas;

use App\Models\Kapal;
use App\Models\BaPengisianBbm;
use App\Models\Sounding;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class BeritaAcaraLaporanPengisian extends Component
{
    use WithPagination;

    // Properti Form Modal
    public $laporan_id, $kapal_id, $hari, $tanggal, $dasar_hukum, $kegiatan, $tujuan, $lokasi;
    public $petugas_list = []; 
    public $isOpen = false;

    // Properti Checklist Sounding
    public $available_soundings = [];
    public $selected_soundings = [];

    // Properti Search, Filter, dan Sort
    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    
    // Properti Filter Tanggal Baru
    public $filterTanggalDari = '';
    public $filterTanggalSampai = '';

    // Reset pagination ketika user melakukan pencarian atau filter
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterTanggalDari() { $this->resetPage(); }
    public function updatingFilterTanggalSampai() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterKapal = '';
        $this->filterTanggalDari = '';
        $this->filterTanggalSampai = '';
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function mount()
    {
        $this->initPetugas();
    }

    public function render()
    {
        $query = BaPengisianBbm::with(['kapal', 'soundings', 'user']);

        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }

        // 1. Fitur Search (Cari Lokasi, Kegiatan, atau Nama Kapal)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('lokasi', 'like', '%' . $this->search . '%')
                  ->orWhere('kegiatan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kapal', function($k) {
                      $k->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // 2. Fitur Filter Kapal
        if ($this->filterKapal) {
            $query->where('kapal_id', $this->filterKapal);
        }
        
        // Fitur Filter Rentang Tanggal
        if ($this->filterTanggalDari) {
            $query->whereDate('tanggal', '>=', $this->filterTanggalDari);
        }
        if ($this->filterTanggalSampai) {
            $query->whereDate('tanggal', '<=', $this->filterTanggalSampai);
        }

        // 3. Fitur Sort
        match($this->sortBy) {
            'oldest' => $query->orderBy('tanggal', 'asc'),
            default => $query->orderBy('tanggal', 'desc'), // 'latest'
        };

        // Paginasi & Data Relasi untuk Dropdown
        $laporans = $query->paginate(10);
        $kapals = Kapal::query();
        if (auth()->user()->role !== 'superadmin') {
            $kapals->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $kapals = $kapals->orderBy('nama_kapal', 'asc')->get();

        return view('livewire.satgas.berita-acara-laporan-pengisian', [
            'laporans' => $laporans,
            'kapals' => $kapals,
        ])->layout('layouts.app');
    }

    public function downloadPdf($id)
    {
        $query = BaPengisianBbm::with(['kapal', 'soundings' => function($q) {
            $q->orderBy('created_at', 'asc');
        }]);

        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }

        $laporan = $query->findOrFail($id);

        $pdf = Pdf::loadView('pdf.laporan-pengisian-bbm', ['laporan' => $laporan]);
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Laporan_BBM_' . str_replace(' ', '_', $laporan->kapal->nama_kapal) . '_' . $laporan->tanggal->format('d-m-Y') . '.pdf';

        return response()->streamDownload(fn () => print($pdf->output()), $namaFile);
    }

    public function updatedKapalId() { $this->loadAvailableSoundings(); }
    public function updatedTanggal() { $this->loadAvailableSoundings(); }

    public function loadAvailableSoundings()
    {
        if ($this->kapal_id && $this->tanggal) {
            $query = Sounding::where('kapal_id', $this->kapal_id)
                ->whereDate('created_at', $this->tanggal);
                
            if (auth()->user()->role !== 'superadmin') {
                $query->whereHas('kapal', function ($q) {
                    $q->where('ukpd_id', auth()->user()?->ukpd_id);
                });
            }

            $this->available_soundings = $query->get();
        } else {
            $this->available_soundings = [];
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { 
        $this->isOpen = false; 
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->laporan_id = '';
        $this->kapal_id = '';
        $this->hari = '';
        $this->tanggal = '';
        $this->dasar_hukum = '';
        $this->kegiatan = 'Pengisian BBM Kapal di Pelabuhan Sunda Kelapa';
        $this->tujuan = 'Meningkatkan Ketersediaan BBM Kapal untuk Menunjang Kegiatan Operasional';
        $this->lokasi = '';
        $this->available_soundings = [];
        $this->selected_soundings = [];
        $this->initPetugas();
    }

    private function initPetugas()
    {
        $this->petugas_list = [];
        for ($i = 0; $i < 7; $i++) { $this->petugas_list[] = ['nama' => '', 'jabatan' => '']; }
    }

    public function store()
    {
        $this->validate([
            'kapal_id' => 'required',
            'tanggal' => 'required|date',
            'dasar_hukum' => 'required',
            'lokasi' => 'required',
        ]);

        $data = [
            'kapal_id' => $this->kapal_id,
            'tanggal' => $this->tanggal,
            'dasar_hukum' => $this->dasar_hukum,
            'petugas_list' => $this->petugas_list,
            'kegiatan' => $this->kegiatan,
            'tujuan' => $this->tujuan,
            'lokasi' => $this->lokasi,
        ];

        if (!$this->laporan_id) {
            $data['user_id'] = auth()->id();
        }

        $laporan = BaPengisianBbm::updateOrCreate(['id' => $this->laporan_id], $data);

        $laporan->soundings()->sync($this->selected_soundings);

        session()->flash('message', $this->laporan_id ? 'Laporan berhasil diperbarui.' : 'Laporan berhasil dibuat.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $query = BaPengisianBbm::query();
        
        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }
        
        $laporan = $query->findOrFail($id);
        
        $this->laporan_id = $id;
        $this->kapal_id = $laporan->kapal_id;
        $this->hari = $laporan->hari;
        $this->tanggal = $laporan->tanggal->format('Y-m-d');
        $this->dasar_hukum = $laporan->dasar_hukum;
        $this->kegiatan = $laporan->kegiatan;
        $this->tujuan = $laporan->tujuan;
        $this->lokasi = $laporan->lokasi;
        
        $this->petugas_list = $laporan->petugas_list ?? [];
        while(count($this->petugas_list) < 7) { $this->petugas_list[] = ['nama' => '', 'jabatan' => '']; }
        
        $this->loadAvailableSoundings();
        $this->selected_soundings = $laporan->soundings->pluck('id')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        $query = BaPengisianBbm::query();
        
        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }
        
        $laporan = $query->findOrFail($id);
        
        $laporan->soundings()->detach(); 
        $laporan->delete();
        
        session()->flash('message', 'Laporan berhasil dihapus.');
    }
}