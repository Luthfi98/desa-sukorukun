<?php
$namaDesa = get_setting('informasi_desa', 'nama_desa', false);
$sigKades = get_setting('etc', 'ttd_kepala_desa', false);
if (file_exists($sigKades)) {
    $sigKades = 'data:image/png;base64,' . base64_encode(file_get_contents($sigKades));
}

$sigCamat = get_setting('etc', 'ttd_camat', false);
if (file_exists($sigCamat)) {
    $sigCamat = 'data:image/png;base64,' . base64_encode(file_get_contents($sigCamat));
}

$logo = get_setting('website', 'logo', false);
$logoPath = FCPATH . $logo;
$logoData = '';
if (file_exists($logoPath)) {
    $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
}

function getAge($date) {
    $from = new DateTime($date);
    $to = new DateTime('today');
    return $from->diff($to)->y;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Ahli Waris</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 40px;
            font-size: 14px;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 2px 4px;
        }
        .logo {
            width: 70px;
            height: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            float: left;
        }
        hr {
            border: 1px solid black;
            margin: 10px 0;
        }
        .content p {
            margin: 5px 0;
            text-align: justify;
        }
        .ahli-waris table, .ahli-waris th, .ahli-waris td {
            border: 1px solid black;
            text-align: center;
            padding: 6px;
            font-size: 13px;
        }
        .ttd {
            margin-top: 40px;
            width: 100%;
        }
        .ttd td {
            text-align: center;
            vertical-align: top;
            font-size: 14px;
            height: 120px;
        }
    </style>
</head>
<body>

<table border="0">
    <tr>
        <td colspan="3" width="90%"></td>
        <td style="border: 1px solid black; border-bottom: 1px solid black !important; " align="center">F-2.16</td>
    </tr>
    <tr>
    <td colspan="3" width="90%"></td>
        <td style="border-top: 1px solid black;" align="center"></td></td>
    </tr>
    <tr><td width="30%">Pemerintah Kabupaten</td><td>:</td><td>PATI</td></tr>
    <tr><td>Kecamatan</td><td>:</td><td>JAKEN</td></tr>
    <tr><td>Desa</td><td>:</td><td>SUKORUKUN</td></tr>
</table>

<br>

<table class="center" style="border-collapse: separate; border-spacing: 0px">
    <tr>
        <td width="25%"></td>
        <td width="50%" style="background-color: #000; color: #fff;">
            <br><h3 style="margin: 0;">UNTUK YANG BERSANGKUTAN</h3><br>
        </td>
        <td width="25%"></td>
    </tr>
    <tr>
        <td></td>
        <td><h3 style="margin: 0;">SURAT KETERANGAN KEMATIAN</h3></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td><h3 style="margin: 0;">No : <?= $request['number'] ?></h3></td>
        <td></td>
    </tr>
</table>

<br>

<table style="border-collapse: separate; border-spacing: 0px">
    <tr>
        <td colspan="3"><p style="margin-left: 40px">Yang bertandatangan ini menerangkan bahwa :</p></td>
    </tr>
    <tr><td width="30%">Nama Lengkap</td><td width="1%">:</td><td><?= $request['name'] ?></td></tr>
    <tr><td>NIK</td><td>:</td><td><?= $request['nik'] ?></td></tr>
    <tr><td>Jenis Kelamin</td><td>:</td><td><?= $request['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
    <tr>
        <td>Tanggal Lahir / Umur</td>
        <td>:</td>
        <td><?= formatDateIndo($request['dob']) ?> / <?= getAge($request['dob']) ?> Tahun</td>
    </tr>
    <tr><td>Agama</td><td>:</td><td><?= $request['religion'] ?></td></tr>
    <tr><td>Alamat</td><td>:</td><td><?= $request['address'] ?></td></tr>
    <tr><td colspan="3">Telah Meninggal Dunia pada :</td></tr>
    <?php
    $dateOfDeath = explode(', ', formatDayDateIndo($request['death_date']));
    $day = $dateOfDeath[0];
    ?>
    <tr><td>Hari</td><td>:</td><td><?= $day ?></td></tr>
    <tr><td>Tanggal</td><td>:</td><td><?= $dateOfDeath[1] ?></td></tr>
    <tr><td>Bertempat di</td><td>:</td><td><?= $request['location'] ?></td></tr>
    <tr><td>Penyebab Kematian</td><td>:</td><td><?= $request['reason'] ?></td></tr>
    <tr>
        <td colspan="3"><p style="margin-left: 40px">Surat keterangan ini dibuat berdasarkan keterangan</p></td>
    </tr>
    <tr><td>Pelapor</td><td>:</td><td></td></tr>
    <tr><td>Nama Lengkap</td><td>:</td><td><?= $request['report_name'] ?></td></tr>
    <tr><td>NIK</td><td>:</td><td><?= $request['report_nik'] ?></td></tr>
    <tr>
        <td>Tanggal Lahir / Umur</td>
        <td>:</td>
        <td><?= formatDateIndo($request['report_dob']) ?> / <?= getAge($request['report_dob']) ?> Tahun</td>
    </tr>
    <tr><td>Pekerjaan</td><td>:</td><td><?= $request['report_occupation'] ?></td></tr>
    <tr><td>Alamat</td><td>:</td><td><?= $request['report_address'] ?></td></tr>
</table>

<p>Hubungan Pelapor dengan yang mati : <?= $request['relation'] ?></p>

<table class="center" style="margin-top: 50px;">
    <tr>
        <td width="50%"></td>
        <td width="50%"><?= $namaDesa ?>, <?= formatDateIndo($request['letter_date']) ?></td>
    </tr>
    <tr>
        <td></td>
        <td><?= $request['village_head_position'] ?> <?= $namaDesa ?></td>
    </tr>
    <tr>
        <td style="position: relative; height: 75px;"></td>
        <td style="position: relative; height: 75px;">
            <?php if ($sigKades): ?>
                <img src="<?= $sigKades ?>" alt="TTD Kades" class="logo"
                     style="position: absolute; top: -50px; left: 50px; width: 200px; height: 200px; object-fit: cover;">
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td><u><?= $request['village_head_name'] ?></u></td>
    </tr>
    <tr><td></td><td>NIP. <?= $request['village_head_nip'] ?></td></tr>
</table>

</body>
</html>
