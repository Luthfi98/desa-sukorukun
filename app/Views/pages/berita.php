<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="fw-bold">Berita Desa</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Berita</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="py-5">
    <div class="container">
        <!-- Search and Category Bar -->
        <div class="row mb-5">
            <div class="col-md-8">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Cari berita..." aria-label="Search">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>
            <div class="col-md-4">
                <select class="form-select">
                    <option selected>Semua Kategori</option>
                    <option value="1">Kegiatan Desa</option>
                    <option value="2">Pengumuman</option>
                    <option value="3">Pembangunan</option>
                    <option value="4">Kesehatan</option>
                    <option value="5">Pendidikan</option>
                </select>
            </div>
        </div>
        
        <!-- News List -->
        <div class="row">
            <!-- News Item 1 -->
            <div class="col-lg-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://source.unsplash.com/random/600x400/?village,meeting" class="img-fluid rounded-start h-100 object-fit-cover" alt="Berita 1">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-primary">Kegiatan</span>
                                    <small class="text-muted"><i class="far fa-calendar me-1"></i> 12 Juni 2023</small>
                                </div>
                                <h3 class="card-title fw-bold">Musyawarah Perencanaan Pembangunan Desa Tahun 2023</h3>
                                <p class="card-text">Musyawarah Perencanaan Pembangunan (Musrenbang) Desa Sejahtera telah dilaksanakan untuk merencanakan program pembangunan tahun depan. Acara yang dihadiri oleh tokoh masyarakat, kepala dusun, dan perwakilan warga ini berlangsung selama dua hari di Balai Desa.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn btn-primary">Baca selengkapnya</a>
                                    <div>
                                        <span class="me-3"><i class="far fa-eye me-1"></i> 256</span>
                                        <span><i class="far fa-comment me-1"></i> 24</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- News Item 2 -->
            <div class="col-lg-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://source.unsplash.com/random/600x400/?harvest,farm" class="img-fluid rounded-start h-100 object-fit-cover" alt="Berita 2">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-success">Pertanian</span>
                                    <small class="text-muted"><i class="far fa-calendar me-1"></i> 5 Juni 2023</small>
                                </div>
                                <h3 class="card-title fw-bold">Panen Raya Padi Organik Meningkat 30% Tahun Ini</h3>
                                <p class="card-text">Program pertanian organik yang dijalankan oleh kelompok tani desa telah menunjukkan hasil positif dengan peningkatan hasil panen sebesar 30% dibandingkan tahun lalu. Keberhasilan ini merupakan buah dari kerjasama antara pemerintah desa, kelompok tani, dan pendampingan dari dinas pertanian.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn btn-primary">Baca selengkapnya</a>
                                    <div>
                                        <span class="me-3"><i class="far fa-eye me-1"></i> 198</span>
                                        <span><i class="far fa-comment me-1"></i> 15</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- News Item 3 -->
            <div class="col-lg-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://source.unsplash.com/random/600x400/?traditional,dance" class="img-fluid rounded-start h-100 object-fit-cover" alt="Berita 3">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-info">Budaya</span>
                                    <small class="text-muted"><i class="far fa-calendar me-1"></i> 28 Mei 2023</small>
                                </div>
                                <h3 class="card-title fw-bold">Festival Budaya Desa Menarik Ratusan Pengunjung</h3>
                                <p class="card-text">Festival budaya tahunan yang menampilkan kesenian tradisional, kuliner khas, dan kerajinan lokal berhasil menarik ratusan pengunjung dari berbagai daerah. Event yang berlangsung selama tiga hari ini menjadi ajang promosi potensi wisata dan budaya Desa Sejahtera.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn btn-primary">Baca selengkapnya</a>
                                    <div>
                                        <span class="me-3"><i class="far fa-eye me-1"></i> 312</span>
                                        <span><i class="far fa-comment me-1"></i> 28</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- News Item 4 -->
            <div class="col-lg-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://source.unsplash.com/random/600x400/?healthcare,rural" class="img-fluid rounded-start h-100 object-fit-cover" alt="Berita 4">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-danger">Kesehatan</span>
                                    <small class="text-muted"><i class="far fa-calendar me-1"></i> 15 Mei 2023</small>
                                </div>
                                <h3 class="card-title fw-bold">Posyandu Desa Dapatkan Bantuan Alat Kesehatan</h3>
                                <p class="card-text">Posyandu Desa Sejahtera menerima bantuan alat kesehatan dari Dinas Kesehatan Kabupaten untuk meningkatkan pelayanan kesehatan di desa. Bantuan ini meliputi timbangan bayi digital, alat ukur tinggi badan, dan perlengkapan medis lainnya.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn btn-primary">Baca selengkapnya</a>
                                    <div>
                                        <span class="me-3"><i class="far fa-eye me-1"></i> 175</span>
                                        <span><i class="far fa-comment me-1"></i> 12</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?> 