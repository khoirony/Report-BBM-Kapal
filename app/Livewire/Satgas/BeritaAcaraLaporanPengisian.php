<?php

namespace App\Livewire\Satgas;

use App\Models\BaPengisianBbm;
use App\Models\Kapal;
use App\Models\LaporanPengisianBbm;
use App\Models\Ukpd;
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
    public $nomor_ba;
    public $tanggal_ba; 
    public $tanggal_pelaksanaan;
    public $nomor_pks, $tanggal_pks;
    
    // Property untuk menampung file upload inline di tabel
    public $upload_files = [];

    // Properti UI & Filter
    public $isOpen = false;
    public $search = '';
    public $filterUkpd = '', $filterKapal = '', $filterTanggalAwal = '', $filterTanggalAkhir = '';
    public $sortBy = 'latest';
    public $pks_suggestions = [];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }

    public function mount() 
    {
        $this->pks_suggestions = BaPengisianBbm::whereNotNull('nomor_pks')
                                    ->distinct()
                                    ->pluck('nomor_pks')
                                    ->toArray();
    }

    // Otomatis berjalan ketika user memilih file di tabel
    public function updatedUploadFiles($value, $key)
    {
        $this->validate([
            "upload_files.{$key}" => 'required|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
        ]);

        $ba = BaPengisianBbm::findOrFail($key);

        // Hapus file lama jika ada
        if ($ba->file_ba_pengisian && Storage::disk('public')->exists($ba->file_ba_pengisian)) {
            Storage::disk('public')->delete($ba->file_ba_pengisian);
        }

        // Simpan file baru
        $path = $value->store('uploads/ba_pengisian', 'public');
        $ba->update(['file_ba_pengisian' => $path]);

        // Bersihkan memori file sementara
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

        $laporanTerpakai = BaPengisianBbm::when($this->laporan_id, function ($q) {
            $q->where('id', '!=', $this->laporan_id);
        })->pluck('laporan_pengisian_bbm_id')->filter()->toArray();

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

    public function updatedLaporanPengisianBbmId($value)
    {
        $lp = LaporanPengisianBbm::with('suratTugas.LaporanSisaBbm.sounding')->find($value);
        
        if ($lp) {
            $this->kapal_id = $lp->suratTugas?->LaporanSisaBbm?->sounding?->kapal_id ?? '';
        } else {
            $this->kapal_id = ''; 
        }
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

        // Load data tanggal ke Form
        $this->tanggal_ba = $ba->tgl_ba ? Carbon::parse($ba->tgl_ba)->format('Y-m-d') : null;
        $this->tanggal_pelaksanaan = $ba->tgl_pelaksanaan ? Carbon::parse($ba->tgl_pelaksanaan)->format('Y-m-d') : null;

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
    }

    public function store() {
        $this->validate([
            'laporan_pengisian_bbm_id' => 'required',
            'kapal_id' => 'required',
            'nomor_ba' => 'required|string|max:255',
            'tanggal_ba' => 'required|date',
            'tanggal_pelaksanaan' => 'required|date',
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