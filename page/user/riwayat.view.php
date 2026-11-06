<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Riwayat Kwitansi - PPTK')
<div class="container-fluid mt-4">

  <!-- Alert Container -->
  <div id="alertContainer"></div>

  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card bg-success text-white" data-aos="fade-down">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h3 class="card-title mb-2">Riwayat Kwitansi Disetujui 📋</h3>
              <p class="card-text mb-0 opacity-75">Daftar lengkap semua kwitansi yang telah disetujui dan selesai diproses</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
              <div class="display-6 opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stats -->
  <div class="row mb-4 justify-content-between">
    <div class="col-xxl-3 col-lg-4 col-md-6 mb-3" data-aos="fade-up">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Total Disetujui</span>
              <h3 class="fw-bold mt-1 mb-0 text-success" id="totalDisetujui"><?= $data['pagination']['total_rows'] ?? 0 ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span class="ms-1">Semua Waktu</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-success">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xxl-3 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="100">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Halaman Aktif</span>
              <h3 class="fw-bold mt-1 mb-0 text-primary" id="currentPage"><?= $data['pagination']['current_page'] ?? 1 ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <span class="ms-1">Dari <?= $data['pagination']['total_pages'] ?? 1 ?> halaman</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="col-xxl-3 col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="300">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Aksi Cepat</span>
              <div class="mt-2">
                <a href="<?= BASEURL ?>user/cetak" class="btn btn-outline-primary btn-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                  </svg>
                  Cetak PDF
                </a>
              </div>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="6 9 6 2 18 2 18 9"></polyline>
                  <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                  <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span class="ms-1">Export Data</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning">
                  <polyline points="6 9 6 2 18 2 18 9"></polyline>
                  <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                  <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search & Filter -->
  <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label small text-muted fw-semibold">Pencarian</label>
          <div class="input-group">
            <span class="input-group-text">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </span>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan no kwitansi, kegiatan...">
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label small text-muted fw-semibold">Berdasarkan</label>
          <select class="form-select" id="searchField">
            <option value="">Semua Field</option>
            <option value="k.no_kwitansi">No Kwitansi</option>
            <option value="kg.nama_kegiatan">Nama Kegiatan</option>
            <option value="k.status">Status</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted fw-semibold">Per Halaman</label>
          <select class="form-select" id="perPageSelect">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
        <div class="col-md-3">
          <button class="btn btn-primary w-100" id="btnSearch">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            Cari Data
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Data Table -->
  <div class="card border-0 shadow-sm" data-aos="fade-up">
    <div class="card-header bg-transparent border-0 pb-0">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold text-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
          </svg>
          Daftar Kwitansi Disetujui
        </h5>
        <div class="d-flex align-items-center">
          <span class="badge bg-success me-2" id="showingCount">
            Menampilkan <?= count($data['initial_data']) ?> data
          </span>
          <button class="btn btn-sm btn-outline-secondary" id="btnRefresh">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M23 4v6h-6"></path>
              <path d="M1 20v-6h6"></path>
              <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"></path>
              <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"></path>
            </svg>
            Refresh
          </button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-borderless mb-0">
          <thead class="table-light d-none d-sm-table-header-group">
            <tr>
              <th class="border-0 small text-uppercase fw-semibold text-muted text-center">Aksi</th>
              <th class="border-0 small text-uppercase fw-semibold text-muted">No Kwitansi</th>
              <th class="border-0 small text-uppercase fw-semibold text-muted">Kegiatan</th>
              <th class="border-0 small text-uppercase fw-semibold text-muted">Jenis</th>
              <th class="border-0 small text-uppercase fw-semibold text-muted">Total</th>
              <th class="border-0 small text-uppercase fw-semibold text-muted">Triwulan</th>
              <th class="border-0 small text-uppercase fw-semibold text-muted">Tanggal</th>

            </tr>
          </thead>
          <tbody id="riwayatTableBody">
            <?php if(empty($data['initial_data'])): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-5">
                  <div class="py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3 text-muted">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="12" y1="8" x2="12" y2="12"></line>
                      <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <p class="mb-0 fw-semibold">Belum ada riwayat kwitansi</p>
                    <small class="text-muted">Kwitansi yang sudah disetujui akan muncul di sini</small>
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach($data['initial_data'] as $item): ?>
                <tr class="riwayat-row" data-id="<?= $item['kwitansi_id'] ?>">
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-primary view-detail" data-id="<?= $item['kwitansi_id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                          <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Detail
                      </button>

                    </div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded"><?= htmlspecialchars($item['no_kwitansi']) ?></code>
                    </div>
                  </td>
                  <td>
                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($item['nama_kegiatan']) ?>">
                      <?= htmlspecialchars($item['nama_kegiatan']) ?>
                    </span>
                    <br>
                    <small class="text-muted"><?= htmlspecialchars($item['kode_rekening']) ?></small>
                  </td>
                  <td>
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                      <?= $item['jenis_kwitansi'] === 'perjalanan_dinas' ? 'Perjalanan' : 'Penyediaan' ?>
                    </span>
                    <br>
                    <small class="text-muted"><?= $item['jenis_ttd'] === '3_ttd' ? '3 TTD' : '4 TTD' ?></small>
                  </td>
                  <td>
                    <strong class="text-success">Rp <?= number_format($item['total_uang'], 0, ',', '.') ?></strong>
                  </td>
                  <td>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                      <?= $item['triwulan'] ?>
                    </span>
                  </td>
                  <td>
                    <small class="text-muted">
                      <?= date('d M Y', strtotime($item['tgl_kwitansi'])) ?>
                    </small>
                    <br>
                    <small class="text-muted">
                      <?= date('H:i', strtotime($item['created_at'])) ?>
                    </small>
                  </td>

                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if(!empty($data['initial_data']) && $data['pagination']['total_pages'] > 1): ?>
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
          Menampilkan <strong id="showingFrom"><?= min(($data['pagination']['current_page'] - 1) * $data['pagination']['per_page'] + 1, $data['pagination']['total_rows']) ?></strong>
          - <strong id="showingTo"><?= min($data['pagination']['current_page'] * $data['pagination']['per_page'], $data['pagination']['total_rows']) ?></strong>
          dari <strong id="showingTotal"><?= $data['pagination']['total_rows'] ?></strong> data
        </div>
        <nav aria-label="Page navigation">
          <ul class="pagination pagination-sm mb-0" id="paginationContainer">
            <?php if($data['pagination']['current_page'] > 1): ?>
              <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="<?= $data['pagination']['current_page'] - 1 ?>">
                  &laquo;
                </a>
              </li>
            <?php endif; ?>

            <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
              <li class="page-item <?= $i == $data['pagination']['current_page'] ? 'active' : '' ?>">
                <a class="page-link" href="javascript:void(0)" data-page="<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>

            <?php if($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
              <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="<?= $data['pagination']['current_page'] + 1 ?>">
                  &raquo;
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Detail Kwitansi -->
<div class="modal fade pt-10" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title d-flex align-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
          </svg>
          Detail Kwitansi
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Navigation Tabs -->
      <div class="modal-header border-bottom-0 py-3">
        <ul class="nav nav-tabs nav-tabs-card" id="detailTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
              </svg>
              Informasi
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="keuangan-tab" data-bs-toggle="tab" data-bs-target="#keuangan-tab-pane" type="button" role="tab">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
              Keuangan
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="kegiatan-tab" data-bs-toggle="tab" data-bs-target="#kegiatan-tab-pane" type="button" role="tab">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
              </svg>
              Kegiatan
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen-tab-pane" type="button" role="tab">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
              </svg>
              Dokumen
            </button>
          </li>
        </ul>
      </div>

      <div class="modal-body p-0">
        <div class="tab-content" id="detailTabsContent">
          <!-- Tab 1: Informasi Utama -->
          <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" tabindex="0">
            <div class="p-4" id="infoTabContent">
              <!-- Content will be loaded here -->
            </div>
          </div>

          <!-- Tab 2: Informasi Keuangan -->
          <div class="tab-pane fade" id="keuangan-tab-pane" role="tabpanel" tabindex="0">
            <div class="p-4" id="keuanganTabContent">
              <!-- Content will be loaded here -->
            </div>
          </div>

          <!-- Tab 3: Data Kegiatan -->
          <div class="tab-pane fade" id="kegiatan-tab-pane" role="tabpanel" tabindex="0">
            <div class="p-4" id="kegiatanTabContent">
              <!-- Content will be loaded here -->
            </div>
          </div>

          <!-- Tab 4: Dokumen -->
          <div class="tab-pane fade" id="dokumen-tab-pane" role="tabpanel" tabindex="0">
            <div class="p-4" id="dokumenTabContent">
              <!-- Content will be loaded here -->
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>

      </div>
    </div>
  </div>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';
let currentPage = <?= $data['pagination']['current_page'] ?? 1 ?>;
let currentPerPage = <?= $data['pagination']['per_page'] ?? 10 ?>;
let currentSearch = '';
let currentSearchField = '';

// Initialize
$(document).ready(function() {
    initializeEventHandlers();
    updateShowingCount();
});


function cleanupStuckModals() {
    // Hapus semua backdrop yang mungkin tertinggal
    $('.modal-backdrop').remove();
    // Hapus class modal-open dari body
    $('body').removeClass('modal-open');
    // Reset overflow
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
    // Sembunyikan semua modal
    $('.modal').hide();
}

// Initialize event handlers
function initializeEventHandlers() {
    // Search
    $('#btnSearch').on('click', performSearch);
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) performSearch();
    });

    // Per page change
    $('#perPageSelect').on('change', function() {
        currentPerPage = $(this).val();
        loadData(1);
    });

    // Refresh
    $('#btnRefresh').on('click', function() {
        resetSearch();
        loadData(1);
    });

    // Pagination
    $(document).on('click', '.page-link', function() {
        const page = $(this).data('page');
        if (page) loadData(page);
    });

    // View detail
    $(document).on('click', '.view-detail', viewDetail);

    $('#detailModal').on('hidden.bs.modal', function() {
    cleanupStuckModals();
  });

  $('#detailModal').on('shown.bs.modal', function() {
    // Reset ke tab pertama setiap kali modal dibuka
    const firstTab = new bootstrap.Tab(document.getElementById('info-tab'));
    firstTab.show();
  });
}

