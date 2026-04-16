<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LaporanPengisianBbm as PengisianBbm;
use App\Models\SuratPermohonanPengisian;
use App\Models\Sounding;
use App\Models\Kapal;
use App\Models\Ukpd;
use Illuminate\Support\Facades\DB;

class LaporanPengisianBBM extends Component
{
    use WithPagination;

    // Master Data
    public $permohonan_list, $kapals, $available_soundings = [];
    
    // Form Properties
    public $laporan_id, $surat_permohonan_id, $surat_tugas_id, $ukpd_id;
    public $tanggal, $dasar_hukum, $lokasi_pengisian;
    public $kegiatan = 'Pengisian BBM KDO Khusus', $kegiatan_lainnya;
    public $tujuan = 'Memastikan ketersediaan BBM Kapal untuk menunjang kegiatan Operasional', $tujuan_lainnya;
    
    // Form Properties - Kalkulasi BBM
    public $sounding_awal_id, $jumlah_bbm_awal = 0;
    public $jumlah_bbm_pengisian = 0;
    public $pemakaian_bbm = 0;
    public $sounding_akhir_id, $jumlah_bbm_akhir = 0;
    
    // Form Properties - Waktu & Dokumentasi
    public $jam_berangkat, $jam_kembali;

    // Filter Properties
    public $search = '', $sortBy = 'latest', $filterKapal = '', $filterUkpd = '';
    public $filterTanggalAwal = '', $filterTanggalAkhir = '';
    public $isOpen = false;

