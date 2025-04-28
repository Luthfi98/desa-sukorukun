<?= $this->extend('layouts/dashboard_layout'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="dashboard-card">
        <div class="mb-4">
            <h5 class="mb-0">Buat Pengaduan Baru</h5>
            <p class="text-muted small">Sampaikan pengaduan atau keluhan Anda kepada perangkat desa</p>
        </div>

        <?php if (session()->has('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->get('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form action="<?= base_url('complaints/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="subject" class="form-label">Judul Pengaduan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject" value="<?= old('subject') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= old('description') ?></textarea>
                <div class="form-text">Jelaskan secara detail permasalahan yang Anda alami</div>
            </div>
            
            <div class="mb-4">
                <label for="attachment" class="form-label">Lampiran (opsional)</label>
                <input type="file" class="form-control" id="attachment" name="attachment">
                <div class="form-text">Format file yang diperbolehkan: JPG, PNG, PDF. Ukuran maksimal: 2MB</div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?= base_url('complaints') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Kirim Pengaduan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Form validation with JavaScript could be added here
</script>
<?= $this->endSection(); ?> 