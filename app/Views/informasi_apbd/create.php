<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Tambah Data APBD</h5>
                    <a href="<?= base_url('informasi-apbd') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('informasi-apbd/create') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="<?= old('tahun', date('Y')) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                            <select class="form-control" id="jenis" name="jenis" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="pendapatan" <?= old('jenis') === 'pendapatan' ? 'selected' : '' ?>>Pendapatan</option>
                                <option value="belanja" <?= old('jenis') === 'belanja' ? 'selected' : '' ?>>Belanja</option>
                                <option value="pembiayaan" <?= old('jenis') === 'pembiayaan' ? 'selected' : '' ?>>Pembiayaan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kategori" name="kategori" value="<?= old('kategori') ?>" required>
                        <div class="form-text text-muted">Contoh: Pendapatan Asli Desa, Dana Desa, Belanja Pegawai, dll.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="uraian" class="form-label">Uraian <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="uraian" name="uraian" rows="3" required><?= old('uraian') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= old('jumlah') ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="rencana" <?= old('status') === 'rencana' ? 'selected' : '' ?>>Rencana</option>
                                <option value="realisasi" <?= old('status') === 'realisasi' ? 'selected' : '' ?>>Realisasi</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= old('keterangan') ?></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="reset" class="btn btn-light me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Format jumlah as currency
    $('#jumlah').on('keyup', function() {
        let val = $(this).val();
        if (val !== '') {
            $(this).val(parseFloat(val.replace(/,/g, '')).toFixed(0));
        }
    });
});
</script>
<?= $this->endSection() ?> 