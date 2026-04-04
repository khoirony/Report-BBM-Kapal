<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanSebelumPengisian;
use App\Models\SuratTugasPengisian;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function previewLaporan($id)
    {
        // Ambil data laporan beserta relasinya (Sama seperti logika di Livewire sebelumnya)
        $laporan = LaporanSebelumPengisian::with(['kapal', 'soundings' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        // Render view PDF
        $pdf = Pdf::loadView('pdf.laporan-bbm-sebelum-pengisian', ['laporan' => $laporan]);

        // Atur ukuran kertas ke A4 (Portrait)
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Laporan_BBM_' . str_replace(' ', '_', $laporan->kapal->nama_kapal ?? 'Kapal') . '_' . $laporan->tanggal->format('d-m-Y') . '.pdf';

        // Gunakan stream() untuk menampilkan preview di browser, bukan download()
        return $pdf->stream($namaFile);
    }

    public function previewSuratTugas($id)
    {
        $surat = SuratTugasPengisian::with(['laporanBbm.kapal'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-tugas-pengisian-bbm', ['surat' => $surat]);
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Surat_Tugas_BBM_' . str_replace(' ', '_', $surat->laporanBbm->kapal->nama_kapal ?? 'Kapal') . '.pdf';

        return $pdf->stream($namaFile);
    }
}