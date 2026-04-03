<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SuratTugas;
use App\Models\LaporanPengisian;
use App\Models\Kapal;

class SuratTugasBBM extends Component
{
    use WithPagination;

    // Properti Form Modal
    public $surat_id, $laporan_bbm_id, $nomor_surat, $waktu_pelaksanaan, $tanggal_dikeluarkan;
    public $isOpen = false;

    // Properti Search, Filter & Sort
    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    
    // Properti Rentang Tanggal
    public $filterTanggalDari = '';
    public $filterTanggalSampai = '';

    // Reset pagination ketika filter/search diubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterTanggalDari() { $this->resetPage(); }
    public function updatingFilterTanggalSampai() { $this->resetPage(); }

    public function render()
    {
        $query = SuratTugas::with(['laporanBbm.kapal']);

        // 1. Fitur Search (Cari Nomor Surat, Nama Kapal, atau Lokasi Laporan)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('laporanBbm', function($l) {
                      $l->where('lokasi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('kapal', function($k) {
                            $k->where('nama_kapal', 'like', '%' . $this->search . '%');
                        });
                  });
            });
        }

        // 2. Fitur Filter Kapal
        if ($this->filterKapal) {
            $query->whereHas('laporanBbm', function($q) {
                $q->where('kapal_id', $this->filterKapal);
            });
        }
        
        // Fitur Filter Rentang Tanggal Dikeluarkan
        if ($this->filterTanggalDari) {
            $query->whereDate('tanggal_dikeluarkan', '>=', $this->filterTanggalDari);
        }
        if ($this->filterTanggalSampai) {
            $query->whereDate('tanggal_dikeluarkan', '<=', $this->filterTanggalSampai);
        }

        // 3. Fitur Sort
        match($this->sortBy) {
            'oldest' => $query->orderBy('tanggal_dikeluarkan', 'asc'),
            default => $query->orderBy('tanggal_dikeluarkan', 'desc'), // 'latest'
        };

        // Ambil data untuk Paginasi & Dropdown Filter
        $surat_tugas = $query->paginate(10);
        $laporans = LaporanPengisian::with('kapal')->latest()->get();
        $kapals = Kapal::orderBy('nama_kapal', 'asc')->get();

        return view('livewire.satgas.surat-tugas', [
            'surat_tugas' => $surat_tugas,
            'laporans' => $laporans,
            'kapals' => $kapals
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->waktu_pelaksanaan = '08:00 - Selesai';
        $this->tanggal_dikeluarkan = date('Y-m-d');
        $this->openModal();
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { 
        $this->isOpen = false; 
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->surat_id = '';
        $this->laporan_bbm_id = '';
        $this->nomor_surat = '';
        $this->waktu_pelaksanaan = '';
        $this->tanggal_dikeluarkan = '';
    }

    public function store()
    {
        $this->validate([
            'laporan_bbm_id' => 'required',
            'nomor_surat' => 'required',
            'waktu_pelaksanaan' => 'required',
            'tanggal_dikeluarkan' => 'required|date',
        ]);

        SuratTugas::updateOrCreate(['id' => $this->surat_id], [
            'laporan_bbm_id' => $this->laporan_bbm_id,
            'nomor_surat' => $this->nomor_surat,
            'waktu_pelaksanaan' => $this->waktu_pelaksanaan,
            'tanggal_dikeluarkan' => $this->tanggal_dikeluarkan,
        ]);

        session()->flash('message', $this->surat_id ? 'Surat Tugas diperbarui.' : 'Surat Tugas dibuat.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $surat = SuratTugas::findOrFail($id);
        $this->surat_id = $id;
        $this->laporan_bbm_id = $surat->laporan_bbm_id;
        $this->nomor_surat = $surat->nomor_surat;
        $this->waktu_pelaksanaan = $surat->waktu_pelaksanaan;
        $this->tanggal_dikeluarkan = $surat->tanggal_dikeluarkan->format('Y-m-d');
        
        $this->openModal();
    }

    public function delete($id)
    {
        SuratTugas::find($id)->delete();
        session()->flash('message', 'Surat Tugas dihapus.');
    }
}