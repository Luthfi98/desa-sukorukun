<?= $this->extend('layouts/dashboard_layout'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Pengaduan Saya</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Pengaduan</li>
    </ol>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-comments me-1"></i>
                Daftar Pengaduan
            </div>
            <a href="/complaints/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Buat Pengaduan Baru
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($complaints)) : ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-1"></i> Anda belum membuat pengaduan. Klik tombol "Buat Pengaduan Baru" untuk memulai.
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="complaintsTable">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($complaints as $i => $complaint) : ?>
                                <tr>
                                    <td><?= $i + 1; ?></td>
                                    <td><?= date('d/m/Y', strtotime($complaint['created_at'])); ?></td>
                                    <td><?= esc($complaint['subject']); ?></td>
                                    <td>
                                        <?php if ($complaint['status'] == 'pending') : ?>
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        <?php elseif ($complaint['status'] == 'processing') : ?>
                                            <span class="badge bg-info">Diproses</span>
                                        <?php elseif ($complaint['status'] == 'resolved') : ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php elseif ($complaint['status'] == 'rejected') : ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/complaints/view/<?= $complaint['id']; ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#complaintsTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            }
        });
    });
</script>
<?= $this->endSection(); ?> 