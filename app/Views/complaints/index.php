<?= $this->extend('layouts/dashboard_layout'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="dashboard-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Pengaduan Saya</h5>
            <a href="<?= base_url('complaints/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Buat Pengaduan Baru
            </a>
        </div>

        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->get('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <div class="table-responsive">
            <table id="complaintsTable" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
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
                type: 'GET'
            },
            columns: [
                { 
                    data: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { 
                    data: 1,
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    }
                },
                { data: 2 },
                { 
                    data: 3,
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
                            case 'completed':
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
                    data: 4,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <a href="<?= base_url('complaints/') ?>${data}" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal${data}">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        `;
                    }
                }
            ],
            order: [[1, 'desc']], // Sort by date by default
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            responsive: true
        });
    });
</script>
<?= $this->endSection(); ?> 