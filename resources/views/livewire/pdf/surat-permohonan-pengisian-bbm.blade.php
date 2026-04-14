<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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

        /* Tabel Petugas */
        .table-border { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .table-border th, .table-border td { border: 1px solid black; padding: 4px 8px; height: 22px; }
        
        /* Bagian Detail BBM */
        .detail-bbm-table { width: 100%; margin-top: 10px; }
        .detail-bbm-table td { padding: 1px 0; }

        /* Footer Tanda Tangan */
        .ttd-container { width: 100%; margin-top: 30px; }
        .ttd-table { width: 100%; border: none; }
        .ttd-table td { vertical-align: top; text-align: center; }

        .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>

    <table class="kop-table" style="width: 100%; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 15px;">
        <tr>
            <td class="logo-container" style="width: 12%; text-align: center; vertical-align: middle;">
                <img src="{{ public_path('img/logo-jaya-raya.jpg') }}" alt="Logo" style="width: 75px; height: auto;">
            </td>
            <td class="kop-text" style="width: 88%; text-align: center; vertical-align: middle;">
                <div class="instansi-utama" style="font-size: 14pt; font-weight: bold; margin: 0; white-space: nowrap;">
                    PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA
                </div>
                <div class="instansi-utama" style="font-size: 14pt; font-weight: bold; margin: 0; white-space: nowrap;">
                    DINAS PERHUBUNGAN
                </div>
                <div class="instansi-utama" style="font-size: 14pt; font-weight: bold; margin: 0; white-space: nowrap;">
                    {{ strtoupper($surat?->suratTugas?->laporanSisaBbm?->sounding?->kapal?->ukpd?->nama) }}
                </div>
                
                <p style="font-size: 10pt; margin: 5px 0 0 0; line-height: 1.2;">
                    {{ $surat?->suratTugas?->laporanSisaBbm?->sounding?->kapal?->ukpd?->alamat }}<br>
                    Website: www.dishub.jakarta.go.id &nbsp; E-mail : {{ $surat?->suratTugas?->laporanSisaBbm?->sounding?->kapal?->ukpd?->email }}
                </p>
    
                <table style="width: 100%; font-size: 10pt; margin-top: 0;">
                    <tr>
                        <td style="text-align: center; padding-left: 50px;">
                            <span style="letter-spacing: 3px; font-size: 10">JAKARTA</span>
                        </td>
                        <td style="text-align: right; width: 100px;">
                            Kode Pos : {{ $surat?->suratTugas?->laporanSisaBbm?->sounding?->kapal?->ukpd?->kode_pos }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="main-header">
        <tr>
            <td style="width: 55%;">
                <table class="sub-header-left">
                    <tr>
                        <td style="width: 30%;">Nomor</td>
                        <td>: {{ $surat->nomor_surat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Klasifikasi</td>
                        <td>: {{ $surat->klasifikasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>: {{ $surat->lampiran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>: <span>Permohonan<br>&nbsp;&nbsp;Pengisian BBM</span></td>
                    </tr>
                </table>
            </td>
            <td style="width: 45%;">
                <table class="sub-header-right">
                    <tr>
                        <td style="width: 25%;">Jakarta,</td>
                        <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 10px;">Kepada</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Kepala {{ $surat?->suratTugas?->laporanSisaBbm?->sounding?->kapal?->ukpd?->nama }}<br>
                            Dinas Perhubungan Provinsi DKI<br>
                            di<br>
                            Jakarta
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="mt-20 text-justify">
        <p style="text-indent: 50px; margin-bottom: 10px;">
            Bersama ini kami mengajukan permohonan surat pengisian Bahan Bakar Minyak guna memenuhi kebutuhan operasional kapal kami. Adapun kapal yang dimaksud adalah KM. <strong>{{ $surat->suratTugas->laporanSisaBbm->sounding->kapal->nama_kapal ?? '........................' }}</strong>
        </p>
        <p style="text-indent: 50px; margin-bottom: 10px;">Berikut nama beserta jabatan yang bertanggung jawab atas nama kapal yang di maksud sebagai berikut :</p>
    </div>

    <table class="table-border">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th>NAMA</th>
                <th style="width: 40%;">JABATAN</th>
            </tr>
        </thead>
        <tbody>
            @php $maxRows = 7; @endphp
            @for($i = 0; $i < $maxRows; $i++)
                @php $petugas = $surat->suratTugas->petugas[$i] ?? null; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}.</td>
                    <td>{{ $petugas->nama_petugas ?? '' }}</td>
                    <td>{{ $petugas->jabatan ?? '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="mt-10">
        <p>Adapun kebutuhan Bahan Bakar Minyak (BBM) yang di gunakan untuk Kapal tersebut jenis <strong>{{ $surat->jenis_bbm ?? 'Pertamax / Dexlite' }}</strong> dengan jumlah kebutuhan <strong>{{ number_format($surat->jumlah_bbm, 0, ',', '.') ?? '..........' }}</strong> Liter</p>
        
        <table class="detail-bbm-table">
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
                <td>: {{ $surat->lokasi_pengambilan ?? 'Pelabuhan Sunda Kelapa' }}</td>
            </tr>
        </table>
    </div>

    <p class="mt-20 text-justify" style="text-indent: 50px;">
        Demikian permohonan ini kami sampaikan atas perhatiannya diucapkan terima kasih.
    </p>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td style="width: 50%;">
                    <br>
                    <br>
                    Nakhoda / ABK<br>
                    KM. {{ $surat->suratTugas->laporanSisaBbm->sounding->kapal->nama_kapal ?? '................' }}
                </td>
                <td style="width: 50%;">
                    Jakarta, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}<br>
                    Pejabat Pelaksana Teknis Kegiatan<br>
                    {{ $surat?->suratTugas?->laporanSisaBbm?->sounding?->kapal?->ukpd?->nama }}<br>
                    Dinas Perhubungan Provinsi DKI Jakarta
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 80px;">
        <table class="ttd-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;">
                    {{-- Mengambil dari database, jika kosong tampilkan default Suparto Napitu --}}
                    <span class="font-bold underline">{{ $surat->nama_pejabat_ttd ?? 'Suparto Napitu, ST, MM.' }}</span><br>
                    NIP. {{ $surat->nip_pejabat_ttd ?? '197602142010011014' }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>