<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Pengguna</h1>
        <a href="<?= base_url('users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->has('errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= base_url('users/update/' . $user['id']) ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
                                   id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                            <?php if (session('errors.username')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.username')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                            <?php if (session('errors.email')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.email')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                   id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <?php if (session('errors.password')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.password')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control <?= session('errors.password_confirmation') ? 'is-invalid' : '' ?>" 
                                   id="password_confirmation" name="password_confirmation">
                            <?php if (session('errors.password_confirmation')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.password_confirmation')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" value="<?= old('name', $user['name']) ?>" required>
                            <?php if (session('errors.name')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.name')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select select2 <?= session('errors.role') ? 'is-invalid' : '' ?>" 
                                    id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="staff" <?= old('role', $user['role']) === 'staff' ? 'selected' : '' ?>>Staff</option>
                                <option value="resident" <?= old('role', $user['role']) === 'resident' ? 'selected' : '' ?>>Resident</option>
                            </select>
                            <?php if (session('errors.role')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.role')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" 
                                       value="active" <?= old('status', $user['status']) === 'active' ? 'checked' : '' ?>>
                                <input type="hidden" name="status" value="inactive">
                                <label class="form-check-label" for="status" id="statusText"><?= old('status', $user['status']) === 'active' ? 'Aktif' : 'Tidak Aktif' ?></label>
                            </div>
                            <?php if (session('errors.status')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.status')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 resident-select" style="display: none;">
                            <label for="resident_id" class="form-label">Pilih Resident <span class="text-danger">*</span></label>
                            <select class="form-select select2 <?= session('errors.resident_id') ? 'is-invalid' : '' ?>" 
                                    id="resident_id" name="resident_id">
                                <option value="">Pilih Resident</option>
                                <?php foreach ($available_residents as $resident) : ?>
                                    <option value="<?= $resident['id'] ?>" <?= old('resident_id', $user['resident_id']) == $resident['id'] ? 'selected' : '' ?>>
                                        <?= esc($resident['name']) ?> || <?= esc($resident['nik']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.resident_id')) : ?>
                                <div class="invalid-feedback"><?= esc(session('errors.resident_id')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });

    // Handle role change
    $('#role').change(function() {
        if ($(this).val() === 'resident') {
            $('.resident-select').show();
            $('#resident_id').prop('required', true);
            $('#name').prop('readonly', true);
        } else {
            $('.resident-select').hide();
            $('#resident_id').prop('required', false);
            $('#name').prop('readonly', false);
        }
    });

    // Handle resident selection
    $('#resident_id').change(function() {
        if ($(this).val()) {
            // Get the selected option text
            const selectedText = $(this).find('option:selected').text();
            // Extract the name (before the ||)
            const residentName = selectedText.split('||')[0].trim();
            // Set the name field value
            $('#name').val(residentName);
        } else {
            $('#name').val('');
        }
    });

    // Handle status toggle
    $('#status').change(function() {
        if ($(this).is(':checked')) {
            $(this).val('active');
            $('input[name="status"][type="hidden"]').val('active');
            $('#statusText').text('Aktif');
        } else {
            $(this).val('inactive');
            $('input[name="status"][type="hidden"]').val('inactive');
            $('#statusText').text('Tidak Aktif');
        }
    });

    // Initial check for role
    if ($('#role').val() === 'resident') {
        $('.resident-select').show();
        $('#resident_id').prop('required', true);
        $('#name').prop('readonly', true);
    }

    // Initial check for status
    if ($('#status').is(':checked')) {
        $('#statusText').text('Aktif');
    } else {
        $('#statusText').text('Tidak Aktif');
    }
});
</script>
<?= $this->endSection() ?> 