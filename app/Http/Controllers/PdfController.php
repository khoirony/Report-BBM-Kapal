<?php

namespace App\Http\Controllers;

use App\Models\BaPengisianBbm;
use App\Models\LaporanPengisianBbm;
use Illuminate\Http\Request;
use App\Models\LaporanSebelumPengisian;
use App\Models\LaporanSisaBbm;
use App\Models\SuratPermohonanPengisian;
use App\Models\SuratTugasPengisian;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function previewBeritaAcaraLaporanPengisian($id)
    {
        // Ambil data laporan beserta relasinya (Sama seperti logika di Livewire sebelumnya)
        $laporan = BaPengisianBbm::with([
            'kapal.ukpd', 
            'laporanPengisian.suratPermohonan', 
            'laporanPengisian.suratTugas.petugas',
            'laporanPengisian.suratTugas.LaporanSisaBbm.sounding.kapal'
        ])->findOrFail($id);

        // Render view PDF
        $pdf = Pdf::loadView('pdf.berita-acara-laporan-pengisian', ['laporan' => $laporan]);

        // Atur ukuran kertas ke A4 (Portrait)
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Berita_Acara_' . $laporan->tgl_ba . '.pdf';

        // Gunakan stream() untuk menampilkan preview di browser, bukan download()
        return $pdf->stream($namaFile);
    }

    public function previewLaporanPengisian($id)
    {
        // Ambil data laporan beserta relasinya (Sama seperti logika di Livewire sebelumnya)
        $laporan = LaporanPengisianBbm::with([
            'suratPermohonan', 
            'suratTugas.petugas',
            'suratTugas.LaporanSisaBbm.sounding.kapal'
        ])->findOrFail($id);

        // Render view PDF
        $pdf = Pdf::loadView('pdf.laporan-pengisian', ['laporan' => $laporan]);

        // Atur ukuran kertas ke A4 (Portrait)
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Laporan_Pengisian_' . $laporan->tanggal . '.pdf';

        // Gunakan stream() untuk menampilkan preview di browser, bukan download()
        return $pdf->stream($namaFile);
    }

    public function previewSuratTugas($id)
    {
        $surat = SuratTugasPengisian::with(['laporanSisaBbm.sounding.kapal', 'petugas'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-tugas-pengisian-bbm', ['surat' => $surat]);
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Surat_Tugas_BBM_' . str_replace(' ', '_', $surat->tanggal_dikeluarkan) . '.pdf';

        return $pdf->stream($namaFile);
    }

    public function previewSuratPermohonan($id)
    {
        $surat = SuratPermohonanPengisian::with(['suratTugas.laporanSisaBbm.sounding.kapal', 'suratTugas.petugas'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-permohonan-pengisian-bbm', ['surat' => $surat]);
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Surat_Permohonan_BBM_' . str_replace(' ', '_', $surat->tanggal_surat) . '.pdf';

        return $pdf->stream($namaFile);
    }

    public function previewLaporanSisaBbm($id)
    {
        // Ambil data laporan sisa BBM beserta relasinya
        $laporan = LaporanSisaBbm::with(['sounding'])->findOrFail($id);

        // Render view PDF (pastikan file blade pdf.laporan-sisa-bbm tersedia)
        $pdf = Pdf::loadView('pdf.laporan-sisa-bbm', ['laporan' => $laporan]);

        // Atur ukuran kertas ke A4 (Portrait)
        $pdf->setPaper('A4', 'portrait');

        // Nama file saat di-download/preview (ubah garis miring pada nomor surat menjadi underscore agar valid)
        $safeNomor = str_replace(['/', '\\'], '_', $laporan->nomor);
        $namaFile = 'Laporan_Sisa_BBM_' . $safeNomor . '.pdf';

        // Gunakan stream() untuk menampilkan preview di browser
        return $pdf->stream($namaFile);
    }
}