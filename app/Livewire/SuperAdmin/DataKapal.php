<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Kapal;
use App\Models\Ukpd;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DataKapal extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Properti Form Modal
    public $kapal_id;
    public $nama_kapal, $ukpd_id, $nahkoda_id, $jenis_dan_tipe, $material, $tahun_pembuatan, $ukuran, $tonase_kotor_gt, $tenaga_penggerak_kw, $daerah_pelayaran, $list_sertifikat_kapal;
    
    // Properti Gambar
    public $foto_kapal;
    public $old_foto_kapal;

    public $isModalOpen = false;

    // Properti Search, Filter, dan Sort
    public $search = '';
    public $sortBy = 'latest';
    public $filterUkpd = '';
    public $filterJenis = '';
    public $filterMaterial = '';
    public $filterTahun = '';
    public $filterTonase = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); }
    public function updatingFilterJenis() { $this->resetPage(); }
    public function updatingFilterMaterial() { $this->resetPage(); }
    public function updatingFilterTahun() { $this->resetPage(); }
    public function updatingFilterTonase() { $this->resetPage(); }

    public function render()
    {
        $query = Kapal::with(['user', 'ukpd', 'nahkoda']);

        if (auth()->user()?->role?->slug !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()?->ukpd_id);
        }

        if (auth()->user()?->role?->slug == 'nahkoda') {
            $query->where('nahkoda_id', auth()->user()?->id);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_kapal', 'like', '%' . $this->search . '%')
                  ->orWhereHas('ukpd', function($qUkpd) {
                      $qUkpd->where('nama', 'like', '%' . $this->search . '%')
                            ->orWhere('singkatan', 'like', '%' . $this->search . '%');
                  })
                  ->orWhere('jenis_dan_tipe', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterUkpd) { $query->where('ukpd_id', $this->filterUkpd); }
        if ($this->filterJenis) { $query->where('jenis_dan_tipe', $this->filterJenis); }
        if ($this->filterMaterial) { $query->where('material', $this->filterMaterial); }
        if ($this->filterTahun) { $query->where('tahun_pembuatan', $this->filterTahun); }
        if ($this->filterTonase) { $query->where('tonase_kotor_gt', $this->filterTonase); }

        match($this->sortBy) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'name_asc' => $query->orderBy('nama_kapal', 'asc'),
            'name_desc' => $query->orderBy('nama_kapal', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $kapals = $query->paginate(10);

        $ukpds = Ukpd::orderBy('nama', 'asc')->get(); 
        
        $usedNahkodaIds = Kapal::whereNotNull('nahkoda_id')
            ->when($this->kapal_id, function($query) {
                $query->where('id', '!=', $this->kapal_id);
            })
            ->pluck('nahkoda_id')
            ->toArray();

        $nahkodas = User::whereHas('role', function($q) {
            $q->where('slug', 'nahkoda');
        })
        ->whereNotIn('id', $usedNahkodaIds)
        ->orderBy('name', 'asc')
        ->get();

        $jenisList = Kapal::whereNotNull('jenis_dan_tipe')->where('jenis_dan_tipe', '!=', '')->distinct()->pluck('jenis_dan_tipe');
        $materials = Kapal::whereNotNull('material')->where('material', '!=', '')->distinct()->pluck('material');
        $tahunList = Kapal::whereNotNull('tahun_pembuatan')->where('tahun_pembuatan', '!=', '')->distinct()->orderBy('tahun_pembuatan', 'desc')->pluck('tahun_pembuatan');
        $tonaseList = Kapal::whereNotNull('tonase_kotor_gt')->where('tonase_kotor_gt', '!=', '')->distinct()->orderBy('tonase_kotor_gt', 'asc')->pluck('tonase_kotor_gt');

        return view('livewire.super-admin.data-kapal', [
            'kapals' => $kapals,
            'ukpds' => $ukpds,
            'nahkodas' => $nahkodas,
            'jenisList' => $jenisList,
            'materials' => $materials,
            'tahunList' => $tahunList,
            'tonaseList' => $tonaseList,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->kapal_id = '';
        $this->nama_kapal = '';
        $this->ukpd_id = ''; 
        $this->nahkoda_id = ''; 
        $this->jenis_dan_tipe = '';
        $this->material = '';
        $this->tahun_pembuatan = '';
        $this->ukuran = '';
        $this->tonase_kotor_gt = '';
        $this->tenaga_penggerak_kw = '';
        $this->daerah_pelayaran = '';
        $this->list_sertifikat_kapal = '';
        
        $this->foto_kapal = null;
        $this->old_foto_kapal = null;
    }

    public function store()
    {
        $this->validate([
            'nama_kapal' => 'required',
            'ukpd_id' => 'required', 
            'foto_kapal' => 'nullable|image|max:2048', 
        ]);

        $data = [
            'nama_kapal' => $this->nama_kapal,
            'ukpd_id' => $this->ukpd_id, 
            'nahkoda_id' => empty($this->nahkoda_id) ? null : $this->nahkoda_id, 
            'jenis_dan_tipe' => $this->jenis_dan_tipe,
            'material' => $this->material,
            'tahun_pembuatan' => $this->tahun_pembuatan,
            'ukuran' => $this->ukuran,
            'tonase_kotor_gt' => $this->tonase_kotor_gt,
            'tenaga_penggerak_kw' => $this->tenaga_penggerak_kw,
            'daerah_pelayaran' => $this->daerah_pelayaran,
            'list_sertifikat_kapal' => $this->list_sertifikat_kapal,
            'user_id' => auth()->id(),
        ];

        if ($this->foto_kapal) {
            if ($this->kapal_id && $this->old_foto_kapal) {
                Storage::disk('public')->delete($this->old_foto_kapal);
            }
            $data['foto_kapal'] = $this->foto_kapal->store('kapals', 'public');
        }

        Kapal::updateOrCreate(['id' => $this->kapal_id], $data);

        session()->flash('message', $this->kapal_id ? 'Data Kapal Berhasil Diperbarui.' : 'Data Kapal Berhasil Ditambahkan.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $kapal = Kapal::findOrFail($id);
        
        $this->kapal_id = $id;
        $this->nama_kapal = $kapal->nama_kapal;
        $this->ukpd_id = $kapal->ukpd_id; 
        $this->nahkoda_id = $kapal->nahkoda_id; 
        $this->jenis_dan_tipe = $kapal->jenis_dan_tipe;
        $this->material = $kapal->material;
        $this->tahun_pembuatan = $kapal->tahun_pembuatan;
        $this->ukuran = $kapal->ukuran;
        $this->tonase_kotor_gt = $kapal->tonase_kotor_gt;
        $this->tenaga_penggerak_kw = $kapal->tenaga_penggerak_kw;
        $this->daerah_pelayaran = $kapal->daerah_pelayaran;
        $this->list_sertifikat_kapal = $kapal->list_sertifikat_kapal;
        
        $this->old_foto_kapal = $kapal->foto_kapal;

        $this->openModal();
    }

    public function delete($id)
    {
        if(auth()->user()?->role?->slug == 'nahkoda') {
            return;
        }
        $kapal = Kapal::find($id);
        
        if ($kapal->foto_kapal) {
            Storage::disk('public')->delete($kapal->foto_kapal);
        }
        
        $kapal->delete();
        session()->flash('message', 'Data Kapal Berhasil Dihapus.');
    }
}