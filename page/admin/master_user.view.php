<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
  ?>#judul('Master User')
<div class="container mt-4">

  <h3 class="mb-lg-4">Master Data - Penyedia & User</h3>
  <ul class="nav nav-tabs" id="masterTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="user-tab" data-bs-toggle="tab" href="#user" role="tab">User</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="penyedia-tab" data-bs-toggle="tab" href="#penyedia" role="tab">Penyedia</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="pejabat-tab" data-bs-toggle="tab" href="#pejabat" role="tab">Pejabat</a>
    </li>
  </ul>
  <!-- Alert Container -->
  <div id="alertContainer" data-aos="zoom-in"></div>

  <div class="tab-content mt-3">
    <!-- Tab User -->
    <div class="tab-pane fade show active" id="user" role="tabpanel">
      <h5>Data Pegawai</h5>
      <form class="row g-3" id="formUser">
        <input type="hidden" name="id" id="user_id">
        <input type="hidden" name="a" value="simpan">
        <input type="hidden" name="tipe" value="user">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" class="form-control" placeholder="Nama lengkap" name="nama_pegawai" id="user_nama_pegawai" required>
          <div class="invalid-feedback" id="user_nama_error"></div>
        </div>

         <div class="col-md-6 edit-only-field" style="display: none;" >
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="user_password"
                   placeholder="Leave blank to keep current password">
            <small class="form-text text-muted">Only fill to change password</small>
            <div class="invalid-feedback" id="user_password_error"></div>
        </div>



        <div class="col-md-6">
          <label class="form-label">NIP</label>
          <input type="text" class="form-control" placeholder="NIP" name="nip" id="user_nip" required>
          <div class="invalid-feedback" id="user_nip_error"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Jabatan</label>
          <input type="text" class="form-control" placeholder="Jabatan dari PPTK" name="jabatan" id="user_jabatan" required>
          <div class="invalid-feedback" id="user_jabatan_error"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Role</label>
          <select class="form-select" name="role" id="user_role" required>
            <option value="" selected disabled>Pilih Role</option>
            <option value="admin">Admin</option>
            <option value="user">PPTK</option>
          </select>
          <div class="invalid-feedback" id="user_role_error"></div>
        </div>
        <div class="col-md-6 edit-only-field" style="display: none;" data-aos="zoom-in">
              <label class="form-label">Account Status</label>
              <div class="d-flex justify-content-center">
                  <button type="button" class="btn btn-toggle-account" id="user_is_active_btn" data-value="1">
                      <span class="btn-text">Account Active</span>
                      <span class="btn-icon">✓</span>
                  </button>
                  <input type="hidden" name="is_active" id="user_is_active" value="1">
              </div>
              <small class="form-text text-muted">Click untuk mengaktifkan akun status</small>
          </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Simpan
          </button>
          <button type="button" class="btn btn-secondary" onclick="resetFormUser()">Batal</button>
        </div>
      </form>
      <hr>
      <h6>Daftar Pegawai</h6>
      <!-- Search and Filter Section -->
      <div class="row mb-3">
          <div class="col-md-6">
              <div class="input-group">
                  <input type="text" class="form-control search-input" placeholder="Cari..." data-tipe="user">
                  <button class="btn btn-outline-secondary search-btn" type="button" data-tipe="user">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                  </button>
              </div>
          </div>
          <div class="col-md-3">
              <select class="form-select search-field-select" data-tipe="user" required>
                  <option value="" >Semua Field</option>
                  <option value="nama_pegawai">Nama</option>
                  <option value="nip">NIP</option>
                  <option value="jabatan">Jabatan</option>
                  <option value="role">Role</option>
              </select>
          </div>
          <div class="col-md-3">
              <select class="form-select per-page-select" data-tipe="user">
                  <option value="10">10 per halaman</option>
                  <option value="25">25 per halaman</option>
                  <option value="50">50 per halaman</option>
                  <option value="100">100 per halaman</option>
              </select>
          </div>
      </div>


      <table class="table table-bordered tbdt" id="tabelUser">
        <thead>
          <tr>
            <th>Nama</th>
            
            <th>NIP</th>
            <th>Jabatan</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <!-- Pagination Controls (akan diisi oleh JavaScript) -->
      <div class="row mt-3">
          <div class="col-md-6">
              <div class="pagination-info" data-tipe="user"></div>
          </div>
          <div class="col-md-6">
              <nav>
                  <ul class="pagination justify-content-end pagination-controls" data-tipe="user"></ul>
              </nav>
          </div>
      </div>
    </div>

    <!-- Tab Penyedia -->
    <div class="tab-pane fade" id="penyedia" role="tabpanel" data-aos="zoom-in" data-aos-duration="1000">
      <h5>Data Penyedia</h5>
      <form class="row g-3" id="formPenyedia">
        <input type="hidden" name="id" id="penyedia_id">
        <input type="hidden" name="a" value="simpan">
        <input type="hidden" name="tipe" value="penyedia">

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="col-md-6">
          <label class="form-label">Nama Perusahaan</label>
          <input type="text" class="form-control" placeholder="Nama Perusahaan" name="nama_perusahaan" id="penyedia_nama_perusahaan" required>
          <div class="invalid-feedback" id="penyedia_nama_perusahaan_error"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Nama Penyedia</label>
          <input type="text" class="form-control" placeholder="Nama Penyedia" name="nama_penyedia" id="penyedia_nama_penyedia" required>
          <div class="invalid-feedback" id="penyedia_nama_penyedia_error"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Jabatan</label>
          <input type="text" class="form-control" placeholder="Jabatan" name="jabatan" id="penyedia_jabatan" required>
          <div class="invalid-feedback" id="penyedia_jabatan_error"></div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Simpan
          </button>
          <button type="button" class="btn btn-secondary" onclick="resetFormPenyedia()">Batal</button>
        </div>
      </form>
      <hr>
      <h6>Daftar Penyedia</h6>
      <div class="row mb-3">
      <div class="col-md-6">
          <div class="input-group">
              <input type="text" class="form-control search-input" placeholder="Cari..." data-tipe="penyedia">
              <button class="btn btn-outline-secondary search-btn" type="button" data-tipe="penyedia">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              </button>
          </div>
        </div>
        <div class="col-md-3">
            <select class="form-select search-field-select" data-tipe="penyedia">
                <option value="">Semua Field</option>
                <option value="nama_perusahaan">Nama Perusahaan</option>
                <option value="nama_penyedia">Nama Penyedia</option>
                <option value="jabatan">Jabatan</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select per-page-select" data-tipe="penyedia">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
                <option value="100">100 per halaman</option>
            </select>
        </div>
      </div>
      <table class="table table-bordered tbdt" id="tabelPenyedia">
        <thead>
          <tr>
            <th>Nama Perusahaan</th>
            <th>Nama Penyedia</th>
            <th>Jabatan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data akan diisi oleh JavaScript -->
        </tbody>
      </table>
      <div class="row mt-3">
          <div class="col-md-6">
              <div class="pagination-info" data-tipe="penyedia"></div>
          </div>
          <div class="col-md-6">
              <nav>
                  <ul class="pagination justify-content-end pagination-controls" data-tipe="penyedia"></ul>
              </nav>
          </div>
      </div>
    </div>

    <!-- Tab Pejabat -->
    <div class="tab-pane fade" id="pejabat" role="tabpanel" data-aos="zoom-in" data-aos-duration="1000">
      <h5>Data Pejabat</h5>
      <form class="row g-3" id="formPejabat">
        <input type="hidden" name="id" id="pejabat_id">
        <input type="hidden" name="a" value="simpan">
        <input type="hidden" name="tipe" value="pejabat">

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="col-md-6">
          <label class="form-label">Nama</label>
          <input type="text" class="form-control" placeholder="Nama Pejabat" name="nama" id="pejabat_nama" required>
          <div class="invalid-feedback" id="pejabat_nama_error"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Jabatan</label>
          <input type="text" class="form-control" placeholder="Jabatan" name="jabatan" id="pejabat_jabatan" required>
          <div class="invalid-feedback" id="pejabat_jabatan_error"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">NIP</label>
          <input type="text" class="form-control" placeholder="NIP" name="nip" id="pejabat_nip">
          <div class="invalid-feedback" id="pejabat_nip_error"></div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Simpan
          </button>
          <button type="button" class="btn btn-secondary" onclick="resetFormPejabat()">Batal</button>
        </div>
      </form>
      <hr>
      <h6>Daftar Pejabat</h6>
      <!-- In Pejabat Tab -->
      <div class="row mb-3">
          <div class="col-md-6">
              <div class="input-group">
                  <input type="text" class="form-control search-input" placeholder="Cari..." data-tipe="pejabat">
                  <button class="btn btn-outline-secondary search-btn" type="button" data-tipe="pejabat">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                  </button>
              </div>
          </div>
          <div class="col-md-3">
              <select class="form-select search-field-select" data-tipe="pejabat">
                  <option value="">Semua Field</option>
                  <option value="nama">Nama</option>
                  <option value="jabatan">Jabatan</option>
                  <option value="nip">NIP</option>
              </select>
          </div>
          <div class="col-md-3">
              <select class="form-select per-page-select" data-tipe="pejabat">
                  <option value="10">10 per halaman</option>
                  <option value="25">25 per halaman</option>
                  <option value="50">50 per halaman</option>
                  <option value="100">100 per halaman</option>
              </select>
          </div>
      </div>

      <!-- Pagination Controls -->

      <table class="table table-bordered tbdt" id="tabelPejabat">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>NIP</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data akan diisi oleh JavaScript -->
        </tbody>
      </table>
      <div class="row mt-3">
          <div class="col-md-6">
              <div class="pagination-info" data-tipe="pejabat"></div>
          </div>
          <div class="col-md-6">
              <nav>
                  <ul class="pagination justify-content-end pagination-controls" data-tipe="pejabat"></ul>
              </nav>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus data ini?</p>
        <input type="hidden" id="deleteId">
        <input type="hidden" id="deleteTipe">
        <input type="hidden" id="deleteCsrf" value="<?= $_SESSION['csrf_token'] ?>"><!--  -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <span class="spinner-border spinner-border-sm d-none" role="status"></span>
          Hapus
        </button>
      </div>
    </div>
  </div>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';
