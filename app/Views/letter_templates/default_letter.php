<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $letterType['name'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 3cm 2cm 2cm 2cm;
            font-size: 12pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 10px 0;
            font-size: 18pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
        }
        .content {
            text-align: justify;
        }
        .reference {
            margin-bottom: 30px;
        }
        .letter-body {
            margin-bottom: 50px;
        }
        .closing {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-space {
            height: 80px;
        }
        table.biodata {
            margin: 20px 0;
            width: 100%;
        }
        table.biodata td {
            padding: 3px 10px;
            vertical-align: top;
        }
        table.biodata td:first-child {
            width: 150px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>PEMERINTAH KABUPATEN NAMA KABUPATEN</h2>
        <h2>KECAMATAN NAMA KECAMATAN</h2>
        <h1>DESA NAMA DESA</h1>
        <p>Alamat: Jalan Raya Desa No. 01, Kode Pos 12345</p>
        <p>Email: desanama@example.com | Telp: (021) 1234567</p>
        <hr style="border: 2px solid #000; margin-top: 10px;">
    </div>

    <div class="reference">
        <table width="100%">
            <tr>
                <td width="120">Nomor</td>
                <td width="10">:</td>
                <td><?= $request['number'] ?? '-' ?></td>
                <td style="text-align: right;">Tanggal: <?= date('d F Y') ?></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td colspan="2"><?= $letterType['name'] ?></td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Kepala Desa Nama Desa, Kecamatan Nama Kecamatan, Kabupaten Nama Kabupaten, dengan ini menerangkan bahwa:</p>

        <table class="biodata">
            <tr>
                <td>Nama Lengkap</td>
                <td>: <?= $resident['name'] ?></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: <?= $resident['nik'] ?></td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir</td>
                <td>: <?= $resident['birth_place'] ?>, <?= date('d F Y', strtotime($resident['birth_date'])) ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>: <?= $resident['gender'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>: <?= $resident['religion'] ?></td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>: <?= $resident['occupation'] ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <?= $resident['address'] ?></td>
            </tr>
        </table>

        <div class="letter-body">
            <p>Adalah benar-benar penduduk Desa Nama Desa, Kecamatan Nama Kecamatan, Kabupaten Nama Kabupaten. Surat keterangan ini dibuat untuk keperluan:</p>
            
            <p style="text-align: center; font-weight: bold; margin: 20px 0;">
                "<?= $request['purpose'] ?>"
            </p>
            
            <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="closing">
            <p>Nama Desa, <?= date('d F Y') ?><br>Kepala Desa Nama Desa</p>
            <div class="signature-space"></div>
            <p><strong>NAMA KEPALA DESA</strong><br>NIP. 19XXXXXXXX</p>
        </div>
    </div>

    <div style="clear: both;"></div>
    
    <div class="footer">
        <p>Dokumen ini diterbitkan secara elektronik melalui Sistem Informasi Desa Nama Desa.</p>
    </div>
</body>
</html> 