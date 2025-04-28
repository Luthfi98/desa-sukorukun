<?= $this->extend('layouts/dashboard_layout'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="my-3"><?= $title; ?></h1>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link <?= empty($status) ? 'active' : ''; ?>" href="<?= base_url('complaints/admin'); ?>">Semua</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status == 'pending' ? 'active' : ''; ?>" href="<?= base_url('complaints/admin?status=pending'); ?>">Menunggu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status == 'processing' ? 'active' : ''; ?>" href="<?= base_url('complaints/admin?status=processing'); ?>">Diproses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status == 'completed' ? 'active' : ''; ?>" href="<?= base_url('complaints/admin?status=completed'); ?>">Selesai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $status == 'rejected' ? 'active' : ''; ?>" href="<?= base_url('complaints/admin?status=rejected'); ?>">Ditolak</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <?php if (empty($complaints)) : ?>
                        <div class="alert alert-info">
                            Tidak ada pengaduan ditemukan.
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="complaintsTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Pengirim</th>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($complaints as $complaint) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= date('d/m/Y', strtotime($complaint['created_at'])); ?></td>
                                            <td><?= esc($complaint['user_name']); ?></td>
                                            <td><?= esc($complaint['subject']); ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm dropdown-toggle status-btn
                                                        <?php
                                                        switch ($complaint['status']) {
                                                            case 'pending':
                                                                echo 'btn-warning';
                                                                break;
                                                            case 'processing':
                                                                echo 'btn-info';
                                                                break;
                                                            case 'completed':
                                                                echo 'btn-success';
                                                                break;
                                                            case 'rejected':
                                                                echo 'btn-danger';
                                                                break;
                                                            default:
                                                                echo 'btn-secondary';
                                                        }
                                                        ?>" 
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <?php
                                                        switch ($complaint['status']) {
                                                            case 'pending':
                                                                echo 'Menunggu';
                                                                break;
                                                            case 'processing':
                                                                echo 'Diproses';
                                                                break;
                                                            case 'completed':
                                                                echo 'Selesai';
                                                                break;
                                                            case 'rejected':
                                                                echo 'Ditolak';
                                                                break;
                                                            default:
                                                                echo 'Unknown';
                                                        }
                                                        ?>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="<?= base_url('complaints/updateStatus'); ?>" method="post">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?= $complaint['id']; ?>">
                                                                <input type="hidden" name="status" value="pending">
                                                                <button type="submit" class="dropdown-item <?= $complaint['status'] == 'pending' ? 'active' : ''; ?>">Menunggu</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?= base_url('complaints/updateStatus'); ?>" method="post">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?= $complaint['id']; ?>">
                                                                <input type="hidden" name="status" value="processing">
                                                                <button type="submit" class="dropdown-item <?= $complaint['status'] == 'processing' ? 'active' : ''; ?>">Diproses</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?= base_url('complaints/updateStatus'); ?>" method="post">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?= $complaint['id']; ?>">
                                                                <input type="hidden" name="status" value="completed">
                                                                <button type="submit" class="dropdown-item <?= $complaint['status'] == 'completed' ? 'active' : ''; ?>">Selesai</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?= base_url('complaints/updateStatus'); ?>" method="post">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?= $complaint['id']; ?>">
                                                                <input type="hidden" name="status" value="rejected">
                                                                <button type="submit" class="dropdown-item <?= $complaint['status'] == 'rejected' ? 'active' : ''; ?>">Ditolak</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('complaints/' . $complaint['id']); ?>" class="btn btn-sm btn-info text-white">Detail</a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?= $complaint['id']; ?>">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $complaint['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus pengaduan ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="<?= base_url('complaints/delete/' . $complaint['id']); ?>" class="btn btn-danger">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#complaintsTable').DataTable({
        "order": [[ 0, "asc" ]],
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>
<?= $this->endSection(); ?> 