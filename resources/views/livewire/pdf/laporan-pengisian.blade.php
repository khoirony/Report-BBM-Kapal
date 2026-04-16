<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengisian</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12pt; line-height: 1.3; color: black; margin: 0; padding: 10px 20px;">
    <div style="padding: 0 10px; line-height: 1.5;">
        <table style="width: 100%; border-collapse: collapse; font-size: 10pt;">
            <tr><td style="width: 15%; vertical-align: top;">Lampiran</td><td style="vertical-align: top;">: Berita Acara Pengisian Bahan Bakar Minyak Kendaraan Dinas Kapal {{ $laporan->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal }}</td></tr>
            <tr><td style="vertical-align: top;">Nomor</td><td style="vertical-align: top;">: {{ $laporan->nomor_ba ?? ".................................../PH.12.00" }}</td></tr>
            <tr><td style="vertical-align: top;">Tanggal</td><td style="vertical-align: top;">: {{ $laporan->tgl_ba ? \Carbon\Carbon::parse($laporan->tgl_ba)->locale('id')->translatedFormat('d F Y') : '................' }}</td></tr>
        </table>

        <div style="text-align: center; margin: 20px 0px; font-size: 12pt;">LAPORAN PENGISIAN BBM KAPAL</div>

        @php
            // Ambil data petugas dan hitung jumlahnya
            $petugasList = $laporan->suratTugas->petugas ?? collect();
            $jumlahPetugas = count($petugasList);
            $minimalBaris = 7;
        @endphp
        <table style="width: 100%; border-collapse: collapse; margin: 10px 0px;">
            <tr><td style="width: 140px; vertical-align: top;">1. Hari</td><td style="vertical-align: top;">: {{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->locale('id')->translatedFormat('l') : '................' }}</td></tr>
            <tr><td style="vertical-align: top;">2. Tanggal</td><td style="vertical-align: top;">: {{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->locale('id')->translatedFormat('d F Y') : '................' }}</td></tr>
            <tr><td style="vertical-align: top;">3. Dasar Hukum</td><td style="vertical-align: top;">: {{ $laporan->dasar_hukum }}</td></tr>
            <tr><td style="vertical-align: top;">4. Petugas</td><td style="vertical-align: top;">: .......................................................................</td></tr>
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
        <div><span style="margin-left: 18px; margin-top: 5px;">{{ $laporan->kegiatan }}</span></div>
        <div>6. Tujuan</div>
        <div><span style="margin-left: 18px; margin-top: 5px;">{{ $laporan->tujuan }}</span></div>
        <div>7. Lokasi</div>
        <div><span style="margin-left: 18px; margin-top: 5px;">{{ $laporan->lokasi_pengisian }}</span></div>

        {{-- pindah halaman --}}
        <div style="page-break-before: always;"></div>
        <div style="margin-top: 10px;">8. Kondisi Kapal</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25px; vertical-align: top; padding-left: 25px">a.</td>
                <td style="width: 200px; vertical-align: top;">Nama Kapal</td><td style="vertical-align: top;">: 
                    <span>{{ $laporan->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal }}</span></td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">b.</td>
                <td style="vertical-align: top;">BBM Awal Di Pom Bensin</td><td style="vertical-align: top;">: 
                    {{ $laporan->jumlah_bbm_awal }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">c.</td>
                <td style="vertical-align: top;">Pengisian</td><td style="vertical-align: top;">: 
                    {{ $laporan->jumlah_bbm_pengisian }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">d.</td>
                <td style="vertical-align: top;">Pemakaian BBM dari Pom</td><td style="vertical-align: top;">: 
                    {{ $laporan->pemakaian_bbm }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">e.</td>
                <td style="vertical-align: top;">BBM Akhir</td><td style="vertical-align: top;">: 
                    {{ $laporan->jumlah_bbm_akhir }} Liter</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">f.</td>
                <td style="vertical-align: top;">Jam Berangkat</td><td style="vertical-align: top;">: 
                    {{ $laporan->jam_berangkat }} WIB</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-left: 25px">g.</td>
                <td style="vertical-align: top;">Jam Kembali</td><td style="vertical-align: top;">: 
                    {{ $laporan->jam_kembali }} WIB</td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 30px;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    Petugas SPBU<br><br><br><br><br>
                    (.........................................)
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    Nakhoda<br><br><br><br><br>
                    (.........................................)
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; padding-top: 30px; vertical-align: top;">
                    Kepala {{ $laporan?->suratTugas?->ukpd?->nama ?? '-' }}<br>
                    Dinas Perhubungan Provinsi DKI Jakarta<br><br><br><br>
                    <span style="font-weight: bold; text-decoration: underline;">{{ $laporan?->suratTugas?->ukpd?->kepalaUkpd->name ?? '-' }}</span><br>
                    NIP. {{ $laporan?->suratTugas?->ukpd?->kepalaUkpd->nip ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-before: always;"></div>
    <div style="margin-top: 20px;">
        <p style="font-weight: bold;">Dokumentasi Proses:</p>
        
        @php
            $path = storage_path('app/public/' . $laporan->suratPermohonan->pencatatanHasil->foto_proses);
            $base64 = '';
            if(file_exists($path) && !is_dir($path)){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        @if($base64)
            <img src="{{ $base64 }}" 
                 alt="Foto Proses" 
                 style="max-width: 100%; max-height: 400px; object-fit: contain; border: 1px solid #ccc; padding: 5px;">
        @else
            <p style="font-style: italic; color: gray;">Tidak ada foto proses yang dilampirkan atau file tidak ditemukan.</p>
        @endif
    </div>

    <div style="margin-top: 20px;">
        <p style="font-weight: bold;">Dokumentasi Meter:</p>
        
        @php
            $path = storage_path('app/public/' . $laporan->suratPermohonan->pencatatanHasil->foto_flow_meter);
            $base64 = '';
            if(file_exists($path) && !is_dir($path)){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        @if($base64)
            <img src="{{ $base64 }}" 
                 alt="Foto Proses" 
                 style="max-width: 100%; max-height: 400px; object-fit: contain; border: 1px solid #ccc; padding: 5px;">
        @else
            <p style="font-style: italic; color: gray;">Tidak ada foto proses yang dilampirkan atau file tidak ditemukan.</p>
        @endif
    </div>

    <div style="margin-top: 20px;">
        <p style="font-weight: bold;">Dokumentasi Struk/Nota:</p>
        
        @php
            $path = storage_path('app/public/' . $laporan->suratPermohonan->pencatatanHasil->foto_struk);
            $base64 = '';
            if(file_exists($path) && !is_dir($path)){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        @if($base64)
            <img src="{{ $base64 }}" 
                 alt="Foto Proses" 
                 style="max-width: 100%; max-height: 400px; object-fit: contain; border: 1px solid #ccc; padding: 5px;">
        @else
            <p style="font-style: italic; color: gray;">Tidak ada foto proses yang dilampirkan atau file tidak ditemukan.</p>
        @endif
    </div>

</body>
</html>