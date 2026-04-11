<?php

namespace App\Livewire\Penyedia;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SuratPermohonanPengisian;
use App\Models\ProsesPenyediaBbm;
use App\Models\User;
use App\Models\Kapal; // Pastikan model Kapal di-import
use Illuminate\Support\Facades\DB;

class PesananMasukBBM extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '', $sortBy = 'latest';
    
    // Properti Filter
    public $filterPenyedia = '';
    public $filterKapal = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';
    
    // Properti Modal Proses
    public $isModalOpen = false;
    public $permohonan_id, $nomor_surat, $jumlah_liter, $lokasi_tujuan;
    
    // Inputan Penyedia
    public $tempat_pengambilan, $nomor_izin_penyedia, $harga_satuan;
    public $file_evidence;

    // Reset halaman saat pencarian atau filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterPenyedia() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterTanggalAwal() { $this->resetPage(); }
    public function updatingFilterTanggalAkhir() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'filterPenyedia', 'filterKapal', 'filterTanggalAwal', 'filterTanggalAkhir']);
        $this->resetPage();
    }

    public function render()
    {
        // Panggil relasi 'penyedia' juga
        $query = SuratPermohonanPengisian::with(['suratTugas.LaporanSisaBbm.sounding.kapal', 'prosesPenyedia', 'penyedia']);

        // 1. Filter hak akses:
        // Jika yang login adalah role penyedia, HANYA tampilkan pesanan untuk perusahaannya
        if (auth()->user()?->role?->slug === 'penyedia') {
            $query->where('penyedia_id', auth()->id());
        } 
        // Jika superadmin yang login, maka dia bisa memfilter berdasarkan dropdown penyedia
        elseif (!empty($this->filterPenyedia)) {
            $query->where('penyedia_id', $this->filterPenyedia);
        }

        // 2. Filter Pencarian Text
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('suratTugas.LaporanSisaBbm.sounding.kapal', function($subQ) {
                      $subQ->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // 3. Filter Kapal
        if (!empty($this->filterKapal)) {
            $query->whereHas('suratTugas.LaporanSisaBbm.sounding.kapal', function($q) {
                $q->where('id', $this->filterKapal);
            });
        }

        // 4. Filter Range Tanggal
        if (!empty($this->filterTanggalAwal)) {
            $query->whereDate('tanggal_surat', '>=', $this->filterTanggalAwal);
        }
        if (!empty($this->filterTanggalAkhir)) {
            $query->whereDate('tanggal_surat', '<=', $this->filterTanggalAkhir);
        }

        // 5. Sorting
        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal_surat');
        } else {
            $query->latest('tanggal_surat');
        }

        // Ambil data untuk dropdown filter (Hanya kirim jika superadmin agar tidak membebani query)
        $penyedias = [];
        if (auth()->user()?->role?->slug === 'superadmin') {
            $penyedias = User::whereHas('role', function($q) {
                $q->where('slug', 'penyedia');
            })->get();
        }

        // Ambil data kapal untuk dropdown
        $kapals = Kapal::all(); 

        return view('livewire.penyedia.pesanan-masuk-bbm', [
            'pesanans'  => $query->paginate(10),
            'penyedias' => $penyedias,
            'kapals'    => $kapals
        ])->layout('layouts.app');
    }

    // ... (method openProsesModal, closeModal, storeProses biarkan sama persis seperti aslinya)
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
            'file_evidence'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ]);

        $permohonan = SuratPermohonanPengisian::findOrFail($this->permohonan_id);

        DB::transaction(function () use ($permohonan) {
            $path = $this->file_evidence->store('evidence_penyedia', 'public');
            $totalHarga = floatval($this->jumlah_liter) * floatval($this->harga_satuan);

            ProsesPenyediaBbm::create([
                'surat_permohonan_id' => $permohonan->id,
                'user_id'             => auth()->id(),
                'tempat_pengambilan'  => $this->tempat_pengambilan,
                'nomor_izin_penyedia' => $this->nomor_izin_penyedia,
                'harga_satuan'        => $this->harga_satuan,
                'total_harga'         => $totalHarga,
                'file_evidence'       => $path,
            ]);

            $permohonan->update(['progress' => 'on progress']);
        });

        session()->flash('message', 'Pesanan berhasil diproses. Status kini menjadi On Progress.');
        $this->closeModal();
    }
}