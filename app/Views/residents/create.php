<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Penduduk</h1>
        <a href="<?= base_url('residents') ?>" class="btn btn-secondary">
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
            <form action="<?= base_url('residents/create') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kk" class="form-label">KK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kk" name="kk" value="<?= old('kk') ?>" required maxlength="16">
                            <div class="form-text">KK harus 16 digit angka</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik') ?>" required maxlength="16">
                            <div class="form-text">NIK harus 16 digit angka</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="birth_place" name="birth_place" value="<?= old('birth_place') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= old('birth_date') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="religion" class="form-label">Agama <span class="text-danger">*</span></label>
                            <select class="form-select" id="religion" name="religion" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam" <?= old('religion') == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                <option value="Kristen" <?= old('religion') == 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                                <option value="Katolik" <?= old('religion') == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                <option value="Hindu" <?= old('religion') == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                <option value="Buddha" <?= old('religion') == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                <option value="Konghucu" <?= old('religion') == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="address" name="address" rows="3" required><?= old('address') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rt" name="rt" value="<?= old('rt') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rw" name="rw" value="<?= old('rw') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="village" class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="village" name="village" value="<?= old('village') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="district" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="district" name="district" value="<?= old('district') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="marital_status" class="form-label">Status Perkawinan <span class="text-danger">*</span></label>
                            <select class="form-select" id="marital_status" name="marital_status" required>
                                <option value="">Pilih Status</option>
                                <option value="single" <?= old('marital_status') == 'single' ? 'selected' : '' ?>>Belum Menikah</option>
                                <option value="married" <?= old('marital_status') == 'married' ? 'selected' : '' ?>>Menikah</option>
                                <option value="divorced" <?= old('marital_status') == 'divorced' ? 'selected' : '' ?>>Cerai</option>
                                <option value="widowed" <?= old('marital_status') == 'widowed' ? 'selected' : '' ?>>Janda/Duda</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="occupation" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="occupation" name="occupation" value="<?= old('occupation') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nationality" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nationality" name="nationality" value="<?= old('nationality', 'WNI') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="education" class="form-label">Pendidikan <span class="text-danger">*</span></label>
                            <select class="form-select" id="education" name="education" required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="Tidak Sekolah" <?= old('education') == 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                                <option value="SD" <?= old('education') == 'SD' ? 'selected' : '' ?>>SD</option>
                                <option value="SMP" <?= old('education') == 'SMP' ? 'selected' : '' ?>>SMP</option>
                                <option value="SMA" <?= old('education') == 'SMA' ? 'selected' : '' ?>>SMA</option>
                                <option value="D3" <?= old('education') == 'D3' ? 'selected' : '' ?>>D3</option>
                                <option value="S1" <?= old('education') == 'S1' ? 'selected' : '' ?>>S1</option>
                                <option value="S2" <?= old('education') == 'S2' ? 'selected' : '' ?>>S2</option>
                                <option value="S3" <?= old('education') == 'S3' ? 'selected' : '' ?>>S3</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="father_name" class="form-label">Nama Ayah</label>
                            <input type="text" class="form-control" id="father_name" name="father_name" value="<?= old('father_name') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mother_name" class="form-label">Nama Ibu</label>
                            <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?= old('mother_name') ?>">
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
<?= $this->endSection() ?> 