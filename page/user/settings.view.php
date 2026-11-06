
<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Settings Profil - PPTK')
<div class="container-fluid mt-4">

  <!-- Alert Container -->
  <div id="alertContainer"></div>

  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card bg-primary text-white" data-aos="fade-down">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h3 class="card-title mb-2">Pengaturan Profil 👤</h3>
              <p class="card-text mb-0 opacity-75">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
              <div class="display-6 opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left Column - Profile Information -->
    <div class="col-xl-8">
      <!-- Profile Form -->
      <div class="card border-0 shadow-sm" data-aos="fade-up">
        <div class="card-header bg-transparent border-0 pb-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Informasi Profil
          </h5>
        </div>
        <div class="card-body">
          <form id="profileForm">
            <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Username</label>
                  <input type="text" class="form-control" value="<?= htmlspecialchars($data['pptk_data']['username']) ?>" readonly style="background-color: #f8f9fa;">
                  <div class="form-text">Username tidak dapat diubah</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Role</label>
                  <input type="text" class="form-control" value="PPTK" readonly style="background-color: #f8f9fa;">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama_pegawai"
                         value="<?= htmlspecialchars($data['pptk_data']['nama_pegawai']) ?>"
                         placeholder="Masukkan nama lengkap" required>
                  <div class="invalid-feedback">Nama lengkap harus diisi</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">NIP <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nip"
                         value="<?= htmlspecialchars($data['pptk_data']['nip']) ?>"
                         placeholder="Masukkan NIP" required>
                  <div class="invalid-feedback">NIP harus diisi</div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Jabatan <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="jabatan"
                         value="<?= htmlspecialchars($data['pptk_data']['jabatan']) ?>"
                         placeholder="Masukkan jabatan" required>
                  <div class="invalid-feedback">Jabatan harus diisi</div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Status Akun</label>
                  <input type="text" class="form-control"
                         value="<?= $data['pptk_data']['is_active'] ? 'Aktif' : 'Non-Aktif' ?>"
                         readonly style="background-color: #f8f9fa;">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Tanggal Bergabung</label>
                  <input type="text" class="form-control"
                         value="<?= date('d M Y', strtotime($data['pptk_data']['created_at'])) ?>"
                         readonly style="background-color: #f8f9fa;">
                </div>
              </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
              <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"></path>
                  <path d="M21 3v5h-5"></path>
                </svg>
                Reset
              </button>
              <button type="submit" class="btn btn-primary" id="btnUpdateProfile">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Update Profil
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Change Password -->
      <div class="card border-0 shadow-sm mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header bg-transparent border-0 pb-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            Ubah Password
          </h5>
        </div>
        <div class="card-body">
          <form id="passwordForm">
            <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">

            <div class="alert alert-info border-0 bg-light text-black">
              <small>
                <strong>Informasi:</strong> Untuk mengubah password, Anda harus memasukkan password saat ini terlebih dahulu untuk keamanan.
              </small>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="current_password"
                           placeholder="Masukkan password saat ini" id="currentPassword">
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="currentPassword">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </button>
                  </div>
                  <div class="invalid-feedback">Password saat ini harus diisi</div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Password Baru</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="password"
                           placeholder="Masukkan password baru" id="newPassword">
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="newPassword">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </button>
                  </div>
                  <div class="form-text">Minimal 6 karakter</div>
                  <div class="invalid-feedback">Password baru minimal 6 karakter</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Konfirmasi Password Baru</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="password_confirm"
                           placeholder="Konfirmasi password baru" id="confirmPassword">
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirmPassword">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </button>
                  </div>
                  <div class="invalid-feedback">Konfirmasi password tidak cocok</div>
                </div>
              </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="button" class="btn btn-outline-secondary" onclick="resetPasswordForm()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"></path>
                  <path d="M21 3v5h-5"></path>
                </svg>
                Reset
              </button>
              <button type="button" class="btn btn-warning" id="btnChangePassword">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Ubah Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Right Column - Account Info & Quick Actions -->
    <div class="col-xl-4">
      <!-- Account Summary -->
      <div class="card border-0 shadow-sm mb-4" data-aos="fade-left">
        <div class="card-header bg-transparent border-0 pb-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="16" x2="12" y2="12"></line>
              <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Ringkasan Akun
          </h5>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center mb-4">
            <div class="flex-shrink-0">
              <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-1 fw-bold"><?= htmlspecialchars($data['pptk_data']['nama_pegawai']) ?></h6>
              <p class="mb-0 text-muted small"><?= htmlspecialchars($data['pptk_data']['username']) ?></p>
              <p class="mb-0 text-muted small">NIP: <?= htmlspecialchars($data['pptk_data']['nip']) ?></p>
            </div>
          </div>

          <div class="list-group list-group-flush">
            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="small text-muted">Jabatan</span>
              <span class="small fw-semibold"><?= htmlspecialchars($data['pptk_data']['jabatan']) ?></span>
            </div>
            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="small text-muted">Role</span>
              <span class="badge bg-info">PPTK</span>
            </div>
            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="small text-muted">Status</span>
              <span class="badge bg-<?= $data['pptk_data']['is_active'] ? 'success' : 'secondary' ?>">
                <?= $data['pptk_data']['is_active'] ? 'Aktif' : 'Non-Aktif' ?>
              </span>
            </div>
            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="small text-muted">Bergabung</span>
              <span class="small"><?= date('d M Y', strtotime($data['pptk_data']['created_at'])) ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card border-0 shadow-sm" data-aos="fade-left" data-aos-delay="100">
        <div class="card-header bg-transparent border-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            Akses Cepat
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-6">
              <a href="<?= BASEURL ?>user/home" class="btn btn-outline-primary w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7"></path>
                    <path d="M9 22V12h6v10"></path>
                  </svg>
                </div>
                <span class="fw-semibold">Dashboard</span>
                <small class="text-muted">Beranda</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>user/kwitansi" class="btn btn-outline-success w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3">
                <div class="bg-success bg-opacity-10 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                  </svg>
                </div>
                <span class="fw-semibold">Kwitansi</span>
                <small class="text-muted">Input Data</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>user/riwayat" class="btn btn-outline-info w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3">
                <div class="bg-info bg-opacity-10 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                </div>
                <span class="fw-semibold">Riwayat</span>
                <small class="text-muted">Disetujui</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>user/cetak" class="btn btn-outline-warning w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3">
                <div class="bg-warning bg-opacity-10 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                  </svg>
                </div>
                <span class="fw-semibold">Cetak</span>
                <small class="text-muted">PDF</small>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Security Tips -->
      <div class="card border-0 shadow-sm mt-4" data-aos="fade-left" data-aos-delay="200">
        <div class="card-header bg-transparent border-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Tips Keamanan
          </h5>
        </div>
        <div class="card-body">
          <div class="alert alert-warning border-0 bg-light">
            <small>
              <strong>Keamanan Akun:</strong><br>
              • Gunakan password yang kuat<br>
              • Jangan bagikan password kepada siapapun<br>
              • Selalu logout setelah menggunakan sistem<br>
              • Perbarui password secara berkala
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';

