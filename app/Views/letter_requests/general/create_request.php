<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Form Pengajuan Surat Keterangan</h5>
                    <a href="<?= base_url('general-request/my-request') ?>" class="btn btn-secondary btn-sm">
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
                    <form action="<?= base_url('general-request/my-request/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik', $resident['nik']) ?>" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $resident['name']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Resident Information Fields -->


                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="pob" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pob" name="pob" value="<?= old('pob', $resident['birth_place']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?= old('dob', $resident['birth_date']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="religion" class="form-label">Agama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?= old('religion', $resident['religion']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nationality" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nationality" name="nationality" value="<?= old('nationality', $resident['nationality']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="rt" name="rt" value="<?= old('rt', $resident['rt']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="rw" name="rw" value="<?= old('rw', $resident['rw']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="letter_type_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                <select name="letter_type_id" id="letter_type_id" class="form-select" required>
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    <?php foreach($letterTypes as $type): ?>
                                    <option value="<?= $type['id'] ?>" <?= old('letter_type_id') == $type['id'] ? 'selected' : '' ?>><?= $type['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="number" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disable id="number" name="number" value="<?= old('number') ?>" placeholder="Nomor Surat Terbuat Ketika sudah disetujui" disabled>
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div id="document-upload-section" style="display: none;">
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
                                <textarea name="purpose" id="purpose" rows="3" class="form-control" required><?= old('purpose') ?></textarea>
                                <div class="form-text">Jelaskan tujuan pembuatan surat ini (min. 10 karakter)</div>
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">Keterangan Tambahan</label>
                                <textarea name="description" id="description" rows="3" class="form-control"><?= old('description') ?></textarea>
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
                                <input type="text" class="form-control" id="village_head_name" name="village_head_name" value="<?= old('village_head_name', $kades['value']) ?>" readonly >
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_nip" class="form-label">NIP Kepala Desa</label>
                                <input type="text" class="form-control" id="village_head_nip" name="village_head_nip" value="<?= old('village_head_nip', $kades['description']) ?>" readonly> 
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_position" class="form-label">Jabatan Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_position" name="village_head_position" value="<?= old('village_head_position', $kades['label']) ?>" readonly >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- <div class="col-md-6">
                                <label for="village_head_signature" class="form-label">Tanda Tangan Kepala Desa</label>
                                <input type="file" class="form-control" id="village_head_signature" name="village_head_signature" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label for="processed_by" class="form-label">Diproses Oleh <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="processed_by" name="processed_by" value="<?= old('processed_by') ?>" required>
                            </div> -->
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
                                <i class="fas fa-paper-plane me-1"></i> Buat Surat
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
    const nikInput = $('#nik');
    
    // Letter type requirements data
    const letterTypes = <?= json_encode($letterTypes) ?>;
    
    function refreshValues() {
        $('#resident_id').val('');
        $('#nik').val('');
        $('#name').val('');
        $('#pob').val('');
        $('#dob').val('');
        $('#religion').val('');
        $('#nationality').val('');
        $('#rt').val('');
        $('#rw').val('');
    }

    // Function to create document upload fields
    function createDocumentUploadFields(selectedType) {
        // Clear previous requirements
        requiredDocumentsList.empty();
        
        if (selectedType && selectedType.required_documents) {
            // Parse requirements (assuming it's stored as comma-separated values)
            const requirements = selectedType.required_documents.split(',');
            
            // Add each requirement to the list and create upload field
            requirements.forEach((req, index) => {
                const reqName = req.trim();
                const isOptional = reqName.includes('(jika ada)');
                const displayName = reqName;
                
                // Create document upload field
                const uploadDiv = $(`
                    <div class="mb-3">
                        <label class="form-label">${displayName} ${!isOptional ? '<span class="text-danger">*</span>' : ''}</label>
                        <input type="file" name="documents[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" ${!isOptional ? 'required' : ''}>
                        <input type="hidden" name="document_names[]" value="${displayName}">
                        <div class="form-text">Format yang diterima: PDF, JPG, JPEG, PNG ${isOptional ? '(Opsional)' : ''}</div>
                    </div>
                `);
                requiredDocumentsList.append(uploadDiv);
            });
            
            // Show the document upload section
            documentUploadSection.show();
        } else {
            documentUploadSection.hide();
        }
    }
    
    // Check NIK
    checkNikButton.click(function() {
        const nik = nikInput.val().trim();
        if (nik.length !== 16) {
            alert('NIK harus terdiri dari 16 digit');
            refreshValues();
            return;
        }
        
        // Make AJAX request to check NIK
        $.ajax({
            url: `<?= base_url('api/residents/check-nik/') ?>${nik}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Populate resident data fields
                    $('#resident_id').val(data.resident.id);
                    $('#nik').val(data.resident.nik);
                    $('#name').val(data.resident.name);
                    $('#pob').val(data.resident.birth_place);
                    $('#dob').val(data.resident.birth_date);
                    $('#religion').val(data.resident.religion);
                    $('#nationality').val(data.resident.nationality);
                    $('#rt').val(data.resident.rt);
                    $('#rw').val(data.resident.rw);
                } else {
                    alert('NIK tidak ditemukan');
                    refreshValues();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memeriksa NIK');
            }
        });
    });
    
    // Handle letter type selection
    letterTypeSelect.change(function() {
        const selectedId = $(this).val();
        if(selectedId) {
            // Find the selected letter type
            const selectedType = letterTypes.find(type => type.id == selectedId);
            createDocumentUploadFields(selectedType);
        } else {
            documentUploadSection.hide();
        }
    });

    // Initialize document upload section if there's a selected letter type
    const initialLetterTypeId = letterTypeSelect.val();
    if (initialLetterTypeId) {
        const selectedType = letterTypes.find(type => type.id == initialLetterTypeId);
        createDocumentUploadFields(selectedType);
    }
});
</script>
<?= $this->endSection() ?> 


