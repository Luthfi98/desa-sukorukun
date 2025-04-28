<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if ($setting): ?>
        <form action="<?= base_url('admin/settings/updateProfile') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <!-- Card Informasi Desa -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Desa
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <div class="mb-3">
                                        <div class="position-relative" style="max-width: 200px;">
                                            <label for="">Logo Desa</label>
                                            <input type="file" class="custom-file-input" id="value_<?= $logo['id'] ?>" name="value_<?= $logo['id'] ?>">

                                            <img src="<?= base_url($logo['value']) ?>" alt="<?= $logo['label'] ?>" class="img-thumbnail w-100">
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_<?= $logo['id'] ?>" value="<?= $logo['id'] ?>">
                                    <input type="hidden" name="value_type_<?= $logo['id'] ?>" value="<?= $logo['value_type'] ?>">
                                </td>
                            </tr>
                            <?php 
                            $desaInfoKeys = ['nama_desa', 'kode_desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'alamat', 'telepon', 'email', 'website', 'sejarah_desa', 'visi_desa', 'misi_desa'];
                            foreach ($setting as $item): 
                                if (in_array($item['key'], $desaInfoKeys)):
                            ?>
                                <tr>
                                    <th width="30%"><?= $item['label'] ?></th>
                                    <td>
                                        <?php if ($item['value_type'] === 'image'): ?>
                                            <div class="mb-3">
                                                <div class="position-relative" style="max-width: 200px;">
                                                    <img src="<?= base_url($item['value']) ?>" alt="<?= $item['label'] ?>" class="img-thumbnail w-100">
                                                    <div class="position-absolute top-0 right-0 p-1">
                                                        <span class="badge badge-info">Preview</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>">
                                                <label class="custom-file-label" for="value_<?= $item['id'] ?>">
                                                    <i class="fas fa-upload mr-1"></i>Pilih file
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB</small>
                                            <input type="hidden" name="old_value_<?= $item['id'] ?>" value="<?= $item['value'] ?>">
                                        <?php elseif ($item['value_type'] === 'file'): ?>
                                            <div class="mb-3">
                                                <a href="<?= base_url($item['value']) ?>" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-download mr-1"></i> Download File
                                                </a>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>">
                                                <label class="custom-file-label" for="value_<?= $item['id'] ?>">
                                                    <i class="fas fa-upload mr-1"></i>Pilih file
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Format yang didukung: PDF, DOC, DOCX. Maksimal 5MB</small>
                                            <input type="hidden" name="old_value_<?= $item['id'] ?>" value="<?= $item['value'] ?>">
                                        <?php elseif ($item['value_type'] === 'boolean'): ?>
                                            <select class="form-control select2" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>">
                                                <option value="1" <?= $item['value'] == '1' ? 'selected' : '' ?>>
                                                    <i class="fas fa-check text-success"></i> Ya
                                                </option>
                                                <option value="0" <?= $item['value'] == '0' ? 'selected' : '' ?>>
                                                    <i class="fas fa-times text-danger"></i> Tidak
                                                </option>
                                            </select>
                                        <?php else: ?>
                                            <?php if (in_array($item['key'], ['sejarah_desa', 'visi_desa', 'misi_desa'])): ?>
                                                <textarea class="form-control editor" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>" rows="5"><?= $item['value'] ?></textarea>
                                            <?php else: ?>
                                                <input type="text" class="form-control" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>" value="<?= $item['value'] ?>">
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <input type="hidden" name="id_<?= $item['id'] ?>" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="value_type_<?= $item['id'] ?>" value="<?= $item['value_type'] ?>">
                                    </td>
                                </tr>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card Struktur Organisasi -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-sitemap mr-2"></i>Struktur Organisasi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <?php 
                            foreach ($strukturOrganisasi as $item): 
                            ?>
                                <tr>
                                    <th width="30%"><?= $item['label'] ?></th>
                                    <td>
                                        <?php if ($item['value_type'] === 'image'): ?>
                                            <div class="mb-3">
                                                <div class="position-relative" style="max-width: 100%;">
                                                    <img src="<?= base_url($item['value']) ?>" alt="<?= $item['label'] ?>" class="img-thumbnail w-100">
                                                    <div class="position-absolute top-0 right-0 p-1">
                                                        <span class="badge badge-info">Preview</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>">
                                                <label class="custom-file-label" for="value_<?= $item['id'] ?>">
                                                    <i class="fas fa-upload mr-1"></i>Pilih file
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB</small>
                                            <input type="hidden" name="old_value_<?= $item['id'] ?>" value="<?= $item['value'] ?>">
                                        <?php else: ?>
                                            <div class="form-group">
                                                <label for="value_<?= $item['id'] ?>">Nama</label>
                                                <input type="text" class="form-control" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>" value="<?= $item['value'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="description_<?= $item['id'] ?>">NIP</label>
                                                <input type="text" class="form-control" id="description_<?= $item['id'] ?>" name="description_<?= $item['id'] ?>" value="<?= $item['description'] ?>">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <input type="hidden" name="id_<?= $item['id'] ?>" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="value_type_<?= $item['id'] ?>" value="<?= $item['value_type'] ?>">
                                    </td>
                                </tr>
                            <?php 
                            endforeach; 
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i> Tidak ada data yang ditemukan
        </div>
    <?php endif; ?>
</div>

<!-- Add CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for all textareas with class 'editor'
    document.querySelectorAll('.editor').forEach(textarea => {
        ClassicEditor
            .create(textarea)
            .catch(error => {
                console.error(error);
            });
    });

    // Initialize custom file input
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file';
            const label = e.target.nextElementSibling;
            label.textContent = fileName;
        });
    });

    // Initialize Select2 for boolean fields
    $('.select2').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
});
</script>

<?= $this->endSection() ?>