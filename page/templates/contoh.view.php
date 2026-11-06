<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

if (isset($data)) {
    // proses untuk data yang dikirim ke template ini
}
$current_page = basename($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="color-scheme" content="dark light">
    <base href="<?=BASEURL?>">
    <meta name="identifier-url" content="<?=BASEURL?>">
    <meta name="url" content="<?=BASEURL?>">
    <title>#template('judul') | BKD Inventaris</title>
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/utility.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome.6.5.1.min.css">
    <link rel="stylesheet" href="assets/bootstrap-icons@1.10.2/bootstrap-icons.css">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f=satoshi@900,700,500,300,401,400&amp;display=swap">
    <link rel="stylesheet" href="assets/css/datatable.css">
    <script src="assets/js/sweetalert.all.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-3.7.0.js"></script>
    <script src="assets/js/script.outbound-links.js"></script>
    <script src="assets/js/select2.js"></script>
    <script type="text/javascript" src="assets/js/m1.js"></script>
    <script type="text/javascript" src="assets/js/c1.js"></script>
    <script type="text/javascript">
        var dtbl = ''; // Variabel global untuk DataTables
    </script>
    <script type="text/javascript" src="assets/js/datatables.js"></script>
</head>

<body class="bg-body-tertiary">

    <div class="d-flex flex-column flex-lg-row h-lg-100 min-vh-100">
        <nav class="flex-none navbar navbar-vertical navbar-expand-lg navbar-light vh-lg-100 px-0 py-2 bg-transparent sticky-lg-top" id="sidebar">
            <div class="container-fluid px-3 px-md-4 px-lg-6">
                <button class="navbar-toggler ms-n2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a class="navbar-brand d-inline-block py-lg-1 mb-lg-5" href="admin/dashboard">
                    <img src="assets/img/logos/logo-dark.svg" class="logo-dark h-rem-8 h-rem-md-10" alt="Logo Dark">
                    <img src="assets/img/logos/logo-light.svg" class="logo-light h-rem-8 h-rem-md-10" alt="Logo Light">
                </a>

                <div class="collapse navbar-collapse overflow-x-hidden" id="sidebarCollapse">
                    <ul class="navbar-nav w-100 flex-column">
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex align-items-center rounded-pill py-2 px-3 bg-light" href="admin/dashboard">
                                <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
                                <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
                            </a>
                        </li>
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex align-items-center rounded-pill py-2 px-3" href="<?=BASEURL?>admin/barang">
                                <i class="bi bi-box-seam me-2"></i> <span>Barang</span>
                                <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
                            </a>
                        </li>
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex align-items-center rounded-pill py-2 px-3" href="<?=BASEURL?>admin/peminjaman">
                                <i class="bi bi-arrow-left-right me-2"></i> <span>Peminjaman</span>
                                <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
                            </a>
                        </li>
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex align-items-center rounded-pill py-2 px-3 " href="<?=BASEURL?>admin/user">
                                <i class="bi bi-people-fill me-2"></i> <span>User</span>
                                <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
                            </a>
                        </li>

                        <li class="nav-item my-1">
                            <a class="nav-link d-flex align-items-center rounded-pill py-2 px-3" href="<?=BASEURL?>admin/history">
                                <i class="bi bi-clock-history me-2"></i> <span>History</span>
                                <span class="badge badge-sm rounded-pill me-n2 bg-success-subtle text-success ms-auto"></span>
                            </a>
                        </li>
                    </ul>
                    <div class="mt-auto"></div>
                    <ul class="navbar-nav">
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex align-items-center rounded-pill py-2 px-3 text-danger" href="<?=BASEURL?>">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="flex-lg-fill overflow-x-hidden ps-lg-1 vstack vh-lg-100 position-relative">
            <nav class="navbar navbar-expand-lg navbar-light bg-body rounded-bottom-4 rounded-bottom-start-lg-0 rounded-bottom-end-lg-0 shadow-sm px-4 py-3 d-flex justify-content-between align-items-center">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link" href="#" id="themeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-half-stroke"></i> </a>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light">Light</button>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">Dark</button>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto">System</button>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center ps-0 pe-2" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?=BASEURL?>" class="rounded-circle me-2" alt="User Avatar"> <span class="d-none d-md-inline-block text-truncate">John Doe</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                            <li><h6 class="dropdown-header">Pengaturan Akun</h6></li>
                            <li><a class="dropdown-item" href="<?=BASEURL?>">Profil Saya</a></li>
                            <li><a class="dropdown-item" href="<?=BASEURL?>">Pengaturan</a></li>
                            </ul>
                    </li>
                </ul>
            </nav>

            <main class="flex-fill overflow-y-lg-auto scrollbar bg-body-tertiary rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2 p-3 p-lg-6 p-xxl-8 mt-3" >
                #template('content')
            </main>
        </div>
    </div>

    <script src="assets/choices.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/switcher.js"></script>
</body>

</html>