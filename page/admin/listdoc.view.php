<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Master Relasi Syarat & Rekening')
<div class="container mt-4">

  <h3 class="mb-lg-4">Master Data - Relasi Syarat & Rekening</h3>

  <!-- Alert Container -->
  <div id="alertContainer" data-aos="zoom-in"></div>

  <div class="row">
    <!-- Form Relasi Rekening & Syarat -->
    <div class="col-md-7">
      <div class="card" data-aos="fade-up">
        <div class="card-header">
          <h5 class="card-title mb-0">Relasi Rekening & Syarat</h5>
        </div>
        <div class="card-body">
          <form class="row g-3" id="formListDoc" data-controller="listdoc" data-type="list_doc">
            <input type="hidden" name="id" id="list_doc_id">
            <input type="hidden" name="a" value="simpan">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="syarat_mode" value="existing">

            <div class="col-12">
              <label class="form-label">Rekening & Belanja <span class="text-danger">*</span></label>
              <select class="form-select" name="rekening_id" id="list_doc_rekening_id" required>
                <option value="" selected disabled>Pilih Rekening</option>
                <?php if(isset($data) && is_array($data)): ?>
                <?php foreach($data['rekenings'] as $rekening): ?>
                  <option value="<?= $rekening['id'] ?>">
                    <?= htmlspecialchars($rekening['kode_rekening']) ?> (<?= htmlspecialchars($rekening['nama_belanja']) ?>)
                  </option>
                <?php endforeach; ?>
                <?php else: ?>
                  <option value="" disabled>Data rekening tidak tersedia</option>
                <?php endif; ?>
              </select>
              <div class="invalid-feedback" id="list_doc_rekening_id_error"></div>
            </div>

            <div class="col-12">
              <label class="form-label">Syarat Verifikasi <span class="text-danger">*</span></label>
              <select class="form-select" name="syarat_id" id="list_doc_syarat_id" required>
                <option value="" selected disabled>Pilih Syarat</option>
                <?php if(isset($data) && is_array($data)): ?>
                  <?php foreach($data['syarats'] as $syarat): ?>
                    <option value="<?= $syarat['id'] ?>">
                      <?= $syarat['urutan'] ? $syarat['urutan'] . '. ' : '' ?><?= htmlspecialchars($syarat['nama_syarat']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="" disabled>Data syarat tidak tersedia</option>
                <?php endif; ?>
              </select>
              <div class="invalid-feedback" id="list_doc_syarat_id_error"></div>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary">
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                Simpan Relasi
              </button>
              <button type="button" class="btn btn-secondary" onclick="resetFormListDoc()">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Form Tambah Syarat Baru -->
    <div class="col-md-4">
      <div class="card" data-aos="fade-up">
        <div class="card-header">
          <h5 class="card-title mb-0">Tambah Syarat Baru</h5>
        </div>
        <div class="card-body">
          <form class="row g-3" id="formNewSyarat">
            <input type="hidden" name="a" value="simpan_syarat">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="col-12">
              <label class="form-label">Urutan <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="urutan" id="new_syarat_urutan" placeholder="Masukkan urutan" min="1" required>
              <div class="invalid-feedback" id="new_syarat_urutan_error"></div>
            </div>

            <div class="col-12">
              <label class="form-label">Nama Syarat <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nama_syarat" id="new_syarat_nama" placeholder="Masukkan nama syarat baru" required>
              <div class="invalid-feedback" id="new_syarat_nama_error"></div>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-success">
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                Tambah Syarat
              </button>
              <button type="button" class="btn btn-secondary p-2" onclick="resetFormNewSyarat()">Reset</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <hr class="my-4">

  <div class="card" data-aos="fade-up">
    <div class="card-header">
      <h5 class="card-title mb-0">Daftar Relasi Syarat & Rekening</h5>
    </div>
    <div class="card-body">
      <!-- Search and Filter Section -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" class="form-control search-input" placeholder="Cari kode rekening, nama belanja, atau syarat..." data-tipe="listdoc">
            <button class="btn btn-outline-primary search-btn" type="button" data-tipe="listdoc">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              Cari
            </button>
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select search-field-select" data-tipe="listdoc">
            <option value="">Semua Field</option>
            <option value="kode_rekening">Kode Rekening</option>
            <option value="nama_belanja">Nama Belanja</option>
            <option value="nama_syarat">Nama Syarat</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select per-page-select" data-tipe="listdoc">
            <option value="10">10 per halaman</option>
            <option value="25">25 per halaman</option>
            <option value="50">50 per halaman</option>
            <option value="100">100 per halaman</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelListdoc">
          <thead class="table-light">
            <tr>
              <th width="25%">Kode Rekening</th>
              <th width="35%">Nama Belanja</th>
              <th width="30%">Syarat Verifikasi</th>
              <th width="10%">Aksi</th>
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
          <div class="pagination-info" data-tipe="listdoc"></div>
        </div>
        <div class="col-md-6">
          <nav>
            <ul class="pagination justify-content-end pagination-controls" data-tipe="listdoc"></ul>
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
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
          </svg>
          Konfirmasi Hapus Relasi
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <strong>Data yang akan dihapus:</strong>
        </div>

        <div id="deleteDetails" class="mb-3">
          <!-- Detail relasi akan diisi oleh JavaScript -->
        </div>

        <p class="text-danger mb-0">
          <strong>PERHATIAN:</strong> Data yang sudah dihapus tidak dapat dikembalikan.
        </p>

        <input type="hidden" id="deleteId">
        <input type="hidden" id="deleteTipe" value="listdoc">
        <input type="hidden" id="deleteCsrf" value="<?= $_SESSION['csrf_token'] ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <span class="spinner-border spinner-border-sm d-none" role="status"></span>
          Ya, Hapus Data
        </button>
      </div>
    </div>
  </div>
</div>

<script>
    const BASEURL = '<?= BASEURL ?>';
    const tableState = {
        listdoc: { page: 1, per_page: 10, search: '', search_field: '' }
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

    function resetFormListDoc() {
        $('#formListDoc')[0].reset();
        $('#list_doc_id').val('');
        $('#formListDoc button[type="submit"]').text('Simpan Relasi');
        clearValidationErrors('listdoc');
    }

    function resetFormNewSyarat() {
        $('#formNewSyarat')[0].reset();
        $('#formNewSyarat button[type="submit"]').text('Tambah Syarat');
        clearValidationErrors('newsyarat');
    }

    function clearValidationErrors(prefix = '') {
        if (prefix) {
            $(`#${prefix}_rekening_id`).removeClass('is-invalid');
            $(`#${prefix}_syarat_id`).removeClass('is-invalid');
            $('#new_syarat_urutan').removeClass('is-invalid');
            $('#new_syarat_nama').removeClass('is-invalid');
            $(`#${prefix}_rekening_id_error`).text('');
            $(`#${prefix}_syarat_id_error`).text('');
            $('#new_syarat_urutan_error').text('');
            $('#new_syarat_nama_error').text('');
        } else {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }
    }

    function showValidationErrors(errors, prefix = '') {
        for (const field in errors) {
            let fieldId, errorId;

            if (prefix === 'newsyarat') {
                fieldId = field === 'urutan' ? 'new_syarat_urutan' : 'new_syarat_nama';
                errorId = fieldId + '_error';
            } else {
                fieldId = prefix ? `${prefix}_${field}` :  `list_doc_${field}`;
                errorId = fieldId + '_error';
            }

            $(`#${fieldId}`).addClass('is-invalid');
            $(`#${errorId}`).text(errors[field]);
        }
    }

    function cleanupStuckModals() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('.modal').hide();
        $('body').css({'overflow': '', 'padding-right': ''});
    }

    function refreshSyaratDropdown() {
        $.ajax({
            url: BASEURL + "admin/listdoc",
            method: 'POST',
            data: {
                a: "get_rekening_syarat"
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === "success") {
                    const syaratSelect = $('#list_doc_syarat_id');
                    syaratSelect.empty().append('<option value="" selected disabled>Pilih Syarat</option>');

                    res.data.syarats.forEach(syarat => {
                        syaratSelect.append(
                            $('<option>', {
                                value: syarat.id,
                                text: (syarat.urutan ? syarat.urutan + '. ' : '') + syarat.nama_syarat
                            })
                        );
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Gagal memuat data syarat:', error);
            }
        });
    }

    function showDeleteModal(id) {
        $("#deleteDetails").html(`
            <div class="text-center">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Memuat detail data...
            </div>
        `);

        $("#deleteId").val(id);

        $.ajax({
            url: BASEURL + "admin/listdoc",
            method: 'POST',
            data: {
                a: "get",
                id: id
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === "success") {
                    const item = res.data;

                    const details = `
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Kode Rekening:</strong><br>
                                <code class="text-primary">${item.kode_rekening || '-'}</code>
                            </div>
                            <div class="col-md-6">
                                <strong>Nama Belanja:</strong><br>
                                ${item.nama_belanja || '-'}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <strong>Syarat Verifikasi:</strong><br>
                                ${item.urutan ? `<strong>${item.urutan}.</strong> ` : ''}${item.nama_syarat || '-'}
                            </div>
                        </div>
                    `;

                    $("#deleteDetails").html(details);
                } else {
                    $("#deleteDetails").html(`
                        <div class="alert alert-warning">
                            Gagal memuat detail data: ${res.message}
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                $("#deleteDetails").html(`
                    <div class="alert alert-danger">
                        Error saat memuat detail data. Silakan coba lagi.
                    </div>
                `);
            }
        });

        $("#deleteModal").modal("show");
        cleanupStuckModals();
    }

    // Load table data dengan pagination dan search
    function loadTableData(tipe) {
        const state = tableState[tipe];
        const tableId = `tabel${tipe.charAt(0).toUpperCase() + tipe.slice(1).replace('_', '')}`;

        $(`#${tableId} tbody`).html(`
            <tr>
                <td colspan="4" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </td>
            </tr>
        `);

        $.ajax({
            url: BASEURL + "admin/listdoc",
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
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        <br>
                                        ${state.search ? 'Tidak ada data yang sesuai dengan pencarian' : 'Belum ada data relasi syarat & rekening'}
                                    </div>
                                </td>
                            </tr>
                        `);
                    } else {
                        res.data.forEach(row => {
                            let tr = $("<tr>");

                            tr.append($("<td>").html(`
                                <code class="text-primary">${row.kode_rekening || '-'}</code>
                            `));

                            tr.append($("<td>").text(row.nama_belanja || '-'));

                            const syaratText = row.urutan ?
                                `<strong>${row.urutan}.</strong> ${row.nama_syarat || '-'}` :
                                (row.nama_syarat || '-');
                            tr.append($("<td>").html(syaratText));

                            let actions = `
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-edit" data-id="${row.id}" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn btn-outline-danger btn-delete" data-id="${row.id}" title="Hapus">
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

        const start = ((current_page - 1) * per_page) + 1;
        const end = Math.min(current_page * per_page, total_rows);
        $(`.pagination-info[data-tipe="${tipe}"]`).html(`
            Menampilkan ${start} - ${end} dari ${total_rows} data
        `);

        const paginationEl = $(`.pagination-controls[data-tipe="${tipe}"]`).empty();

        paginationEl.append(`
            <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${current_page - 1}">‹</a>
            </li>
        `);

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

        paginationEl.append(`
            <li class="page-item ${current_page === total_pages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${current_page + 1}">›</a>
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

    // Event handlers untuk per page change
    $(document).on('change', '.per-page-select', function() {
        const tipe = $(this).data('tipe');
        tableState[tipe].per_page = $(this).val();
        tableState[tipe].page = 1;
        loadTableData(tipe);
    });

    $(document).ready(function() {
        // Load initial data
        loadTableData('listdoc');

        // Form submission handler untuk relasi
        $("#formListDoc").submit(function(e) {
            e.preventDefault();
            submitFormRelasi('listdoc');
        });

        // Form submission handler untuk syarat baru
        $("#formNewSyarat").submit(function(e) {
            e.preventDefault();
            submitFormNewSyarat();
        });

        // Edit button handler
        $(document).on("click", ".btn-edit", function() {
            const id = $(this).data("id");
            editData(id);
        });

        $(document).on("click", ".btn-delete", function() {
            const id = $(this).data("id");
            showDeleteModal(id);
            cleanupStuckModals();
        });

        // Confirm delete
        $("#confirmDelete").click(function() {
            const deleteBtn = $(this);
            const id = $("#deleteId").val();
            const csrfToken = $("#deleteCsrf").val();

            deleteBtn.prop('disabled', true);
            deleteBtn.find('.spinner-border').removeClass('d-none');

            $.ajax({
                url: BASEURL + "admin/listdoc",
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
                        loadTableData('listdoc');
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

    function showDuplicateAlert(errors) {
        let errorMessages = [];
        if (errors.rekening_id) errorMessages.push(errors.rekening_id);
        if (errors.syarat_id) errorMessages.push(errors.syarat_id);

        const uniqueMessages = [...new Set(errorMessages)];

        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" data-aos="zoom-in">
                <h5 class="alert-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    Data Duplikat Ditemukan
                </h5>
                <p class="mb-2">Tidak dapat menyimpan relasi karena:</p>
                <ul class="mb-2">
                    ${uniqueMessages.map(msg => `<li>${msg}</li>`).join('')}
                </ul>
                <p class="mb-0"><strong>Silakan pilih rekening atau syarat yang berbeda.</strong></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        $('#alertContainer').html(alertHtml);

        $('html, body').animate({ scrollTop: 0 }, 500);
        setTimeout(() => $('#alertContainer .alert').alert('close'), 5000);
    }

    function submitFormRelasi(tipe) {
        clearValidationErrors(tipe);

        const form = $('#formListDoc');
        const formData = form.serializeArray();
        const data = {};
        formData.forEach(f => data[f.name] = f.value);

        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: BASEURL + "admin/listdoc",
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                submitBtn.prop('disabled', false);
                submitBtn.find('.spinner-border').addClass('d-none');

                if (res.status === "success") {
                    form[0].reset();
                    $(`#${tipe}_id`).val('');
                    submitBtn.text('Simpan Relasi');
                    showAlert(res.message, 'success');
                    loadTableData(tipe);
                    $('html, body').animate({ scrollTop: 0 }, 500);
                } else {
                    if (res.errors) {
                        showValidationErrors(res.errors, tipe);
                        if (res.errors.rekening_id && res.errors.rekening_id.includes('sudah ada') ||
                            res.errors.syarat_id && res.errors.syarat_id.includes('terdaftar')) {
                            showDuplicateAlert(res.errors);
                        }
                        $('html, body').animate({ scrollTop: 0 }, 500);
                    } else {
                        showAlert("Gagal simpan: " + res.message, 'danger');
                        $('html, body').animate({ scrollTop: 0 }, 500);
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

    function submitFormNewSyarat() {
        clearValidationErrors('newsyarat');

        const form = $('#formNewSyarat');
        const formData = form.serializeArray();
        const data = {};
        formData.forEach(f => data[f.name] = f.value);

        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: BASEURL + "admin/listdoc",
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                submitBtn.prop('disabled', false);
                submitBtn.find('.spinner-border').addClass('d-none');

                if (res.status === "success") {
                    form[0].reset();
                    submitBtn.text('Tambah Syarat');
                    showAlert(res.message, 'success');
                    refreshSyaratDropdown();
                    $('html, body').animate({ scrollTop: 0 }, 500);
                } else {
                    if (res.errors) {
                        showValidationErrors(res.errors, 'newsyarat');
                        $('html, body').animate({ scrollTop: 0 }, 500);
                    } else {
                        showAlert("Gagal menambah syarat: " + res.message, 'danger');
                        $('html, body').animate({ scrollTop: 0 }, 500);
                    }
                }
            },
            error: function(xhr, status, error) {
                submitBtn.prop('disabled', false);
                submitBtn.find('.spinner-border').addClass('d-none');
                showAlert("Server error saat menambah syarat.", 'danger');
            }
        });
    }

    function editData(id) {
        clearValidationErrors('listdoc');

        $.ajax({
            url: BASEURL + "admin/listdoc",
            method: 'POST',
            data: {
                a: "get",
                id: id
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === "success") {
                    const item = res.data;
                    const form = $('#formListDoc');

                    $('#list_doc_id').val(item.id);
                    $('#list_doc_rekening_id').val(item.rekening_id);
                    $('#list_doc_syarat_id').val(item.syarat_id);

                    form.find('button[type="submit"]').text('Update Relasi');

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
<link rel="stylesheet" href="assets/css/listdoc_admin.css">
<?php
}