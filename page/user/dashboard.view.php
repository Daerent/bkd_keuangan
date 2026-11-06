<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Dashboard PPTK')
<div class="container-fluid mt-4 mt-md-0">

  <!-- Alert Container -->
  <div id="alertContainer"></div>

  <!-- Welcome Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card bg-primary text-white" data-aos="fade-down">
        <div class="card-body p-3 p-md-4">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h3 class="card-title h4 h3-md mb-2">Selamat Datang, <?= htmlspecialchars($data['user_name']) ?>! 👋</h3>
              <p class="card-text mb-0 opacity-75 d-none d-md-block">Sistem Kwitansi Digital - Pilih kegiatan untuk membuat kwitansi baru</p>
              <p class="card-text mb-0 opacity-75 d-md-none small">Pilih kegiatan untuk membuat kwitansi baru</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
              <div class="display-6 opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stats Cards -->
  <div class="row mb-4 g-2 g-sm-3">
    <!-- Total Kegiatan -->
    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2 mb-2 mb-sm-3" data-aos="fade-up" data-aos-delay="100">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-2 p-sm-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase d-none d-sm-block">Total Kegiatan</span>
              <span class="text-muted small fw-semibold text-uppercase d-sm-none">Total</span>
              <h3 class="fw-bold mt-1 mb-0 fs-4 fs-sm-3" id="totalKegiatan"><?= $data['statistics']['total_kegiatan'] ?? 0 ?></h3>
              <div class="text-muted small mt-1 mt-sm-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-none d-sm-inline">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <span class="ms-0 ms-sm-1 d-none d-sm-inline">Di-assign ke Anda</span>
                <span class="d-sm-none">Assign</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-primary bg-opacity-10 p-2 p-sm-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                  <line x1="12" y1="1" x2="12" y2="23"></line>
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Draft -->
    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2 mb-2 mb-sm-3" data-aos="fade-up" data-aos-delay="150">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-2 p-sm-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase d-none d-sm-block">Dalam Proses</span>
              <span class="text-muted small fw-semibold text-uppercase d-sm-none">Proses</span>
              <h3 class="fw-bold mt-1 mb-0 fs-4 fs-sm-3 text-warning" id="draftCount"><?= $data['statistics']['draft_count'] ?? 0 ?></h3>
              <div class="text-muted small mt-1 mt-sm-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-none d-sm-inline">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span class="ms-0 ms-sm-1 d-none d-sm-inline">Perlu Dikerjakan</span>
                <span class="d-sm-none">Dikerjakan</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-warning bg-opacity-10 p-2 p-sm-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Verifikasi -->
    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2 mb-2 mb-sm-3" data-aos="fade-up" data-aos-delay="200">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-2 p-sm-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase d-none d-sm-block">Menunggu Verifikasi</span>
              <span class="text-muted small fw-semibold text-uppercase d-sm-none">Verifikasi</span>
              <h3 class="fw-bold mt-1 mb-0 fs-4 fs-sm-3 text-info" id="verifikasiCount"><?= $data['statistics']['verifikasi_count'] ?? 0 ?></h3>
              <div class="text-muted small mt-1 mt-sm-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-none d-sm-inline">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span class="ms-0 ms-sm-1 d-none d-sm-inline">Sedang Diproses</span>
                <span class="d-sm-none">Diproses</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-info bg-opacity-10 p-2 p-sm-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-info">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Disetujui -->
    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2 mb-2 mb-sm-3" data-aos="fade-up" data-aos-delay="250">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-2 p-sm-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase d-none d-sm-block">Disetujui</span>
              <span class="text-muted small fw-semibold text-uppercase d-sm-none">Setuju</span>
              <h3 class="fw-bold mt-1 mb-0 fs-4 fs-sm-3 text-success" id="disetujuiCount"><?= $data['statistics']['disetujui_count'] ?? 0 ?></h3>
              <div class="text-muted small mt-1 mt-sm-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-none d-sm-inline">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span class="ms-0 ms-sm-1 d-none d-sm-inline">Selesai</span>
                <span class="d-sm-none">Selesai</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-success bg-opacity-10 p-2 p-sm-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-success">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ditolak -->
    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2 mb-2 mb-sm-3" data-aos="fade-up" data-aos-delay="300">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-2 p-sm-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase d-none d-sm-block">Ditolak</span>
              <span class="text-muted small fw-semibold text-uppercase d-sm-none">Tolak</span>
              <h3 class="fw-bold mt-1 mb-0 fs-4 fs-sm-3 text-danger" id="ditolakCount"><?= $data['statistics']['ditolak_count'] ?? 0 ?></h3>
              <div class="text-muted small mt-1 mt-sm-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-none d-sm-inline">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="15" y1="9" x2="9" y2="15"></line>
                  <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <span class="ms-0 ms-sm-1 d-none d-sm-inline">Perlu Revisi</span>
                <span class="d-sm-none">Revisi</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-danger bg-opacity-10 p-2 p-sm-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger">
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

    <!-- Aktif Sekarang -->
    <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xxl-2 mb-2 mb-sm-3" data-aos="fade-up" data-aos-delay="350">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-2 p-sm-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase d-none d-sm-block">Aktif Sekarang</span>
              <span class="text-muted small fw-semibold text-uppercase d-sm-none">Aktif</span>
              <h3 class="fw-bold mt-1 mb-0 fs-4 fs-sm-3 text-primary" id="activeCount"><?= $data['statistics']['active_kwitansi_count'] ?? 0 ?></h3>
              <div class="text-muted small mt-1 mt-sm-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-none d-sm-inline">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                <span class="ms-0 ms-sm-1 d-none d-sm-inline">Sedang Dikerjakan</span>
                <span class="d-sm-none">Dikerjakan</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-primary bg-opacity-10 p-2 p-sm-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Row -->
  <div class="row g-3 g-md-4">
    <!-- Left Column -->
    <div class="col-xl-8">
      <!-- Available Kegiatan -->
      <div class="card border-0 shadow-sm mb-3 mb-md-4" data-aos="fade-up">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
          <h5 class="card-title mb-0 fw-bold h6 h5-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            Kegiatan yang Di-assign
          </h5>
        </div>
        <div class="card-body p-3 p-md-4">
          <div id="kegiatanContainer">
            <?php if(empty($data['assigned_kegiatan'])): ?>
              <div class="text-center text-muted py-4 py-md-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p class="mb-0 fw-semibold">Belum ada kegiatan yang di-assign</p>
                <small class="text-muted">Admin akan meng-assign kegiatan baru untuk Anda</small>
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover table-borderless mb-0 d-none d-md-table">
                  <thead class="table-light">
                    <tr>
                      <th class="border-0 small text-uppercase fw-semibold text-muted">Kegiatan</th>
                      <th class="border-0 small text-uppercase fw-semibold text-muted">Kode Rekening</th>
                      <th class="border-0 small text-uppercase fw-semibold text-muted">Jenis Kwitansi</th>
                      <th class="border-0 small text-uppercase fw-semibold text-muted">Jenis TTD</th>
                      <th class="border-0 small text-uppercase fw-semibold text-muted">Total Kwitansi</th>
                      <th class="border-0 small text-uppercase fw-semibold text-muted">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($data['assigned_kegiatan'] as $kegiatan): ?>
                      <tr class="kegiatan-row" data-id="<?= $kegiatan['id'] ?>">
                        <td>
                          <span class="d-inline-block text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>">
                            <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                          </span>
                        </td>
                        <td>
                          <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded"><?= htmlspecialchars($kegiatan['kode_rekening']) ?></code>
                        </td>
                        <td>
                          <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                            <?= $kegiatan['jenis_kwitansi'] === 'perjalanan_dinas' ? 'Perjalanan Dinas' : 'Penyedia Barang' ?>
                          </span>
                        </td>
                        <td>
                          <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                            <?= $kegiatan['jenis_ttd'] === '4_ttd' ? '4 TTD' : '3 TTD' ?>
                          </span>
                        </td>
                        <td>
                          <span class="badge bg-light text-dark">
                            <?= $kegiatan['total_kwitansi'] ?? 0 ?> kwitansi
                          </span>
                        </td>
                        <td>
                          <button class="btn btn-primary btn-sm create-kwitansi" data-id="<?= $kegiatan['id'] ?>" data-nama="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>">
                            <span class="d-none d-md-inline">Buat Kwitansi</span>
                            <span class="d-md-none">Buat</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

                <!-- Mobile Cards View -->
                <div class="d-md-none">
                  <?php foreach($data['assigned_kegiatan'] as $kegiatan): ?>
                    <div class="card mb-3 border shadow-sm kegiatan-row" data-id="<?= $kegiatan['id'] ?>">
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                          <h6 class="card-title mb-0 text-truncate me-2" title="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>">
                            <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                          </h6>
                          <span class="badge bg-light text-dark small">
                            <?= $kegiatan['total_kwitansi'] ?? 0 ?> kwitansi
                          </span>
                        </div>

                        <div class="mb-2">
                          <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small"><?= htmlspecialchars($kegiatan['kode_rekening']) ?></code>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                          <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                            <?= $kegiatan['jenis_kwitansi'] === 'perjalanan_dinas' ? 'Perjalanan Dinas' : 'Penyedia Barang' ?>
                          </span>
                          <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 small">
                            <?= $kegiatan['jenis_ttd'] === '4_ttd' ? '4 TTD' : '3 TTD' ?>
                          </span>
                        </div>

                        <button class="btn btn-primary btn-sm w-100 create-kwitansi" data-id="<?= $kegiatan['id'] ?>" data-nama="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>">
                          Buat Kwitansi
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                          </svg>
                        </button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Kwitansi Aktif -->
      <?php if(!empty($data['active_kwitansi'])): ?>
      <div class="card border-0 shadow-sm mb-3 mb-md-4" data-aos="fade-up">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
          <h5 class="card-title mb-0 fw-bold h6 h5-md text-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Kwitansi Aktif
          </h5>
        </div>
        <div class="card-body p-3 p-md-4">
          <div class="alert alert-warning mb-3 mb-md-4">
            <strong>Anda memiliki kwitansi yang sedang aktif!</strong> Selesaikan atau batalkan kwitansi ini sebelum membuat yang baru.
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0 d-none d-md-table">
              <thead class="table-light">
                <tr>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">No Kwitansi</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Kegiatan</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Status</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($data['active_kwitansi'] as $kwitansi): ?>
                  <tr>
                    <td>
                      <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded"><?= htmlspecialchars($kwitansi['no_kwitansi']) ?></code>
                    </td>
                    <td>
                      <span class="d-inline-block text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($kwitansi['nama_kegiatan']) ?>">
                        <?= htmlspecialchars($kwitansi['nama_kegiatan']) ?>
                      </span>
                    </td>
                    <td>
                      <?php
                        $statusClass = [
                          'draft' => 'warning',
                          'verifikasi' => 'info',
                          'ditolak' => 'danger'
                        ][$kwitansi['status']] ?? 'secondary';

                        $statusText = [
                          'draft' => 'Draft',
                          'verifikasi' => 'Verifikasi',
                          'ditolak' => 'Ditolak'
                        ][$kwitansi['status']] ?? $kwitansi['status'];
                      ?>
                      <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> border border-<?= $statusClass ?> border-opacity-25">
                        <?= $statusText ?>
                      </span>
                    </td>
                    <td>
                      <a href="<?= BASEURL ?>user/kwitansi" class="btn btn-warning btn-sm">
                        <span class="d-none d-md-inline">Lanjutkan</span>
                        <span class="d-md-none">Lanjut</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <!-- Mobile Cards View -->
            <div class="d-md-none">
              <?php foreach($data['active_kwitansi'] as $kwitansi): ?>
                <div class="card mb-3 border shadow-sm">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <h6 class="card-title mb-0 text-truncate me-2" title="<?= htmlspecialchars($kwitansi['nama_kegiatan']) ?>">
                        <?= htmlspecialchars($kwitansi['nama_kegiatan']) ?>
                      </h6>
                      <?php
                        $statusClass = [
                          'draft' => 'warning',
                          'verifikasi' => 'info',
                          'ditolak' => 'danger'
                        ][$kwitansi['status']] ?? 'secondary';

                        $statusText = [
                          'draft' => 'Draft',
                          'verifikasi' => 'Verifikasi',
                          'ditolak' => 'Ditolak'
                        ][$kwitansi['status']] ?? $kwitansi['status'];
                      ?>
                      <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> border border-<?= $statusClass ?> border-opacity-25 small">
                        <?= $statusText ?>
                      </span>
                    </div>

                    <div class="mb-3">
                      <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small"><?= htmlspecialchars($kwitansi['no_kwitansi']) ?></code>
                    </div>

                    <a href="<?= BASEURL ?>user/kwitansi" class="btn btn-warning btn-sm w-100">
                      Lanjutkan Kwitansi
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                      </svg>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Riwayat Terbaru -->
      <div class="card border-0 shadow-sm" data-aos="fade-up">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold h6 h5-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              Riwayat Terbaru
            </h5>
            <a href="<?= BASEURL ?>user/riwayat" class="btn btn-sm btn-outline-primary rounded-pill">
              <span class="d-none d-md-inline">Lihat Semua</span>
              <span class="d-md-none">Semua</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
        <div class="card-body p-3 p-md-4">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0 d-none d-md-table">
              <thead class="table-light">
                <tr>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">No Kwitansi</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Kegiatan</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Status</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Tanggal</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($data['kwitansi_history'])): ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4 py-md-5">
                      <div class="py-3 py-md-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3 text-muted">
                          <circle cx="12" cy="12" r="10"></circle>
                          <line x1="12" y1="8" x2="12" y2="12"></line>
                          <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p class="mb-0 fw-semibold">Belum ada riwayat</p>
                        <small class="text-muted">Riwayat kwitansi akan muncul di sini</small>
                      </div>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach($data['kwitansi_history'] as $history): ?>
                    <tr>
                      <td>
                        <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded"><?= htmlspecialchars($history['no_kwitansi']) ?></code>
                      </td>
                      <td>
                        <span class="d-inline-block text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($history['nama_kegiatan']) ?>">
                          <?= htmlspecialchars($history['nama_kegiatan']) ?>
                        </span>
                      </td>
                      <td>
                        <?php
                          $statusClass = [
                            'disetujui' => 'success',
                            'ditolak' => 'danger'
                          ][$history['status']] ?? 'secondary';

                          $statusText = [
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak'
                          ][$history['status']] ?? $history['status'];
                        ?>
                        <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> border border-<?= $statusClass ?> border-opacity-25">
                          <?= $statusText ?>
                        </span>
                      </td>
                      <td>
                        <small class="text-muted">
                          <?= date('d M Y', strtotime($history['created_at'])) ?>
                        </small>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>

            <!-- Mobile Cards View -->
            <div class="d-md-none">
              <?php if(empty($data['kwitansi_history'])): ?>
                <div class="text-center text-muted py-4">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3 text-muted">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                  </svg>
                  <p class="mb-0 fw-semibold">Belum ada riwayat</p>
                  <small class="text-muted">Riwayat kwitansi akan muncul di sini</small>
                </div>
              <?php else: ?>
                <?php foreach($data['kwitansi_history'] as $history): ?>
                  <div class="card mb-3 border shadow-sm">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 text-truncate me-2" title="<?= htmlspecialchars($history['nama_kegiatan']) ?>">
                          <?= htmlspecialchars($history['nama_kegiatan']) ?>
                        </h6>
                        <?php
                          $statusClass = [
                            'disetujui' => 'success',
                            'ditolak' => 'danger'
                          ][$history['status']] ?? 'secondary';

                          $statusText = [
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak'
                          ][$history['status']] ?? $history['status'];
                        ?>
                        <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> border border-<?= $statusClass ?> border-opacity-25 small">
                          <?= $statusText ?>
                        </span>
                      </div>

                      <div class="mb-2">
                        <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small"><?= htmlspecialchars($history['no_kwitansi']) ?></code>
                      </div>

                      <div class="text-muted small">
                        <?= date('d M Y', strtotime($history['created_at'])) ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-xl-4">
      <!-- Quick Actions -->
      <div class="card border-0 shadow-sm mb-3 mb-md-4" data-aos="fade-left">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
          <h5 class="card-title mb-0 fw-bold h6 h5-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            Akses Cepat
          </h5>
        </div>
        <div class="card-body p-3 p-md-4">
          <div class="row g-2 g-sm-3">
            <div class="col-6">
              <a href="<?= BASEURL ?>user/kwitansi" class="btn btn-primary w-100 h-100 py-2 py-sm-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-1 mb-sm-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                  </svg>
                </div>
                <span class="fw-semibold text-white small">Input Kwitansi</span>
                <small class="text-white opacity-90 d-none d-sm-block">Kerjakan sekarang</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>user/riwayat" class="btn btn-success w-100 h-100 py-2 py-sm-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-1 mb-sm-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                </div>
                <span class="fw-semibold text-white small">Riwayat</span>
                <small class="text-white opacity-90 d-none d-sm-block">Lihat semua</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>user/cetak_kwitansi" class="btn btn-info w-100 h-100 py-2 py-sm-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-1 mb-sm-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                  </svg>
                </div>
                <span class="fw-semibold text-white small">Cetak PDF</span>
                <small class="text-white opacity-90 d-none d-sm-block">Kwitansi</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>user/profile" class="btn btn-secondary w-100 h-100 py-2 py-sm-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-1 mb-sm-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </div>
                <span class="fw-semibold text-white small">Profil</span>
                <small class="text-white opacity-90 d-none d-sm-block">Akun saya</small>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Status Info -->
      <div class="card border-0 shadow-sm" data-aos="fade-left" data-aos-delay="200">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
          <h5 class="card-title mb-0 fw-bold h6 h5-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="16" x2="12" y2="12"></line>
              <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Informasi Status
          </h5>
        </div>
        <div class="card-body p-3 p-md-4">
          <div class="d-flex align-items-start mb-3">
            <div class="flex-shrink-0 mt-1">
              <div class="bg-warning rounded-circle" style="width: 8px; height: 8px;"></div>
            </div>
            <div class="flex-grow-1 ms-3">
              <small class="fw-semibold">Draft</small>
              <p class="mb-0 small text-muted">Kwitansi sedang dikerjakan, bisa diinput dan diupload dokumen</p>
            </div>
          </div>
          <div class="d-flex align-items-start mb-3">
            <div class="flex-shrink-0 mt-1">
              <div class="bg-info rounded-circle" style="width: 8px; height: 8px;"></div>
            </div>
            <div class="flex-grow-1 ms-3">
              <small class="fw-semibold">Verifikasi</small>
              <p class="mb-0 small text-muted">Menunggu persetujuan admin, tidak bisa diedit</p>
            </div>
          </div>
          <div class="d-flex align-items-start mb-3">
            <div class="flex-shrink-0 mt-1">
              <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
            </div>
            <div class="flex-grow-1 ms-3">
              <small class="fw-semibold">Disetujui</small>
              <p class="mb-0 small text-muted">Kwitansi sudah disetujui, bisa dicetak</p>
            </div>
          </div>
          <div class="d-flex align-items-start">
            <div class="flex-shrink-0 mt-1">
              <div class="bg-danger rounded-circle" style="width: 8px; height: 8px;"></div>
            </div>
            <div class="flex-grow-1 ms-3">
              <small class="fw-semibold">Ditolak</small>
              <p class="mb-0 small text-muted">Perlu revisi, lihat keterangan admin</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Create Kwitansi Confirmation Modal -->