// Initialize
$(document).ready(function() {
    initializeEventHandlers();
});

// Initialize event handlers
function initializeEventHandlers() {
    // Profile form submission
    $('#profileForm').on('submit', updateProfile);

    // Change password button
    $('#btnChangePassword').on('click', changePassword);

    // Toggle password visibility
    $(document).on('click', '.toggle-password', togglePasswordVisibility);

    // Real-time password validation
    $('#newPassword, #confirmPassword').on('input', validatePasswordMatch);
}

// Update profile
function updateProfile(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('a', 'update_profile');

    $.ajax({
        url: BASEURL + "user/settings",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            $('#btnUpdateProfile').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');
        },
        success: function(res) {
            if (res.status === "success") {
                showAlert(res.message, 'success');

                // Update account summary if name changed
                if (res.data && res.data.nama_pegawai) {
                    $('.card-body h6.fw-bold').text(res.data.nama_pegawai);
                }
            } else {
                showAlert(res.message, 'danger');
                if (res.errors) {
                    displayFormErrors(res.errors, 'profileForm');
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Update profile error:', error);
            showAlert('Terjadi kesalahan saat memperbarui profil', 'danger');
            $('html, body').animate({ scrollTop: 0 }, 500);
        },
        complete: function() {
            $('#btnUpdateProfile').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Update Profil');
        }
    });
}

// Change password
function changePassword() {
    const currentPassword = $('input[name="current_password"]').val();
    const newPassword = $('input[name="password"]').val();
    const confirmPassword = $('input[name="password_confirm"]').val();

    // Basic validation
    if (!currentPassword) {
        showAlert('Password saat ini harus diisi', 'danger');
        $('input[name="current_password"]').focus();
        return;
    }

    if (newPassword && newPassword.length < 6) {
        showAlert('Password baru minimal 6 karakter', 'danger');
        $('input[name="password"]').focus();
        return;
    }

    if (newPassword !== confirmPassword) {
        showAlert('Konfirmasi password tidak cocok', 'danger');
        $('input[name="password_confirm"]').focus();
        return;
    }

    // If no new password provided, just show info
    if (!newPassword) {
        showAlert('Masukkan password baru untuk mengubah password', 'info');
        return;
    }

    const formData = new FormData(document.getElementById('profileForm'));
    formData.append('a', 'update_profile');
    formData.append('current_password', currentPassword);
    formData.append('password', newPassword);
    formData.append('password_confirm', confirmPassword);

    $.ajax({
        url: BASEURL + "user/settings",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            $('#btnChangePassword').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Mengubah...');
        },
        success: function(res) {
            if (res.status === "success") {
                let message = res.message;
                if (res.warning) {
                    message += '<br><strong>' + res.warning + '</strong>';
                }
                showAlert(message, 'success');
                resetPasswordForm();
                  $('html, body').animate({ scrollTop: 0 }, 500);

                // Suggest logout if password changed
                if (res.warning) {
                    setTimeout(() => {
                        if (confirm('Password berhasil diubah. Disarankan untuk login ulang. Login sekarang?')) {
                            window.location.href = BASEURL + 'processing/keluar';
                        }
                    }, 2000);
                }
            } else {
                showAlert(res.message, 'danger');
                if (res.errors) {
                    displayFormErrors(res.errors, 'passwordForm');
                }
            }
        },
        error: function(xhr, status, error) {
            // console.error('Change password error:', error);
            showAlert('Terjadi kesalahan saat mengubah password', 'danger');
            $('html, body').animate({
                scrollTop: element.offset().top - 100
            }, 500);
        },
        complete: function() {
            $('#btnChangePassword').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Ubah Password');
        }
    });
}