// Load data via AJAX
function loadData(page = 1) {
    $.ajax({
        url: BASEURL + "user/riwayat",
        method: 'POST',
        data: {
            a: 'get_data',
            csrf_token: '<?= $data['csrf_token'] ?>',
            page: page,
            per_page: currentPerPage,
            search: currentSearch,
            search_field: currentSearchField
        },
        dataType: 'json',
        beforeSend: function() {
            $('#riwayatTableBody').html(`
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 mb-0 text-muted">Memuat data...</p>
                    </td>
                </tr>
            `);
        },
        success: function(res) {
            if (res.status === "success") {
                updateTable(res.data);
                updatePagination(res.pagination);
                updateStats(res.pagination);
                currentPage = res.pagination.current_page;
            } else {
                showAlert(res.message, 'danger');
                showEmptyState();
            }
        },
        error: function(xhr, status, error) {
            console.error('Load data error:', error);
            showAlert('Terjadi kesalahan saat memuat data', 'danger');
            showEmptyState();
        }
    });
}



// Update table with new data
function updateTable(data) {
    const tbody = $('#riwayatTableBody');

    if (data.length === 0) {
        showEmptyState();
        return;
    }

    let html = '';
    data.forEach(item => {
        const jenisText = item.jenis_kwitansi === 'perjalanan_dinas' ? 'Perjalanan' : 'Penyediaan';
        const ttdText = item.jenis_ttd === '3_ttd' ? '3 TTD' : '4 TTD';
        const tanggal = new Date(item.tgl_kwitansi).toLocaleDateString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric'
        });
        const waktu = new Date(item.created_at).toLocaleTimeString('id-ID', {
            hour: '2-digit', minute: '2-digit'
        });

        // Mobile-optimized row
        html += `
            <tr class="riwayat-row" data-id="${item.kwitansi_id}">
                <td class="text-center" data-label="Aksi">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary view-detail" data-id="${item.kwitansi_id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <span class="d-none d-sm-inline">Detail</span>
                            <span class="d-inline d-sm-none">Lihat</span>
                        </button>

                    </div>
                </td>
                <td data-label="No Kwitansi">
                    <div class="d-flex align-items-center">
                        <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small">${item.no_kwitansi}</code>
                    </div>
                </td>
                <td data-label="Kegiatan">
                    <span class="d-inline-block text-truncate mobile-truncate" title="${item.nama_kegiatan}">
                        ${item.nama_kegiatan}
                    </span>
                    <br>
                    <small class="text-muted">${item.kode_rekening}</small>
                </td>
                <td data-label="Jenis">
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                        ${jenisText}
                    </span>
                    <br>
                    <small class="text-muted">${ttdText}</small>
                </td>
                <td data-label="Total">
                    <strong class="text-success">Rp ${formatNumber(item.total_uang)}</strong>
                </td>
                <td data-label="Triwulan">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                        ${item.triwulan}
                    </span>
                </td>
                <td data-label="Tanggal">
                    <small class="text-muted">${tanggal}</small>
                    <br>
                    <small class="text-muted">${waktu}</small>
                </td>
            </tr>
        `;
    });

    tbody.html(html);
}

