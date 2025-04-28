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
      padding: 4px;
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
    <p class="bold" style="text-decoration: underline;">SURAT KETERANGAN</p>
    <p class="bold">PENGANTAR</p>
    <p>Nomor : <?= $request['number'] ?></p>
  </div>


  <p>Yang bertandatangan di bawah ini menerangkan bahwa :</p>

  <table>
    <tr>
        <td>1.</td>
        <td>Nama</td>
        <td>: <?= $request['name'] ?> ( <?= $request['gender'] == 'male' ? '<s>L</s> / P' : 'L / <s>P</s>' ?> )</td></tr>
    <tr>
        <td>2.</td>
        <td>Tempat, tanggal lahir</td>
        <td>: <?= $request['pob'] ?>, <?= date('d F Y', strtotime($request['dob'])) ?></td></tr>
    <tr>
        <td>3.</td>
        <td>Kewarganegaraan & agama</td>
        <td>: Indonesia / <?= $request['religion'] ?></td></tr>
    <tr>
        <td>4.</td>
        <td>Pekerjaan</td>
        <td>: <?= $request['occupation'] ?></td></tr>
    <tr>
        <td>5.</td>
        <td>Tempat tinggal</td>
        <td>: Desa Sukorukun RT. <?= $request['rt']?> RW <?= $request['rw'] ?><br>
      &nbsp;&nbsp;Kecamatan Jaken Kabupaten Pati</td></tr>
    <tr>
        <td>6.</td>
        <td>Surat bukti diri</td>
        <td>: KTP : <?= $request['nik'] ?></td></tr>
    <tr>
        <td>7.</td>
        <td>Keperluan</td>
        <td>: <?= $request['purpose'] ?></td></tr>
    <tr>
        <td>8.</td>
        <td>Berlaku Mulai</td>
        <td>: <?= date('d F Y', strtotime($request['valid_from'])) ?> s/d Seperlunya</td></tr>
    <tr>
        <td>9.</td>
        <td>Keterangan lain - lain</td>
        <td>: <?= $request['description'] ?></td></tr>
  </table>

  <br>
  <p>Demikian untuk menjadikan maklum bagi yang berkepentingan.</p>

  <table class="signature-table">
    <tr>
      <td>
        <br>
        Tanda tangan pemegang,<br><br><br><br><br>
        <?= $request['name'] ?>
      </td>
      <td>
        Mengetahui<br>
        <strong>Kepala Desa Sukorukun</strong><br><br><br><br><br>
        <span class="bold"><?= $kepalaDesa ?></span>
      </td>
    </tr>
  </table>

</body>
</html>

