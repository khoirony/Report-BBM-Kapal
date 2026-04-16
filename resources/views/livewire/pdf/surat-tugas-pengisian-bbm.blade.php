<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Tugas</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.1; color: black; margin: 0; padding: 10px 30px; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .uppercase { text-transform: uppercase; }
        
        /* Kop Surat Logo Tengah */
        .kop-surat { text-align: center; margin-bottom: 25px; }
        .kop-surat img { width: 70px; height: auto; margin-bottom: 10px; }
        .kop-surat div { font-size: 14pt; margin: 0; font-weight: normal; line-height: 1.2; }
        
        .title-section { margin-top: 20px; margin-bottom: 20px; }
        .title-section div { font-size: 12pt; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; }
        .table-no-border td { vertical-align: top; padding: 2px 0; }
        
        /* List Custom */
        .list-tugas { padding-left: 20px; margin: 0; }
        .list-tugas li { margin-bottom: 10px; list-style-type: decimal; }
        .sub-list { list-style-type: decimal; padding-left: 20px; }

        .signature-wrapper { margin-top: 40px; width: 100%; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; }

        .page-break { page-break-after: always; }
        .table-border th, .table-border td { border: 1px solid black; padding: 6px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="{{ public_path('img/logo-jaya-raya.jpg') }}" alt="Logo">
        <div class="uppercase">{{ strtoupper($surat?->ukpd?->nama ?? '-') }}</div>
        <div class="uppercase">DINAS PERHUBUNGAN</div>
        <div class="uppercase">PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
    </div>

    <div class="title-section text-center">
        <div class="uppercase">SURAT PERINTAH TUGAS</div>
        <br>
        <div style="font-weight: normal;">NOMOR : {{ $surat->nomor_surat ?? '          /PH.12.00' }}</div>
    </div>

    <div class="text-center font-bold mb-4">
        TENTANG<br>
        PENYEDIAAN BBM KDO/KDO KHUSUS
    </div>

    <div class="text-justify mt-10">
        <p style="text-indent: 50px; margin-bottom: 10px;">
            Dalam Rangka Penyediaan BBM Kapal, Kepala <span>{{ $surat?->ukpd?->nama ?? '.........' }}</span> Dinas Perhubungan Provinsi DKI Jakarta dengan ini
        </p>
        
        <div class="text-center font-bold mt-10 mb-10">MENUGASKAN:</div>

        <table class="table-no-border">
            <tr>
                <td style="width: 15%;">kepada</td>
                <td style="width: 3%;">:</td>
                <td style="width: 82%;">Nama-nama terlampir</td>
            </tr>
            <tr>
                <td>untuk</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>
        
        <ol class="list-tugas">
            <li>
                Melaksanakan Pengisian BBM untuk melakukan kegiatan Operasional Kapal 
                <strong>KM. {{ $surat->laporanSisaBbm->sounding->kapal->nama_kapal ?? '...............' }}</strong> di 
                <strong>{{ $surat->lokasi ?? '...............' }}</strong>;
            </li>
            <li>
                Menambatlabuhkan kembali Kapal 
                <strong>KM. {{ $surat->laporanSisaBbm->sounding->kapal->nama_kapal ?? '...............' }}</strong> setelah pengisian BBM ke Pelabuhan Muara Angke dengan ketentuan:
                
                <ol class="sub-list" style="margin-top: 5px;">
                    <li>
                        Waktu Pelaksanaan
                        <table style="margin-left: -5px;">
                            <tr>
                                <td style="width: 140px;">Hari</td>
                                <td style="width: 10px;">:</td>
                                <td>{{ $surat->tanggal_dikeluarkan ? \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->locale('id')->translatedFormat('l') : '.....................' }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td>{{ $surat->tanggal_dikeluarkan ? \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->translatedFormat('d F Y') : '.....................' }}</td>
                            </tr>
                            <tr>
                                <td>Pukul</td>
                                <td>:</td>
                                <td>{{ $surat->waktu_pelaksanaan ?? '.....................' }} WIB</td>
                            </tr>
                            <tr>
                                <td>Lokasi pengisian</td>
                                <td>:</td>
                                <td>{{ $surat->lokasi ?? '.....................' }}</td>
                            </tr>
                            <tr>
                                <td>Pakaian</td>
                                <td>:</td>
                                <td>{{ $surat->pakaian ?? '.....................' }}</td>
                            </tr>
                        </table>
                    </li>
                    <li style="margin-top: 5px;">Melaporkan hasil kegiatan kepada atasan langsung dan koordinasi dengan unit terkait;</li>
                    <li>Surat Tugas ini berlaku sesuai dengan waktu pelaksanaan.</li>
                </ol>
            </li>
        </ol>


        <p class="mt-10" style="text-indent: 50px; margin-bottom: 10px;">
            Surat tugas ini dibuat untuk dilaksanakan sebaik–baiknya dan penuh tanggung jawab.
        </p>
    </div>

    <div class="signature-wrapper">
        <table class="signature-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;">
                    Dikeluarkan di Jakarta<br>
                    Pada tanggal {{ $surat->tanggal_dikeluarkan ? \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->translatedFormat('d F Y') : '.....................' }}<br>
                    {{ $surat?->ukpd?->nama ?? '-' }}<br>
                    Dinas Perhubungan Provinsi DKI Jakarta
                    <br><br><br><br><br>
                    <span class="font-bold underline">{{ $surat?->ukpd?->kepalaUkpd?->name ?? '-' }}</span><br>
                    NIP. {{ $surat?->ukpd?->kepalaUkpd?->nip ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <div style="margin-bottom: 20px;">
        <table>
            <tr><td style="width: 15%;">Lampiran</td><td>: Surat Tugas Penyediaan BBM KDO/KDO Khusus</td></tr>
            <tr><td>Nomor</td><td>: {{ $surat->nomor_surat ?? '          /PH.12.00' }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ $surat->tanggal_dikeluarkan ? \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->translatedFormat('d F Y') : '.....................' }}</td></tr>
        </table>
    </div>

    <table class="table-border mt-20">
        <tr style="background-color: #f2f2f2;">
            <th style="width: 10%;">No</th>
            <th style="width: 45%;">NAMA</th>
            <th style="width: 45%;">JABATAN</th>
        </tr>
        
        @forelse($surat->petugas as $index => $petugas)
        <tr>
            <td class="text-center">{{ $index + 1 }}.</td>
            <td>{{ $petugas->nama_petugas }}</td>
            <td>{{ $petugas->jabatan }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">Belum ada daftar petugas.</td>
        </tr>
        @endforelse
    </table>

</body>
</html>