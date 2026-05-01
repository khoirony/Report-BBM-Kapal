<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use Illuminate\Support\Facades\Storage; 
use App\Models\SuratPermohonanPengisian;
use App\Models\LaporanSisaBbm;
use App\Models\Kapal;
use App\Models\Ukpd; 
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class SuratPermohonanPengisianBBM extends Component
{
    use WithPagination, WithFileUploads; 

    public $laporan_sisa_list, $kapals;
    public $permohonan_id, $laporan_sisa_bbm_id, $nomor_surat, $tanggal_surat, $klasifikasi, $lampiran;
    
    public $tanggal_pelaksanaan, $waktu_pelaksanaan;

    public $penyedia_id, $jenis_penyedia_bbm, $tempat_pengambilan_bbm, $lokasi_pengisian, $nomor_spbu, $metode_pengiriman, $jenis_bbm, $jumlah_bbm;
    
    public $jenis_penyedia_bbm_lainnya = '';
    public $jenis_bbm_lainnya = '';

    public $nama_nakhoda, $id_nakhoda, $nama_pptk, $id_pptk;

    public $petugasList = [];

    public $upload_files = [];
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

    public function addPetugas()
    {
        $this->petugasList[] = ['nama_petugas' => '', 'jabatan' => ''];
    }

    public function removePetugas($index)
    {
        unset($this->petugasList[$index]);
        $this->petugasList = array_values($this->petugasList);
    }

    public function loadLaporanSisaList($currentLaporanId = null)
    {
        $queryLaporan = LaporanSisaBbm::with('sounding.kapal', 'user');
        
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $queryLaporan->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $usedLaporanIds = SuratPermohonanPengisian::whereNotNull('laporan_sisa_bbm_id')
            ->pluck('laporan_sisa_bbm_id')
            ->toArray();

        if ($currentLaporanId) {
            $usedLaporanIds = array_diff($usedLaporanIds, [$currentLaporanId]);
        }

        if (!empty($usedLaporanIds)) {
            $queryLaporan->whereNotIn('id', $usedLaporanIds);
        }

        $this->laporan_sisa_list = $queryLaporan->get();
    }

    public function updatedUploadFiles($value, $key)
    {
        $this->validate([
            "upload_files.{$key}" => 'required|mimes:pdf,jpg,jpeg,png|max:10120', 
        ]);

        $permohonan = SuratPermohonanPengisian::findOrFail($key);

        if ($permohonan->file_surat_permohonan && Storage::disk('public')->exists($permohonan->file_surat_permohonan)) {
            Storage::disk('public')->delete($permohonan->file_surat_permohonan);
        }

        $path = $value->store('uploads/surat_permohonan', 'public');
        $permohonan->update(['file_surat_permohonan' => $path]);
        unset($this->upload_files[$key]);

        session()->flash('message', 'Dokumen Surat Permohonan berhasil diupload!');
    }

    public function render()
    {
        $query = SuratPermohonanPengisian::with([
            'LaporanSisaBbm.sounding.kapal',
            'user',
            'penyedia',
            'petugas' 
        ]);

        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_spbu', 'like', '%' . $this->search . '%')
                  ->orWhere('lokasi_pengisian', 'like', '%' . $this->search . '%')
                  ->orWhereHas('penyedia', function($qPenyedia) {
                      $qPenyedia->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('LaporanSisaBbm.sounding.kapal', function($qKapal) {
                      $qKapal->where('nama_kapal', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->filterKapal)) {
            $query->whereHas('LaporanSisaBbm', function($q) {
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
        $this->loadLaporanSisaList();
        
        $this->tanggal_pelaksanaan = date('Y-m-d'); 
        $this->waktu_pelaksanaan = '08:00 - Selesai';

        $this->petugasList = [
            ['nama_petugas' => '', 'jabatan' => 'Nakhoda'],
            ['nama_petugas' => '', 'jabatan' => 'KKM'],
            ['nama_petugas' => '', 'jabatan' => 'ABK']
        ];
        
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
        $this->laporan_sisa_bbm_id = $permohonan->laporan_sisa_bbm_id;
        $this->nomor_surat = $permohonan->nomor_surat;
        $this->tanggal_surat = \Carbon\Carbon::parse($permohonan->tanggal_surat)->format('Y-m-d');
        
        $this->tanggal_pelaksanaan = $permohonan->tanggal_pelaksanaan ? \Carbon\Carbon::parse($permohonan->tanggal_pelaksanaan)->format('Y-m-d') : '';
        $this->waktu_pelaksanaan = $permohonan->waktu_pelaksanaan;

        $this->klasifikasi = $permohonan->klasifikasi;
        $this->lampiran = $permohonan->lampiran;
        
        $this->penyedia_id = $permohonan->penyedia_id;
        $this->tempat_pengambilan_bbm = $permohonan->tempat_pengambilan_bbm;
        $this->lokasi_pengisian = $permohonan->lokasi_pengisian;
        $this->nomor_spbu = $permohonan->nomor_spbu;
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

        $petugasRecords = DB::table('petugas_surat_tugas')->where('surat_permohonan_id', $id)->get();
        $this->petugasList = [];
        foreach ($petugasRecords as $p) {
            $this->petugasList[] = ['nama_petugas' => $p->nama_petugas, 'jabatan' => $p->jabatan];
        }
        if (empty($this->petugasList)) {
            $this->petugasList[] = ['nama_petugas' => '', 'jabatan' => ''];
        }

        $this->loadLaporanSisaList($this->laporan_sisa_bbm_id);

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'laporan_sisa_bbm_id' => 'required',
            'nomor_surat' => 'nullable|unique:surat_permohonan_pengisians,nomor_surat,' . $this->permohonan_id,
            'tanggal_surat' => 'required|date',
            'tanggal_pelaksanaan' => 'required|date',
            'waktu_pelaksanaan' => 'required|string|max:255',
            'penyedia_id' => 'nullable|exists:users,id',
            'tempat_pengambilan_bbm' => 'nullable|string|max:255',
            'lokasi_pengisian' => 'required|string|max:255',
            'nomor_spbu' => 'nullable|string|max:255',
            'metode_pengiriman' => 'nullable|in:Ambil ditempat,Pengiriman Jalur Darat,Pengiriman Jalur Laut',
            'jumlah_bbm' => 'nullable|numeric|min:0',
            'nama_nakhoda' => 'nullable|string|max:255',
            'id_nakhoda' => 'nullable|string|max:255',
            'nama_pptk' => 'nullable|string|max:255',
            'id_pptk' => 'nullable|string|max:255',
            'petugasList.*.nama_petugas' => 'required',
            'petugasList.*.jabatan' => 'required',
        ],[
            'petugasList.*.nama_petugas.required' => 'Nama petugas wajib diisi.',
            'petugasList.*.jabatan.required' => 'Jabatan petugas wajib diisi.',
            'lokasi_pengisian.required' => 'Lokasi pengisian wajib diisi.',
            'tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan wajib diisi.',
            'waktu_pelaksanaan.required' => 'Waktu pelaksanaan wajib diisi.',
        ]);

        $laporan = LaporanSisaBbm::find($this->laporan_sisa_bbm_id);

        $finalJenisPenyedia = $this->jenis_penyedia_bbm === 'Lainnya' ? $this->jenis_penyedia_bbm_lainnya : $this->jenis_penyedia_bbm;
        $finalJenisBbm = $this->jenis_bbm === 'Lainnya' ? $this->jenis_bbm_lainnya : $this->jenis_bbm;

        $data = [
            'laporan_sisa_bbm_id' => $this->laporan_sisa_bbm_id,
            'ukpd_id' => $laporan ? $laporan->ukpd_id : null,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_surat' => $this->tanggal_surat,
            'tanggal_pelaksanaan' => $this->tanggal_pelaksanaan,
            'waktu_pelaksanaan' => $this->waktu_pelaksanaan,
            'klasifikasi' => $this->klasifikasi,
            'lampiran' => $this->lampiran,
            'penyedia_id' => $this->penyedia_id,
            'jenis_penyedia_bbm' => $finalJenisPenyedia,
            'tempat_pengambilan_bbm' => $this->tempat_pengambilan_bbm,
            'lokasi_pengisian' => $this->lokasi_pengisian, 
            'nomor_spbu' => $this->nomor_spbu, 
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

        DB::beginTransaction();
        try {
            $permohonan = SuratPermohonanPengisian::updateOrCreate(['id' => $this->permohonan_id], $data);

            $st_id = $permohonan->suratTugas?->id;

            DB::table('petugas_surat_tugas')->where('surat_permohonan_id', $permohonan->id)->delete();
            
            $petugasData = [];
            foreach ($this->petugasList as $petugas) {
                $petugasData[] = [
                    'surat_permohonan_id' => $permohonan->id,
                    'surat_tugas_pengisian_id' => $st_id, 
                    'nama_petugas' => $petugas['nama_petugas'],
                    'jabatan' => $petugas['jabatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('petugas_surat_tugas')->insert($petugasData);

            DB::commit();
            session()->flash('message', $this->permohonan_id ? 'Data Surat Berhasil Diperbarui.' : 'Data Surat Berhasil Dibuat.');
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetFields()
    {
        $this->reset([
            'permohonan_id', 'laporan_sisa_bbm_id', 'nomor_surat', 'tanggal_surat', 'tanggal_pelaksanaan', 'waktu_pelaksanaan', 'klasifikasi', 
            'penyedia_id', 'jenis_penyedia_bbm', 'jenis_penyedia_bbm_lainnya', 
            'tempat_pengambilan_bbm', 'lokasi_pengisian', 'nomor_spbu', 'metode_pengiriman', 'jenis_bbm', 'jenis_bbm_lainnya', 'jumlah_bbm',
            'nama_nakhoda', 'id_nakhoda', 'nama_pptk', 'id_pptk', 'petugasList'
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