<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="dashboard-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0">Detail Pengaduan</h5>
                <p class="text-muted small mb-0">ID: <?= $complaint['id'] ?></p>
            </div>
            <a href="<?= base_url('complaints') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success">
                <?= session()->get('success') ?>
            </div>
        <?php endif ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($complaint['subject']) ?></h5>
                        <div class="d-flex align-items-center text-muted small mb-3">
                            <div class="me-3">
                                <i class="far fa-calendar me-1"></i> <?= date('d M Y', strtotime($complaint['created_at'])) ?>
                            </div>
                            <div class="me-3">
                                <i class="far fa-clock me-1"></i> <?= date('H:i', strtotime($complaint['created_at'])) ?> WIB
                            </div>
                            <div>
                                <i class="fas fa-tag me-1"></i> Status: 
                                <?php if ($complaint['status'] == 'pending') : ?>
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                <?php elseif ($complaint['status'] == 'processing') : ?>
                                    <span class="badge bg-info">Diproses</span>
                                <?php elseif ($complaint['status'] == 'resolved') : ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php elseif ($complaint['status'] == 'rejected') : ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php endif ?>
                            </div>
                        </div>
                        
                        <div class="card-text mb-3">
                            <?= nl2br(esc($complaint['description'])) ?>
                        </div>
                        
                        <?php if (!empty($complaint['attachment'])) : ?>
                            <div class="mt-3">
                                <h6><i class="fas fa-paperclip me-1"></i> Lampiran:</h6>
                                <?php $ext = pathinfo($complaint['attachment'], PATHINFO_EXTENSION); ?>
                                <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) : ?>
                                    <a href="<?= base_url('uploads/' . $complaint['attachment']) ?>" target="_blank">
                                        <img src="<?= base_url('uploads/' . $complaint['attachment']) ?>" alt="Attachment" class="img-fluid img-thumbnail" style="max-height: 300px">
                                    </a>
                                <?php else : ?>
                                    <a href="<?= base_url('uploads/' . $complaint['attachment']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-download me-1"></i> Unduh Lampiran
                                    </a>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
                
                <?php if (!empty($responses)) : ?>
                    <h6 class="mb-3"><i class="fas fa-comments me-1"></i> Tanggapan Petugas:</h6>
                    <?php foreach ($responses as $response) : ?>
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="fw-bold"><?= esc($response['responder_name']) ?></span>
                                        <span class="text-muted small ms-2"><?= date('d M Y H:i', strtotime($response['created_at'])) ?></span>
                                    </div>
                                    <?php if (session('role') == 'admin' || session('id') == $response['user_id']) : ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="<?= base_url('responses/edit/' . $response['id']) ?>">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteResponseModal<?= $response['id'] ?>">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Delete Response Modal -->
                                        <div class="modal fade" id="deleteResponseModal<?= $response['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus tanggapan ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="<?= base_url('responses/delete/' . $response['id']) ?>" method="post">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                                <div class="card-text">
                                    <?= nl2br(esc($response['response'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
                
                <?php if (session('role') == 'admin' || session('role') == 'staff') : ?>
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-reply me-1"></i> Berikan Tanggapan</h6>
                            <form action="<?= base_url('responses/store') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="complaint_id" value="<?= $complaint['id'] ?>">
                                
                                <div class="mb-3">
                                    <textarea class="form-control" name="response" rows="4" placeholder="Tulis tanggapan Anda disini..." required></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="updateStatus" name="update_status" value="1">
                                        <label class="form-check-label" for="updateStatus">Perbarui status pengaduan</label>
                                    </div>
                                    
                                    <div class="status-options d-none">
                                        <select class="form-control form-control-sm" name="status" style="width: auto;">
                                            <option value="processing" <?= $complaint['status'] == 'processing' ? 'selected' : '' ?>>Diproses</option>
                                            <option value="resolved" <?= $complaint['status'] == 'resolved' ? 'selected' : '' ?>>Selesai</option>
                                            <option value="rejected" <?= $complaint['status'] == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i> Kirim Tanggapan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><i class="fas fa-user me-1"></i> Pelapor</h6>
                        <p class="mb-1"><strong>Nama:</strong> <?= esc($complaint['reporter_name']) ?></p>
                        <p class="mb-1"><strong>NIK:</strong> <?= esc($complaint['nik']) ?></p>
                        <p class="mb-0"><strong>Telepon:</strong> <?= esc($complaint['phone']) ?></p>
                    </div>
                </div>
                
                <?php if (session('role') == 'admin') : ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="fas fa-cog me-1"></i> Pengaturan</h6>
                            
                            <form action="<?= base_url('complaints/update-status/' . $complaint['id']) ?>" method="post" class="mb-3">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Ubah Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="pending" <?= $complaint['status'] == 'pending' ? 'selected' : '' ?>>Menunggu</option>
                                        <option value="processing" <?= $complaint['status'] == 'processing' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="resolved" <?= $complaint['status'] == 'resolved' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="rejected" <?= $complaint['status'] == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </form>
                            
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteComplaintModal">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus Pengaduan
                                </button>
                            </div>
                            
                            <!-- Delete Complaint Modal -->
                            <div class="modal fade" id="deleteComplaintModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus pengaduan dengan judul:</p>
                                            <p class="fw-bold text-danger">"<?= esc($complaint['subject']) ?>"</p>
                                            <p>Semua tanggapan terkait juga akan dihapus. Tindakan ini tidak dapat dibatalkan.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="<?= base_url('complaints/delete/' . $complaint['id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><i class="fas fa-history me-1"></i> Riwayat Status</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title mb-0">Pengaduan Dibuat</h6>
                                    <p class="text-muted small"><?= date('d M Y H:i', strtotime($complaint['created_at'])) ?></p>
                                </div>
                            </div>
                            
                            <?php if (!empty($status_history)) : ?>
                                <?php foreach ($status_history as $history) : ?>
                                    <div class="timeline-item">
                                        <?php if ($history['status'] == 'processing') : ?>
                                            <div class="timeline-marker bg-info"></div>
                                        <?php elseif ($history['status'] == 'resolved') : ?>
                                            <div class="timeline-marker bg-success"></div>
                                        <?php elseif ($history['status'] == 'rejected') : ?>
                                            <div class="timeline-marker bg-danger"></div>
                                        <?php else : ?>
                                            <div class="timeline-marker bg-secondary"></div>
                                        <?php endif ?>
                                        
                                        <div class="timeline-content">
                                            <h6 class="timeline-title mb-0">
                                                <?php if ($history['status'] == 'pending') : ?>
                                                    Status: Menunggu
                                                <?php elseif ($history['status'] == 'processing') : ?>
                                                    Status: Diproses
                                                <?php elseif ($history['status'] == 'resolved') : ?>
                                                    Status: Selesai
                                                <?php elseif ($history['status'] == 'rejected') : ?>
                                                    Status: Ditolak
                                                <?php endif ?>
                                            </h6>
                                            <p class="text-muted small"><?= date('d M Y H:i', strtotime($history['created_at'])) ?></p>
                                            <?php if (!empty($history['notes'])) : ?>
                                                <p class="small"><?= esc($history['notes']) ?></p>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateStatusCheckbox = document.getElementById('updateStatus');
    const statusOptions = document.querySelector('.status-options');
    
    if (updateStatusCheckbox) {
        updateStatusCheckbox.addEventListener('change', function() {
            if (this.checked) {
                statusOptions.classList.remove('d-none');
            } else {
                statusOptions.classList.add('d-none');
            }
        });
    }
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 0;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    height: 100%;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    left: -1.5rem;
    top: 0.25rem;
}

.timeline-content {
    padding-left: 0.5rem;
}
</style>
<?= $this->endSection() ?> 