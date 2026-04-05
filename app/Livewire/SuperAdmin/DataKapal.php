<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Kapal;
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
    public $nama_kapal, $skpd_ukpd, $jenis_dan_tipe, $material, $tahun_pembuatan, $ukuran, $tonase_kotor_gt, $tenaga_penggerak_kw, $daerah_pelayaran, $list_sertifikat_kapal;
    
    // Properti Gambar
    public $foto_kapal;
    public $old_foto_kapal;

    public $isModalOpen = false;

    // Properti Search, Filter, dan Sort
    public $search = '';
    public $sortBy = 'latest';
    public $filterSkpd = '';
    public $filterJenis = '';
    public $filterMaterial = '';
    public $filterTahun = '';
    public $filterTonase = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }
    public function updatingFilterSkpd() { $this->resetPage(); }
    public function updatingFilterJenis() { $this->resetPage(); }
    public function updatingFilterMaterial() { $this->resetPage(); }
    public function updatingFilterTahun() { $this->resetPage(); }
    public function updatingFilterTonase() { $this->resetPage(); }

    public function render()
    {
        $query = Kapal::with('user');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_kapal', 'like', '%' . $this->search . '%')
                  ->orWhere('skpd_ukpd', 'like', '%' . $this->search . '%')
                  ->orWhere('jenis_dan_tipe', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterSkpd) { $query->where('skpd_ukpd', $this->filterSkpd); }
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

        $skpds = Kapal::whereNotNull('skpd_ukpd')->where('skpd_ukpd', '!=', '')->distinct()->pluck('skpd_ukpd');
        $jenisList = Kapal::whereNotNull('jenis_dan_tipe')->where('jenis_dan_tipe', '!=', '')->distinct()->pluck('jenis_dan_tipe');
        $materials = Kapal::whereNotNull('material')->where('material', '!=', '')->distinct()->pluck('material');
        $tahunList = Kapal::whereNotNull('tahun_pembuatan')->where('tahun_pembuatan', '!=', '')->distinct()->orderBy('tahun_pembuatan', 'desc')->pluck('tahun_pembuatan');
        $tonaseList = Kapal::whereNotNull('tonase_kotor_gt')->where('tonase_kotor_gt', '!=', '')->distinct()->orderBy('tonase_kotor_gt', 'asc')->pluck('tonase_kotor_gt');

        return view('livewire.super-admin.data-kapal', [
            'kapals' => $kapals,
            'skpds' => $skpds,
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
        $this->skpd_ukpd = '';
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
            'skpd_ukpd' => 'required',
            'foto_kapal' => 'nullable|image|max:2048', 
        ]);

        $data = [
            'nama_kapal' => $this->nama_kapal,
            'skpd_ukpd' => $this->skpd_ukpd,
            'jenis_dan_tipe' => $this->jenis_dan_tipe,
            'material' => $this->material,
            'tahun_pembuatan' => $this->tahun_pembuatan,
            'ukuran' => $this->ukuran,
            'tonase_kotor_gt' => $this->tonase_kotor_gt,
            'tenaga_penggerak_kw' => $this->tenaga_penggerak_kw,
            'daerah_pelayaran' => $this->daerah_pelayaran,
            'list_sertifikat_kapal' => $this->list_sertifikat_kapal,
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
        $this->skpd_ukpd = $kapal->skpd_ukpd;
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
        $kapal = Kapal::find($id);
        
        if ($kapal->foto_kapal) {
            Storage::disk('public')->delete($kapal->foto_kapal);
        }
        
        $kapal->delete();
        session()->flash('message', 'Data Kapal Berhasil Dihapus.');
    }
}