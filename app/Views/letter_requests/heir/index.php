<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link <?= $status === 'pending' ? 'active' : '' ?>" href="<?= base_url('heir-request?status=pending') ?>">
                                <i class="fas fa-clock me-1"></i> Menunggu
                                <?php $pendingCount = count(array_filter($requests, function($r) { return $r['status'] === 'pending'; })); ?>
                                <?php if ($pendingCount > 0): ?>
                                <span class="badge bg-danger"><?= $pendingCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status === 'processing' ? 'active' : '' ?>" href="<?= base_url('heir-request?status=processing') ?>">
                                <i class="fas fa-spinner me-1"></i> Diproses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status === 'approved' ? 'active' : '' ?>" href="<?= base_url('heir-request?status=approved') ?>">
                                <i class="fas fa-check me-1"></i> Disetujui
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status === 'completed' ? 'active' : '' ?>" href="<?= base_url('heir-request?status=completed') ?>">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status === 'rejected' ? 'active' : '' ?>" href="<?= base_url('heir-request?status=rejected') ?>">
                                <i class="fas fa-times-circle me-1"></i> Ditolak
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <?php if(session()->getFlashdata('message')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('message') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="letter_type_filter">Jenis Surat</label>
                                <select class="form-select" id="letter_type_filter">
                                    <option value="">Semua Jenis Surat</option>
                                    <?php foreach($letterTypes as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_start">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="date_start">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_end">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="date_end">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end justify-content-between">
                            
                            <button class="btn btn-primary" id="filter_button">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="<?= base_url('heir-request/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Buat SK
                            </a>
                        </div>
                        
                    </div>

                    <div class="table-responsive">
                        <table id="letterRequestsTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Pemohon</th>
                                    <th>NIK</th>
                                    <th>Jenis Surat</th>
                                    <th>Nomer Dokumen</th>
                                    <!-- <th>Tujuan</th> -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const status = '<?= $status ?>';
        const table = $('#letterRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('heir-request/getDataTable') ?>',
                type: 'GET',
                data: function(d) {
                    d.status = status;
                    d.letter_type = $('#letter_type_filter').val();
                    d.date_start = $('#date_start').val();
                    d.date_end = $('#date_end').val();
                }
            },
            columns: [
                { data: 0 }, // Tanggal
                { data: 1 }, // Nama Pemohon
                { data: 2 }, // NIK
                { data: 3 }, // Jenis Surat
                { data: 4 }, // Nomer Dokumen
                { 
                    data: 5, // Aksi
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[0, 'desc']], // Sort by date by default
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            responsive: true
        });

        // Add filter functionality
        $('#filter_button').on('click', function() {
            table.ajax.reload();
        });

        // Add filter on enter key for date inputs
        $('#date_start, #date_end').on('keypress', function(e) {
            if (e.which === 13) {
                table.ajax.reload();
            }
        });

        // Add filter on change for letter type
        $('#letter_type_filter').on('change', function() {
            table.ajax.reload();
        });
    });
</script>

<?= $this->endSection() ?> 