<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Verifikasi Kwitansi')
<div class="container mt-4">

  <h3 class="mb-lg-4">Verifikasi Kwitansi</h3>

  <!-- Alert Container -->
  <div id="alertContainer" data-aos="zoom-in"></div>

  <!-- Status Cards -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card bg-success text-white" data-aos="fade-right">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h4 class="card-title"><?= $data['disetujui_count'] ?></h4>
              <p class="card-text mb-0">Kwitansi Disetujui</p>
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
      <div class="card bg-danger text-white" data-aos="fade-left">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h4 class="card-title"><?= $data['ditolak_count'] ?></h4>
              <p class="card-text mb-0">Kwitansi Ditolak</p>
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

  <div class="card" data-aos="fade-up">
    <div class="card-header">
      <h5 class="card-title mb-0">Daftar Kwitansi Menunggu Verifikasi</h5>
    </div>
    <div class="card-body">
      <!-- Search and Filter Section -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" class="form-control search-input" placeholder="Cari PPTK, kegiatan, atau rekening..." data-tipe="verifikasi">
            <button class="btn btn-outline-primary search-btn" type="button" data-tipe="verifikasi">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              Cari
            </button>
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select search-field-select" data-tipe="verifikasi">
            <option value="">Semua Field</option>
            <option value="nama_pegawai">PPTK</option>
            <option value="nama_kegiatan">Kegiatan</option>
            <option value="kode_rekening">Kode Rekening</option>
            <option value="nama_belanja">Nama Belanja</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select per-page-select" data-tipe="verifikasi">
            <option value="10">10 per halaman</option>
            <option value="25">25 per halaman</option>
            <option value="50">50 per halaman</option>
            <option value="100">100 per halaman</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelVerifikasi">
          <thead class="table-light">
            <tr>
              <th width="6%">Aksi</th>
              <th width="12%">No Kwitansi</th>
              <th width="15%">PPTK</th>
              <th width="20%">Kegiatan</th>
              <th width="12%">Rekening</th>
              <th width="15%">Jenis</th>
              <th width="10%">Total</th>
              <th width="10%">Tanggal</th>

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
          <div class="pagination-info" data-tipe="verifikasi"></div>
        </div>
        <div class="col-md-6">
          <nav>
            <ul class="pagination justify-content-end pagination-controls" data-tipe="verifikasi"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Detail Verifikasi Modal -->
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
          Detail Verifikasi Kwitansi
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

          <!-- Daftar Syarat -->
          <div class="card">
            <div class="card-header bg-light">
              <h6 class="card-title mb-0">Checklist Persyaratan</h6>
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
        <button type="button" class="btn btn-success" id="btnApprove" style="display: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          Setujui
        </button>
        <button type="button" class="btn btn-danger" id="btnReject" style="display: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
          </svg>
          Tolak
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>Perhatian:</strong> Alasan penolakan wajib diisi.
        </div>
        <form id="rejectForm">
          <input type="hidden" id="rejectKwitansiId">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <div class="mb-3">
            <label for="rejectAlasan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="rejectAlasan" name="alasan" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
            <div class="invalid-feedback">Alasan penolakan harus diisi</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmReject">
          <span class="spinner-border spinner-border-sm d-none" role="status"></span>
          Konfirmasi Tolak
        </button>
      </div>
    </div>
  </div>
</div>

<script>const BASEURL = '<?= BASEURL ?>';
const tableState = {
    verifikasi: { page: 1, per_page: 10, search: '', search_field: '' }
};

let currentKwitansiId = null;

// ==================== ENHANCED GLOBAL FUNCTIONS ====================
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
    $('#rejectAlasan').removeClass('is-invalid');
    $('.invalid-feedback').text('');
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

