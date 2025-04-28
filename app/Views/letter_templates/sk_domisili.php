<?php 
    $kepalaDesa = get_setting('struktur_organisasi','kepala_desa', false);
    $logo = get_setting('website','logo', false);
    $logoPath = FCPATH . $logo;
    $logoData = '';
    if (file_exists($logoPath)) {
        $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Keterangan Pengantar</title>
  <style>
    body {
      font-family: 'Times New Roman', Times, serif;
      margin: 50px;
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
      padding: 0px;
    }
    .signature-table {
      margin-top: 60px;
      width: 100%;
    }
    .signature-table td {
      width: 50%;
      text-align: center;
      vertical-align: top;
    }
    .logo {
        max-width: 70px;
        height: auto;
        display: block;
        margin: auto;
        }

  </style>
</head>
<body>

<table style="width: 100%;" border="0">
  <tr>
    
    <td class="bold" style="text-align: center; margin:left: 100px !important;">
    <?php if ($logoData): ?>
        <img src="<?= $logoData ?>" alt="Logo Desa Sukorukun" class="logo" style="float: left;">
      <?php endif; ?>
      <p style="margin:5px; margin-left: 10px;">PEMERINTAH KABUPATEN PATI</p>
      <p style="margin:5px; margin-left: 10px;">KECAMATAN JAKEN</p>
      <p style="margin:5px; margin-left: 10px;">DESA SUKORUKUN</p>
      <p style="margin:5px; margin-left: 10px;">Ds. Sukorukun Kode Pos 59184</p>
    </td>
  </tr>
</table>
<hr>
<div class="center bold">
    <p class="bold" style="text-decoration: underline;">SURAT KETERANGAN DOMISILI</p>
    <p>Nomor : <?= $request['number'] ?></p>
  </div>


  <p>Yang bertandatangan di bawah ini menerangkan bahwa :</p>

  <table>
    <tr>
        <td width="1%">1.</td>
        <td width="29%">Nama</td>
        <td width="1%">:</td>
        <td><?= $request['village_head_name'] ?></td>
    </tr>
    <tr>
        <td width="1%">2.</td>
        <td width="29%">Jabatan</td>
        <td width="1%">:</td>
        <td><?= $request['village_head_position'] ?></td>
    </tr>
    <tr>
        <td width="1%">3.</td>
        <td width="29%">Alamat</td>
        <td width="1%">:</td>
        <td>Desa SUKORUKUN RT 03 RW 01 Kecamatan Jaken Kabupaten Pati</td>
    </tr>
    <tr>
        <td colspan="4" style="padding-top: 20px; padding-bottom: 20px;">Menerangkan Bahwa : </td>
    </tr>
    <tr>
        <td width="1%">1.</td>
        <td width="29%">Nama</td>
        <td width="1%">:</td>
        <td><?= $request['name'] ?></td>
    </tr>
    <tr>
        <td width="1%">2.</td>
        <td width="29%">Bukti Diri</td>
        <td width="1%">:</td>
        <td><span>NIK: <?= $request['nik'] ?></span> <span style="margin-left: 10px;"> KK: <?= $request['kk'] ?></span></td>
    </tr>
    <tr>
        <td width="1%">3.</td>
        <td width="29%">Tempat, tanggal lahir</td>
        <td width="1%">:</td>
        <td><?= $request['pob'] ?>, <?= formatDateIndo($request['dob']) ?></td>
    </tr>
    <tr>
      <td width="1%">4.</td>
      <td>Agama</td>
      <td>:</td>
      <td><?= $request['religion'] ?></td>
    </tr>
    <tr>
      <td width="1%">5.</td>
      <td>Pekerjaan</td>
      <td>:</td>
      <td><?= $request['occupation'] ?></td>
    </tr>
    <tr>
        <td width="1%">6.</td>
        <td width="29%">Alamat</td>
        <td width="1%">:</td>
        <td><?= $request['address'] ?></td>
    </tr>
    <tr>
        <td width="1%">7.</td>
        <td width="29%">Keperluan</td>
        <td width="1%">:</td>
        <td><?= $request['purpose'] ?></td>
    </tr>
    <tr>
        <td width="1%">8.</td>
        <td width="29%">Berlaku</td>
        <td width="1%">:</td>
        <td>Mulai tanggal<?= formatDateIndo($request['valid_from']) ?></td>
    </tr>
    <tr>
        <td width="1%">9.</td>
        <td width="29%">Keterangan Lain-lain</td>
        <td width="1%">:</td>
        <td><?= $request['purpose'] ?></td>
    </tr>
    <tr>
      <td colspan="4" style="padding-top: 20px; padding-bottom: 20px;">
      Demikian surat keterangan domisili ini kami buat sebagaimana perlunya semoga dapat
      digunakan sebagaimana mestinya.
      </td>
    </tr>
  </table>

  <table class="signature-table">
    <tr>
      <td>
        <br>
        Pemohon,<br><br><br><br><br>
        (<?= $request['name'] ?>)
      </td>
      <td>
        Sukorukun, <?= formatDateIndo($request['valid_from']) ?><br>
        <strong><?= $request['village_head_position'] ?> Sukorukun</strong><br><br><br><br><br>
        <span class="bold">(<?= $request['village_head_name'] ?>)</span>
      </td>
    </tr>
  </table>

</body>
</html>

