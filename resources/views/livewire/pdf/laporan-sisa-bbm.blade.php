<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sisa BBM - {{ $laporan->sounding->kapal->nama_kapal ?? '-' }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.2; color: black; margin: 0; padding: 10px 20px; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        
        /* Kop Surat */
        .kop-table { width: 100%; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 15px; }
        .logo-container { width: 12%; text-align: center; vertical-align: middle; }
        .logo-container img { width: 75px; height: auto; }
        .kop-text { width: 88%; text-align: center; vertical-align: middle; }
        .instansi-utama { font-size: 14pt; margin: 0; font-weight: bold; }

        /* Header Utama (Mencegah Gap) */
        .main-header { width: 100%; margin-bottom: 10px; }
        .main-header td { vertical-align: top; padding: 0; }
        
        .sub-header-left { width: 100%; }
        .sub-header-left td { padding: 1px 0; vertical-align: top; }
        
        .sub-header-right { width: 100%; }
        .sub-header-right td { padding: 1px 0; vertical-align: top; }

        /* Tabel Data */
        .table-border { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table-border th, .table-border td { border: 1px solid black; padding: 8px 10px; }
        .table-border th { background-color: #f2f2f2; }
        
        /* Footer Tanda Tangan */
        .ttd-container { width: 100%; margin-top: 30px; }
        .ttd-table { width: 100%; border: none; }
        .ttd-table td { vertical-align: top; text-align: center; }

        .mt-20 { margin-top: 20px; }
        .mt-10 { margin-top: 10px; }
    </style>
</head>
<body>

    <table class="kop-table">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('img/logo-jaya-raya.jpg') }}" alt="Logo">
            </td>
            <td class="kop-text">
                <div class="instansi-utama">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
                <div class="instansi-utama">DINAS PERHUBUNGAN</div>
                <div class="instansi-utama">
                    {{ strtoupper($laporan?->ukpd?->nama ?? ($laporan?->sounding?->kapal?->ukpd?->nama ?? '')) }}
                </div>
                
                <p style="font-size: 10pt; margin: 5px 0 0 0; line-height: 1.2;">
                    {{ $laporan?->ukpd?->alamat ?? ($laporan?->sounding?->kapal?->ukpd?->alamat ?? '') }}<br>
                    Website: www.dishub.jakarta.go.id &nbsp;&nbsp;&nbsp; E-mail : {{ $laporan?->ukpd?->email ?? ($laporan?->sounding?->kapal?->ukpd?->email ?? '') }}
                </p>
    
                <table style="width: 100%; font-size: 10pt; margin-top: 0;">
                    <tr>
                        <td style="text-align: center; padding-left: 50px;">
                            <span style="letter-spacing: 3px; font-size: 10pt;">JAKARTA</span>
                        </td>
                        <td style="text-align: right; width: 100px;">
                            Kode Pos : {{ $laporan?->ukpd?->kode_pos ?? ($laporan?->sounding?->kapal?->ukpd?->kode_pos ?? '') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="main-header">
        <tr>
            <td style="width: 45%;">
                <table class="sub-header-left">
                    <tr>
                        <td style="width: 30%;">Nomor</td>
                        <td>: {{ $laporan->nomor ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Klasifikasi</td>
                        <td>: {{ $laporan->klasifikasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>: {{ $laporan->lampiran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-top:5px;">Perihal</td>
                        <td style="padding-top:5px;">: <span class="font-bold">{{ $laporan->perihal }}</span></td>
                    </tr>
                </table>
            </td>
            <td>
            </td>
            <td style="width: 45%;">
                <table class="sub-header-right">
                    <tr>
                        <td style="width: 25%;">Jakarta,</td>
                        <td>{{ $laporan->tanggal_surat ? \Carbon\Carbon::parse($laporan->tanggal_surat)->translatedFormat('d F Y') : '........................' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 10px;">Kepada</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Pejabat Pelaksana Teknis Kegiatan<br>
                            BBM Kapal {{ $laporan?->ukpd?->nama ?? ($laporan?->sounding?->kapal?->ukpd?->nama ?? '') }}<br>
                            Dinas Perhubungan Provinsi DKI<br>
                            di<br>
                            Jakarta
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="mt-20">
        <p>Nama Kapal : <b>{{ $laporan->sounding->kapal->nama_kapal ?? '-' }}</b></p>
    </div>

    <table class="table-border mt-10">
        <thead>
            <tr class="text-center">
                <th style="width: 8%;">No</th>
                <th>Nakhoda / ABK</th>
                <th style="width: 25%;">Tanggal</th>
                <th style="width: 25%;">Jumlah BBM Sebelum <br> Pengisian (Liter)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>{{ $laporan->nama_nakhoda }}</td>
                <td class="text-center">
                    {{ $laporan->sounding ? \Carbon\Carbon::parse($laporan->sounding->created_at)->format('d/m/Y') : \Carbon\Carbon::parse($laporan->tanggal_surat)->format('d/m/Y') }}
                </td>
                <td class="text-center font-bold">
                    {{ $laporan->sounding ? number_format(floatval($laporan->sounding->bbm_akhir), 2, ',', '.') : '0,00' }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td style="width: 50%;">
                    Mengetahui,<br>
                    Nakhoda Kapal {{ $laporan->sounding->kapal->nama_kapal ?? '-' }}
                </td>
                <td style="width: 50%; padding: 0 10%">
                    <br>
                    Pengawas {{ $laporan?->ukpd?->nama ?? ($laporan?->sounding?->kapal?->ukpd?->nama ?? '') }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <br><br><br>
                    <span class="font-bold underline">{{ $laporan->nama_nakhoda }}</span> <br>
                    <span class="font-bold">ID.{{ $laporan->id_nakhoda }}</span>
                </td>
                <td style="width: 50%; padding: 0 10%">
                    <br><br><br>
                    <span class="font-bold underline">{{ $laporan->nama_pengawas }}</span> <br>
                    <span class="font-bold">ID.{{ $laporan->id_pengawas }}</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>