<?php

namespace App\Livewire\Penyedia;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\RekonsiliasiInvoice;
use App\Models\SuratPermohonanPengisian;
use App\Models\Ukpd;
use App\Models\User;

class InvoiceManager extends Component
{
    use WithFileUploads, WithPagination;

    public $ukpd_id, $nomor_invoice, $tanggal_invoice, $periode_awal, $periode_akhir, $total_tagihan, $file_evidence;
    public $selected_transaksi = []; 
    public $transaksi_tersedia = [];

    public $isModalOpen = false;

    // Properti Filter & Pencarian
    public $search = '';
    public $sortBy = 'latest';
    public $filterPenyedia = ''; 
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';

    // Reset halaman saat filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterPenyedia() { $this->resetPage(); }
    public function updatingFilterTanggalAwal() { $this->resetPage(); }
    public function updatingFilterTanggalAkhir() { $this->resetPage(); }
    public function updatingSortBy() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'filterPenyedia', 'filterTanggalAwal', 'filterTanggalAkhir']);
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    // Saat UKPD atau Periode berubah di Form Modal, cari transaksi yang bisa ditagih
    public function updatedUkpdId() { $this->loadTransaksiTersedia(); }
    public function updatedPeriodeAwal() { $this->loadTransaksiTersedia(); }
    public function updatedPeriodeAkhir() { $this->loadTransaksiTersedia(); }

    public function updatedSelectedTransaksi()
    {
        $this->total_tagihan = collect($this->transaksi_tersedia)
            ->whereIn('id', $this->selected_transaksi)
            ->sum(function($ts) {
                return $ts->prosesPenyedia->total_harga ?? 0;
            });
    }

    public function loadTransaksiTersedia()
    {
        if ($this->ukpd_id && $this->periode_awal && $this->periode_akhir) {
            $this->transaksi_tersedia = SuratPermohonanPengisian::with(['suratTugas.LaporanSisaBbm.sounding.kapal', 'prosesPenyedia'])
                ->where('penyedia_id', auth()->id())
                ->where('ukpd_id', $this->ukpd_id)
                ->whereNull('rekonsiliasi_invoice_id') // Belum ditagihkan
                ->where('progress', 'done') // Hanya yang sudah selesai
                ->whereBetween('tanggal_surat', [$this->periode_awal, $this->periode_akhir])
                ->get();
        } else {
            $this->transaksi_tersedia = [];
        }
        
        $this->selected_transaksi = [];
        $this->total_tagihan = null;
    }

    public function render()
    {
        $query = RekonsiliasiInvoice::with(['ukpd', 'suratPermohonan.prosesPenyedia', 'penyedia']);

        // Jika bukan superadmin, filter data hanya milik penyedia yang sedang login
        if (auth()->user()?->role?->slug !== 'superadmin') {
            $query->where('penyedia_id', auth()->id());
        }

        // Logic Pencarian
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nomor_invoice', 'like', '%' . $this->search . '%')
                  ->orWhereHas('penyedia', function($qPenyedia) {
                      $qPenyedia->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Logic Filter Penyedia
        if (!empty($this->filterPenyedia)) {
            $query->where('penyedia_id', $this->filterPenyedia);
        }

        if (!empty($this->filterTanggalAwal)) {
            $query->whereDate('tanggal_invoice', '>=', $this->filterTanggalAwal);
        }
        
        if (!empty($this->filterTanggalAkhir)) {
            $query->whereDate('tanggal_invoice', '<=', $this->filterTanggalAkhir);
        }

        // Logic Sorting
        if ($this->sortBy === 'oldest') {
            $query->oldest('tanggal_invoice');
        } else {
            $query->latest('tanggal_invoice');
        }

        $invoices = $query->paginate(10);
        
        // Data master untuk modal
        $ukpds = Ukpd::orderBy('nama')->get(); 
        
        // Data penyedia untuk dropdown filter Superadmin
        $penyedias = User::whereHas('role', function($q) {
            $q->where('slug', 'penyedia');
        })->orderBy('name')->get();

        return view('livewire.penyedia.invoice-manager', compact('invoices', 'ukpds', 'penyedias'))
               ->layout('layouts.app');
    }

    public function create()
    {
        $this->reset(['ukpd_id', 'nomor_invoice', 'tanggal_invoice', 'periode_awal', 'periode_akhir', 'total_tagihan', 'file_evidence', 'selected_transaksi']);
        $this->transaksi_tersedia = [];
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'ukpd_id' => 'required',
            'nomor_invoice' => 'required|unique:rekonsiliasi_invoices,nomor_invoice',
            'tanggal_invoice' => 'required|date',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
            'total_tagihan' => 'required|numeric',
            'file_evidence' => 'required|file|mimes:pdf,jpg,png|max:5120', // Max 5MB
            'selected_transaksi' => 'required|array|min:1',
        ]);

        // Simpan File
        $filePath = $this->file_evidence->store('invoices', 'public');

        // Buat Invoice
        $invoice = RekonsiliasiInvoice::create([
            'penyedia_id' => auth()->id(),
            'ukpd_id' => $this->ukpd_id,
            'nomor_invoice' => $this->nomor_invoice,
            'tanggal_invoice' => $this->tanggal_invoice,
            'periode_awal' => $this->periode_awal,
            'periode_akhir' => $this->periode_akhir,
            'total_tagihan' => $this->total_tagihan,
            'file_evidence' => $filePath,
            'status' => 'pending'
        ]);

        // Update Checklist Transaksi (Tautkan ke Invoice ini)
        SuratPermohonanPengisian::whereIn('id', $this->selected_transaksi)
            ->update(['rekonsiliasi_invoice_id' => $invoice->id]);

        session()->flash('message', 'Invoice berhasil diajukan untuk proses Rekonsiliasi.');
        $this->isModalOpen = false;
    }
}