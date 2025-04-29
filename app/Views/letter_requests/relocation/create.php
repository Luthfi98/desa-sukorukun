<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Form Surat Pindah</h5>
                    <a href="<?= base_url('relocation-request') ?>" class="btn btn-secondary btn-sm">
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
                    <form action="<?= base_url('relocation-request/store') ?>" method="post" enctype="multipart/form-data">
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
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik') ?>" required>
                                    <button type="button" class="btn btn-primary" id="checkNik">
                                        <i class="fas fa-search"></i> Cek
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-select" readonly required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option readonly value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option readonly value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="pob" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pob" name="pob" value="<?= old('pob') ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?= old('dob') ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nationality" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nationality" name="nationality" value="<?= old('nationality') ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="occupation" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?= old('occupation') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="education" class="form-label">Pendidikan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="education" name="education" value="<?= old('education') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="origin_address" class="form-label">Alamat Asal <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="origin_address" name="origin_address" rows="3" required><?= old('origin_address') ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="destination_detail" class="form-label">Detail Tujuan Pindah <span class="text-danger">*</span></label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="destination_dusun" class="form-label">Dusun/Alamat <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_dusun" name="destination_dusun" value="<?= old('destination_dusun') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_rt" class="form-label">RT <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_rt" name="destination_rt" value="<?= old('destination_rt') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_rw" class="form-label">RW <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_rw" name="destination_rw" value="<?= old('destination_rw') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_desa" class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_desa" name="destination_desa" value="<?= old('destination_desa') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_kecamatan" name="destination_kecamatan" value="<?= old('destination_kecamatan') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_kabupaten" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_kabupaten" name="destination_kabupaten" value="<?= old('destination_kabupaten') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_provinsi" name="destination_provinsi" value="<?= old('destination_provinsi') ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="reason" class="form-label">Alasan Pindah <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" required><?= old('reason') ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="move_date" class="form-label">Tanggal Pindah <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="move_date" name="move_date" value="<?= old('move_date') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Pengikut yang Pindah</h5>
                                        <button type="button" class="btn btn-primary btn-sm" id="addFollower">
                                            <i class="fas fa-plus"></i> Tambah Pengikut
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="followersTable">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Jenis Kelamin</th>
                                                        <th>Umur</th>
                                                        <th>Status Perkawinan</th>
                                                        <th>Pendidikan</th>
                                                        <th>Nomor KTP</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $oldFollowers = old('followers') ?? [];
                                                    if (empty($oldFollowers)) {
                                                        $oldFollowers = [['name' => '', 'gender' => '', 'age' => '', 'marital_status' => '', 'education' => '', 'id_card' => '']];
                                                    }
                                                    foreach ($oldFollowers as $index => $follower): 
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="foll_name[]" value="<?= $follower['name'] ?? '' ?>" required>
                                                        </td>
                                                        <td>
                                                            <select class="form-select" name="foll_gender[]" required>
                                                                <option value="">-- Pilih --</option>
                                                                <option value="male" <?= ($follower['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                                                <option value="female" <?= ($follower['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Perempuan</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="foll_age[]" value="<?= $follower['age'] ?? '' ?>" required min="1">
                                                        </td>
                                                        <td>
                                                            <select class="form-select" name="foll_marital_status[]" required>
                                                                <option value="">-- Pilih --</option>
                                                                <option value="single" <?= ($follower['marital_status'] ?? '') == 'single' ? 'selected' : '' ?>>Belum Kawin</option>
                                                                <option value="married" <?= ($follower['marital_status'] ?? '') == 'married' ? 'selected' : '' ?>>Kawin</option>
                                                                <option value="divorced" <?= ($follower['marital_status'] ?? '') == 'divorced' ? 'selected' : '' ?>>Cerai</option>
                                                                <option value="widowed" <?= ($follower['marital_status'] ?? '') == 'widowed' ? 'selected' : '' ?>>Cerai Mati</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select" name="foll_education[]" required>
                                                                <option value="">-- Pilih --</option>
                                                                <option value="sd" <?= ($follower['education'] ?? '') == 'sd' ? 'selected' : '' ?>>SD</option>
                                                                <option value="smp" <?= ($follower['education'] ?? '') == 'smp' ? 'selected' : '' ?>>SMP</option>
                                                                <option value="sma" <?= ($follower['education'] ?? '') == 'sma' ? 'selected' : '' ?>>SMA</option>
                                                                <option value="d1" <?= ($follower['education'] ?? '') == 'd1' ? 'selected' : '' ?>>D1</option>
                                                                <option value="d2" <?= ($follower['education'] ?? '') == 'd2' ? 'selected' : '' ?>>D2</option>
                                                                <option value="d3" <?= ($follower['education'] ?? '') == 'd3' ? 'selected' : '' ?>>D3</option>
                                                                <option value="s1" <?= ($follower['education'] ?? '') == 's1' ? 'selected' : '' ?>>S1</option>
                                                                <option value="s2" <?= ($follower['education'] ?? '') == 's2' ? 'selected' : '' ?>>S2</option>
                                                                <option value="s3" <?= ($follower['education'] ?? '') == 's3' ? 'selected' : '' ?>>S3</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="foll_id_card[]" value="<?= $follower['id_card'] ?? '' ?>" required>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-follower">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="village_head_name" class="form-label">Nama Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_name" name="village_head_name" value="<?= old('village_head_name', $kades['value']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_nip" class="form-label">NIP Kepala Desa</label>
                                <input type="text" class="form-control" id="village_head_nip" name="village_head_nip" value="<?= old('village_head_nip', $kades['description']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="village_head_position" class="form-label">Jabatan Kepala Desa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="village_head_position" name="village_head_position" value="<?= old('village_head_position', $kades['label']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="subdistrict_head_name" class="form-label">Nama Camat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subdistrict_head_name" name="subdistrict_head_name" value="<?= old('subdistrict_head_name', $camat['value']) ?>" readonly required>
                            </div>
                            <div class="col-md-4">
                                <label for="subdistrict_head_nip" class="form-label">NIP Camat</label>
                                <input type="text" class="form-control" id="subdistrict_head_nip" name="subdistrict_head_nip" value="<?= old('subdistrict_head_nip', $camat['description']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="subdistrict_head_position" class="form-label">Jabatan Camat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subdistrict_head_position" name="subdistrict_head_position" value="<?= old('subdistrict_head_position', $camat['label']) ?>" readonly required>
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
        $('#gender').val('');
        $('#occupation').val('');
        $('#education').val('');
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
                    $('#gender').val(data.resident.gender);
                    $('#name').val(data.resident.name);
                    $('#kk').val(data.resident.kk);
                    $('#occupation').val(data.resident.occupation);
                    $('#education').val(data.resident.education);
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

    // Function to add a new follower row
    function addFollowerRow() {
        const row = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="foll_name[]" required>
                </td>
                <td>
                    <select class="form-select" name="foll_gender[]" required>
                        <option value="">-- Pilih --</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="foll_age[]" required min="1">
                </td>
                <td>
                    <select class="form-select" name="foll_marital_status[]" required>
                        <option value="">-- Pilih --</option>
                        <option value="single">Belum Kawin</option>
                        <option value="married">Kawin</option>
                        <option value="divorced">Cerai</option>
                        <option value="widowed">Cerai Mati</option>
                    </select>
                </td>
                <td>
                    <select class="form-select" name="foll_education[]" required>
                        <option value="">-- Pilih --</option>
                        <option value="sd">SD</option>
                        <option value="smp">SMP</option>
                        <option value="sma">SMA</option>
                        <option value="d1">D1</option>
                        <option value="d2">D2</option>
                        <option value="d3">D3</option>
                        <option value="s1">S1</option>
                        <option value="s2">S2</option>
                        <option value="s3">S3</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="foll_id_card[]" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-follower">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#followersTable tbody').append(row);
    }

    // Add follower button click handler
    $('#addFollower').click(function() {
        addFollowerRow();
    });

    // Remove follower button click handler
    $(document).on('click', '.remove-follower', function() {
        $(this).closest('tr').remove();
    });

    // Initialize with one empty row if no followers exist
    if ($('#followersTable tbody tr').length === 0) {
        addFollowerRow();
    }

    // Function to update destination_detail hidden field
    function updateDestinationDetail() {
        const destinationDetail = {
            dusun: $('#destination_dusun').val(),
            rt: $('#destination_rt').val(),
            rw: $('#destination_rw').val(),
            desa: $('#destination_desa').val(),
            kecamatan: $('#destination_kecamatan').val(),
            kabupaten: $('#destination_kabupaten').val(),
            provinsi: $('#destination_provinsi').val()
        };
        $('#destination_detail').val(JSON.stringify(destinationDetail));
    }

    // Update destination_detail when any address field changes
    $('input[name^="destination_"]').on('change', function() {
        updateDestinationDetail();
    });

    // Initialize destination_detail on page load
    updateDestinationDetail();
});
</script>
<?= $this->endSection() ?> 