const tableState = {
    user: { page: 1, per_page: 10, search: '', search_field: '' },
    penyedia: { page: 1, per_page: 10, search: '', search_field: '' },
    pejabat: { page: 1, per_page: 10, search: '', search_field: '' }
};
// ==================== GLOBAL FUNCTIONS ====================
function showAlert(message, type = 'success') {
    const alert = `<div class="alert alert-${type} alert-dismissible fade show" data-aos="zoom-in" data-aos-duration="1000">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    $('#alertContainer').html(alert);

    // Auto hide after 5 seconds
    setTimeout(() => $('#alertContainer .alert').alert('close'), 5000);
}

function resetFormUser() {
    $('#formUser')[0].reset();
    $('#user_id').val('');
    $('#formUser button[type="submit"]').text('Simpan');
    clearValidationErrors('user');

     // Reset account toggle to active state
    $('#user_is_active').val('1');
    $('#user_is_active_btn').removeClass('inactive');
    $('#user_is_active_btn').find('.btn-text').text('Account Active');
    $('#user_is_active_btn').find('.btn-icon').html('✓');

    toggleEditFields(false);
}

function resetFormPenyedia() {
    $('#formPenyedia')[0].reset();
    $('#penyedia_id').val('');
    $('#formPenyedia button[type="submit"]').text('Simpan');
    clearValidationErrors('penyedia');
}

function resetFormPejabat() {
    $('#formPejabat')[0].reset();
    $('#pejabat_id').val('');
    $('#formPejabat button[type="submit"]').text('Simpan');
    clearValidationErrors('pejabat');
}

function clearValidationErrors(prefix = '') {
    if (prefix) {
        $(`.form-control`).removeClass('is-invalid');
        $(`.invalid-feedback`).text('');
    } else {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }
}

function showValidationErrors(errors, prefix = '') {
    for (const field in errors) {
        const fieldId = prefix ? `${prefix}_${field}` : field;
        $(`#${fieldId}`).addClass('is-invalid');
        $(`#${fieldId}_error`).text(errors[field]);
    }
}

