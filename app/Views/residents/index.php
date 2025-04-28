<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
        <a href="<?= base_url('residents/new') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Penduduk
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Penduduk</h6>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('residents/import') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-import"></i> Import
                    </a>
                    <a href="<?= base_url('residents/export-template') ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-download"></i> Template
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="residentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>RT/RW</th>
                            <th>Agama</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#residentsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('residents/get-residents') ?>',
                type: 'POST',
                data: function(d) {
                    d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                }
            },
            columns: [
                { data: 'nik' },
                { data: 'name' },
                { 
                    data: null,
                    render: function(data, type, row) {
                        return row.birth_place + ', ' + moment(row.birth_date).format('DD/MM/YYYY');
                    }
                },
                { 
                    data: 'gender',
                    render: function(data, type, row) {
                        return data == 'male' ? 'Laki-laki' : 'Perempuan';
                    }
                },
                { data: 'address' },
                { 
                    data: null,
                    render: function(data, type, row) {
                        return row.rt + '/' + row.rw;
                    }
                },
                { data: 'religion' },
                { 
                    data: 'marital_status',
                    render: function(data, type, row) {
                        let status = '';
                        switch (data) {
                            case 'single':
                                status = 'Belum Menikah';
                                break;
                            case 'married':
                                status = 'Menikah';
                                break;
                            case 'divorced':
                                status = 'Cerai';
                                break;
                            case 'widowed':
                                status = 'Janda/Duda';
                                break;
                        }
                        return status;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('residents/edit/') ?>${row.id}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('residents/delete/') ?>${row.id}" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        `;
                    },
                    orderable: false
                }
            ],
            order: [[1, 'asc']], // Sort by name by default
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    });
</script>
<?= $this->endSection() ?> 