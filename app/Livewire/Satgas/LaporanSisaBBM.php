<?php

namespace App\Livewire\Satgas;

use App\Models\Kapal;
use App\Models\LaporanSisaBbm as SisaBBM; 
use App\Models\Sounding;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanSisaBBM extends Component
{
    use WithPagination;

    // Filter & Search
    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    public $filterTanggalDari = '';
    public $filterTanggalSampai = '';

    // Form Properties (Sesuai Schema)
    public $laporan_id, $nomor, $kapal_id, $sounding_id, $tanggal_surat, $klasifikasi, $lampiran, $perihal, $nama_nakhoda, $nama_pengawas;
    
    public $available_soundings = [];
    public $isOpen = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterTanggalDari() { $this->resetPage(); }
    public function updatingFilterTanggalSampai() { $this->resetPage(); }

    public function render()
    {
        // 1. Ubah Eager Loading melewati sounding->kapal
        $query = SisaBBM::with(['sounding.kapal']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor', 'like', '%' . $this->search . '%')
                  ->orWhere('perihal', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_nakhoda', 'like', '%' . $this->search . '%')
                  // 2. Ubah pencarian relasi melewati sounding->kapal
                  ->orWhereHas('sounding.kapal', function($qKapal) {
                      $qKapal->where('nama_kapal', 'like', '%' . $this->search . '%')
                             ->orWhere('nama', 'like', '%' . $this->search . '%'); // Antisipasi nama kolom
                  });
            });
        }

        if ($this->filterKapal) {
            // 3. Ubah filter agar mengecek kapal_id dari relasi sounding
            $query->whereHas('sounding', function($q) {
                $q->where('kapal_id', $this->filterKapal);
            });
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
        
        // 4. Ubah sumber data dropdown Kapal menjadi hanya kapal yang ada di data Sounding
        $kapals = Kapal::whereHas('sounding')->get();

        return view('livewire.satgas.laporan-sisa-bbm', compact('laporans', 'kapals'))
            ->layout('layouts.app');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'sortBy', 'filterKapal', 'filterTanggalDari', 'filterTanggalSampai']);
    }

    // Load data sounding otomatis ketika Kapal dipilih
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
        $this->nama_pengawas = '';
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
            'nama_pengawas' => 'required|string',
        ]);

        SisaBBM::updateOrCreate(['id' => $this->laporan_id], [
            'nomor' => $this->nomor,
            'kapal_id' => $this->kapal_id,
            'sounding_id' => $this->sounding_id,
            'tanggal_surat' => $this->tanggal_surat,
            'klasifikasi' => $this->klasifikasi,
            'lampiran' => $this->lampiran,
            'perihal' => $this->perihal ?: 'Laporan Perhitungan Jumlah Sisa BBM Kapal Sebelum Pengisian',
            'nama_nakhoda' => $this->nama_nakhoda,
            'nama_pengawas' => $this->nama_pengawas,
            'user_id' => auth()->id() ?? 1,
        ]);

        session()->flash('message', $this->laporan_id ? 'Laporan berhasil diperbarui!' : 'Laporan baru berhasil disimpan!');
        $this->closeModal();
    }

    public function edit($id)
    {
        $this->resetValidation();
        $laporan = SisaBBM::findOrFail($id);
        
        $this->laporan_id = $id;
        $this->nomor = $laporan->nomor;
        $this->kapal_id = $laporan->kapal_id;
        $this->sounding_id = $laporan->sounding_id;
        $this->tanggal_surat = $laporan->tanggal_surat->format('Y-m-d');
        $this->klasifikasi = $laporan->klasifikasi;
        $this->lampiran = $laporan->lampiran;
        $this->perihal = $laporan->perihal;
        $this->nama_nakhoda = $laporan->nama_nakhoda;
        $this->nama_pengawas = $laporan->nama_pengawas;
        
        $this->updatedKapalId($laporan->kapal_id); // Load soundings
        
        $this->openModal();
    }

    public function delete($id)
    {
        SisaBBM::find($id)->delete();
        session()->flash('message', 'Laporan berhasil dihapus.');
    }
}