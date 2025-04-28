<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Admin Dashboard Overview -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-container me-3">
                        <i class="fas fa-chart-line text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Selamat Datang, <?= session()->get('name') ?>!</h5>
                        <p class="text-muted mb-0">Dashboard Admin Sistem Layanan Surat Menyurat Desa</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-success">
                                <i class="fas fa-envelope-open-text fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Pengajuan Surat</h6>
                                <h4 class="mb-0"><?= $pendingCount ?? 0 ?></h4>
                                <small class="text-muted">Menunggu</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-warning">
                                <i class="fas fa-sync-alt fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Diproses</h6>
                                <h4 class="mb-0"><?= $processingCount ?? 0 ?></h4>
                                <small class="text-muted">Sedang diproses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-danger">
                                <i class="fas fa-bullhorn fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Pengaduan</h6>
                                <h4 class="mb-0"><?= $pendingPengaduanCount ?? 0 ?></h4>
                                <small class="text-muted">Belum direspon</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-info">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Penduduk</h6>
                                <h4 class="mb-0"><?= $residentCount ?? 0 ?></h4>
                                <small class="text-muted">Total penduduk</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Functionality Cards -->
    <div class="row">
        <!-- Program Pemerintah Desa -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4>Program Pemerintah Desa</h4>
                    <p>Kelola informasi program pemerintah desa untuk transparansi publik</p>
                    <a href="<?= base_url('program-desa') ?>" class="btn btn-action">
                        <i class="fas fa-plus-circle me-1"></i> Kelola Program
                    </a>
                </div>
            </div>
        </div>

        <!-- Informasi APBD -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h4>Informasi APBD</h4>
                    <p>Kelola informasi Anggaran Pendapatan dan Belanja Desa</p>
                    <a href="<?= base_url('informasi-apbd') ?>" class="btn btn-action">
                        <i class="fas fa-plus-circle me-1"></i> Kelola APBD
                    </a>
                </div>
            </div>
        </div>

        <!-- Pengajuan Surat -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Pengajuan Surat</h4>
                    <p>Respon pengajuan surat pengantar dari masyarakat</p>
                    <a href="<?= base_url('pengajuan-surat') ?>" class="btn btn-action">
                        <?php if(isset($pendingSuratCount) && $pendingSuratCount > 0): ?>
                            <i class="fas fa-bell me-1"></i> <?= $pendingSuratCount ?> Menunggu
                        <?php else: ?>
                            <i class="fas fa-list me-1"></i> Lihat Daftar
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Pengaduan Masyarakat -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h4>Pengaduan Masyarakat</h4>
                    <p>Respon pengaduan keresahan dan saran dari masyarakat</p>
                    <a href="<?= base_url('pengaduan') ?>" class="btn btn-action">
                        <?php if(isset($pendingPengaduanCount) && $pendingPengaduanCount > 0): ?>
                            <i class="fas fa-bell me-1"></i> <?= $pendingPengaduanCount ?> Menunggu
                        <?php else: ?>
                            <i class="fas fa-list me-1"></i> Lihat Daftar
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Arsip Data -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-archive"></i>
                    </div>
                    <h4>Arsip Data</h4>
                    <p>Kelola penyimpanan dan tampilan data arsip desa</p>
                    <a href="<?= base_url('arsip-data') ?>" class="btn btn-action">
                        <i class="fas fa-folder-open me-1"></i> Kelola Arsip
                    </a>
                </div>
            </div>
        </div>

        <!-- Kelola Pengguna -->
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h4>Kelola Pengguna</h4>
                    <p>Kelola akun pengguna sistem dan pengaturan hak akses</p>
                    <a href="<?= base_url('pengguna') ?>" class="btn btn-action">
                        <i class="fas fa-user-edit me-1"></i> Kelola Pengguna
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Pengajuan Terbaru</h5>
                <?php if(isset($recentRequests) && count($recentRequests) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pemohon</th>
                                    <th>Jenis Surat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentRequests as $request): ?>
                                <tr>
                                    <td><?= $request['resident_name'] ?></td>
                                    <td><?= $request['letter_type_name'] ?></td>
                                    <td><?= date('d M Y', strtotime($request['created_at'])) ?></td>
                                    <td>
                                        <?php if($request['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Menunggu</span>
                                        <?php elseif($request['status'] == 'processing'): ?>
                                            <span class="badge bg-info">Diproses</span>
                                        <?php elseif($request['status'] == 'completed'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php elseif($request['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('pengajuan-surat') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan surat terbaru</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Pengaduan Terbaru</h5>
                <?php if(isset($recentComplaints) && count($recentComplaints) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pelapor</th>
                                    <th>Subjek</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentComplaints as $complaint): ?>
                                <tr>
                                    <td><?= $complaint['reporter_name'] ?></td>
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
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('pengaduan') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengaduan masyarakat terbaru</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 