<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Create New Letter Request</h5>
                    <a href="<?= base_url('letter-requests/my-requests') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to My Requests
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
                    <form action="<?= base_url('letter-requests/create') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="resident_name" class="form-label">Resident Name</label>
                                <input type="text" class="form-control" id="resident_name" value="<?= $resident['name'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK (ID Number)</label>
                                <input type="text" class="form-control" id="nik" value="<?= $resident['nik'] ?? '-' ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="letter_type_id" class="form-label">Letter Type <span class="text-danger">*</span></label>
                                <select name="letter_type_id" id="letter_type_id" class="form-control" required>
                                    <option value="">-- Select Letter Type --</option>
                                    <?php foreach($letterTypes as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Select the type of letter you want to request</div>
                            </div>
                            <div class="col-md-6" id="requirements-container" style="display: none;">
                                <label class="form-label">Required Documents</label>
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
                                <label class="form-label">Upload Required Documents</label>
                                <div id="document-uploads">
                                    <!-- Document upload fields will be added here -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                            <textarea name="purpose" id="purpose" rows="3" class="form-control" required><?= old('purpose') ?></textarea>
                            <div class="form-text">Explain the purpose of this letter request (min. 10 characters)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Additional Information</label>
                            <textarea name="description" id="description" rows="3" class="form-control"><?= old('description') ?></textarea>
                            <div class="form-text">Provide any additional information that might be helpful</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light">
                                <i class="fas fa-redo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Submit Request
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
    
    // Letter type requirements data
    const letterTypes = <?= json_encode($letterTypes) ?>;
    
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