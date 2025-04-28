<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Proses Pengaduan</h5>
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

                        <form action="<?= base_url('complaints/processComplaint/' . $complaint['id']); ?>" method="post">
                            <?= csrf_field(); ?>
                            
                            <div class="mb-3">
                                <label for="response" class="form-label">Catatan Proses (Opsional)</label>
                                <textarea class="form-control" id="response" name="response" rows="3"><?= old('response'); ?></textarea>
                                <div class="form-text">Catatan internal terkait proses penanganan pengaduan ini</div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Dengan mengklik tombol "Proses Pengaduan", status pengaduan akan berubah menjadi <strong>Diproses</strong> dan pengirim akan mendapatkan notifikasi.
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Proses Pengaduan</button>
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
                                <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Alur Penanganan Pengaduan</h6>
                                <hr>
                                <ul class="small mb-0 ps-3">
                                    <li class="mb-2">Tahap 1: <strong>Menunggu</strong> - Pengaduan baru yang belum ditanggapi</li>
                                    <li class="mb-2">Tahap 2: <strong>Diproses</strong> - Pengaduan sedang dalam penanganan</li>
                                    <li class="mb-2">Tahap 3: <strong>Selesai/Ditolak</strong> - Pengaduan sudah selesai ditangani</li>
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
