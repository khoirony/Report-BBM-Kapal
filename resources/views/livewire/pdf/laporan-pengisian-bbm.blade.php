<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan BBM {{ $laporan->kapal->nama_kapal }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.5; color: black; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .w-full { width: 100%; }
        
        /* Kop Surat */
        .kop-surat { text-align: center; border-bottom: 3px solid black; margin-bottom: 15px; padding-bottom: 5px; }
        .kop-surat h3, .kop-surat h2, .kop-surat p { margin: 0; }
        .kop-surat p { font-size: 10pt; }
        
        /* Tabel Umum */
        table { width: 100%; border-collapse: collapse; }
        .table-border th, .table-border td { border: 1px solid black; padding: 5px; }
        .table-border th { background-color: #e5e5e5; }
        
        /* Layout Tanda Tangan */
        .signature-table td { width: 33%; text-align: center; vertical-align: bottom; height: 120px; }
        .signature-name { font-weight: bold; text-decoration: underline; display: block; }
        
        .page-break { page-break-after: always; }
        .mt-20 { margin-top: 20px; }
        .mt-10 { margin-top: 10px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h3>PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h3>
        <h2>DINAS PERHUBUNGAN</h2>
        <h2 class="font-bold">UNIT PENGELOLA ANGKUTAN PERAIRAN</h2>
        <p>Jln. Dermaga Ujung, Kecamatan Penjaringan, Pelabuhan Muara Angke, Jakarta Utara</p>
        <p>Telp. 43903035, Fax. 43903035 E-mail : upapkdishubdki@gmail.com</p>
        <p class="font-bold">J A K A R T A <span style="float: right;">Kode Pos : 14450</span></p>
    </div>

    <div class="text-center mt-10">
        <div class="font-bold underline">BERITA ACARA LAPORAN PENGISIAN BAHAN BAKAR MINYAK KENDARAAN DINAS KAPAL {{ strtoupper($laporan->kapal->nama_kapal) }}</div>
        <div>NOMOR: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/PH.12.00</div>
        <div>Tanggal : {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</div>
    </div>

    <div class="mt-20 text-justify">
        <p>Pada hari ini <b>{{ $laporan->hari }}</b> tanggal <b>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d') }}</b> bulan <b>{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('F') }}</b> tahun <b>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('Y') }}</b> bertempat di Stasiun Pengisian Bahan Bakar Umum (SPBU) {{ $laporan->lokasi }}</p>
        
        <p>Berdasarkan:</p>
        <ol>
            <li>Peraturan Gubernur Daerah Khusus Ibukota Jakarta Nomor 75 Tahun 2021 tentang Pemberian Bahan Bakar Minyak Kendaraan Dinas;</li>
            <li>Perjanjian Kerja Sama (PKS) Penyediaan Bahan Bakar Minyak Untuk Kendaraan Dinas antara Dinas Perhubungan Provinsi DKI Jakarta dengan PT. Universal Energi Nusantara Nomor ............................................... Tanggal ...............................................; dan</li>
            <li>Surat Permohonan Nomor {{ $laporan->dasar_hukum }} tanggal {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }} perihal Pengisian Bahan Bakar Minyak.</li>
        </ol>

        <p>Awak Kapal yang bertugas:</p>
        <table class="table-border mt-10">
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 40%;">Nama</th>
                <th style="width: 35%;">Jabatan</th>
                <th style="width: 20%;">Paraf</th>
            </tr>
            @for($i = 0; $i < 7; $i++)
            <tr>
                <td class="text-center">{{ $i + 1 }}.</td>
                <td>{{ isset($laporan->petugas_list[$i]['nama']) ? $laporan->petugas_list[$i]['nama'] : '' }}</td>
                <td>{{ isset($laporan->petugas_list[$i]['jabatan']) ? $laporan->petugas_list[$i]['jabatan'] : '' }}</td>
                <td></td>
            </tr>
            @endfor
        </table>

        <p class="mt-20">Demikian Berita Acara Pengisian Bahan Bakar Minyak ini dibuat dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Penyedia
                <br><br><br><br><br>
                <span class="signature-name">.........................................</span>
            </td>
            <td></td>
            <td>
                Pejabat Pelaksana Kegiatan<br>Unit Pengelola Angkutan Perairan
                <br><br><br><br>
                <span class="signature-name">Muhammad Fauzi</span><br>
                NIP. .........................................
            </td>
        </tr>
        <tr>
            <td colspan="3" style="height: 140px;">
                Mengetahui,<br>
                Kepala Unit Pengelola Angkutan Perairan<br>Dinas Perhubungan Provinsi DKI Jakarta
                <br><br><br><br>
                <span class="signature-name">Muhamad Widan Anwar</span><br>
                NIP. 197509201998031004
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <div>
        <table style="width: 100%; font-size: 10pt;">
            <tr>
                <td style="width: 15%; vertical-align: top;">Lampiran</td>
                <td style="width: 85%;">: Berita Acara Pengisian Bahan Bakar Minyak Kendaraan Dinas Kapal {{ $laporan->kapal->nama_kapal }}</td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td>: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/PH.12.00</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <h3 class="text-center mt-20 font-bold underline">LAPORAN PENGISIAN BBM KAPAL</h3>

    <table style="width: 100%; margin-top: 20px;">
        <tr><td style="width: 5%;">1.</td><td style="width: 25%;">Hari</td><td>: {{ $laporan->hari }}</td></tr>
        <tr><td>2.</td><td>Tanggal</td><td>: {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</td></tr>
        <tr><td>3.</td><td>Dasar Hukum</td><td>: {{ $laporan->dasar_hukum }}</td></tr>
        <tr><td style="vertical-align: top;">4.</td><td style="vertical-align: top;">Petugas</td><td>: </td></tr>
    </table>

    <table class="table-border mt-10">
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 40%;">Nama</th>
            <th style="width: 35%;">Jabatan</th>
            <th style="width: 20%;">Paraf</th>
        </tr>
        @php
            $hasPetugas = false;
        @endphp
        @for($i = 0; $i < 7; $i++)
            @if(isset($laporan->petugas_list[$i]['nama']) && $laporan->petugas_list[$i]['nama'] != '')
            @php $hasPetugas = true; @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}.</td>
                <td>{{ $laporan->petugas_list[$i]['nama'] }}</td>
                <td>{{ $laporan->petugas_list[$i]['jabatan'] }}</td>
                <td></td>
            </tr>
            @endif
        @endfor
        @if(!$hasPetugas)
        <tr><td colspan="4" class="text-center"><i>Tidak ada petugas terdata</i></td></tr>
        @endif
    </table>

    <table class="table-border mt-20">
        <tr>
            <td style="width: 30%; font-weight: bold; background-color: #f9f9f9;">Kegiatan</td>
            <td style="width: 70%;">{{ $laporan->kegiatan }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f9f9f9;">Tujuan</td>
            <td>{{ $laporan->tujuan }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f9f9f9;">Lokasi</td>
            <td>{{ $laporan->lokasi }}</td>
        </tr>
    </table>

    @php
        $firstSounding = $laporan->soundings->first();
        $lastSounding = $laporan->soundings->last();
        $totalIsi = $laporan->soundings->sum('pengisian');
        $totalPakai = $laporan->soundings->sum('pemakaian');
    @endphp

    <h4 class="mt-20">Kondisi Kapal</h4>
    <table style="width: 100%;">
        <tr>
            <td style="width: 5%;">a.</td>
            <td style="width: 40%;">Nama Kapal</td>
            <td style="width: 55%;">: <b>{{ $laporan->kapal->nama_kapal }}</b></td>
        </tr>
        <tr>
            <td>b.</td>
            <td>BBM Awal Di Pom Bensin</td>
            <td>: {{ $firstSounding ? floatval($firstSounding->bbm_awal) : 0 }} Liter</td>
        </tr>
        <tr>
            <td>c.</td>
            <td>Pengisian</td>
            <td>: {{ floatval($totalIsi) }} Liter</td>
        </tr>
        <tr>
            <td>d.</td>
            <td>Pemakaian BBM dari Pom</td>
            <td>: {{ floatval($totalPakai) }} Liter</td>
        </tr>
        <tr>
            <td>e.</td>
            <td>BBM Akhir</td>
            <td>: {{ $lastSounding ? floatval($lastSounding->bbm_akhir) : 0 }} Liter</td>
        </tr>
        <tr>
            <td>f.</td>
            <td>Jam Berangkat</td>
            <td>: {{ $firstSounding ? \Carbon\Carbon::parse($firstSounding->jam_berangkat)->format('H:i') : '-' }} WIB</td>
        </tr>
        <tr>
            <td>g.</td>
            <td>Jam Kembali</td>
            <td>: {{ $lastSounding ? \Carbon\Carbon::parse($lastSounding->jam_kembali)->format('H:i') : '-' }} WIB</td>
        </tr>
    </table>

    <table class="signature-table" style="margin-top: 40px;">
        <tr>
            <td>
                Petugas SPBU
                <br><br><br><br><br>
                (.........................................)
            </td>
            <td></td>
            <td>
                Nakhoda
                <br><br><br><br><br>
                (.........................................)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="height: 140px;">
                Kepala Unit Pengelola Angkutan Perairan<br>Dinas Perhubungan Provinsi DKI Jakarta
                <br><br><br><br><br>
                <span class="signature-name">Muhamad Widan Anwar</span><br>
                NIP. 197509201998031004
            </td>
        </tr>
    </table>

</body>
</html>