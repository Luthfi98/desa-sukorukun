<?php 
    $logo = get_setting('website', 'logo', false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Layanan Surat Menyurat Desa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .forgot-container {
            margin-top: 5%;
            max-width: 400px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 100px;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            width: 100%;
            padding: 10px;
        }
        .form-control {
            padding: 10px;
            height: auto;
        }
        .forgot-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .footer-links {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="forgot-container">
                    <div class="logo">
                        <img src="<?= base_url($logo) ?>" alt="Logo Desa" >
                    </div>
                    <h3 class="forgot-title">Lupa Password</h3>
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('auth/send-reset-link') ?>" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Kirim Link Reset Password</button>
                        </div>
                    </form>
                    
                    <div class="footer-links">
                        <p><a href="<?= base_url('auth/login') ?>">Kembali ke Halaman Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 