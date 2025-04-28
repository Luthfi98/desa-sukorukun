<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Buat Pengajuan SK Domisili</h5>
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
                    <form action="<?= base_url('heir-request/my-request/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
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
                                <label for="number" class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" id="number" name="number" value="<?= old('number') ?>" placeholder="Nomor Surat Terbuat Ketika sudah disetujui" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik', $resident['nik']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $resident['name']) ?>" readonly>
                            </div>
                        </div>

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
                            <div class="col-md-6">
                                <label for="occupation" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?= old('occupation', $resident['occupation']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= old('address', 'Desa Sukorukun RT '.$resident['rt'].' RW '. $resident['rw'].' Kecamatan Jaken Kabupaten Pati') ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_of_death" class="form-label">Tanggal Kematian <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_of_death" name="date_of_death" value="<?= old('date_of_death') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="time_of_death" class="form-label">Waktu Kematian <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="time_of_death" name="time_of_death" value="<?= old('time_of_death') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="place_of_death" class="form-label">Tempat Kematian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="place_of_death" name="place_of_death" value="<?= old('place_of_death') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cause_of_death" class="form-label">Sebab Kematian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cause_of_death" name="cause_of_death" value="<?= old('cause_of_death') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="burial_location" class="form-label">Lokasi Pemakaman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="burial_location" name="burial_location" value="<?= old('burial_location') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="burial_date" class="form-label">Tanggal Pemakaman <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="burial_date" name="burial_date" value="<?= old('burial_date') ?>" required>
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
                                            $oldHeirNames = old('heir_names') ?? [''];
                                            $oldHeirBirthPlaces = old('heir_birth_places') ?? [''];
                                            $oldHeirBirthDates = old('heir_birth_dates') ?? [''];
                                            $oldHeirRelationships = old('heir_relationships') ?? [''];
                                            $oldHeirNiks = old('heir_niks') ?? [''];
                                            $oldHeirReporters = old('heir_reporter') ?? [0];
                                            
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

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
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
    const checkNikButton = $('#checkNik');
    const nikInput = $('#nik');
    
    // Letter type requirements data
    const letterTypes = <?= json_encode($letterTypes) ?>;
    
    function refreshValues() {
        $('#resident_id').val('');
        $('#nik').val('');
        $('#name').val('');
        $('#kk').val('');
        $('#pob').val('');
        $('#dob').val('');
        $('#address').val('');
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
                    $('#kk').val(data.resident.kk);
                    $('#pob').val(data.resident.birth_place);
                    $('#dob').val(data.resident.birth_date);
                    $('#address').val(data.resident.address);
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