<div class="modal fade" id="createKwitansiModal" tabindex="-1" aria-labelledby="createKwitansiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title h6 h5-md" id="createKwitansiModalLabel">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
          </svg>
          Buat Kwitansi Baru
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <strong>Konfirmasi:</strong> Anda akan membuat kwitansi baru untuk kegiatan:
        </div>
        <div class="mb-3">
          <strong>Kegiatan:</strong> <span id="modalKegiatanName" class="text-primary">-</span>
        </div>
        <p class="mb-0">Kwitansi akan dibuat dengan status <strong>Draft</strong> dan Anda akan diarahkan ke halaman input kwitansi.</p>
        <div class="alert alert-warning">
          <strong>Perhatian!:</strong> Anda tidak akan bisa membuat kwitansi lainnya sebelum kwitansi ini disetujui. Jika belum maka anda <strong>harus</strong>  menyelesaikan kwitansi ini
        </div>
        <input type="hidden" id="modalKegiatanId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmCreateKwitansi">
          <span class="spinner-border spinner-border-sm d-none" role="status"></span>
          Ya, Buat Kwitansi
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Your existing JavaScript remains exactly the same - no changes needed
const BASEURL = '<?= BASEURL ?>';

// Initialize Dashboard
$(document).ready(function() {
    initializeEventHandlers();
});

