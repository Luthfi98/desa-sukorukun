<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="fw-bold">Profil <?= $namaDesa ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profil Desa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Profile Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="<?= $logoDesa ?>" alt="<?= $namaDesa ?>" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Sejarah Desa</h2>
                <p class="mb-0"><?= $sejarahDesa ?></p>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Visi</h3>
                        <p class="mb-0"><?= $visiDesa ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Misi</h3>
                        <p class="mb-0"><?= $misiDesa ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-12">
                <h3 class="fw-bold mb-4">Struktur Organisasi</h3>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-md-4 mx-auto mb-4">
                                <div class="position-relative mb-3">
                                    <img src="https://source.unsplash.com/random/150x150/?man,portrait" class="rounded-circle border border-3 border-primary" width="120" height="120" alt="Kepala Desa">
                                </div>
                                <h5 class="fw-bold">H. Ahmad Suparjo</h5>
                                <p class="text-muted">Kepala Desa</p>
                            </div>
                        </div>
                        <div class="row text-center g-4 mt-2">
                            <div class="col-md-3 mb-4">
                                <div class="position-relative mb-3">
                                    <img src="https://source.unsplash.com/random/150x150/?woman,portrait" class="rounded-circle border border-3 border-primary" width="100" height="100" alt="Sekretaris Desa">
                                </div>
                                <h5 class="fw-bold">Siti Aminah, S.IP</h5>
                                <p class="text-muted">Sekretaris Desa</p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="position-relative mb-3">
                                    <img src="https://source.unsplash.com/random/150x150/?man,portrait,2" class="rounded-circle border border-3 border-primary" width="100" height="100" alt="Kaur Keuangan">
                                </div>
                                <h5 class="fw-bold">Budi Santoso</h5>
                                <p class="text-muted">Kaur Keuangan</p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="position-relative mb-3">
                                    <img src="https://source.unsplash.com/random/150x150/?woman,portrait,2" class="rounded-circle border border-3 border-primary" width="100" height="100" alt="Kaur Umum">
                                </div>
                                <h5 class="fw-bold">Ratna Dewi</h5>
                                <p class="text-muted">Kaur Umum</p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="position-relative mb-3">
                                    <img src="https://source.unsplash.com/random/150x150/?man,portrait,3" class="rounded-circle border border-3 border-primary" width="100" height="100" alt="Kaur Pembangunan">
                                </div>
                                <h5 class="fw-bold">Hendra Wijaya</h5>
                                <p class="text-muted">Kaur Pembangunan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="fw-bold mb-4">Demografis</h3>
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="fw-bold">Jumlah Penduduk</h5>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="2500" aria-valuemin="0" aria-valuemax="2500">2,500 Jiwa</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold">Jenis Kelamin</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="1200" aria-valuemin="0" aria-valuemax="1200">1,200 Laki-laki</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="1300" aria-valuemin="0" aria-valuemax="1300">1,300 Perempuan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold">Jumlah Keluarga</h5>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="650" aria-valuemin="0" aria-valuemax="650">650 KK</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="fw-bold mb-4">Geografis</h3>
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="40%"><strong>Luas Wilayah</strong></td>
                                    <td>: 500 Hektar</td>
                                </tr>
                                <tr>
                                    <td><strong>Batas Utara</strong></td>
                                    <td>: Desa Harapan</td>
                                </tr>
                                <tr>
                                    <td><strong>Batas Selatan</strong></td>
                                    <td>: Desa Makmur</td>
                                </tr>
                                <tr>
                                    <td><strong>Batas Barat</strong></td>
                                    <td>: Kecamatan Jaya</td>
                                </tr>
                                <tr>
                                    <td><strong>Batas Timur</strong></td>
                                    <td>: Hutan Lindung</td>
                                </tr>
                                <tr>
                                    <td><strong>Ketinggian</strong></td>
                                    <td>: 500 - 700 mdpl</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Dusun</strong></td>
                                    <td>: 5 Dusun</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah RT/RW</strong></td>
                                    <td>: 20 RT / 5 RW</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 