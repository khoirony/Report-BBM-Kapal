<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use Illuminate\Support\Facades\Storage; 
use App\Models\SuratPermohonanPengisian;
use App\Models\SuratTugasPengisian;
use App\Models\Kapal;
use App\Models\Ukpd; 
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class SuratPermohonanPengisianBBM extends Component
{
    use WithPagination, WithFileUploads; 

    public $surat_tugas_list, $kapals;
    public $permohonan_id, $surat_tugas_id, $nomor_surat, $tanggal_surat, $klasifikasi, $lampiran;
    
    public $penyedia_id, $jenis_penyedia_bbm, $tempat_pengambilan_bbm, $metode_pengiriman, $jenis_bbm, $jumlah_bbm;
    
    // Properti Khusus "Lainnya"
    public $jenis_penyedia_bbm_lainnya = '';
    public $jenis_bbm_lainnya = '';

    public $nama_nakhoda, $id_nakhoda, $nama_pptk, $id_pptk;

    // Property untuk menampung file upload inline di tabel
    public $upload_files = [];

    // Kontrol Modal
    public $isModalOpen = false;

    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    public $filterUkpd = ''; 
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';

    public function mount()
    {
        $queryKapal = Kapal::orderBy('nama_kapal');
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $queryKapal->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $this->kapals = $queryKapal->get();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); } 
    public function updatingFilterTanggalAwal() { $this->resetPage(); }
    public function updatingFilterTanggalAkhir() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'filterKapal', 'filterUkpd', 'filterTanggalAwal', 'filterTanggalAkhir']);
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function loadSuratTugasList($currentSuratTugasId = null)
    {
        $queryTugas = SuratTugasPengisian::with('LaporanSisaBbm.sounding.kapal', 'user');
        
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $queryTugas->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $usedSuratTugasIds = SuratPermohonanPengisian::whereNotNull('surat_tugas_id')
            ->pluck('surat_tugas_id')
            ->toArray();

        if ($currentSuratTugasId) {
            $usedSuratTugasIds = array_diff($usedSuratTugasIds, [$currentSuratTugasId]);
        }

        if (!empty($usedSuratTugasIds)) {
            $queryTugas->whereNotIn('id', $usedSuratTugasIds);
        }

        $this->surat_tugas_list = $queryTugas->get();
    }

    public function setLokasiSama()
    {
        if($this->surat_tugas_id) {
            $suratTugas = SuratTugasPengisian::find($this->surat_tugas_id);
            if($suratTugas) {
                $this->tempat_pengambilan_bbm = $suratTugas->lokasi;
            }
        }
    }

    // Otomatis berjalan ketika user memilih file di tabel
    public function updatedUploadFiles($value, $key)
    {
        $this->validate([
            "upload_files.{$key}" => 'required|mimes:pdf,jpg,jpeg,png|max:10120', 
        ]);

        $permohonan = SuratPermohonanPengisian::findOrFail($key);

        // Hapus file lama jika ada
        if ($permohonan->file_surat_permohonan && Storage::disk('public')->exists($permohonan->file_surat_permohonan)) {
            Storage::disk('public')->delete($permohonan->file_surat_permohonan);
        }

        // Simpan file baru
        $path = $value->store('uploads/surat_permohonan', 'public');
        $permohonan->update(['file_surat_permohonan' => $path]);

        // Bersihkan memori file sementara
        unset($this->upload_files[$key]);

        session()->flash('message', 'Dokumen Surat Permohonan berhasil diupload!');
    }

    public function render()
    {
        $query = SuratPermohonanPengisian::with([
            'suratTugas.LaporanSisaBbm.sounding.kapal',
            'user',
            'penyedia' 
        ]);

        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhereHas('penyedia', function($qPenyedia) {
                      $qPenyedia->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('suratTugas.LaporanSisaBbm.sounding.kapal', function($qKapal) {
                      $qKapal->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->filterKapal)) {
            $query->whereHas('suratTugas.LaporanSisaBbm', function($q) {
                $q->whereHas('sounding', function($s) {
                    $s->where('kapal_id', $this->filterKapal);
                });
            });
        }

        if (!empty($this->filterUkpd)) {
            $query->where('ukpd_id', $this->filterUkpd);
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

        $ukpds = Ukpd::orderBy('nama', 'asc')->get();

        $penyediaList = User::whereHas('role', function($q) {
            $q->where('slug', 'penyedia');
        })->orderBy('name', 'asc')->get();

        $nakhoda_users = User::whereHas('role', function($q) {
            $q->where('slug', 'nakhoda'); 
        })->get(['id', 'name', 'nip']); 

        $pptk_users = User::whereHas('role', function($q) {
            $q->where('slug', 'pptk'); 
        })->get(['id', 'name', 'nip']);

        return view('livewire.satgas.surat-permohonan-pengisian-bbm', [
            'permohonans' => $query->paginate(10),
            'ukpds' => $ukpds,
            'penyediaList' => $penyediaList,
            'nakhoda_users' => $nakhoda_users,
            'pptk_users' => $pptk_users
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetFields();
        $this->loadSuratTugasList();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        
        $query = SuratPermohonanPengisian::query();
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        
        $permohonan = $query->findOrFail($id);
        
        $this->permohonan_id = $id;
        $this->surat_tugas_id = $permohonan->surat_tugas_id;
        $this->nomor_surat = $permohonan->nomor_surat;
        $this->tanggal_surat = \Carbon\Carbon::parse($permohonan->tanggal_surat)->format('Y-m-d');
        $this->klasifikasi = $permohonan->klasifikasi;
        $this->lampiran = $permohonan->lampiran;
        
        $this->penyedia_id = $permohonan->penyedia_id;
        $this->tempat_pengambilan_bbm = $permohonan->tempat_pengambilan_bbm;
        $this->metode_pengiriman = $permohonan->metode_pengiriman;
        $this->jumlah_bbm = $permohonan->jumlah_bbm;

        $this->nama_nakhoda = $permohonan->nama_nakhoda;
        $this->id_nakhoda = $permohonan->id_nakhoda;
        $this->nama_pptk = $permohonan->nama_pptk;
        $this->id_pptk = $permohonan->id_pptk;
        
        $jenisPenyediaStandard = ['Stasiun Pengisian Bahan Bakar Umum (SPBU)', 'Agen BBM'];
        if (in_array($permohonan->jenis_penyedia_bbm, $jenisPenyediaStandard) || empty($permohonan->jenis_penyedia_bbm)) {
            $this->jenis_penyedia_bbm = $permohonan->jenis_penyedia_bbm;
            $this->jenis_penyedia_bbm_lainnya = '';
        } else {
            $this->jenis_penyedia_bbm = 'Lainnya';
            $this->jenis_penyedia_bbm_lainnya = $permohonan->jenis_penyedia_bbm;
        }
        
        $jenisBbmStandard = ['Pertamax/sekelas', 'Pertamina Dex/sekelas', 'Dexlite/sekelas'];
        if (in_array($permohonan->jenis_bbm, $jenisBbmStandard) || empty($permohonan->jenis_bbm)) {
            $this->jenis_bbm = $permohonan->jenis_bbm;
            $this->jenis_bbm_lainnya = '';
        } else {
            $this->jenis_bbm = 'Lainnya';
            $this->jenis_bbm_lainnya = $permohonan->jenis_bbm;
        }

        $this->loadSuratTugasList($this->surat_tugas_id);

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'surat_tugas_id' => 'required',
            'nomor_surat' => 'nullable|unique:surat_permohonan_pengisians,nomor_surat,' . $this->permohonan_id,
            'tanggal_surat' => 'required|date',
            'penyedia_id' => 'nullable|exists:users,id',
            'tempat_pengambilan_bbm' => 'nullable|string|max:255',
            'metode_pengiriman' => 'nullable|in:Ambil ditempat,Pengiriman Jalur Darat,Pengiriman Jalur Laut',
            'jumlah_bbm' => 'nullable|numeric|min:0',
            'nama_nakhoda' => 'nullable|string|max:255',
            'id_nakhoda' => 'nullable|string|max:255',
            'nama_pptk' => 'nullable|string|max:255',
            'id_pptk' => 'nullable|string|max:255',
        ]);

        $suratTugas = SuratTugasPengisian::find($this->surat_tugas_id);

        $finalJenisPenyedia = $this->jenis_penyedia_bbm === 'Lainnya' ? $this->jenis_penyedia_bbm_lainnya : $this->jenis_penyedia_bbm;
        $finalJenisBbm = $this->jenis_bbm === 'Lainnya' ? $this->jenis_bbm_lainnya : $this->jenis_bbm;

        $data = [
            'surat_tugas_id' => $this->surat_tugas_id,
            'ukpd_id' => $suratTugas ? $suratTugas->ukpd_id : null,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_surat' => $this->tanggal_surat,
            'klasifikasi' => $this->klasifikasi,
            'lampiran' => $this->lampiran,
            'penyedia_id' => $this->penyedia_id,
            'jenis_penyedia_bbm' => $finalJenisPenyedia,
            'tempat_pengambilan_bbm' => $this->tempat_pengambilan_bbm,
            'metode_pengiriman' => $this->metode_pengiriman,
            'jenis_bbm' => $finalJenisBbm,
            'jumlah_bbm' => $this->jumlah_bbm ? str_replace(',', '.', $this->jumlah_bbm) : null,
            'nama_nakhoda' => $this->nama_nakhoda,
            'id_nakhoda' => $this->id_nakhoda,
            'nama_pptk' => $this->nama_pptk,
            'id_pptk' => $this->id_pptk,
        ];

        if (!$this->permohonan_id) {
            $data['user_id'] = auth()->id();
        }

        SuratPermohonanPengisian::updateOrCreate(['id' => $this->permohonan_id], $data);

        session()->flash('message', $this->permohonan_id ? 'Data Surat Berhasil Diperbarui.' : 'Data Surat Berhasil Dibuat.');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetFields()
    {
        $this->reset([
            'permohonan_id', 'surat_tugas_id', 'nomor_surat', 'tanggal_surat', 'klasifikasi', 
            'penyedia_id', 'jenis_penyedia_bbm', 'jenis_penyedia_bbm_lainnya', 
            'tempat_pengambilan_bbm', 'metode_pengiriman', 'jenis_bbm', 'jenis_bbm_lainnya', 'jumlah_bbm',
            'nama_nakhoda', 'id_nakhoda', 'nama_pptk', 'id_pptk'
        ]);
        $this->lampiran = '1 (satu) berkas';
    }

    public function delete($id)
    {
        $query = SuratPermohonanPengisian::query();
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        
        $permohonan = $query->findOrFail($id);
        
        // Hapus file fisik jika ada
        if ($permohonan->file_surat_permohonan && Storage::disk('public')->exists($permohonan->file_surat_permohonan)) {
            Storage::disk('public')->delete($permohonan->file_surat_permohonan);
        }

        $permohonan->delete();
        session()->flash('message', 'Surat Permohonan Berhasil Dihapus.');
    }

    public function setujui($id)
    {
        $userRole = auth()->user()?->role?->slug;
        if (!in_array($userRole, ['superadmin', 'pptk'])) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menyetujui permohonan ini.');
            return;
        }

        $permohonan = SuratPermohonanPengisian::findOrFail($id);
        
        $permohonan->update([
            'disetujui_pptk_by' => auth()->id(),
            'disetujui_pptk_at' => now(),
            'progress' => 'on progress'
        ]);

        session()->flash('message', 'Surat Permohonan berhasil disetujui.');
    }

    public function batalSetuju($id)
    {
        $userRole = auth()->user()?->role?->slug;
        if (!in_array($userRole, ['superadmin', 'pptk'])) {
            session()->flash('error', 'Anda tidak memiliki akses untuk membatalkan persetujuan ini.');
            return;
        }

        $permohonan = SuratPermohonanPengisian::findOrFail($id);
        
        $permohonan->update([
            'disetujui_pptk_by' => null,
            'disetujui_pptk_at' => null,
            'progress' => 'not started'
        ]);

        session()->flash('message', 'Persetujuan Surat Permohonan dibatalkan.');
    }
}