function cleanupStuckModals() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('.modal').hide();
    $('body').css({'overflow': '', 'padding-right': ''});
}

function toggleEditFields(show) {
    if (show) {
        $('.edit-only-field').show();
        initAccountToggle();
    } else {
        $('.edit-only-field').hide();


        $('#user_password').val('');
        $('#user_is_active').prop('checked', true);
    }
}
function initAccountToggle() {
    $('#user_is_active_btn').off('click').on('click', function() {
        const $btn = $(this);
        const $hiddenInput = $('#user_is_active');
        const isActive = $hiddenInput.val() === '1';

        if (isActive) {
            // Change to inactive
            $hiddenInput.val('0');
            $btn.addClass('inactive');
            $btn.find('.btn-text').text('Account Inactive');
            $btn.find('.btn-icon').html('✕');
        } else {
            // Change to active
            $hiddenInput.val('1');
            $btn.removeClass('inactive');
            $btn.find('.btn-text').text('Account Active');
            $btn.find('.btn-icon').html('✓');
        }
    });
}
// Load table data dengan pagination dan search
// Load table data dengan pagination dan search
// Load table data dengan pagination dan search
function loadTableData(tipe) {
    const state = tableState[tipe];
    const tableId = `tabel${tipe.charAt(0).toUpperCase() + tipe.slice(1)}`;

    $(`#${tableId} tbody`).html(`
        <tr>
            <td colspan="5" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </td>
        </tr>
    `);

    $.ajax({
        url: BASEURL + "admin/master2",
        method: 'POST',
        data: {
            a: "data",
            tipe: tipe,
            page: state.page,
            per_page: state.per_page,
            search: state.search,
            search_field: state.search_field
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                let tbody = $(`#${tableId} tbody`).empty();

                if (res.data.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="5" class="text-center">
                                ${state.search ? 'Tidak ada data yang sesuai dengan pencarian' : 'Tidak ada data'}
                            </td>
                        </tr>
                    `);
                } else {
                    res.data.forEach(row => {
                        let tr = $("<tr>");

                        if (tipe === 'user') {

                            tr.append($("<td>").text(row.nama_pegawai || ''));
                            tr.append($("<td>").text(row.nip || ''));
                            tr.append($("<td>").text(row.jabatan || ''));
                            tr.append($("<td>").text(row.role || ''));
                            const statusClass = row.is_active == 1 ? 'bg-success' : 'bg-danger';
                            const statusText = row.is_active == 1 ? 'Active' : 'Inactive';
                            tr.append($("<td>").html(`<span class="badge ${statusClass}">${statusText}</span>`));
                        } else if (tipe === 'penyedia') {
                            tr.append($("<td>").text(row.nama_perusahaan || ''));
                            tr.append($("<td>").text(row.nama_penyedia || ''));
                            tr.append($("<td>").text(row.jabatan || ''));
                        } else if (tipe === 'pejabat') {
                            tr.append($("<td>").text(row.nama || ''));
                            tr.append($("<td>").text(row.jabatan || ''));
                            tr.append($("<td>").text(row.nip || ''));
                        }

                        let actions = `
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary btn-edit me-1" data-id="${row.id}" data-tipe="${tipe}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}" data-tipe="${tipe}" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                </button>
                            </div>
                        `;
                        tr.append($("<td>").html(actions));
                        tbody.append(tr);
                    });
                }

                // Update pagination controls
                updatePaginationControls(tipe, res.pagination);
            } else {
                showAlert("Gagal memuat data: " + res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            showAlert("Server error saat mengambil data.", 'danger');
        }
    });
}

// Update pagination controls
function updatePaginationControls(tipe, pagination) {
    const { current_page, per_page, total_rows, total_pages } = pagination;

    // Update pagination info
    const start = ((current_page - 1) * per_page) + 1;
    const end = Math.min(current_page * per_page, total_rows);
    $(`.pagination-info[data-tipe="${tipe}"]`).html(`
        Menampilkan ${start} - ${end} dari ${total_rows} data
    `);

    // Update pagination buttons
    const paginationEl = $(`.pagination-controls[data-tipe="${tipe}"]`).empty();

    // Previous button
    paginationEl.append(`
        <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current_page - 1}">Previous</a>
        </li>
    `);

    // Page buttons
    const maxVisiblePages = 5;
    let startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(total_pages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        paginationEl.append(`
            <li class="page-item ${i === current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `);
    }

    // Next button
    paginationEl.append(`
        <li class="page-item ${current_page === total_pages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current_page + 1}">Next</a>
        </li>
    `);
}

// Event handlers untuk pagination
$(document).on('click', '.pagination-controls .page-link', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    const tipe = $(this).closest('.pagination-controls').data('tipe');

    if (page) {
        tableState[tipe].page = page;
        loadTableData(tipe);
    }
});

