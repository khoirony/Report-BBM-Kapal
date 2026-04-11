<?php

namespace App\Livewire\Satgas;

use App\Models\Kapal;
use App\Models\LaporanSisaBbm as SisaBBM; 
use App\Models\Sounding;
use App\Models\Ukpd; 
use Livewire\Component;
use Livewire\WithPagination;

class LaporanSisaBBM extends Component
{
    use WithPagination;

    // Filter & Search
    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    public $filterUkpd = ''; 
    public $filterTanggalDari = '';
    public $filterTanggalSampai = '';

    // Form Properties (Ditambahkan id_nakhoda dan id_pengawas)
    public $laporan_id, $nomor, $kapal_id, $sounding_id, $tanggal_surat, $klasifikasi, $lampiran, $perihal, $nama_nakhoda, $id_nakhoda, $nama_pengawas, $id_pengawas;
    
    public $available_soundings = [];
    public $isOpen = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); } 
    public function updatingFilterTanggalDari() { $this->resetPage(); }
    public function updatingFilterTanggalSampai() { $this->resetPage(); }

    public function render()
    {
        $query = SisaBBM::with(['sounding.kapal.ukpd', 'ukpd']);

        if (auth()->user()?->role?->slug !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor', 'like', '%' . $this->search . '%')
                  ->orWhere('perihal', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_nakhoda', 'like', '%' . $this->search . '%')
                  ->orWhere('id_nakhoda', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pengawas', 'like', '%' . $this->search . '%')
                  ->orWhere('id_pengawas', 'like', '%' . $this->search . '%')
                  ->orWhereHas('sounding.kapal', function($qKapal) {
                      $qKapal->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterKapal) {
            $query->whereHas('sounding', function($q) {
                $q->where('kapal_id', $this->filterKapal);
            });
        }

        if ($this->filterUkpd) {
            $query->where('ukpd_id', $this->filterUkpd);
        }

        if ($this->filterTanggalDari) {
            $query->whereDate('tanggal_surat', '>=', $this->filterTanggalDari);
        }

        if ($this->filterTanggalSampai) {
            $query->whereDate('tanggal_surat', '<=', $this->filterTanggalSampai);
        }

        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal_surat');
        } else {
            $query->latest('tanggal_surat');
        }

        $laporans = $query->paginate(10);
        
        $kapals = Kapal::query();
        if (auth()->user()?->role?->slug !== 'superadmin') {
            $kapals->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $kapals = $kapals->orderBy('nama_kapal', 'asc')->get();

        $ukpds = Ukpd::orderBy('nama', 'asc')->get();

        return view('livewire.satgas.laporan-sisa-bbm', compact('laporans', 'kapals', 'ukpds'))
            ->layout('layouts.app');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'sortBy', 'filterKapal', 'filterUkpd', 'filterTanggalDari', 'filterTanggalSampai']);
    }

    public function updatedKapalId($value) 
    { 
        if ($value) {
            $this->available_soundings = Sounding::where('kapal_id', $value)->latest()->get();
        } else {
            $this->available_soundings = [];
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->laporan_id = null;
        $this->nomor = '';
        $this->kapal_id = '';
        $this->sounding_id = '';
        $this->tanggal_surat = date('Y-m-d');
        $this->klasifikasi = '';
        $this->lampiran = '';
        $this->perihal = 'Laporan Perhitungan Jumlah Sisa BBM Kapal Sebelum Pengisian';
        $this->nama_nakhoda = '';
        $this->id_nakhoda = '';
        $this->nama_pengawas = '';
        $this->id_pengawas = '';
        $this->available_soundings = [];
    }

    public function store()
    {
        $this->validate([
            'nomor' => 'required|string',
            'kapal_id' => 'required',
            'sounding_id' => 'required',
            'tanggal_surat' => 'required|date',
            'nama_nakhoda' => 'required|string',
            'id_nakhoda' => 'nullable|string',
            'nama_pengawas' => 'required|string',
            'id_pengawas' => 'nullable|string',
        ]);

        $sounding = Sounding::with('kapal')->find($this->sounding_id);
        $ukpdId = $sounding && $sounding->kapal ? $sounding->kapal->ukpd_id : null;

        SisaBBM::updateOrCreate(['id' => $this->laporan_id], [
            'nomor' => $this->nomor,
            'ukpd_id' => $ukpdId, 
            'sounding_id' => $this->sounding_id,
            'tanggal_surat' => $this->tanggal_surat,
            'klasifikasi' => $this->klasifikasi,
            'lampiran' => $this->lampiran,
            'perihal' => $this->perihal ?: 'Laporan Perhitungan Jumlah Sisa BBM Kapal Sebelum Pengisian',
            'nama_nakhoda' => $this->nama_nakhoda,
            'id_nakhoda' => $this->id_nakhoda,
            'nama_pengawas' => $this->nama_pengawas,
            'id_pengawas' => $this->id_pengawas,
            'user_id' => auth()->id() ?? 1,
        ]);

        session()->flash('message', $this->laporan_id ? 'Laporan berhasil diperbarui!' : 'Laporan baru berhasil disimpan!');
        $this->closeModal();
    }

    public function edit($id)
    {
        $this->resetValidation();
        $laporan = SisaBBM::with('sounding')->findOrFail($id);
        
        $this->laporan_id = $id;
        $this->nomor = $laporan->nomor;
        $this->sounding_id = $laporan->sounding_id;
        
        $this->kapal_id = $laporan->sounding ? $laporan->sounding->kapal_id : '';
        $this->tanggal_surat = \Carbon\Carbon::parse($laporan->tanggal_surat)->format('Y-m-d');
        
        $this->klasifikasi = $laporan->klasifikasi;
        $this->lampiran = $laporan->lampiran;
        $this->perihal = $laporan->perihal;
        
        $this->nama_nakhoda = $laporan->nama_nakhoda;
        $this->id_nakhoda = $laporan->id_nakhoda;
        $this->nama_pengawas = $laporan->nama_pengawas;
        $this->id_pengawas = $laporan->id_pengawas;
        
        $this->updatedKapalId($this->kapal_id); 
        
        $this->openModal();
    }

    public function delete($id)
    {
        SisaBBM::find($id)->delete();
        session()->flash('message', 'Laporan berhasil dihapus.');
    }
}