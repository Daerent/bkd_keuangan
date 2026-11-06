<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Riwayat Verifikasi')
<div class="container mt-4">

  <h3 class="mb-lg-4">Riwayat Verifikasi Kwitansi</h3>

  <!-- Alert Container -->
  <div id="alertContainer" data-aos="zoom-in"></div>

    <!-- Status Cards -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card bg-success text-white clickable-card" data-aos="fade-right" data-target="disetujui">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h4 class="card-title" id="disetujuiCount"><?= $data['disetujui_count'] ?></h4>
              <p class="card-text mb-0">Total Disetujui</p>
              <small class="opacity-75">Klik untuk lihat detail →</small>
            </div>
            <div class="display-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card bg-danger text-white clickable-card" data-aos="fade-left" data-target="ditolak">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h4 class="card-title" id="ditolakCount"><?= $data['ditolak_count'] ?></h4>
              <p class="card-text mb-0">Total Ditolak</p>
              <small class="opacity-75">Klik untuk lihat detail →</small>
            </div>
            <div class="display-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Approved Kwitansi Section -->
  <div class="card mb-5" data-aos="fade-up">
    <div class="card-header bg-success text-white">
      <h5 class="card-title mb-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        Kwitansi Disetujui
      </h5>
    </div>
    <div class="card-body">
      <!-- Search and Filter Section -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" class="form-control search-input" placeholder="Cari PPTK, kegiatan, atau rekening..." data-tipe="disetujui">
            <button class="btn btn-outline-success search-btn" type="button" data-tipe="disetujui">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              Cari
            </button>
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select search-field-select" data-tipe="disetujui">
            <option value="">Semua Field</option>
            <option value="nama_pegawai">PPTK</option>
            <option value="nama_kegiatan">Kegiatan</option>
            <option value="kode_rekening">Kode Rekening</option>
            <option value="nama_belanja">Nama Belanja</option>
            <option value="no_kwitansi">No Kwitansi</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select per-page-select" data-tipe="disetujui">
            <option value="10">10 per halaman</option>
            <option value="25">25 per halaman</option>
            <option value="50">50 per halaman</option>
            <option value="100">100 per halaman</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelDisetujui">
          <thead class="table-light">
            <tr>
              <th width="8%">Aksi</th>
              <th width="12%">No Kwitansi</th>
              <th width="15%">PPTK</th>
              <th width="18%">Kegiatan</th>
              <th width="12%">Rekening</th>
              <th width="13%">Jenis</th>
              <th width="10%">Total</th>
              <th width="12%">Tanggal</th>
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
          <div class="pagination-info" data-tipe="disetujui"></div>
        </div>
        <div class="col-md-6">
          <nav>
            <ul class="pagination justify-content-end pagination-controls" data-tipe="disetujui"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Rejected Kwitansi Section -->
  <div class="card" data-aos="fade-up">
    <div class="card-header bg-danger text-white">
      <h5 class="card-title mb-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="15" y1="9" x2="9" y2="15"></line>
          <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        Kwitansi Ditolak
      </h5>
    </div>
    <div class="card-body">
      <!-- Search and Filter Section -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" class="form-control search-input" placeholder="Cari PPTK, kegiatan, atau rekening..." data-tipe="ditolak">
            <button class="btn btn-outline-danger search-btn" type="button" data-tipe="ditolak">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              Cari
            </button>
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select search-field-select" data-tipe="ditolak">
            <option value="">Semua Field</option>
            <option value="nama_pegawai">PPTK</option>
            <option value="nama_kegiatan">Kegiatan</option>
            <option value="kode_rekening">Kode Rekening</option>
            <option value="nama_belanja">Nama Belanja</option>
            <option value="no_kwitansi">No Kwitansi</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select per-page-select" data-tipe="ditolak">
            <option value="10">10 per halaman</option>
            <option value="25">25 per halaman</option>
            <option value="50">50 per halaman</option>
            <option value="100">100 per halaman</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelDitolak">
          <thead class="table-light">
            <tr>
              <th width="8%">Aksi</th>
              <th width="12%">No Kwitansi</th>
              <th width="15%">PPTK</th>
              <th width="18%">Kegiatan</th>
              <th width="12%">Rekening</th>
              <th width="13%">Jenis</th>
              <th width="10%">Total</th>
              <th width="12%">Tanggal</th>
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
          <div class="pagination-info" data-tipe="ditolak"></div>
        </div>
        <div class="col-md-6">
          <nav>
            <ul class="pagination justify-content-end pagination-controls" data-tipe="ditolak"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Detail View Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="detailModalLabel">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
          </svg>
          Detail Riwayat Kwitansi
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="detailLoading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Memuat detail kwitansi...</p>
        </div>

        <div id="detailContent" style="display: none;">
          <!-- Status Badge -->
          <div class="row mb-4">
            <div class="col-12">
              <div id="statusBadge" class="text-center">
                <!-- Status badge akan diisi oleh JavaScript -->
              </div>
            </div>
          </div>

          <!-- Detail Kwitansi -->
          <div class="card mb-4">
            <div class="card-header bg-light">
              <h6 class="card-title mb-0">Informasi Kwitansi</h6>
            </div>
            <div class="card-body">
              <div class="row" id="kwitansiInfo">
                <!-- Informasi kwitansi akan diisi oleh JavaScript -->
              </div>
            </div>
          </div>

          <!-- Keterangan (jika ada) -->
          <div id="keteranganSection" class="card mb-4" style="display: none;">
            <div class="card-header bg-light">
              <h6 class="card-title mb-0">Keterangan</h6>
            </div>
            <div class="card-body">
              <div id="keteranganContent">
                <!-- Keterangan akan diisi oleh JavaScript -->
              </div>
            </div>
          </div>

          <!-- Daftar Syarat -->
          <div class="card">
            <div class="card-header bg-light">
              <h6 class="card-title mb-0">Dokumen Persyaratan</h6>
            </div>
            <div class="card-body">
              <div id="syaratList">
                <!-- Daftar syarat akan diisi oleh JavaScript -->
              </div>
            </div>
          </div>
        </div>

        <div id="detailError" class="alert alert-danger text-center" style="display: none;">
          <!-- Error message akan diisi oleh JavaScript -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
          </svg>
          Konfirmasi Hapus Riwayat
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>PERHATIAN:</strong> Tindakan ini tidak dapat dibatalkan!
        </div>

        <div id="deleteDetails" class="mb-3">
          <!-- Detail yang akan dihapus akan diisi oleh JavaScript -->
        </div>

        <p class="text-danger mb-0">
          <strong>⚠️ Data yang dihapus termasuk semua dokumen terkait akan hilang permanen.</strong>
        </p>

        <input type="hidden" id="deleteId">
        <input type="hidden" id="deleteTipe">
        <input type="hidden" id="deleteCsrf" value="<?= $_SESSION['csrf_token'] ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <span class="spinner-border spinner-border-sm d-none" role="status"></span>
          Ya, Hapus Permanen
        </button>
      </div>
    </div>
  </div>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';
