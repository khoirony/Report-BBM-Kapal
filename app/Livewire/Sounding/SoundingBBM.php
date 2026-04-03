<?php

namespace App\Livewire\Sounding;

use App\Models\Kapal;
use App\Models\Sounding;
use Livewire\Component;
use Livewire\WithPagination;

class SoundingBBM extends Component
{
    use WithPagination;

    // Properti Form Modal
    public $sounding_id, $kapal_id, $lokasi, $bbm_awal, $pengisian, $pemakaian, $bbm_akhir, $jam_berangkat, $jam_kembali;
    public $isModalOpen = false;

    // Properti Search, Filter, & Sort
    public $search = '';
    public $sortBy = 'latest'; // Hanya latest & oldest
    
    public $filterKapal = '';
    public $filterSkpd = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';

    // Reset pagination ketika filter/search diubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterSkpd() { $this->resetPage(); }
    public function updatingFilterTanggalAwal() { $this->resetPage(); }
    public function updatingFilterTanggalAkhir() { $this->resetPage(); }

    // Menghitung otomatis BBM Akhir saat input berubah di form modal
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['bbm_awal', 'pengisian', 'pemakaian'])) {
            $this->bbm_akhir = (float)$this->bbm_awal + (float)$this->pengisian - (float)$this->pemakaian;
        }
    }

    public function resetFilters()
    {
        $this->filterKapal = '';
        $this->filterSkpd = '';
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Sounding::with('kapal');

        // 1. Fitur Search (Mencari Lokasi atau Nama Kapal)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('lokasi', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kapal', function($k) {
                      $k->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // 2. Fitur Filter Spesifik
        if ($this->filterKapal) {
            $query->where('kapal_id', $this->filterKapal);
        }

        if ($this->filterSkpd) {
            $query->whereHas('kapal', function($q) {
                $q->where('skpd_ukpd', $this->filterSkpd);
            });
        }

        // Rentang Tanggal (Date Range)
        if ($this->filterTanggalAwal) {
            $query->whereDate('created_at', '>=', $this->filterTanggalAwal);
        }
        
        if ($this->filterTanggalAkhir) {
            $query->whereDate('created_at', '<=', $this->filterTanggalAkhir);
        }

        // 3. Fitur Sort (Hanya Tanggal)
        match($this->sortBy) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'), // 'latest'
        };

        // Paginasi & Data Dropdown
        $soundings = $query->paginate(10);
        $kapals = Kapal::orderBy('nama_kapal', 'asc')->get();
        
        // Ambil data SKPD/UKPD yang unik dari tabel kapal
        $skpds = Kapal::whereNotNull('skpd_ukpd')
                    ->where('skpd_ukpd', '!=', '')
                    ->distinct()
                    ->pluck('skpd_ukpd');

        return view('livewire.sounding.sounding-bbm', [
            'soundings' => $soundings,
            'kapals' => $kapals,
            'skpds' => $skpds,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->sounding_id = '';
        $this->kapal_id = '';
        $this->lokasi = '';
        $this->bbm_awal = 0;
        $this->pengisian = 0;
        $this->pemakaian = 0;
        $this->bbm_akhir = 0;
        $this->jam_berangkat = '';
        $this->jam_kembali = '';
    }

    public function store()
    {
        $this->validate([
            'kapal_id' => 'required',
            'lokasi' => 'required|string|max:255',
            'bbm_awal' => 'required|numeric',
            'pengisian' => 'required|numeric',
            'pemakaian' => 'required|numeric',
            'jam_berangkat' => 'required',
            'jam_kembali' => 'required',
        ]);

        $bbm_akhir_calc = (float)$this->bbm_awal + (float)$this->pengisian - (float)$this->pemakaian;

        Sounding::updateOrCreate(['id' => $this->sounding_id], [
            'kapal_id' => $this->kapal_id,
            'lokasi' => $this->lokasi,
            'bbm_awal' => $this->bbm_awal,
            'pengisian' => $this->pengisian,
            'pemakaian' => $this->pemakaian,
            'bbm_akhir' => $bbm_akhir_calc,
            'jam_berangkat' => $this->jam_berangkat,
            'jam_kembali' => $this->jam_kembali,
        ]);

        session()->flash('message', $this->sounding_id ? 'Pencatatan berhasil diperbarui.' : 'Pencatatan Sounding berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $sounding = Sounding::findOrFail($id);
        $this->sounding_id = $id;
        $this->kapal_id = $sounding->kapal_id;
        $this->lokasi = $sounding->lokasi;
        $this->bbm_awal = $sounding->bbm_awal;
        $this->pengisian = $sounding->pengisian;
        $this->pemakaian = $sounding->pemakaian;
        $this->bbm_akhir = $sounding->bbm_akhir;
        $this->jam_berangkat = $sounding->jam_berangkat;
        $this->jam_kembali = $sounding->jam_kembali;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Sounding::find($id)->delete();
        session()->flash('message', 'Data pencatatan berhasil dihapus.');
    }
}