<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Edit Pengajuan Surat</h5>
                    <a href="<?= base_url('general-request') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                
                <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <h4>Errors:</h4>
                    <ul>
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <form action="<?= base_url('general-request/update/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nik" name="nik" value="<?= $request['nik'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= $request['name'] ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="pob" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pob" name="pob" value="<?= $request['pob'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?= $request['dob'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="religion" class="form-label">Agama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?= $request['religion'] ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nationality" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nationality" name="nationality" value="<?= $request['nationality'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="rt" name="rt" value="<?= $request['rt'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="rw" name="rw" value="<?= $request['rw'] ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="letter_type_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                <select name="letter_type_id" id="letter_type_id" class="form-control" required>
                                    <option value="<?= $request['letter_type_id'] ?>"><?= $request['letter_type_name'] ?></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="number" class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" id="number" name="number" value="<?= $request['number'] ?>" readonly>
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div id="document-upload-section">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Dokumen yang Diperlukan</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="required-documents-list">
                                                <!-- Document upload fields will be added here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="purpose" class="form-label">Tujuan <span class="text-danger">*</span></label>
                                <textarea name="purpose" id="purpose" rows="3" class="form-control" required><?= $request['purpose'] ?></textarea>
                                <div class="form-text">Jelaskan tujuan pembuatan surat ini (min. 10 karakter)</div>
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">Keterangan Tambahan</label>
                                <textarea name="description" id="description" rows="3" class="form-control"><?= $request['description'] ?></textarea>
                                <div class="form-text">Berikan keterangan tambahan jika diperlukan</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="village_head_name" class="form-label">Nama Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_name" name="village_head_name" value="<?= $request['village_head_name'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_nip" class="form-label">NIP Kepala Desa</label>
                                <input type="text" class="form-control" id="village_head_nip" name="village_head_nip" value="<?= $request['village_head_nip'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_position" class="form-label">Jabatan Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_position" name="village_head_position" value="<?= $request['village_head_position'] ?>" readonly>
                            </div>
                        </div>
                        
                        <div id="documents-container" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Upload Dokumen yang Diperlukan</label>
                                <div id="document-uploads">
                                    <!-- Document upload fields will be added here -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light">
                                <i class="fas fa-redo me-1"></i> Reset
                            </button>
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

<script>
$(document).ready(function() {
    const letterTypeSelect = $('#letter_type_id');
    const documentUploadSection = $('#document-upload-section');
    const requiredDocumentsList = $('#required-documents-list');
    
    // Function to create document upload fields
    function createDocumentUploadFields() {
        // Clear previous requirements
        requiredDocumentsList.empty();
        
        const requiredDocuments = <?= json_encode($request['required_documents']) ?>;
        
        // Convert to array if it's a string
        const documentsArray = typeof requiredDocuments === 'string' 
            ? requiredDocuments.split(',') 
            : Array.isArray(requiredDocuments) 
                ? requiredDocuments 
                : [];
        
        if (documentsArray.length > 0) {
            documentsArray.forEach((req, index) => {
                const reqName = req.trim();
                const isOptional = reqName.includes('(jika ada)');
                const displayName = reqName;
                
                // Check if document already exists
                const existingDoc = <?= json_encode($attachments) ?>.find(doc => doc.name === displayName);
                
                // Create document upload field
                const uploadDiv = $(`
                    <div class="mb-3">
                        <label class="form-label">${displayName} ${!isOptional ? '<span class="text-danger">*</span>' : ''}</label>
                        ${existingDoc ? `
                            <div class="mb-2">
                                <a href="<?= base_url() ?>${existingDoc.file_path}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                </a>
                            </div>
                        ` : ''}
                        <input type="file" name="documents[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" ${!isOptional && !existingDoc ? 'required' : ''}>
                        <input type="hidden" name="document_names[]" value="${displayName}">
                        <input type="hidden" name="document_ids[]" value="${existingDoc ? existingDoc.id : ''}">
                        <div class="form-text">Format yang diterima: PDF, JPG, JPEG, PNG ${isOptional ? '(Opsional)' : ''}</div>
                    </div>
                `);
                requiredDocumentsList.append(uploadDiv);
            });
        }
    }
    
    // Initialize document upload section
    createDocumentUploadFields();
});
</script>
<?= $this->endSection() ?> 


