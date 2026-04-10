<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SuratTugasPengisian;
use App\Models\Kapal;
use App\Models\LaporanSisaBbm;
use App\Models\Ukpd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuratTugasPengisianBBM extends Component
{
    use WithPagination;

    // Properti Form Modal
    public $surat_id, $laporan_pengisian_id, $nomor_surat, $lokasi, $waktu_pelaksanaan, $tanggal_dikeluarkan;
    public $petugasList = [];
    public $isOpen = false;

    // Properti Search, Filter & Sort
    public $search = '';
    public $sortBy = 'latest';
    public $filterKapal = '';
    public $filterUkpd = ''; 
    public $filterTanggalDari = '';
    public $filterTanggalSampai = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); } 
    public function updatingFilterTanggalDari() { $this->resetPage(); }
    public function updatingFilterTanggalSampai() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterKapal = '';
        $this->filterUkpd = '';
        $this->filterTanggalDari = '';
        $this->filterTanggalSampai = '';
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $query = SuratTugasPengisian::with(['LaporanSisaBbm.sounding.kapal', 'user', 'petugas']);

        if (auth()->user()->role !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $this->search . '%') // Pencarian Lokasi
                  ->orWhereHas('petugas', function($p) {
                      $p->where('nama_petugas', 'like', '%' . $this->search . '%'); // Pencarian Nama Petugas
                  })
                  ->orWhereHas('LaporanSisaBbm', function($l) {
                      $l->where('keterangan', 'like', '%' . $this->search . '%') 
                        ->orWhereHas('kapal', function($k) {
                            $k->where('nama_kapal', 'like', '%' . $this->search . '%');
                        });
                  });
            });
        }

        if ($this->filterKapal) {
            $query->whereHas('LaporanSisaBbm', function($q) {
                $q->whereHas('sounding', function($s) {
                    $s->where('kapal_id', $this->filterKapal);
                });
            });
        }

        if ($this->filterUkpd) {
            $query->where('ukpd_id', $this->filterUkpd);
        }
        
        if ($this->filterTanggalDari) {
            $query->whereDate('tanggal_dikeluarkan', '>=', $this->filterTanggalDari);
        }
        if ($this->filterTanggalSampai) {
            $query->whereDate('tanggal_dikeluarkan', '<=', $this->filterTanggalSampai);
        }

        match($this->sortBy) {
            'oldest' => $query->orderBy('tanggal_dikeluarkan', 'asc'),
            default => $query->orderBy('tanggal_dikeluarkan', 'desc'), 
        };

        $surat_tugas = $query->paginate(10);
        
        $kapals = Kapal::query();
        if (auth()->user()->role !== 'superadmin') {
            $kapals->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $kapals = $kapals->orderBy('nama_kapal', 'asc')->get();

        $ukpds = Ukpd::orderBy('nama', 'asc')->get();
        
        $queryLaporan = LaporanSisaBbm::with('sounding.kapal')->latest();
        if (auth()->user()->role !== 'superadmin') {
            $queryLaporan->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $laporans = $queryLaporan->get();

        return view('livewire.satgas.surat-tugas-pengisian-bbm', [
            'surat_tugas' => $surat_tugas,
            'laporans' => $laporans,
            'kapals' => $kapals,
            'ukpds' => $ukpds
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->waktu_pelaksanaan = '08:00 - Selesai';
        $this->tanggal_dikeluarkan = date('Y-m-d');
        
        $this->petugasList = [
            ['nama_petugas' => '', 'jabatan' => 'Supir'],
            ['nama_petugas' => '', 'jabatan' => 'Pendamping']
        ];
        
        $this->openModal();
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

    public function openModal() { $this->isOpen = true; }

    public function closeModal() { 
        $this->isOpen = false; 
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->surat_id = '';
        $this->laporan_pengisian_id = '';
        $this->nomor_surat = '';
        $this->lokasi = '';
        $this->waktu_pelaksanaan = '';
        $this->tanggal_dikeluarkan = '';
        $this->petugasList = [];
    }

    public function store()
    {
        $this->validate([
            'laporan_pengisian_id' => 'required',
            'nomor_surat' => 'required',
            'lokasi' => 'required',
            'waktu_pelaksanaan' => 'required',
            'tanggal_dikeluarkan' => 'required|date',
            'petugasList.*.nama_petugas' => 'required',
            'petugasList.*.jabatan' => 'required',
        ],[
            'petugasList.*.nama_petugas.required' => 'Nama petugas wajib diisi.',
            'petugasList.*.jabatan.required' => 'Jabatan petugas wajib diisi.',
        ]);

        $laporanTerkait = LaporanSisaBbm::find($this->laporan_pengisian_id);

        $data = [
            'laporan_sisa_bbm_id' => $this->laporan_pengisian_id,
            'ukpd_id' => $laporanTerkait ? $laporanTerkait->ukpd_id : null,
            'nomor_surat' => $this->nomor_surat,
            'lokasi' => $this->lokasi,
            'waktu_pelaksanaan' => $this->waktu_pelaksanaan,
            'tanggal_dikeluarkan' => $this->tanggal_dikeluarkan,
        ];

        if (!$this->surat_id) {
            $data['user_id'] = auth()->id();
        }

        DB::beginTransaction();
        try {
            $surat = SuratTugasPengisian::updateOrCreate(['id' => $this->surat_id], $data);

            DB::table('petugas_surat_tugas')->where('surat_tugas_pengisian_id', $surat->id)->delete();
            
            $petugasData = [];
            foreach ($this->petugasList as $petugas) {
                $petugasData[] = [
                    'surat_tugas_pengisian_id' => $surat->id,
                    'nama_petugas' => $petugas['nama_petugas'],
                    'jabatan' => $petugas['jabatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('petugas_surat_tugas')->insert($petugasData);

            DB::commit();
            session()->flash('message', $this->surat_id ? 'Surat Tugas diperbarui.' : 'Surat Tugas dibuat.');
            $this->closeModal();
            $this->resetInputFields();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $query = SuratTugasPengisian::query();
        
        if (auth()->user()->role !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $surat = $query->findOrFail($id);
        
        $this->surat_id = $id;
        $this->laporan_pengisian_id = $surat->laporan_sisa_bbm_id ?? $surat->laporan_pengisian_id; 
        $this->nomor_surat = $surat->nomor_surat;
        $this->lokasi = $surat->lokasi;
        $this->waktu_pelaksanaan = $surat->waktu_pelaksanaan;
        $this->tanggal_dikeluarkan = \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->format('Y-m-d');
        
        $petugasRecords = DB::table('petugas_surat_tugas')
                            ->where('surat_tugas_pengisian_id', $surat->id)
                            ->get();
        
        $this->petugasList = [];
        foreach ($petugasRecords as $p) {
            $this->petugasList[] = [
                'nama_petugas' => $p->nama_petugas,
                'jabatan' => $p->jabatan
            ];
        }

        if (empty($this->petugasList)) {
            $this->petugasList[] = ['nama_petugas' => '', 'jabatan' => ''];
        }

        $this->openModal();
    }

    public function delete($id)
    {
        $query = SuratTugasPengisian::query();
        
        if (auth()->user()->role !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $query->findOrFail($id)->delete();
        
        session()->flash('message', 'Surat Tugas dihapus.');
    }
}