<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View News</h1>
        <div>
            <a href="<?= base_url('news/edit/' . $news['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="<?= base_url('news') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">News Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-4"><?= $news['title'] ?></h2>
                    
                    <?php if ($news['image']): ?>
                        <div class="mb-4">
                            <img src="<?= base_url($news['image']) ?>" alt="<?= $news['title'] ?>" class="img-fluid rounded">
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <?= $news['content'] ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">News Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Type</th>
                                    <td><?= ucfirst($news['type']) ?></td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td><?= $news['category'] ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-<?= $news['status'] === 'published' ? 'success' : ($news['status'] === 'draft' ? 'warning' : 'info') ?>">
                                            <?= ucfirst($news['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td><?= date('d/m/Y H:i', strtotime($news['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
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