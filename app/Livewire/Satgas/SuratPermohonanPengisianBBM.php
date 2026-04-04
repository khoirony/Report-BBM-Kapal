<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SuratPermohonanPengisian;
use App\Models\SuratTugasPengisian;
use App\Models\Kapal; // Pastikan model Kapal di-import

class SuratPermohonanPengisianBBM extends Component
{
    use WithPagination;

    public $surat_tugas_list, $kapals;
    public $permohonan_id, $surat_tugas_id, $nomor_surat, $tanggal_surat, $klasifikasi, $lampiran;
    public $isModalOpen = 0;

    // Filter, Search, & Sort Properties
    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';

    public function mount()
    {
        $this->surat_tugas_list = SuratTugasPengisian::with('laporanSebelumPengisianBbm.kapal')->get();
        $this->kapals = Kapal::orderBy('nama_kapal')->get(); // Untuk dropdown filter
    }

    // Reset pagination ketika melakukan pencarian atau filter
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterTanggalAwal() { $this->resetPage(); }
    public function updatingFilterTanggalAkhir() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterKapal = '';
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $query = SuratPermohonanPengisian::with([
            'suratTugas.laporanSebelumPengisianBbm.kapal',
            'suratTugas.laporanSebelumPengisianBbm.soundings'
        ]);

        // Pencarian (Nomor Surat atau Nama Kapal)
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('suratTugas.laporanSebelumPengisianBbm.kapal', function($qKapal) {
                      $qKapal->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter Kapal
        if (!empty($this->filterKapal)) {
            $query->whereHas('suratTugas.laporanSebelumPengisianBbm', function($q) {
                $q->where('kapal_id', $this->filterKapal);
            });
        }

        // Filter Rentang Tanggal
        if (!empty($this->filterTanggalAwal)) {
            $query->whereDate('tanggal_surat', '>=', $this->filterTanggalAwal);
        }
        if (!empty($this->filterTanggalAkhir)) {
            $query->whereDate('tanggal_surat', '<=', $this->filterTanggalAkhir);
        }

        // Sortir
        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal_surat');
        } else {
            $query->latest('tanggal_surat');
        }

        $permohonans = $query->paginate(10);

        return view('livewire.satgas.surat-permohonan-pengisian-bbm', [
            'permohonans' => $permohonans
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetFields()
    {
        $this->permohonan_id = null;
        $this->surat_tugas_id = '';
        $this->nomor_surat = '';
        $this->tanggal_surat = '';
        $this->klasifikasi = '';
        $this->lampiran = '1 (satu) berkas';
    }

    public function store()
    {
        $this->validate([
            'surat_tugas_id' => 'required',
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
        ]);

        SuratPermohonanPengisian::updateOrCreate(['id' => $this->permohonan_id], [
            'surat_tugas_id' => $this->surat_tugas_id,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_surat' => $this->tanggal_surat,
            'klasifikasi' => $this->klasifikasi,
            'lampiran' => $this->lampiran,
        ]);

        session()->flash('message', $this->permohonan_id ? 'Surat Permohonan Berhasil Diperbarui.' : 'Surat Permohonan Berhasil Dibuat.');
        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id)
    {
        $permohonan = SuratPermohonanPengisian::findOrFail($id);
        $this->permohonan_id = $id;
        $this->surat_tugas_id = $permohonan->surat_tugas_id;
        $this->nomor_surat = $permohonan->nomor_surat;
        $this->tanggal_surat = $permohonan->tanggal_surat;
        $this->klasifikasi = $permohonan->klasifikasi;
        $this->lampiran = $permohonan->lampiran;

        $this->openModal();
    }

    public function delete($id)
    {
        SuratPermohonanPengisian::find($id)->delete();
        session()->flash('message', 'Surat Permohonan Berhasil Dihapus.');
    }
}