<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Detail Pengajuan Surat</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Surat</h5>
                    <div>
                        <a href="<?= base_url('letter-requests/my-requests') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <?php if ($request['status'] === 'completed' || $request['status'] === 'approved'): ?>
                            <a href="<?= base_url('letter-requests/download-pdf/' . $request['id']) ?>" class="btn btn-primary btn-sm" target="_blank">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Pengajuan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Jenis Surat</td>
                                    <td>: <?= $letterType['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pengajuan</td>
                                    <td>: <?= date('d F Y H:i', strtotime($request['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>: 
                                        <span class="badge bg-<?php
                                            if ($request['status'] === 'pending') echo 'warning';
                                            elseif ($request['status'] === 'processing') echo 'info';
                                            elseif ($request['status'] === 'approved') echo 'primary';
                                            elseif ($request['status'] === 'completed') echo 'success';
                                            elseif ($request['status'] === 'rejected') echo 'danger';
                                        ?>">
                                            <?php
                                                if ($request['status'] === 'pending') echo 'Menunggu';
                                                elseif ($request['status'] === 'processing') echo 'Diproses';
                                                elseif ($request['status'] === 'approved') echo 'Disetujui';
                                                elseif ($request['status'] === 'completed') echo 'Selesai';
                                                elseif ($request['status'] === 'rejected') echo 'Ditolak';
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php if ($request['status'] === 'rejected' && !empty($request['rejection_reason'])): ?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td class="text-danger">: <?= $request['rejection_reason'] ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($request['number'])): ?>
                                <tr>
                                    <td>Nomor Dokumen</td>
                                    <td>: <?= $request['number'] ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Pemohon</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Nama</td>
                                    <td>: <?= $resident['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>: <?= $resident['nik'] ?></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>: <?= $resident['address'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Detail Pengajuan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="18%">Tujuan Pengajuan</td>
                                    <td>: <?= $request['purpose'] ?></td>
                                </tr>
                                <?php if (!empty($request['description'])): ?>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>: <?= $request['description'] ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($attachments)): ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Dokumen yang Diunggah</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Dokumen</th>
                                            <th>Tipe File</th>
                                            <th>Ukuran</th>
                                            <th>Tanggal Unggah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($attachments as $attachment): ?>
                                        <tr>
                                            <td><?= $attachment['name'] ?></td>
                                            <td><?= $attachment['file_type'] ?></td>
                                            <td><?= number_format($attachment['file_size'] / 1024, 2) ?> KB</td>
                                            <td><?= date('d F Y H:i', strtotime($attachment['created_at'])) ?></td>
                                            <td>
                                                <a href="<?= base_url($attachment['file_path']) ?>" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($processor)): ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Informasi Pemrosesan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="18%">Diproses Oleh</td>
                                    <td>: <?= $processor['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Diproses</td>
                                    <td>: <?= date('d F Y H:i', strtotime($request['processed_at'])) ?></td>
                                </tr>
                                <?php if (!empty($request['completed_at'])): ?>
                                <tr>
                                    <td>Tanggal Selesai</td>
                                    <td>: <?= date('d F Y H:i', strtotime($request['completed_at'])) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($request['status'] === 'pending'): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="<?= base_url('letter-requests/edit/' . $request['id']) ?>" class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> Edit Pengajuan
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Batalkan Pengajuan
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($request['status'] === 'pending'): ?>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin membatalkan pengajuan surat ini? Tindakan ini tidak dapat dikembalikan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('letter-requests/delete/' . $request['id']) ?>" class="btn btn-danger">Ya, Batalkan</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?> 