// Enhanced CSS untuk mobile truncation
const mobileStyles = `
<style>
@media (max-width: 576px) {
    .mobile-truncate {
        max-width: 150px;
        display: inline-block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .riwayat-row {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }

    .riwayat-row:active {
        transform: scale(0.98);
        background-color: #f8f9fa;
    }
}
</style>
`;

// Inject mobile styles
$(mobileStyles).appendTo('head');

// Update pagination
function updatePagination(pagination) {
    const container = $('#paginationContainer');

    if (pagination.total_pages <= 1) {
        container.closest('nav').hide();
        return;
    }

    container.closest('nav').show();

    let html = '';

    // Previous button
    if (pagination.current_page > 1) {
        html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="${pagination.current_page - 1}">&laquo;</a></li>`;
    }

    // Page numbers
    for (let i = 1; i <= pagination.total_pages; i++) {
        const active = i === pagination.current_page ? 'active' : '';
        html += `<li class="page-item ${active}"><a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a></li>`;
    }

    // Next button
    if (pagination.current_page < pagination.total_pages) {
        html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="${pagination.current_page + 1}">&raquo;</a></li>`;
    }

    container.html(html);

    // Update showing text
    const from = Math.min((pagination.current_page - 1) * pagination.per_page + 1, pagination.total_rows);
    const to = Math.min(pagination.current_page * pagination.per_page, pagination.total_rows);
    $('#showingFrom').text(from);
    $('#showingTo').text(to);
    $('#showingTotal').text(pagination.total_rows);
}

