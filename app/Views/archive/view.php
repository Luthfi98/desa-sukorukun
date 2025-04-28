<?= $this->extend('layouts/dashboard_layout'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Arsip Pengajuan Surat</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('archive') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nomor Surat</th>
                                    <td><?= $pengajuan['nomor_surat'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Pemohon</th>
                                    <td><?= $pengajuan['nama_pemohon'] ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <td><?= $pengajuan['jenis_surat'] ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td><?= date('d/m/Y', strtotime($pengajuan['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php
                                        $badge = 'secondary';
                                        if ($pengajuan['status'] === 'selesai') {
                                            $badge = 'success';
                                        } elseif ($pengajuan['status'] === 'ditolak') {
                                            $badge = 'danger';
                                        }
                                        ?>
                                        <span class="badge badge-<?= $badge ?>"><?= $pengajuan['status'] ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Keperluan</th>
                                    <td><?= $pengajuan['keperluan'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td><?= $pengajuan['keterangan'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td><?= $pengajuan['updated_at'] ? date('d/m/Y', strtotime($pengajuan['updated_at'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td><?= $pengajuan['catatan'] ?? '-' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if ($pengajuan['file_surat']): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>File Surat</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama File</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= basename($pengajuan['file_surat']) ?></td>
                                            <td>
                                                <a href="<?= base_url('uploads/surat/' . $pengajuan['file_surat']) ?>" 
                                                   class="btn btn-info btn-sm" 
                                                   target="_blank">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?> 