<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Buat Pengajuan Surat untuk Penduduk</h5>
                    <a href="<?= base_url('letter-requests') ?>" class="btn btn-secondary btn-sm">
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
                    <form action="<?= base_url('letter-requests/process-create-for-resident') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik') ?>" required>
                                    <button type="button" class="btn btn-primary" id="checkNik">
                                        <i class="fas fa-search"></i> Cek
                                    </button>
                                </div>
                                <div id="residentInfo" class="mt-2" style="display: none;">
                                    <div class="alert alert-info">
                                        <strong>Nama:</strong> <span id="residentName"></span><br>
                                        <strong>Alamat:</strong> <span id="residentAddress"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="letter_type_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                <select name="letter_type_id" id="letter_type_id" class="form-select" required>
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    <?php foreach($letterTypes as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Pilih jenis surat yang akan dibuat</div>
                            </div>
                            <div class="col-md-6" id="requirements-container" style="display: none;">
                                <label class="form-label">Dokumen yang Diperlukan</label>
                                <div class="card">
                                    <div class="card-body p-3">
                                        <ul class="mb-0" id="requirements-list">
                                            <!-- Requirements will be loaded here -->
                                        </ul>
                                    </div>
                                </div>
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
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Tujuan <span class="text-danger">*</span></label>
                            <textarea name="purpose" id="purpose" rows="3" class="form-control" required><?= old('purpose') ?></textarea>
                            <div class="form-text">Jelaskan tujuan pembuatan surat ini (min. 10 karakter)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan Tambahan</label>
                            <textarea name="description" id="description" rows="3" class="form-control"><?= old('description') ?></textarea>
                            <div class="form-text">Berikan keterangan tambahan jika diperlukan</div>
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
document.addEventListener('DOMContentLoaded', function() {
    const letterTypeSelect = document.getElementById('letter_type_id');
    const requirementsContainer = document.getElementById('requirements-container');
    const requirementsList = document.getElementById('requirements-list');
    const documentsContainer = document.getElementById('documents-container');
    const documentUploads = document.getElementById('document-uploads');
    const checkNikButton = document.getElementById('checkNik');
    const nikInput = document.getElementById('nik');
    const residentInfo = document.getElementById('residentInfo');
    const residentName = document.getElementById('residentName');
    const residentAddress = document.getElementById('residentAddress');
    
    // Letter type requirements data
    const letterTypes = <?= json_encode($letterTypes) ?>;
    
    // Check NIK
    checkNikButton.addEventListener('click', function() {
        const nik = nikInput.value.trim();
        if (nik.length !== 16) {
            alert('NIK harus terdiri dari 16 digit');
            return;
        }
        
        // Make AJAX request to check NIK
        fetch(`<?= base_url('api/residents/check-nik/') ?>${nik}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    residentName.textContent = data.resident.name;
                    residentAddress.textContent = data.resident.address;
                    residentInfo.style.display = 'block';
                } else {
                    alert('NIK tidak ditemukan');
                    residentInfo.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memeriksa NIK');
            });
    });
    
    // Handle letter type selection
    letterTypeSelect.addEventListener('change', function() {
        const selectedId = this.value;
        
        if(selectedId) {
            // Find the selected letter type
            const selectedType = letterTypes.find(type => type.id == selectedId);
            
            if(selectedType && selectedType.required_documents) {
                // Clear previous requirements
                requirementsList.innerHTML = '';
                documentUploads.innerHTML = '';
                
                // Parse requirements (assuming it's stored as comma-separated values)
                const requirements = selectedType.required_documents.split(',');
                
                // Add each requirement to the list and create upload field
                requirements.forEach(req => {
                    const reqName = req.trim();
                    
                    // Add to requirements list
                    const li = document.createElement('li');
                    li.textContent = reqName;
                    requirementsList.appendChild(li);
                    
                    // Create upload field
                    const uploadDiv = document.createElement('div');
                    uploadDiv.className = 'mb-2';
                    uploadDiv.innerHTML = `
                        <label class="form-label">${reqName}</label>
                        <input type="file" name="documents[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        <input type="hidden" name="document_names[]" value="${reqName}">
                    `;
                    documentUploads.appendChild(uploadDiv);
                });
                
                // Show the containers
                requirementsContainer.style.display = 'block';
                documentsContainer.style.display = 'block';
            } else {
                requirementsContainer.style.display = 'none';
                documentsContainer.style.display = 'none';
            }
        } else {
            requirementsContainer.style.display = 'none';
            documentsContainer.style.display = 'none';
        }
    });
});
</script>
<?= $this->endSection() ?> 