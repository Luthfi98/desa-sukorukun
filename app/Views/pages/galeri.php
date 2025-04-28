<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="fw-bold">Galeri Desa Sejahtera</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Galeri</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Gallery Item 1 -->
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?= base_url('assets/img/gallery/1.jpg') ?>" class="card-img-top" alt="Gallery Image">
                    <div class="card-body">
                        <h5 class="card-title">Kegiatan Desa</h5>
                        <p class="card-text">Kegiatan gotong royong membersihkan lingkungan desa.</p>
                    </div>
                </div>
            </div>
            <!-- Gallery Item 2 -->
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?= base_url('assets/img/gallery/2.jpg') ?>" class="card-img-top" alt="Gallery Image">
                    <div class="card-body">
                        <h5 class="card-title">Pembangunan</h5>
                        <p class="card-text">Proses pembangunan infrastruktur desa.</p>
                    </div>
                </div>
            </div>
            <!-- Gallery Item 3 -->
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?= base_url('assets/img/gallery/3.jpg') ?>" class="card-img-top" alt="Gallery Image">
                    <div class="card-body">
                        <h5 class="card-title">Kegiatan Sosial</h5>
                        <p class="card-text">Kegiatan sosial dan budaya masyarakat desa.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 