<?= $this->extend('layouts/dashboard_layout') ?>


<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
        <a href="<?= base_url('news/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Berita
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="typeFilter">Type</label>
                        <select class="form-control" id="typeFilter">
                            <option value="all">All Types</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= $type ?>"><?= ucfirst($type) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statusFilter">Status</label>
                        <select class="form-control" id="statusFilter">
                            <option value="all">All Status</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status ?>"><?= ucfirst($status) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary" id="filter_button">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="newsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = $('#newsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url("news/getDataTable") ?>',
            type: 'GET',
            data: function(d) {
                d.type = $('#typeFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            { data: 'title' },
            { data: 'type' },
            { data: 'category' },
            { data: 'status' },
            { data: 'author' },
            { data: 'created_at' },
            { 
                data: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        order: [[4, 'desc']],
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

    // Add filter on change for type and status
    $('#typeFilter, #statusFilter').on('change', function() {
        table.ajax.reload();
    });
});
</script>
<?= $this->endSection() ?> 