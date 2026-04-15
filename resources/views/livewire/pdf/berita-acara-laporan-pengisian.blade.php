<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan BBM {{ $laporan->kapal->nama_kapal }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.2; color: black; margin: 0; padding: 10px 20px; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        
        /* Kop Surat */
        .kop-table { width: 100%; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 15px; }
        .logo-container { width: 15%; text-align: center; vertical-align: middle; }
        .logo-container img { width: 75px; height: auto; }
        .kop-text { width: 85%; text-align: center; vertical-align: middle; padding-bottom: 10px; }
        .kop-text div { margin: 0; padding: 0; line-height: 1.2; }
        .instansi { font-size: 13pt; font-weight: bold; }
        .ukpd { font-size: 15pt; font-weight: bold; margin: 2px 0 !important; }

        /* Content */
        .content { padding: 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        .table-border th, .table-border td { border: 1px solid black; padding: 4px; }
        
        /* Layout Tanda Tangan */
        .signature-section { width: 100%; margin-top: 20px; }
        .signature-table td { width: 50%; text-align: center; vertical-align: top; padding-bottom: 60px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        
        .page-break { page-break-after: always; }
        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>

    <table class="kop-table" style="width: 100%; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 15px;">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('img/logo-jaya-raya.jpg') }}" alt="Logo">
            </td>
            <td class="kop-text">
                <div class="instansi" style="font-size: 14pt; font-weight: bold; margin: 0; white-space: nowrap;">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
                <div class="instansi" style="font-size: 14pt; font-weight: bold; margin: 0; white-space: nowrap;">DINAS PERHUBUNGAN</div>
                <div class="ukpd">{{ strtoupper($laporan?->kapal?->ukpd?->nama ?? '') }}</div>
                <div style="font-size: 10pt;">{{ $laporan?->kapal?->ukpd?->alamat }}</div>
                <div style="font-size: 10pt;">Website: <span style="color: blue; text-decoration: underline;">www.dishub.jakarta.go.id</span> &nbsp;&nbsp;&nbsp; E-mail: {{ $laporan?->kapal?->ukpd?->email }}</div>
                <table style="width: 100%; font-size: 10pt; margin-top: 0;">
                    <tr>
                        <td style="text-align: center; padding-left: 50px;">
                            <span style="letter-spacing: 3px; font-size: 10">JAKARTA</span>
                        </td>
                        <td style="text-align: right; width: 100px;">
                            Kode Pos : {{ $laporan?->kapal?->ukpd?->kode_pos ?? '-' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="content">
        <div class="text-center font-bold" style="font-size: 12pt;">
            BERITA ACARA LAPORAN PENGISIAN BAHAN BAKAR<br>
            MINYAK KENDARAAN DINAS KAPAL {{ strtoupper($laporan->kapal->nama_kapal) }}<br>
            NOMOR: {{ $laporan->suratPermohonan->nomor_surat }}<br>
            Tanggal : {{ $laporan->suratPermohonan->tanggal_surat }}
        </div>

        <div class="mt-10 text-justify">
            <p>Pada hari ini <b>{{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('l') : '................' }}</b> 
                tanggal <b>{{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('d') : '................' }}</b> 
                bulan <b>{{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('F') : '................' }}</b> 
                tahun <b>{{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('Y') : '................' }}</b> 
                bertempat di {{ $laporan->suratPermohonan->suratTugas->lokasi ?? '-' }}</p>
            
            <p style="margin-bottom: 5px;">Berdasarkan:</p>
            <table style="width: 100%; vertical-align: top;">
                <tr><td style="width: 25px;">a.</td><td>Peraturan Gubernur Daerah Khusus Ibukota Jakarta Nomor 75 Tahun 2021 tentang Pemberian Bahan Bakar Minyak Kendaraan Dinas;</td></tr>
                <tr><td style="vertical-align: top;">b.</td><td>Perjanjian Kerja Sama (PKS) Penyediaan Bahan Bakar Minyak Kendaraan Dinas Operasional (KDO) Khusus <b>{{ $laporan?->kapal?->ukpd?->nama }}</b> Dinas Perhubungan Provinsi DKI Jakarta dengan <b>{{ $laporan->suratPermohonan->penyedia->name ?? '-' }}</b> Nomor ............................................... Tanggal ...............................................; dan</td></tr>
                <tr><td style="vertical-align: top;">c.</td><td>Surat Permohonan Nomor ................................... perihal Permohonan Pengisian Bahan Bakar Minyak pada tanggal ................................... .</td></tr>
            </table>

            <p class="mt-10">Awak Kapal yang bertugas:</p>
            <table class="table-border">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 45%;">Nama</th>
                        <th style="width: 30%;">Jabatan</th>
                        <th style="width: 20%;">Paraf</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 7; $i++)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}.</td>
                        <td>{{ $laporan->petugas_list[$i]['nama'] ?? '' }}</td>
                        <td>{{ $laporan->petugas_list[$i]['jabatan'] ?? '' }}</td>
                        <td></td>
                    </tr>
                    @endfor
                </tbody>
            </table>

            <p class="mt-10">Demikian Berita Acara Pengisian Bahan Bakar Minyak ini dibuat dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="signature-section">
            <table class="w-full">
                <tr>
                    <td class="text-center" style="width: 50%;">
                        Penyedia BBM<br><br><br><br><br>
                        (.........................................)<br>
                        PT. Universal Energi Nusantara
                    </td>
                    <td class="text-center" style="width: 50%;">
                        Pejabat Pelaksana Teknis Kegiatan<br>
                        (Unit Pengelola Angkutan Perairan)<br><br><br><br>
                        <span class="signature-name">M. Fauzi Syu'aib, ST, M.Sc</span><br>
                        NIP. 198007202009011001
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center" style="padding-top: 30px;">
                        Mengetahui,<br>
                        Kepala Unit Pengelola Angkutan Perairan<br>
                        Dinas Perhubungan Provinsi DKI Jakarta<br><br><br><br>
                        <span class="signature-name">Muhamad Widan Anwar</span><br>
                        NIP. 197509201998031004
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="content">
        <table style="font-size: 10pt;">
            <tr><td style="width: 15%;">Lampiran</td><td>: Berita Acara Pengisian Bahan Bakar Minyak Kendaraan Dinas Kapal {{ $laporan->kapal->nama_kapal }}</td></tr>
            <tr><td>Nomor</td><td>: .................................../PH.12.00</td></tr>
            <tr><td>Tanggal</td><td>: ................................... 2026</td></tr>
        </table>

        <div class="text-center font-bold mt-10" style="font-size: 12pt;">LAPORAN PENGISIAN BBM KAPAL</div>

        <table style="width: 100%; margin-top: 10px;">
            <tr><td style="width: 140px;">1. Hari</td><td>: .......................................................................</td></tr>
            <tr><td>2. Tanggal</td><td>: .......................................................................</td></tr>
            <tr><td>3. Dasar Hukum</td><td>: .......................................................................</td></tr>
            <tr><td>4. Petugas</td><td>: .......................................................................</td></tr>
        </table>

        <table class="table-border mt-10">
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Nama</th>
                <th style="width: 30%;">Jabatan</th>
                <th style="width: 20%;">Paraf</th>
            </tr>
            @for($i = 0; $i < 7; $i++)
            <tr>
                <td class="text-center">{{ $i + 1 }}.</td>
                <td>{{ $laporan->petugas_list[$i]['nama'] ?? '' }}</td>
                <td>{{ $laporan->petugas_list[$i]['jabatan'] ?? '' }}</td>
                <td></td>
            </tr>
            @endfor
        </table>

        <table style="width: 100%; margin-top: 10px;">
            <tr><td style="width: 140px; vertical-align: top;">5. Kegiatan</td><td>: Pengisian BBM Kapal di Pelabuhan Sunda Kelapa</td></tr>
            <tr><td style="vertical-align: top;">6. Tujuan</td><td>: Meningkatkan Ketersediaan BBM Kapal untuk Menunjang Kegiatan Operasional</td></tr>
            <tr><td style="vertical-align: top;">7. Lokasi</td><td>: .......................................................................</td></tr>
        </table>

        <div class="font-bold mt-10">8. Kondisi Kapal</div>
        <table style="width: 100%;">
            <tr><td style="width: 25px;">a.</td><td style="width: 200px;">Nama Kapal</td><td>: <b>{{ $laporan->kapal->nama_kapal }}</b></td></tr>
            <tr><td>b.</td><td>BBM Awal Di Pom Bensin</td><td>: ........................................... Liter</td></tr>
            <tr><td>c.</td><td>Pengisian</td><td>: ........................................... Liter</td></tr>
            <tr><td>d.</td><td>Pemakaian BBM dari Pom</td><td>: ........................................... Liter</td></tr>
            <tr><td>e.</td><td>BBM Akhir</td><td>: ........................................... Liter</td></tr>
            <tr><td>f.</td><td>Jam Berangkat</td><td>: ........................................... WIB</td></tr>
            <tr><td>g.</td><td>Jam Kembali</td><td>: ........................................... WIB</td></tr>
        </table>

        <table class="signature-section" style="margin-top: 30px;">
            <tr>
                <td class="text-center" style="width: 50%;">
                    Petugas SPBU<br><br><br><br><br>
                    (.........................................)
                </td>
                <td class="text-center" style="width: 50%;">
                    Nakhoda<br><br><br><br><br>
                    (.........................................)
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center" style="padding-top: 30px;">
                    Kepala Unit Pengelola Angkutan Perairan<br>
                    Dinas Perhubungan Provinsi DKI Jakarta<br><br><br><br>
                    <span class="signature-name">Muhamad Widan Anwar</span><br>
                    NIP. 197509201998031004
                </td>
            </tr>
        </table>
    </div>

</body>
</html>