    public function mount()
    {
        $queryPermohonan = SuratPermohonanPengisian::with('suratTugas.LaporanSisaBbm.sounding.kapal')->whereIn('progress', ['on progress', 'done']);
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $queryPermohonan->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $this->permohonan_list = $queryPermohonan->get();

        $queryKapal = Kapal::orderBy('nama_kapal');
        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $queryKapal->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $this->kapals = $queryKapal->get();
        
        $this->dasar_hukum = "1. Undang-Undang Nomor 17 Tahun 2008 tentang Pelayaran;\n2. DPA Dinas Perhubungan Provinsi DKI Jakarta Tahun 2026;";
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

    // Listener otomatis saat memilih surat permohonan
    public function updatedSuratPermohonanId($val)
    {
        if($val) {
            $permohonan = SuratPermohonanPengisian::with('suratTugas.LaporanSisaBbm.sounding.kapal')->find($val);
            if($permohonan) {
                $this->surat_tugas_id = $permohonan->surat_tugas_id;
                $this->ukpd_id = $permohonan->ukpd_id;
                $this->lokasi_pengisian = $permohonan->tempat_pengambilan_bbm ?? ($permohonan->suratTugas->lokasi ?? '');
                $this->jumlah_bbm_pengisian = $permohonan->jumlah_bbm ?? 0;
                
                $kapalId = $permohonan->suratTugas->LaporanSisaBbm->sounding->kapal_id ?? null;
                if($kapalId) {
                    $this->available_soundings = Sounding::where('kapal_id', $kapalId)->latest()->take(10)->get();
                }
            }
        }
        $this->calculateBbm();
    }

    // Listener untuk Sounding Awal
    public function updatedSoundingAwalId($val)
    {
        if($val) {
            $snd = Sounding::find($val);
            $this->jumlah_bbm_awal = $snd ? $snd->bbm_akhir : 0;
        }
        $this->calculateBbm();
    }

    // Listener untuk Pemakaian (Triggers hitung ulang BBM Akhir)
    public function updatedPemakaianBbm()
    {
        $this->calculateBbm();
    }

    public function updatedJumlahBbmAwal()
    {
        $this->calculateBbm();
    }

    // Fungsi Kalkulasi
    private function calculateBbm()
    {
        $awal = floatval($this->jumlah_bbm_awal ?: 0);
        $isi = floatval($this->jumlah_bbm_pengisian ?: 0);
        $pakai = floatval($this->pemakaian_bbm ?: 0);
        $this->jumlah_bbm_akhir = ($awal + $isi) - $pakai;
    }

    public function render()
    {
        $query = PengisianBbm::with(['suratTugas.LaporanSisaBbm.sounding.kapal', 'suratPermohonan', 'soundingAwal', 'soundingAkhir', 'approverNakhoda', 'approverPenyedia']);

        if (auth()->user()?->role?->slug !== 'superadmin' && auth()->user()?->role?->slug !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('lokasi_pengisian', 'like', '%' . $this->search . '%')
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
            $query->whereDate('tanggal', '>=', $this->filterTanggalAwal);
        }
        if (!empty($this->filterTanggalAkhir)) {
            $query->whereDate('tanggal', '<=', $this->filterTanggalAkhir);
        }

        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal');
        } else {
            $query->latest('tanggal');
        }

        return view('livewire.satgas.laporan-pengisian-bbm', [
            'laporans' => $query->paginate(10),
            'ukpds' => Ukpd::orderBy('nama', 'asc')->get()
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $laporan = PengisianBbm::findOrFail($id);
        
        $this->laporan_id = $id;
        $this->ukpd_id = $laporan->ukpd_id;
        $this->surat_permohonan_id = $laporan->surat_permohonan_id;
        
        // Panggil updated function untuk load soundings
        $this->updatedSuratPermohonanId($this->surat_permohonan_id);

        $this->surat_tugas_id = $laporan->surat_tugas_id;
        $this->tanggal = $laporan->tanggal;
        $this->dasar_hukum = $laporan->dasar_hukum;
        $this->lokasi_pengisian = $laporan->lokasi_pengisian;
        
        $this->kegiatan = in_array($laporan->kegiatan, ['Pengisian BBM KDO Khusus']) ? $laporan->kegiatan : 'Lainnya';
        $this->kegiatan_lainnya = $this->kegiatan === 'Lainnya' ? $laporan->kegiatan : '';

        $this->tujuan = in_array($laporan->tujuan, ['Memastikan ketersediaan BBM Kapal untuk menunjang kegiatan Operasional']) ? $laporan->tujuan : 'Lainnya';
        $this->tujuan_lainnya = $this->tujuan === 'Lainnya' ? $laporan->tujuan : '';

        $this->sounding_awal_id = $laporan->sounding_awal_id;
        $this->jumlah_bbm_awal = $laporan->jumlah_bbm_awal;
        $this->jumlah_bbm_pengisian = $laporan->jumlah_bbm_pengisian;
        $this->pemakaian_bbm = $laporan->pemakaian_bbm;
        $this->sounding_akhir_id = $laporan->sounding_akhir_id;
        $this->jumlah_bbm_akhir = $laporan->jumlah_bbm_akhir;
        
        $this->jam_berangkat = \Carbon\Carbon::parse($laporan->jam_berangkat)->format('H:i');
        $this->jam_kembali = \Carbon\Carbon::parse($laporan->jam_kembali)->format('H:i');

        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'surat_permohonan_id' => 'required',
            'tanggal' => 'required|date',
            'lokasi_pengisian' => 'required|string',
            'jumlah_bbm_pengisian' => 'required|numeric|min:0',
        ]);

        $finalKegiatan = $this->kegiatan === 'Lainnya' ? $this->kegiatan_lainnya : $this->kegiatan;
        $finalTujuan = $this->tujuan === 'Lainnya' ? $this->tujuan_lainnya : $this->tujuan;

        $data = [
            'ukpd_id' => $this->ukpd_id,
            'surat_tugas_id' => $this->surat_tugas_id,
            'surat_permohonan_id' => $this->surat_permohonan_id,
            'tanggal' => $this->tanggal,
            'dasar_hukum' => $this->dasar_hukum,
            'lokasi_pengisian' => $this->lokasi_pengisian,
            'kegiatan' => $finalKegiatan,
            'tujuan' => $finalTujuan,
            'sounding_awal_id' => $this->sounding_awal_id ?: null,
            'jumlah_bbm_awal' => $this->jumlah_bbm_awal ?: 0,
            'jumlah_bbm_pengisian' => $this->jumlah_bbm_pengisian,
            'pemakaian_bbm' => $this->pemakaian_bbm ?: 0,
            'sounding_akhir_id' => $this->sounding_akhir_id ?: null,
            'jumlah_bbm_akhir' => $this->jumlah_bbm_akhir ?: 0,
            'jam_berangkat' => $this->jam_berangkat,
            'jam_kembali' => $this->jam_kembali,
        ];

        if (!$this->laporan_id) {
            $data['user_id'] = auth()->id();
        }

        PengisianBbm::updateOrCreate(['id' => $this->laporan_id], $data);

        session()->flash('message', $this->laporan_id ? 'Laporan Diperbarui.' : 'Laporan Dibuat.');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetFields()
    {
        $this->reset([
            'laporan_id', 'surat_permohonan_id', 'surat_tugas_id', 'ukpd_id', 
            'tanggal', 'lokasi_pengisian', 'kegiatan_lainnya', 'tujuan_lainnya',
            'sounding_awal_id', 'jumlah_bbm_awal', 'jumlah_bbm_pengisian', 'pemakaian_bbm', 'sounding_akhir_id', 'jumlah_bbm_akhir',
            'jam_berangkat', 'jam_kembali', 'available_soundings'
        ]);
        $this->kegiatan = 'Pengisian BBM KDO Khusus';
        $this->tujuan = 'Memastikan ketersediaan BBM Kapal untuk menunjang kegiatan Operasional';
        $this->dasar_hukum = "1. Undang-Undang Nomor 17 Tahun 2008 tentang Pelayaran;\n2. DPA Dinas Perhubungan Provinsi DKI Jakarta Tahun 2026;";
    }

    public function delete($id)
    {
        $laporan = PengisianBbm::findOrFail($id);
        $laporan->delete();
        session()->flash('message', 'Laporan Berhasil Dihapus.');
    }

    public function approveNakhoda($id)
    {
        $laporan = PengisianBbm::findOrFail($id);
        $laporan->update([
            'disetujui_nakhoda_by' => auth()->id(),
            'disetujui_nakhoda_at' => now(),
        ]);
        session()->flash('message', 'Laporan berhasil disetujui oleh Nakhoda.');
    }

    public function approvePenyedia($id)
    {
        $laporan = PengisianBbm::findOrFail($id);
        $laporan->update([
            'disetujui_penyedia_by' => auth()->id(),
            'disetujui_penyedia_at' => now(),
        ]);
        session()->flash('message', 'Laporan berhasil disetujui oleh Penyedia BBM.');
    }
}