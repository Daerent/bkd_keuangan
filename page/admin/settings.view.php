<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Pengaturan Akun')
<div class="container mt-4">

  <h3 class="mb-lg-4">Pengaturan Akun</h3>

  <!-- Alert Container -->
  <div id="alertContainer" data-aos="zoom-in"></div>

  <div class="row">
    <!-- Left Column - Profile Information -->
    <div class="col-lg-4 mb-4">
      <div class="card border-0 shadow-sm" data-aos="fade-right">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Informasi Profil
          </h5>
        </div>
        <div class="card-body text-center">
          <!-- Profile Avatar -->
          <div class="mb-4">
            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-primary">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
          </div>

          <!-- Admin Info -->
          <h5 class="fw-bold mb-1"><?= htmlspecialchars($data['admin_data']['nama_pegawai']) ?></h5>
          <p class="text-muted mb-2"><?= htmlspecialchars($data['admin_data']['jabatan']) ?></p>

          <div class="d-flex justify-content-center align-items-center mb-3">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <?= ucfirst($data['admin_data']['role']) ?>
            </span>
          </div>

          <!-- Account Stats -->
          <div class="border-top pt-3">
            <div class="row text-center">
              <div class="col-6">
                <small class="text-muted d-block">NIP</small>
                <strong class="text-primary"><?= htmlspecialchars($data['admin_data']['nip']) ?></strong>
              </div>
              <div class="col-6">
                <small class="text-muted d-block">Status</small>
                <strong class="<?= $data['admin_data']['is_active'] ? 'text-success' : 'text-danger' ?>">
                  <?= $data['admin_data']['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                </strong>
              </div>
            </div>
          </div>

          <!-- Member Since -->
          <div class="mt-3 pt-3 border-top">
            <small class="text-muted">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              Bergabung sejak <?= date('M Y', strtotime($data['admin_data']['created_at'])) ?>
            </small>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column - Edit Profile Form -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm" data-aos="fade-left">
        <div class="card-header bg-transparent border-0">
          <h5 class="card-title mb-0 fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit Profil
          </h5>
        </div>
        <div class="card-body">
          <form id="profileForm">
            <input type="hidden" name="a" value="update_profile">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="row g-3">
              <!-- Username (Readonly) -->
              <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['admin_data']['username']) ?>" readonly>
                <small class="form-text text-muted">Username tidak dapat diubah</small>
              </div>

              <!-- Role (Readonly) -->
              <div class="col-md-6">
                <label class="form-label">Role</label>
                <input type="text" class="form-control" value="<?= ucfirst($data['admin_data']['role']) ?>" readonly>
                <small class="form-text text-muted">Hak akses sistem</small>
              </div>

              <!-- Nama Lengkap -->
              <div class="col-md-6">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_pegawai" id="nama_pegawai"
                       value="<?= htmlspecialchars($data['admin_data']['nama_pegawai']) ?>" required>
                <div class="invalid-feedback" id="nama_pegawai_error"></div>
              </div>

              <!-- NIP -->
              <div class="col-md-6">
                <label class="form-label">NIP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nip" id="nip"
                       value="<?= htmlspecialchars($data['admin_data']['nip']) ?>" required>
                <div class="invalid-feedback" id="nip_error"></div>
              </div>

              <!-- Jabatan -->
              <div class="col-12">
                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="jabatan" id="jabatan"
                       value="<?= htmlspecialchars($data['admin_data']['jabatan']) ?>" required>
                <div class="invalid-feedback" id="jabatan_error"></div>
              </div>

              <!-- Password Section -->
              <div class="col-12">
                <div class="border-top pt-4 mt-2">
                  <h6 class="fw-bold mb-3 text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Ubah Password
                    <small class="text-muted fw-normal ms-2">(Kosongkan jika tidak ingin mengubah)</small>
                  </h6>
                </div>
              </div>

              <!-- New Password -->
              <div class="col-md-6">
                <label class="form-label">Password Baru</label>
                <input type="password" class="form-control" name="password" id="password"
                       placeholder="Minimal 6 karakter">
                <div class="invalid-feedback" id="password_error"></div>
                <small class="form-text text-muted">Biarkan kosong untuk mempertahankan password saat ini</small>
              </div>

              <!-- Confirm Password -->
              <div class="col-md-6">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" name="password_confirm" id="password_confirm"
                       placeholder="Ulangi password baru">
                <div class="invalid-feedback" id="password_confirm_error"></div>
              </div>

              <!-- Form Actions -->
              <div class="col-12">
                <div class="border-top pt-4 mt-2">
                  <button type="submit" class="btn btn-primary px-4">
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                      <polyline points="17 21 17 13 7 13 7 21"></polyline>
                      <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Perubahan
                  </button>
                  <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetForm()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                      <polyline points="1 4 1 10 7 10"></polyline>
                      <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                    </svg>
                    Reset
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Notice -->
      <div class="card border-0 shadow-sm mt-4" data-aos="fade-up">
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0 text-warning">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="fw-bold text-warning mb-2">Keamanan Akun</h6>
              <p class="text-muted mb-2 small">
                Pastikan untuk selalu menjaga kerahasiaan data akun Anda. Gunakan password yang kuat dan jangan berikan informasi login kepada siapapun.
              </p>
              <ul class="text-muted small mb-0">
                <li>Gunakan kombinasi huruf, angka, dan simbol untuk password</li>
                <li>Jangan gunakan password yang sama dengan akun lain</li>
                <li>Selalu logout setelah menggunakan sistem</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';

