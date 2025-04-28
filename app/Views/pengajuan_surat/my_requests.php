<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Pengajuan Surat Saya</h5>
                    <a href="<?= base_url('letter-requests/new') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Pengajuan Baru
                    </a>
                </div>
                
                <?php if(session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
                <?php endif; ?>
                
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table id="myRequestsTable" class="table table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Jenis Surat</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Nomor Dokumen</th>
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
                <h5 class="mb-3">Cara Mengajukan Surat</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-file-alt fa-2x text-primary"></i>
                            </div>
                            <h6>1. Ajukan Surat</h6>
                            <p class="small text-muted">Isi formulir pengajuan surat dengan informasi lengkap</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-clock fa-2x text-primary"></i>
                            </div>
                            <h6>2. Tunggu Persetujuan</h6>
                            <p class="small text-muted">Petugas desa akan memeriksa pengajuan Anda</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                            </div>
                            <h6>3. Terima Notifikasi</h6>
                            <p class="small text-muted">Anda akan diberitahu ketika surat sudah siap</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-download fa-2x text-primary"></i>
                            </div>
                            <h6>4. Unduh atau Ambil</h6>
                            <p class="small text-muted">Unduh surat Anda atau ambil di kantor desa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#myRequestsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('letter-requests/getMyRequestsDataTable') ?>',
            type: 'GET'
        },
        columns: [
            { data: 'letter_type_name' },
            { 
                data: 'created_at',
                render: function(data, type, row) {
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            { 
                data: 'status',
                render: function(data, type, row) {
                    let statusBadge = '';
                    if (data == 'pending') {
                        statusBadge = '<span class="badge bg-warning">Menunggu</span>';
                    } else if (data == 'processing') {
                        statusBadge = '<span class="badge bg-info">Diproses</span>';
                    } else if (data == 'approved') {
                        statusBadge = '<span class="badge bg-primary">Disetujui</span>';
                    } else if (data == 'completed') {
                        statusBadge = '<span class="badge bg-success">Selesai</span>';
                    } else if (data == 'rejected') {
                        statusBadge = '<span class="badge bg-danger">Ditolak</span>';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">Tidak Diketahui</span>';
                    }
                    return statusBadge;
                }
            },
            { data: 'number' },
            {
                data: null,
                render: function(data, type, row) {
                    let actions = '<div class="btn-group" role="group">';
                    actions += `<a href="<?= base_url('letter-requests/view-detail/') ?>${row.id}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>`;
                    
                    if (!row.status || row.status == 'pending') {
                        actions += `<a href="<?= base_url('letter-requests/edit/') ?>${row.id}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>`;
                        actions += `<a href="<?= base_url('letter-requests/delete/') ?>${row.id}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')"><i class="fas fa-trash"></i></a>`;
                    }
                    
                    if (row.status && (row.status == 'completed' || row.status == 'approved')) {
                        actions += `<a href="<?= base_url('letter-requests/download-pdf/') ?>${row.id}" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-download"></i></a>`;
                    }
                    
                    actions += '</div>';
                    return actions;
                },
                orderable: false
            }
        ],
        order: [[1, 'desc']], // Sort by request date by default
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });
});
</script>

<?= $this->endSection() ?> 