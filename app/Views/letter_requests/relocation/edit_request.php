<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Edit Surat Pindah</h5>
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
                    <form action="<?= base_url('relocation-request/my-request/update/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
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
                                <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik', $request['nik']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $request['name']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-control" readonly required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="male" <?= old('gender', $request['gender']) == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="female" <?= old('gender', $request['gender']) == 'female' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="pob" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pob" name="pob" value="<?= old('pob', $request['pob']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?= old('dob', $request['dob']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nationality" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nationality" name="nationality" value="<?= old('nationality', $request['nationality']) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="occupation" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?= old('occupation', $request['occupation']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="education" class="form-label">Pendidikan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="education" name="education" value="<?= old('education', $request['education']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="origin_address" class="form-label">Alamat Asal <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="origin_address" name="origin_address" rows="3" required><?= old('origin_address', $request['origin_address']) ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="destination_detail" class="form-label">Detail Tujuan Pindah <span class="text-danger">*</span></label>
                                <?php $destination = json_decode($request['destination_detail']) ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="destination_dusun" class="form-label">Dusun/Alamat <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_dusun" name="destination_dusun" value="<?= old('destination_dusun', $destination->dusun) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_rt" class="form-label">RT <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_rt" name="destination_rt" value="<?= old('destination_rt', $destination->rt) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_rw" class="form-label">RW <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_rw" name="destination_rw" value="<?= old('destination_rw', $destination->rw) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_desa" class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_desa" name="destination_desa" value="<?= old('destination_desa', $destination->desa) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_kecamatan" name="destination_kecamatan" value="<?= old('destination_kecamatan', $destination->kecamatan) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_kabupaten" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_kabupaten" name="destination_kabupaten" value="<?= old('destination_kabupaten', $destination->kabupaten) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="destination_provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_provinsi" name="destination_provinsi" value="<?= old('destination_provinsi', $destination->provinsi) ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="reason" class="form-label">Alasan Pindah <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" required><?= old('reason', $request['reason']) ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="move_date" class="form-label">Tanggal Pindah <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="move_date" name="move_date" value="<?= old('move_date', $request['move_date']) ?>" required>
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
                                                    $followerData = json_decode($request['move_followers'] ?? '[]', true) ?? [];
                                                    $oldFollowerNames = old('foll_name') ?? array_column($followerData, 'name');
                                                    $oldFollowerGenders = old('foll_gender') ?? array_column($followerData, 'gender');
                                                    $oldFollowerAges = old('foll_age') ?? array_column($followerData, 'age');
                                                    $oldFollowerMaritalStatuses = old('foll_marital_status') ?? array_column($followerData, 'marital_status');
                                                    $oldFollowerEducations = old('foll_education') ?? array_column($followerData, 'education');
                                                    $oldFollowerIdCards = old('foll_id_card') ?? array_column($followerData, 'id_card');

                                                    // Jika kosong, isi satu baris kosong
                                                    if (empty($oldFollowerNames)) {
                                                        $oldFollowerNames = [''];
                                                        $oldFollowerGenders = [''];
                                                        $oldFollowerAges = [''];
                                                        $oldFollowerMaritalStatuses = [''];
                                                        $oldFollowerEducations = [''];
                                                        $oldFollowerIdCards = [''];
                                                    }

                                                    for($i = 0; $i < count($oldFollowerNames); $i++):
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" name="foll_name[]" value="<?= $oldFollowerNames[$i] ?>" required>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="foll_gender[]" required>
                                                            <option value="">-- Pilih --</option>
                                                            <option value="male" <?= $oldFollowerGenders[$i] == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                                            <option value="female" <?= $oldFollowerGenders[$i] == 'female' ? 'selected' : '' ?>>Perempuan</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="foll_age[]" value="<?= $oldFollowerAges[$i] ?>" required min="1">
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="foll_marital_status[]" required>
                                                            <option value="">-- Pilih --</option>
                                                            <option value="single" <?= $oldFollowerMaritalStatuses[$i] == 'single' ? 'selected' : '' ?>>Belum Kawin</option>
                                                            <option value="married" <?= $oldFollowerMaritalStatuses[$i] == 'married' ? 'selected' : '' ?>>Kawin</option>
                                                            <option value="divorced" <?= $oldFollowerMaritalStatuses[$i] == 'divorced' ? 'selected' : '' ?>>Cerai</option>
                                                            <option value="widowed" <?= $oldFollowerMaritalStatuses[$i] == 'widowed' ? 'selected' : '' ?>>Cerai Mati</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="foll_education[]" required>
                                                            <option value="">-- Pilih --</option>
                                                            <option value="sd" <?= $oldFollowerEducations[$i] == 'sd' ? 'selected' : '' ?>>SD</option>
                                                            <option value="smp" <?= $oldFollowerEducations[$i] == 'smp' ? 'selected' : '' ?>>SMP</option>
                                                            <option value="sma" <?= $oldFollowerEducations[$i] == 'sma' ? 'selected' : '' ?>>SMA</option>
                                                            <option value="d1" <?= $oldFollowerEducations[$i] == 'd1' ? 'selected' : '' ?>>D1</option>
                                                            <option value="d2" <?= $oldFollowerEducations[$i] == 'd2' ? 'selected' : '' ?>>D2</option>
                                                            <option value="d3" <?= $oldFollowerEducations[$i] == 'd3' ? 'selected' : '' ?>>D3</option>
                                                            <option value="s1" <?= $oldFollowerEducations[$i] == 's1' ? 'selected' : '' ?>>S1</option>
                                                            <option value="s2" <?= $oldFollowerEducations[$i] == 's2' ? 'selected' : '' ?>>S2</option>
                                                            <option value="s3" <?= $oldFollowerEducations[$i] == 's3' ? 'selected' : '' ?>>S3</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="foll_id_card[]" value="<?= $oldFollowerIdCards[$i] ?>" required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-follower">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endfor; ?>

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

    // Function to add a new follower row
    function addFollowerRow() {
        const row = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="foll_name[]" required>
                </td>
                <td>
                    <select class="form-control" name="foll_gender[]" required>
                        <option value="">-- Pilih --</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="foll_age[]" required min="1">
                </td>
                <td>
                    <select class="form-control" name="foll_marital_status[]" required>
                        <option value="">-- Pilih --</option>
                        <option value="single">Belum Kawin</option>
                        <option value="married">Kawin</option>
                        <option value="divorced">Cerai</option>
                        <option value="widowed">Cerai Mati</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="foll_education[]" required>
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