// ==================== GLOBAL FUNCTIONS ====================
function showAlert(message, type = 'success') {
    const alert = `<div class="alert alert-${type} alert-dismissible fade show" data-aos="zoom-in">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    $('#alertContainer').html(alert);

    // Auto hide after 5 seconds
    setTimeout(() => $('#alertContainer .alert').alert('close'), 5000);
}

function clearValidationErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

function showValidationErrors(errors) {
    for (const field in errors) {
        $(`#${field}`).addClass('is-invalid');
        $(`#${field}_error`).text(errors[field]);
    }
}

function resetForm() {
    // Reload the page to reset form to original values
    location.reload();
}

// Real-time password validation
function validatePassword() {
    const password = $('#password').val();
    const confirm = $('#password_confirm').val();

    if (password && password.length < 6) {
        $('#password').addClass('is-invalid');
        $('#password_error').text('Password must be at least 6 characters');
        return false;
    }

    if (password && password !== confirm) {
        $('#password_confirm').addClass('is-invalid');
        $('#password_confirm_error').text('Password confirmation does not match');
        return false;
    }

    return true;
}

// ==================== EVENT HANDLERS ====================
$(document).ready(function() {
    // Form submission handler
    $("#profileForm").submit(function(e) {
        e.preventDefault();
        submitProfileForm();
    });

    // Real-time password validation
    $('#password, #password_confirm').on('input', function() {
        const password = $('#password').val();
        const confirm = $('#password_confirm').val();

        // Clear errors when typing
        if (password.length >= 6) {
            $('#password').removeClass('is-invalid');
            $('#password_error').text('');
        }

        if (password === confirm && confirm) {
            $('#password_confirm').removeClass('is-invalid');
            $('#password_confirm_error').text('');
        }
    });

    // Clear validation on input
    $('input').on('input', function() {
        if ($(this).val().trim()) {
            $(this).removeClass('is-invalid');
            $(`#${this.id}_error`).text('');
        }
    });
});

function submitProfileForm() {
    clearValidationErrors();

    // Validate password before submission
    if (!validatePassword()) {
        showAlert('Please fix the password errors before submitting.', 'warning');
        return;
    }

    const form = $('#profileForm');
    const formData = form.serializeArray();
    const data = {};
    formData.forEach(f => data[f.name] = f.value);

    // Show loading state
    const submitBtn = form.find('button[type="submit"]');
    submitBtn.prop('disabled', true);
    submitBtn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: BASEURL + "admin/settings",
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            submitBtn.prop('disabled', false);
            submitBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                showAlert(res.message, 'success');

                // Clear password fields on success
                $('#password').val('');
                $('#password_confirm').val('');

                // Scroll to top to see the alert
                $('html, body').animate({ scrollTop: 0 }, 500);
            } else {
                if (res.errors) {
                    showValidationErrors(res.errors);
                } else {
                    showAlert("Gagal update profil: " + res.message, 'danger');
                }
            }
        },
        error: function(xhr, status, error) {
            submitBtn.prop('disabled', false);
            submitBtn.find('.spinner-border').addClass('d-none');
            showAlert("Server error saat menyimpan perubahan.", 'danger');
        }
    });
}
</script>

<link rel="stylesheet" href="assets/css/settings_admin.css">
<?php
}