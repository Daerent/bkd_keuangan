<!doctype html>
<html lang="en">




<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <base href="http://localhost/bkd_kwitansi/">
 <link rel="stylesheet" href="assets/css/datatable.css">
 
 <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
 
 <link rel="stylesheet" href="assets/css/libs.bundle.css">
 
 <link rel="stylesheet" href="assets/css/theme.bundle.css">
 <link rel="stylesheet" href="assets/css/aos.css">
 
 <title>#template('judul')|BKD KWITANSI</title>

 <script type="text/javascript" src="assets/js/jquery-3.7.0.js"></script>
 <script src="assets/js/select2.js"></script>
 <script type="text/javascript" src="assets/js/swal.js"></script>
 <script type="text/javascript" src="assets/js/m1.js"></script>
 <script type="text/javascript" src="assets/js/c1.js"></script>
 <script type="text/javascript" src="assets/js/datatables.js"></script>
 <script type="text/javascript">
 var dtbl= '';
 </script>
 

</head>
<body class="pt-10">

 
 
 <div class="modal fade" id="modalExample" tabindex="-1" role="dialog" aria-labelledby="modalExampleTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered" role="document">
 <div class="modal-content">
 <div class="modal-body">

 
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

 
 <div class="text-center">
 <img src="assets/img/illustrations/illustration-1.png" alt="..." class="img-fluid mb-3" style="width: 200px;">
 </div>

 
 <h2 class="fw-bold text-center mb-1" id="modalExampleTitle">
 Schedule a demo with us
 </h2>

 
 <p class="fs-lg text-center text-body-secondary mb-6 mb-md-8">
 We can help you solve company communication.
 </p>

 
 <form>
 <div class="row">
 <div class="col-12 col-md-6">

 
 <div class="form-floating">
 <input type="text" class="form-control form-control-flush" id="registrationFirstNameModal" placeholder="First name">
 <label for="registrationFirstNameModal">First name</label>
 </div>

 </div>
 <div class="col-12 col-md-6">

 
 <div class="form-floating">
 <input type="text" class="form-control form-control-flush" id="registrationLastNameModal" placeholder="Last name">
 <label for="registrationLastNameModal">Last name</label>
 </div>

 </div>
 </div>
 <div class="row">
 <div class="col-12 col-md-6">

 
 <div class="form-floating">
 <input type="email" class="form-control form-control-flush" id="registrationEmailModal" placeholder="Email">
 <label for="registrationEmailModal">Email</label>
 </div>

 </div>
 <div class="col-12 col-md-6">

 
 <div class="form-floating">
 <input type="password" class="form-control form-control-flush" id="registrationPasswordModal" placeholder="Password">
 <label for="registrationPasswordModal">Password</label>
 </div>

 </div>
 </div>
 <div class="row">
 <div class="col-12">

 
 <button class="btn w-100 btn-primary mt-3 lift">
 Request a demo
 </button>

 </div>
 </div>
 </form>

 </div>
 </div>
 </div>
 </div>

 
 <div class="modal fade" id="modalSignupHorizontal" tabindex="-1" role="dialog" aria-labelledby="modalSignupHorizontalTitle" aria-hidden="true">
 <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
 <div class="modal-content">
 <div class="card card-row">
 <div class="row gx-0">
 <div class="col-12 col-md-6 bg-cover card-img-start" style="background-image: url(assets/img/photos/photo-8.jpg);">

 
 <img src="assets/img/photos/photo-8.jpg" alt="..." class="img-fluid d-md-none invisible">

 
 <div class="shape shape-end shape-fluid-y text-white d-none d-md-block">
 <svg viewBox="0 0 112 690" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M116 0H51v172C76 384 0 517 0 517v173h116V0z" fill="currentColor"/></svg> </div>

 </div>
 <div class="col-12 col-md-6">
 <div class="card-body">

 
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

 
 <h2 class="mb-0 fw-bold text-center" id="modalSignupHorizontalTitle">
 Sign Up
 </h2>

 
 <p class="mb-6 text-center text-body-secondary">
 Simplify your workflow in minutes.
 </p>

 
 <form class="mb-6">

 
 <div class="form-group">
 <label class="visually-hidden" for="modalSignupHorizontalEmail">
 Your email
 </label>
 <input type="email" class="form-control" id="modalSignupHorizontalEmail" placeholder="Your email">
 </div>

 
 <div class="form-group mb-5">
 <label class="visually-hidden" for="modalSignupHorizontalPassword">
 Create a password
 </label>
 <input type="password" class="form-control" id="modalSignupHorizontalPassword" placeholder="Create a password">
 </div>

 
 <button class="btn w-100 btn-primary" type="submit">
 Sign up
 </button>

 </form>

 
 <p class="mb-0 fs-sm text-center text-body-secondary">
 Already have an account? <a href="signin-illustration.html">Log in</a>.
 </p>

 </div>
 </div>

 </div> 
 </div>
 </div>
 </div>