// ==================== CARD COUNT MANAGEMENT ====================
function updateCardCounts() {
    $.ajax({
        url: BASEURL + "admin/verifikasi",
        method: 'POST',
        data: { a: "get_counts" },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                // Update card counts with animation
                animateCountUpdate('#disetujuiCount', res.counts.disetujui || 0);
                animateCountUpdate('#ditolakCount', res.counts.ditolak || 0);

                // Update pending count in table header if needed
                if (res.counts.pending !== undefined) {
                    $('.card-title').text(`Daftar Kwitansi Menunggu Verifikasi (${res.counts.pending})`);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to update card counts:', error);
        }
    });
}

function animateCountUpdate(selector, newValue) {
    const element = $(selector);
    const currentValue = parseInt(element.text()) || 0;

    if (currentValue === newValue) return;

    element.fadeOut(200, function() {
        $(this).text(newValue).fadeIn(200);
    });
}

// ==================== TABLE MANAGEMENT ====================
function loadTableData(tipe) {
    const state = tableState[tipe];
    const tableId = `tabel${tipe.charAt(0).toUpperCase() + tipe.slice(1)}`;

    // Show loading state
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
        url: BASEURL + "admin/verifikasi",
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
                            'Tidak ada kwitansi menunggu verifikasi'
                        }
                    </div>
                </td>
            </tr>
        `);
    } else {
        data.forEach(row => {
            tbody.append(createTableRow(row));
        });
    }

    updatePaginationControls(tipe, pagination);
}

function createTableRow(row) {
    return `
        <tr data-kwitansi-id="${row.kwitansi_id}">
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-detail" data-id="${row.kwitansi_id}" title="Detail & Verifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </td>
            <td><code class="text-primary">${row.no_kwitansi || '-'}</code></td>
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

