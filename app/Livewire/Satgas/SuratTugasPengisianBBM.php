<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratTugasPengisian;
use App\Models\Kapal;
use App\Models\SuratPermohonanPengisian;
use App\Models\Ukpd;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuratTugasPengisianBBM extends Component
{
    use WithPagination, WithFileUploads;

    public $surat_id, $surat_permohonan_id, $nomor_surat, $lokasi, $pakaian, $tanggal_pelaksanaan, $waktu_pelaksanaan, $tanggal_surat;
    
    public $nama_kepala_ukpd, $id_kepala_ukpd;

    public $petugasList = [];
    public $isOpen = false;
    
    public $permohonanList = [];
    public $upload_files = [];

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

    public function updatedUploadFiles($value, $key)
    {
        $this->validate([
            "upload_files.{$key}" => 'required|mimes:pdf,jpg,jpeg,png|max:10120',
        ]);

        $suratTugas = SuratTugasPengisian::findOrFail($key);

        if ($suratTugas->file_surat_tugas && Storage::disk('public')->exists($suratTugas->file_surat_tugas)) {
            Storage::disk('public')->delete($suratTugas->file_surat_tugas);
        }

        $path = $value->store('uploads/surat_tugas', 'public');
        $suratTugas->update(['file_surat_tugas' => $path]);

        unset($this->upload_files[$key]);

        session()->flash('message', 'Dokumen Surat Tugas berhasil diupload!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterKapal', 'filterUkpd', 'filterTanggalDari', 'filterTanggalSampai', 'sortBy']);
        $this->resetPage();
    }

    public function loadPermohonanList($currentPermohonanId = null)
    {
        $usedPermohonanIds = SuratTugasPengisian::whereNotNull('surat_permohonan_id')
            ->pluck('surat_permohonan_id')
            ->toArray();

        if ($currentPermohonanId) {
            $usedPermohonanIds = array_diff($usedPermohonanIds, [$currentPermohonanId]);
        }

        $queryPermohonan = SuratPermohonanPengisian::with('LaporanSisaBbm.sounding.kapal')->latest();
        
        if (auth()->user()?->role?->slug !== 'superadmin') {
            $queryPermohonan->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if (!empty($usedPermohonanIds)) {
            $queryPermohonan->whereNotIn('id', $usedPermohonanIds);
        }

        $this->permohonanList = $queryPermohonan->get();
    }

    public function render()
    {
        $query = SuratTugasPengisian::with(['suratPermohonan.LaporanSisaBbm.sounding.kapal', 'user', 'petugas']);

        if (auth()->user()?->role?->slug !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $this->search . '%') 
                  ->orWhereHas('petugas', function($p) {
                      $p->where('nama_petugas', 'like', '%' . $this->search . '%'); 
                  })
                  ->orWhereHas('suratPermohonan', function($sp) {
                      $sp->where('nomor_surat', 'like', '%' . $this->search . '%')
                         ->orWhereHas('LaporanSisaBbm.sounding.kapal', function($k) {
                             $k->where('nama_kapal', 'like', '%' . $this->search . '%');
                         });
                  });
            });
        }

        if ($this->filterKapal) {
            $query->whereHas('suratPermohonan.LaporanSisaBbm.sounding', function($s) {
                $s->where('kapal_id', $this->filterKapal);
            });
        }

        if ($this->filterUkpd) {
            $query->where('ukpd_id', $this->filterUkpd);
        }
        
        if ($this->filterTanggalDari) {
            $query->whereDate('tanggal_surat', '>=', $this->filterTanggalDari);
        }
        if ($this->filterTanggalSampai) {
            $query->whereDate('tanggal_surat', '<=', $this->filterTanggalSampai);
        }

        match($this->sortBy) {
            'oldest' => $query->orderBy('tanggal_surat', 'asc'),
            default => $query->orderBy('tanggal_surat', 'desc'), 
        };

        $surat_tugas = $query->paginate(10);
        
        $kapals = Kapal::query();
        if (auth()->user()?->role?->slug !== 'superadmin') {
            $kapals->where('ukpd_id', auth()->user()?->ukpd_id);
        }
        $kapals = $kapals->orderBy('nama_kapal', 'asc')->get();

        $ukpds = Ukpd::orderBy('nama', 'asc')->get();

        $kepala_users = User::whereHas('role', function($q) {
            $q->where('slug', 'kepala_ukpd'); 
        })->get(['id', 'name', 'nip']);

        return view('livewire.satgas.surat-tugas-pengisian-bbm', [
            'surat_tugas' => $surat_tugas,
            'kapals' => $kapals,
            'ukpds' => $ukpds,
            'kepala_users' => $kepala_users
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->tanggal_pelaksanaan = date('Y-m-d'); 
        $this->waktu_pelaksanaan = '08:00 - Selesai';
        $this->tanggal_surat = date('Y-m-d');
        
        $this->petugasList = [
            ['nama_petugas' => '', 'jabatan' => 'Nakhoda'],
            ['nama_petugas' => '', 'jabatan' => 'KKM'],
            ['nama_petugas' => '', 'jabatan' => 'ABK']
        ];
        
        $this->loadPermohonanList();
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
        $this->surat_permohonan_id = '';
        $this->nomor_surat = '';
        $this->lokasi = '';
        $this->pakaian = '';
        $this->tanggal_pelaksanaan = ''; 
        $this->waktu_pelaksanaan = '';
        $this->tanggal_surat = '';
        
        $this->nama_kepala_ukpd = '';
        $this->id_kepala_ukpd = '';
        
        $this->petugasList = [];
    }

    public function store()
    {
        $this->validate([
            'surat_permohonan_id' => 'required',
            'nomor_surat' => 'nullable|unique:surat_tugas_pengisians,nomor_surat,' . $this->surat_id,
            'lokasi' => 'required',
            'pakaian' => 'required',
            'tanggal_pelaksanaan' => 'required|date', 
            'waktu_pelaksanaan' => 'required',
            'tanggal_surat' => 'required|date',
            'nama_kepala_ukpd' => 'required|string', 
            'id_kepala_ukpd' => 'nullable|string',   
            'petugasList.*.nama_petugas' => 'required',
            'petugasList.*.jabatan' => 'required',
        ],[
            'petugasList.*.nama_petugas.required' => 'Nama petugas wajib diisi.',
            'petugasList.*.jabatan.required' => 'Jabatan petugas wajib diisi.',
            'nama_kepala_ukpd.required' => 'Nama Kepala UKPD wajib diisi.',
            'surat_permohonan_id.required' => 'Surat Permohonan wajib dipilih.'
        ]);

        $permohonanTerkait = SuratPermohonanPengisian::find($this->surat_permohonan_id);

        $data = [
            'surat_permohonan_id' => $this->surat_permohonan_id,
            'ukpd_id' => $permohonanTerkait ? $permohonanTerkait->ukpd_id : null,
            'nomor_surat' => $this->nomor_surat,
            'lokasi' => $this->lokasi,
            'pakaian' => $this->pakaian,
            'tanggal_pelaksanaan' => $this->tanggal_pelaksanaan,
            'waktu_pelaksanaan' => $this->waktu_pelaksanaan,
            'tanggal_surat' => $this->tanggal_surat,
            'nama_kepala_ukpd' => $this->nama_kepala_ukpd, 
            'id_kepala_ukpd' => $this->id_kepala_ukpd,     
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
        
        if (auth()->user()?->role?->slug !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $surat = $query->findOrFail($id);
        
        $this->surat_id = $id;
        $this->surat_permohonan_id = $surat->surat_permohonan_id; 
        $this->nomor_surat = $surat->nomor_surat;
        $this->lokasi = $surat->lokasi;
        $this->pakaian = $surat->pakaian;
        $this->tanggal_pelaksanaan = $surat->tanggal_pelaksanaan ? \Carbon\Carbon::parse($surat->tanggal_pelaksanaan)->format('Y-m-d') : '';
        $this->waktu_pelaksanaan = $surat->waktu_pelaksanaan;
        $this->tanggal_surat = \Carbon\Carbon::parse($surat->tanggal_surat)->format('Y-m-d');
        
        $this->nama_kepala_ukpd = $surat->nama_kepala_ukpd;
        $this->id_kepala_ukpd = $surat->id_kepala_ukpd;
        
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

        $this->loadPermohonanList($this->surat_permohonan_id);

        $this->openModal();
    }

    public function delete($id)
    {
        $query = SuratTugasPengisian::query();
        
        if (auth()->user()?->role?->slug !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        $suratTugas = $query->findOrFail($id);
        
        if ($suratTugas->file_surat_tugas && Storage::disk('public')->exists($suratTugas->file_surat_tugas)) {
            Storage::disk('public')->delete($suratTugas->file_surat_tugas);
        }

        $suratTugas->delete();
        
        session()->flash('message', 'Surat Tugas dihapus.');
    }
}