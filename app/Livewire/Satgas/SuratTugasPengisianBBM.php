<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SuratTugasPengisian;
use App\Models\Kapal;
use App\Models\LaporanSisaBbm;
use App\Models\Ukpd; // Tambahkan Model Ukpd
use Illuminate\Support\Facades\Auth;

class SuratTugasPengisianBBM extends Component
{
    use WithPagination;

    // Properti Form Modal
    public $surat_id, $laporan_pengisian_id, $nomor_surat, $waktu_pelaksanaan, $tanggal_dikeluarkan;
    public $isOpen = false;

    // Properti Search, Filter & Sort
    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    public $filterUkpd = ''; // Tambahan properti filter UKPD
    
    // Properti Rentang Tanggal
    public $filterTanggalDari = '';
    public $filterTanggalSampai = '';

    // Reset pagination ketika filter/search diubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); } // Reset saat UKPD diganti
    public function updatingFilterTanggalDari() { $this->resetPage(); }
    public function updatingFilterTanggalSampai() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterKapal = '';
        $this->filterUkpd = '';
        $this->filterTanggalDari = '';
        $this->filterTanggalSampai = '';
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        // Tambahkan relasi ukpd jika ada di model
        $query = SuratTugasPengisian::with(['LaporanSisaBbm.sounding.kapal', 'user']);

        // Batasi tampilan tabel utama berdasarkan ukpd_id untuk selain superadmin
        if (auth()->user()->role !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        // 1. Fitur Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('LaporanSisaBbm', function($l) {
                      $l->where('keterangan', 'like', '%' . $this->search . '%') // Jika lokasi diubah jadi keterangan sebelumnya
                        ->orWhereHas('kapal', function($k) {
                            $k->where('nama_kapal', 'like', '%' . $this->search . '%');
                        });
                  });
            });
        }

        // 2. Fitur Filter Kapal
        if ($this->filterKapal) {
            $query->whereHas('LaporanSisaBbm', function($q) {
                $q->whereHas('sounding', function($s) {
                    $s->where('kapal_id', $this->filterKapal);
                });
            });
        }

        // Fitur Filter UKPD
        if ($this->filterUkpd) {
            $query->where('ukpd_id', $this->filterUkpd);
        }
        
        // Fitur Filter Rentang Tanggal
        if ($this->filterTanggalDari) {
            $query->whereDate('tanggal_dikeluarkan', '>=', $this->filterTanggalDari);
        }
        if ($this->filterTanggalSampai) {
            $query->whereDate('tanggal_dikeluarkan', '<=', $this->filterTanggalSampai);
        }

        // 3. Fitur Sort
        match($this->sortBy) {
            'oldest' => $query->orderBy('tanggal_dikeluarkan', 'asc'),
            default => $query->orderBy('tanggal_dikeluarkan', 'desc'), 
        };

        $surat_tugas = $query->paginate(10);
        
        $kapals = Kapal::query();
        if (auth()->user()->role !== 'superadmin') {
            $kapals->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $kapals = $kapals->orderBy('nama_kapal', 'asc')->get();

        $ukpds = Ukpd::orderBy('nama', 'asc')->get();
        
        // Filter Laporan (Perbaikan bug variabel $queryLaporan)
        $queryLaporan = LaporanSisaBbm::with('sounding.kapal')->latest();
        if (auth()->user()->role !== 'superadmin') {
            $queryLaporan->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $laporans = $queryLaporan->get();

        return view('livewire.satgas.surat-tugas-pengisian-bbm', [
            'surat_tugas' => $surat_tugas,
            'laporans' => $laporans,
            'kapals' => $kapals,
            'ukpds' => $ukpds
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
        $this->laporan_pengisian_id = '';
        $this->nomor_surat = '';
        $this->waktu_pelaksanaan = '';
        $this->tanggal_dikeluarkan = '';
    }

    public function store()
    {
        $this->validate([
            'laporan_pengisian_id' => 'required',
            'nomor_surat' => 'required',
            'waktu_pelaksanaan' => 'required',
            'tanggal_dikeluarkan' => 'required|date',
        ]);

        $laporanTerkait = LaporanSisaBbm::find($this->laporan_pengisian_id);

        $data = [
            'laporan_sisa_bbm_id' => $this->laporan_pengisian_id, // Disamakan dengan schema DB
            'ukpd_id' => $laporanTerkait ? $laporanTerkait->ukpd_id : null,
            'nomor_surat' => $this->nomor_surat,
            'waktu_pelaksanaan' => $this->waktu_pelaksanaan,
            'tanggal_dikeluarkan' => $this->tanggal_dikeluarkan,
        ];

        if (!$this->surat_id) {
            $data['user_id'] = auth()->id();
        }

        SuratTugasPengisian::updateOrCreate(['id' => $this->surat_id], $data);

        session()->flash('message', $this->surat_id ? 'Surat Tugas diperbarui.' : 'Surat Tugas dibuat.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $query = SuratTugasPengisian::query();
        
        // Ubah dari user_id ke ukpd_id agar bisa mengedit dalam 1 UKPD
        if (auth()->user()->role !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $surat = $query->findOrFail($id);
        
        $this->surat_id = $id;
        $this->laporan_pengisian_id = $surat->laporan_sisa_bbm_id ?? $surat->laporan_pengisian_id; 
        $this->nomor_surat = $surat->nomor_surat;
        $this->waktu_pelaksanaan = $surat->waktu_pelaksanaan;
        $this->tanggal_dikeluarkan = \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->format('Y-m-d');
        
        $this->openModal();
    }

    public function delete($id)
    {
        $query = SuratTugasPengisian::query();
        
        if (auth()->user()->role !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $query->findOrFail($id)->delete();
        
        session()->flash('message', 'Surat Tugas dihapus.');
    }
}