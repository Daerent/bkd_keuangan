<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Dashboard Admin')
<div class="container-fluid mt-4">

  <!-- Alert Container -->
  <div id="alertContainer"></div>

  <!-- Welcome Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card bg-primary text-white" data-aos="fade-down">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h3 class="card-title mb-2">Selamat Datang, Admin! 👋</h3>
              <p class="card-text mb-0 opacity-75">Sistem Manajemen Kwitansi - Monitor semua aktivitas kwitansi dalam satu dashboard</p>
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
  <div class="row mb-4">
    <!-- Total Kwitansi -->
    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3" data-aos="fade-up" data-aos-delay="100">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Total Kwitansi</span>
              <h3 class="fw-bold mt-1 mb-0" id="totalKwitansi"><?= $data['stats']['total_kwitansi'] ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <span class="ms-1">All Time</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                  <line x1="12" y1="1" x2="12" y2="23"></line>
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Verifikasi -->
    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3" data-aos="fade-up" data-aos-delay="150">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Menunggu Verifikasi</span>
              <h3 class="fw-bold mt-1 mb-0 text-warning" id="pendingVerifikasi"><?= $data['stats']['pending_verifikasi'] ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span class="ms-1">Perlu Tindakan</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning">
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

    <!-- Disetujui -->
    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3" data-aos="fade-up" data-aos-delay="200">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Disetujui</span>
              <h3 class="fw-bold mt-1 mb-0 text-success" id="disetujuiCount"><?= $data['stats']['disetujui'] ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span class="ms-1">Bulan Ini</span>
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

    <!-- Ditolak -->
    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3" data-aos="fade-up" data-aos-delay="250">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Ditolak</span>
              <h3 class="fw-bold mt-1 mb-0 text-danger" id="ditolakCount"><?= $data['stats']['ditolak'] ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="15" y1="9" x2="9" y2="15"></line>
                  <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <span class="ms-1">Bulan Ini</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger">
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

    <!-- Total PPTK -->
    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3" data-aos="fade-up" data-aos-delay="300">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Total PPTK</span>
              <h3 class="fw-bold mt-1 mb-0" id="totalPPTK"><?= $data['stats']['total_pptk'] ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                </svg>
                <span class="ms-1">Aktif</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-info">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Kegiatan -->
    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-3" data-aos="fade-up" data-aos-delay="350">
      <div class="card border-0 shadow-sm h-100 card-hover">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <span class="text-muted small fw-semibold text-uppercase">Total Kegiatan</span>
              <h3 class="fw-bold mt-1 mb-0" id="totalKegiatan"><?= $data['stats']['total_kegiatan'] ?></h3>
              <div class="text-muted small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                <span class="ms-1">Aktif</span>
              </div>
            </div>
            <div class="flex-shrink-0">
              <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-secondary">
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
  <div class="row g-4">
    <!-- Left Column -->
    <div class="col-xl-8">
      <!-- Status Distribution Chart -->
      <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
        <div class="card-header bg-transparent border-0">
          <h5 class="card-title mb-0 fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
              <circle cx="12" cy="12" r="10"></circle>
              <circle cx="12" cy="12" r="4"></circle>
            </svg>
            Distribusi Status Kwitansi
          </h5>
        </div>
        <div class="card-body">
          <div class="chart-container" style="height: 400px;">
            <canvas id="statusDistributionChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Activities -->
      <div class="card border-0 shadow-sm" data-aos="fade-up">
        <div class="card-header bg-transparent border-0 pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              Aktivitas Terbaru
            </h5>
            <a href="<?= BASEURL ?>admin/riwayat_ver" class="btn btn-sm btn-outline-primary rounded-pill">
              Lihat Semua
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0">
              <thead class="table-light">
                <tr>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">No Kwitansi</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">PPTK</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Kegiatan</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Status</th>
                  <th class="border-0 small text-uppercase fw-semibold text-muted">Tanggal</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($data['recent_activities'])): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                      <div class="py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3 text-muted">
                          <circle cx="12" cy="12" r="10"></circle>
                          <line x1="12" y1="8" x2="12" y2="12"></line>
                          <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p class="mb-0 fw-semibold">Belum ada aktivitas</p>
                        <small class="text-muted">Aktivitas terbaru akan muncul di sini</small>
                      </div>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach($data['recent_activities'] as $activity): ?>
                    <tr class="activity-row">
                      <td>
                        <div class="d-flex align-items-center">
                          <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded"><?= htmlspecialchars($activity['no_kwitansi'] ?? '-') ?></code>
                        </div>
                      </td>
                      <td>
                        <span class="fw-medium"><?= htmlspecialchars($activity['pptk'] ?? '-') ?></span>
                      </td>
                      <td>
                        <span class="d-inline-block text-truncate" style="max-width: 180px;" title="<?= htmlspecialchars($activity['nama_kegiatan'] ?? '') ?>">
                          <?= htmlspecialchars($activity['nama_kegiatan'] ?? '-') ?>
                        </span>
                      </td>
                      <td>
                        <?php
                          $statusClass = [
                            'verifikasi' => 'warning',
                            'disetujui' => 'success',
                            'ditolak' => 'danger',
                            'draft' => 'secondary'
                          ][$activity['status']] ?? 'secondary';

                          $statusText = [
                            'verifikasi' => 'Menunggu',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            'draft' => 'Draft'
                          ][$activity['status']] ?? $activity['status'];
                        ?>
                        <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> border border-<?= $statusClass ?> border-opacity-25">
                          <?= $statusText ?>
                        </span>
                      </td>
                      <td>
                        <small class="text-muted">
                          <?= date('d M Y', strtotime($activity['created_at'])) ?>
                        </small>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-xl-4">
      <!-- Quick Actions -->
      <div class="card border-0 shadow-sm mb-4" data-aos="fade-left">
        <div class="card-header bg-transparent border-0 pb-0">
          <h5 class="card-title mb-0 fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-primary">
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
              <a href="<?= BASEURL ?>admin/verifikasi" class="btn btn-warning w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                </div>
                <span class="fw-semibold text-white">Verifikasi</span>
                <small class="text-white opacity-90"><?= $data['stats']['pending_verifikasi'] ?> Pending</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>admin/penugasan" class="btn btn-primary w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                  </svg>
                </div>
                <span class="fw-semibold text-white">Penugasan</span>
                <small class="text-white opacity-90">PPTK & Kegiatan</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>admin/riwayat_ver" class="btn btn-success w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                </div>
                <span class="fw-semibold text-white">Riwayat</span>
                <small class="text-white opacity-90">Disetujui & Ditolak</small>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= BASEURL ?>admin/master2" class="btn btn-info w-100 h-100 py-3 d-flex flex-column align-items-center text-decoration-none rounded-3 action-card">
                <div class="bg-white bg-opacity-25 p-2 rounded-circle mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  </svg>
                </div>
                <span class="fw-semibold text-white">Master Data</span>
                <small class="text-white opacity-90">User & Penyedia</small>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Urgent -->
      <?php if(!empty($data['pending_verifikasi_list'])): ?>
      <div class="card border-0 shadow-sm" data-aos="fade-left" data-aos-delay="200">
        <div class="card-header bg-warning bg-opacity-10 border-0">
          <h5 class="card-title mb-0 text-warning fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Perhatian! Menunggu Verifikasi
          </h5>
        </div>
        <div class="card-body">
          <?php foreach($data['pending_verifikasi_list'] as $pending): ?>
            <div class="d-flex align-items-start mb-3 p-3 bg-warning bg-opacity-5 rounded-3">
              <div class="flex-shrink-0 mt-1">
                <div class="spinner-grow spinner-grow-sm text-warning" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6 class="mb-1 fw-semibold">
                  <code class="text-warning"><?= htmlspecialchars($pending['no_kwitansi']) ?></code>
                </h6>
                <p class="mb-1 small text-muted"><?= htmlspecialchars($pending['pptk']) ?></p>
                <p class="mb-2 small text-muted">
                  <?= htmlspecialchars($pending['nama_kegiatan']) ?>
                </p>
                <small class="text-muted">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                  </svg>
                  <?= date('d M Y', strtotime($pending['created_at'])) ?>
                </small>
              </div>
            </div>
          <?php endforeach; ?>
          <div class="text-center mt-3">
            <a href="<?= BASEURL ?>admin/verifikasi" class="btn btn-warning btn-sm rounded-pill px-4">
              Verifikasi Sekarang
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const BASEURL = '<?= BASEURL ?>';
let statusDistributionChart = null;