// Toggle password visibility
function togglePasswordVisibility() {
    const target = $(this).data('target');
    const input = $('#' + target);
    const icon = $(this).find('svg');

    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.html('<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>');
    } else {
        input.attr('type', 'password');
        icon.html('<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>');
    }
}

// Validate password match in real-time
function validatePasswordMatch() {
    const password = $('#newPassword').val();
    const confirm = $('#confirmPassword').val();

    if (password && confirm) {
        if (password !== confirm) {
            $('#confirmPassword').addClass('is-invalid');
            $('#confirmPassword').removeClass('is-valid');
        } else {
            $('#confirmPassword').removeClass('is-invalid');
            $('#confirmPassword').addClass('is-valid');
        }
    } else {
        $('#confirmPassword').removeClass('is-invalid is-valid');
    }
}

// Reset profile form
function resetForm() {
    // Reload original data
    $.ajax({
        url: BASEURL + "user/settings",
        method: 'POST',
        data: {
            a: 'get_profile',
            csrf_token: '<?= $data['csrf_token'] ?>'
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                $('input[name="nama_pegawai"]').val(res.data.nama_pegawai);
                $('input[name="nip"]').val(res.data.nip);
                $('input[name="jabatan"]').val(res.data.jabatan);

                // Clear validation
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                showAlert('Form berhasil direset ke data asli', 'info');
                $('html, body').animate({
                scrollTop: element.offset().top - 100
            }, 500);
            }
        },
        error: function() {
            showAlert('Gagal memuat data asli', 'danger');
            $('html, body').animate({
                scrollTop: element.offset().top - 100
            }, 500);
        }
    });
}

// Reset password form
function resetPasswordForm() {
    $('#passwordForm')[0].reset();
    $('.toggle-password svg').html('<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>');
    $('input[type="password"]').attr('type', 'password');
    $('.is-invalid').removeClass('is-invalid');
    $('.is-valid').removeClass('is-valid');
    $('.invalid-feedback').remove();
}

// Display form errors
function displayFormErrors(errors, formType) {
    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    // Display new errors
    $.each(errors, function(field, message) {
        const element = $(`[name="${field}"]`);
        element.addClass('is-invalid');

        // Add error message
        if (element.parent().hasClass('input-group')) {
            element.parent().after(`<div class="invalid-feedback">${message}</div>`);
        } else {
            element.after(`<div class="invalid-feedback">${message}</div>`);
        }

        // Scroll to first error
        if (Object.keys(errors)[0] === field) {
            $('html, body').animate({
                scrollTop: element.offset().top - 100
            }, 500);
        }
    });
}

// Global function for alerts
function showAlert(message, type = 'success') {
    const alert = `<div class="alert alert-${type} alert-dismissible fade show">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    $('#alertContainer').html(alert);

    // Auto-close success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            $('#alertContainer .alert').alert('close');
        }, 5000);
    }
}
</script>


<link rel="stylesheet" href="assets/css/settings_user.css">
<?php
}