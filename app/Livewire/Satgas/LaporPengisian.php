<?php

namespace App\Livewire\Satgas;

use App\Models\Kapal;
use Livewire\Component;
use App\Models\LaporanPengisian;
use App\Models\Sounding;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporPengisian extends Component
{
    public $laporans, $kapals;
    public $laporan_id, $kapal_id, $hari, $tanggal, $dasar_hukum, $kegiatan, $tujuan, $lokasi;
    public $petugas_list = []; 
    public $isOpen = false;

    // Properti baru untuk fitur Checklist Sounding
    public $available_soundings = [];
    public $selected_soundings = [];

    public function mount()
    {
        $this->kapals = Kapal::all(); 
        $this->initPetugas();
    }

    public function render()
    {
        $this->laporans = LaporanPengisian::with(['kapal', 'soundings'])->latest()->get();
        return view('livewire.satgas.lapor-pengisian')->layout('layouts.app');
    }

    public function downloadPdf($id)
    {
        // Ambil data laporan beserta relasinya
        $laporan = LaporanPengisian::with(['kapal', 'soundings' => function($q) {
            // Urutkan sounding berdasarkan waktu dibuat agar logis dari Awal ke Akhir
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        // Render view PDF
        $pdf = Pdf::loadView('pdf.laporan-pengisian-bbm', ['laporan' => $laporan]);

        // Atur ukuran kertas ke A4 (Portrait)
        $pdf->setPaper('A4', 'portrait');

        // Nama file saat didownload
        $namaFile = 'Laporan_BBM_' . str_replace(' ', '_', $laporan->kapal->nama_kapal) . '_' . $laporan->tanggal->format('d-m-Y') . '.pdf';

        // Kembalikan response berupa file download
        return response()->streamDownload(fn () => print($pdf->output()), $namaFile);
    }

    // Trigger otomatis saat Kapal atau Tanggal diubah di form
    public function updatedKapalId() { $this->loadAvailableSoundings(); }
    public function updatedTanggal() { $this->loadAvailableSoundings(); }

    public function loadAvailableSoundings()
    {
        if ($this->kapal_id && $this->tanggal) {
            // Cari data sounding milik kapal ini di tanggal yang dipilih
            $this->available_soundings = Sounding::where('kapal_id', $this->kapal_id)
                ->whereDate('created_at', $this->tanggal)
                ->get();
        } else {
            $this->available_soundings = [];
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }

    private function resetInputFields()
    {
        $this->laporan_id = '';
        $this->kapal_id = '';
        $this->hari = '';
        $this->tanggal = '';
        $this->dasar_hukum = '';
        $this->kegiatan = 'Pengisian BBM Kapal di Pelabuhan Sunda Kelapa';
        $this->tujuan = 'Meningkatkan Ketersediaan BBM Kapal untuk Menunjang Kegiatan Operasional';
        $this->lokasi = '';
        $this->available_soundings = [];
        $this->selected_soundings = [];
        $this->initPetugas();
    }

    private function initPetugas()
    {
        $this->petugas_list = [];
        for ($i = 0; $i < 7; $i++) { $this->petugas_list[] = ['nama' => '', 'jabatan' => '']; }
    }

    public function store()
    {
        $this->validate([
            'kapal_id' => 'required',
            'hari' => 'required',
            'tanggal' => 'required|date',
            'dasar_hukum' => 'required',
            'lokasi' => 'required',
        ]);

        $laporan = LaporanPengisian::updateOrCreate(['id' => $this->laporan_id], [
            'kapal_id' => $this->kapal_id,
            'hari' => $this->hari,
            'tanggal' => $this->tanggal,
            'dasar_hukum' => $this->dasar_hukum,
            'petugas_list' => $this->petugas_list,
            'kegiatan' => $this->kegiatan,
            'tujuan' => $this->tujuan,
            'lokasi' => $this->lokasi,
        ]);

        // Menyimpan relasi ke tabel pivot (laporan_sounding)
        // Fungsi sync() akan otomatis menambah/menghapus relasi sesuai array yang dicentang
        $laporan->soundings()->sync($this->selected_soundings);

        session()->flash('message', $this->laporan_id ? 'Laporan berhasil diperbarui.' : 'Laporan berhasil dibuat.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $laporan = LaporanPengisian::findOrFail($id);
        $this->laporan_id = $id;
        $this->kapal_id = $laporan->kapal_id;
        $this->hari = $laporan->hari;
        $this->tanggal = $laporan->tanggal->format('Y-m-d');
        $this->dasar_hukum = $laporan->dasar_hukum;
        $this->kegiatan = $laporan->kegiatan;
        $this->tujuan = $laporan->tujuan;
        $this->lokasi = $laporan->lokasi;
        
        $this->petugas_list = $laporan->petugas_list ?? [];
        while(count($this->petugas_list) < 7) { $this->petugas_list[] = ['nama' => '', 'jabatan' => '']; }
        
        // Load opsi sounding yang tersedia
        $this->loadAvailableSoundings();
        
        // Centang sounding yang sudah tersimpan di database
        $this->selected_soundings = $laporan->soundings->pluck('id')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        $laporan = LaporanPengisian::find($id);
        // Hapus relasi pivot terlebih dahulu
        $laporan->soundings()->detach(); 
        $laporan->delete();
        
        session()->flash('message', 'Laporan berhasil dihapus.');
    }
}