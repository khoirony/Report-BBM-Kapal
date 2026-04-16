<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Spj;
use App\Models\Kapal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratSpj extends Component
{
    use WithPagination, WithFileUploads;

    // Properti Form
    public $spj_id; // Digunakan untuk tracking Edit
    public $nomor_spj;
    public $kapal_id;
    public $tanggal_spj;
    public $total_biaya; // Input baru
    public $file_spj;

    // Properti Filter
    public $filter_start_date;
    public $filter_end_date;
    public $filter_kapal_id;

    // State Modal
    public $isOpen = false;

    // Reset pagination ketika filter berubah
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
        $this->tanggal_spj = '';
        $this->total_biaya = '';
        $this->file_spj = null;
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $spj = Spj::findOrFail($id);
        
        $this->spj_id = $id;
        $this->nomor_spj = $spj->nomor_spj;
        $this->kapal_id = $spj->kapal_id;
        $this->tanggal_spj = $spj->tanggal_spj;
        $this->total_biaya = $spj->total_biaya;
        // File tidak di-bind kembali, jika kosong berarti user tidak mengganti file saat edit
        
        $this->isOpen = true;
    }

    public function store()
    {
        // Validasi Dinamis (Jika edit, file opsional dan nomor_spj exclude ID saat ini)
        $this->validate([
            'nomor_spj'   => 'required|unique:spjs,nomor_spj,' . $this->spj_id,
            'kapal_id'    => 'required|exists:kapals,id',
            'tanggal_spj' => 'required|date',
            'total_biaya' => 'required|numeric|min:0',
            'file_spj'    => $this->spj_id ? 'nullable|mimes:pdf,jpg,jpeg,png|max:5120' : 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'nomor_spj'   => $this->nomor_spj,
            'kapal_id'    => $this->kapal_id,
            'tanggal_spj' => $this->tanggal_spj,
            'total_biaya' => $this->total_biaya,
        ];

        // Jika ada file yang diunggah (baik saat Create maupun Edit)
        if ($this->file_spj) {
            $data['file_spj'] = $this->file_spj->store('uploads/spj', 'public');
        }

        if ($this->spj_id) {
            // Proses Update
            $spj = Spj::find($this->spj_id);
            $spj->update($data);
            session()->flash('message', 'Dokumen SPJ berhasil diperbarui.');
        } else {
            // Proses Create Baru
            $user = Auth::user();
            $data['created_by']  = $user->id;
            $data['ukpd_id']     = $user->ukpd_id;
            
            Spj::create($data);
            session()->flash('message', 'SPJ berhasil ditambahkan dan menunggu persetujuan PPTK.');
        }

        $this->closeModal();
    }

    public function approve($id)
    {
        $spj = Spj::findOrFail($id);
        $user = Auth::user();
        $role = $user->role->slug;

        // 1. PPTK Approve (Syarat: Belum disetujui PPTK)
        if (in_array($role, ['pptk', 'superadmin']) && is_null($spj->disetujui_pptk_at)) {
            $spj->update([
                'disetujui_pptk_by' => $user->id,
                'disetujui_pptk_at' => now(),
            ]);
            session()->flash('message', 'SPJ disetujui. Diteruskan ke Kepala UKPD.');
            return;
        }

        // 2. Kepala UKPD Approve (Syarat: Sudah disetujui PPTK, tapi belum disetujui Ka. UKPD)
        if (in_array($role, ['kepala_ukpd', 'superadmin']) && !is_null($spj->disetujui_pptk_at) && is_null($spj->disetujui_kepala_ukpd_at)) {
            $spj->update([
                'disetujui_kepala_ukpd_by' => $user->id,
                'disetujui_kepala_ukpd_at' => now(),
            ]);
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

        // 1. Batal Setuju PPTK (Syarat: Sudah disetujui PPTK, TAPI belum disetujui Kepala UKPD)
        if (in_array($role, ['pptk', 'superadmin']) && !is_null($spj->disetujui_pptk_at) && is_null($spj->disetujui_kepala_ukpd_at)) {
            $spj->update([
                'disetujui_pptk_by' => null,
                'disetujui_pptk_at' => null,
            ]);
            session()->flash('message', 'Persetujuan PPTK dibatalkan.');
            return;
        }

        // 2. Batal Setuju Kepala UKPD (Syarat: Sudah disetujui Kepala UKPD)
        if (in_array($role, ['kepala_ukpd', 'superadmin']) && !is_null($spj->disetujui_kepala_ukpd_at)) {
            $spj->update([
                'disetujui_kepala_ukpd_by' => null,
                'disetujui_kepala_ukpd_at' => null,
            ]);
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

        // Pastikan hanya role tertentu yang bisa hapus, dan HANYA JIKA dokumen belum di-ACC PPTK
        if (in_array($role, ['satgas', 'admin_ukpd', 'superadmin']) && is_null($spj->disetujui_pptk_at)) {
            
            // Hapus file fisik dari storage jika ada
            if ($spj->file_spj && Storage::disk('public')->exists($spj->file_spj)) {
                Storage::disk('public')->delete($spj->file_spj);
            }

            // Hapus record dari database
            $spj->delete();
            
            session()->flash('message', 'Dokumen SPJ dan file lampirannya berhasil dihapus.');
        } else {
            session()->flash('error', 'Anda tidak dapat menghapus dokumen yang sudah diproses atau tidak memiliki izin.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        // Base Query SPJ
        $query = Spj::with(['kapal', 'creator', 'pemberiPersetujuanPptk', 'pemberiPersetujuanKaUkpd']);

        // Filter berdasarkan UKPD (Kecuali Superadmin)
        if ($user->role->slug !== 'superadmin') {
            $query->where('ukpd_id', $user->ukpd_id);
        }

        // Terapkan Filter Tanggal
        if ($this->filter_start_date && $this->filter_end_date) {
            $query->whereBetween('tanggal_spj', [$this->filter_start_date, $this->filter_end_date]);
        }

        // Terapkan Filter Kapal
        if ($this->filter_kapal_id) {
            $query->where('kapal_id', $this->filter_kapal_id);
        }

        $spjs = $query->latest()->paginate(10);

        // Data Kapal untuk Dropdown (Tambah & Filter)
        $kapals = Kapal::when($user->role->slug !== 'superadmin', function($q) use ($user) {
            return $q->where('ukpd_id', $user->ukpd_id);
        })->get();

        return view('livewire.satgas.surat-spj', [
            'spjs'   => $spjs,
            'kapals' => $kapals,
        ])->layout('layouts.app');
    }
}