// Initialize event handlers
function initializeEventHandlers() {
    // Handle create kwitansi button click
    $(document).on('click', '.create-kwitansi', function() {
        const kegiatanId = $(this).data('id');
        const kegiatanNama = $(this).data('nama');
        showCreateKwitansiModal(kegiatanId, kegiatanNama);
    });

    // Handle confirm create kwitansi
    $('#confirmCreateKwitansi').click(function() {
        createKwitansi();
    });
}

// Show create kwitansi confirmation modal
function showCreateKwitansiModal(kegiatanId, kegiatanNama) {
    $('#modalKegiatanId').val(kegiatanId);
    $('#modalKegiatanName').text(kegiatanNama);
    $('#createKwitansiModal').modal('show');
    cleanupStuckModals();
}

function cleanupStuckModals() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('.modal').hide();
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
}

// Create new kwitansi via AJAX
function createKwitansi() {
    const kegiatanId = $('#modalKegiatanId').val();
    const confirmBtn = $('#confirmCreateKwitansi');

    // Show loading state
    confirmBtn.prop('disabled', true);
    confirmBtn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: BASEURL + "user/home",
        method: 'POST',
        data: {
            a: "create_kwitansi",
            kegiatan_id: kegiatanId
        },
        dataType: 'json',
        success: function(res) {
            confirmBtn.prop('disabled', false);
            confirmBtn.find('.spinner-border').addClass('d-none');

            if (res.status === "success") {
                $('#createKwitansiModal').modal('hide');
                showAlert('Kwitansi berhasil dibuat! Mengarahkan ke halaman input...', 'success');
                setTimeout(() => {
                    window.location.href = res.redirect_url;
                }, 1500);
            } else {
                showAlert(res.message || 'Gagal membuat kwitansi', 'danger');
                $('html, body').animate({ scrollTop: 0 }, 500);
            }
        },
        error: function(xhr, status, error) {
            confirmBtn.prop('disabled', false);
            confirmBtn.find('.spinner-border').addClass('d-none');
            showAlert('Terjadi kesalahan saat membuat kwitansi', 'danger');
            $('html, body').animate({ scrollTop: 0 }, 500);
        }
    });
}

