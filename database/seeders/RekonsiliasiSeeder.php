<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ukpd;
use App\Models\RekonsiliasiInvoice;
use App\Models\SuratPermohonanPengisian;

class RekonsiliasiSeeder extends Seeder
{
    public function run(): void
    {
        $penyedia = User::whereHas('role', fn($q) => $q->where('slug', 'penyedia'))->first();
        $ukpd = Ukpd::first();

        if (!$penyedia || !$ukpd) return;

        // 1. Buat Invoice Dummy
        $invoice = RekonsiliasiInvoice::create([
            'penyedia_id' => $penyedia->id,
            'ukpd_id' => $ukpd->id,
            'nomor_invoice' => 'INV/BBM/' . date('Y/m') . '/001',
            'tanggal_invoice' => now()->format('Y-m-d'),
            'periode_awal' => now()->startOfMonth()->format('Y-m-d'),
            'periode_akhir' => now()->endOfMonth()->format('Y-m-d'),
            'total_tagihan' => 15000000.00,
            'file_evidence' => 'dummy_invoice.pdf',
            'status' => 'pending'
        ]);

        // 2. Tautkan transaksi (Checklist simulasi)
        // Ambil 2 surat permohonan milik penyedia ini yang belum di-invoice
        $transaksiList = SuratPermohonanPengisian::where('penyedia_id', $penyedia->id)
            ->whereNull('rekonsiliasi_invoice_id')
            ->take(2)
            ->get();

        foreach ($transaksiList as $transaksi) {
            $transaksi->update(['rekonsiliasi_invoice_id' => $invoice->id]);
        }
    }
}