const tableState = {
    disetujui: { page: 1, per_page: 10, search: '', search_field: '' },
    ditolak: { page: 1, per_page: 10, search: '', search_field: '' }
};

let currentKwitansiId = null;
let currentTableType = null;

// ==================== GLOBAL FUNCTIONS ====================
function showAlert(message, type = 'success') {
    const alert = `<div class="alert alert-${type} alert-dismissible fade show" data-aos="zoom-in">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    $('#alertContainer').html(alert);

    setTimeout(() => $('#alertContainer .alert').alert('close'), 5000);
}

function cleanupStuckModals() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('.modal').hide();
    $('body').css({'overflow': '', 'padding-right': ''});
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// ==================== TABLE MANAGEMENT ====================
function loadTableData(tipe) {
    const state = tableState[tipe];
    const tableId = `tabel${tipe.charAt(0).toUpperCase() + tipe.slice(1)}`;

    $(`#${tableId} tbody`).html(`
        <tr>
            <td colspan="8" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data...</p>
            </td>
        </tr>
    `);

    $.ajax({
        url: BASEURL + "admin/riwayat_ver",
        method: 'POST',
        data: {
            a: `data_${tipe}`,
            page: state.page,
            per_page: state.per_page,
            search: state.search,
            search_field: state.search_field
        },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                renderTableData(tipe, tableId, res.data, res.pagination);
            } else {
                showTableError(tipe, tableId, res.message);
            }
        },
        error: function(xhr, status, error) {
            showTableError(tipe, tableId, "Server error saat mengambil data.");
        }
    });
}

function renderTableData(tipe, tableId, data, pagination) {
    let tbody = $(`#${tableId} tbody`).empty();

    if (data.length === 0) {
        const searchTerm = tableState[tipe].search;
        tbody.append(`
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <br>
                        ${searchTerm ?
                            `Tidak ada data yang sesuai dengan pencarian "<strong>${searchTerm}</strong>"` :
                            'Belum ada data riwayat'
                        }
                    </div>
                </td>
            </tr>
        `);
    } else {
        data.forEach(row => {
            tbody.append(createTableRow(tipe, row));
        });
    }

    updatePaginationControls(tipe, pagination);
}

