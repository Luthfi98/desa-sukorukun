<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Tanggapi Pengaduan</h5>
                    <a href="<?= base_url('complaints/admin') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
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
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4 border-0">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><?= esc($complaint['subject']); ?></h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i> Diajukan pada: <?= date('d M Y H:i', strtotime($complaint['created_at'])); ?>
                                    <br>
                                    <i class="far fa-user me-1"></i> Oleh: <?= esc($resident['name']); ?>
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
                            </div>
                        </div>

                        <form action="<?= base_url('complaints/updateResponse/' . $complaint['id']); ?>" method="post">
                            <?= csrf_field(); ?>
                            
                            <div class="mb-3">
                                <label for="response" class="form-label">Tanggapan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="response" name="response" rows="5" required><?= old('response'); ?></textarea>
                                <div class="form-text">Berikan tanggapan yang jelas dan solusi atas pengaduan ini</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="processing" <?= old('status') == 'processing' ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="resolved" <?= old('status') == 'resolved' ? 'selected' : ''; ?>>Selesai</option>
                                    <option value="rejected" <?= old('status') == 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                                </select>
                                <div class="form-text">Pilih status yang sesuai dengan tindakan yang diambil</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
                        </form>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="dashboard-card mb-4">
                            <h5 class="mb-3">Informasi Pelapor</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Nama:</strong></td>
                                    <td><?= esc($resident['name']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>NIK:</strong></td>
                                    <td><?= esc($resident['nik']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?= esc($resident['email']); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Panduan Menanggapi</h6>
                                <hr>
                                <ul class="small mb-0 ps-3">
                                    <li class="mb-2">Baca pengaduan dengan teliti dan pahami permasalahannya</li>
                                    <li class="mb-2">Berikan tanggapan yang sopan, jelas, dan solusi yang konstruktif</li>
                                    <li class="mb-2">Status "Diproses" berarti pengaduan sedang ditindaklanjuti</li>
                                    <li class="mb-2">Status "Selesai" berarti pengaduan sudah diselesaikan dengan baik</li>
                                    <li>Status "Ditolak" untuk pengaduan yang tidak relevan atau tidak dapat ditindaklanjuti</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
