<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <h1 class="display-4 fw-bold mb-4">Selamat Datang di <?= $namaDesa ?></h1>
                <p class="lead mb-5"><?= $visiDesa ?></p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#layanan" class="btn btn-primary btn-lg px-4 py-3">Lihat Layanan</a>
                    <a href="#profil" class="btn btn-outline-light btn-lg px-4 py-3">Tentang Kami</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Announcement Section -->
<section class="bg-light py-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white p-2 me-3">
                        <i class="fas fa-bullhorn fa-2x"></i>
                    </div>
                    <div class="announcement-slider overflow-hidden">
                        <div class="py-2">
                            <strong>PENGUMUMAN:</strong> Jadwal vaksinasi COVID-19 tahap kedua akan dilaksanakan pada tanggal 25 Juni 2023 di Balai Desa.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="layanan" class="section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-md-8 mx-auto">
                <h2 class="fw-bold">Layanan Kami</h2>
                <p class="text-muted">Berbagai layanan yang tersedia untuk memudahkan warga desa</p>
            </div>
        </div>
        <div class="row g-4">
        <?php
            foreach ($layananDesa as $layanan):
            ?>
                <div class="col-md-4">
                    <div class="card feature-box shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <?= $layanan['description'] ?>
                            </div>
                            <h4><?=$layanan['label'] ?></h4>
                            <p class="text-muted"><?= $layanan['value'] ?></p>
                            <!-- <a href="#" class="btn btn-sm btn-outline-primary mt-3">Selengkapnya</a> -->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="profil" class="section-padding bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="<?= base_url('uploads/settings/desa_(10).jpeg') ?>" alt="Desa Sejahtera"  class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Tentang <?= $namaDesa ?></h2>
                <p><?= $namaDesa ?> merupakan desa yang terletak di kaki gunung dengan pemandangan alam yang indah dan udara yang sejuk. Mayoritas penduduk bermata pencaharian sebagai petani dan peternak.</p>
                <p>Dengan luas wilayah sekitar 500 hektar, desa kami memiliki potensi pertanian, peternakan, dan pariwisata yang menjanjikan.</p>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?= $demografis ?></h5>
                                <small class="text-muted">Jumlah Penduduk</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-home"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?= $kk ?></h5>
                                <small class="text-muted">Jumlah Rumah Tangga</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?= $luasWilayah ?></h5>
                                <small class="text-muted">Luas Wilayah</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?= $ketinggian ?></h5>
                                <small class="text-muted">Ketinggian</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-md-8 mx-auto">
                <h2 class="fw-bold">Berita Terbaru</h2>
                <p class="text-muted">Informasi terkini seputar kegiatan dan program di Desa Sejahtera</p>
            </div>
        </div>
        <div class="row g-4 d-flex justify-content-center">
            <?php foreach($news as $new) :?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= base_url($new['image']) ?>" class="card-img-top" alt="Berita 1">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <small class="text-muted"><i class="far fa-calendar me-1"></i><?= formatDateIndo($new['created_at']) ?></small>
                                <small class="text-primary"><?=  $new['category'] ?></small>
                            </div>
                            <h5 class="card-title"><?= $new['title'] ?></h5>
                            <p class="card-text"><?= mb_strimwidth($new['content'], 0, 200, '...') ?></p>
                            <a href="<?= base_url('berita/' . $new['id'].'-'.$new['slug']) ?>" class="btn btn-link p-0">Baca selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= base_url('berita') ?>" class="btn btn-primary px-4 py-2">Lihat Semua Berita</a>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-4">Hubungi Kami</h2>
                <p class="mb-4">Silakan hubungi kami untuk informasi lebih lanjut atau kirimkan pertanyaan, saran, dan masukan Anda melalui formulir berikut:</p>
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Subjek" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" rows="5" placeholder="Pesan" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 py-2">Kirim Pesan</button>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="ratio ratio-16x9 h-100 min-vh-50">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15848.179364947933!2d111.2081315!3d-6.7643873!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e772e17aa6aa63b%3A0x5027a76e3560360!2sSukorukun%2C%20Kec.%20Jaken%2C%20Kabupaten%20Pati%2C%20Jawa%20Tengah!5e0!3m2!1sid!2sid!4v1745918380731!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 