// Update pagination controls (keep your existing function)
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
function showDetailModal(kwitansiId) {
    currentKwitansiId = kwitansiId;

    // Reset modal state
    $('#detailLoading').show();
    $('#detailContent').hide();
    $('#detailError').hide();
    $('#btnApprove').hide();
    $('#btnReject').hide();

    // Clear previous content
    $('#kwitansiInfo').empty();
    $('#syaratList').empty();

    // Fetch detail data
    $.ajax({
        url: BASEURL + "admin/verifikasi",
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
                $('#btnApprove').show();
                $('#btnReject').show();

                displayKwitansiInfo(res.data.kwitansi);
                displaySyaratList(res.data.syarats);
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

// Display kwitansi information (keep your existing function)
function displayKwitansiInfo(kwitansi) {
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
                    <td><strong>Tanggal:</strong></td>
                    <td>${formatDate(kwitansi.tgl_kwitansi)}</td>
                </tr>
                <tr>
                    <td><strong>Dibuat:</strong></td>
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

// Display syarat list with status (keep your existing function)
function displaySyaratList(syarats) {
    if (syarats.length === 0) {
        $('#syaratList').html(`
            <div class="alert alert-warning text-center">
                Tidak ada persyaratan yang harus diverifikasi untuk rekening ini.
            </div>
        `);
        return;
    }

    let syaratHtml = '';
    let allUploaded = true;

    syarats.forEach(syarat => {
        const statusClass = syarat.is_uploaded ? 'success' : 'warning';
        const statusIcon = syarat.is_uploaded ? '✓' : '!';
        const statusText = syarat.is_uploaded ? 'Telah diupload' : 'Belum diupload';

        if (!syarat.is_uploaded) allUploaded = false;

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
                                    <a href="${BASEURL}assets/img/dokumen/${syarat.dokumen_file}" target="_blank" class="ms-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        Lihat
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
    const uploadedCount = syarats.filter(s => s.is_uploaded).length;
    const totalCount = syarats.length;

    syaratHtml = `
        <div class="alert ${allUploaded ? 'alert-success' : 'alert-warning'}">
            <strong>Status Persyaratan:</strong> ${uploadedCount} dari ${totalCount} syarat telah diupload
            ${allUploaded ? '- <strong>Semua syarat lengkap</strong>' : '- <strong>Ada syarat yang belum diupload</strong>'}
        </div>
        ${syaratHtml}
    `;

    $('#syaratList').html(syaratHtml);
}

// ==================== VERIFICATION ACTIONS ====================
function approveKwitansi(kwitansiId) {
    const approveBtn = $("#btnApprove");
    approveBtn.prop('disabled', true);
    approveBtn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: BASEURL + "admin/verifikasi",
        method: 'POST',
        data: {
            a: "approve",
            id: kwitansiId,
            csrf_token: '<?= $_SESSION['csrf_token'] ?>'
        },
        dataType: 'json',
        success: function(res) {
            approveBtn.prop('disabled', false);
            approveBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                $("#detailModal").modal("hide");
                showAlert(res.message, 'success');

                // Update UI without page refresh
                loadTableData('verifikasi');
                updateCardCounts();

                // Remove the approved row with animation
                $(`tr[data-kwitansi-id="${kwitansiId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                showAlert("Gagal menyetujui: " + res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            approveBtn.prop('disabled', false);
            approveBtn.find('.spinner-border').addClass('d-none');
            showAlert("Server error saat menyetujui.", 'danger');
        }
    });
}

function rejectKwitansi(kwitansiId, alasan) {
    const rejectBtn = $("#confirmReject");
    rejectBtn.prop('disabled', true);
    rejectBtn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: BASEURL + "admin/verifikasi",
        method: 'POST',
        data: {
            a: "reject",
            id: kwitansiId,
            alasan: alasan,
            csrf_token: '<?= $_SESSION['csrf_token'] ?>'
        },
        dataType: 'json',
        success: function(res) {
            rejectBtn.prop('disabled', false);
            rejectBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                $("#rejectModal").modal("hide");
                $("#detailModal").modal("hide");
                showAlert(res.message, 'success');

                // Update UI without page refresh
                loadTableData('verifikasi');
                updateCardCounts();

                // Remove the rejected row with animation
                $(`tr[data-kwitansi-id="${kwitansiId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                showAlert("Gagal menolak: " + res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            rejectBtn.prop('disabled', false);
            rejectBtn.find('.spinner-border').addClass('d-none');
            showAlert("Server error saat menolak.", 'danger');
        }
    });
}

// ==================== EVENT HANDLERS ====================
$(document).ready(function() {
    // Load initial data
    loadTableData('verifikasi');

    // Add IDs to card count elements for dynamic updates
    $('.card.bg-success h4').attr('id', 'disetujuiCount');
    $('.card.bg-danger h4').attr('id', 'ditolakCount');

    // Detail button handler
    $(document).on("click", ".btn-detail", function() {
        const id = $(this).data("id");
        showDetailModal(id);
    });

    // Approve button handler
    $("#btnApprove").click(function() {
        if (!currentKwitansiId) {
            showAlert("Tidak ada kwitansi yang dipilih.", 'warning');
            return;
        }

        // Confirmation before approve
        if (confirm("Apakah Anda yakin ingin menyetujui kwitansi ini?")) {
            approveKwitansi(currentKwitansiId);
        }
    });

    // Reject button handler
    $("#btnReject").click(function() {
        if (!currentKwitansiId) {
            showAlert("Tidak ada kwitansi yang dipilih.", 'warning');
            return;
        }

        $("#rejectModal").modal("show");
        $("#rejectKwitansiId").val(currentKwitansiId);
        clearValidationErrors();
        cleanupStuckModals();
    });

    // Confirm reject handler
    $("#confirmReject").click(function() {
        const alasan = $("#rejectAlasan").val().trim();

        if (!alasan) {
            $("#rejectAlasan").addClass('is-invalid');
            showAlert("Alasan penolakan harus diisi.", 'warning');
            return;
        }

        if (confirm("Apakah Anda yakin ingin menolak kwitansi ini?")) {
            rejectKwitansi(currentKwitansiId, alasan);
        }
    });

    // Clear validation on input
    $("#rejectAlasan").on('input', function() {
        if ($(this).val().trim()) {
            $(this).removeClass('is-invalid');
        }
    });

    // Pagination, search, and per-page handlers (keep your existing ones)
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
});
</script>

<link rel="stylesheet" href="assets/css/verifikasi_admin.css">
<?php
}