<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Edit Pengajuan Keterangan Kematian</h5>
                    <a href="<?= base_url(session()->get('role') == 'resident' ? 'death-cetificate-request/my-request' : 'death-cetificate-request/') ?>" class="btn btn-secondary btn-sm">
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
                    <form action="<?= base_url('death-cetificate-request/update/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="report_nik" class="form-label">NIK (Nomor Induk Kependudukan) Pelapor <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="report_nik" name="report_nik" value="<?= old('report_nik', $request['report_nik']) ?>">
                                    <button type="button" class="btn btn-primary" id="checkNik">
                                        <i class="fas fa-search"></i> Cek
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="report_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="report_name" name="report_name" value="<?= old('report_name', $request['report_name']) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="report_occupation" class="form-label">Pekerjaan Pelapor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="report_occupation" name="report_occupation" value="<?= old('report_occupation', $request['report_occupation']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="report_dob" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="report_dob" name="report_dob" value="<?= old('report_dob', $request['report_dob']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="relation" class="form-label">Hubungan dengan yang Meninggal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="relation" name="relation" value="<?= old('relation', $request['relation']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="report_address" class="form-label">Alamat Pelapor <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="report_address" name="report_address" rows="2" required><?= old('report_address', $request['report_address']) ?></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $request['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK </label>
                                <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik', $request['nik']) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="pob" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pob" name="pob" value="<?= old('pob', $request['pob']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?= old('dob', $request['dob']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="male" <?= old('gender', $request['gender']) == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="female" <?= old('gender', $request['gender']) == 'female' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="religion" class="form-label">Agama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?= old('religion', $request['religion']) ?>">
                            </div>
                            <div class="col-md-8">
                                <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="2" required><?= old('address', $request['address']) ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="death_date" class="form-label">Tanggal Meninggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="death_date" name="death_date" value="<?= old('death_date', $request['death_date']) ?>" required>
                            </div>
                            <div class="col-md-8">
                                <label for="location" class="form-label">Lokasi Meninggal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="location" name="location" value="<?= old('location', $request['location']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="reason" class="form-label">Sebab Kematian <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reason" name="reason" rows="2" required><?= old('reason', $request['reason']) ?></textarea>
                            </div>
                        </div>
                        <?php if(session()->get('role') == 'admin' || session()->get('role') == 'staff'): ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="letter_date" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="letter_date" name="letter_date" value="<?= old('letter_date', $request['letter_date']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="letter_type_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                <select name="letter_type_id" id="letter_type_id" class="form-select" required>
                                    <option value="<?= old('letter_type_id', $request['letter_type_id']) ?>"><?= old('letter_type_name', $request['letter_type_name']) ?></option>
                                </select>
                            </div>
                        </div>
                        <?php else: ?>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="letter_type_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                    <select name="letter_type_id" id="letter_type_id" class="form-select" required>
                                        <option value="<?= old('letter_type_id', $request['letter_type_id']) ?>"><?= old('letter_type_name', $request['letter_type_name']) ?></option>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>


                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="village_head_name" class="form-label">Nama Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_name" name="village_head_name" value="<?= old('village_head_name', $request['village_head_name']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_nip" class="form-label">NIP Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_nip" name="village_head_nip" value="<?= old('village_head_nip', $request['village_head_nip']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_position" class="form-label">Jabatan Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_position" name="village_head_position" value="<?= old('village_head_position', $request['village_head_position']) ?>" readonly>
                            </div>
                        </div>
                        
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
    const checkNikButton = $('#checkNik');
    const nikInput = $('#report_nik');
    
    // Letter type requirements data
    const letterTypes = <?= json_encode($request['required_documents']) ?>;
    
    function refreshValues() {
    }

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


