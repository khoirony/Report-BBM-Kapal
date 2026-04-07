<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sisa BBM - {{ $laporan->kapal->nama_kapal ?? $laporan->kapal->nama ?? '-' }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.5; color: black; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .w-full { width: 100%; }
        
        /* Layout Header Surat */
        .header-table { width: 100%; border: none; margin-bottom: 20px; }
        .header-table td { vertical-align: top; padding: 2px 0; }
        
        /* Tabel Umum */
        table { width: 100%; border-collapse: collapse; }
        .table-border th, .table-border td { border: 1px solid black; padding: 8px 5px; text-align: center; }
        .table-border th { background-color: #e5e5e5; font-weight: bold; }
        
        /* Layout Tanda Tangan */
        .signature-table { width: 100%; margin-top: 50px; border: none; }
        .signature-table td { width: 50%; text-align: center; vertical-align: bottom; height: 120px; }
        .signature-name { font-weight: bold; text-decoration: underline; display: block; }
        
        .mt-20 { margin-top: 20px; }
        .mt-10 { margin-top: 10px; }
        .mb-20 { margin-bottom: 20px; }
    </style>
</head>
<body>

    <h3 class="text-center font-bold underline mb-20" style="font-size: 12pt;">
        LAPORAN PERHITUNGAN JUMLAH SISA BBM KAPAL SEBELUM PENGISIAN
    </h3>

    <table class="header-table">
        <tr>
            <td style="width: 15%;">Nomor</td>
            <td style="width: 2%;">:</td>
            <td style="width: 43%;">{{ $laporan->nomor }}</td>
            <td style="width: 40%; text-align: right;">Jakarta, {{ \Carbon\Carbon::parse($laporan->tanggal_surat)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Klasifikasi</td>
            <td>:</td>
            <td colspan="2">{{ $laporan->klasifikasi ?? '-' }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td colspan="2">{{ $laporan->lampiran ?? '-' }}</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td colspan="2">{{ $laporan->perihal }}</td>
        </tr>
    </table>

    <div class="mt-20">
        <p style="margin: 0;">Kepada</p>
        <p style="margin: 0;">Yth. Pejabat Pelaksana Kegiatan BBM</p>
        <p style="margin: 0; padding-left: 28px;">Kapal Unit Pengelola Angkutan Perairan</p>
        <p style="margin: 0; padding-left: 28px;">Dinas Perhubungan Provinsi DKI</p>
        <p style="margin: 0;">di</p>
        <p style="margin: 0; padding-left: 28px;">Jakarta</p>
    </div>

    <div class="mt-20">
        <p>Nama Kapal : <b>{{ $laporan->sounding->kapal->nama_kapal ?? $laporan->sounding->kapal->nama ?? '-' }}</b></p>
    </div>

    <table class="table-border mt-10">
        <thead>
            <tr>
                <th style="width: 10%;">No</th>
                <th style="width: 40%;">Nakhoda / ABK</th>
                <th style="width: 25%;">Tanggal</th>
                <th style="width: 25%;">Jumlah BBM <br> Sebelum Pengisian (Liter)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $laporan->nama_nakhoda }}</td>
                <td>
                    {{ $laporan->sounding ? \Carbon\Carbon::parse($laporan->sounding->created_at)->format('d/m/Y') : \Carbon\Carbon::parse($laporan->tanggal_surat)->format('d/m/Y') }}
                </td>
                <td>
                    <b>{{ $laporan->sounding ? number_format(floatval($laporan->sounding->bbm_akhir), 2, ',', '.') : '0,00' }}</b>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                Nakhoda
                <br><br><br><br><br><br>
                <span class="signature-name">{{ $laporan->nama_nakhoda }}</span>
            </td>
            <td>
                Pengawas UPAP
                <br><br><br><br><br><br><br>
                <span class="signature-name">{{ $laporan->nama_pengawas }}</span>
            </td>
        </tr>
    </table>

</body>
</html>