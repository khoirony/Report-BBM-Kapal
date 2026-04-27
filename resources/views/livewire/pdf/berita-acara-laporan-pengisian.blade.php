<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Laporan Pengisian {{ $laporan->kapal->nama_kapal }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12pt; line-height: 1.3; color: black; margin: 0; padding: 10px 20px;">

    {{-- KOP Surat --}}
    <table style="width: 100%; line-height: 1.1; border-collapse: collapse; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 5px;">
        <tr>
            <td style="width: 15%; text-align: center; vertical-align: top; padding-top: 5px;">
                <img src="{{ public_path('img/logo-jaya-raya.jpg') }}" style="height: 120px; width: 100px; object-fit: contain;" alt="Logo">
            </td>
            <td style="width: 85%; text-align: center; vertical-align: middle;">
                <div style="font-size: 13pt; margin: 0; padding: 0; line-height: 1.2; white-space: nowrap;">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
                <div style="font-size: 13pt; margin: 0; padding: 0; line-height: 1.2; white-space: nowrap;">DINAS PERHUBUNGAN</div>
                <div style="font-size: 16pt; font-weight: bold; margin: 0; padding: 0; line-height: 1.2;">{{ strtoupper($laporan?->kapal?->ukpd?->nama ?? '') }}</div>
                <div style="font-size: 10pt; margin: 0; padding: 0; line-height: 1.2;">{{ $laporan?->kapal?->ukpd?->alamat }}</div>
                <div style="font-size: 10pt; margin: 0; padding: 0; line-height: 1.2;">Website: <span style="color: blue; text-decoration: underline;">www.dishub.jakarta.go.id</span> &nbsp;&nbsp;&nbsp; E-mail: {{ $laporan?->kapal?->ukpd?->email }}</div>
                <div style="margin: 0; padding: 0; line-height: 1.2;"><span style="letter-spacing: 3px; font-size: 10pt;">JAKARTA</span></div>
                <div style="text-align: right; margin: 0; padding: 0; line-height: 1.2;"><span style="font-size: 10pt;">Kode Pos : {{ $laporan?->kapal?->ukpd?->kode_pos ?? '-' }}</span></div>
            </td>
        </tr>
    </table>

    <div style="padding: 0 10px;">
        <div style="text-align: center; font-size: 12pt;">
            BERITA ACARA LAPORAN PENGISIAN BAHAN BAKAR<br>
            MINYAK KENDARAAN DINAS {{ strtoupper($laporan->kapal->nama_kapal) }}<br>
            NOMOR: {{ $laporan->nomor_ba ?? ' ' }}<br>
            Tanggal : {{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('d F Y') : '................' }}
        </div>

        <div style="margin-top: 10px; text-align: justify;">
            <p>Pada hari ini {{ $laporan->tgl_pelaksanaan ? \Carbon\Carbon::parse($laporan->tgl_pelaksanaan)->locale('id')->translatedFormat('l') : '................' }}
                tanggal {{ $laporan->tgl_pelaksanaan ? \Carbon\Carbon::parse($laporan->tgl_pelaksanaan)->locale('id')->translatedFormat('d') : '................' }}
                bulan {{ $laporan->tgl_pelaksanaan ? \Carbon\Carbon::parse($laporan->tgl_pelaksanaan)->locale('id')->translatedFormat('F') : '................' }}
                tahun {{ $laporan->tgl_pelaksanaan ? \Carbon\Carbon::parse($laporan->tgl_pelaksanaan)->locale('id')->translatedFormat('Y') : '................' }}
                bertempat di {{ $laporan->suratPermohonan->suratTugas->lokasi ?? '-' }}</p>
            
            <p style="margin-bottom: 5px;">Berdasarkan:</p>
            <table style="width: 100%; border-collapse: collapse; vertical-align: top;">
                <tr>
                    <td style="width: 25px; vertical-align: top;">a.</td>
                    <td style="vertical-align: top; text-align: justify;">Peraturan Gubernur Daerah Khusus Ibukota Jakarta Nomor 75 Tahun 2021 tentang Pemberian Bahan Bakar Minyak Kendaraan Dinas;</td></tr>
                <tr>
                    <td style="vertical-align: top;">b.</td>
                    <td style="vertical-align: top; text-align: justify;">Perjanjian Kerja Sama (PKS) Penyediaan Bahan Bakar Minyak Kendaraan Dinas Operasional (KDO) Khusus {{ $laporan?->kapal?->ukpd?->nama }} 
                        Dinas Perhubungan Provinsi DKI Jakarta dengan {{ $laporan->suratPermohonan->penyedia->name ?? '-' }} Nomor {{ $laporan->nomor_pks ?? '.......................' }} Tanggal {{ $laporan->tanggal_pks ? \Carbon\Carbon::parse($laporan->tanggal_pks)->locale('id')->translatedFormat('d F Y') : '................' }} dan
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">c.</td>
                    <td style="vertical-align: top; text-align: justify;">Surat Permohonan Nomor {{ $laporan->suratPermohonan->nomor_surat ?? '.......................' }} perihal Permohonan Pengisian Bahan Bakar Minyak pada tanggal
                        {{ $laporan->suratPermohonan->tanggal_surat ? \Carbon\Carbon::parse($laporan->suratPermohonan->tanggal_surat)->locale('id')->translatedFormat('d F Y') : '................' }}.
                    </td>
                </tr>
            </table>

            <p style="margin-top: 10px;">Awak Kapal yang bertugas:</p>
            @php
                // Ambil data petugas dan hitung jumlahnya
                $petugasList = $laporan->suratPermohonan->suratTugas->petugas ?? collect();
                $jumlahPetugas = count($petugasList);
                $minimalBaris = 7;
            @endphp

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 4px; width: 5%;">No.</th>
                        <th style="border: 1px solid black; padding: 4px; width: 45%;">Nama</th>
                        <th style="border: 1px solid black; padding: 4px; width: 30%;">Jabatan</th>
                        <th style="border: 1px solid black; padding: 4px; width: 20%;">Paraf</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 1. Render data petugas yang ada --}}
                    @foreach($petugasList as $index => $petugas)
                    <tr>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $index + 1 }}.</td>
                        <td style="border: 1px solid black; padding: 4px;">{{ $petugas->nama_petugas ?? '-' }}</td>
                        <td style="border: 1px solid black; padding: 4px;">{{ $petugas->jabatan ?? '-' }}</td>
                        <td style="border: 1px solid black; padding: 4px;"></td>
                    </tr>
                    @endforeach

                    {{-- 2. Render baris kosong tambahan JIKA data kurang dari 7 --}}
                    @if($jumlahPetugas < $minimalBaris)
                        @for($i = $jumlahPetugas + 1; $i <= $minimalBaris; $i++)
                        <tr>
                            <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $i }}.</td>
                            <td style="border: 1px solid black; padding: 4px;">&nbsp;</td>
                            <td style="border: 1px solid black; padding: 4px;">&nbsp;</td>
                            <td style="border: 1px solid black; padding: 4px;">&nbsp;</td>
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>

            {{-- pindah halaman --}}
            <div style="page-break-before: always;"></div>
            <p style="margin-top: 10px;">Demikian Berita Acara Pengisian Bahan Bakar Minyak ini dibuat dalam rangkap secukupnya untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        <div style="width: 100%; margin-top: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; text-align: center; vertical-align: top;">
                        Penyedia BBM<br><br><br><br><br>
                        .........................................<br>
                        {{ $laporan->suratPermohonan->penyedia->name ?? '-' }}
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: top;">
                        Pejabat Pelaksana Teknis Kegiatan<br>
                        {{ $laporan?->kapal?->ukpd?->nama ?? '-' }}<br><br><br><br>
                        <span style="text-decoration: underline;">{{ $laporan?->nama_pptk ?: ' ' }}</span><br>
                        NIP. {{ $laporan?->id_pptk ?: '...........................................' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; padding-top: 30px; vertical-align: top;">
                        Mengetahui,<br>
                        Kepala {{ $laporan?->kapal?->ukpd?->nama ?? '-' }}<br>
                        Dinas Perhubungan Provinsi DKI Jakarta<br><br><br><br>
                        <span style="text-decoration: underline;">{{ $laporan?->nama_kepala_ukpd ?: ' ' }}</span><br>
                        NIP. {{ $laporan?->id_kepala_ukpd ?: '...........................................' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div style="page-break-after: always;"></div>

    <div style="padding: 0 10px; line-height: 1.5;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12pt;">
            <tr>
                <td style="width: 15%; vertical-align: top;">Lampiran</td>
                <td style="width: 1%; vertical-align: top;">: </td>
                <td style="vertical-align: top;">Berita Acara Pengisian Bahan Bakar Minyak Kendaraan Dinas Kapal {{ $laporan->kapal->nama_kapal }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Nomor</td>
                <td style="vertical-align: top;">: </td>
                <td style="vertical-align: top;">{{ $laporan->nomor_ba ?? " " }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Tanggal</td>
                <td style="vertical-align: top;">: </td>
                <td style="vertical-align: top;">{{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('d F Y') : '................' }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin: 20px 0px; font-size: 12pt;">LAPORAN PENGISIAN BBM KAPAL</div>

        <table style="width: 100%; border-collapse: collapse; margin: 10px 0px;">
            <tr>
                <td style="width: 140px; vertical-align: top;">1. Hari</td>
                <td style="width: 1%; vertical-align: top;">: </td>
                <td style="vertical-align: top;"> {{ $laporan->laporanPengisian->tanggal ? \Carbon\Carbon::parse($laporan->laporanPengisian->tanggal)->locale('id')->translatedFormat('l') : '................' }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">2. Tanggal</td>
                <td style="width: 1%; vertical-align: top;">: </td>
                <td style="vertical-align: top;"> {{ $laporan->laporanPengisian->tanggal ? \Carbon\Carbon::parse($laporan->laporanPengisian->tanggal)->locale('id')->translatedFormat('d F Y') : '................' }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">3. Dasar Hukum</td>
                <td style="width: 1%; vertical-align: top;">: </td>
                <td style="vertical-align: top;"> {!! nl2br(e(str_replace(';', '', $laporan->laporanPengisian->dasar_hukum))) !!}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">4. Petugas</td>
                <td style="width: 1%; vertical-align: top;">:</td>
                <td style="vertical-align: top;"> </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 4px; width: 5%;">No.</th>
                    <th style="border: 1px solid black; padding: 4px; width: 45%;">Nama</th>
                    <th style="border: 1px solid black; padding: 4px; width: 30%;">Jabatan</th>
                    <th style="border: 1px solid black; padding: 4px; width: 20%;">Paraf</th>
                </tr>
            </thead>
            <tbody>
                {{-- 1. Render data petugas yang ada --}}
                @foreach($petugasList as $index => $petugas)
                <tr>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $index + 1 }}.</td>
                    <td style="border: 1px solid black; padding: 4px;">{{ $petugas->nama_petugas ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 4px;">{{ $petugas->jabatan ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 4px;"></td>
                </tr>
                @endforeach

                {{-- 2. Render baris kosong tambahan JIKA data kurang dari 7 --}}
                @if($jumlahPetugas < $minimalBaris)
                    @for($i = $jumlahPetugas + 1; $i <= $minimalBaris; $i++)
                    <tr>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $i }}.</td>
                        <td style="border: 1px solid black; padding: 4px;">&nbsp;</td>
                        <td style="border: 1px solid black; padding: 4px;">&nbsp;</td>
                        <td style="border: 1px solid black; padding: 4px;">&nbsp;</td>
                    </tr>
                    @endfor
                @endif
            </tbody>
        </table>

        <div style="margin-top: 10px">5. Kegiatan</div>
        <div><span style="margin-left: 18px; margin-top: 5px;">{{ $laporan->laporanPengisian->kegiatan }}</span></div>
        <div>6. Tujuan</div>
        <div><span style="margin-left: 18px; margin-top: 5px;">{{ $laporan->laporanPengisian->tujuan }}</span></div>
        <div>7. Lokasi</div>
        <div><span style="margin-left: 18px; margin-top: 5px;">{{ $laporan->laporanPengisian->lokasi_pengisian }}</span></div>

        {{-- pindah halaman --}}
        <div style="page-break-before: always;"></div>
        <div style="margin-top: 10px;">8. Kondisi Kapal</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25px; vertical-align: top; padding-left: 25px">a.</td>
                <td style="width: 200px; vertical-align: top;">Nama Kapal</td><td style="vertical-align: top;">: 
                    <span>{{ $laporan->kapal->nama_kapal }}</span></td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">b.</td>
                <td style="vertical-align: top;">BBM Awal Di Pom Bensin</td><td style="vertical-align: top;">: 
                    {{ $laporan->laporanPengisian->jumlah_bbm_awal }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">c.</td>
                <td style="vertical-align: top;">Pengisian</td><td style="vertical-align: top;">: 
                    {{ $laporan->laporanPengisian->jumlah_bbm_pengisian }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">d.</td>
                <td style="vertical-align: top;">Pemakaian BBM dari Pom</td><td style="vertical-align: top;">: 
                    {{ $laporan->laporanPengisian->pemakaian_bbm }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">e.</td>
                <td style="vertical-align: top;">BBM Akhir</td><td style="vertical-align: top;">: 
                    {{ $laporan->laporanPengisian->jumlah_bbm_akhir }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">f.</td>
                <td style="vertical-align: top;">Jam Berangkat</td><td style="vertical-align: top;">: 
                    {{ $laporan->laporanPengisian->jam_berangkat }} WIB</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">g.</td>
                <td style="vertical-align: top;">Jam Kembali</td><td style="vertical-align: top;">: 
                    {{ $laporan->laporanPengisian->jam_kembali }} WIB</td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 30px;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    Petugas SPBU<br><br><br><br><br>
                    .........................................
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    Nakhoda<br><br><br><br><br>
                    <span style="text-decoration: underline;">{{ $laporan?->nama_nakhoda ?: ' ' }}</span><br>
                    ID. {{ $laporan?->id_nakhoda ?: '...........................................' }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; padding-top: 30px; vertical-align: top;">
                    Kepala {{ $laporan?->kapal?->ukpd?->nama ?? '-' }}<br>
                    Dinas Perhubungan Provinsi DKI Jakarta<br><br><br><br>
                    <span style="text-decoration: underline;">{{ $laporan?->nama_kepala_ukpd ?? ' ' }}</span><br>
                    NIP. {{ $laporan?->id_kepala_ukpd ?? '..............................................' }}
                </td>
            </tr>
        </table>
    </div>


    <div style="page-break-before: always;"></div>
    <table style="width: 100%; border-collapse: collapse; font-size: 12pt;">
        <tr>
            <td style="width: 15%; vertical-align: top;">Lampiran</td>
            <td style="width: 1%; vertical-align: top;">: </td>
            <td style="vertical-align: top;">Berita Acara Pengisian Bahan Bakar Minyak Kendaraan Dinas Kapal {{ $laporan->kapal->nama_kapal }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Nomor</td>
            <td style="vertical-align: top;">: </td>
            <td style="vertical-align: top;">{{ $laporan->nomor_ba ?? " " }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Tanggal</td>
            <td style="vertical-align: top;">: </td>
            <td style="vertical-align: top;">{{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('d F Y') : '................' }}</td>
        </tr>
    </table>

    <p style="text-align: center;">DOKUMENTASI KEGIATAN PENGISIAN BBM KAPAL</p>

    <div style="margin-top: 5px;">
        <p style="line-height: 1.0">1. Foto Proses Pengisian</p>
        @php
            $path = storage_path('app/public/' . $laporan?->suratPermohonan?->pencatatanHasil?->foto_proses ?? '');
            $base64 = '';
            if(file_exists($path) && !is_dir($path)){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        <div style="text-align: center;">
            @if($base64)
                <img src="{{ $base64 }}" 
                    alt="Foto Proses" 
                    style="max-width: 100%; max-height: 220px; object-fit: contain; border: 1px solid #ccc; padding: 5px;">
            @else
                <p style="font-style: italic; color: gray;">Tidak ada foto proses yang dilampirkan atau file tidak ditemukan.</p>
            @endif
        </div>
    </div>

    <div style="margin-top: 5px;">
        <p style="line-height: 1.0">2. Foto Flow Meter / Dispenser</p>
        
        @php
            $path = storage_path('app/public/' . $laporan?->suratPermohonan?->pencatatanHasil?->foto_flow_meter ?? '');
            $base64 = '';
            if(file_exists($path) && !is_dir($path)){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        <div style="text-align: center;">
            @if($base64)
                <img src="{{ $base64 }}" 
                    alt="Foto Proses" 
                    style="max-width: 100%; max-height: 220px; object-fit: contain; border: 1px solid #ccc; padding: 5px;">
            @else
                <p style="font-style: italic; color: gray;">Tidak ada foto proses yang dilampirkan atau file tidak ditemukan.</p>
            @endif
        </div>
    </div>

    <div style="margin-top: 5px;">
        <p style="line-height: 1.0">3. Foto Struk / Surat Jalan / Delivery Order</p>
        
        @php
            $path = storage_path('app/public/' . $laporan?->suratPermohonan?->pencatatanHasil?->foto_struk ?? '');
            $base64 = '';
            if(file_exists($path) && !is_dir($path)){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        <div style="text-align: center;">
            @if($base64)
                <img src="{{ $base64 }}" 
                    alt="Foto Proses" 
                    style="max-width: 100%; max-height: 220px; object-fit: contain; border: 1px solid #ccc; padding: 5px;">
            @else
                <p style="font-style: italic; color: gray;">Tidak ada foto proses yang dilampirkan atau file tidak ditemukan.</p>
            @endif
        </div>
    </div>

</body>
</html>