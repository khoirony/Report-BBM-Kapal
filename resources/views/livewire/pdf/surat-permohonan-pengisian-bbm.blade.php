<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Pengisian BBM - {{ $surat->suratTugas->laporanSisaBbm->sounding->kapal->nama_kapal ?? '' }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.4; color: black; margin: 0; padding: 10px; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        
        /* Kop Surat */
        .kop-surat { text-align: center; border-bottom: 3px solid black; padding-bottom: 5px; margin-bottom: 15px; }
        .kop-surat h2 { font-size: 14pt; margin: 0; }
        .kop-surat h3 { font-size: 16pt; margin: 2px 0; }
        .kop-surat .alamat { font-size: 9pt; margin-top: 5px; line-height: 1.2; }

        /* Header info (Nomor, Lampiran, dll) */
        .header-table { width: 100%; margin-bottom: 20px; border: none; }
        .header-table td { vertical-align: top; padding: 1px 0; }

        /* Tabel Petugas */
        .table-border { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .table-border th, .table-border td { border: 1px solid black; padding: 6px 10px; }
        
        .ttd-container { width: 100%; margin-top: 30px; }
        .ttd-table { width: 100%; border: none; }

        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h2>PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h2>
        <h3>DINAS PERHUBUNGAN</h3>
        <h2 class="font-bold">UNIT PENGELOLA ANGKUTAN PERAIRAN</h2>
        <p class="alamat">
            Jln. Dermaga Ujung, Kecamatan Penjaringan, Pelabuhan Muara Angke, Jakarta Utara<br>
            Telp. 43903035, Fax. 43903035 E-mail : upapkdishubdki@gmail.com<br>
            <span class="font-bold">J A K A R T A</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Kode Pos : 14450
        </p>
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 15%;">Nomor</td>
            <td style="width: 40%;">: {{ $surat->nomor_surat }}</td>
            <td style="width: 45%; text-align: right;">
                Jakarta, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td>Klasifikasi</td>
            <td>: {{ $surat->klasifikasi ?? '-' }}</td>
            <td></td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>: {{ $surat->lampiran ?? '1 (satu) berkas' }}</td>
            <td></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td class="font-bold">: Permohonan Pengisian BBM</td>
            <td></td>
        </tr>
    </table>

    <div class="mt-10">
        Yth. <br>
        <span class="font-bold">Kepala Unit Pengelola Angkutan Perairan</span><br>
        Dinas Perhubungan Provinsi DKI Jakarta<br>
        di - <br>
        <span style="margin-left: 20px;">Jakarta</span>
    </div>

    <div class="text-justify mt-20">
        <p>
            Bersama ini kami mengajukan permohonan surat pengisian Bahan Bakar Minyak guna memenuhi kebutuhan operasional kapal kami. 
            Adapun kapal yang dimaksud adalah <span class="font-bold">KM. {{ $surat->suratTugas->laporanSisaBbm->sounding->kapal->nama_kapal ?? '........................' }}</span>.
        </p>
        <p>Berikut nama beserta jabatan yang bertanggung jawab atas nama kapal yang dimaksud sebagai berikut :</p>
    </div>

    <table class="table-border">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="width: 10%;">No</th>
                <th style="width: 45%;">NAMA</th>
                <th style="width: 45%;">JABATAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surat->suratTugas->petugas as $index => $petugas)
            <tr>
                <td class="text-center">{{ $index + 1 }}.</td>
                <td>{{ $petugas->nama_petugas }}</td>
                <td>{{ $petugas->jabatan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Data petugas tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="text-justify mt-20">
        <p>
            Adapun kebutuhan Bahan Bakar Minyak (BBM) yang digunakan untuk Kapal tersebut jenis 
            <span class="font-bold">{{ $surat->jenis_bbm ?? 'Pertamax / Dexlite' }}</span> dengan jumlah kebutuhan 
            <span class="font-bold">{{ number_format($surat->jumlah_bbm, 0, ',', '.') }} Liter</span>.
        </p>
        
        <table style="width: 100%;">
            <tr>
                <td style="width: 30%;">Tempat Pengambilan</td>
                <td>: {{ $surat->tempat_pengambilan_bbm ?? 'Stasiun Pengisian Bahan Bakar Umum' }}</td>
            </tr>
            <tr>
                <td>Nomor Lembaga Penyalur</td>
                <td>: {{ $surat->nomor_izin_penyedia ?? 'SPBU 3P-144.01' }}</td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>: Pelabuhan Sunda Kelapa</td>
            </tr>
        </table>

        <p class="mt-20">Demikian permohonan ini kami sampaikan atas perhatiannya diucapkan terima kasih.</p>
    </div>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%; text-align: center;">
                    Jakarta, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}<br>
                    Pejabat Pelaksana Kegiatan<br>
                    Unit Pengelola Angkutan Perairan<br>
                    Dinas Perhubungan Provinsi DKI Jakarta
                    <br><br><br><br><br>
                    <span class="font-bold underline">Suparto Napitu, ST, MM.</span><br>
                    NIP. 197602142010011014
                </td>
            </tr>
        </table>
    </div>

</body>
</html>