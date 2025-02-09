<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SPPD Dalam Daerah - {{ $sppdDalamDaerah->no_surat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid black;
            padding-bottom: 20px;
        }
        .header h3, .header h2 {
            margin: 5px 0;
        }
        .content {
            margin: 20px 0;
        }
        .content table {
            width: 100%;
            border-spacing: 0 10px;
        }
        .content table td {
            vertical-align: top;
            padding: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .signature {
            margin-top: 80px;
        }
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>PROVINSI SULAWESI TENGGARA</h3>
        <h2>BADAN PENGELOLAAN KEUANGAN DAERAH</h2>
        <p>Jl. Prof. Dr. H. La Ode Saadia No.1, Kota Kendari</p>
    </div>

    <div class="content">
        <h3 style="text-align: center; text-decoration: underline;">SURAT PERINTAH PERJALANAN DINAS</h3>
        <p style="text-align: center; margin-top: -10px;">Nomor: {{ $sppdDalamDaerah->no_surat }}</p>

        <table>
            <tr>
                <td width="30%">Dasar</td>
                <td width="5%">:</td>
                <td>{{ $sppdDalamDaerah->perihal }}</td>
            </tr>
            <tr>
                <td>Nama Petugas</td>
                <td>:</td>
                <td>{!! nl2br(e($sppdDalamDaerah->nama_petugas)) !!}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $sppdDalamDaerah->tanggal->isoFormat('D MMMM Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Martapura, {{ $sppdDalamDaerah->tanggal->isoFormat('D MMMM Y') }}</p>
        <p>Kepala Badan Pengelolaan Keuangan Daerah</p>
        <div class="signature">
            <p>Nama Kepala</p>
            <p>NIP. ........................</p>
        </div>
    </div>
</body>
</html> 