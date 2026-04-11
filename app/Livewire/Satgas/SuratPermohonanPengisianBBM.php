<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SuratPermohonanPengisian;
use App\Models\SuratTugasPengisian;
use App\Models\Kapal;
use App\Models\Ukpd; 
use Illuminate\Support\Facades\DB;

class SuratPermohonanPengisianBBM extends Component
{
    use WithPagination;

    public $surat_tugas_list, $kapals;
    public $permohonan_id, $surat_tugas_id, $nomor_surat, $tanggal_surat, $klasifikasi, $lampiran;
    
    // Properti Baru (Update Kolom BBM)
    public $nama_perusahaan, $jenis_penyedia_bbm, $tempat_pengambilan_bbm, $metode_pengiriman, $jenis_bbm, $jumlah_bbm;
    
    // Properti Khusus "Lainnya"
    public $jenis_penyedia_bbm_lainnya = '';
    public $jenis_bbm_lainnya = '';

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

    public function render()
    {
        $query = SuratPermohonanPengisian::with([
            'suratTugas.LaporanSisaBbm.sounding.kapal'
        ]);

        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_perusahaan', 'like', '%' . $this->search . '%')
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

        return view('livewire.satgas.surat-permohonan-pengisian-bbm', [
            'permohonans' => $query->paginate(10),
            'ukpds' => $ukpds
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
        
        $this->nama_perusahaan = $permohonan->nama_perusahaan;
        $this->tempat_pengambilan_bbm = $permohonan->tempat_pengambilan_bbm;
        $this->metode_pengiriman = $permohonan->metode_pengiriman;
        $this->jumlah_bbm = $permohonan->jumlah_bbm;
        
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
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'nama_perusahaan' => 'nullable|string|max:255',
            'tempat_pengambilan_bbm' => 'nullable|string|max:255',
            'metode_pengiriman' => 'nullable|in:Ambil ditempat,Pengiriman Jalur Darat,Pengiriman Jalur Laut',
            'jumlah_bbm' => 'nullable|numeric|min:0',
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
            'nama_perusahaan' => $this->nama_perusahaan,
            'jenis_penyedia_bbm' => $finalJenisPenyedia,
            'tempat_pengambilan_bbm' => $this->tempat_pengambilan_bbm,
            'metode_pengiriman' => $this->metode_pengiriman,
            'jenis_bbm' => $finalJenisBbm,
            'jumlah_bbm' => $this->jumlah_bbm ? str_replace(',', '.', $this->jumlah_bbm) : null,
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
            'nama_perusahaan', 'jenis_penyedia_bbm', 'jenis_penyedia_bbm_lainnya', 
            'tempat_pengambilan_bbm', 'metode_pengiriman', 'jenis_bbm', 'jenis_bbm_lainnya', 'jumlah_bbm'
        ]);
        $this->lampiran = '1 (satu) berkas';
    }

    public function delete($id)
    {
        $query = SuratPermohonanPengisian::query();
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        
        $query->findOrFail($id)->delete();
        session()->flash('message', 'Surat Permohonan Berhasil Dihapus.');
    }
}