// Update stats
function updateStats(pagination) {
    $('#totalDisetujui').text(pagination.total_rows);
    $('#currentPage').text(pagination.current_page);
    $('#perPage').text(pagination.per_page);
    updateShowingCount(pagination.total_rows);
}

// Update showing count
function updateShowingCount(total = null) {
    const count = total !== null ? total : <?= $data['pagination']['total_rows'] ?? 0 ?>;
    $('#showingCount').text(`Menampilkan ${count} data`);
}

// Perform search
function performSearch() {
    currentSearch = $('#searchInput').val().trim();
    currentSearchField = $('#searchField').val();
    loadData(1);
}

// Reset search
function resetSearch() {
    $('#searchInput').val('');
    $('#searchField').val('');
    $('#perPageSelect').val('10');
    currentSearch = '';
    currentSearchField = '';
    currentPerPage = 10;
}

// Show empty state
function showEmptyState() {
    $('#riwayatTableBody').html(`
        <tr>
            <td colspan="7" class="text-center text-muted py-5">
                <div class="py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3 text-muted">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <p class="mb-0 fw-semibold">Tidak ada data ditemukan</p>
                    <small class="text-muted">Coba ubah kata kunci pencarian atau filter</small>
                </div>
            </td>
        </tr>
    `);
    $('#paginationContainer').closest('nav').hide();
    updateShowingCount(0);
}

function showDetailModal(data) {
  const kwitansi = data.kwitansi;
  const dokumen = data.dokumen;

  // Load data ke masing-masing tab
  loadInfoTab(kwitansi);
  loadKeuanganTab(kwitansi);
  loadKegiatanTab(kwitansi);
  loadDokumenTab(dokumen);

  // Reset ke tab pertama
  const firstTab = new bootstrap.Tab(document.getElementById('info-tab'));
  firstTab.show();

}

