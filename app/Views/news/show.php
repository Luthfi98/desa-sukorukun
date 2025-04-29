<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Berita</h1>
        <div>
            <a href="<?= base_url('news/edit/' . $news['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="<?= base_url('news') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Berita</h6>
            <div>
                <?php
                    switch ($news['status']) {
                        case 'draft':
                            $statusColor = 'warning';
                            break;
                            
                        case 'published':
                            $statusColor = 'info';
                            break;
                            
                        case 'active':
                            $statusColor = 'success';
                            break;
                            
                        case 'inactive':
                            $statusColor = 'danger';
                            break;
                    }
                ?>
                <span class="badge bg-<?= $statusColor ?>">
                    <?= ucfirst($news['status']) ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-4"><?= $news['title'] ?></h2>
                    
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-user mr-2 text-gray-600"></i>
                        <span class="text-gray-600">Ditulis oleh: <?= $news['author_name'] ?? 'Admin' ?></span>
                    </div>
                    
                    <?php if ($news['image']): ?>
                        <div class="mb-4">
                            <img src="<?= base_url($news['image']) ?>" alt="<?= $news['title'] ?>" class="img-fluid rounded shadow">
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <?= $news['content'] ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="m-0 font-weight-bold">Detail Informasi</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Jenis</th>
                                    <td><?= ucfirst($news['type']) ?></td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td><?= $news['category'] ?></td>
                                </tr>
                                <tr>
                                    <th>Penulis</th>
                                    <td><?= $news['author_name'] ?? 'Admin' ?></td>
                                </tr>
                                <tr>
                                    <th>Dibuat Pada</th>
                                    <td><?= date('d/m/Y H:i', strtotime($news['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Diperbarui Pada</th>
                                    <td><?= date('d/m/Y H:i', strtotime($news['updated_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 