function createTableRow(tipe, row) {
    const statusClass = tipe === 'disetujui' ? 'success' : 'danger';
    const statusIcon = tipe === 'disetujui' ? '✓' : '✕';

    return `
        <tr data-kwitansi-id="${row.kwitansi_id}" data-type="${tipe}">
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-detail" data-id="${row.kwitansi_id}" data-type="${tipe}" title="Lihat Detail">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                    <button class="btn btn-outline-danger btn-delete" data-id="${row.kwitansi_id}" data-type="${tipe}" title="Hapus Riwayat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
            </td>
            <td>
                <code class="text-primary">${row.no_kwitansi || '-'}</code>
                <br>
                <span class="badge bg-${statusClass}">${statusIcon} ${tipe === 'disetujui' ? 'Disetujui' : 'Ditolak'}</span>
            </td>
            <td>${row.pptk || '-'}</td>
            <td>${row.nama_kegiatan || '-'}</td>
            <td><small><code>${row.kode_rekening || '-'}</code></small></td>
            <td>
                ${row.jenis_kwitansi || '-'}
                ${row.jenis_ttd ? `<br><small class="text-muted">${row.jenis_ttd}</small>` : ''}
            </td>
            <td><strong>${formatCurrency(row.total_uang || 0)}</strong></td>
            <td>${formatDate(row.tgl_kwitansi)}</td>
        </tr>
    `;
}

