<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Data Pengguna</h1>
        <a href="<?= base_url('users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pengguna</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Username</th>
                                <td><?= esc($user['username']) ?></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td><?= esc($user['name']) ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= esc($user['email']) ?></td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td><?= ucfirst($user['role']) ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if ($user['status'] === 'active'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td><?= date('d M Y H:i', strtotime($user['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <th>Terakhir Diperbarui</th>
                                <td><?= date('d M Y H:i', strtotime($user['updated_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($user['role'] === 'resident' && $resident): ?>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Penduduk</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">NIK</th>
                                <td><?= esc($resident['nik']) ?></td>
                            </tr>
                            <tr>
                                <th>Tempat Lahir</th>
                                <td><?= esc($resident['birth_place']) ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td><?= date('d M Y', strtotime($resident['birth_date'])) ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td><?= $resident['gender'] === 'male' ? 'Laki-laki' : 'Perempuan' ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?= esc($resident['address']) ?></td>
                            </tr>
                            <tr>
                                <th>RT/RW</th>
                                <td><?= esc($resident['rt']) ?>/<?= esc($resident['rw']) ?></td>
                            </tr>
                            <tr>
                                <th>Agama</th>
                                <td><?= esc($resident['religion']) ?></td>
                            </tr>
                            <tr>
                                <th>Status Perkawinan</th>
                                <td><?= ucfirst($resident['marital_status']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($letterRequests)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Riwayat Pengajuan Surat</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="25%">Judul</th>
                                    <th width="40%">Pesan</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($letterRequests as $index => $request): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= date('d M Y H:i', strtotime($request['created_at'])) ?></td>
                                    <td><?= esc($request['title']) ?></td>
                                    <td><?= esc($request['message']) ?></td>
                                    <td>
                                        <?php if ($request['is_read']): ?>
                                            <span class="badge bg-secondary">Dibaca</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Belum Dibaca</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Pengguna
                        </a>
                        <a href="<?= base_url('users/delete/' . $user['id']) ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                            <i class="fas fa-trash"></i> Hapus Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 