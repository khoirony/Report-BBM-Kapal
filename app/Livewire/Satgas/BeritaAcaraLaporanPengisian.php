<?php

namespace App\Livewire\Satgas;

use App\Models\BaPengisianBbm;
use App\Models\Kapal;
use App\Models\LaporanPengisianBbm;
use App\Models\Ukpd;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Tambahkan ini
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Carbon\Carbon;

class BeritaAcaraLaporanPengisian extends Component
{
    use WithPagination, WithFileUploads; // Gunakan trait di sini

    // Properti Data Form
    public $laporan_id, $laporan_pengisian_bbm_id, $kapal_id;
    public $tanggal_ba; 
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

    // Penarikan otomatis kapal dari Laporan Master yang bersarang
    public function updatedLaporanPengisianBbmId($value)
    {
        $lp = LaporanPengisianBbm::with('suratTugas.LaporanSisaBbm.sounding')->find($value);
        
        if ($lp) {
            // Ambil kapal_id dari relasi terdalam
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
        $this->nomor_pks = $ba->nomor_pks;
        $this->tanggal_pks = $ba->tanggal_pks;

        $months = ['Januari'=>'01', 'Februari'=>'02', 'Maret'=>'03', 'April'=>'04', 'Mei'=>'05', 'Juni'=>'06', 'Juli'=>'07', 'Agustus'=>'08', 'September'=>'09', 'Oktober'=>'10', 'November'=>'11', 'Desember'=>'12'];
        $monthNum = $months[$ba->bulan_ba] ?? '01';
        $this->tanggal_ba = $ba->tahun_ba . '-' . $monthNum . '-' . str_pad($ba->tgl_ba, 2, '0', STR_PAD_LEFT);

        $this->isOpen = true;
    }

    public function closeModal() { 
        $this->isOpen = false; 
    }

    private function resetFields() {
        $this->laporan_id = null;
        $this->laporan_pengisian_bbm_id = '';
        $this->kapal_id = '';
        $this->tanggal_ba = date('Y-m-d');
        $this->nomor_pks = '';
        $this->tanggal_pks = '';
    }

    public function store() {
        $this->validate([
            'laporan_pengisian_bbm_id' => 'required',
            'kapal_id' => 'required',
            'tanggal_ba' => 'required|date',
        ]);

        $parsedDate = Carbon::parse($this->tanggal_ba)->locale('id');

        BaPengisianBbm::updateOrCreate(['id' => $this->laporan_id], [
            'laporan_pengisian_bbm_id' => $this->laporan_pengisian_bbm_id,
            'kapal_id' => $this->kapal_id,
            'hari' => $parsedDate->isoFormat('dddd'),
            'tgl_ba' => $parsedDate->format('d'),
            'bulan_ba' => $parsedDate->isoFormat('MMMM'),
            'tahun_ba' => $parsedDate->format('Y'),
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
        
        // Hapus file fisik jika ada sebelum menghapus data
        if ($ba->file_ba_pengisian && Storage::disk('public')->exists($ba->file_ba_pengisian)) {
            Storage::disk('public')->delete($ba->file_ba_pengisian);
        }

        $ba->delete();
        session()->flash('message', 'Berita Acara berhasil dihapus.');
    }
}