<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.5; color: black; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        
        .kop-surat { text-align: center; margin-bottom: 20px; }
        .kop-surat h3, .kop-surat h2 { margin: 0; padding: 0; font-size: 14pt; }
        
        table { width: 100%; border-collapse: collapse; }
        .table-border th, .table-border td { border: 1px solid black; padding: 6px; }
        
        .page-break { page-break-after: always; }
        .mt-20 { margin-top: 20px; }
        .mt-10 { margin-top: 10px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h3>DINAS PERHUBUNGAN</h3>
        <h3>PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h3>
    </div>

    <div class="text-center mt-20">
        <div class="font-bold underline text-lg">SURAT TUGAS</div>
        <div>NOMOR : {{ $surat->nomor_surat }}/PH.12.00</div>
    </div>

    <div class="text-center font-bold mt-10">TENTANG PENYEDIAAN BBM KDO/KDO KHUSUS</div>

    <div class="mt-10 text-justify">
        <p>Dalam Rangka Penyediaan BBM dan Operasional Kapal Pelayanan Unit Pengelola Angkutan Perairan Dinas Perhubungan Provinsi DKI Jakarta dengan ini</p>
        
        <div class="text-center font-bold mt-10 mb-10">MENUGASKAN:</div>

        <table style="width: 100%; margin-bottom: 10px;">
            <tr>
                <td style="width: 15%; vertical-align: top;">kepada</td>
                <td style="width: 5%; vertical-align: top;">:</td>
                <td>Nama-nama terlampir</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">untuk</td>
                <td style="vertical-align: top;">:</td>
                <td>
                    <ol style="margin: 0; padding-left: 15px;">
                        <li>Melaksanakan Pengisian BBM dan Operasional Kapal di SPBU {{ $surat->LaporanSisaBbm->sounding->lokasi }};</li>
                        <li>Menambatlabuhkan kembali Kapal {{ $surat->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '........................' }} setelah pengisian BBM ke Pelabuhan Muara Angke.</li>
                    </ol>
                </td>
            </tr>
        </table>

        <div class="font-bold">Waktu Pelaksanaan</div>
        <table style="width: 100%; margin-bottom: 10px;">
            <tr>
                <td style="width: 25%;">hari</td>
                <td style="width: 75%;">: {{ $surat->LaporanSisaBbm->sounding->hari }}</td>
            </tr>
            <tr>
                <td>tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($surat->LaporanSisaBbm->sounding->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>lokasi pengisian</td>
                <td>: {{ $surat->LaporanSisaBbm->sounding->lokasi }}</td>
            </tr>
            <tr>
                <td>pukul</td>
                <td>: {{ $surat->waktu_pelaksanaan }} WIB</td>
            </tr>
        </table>

        <div style="padding-left: 20px; margin-bottom: 15px;">
            - melaporkan hasil kegiatan kepada atasan langsung dan koordinasi dengan unit terkait;
        </div>

        <div>dengan ketentuan :</div>
        <div style="padding-left: 20px;">
            - Surat tugas ini dibuat untuk dilaksanakan sebaik–baiknya dan penuh tanggung jawab.
        </div>
    </div>

    <table style="width: 100%; margin-top: 40px;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center;">
                Dikeluarkan di Jakarta<br>
                pada tanggal {{ \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->translatedFormat('d F Y') }}
                <br><br>
                Kepala Unit Pengelola Angkutan Perairan<br>
                Dinas Perhubungan Provinsi DKI Jakarta,
                <br><br><br><br><br>
                <span class="font-bold underline">Muhamad Widan Anwar</span><br>
                NIP. 197509201998031004
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <table style="width: 100%; font-size: 10pt;">
        <tr>
            <td style="width: 20%; vertical-align: top;">Lampiran</td>
            <td style="width: 80%;">: Surat Tugas Penyediaan BBM KDO/KDO Khusus</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>: {{ $surat->nomor_surat }}/PH.12.00</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table class="table-border mt-20">
        <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 45%;">NAMA</th>
            <th style="width: 45%;">INSTANSI</th>
        </tr>
        @php
            $petugasList = $surat->LaporanSisaBbm->petugas_list ?? [];
        @endphp
        @for($i = 0; $i < 7; $i++)
        <tr>
            <td class="text-center">{{ $i + 1 }}.</td>
            <td>{{ isset($petugasList[$i]['nama']) ? $petugasList[$i]['nama'] : '' }}</td>
            <td class="text-center">Unit Pengelola Angkutan Perairan (UPAP)</td>
        </tr>
        @endfor
    </table>

</body>
</html>