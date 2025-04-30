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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($setting): ?>
        <form action="<?= base_url('admin/settings/updateProfile') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-info-tab" data-toggle="pill" href="#v-pills-info" role="tab" aria-controls="v-pills-info" aria-selected="true">
                            <i class="fas fa-info-circle mr-2"></i>Informasi Desa
                        </a>
                        <a class="nav-link" id="v-pills-struktur-tab" data-toggle="pill" href="#v-pills-struktur" role="tab" aria-controls="v-pills-struktur" aria-selected="false">
                            <i class="fas fa-sitemap mr-2"></i>Struktur Organisasi
                        </a>
                        <a class="nav-link" id="v-pills-geografis-tab" data-toggle="pill" href="#v-pills-geografis" role="tab" aria-controls="v-pills-geografis" aria-selected="false">
                            <i class="fas fa-map-marker-alt mr-2"></i>Geografis
                        </a>
                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <!-- Tab Informasi Desa -->
                        <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <div class="mb-3">
                                                        <div class="position-relative" style="max-width: 200px;">
                                                            <label for="">Logo Desa</label>
                                                            
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id_<?= $logo['id'] ?>" value="<?= $logo['id'] ?>">
                                                    <input type="hidden" name="value_type_<?= $logo['id'] ?>" value="<?= $logo['value_type'] ?>">
                                                </td>
                                                <td>
                                                    <img src="<?= base_url($logo['value']) ?>" alt="<?= $logo['label'] ?>" width="250px" class="img-thumbnail mt-2">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="value_<?= $logo['id'] ?>" name="value_<?= $logo['id'] ?>">
                                                        <label class="custom-file-label" for="value_<?= $logo['id'] ?>">Pilih file</label>
                                                    </div>
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
                                                                    <div class="position-absolute" style="top: 0; right: 0; padding: 5px;">
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
                        </div>

                        <!-- Tab Struktur Organisasi -->
                        <div class="tab-pane fade" id="v-pills-struktur" role="tabpanel" aria-labelledby="v-pills-struktur-tab">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <?php 
                                                $settingModel = new \App\Models\SettingModel();
                                            foreach ($strukturOrganisasi as $item): 
                                                $ttd = $settingModel->where('key', 'ttd_'.$item['key'])->first();
                                            
                                                // $ttd = get_setting('etc', 'ttd_'.$item['key'], false);
                                            ?>
                                                <tr>
                                                    <th width="30%"><?= $item['label'] ?></th>
                                                    <td>
                                                        <div class="form-group">
                                                            <label for="value_<?= $item['id'] ?>">Nama</label>
                                                            <input type="text" class="form-control" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>" value="<?= $item['value'] ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description_<?= $item['id'] ?>">NIP</label>
                                                            <input type="text" class="form-control" id="description_<?= $item['id'] ?>" name="description_<?= $item['id'] ?>" value="<?= $item['description'] ?>">
                                                        </div>
                                                        <?php if($ttd):?>
                                                            <div class="form-group">
                                                                <label for="ttd_<?= $ttd['id'] ?>">Tanda Tangan</label> <br>
                                                                <img src="<?= base_url($ttd['value']) ?>" width="200px" alt="Tanda Tangan <?= $item['label'] ?>" width="250px" class="img-thumbnail">
                                                                <input type="file" class="form-control" id="ttd_<?= $ttd['id'] ?>" name="ttd_<?= $ttd['id'] ?>">
                                                                <input type="hidden" name="id_<?= $ttd['id'] ?>" value="<?= $ttd['id'] ?>">
                                                                <input type="hidden" name="value_type_<?= $ttd['id'] ?>" value="<?= $ttd['value_type'] ?>">

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
                        </div>

                        <!-- Tab Geografis -->
                        <div class="tab-pane fade" id="v-pills-geografis" role="tabpanel" aria-labelledby="v-pills-geografis-tab">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <?php 
                                            foreach ($geografis as $item): 
                                            ?>
                                                <tr>
                                                    <th width="30%"><?= $item['label'] ?></th>
                                                    <td>
                                                        <div class="form-group">
                                                            <label for="value_<?= $item['id'] ?>"><?= $item['label'] ?></label>
                                                            <input type="text" class="form-control" id="value_<?= $item['id'] ?>" name="value_<?= $item['id'] ?>" value="<?= $item['value'] ?>">
                                                        </div>
                                                        
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right mt-4">
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