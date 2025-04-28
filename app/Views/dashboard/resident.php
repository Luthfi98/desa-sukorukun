<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Resident Dashboard Overview -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-container me-3">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Selamat Datang, <?= session()->get('name') ?>!</h5>
                        <p class="text-muted mb-0">Dashboard Masyarakat Sistem Layanan Surat Menyurat Desa</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-success">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Pengajuan</h6>
                                <h4 class="mb-0"><?= $mySuratCount ?? 0 ?></h4>
                                <small class="text-muted">Total pengajuan surat Anda</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-warning">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Pengaduan</h6>
                                <h4 class="mb-0"><?= $myComplaintCount ?? 0 ?></h4>
                                <small class="text-muted">Total pengaduan Anda</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-danger">
                                <i class="fas fa-bell fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Notifikasi</h6>
                                <h4 class="mb-0"><?= $unreadCount ?? 0 ?></h4>
                                <small class="text-muted">Notifikasi belum dibaca</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resident Functionality Cards -->
    <div class="row">
        <!-- Pengajuan Surat Pengantar -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4>Pengajuan Surat Pengantar</h4>
                    <p>Ajukan surat pengantar untuk kebutuhan administrasi Anda</p>
                    <a href="<?= base_url('surat-pengantar') ?>" class="btn btn-action">
                        <i class="fas fa-file-signature me-1"></i> Ajukan Surat
                    </a>
                </div>
            </div>
        </div>

        <!-- Pengaduan Keresahan -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h4>Pengaduan Keresahan</h4>
                    <p>Sampaikan keluhan dan saran untuk perbaikan layanan desa</p>
                    <a href="<?= base_url('pengaduan-masyarakat') ?>" class="btn btn-action">
                        <i class="fas fa-comment-alt me-1"></i> Kirim Pengaduan
                    </a>
                </div>
            </div>
        </div>

        <!-- Informasi APBD -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h4>Informasi APBD</h4>
                    <p>Lihat informasi transparansi anggaran desa</p>
                    <a href="<?= base_url('informasi-apbd-publik') ?>" class="btn btn-action">
                        <i class="fas fa-eye me-1"></i> Lihat Informasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Informasi Pemerintah Desa -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h4>Informasi Pemerintah Desa</h4>
                    <p>Akses informasi tentang program dan kebijakan pemerintah desa</p>
                    <a href="<?= base_url('informasi-desa') ?>" class="btn btn-action">
                        <i class="fas fa-eye me-1"></i> Lihat Informasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Arsip Data -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4>Arsip Data</h4>
                    <p>Simpan dan akses dokumen-dokumen penting Anda</p>
                    <a href="<?= base_url('arsip-dokumen') ?>" class="btn btn-action">
                        <i class="fas fa-cloud-upload-alt me-1"></i> Kelola Arsip
                    </a>
                </div>
            </div>
        </div>

        <!-- Tracking Pengajuan -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>Tracking Pengajuan</h4>
                    <p>Lacak status pengajuan surat yang telah Anda kirim</p>
                    <a href="<?= base_url('tracking-pengajuan') ?>" class="btn btn-action">
                        <i class="fas fa-search-location me-1"></i> Lacak Status
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Pengajuan Surat Terbaru</h5>
                <?php if(isset($mySuratRecent) && count($mySuratRecent) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($mySuratRecent as $surat): ?>
                                <tr>
                                    <td><?= $surat['letter_type_name'] ?></td>
                                    <td><?= date('d M Y', strtotime($surat['created_at'])) ?></td>
                                    <td>
                                        <?php if($surat['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Menunggu</span>
                                        <?php elseif($surat['status'] == 'processing'): ?>
                                            <span class="badge bg-info">Diproses</span>
                                        <?php elseif($surat['status'] == 'completed'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php elseif($surat['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('surat-pengantar/detail/' . $surat['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('surat-pengantar/riwayat') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan surat yang Anda kirim</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Pengaduan Terbaru</h5>
                <?php if(isset($myComplaintRecent) && count($myComplaintRecent) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subjek</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($myComplaintRecent as $complaint): ?>
                                <tr>
                                    <td><?= $complaint['subject'] ?></td>
                                    <td><?= date('d M Y', strtotime($complaint['created_at'])) ?></td>
                                    <td>
                                        <?php if($complaint['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Menunggu</span>
                                        <?php elseif($complaint['status'] == 'processing'): ?>
                                            <span class="badge bg-info">Diproses</span>
                                        <?php elseif($complaint['status'] == 'resolved'): ?>
                                            <span class="badge bg-success">Teratasi</span>
                                        <?php elseif($complaint['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('pengaduan-masyarakat/detail/' . $complaint['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('pengaduan-masyarakat/riwayat') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-comment-alt fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengaduan yang Anda kirim</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 