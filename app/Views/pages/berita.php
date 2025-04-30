<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="fw-bold">Berita & Informasi Desa</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Berita & Informasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="py-5">
    <div class="container">
        <!-- Search and Category Bar -->
        <div class="row mb-5">
            <div class="col-md-8">
                <form class="d-flex" action="<?= base_url('berita') ?>" method="get">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari berita..." aria-label="Search" value="<?= $search ?? '' ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>
            <div class="col-md-4">
                <form action="<?= base_url('berita') ?>" method="get">
                    <select class="form-control" name="category" onchange="this.form.submit()">
                        <option value="Semua Kategori" <?= ($categoryFilter ?? '') === 'Semua Kategori' ? 'selected' : '' ?>>Semua Kategori</option>
                        <?php foreach ($category as $cat) : ?>
                            <option value="<?= $cat['category'] ?>" <?= ($categoryFilter ?? '') === $cat['category'] ? 'selected' : '' ?>><?= $cat['category'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($search)): ?>
                        <input type="hidden" name="search" value="<?= $search ?>">
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <!-- News List -->
        <div class="row">
            <?php if (empty($news)): ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Belum ada berita & informasi yang tersedia
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($news as $new) : ?>
                <div class="col-lg-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?= base_url($new['image']) ?>" class="img-fluid rounded-start h-100 object-fit-cover" alt="Berita 1">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge bg-primary"><?= $new['category'] ?></span>
                                        <small class="text-muted"><i class="far fa-calendar me-1"></i> <?= formatDateIndo($new['created_at']) ?></small>
                                    </div>
                                    <h3 class="card-title fw-bold"><?= $new['title'] ?></h3>
                                    <p class="card-text"><?= mb_strimwidth($new['content'], 0, 250, '...') ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?= base_url('berita/' . $new['id'].'-'.$new['slug']) ?>" class="btn btn-primary">Baca selengkapnya</a>
                                        <div>
                                            <span class="me-3"><i class="fa fa-user-tie"></i> <?= $new['name'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <?= $pager->links('default', 'bootstrap_pagination') ?>
                </nav>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 