</div>

 
 <div class="modal fade" id="modalSignupVertical" tabindex="-1" role="dialog" aria-labelledby="modalSignupVerticalTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered" role="document">
 <div class="modal-content">
 <div class="card">

 
 <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>

 
 <img src="assets/img/photos/photo-7.jpg" alt="..." class="card-img-top">

 
 <div class="position-relative">
 <div class="shape shape-bottom shape-fluid-x text-white">
 <svg viewBox="0 0 2880 480" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2160 0C1440 240 720 240 720 240H0v240h2880V0h-720z" fill="currentColor"/></svg> </div>
 </div>

 
 <div class="card-body">

 
 <h2 class="mb-0 fw-bold text-center" id="modalSignupVerticalTitle">
 Sign Up
 </h2>

 
 <p class="mb-6 text-center text-body-secondary">
 Simplify your workflow in minutes.
 </p>

 
 <form class="mb-6">

 
 <div class="form-group">
 <label class="visually-hidden" for="modalSignupVerticalEmail">
 Your email
 </label>
 <input type="email" class="form-control" id="modalSignupVerticalEmail" placeholder="Your email">
 </div>

 
 <div class="form-group mb-5">
 <label class="visually-hidden" for="modalSignupVerticalPassword">
 Create a password
 </label>
 <input type="password" class="form-control" id="modalSignupVerticalPassword" placeholder="Create a password">
 </div>

 
 <button class="btn w-100 btn-primary" type="submit">
 Sign up
 </button>

 </form>

 
 <p class="mb-0 fs-sm text-center text-body-secondary">
 Already have an account? <a href="signin-illustration.html">Log in</a>.
 </p>

 </div>

 </div>
 </div>
 </div>
 </div>

 
 <div class="modal fade" id="modalSigninHorizontal" tabindex="-1" role="dialog" aria-labelledby="modalSigninHorizontalTitle" aria-hidden="true">
 <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
 <div class="modal-content">
 <div class="card card-row">
 <div class="row gx-0">
 <div class="col-12 col-md-6 bg-cover card-img-start" style="background-image: url(assets/img/photos/photo-1.jpg);">

 
 <img src="assets/img/photos/photo-1.jpg" alt="..." class="img-fluid d-md-none invisible">

 
 <div class="shape shape-end shape-fluid-y text-white d-none d-md-block">
 <svg viewBox="0 0 112 690" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M116 0H51v172C76 384 0 517 0 517v173h116V0z" fill="currentColor"/></svg> </div>

 </div>
 <div class="col-12 col-md-6">
 <div class="card-body">

 
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

 
 <h2 class="mb-0 fw-bold text-center" id="modalSigninHorizontalTitle">
 Sign In
 </h2>

 
 <p class="mb-6 text-center text-body-secondary">
 Simplify your workflow in minutes.
 </p>

 
 <form class="mb-6">

 
 <div class="form-group">
 <label class="visually-hidden" for="modalSigninHorizontalEmail">
 Your email
 </label>
 <input type="email" class="form-control" id="modalSigninHorizontalEmail" placeholder="Your email">
 </div>

 
 <div class="form-group mb-5">
 <label class="visually-hidden" for="modalSigninHorizontalPassword">
 Enter your password
 </label>
 <input type="password" class="form-control" id="modalSigninHorizontalPassword" placeholder="Enter your password">
 </div>

 
 <button class="btn w-100 btn-primary" type="submit">
 Sign in
 </button>

 </form>

 
 <p class="mb-0 fs-sm text-center text-body-secondary">
 Don't have an account yet? <a href="signin-illustration.html">Sign up</a>.
 </p>

 </div>
 </div>

 </div> 
 </div>
 </div>
 </div>
 </div>

 
 <div class="modal fade" id="modalSigninVertical" tabindex="-1" role="dialog" aria-labelledby="modalSigninVerticalTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered" role="document">
 <div class="modal-content">
 <div class="card">

 
 <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>

 
 <img src="assets/img/photos/photo-21.jpg" alt="..." class="card-img-top">

 
 <div class="position-relative">
 <div class="shape shape-bottom shape-fluid-x text-white">
 <svg viewBox="0 0 2880 480" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2160 0C1440 240 720 240 720 240H0v240h2880V0h-720z" fill="currentColor"/></svg> </div>
 </div>

 
 <div class="card-body">

 
 <h2 class="mb-0 fw-bold text-center" id="modalSigninVerticalTitle">
 Sign In
 </h2>

 
 <p class="mb-6 text-center text-body-secondary">
 Simplify your workflow in minutes.
 </p>

 
 <form class="mb-6">

 
 <div class="form-group">
 <label class="visually-hidden" for="modalSigninVerticalEmail">
 Your email
 </label>
 <input type="email" class="form-control" id="modalSigninVerticalEmail" placeholder="Your email">
 </div>

 
 <div class="form-group mb-5">
 <label class="visually-hidden" for="modalSigninVerticalPassword">
 Enter your password
 </label>
 <input type="password" class="form-control" id="modalSigninVerticalPassword" placeholder="Enter your password">
 </div>

 
 <button class="btn w-100 btn-primary" type="submit">
 Sign in
 </button>

 </form>

 
 <p class="mb-0 fs-sm text-center text-body-secondary">
 Don't have an account yet? <a href="signin-illustration.html">Sign up</a>.
 </p>

 </div>

 </div>
 </div>
 </div>
 </div>

 
 <div class="modal fade" id="modalPayment" tabindex="-1" role="dialog" aria-labelledby="modalPaymentTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered" role="document">
 <div class="modal-content">
 <div class="modal-body">

 
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

 
 <h2 class="fw-bold text-center mb-1" id="modalPaymentTitle">
 Add Payment
 </h2>

 
 <p class="fs-lg text-center text-body-secondary mb-6 mb-md-8">
 Simplify your workflow in minutes.
 </p>

 
 <form>

 
 <div class="form-group">
 <label class="form-label" for="modalPaymentName">Name on card</label>
 <input class="form-control" id="modalPaymentName" type="text" placeholder="First Last">
 </div>

 
 <div class="form-group">
 <label class="form-label" for="modalPaymentNumbber">Card number</label>
 <input class="form-control" id="modalPaymentNumbber" type="number" placeholder="4242 4242 4242 4242">
 </div>

 
 <div class="form-group">
 <label class="form-label" for="modalPaymentDate">Exp. Date</label>
 <input class="form-control" id="modalPaymentDate" type="text" placeholder="03/2023">
 </div>

 
 <button class="btn w-100 btn-primary mt-3 lift">
 Add Payment Method
 </button>

 </form>

 </div>
 </div>
 </div>
 </div>

 
 <nav class="navbar navbar-expand-lg fixed-top bg-white border-bottom">
 <div class="container-fluid">

 
 <a class="navbar-brand" href="/pptk/dashboard">
 <img src="/assets/img/brand.svg" class="navbar-brand-img" alt="Logo">
 </a>

 
 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
 <span class="navbar-toggler-icon"></span>
 </button>

 
 <div class="collapse navbar-collapse" id="navbarCollapse">

 
 <ul class="navbar-nav ms-auto">

 
 <li class="nav-item">
 <a class="nav-link" href="/pptk/dashboard">
 <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
 <path d="M3 9l9-7 9 7"></path>
 <path d="M9 22V12h6v10"></path>
 </svg>Dashboard
 </a>
 </li>

 
 <li class="nav-item dropdown">
 <a class="nav-link dropdown-toggle" id="navKwitansi" data-bs-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
 <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
 <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
 <polyline points="14 2 14 8 20 8"></polyline>
 <line x1="16" y1="13" x2="8" y2="13"></line>
 <line x1="16" y1="17" x2="8" y2="17"></line>
 </svg> Kwitansi
 </a>
 <div class="dropdown-menu dropdown-menu-lg" aria-labelledby="navKwitansi">
 <div class="row gx-4">
 <div class="col-12 col-lg-6">
 <h6 class="dropdown-header">Buat</h6>
 <a class="dropdown-item disabled"
 href="#">
 Buat Kwitansi
 </a>
 <a class="dropdown-item disabled"
 href="#">
 Buat (3 TTD)
 </a>
 <a class="dropdown-item disabled"
 href="#">
 Buat (4 TTD + Penyedia)
 </a>

 <div class="dropdown-divider"></div>
 <h6 class="dropdown-header">Status Saya</h6>
 <a class="dropdown-item d-flex justify-content-between" href="/pptk/kwitansi?status=draft">
 Draft <span class="badge bg-secondary">0</span>
 </a>
 <a class="dropdown-item d-flex justify-content-between" href="/pptk/kwitansi?status=verifikasi">
 Menunggu Verifikasi <span class="badge bg-warning">0</span>
 </a>
 <a class="dropdown-item d-flex justify-content-between" href="/pptk/kwitansi?status=disetujui">
 Disetujui <span class="badge bg-success">0</span>
 </a>
 <a class="dropdown-item d-flex justify-content-between" href="/pptk/kwitansi?status=ditolak">
 Ditolak <span class="badge bg-danger">0</span>
 </a>
 </div>
 <div class="col-12 col-lg-6">
 <h6 class="dropdown-header">Arsip & Cetak</h6>
 <a class="dropdown-item" href="/pptk/kwitansi/riwayat">Riwayat Kwitansi</a>
 <a class="dropdown-item" href="/pptk/kwitansi/cetak">Cetak Kwitansi</a>

 <div class="dropdown-divider"></div>
 <h6 class="dropdown-header">Penyedia</h6>
 <a class="dropdown-item" href="/pptk/penyedia">Data Penyedia</a>
 <a class="dropdown-item" href="/pptk/penyedia/tambah">Tambah Penyedia</a>
 </div>
 </div>
 </div>
 </li>

 
 <li class="nav-item dropdown">
 <a class="nav-link dropdown-toggle" id="navKegiatan" data-bs-toggle="dropdown" href="#">
 <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
 <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
 <polyline points="2 17 12 22 22 17"></polyline>
 <polyline points="2 12 12 17 22 12"></polyline>
 </svg>Kegiatan
 </a>
 <div class="dropdown-menu dropdown-menu-md">
 <a class="dropdown-item" href="/pptk/kegiatan">Kegiatan Saya</a>
 <a class="dropdown-item" href="/pptk/sub-kegiatan">Sub Kegiatan & Belanja</a>
 <div class="dropdown-divider"></div>
 <a class="dropdown-item" href="/pptk/rekening">Rekening</a>
 </div>
 </li>

 
 <li class="nav-item dropdown">
 <a class="nav-link dropdown-toggle" id="navChecklist" data-bs-toggle="dropdown" href="#">
 <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
 <polyline points="9 11 12 14 22 4"></polyline>
 <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
 </svg>Checklist
 </a>
 <div class="dropdown-menu dropdown-menu-md">
 <a class="dropdown-item" href="/pptk/checklist/template">Template per Rekening</a>
 <a class="dropdown-item" href="/pptk/checklist/pemenuhan">Pemenuhan per Kwitansi</a>
 </div>
 </li>

 
 <li class="nav-item dropdown">
 <a class="nav-link dropdown-toggle" id="navDok" data-bs-toggle="dropdown" href="#">
 <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
 <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
 </svg>
 Dokumentasi
 </a>
 <div class="dropdown-menu dropdown-menu-md">
 <a class="dropdown-item" href="/pptk/dokumentasi/upload">Upload Bukti</a>
 <a class="dropdown-item" href="/pptk/dokumentasi">File Saya</a>
 </div>
 </li>

 
 <li class="nav-item dropdown">
 <a class="nav-link dropdown-toggle" id="navAccount" data-bs-toggle="dropdown" href="#">
 <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
 <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
 <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
 <circle cx="12" cy="7" r="4"></circle>
 </svg>
 Akun
 </a>
 <ul class="dropdown-menu">
 <li><a class="dropdown-item" href="/account/profile">Profil</a></li>
 <li><a class="dropdown-item" href="/account/security">Keamanan</a></li>
 <li><hr class="dropdown-divider"></li>
 <li><a class="dropdown-item" href="/auth/logout">Keluar</a></li>
 </ul>
 </li>

 </ul>
 </div>
 </div>
 </nav>

 
 <nav class="navbar navbar-light bg-white border-bottom fixed-top d-lg-none">
 <div class="container-fluid">
 
 <a class="navbar-brand" href="/pptk/dashboard">
 <img src="/assets/img/brand.svg" alt="Logo" height="32">
 </a>

 
 <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
 <line x1="3" y1="12" x2="21" y2="12"></line>
 <line x1="3" y1="6" x2="21" y2="6"></line>
 <line x1="3" y1="18" x2="21" y2="18"></line>
 </svg>
 </button>
 </div>
 </nav>
 
 <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
 <div class="offcanvas-header">
 <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
 <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
 </div>
 <div class="offcanvas-body">
 <ul class="list-group list-group-flush">

 
 <li class="list-group-item">
 <a class="nav-link" href="/pptk/dashboard">🏠 Dashboard</a>
 </li>

 
 <li class="list-group-item">
 <details>
 <summary>🧾 Kwitansi</summary>
 <ul class="list-unstyled ms-3">
 <li><a href="#">Buat Kwitansi</a></li>
 <li><a href="#">Buat (3 TTD)</a></li>
 <li><a href="#">Buat (4 TTD + Penyedia)</a></li>
 <li class="mt-2"><strong>Status Saya</strong></li>
 <li><a href="/pptk/kwitansi?status=draft">Draft (0)</a></li>
 <li><a href="/pptk/kwitansi?status=verifikasi">Menunggu Verifikasi (0)</a></li>
 <li><a href="/pptk/kwitansi?status=disetujui">Disetujui (0)</a></li>
 <li><a href="/pptk/kwitansi?status=ditolak">Ditolak (0)</a></li>
 <li class="mt-2"><strong>Arsip & Cetak</strong></li>
 <li><a href="/pptk/kwitansi/riwayat">Riwayat Kwitansi</a></li>
 <li><a href="/pptk/kwitansi/cetak">Cetak Kwitansi</a></li>
 <li class="mt-2"><strong>Penyedia</strong></li>
 <li><a href="/pptk/penyedia">Data Penyedia</a></li>
 <li><a href="/pptk/penyedia/tambah">Tambah Penyedia</a></li>
 </ul>
 </details>
 </li>

 
 <li class="list-group-item">
 <details>
 <summary>📂 Kegiatan</summary>
 <ul class="list-unstyled ms-3">
 <li><a href="/pptk/kegiatan">Kegiatan Saya</a></li>
 <li><a href="/pptk/sub-kegiatan">Sub Kegiatan & Belanja</a></li>
 <li><a href="/pptk/rekening">Rekening</a></li>
 </ul>
 </details>
 </li>

 
 <li class="list-group-item">
 <details>
 <summary>✅ Checklist</summary>
 <ul class="list-unstyled ms-3">
 <li><a href="/pptk/checklist/template">Template per Rekening</a></li>
 <li><a href="/pptk/checklist/pemenuhan">Pemenuhan per Kwitansi</a></li>
 </ul>
 </details>
 </li>

 
 <li class="list-group-item">
 <details>
 <summary>📁 Dokumentasi</summary>
 <ul class="list-unstyled ms-3">
 <li><a href="/pptk/dokumentasi/upload">Upload Bukti</a></li>
 <li><a href="/pptk/dokumentasi">File Saya</a></li>
 </ul>
 </details>
 </li>

 
 <li class="list-group-item">
 <details>
 <summary>👤 Akun</summary>
 <ul class="list-unstyled ms-3">
 <li><a href="/account/profile">Profil</a></li>
 <li><a href="/account/security">Keamanan</a></li>
 <li><a href="/auth/logout">Keluar</a></li>
 </ul>
 </details>
 </li>
 </ul>
 </div>
 </div>
 

