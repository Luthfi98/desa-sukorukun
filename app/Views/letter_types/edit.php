<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Edit Jenis Surat</h1>
        <div>
            <a href="<?= base_url('letter-types') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('letter-types/update/' . $letterType['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Jenis Surat</label>
                            <input type="text" class="form-control <?= ($validation->hasError('name')) ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" value="<?= old('name', $letterType['name']) ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('name') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Surat</label>
                            <input type="text" class="form-control <?= ($validation->hasError('code')) ? 'is-invalid' : '' ?>" 
                                   id="code" name="code" value="<?= old('code', $letterType['code']) ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('code') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control <?= ($validation->hasError('description')) ? 'is-invalid' : '' ?>" 
                                      id="description" name="description" rows="3"><?= old('description', $letterType['description']) ?></textarea>
                            <div class="invalid-feedback">
                                <?= $validation->getError('description') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" 
                                    id="status" name="status">
                                <option value="active" <?= (old('status', $letterType['status']) == 'active') ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= (old('status', $letterType['status']) == 'inactive') ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="template" class="form-label">Template Surat</label>
                            <textarea class="form-control <?= ($validation->hasError('template')) ? 'is-invalid' : '' ?>" 
                                      id="template" name="template" rows="5"><?= old('template', $letterType['template']) ?></textarea>
                            <div class="form-text">Masukkan template surat dengan format HTML</div>
                            <div class="invalid-feedback">
                                <?= $validation->getError('template') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="required_documents" class="form-label">Dokumen yang Diperlukan</label>
                            <textarea class="form-control <?= ($validation->hasError('required_documents')) ? 'is-invalid' : '' ?>" 
                                      id="required_documents" name="required_documents" rows="3"><?= old('required_documents', $letterType['required_documents']) ?></textarea>
                            <div class="form-text">Masukkan daftar dokumen yang diperlukan, pisahkan dengan koma (,)</div>
                            <div class="invalid-feedback">
                                <?= $validation->getError('required_documents') ?>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 