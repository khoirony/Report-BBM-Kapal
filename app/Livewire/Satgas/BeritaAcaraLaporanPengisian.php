<?php

namespace App\Livewire\Satgas;

use App\Models\BaPengisianBbm;
use App\Models\Kapal;
use App\Models\LaporanPengisianBbm;
use App\Models\Ukpd;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BeritaAcaraLaporanPengisian extends Component
{
    use WithPagination, WithFileUploads;

    // Properti Data Form
    public $laporan_id, $laporan_pengisian_bbm_id, $kapal_id;
    public $nomor_ba, $tanggal_ba, $tanggal_pelaksanaan, $nomor_pks, $tanggal_pks;
    
    // Properti Pejabat
    public $nama_pptk, $nip_pptk;
    public $nama_kepala_ukpd, $nip_kepala_ukpd;
    public $nama_nakhoda, $nip_nakhoda;
    
    // Properti Array User agar bisa dibaca oleh $wire di AlpineJS
    public $pptk_users = [];
    public $kepala_ukpd_users = [];
    public $nakhoda_users = [];
    public $pks_suggestions = [];
    
    public $upload_files = [];
    public $isOpen = false;
    public $search = '';
    public $filterUkpd = '', $filterKapal = '', $filterTanggalAwal = '', $filterTanggalAkhir = '';
    public $sortBy = 'latest';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }

    public function mount() 
    {
        $this->pks_suggestions = BaPengisianBbm::whereNotNull('nomor_pks')
                                    ->distinct()
                                    ->pluck('nomor_pks')
                                    ->toArray();
                                    
        $this->loadUserSuggestions(null); // Load default
    }

    // Fungsi khusus untuk menarik data agar bisa disimpan di public property
    public function loadUserSuggestions($ukpdId = null)
    {
        $this->pptk_users = User::whereHas('role', fn($q) => $q->where('slug', 'pptk'))
            ->when($ukpdId, fn($q) => $q->where('ukpd_id', $ukpdId))
            ->get(['id', 'name', 'nip'])->toArray();

        $this->kepala_ukpd_users = User::whereHas('role', fn($q) => $q->where('slug', 'kepala_ukpd'))
            ->when($ukpdId, fn($q) => $q->where('ukpd_id', $ukpdId))
            ->get(['id', 'name', 'nip'])->toArray();

        $this->nakhoda_users = User::whereHas('role', fn($q) => $q->where('slug', 'nakhoda'))
            ->when($ukpdId, fn($q) => $q->where('ukpd_id', $ukpdId))
            ->get(['id', 'name', 'nip'])->toArray();
    }

    // Trigger saat dropdown Laporan Pengisian dipilih
    public function updatedLaporanPengisianBbmId($value)
    {
        $lp = LaporanPengisianBbm::with('suratTugas.LaporanSisaBbm.sounding.kapal')->find($value);
        if ($lp) {
            $this->kapal_id = $lp->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id ?? '';
            $ukpdId = $lp->suratTugas?->LaporanSisaBbm?->sounding?->kapal?->ukpd_id ?? null;
            
            // Filter user sesuai UKPD Kapal
            $this->loadUserSuggestions($ukpdId);
        } else {
            $this->kapal_id = ''; 
            $this->loadUserSuggestions(null);
        }
    }

    public function updatedUploadFiles($value, $key)
    {
        $this->validate([
            "upload_files.{$key}" => 'required|mimes:pdf,jpg,jpeg,png|max:10120',
        ]);

        $ba = BaPengisianBbm::findOrFail($key);

        if ($ba->file_ba_pengisian && Storage::disk('public')->exists($ba->file_ba_pengisian)) {
            Storage::disk('public')->delete($ba->file_ba_pengisian);
        }

        $path = $value->store('uploads/ba_pengisian', 'public');
        $ba->update(['file_ba_pengisian' => $path]);

        unset($this->upload_files[$key]);
        session()->flash('message', 'Dokumen Berita Acara berhasil diupload!');
    }

    public function render()
    {
        $query = BaPengisianBbm::with([
            'kapal.ukpd', 
            'laporanPengisian.suratPermohonan', 
            'laporanPengisian.suratTugas.petugas',
            'laporanPengisian.suratTugas.LaporanSisaBbm.sounding.kapal'
        ]);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_pks', 'like', "%{$this->search}%")
                ->orWhere('nomor_ba', 'like', "%{$this->search}%")
                ->orWhereHas('kapal', function($k) {
                    $k->where('nama_kapal', 'like', "%{$this->search}%");
                });
            });
        }

        if ($this->filterKapal) $query->where('kapal_id', $this->filterKapal);
        if ($this->filterUkpd) $query->whereHas('kapal', fn($q) => $q->where('ukpd_id', $this->filterUkpd));
        if ($this->filterTanggalAwal) $query->whereDate('created_at', '>=', $this->filterTanggalAwal);
        if ($this->filterTanggalAkhir) $query->whereDate('created_at', '<=', $this->filterTanggalAkhir);

        $query->orderBy('created_at', $this->sortBy === 'latest' ? 'desc' : 'asc');

        $laporanTerpakai = BaPengisianBbm::when($this->laporan_id, fn($q) => $q->where('id', '!=', $this->laporan_id))
            ->pluck('laporan_pengisian_bbm_id')->filter()->toArray();

        return view('livewire.satgas.berita-acara-laporan-pengisian', [
            'laporans' => $query->paginate(10),
            'kapals' => Kapal::orderBy('nama_kapal', 'asc')->get(),
            'ukpds' => Ukpd::orderBy('nama', 'asc')->get(),
            'laporan_pengisian_list' => LaporanPengisianBbm::with(['suratTugas.LaporanSisaBbm.sounding.kapal'])
                ->whereNotIn('id', $laporanTerpakai) 
                ->latest()
                ->get(),
        ])->layout('layouts.app');
    }

    public function resetFilters()
    {
        $this->search = ''; $this->filterUkpd = ''; $this->filterKapal = '';
        $this->filterTanggalAwal = ''; $this->filterTanggalAkhir = '';
        $this->sortBy = 'latest'; $this->resetPage();
    }

    public function approve($id, $level)
    {
        $ba = BaPengisianBbm::findOrFail($id);
        if ($level === 'penyedia_pptk') {
            $ba->update(['disetujui_penyedia_at' => now(), 'disetujui_pptk_at' => now()]);
        } elseif ($level === 'kepala_ukpd') {
            $ba->update(['disetujui_kepala_ukpd_at' => now()]);
        }
        session()->flash('message', 'Persetujuan Berita Acara berhasil dicatat.');
    }

    public function create() { 
        $this->resetFields(); 
        $this->isOpen = true; 
    }
    
    public function edit($id) {
        $ba = BaPengisianBbm::findOrFail($id);
        $this->laporan_id = $ba->id;
        $this->laporan_pengisian_bbm_id = $ba->laporan_pengisian_bbm_id;
        $this->kapal_id = $ba->kapal_id;
        $this->nomor_ba = $ba->nomor_ba;
        $this->nomor_pks = $ba->nomor_pks;
        $this->tanggal_pks = $ba->tanggal_pks;
        $this->tanggal_ba = $ba->tgl_ba ? Carbon::parse($ba->tgl_ba)->format('Y-m-d') : null;
        $this->tanggal_pelaksanaan = $ba->tgl_pelaksanaan ? Carbon::parse($ba->tgl_pelaksanaan)->format('Y-m-d') : null;

        $this->nama_pptk = $ba->nama_pptk;
        $this->nip_pptk = $ba->nip_pptk;
        $this->nama_kepala_ukpd = $ba->nama_kepala_ukpd;
        $this->nip_kepala_ukpd = $ba->nip_kepala_ukpd;
        $this->nama_nakhoda = $ba->nama_nakhoda;
        $this->nip_nakhoda = $ba->nip_nakhoda;

        // Load UKPD data for edit mode
        $lp = LaporanPengisianBbm::with('suratTugas.LaporanSisaBbm.sounding.kapal')->find($this->laporan_pengisian_bbm_id);
        $ukpdId = $lp?->suratTugas?->LaporanSisaBbm?->sounding?->kapal?->ukpd_id ?? null;
        $this->loadUserSuggestions($ukpdId);

        $this->isOpen = true;
    }

    public function closeModal() { 
        $this->isOpen = false; 
    }

    private function resetFields() {
        $this->laporan_id = null;
        $this->laporan_pengisian_bbm_id = '';
        $this->kapal_id = '';
        $this->nomor_ba = '';
        $this->tanggal_ba = date('Y-m-d');
        $this->tanggal_pelaksanaan = date('Y-m-d');
        $this->nomor_pks = '';
        $this->tanggal_pks = '';

        $this->nama_pptk = '';
        $this->nip_pptk = '';
        $this->nama_kepala_ukpd = '';
        $this->nip_kepala_ukpd = '';
        $this->nama_nakhoda = '';
        $this->nip_nakhoda = '';
        
        $this->loadUserSuggestions(null);
    }

    public function store() 
    {
        $this->validate([
            'laporan_pengisian_bbm_id' => 'required',
            'kapal_id' => 'required',
            'nomor_ba' => 'nullable|string|max:255|unique:ba_pengisian_bbms,nomor_ba,' . $this->laporan_id,
            'tanggal_ba' => 'required|date',
            'tanggal_pelaksanaan' => 'required|date',
            'nama_pptk' => 'nullable|string|max:255',
            'nip_pptk' => 'nullable|string|max:255',
            'nama_kepala_ukpd' => 'nullable|string|max:255',
            'nip_kepala_ukpd' => 'nullable|string|max:255',
            'nama_nakhoda' => 'nullable|string|max:255',
            'nip_nakhoda' => 'nullable|string|max:255',
        ]);

        $parsedDateBA = Carbon::parse($this->tanggal_ba)->locale('id');

        BaPengisianBbm::updateOrCreate(['id' => $this->laporan_id], [
            'laporan_pengisian_bbm_id' => $this->laporan_pengisian_bbm_id,
            'kapal_id' => $this->kapal_id,
            'nomor_ba' => $this->nomor_ba,
            'hari' => $parsedDateBA->isoFormat('dddd'),
            'tgl_ba' => $parsedDateBA->format('Y-m-d'),
            'bulan_ba' => $parsedDateBA->isoFormat('MMMM'),
            'tahun_ba' => $parsedDateBA->format('Y'),
            'tgl_pelaksanaan' => Carbon::parse($this->tanggal_pelaksanaan)->format('Y-m-d'),
            'nomor_pks' => $this->nomor_pks,
            'tanggal_pks' => $this->tanggal_pks ?: null,
            'user_id' => auth()->id(),
            'nama_pptk' => $this->nama_pptk,
            'id_pptk' => $this->nip_pptk,
            'nama_kepala_ukpd' => $this->nama_kepala_ukpd,
            'id_kepala_ukpd' => $this->nip_kepala_ukpd,
            'nama_nakhoda' => $this->nama_nakhoda,
            'id_nakhoda' => $this->nip_nakhoda,
        ]);

        session()->flash('message', $this->laporan_id ? 'Berita Acara berhasil diperbarui!' : 'Berita Acara berhasil dibuat!');
        $this->mount();
        $this->closeModal();
    }

    public function delete($id) {
        $ba = BaPengisianBbm::findOrFail($id);
        
        if ($ba->file_ba_pengisian && Storage::disk('public')->exists($ba->file_ba_pengisian)) {
            Storage::disk('public')->delete($ba->file_ba_pengisian);
        }

        $ba->delete();
        session()->flash('message', 'Berita Acara berhasil dihapus.');
    }
}