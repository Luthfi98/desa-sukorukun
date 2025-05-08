<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Manajemen Pengaduan</h5>
                    <div>
                        <div class="btn-group" role="group">
                            <a href="<?= base_url('complaints/admin?status=pending') ?>" class="btn btn-<?= (!isset($_GET['status']) || $_GET['status'] == 'pending') ? 'warning' : 'outline-warning' ?> btn-sm">
                                Menunggu
                            </a>
                            <a href="<?= base_url('complaints/admin?status=processing') ?>" class="btn btn-<?= (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'info' : 'outline-info' ?> btn-sm">
                                Diproses
                            </a>
                            <a href="<?= base_url('complaints/admin?status=resolved') ?>" class="btn btn-<?= (isset($_GET['status']) && $_GET['status'] == 'resolved') ? 'success' : 'outline-success' ?> btn-sm">
                                Selesai
                            </a>
                            <a href="<?= base_url('complaints/admin?status=rejected') ?>" class="btn btn-<?= (isset($_GET['status']) && $_GET['status'] == 'rejected') ? 'danger' : 'outline-danger' ?> btn-sm">
                                Ditolak
                            </a>
                            <a href="<?= base_url('complaints/admin?status=all') ?>" class="btn btn-<?= (isset($_GET['status']) && $_GET['status'] == 'all') ? 'primary' : 'outline-primary' ?> btn-sm">
                                Semua
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
                <?php endif; ?>
                
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table id="complaintsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengirim</th>
                                <th>Subjek</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="dashboard-card">
                <h5 class="mb-3">Ringkasan Pengaduan</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <div class="display-4 text-warning mb-2">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h5 class="card-title mb-0">Menunggu</h5>
                                <p class="h2 mt-2 mb-0"><?= $countPending ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-info h-100">
                            <div class="card-body text-center">
                                <div class="display-4 text-info mb-2">
                                    <i class="fas fa-spinner"></i>
                                </div>
                                <h5 class="card-title mb-0">Diproses</h5>
                                <p class="h2 mt-2 mb-0"><?= $countProcessing ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <div class="display-4 text-success mb-2">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h5 class="card-title mb-0">Selesai</h5>
                                <p class="h2 mt-2 mb-0"><?= $countResolved ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-danger h-100">
                            <div class="card-body text-center">
                                <div class="display-4 text-danger mb-2">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <h5 class="card-title mb-0">Ditolak</h5>
                                <p class="h2 mt-2 mb-0"><?= $countRejected ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const status = '<?= $status ?? 'all' ?>';
        const table = $('#complaintsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('complaints/getDataTable') ?>',
                type: 'GET',
                data: function(d) {
                    d.status = status;
                }
            },
            columns: [
                { 
                    data: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 1 }, // Nama Pengirim
                { data: 2 }, // Subjek
                { 
                    data: 3,
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    }
                },
                { 
                    data: 4,
                    render: function(data) {
                        let badgeClass = 'secondary';
                        let statusText = 'Unknown';
                        
                        switch (data) {
                            case 'pending':
                                badgeClass = 'warning';
                                statusText = 'Menunggu';
                                break;
                            case 'processing':
                                badgeClass = 'info';
                                statusText = 'Diproses';
                                break;
                            case 'resolved':
                                badgeClass = 'success';
                                statusText = 'Selesai';
                                break;
                            case 'rejected':
                                badgeClass = 'danger';
                                statusText = 'Ditolak';
                                break;
                        }
                        return `<span class="badge bg-${badgeClass}">${statusText}</span>`;
                    }
                },
                { 
                    data: 5,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let buttons = `
                            <a href="<?= base_url('complaints/') ?>${data}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        `;
                        
                        if (row[4] !== 'resolved' && row[4] !== 'rejected') {
                            buttons += `
                                <a href="<?= base_url('complaints/respond/') ?>${data}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-reply"></i>
                                </a>
                            `;
                        }
                        
                        return buttons;
                    }
                }
            ],
            order: [[3, 'desc']], // Sort by date by default
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            responsive: true
        });
    });
</script>
<?= $this->endSection() ?> 