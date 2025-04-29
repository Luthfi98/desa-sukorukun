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
                        <?php $kades = $strukturDesa[0]; ?>
                        <div class="row text-center">
                            <div class="col-md-4 mx-auto mb-4">
                                <div class="position-relative mb-3">
                                    <i class="fa fa-user-tie fa-3x text-primary"></i>
                                </div>
                                <h5 class="fw-bold"><?= $kades['value'] ?></h5>
                                <p class="text-muted"><?= $kades['label'] ?></p>
                            </div>
                        </div>
                        <div class="row text-center g-4 mt-2 d-flex justify-content-center">
                            <?php foreach ($strukturDesa as $key => $struktur) : ?>
                                <?php if ($key == 0) continue; ?>
                                <div class="col-md-3 mb-4">
                                    <div class="position-relative mb-3">
                                        <i class="fa fa-user-tie fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold"><?= $struktur['value'] ?></h5>
                                    <p class="text-muted"><?= $struktur['label'] ?></p>
                                </div>
                            <?php endforeach;?>
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
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="<?= $demografis ?>" aria-valuemin="0" aria-valuemax="<?= $demografis ?>"><?= $demografis ?> Jiwa</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold">Jenis Kelamin</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="<?= $male ?>" aria-valuemin="0" aria-valuemax="<?= $male ?>"><?= $male ?> Laki-laki</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="<?= $female ?>" aria-valuemin="0" aria-valuemax="<?= $female ?>"><?= $female ?> Perempuan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold">Jumlah Keluarga</h5>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="<?= $kk ?>" aria-valuemin="0" aria-valuemax="<?= $kk ?>"><?= $kk ?> KK</div>
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
                                <?php foreach ($geografis as $geo) : ?>
                                    <tr>
                                        <td width="40%"><strong><?= $geo['label'] ?></strong></td>
                                        <td>: <?=  $geo['value'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 