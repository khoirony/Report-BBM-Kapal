<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Spj;
use App\Models\Kapal;
use App\Models\ProsesPenyediaBbm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratSpj extends Component
{
    use WithPagination, WithFileUploads;

    public $spj_id; 
    public $nomor_spj;
    public $kapal_id;
    public $proses_penyedia_bbm_id;
    public $tanggal_spj;
    public $total_biaya; 
    public $file_spj;

    public $filter_start_date;
    public $filter_end_date;
    public $filter_kapal_id;

    public $isOpen = false;

    public function updatingFilterStartDate() { $this->resetPage(); }
    public function updatingFilterEndDate() { $this->resetPage(); }
    public function updatingFilterKapalId() { $this->resetPage(); }

    public function openModal()
    {
        $this->resetFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->spj_id = null;
        $this->nomor_spj = '';
        $this->kapal_id = '';
        $this->proses_penyedia_bbm_id = '';
        $this->tanggal_spj = '';
        $this->total_biaya = '';
        $this->file_spj = null;
        $this->resetErrorBag();
    }

    public function updatedProsesPenyediaBbmId($value)
    {
        if ($value) {
            $this->tarikBiayaPenyedia();
            $this->tarikKapalPenyedia();
        }
    }

    public function tarikBiayaPenyedia()
    {
        if ($this->proses_penyedia_bbm_id) {
            $proses = ProsesPenyediaBbm::find($this->proses_penyedia_bbm_id);
            if ($proses) {
                $this->total_biaya = $proses->total_harga;
            }
        }
    }

    public function tarikKapalPenyedia()
    {
        if ($this->proses_penyedia_bbm_id) {
            $proses = ProsesPenyediaBbm::with('suratPermohonan.suratTugas.LaporanSisaBbm.sounding.kapal')->find($this->proses_penyedia_bbm_id);
            
            $kapal = $proses?->suratPermohonan?->suratTugas?->LaporanSisaBbm?->sounding?->kapal;
            
            if ($kapal) {
                $this->kapal_id = $kapal->id;
            }
        }
    }

    public function edit($id)
    {
        $spj = Spj::findOrFail($id);
        
        $this->spj_id = $id;
        $this->nomor_spj = $spj->nomor_spj;
        $this->kapal_id = $spj->kapal_id;
        $this->proses_penyedia_bbm_id = $spj->proses_penyedia_bbm_id;
        $this->tanggal_spj = $spj->tanggal_spj;
        $this->total_biaya = $spj->total_biaya;
        
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'nomor_spj'              => 'nullable|unique:spjs,nomor_spj' . $this->spj_id,
            'kapal_id'               => 'required|exists:kapals,id',
            'proses_penyedia_bbm_id' => 'nullable|exists:proses_penyedia_bbms,id',
            'tanggal_spj'            => 'required|date',
            'total_biaya'            => 'required|numeric|min:0',
            'file_spj'               => $this->spj_id ? 'nullable|mimes:pdf,jpg,jpeg,png|max:10120' : 'required|mimes:pdf,jpg,jpeg,png|max:10120',
        ]);

        $data = [
            'nomor_spj'              => $this->nomor_spj,
            'kapal_id'               => $this->kapal_id,
            'proses_penyedia_bbm_id' => $this->proses_penyedia_bbm_id ?: null,
            'tanggal_spj'            => $this->tanggal_spj,
            'total_biaya'            => $this->total_biaya,
        ];

        if ($this->file_spj) {
            $data['file_spj'] = $this->file_spj->store('uploads/spj', 'public');
        }

        if ($this->spj_id) {
            $spj = Spj::find($this->spj_id);
            $spj->update($data);
            session()->flash('message', 'Dokumen SPJ berhasil diperbarui.');
        } else {
            $user = Auth::user();
            $data['created_by']  = $user->id;
            $data['ukpd_id']     = $user->ukpd_id;
            
            Spj::create($data);
            session()->flash('message', 'SPJ berhasil ditambahkan dan menunggu persetujuan PPTK.');
        }

        if ($this->proses_penyedia_bbm_id) {
            $proses = ProsesPenyediaBbm::with('suratPermohonan')->find($this->proses_penyedia_bbm_id);
            
            if ($proses && $proses->suratPermohonan) {
                $proses->suratPermohonan->update([
                    'progress' => 'done'
                ]);
            }
        }

        $this->closeModal();
    }

    public function approve($id)
    {
        $spj = Spj::findOrFail($id);
        $user = Auth::user();
        $role = $user->role->slug;

        if (in_array($role, ['pptk', 'superadmin']) && is_null($spj->disetujui_pptk_at)) {
            $spj->update(['disetujui_pptk_by' => $user->id, 'disetujui_pptk_at' => now()]);
            session()->flash('message', 'SPJ disetujui. Diteruskan ke Kepala UKPD.');
            return;
        }

        if (in_array($role, ['kepala_ukpd', 'superadmin']) && !is_null($spj->disetujui_pptk_at) && is_null($spj->disetujui_kepala_ukpd_at)) {
            $spj->update(['disetujui_kepala_ukpd_by' => $user->id, 'disetujui_kepala_ukpd_at' => now()]);
            session()->flash('message', 'SPJ disetujui Kepala UKPD. Transaksi Selesai.');
            return;
        }

        session()->flash('error', 'Anda tidak memiliki hak untuk menyetujui dokumen ini pada tahap ini.');
    }

    public function cancelApprove($id)
    {
        $spj = Spj::findOrFail($id);
        $user = Auth::user();
        $role = $user->role->slug;

        if (in_array($role, ['pptk', 'superadmin']) && !is_null($spj->disetujui_pptk_at) && is_null($spj->disetujui_kepala_ukpd_at)) {
            $spj->update(['disetujui_pptk_by' => null, 'disetujui_pptk_at' => null]);
            session()->flash('message', 'Persetujuan PPTK dibatalkan.');
            return;
        }

        if (in_array($role, ['kepala_ukpd', 'superadmin']) && !is_null($spj->disetujui_kepala_ukpd_at)) {
            $spj->update(['disetujui_kepala_ukpd_by' => null, 'disetujui_kepala_ukpd_at' => null]);
            session()->flash('message', 'Persetujuan Kepala UKPD dibatalkan.');
            return;
        }

        session()->flash('error', 'Aksi tidak diizinkan atau status dokumen sudah berubah.');
    }

    public function delete($id)
    {
        $spj = Spj::findOrFail($id);
        $user = Auth::user();
        $role = $user->role->slug;

        if (in_array($role, ['satgas', 'admin_ukpd', 'superadmin']) && is_null($spj->disetujui_pptk_at)) {
            if ($spj->file_spj && Storage::disk('public')->exists($spj->file_spj)) {
                Storage::disk('public')->delete($spj->file_spj);
            }
            $spj->delete();
            session()->flash('message', 'Dokumen SPJ dan file lampirannya berhasil dihapus.');
        } else {
            session()->flash('error', 'Anda tidak dapat menghapus dokumen yang sudah diproses atau tidak memiliki izin.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Spj::with(['kapal', 'creator', 'pemberiPersetujuanPptk', 'pemberiPersetujuanKaUkpd']);

        if ($user->role->slug !== 'superadmin') {
            $query->where('ukpd_id', $user->ukpd_id);
        }

        if ($this->filter_start_date && $this->filter_end_date) {
            $query->whereBetween('tanggal_spj', [$this->filter_start_date, $this->filter_end_date]);
        }

        if ($this->filter_kapal_id) {
            $query->where('kapal_id', $this->filter_kapal_id);
        }

        $spjs = $query->latest()->paginate(10);

        $kapals = Kapal::when($user->role->slug !== 'superadmin', function($q) use ($user) {
            return $q->where('ukpd_id', $user->ukpd_id);
        })->get();

        // LOGIKA BARU: Filter Proses Penyedia yang belum ditautkan (kecuali yang sedang diedit)
        $prosesTerpakai = Spj::whereNotNull('proses_penyedia_bbm_id')
            ->when($this->spj_id, function($q) {
                // Kecualikan ID Penyedia yang sedang dipakai oleh SPJ yang sedang di-edit
                return $q->where('id', '!=', $this->spj_id);
            })
            ->pluck('proses_penyedia_bbm_id')
            ->toArray();

        $proses_penyedia_list = ProsesPenyediaBbm::with('suratPermohonan')
            ->whereNotIn('id', $prosesTerpakai)
            ->latest()
            ->get();

        return view('livewire.satgas.surat-spj', [
            'spjs' => $spjs,
            'kapals' => $kapals,
            'proses_penyedia_list' => $proses_penyedia_list,
        ])->layout('layouts.app');
    }
}