<?php 
    $kepalaDesa = get_setting('struktur_organisasi','kepala_desa', false);
    $sigKades = $request['village_head_signature'];
    if (file_exists($sigKades)) {
        $sigKades = 'data:image/png;base64,' . base64_encode(file_get_contents($sigKades));
    }
    $logo = get_setting('website','logo', false);
    $logoPath = FCPATH . $logo;
    $logoData = '';
    if (file_exists($logoPath)) {
        $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
    // var_dump($sigKades);die;
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
      width: 70px;
      height: auto;
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

  <table class="center" style="width=100%; margin-top: 50px">
        <tr>
            <td width="50%"></td>
            <td width="50%">Mengetahui</td>
        </tr>
        <tr>
            <td width="50%">Tanda tangan pemegang</td>
            <td width="50%"><b>Kepala Desa Sukorukun</b></td>
        </tr>
        <tr>
        <td width="50%" style="position: relative; height: 75px;">
            </td>
            <td width="50%" style="position: relative; height: 75px;">
            <?php if ($sigKades): ?>
                <img src="<?= $sigKades ?>" alt="TTD Kades" class="logo" style="position: absolute; top: -50px; left: 50px; width: 200px; height: 200px; object-fit: cover;">
            <?php endif; ?>
            </td>
        </tr>
        <tr>
          <td><?= $request['name'] ?></td>
          <td width="50%"><b><?= $request['village_head_name'] ?></b></td>
        </tr>
</table>

</body>
</html>

