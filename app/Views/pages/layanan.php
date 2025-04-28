<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="fw-bold">Layanan Desa</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Layanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="fw-bold mb-4">Kami Melayani Masyarakat Desa</h2>
                <p>Kantor Desa Sejahtera berkomitmen untuk memberikan pelayanan terbaik bagi seluruh warga desa. Kami terus berupaya meningkatkan kualitas pelayanan melalui pengembangan sistem pelayanan yang lebih efisien dan responsif.</p>
                <p>Untuk informasi lebih lanjut tentang layanan yang tersedia, silakan hubungi kantor desa selama jam kerja atau kirim pertanyaan melalui formulir kontak di website ini.</p>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Jam Layanan</h5>
                                <small class="text-muted">Senin - Jumat, 08.00 - 16.00</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary p-2 rounded text-white me-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Telepon</h5>
                                <small class="text-muted">(021) 1234-5678</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="https://source.unsplash.com/random/600x400/?village,office" alt="Kantor Desa" class="img-fluid rounded shadow">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12 text-center mb-5">
                <h2 class="fw-bold">Jenis Layanan</h2>
                <p class="text-muted">Berikut adalah layanan yang tersedia di Kantor Desa Sejahtera</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Service 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4 text-center">
                            <div class="bg-primary d-inline-block p-3 rounded-circle text-white mb-3">
                                <i class="fas fa-id-card fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">Administrasi Kependudukan</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Pengurusan Kartu Tanda Penduduk (KTP)
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Pengurusan Kartu Keluarga (KK)
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Domisili
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Kelahiran
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Kematian
                            </li>
                        </ul>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4 text-center">
                            <div class="bg-success d-inline-block p-3 rounded-circle text-white mb-3">
                                <i class="fas fa-house-user fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">Pertanahan dan Domisili</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Tanah
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Jual Beli Tanah
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Waris
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Domisili Usaha
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Rekomendasi Izin Mendirikan Bangunan
                            </li>
                        </ul>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4 text-center">
                            <div class="bg-info d-inline-block p-3 rounded-circle text-white mb-3">
                                <i class="fas fa-hand-holding-medical fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">Layanan Kesehatan</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Posyandu Balita dan Lansia
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Poliklinik Desa
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Program Keluarga Berencana
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Tidak Mampu (SKTM) untuk Berobat
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Pendampingan Program BPJS Kesehatan
                            </li>
                        </ul>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4 text-center">
                            <div class="bg-warning d-inline-block p-3 rounded-circle text-white mb-3">
                                <i class="fas fa-tractor fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">Pemberdayaan Masyarakat</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Pelatihan Keterampilan
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Program Pemberdayaan UMKM
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Kelompok Tani dan Ternak
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Bantuan Sosial dan Ekonomi
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> BUMDes (Badan Usaha Milik Desa)
                            </li>
                        </ul>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4 text-center">
                            <div class="bg-danger d-inline-block p-3 rounded-circle text-white mb-3">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">Pendidikan</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> PAUD (Pendidikan Anak Usia Dini)
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Program Beasiswa
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Perpustakaan Desa
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Taman Bacaan Masyarakat
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Pelatihan Komputer untuk Warga
                            </li>
                        </ul>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4 text-center">
                            <div class="bg-secondary d-inline-block p-3 rounded-circle text-white mb-3">
                                <i class="fas fa-file-signature fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">Administrasi Umum</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Usaha
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Pengantar Catatan Kepolisian
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Legalisasi Dokumen
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Keterangan Nikah
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Surat Pernyataan Waris
                            </li>
                        </ul>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">Detail Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Process -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-5">
                <h2 class="fw-bold">Alur Pelayanan</h2>
                <p class="text-muted">Berikut adalah proses untuk mendapatkan layanan di Kantor Desa Sejahtera</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">1</h3>
                                </div>
                                <h5 class="fw-bold">Persiapan Dokumen</h5>
                                <p class="text-muted">Siapkan dokumen yang diperlukan sesuai dengan jenis layanan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">2</h3>
                                </div>
                                <h5 class="fw-bold">Pengisian Formulir</h5>
                                <p class="text-muted">Isi formulir permohonan yang tersedia di kantor desa</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">3</h3>
                                </div>
                                <h5 class="fw-bold">Verifikasi</h5>
                                <p class="text-muted">Petugas akan memeriksa kelengkapan dokumen anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <h3 class="mb-0">4</h3>
                                </div>
                                <h5 class="fw-bold">Pengambilan Dokumen</h5>
                                <p class="text-muted">Ambil dokumen yang telah selesai sesuai waktu yang ditentukan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-8 mx-auto text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Butuh Informasi Lebih Lanjut?</h4>
                        <p>Silakan hubungi kami di nomor (021) 1234-5678 atau datang langsung ke Kantor Desa Sejahtera</p>
                        <a href="<?= base_url('kontak') ?>" class="btn btn-primary px-4 py-2">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 