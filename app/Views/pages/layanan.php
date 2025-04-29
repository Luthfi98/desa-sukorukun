<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="fw-bold">Layanan Desa</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Layanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="fw-bold mb-4"><?= $textLayanan['value'] ?></h2>
                <?= $textLayanan['description'] ?>
                 <div class="row mt-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Jam Layanan</h5>
                                <small class="text-muted"><?= $jamLayanan ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Telepon</h5>
                                <small class="text-muted"><?= $phone ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="<?= base_url('uploads/settings/kantor-desa.jpg') ?>" alt="Kantor Desa" class="img-fluid rounded shadow">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12 text-center mb-5">
                <h2 class="fw-bold">Jenis Layanan</h2>
                <p class="text-muted">Berikut adalah layanan yang tersedia di Kantor Desa</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Service 1 -->
             <?php foreach ($layanan as $key => $value) :
                $documents = explode(',', $value['required_documents']);
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="mb-4 text-center">
                                <div class="bg-primary d-inline-block p-3 rounded-circle text-white mb-3">
                                    <i class="fas fa-id-card fa-2x"></i>
                                </div>
                                <h4 class="fw-bold"><?= $value['name'] ?></h4>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php foreach($documents as $document): ?>
                                    <li class="list-group-item bg-transparent px-0">
                                        <i class="fas fa-check-circle text-success me-2"></i> <?= $document ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <!-- <div class="text-center mt-3">
                                <a href="#" class="btn btn-outline-primary">Detail Layanan</a>
                            </div> -->
                        </div>
                    </div>
                </div>
             <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Service Process -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-5">
                <h2 class="fw-bold">Alur Pelayanan</h2>
                <p class="text-muted">Berikut adalah proses untuk mendapatkan layanan di Kantor Desa Sejahtera</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">1</h3>
                                </div>
                                <h5 class="fw-bold">Persiapan Dokumen</h5>
                                <p class="text-muted">Siapkan dokumen yang diperlukan sesuai dengan jenis layanan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">2</h3>
                                </div>
                                <h5 class="fw-bold">Pengisian Formulir</h5>
                                <p class="text-muted">Isi formulir permohonan yang tersedia di kantor desa</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">3</h3>
                                </div>
                                <h5 class="fw-bold">Verifikasi</h5>
                                <p class="text-muted">Petugas akan memeriksa kelengkapan dokumen anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">4</h3>
                                </div>
                                <h5 class="fw-bold">Pengambilan Dokumen</h5>
                                <p class="text-muted">Ambil dokumen yang telah selesai sesuai waktu yang ditentukan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-8 mx-auto text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Butuh Informasi Lebih Lanjut?</h4>
                        <p>Silakan hubungi kami di nomor (021) 1234-5678 atau datang langsung ke Kantor Desa Sejahtera</p>
                        <a href="<?= base_url('kontak') ?>" class="btn btn-primary px-4 py-2">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 