// Event handlers untuk search
$(document).on('click', '.search-btn', function() {
    const tipe = $(this).data('tipe');
    const searchInput = $(`.search-input[data-tipe="${tipe}"]`);
    const searchField = $(`.search-field-select[data-tipe="${tipe}"]`);

     console.log('Search clicked for type:', tipe); // Debug log
    console.log('Search term:', searchInput.val()); // Debug log
    console.log('Search field:', searchField.val()); // Debug log

    tableState[tipe].search = searchInput.val();
    tableState[tipe].search_field = searchField.val();
    tableState[tipe].page = 1; // Reset ke page 1 saat search

    loadTableData(tipe);
});

$(document).on('keypress', '.search-input', function(e) {
    if (e.which === 13) { // Enter key
        const tipe = $(this).data('tipe');
        $(`.search-btn[data-tipe="${tipe}"]`).click();
    }
});

// Event handlers untuk per page change
$(document).on('change', '.per-page-select', function() {
    const tipe = $(this).data('tipe');
    tableState[tipe].per_page = $(this).val();
    tableState[tipe].page = 1; // Reset ke page 1 saat ganti per_page

    loadTableData(tipe);
});

// Clear search
function clearSearch(tipe) {
    $(`.search-input[data-tipe="${tipe}"]`).val('');
    $(`.search-field-select[data-tipe="${tipe}"]`).val('');

    tableState[tipe].search = '';
    tableState[tipe].search_field = '';
    tableState[tipe].page = 1;

    loadTableData(tipe);
}







