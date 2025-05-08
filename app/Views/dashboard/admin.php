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

    <!-- Request Type Overview -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <h5 class="card-title mb-3">Ringkasan Jenis Surat</h5>
                <div class="row">
                    <div class="col-md-2 col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-primary">
                                <i class="fas fa-file-medical fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Kematian</h6>
                                <h4 class="mb-0"><?= $deathCertificateCount ?? 0 ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-success">
                                <i class="fas fa-home fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Domisili</h6>
                                <h4 class="mb-0"><?= $domicileRequestCount ?? 0 ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-warning">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Umum</h6>
                                <h4 class="mb-0"><?= $generalRequestCount ?? 0 ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-info">
                                <i class="fas fa-user-friends fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Surat Ahli Waris</h6>
                                <h4 class="mb-0"><?= $heirRequestCount ?? 0 ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-secondary">
                                <i class="fas fa-newspaper fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Berita</h6>
                                <h4 class="mb-0"><?= $newsCount ?? 0 ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-danger">
                                <i class="fas fa-truck-moving fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Pindah Alamat</h6>
                                <h4 class="mb-0"><?= $relocationCount ?? 0 ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <!-- <div class="col-md-6 mb-4">
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
                                        <td><?= esc($request['resident_name']) ?></td>
                                        <td><?= esc($request['letter_type']) ?></td>
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
                        <p>Belum ada pengajuan surat</p>
                    </div>
                <?php endif; ?>
            </div>
        </div> -->
        

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
                                        <td><?= esc($complaint['resident_name']) ?></td>
                                        <td><?= esc($complaint['subject']) ?></td>
                                        <td><?= date('d M Y', strtotime($complaint['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $complaint['status'] == 'pending' ? 'warning' : ($complaint['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($complaint['status']) ?>
                                            </span>
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
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Surat Umum Terbaru</h5>
                <?php if(isset($recentGeneralRequests) && count($recentGeneralRequests) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pemohon</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentGeneralRequests as $request): ?>
                                    <tr>
                                        <td><?= esc($request['resident_name']) ?></td>
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
    </div>

    <!-- Recent Requests by Type -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Surat Kematian Terbaru</h5>
                <?php if(isset($recentDeathCertificates) && count($recentDeathCertificates) > 0): ?>
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
                                <?php foreach($recentDeathCertificates as $cert): ?>
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
                <?php if(isset($recentDomicileRequests) && count($recentDomicileRequests) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pemohon</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentDomicileRequests as $request): ?>
                                    <tr>
                                        <td><?= esc($request['resident_name']) ?></td>
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
                <h5 class="card-title mb-3">Surat Ahli Waris Terbaru</h5>
                <?php if(isset($recentHeirRequests) && count($recentHeirRequests) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pemohon</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentHeirRequests as $request): ?>
                                    <tr>
                                        <td><?= esc($request['resident_name']) ?></td>
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
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Pindah Alamat Terbaru</h5>
                <?php if(isset($recentRelocations) && count($recentRelocations) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Alamat Baru</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentRelocations as $relocation): ?>
                                    <tr>
                                        <td><?= esc($relocation['resident_name']) ?></td>
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

    <div class="row">
        <!-- <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <h5 class="card-title mb-3">Penduduk Baru Terbaru</h5>
                <?php if(isset($recentNewResidents) && count($recentNewResidents) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentNewResidents as $resident): ?>
                                    <tr>
                                        <td><?= esc($resident['name']) ?></td>
                                        <td><?= date('d M Y', strtotime($resident['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $resident['status'] == 'pending' ? 'warning' : ($resident['status'] == 'processing' ? 'info' : 'success') ?>">
                                                <?= ucfirst($resident['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <p>Belum ada penduduk baru</p>
                    </div>
                <?php endif; ?>
            </div>
        </div> -->

        
    </div>
</div>
<?= $this->endSection() ?> 