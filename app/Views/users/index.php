<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
        <a href="<?= base_url('users/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengguna Baru
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
       
            <div class="d-flex gap-2">
                <select id="roleFilter" class="form-control form-control-sm">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="resident">Resident</option>
                </select>
                <select id="statusFilter" class="form-control form-control-sm">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
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
        const table = $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('users/getDataTable') ?>',
                type: 'GET',
                data: function(d) {
                    d.role = $('#roleFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                { data: 'no' },
                { data: 'username' },
                { data: 'name' },
                { data: 'email' },
                { data: 'role' },
                { data: 'status' },
                { 
                    data: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[1, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            responsive: true
        });

        // Add event listeners for filters
        $('#roleFilter, #statusFilter').change(function() {
            table.ajax.reload();
        });
    });
</script>
<?= $this->endSection() ?> 