//**
// Update tab switching handler
$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    const target = $(e.target).attr("href");
    const tipe = target.substring(1);
    loadTableData(tipe);
});

// Initialize per-page selects
$(document).ready(function() {
    $('.per-page-select').each(function() {
        const tipe = $(this).data('tipe');
        $(this).val(tableState[tipe].per_page);
    });

    // Load initial data
    loadTableData('user');
});

$(document).ready(function() {
    // Load initial data for active tab
    loadTableData('user');

    // Tab switching handler
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href"); // e.g. "#user"
        const tipe = target.substring(1); // remove the # to get "user"
        loadTableData(tipe);
    });

    // Form submission handlers
    $("#formUser").submit(function(e) {
        e.preventDefault();
        submitForm('user');
    });

    $("#formPenyedia").submit(function(e) {
        e.preventDefault();
        submitForm('penyedia');
    });

    $("#formPejabat").submit(function(e) {
        e.preventDefault();
        submitForm('pejabat');
    });

    // Edit button handler
    $(document).on("click", ".btn-edit", function() {
        const id = $(this).data("id");
        const tipe = $(this).data("tipe");
        editData(id, tipe);
    });

    // Delete button handler
    $(document).on("click", ".btn-delete", function() {
        const id = $(this).data("id");
        const tipe = $(this).data("tipe");
        $("#deleteId").val(id);
        $("#deleteTipe").val(tipe);
        $("#deleteModal").modal("show");
        cleanupStuckModals();
    });

    // Confirm delete
    $("#confirmDelete").click(function() {
        const deleteBtn = $(this);
        const id = $("#deleteId").val();
        const tipe = $("#deleteTipe").val();
        const csrfToken = $("#deleteCsrf").val();

        deleteBtn.prop('disabled', true);
        deleteBtn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: BASEURL + "admin/master2",
            method: 'POST',
            data: {
                a: "hapus",
                id: id,
                tipe: tipe,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function(res) {
                deleteBtn.prop('disabled', false);
                deleteBtn.find('.spinner-border').addClass('d-none');

                if (res.status === "success") {
                    $("#deleteModal").modal("hide");
                    showAlert(res.message, 'success');
                    loadTableData(tipe);
                } else {
                    showAlert("Gagal hapus: " + res.message, 'danger');
                }
            },
            error: function(xhr, status, error) {
                deleteBtn.prop('disabled', false);
                deleteBtn.find('.spinner-border').addClass('d-none');
                showAlert("Server error saat menghapus.", 'danger');
            }
        });
    });
});

