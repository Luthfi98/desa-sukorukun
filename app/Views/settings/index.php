<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <h5 class="card-title mb-0">Settings Management</h5>
                    <a href="<?= base_url('admin/settings/create') ?>" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> Tambah Pengaturan
                            </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3 d-flex justify-content-between">
                        <div class="col-md-6">
                            <form id="categoryFilter">
                                <div class="input-group">
                                    <select name="category" class="form-control form-control" id="categorySelect">
                                        <option value="all">All Categories</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category ?>">
                                                <?= ucwords(str_replace('_', ' ', $category)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" id="filterBtn">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </div>
                    
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="settingsTable">
                            <thead >
                                <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Key</th>
                                    <th>Label</th>
                                    <th>Value</th>
                                    <th>Type</th>
                                    <th>Order</th>
                                    <th>Public</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-group {
    display: flex;
    gap: 0.25rem;
}
.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.btn-group .btn i {
    margin-right: 0;
}
</style>

<script>
$(document).ready(function() {
    var table = $('#settingsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('admin/settings/getDatatable') ?>',
            type: 'GET',
            data: function(d) {
                d.category = $('#categorySelect').val();
            }
        },
        columns: [
            { data: 'id' },
            { data: 'category' },
            { data: 'key' },
            { data: 'label' },
            { data: 'value' },
            { data: 'value_type' },
            { data: 'order' },
            { data: 'is_public' },
            { data: 'status' },
            { 
                data: 'actions', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table"
        }
    });

    $('#filterBtn').on('click', function() {
        table.ajax.reload();
    });
});
</script>

<?= $this->endSection() ?> 