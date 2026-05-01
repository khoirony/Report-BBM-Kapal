<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Pengisian BBM</title>
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
                <div style="font-size: 13pt; margin: 0; padding: 0; line-height: 1.2; white-space: nowrap;">
                    PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA
                </div>
                <div style="font-size: 13pt; margin: 0; padding: 0; line-height: 1.2; white-space: nowrap;">
                    DINAS PERHUBUNGAN
                </div>
                <div style="font-size: 16pt; font-weight: bold; margin: 0; padding: 0; line-height: 1.2;">
                    {{ strtoupper($surat?->LaporanSisaBbm?->sounding?->kapal?->ukpd?->nama ?? '-') }}
                </div>
                <div style="font-size: 10pt; margin: 0; padding: 0; line-height: 1.2;">
                    {{ $surat?->LaporanSisaBbm?->sounding?->kapal?->ukpd?->alamat ?? '-' }}
                </div>
                <div style="font-size: 10pt; margin: 0; padding: 0; line-height: 1.2;">
                    Website: <span style="color: blue; text-decoration: underline;">www.dishub.jakarta.go.id</span> &nbsp;&nbsp;&nbsp; 
                    E-mail: {{ $surat?->LaporanSisaBbm?->sounding?->kapal?->ukpd?->email ?? '-' }}
                </div>
                <div style="margin: 0; padding: 0; line-height: 1.2;">
                    <span style="letter-spacing: 3px; font-size: 10pt;">JAKARTA</span>
                </div>
                <div style="text-align: right; margin: 0; padding: 0; line-height: 1.2;">
                    <span style="font-size: 10pt;">Kode Pos : {{ $surat?->LaporanSisaBbm?->ukpd?->kode_pos ?? ($surat?->LaporanSisaBbm?->sounding?->kapal?->ukpd?->kode_pos ?? '-') }}</span>
                </div>
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
                        <td style="width: 70%; border: none; padding: 2px 0; vertical-align: top;">{{ $surat->nomor_surat ?? ' ' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">Klasifikasi</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">:</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">{{ $surat->klasifikasi ?? 'Penting' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">Lampiran</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">:</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">{{ $surat->lampiran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">Perihal</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">:</td>
                        <td style="border: none; padding: 2px 0; vertical-align: top;">
                            <span>Permohonan Pengisian<br>BBM Kapal</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; border: none; padding: 2px 0 2px 20px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="2" style="text-align: left; border: none; padding: 2px 0; padding-left: 50px; vertical-align: top;">
                            Jakarta, <span>{{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : '........................' }}</span>
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
                            Kepala {{ $surat?->LaporanSisaBbm?->sounding?->kapal?->ukpd?->nama ?? '....................' }}<br>
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

    <div style="margin-top: 20px; text-align: justify;">
        <p style="text-indent: 50px; margin-bottom: 10px; margin-top: 0;">
            Bersama ini kami mengajukan permohonan surat pengisian Bahan Bakar Minyak guna memenuhi kebutuhan operasional kapal kami yang akan dilaksanakan pada ...................... pukul ............ Adapun kapal yang dimaksud adalah <strong style="font-weight: bold;">{{ $surat?->LaporanSisaBbm?->sounding?->kapal?->nama_kapal ?? '........................' }}</strong>
        </p>
        <p style="text-indent: 50px; margin-bottom: 10px; margin-top: 0;">Berikut nama beserta jabatan yang bertanggung jawab atas nama kapal yang di maksud sebagai berikut :</p>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin: 10px 0;">
        <thead>
            <tr>
                <th style="width: 8%; border: 1px solid black; padding: 4px 8px; height: 22px;">No</th>
                <th style="border: 1px solid black; padding: 4px 8px; height: 22px;">NAMA</th>
                <th style="width: 40%; border: 1px solid black; padding: 4px 8px; height: 22px;">JABATAN</th>
            </tr>
        </thead>
        <tbody>
            @php $maxRows = 7; @endphp
            @for($i = 0; $i < $maxRows; $i++)
                @php $petugas = $surat->petugas[$i] ?? null; @endphp
                <tr>
                    <td style="text-align: center; border: 1px solid black; padding: 4px 8px; height: 22px;">{{ $i + 1 }}.</td>
                    <td style="border: 1px solid black; padding: 4px 8px; height: 22px;">{{ $petugas ? $petugas->nama_petugas : ' ' }}</td>
                    <td style="border: 1px solid black; padding: 4px 8px; height: 22px;">{{ $petugas ? $petugas->jabatan : ' ' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div style="margin-top: 10px;">
        <p style="margin: 0 0 10px 0;">Adapun kebutuhan Bahan Bakar Minyak (BBM) yang di gunakan untuk Kapal tersebut jenis {{ $surat->jenis_bbm ?? 'Pertamax / Dexlite' }} dengan jumlah kebutuhan {{ number_format($surat->jumlah_bbm, 0, ',', '.') ?? '..........' }} Liter</p>
        
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 35%; padding: 1px 0;">Perusahaan Penyedia BBM</td>
                <td style="padding: 1px 0;">: {{ $surat->penyedia->name ?? '' }}</td>
            </tr>
            <tr>
                <td style="padding: 1px 0;">Jenis Penyedia BBM</td>
                <td style="padding: 1px 0;">: {{ $surat->jenis_penyedia_bbm ?? ' ' }}</td>
            </tr>
            <tr>
                <td style="padding: 1px 0;">Tempat Pengambilan</td>
                <td style="padding: 1px 0;">: {{ $surat->tempat_pengambilan_bbm ?? ' ' }}</td>
            </tr>
            <tr>
                <td style="padding: 1px 0;">Nomor SPBU/Agen/Penyedia</td>
                <td style="padding: 1px 0;">: {{ $surat->nomor_spbu ?? ' ' }}</td>
            </tr>
            <tr>
                <td style="padding: 1px 0;">Lokasi Pengisian</td>
                <td style="padding: 1px 0;">: ................................</td>
            </tr>
            <tr>
                <td style="padding: 1px 0;">Metode Pengiriman</td>
                <td style="padding: 1px 0;">: {{ $surat->metode_pengiriman ?? ' ' }}</td>
            </tr>
        </table>
    </div>

    <div style="page-break-before: always;"></div>
    <p style="margin-top: 20px; text-align: justify; text-indent: 50px; margin-bottom: 0;">
        Demikian permohonan ini kami sampaikan atas perhatiannya diucapkan terima kasih.
    </p>

    <div style="width: 100%; margin-top: 30px;">
        <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    <br>
                    <br>
                    Nakhoda / ABK<br>
                    {{ $surat?->LaporanSisaBbm?->sounding?->kapal?->nama_kapal ?? '................' }}
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    Jakarta, {{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : '........................' }}<br>
                    Pejabat Pelaksana Teknis Kegiatan<br>
                    {{ $surat?->LaporanSisaBbm?->sounding?->kapal?->ukpd?->nama ?? '........................' }}<br>
                    Dinas Perhubungan Provinsi DKI Jakarta
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 80px;">
        <table style="width: 100%; border: none; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center; line-height: 1.5;">
                    <span style="text-decoration: underline;">{{ $surat->nama_nakhoda ?? '' }}</span><br>
                    ID. {{ $surat->id_nakhoda ?: '..........................................' }}
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center;  line-height: 1.5;">
                    <span style="text-decoration: underline;">{{ $surat->nama_pptk ?? '' }}</span><br>
                    NIP. {{ $surat->id_pptk ?: '..........................................' }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>