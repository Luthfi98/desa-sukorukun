<?= $this->extend('layouts/dashboard_layout'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Detail Pengaduan</h5>
                    <a href="<?= base_url('complaints') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                
                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('message') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('errors')) : ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->get('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>

                <div class="card mb-4 border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><?= esc($complaint['subject']); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Tanggal</div>
                            <div class="col-md-9"><?= date('d M Y H:i', strtotime($complaint['created_at'])); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Status</div>
                            <div class="col-md-9">
                                <?php
                                $badgeClass = 'secondary';
                                $statusText = 'Menunggu';
                                
                                switch ($complaint['status']) {
                                    case 'pending':
                                        $badgeClass = 'warning';
                                        $statusText = 'Menunggu';
                                        break;
                                    case 'processing':
                                        $badgeClass = 'info';
                                        $statusText = 'Diproses';
                                        break;
                                    case 'completed':
                                    case 'resolved':
                                        $badgeClass = 'success';
                                        $statusText = 'Selesai';
                                        break;
                                    case 'rejected':
                                        $badgeClass = 'danger';
                                        $statusText = 'Ditolak';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?= $badgeClass; ?>"><?= $statusText; ?></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Deskripsi</div>
                            <div class="col-md-9">
                                <div class="p-3 bg-light rounded">
                                    <?= nl2br(esc($complaint['description'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($complaint['attachment'])) : ?>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Lampiran</div>
                                <div class="col-md-9">
                                    <?php 
                                    $ext = pathinfo($complaint['attachment'], PATHINFO_EXTENSION);
                                    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) : 
                                    ?>
                                        <img src="/uploads/complaints/<?= $complaint['attachment']; ?>" alt="Lampiran" class="img-fluid mb-2">
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?= base_url('complaints/download/' . $complaint['id']); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download me-1"></i> Download Lampiran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Responses Section -->
                <h5 class="mb-3">Tanggapan</h5>
                <?php if (empty($responses)) : ?>
                    <div class="alert alert-info">
                        Belum ada tanggapan untuk pengaduan ini.
                    </div>
                <?php else : ?>
                    <?php foreach ($responses as $response) : ?>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm rounded-circle bg-<?= $response['user_id'] == session()->get('user_id') ? 'primary' : 'secondary'; ?> text-white" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?= substr($response['name'], 0, 1); ?>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="mb-0 me-2"><?= esc($response['name']); ?></h6>
                                    <small class="text-muted"><?= date('d M Y H:i', strtotime($response['created_at'])); ?></small>
                                    <?php if ($response['user_id'] == session()->get('user_id')) : ?>
                                        <span class="badge bg-primary ms-2">Anda</span>
                                    <?php elseif (session()->get('role') == 'admin') : ?>
                                        <span class="badge bg-danger ms-2">Admin</span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3 bg-light rounded">
                                    <?= nl2br(esc($response['response'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Response Form -->
                <form action="<?= base_url('complaints/respond'); ?>" method="post" class="mt-4">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="complaint_id" value="<?= $complaint['id']; ?>">
                    <?php if (session()->get('role') == 'admin') : ?>
                        <div class="mb-3">
                            <label for="status" class="form-label">Ubah Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">-- Pilih Status --</option>
                                <option value="pending" <?= $complaint['status'] == 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="processing" <?= $complaint['status'] == 'processing' ? 'selected' : ''; ?>>Diproses</option>
                                <option value="resolved" <?= $complaint['status'] == 'resolved' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="rejected" <?= $complaint['status'] == 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="response" class="form-label">Tanggapan Anda</label>
                        <textarea class="form-control" id="response" name="response" rows="3" required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="dashboard-card mb-4">
                <h5 class="mb-3">Informasi Pelapor</h5>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama:</strong></td>
                            <td><?= esc($complaint['user_name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><?= esc($complaint['email'] ?? '-'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="dashboard-card">
                <h5 class="mb-3">Status Pengaduan</h5>
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="bg-<?= $complaint['status'] == 'pending' ? 'warning' : 'light'; ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-clock <?= $complaint['status'] == 'pending' ? 'text-white' : 'text-muted'; ?>"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">Menunggu</h6>
                            <p class="small text-muted mb-0">Pengaduan sedang menunggu ditinjau</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="bg-<?= $complaint['status'] == 'processing' ? 'info' : 'light'; ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-spinner <?= $complaint['status'] == 'processing' ? 'text-white' : 'text-muted'; ?>"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">Diproses</h6>
                            <p class="small text-muted mb-0">Pengaduan sedang diproses</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-<?= ($complaint['status'] == 'completed' || $complaint['status'] == 'resolved') ? 'success' : ($complaint['status'] == 'rejected' ? 'danger' : 'light'); ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas <?= $complaint['status'] == 'rejected' ? 'fa-times' : 'fa-check'; ?> <?= ($complaint['status'] == 'completed' || $complaint['status'] == 'resolved' || $complaint['status'] == 'rejected') ? 'text-white' : 'text-muted'; ?>"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= $complaint['status'] == 'rejected' ? 'Ditolak' : 'Selesai'; ?></h6>
                            <p class="small text-muted mb-0">Pengaduan telah <?= $complaint['status'] == 'rejected' ? 'ditolak' : 'diselesaikan'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?> 