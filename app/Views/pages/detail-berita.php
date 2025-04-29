<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="fw-bold">Detail Berita</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('berita') ?>">Berita & Informasi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Berita</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- News Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm mb-4">
                    <img src="<?= base_url($news['image']) ?>" class="card-img-top" alt="<?= $news['title'] ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="badge bg-primary"><?= $news['category'] ?></span>
                            <small class="text-muted"><i class="far fa-calendar me-1"></i> <?= formatDateIndo($news['created_at']) ?></small>
                        </div>
                        <h1 class="card-title fw-bold mb-4"><?= $news['title'] ?></h1>
                        <div class="d-flex align-items-center mb-4">
                            <span class="fa fa-user-tie fa-2x"></span>
                            <div class="ms-3">
                                <h6 class="mb-0"><?= $news['name'] ?></h6>
                                <small class="text-muted">Penulis</small>
                            </div>
                        </div>
                        <div class="content mb-4">
                            <?= $news['content'] ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="social-share">
                                <span class="me-2">Bagikan:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url() ?>" target="_blank" rel="noopener noreferrer" class="text-primary me-2"><i class="fab fa-facebook"></i></a>
                                <a href="https://twitter.com/intent/tweet?url=<?= current_url() ?>" target="_blank" rel="noopener noreferrer" class="text-info me-2"><i class="fab fa-twitter"></i></a>
                                <a href="https://api.whatsapp.com/send?text=<?= current_url() ?>" target="_blank" rel="noopener noreferrer" class="text-success"><i class="fab fa-whatsapp"></i></a>
                                <a href="https://www.instagram.com/?url=<?= current_url() ?>" target="_blank" rel="noopener noreferrer" class="text-danger me-2"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Search Widget -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Cari Berita</h5>
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Cari berita..." aria-label="Search">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </form>
                    </div>
                </div>

                <!-- Categories Widget -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Kategori</h5>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($category as $cat) : ?>
                                <li class="mb-2">
                                    <a href="<?= base_url('berita?category=' . $cat['category']) ?>" class="text-decoration-none">
                                        <i class="fas fa-chevron-right me-2"></i> <?= $cat['category'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Recent News Widget -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Berita Terbaru</h5>
                        <?php foreach ($recent_news as $recent) : ?>
                            <div class="d-flex mb-3">
                                <img src="<?= base_url($recent['image']) ?>" class="rounded me-3" width="60" height="60" alt="<?= $recent['title'] ?>">
                                <div>
                                    <h6 class="mb-0"><a href="<?= base_url('berita/' . $recent['id'].'-'.$recent['slug']) ?>" class="text-decoration-none"><?= $recent['title'] ?></a></h6>
                                    <small class="text-muted"><?= formatDateIndo($recent['created_at']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 