// Initialize Dashboard
$(document).ready(function() {
    initializeChart();
    startAutoRefresh();
});

// Initialize Chart
function initializeChart() {
    loadChartData();
}

// Load chart data via AJAX
function loadChartData() {
    $.ajax({
        url: BASEURL + "admin/dashboard",
        method: 'POST',
        data: { a: "get_chart_data" },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                renderStatusDistributionChart(res.data.status_distribution || {});
            } else {
                showChartError();
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to load chart data:', error);
            showChartError();
        }
    });
}

// Status Distribution Chart (Doughnut)
function renderStatusDistributionChart(statusData) {
    const ctx = document.getElementById('statusDistributionChart')?.getContext('2d');
    if (!ctx) {
        console.error('Status distribution chart canvas not found');
        return;
    }

    console.log('Rendering status distribution chart with data:', statusData);

    // Process status data
    const labels = ['Disetujui', 'Menunggu Verifikasi', 'Ditolak', 'Draft'];
    const data = [
        parseInt(statusData['disetujui']) || 0,
        parseInt(statusData['verifikasi']) || 0,
        parseInt(statusData['ditolak']) || 0,
        parseInt(statusData['draft']) || 0
    ];

    // Filter out labels with zero data
    const filteredLabels = [];
    const filteredData = [];
    const filteredColors = ['#198754', '#ffc107', '#dc3545', '#6c757d'];
    const finalColors = [];

    labels.forEach((label, index) => {
        if (data[index] > 0) {
            filteredLabels.push(label);
            filteredData.push(data[index]);
            finalColors.push(filteredColors[index]);
        }
    });

    // Destroy existing chart
    if (statusDistributionChart) {
        statusDistributionChart.destroy();
    }

    // Create new chart
    statusDistributionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: filteredLabels.length > 0 ? filteredLabels : ['Tidak ada data'],
            datasets: [{
                data: filteredData.length > 0 ? filteredData : [1],
                backgroundColor: finalColors.length > 0 ? finalColors : ['#6c757d'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%',
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    console.log('Status distribution chart rendered successfully');
}

// Show error state for chart
function showChartError() {
    const chartContainer = document.getElementById('statusDistributionChart')?.parentElement;
    if (chartContainer) {
        chartContainer.innerHTML = `
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <p class="mb-0 fw-semibold">Gagal memuat chart</p>
                    <small>Silakan refresh halaman</small>
                </div>
            </div>
        `;
    }
}

// Auto-refresh stats every 30 seconds
function startAutoRefresh() {
    setInterval(() => {
        updateDashboardStats();
    }, 30000);
}

// Update stats via AJAX
function updateDashboardStats() {
    $.ajax({
        url: BASEURL + "admin/dashboard",
        method: 'POST',
        data: { a: "get_stats" },
        dataType: 'json',
        success: function(res) {
            if (res.status === "success") {
                updateStatsCards(res.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to update dashboard stats');
        }
    });
}

// Update stats cards with animation
function updateStatsCards(stats) {
    animateCountUpdate('#totalKwitansi', stats.total_kwitansi);
    animateCountUpdate('#pendingVerifikasi', stats.pending_verifikasi);
    animateCountUpdate('#disetujuiCount', stats.disetujui);
    animateCountUpdate('#ditolakCount', stats.ditolak);
    animateCountUpdate('#totalPPTK', stats.total_pptk);
    animateCountUpdate('#totalKegiatan', stats.total_kegiatan);
}

// Animate number updates
function animateCountUpdate(selector, newValue) {
    const element = $(selector);
    const currentValue = parseInt(element.text()) || 0;

    if (currentValue !== newValue) {
        element.fadeOut(200, function() {
            $(this).text(newValue).fadeIn(200);
        });
    }
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
<link rel="stylesheet" href="assets/css/dashboard_admin.css">
<?php
}