function submitForm(tipe) {
    clearValidationErrors(tipe);

    const form = $(`#form${tipe.charAt(0).toUpperCase() + tipe.slice(1)}`);
    const formData = form.serializeArray();
    const data = {};
    formData.forEach(f => data[f.name] = f.value);

    // Show loading state
    const submitBtn = form.find('button[type="submit"]');
    submitBtn.prop('disabled', true);
    submitBtn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: BASEURL + "admin/master2",
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            submitBtn.prop('disabled', false);
            submitBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                form[0].reset();
                $(`#${tipe}_id`).val('');
                submitBtn.text('Simpan');
                showAlert(res.message, 'success');
                loadTableData(tipe);

                if (tipe === 'user') {
                    toggleEditFields(false);
                }
                $('html, body').animate({ scrollTop: 0 }, 500);
            } else {
                if (res.errors) {
                    showValidationErrors(res.errors, tipe);
                } else {
                    showAlert("Gagal simpan: " + res.message, 'danger');
                }
            }
        },
        error: function(xhr, status, error) {
            submitBtn.prop('disabled', false);
            submitBtn.find('.spinner-border').addClass('d-none');
            showAlert("Server error saat menyimpan.", 'danger');
        }
    });
}

function editData(id, tipe) {
    clearValidationErrors(tipe);
    if (tipe === 'user') {
        toggleEditFields(true);
    }
    $.ajax({
        url: BASEURL + "admin/master2",
        method: 'POST',
        data: {
            a: "get",
            id: id,
            tipe: tipe
        },
        dataType: 'json',
        beforeSend: function() {
            // Show loading indicator if needed
        },
        success: function(res) {
            if (res.status === "success") {
                const item = res.data;
                const form = $(`#form${tipe.charAt(0).toUpperCase() + tipe.slice(1)}`);

                // Fill form with data
                $(`#${tipe}_id`).val(item.id);

                for (const key in item) {
                    if (item.hasOwnProperty(key) && $(`#${tipe}_${key}`).length) {
                        if (key === 'is_active') {
                        $(`#${tipe}_${key}`).prop('checked', item[key] == 1);
                         const $btn = $('#user_is_active_btn');
                            if (item[key] == 1) {
                                $btn.removeClass('inactive');
                                $btn.find('.btn-text').text('Account Active');
                                $btn.find('.btn-icon').html('✓');
                            } else {
                                $btn.addClass('inactive');
                                $btn.find('.btn-text').text('Account Inactive');
                                $btn.find('.btn-icon').html('✕');
                            }
                        }
                        else {
                        $(`#${tipe}_${key}`).val(item[key]);
                        }
                    }
                }
                form.find('button[type="submit"]').text('Update');

                // Scroll to form
                $('html, body').animate({
                    scrollTop: form.offset().top - 100
                }, 500);
            } else {
                showAlert("Gagal mengambil data: " + res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            showAlert("Server error saat mengambil data.", 'danger');
        }
    });
}
</script>
<link rel="stylesheet" href="assets/css/master_admin.css">
<?php
}