<?php

namespace App\Livewire\Penyedia;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SuratPermohonanPengisian;
use App\Models\ProsesPenyediaBbm;
use App\Models\User;
use App\Models\Kapal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Pastikan ini di-import untuk fitur Hapus File Lama

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
    public $proses_id; // Ditambahkan untuk penanda Mode Edit
    
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
        $query = SuratPermohonanPengisian::with(['suratTugas.LaporanSisaBbm.sounding.kapal', 'prosesPenyedia', 'penyedia']);

        if (auth()->user()?->role?->slug === 'penyedia') {
            $query->where('penyedia_id', auth()->id());
        } 
        elseif (!empty($this->filterPenyedia)) {
            $query->where('penyedia_id', $this->filterPenyedia);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('suratTugas.LaporanSisaBbm.sounding.kapal', function($subQ) {
                      $subQ->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->filterKapal)) {
            $query->whereHas('suratTugas.LaporanSisaBbm.sounding.kapal', function($q) {
                $q->where('id', $this->filterKapal);
            });
        }

        if (!empty($this->filterTanggalAwal)) {
            $query->whereDate('tanggal_surat', '>=', $this->filterTanggalAwal);
        }
        if (!empty($this->filterTanggalAkhir)) {
            $query->whereDate('tanggal_surat', '<=', $this->filterTanggalAkhir);
        }

        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal_surat');
        } else {
            $query->latest('tanggal_surat');
        }

        $penyedias = [];
        if (auth()->user()?->role?->slug === 'superadmin') {
            $penyedias = User::whereHas('role', function($q) {
                $q->where('slug', 'penyedia');
            })->get();
        }

        $kapals = Kapal::all(); 

        return view('livewire.penyedia.pesanan-masuk-bbm', [
            'pesanans'  => $query->paginate(10),
            'penyedias' => $penyedias,
            'kapals'    => $kapals
        ])->layout('layouts.app');
    }

    public function openProsesModal($id)
    {
        $this->resetValidation();
        $this->reset(['proses_id', 'tempat_pengambilan', 'nomor_izin_penyedia', 'harga_satuan', 'file_evidence']); // Reset proses_id

        $permohonan = SuratPermohonanPengisian::findOrFail($id);
        
        $this->permohonan_id = $permohonan->id;
        $this->nomor_surat = $permohonan->nomor_surat;
        $this->jumlah_liter = $permohonan->jumlah_bbm ?? 0;
        $this->lokasi_tujuan = $permohonan->tempat_pengambilan_bbm ?? 'Pelabuhan / SPBU';

        $this->isModalOpen = true;
    }

    // FUNGSI BARU: Buka Modal Edit
    public function openEditModal($permohonan_id)
    {
        $this->resetValidation();
        $this->reset(['file_evidence']); 

        $permohonan = SuratPermohonanPengisian::with('prosesPenyedia')->findOrFail($permohonan_id);
        $proses = $permohonan->prosesPenyedia;

        if (!$proses) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        $this->proses_id = $proses->id; // Set ID proses untuk mode edit
        $this->permohonan_id = $permohonan->id;
        $this->nomor_surat = $permohonan->nomor_surat;
        $this->jumlah_liter = $permohonan->jumlah_bbm ?? 0;
        $this->lokasi_tujuan = $permohonan->tempat_pengambilan_bbm ?? 'Pelabuhan / SPBU';

        $this->tempat_pengambilan = $proses->tempat_pengambilan;
        $this->nomor_izin_penyedia = $proses->nomor_izin_penyedia;
        $this->harga_satuan = $proses->harga_satuan;

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function storeProses()
    {
        $rules = [
            'tempat_pengambilan'  => 'required|string|max:255',
            'nomor_izin_penyedia' => 'required|string|max:255',
            'harga_satuan'        => 'required|numeric|min:1',
        ];

        // Validasi file: Wajib jika baru, Opsional jika Edit
        if ($this->proses_id) {
            $rules['file_evidence'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072';
        } else {
            $rules['file_evidence'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:3072';
        }

        $this->validate($rules);

        $permohonan = SuratPermohonanPengisian::findOrFail($this->permohonan_id);

        DB::transaction(function () use ($permohonan) {
            $totalHarga = floatval($this->jumlah_liter) * floatval($this->harga_satuan);
            
            $data = [
                'tempat_pengambilan'  => $this->tempat_pengambilan,
                'nomor_izin_penyedia' => $this->nomor_izin_penyedia,
                'harga_satuan'        => $this->harga_satuan,
                'total_harga'         => $totalHarga,
            ];

            // Mode Edit
            if ($this->proses_id) {
                $proses = ProsesPenyediaBbm::findOrFail($this->proses_id);
                
                // Jika user upload file baru, simpan & hapus file lama
                if ($this->file_evidence) {
                    $data['file_evidence'] = $this->file_evidence->store('evidence_penyedia', 'public');
                    if ($proses->file_evidence && Storage::disk('public')->exists($proses->file_evidence)) {
                        Storage::disk('public')->delete($proses->file_evidence);
                    }
                }

                $proses->update($data);
                session()->flash('message', 'Pesanan Delivery Order berhasil diperbarui.');
            } 
            // Mode Tambah Baru
            else {
                $data['surat_permohonan_id'] = $permohonan->id;
                $data['user_id'] = auth()->id();
                $data['file_evidence'] = $this->file_evidence->store('evidence_penyedia', 'public');

                ProsesPenyediaBbm::create($data);
                $permohonan->update(['progress' => 'on progress']);
                session()->flash('message', 'Pesanan berhasil diproses. Status kini menjadi On Progress.');
            }
        });

        $this->closeModal();
    }
}