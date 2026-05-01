<?php

namespace App\Livewire\Nakhoda;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\PencatatanHasil;
use App\Models\Kapal;
use App\Models\SuratPermohonanPengisian; // <-- TAMBAHKAN MODEL INI
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PencatatanHasilPengisian extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(as: 'kapal')]
    public $kapal_id = '';
    
    public $surat_permohonan_id = '';
    public $nama_kapal_readonly = '';
    
    public $tanggal_pengisian, $jumlah_pengisian;
    
    // Variabel untuk Edit & Foto
    public $edit_id = null;
    public $old_foto_proses, $old_foto_flow_meter, $old_foto_struk;
    public $new_foto_proses, $new_foto_flow_meter, $new_foto_struk;

    public $isOpen = false;

    // Variabel Filter
    public $search = '';
    public $filter_kapal = '';
    public $filter_start_date = '';
    public $filter_end_date = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKapal() { $this->resetPage(); }
    public function updatingFilterStartDate() { $this->resetPage(); }
    public function updatingFilterEndDate() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'filter_kapal', 'filter_start_date', 'filter_end_date']);
        $this->resetPage();
    }

    public function updatedSuratPermohonanId($value)
    {
        if ($value) {
            $permohonan = SuratPermohonanPengisian::with('LaporanSisaBbm.sounding.kapal')->find($value);
            
            if ($permohonan) {
                $this->tanggal_pengisian = $permohonan->tanggal_surat;
                
                // Ambil data kapal dari relasi terdalam
                $kapal = $permohonan?->LaporanSisaBbm->sounding->kapal ?? null;
                if ($kapal) {
                    $this->kapal_id = $kapal->id;
                    $this->nama_kapal_readonly = $kapal->nama_kapal;
                }
            }
        } else {
            // Reset jika dropdown permohonan dikosongkan
            $this->tanggal_pengisian = '';
            if(!request()->query('kapal')) {
                $this->kapal_id = '';
                $this->nama_kapal_readonly = '';
            }
        }
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->edit_id = null;
        $this->surat_permohonan_id = '';
        $this->nama_kapal_readonly = '';
        
        if(!request()->query('kapal')) {
            $this->kapal_id = '';
        }
        
        $this->tanggal_pengisian = '';
        $this->jumlah_pengisian = '';
        $this->resetFotos();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        
        // Pastikan menarik relasi secara mendalam untuk kebutuhan form edit
        $record = PencatatanHasil::with('suratPermohonan.LaporanSisaBbm.sounding.kapal')->findOrFail($id);
        
        $this->edit_id = $record->id;
        $this->surat_permohonan_id = $record->surat_permohonan_id;
        $this->kapal_id = $record->kapal_id;
        $this->tanggal_pengisian = $record->tanggal_pengisian;
        $this->jumlah_pengisian = floatval($record->jumlah_pengisian);
        
        // Tarik nama kapal dari relasi untuk mengisi visual readonly
        $kapal = $record?->suratPermohonan?->LaporanSisaBbm?->sounding?->kapal ?? null;
        $this->nama_kapal_readonly = $kapal ? $kapal->nama_kapal : '-';
        
        // Simpan path foto lama untuk preview
        $this->old_foto_proses = $record->foto_proses;
        $this->old_foto_flow_meter = $record->foto_flow_meter;
        $this->old_foto_struk = $record->foto_struk;

        // Kosongkan inputan foto baru
        $this->new_foto_proses = null;
        $this->new_foto_flow_meter = null;
        $this->new_foto_struk = null;

        $this->isOpen = true;
    }

    public function resetFotos()
    {
        $this->old_foto_proses = null;
        $this->old_foto_flow_meter = null;
        $this->old_foto_struk = null;
        $this->new_foto_proses = null;
        $this->new_foto_flow_meter = null;
        $this->new_foto_struk = null;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetFotos();
    }

    public function store()
    {
        $rules = [
            'surat_permohonan_id' => 'required|exists:surat_permohonan_pengisians,id',
            'kapal_id'            => 'required|exists:kapals,id',
            'tanggal_pengisian'   => 'required|date',
            'jumlah_pengisian'    => 'required|numeric|min:1',
        ];

        if ($this->edit_id) {
            $rules['new_foto_proses']     = 'nullable|image|max:5120';
            $rules['new_foto_flow_meter'] = 'nullable|image|max:5120';
            $rules['new_foto_struk']      = 'nullable|image|max:5120';
        } else {
            $rules['new_foto_proses']     = 'required|image|max:5120';
            $rules['new_foto_flow_meter'] = 'required|image|max:5120';
            $rules['new_foto_struk']      = 'required|image|max:5120';
        }

        $this->validate($rules);

        $data = [
            'surat_permohonan_id' => $this->surat_permohonan_id, // <-- Simpan ID Permohonan
            'kapal_id'            => $this->kapal_id,
            'tanggal_pengisian'   => $this->tanggal_pengisian,
            'jumlah_pengisian'    => $this->jumlah_pengisian,
        ];

        if ($this->new_foto_proses) {
            $data['foto_proses'] = $this->new_foto_proses->store('uploads/evidence', 'public');
            if ($this->edit_id && $this->old_foto_proses) Storage::disk('public')->delete($this->old_foto_proses);
        }
        if ($this->new_foto_flow_meter) {
            $data['foto_flow_meter'] = $this->new_foto_flow_meter->store('uploads/evidence', 'public');
            if ($this->edit_id && $this->old_foto_flow_meter) Storage::disk('public')->delete($this->old_foto_flow_meter);
        }
        if ($this->new_foto_struk) {
            $data['foto_struk'] = $this->new_foto_struk->store('uploads/evidence', 'public');
            if ($this->edit_id && $this->old_foto_struk) Storage::disk('public')->delete($this->old_foto_struk);
        }

        if ($this->edit_id) {
            $record = PencatatanHasil::findOrFail($this->edit_id);
            $record->update($data);
            session()->flash('message', 'Pencatatan berhasil diperbarui.');
        } else {
            $data['created_by'] = Auth::id();
            PencatatanHasil::create($data);
            session()->flash('message', 'Pencatatan hasil pengisian berhasil disimpan.');
        }

        $this->closeModal();
    }

    public function approve($id, $type)
    {
        $catatan = PencatatanHasil::findOrFail($id);
        
        if ($type === 'pengawas') {
            $catatan->update(['disetujui_pengawas_at' => now()]);
            session()->flash('message', 'Disetujui sebagai Pengawas.');
        } elseif ($type === 'penyedia') {
            $catatan->update(['disetujui_penyedia_at' => now()]);
            session()->flash('message', 'Disetujui sebagai Penyedia.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = PencatatanHasil::with(['suratPermohonan.LaporanSisaBbm.sounding.kapal', 'creator'])->latest();

        if ($user->role->slug === 'nakhoda') {
            $query->whereHas('kapal', function($q) use ($user) {
                $q->where('nakhoda_id', $user->id);
            });
        }

        if (!empty($this->search)) {
            $query->whereHas('kapal', function($q) {
                $q->where('nama_kapal', 'like', '%' . $this->search . '%');
            });
        }
        if (!empty($this->filter_kapal)) {
            $query->where('kapal_id', $this->filter_kapal);
        }
        if (!empty($this->filter_start_date) && !empty($this->filter_end_date)) {
            $query->whereBetween('tanggal_pengisian', [$this->filter_start_date, $this->filter_end_date]);
        }

        $permohonanQuery = SuratPermohonanPengisian::with('LaporanSisaBbm.sounding.kapal');
        if ($user->role->slug === 'nakhoda') {
            $permohonanQuery->whereHas('LaporanSisaBbm.sounding.kapal', function($q) use ($user) {
                $q->where('nakhoda_id', $user->id);
            });
        }
        
        $permohonanQuery->where(function($q) {
            $q->doesntHave('pencatatanHasil'); 
        
            if ($this->edit_id && $this->surat_permohonan_id) {
                $q->orWhere('id', $this->surat_permohonan_id);
            }
        });

        if ($user->role->slug === 'nakhoda') {
            $permohonanQuery->whereHas('LaporanSisaBbm.sounding.kapal', function($q) use ($user) {
                $q->where('nakhoda_id', $user->id);
            });
        }
        
        if ($this->kapal_id && !empty(request()->query('kapal'))) {
            $permohonanQuery->whereHas('LaporanSisaBbm.sounding', function($q) {
                $q->where('kapal_id', $this->kapal_id);
            });
        }

        return view('livewire.nakhoda.pencatatan-hasil-pengisian', [
            'pencatatans' => $query->paginate(10),
            'permohonans' => $permohonanQuery->get(),
            'kapals'      => Kapal::all(),
        ])->layout('layouts.app');
    }
}