<?php

namespace App\Livewire\Penyedia;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SuratPermohonanPengisian;
use App\Models\ProsesPenyediaBbm;
use Illuminate\Support\Facades\DB;

class PesananMasukBBM extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '', $sortBy = 'latest';
    
    // Properti Modal Proses
    public $isModalOpen = false;
    public $permohonan_id, $nomor_surat, $jumlah_liter, $lokasi_tujuan;
    
    // Inputan Penyedia
    public $tempat_pengambilan, $nomor_izin_penyedia, $harga_satuan;
    public $file_evidence;

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $query = SuratPermohonanPengisian::with(['suratTugas.LaporanSisaBbm.sounding.kapal', 'prosesPenyedia']);

        // Penyedia hanya melihat yang belum selesai (Not Started & On Progress)
        // Jika butuh filter spesifik nama PT penyedia, tambahkan ->where('nama_perusahaan', 'Nama PT User')
        
        if (!empty($this->search)) {
            $query->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('suratTugas.LaporanSisaBbm.sounding.kapal', function($q) {
                      $q->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
        }

        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal_surat');
        } else {
            $query->latest('tanggal_surat');
        }

        return view('livewire.penyedia.pesanan-masuk-bbm', [
            'pesanans' => $query->paginate(10)
        ])->layout('layouts.app');
    }

    public function openProsesModal($id)
    {
        $this->resetValidation();
        $this->reset(['tempat_pengambilan', 'nomor_izin_penyedia', 'harga_satuan', 'file_evidence']);

        $permohonan = SuratPermohonanPengisian::findOrFail($id);
        
        $this->permohonan_id = $permohonan->id;
        $this->nomor_surat = $permohonan->nomor_surat;
        $this->jumlah_liter = $permohonan->jumlah_bbm ?? 0;
        $this->lokasi_tujuan = $permohonan->tempat_pengambilan_bbm ?? 'Pelabuhan / SPBU';

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function storeProses()
    {
        $this->validate([
            'tempat_pengambilan'  => 'required|string|max:255',
            'nomor_izin_penyedia' => 'required|string|max:255',
            'harga_satuan'        => 'required|numeric|min:1',
            'file_evidence'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072', // Max 3MB
        ]);

        $permohonan = SuratPermohonanPengisian::findOrFail($this->permohonan_id);

        DB::transaction(function () use ($permohonan) {
            // 1. Upload File Bukti
            $path = $this->file_evidence->store('evidence_penyedia', 'public');

            // 2. Kalkulasi Total
            $totalHarga = floatval($this->jumlah_liter) * floatval($this->harga_satuan);

            // 3. Simpan ke tabel baru
            ProsesPenyediaBbm::create([
                'surat_permohonan_id' => $permohonan->id,
                'user_id'             => auth()->id(),
                'tempat_pengambilan'  => $this->tempat_pengambilan,
                'nomor_izin_penyedia' => $this->nomor_izin_penyedia,
                'harga_satuan'        => $this->harga_satuan,
                'total_harga'         => $totalHarga,
                'file_evidence'       => $path,
            ]);

            // 4. Ubah Progress Permohonan (Dishub) menjadi On Progress
            $permohonan->update(['progress' => 'on progress']);
            
            // Opsional: Taruh kode trigger notifikasi ke Dishub di sini
        });

        session()->flash('message', 'Pesanan berhasil diproses. Status kini menjadi On Progress.');
        $this->closeModal();
    }
}