<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Import Data Penduduk</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->has('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('residents/processImport') ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file">Pilih File Excel (.xlsx) atau CSV (.csv)</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.csv" required>
                            <small class="form-text text-muted">
                                Format file harus sesuai dengan template yang disediakan. 
                                <a href="<?= base_url('residents/export-template') ?>" class="text-primary">Download Template</a>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">Import Data</button>
                        <a href="<?= base_url('residents') ?>" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 