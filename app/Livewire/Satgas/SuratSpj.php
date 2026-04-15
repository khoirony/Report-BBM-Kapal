<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Spj;
use App\Models\Kapal;
use Illuminate\Support\Facades\Auth;

class SuratSpj extends Component
{
    use WithPagination, WithFileUploads;

    // Properti Form
    public $nomor_spj;
    public $kapal_id;
    public $tanggal_spj;
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
        $this->nomor_spj = '';
        $this->kapal_id = '';
        $this->tanggal_spj = '';
        $this->file_spj = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'nomor_spj'   => 'required|unique:spjs,nomor_spj',
            'kapal_id'    => 'required|exists:kapals,id',
            'tanggal_spj' => 'required|date',
            'file_spj'    => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $filePath = $this->file_spj->store('uploads/spj', 'public');

        Spj::create([
            'nomor_spj'   => $this->nomor_spj,
            'kapal_id'    => $this->kapal_id,
            'tanggal_spj' => $this->tanggal_spj,
            'file_spj'    => $filePath,
            // Kolom status dihapus dari sini
            'created_by'  => $user->id,
            'ukpd_id'     => $user->ukpd_id,
        ]);

        session()->flash('message', 'SPJ berhasil ditambahkan dan menunggu persetujuan PPTK.');
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