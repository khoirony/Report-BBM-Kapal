<?php

namespace App\Livewire\Sounding;

use App\Models\Kapal;
use App\Models\Sounding;
use App\Models\Ukpd; // Tambahkan Model Ukpd
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SoundingBBM extends Component
{
    use WithPagination;

    // Properti Form Modal
    public $sounding_id, $kapal_id, $lokasi, $bbm_awal, $pengisian, $pemakaian, $bbm_akhir, $jam_berangkat, $jam_kembali;
    public $isModalOpen = false;

    // Properti Search, Filter, & Sort
    public $search = '';
    public $sortBy = 'latest'; 
    
    public $filterKapal = '';
    public $filterUkpd = ''; // Ubah nama properti menjadi filterUkpd
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); } // Sesuaikan method
    public function updatingFilterTanggalAwal() { $this->resetPage(); }
    public function updatingFilterTanggalAkhir() { $this->resetPage(); }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['bbm_awal', 'pengisian', 'pemakaian'])) {
            $this->bbm_akhir = (float)$this->bbm_awal + (float)$this->pengisian - (float)$this->pemakaian;
        }
    }

    public function resetFilters()
    {
        $this->filterKapal = '';
        $this->filterUkpd = ''; // Sesuaikan
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
        $this->resetPage();
    }

    public function render()
    {
        // Eager load ditambahkan 'kapal.ukpd' agar query lebih efisien
        $query = Sounding::with(['kapal.ukpd', 'user']);

        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }

        // 1. Fitur Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('lokasi', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kapal', function($k) {
                      $k->where('nama_kapal', 'like', '%' . $this->search . '%')
                        // Tambahan: Bisa mencari berdasarkan nama/singkatan UKPD
                        ->orWhereHas('ukpd', function($u) {
                            $u->where('nama', 'like', '%' . $this->search . '%')
                              ->orWhere('singkatan', 'like', '%' . $this->search . '%');
                        });
                  });
            });
        }

        // 2. Fitur Filter
        if ($this->filterKapal) {
            $query->where('kapal_id', $this->filterKapal);
        }

        // Filter menggunakan relasi ukpd_id pada tabel kapal
        if ($this->filterUkpd) {
            $query->whereHas('kapal', function($q) {
                $q->where('ukpd_id', $this->filterUkpd);
            });
        }

        if ($this->filterTanggalAwal) {
            $query->whereDate('created_at', '>=', $this->filterTanggalAwal);
        }
        
        if ($this->filterTanggalAkhir) {
            $query->whereDate('created_at', '<=', $this->filterTanggalAkhir);
        }

        match($this->sortBy) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'), 
        };

        $soundings = $query->paginate(10);
        $kapals = Kapal::orderBy('nama_kapal', 'asc')->get();
        
        // Ambil data dari tabel Ukpd langsung
        $ukpds = Ukpd::orderBy('nama', 'asc')->get();

        return view('livewire.sounding.sounding-bbm', [
            'soundings' => $soundings,
            'kapals' => $kapals,
            'ukpds' => $ukpds, // Parsing data ukpds
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

        $data = [
            'kapal_id' => $this->kapal_id,
            'lokasi' => $this->lokasi,
            'bbm_awal' => $this->bbm_awal,
            'pengisian' => $this->pengisian,
            'pemakaian' => $this->pemakaian,
            'bbm_akhir' => $bbm_akhir_calc,
            'jam_berangkat' => $this->jam_berangkat,
            'jam_kembali' => $this->jam_kembali,
        ];

        if (!$this->sounding_id) {
            $data['user_id'] = auth()->id();
        }

        Sounding::updateOrCreate(['id' => $this->sounding_id], $data);

        session()->flash('message', $this->sounding_id ? 'Pencatatan berhasil diperbarui.' : 'Pencatatan Sounding berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $query = Sounding::query();
        
        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }
        
        $sounding = $query->findOrFail($id);
        
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
        $query = Sounding::query();
        
        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }
        
        $sounding = $query->findOrFail($id);
        $sounding->delete();
        
        session()->flash('message', 'Data pencatatan berhasil dihapus.');
    }
}