<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold">Informasi APBD Desa Tahun <?= $tahun ?></h4>
                </div>
                <div class="card-body py-4">
                    <!-- Tahun Filter -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form action="<?= base_url('informasi-apbd-desa') ?>" method="get">
                                <div class="input-group">
                                    <select name="tahun" class="form-select">
                                        <?php foreach($tahun_list as $t): ?>
                                            <option value="<?= $t['tahun'] ?>" <?= $tahun == $t['tahun'] ? 'selected' : '' ?>><?= $t['tahun'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <?php 
                        $total_pendapatan = 0;
                        foreach($pendapatan as $p) {
                            $total_pendapatan += $p['jumlah'];
                        }
                        
                        $total_belanja = 0;
                        foreach($belanja as $b) {
                            $total_belanja += $b['jumlah'];
                        }
                        
                        $total_pembiayaan = 0;
                        foreach($pembiayaan as $p) {
                            $total_pembiayaan += $p['jumlah'];
                        }
                        
                        $sisa = $total_pendapatan - $total_belanja + $total_pembiayaan;
                        ?>
                        
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 p-3 mb-3">
                                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                    </div>
                                    <h6 class="text-muted">Total Pendapatan</h6>
                                    <h5 class="mb-0 fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 p-3 mb-3">
                                        <i class="fas fa-shopping-cart fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="text-muted">Total Belanja</h6>
                                    <h5 class="mb-0 fw-bold">Rp <?= number_format($total_belanja, 0, ',', '.') ?></h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 p-3 mb-3">
                                        <i class="fas fa-exchange-alt fa-2x text-info"></i>
                                    </div>
                                    <h6 class="text-muted">Total Pembiayaan</h6>
                                    <h5 class="mb-0 fw-bold">Rp <?= number_format($total_pembiayaan, 0, ',', '.') ?></h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 p-3 mb-3">
                                        <i class="fas fa-chart-pie fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="text-muted">Sisa Anggaran</h6>
                                    <h5 class="mb-0 fw-bold">Rp <?= number_format($sisa, 0, ',', '.') ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-fill mb-4" id="apbdTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pendapatan-tab" data-bs-toggle="tab" data-bs-target="#pendapatan" type="button" role="tab">
                                <i class="fas fa-money-bill-wave me-2"></i>Pendapatan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="belanja-tab" data-bs-toggle="tab" data-bs-target="#belanja" type="button" role="tab">
                                <i class="fas fa-shopping-cart me-2"></i>Belanja
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pembiayaan-tab" data-bs-toggle="tab" data-bs-target="#pembiayaan" type="button" role="tab">
                                <i class="fas fa-exchange-alt me-2"></i>Pembiayaan
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Pendapatan Tab -->
                        <div class="tab-pane fade show active" id="pendapatan" role="tabpanel">
                            <?php if (empty($pendapatan)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Belum ada data pendapatan untuk tahun <?= $tahun ?>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Kategori</th>
                                                <th width="40%">Uraian</th>
                                                <th width="15%">Status</th>
                                                <th width="20%" class="text-end">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($pendapatan as $p): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $p['kategori'] ?></td>
                                                    <td><?= $p['uraian'] ?></td>
                                                    <td>
                                                        <?= $p['status'] === 'rencana' ? 
                                                            '<span class="badge bg-warning">Rencana</span>' : 
                                                            '<span class="badge bg-primary">Realisasi</span>' ?>
                                                    </td>
                                                    <td class="text-end fw-bold">Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-success">
                                                <td colspan="4" class="text-end fw-bold">TOTAL PENDAPATAN</td>
                                                <td class="text-end fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Belanja Tab -->
                        <div class="tab-pane fade" id="belanja" role="tabpanel">
                            <?php if (empty($belanja)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Belum ada data belanja untuk tahun <?= $tahun ?>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Kategori</th>
                                                <th width="40%">Uraian</th>
                                                <th width="15%">Status</th>
                                                <th width="20%" class="text-end">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($belanja as $b): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $b['kategori'] ?></td>
                                                    <td><?= $b['uraian'] ?></td>
                                                    <td>
                                                        <?= $b['status'] === 'rencana' ? 
                                                            '<span class="badge bg-warning">Rencana</span>' : 
                                                            '<span class="badge bg-primary">Realisasi</span>' ?>
                                                    </td>
                                                    <td class="text-end fw-bold">Rp <?= number_format($b['jumlah'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-danger">
                                                <td colspan="4" class="text-end fw-bold">TOTAL BELANJA</td>
                                                <td class="text-end fw-bold">Rp <?= number_format($total_belanja, 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pembiayaan Tab -->
                        <div class="tab-pane fade" id="pembiayaan" role="tabpanel">
                            <?php if (empty($pembiayaan)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Belum ada data pembiayaan untuk tahun <?= $tahun ?>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Kategori</th>
                                                <th width="40%">Uraian</th>
                                                <th width="15%">Status</th>
                                                <th width="20%" class="text-end">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($pembiayaan as $p): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $p['kategori'] ?></td>
                                                    <td><?= $p['uraian'] ?></td>
                                                    <td>
                                                        <?= $p['status'] === 'rencana' ? 
                                                            '<span class="badge bg-warning">Rencana</span>' : 
                                                            '<span class="badge bg-primary">Realisasi</span>' ?>
                                                    </td>
                                                    <td class="text-end fw-bold">Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-info">
                                                <td colspan="4" class="text-end fw-bold">TOTAL PEMBIAYAAN</td>
                                                <td class="text-end fw-bold">Rp <?= number_format($total_pembiayaan, 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="text-muted mb-0">
                            <small><i class="fas fa-info-circle me-1"></i>Informasi APBD ini diperbarui secara berkala</small>
                        </p>
                        <small class="text-muted">Terakhir diperbarui: <?= date('d F Y') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 