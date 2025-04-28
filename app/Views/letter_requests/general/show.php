<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Surat</h5>
                    <div>
                        <a href="<?= base_url($url.'?status='.$request['status']) ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <?php if(session()->get('role') === 'resident' && $request['status'] === 'pending'): ?>
                            <a href="<?= base_url($url.'/edit/'.$request['id']) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a href="<?= base_url($url.'/delete/'.$request['id']) ?>" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')"
                            class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        <?php endif; ?>
                        
                        <?php if($request['status'] === 'pending' && session()->get('role') !== 'resident'): ?>
                            <a href="<?= base_url($url.'/process/'.$request['id']) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-cog"></i> Proses
                            </a>
                        <?php elseif($request['status'] === 'processing' && session()->get('role') !== 'resident'): ?>
                            <a href="<?= base_url($url.'/process/'.$request['id']) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-cog"></i> Update Status
                            </a>
                        <?php endif; ?>
                        
                        <?php if(($request['status'] === 'approved' || $request['status'] === 'completed')): ?>
                            <a href="<?= base_url($url.'/download/'.$request['id']) ?>" class="btn btn-primary btn-sm" target="_blank">
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
                            <?php if (!empty($request['number'])): ?>
                                <tr>
                                    <td>Nomor Dokumen</td>
                                    <td>: <?= $request['number'] ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td width="35%">Jenis Surat</td>
                                    <td>: <?= $letterType['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Kode Surat</td>
                                    <td>: <?= $letterType['code'] ?? '-' ?></td>
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
                                
                                <?php if (!empty($request['processed_at'])): ?>
                                <tr>
                                    <td>Tanggal Diproses</td>
                                    <td>: <?= date('d F Y H:i', strtotime($request['processed_at'])) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($request['completed_at'])): ?>
                                <tr>
                                    <td>Tanggal Selesai</td>
                                    <td>: <?= date('d F Y H:i', strtotime($request['completed_at'])) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Pemohon</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Nama</td>
                                    <td>: <?= $request['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>: <?= $request['nik'] ?></td>
                                </tr>
                                <tr>
                                    <td>Tempat, Tgl Lahir</td>
                                    <td>: <?= $request['pob'] ?>, <?= formatDateIndo($request['dob']) ?></td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>: <?= $request['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' ?></td>
                                </tr>
                                <tr>
                                    <td>Agama</td>
                                    <td>: <?= $request['religion'] ?></td>
                                </tr>
                                <tr>
                                    <td>Pekerjaan</td>
                                    <td>: <?= $request['occupation'] ?></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>: Desa Sukorukun RT. <?= $request['rt']?> RW <?= $request['rw'] ?> Kecamatan Jaken Kabupaten Pati</td>
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
                    <div class="row mb-4">
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

                    <div class="row mt-4">
                        <div class="col-12">
                            <?php if (($request['status'] === 'pending' || $request['status'] === 'processing') && session()->get('role') !== 'resident'): ?>
                            <form action="<?= base_url($url.'/update-status/' . $request['id']) ?>" method="post" class="d-inline-block">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-check-circle"></i> Setujui
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle"></i> Tolak
                            </button>
                            <?php elseif ($request['status'] === 'approved' && session()->get('role') !== 'resident'): ?>
                            <form action="<?= base_url($url.'/update-status/' . $request['id']) ?>" method="post" class="d-inline-block">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-check-double"></i> Tandai Selesai
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url($url.'/update-status/' . $request['id']) ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        <div class="form-text">Berikan alasan mengapa pengajuan surat ini ditolak.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 