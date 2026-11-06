<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

// Security: Validate BASEURL
$baseUrl = filter_var(BASEURL, FILTER_SANITIZE_URL);
$safeUsername = htmlspecialchars($_SESSION['username'] ?? 'User', ENT_QUOTES, 'UTF-8');

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="<?= $baseUrl ?>">

    <!-- Preload critical resources -->
    <link rel="preload" href="assets/css/theme.bundle.css" as="style">
    <link rel="preload" href="assets/js/vendor.bundle.js" as="script">

    <link rel="stylesheet" href="assets/css/datatable.css">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/libs.bundle.css">
    <link rel="stylesheet" href="assets/css/theme.bundle.css">
    <link rel="stylesheet" href="assets/css/aos.css">
    <link rel="stylesheet" href="assets/css/template_user.css">
    <title>#template('judul')|BKD KWITANSI - PPTK</title>

    <script type="text/javascript" src="assets/js/jquery-3.7.0.js"></script>
    <script src="assets/js/select2.js"></script>
    <script type="text/javascript">
        var dtbl= '';
    </script>

</head>
<body class="pt-10">

<!-- NAVBAR USER - Desktop -->
<nav class="navbar navbar-expand-lg fixed-top bg-white border-bottom d-none d-lg-flex">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $baseUrl ?>user/home">
            <img src="<?= $baseUrl ?>assets/img/KWITANSI_BKD.png" class="navbar-brand-img  responsive-large" alt="Logo" >
            <span>Kwitansi</span>
            <span class="badge bg-primary ms-2 fs-6">PPTK</span>
        </a>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ms-auto">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>user/home" aria-label="Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7"></path>
                            <path d="M9 22V12h6v10"></path>
                        </svg>
                        <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </li>

                <!-- Input Kwitansi -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="<?= $baseUrl ?>user/kwitansi" aria-label="Input Kwitansi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span class="d-none d-sm-inline">Input Kwitansi</span>
                        <?php if(($data['active_kwitansi_count'] ?? 0) > 0): ?>
                            <span class="badge bg-warning position-absolute top-0 start-100 translate-middle">
                                <?= (int)$data['active_kwitansi_count'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- Riwayat -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>user/riwayat" aria-label="Riwayat Kwitansi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="d-none d-sm-inline">Riwayat</span>
                    </a>
                </li>

                <!-- Account Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" id="navAccount" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span class="d-none d-sm-inline">Settings</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-header text-muted small">
                                Login sebagai: <strong><?= $safeUsername ?></strong>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?= $baseUrl ?>user/settings">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                                Pengaturan
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= $baseUrl ?>processing/keluar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Keluar
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- NAVBAR USER - Mobile -->
<nav class="navbar navbar-light bg-white border-bottom fixed-top d-lg-none">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $baseUrl ?>user/home">
            <img src="<?= $baseUrl ?>assets/img/KWITANSI_BKD.png" alt="Logo BKD" loading="lazy" class="navbar-brand-img  responsive-large">
            <span>Kwitansi</span>
            <span class="badge bg-primary ms-1 fs-7">PPTK</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#userMobileMenu" aria-controls="userMobileMenu" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- MOBILE MENU USER -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="userMobileMenu" aria-labelledby="userMobileMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="userMobileMenuLabel">
            <img src="<?= $baseUrl ?>assets/img/KWITANSI_BKD.png" alt="Logo" height="32" class="me-2" loading="lazy">
            Menu PPTK
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
            <!-- Dashboard -->
            <a href="<?= $baseUrl ?>user/home" class="list-group-item list-group-item-action border-0 py-3">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-3" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7"></path>
                        <path d="M9 22V12h6v10"></path>
                    </svg>
                    <span class="fs-6">Dashboard</span>
                </div>
            </a>

            <!-- Input Kwitansi -->
            <a href="<?= $baseUrl ?>user/kwitansi" class="list-group-item list-group-item-action border-0 py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-3" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span class="fs-6">Input Kwitansi</span>
                    </div>
                    <?php if(($data['active_kwitansi_count'] ?? 0) > 0): ?>
                        <span class="badge bg-warning fs-7"><?= (int)$data['active_kwitansi_count'] ?></span>
                    <?php endif; ?>
                </div>
            </a>

            <!-- Riwayat -->
            <a href="<?= $baseUrl ?>user/riwayat" class="list-group-item list-group-item-action border-0 py-3">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-3" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span class="fs-6">Riwayat</span>
                </div>
            </a>

            <!-- Cetak PDF -->
            <a href="<?= $baseUrl ?>user/cetak" class="list-group-item list-group-item-action border-0 py-3">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-3" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span class="fs-6">Cetak PDF</span>
                </div>
            </a>

            <!-- Account -->
            <div class="list-group-item border-0 p-0">
                <a class="list-group-item list-group-item-action border-0 py-3" data-bs-toggle="collapse" href="#collapseUserAccount" role="button" aria-expanded="false">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-3" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="fs-6">Akun</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>
                    </div>
                </a>
                <div class="collapse" id="collapseUserAccount">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 small text-muted py-2">
                            <small>Login sebagai: <strong><?= $safeUsername ?></strong></small>
                        </div>
                        <a href="<?= $baseUrl ?>user/settings" class="list-group-item list-group-item-action border-0 py-2 small">Pengaturan</a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= $baseUrl ?>processing/keluar" class="list-group-item list-group-item-action border-0 py-2 small text-danger">Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="min-vh-100">
    <!-- Floating Background Shapes for User Template -->
    <div class="floating-shapes-user">
        <div class="shape-user shape-user-1"></div>
        <div class="shape-user shape-user-2"></div>
        <div class="shape-user shape-user-3"></div>
        <div class="shape-user shape-user-4"></div>
        <div class="shape-user shape-user-5"></div>
        <div class="shape-user shape-user-6"></div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="pt-8 pb-11 pt-md-9 pb-md-8 py-lg-10 bg-light bg-between position-relative">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-12" data-aos="fade-up">
                    #template('content')
                </div>
            </div>
        </div>

    </section>

</main>

<!-- FOOTER -->
<footer class="py-8 py-md-11 bg-dark position-relative">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 col-lg-4">

                <!-- Brand -->
                <img src="assets/img/KWITANSI_BKD.png" alt="BKD Logo" class="img-fluid mb-3 footer" >

                <!-- Text -->
                <p class="text-light mb-3">
                    Sistem Kwitansi Digital - Badan Kepegawaian Daerah
                </p>
                <p class="text-gray-400 small mb-3">
                    Platform digital untuk pengelolaan kwitansi dan verifikasi dokumen keuangan daerah secara efisien dan ter-Digitalisasi.
                </p>

                <!-- Contact Info -->
                <div class="text-gray-400 small mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt me-2" viewBox="0 0 16 16">
                            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                        Jl. Sam Ratulangi, Kec. Palu Tim., Kota Palu
                    </div>

                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope me-2" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                        </svg>
                        bkd@domain.go.id
                    </div>
                </div>

                <!-- Social -->


            </div>
            <div class="col-6 col-md-4 col-lg-2">

                <!-- Heading -->
                <h6 class="fw-bold text-uppercase text-light mb-3">
                    Menu Utama
                </h6>

                <!-- List -->
                <ul class="list-unstyled text-gray-400 mb-6 mb-md-8 mb-lg-0">
                    <li class="mb-2">
                        <a href="<?= BASEURL ?>/admin/dashboard" class="text-reset text-decoration-none small">
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASEURL ?>/admin/master2" class="text-reset text-decoration-none small">
                            Master Data
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASEURL ?>/admin/verifikasi" class="text-reset text-decoration-none small">
                            Verifikasi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASEURL ?>/admin/penugasan" class="text-reset text-decoration-none small">
                            Penugasan
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASEURL ?>/admin/kwitansi" class="text-reset text-decoration-none small">
                            Kwitansi
                        </a>
                    </li>
                </ul>

            </div>

            <div class="col-12 col-md-6 col-lg-3">

                <!-- Heading -->
                <h6 class="fw-bold text-uppercase text-light mb-3">
                    Jam Operasional
                </h6>

                <!-- Operating Hours -->
                <div class="text-gray-400 small mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Senin - Kamis</span>
                        <span>08:00 - 16:00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Jumat</span>
                        <span>08:00 - 16:30</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sabtu - Minggu</span>
                        <span>Libur</span>
                    </div>
                </div>

                <!-- Quick Stats -->


            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="row align-items-center mt-8 pt-6 border-top border-gray-700">
            <div >
                <p class="text-gray-400 small mb-3 mb-md-0">
                    &copy; 2025 <span class="text-light">Badan Kepegawaian Daerah</span>. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- JAVASCRIPT -->
<script src="assets/js/vendor.bundle.js"></script>
<script src="assets/js/theme.bundle.js"></script>
<script src="assets/js/aos.js"></script>

<script>
  // Enhanced mobile menu handling
  document.addEventListener('DOMContentLoaded', function() {
      AOS.init();

      // Auto-close mobile menu when clicking on links
      var mobileMenu = document.getElementById('userMobileMenu');
      if (mobileMenu) {
          var links = mobileMenu.querySelectorAll('a');
          links.forEach(function(link) {
              link.addEventListener('click', function(e) {
                  // Don't close for dropdown toggles
                  if (this.getAttribute('data-bs-toggle') === 'collapse') {
                      return;
                  }
                  var bsOffcanvas = bootstrap.Offcanvas.getInstance(mobileMenu);
                  if (bsOffcanvas) {
                      bsOffcanvas.hide();
                  }
              });
          });
      }

      // Enhance touch experience
      if ('ontouchstart' in window) {
          document.body.classList.add('touch-device');
      }
  });

  // Prevent zoom on double-tap (iOS)
  document.addEventListener('touchstart', function(e) {
      if (e.touches.length > 1) {
          e.preventDefault();
      }
  }, { passive: false });
</script>
</body>
</html>