function showTableError(tipe, tableId, message) {
    $(`#${tableId} tbody`).html(`
        <tr>
            <td colspan="8" class="text-center py-4">
                <div class="alert alert-danger">
                    <strong>Error:</strong> ${message}
                </div>
                <button class="btn btn-sm btn-outline-primary mt-2" onclick="loadTableData('${tipe}')">
                    Coba Lagi
                </button>
            </td>
        </tr>
    `);
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

// ==================== MODAL MANAGEMENT ====================
function showDetailModal(kwitansiId, type) {
    currentKwitansiId = kwitansiId;
    currentTableType = type;

    // Reset modal state
    $('#detailLoading').show();
    $('#detailContent').hide();
    $('#detailError').hide();
    $('#keteranganSection').hide();

    // Clear previous content
    $('#statusBadge').empty();
    $('#kwitansiInfo').empty();
    $('#syaratList').empty();
    $('#keteranganContent').empty();

    // Fetch detail data
    $.ajax({
        url: BASEURL + "admin/riwayat_ver",
        method: 'POST',
        data: {
            a: "get_detail",
            id: kwitansiId
        },
        dataType: 'json',
        success: function(res) {
            $('#detailLoading').hide();

            if (res.status === "success") {
                $('#detailContent').show();
                displayKwitansiInfo(res.data.kwitansi, type);
                displaySyaratList(res.data.syarats);

                // Show keterangan if exists
                if (res.data.kwitansi.keterangan) {
                    $('#keteranganSection').show();
                    $('#keteranganContent').html(`
                        <p class="mb-0">${res.data.kwitansi.keterangan}</p>
                    `);
                }
            } else {
                $('#detailError').show().html(`
                    <strong>Error:</strong> ${res.message}
                `);
            }
        },
        error: function(xhr, status, error) {
            $('#detailLoading').hide();
            $('#detailError').show().html(`
                <strong>Server Error:</strong> Gagal memuat detail kwitansi. Silakan coba lagi.
            `);
        }
    });

    $("#detailModal").modal("show");
    cleanupStuckModals();
}

function displayKwitansiInfo(kwitansi, type) {
    const statusClass = type === 'disetujui' ? 'success' : 'danger';
    const statusText = type === 'disetujui' ? 'DISETUJUI' : 'DITOLAK';

    // Status badge
    $('#statusBadge').html(`
        <span class="badge bg-${statusClass} fs-6 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                ${type === 'disetujui' ?
                    '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>' :
                    '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>'
                }
            </svg>
            STATUS: ${statusText}
        </span>
    `);

    const infoHtml = `
        <div class="col-md-6">
            <table class="table table-sm">
                <tr>
                    <td width="40%"><strong>No Kwitansi:</strong></td>
                    <td><code class="text-primary">${kwitansi.no_kwitansi || '-'}</code></td>
                </tr>
                <tr>
                    <td><strong>PPTK:</strong></td>
                    <td>${kwitansi.pptk || '-'} <small class="text-muted">${kwitansi.nip_pptk ? `(${kwitansi.nip_pptk})` : ''}</small></td>
                </tr>
                <tr>
                    <td><strong>Kegiatan:</strong></td>
                    <td>${kwitansi.nama_kegiatan || '-'}</td>
                </tr>
                <tr>
                    <td><strong>Rekening:</strong></td>
                    <td><code>${kwitansi.kode_rekening || '-'}</code> - ${kwitansi.nama_belanja || '-'}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm">
                <tr>
                    <td width="40%"><strong>Jenis Kwitansi:</strong></td>
                    <td>${kwitansi.jenis_kwitansi || '-'} <small class="text-muted">${kwitansi.jenis_ttd ? `(${kwitansi.jenis_ttd})` : ''}</small></td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td><strong class="text-success">${formatCurrency(kwitansi.total_uang || 0)}</strong></td>
                </tr>
                <tr>
                    <td><strong>Tanggal Kwitansi:</strong></td>
                    <td>${formatDate(kwitansi.tgl_kwitansi)}</td>
                </tr>
                <tr>
                    <td><strong>Diverifikasi:</strong></td>
                    <td>${formatDate(kwitansi.created_at)}</td>
                </tr>
            </table>
        </div>
        ${kwitansi.nama_penyedia ? `
        <div class="col-12 mt-3">
            <div class="alert alert-info">
                <strong>Informasi Penyedia:</strong><br>
                ${kwitansi.nama_perusahaan ? `Perusahaan: ${kwitansi.nama_perusahaan}<br>` : ''}
                ${kwitansi.nama_penyedia ? `Penyedia: ${kwitansi.nama_penyedia}<br>` : ''}
                ${kwitansi.jabatan_penyedia ? `Jabatan: ${kwitansi.jabatan_penyedia}` : ''}
            </div>
        </div>
        ` : ''}
    `;

    $('#kwitansiInfo').html(infoHtml);
}

function displaySyaratList(syarats) {
    if (syarats.length === 0) {
        $('#syaratList').html(`
            <div class="alert alert-warning text-center">
                Tidak ada dokumen persyaratan untuk kwitansi ini.
            </div>
        `);
        return;
    }

    let syaratHtml = '';
    let uploadedCount = 0;

    syarats.forEach(syarat => {
        const statusClass = syarat.is_uploaded ? 'success' : 'secondary';
        const statusIcon = syarat.is_uploaded ? '✓' : '−';
        const statusText = syarat.is_uploaded ? 'Tersedia' : 'Tidak tersedia';

        if (syarat.is_uploaded) uploadedCount++;

        syaratHtml += `
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">${syarat.urutan || '?'}</span>
                        <div>
                            <h6 class="mb-1">${syarat.nama_syarat || '-'}</h6>
                            ${syarat.dokumen_file ? `
                                <small class="text-muted">
                                    File: ${syarat.nama_dokumen || 'Dokumen'}
                                    <a href="${BASEURL}uploads/${syarat.dokumen_file}" target="_blank" class="ms-1 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        Lihat Dokumen
                                    </a>
                                </small>
                            ` : ''}
                        </div>
                    </div>
                </div>
                <div>
                    <span class="badge bg-${statusClass}">
                        ${statusIcon} ${statusText}
                    </span>
                </div>
            </div>
        `;
    });

    // Add summary
    const totalCount = syarats.length;

    syaratHtml = `
        <div class="alert ${uploadedCount === totalCount ? 'alert-success' : 'alert-info'}">
            <strong>Ringkasan Dokumen:</strong> ${uploadedCount} dari ${totalCount} dokumen tersedia
        </div>
        ${syaratHtml}
    `;

    $('#syaratList').html(syaratHtml);
}

function showDeleteModal(kwitansiId, type) {
    currentKwitansiId = kwitansiId;
    currentTableType = type;

    // Get row data for confirmation message
    const row = $(`tr[data-kwitansi-id="${kwitansiId}"]`);
    const noKwitansi = row.find('td:eq(1) code').text();
    const pptk = row.find('td:eq(2)').text();
    const kegiatan = row.find('td:eq(3)').text();

    $("#deleteDetails").html(`
        <div class="row">
            <div class="col-12">
                <strong>Data yang akan dihapus:</strong>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <strong>No Kwitansi:</strong><br>
                <code class="text-danger">${noKwitansi}</code>
            </div>
            <div class="col-md-6">
                <strong>PPTK:</strong><br>
                ${pptk}
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <strong>Kegiatan:</strong><br>
                ${kegiatan}
            </div>
        </div>
    `);

    $("#deleteId").val(kwitansiId);
    $("#deleteTipe").val(type);
    $("#deleteModal").modal("show");
    cleanupStuckModals();
}

function deleteKwitansi(kwitansiId, type) {
    const deleteBtn = $("#confirmDelete");
    deleteBtn.prop('disabled', true);
    deleteBtn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: BASEURL + "admin/riwayat_ver",
        method: 'POST',
        data: {
            a: "hapus",
            id: kwitansiId,
            csrf_token: '<?= $_SESSION['csrf_token'] ?>'
        },
        dataType: 'json',
        success: function(res) {
            deleteBtn.prop('disabled', false);
            deleteBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                $("#deleteModal").modal("hide");
                showAlert(res.message, 'success');

                // Remove the row with animation
                $(`tr[data-kwitansi-id="${kwitansiId}"]`).fadeOut(300, function() {
                    $(this).remove();

                    // Reload table to refresh counts and handle empty state
                    loadTableData(type);

                    // Update card counts
                    updateCardCounts();
                });
            } else {
                showAlert("Gagal menghapus: " + res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            deleteBtn.prop('disabled', false);
            deleteBtn.find('.spinner-border').addClass('d-none');
            showAlert("Server error saat menghapus.", 'danger');
        }
    });
}