function loadInfoTab(kwitansi) {
  const jenisText = kwitansi.jenis_kwitansi === 'perjalanan_dinas' ? 'Perjalanan Dinas' : 'Penyediaan Barang/Jasa';
  const ttdText = kwitansi.jenis_ttd === '3_ttd' ? '3 Tanda Tangan' : '4 Tanda Tangan';
  const tanggal = new Date(kwitansi.tgl_kwitansi).toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  let penyediaHtml = '';
  if (kwitansi.nama_penyedia) {
    penyediaHtml = `
      <div class="col-md-6">
        <div class="card border-0 h-100">
          <div class="card-header bg-transparent border-0 pb-2">
            <h6 class="card-title mb-0 text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
              Penyedia Barang/Jasa
            </h6>
          </div>
          <div class="card-body pt-0">
            <div class="mb-2">
              <small class="text-muted">Perusahaan</small>
              <p class="mb-1 fw-semibold">${kwitansi.nama_perusahaan || '-'}</p>
            </div>
            <div class="mb-2">
              <small class="text-muted">Penanggung Jawab</small>
              <p class="mb-1 fw-semibold">${kwitansi.nama_penyedia}</p>
            </div>
            <div>
              <small class="text-muted">Jabatan</small>
              <p class="mb-0 fw-semibold">${kwitansi.jabatan_penyedia || '-'}</p>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  const html = `
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card border-0 h-100">
          <div class="card-header bg-transparent border-0 pb-2">
            <h6 class="card-title mb-0 text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
              </svg>
              Informasi Kwitansi
            </h6>
          </div>
          <div class="card-body pt-0">
            <div class="mb-2">
              <small class="text-muted">No Kwitansi</small>
              <p class="mb-1"><code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded">${kwitansi.no_kwitansi}</code></p>
            </div>
            <div class="mb-2">
              <small class="text-muted">Jenis Kwitansi</small>
              <p class="mb-1"><span class="badge bg-info">${jenisText}</span></p>
            </div>
            <div class="mb-2">
              <small class="text-muted">Tanda Tangan</small>
              <p class="mb-1"><span class="badge bg-secondary">${ttdText}</span></p>
            </div>
            <div class="mb-2">
              <small class="text-muted">Tanggal</small>
              <p class="mb-1 fw-semibold">${tanggal}</p>
            </div>
            <div>
              <small class="text-muted">Triwulan</small>
              <p class="mb-0"><span class="badge bg-warning">${kwitansi.triwulan}</span></p>
            </div>
          </div>
        </div>
      </div>
      ${penyediaHtml}
    </div>
  `;

  $('#infoTabContent').html(html);
}

function loadKeuanganTab(kwitansi) {
  const html = `
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card border-0 h-100">
          <div class="card-header bg-transparent border-0 pb-2">
            <h6 class="card-title mb-0 text-success">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
              Informasi Keuangan
            </h6>
          </div>
          <div class="card-body pt-0">
            <div class="mb-3">
              <small class="text-muted">Total Uang</small>
              <h4 class="text-success fw-bold mb-1">Rp ${formatNumber(kwitansi.total_uang)}</h4>
            </div>
            <div>
              <small class="text-muted">Terbilang</small>
              <p class="mb-0 fst-italic text-muted">${kwitansi.terbilang || '-'}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 h-100">
          <div class="card-header bg-transparent border-0 pb-2">
            <h6 class="card-title mb-0 text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
              Uraian SPJ
            </h6>
          </div>
          <div class="card-body pt-0">
            <div class="bg-light rounded p-3">
              <p class="mb-0">${kwitansi.uraian_spj || 'Tidak ada uraian SPJ'}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

  $('#keuanganTabContent').html(html);
}

function loadKegiatanTab(kwitansi) {
  const html = `
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card border-0 h-100">
          <div class="card-header bg-transparent border-0 pb-2">
            <h6 class="card-title mb-0 text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
              </svg>
              Data Kegiatan
            </h6>
          </div>
          <div class="card-body pt-0">
            <div class="mb-2">
              <small class="text-muted">Nama Kegiatan</small>
              <p class="mb-1 fw-semibold">${kwitansi.nama_kegiatan}</p>
            </div>
            <div class="mb-2">
              <small class="text-muted">Kode Rekening</small>
              <p class="mb-1"><code class="text-primary">${kwitansi.kode_rekening}</code></p>
            </div>
            <div>
              <small class="text-muted">Jenis Belanja</small>
              <p class="mb-0 fw-semibold">${kwitansi.nama_belanja}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 h-100">
          <div class="card-header bg-transparent border-0 pb-2">
            <h6 class="card-title mb-0 text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              Pejabat Penandatangan
            </h6>
          </div>
          <div class="card-body pt-0">
            <div class="mb-3">
              <small class="text-muted">${kwitansi.jabatan_pejabat}</small>
              <p class="mb-1 fw-semibold">${kwitansi.nama_pejabat}</p>
            </div>
            <div>
              <small class="text-muted">${kwitansi.jabatan_bendahara}</small>

              <p class="mb-1 fw-semibold">${kwitansi.nama_bendahara}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

  $('#kegiatanTabContent').html(html);
}

function loadDokumenTab(dokumen) {
  let html = '';

  if (dokumen.length > 0) {
    html = `
      <div class="row">
        <div class="col-12">
          <div class="card border-0">
            <div class="card-header bg-transparent border-0 pb-2">
              <h6 class="card-title mb-0 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                Dokumen Terlampir (${dokumen.length})
              </h6>
            </div>
            <div class="card-body pt-0">
              <div class="list-group list-group-flush">
    `;

    dokumen.forEach((doc, index) => {
      const uploadDate = new Date(doc.uploaded_at).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });

      html += `
                <div class="list-group-item px-0 ${index !== dokumen.length - 1 ? 'border-bottom' : ''}">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                      <h6 class="mb-1 small fw-semibold">${doc.nama_syarat || doc.nama_dokumen}</h6>
                      <small class="text-muted">Diupload: ${uploadDate}</small>
                    </div>
                    <a href="${BASEURL}assets/img/dokumen/${doc.file}" target="_blank" class="btn btn-sm btn-outline-primary ms-3">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                      Lihat
                    </a>
                  </div>
                </div>
      `;
    });

    html += `
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
  } else {
    html = `
      <div class="text-center py-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-muted mb-3">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <h6 class="text-muted">Tidak ada dokumen terlampir</h6>
        <p class="text-muted small">Tidak ada dokumen yang diupload untuk kwitansi ini.</p>
      </div>
    `;
  }

  $('#dokumenTabContent').html(html);
}



