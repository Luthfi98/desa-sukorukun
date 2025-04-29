<?php 
    $namaDesa = get_setting('informasi_desa','nama_desa', false); 
    $sigKades = get_setting('etc','ttd_kades', false);
    if (file_exists($sigKades)) {
        $sigKades = 'data:image/png;base64,' . base64_encode(file_get_contents($sigKades));
    }
    $sigCamat = get_setting('etc','ttd_camat', false);
    if (file_exists($sigCamat)) {
        $sigCamat = 'data:image/png;base64,' . base64_encode(file_get_contents($sigCamat));
    }
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
  <p style="text-decoration: underline;">SURAT KETERANGAN PINDAH</p>
  <p>Nomor : <?= $request['number'] ?></p>
</div>

<div class="content">
        <table>
            <tr>
                <td width="1%">1.</td>
                <td width="29%">Nama lengkap</td>
                <td><?= $request['name'] ?></td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Jenis Kelamin</td>
                <td><?= $request['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Dilahirkan</td>
                <td><?= $request['pob'] ?>, <?= formatDayDateIndo($request['dob']) ?></td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Kewarganegaraan</td>
                <td><?= $request['nationality'] ?></td>
            </tr>
            <tr>
                <td>5.</td>
                <td>Agama</td>
                <td><?= $request['religion'] ?></td>
            </tr>
            <tr>
                <td>6.</td>
                <td>Status Perkawinan</td>
                <td><?= $request['marital_status'] ?></td>
            </tr>
            <tr>
                <td>7.</td>
                <td>Pekerjaan</td>
                <td><?= $request['occupation'] ?></td>
            </tr>
            <tr>
                <td>8.</td>
                <td>Pendidikan Terakhir</td>
                <td><?= $request['education'] ?></td>
            </tr>
            <tr>
                <td>9.</td>
                <td>Alamat Asal</td>
                <td><?= $request['origin_address'] ?></td>
            </tr>
            <tr>
                <td>10.</td>
                <td>Nomor KTP</td>
                <td><?= $request['nik'] ?></td>
            </tr>
            <tr>
                <td>11.</td>
                <td>Pindah Ke</td>
                <td>
                    <?php $destination = json_decode($request['destination_detail']) ?>
                    Dusun : <?= $destination->dusun ?><br>
                    RT : <?= $destination->rt ?><br>
                    RW : <?= $destination->rw ?><br>
                    Desa : <?= $destination->desa ?><br>
                    Kecamatan : <?= $destination->kecamatan ?><br>
                    Kabupaten : <?= $destination->kabupaten ?><br>
                    Provinsi : <?= $destination->provinsi ?><br>
                    Pada Tanggal : <?= formatDayDateIndo($request['move_date']) ?>
                </td>
            </tr>
            <tr>
                <td>12.</td>
                <td>Alasan Pindah</td>
                <td><?= $request['reason'] ?></td>
            </tr>
            <tr>
                <td>13.</td>
                <td>Pengikut</td>
                <td>
                    <?php $followers = json_decode($request['move_followers']) ?>
                    <?= count($followers) ?> Orang
                </td>
            </tr>
        </table>

  <div class="ahli-waris">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th colspan="2">Jenis Kelamin</th>
          <th>Status Perkawinan</th>
          <th>Pendidikan</th>
          <th>NIK</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach($followers as $key => $follower): ?>
        <tr>
            <td><?= $key + 1 ?></td>
            <td><?= $follower->name ?></td>
            <td><?= $follower->gender == 'male' ? 'L':'' ?></td>
            <td><?= $follower->gender == 'male' ? '':'P' ?></td>
            <td><?= $follower->marital_status ?></td>
            <td><?= $follower->education ?></td>
            <td><?= $follower->id_card ?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>

</div>

<table class="center" style="width=100%; margin-top: 50px">
        <tr>
            <td width="50%">Diketahui Oleh</td>
            <td width="50%"><?= $namaDesa ?>, <?= formatDateIndo($request['updated_at']) ?></td>
        </tr>
        <tr>
            <td width="50%">Camat Jaken</td>
            <td width="50%"><?= $request['subdistrict_head_name'] ?></td>
        </tr>
        <tr>
        <td width="50%" style="position: relative; height: 75px;">
            <?php if ($sigCamat): ?>
                <img src="<?= $sigCamat ?>" alt="TTD Camat" class="logo" style="position: absolute; top: -50px; left: 50px; width: 200px; height: 200px; object-fit: cover;">
            <?php endif; ?>
            </td>
            <td width="50%" style="position: relative; height: 75px;">
            <?php if ($sigKades): ?>
                <img src="<?= $sigKades ?>" alt="TTD Kades" class="logo" style="position: absolute; top: -50px; left: 50px; width: 200px; height: 200px; object-fit: cover;">
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td width="50%"><u><?= $request['subdistrict_head_name'] ?></u></td>
            <td width="50%"><u><?= $request['village_head_name'] ?></u></td>
        </tr>
        <tr>
            <td width="50%"><?= $request['subdistrict_head_position'] ?></td>
            <td width="50%"><?= $request['village_head_position'] ?></td>
        </tr>
        <tr>
            <td width="50%">NIP. <?= $request['subdistrict_head_nip'] ?></td>
            <td width="50%">NIP. <?= $request['village_head_nip'] ?></td>
        </tr>
</table>

</body>
</html>