// Refresh kegiatan list via AJAX
function refreshKegiatan() {
    $.ajax({
        url: BASEURL + "user/home",
        method: 'POST',
        data: { a: "get_kegiatan" },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                updateKegiatanList(res.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to refresh kegiatan:', error);
        }
    });
}

// Update kegiatan list
function updateKegiatanList(kegiatans) {
    const container = $('#kegiatanContainer');

    if (kegiatans.length === 0) {
        container.html(`
            <div class="text-center text-muted py-4 py-md-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p class="mb-0 fw-semibold">Belum ada kegiatan yang di-assign</p>
                <small class="text-muted">Admin akan meng-assign kegiatan baru untuk Anda</small>
            </div>
        `);
        return;
    }

    let desktopHtml = `
        <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0 d-none d-md-table">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 small text-uppercase fw-semibold text-muted">Kegiatan</th>
                        <th class="border-0 small text-uppercase fw-semibold text-muted">Kode Rekening</th>
                        <th class="border-0 small text-uppercase fw-semibold text-muted">Jenis Kwitansi</th>
                        <th class="border-0 small text-uppercase fw-semibold text-muted">Jenis TTD</th>
                        <th class="border-0 small text-uppercase fw-semibold text-muted">Total Kwitansi</th>
                        <th class="border-0 small text-uppercase fw-semibold text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
    `;

    let mobileHtml = `<div class="d-md-none">`;

    kegiatans.forEach(kegiatan => {
        const jenisText = kegiatan.jenis_kwitansi === 'perjalanan_dinas' ? 'Perjalanan Dinas' : 'Penyedia Barang';
        const ttdText = kegiatan.jenis_ttd === '4_ttd' ? '4 TTD' : '3 TTD';

        // Desktop table row
        desktopHtml += `
            <tr class="kegiatan-row" data-id="${kegiatan.id}">
                <td>
                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="${kegiatan.nama_kegiatan}">
                        ${kegiatan.nama_kegiatan}
                    </span>
                </td>
                <td>
                    <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded">${kegiatan.kode_rekening}</code>
                </td>
                <td>
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                        ${jenisText}
                    </span>
                </td>
                <td>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                        ${ttdText}
                    </span>
                </td>
                <td>
                    <span class="badge bg-light text-dark">
                        ${kegiatan.total_kwitansi || 0} kwitansi
                    </span>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm create-kwitansi" data-id="${kegiatan.id}" data-nama="${kegiatan.nama_kegiatan}">
                        <span class="d-none d-md-inline">Buat Kwitansi</span>
                        <span class="d-md-none">Buat</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </td>
            </tr>
        `;

        // Mobile card
        mobileHtml += `
            <div class="card mb-3 border shadow-sm kegiatan-row" data-id="${kegiatan.id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 text-truncate me-2" title="${kegiatan.nama_kegiatan}">
                            ${kegiatan.nama_kegiatan}
                        </h6>
                        <span class="badge bg-light text-dark small">
                            ${kegiatan.total_kwitansi || 0} kwitansi
                        </span>
                    </div>

                    <div class="mb-2">
                        <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small">${kegiatan.kode_rekening}</code>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 small">
                            ${jenisText}
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 small">
                            ${ttdText}
                        </span>
                    </div>

                    <button class="btn btn-primary btn-sm w-100 create-kwitansi" data-id="${kegiatan.id}" data-nama="${kegiatan.nama_kegiatan}">
                        Buat Kwitansi
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    });

    desktopHtml += `</tbody></table>`;
    mobileHtml += `</div></div>`;

    container.html(desktopHtml + mobileHtml);
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

<link rel="stylesheet" href="assets/css/dashboard_user.css">

<?php
}