function updateCardCounts() {
    // Simple count update based on remaining rows
    const disetujuiCount = $('#tabelDisetujui tbody tr[data-kwitansi-id]').length;
    const ditolakCount = $('#tabelDitolak tbody tr[data-kwitansi-id]').length;

    $('#disetujuiCount').text(disetujuiCount);
    $('#ditolakCount').text(ditolakCount);
}

// ==================== CARD CLICK HANDLERS ====================
function setupCardClickHandlers() {
    $('.clickable-card').on('click', function() {
        const target = $(this).data('target');
        scrollToTable(target);
    });
}

function scrollToTable(tableType) {
    const targetElement = tableType === 'disetujui'
        ? document.getElementById('tabelDisetujui')
        : document.getElementById('tabelDitolak');

    if (targetElement) {
        // Add highlight effect
        const cardSection = targetElement.closest('.card');
        cardSection.classList.add('highlight-section');

        // Smooth scroll to the table
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        // Remove highlight after animation
        setTimeout(() => {
            cardSection.classList.remove('highlight-section');
        }, 2000);

        // Optional: Auto-expand if collapsed (jika ada fitur collapse)
        expandTableSection(tableType);
    }
}

function expandTableSection(tableType) {
    // Jika menggunakan collapse bootstrap, buka section yang sesuai
    const collapseElement = $(`#collapse${tableType.charAt(0).toUpperCase() + tableType.slice(1)}`);
    if (collapseElement.length && !collapseElement.hasClass('show')) {
        collapseElement.collapse('show');
    }
}

// Tambahkan CSS untuk highlight effect
const highlightStyle = `
.highlight-section {
    animation: highlight-pulse 2s ease-in-out;
    border: 2px solid #0d6efd !important;
}

@keyframes highlight-pulse {
    0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
    50% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
}
`;

// Inject styles
$('head').append(`<style>${highlightStyle}</style>`);

// Initialize card click handlers ketika document ready
$(document).ready(function() {
    setupCardClickHandlers();

    // Juga load initial data untuk kedua tabel
    loadTableData('disetujui');
    loadTableData('ditolak');
});
// ==================== EVENT HANDLERS ====================
$(document).ready(function() {
    // Load initial data for both tables
    loadTableData('disetujui');
    loadTableData('ditolak');

    // Detail button handler
    $(document).on("click", ".btn-detail", function() {
        const id = $(this).data("id");
        const type = $(this).data("type");
        showDetailModal(id, type);
    });

    // Delete button handler
    $(document).on("click", ".btn-delete", function() {
        const id = $(this).data("id");
        const type = $(this).data("type");
        showDeleteModal(id, type);
    });

    // Confirm delete handler
    $("#confirmDelete").click(function() {
        const id = $("#deleteId").val();
        const type = $("#deleteTipe").val();

        if (confirm("Apakah Anda yakin ingin menghapus permanen riwayat ini?")) {
            deleteKwitansi(id, type);
        }
    });

    // Pagination handlers
    $(document).on('click', '.pagination-controls .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        const tipe = $(this).closest('.pagination-controls').data('tipe');
        if (page) {
            tableState[tipe].page = page;
            loadTableData(tipe);
        }
    });

    // Search handlers
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

    // Per-page change handlers
    $(document).on('change', '.per-page-select', function() {
        const tipe = $(this).data('tipe');
        tableState[tipe].per_page = $(this).val();
        tableState[tipe].page = 1;
        loadTableData(tipe);
    });
});
</script>

<link rel="stylesheet" href="assets/css/riwayat_ver_admin.css">
<?php
}