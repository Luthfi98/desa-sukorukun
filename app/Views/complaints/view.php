<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Detail Pengaduan</h5>
                    <div>
                        <a href="<?= base_url('complaints') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <?php if ($complaint['status'] == 'pending'): ?>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        <?php endif; ?>
                        <?php if ($complaint['status'] == 'processing' && session()->get('role') === 'admin'): ?>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#resolveModal">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mb-4 border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><?= esc($complaint['subject']); ?></h6>
                        <?php if ($complaint['status'] == 'pending') : ?>
                            <span class="badge bg-warning">Menunggu</span>
                        <?php elseif ($complaint['status'] == 'processing') : ?>
                            <span class="badge bg-info">Diproses</span>
                        <?php elseif ($complaint['status'] == 'resolved') : ?>
                            <span class="badge bg-success">Selesai</span>
                        <?php elseif ($complaint['status'] == 'rejected') : ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            <i class="far fa-calendar-alt me-1"></i> Diajukan pada: <?= date('d M Y H:i', strtotime($complaint['created_at'])); ?>
                        </p>
                        
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">Deskripsi Pengaduan</h6>
                            <div class="p-3 bg-light rounded">
                                <?= nl2br(esc($complaint['description'])); ?>
                            </div>
                        </div>

                        <?php if (!empty($complaint['attachment'])) : ?>
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2">Lampiran</h6>
                                <div class="p-3 bg-light rounded">
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

                        <?php if (!empty($complaint['response']) || !empty($responses)): ?>
                            <div class="mt-4 mb-4">
                                <h6 class="border-bottom pb-2">Riwayat Tanggapan</h6>
                                
                                <?php if (!empty($complaint['response'])): ?>
                                <div class="p-3 bg-light rounded mb-3">
                                    <p class="text-muted small mb-2">
                                        <i class="far fa-calendar-alt me-1"></i> Tanggapan awal: 
                                        <?= !empty($responses) ? date('d M Y H:i', strtotime($complaint['created_at'])) : date('d M Y H:i', strtotime($complaint['updated_at'])); ?>
                                    </p>
                                    <?= nl2br(esc($complaint['response'])); ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($responses)): ?>
                                    <?php foreach($responses as $index => $response): ?>
                                        <?php if ($index > 0 || empty($complaint['response'])): ?>
                                        <div class="p-3 bg-light rounded mb-3">
                                            <p class="text-muted small mb-2">
                                                <i class="far fa-calendar-alt me-1"></i> <?= date('d M Y H:i', strtotime($response['created_at'])); ?>
                                                oleh <strong><?= esc($response['user_name']); ?></strong>
                                            </p>
                                            <?= nl2br(esc($response['response'])); ?>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="dashboard-card mb-4">
                <h5 class="mb-3">Informasi Pelapor</h5>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama:</strong></td>
                            <td><?= esc($resident['name'] ?? $complaint['full_name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>NIK:</strong></td>
                            <td><?= esc($resident['nik'] ?? $complaint['nik']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><?= esc($resident['email'] ?? $complaint['email']); ?></td>
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
                            <div class="bg-<?= $complaint['status'] == 'resolved' ? 'success' : ($complaint['status'] == 'rejected' ? 'danger' : 'light'); ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas <?= $complaint['status'] == 'rejected' ? 'fa-times' : 'fa-check'; ?> <?= ($complaint['status'] == 'resolved' || $complaint['status'] == 'rejected') ? 'text-white' : 'text-muted'; ?>"></i>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengaduan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('complaints/delete/' . $complaint['id']); ?>" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Complaint Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1" aria-labelledby="resolveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resolveModalLabel">Selesaikan Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('complaints/resolve/' . $complaint['id']); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="resolution_notes" class="form-label">Catatan Penyelesaian</label>
                        <textarea class="form-control" id="resolution_notes" name="response" rows="4" placeholder="Masukkan catatan tentang bagaimana pengaduan ini diselesaikan"></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i> Catatan ini akan ditambahkan sebagai tanggapan baru dan pengaduan akan ditandai sebagai "Selesai".
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Selesaikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 