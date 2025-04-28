<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Data APBD</h5>
                    <a href="<?= base_url('informasi-apbd/new') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Data
                    </a>
                </div>
                
                <?php if (session()->has('message')): ?>
                <div class="alert alert-success">
                    <?= session('message') ?>
                </div>
                <?php endif; ?>
                
                <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="apbd-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Uraian</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($apbd_data)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-folder-open text-muted mb-3 d-block" style="font-size: 2.5rem;"></i>
                                    <p class="mb-0">Belum ada data APBD yang tersedia.</p>
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>
                                <?php foreach ($apbd_data as $apbd): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $apbd['tahun'] ?></td>
                                        <td>
                                            <?php 
                                            switch($apbd['jenis']) {
                                                case 'pendapatan':
                                                    echo '<span class="badge bg-success">Pendapatan</span>';
                                                    break;
                                                case 'belanja':
                                                    echo '<span class="badge bg-danger">Belanja</span>';
                                                    break;
                                                case 'pembiayaan':
                                                    echo '<span class="badge bg-info">Pembiayaan</span>';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td><?= $apbd['kategori'] ?></td>
                                        <td><?= $apbd['uraian'] ?></td>
                                        <td>Rp <?= number_format($apbd['jumlah'], 0, ',', '.') ?></td>
                                        <td>
                                            <?= $apbd['status'] === 'rencana' ? 
                                                '<span class="badge bg-warning">Rencana</span>' : 
                                                '<span class="badge bg-primary">Realisasi</span>' ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('informasi-apbd/edit/' . $apbd['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete('<?= base_url('informasi-apbd/delete/' . $apbd['id']) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="dashboard-card">
                <h5 class="mb-3">Informasi Pengelolaan APBD</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                            </div>
                            <h6>1. Pendapatan</h6>
                            <p class="small text-muted">Sumber pendapatan desa dari berbagai alokasi</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                            </div>
                            <h6>2. Belanja</h6>
                            <p class="small text-muted">Alokasi pengeluaran untuk pembangunan desa</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-exchange-alt fa-2x text-primary"></i>
                            </div>
                            <h6>3. Pembiayaan</h6>
                            <p class="small text-muted">Transaksi keuangan untuk tutup defisit atau pengelolaan surplus</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-chart-pie fa-2x text-primary"></i>
                            </div>
                            <h6>4. Transparansi</h6>
                            <p class="small text-muted">Keterbukaan informasi untuk akuntabilitas pengelolaan keuangan desa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#apbd-table').DataTable();
});

function confirmDelete(url) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
<?= $this->endSection() ?> 