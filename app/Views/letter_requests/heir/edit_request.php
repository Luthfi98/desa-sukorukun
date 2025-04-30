<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Edit Pengajuan SK Ahli Waris</h5>
                    <a href="<?= base_url('heir-request/my-request') ?>" class="btn btn-secondary btn-sm">
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
                    <form action="<?= base_url('heir-request/my-request/update/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="letter_type_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                <select name="letter_type_id" id="letter_type_id" class="form-control" required>
                                    <option value="<?= $request['letter_type_id'] ?>"><?= $request['letter_type_name'] ?></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="number" class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" id="number" name="number" value="<?= old('number', $request['number']) ?>" placeholder="Nomor Surat Terbuat Ketika sudah disetujui" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik', $request['nik']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $request['name']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="pob" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pob" name="pob" value="<?= old('pob', $request['pob']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?= old('dob', $request['dob']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="religion" class="form-label">Agama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?= old('religion', $request['religion']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="occupation" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?= old('occupation', $request['occupation']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= old('address', $request['address']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_of_death" class="form-label">Tanggal Kematian <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_of_death" name="date_of_death" value="<?= old('date_of_death', $request['date_of_death']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="time_of_death" class="form-label">Waktu Kematian <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="time_of_death" name="time_of_death" value="<?= old('time_of_death', $request['time_of_death']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="place_of_death" class="form-label">Tempat Kematian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="place_of_death" name="place_of_death" value="<?= old('place_of_death', $request['place_of_death']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cause_of_death" class="form-label">Sebab Kematian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cause_of_death" name="cause_of_death" value="<?= old('cause_of_death', $request['cause_of_death']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="burial_location" class="form-label">Lokasi Pemakaman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="burial_location" name="burial_location" value="<?= old('burial_location', $request['burial_location']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="burial_date" class="form-label">Tanggal Pemakaman <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="burial_date" name="burial_date" value="<?= old('burial_date', $request['burial_date']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Data Ahli Waris <span class="text-danger">*</span></label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="heir-table">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Tempat Lahir</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Hubungan</th>
                                                <th>NIK</th>
                                                <th>Pelapor</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $heirData = json_decode($request['heir_data'], true) ?? [];
                                            $oldHeirNames = old('heir_names') ?? array_column($heirData, 'name');
                                            $oldHeirBirthPlaces = old('heir_birth_places') ?? array_column($heirData, 'birth_place');
                                            $oldHeirBirthDates = old('heir_birth_dates') ?? array_column($heirData, 'birth_date');
                                            $oldHeirRelationships = old('heir_relationships') ?? array_column($heirData, 'relationship');
                                            $oldHeirNiks = old('heir_niks') ?? array_column($heirData, 'nik');
                                            $oldHeirReporters = old('heir_reporter') ?? array_column($heirData, 'is_reporter');
                                            
                                            for($i = 0; $i < count($oldHeirNames); $i++):
                                            ?>
                                            <tr>
                                                <td><input type="text" class="form-control" name="heir_names[]" value="<?= $oldHeirNames[$i] ?>" required></td>
                                                <td><input type="text" class="form-control" name="heir_birth_places[]" value="<?= $oldHeirBirthPlaces[$i] ?>" required></td>
                                                <td><input type="date" class="form-control" name="heir_birth_dates[]" value="<?= $oldHeirBirthDates[$i] ?>" required></td>
                                                <td><input type="text" class="form-control" name="heir_relationships[]" value="<?= $oldHeirRelationships[$i] ?>" required></td>
                                                <td><input type="text" class="form-control" name="heir_niks[]" value="<?= $oldHeirNiks[$i] ?>" required></td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="heir_reporter[]" value="1" <?= isset($oldHeirReporters[$i]) && $oldHeirReporters[$i] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label">Ya</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm mt-2" id="add-heir-row">
                                    <i class="fas fa-plus"></i> Tambah Ahli Waris
                                </button>
                            </div>
                        </div>

                        <!-- Village Head Information -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 class="mb-3">Informasi Kepala Desa</h5>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_name" class="form-label">Nama Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly id="village_head_name" name="village_head_name" value="<?= old('village_head_name', $kades['value']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_nip" class="form-label">NIP Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly id="village_head_nip" name="village_head_nip" value="<?= old('village_head_nip', $kades['description']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_position" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly id="village_head_position" name="village_head_position" value="<?= old('village_head_position', $kades['label']) ?>" required>
                            </div>
                        </div>

                        <!-- Subdistrict Head Information -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 class="mb-3">Informasi Camat</h5>
                            </div>
                            <div class="col-md-4">
                                <label for="subdistrict_head_name" class="form-label">Nama Camat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly id="subdistrict_head_name" name="subdistrict_head_name" value="<?= old('subdistrict_head_name', $camat['value']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="subdistrict_head_nip" class="form-label">NIP Camat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly id="subdistrict_head_nip" name="subdistrict_head_nip" value="<?= old('subdistrict_head_nip', $camat['description']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="subdistrict_head_position" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly id="subdistrict_head_position" name="subdistrict_head_position" value="<?= old('subdistrict_head_position', $camat['label']) ?>" required>
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

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
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



