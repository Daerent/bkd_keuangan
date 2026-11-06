<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Relasi PPTK - Kegiatan')
<div class="container mt-4">

  <h3 class="mb-lg-4">Relasi PPTK - Kegiatan</h3>

  <!-- Alert Container -->
  <div id="alertContainer" data-aos="zoom-in"></div>

  <div class="card" data-aos="fade-up">
    <div class="card-header">
      <h5 class="card-title mb-0">Form Relasi Baru</h5>
    </div>
    <div class="card-body">
      <form class="row g-3" id="formRelasi">
        <input type="hidden" name="id" id="relasi_id">
        <input type="hidden" name="a" value="simpan">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="col-md-6">
          <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
          <select class="form-select" name="kegiatan_id" id="relasi_kegiatan_id" required>
            <option value="">Pilih Kegiatan</option>
            <?php foreach($data['kegiatans'] as $kegiatan): ?>
              <option value="<?= $kegiatan['id'] ?>"
                      data-rekening="<?= $kegiatan['kode_rekening'] ?>"
                      data-belanja="<?= $kegiatan['nama_belanja'] ?>"
                      data-pptk="<?= $kegiatan['pptk_id'] ?>"
                      data-jenis-kwitansi="<?= $kegiatan['jenis_kwitansi'] ?>"
                      data-jenis-ttd="<?= $kegiatan['jenis_ttd'] ?>">
                <?= $kegiatan['nama_kegiatan'] ?> | <?= $kegiatan['nama_belanja'] ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback" id="relasi_kegiatan_id_error"></div>
        </div>

        <div class="col-md-6">
          <label class="form-label">PPTK <span class="text-danger">*</span></label>
          <select class="form-select" name="pptk_id" id="relasi_pptk_id" required>
            <option value="">Pilih PPTK</option>
            <?php foreach($data['pptks'] as $pptk): ?>
              <option value="<?= $pptk['id'] ?>">
                <?= $pptk['nama_pegawai'] ?> (<?= $pptk['nip'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback" id="relasi_pptk_id_error"></div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Jenis Kwitansi <span class="text-danger">*</span></label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kwitansi" id="jenis_perjalanan" value="perjalanan_dinas" required>
            <label class="form-check-label" for="jenis_perjalanan">
              Perjalanan Dinas
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kwitansi" id="jenis_penyedia" value="penyedia" required>
            <label class="form-check-label" for="jenis_penyedia">
              Penyedia Barang/Jasa
            </label>
          </div>
          <div class="invalid-feedback" id="relasi_jenis_kwitansi_error"></div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Jenis Tanda Tangan</label>
          <div id="jenis_ttd_container">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_ttd" id="ttd_3" value="3_ttd" checked>
              <label class="form-check-label" for="ttd_3">
                3 TTD (PPTK + Bendahara + KPA)
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_ttd" id="ttd_4" value="4_ttd">
              <label class="form-check-label" for="ttd_4">
                4 TTD (+ Penyedia)
              </label>
            </div>
          </div>
          <small class="text-muted" id="ttd_info">Default: 3 TTD untuk semua jenis</small>
        </div>

        <!-- Info Rekening & Belanja -->
        <div class="col-12">
          <div class="card bg-light">
            <div class="card-body py-2">
              <div class="row">
                <div class="col-md-6">
                  <strong>Kode Rekening:</strong>
                  <span id="info_rekening" class="text-primary">-</span>
                </div>
                <div class="col-md-6">
                  <strong>Nama Belanja:</strong>
                  <span id="info_belanja" class="text-primary">-</span>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-6">
                  <strong>Status Relasi:</strong>
                  <span id="info_status" class="badge bg-secondary">Belum Ada PPTK</span>
                </div>
                <div class="col-md-6">
                  <strong>PPTK Saat Ini:</strong>
                  <span id="info_pptk" class="text-muted">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            Simpan Relasi
          </button>
          <button type="button" class="btn btn-secondary" onclick="resetFormRelasi()">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <hr class="my-4">

  <div class="card" data-aos="fade-up">
    <div class="card-header">
      <h5 class="card-title mb-0">Daftar Relasi PPTK - Kegiatan</h5>
    </div>
    <div class="card-body">
      <!-- Search and Filter Section -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" class="form-control search-input" placeholder="Cari PPTK, kegiatan, atau jenis..." data-tipe="relasi">
            <button class="btn btn-outline-primary search-btn" type="button" data-tipe="relasi">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              Cari
            </button>
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select search-field-select" data-tipe="relasi">
            <option value="">Semua Field</option>
            <option value="nama_pegawai">Nama PPTK</option>
            <option value="nama_kegiatan">Kegiatan</option>
            <option value="jenis_kwitansi">Jenis Kwitansi</option>
            <option value="jenis_ttd">Jenis TTD</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select per-page-select" data-tipe="relasi">
            <option value="10">10 per halaman</option>
            <option value="25">25 per halaman</option>
            <option value="50">50 per halaman</option>
            <option value="100">100 per halaman</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelRelasi">
          <thead class="table-light">
            <tr>
              <th width="20%">Kegiatan</th>
              <th width="15%">PPTK</th>
              <th width="10%">Kode Rekening</th>
              <th width="15%">Jenis Kwitansi</th>
              <th width="10%">Jenis TTD</th>

              <th width="15%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data akan diisi oleh JavaScript -->
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <div class="row mt-3">
        <div class="col-md-6">
          <div class="pagination-info" data-tipe="relasi"></div>
        </div>
        <div class="col-md-6">
          <nav>
            <ul class="pagination justify-content-end pagination-controls" data-tipe="relasi"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
          </svg>
          Konfirmasi Hapus Relasi
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Info Section -->
        <div class="alert alert-info">
          <strong>Relasi yang akan dihapus:</strong>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <strong>PPTK:</strong> <span id="deletePptkName" class="text-primary">-</span>
          </div>
          <div class="col-md-6">
            <strong>Kegiatan:</strong> <span id="deleteKegiatanName" class="text-primary">-</span>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <strong>Jenis Kwitansi:</strong> <span id="deleteJenisKwitansi">-</span>
          </div>
          <div class="col-md-6">
            <strong>Jenis TTD:</strong> <span id="deleteJenisTtd">-</span>
          </div>
        </div>

        <!-- Warning Section -->
        <div id="deleteWarningSection">
          <div class="alert alert-warning">
            <h6 class="alert-heading">⚠️ PERHATIAN!</h6>
            <p class="mb-2"><strong>PPTK tidak akan bisa membuat kwitansi untuk kegiatan ini setelah relasi dihapus.</strong></p>
            <ul class="mb-2">
              <li>PPTK tidak dapat mengakses kegiatan ini lagi</li>
              <li>Relasi dapat dibuat kembali kapan saja</li>
              <li>Tidak mempengaruhi kwitansi yang sudah dibuat</li>
            </ul>
            <p class="mb-0"><strong>Yakin ingin menghapus relasi ini?</strong></p>
          </div>
        </div>

        <input type="hidden" id="deleteId">
        <input type="hidden" id="deleteTipe" value="relasi">
        <input type="hidden" id="deleteCsrf" value="<?= $_SESSION['csrf_token'] ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <span class="spinner-border spinner-border-sm d-none" role="status"></span>
          Ya, Hapus Relasi
        </button>
      </div>
    </div>
  </div>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';
const tableState = {
    relasi: { page: 1, per_page: 10, search: '', search_field: '' }
};

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

function resetFormRelasi() {
    $('#formRelasi')[0].reset();
    $('#relasi_id').val('');
    $('#formRelasi button[type="submit"]').text('Simpan Relasi');
    clearValidationErrors('relasi');
    resetInfoDisplay();
}

function resetInfoDisplay() {
    $('#info_rekening').text('-');
    $('#info_belanja').text('-');
    $('#info_status').text('Belum Ada PPTK').removeClass().addClass('badge bg-secondary');
    $('#info_pptk').text('-');
    $('#ttd_info').text('Default: 3 TTD untuk semua jenis');

    // Reset TTD options
    $('#ttd_3').prop('checked', true);
    $('#ttd_4').prop('disabled', false);

    // Force update info
    setTimeout(updateKegiatanInfo, 100);
}

function clearValidationErrors(prefix = '') {
    if (prefix) {
        $(`#${prefix}_kegiatan_id`).removeClass('is-invalid');
        $(`#${prefix}_pptk_id`).removeClass('is-invalid');
        $(`#${prefix}_jenis_kwitansi`).closest('.form-check').removeClass('is-invalid');
        $(`#${prefix}_kegiatan_id_error`).text('');
        $(`#${prefix}_pptk_id_error`).text('');
        $(`#${prefix}_jenis_kwitansi_error`).text('');
    } else {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }
}

function showValidationErrors(errors, prefix = '') {
    for (const field in errors) {
        const fieldId = prefix ? `${prefix}_${field}` : field;
        const $field = $(`#${fieldId}`);

        $field.addClass('is-invalid');
        $(`#${fieldId}_error`).text(errors[field]);

        // Tambahkan efek shake untuk duplicate errors
        if (errors[field].includes('belum selesai') || errors[field].includes('masih memiliki')) {
            $field.addClass('duplicate-error');
            setTimeout(() => {
                $field.removeClass('duplicate-error');
            }, 500);
        }
        if (errors[field].includes('belum memiliki persyaratan dokumen')) {
            $field.addClass('list-doc-error');
            $(`#${fieldId}_error`).html(errors[field]);
        }
    }
}

function cleanupStuckModals() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('.modal').hide();
    $('body').css({'overflow': '', 'padding-right': ''});
}

// Event handler untuk perubahan jenis kwitansi
$(document).on('change', 'input[name="jenis_kwitansi"]', function() {
    const jenis = $(this).val();
    const $ttd4 = $('#ttd_4');
    const $ttdInfo = $('#ttd_info');

    if (jenis === 'perjalanan_dinas') {
        // Untuk perjalanan dinas, hanya 3 TTD
        $ttd4.prop('disabled', true);
        $ttd4.prop('checked', false);
        $('#ttd_3').prop('checked', true);
        $ttdInfo.text('Perjalanan dinas hanya menggunakan 3 TTD');
    } else {
        // Untuk penyedia, bisa pilih 3 atau 4 TTD
        $ttd4.prop('disabled', false);
        $ttdInfo.text('Pilih 3 TTD atau 4 TTD (+ Penyedia)');
    }
});

// Event handler untuk perubahan kegiatan
$(document).on('change', '#relasi_kegiatan_id', function() {
    updateKegiatanInfo();
});

function updateKegiatanInfo() {
    const selectedOption = $('#relasi_kegiatan_id option:selected');
    const rekening = selectedOption.data('rekening') || '-';
    const belanja = selectedOption.data('belanja') || '-';
    const currentPptk = selectedOption.data('pptk');
    const jenisKwitansi = selectedOption.data('jenis-kwitansi');
    const jenisTtd = selectedOption.data('jenis-ttd');

    $('#info_rekening').text(rekening);
    $('#info_belanja').text(belanja);

    // Update status relasi
    if (currentPptk && currentPptk !== '') {
        $('#info_status').text('Sudah Ada PPTK').removeClass().addClass('badge bg-warning');
        // Cari nama PPTK dari dropdown
        const pptkName = $('#relasi_pptk_id option[value="' + currentPptk + '"]').text();
        $('#info_pptk').text(pptkName || 'Unknown PPTK');

        // Auto-fill form untuk edit
        $('#relasi_pptk_id').val(currentPptk);

        if (jenisKwitansi) {
            $(`input[name="jenis_kwitansi"][value="${jenisKwitansi}"]`).prop('checked', true).trigger('change');
        }

        if (jenisTtd) {
            $(`input[name="jenis_ttd"][value="${jenisTtd}"]`).prop('checked', true);
        }

        // Update submit button text untuk edit mode
        $('#formRelasi button[type="submit"]').text('Update Relasi');
        $('#relasi_id').val($('#relasi_kegiatan_id').val());
    } else {
        $('#info_status').text('Belum Ada PPTK').removeClass().addClass('badge bg-secondary');
        $('#info_pptk').text('-');

        // Reset ke mode tambah baru
        $('#formRelasi button[type="submit"]').text('Simpan Relasi');
        $('#relasi_id').val('');
    }
}

// Load table data dengan pagination dan search
function loadTableData(tipe) {
    const state = tableState[tipe];
    const tableId = `tabel${tipe.charAt(0).toUpperCase() + tipe.slice(1)}`;

    $(`#${tableId} tbody`).html(`
        <tr>
            <td colspan="7" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </td>
        </tr>
    `);

    $.ajax({
        url: BASEURL + "admin/relasi_kegiatan",
        method: 'POST',
        data: {
            a: "data",
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
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <br>
                                    ${state.search ? 'Tidak ada data yang sesuai dengan pencarian' : 'Belum ada data relasi'}
                                </div>
                            </td>
                        </tr>
                    `);
                } else {
                    res.data.forEach(row => {
                        let tr = $("<tr>");

                        // Format data untuk display
                        const ttdText = row.jenis_ttd === '4_ttd' ? '4 TTD (+Penyedia)' : '3 TTD';
                        const jenisKwitansiText = row.jenis_kwitansi === 'perjalanan_dinas' ? 'Perjalanan Dinas' : 'Penyedia Barang';

                        tr.append($("<td>").text(row.nama_kegiatan || '-'));
                        tr.append($("<td>").text(row.nama_pegawai || '-'));
                        tr.append($("<td>").html(`<code class="text-primary">${row.kode_rekening || '-'}</code>`));
                        tr.append($("<td>").text(jenisKwitansiText));
                        tr.append($("<td>").text(ttdText));

                        // Action buttons
                        let actions = `
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-edit" data-id="${row.kegiatan_id}" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                </button>
                                <button class="btn btn-outline-danger btn-delete" data-id="${row.kegiatan_id}" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
            <a class="page-link" href="#" data-page="${current_page - 1}">‹</a>
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
            <a class="page-link" href="#" data-page="${current_page + 1}">›</a>
        </li>
    `);
}

// Event handlers untuk pagination, search, per-page
$(document).on('click', '.pagination-controls .page-link', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    const tipe = $(this).closest('.pagination-controls').data('tipe');
    if (page) {
        tableState[tipe].page = page;
        loadTableData(tipe);
    }
});

$(document).on('click', '.search-btn', function() {
    const tipe = $(this).data('tipe');
    const searchInput = $(`.search-input[data-tipe="${tipe}"]`);
    const searchField = $(`.search-field-select[data-tipe="${tipe}"]`);
    tableState[tipe].search = searchInput.val();
    tableState[tipe].search_field = searchField.val();
    tableState[tipe].page = 1;
    loadTableData(tipe);
});

$(document).on('keypress', '.search-input', function(e) {
    if (e.which === 13) {
        const tipe = $(this).data('tipe');
        $(`.search-btn[data-tipe="${tipe}"]`).click();
    }
});

$(document).on('change', '.per-page-select', function() {
    const tipe = $(this).data('tipe');
    tableState[tipe].per_page = $(this).val();
    tableState[tipe].page = 1;
    loadTableData(tipe);
});

// Event handlers khusus untuk Relasi
$(document).ready(function() {
    // Load initial data
    loadTableData('relasi');

    // Form submission handler
    $("#formRelasi").submit(function(e) {
        e.preventDefault();
        submitForm('relasi');
    });

    // Edit button handler
    $(document).on("click", ".btn-edit", function() {
        const id = $(this).data("id");
        editData(id);
    });

    // Delete button handler
    $(document).on("click", ".btn-delete", function() {
        const id = $(this).data("id");
        const row = $(this).closest('tr');

        const kegiatanName = row.find('td:eq(0)').text();
        const pptkName = row.find('td:eq(1)').text();
        const jenisKwitansi = row.find('td:eq(3)').text();
        const jenisTtd = row.find('td:eq(4)').text();

        showDeleteModal(id, pptkName, kegiatanName, jenisKwitansi, jenisTtd);
    });

    // Confirm delete
    $("#confirmDelete").click(function() {
        const deleteBtn = $(this);
        const id = $("#deleteId").val();
        const csrfToken = $("#deleteCsrf").val();

        // Disable button selama proses
        deleteBtn.prop('disabled', true);
        deleteBtn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: BASEURL + "admin/relasi_kegiatan",
            method: 'POST',
            data: {
                a: "hapus",
                id: id,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function(res) {
                deleteBtn.prop('disabled', false);
                deleteBtn.find('.spinner-border').addClass('d-none');

                if (res.status === "success") {
                    $("#deleteModal").modal("hide");
                    showAlert(res.message, 'success');
                    loadTableData('relasi');
                    resetFormRelasi();
                    reloadKegiatanOptions();
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
        url: BASEURL + "admin/relasi_kegiatan",
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            submitBtn.prop('disabled', false);
            submitBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                // Success case - reset form and reload data
                form[0].reset();
                $('#relasi_id').val('');
                submitBtn.text('Simpan Relasi');
                showAlert(res.message, 'success');
                loadTableData('relasi');
                resetInfoDisplay();
                reloadKegiatanOptions();
                $('html, body').animate({ scrollTop: 0 }, 500);
            } else {
                if (res.errors) {
                    // Tampilkan error validasi di form
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


// Function untuk reload options kegiatan dari server

function reloadKegiatanOptions(editData = null) {
    const select = $('#relasi_kegiatan_id');
    const originalHtml = select.html();
    select.html('<option value="">Loading data...</option>');

    $.ajax({
        url: BASEURL + "admin/relasi_kegiatan",
        method: 'POST',
        data: {
            a: "get_kegiatan_options",
            csrf_token: '<?= $_SESSION['csrf_token'] ?>'
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                // Replace options dengan data terbaru
                select.empty().append('<option value="">Pilih Kegiatan</option>');

                res.data.forEach(kegiatan => {
                    select.append(
                        $('<option>', {
                            value: kegiatan.id,
                            'data-rekening': kegiatan.kode_rekening || '',
                            'data-belanja': kegiatan.nama_belanja || '',
                            'data-pptk': kegiatan.pptk_id || '',
                            'data-jenis-kwitansi': kegiatan.jenis_kwitansi || '',
                            'data-jenis-ttd': kegiatan.jenis_ttd || ''
                        }).text(`${kegiatan.nama_kegiatan} (${kegiatan.kode_rekening} - ${kegiatan.nama_belanja})`)
                    );
                });


                if (editData) {
                    setEditData(editData);
                } else {
                    // Update info normal setelah reload options
                    updateKegiatanInfo();
                }

            } else {
                select.html(originalHtml);
                if (!editData) {
                    showAlert("Gagal memuat data terbaru: " + res.message, 'warning');
                }
            }
        },
        error: function() {
            select.html(originalHtml);
            if (!editData) {
                showAlert("Gagal terhubung ke server", 'danger');
            }
        }
    });
}


function setEditData(editData) {
    // Set nilai dropdown kegiatan
    $('#relasi_kegiatan_id').val(editData.kegiatan_id);

    // Tunggu sebentar untuk memastikan DOM sudah update
    setTimeout(() => {
        // Trigger change event untuk update info display
        $('#relasi_kegiatan_id').trigger('change');

        // Set nilai lainnya
        $('#relasi_pptk_id').val(editData.pptk_id);
        $(`input[name="jenis_kwitansi"][value="${editData.jenis_kwitansi}"]`).prop('checked', true).trigger('change');
        $(`input[name="jenis_ttd"][value="${editData.jenis_ttd}"]`).prop('checked', true);

        // Set info display manual untuk memastikan data benar
        $('#info_rekening').text(editData.kode_rekening || '-');
        $('#info_belanja').text(editData.nama_belanja || '-');
        $('#info_status').text('Sedang Edit').removeClass().addClass('badge bg-info');

        // Cari nama PPTK dari dropdown
        const pptkName = $('#relasi_pptk_id option[value="' + editData.pptk_id + '"]').text();
        $('#info_pptk').text(pptkName || 'Unknown PPTK');

        // Set hidden ID
        $('#relasi_id').val(editData.kegiatan_id);

        // Update submit button
        $('#formRelasi button[type="submit"]').text('Update Relasi');

        console.log('Edit data berhasil di-set:', editData);
    }, 100);
}


function showDeleteModal(id, pptkName, kegiatanName, jenisKwitansi, jenisTtd) {
    $("#deleteId").val(id);
    $("#deletePptkName").text(pptkName);
    $("#deleteKegiatanName").text(kegiatanName);
    $("#deleteJenisKwitansi").text(jenisKwitansi);
    $("#deleteJenisTtd").text(jenisTtd);

    // Show modal
    $("#deleteModal").modal("show");
    cleanupStuckModals();
}

function editData(id) {
    clearValidationErrors('relasi');

    $.ajax({
        url: BASEURL + "admin/relasi_kegiatan",
        method: 'POST',
        data: {
            a: "get",
            id: id
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                const item = res.data;
                const form = $('#formRelasi');

                 const editData = {
                    kegiatan_id: item.kegiatan_id,
                    pptk_id: item.pptk_id,
                    jenis_kwitansi: item.jenis_kwitansi,
                    jenis_ttd: item.jenis_ttd,
                    nama_kegiatan: item.nama_kegiatan,
                    kode_rekening: item.kode_rekening,
                    nama_belanja: item.nama_belanja
                };

                reloadKegiatanOptions(editData);
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

<link rel="stylesheet" href="assets/css/relasi_kegiatan_admin.css">
<?php
}