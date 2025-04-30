<?php 
    $namaDesa = get_setting('informasi_desa','nama_desa', false); 
    $sigKades = get_setting('etc','ttd_kepala_desa', false);
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
  <p style="text-decoration: underline;">SURAT KETERANGAN AHLI WARIS</p>
  <p>Nomor : <?= $request['number'] ?></p>
</div>

<div class="content">
  <p>Yang bertanda tangan di bawah ini Lurah Sukorukun Kecamatan Jaken Kabupaten Pati, menerangkan dengan sebenarnya bahwa :</p>

  <table>
    <tr>
      <td width="30%">Nama</td>
      <td width="1%">:</td>
      <td><?= $request['name'] ?></td>
    </tr>
    <tr>
      <td width="30%">NIK</td>
      <td width="1px">:</td>
      <td><?= $request['nik'] ?></td>
    </tr>
    <tr>
      <td width="30%">Tempat, tanggal lahir</td>
      <td width="1px">:</td>
      <td><?= $request['pob'] ?>, <?= formatDateIndo($request['dob']) ?></td>
    </tr>
    <tr>
      <td width="30%">Jenis Kelamin</td>
      <td width="1px">:</td>
      <td><?= $request['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' ?></td>
    </tr>
    <tr>
      <td width="30%">Agama</td>
      <td width="1px">:</td>
      <td><?= $request['religion'] ?></td>
    </tr>
    <tr>
      <td width="30%">Pekerjaan</td>
      <td width="1px">:</td>
      <td><?= $request['occupation'] ?></td>
    </tr>
    <tr>
      <td width="30%">Alamat</td>
      <td width="1px">:</td>
      <td><?= $request['address'] ?></td>
    </tr>
  </table>

  <p>
    Adalah benar penduduk yang bersangkutan telah meninggal dunia pada hari <?= formatDayDateIndo($request['date_of_death']) ?> pukul <?= $request['time_of_death'] ?> WIB <?= $request['place_of_death'] ?>, <?= $request['cause_of_death'] ?>, dan telah dimakamkan pada tanggal <?= formatDayDateIndo($request['burial_date']) ?> <?= $request['burial_location'] ?>. Selanjutnya, <?= $request['gender'] == 'male' ? 'almarhum' : 'almarhumah' ?> mempunyai susunan ahli waris sebagai berikut:
  </p>

  <div class="ahli-waris">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Tempat/Tanggal Lahir</th>
          <th>Hubungan</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $heirData = json_decode($request['heir_data'], true);
        $pelapor = '';
        foreach($heirData as $key => $heir): 
            if ($heir['is_reporter']) {
                $pelapor = $heir['relationship'];
            }
        ?>
        <tr>
            <td><?= $key + 1 ?></td>
            <td><?= $heir['name'] ?></td>
            <td><?= $heir['birth_place'] ?>, <?= formatDateIndo($heir['birth_date']) ?></td>
            <td><?= $heir['relationship'] ?></td>
            <td><?= $heir['is_reporter'] ? 'Pelapor' :  '' ?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>

  <p>
    Demikian surat keterangan ahli waris ini dibuat dengan sebenarnya atas permintaan <?= $pelapor ?> <?= $request['gender'] == 'male' ? 'almarhum' : 'almarhumah' ?>  sesuai dengan pernyataan ahli waris tertanggal <?= formatDayDateIndo($request['created_at']) ?>.
  </p>
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
