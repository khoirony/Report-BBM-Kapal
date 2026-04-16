<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cover SPJ</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: black; margin: 10; padding: 0; line-height: 1.5;">

    <table width="100%" style="margin-bottom: 40px; border-collapse: collapse;">
        <tr>
            <td align="left" width="50%" valign="top">
                <img src="{{ public_path('img/logo-jaya-raya-berwarna.png') }}"  alt="Logo DKI" style="height: 120px; width: 100px; object-fit: contain;">
            </td>
            <td align="right" width="50%" valign="top">
                <img src="{{ public_path('img/logo-dishub-jakarta.jpeg') }}" alt="Logo Dishub" style="height: 120px; width: 120px; object-fit: contain;">
            </td>
        </tr>
    </table>

    <div style="text-align: center; font-weight: bold; font-size: 28pt; margin-top: 60px; margin-bottom: 30px; line-height: 1.2;">
        SURAT PERTANGGUNG JAWABAN<br>
        (SPJ)
    </div>

    <div style="text-align: center; font-weight: bold; font-size: 24pt; margin-bottom: 80px; line-height: 1.2;">
        BELANJA BAHAN BAKAR MINYAK (BBM)<br>
        KENDARAAN DINAS OPERASIONAL (KDO)<br>
        KHUSUS
    </div>

    <table style="width: 85%; margin: 0 auto; font-size: 22pt; margin-bottom: 80px; border-collapse: collapse;">
        <tr>
            <td width="35%" style="padding-bottom: 15px; vertical-align: bottom;">Nama Kapal</td>
            <td width="5%" style="padding-bottom: 15px; vertical-align: bottom;">:</td>
            <td width="65%" style="padding-bottom: 15px; vertical-align: bottom;">{{ $spj->kapal->nama_kapal }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: 15px; vertical-align: bottom;">Tanggal</td>
            <td style="padding-bottom: 15px; vertical-align: bottom;">:</td>
            <td style="padding-bottom: 15px; vertical-align: bottom;">{{ $spj->tanggal_spj ? \Carbon\Carbon::parse($spj->tanggal_spj)->translatedFormat('d F Y') : '........' }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: 15px; vertical-align: bottom;">Nakhoda</td>
            <td style="padding-bottom: 15px; vertical-align: bottom;">:</td>
            <td style="padding-bottom: 15px; vertical-align: bottom;">{{ $spj->kapal->nakhoda->name }}</td>
        </tr>
    </table>

    <div style="text-align: center; font-weight: bold; font-size: 24pt; margin-top: 80px; line-height: 1.2">
        {{ strtoupper($spj->kapal->ukpd->nama ?? '') }}<br>
        DINAS PERHUBUNGAN<br>
        PROVINSI DKI JAKARTA
    </div>

    <div style="text-align: center; font-size: 22pt; margin-top: 30px;">
        TAHUN ANGGARAN {{ $spj->tanggal_spj ? \Carbon\Carbon::parse($spj->tanggal_spj)->translatedFormat('Y') : '........' }}
    </div>

</body>
</html>