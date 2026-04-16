<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Tugas</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12pt; line-height: 1.1; color: black; margin: 0; padding: 10px 30px;">
    
    <div style="text-align: center; margin-bottom: 10px; line-height: 1.3;">
        <img src="{{ public_path('img/logo-jaya-raya.jpg') }}" style="height: 90px; width: 80px; object-fit: contain;" alt="Logo">
        <div style="font-size: 16pt; margin: 0; font-weight: normal; text-transform: uppercase; margin-top: 10px;">{{ strtoupper($surat?->ukpd?->nama ?? '-') }}</div>
        <div style="font-size: 16pt; margin: 0; font-weight: normal; text-transform: uppercase;">DINAS PERHUBUNGAN</div>
        <div style="font-size: 16pt; margin: 0; font-weight: normal; text-transform: uppercase;">PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
    </div>

    <div style="margin-bottom: 20px; text-align: center; line-height: 2;">
        <div style="font-size: 12pt; text-transform: uppercase;">SURAT PERINTAH TUGAS</div>
        <div style="font-weight: normal;">NOMOR : {{ $surat->nomor_surat ?? '          /PH.12.00' }}</div>
    </div>

    <div style="text-align: center; margin-bottom: 16px;">
        TENTANG<br>
        PENYEDIAAN BBM KDO/KDO KHUSUS
    </div>

    <div style="text-align: justify; margin-top: 10px;">
        <span style="margin-bottom: 10px; margin-left: 40px">
            Dalam Rangka Penyediaan BBM Kapal, Kepala <span style="font-weight: bold;">{{ $surat?->ukpd?->nama ?? '.........' }}</span> Dinas Perhubungan Provinsi DKI Jakarta dengan ini
        </span>
        
        <div style="text-align: center; margin-bottom: 10px;">MENUGASKAN:</div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 15%; vertical-align: top; padding: 2px 0;">kepada</td>
                <td style="width: 3%; vertical-align: top; padding: 2px 0;">:</td>
                <td style="width: 82%; vertical-align: top; padding: 2px 0;">Nama-nama terlampir</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">untuk</td>
                <td style="vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;"></td>
            </tr>
        </table>
        
        <ol style="padding-left: 20px; margin: 0;">
            <li style="margin-bottom: 10px; list-style-type: decimal;">
                Melaksanakan Pengisian BBM untuk melakukan kegiatan Operasional Kapal 
                <strong style="font-weight: bold;">{{ $surat->laporanSisaBbm->sounding->kapal->nama_kapal ?? '...............' }}</strong> di 
                <strong style="font-weight: bold;">{{ $surat->lokasi ?? '...............' }}</strong>;
            </li>
            <li style="margin-bottom: 10px; list-style-type: decimal;">
                Menambatlabuhkan kembali Kapal 
                <strong style="font-weight: bold;">{{ $surat->laporanSisaBbm->sounding->kapal->nama_kapal ?? '...............' }}</strong> setelah pengisian BBM ke Pelabuhan Muara Angke dengan ketentuan:
                
                <ol style="list-style-type: decimal; padding-left: 20px; margin-top: 5px;">
                    <li style="margin-bottom: 5px;">
                        Waktu Pelaksanaan
                        <table style="width: 100%; margin-left: -5px; border-collapse: collapse;">
                            <tr>
                                <td style="width: 140px; vertical-align: top; padding: 2px 0;">Hari</td>
                                <td style="width: 10px; vertical-align: top; padding: 2px 0;">:</td>
                                <td style="vertical-align: top; padding: 2px 0;">{{ $surat->tanggal_pelaksanaan ? \Carbon\Carbon::parse($surat->tanggal_pelaksanaan)->locale('id')->translatedFormat('l') : '.....................' }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 2px 0;">Tanggal</td>
                                <td style="vertical-align: top; padding: 2px 0;">:</td>
                                <td style="vertical-align: top; padding: 2px 0;">{{ $surat->tanggal_pelaksanaan ? \Carbon\Carbon::parse($surat->tanggal_pelaksanaan)->translatedFormat('d F Y') : '.....................' }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 2px 0;">Pukul</td>
                                <td style="vertical-align: top; padding: 2px 0;">:</td>
                                <td style="vertical-align: top; padding: 2px 0;">{{ $surat->waktu_pelaksanaan ?? '.....................' }} WIB</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 2px 0;">Lokasi pengisian</td>
                                <td style="vertical-align: top; padding: 2px 0;">:</td>
                                <td style="vertical-align: top; padding: 2px 0;">{{ $surat->lokasi ?? '.....................' }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 2px 0;">Pakaian</td>
                                <td style="vertical-align: top; padding: 2px 0;">:</td>
                                <td style="vertical-align: top; padding: 2px 0;">{{ $surat->pakaian ?? '.....................' }}</td>
                            </tr>
                        </table>
                    </li>
                    <li style="margin-bottom: 5px; margin-top: 5px;">Melaporkan hasil kegiatan kepada atasan langsung dan koordinasi dengan unit terkait;</li>
                    <li style="margin-bottom: 5px;">Surat Tugas ini berlaku sesuai dengan waktu pelaksanaan.</li>
                </ol>
            </li>
        </ol>

        <div style="page-break-before: always;"></div>
        <p style="text-indent: 50px; margin-top: 10px; margin-bottom: 10px;">
            Surat tugas ini dibuat untuk dilaksanakan sebaik–baiknya dan penuh tanggung jawab.
        </p>
    </div>

    <div style="margin-top: 20px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; border: none;"></td>
                <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
                    Dikeluarkan di Jakarta<br>
                    Pada tanggal {{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : '.....................' }}<br>
                    Kepala {{ $surat?->ukpd?->nama ?? '-' }}<br>
                    Dinas Perhubungan Provinsi DKI Jakarta
                    <br><br><br><br><br>
                    <span style="font-weight: bold; text-decoration: underline;">{{ $surat?->ukpd?->kepalaUkpd?->name ?? '-' }}</span><br>
                    NIP. {{ $surat?->ukpd?->kepalaUkpd?->nip ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>

    <div style="margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 15%; vertical-align: top; padding: 2px 0;">Lampiran</td>
                <td style="vertical-align: top; padding: 2px 0;">: Surat Tugas Penyediaan BBM KDO/KDO Khusus</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Nomor</td>
                <td style="vertical-align: top; padding: 2px 0;">: {{ $surat->nomor_surat ?? '          /PH.12.00' }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Tanggal</td>
                <td style="vertical-align: top; padding: 2px 0;">: {{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : '.....................' }}</td>
            </tr>
        </table>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr style="background-color: #f2f2f2;">
            <th style="border: 1px solid black; padding: 6px; width: 10%; text-align: center;">No</th>
            <th style="border: 1px solid black; padding: 6px; width: 45%; text-align: center;">NAMA</th>
            <th style="border: 1px solid black; padding: 6px; width: 45%; text-align: center;">JABATAN</th>
        </tr>
        
        @forelse($surat->petugas as $index => $petugas)
        <tr>
            <td style="border: 1px solid black; padding: 6px; text-align: center;">{{ $index + 1 }}.</td>
            <td style="border: 1px solid black; padding: 6px;">{{ $petugas->nama_petugas }}</td>
            <td style="border: 1px solid black; padding: 6px;">{{ $petugas->jabatan }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="border: 1px solid black; padding: 6px; text-align: center;">Belum ada daftar petugas.</td>
        </tr>
        @endforelse
    </table>

</body>
</html>