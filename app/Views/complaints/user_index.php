<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Daftar Pengaduan</h5>
                    <a href="<?= base_url('complaints/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Buat Pengaduan Baru
                    </a>
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

                <div class="row mb-3">
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
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary" id="filter_button">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table id="complaintsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
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
                <h5 class="mb-3">Cara Membuat Pengaduan</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-edit fa-2x text-primary"></i>
                            </div>
                            <h6>1. Buat Pengaduan</h6>
                            <p class="small text-muted">Isi form pengaduan dengan lengkap dan jelas</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-clock fa-2x text-primary"></i>
                            </div>
                            <h6>2. Menunggu Tanggapan</h6>
                            <p class="small text-muted">Petugas desa akan meninjau pengaduan Anda</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-comments fa-2x text-primary"></i>
                            </div>
                            <h6>3. Menerima Tanggapan</h6>
                            <p class="small text-muted">Anda akan mendapatkan tanggapan atas pengaduan</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                            </div>
                            <h6>4. Penyelesaian</h6>
                            <p class="small text-muted">Pengaduan Anda akan ditindaklanjuti hingga selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = $('#complaintsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('complaints/getDataTable') ?>',
                type: 'GET',
                data: function(d) {
                    d.date_start = $('#date_start').val();
                    d.date_end = $('#date_end').val();
                }
            },
            columns: [
                { 
                    data: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
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
                    render: function(data) {
                        return `
                            <a href="<?= base_url('complaints/') ?>${data}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        `;
                    }
                }
            ],
            order: [[2, 'desc']], // Sort by date by default
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
    });
</script>
<?= $this->endSection() ?> 