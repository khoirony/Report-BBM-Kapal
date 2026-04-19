<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sisa BBM - {{ $laporan->sounding->kapal->nama_kapal ?? '-' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12pt; line-height: 1.1; color: black; margin: 0; padding: 10px 20px;">

    {{-- KOP Surat --}}
    <table style="width: 100%; border-collapse: collapse; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 5px;">
        <tr>
            <td style="width: 15%; text-align: center; vertical-align: top; padding-top: 5px;">
                <img src="{{ public_path('img/logo-jaya-raya.jpg') }}" style="height: 120px; width: 100px; object-fit: contain;" alt="Logo">
            </td>
            <td style="width: 85%; text-align: center; vertical-align: middle;">
                <div style="font-size: 13pt; margin: 0; padding: 0; line-height: 1.2; white-space: nowrap;">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
                <div style="font-size: 13pt; margin: 0; padding: 0; line-height: 1.2; white-space: nowrap;">DINAS PERHUBUNGAN</div>
                <div style="font-size: 16pt; font-weight: bold; margin: 0; padding: 0; line-height: 1.2;">{{ strtoupper($laporan?->ukpd?->nama ?? ($laporan?->sounding?->kapal?->ukpd?->nama ?? '')) }}</div>
                <div style="font-size: 10pt; margin: 0; padding: 0; line-height: 1.2;">{{ $laporan?->ukpd?->alamat ?? ($laporan?->sounding?->kapal?->ukpd?->alamat ?? '') }}</div>
                <div style="font-size: 10pt; margin: 0; padding: 0; line-height: 1.2;">Website: <span style="color: blue; text-decoration: underline;">www.dishub.jakarta.go.id</span> &nbsp;&nbsp;&nbsp; E-mail: {{ $laporan?->ukpd?->email ?? ($laporan?->sounding?->kapal?->ukpd?->email ?? '') }}</div>
                <div style="margin: 0; padding: 0; line-height: 1.2;"><span style="letter-spacing: 3px; font-size: 10pt;">JAKARTA</span></div>
                <div style="text-align: right; margin: 0; padding: 0; line-height: 1.2;"><span style="font-size: 10pt;">Kode Pos : {{ $laporan?->ukpd?->kode_pos ?? ($laporan?->sounding?->kapal?->ukpd?->kode_pos ?? '-') }}</span></div>
            </td>
        </tr>
    </table>

    {{-- ISI Surat --}}
    <table style="width: 100%; border-collapse: collapse; margin-top: 0; border: none;">
        <tr>
            <td style="width: 50%; border: none; padding: 2px 0; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 25%; border: none; padding: 2px 0; vertical-align: top;">Nomor</td>
                        <td style="width: 5%; border: none; padding: 2px 0; vertical-align: top;">:</td>
                        <td style="width: 70%; border: none; padding: 2px 0; vertical-align: top;">{{ $laporan->nomor ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">Klasifikasi</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">:</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">{{ $laporan->klasifikasi ?? 'Penting' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">Lampiran</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">:</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">{{ $laporan->lampiran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">Perihal</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">:</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">
                            Laporan Perhitungan<br>
                            Jumlah Sisa BBM Kapal<br>
                            Sebelum Pengisian
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; border: none; padding: 2px 0 2px 20px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="text-align: left; border: none; padding: 2px 0; padding-left: 50px; vertical-align: top;">
                            Jakarta, <span style="font-weight: bold;">{{ $laporan->tanggal_surat ? \Carbon\Carbon::parse($laporan->tanggal_surat)->translatedFormat('d F Y') : '(tanggal bulan tahun)' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: none; padding: 2px 0; padding-left: 50px; vertical-align: top;">
                            <br><br>
                            Kepada
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 10%; border: none; padding: 2px 0; vertical-align: top;">Yth.</td>
                        <td style="width: 90%; border: none; padding: 2px 0; vertical-align: top;">
                            Pejabat Pelaksana Teknis Kegiatan<br>
                            BBM Kapal {{ $laporan?->ukpd?->nama ?? ($laporan?->sounding?->kapal?->ukpd?->nama ?? '..........') }}<br>
                            Dinas Perhubungan Provinsi DKI<br>
                            Jakarta<br>
                            di<br>
                            <span style="padding-left: 20px;">Jakarta</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Judul Laporan --}}
    <div style="text-align: center; font-weight: bold; margin-top: 40px; margin-bottom: 30px; font-size: 12pt;">
        LAPORAN PERHITUNGAN JUMLAH SISA BBM KAPAL SEBELUM PENGISIAN
    </div>

    {{-- Nama Kapal --}}
    <div>
        Nama Kapal : {{ $laporan->sounding->kapal->nama_kapal ?? '..........................................................' }}
    </div>

    {{-- Tabel Utama --}}
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 4px; text-align: center; width: 5%;">No</th>
                <th style="border: 1px solid black; padding: 4px; text-align: center; width: 30%;">Nakhoda/<br>ABK</th>
                <th style="border: 1px solid black; padding: 4px; text-align: center; width: 30%;">Tanggal</th>
                <th style="border: 1px solid black; padding: 4px; text-align: center; width: 35%;">Jumlah BBM <br> Sebelum Pengisian (Liter)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; padding: 20px 0; text-align: center;">1</td>
                <td style="border: 1px solid black; padding: 4px; text-align: center;">
                    {{ $laporan->nama_nakhoda ?? '..............................' }}
                </td>
                <td style="border: 1px solid black; padding: 4px; text-align: center;">
                    {{ $laporan->sounding ? \Carbon\Carbon::parse($laporan->sounding->created_at)->format('d/m/Y') : (\Carbon\Carbon::parse($laporan->tanggal_surat)->format('d/m/Y') ?? '..............................') }}
                </td>
                <td style="border: 1px solid black; padding: 4px; text-align: center;">
                    {{ $laporan->sounding ? number_format(floatval($laporan->sounding->bbm_akhir), 2, ',', '.') : '..............................' }}
                </td>
            </tr>
        </tbody>
    </table>
    <br><br><br><br>

    {{-- Area Tanda Tangan --}}
    <table style="width: 100%; border-collapse: collapse; margin-top: 40px; border: none;">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding: 0;">
                Menyetujui,<br>
                Nakhoda Kapal {{ $laporan->sounding->kapal->nama_kapal ?? '(Nama Kapal)' }}
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding: 0;">
                Mengetahui,<br>
                Pengawas {{ $laporan?->ukpd?->nama ?? ($laporan?->sounding?->kapal?->ukpd?->nama ?? '(Nama UKPD)') }}
            </td>
        </tr>
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding-top: 80px;">
                <div style="text-align: center;">
                    <span style="width: 250px; text-align: center; padding-bottom: 2px;">{{ $laporan->nama_nakhoda ?? '' }}</span><br>
                    ID. {{ $laporan->id_nakhoda ?? '' }}
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding-top: 80px;">
                <div style="text-align: center;">
                    <span style="width: 250px; text-align: center; padding-bottom: 2px;">{{ $laporan->nama_pengawas ?? '' }}</span><br>
                    NIP. {{ $laporan->id_pengawas ?? '' }} 
                </div>
            </td>
        </tr>
    </table>

</body>
</html>