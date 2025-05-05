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
                    <div class="col-md-3 col-sm-6 mb-3">
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
                    <div class="col-md-3 col-sm-6 mb-3">
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
                    <div class="col-md-3 col-sm-6 mb-3">
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
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-info">
                                <i class="fas fa-file-medical fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Kematian</h6>
                                <h4 class="mb-0"><?= $myDeathCertificateCount ?? 0 ?></h4>
                                <small class="text-muted">Total pengajuan Anda</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-primary">
                                <i class="fas fa-home fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Domisili</h6>
                                <h4 class="mb-0"><?= $myDomicileRequestCount ?? 0 ?></h4>
                                <small class="text-muted">Total pengajuan Anda</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-success">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Umum</h6>
                                <h4 class="mb-0"><?= $myGeneralRequestCount ?? 0 ?></h4>
                                <small class="text-muted">Total pengajuan Anda</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-warning">
                                <i class="fas fa-user-friends fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Ahli Waris</h6>
                                <h4 class="mb-0"><?= $myHeirRequestCount ?? 0 ?></h4>
                                <small class="text-muted">Total pengajuan Anda</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-danger">
                                <i class="fas fa-truck-moving fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Pindah Alamat</h6>
                                <h4 class="mb-0"><?= $myRelocationCount ?? 0 ?></h4>
                                <small class="text-muted">Total pengajuan Anda</small>
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
                    <a href="<?= base_url('general-request/my-request') ?>" class="btn btn-action">
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
                    <a href="<?= base_url('complaints') ?>" class="btn btn-action">
                        <i class="fas fa-comment-alt me-1"></i> Kirim Pengaduan
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
                    <h4>Notifikasi</h4>
                    <p>Akses terkait notifikasi</p>
                    <a href="<?= base_url('notifications') ?>" class="btn btn-action">
                        <i class="fas fa-eye me-1"></i> Lihat Notifikasi
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
                                        <td><?= esc($surat['letter_type']) ?></td>
                                        <td><?= date('d M Y', strtotime($surat['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $surat['status'] == 'pending' ? 'warning' : ($surat['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($surat['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('surat-pengantar/view/' . $surat['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan surat</p>
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
                                        <td><?= esc($complaint['subject']) ?></td>
                                        <td><?= date('d M Y', strtotime($complaint['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $complaint['status'] == 'pending' ? 'warning' : ($complaint['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($complaint['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('complaints/view/' . $complaint['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-comment-alt fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengaduan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Requests by Type -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Surat Kematian Terbaru</h5>
                <?php if(isset($myDeathCertificateRecent) && count($myDeathCertificateRecent) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Almarhum</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($myDeathCertificateRecent as $cert): ?>
                                    <tr>
                                        <td><?= esc($cert['name']) ?></td>
                                        <td><?= date('d M Y', strtotime($cert['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $cert['status'] == 'pending' ? 'warning' : ($cert['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($cert['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-medical fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan surat kematian</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Surat Domisili Terbaru</h5>
                <?php if(isset($myDomicileRequestRecent) && count($myDomicileRequestRecent) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Alamat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($myDomicileRequestRecent as $request): ?>
                                    <tr>
                                        <td><?= esc($request['address']) ?></td>
                                        <td><?= date('d M Y', strtotime($request['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $request['status'] == 'pending' ? 'warning' : ($request['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($request['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-home fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan surat domisili</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Surat Umum Terbaru</h5>
                <?php if(isset($myGeneralRequestRecent) && count($myGeneralRequestRecent) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($myGeneralRequestRecent as $request): ?>
                                    <tr>
                                        <td><?= esc($request['request_type']) ?></td>
                                        <td><?= date('d M Y', strtotime($request['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $request['status'] == 'pending' ? 'warning' : ($request['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($request['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan surat umum</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Surat Ahli Waris Terbaru</h5>
                <?php if(isset($myHeirRequestRecent) && count($myHeirRequestRecent) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Almarhum</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($myHeirRequestRecent as $request): ?>
                                    <tr>
                                        <td><?= esc($request['name']) ?></td>
                                        <td><?= date('d M Y', strtotime($request['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $request['status'] == 'pending' ? 'warning' : ($request['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($request['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan surat ahli waris</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Pindah Alamat Terbaru</h5>
                <?php if(isset($myRelocationRecent) && count($myRelocationRecent) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Alamat Baru</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($myRelocationRecent as $relocation): ?>
                                    <tr>
                                        <td><?= esc($relocation['destination_detail']) ?></td>
                                        <td><?= date('d M Y', strtotime($relocation['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $relocation['status'] == 'pending' ? 'warning' : ($relocation['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($relocation['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-truck-moving fa-3x text-muted mb-3"></i>
                        <p>Belum ada pengajuan pindah alamat</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 