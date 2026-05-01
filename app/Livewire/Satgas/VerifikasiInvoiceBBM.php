<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\RekonsiliasiInvoice;
use App\Models\User;

class VerifikasiInvoiceBBM extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'latest';
    public $filterPenyedia = '';
    public $filterStatus = '';

    // Properti UI Modal
    public $isDetailModalOpen = false;
    public $isRejectModalOpen = false;
    
    public $selectedInvoiceId = null; 
    public $catatan_penolakan = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterPenyedia() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'filterPenyedia', 'filterStatus']);
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    #[Computed]
    public function selectedInvoice()
    {
        if (!$this->selectedInvoiceId) {
            return null;
        }

        return RekonsiliasiInvoice::with([
            'ukpd', 
            'penyedia', 
            'suratPermohonan.LaporanSisaBbm.sounding.kapal',
            'suratPermohonan.prosesPenyedia'
        ])->find($this->selectedInvoiceId);
    }

    public function render()
    {
        $query = RekonsiliasiInvoice::with([
            'ukpd', 
            'penyedia', 
            'suratPermohonan.LaporanSisaBbm.sounding.kapal',
            'suratPermohonan.prosesPenyedia'
        ]);

        $userRole = auth()->user()?->role?->slug;

        // Filter berdasarkan UKPD untuk Satgas & PPTK (Superadmin bisa lihat semua)
        if ($userRole !== 'superadmin') {
            $query->where('ukpd_id', auth()->user()->ukpd_id);
        }

        // Filter Pencarian
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_invoice', 'like', '%' . $this->search . '%')
                  ->orWhereHas('penyedia', function($qPenyedia) {
                      $qPenyedia->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter Penyedia
        if (!empty($this->filterPenyedia)) {
            $query->where('penyedia_id', $this->filterPenyedia);
        }

        // Filter Status
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        // Sorting
        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal_invoice');
        } else {
            $query->latest('tanggal_invoice');
        }

        $penyedias = User::whereHas('role', function($q) {
            $q->where('slug', 'penyedia');
        })->orderBy('name')->get();

        return view('livewire.satgas.verifikasi-invoice-bbm', [
            'invoices' => $query->paginate(10),
            'penyedias' => $penyedias
        ])->layout('layouts.app');
    }

    public function openDetail($id)
    {
        $this->selectedInvoiceId = $id; 
        $this->isDetailModalOpen = true;
    }

    public function closeDetail()
    {
        $this->isDetailModalOpen = false;
        $this->selectedInvoiceId = null;
    }

    public function approveSatgas()
    {
        $invoice = $this->selectedInvoice; 

        if ($invoice && $invoice->status === 'pending') {
            $invoice->update(['status' => 'satgas_approved', 'catatan_penolakan' => null]);
            session()->flash('message', 'Invoice berhasil diverifikasi oleh Satgas. Menunggu persetujuan PPTK.');
            $this->closeDetail();
        }
    }

    public function approvePptk()
    {
        $invoice = $this->selectedInvoice;

        if ($invoice && $invoice->status === 'satgas_approved') {
            $invoice->update(['status' => 'pptk_approved', 'catatan_penolakan' => null]);
            session()->flash('message', 'Invoice disetujui final oleh PPTK. Siap untuk dibuatkan Surat Rekonsiliasi.');
            $this->closeDetail();
        }
    }

    public function openRejectModal()
    {
        $this->catatan_penolakan = '';
        $this->isRejectModalOpen = true;
    }

    public function closeRejectModal()
    {
        $this->isRejectModalOpen = false;
        $this->catatan_penolakan = '';
    }

    public function rejectInvoice()
    {
        $this->validate([
            'catatan_penolakan' => 'required|min:10'
        ], [
            'catatan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'catatan_penolakan.min' => 'Alasan penolakan terlalu singkat (minimal 10 karakter).'
        ]);

        $invoice = $this->selectedInvoice;

        if ($invoice) {
            $invoice->update([
                'status' => 'rejected',
                'catatan_penolakan' => $this->catatan_penolakan
            ]);
            
            // Melepas tautan transaksi agar penyedia bisa mengajukan ulang
            foreach ($invoice->suratPermohonan as $permohonan) {
                $permohonan->update(['rekonsiliasi_invoice_id' => null]);
            }

            session()->flash('message', 'Invoice berhasil ditolak dan dikembalikan ke penyedia.');
            $this->closeRejectModal();
            $this->closeDetail();
        }
    }
}