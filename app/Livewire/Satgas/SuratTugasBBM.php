<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use App\Models\SuratTugas;
use App\Models\LaporanPengisian;

class SuratTugasBBM extends Component
{
    public $surat_tugas, $laporans;
    public $surat_id, $laporan_bbm_id, $nomor_surat, $waktu_pelaksanaan, $tanggal_dikeluarkan;
    public $isOpen = false;

    public function mount()
    {
        // Mengambil semua laporan untuk relasi
        $this->laporans = LaporanPengisian::with('kapal')->latest()->get();
    }

    public function render()
    {
        $this->surat_tugas = SuratTugas::with('laporanBbm.kapal')->latest()->get();
        return view('livewire.satgas.surat-tugas')->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        // Set default value
        $this->waktu_pelaksanaan = '08:00 - Selesai';
        $this->tanggal_dikeluarkan = date('Y-m-d');
        $this->openModal();
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }

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