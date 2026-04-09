<?php

namespace App\Livewire\Sounding;

use App\Models\Kapal;
use App\Models\Sounding;
use App\Models\Ukpd;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SoundingBBM extends Component
{
    use WithPagination;

    // Properti Form Modal
    public $sounding_id, $kapal_id, $keterangan, $tanggal_sounding, $bbm_awal, $pengisian, $pemakaian, $bbm_akhir, $jam_berangkat, $jam_kembali;
    public $isModalOpen = false;

    // Properti Search, Filter, & Sort
    public $search = '';
    public $sortBy = 'latest'; 
    
    public $filterKapal = '';
    public $filterUkpd = ''; 
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); } 
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
        $this->filterUkpd = ''; 
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Sounding::with(['kapal.ukpd', 'user']);

        if (auth()->user()->role !== 'superadmin') {
            $query->whereHas('kapal', function ($q) {
                $q->where('ukpd_id', auth()->user()?->ukpd_id);
            });
        }

        // 1. Fitur Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kapal', function($k) {
                      $k->where('nama_kapal', 'like', '%' . $this->search . '%')
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

        if ($this->filterUkpd) {
            $query->whereHas('kapal', function($q) {
                $q->where('ukpd_id', $this->filterUkpd);
            });
        }

        if ($this->filterTanggalAwal) {
            $query->whereDate('tanggal_sounding', '>=', $this->filterTanggalAwal);
        }
        
        if ($this->filterTanggalAkhir) {
            $query->whereDate('tanggal_sounding', '<=', $this->filterTanggalAkhir);
        }

        match($this->sortBy) {
            'oldest' => $query->orderBy('tanggal_sounding', 'asc')->orderBy('id', 'asc'),
            default => $query->orderBy('tanggal_sounding', 'desc')->orderBy('id', 'desc'), 
        };

        $soundings = $query->paginate(10);
        $kapals = Kapal::query();
        if (auth()->user()->role !== 'superadmin') {
            $kapals->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $kapals = $kapals->orderBy('nama_kapal', 'asc')->get();
        
        $ukpds = Ukpd::orderBy('nama', 'asc')->get();

        return view('livewire.sounding.sounding-bbm', [
            'soundings' => $soundings,
            'kapals' => $kapals,
            'ukpds' => $ukpds,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->tanggal_sounding = date('Y-m-d'); 
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
        $this->keterangan = '';
        $this->tanggal_sounding = '';
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
            'keterangan' => 'required|string|max:255',
            'tanggal_sounding' => 'required|date',
            'bbm_awal' => 'required|numeric',
            'pengisian' => 'required|numeric',
            'pemakaian' => 'required|numeric',
            'jam_berangkat' => 'required',
            'jam_kembali' => 'required',
        ]);

        $bbm_akhir_calc = (float)$this->bbm_awal + (float)$this->pengisian - (float)$this->pemakaian;

        $data = [
            'kapal_id' => $this->kapal_id,
            'keterangan' => $this->keterangan,
            'tanggal_sounding' => $this->tanggal_sounding,
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
            $query->whereHas('kapal', function ($q) {
                $q->where('ukpd_id', auth()->user()?->ukpd_id);
            });
        }
        
        $sounding = $query->findOrFail($id);
        
        $this->sounding_id = $id;
        $this->kapal_id = $sounding->kapal_id;
        $this->keterangan = $sounding->keterangan;
        $this->tanggal_sounding = $sounding->tanggal_sounding;
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
            $query->whereHas('kapal', function ($q) {
                $q->where('ukpd_id', auth()->user()?->ukpd_id);
            });
        }
        
        $sounding = $query->findOrFail($id);
        $sounding->delete();
        
        session()->flash('message', 'Data pencatatan berhasil dihapus.');
    }
}