<?php 
$namaDesa = get_setting('informasi_desa','nama_desa', false);
$logo = get_setting('website','logo', false); 
$phone = get_setting('kontak','telepon_desa', false);
$adress = get_setting('kontak','alamat_desa', false);
$email = get_setting('kontak','email_desa', false);
$jamKerja = get_setting('layanan','jam_layanan', false);
$visi = get_setting('informasi_desa','visi_desa', false);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $namaDesa.' - '. $title ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
       <!-- jQuery -->
       <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Popper.js -->
   
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #333;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--primary-color) !important;
            padding: 15px 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-brand img {
            max-height: 50px;
            margin-right: 10px;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            padding: 8px 15px !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }

        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://source.unsplash.com/random/1920x1080/?village,rural');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .feature-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            text-align: center;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .feature-box i {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        .section-padding {
            padding: 100px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .section-title p {
            color: #666;
            font-size: 1.1rem;
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 60px 0 20px;
        }

        .footer-widget h5 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .footer-links {
            list-style: none;
            padding-left: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background: rgba(255,255,255,0.1);
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }

        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 6px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn {
            border-radius: 4px;
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 0.8rem;
        }

        .btn-lg {
            padding: 8px 20px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="<?= base_url($logo) ?>" alt="<?= $namaDesa ?>">
                <?= $namaDesa ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('/') ? 'active' : '' ?>" href="<?= base_url('/') ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('profil') ? 'active' : '' ?>" href="<?= base_url('profil') ?>">Profil Desa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('berita') ? 'active' : '' ?>" href="<?= base_url('berita') ?>">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('layanan') ? 'active' : '' ?>" href="<?= base_url('layanan') ?>">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('kontak') ? 'active' : '' ?>" href="<?= base_url('kontak') ?>">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('auth/login') ? 'active' : '' ?>" href="<?= base_url('auth/login') ?>">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <?= $this->renderSection('content') ?>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h5>Tentang Desa</h5>
                        <p><?= $visi ?></p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h5>Link Terkait</h5>
                        <ul class="footer-links">
                            <li><a href="<?= base_url('profil') ?>">Profil</a></li>
                            <li><a href="<?= base_url('berita') ?>">Berita & Informasi Desa</a></li>
                            <li><a href="<?= base_url('layanan') ?>">Layanan</a></li>
                            <li><a href="<?= base_url('kontak') ?>">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h5>Kontak Kami</h5>
                        <ul class="footer-links">
                            <li><i class="fas fa-map-marker-alt me-2"></i> <?= $adress ?></li>
                            <li><i class="fas fa-phone me-2"></i> <?= $phone ?></li>
                            <li><i class="fas fa-envelope me-2"></i> <?= $email ?></li>
                            <li><i class="fas fa-clock me-2"></i> <?= $jamKerja ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= $namaDesa ?>. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button
            $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...');
            
            // Get form data
            var formData = $(this).serialize();
            
            // Send AJAX request
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        
                        // Reset form
                        $('#contactForm')[0].reset();
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.'
                    });
                },
                complete: function() {
                    // Re-enable submit button
                    $('#submitBtn').prop('disabled', false).html('Kirim Pesan');
                }
            });
        });
    });
</script>
    
    
    
</body>
</html> 