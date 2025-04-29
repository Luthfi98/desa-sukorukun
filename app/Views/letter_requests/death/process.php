<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Surat</h5>
                    <div>
                        <a href="<?= base_url('death-cetificate-request/view/'.$request['id']) ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Surat</h6>
                            <table class="table table-borderless">
                                <?php if (!empty($request['number'])): ?>
                                <tr>
                                    <td>Nomor Surat</td>
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
                                    <td>Tanggal Surat</td>
                                    <td>: <?= date('d F Y', strtotime($request['letter_date'])) ?></td>
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
                                <?php if ($request['status'] === 'rejected' && !empty($request['rejected_reason'])): ?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td class="text-danger">: <?= $request['rejected_reason'] ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Almarhum/Almarhumah</h6>
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
                                    <td>Alamat</td>
                                    <td>: <?= $request['address'] ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Meninggal</td>
                                    <td>: <?= formatDateIndo($request['death_date']) ?></td>
                                </tr>
                                <tr>
                                    <td>Lokasi Meninggal</td>
                                    <td>: <?= $request['location'] ?></td>
                                </tr>
                                <tr>
                                    <td>Sebab Meninggal</td>
                                    <td>: <?= $request['reason'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Pelapor</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Nama</td>
                                    <td>: <?= $request['report_name'] ?></td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>: <?= $request['report_nik'] ?></td>
                                </tr>
                                <tr>
                                    <td>Pekerjaan</td>
                                    <td>: <?= $request['report_occupation'] ?></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>: <?= $request['report_address'] ?></td>
                                </tr>
                                <tr>
                                    <td>Hubungan</td>
                                    <td>: <?= $request['relation'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Kepala Desa</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Nama</td>
                                    <td>: <?= $request['village_head_name'] ?></td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>: <?= $request['village_head_nip'] ?></td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>: <?= $request['village_head_position'] ?></td>
                                </tr>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Update Status Pengajuan</h6>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('death-cetificate-request/update-status/'.$request['id']) ?>" method="post">
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_processing" value="processing" <?= $request['status'] === 'processing' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="status_processing">Sedang Diproses</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_approved" value="approved" <?= $request['status'] === 'approved' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="status_approved">Disetujui</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_completed" value="completed" <?= $request['status'] === 'completed' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="status_completed">Selesai</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_rejected" value="rejected" <?= $request['status'] === 'rejected' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="status_rejected">Ditolak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" id="rejection_reason_container" style="display: none;">
                                            <label for="rejection_reason" class="col-sm-2 col-form-label">Alasan Penolakan</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"><?= $request['rejection_reason'] ?? '' ?></textarea>
                                                <div class="form-text">Berikan alasan mengapa pengajuan surat ini ditolak.</div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide rejection reason based on status
        const statusRejected = document.getElementById('status_rejected');
        const rejectionContainer = document.getElementById('rejection_reason_container');
        const rejectionReason = document.getElementById('rejection_reason');
        
        // Initial check
        if (statusRejected.checked) {
            rejectionContainer.style.display = 'flex';
            rejectionReason.setAttribute('required', 'required');
        }
        
        // Add event listeners to all radio buttons
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (statusRejected.checked) {
                    rejectionContainer.style.display = 'flex';
                    rejectionReason.setAttribute('required', 'required');
                } else {
                    rejectionContainer.style.display = 'none';
                    rejectionReason.removeAttribute('required');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?> 