<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Formulir Pemrosesan</h5>
                    <div>
                        <a href="<?= base_url('relocation-request/view/'.$request['id']) ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Surat</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Nomor Surat</td>
                                    <td>: <?= $request['number'] ?? '-' ?></td>
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
                                <?php if ($request['status'] === 'rejected' && !empty($request['description'])): ?>
                                <tr>
                                    <td>Alasan Penolakan</td>
                                    <td class="text-danger">: <?= $request['description'] ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($request['processed_by'])): ?>
                                <tr>
                                    <td>Diproses Oleh</td>
                                    <td>: <?= $request['processor_name'] ?? 'Unknown' ?></td>
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
                                    <td>Jenis Kelamin</td>
                                    <td>: <?= $request['gender'] === 'male' ? 'Laki-laki' : 'Perempuan' ?></td>
                                </tr>
                                <tr>
                                    <td>Tempat, Tgl Lahir</td>
                                    <td>: <?= $request['pob'] ?>, <?= date('d F Y', strtotime($request['dob'])) ?></td>
                                </tr>
                                <tr>
                                    <td>Kewarganegaraan</td>
                                    <td>: <?= $request['nationality'] ?></td>
                                </tr>
                                <tr>
                                    <td>Pekerjaan</td>
                                    <td>: <?= $request['occupation'] ?></td>
                                </tr>
                                <tr>
                                    <td>Pendidikan</td>
                                    <td>: <?= $request['education'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Alamat Asal</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%">Alamat</td>
                                    <td>: <?= $request['origin_address'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Alamat Tujuan</h6>
                            <table class="table table-borderless">
                                <?php 
                                    $destination = json_decode($request['destination_detail'], true);
                                    if ($destination):
                                ?>
                                <tr>
                                    <td width="35%">Dusun/Alamat</td>
                                    <td>: <?= $destination['dusun'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td>RT/RW</td>
                                    <td>: <?= $destination['rt'] ?? '-' ?>/<?= $destination['rw'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td>Desa/Kelurahan</td>
                                    <td>: <?= $destination['desa'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td>Kecamatan</td>
                                    <td>: <?= $destination['kecamatan'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td>Kabupaten/Kota</td>
                                    <td>: <?= $destination['kabupaten'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td>Provinsi</td>
                                    <td>: <?= $destination['provinsi'] ?? '-' ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Detail Pindah</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="18%">Alasan Pindah</td>
                                    <td>: <?= $request['reason'] ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pindah</td>
                                    <td>: <?= date('d F Y', strtotime($request['move_date'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php 
                        $followers = json_decode($request['move_followers'], true);
                        if (!empty($followers)):
                    ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Pengikut Pindah</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Umur</th>
                                            <th>Status Perkawinan</th>
                                            <th>Pendidikan</th>
                                            <th>Nomor KTP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($followers as $follower): 
                                            switch ($follower['marital_status']) {
                                                case 'single':
                                                    $follower['marital_status'] = 'Belum Menikah';
                                                    break;
                                                case 'married':
                                                    $follower['marital_status'] = 'Menikah';
                                                    break;
                                                case 'divorced':
                                                    $follower['marital_status'] = 'Cerai';
                                                    break;
                                                case 'widow':
                                                    $follower['marital_status'] = 'Cerai Mati';
                                                    break;
                                            }
                                            ?>
                                        <tr>
                                            <td><?= $follower['name'] ?></td>
                                            <td><?= $follower['gender'] === 'male' ? 'Laki-laki' : 'Perempuan' ?></td>
                                            <td><?= $follower['age'] ?></td>
                                            <td><?= $follower['marital_status'] ?></td>
                                            <td><?= strtoupper($follower['education']) ?></td>
                                            <td><?= $follower['id_card'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

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
                                    <form action="<?= base_url('relocation-request/update-status/'.$request['id']) ?>" method="post">
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