<main>
 
 <section class="pt-8 pb-11 pt-md-9 pb-md-8 py-lg-10 bg-light bg-between" style="background-image: url(assets/img/illustrations/illustration-6-cropped.png), url(assets/img/illustrations/illustration-4-cropped.png);">
 <div class="container">
 <div class="row justify-content-center">
 <div class="col-12 text-center" data-aos="fade-up" >
 #template('content')
 </div>
 </div> 
 </div> 
 </section>
</main>
 
 <footer class="py-8 py-md-11 bg-white">
 <div class="container">
 <div class="row">
 <div class="col-12 col-md-4 col-lg-3">

 
 <img src="assets/img/brand.svg" alt="..." class="footer-brand img-fluid mb-2">

 
 <p class="text-gray-700 mb-2">
 A better way to build.
 </p>

 
 <ul class="list-unstyled list-inline list-social mb-6 mb-md-0">
 <li class="list-inline-item list-social-item me-3">
 <a href="#!" class="text-decoration-none">
 <img src="assets/img/icons/social/instagram.svg" class="list-social-icon" alt="...">
 </a>
 </li>
 <li class="list-inline-item list-social-item me-3">
 <a href="#!" class="text-decoration-none">
 <img src="assets/img/icons/social/facebook.svg" class="list-social-icon" alt="...">
 </a>
 </li>
 <li class="list-inline-item list-social-item me-3">
 <a href="#!" class="text-decoration-none">
 <img src="assets/img/icons/social/twitter.svg" class="list-social-icon" alt="...">
 </a>
 </li>
 <li class="list-inline-item list-social-item">
 <a href="#!" class="text-decoration-none">
 <img src="assets/img/icons/social/pinterest.svg" class="list-social-icon" alt="...">
 </a>
 </li>
 </ul>

 </div>
 <div class="col-6 col-md-4 col-lg-2">

 
 <h6 class="fw-bold text-uppercase text-gray-700">
 Products
 </h6>

 
 <ul class="list-unstyled text-body-secondary mb-6 mb-md-8 mb-lg-0">
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Page Builder
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 UI Kit
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Styleguide
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Documentation
 </a>
 </li>
 <li>
 <a href="#!" class="text-reset">
 Changelog
 </a>
 </li>
 </ul>

 </div>
 <div class="col-6 col-md-4 col-lg-2">

 
 <h6 class="fw-bold text-uppercase text-gray-700">
 Services
 </h6>

 
 <ul class="list-unstyled text-body-secondary mb-6 mb-md-8 mb-lg-0">
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Documentation
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Changelog
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Pagebuilder
 </a>
 </li>
 <li>
 <a href="#!" class="text-reset">
 UI Kit
 </a>
 </li>
 </ul>

 </div>
 <div class="col-6 col-md-4 offset-md-4 col-lg-2 offset-lg-0">

 
 <h6 class="fw-bold text-uppercase text-gray-700">
 Connect
 </h6>

 
 <ul class="list-unstyled text-body-secondary mb-0">
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Page Builder
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 UI Kit
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Styleguide
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Documentation
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Changelog
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Documentation
 </a>
 </li>
 <li>
 <a href="#!" class="text-reset">
 Changelog
 </a>
 </li>
 </ul>

 </div>
 <div class="col-6 col-md-4 col-lg-2">

 
 <h6 class="fw-bold text-uppercase text-gray-700">
 Legal
 </h6>

 
 <ul class="list-unstyled text-body-secondary mb-0">
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Documentation
 </a>
 </li>
 <li class="mb-3">
 <a href="#!" class="text-reset">
 Changelog
 </a>
 </li>
 <li>
 <a href="#!" class="text-reset">
 Pagebuilder
 </a>
 </li>
 </ul>

 </div>
 </div> 
 </div> 
 </footer>

 

 
 <script src="assets/js/vendor.bundle.js"></script>

 
 <script src="assets/js/theme.bundle.js"></script>

 <script src="assets/js/aos.js"></script>



 <script>
 feather.replace();
 </script>
 <!-- <script>
 $(document).ready(function(){

 // Load Data User
 function loadUser(){
 $.post('assets/php/classes/admin/master.class.php', {a:'user_data', c:0, e:50}, function(res){
 if(res.h){
 let rows = '';
 res.i.forEach(function(item){
 rows += '<tr>'+
 '<td>'+item[0]+'</td>'+
 '<td>'+item[1]+'</td>'+
 '<td>'+item[2]+'</td>'+
 '<td>'+item[3]+'</td>'+
 '<td>'+item[4]+'</td>'+
 '</tr>';
 });
 $('#tabelUser tbody').html(rows);
 } else {
 $('#tabelUser tbody').html('<tr><td colspan="5">Tidak ada data</td></tr>');
 }
 },'json');
 }
 loadUser(); // pertama kali load

 // Submit Form (Create / Update)
 $('#formUser').submit(function(e){
 e.preventDefault();
 let formData = $(this).serializeArray();
 let aksi = $('#id').val() ? 'user_ubah' : 'user_simpan'; // kalau ada id = update

 formData.push({name:'a', value:aksi});

 $.post('controller.php', formData, function(res){
 alert(res.t);
 if(res.h){
 $('#formUser')[0].reset();
 $('#id').val('');
 loadUser();
 }
 },'json');
 });

 // Event Delegation tombol Edit & Hapus
 $('#tabelUser').on('click','button',function(){
 let apa = $(this).attr('apa');
 let ini = $(this).attr('ini');

 if(ini == 'ubah'){
 // ambil data row dari server
 $.post('controller.php',{a:'user_data',id:apa},function(res){
 if(res.h){
 let d = res.i[0]; // asumsi return single row
 $('#id').val(apa);
 $('#nama').val(d.nama);
 $('#nip').val(d.nip);
 $('#jabatan').val(d.jabatan);
 $('#role').val(d.role);
 }
 },'json');
 }

 if(ini == 'hapus'){
 if(confirm("Yakin hapus user ini?")){
 $.post('controller.php',{a:'user_hapus',id:apa},function(res){
 alert(res.t);
 if(res.h) loadUser();
 },'json');
 }
 }
 });

 });
 </script> -->
<script>
 AOS.init();
</script>
</body>


</html>