// View detail
function viewDetail() {
    const kwitansiId = $(this).data('id');
    cleanupStuckModals();
    $.ajax({
        url: BASEURL + "user/riwayat",
        method: 'POST',
        data: {
            a: 'get_detail',
            csrf_token: '<?= $data['csrf_token'] ?>',
            id: kwitansiId
        },
        dataType: 'json',
        beforeSend: function() {
             const loadingHTML = `
              <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0 text-muted">Memuat detail...</p>
              </div>
            `;
            $('#infoTabContent').html(loadingHTML);
            $('#keuanganTabContent').html(loadingHTML);
            $('#kegiatanTabContent').html(loadingHTML);
            $('#dokumenTabContent').html(loadingHTML);
        },
        success: function(res) {
            if (res.status === "success") {
                showDetailModal(res.data);


                $('#detailModal').modal('show');
                // cleanupStuckModals();

            } else {
                $('#infoTabContent').html(`
                    <div class="alert alert-danger">
                        <p class="mb-0">${res.message}</p>
                    </div>
                `);
                $('#detailModal').modal('show');
                // cleanupStuckModals();
            }
        },
        error: function(xhr, status, error) {
            console.error('Detail error:', error);
            $('#infoTabContent').html(`
                <div class="alert alert-danger">
                    <p class="mb-0">Terjadi kesalahan saat memuat detail</p>
                </div>
            `);
        }
    });

    $('#detailModal').modal('show');
    cleanupStuckModals();
}


// Utility function to format numbers
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Global function for alerts
function showAlert(message, type = 'success') {
    const alert = `<div class="alert alert-${type} alert-dismissible fade show">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    $('#alertContainer').html(alert);

    setTimeout(() => $('#alertContainer .alert').alert('close'), 5000);
}
</script>

<link rel="stylesheet" href="assets/css/riwayat_user.css">

<?php
}