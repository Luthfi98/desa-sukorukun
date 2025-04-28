<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <?php if (session()->has('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profile/update-password') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control <?= (session('errors.current_password')) ? 'is-invalid' : '' ?>" id="current_password" name="current_password">
                            <?php if (session('errors.current_password')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.current_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control <?= (session('errors.new_password')) ? 'is-invalid' : '' ?>" id="new_password" name="new_password">
                            <?php if (session('errors.new_password')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.new_password') ?>
                                </div>
                            <?php endif; ?>
                            <small class="text-muted">Password minimal 6 karakter</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control <?= (session('errors.confirm_password')) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password">
                            <?php if (session('errors.confirm_password')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.confirm_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('profile') ?>" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 