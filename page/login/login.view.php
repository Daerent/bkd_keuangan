<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= BASEURL ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">

    <!-- Libs CSS -->
    <link rel="stylesheet" href="assets/css/libs.bundle.css">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="assets/css/theme.bundle.css">

    <!-- AOS Animation -->
    <link rel="stylesheet" href="assets/css/aos.css">

    <link rel="stylesheet" href="assets/css/login.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Title -->
    <title>Login - Sistem Kwitansi BKD</title>


</head>
<body>
    <div class="login-container">
        <!-- Floating Background Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="container d-flex flex-column min-vh-100">
            <div class="row align-items-center justify-content-center gx-0 flex-grow-1">
                <div class="col-12 col-md-8 col-lg-6 col-xl-7" data-aos="zoom-in" data-aos-duration="1000">
                    <!-- Login Card -->
                    <div class="login-card">
                        <!-- Header -->
                        <div class="login-header text-center text-white">
                            <div class="mb-4">
                                <img src="<?= BASEURL ?>assets/img/KWITANSI_BKD.png" alt="BKD Logo" class="brand" height="140">
                            </div>
                            <h2 class="fw-bold mb-2">Selamat Datang</h2>
                            <p class="mb-0 opacity-75">Sistem Manajemen Kwitansi Digital</p>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-5">
                            <!-- Login Form -->
                            <form id="loginForm" action="processing/masuk" method="post">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                                                                <!-- NIP Input -->
                                <div class="mb-4" data-aos="fade-up" data-aos-delay="100">
                                    <label for="username" class="form-label fw-semibold text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        NIP
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="username"
                                           name="username"
                                           placeholder="Masukkan NIP Anda"
                                           required
                                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                           autocomplete="username">
                                </div>

                                <!-- Password Input -->
                                <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
                                    <label for="password" class="form-label fw-semibold text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        Password
                                    </label>
                                    <div class="position-relative">
                                        <input type="password"
                                               class="form-control"
                                               id="password"
                                               name="password"
                                               placeholder="Masukkan password Anda"
                                               required
                                               autocomplete="current-password">
                                        <button type="button" class="password-toggle" id="togglePassword">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="mb-4" data-aos="fade-up" data-aos-delay="300">
                                    <button type="submit" class="btn btn-login text-white w-100" id="loginButton">
                                        <span id="loginText">Masuk ke Sistem</span>
                                        <div class="loading-spinner" id="loginSpinner"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-4 aos-init d-flex flex-column aos-animate" data-aos="fade-up" data-aos-delay="600">
                        <small class="text-white opacity-75">
                            &copy; <?= date('Y') ?> Badan Kepegawaian Daerah. All rights reserved.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/vendor.bundle.js"></script>
    <script src="assets/js/theme.bundle.js"></script>
    <script src="assets/js/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            const icon = this.querySelector('svg');
            if (type === 'password') {
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            } else {
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            }
        });
    }

    // Form submission handling - FIXED VERSION
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const loginButton = document.getElementById('loginButton');
        const loginText = document.getElementById('loginText');
        const loginSpinner = document.getElementById('loginSpinner');
        const formData = new FormData(this);

        // Show loading state
        loginText.textContent = 'Memproses...';
        loginSpinner.style.display = 'inline-block';
        loginButton.disabled = true;

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Parse JSON response
            const result = await response.json();

            if (result.status === 'success') {
                // Login successful - redirect
                window.location.href = result.redirect;
            } else {
                // Login failed - show error
                showLoginError(result.message || 'Login gagal');

                // Reset button state
                loginText.textContent = 'Masuk ke Sistem';
                loginSpinner.style.display = 'none';
                loginButton.disabled = false;
            }

        } catch (error) {
            console.error('Login error:', error);
            showLoginError('Terjadi kesalahan koneksi. Silakan coba lagi.');

            // Reset button state on error
            loginText.textContent = 'Masuk ke Sistem';
            loginSpinner.style.display = 'none';
            loginButton.disabled = false;
        }
    });

    // Function to show SweetAlert error
    function showLoginError(message) {
        const nipMessage = message.replace('Username', 'NIP');
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: nipMessage,
            confirmButtonText: 'Coba Lagi',
            confirmButtonColor: '#667eea',
            backdrop: 'rgba(0,0,0,0.4)',
            customClass: {
                popup: 'sweet-alert-popup'
            }
        }).then((result) => {
            // Focus kembali ke field yang sesuai
            if (message.includes('username') || message.includes('NIP')) {
                document.getElementById('username').focus();
            } else if (message.includes('password')) {
                document.getElementById('password').focus();
            } else {
                document.getElementById('username').focus();
            }
        });
    }

    // Auto-focus on username field
    document.getElementById('username').focus();

    // Check if there's an error message from PHP session
    <?php if (!empty($data['error_message'])): ?>
        showLoginError('<?= addslashes($data['error_message']) ?>');
    <?php endif; ?>

    // Add input validation styling
    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('invalid', function() {
            this.style.borderColor = '#dc3545';
        });

        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.style.borderColor = '#28a745';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });
    });
});

// Initialize AOS
AOS.init({
    duration: 800,
    once: true
});
    </script>

    <style>
        .sweet-alert-popup {
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .swal2-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .swal2-confirm {
            border-radius: 10px;
            padding: 10px 25px;
        }
    </style>
</body>
</html>