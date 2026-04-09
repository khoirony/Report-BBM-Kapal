<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SuratPermohonanPengisian;
use App\Models\SuratTugasPengisian;
use App\Models\Kapal;
use App\Models\Ukpd; // Tambahkan ini
use App\Models\FileSuratPermohonan;
use Illuminate\Support\Facades\DB;

class SuratPermohonanPengisianBBM extends Component
{
    use WithPagination, WithFileUploads;

    public $surat_tugas_list, $kapals;
    public $permohonan_id, $surat_tugas_id, $nomor_surat, $tanggal_surat, $klasifikasi, $lampiran;
    
    // Properti khusus Progress & File
    public $berkas; 
    public $progress = 'not started'; 

    // Kontrol Modal
    public $isModalOpen = false;
    public $isProgressModalOpen = false;

    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    public $filterUkpd = ''; // Tambahan properti filter UKPD
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';

    public function mount()
    {
        // Menampilkan pilihan Surat Tugas sesuai UKPD
        $queryTugas = SuratTugasPengisian::with('LaporanSisaBbm.sounding.kapal', 'user');
        if (auth()->user()->role !== 'superadmin' && auth()->user()->role !== 'penyedia') {
            $queryTugas->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $this->surat_tugas_list = $queryTugas->get();

        // Menampilkan pilihan Kapal sesuai UKPD
        $queryKapal = Kapal::orderBy('nama_kapal');
        if (auth()->user()->role !== 'superadmin' && auth()->user()->role !== 'penyedia') {
            $queryKapal->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $this->kapals = $queryKapal->get();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); } // Reset saat filter UKPD diubah
    public function updatingFilterTanggalAwal() { $this->resetPage(); }
    public function updatingFilterTanggalAkhir() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'filterKapal', 'filterUkpd', 'filterTanggalAwal', 'filterTanggalAkhir']);
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $query = SuratPermohonanPengisian::with([
            'suratTugas.LaporanSisaBbm.sounding.kapal',
            'files' 
        ]);

        // Batasi tampilan tabel utama berdasarkan ukpd_id untuk selain superadmin & penyedia
        if (auth()->user()->role !== 'superadmin' && auth()->user()->role !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
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

        // Terapkan Filter UKPD
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
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        
        $query = SuratPermohonanPengisian::query();
        if (auth()->user()->role !== 'superadmin' && auth()->user()->role !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        
        $permohonan = $query->findOrFail($id);
        
        $this->permohonan_id = $id;
        $this->surat_tugas_id = $permohonan->surat_tugas_id;
        $this->nomor_surat = $permohonan->nomor_surat;
        $this->tanggal_surat = \Carbon\Carbon::parse($permohonan->tanggal_surat)->format('Y-m-d');
        $this->klasifikasi = $permohonan->klasifikasi;
        $this->lampiran = $permohonan->lampiran;

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'surat_tugas_id' => 'required',
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
        ]);

        $suratTugas = SuratTugasPengisian::find($this->surat_tugas_id);

        $data = [
            'surat_tugas_id' => $this->surat_tugas_id,
            'ukpd_id' => $suratTugas ? $suratTugas->ukpd_id : null, // Membawa UKPD ID dari Surat Tugas
            'nomor_surat' => $this->nomor_surat,
            'tanggal_surat' => $this->tanggal_surat,
            'klasifikasi' => $this->klasifikasi,
            'lampiran' => $this->lampiran,
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

    public function openProgressModal($id)
    {
        $this->reset(['berkas']); 
        
        $query = SuratPermohonanPengisian::query();
        if (auth()->user()->role !== 'superadmin' && auth()->user()->role !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        
        $permohonan = $query->findOrFail($id);
        
        $this->permohonan_id = $permohonan->id;
        $this->progress = $permohonan->progress;
        
        $this->isProgressModalOpen = true;
    }

    public function closeProgressModal()
    {
        $this->isProgressModalOpen = false;
        $this->reset(['berkas', 'permohonan_id', 'progress']);
    }

    public function updateProgress()
    {
        $this->validate([
            'progress' => 'required|in:not started,on progress,done',
            'berkas' => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:2048',
        ]);

        $permohonan = SuratPermohonanPengisian::findOrFail($this->permohonan_id);

        DB::transaction(function () use ($permohonan) {
            if ($this->berkas) {
                $path = $this->berkas->store('berkas_surat', 'public');

                FileSuratPermohonan::create([
                    'surat_permohonan_id' => $permohonan->id,
                    'nama_file' => $this->berkas->getClientOriginalName(),
                    'file_path' => $path,
                ]);

                if ($this->progress === 'not started') {
                    $this->progress = 'on progress';
                }
            }

            $permohonan->update([
                'progress' => $this->progress
            ]);
        });

        session()->flash('message', 'Progress dan File berhasil diperbarui.');
        $this->closeProgressModal();
    }

    public function resetFields()
    {
        $this->reset(['permohonan_id', 'surat_tugas_id', 'nomor_surat', 'tanggal_surat', 'klasifikasi', 'lampiran', 'berkas', 'progress']);
        $this->lampiran = '1 (satu) berkas';
    }

    public function delete($id)
    {
        $query = SuratPermohonanPengisian::query();
        if (auth()->user()->role !== 'superadmin' && auth()->user()->role !== 'penyedia') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        
        $query->findOrFail($id)->delete();
        session()->flash('message', 'Surat Permohonan Berhasil Dihapus.');
    }
}