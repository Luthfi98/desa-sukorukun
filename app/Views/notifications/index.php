<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h4 class="mb-0">Notifikasi</h4>
                    <?php if (!empty($notifications)): ?>
                    <a href="<?= base_url('notifications/mark-all-as-read') ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                            <h5>Tidak ada notifikasi</h5>
                            <p class="text-muted">Anda belum memiliki notifikasi saat ini.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group notification-list">
                            <?php foreach ($notifications as $notification): ?>
                                <div class="list-group-item list-group-item-action <?= $notification['is_read'] ? '' : 'unread' ?> d-flex">
                                    <div class="notification-icon me-3">
                                        <i class="fas fa-bell <?= $notification['is_read'] ? 'text-muted' : 'text-primary' ?>"></i>
                                    </div>
                                    <div class="notification-content flex-grow-1">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1 <?= $notification['is_read'] ? '' : 'fw-bold' ?>"><?= esc($notification['title']) ?></h5>
                                            <small class="text-muted"><?= date('d M Y H:i', strtotime($notification['created_at'])) ?></small>
                                        </div>
                                        <p class="mb-1"><?= esc($notification['message']) ?></p>
                                        <?php if (!empty($notification['link'])): ?>
                                            <a href="<?= base_url('notifications/mark-as-read/' . $notification['id']) ?>" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-external-link-alt"></i> Lihat Detail
                                            </a>
                                        <?php elseif (!$notification['is_read']): ?>
                                            <a href="<?= base_url('notifications/mark-as-read/' . $notification['id']) ?>" class="btn btn-sm btn-outline-secondary mt-2">
                                                <i class="fas fa-check"></i> Tandai Dibaca
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-list .unread {
        background-color: rgba(13, 110, 253, 0.05);
        border-left: 3px solid #0d6efd;
    }
    .notification-icon {
        display: flex;
        align-items: center;
        font-size: 1.25rem;
    }
</style>

<?= $this->endSection() ?> 