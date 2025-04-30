<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <?php if (session()->has('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profil Akun</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profile/update') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?= $user['username'] ?? '' ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control <?= (session('errors.name')) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= old('name', $user['name'] ?? '') ?>">
                            <?php if (session('errors.name')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.name') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email', $user['email'] ?? '') ?>">
                            <?php if (session('errors.email')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.email') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" value="<?= ucfirst($user['role'] ?? '') ?>" readonly>
                        </div> -->
                        <button type="submit" class="btn btn-primary">Perbarui Profil</button>
                        <a href="<?= base_url('profile/change-password') ?>" class="btn btn-warning">Ubah Password</a>
                    </form>
                </div>
            </div>
        </div>

        <?php if (session()->get('role') === 'resident'): ?>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Data Diri</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profile/update-resident-data') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                        <div class="col-md-6 mb-3">
                                <label for="kk" class="form-label">KK</label>
                                <input type="text" class="form-control <?= (session('errors.kk')) ? 'is-invalid' : '' ?>" id="kk" name="kk" value="<?= old('kk', $resident['kk'] ?? '') ?>">
                                <?php if (session('errors.kk')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.kk') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control <?= (session('errors.nik')) ? 'is-invalid' : '' ?>" id="nik" name="nik" value="<?= old('nik', $resident['nik'] ?? '') ?>">
                                <?php if (session('errors.nik')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.nik') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control <?= (session('errors.name')) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= old('name', $resident['name'] ?? $user['name'] ?? '') ?>">
                                <?php if (session('errors.name')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.name') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_place" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control <?= (session('errors.birth_place')) ? 'is-invalid' : '' ?>" id="birth_place" name="birth_place" value="<?= old('birth_place', $resident['birth_place'] ?? '') ?>">
                                <?php if (session('errors.birth_place')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.birth_place') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control <?= (session('errors.birth_date')) ? 'is-invalid' : '' ?>" id="birth_date" name="birth_date" value="<?= old('birth_date', $resident['birth_date'] ?? '') ?>">
                                <?php if (session('errors.birth_date')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.birth_date') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-control <?= (session('errors.gender')) ? 'is-invalid' : '' ?>" id="gender" name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male" <?= (old('gender', $resident['gender'] ?? '') == 'male') ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="female" <?= (old('gender', $resident['gender'] ?? '') == 'female') ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                                <?php if (session('errors.gender')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.gender') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="religion" class="form-label">Agama</label>
                                <select class="form-control <?= (session('errors.religion')) ? 'is-invalid' : '' ?>" id="religion" name="religion">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam" <?= (old('religion', $resident['religion'] ?? '') == 'Islam') ? 'selected' : '' ?>>Islam</option>
                                    <option value="Kristen" <?= (old('religion', $resident['religion'] ?? '') == 'Kristen') ? 'selected' : '' ?>>Kristen</option>
                                    <option value="Katolik" <?= (old('religion', $resident['religion'] ?? '') == 'Katolik') ? 'selected' : '' ?>>Katolik</option>
                                    <option value="Hindu" <?= (old('religion', $resident['religion'] ?? '') == 'Hindu') ? 'selected' : '' ?>>Hindu</option>
                                    <option value="Buddha" <?= (old('religion', $resident['religion'] ?? '') == 'Buddha') ? 'selected' : '' ?>>Buddha</option>
                                    <option value="Konghucu" <?= (old('religion', $resident['religion'] ?? '') == 'Konghucu') ? 'selected' : '' ?>>Konghucu</option>
                                </select>
                                <?php if (session('errors.religion')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.religion') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control <?= (session('errors.address')) ? 'is-invalid' : '' ?>" id="address" name="address" rows="3"><?= old('address', $resident['address'] ?? '') ?></textarea>
                            <?php if (session('errors.address')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.address') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="rt" class="form-label">RT</label>
                                <input type="text" class="form-control <?= (session('errors.rt')) ? 'is-invalid' : '' ?>" id="rt" name="rt" value="<?= old('rt', $resident['rt'] ?? '') ?>">
                                <?php if (session('errors.rt')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.rt') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="rw" class="form-label">RW</label>
                                <input type="text" class="form-control <?= (session('errors.rw')) ? 'is-invalid' : '' ?>" id="rw" name="rw" value="<?= old('rw', $resident['rw'] ?? '') ?>">
                                <?php if (session('errors.rw')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.rw') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="village" class="form-label">Desa/Kelurahan</label>
                                <input type="text" class="form-control <?= (session('errors.village')) ? 'is-invalid' : '' ?>" id="village" name="village" value="<?= old('village', $resident['village'] ?? '') ?>">
                                <?php if (session('errors.village')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.village') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="district" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control <?= (session('errors.district')) ? 'is-invalid' : '' ?>" id="district" name="district" value="<?= old('district', $resident['district'] ?? '') ?>">
                                <?php if (session('errors.district')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.district') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="marital_status" class="form-label">Status Pernikahan</label>
                                <select class="form-control <?= (session('errors.marital_status')) ? 'is-invalid' : '' ?>" id="marital_status" name="marital_status">
                                    <option value="">Pilih Status</option>
                                    <option value="single" <?= (old('marital_status', $resident['marital_status'] ?? '') == 'single') ? 'selected' : '' ?>>Belum Menikah</option>
                                    <option value="married" <?= (old('marital_status', $resident['marital_status'] ?? '') == 'married') ? 'selected' : '' ?>>Menikah</option>
                                    <option value="divorced" <?= (old('marital_status', $resident['marital_status'] ?? '') == 'divorced') ? 'selected' : '' ?>>Cerai</option>
                                    <option value="widowed" <?= (old('marital_status', $resident['marital_status'] ?? '') == 'widowed') ? 'selected' : '' ?>>Janda/Duda</option>
                                </select>
                                <?php if (session('errors.marital_status')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.marital_status') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="occupation" class="form-label">Pekerjaan</label>
                                <input type="text" class="form-control <?= (session('errors.occupation')) ? 'is-invalid' : '' ?>" id="occupation" name="occupation" value="<?= old('occupation', $resident['occupation'] ?? '') ?>">
                                <?php if (session('errors.occupation')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.occupation') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label">Kewarganegaraan</label>
                                <input type="text" class="form-control <?= (session('errors.nationality')) ? 'is-invalid' : '' ?>" id="nationality" name="nationality" value="<?= old('nationality', $resident['nationality'] ?? 'WNI') ?>">
                                <?php if (session('errors.nationality')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.nationality') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="education" class="form-label">Pendidikan Terakhir</label>
                                <select class="form-control <?= (session('errors.education')) ? 'is-invalid' : '' ?>" id="education" name="education">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="SD" <?= (old('education', $resident['education'] ?? '') == 'SD') ? 'selected' : '' ?>>SD</option>
                                    <option value="SMP" <?= (old('education', $resident['education'] ?? '') == 'SMP') ? 'selected' : '' ?>>SMP</option>
                                    <option value="SMA" <?= (old('education', $resident['education'] ?? '') == 'SMA') ? 'selected' : '' ?>>SMA</option>
                                    <option value="D1" <?= (old('education', $resident['education'] ?? '') == 'D1') ? 'selected' : '' ?>>D1</option>
                                    <option value="D2" <?= (old('education', $resident['education'] ?? '') == 'D2') ? 'selected' : '' ?>>D2</option>
                                    <option value="D3" <?= (old('education', $resident['education'] ?? '') == 'D3') ? 'selected' : '' ?>>D3</option>
                                    <option value="D4" <?= (old('education', $resident['education'] ?? '') == 'D4') ? 'selected' : '' ?>>D4</option>
                                    <option value="S1" <?= (old('education', $resident['education'] ?? '') == 'S1') ? 'selected' : '' ?>>S1</option>
                                    <option value="S2" <?= (old('education', $resident['education'] ?? '') == 'S2') ? 'selected' : '' ?>>S2</option>
                                    <option value="S3" <?= (old('education', $resident['education'] ?? '') == 'S3') ? 'selected' : '' ?>>S3</option>
                                </select>
                                <?php if (session('errors.education')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.education') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="father_name" class="form-label">Nama Ayah</label>
                                <input type="text" class="form-control" id="father_name" name="father_name" value="<?= old('father_name', $resident['father_name'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mother_name" class="form-label">Nama Ibu</label>
                                <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?= old('mother_name', $resident['mother_name'] ?? '') ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Data Diri</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?> 