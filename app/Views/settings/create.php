<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Add New Setting</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('admin/settings/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="category" name="category" 
                                        value="<?= old('category') ?>" required 
                                        placeholder="e.g. apbd, program_desa, informasi_desa, etc."
                                        list="category-suggestions">
                                    <datalist id="category-suggestions">
                                        <option value="apbd">APBD</option>
                                        <option value="program_desa">Program Desa</option>
                                        <option value="informasi_desa">Informasi Desa</option>
                                        <option value="kontak">Kontak</option>
                                        <option value="sosial_media">Sosial Media</option>
                                        <option value="pengaturan_umum">Pengaturan Umum</option>
                                        <option value="logo">Logo</option>
                                    </datalist>
                                    <small class="text-muted">Category for grouping settings (use lowercase with underscore for spaces)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="key" name="key" 
                                        value="<?= old('key') ?>" required
                                        placeholder="e.g. site_name, email, phone_number">
                                    <small class="text-muted">Unique identifier for this setting (use lowercase with underscore for spaces)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="label" class="form-label">Label <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="label" name="label" 
                                value="<?= old('label') ?>" required
                                placeholder="e.g. Site Name, Contact Email, Phone Number">
                            <small class="text-muted">Human-readable name for this setting</small>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value_type" class="form-label">Value Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="value_type" name="value_type" required>
                                        <option value="text" <?= old('value_type') == 'text' ? 'selected' : '' ?>>Text</option>
                                        <option value="number" <?= old('value_type') == 'number' ? 'selected' : '' ?>>Number</option>
                                        <option value="date" <?= old('value_type') == 'date' ? 'selected' : '' ?>>Date</option>
                                        <option value="boolean" <?= old('value_type') == 'boolean' ? 'selected' : '' ?>>Boolean</option>
                                        <option value="json" <?= old('value_type') == 'json' ? 'selected' : '' ?>>JSON</option>
                                        <option value="file" <?= old('value_type') == 'file' ? 'selected' : '' ?>>File</option>
                                        <option value="image" <?= old('value_type') == 'image' ? 'selected' : '' ?>>Image</option>
                                    </select>
                                    <small class="text-muted">The type of value this setting will store</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="order" name="order" 
                                        value="<?= old('order', 0) ?>" min="0">
                                    <small class="text-muted">Order for displaying settings (smaller numbers appear first)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="value-container">
                            <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="value" name="value" rows="3"><?= old('value') ?></textarea>
                            <small class="text-muted">The value of this setting</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2"><?= old('description') ?></textarea>
                            <small class="text-muted">Optional description or help text for this setting</small>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" <?= old('is_public') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_public">Publicly Visible</label>
                            <small class="d-block text-muted">If checked, this setting will be visible to the public</small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/settings') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Setting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const valueTypeSelect = document.getElementById('value_type');
    const valueContainer = document.getElementById('value-container');
    let editor = null;
    
    function updateValueField() {
        const valueType = valueTypeSelect.value;
        let html = '';
        
        switch(valueType) {
            case 'text':
                html = `
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="value" name="value">${document.getElementById('value')?.value || ''}</textarea>
                    <small class="text-muted">The text value of this setting</small>
                `;
                break;
            case 'number':
                html = `
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="value" name="value" value="${document.getElementById('value')?.value || '0'}" step="any">
                    <small class="text-muted">The numeric value of this setting</small>
                `;
                break;
            case 'date':
                html = `
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="value" name="value" value="${document.getElementById('value')?.value || ''}">
                    <small class="text-muted">The date value of this setting</small>
                `;
                break;
            case 'boolean':
                html = `
                    <label class="form-label">Value <span class="text-danger">*</span></label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="value" id="value_true" value="true" ${document.getElementById('value')?.value === 'true' ? 'checked' : ''}>
                            <label class="form-check-label" for="value_true">True</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="value" id="value_false" value="false" ${document.getElementById('value')?.value === 'false' ? 'checked' : ''}>
                            <label class="form-check-label" for="value_false">False</label>
                        </div>
                    </div>
                    <small class="text-muted">The boolean value of this setting</small>
                `;
                break;
            case 'json':
                html = `
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="value" name="value" rows="5">${document.getElementById('value')?.value || '{}'}</textarea>
                    <small class="text-muted">Enter a valid JSON value</small>
                `;
                break;
            case 'file':
            case 'image':
                html = `
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="value" name="value">
                    <small class="text-muted">Upload a ${valueType}</small>
                `;
                break;
            default:
                html = `
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="value" name="value" value="${document.getElementById('value')?.value || ''}">
                    <small class="text-muted">The value of this setting</small>
                `;
        }
        
        valueContainer.innerHTML = html;
        
        // Initialize CKEditor if type is text
        if (valueType === 'text') {
            if (editor) {
                editor.destroy();
            }
            ClassicEditor
                .create(document.querySelector('#value'))
                .then(newEditor => {
                    editor = newEditor;
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
    
    valueTypeSelect.addEventListener('change', updateValueField);
    updateValueField();
});
</script>
<?= $this->endSection() ?> 