<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Cetak PDF - PPTK')
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
              <h3 class="card-title mb-2">Cetak PDF Kwitansi 🖨️</h3>
              <p class="card-text mb-0 opacity-75">Pilih kwitansi yang sudah disetujui untuk dicetak dalam format PDF</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
              <div class="display-6 opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
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

  <!-- Info Panel -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="alert alert-info border-0" data-aos="fade-up">
        <div class="d-flex align-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-3">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
          </svg>
          <div>
            <strong>Informasi:</strong> Hanya kwitansi dengan status <span class="badge bg-success">Disetujui</span> yang dapat dicetak.
            Pilih jenis cetakan yang diinginkan untuk mengunduh dokumen PDF.
          </div>
        </div>
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
          Daftar Kwitansi yang Dapat Dicetak
        </h5>
        <span class="badge bg-success">
          <?= count($data['kwitansi_list']) ?> Kwitansi Disetujui
        </span>
      </div>
    </div>
    <div class="card-body">
      <?php if(empty($data['kwitansi_list'])): ?>
        <div class="text-center text-muted py-5">
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <h5 class="fw-semibold text-muted">Belum ada kwitansi yang dapat dicetak</h5>
          <p class="text-muted mb-4">Kwitansi yang sudah disetujui akan muncul di sini untuk dicetak</p>
          <a href="<?= BASEURL ?>user/riwayat" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Lihat Riwayat Kwitansi
          </a>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover table-borderless mb-0">
            <thead class="table-light">
              <tr>
                <th class="border-0 small text-uppercase fw-semibold text-muted">No Kwitansi</th>
                <th class="border-0 small text-uppercase fw-semibold text-muted">Kegiatan</th>
                <th class="border-0 small text-uppercase fw-semibold text-muted">Jenis</th>
                <th class="border-0 small text-uppercase fw-semibold text-muted">Total</th>
                <th class="border-0 small text-uppercase fw-semibold text-muted">Tanggal</th>
                <th class="border-0 small text-uppercase fw-semibold text-muted text-center">Aksi Cetak</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data['kwitansi_list'] as $item): ?>
                <tr>
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
                  </td>
                  <td>
                    <strong class="text-success">Rp <?= number_format($item['total_uang'], 0, ',', '.') ?></strong>
                  </td>
                  <td>
                    <small class="text-muted">
                      <?= date('d M Y', strtotime($item['tgl_kwitansi'])) ?>
                    </small>
                  </td>
                  <td class="text-center">
                  <div class="btn-group btn-group-sm" role="group">
                    <!-- Cetak Kwitansi -->
                    <button type="button"
                            class="btn btn-outline-primary cetak-single"
                            data-jenis="kwitansi"
                            data-kwitansi-id="<?= $item['Id'] ?>"
                            data-no-kwitansi="<?= htmlspecialchars($item['no_kwitansi']) ?>"
                            title="Cetak Kwitansi">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                      </svg>
                      Kwitansi
                    </button>

                    <!-- Cetak Checklist -->
                    <button type="button"
                            class="btn btn-outline-success cetak-single"
                            data-jenis="checklist"
                            data-kwitansi-id="<?= $item['Id'] ?>"
                            data-no-kwitansi="<?= htmlspecialchars($item['no_kwitansi']) ?>"
                            title="Cetak Checklist">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                      </svg>
                      Checklist
                    </button>
                  </div>
                </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>


      <?php endif; ?>
    </div>
  </div>

  <!-- Information Section -->
  <div class="row mt-4">
    <div class="col-md-6" data-aos="fade-up">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
          <h6 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="16" x2="12" y2="12"></line>
              <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Panduan Cetak
          </h6>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-start mb-3">
            <div class="flex-shrink-0 mt-1">
              <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
            </div>
            <div class="flex-grow-1 ms-3">
              <small class="fw-semibold">Kwitansi</small>
              <p class="mb-0 small text-muted">Dokumen resmi kwitansi dengan format standar untuk arsip dan pertanggungjawaban</p>
            </div>
          </div>
          <div class="d-flex align-items-start mb-3">
            <div class="flex-shrink-0 mt-1">
              <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
            </div>
            <div class="flex-grow-1 ms-3">
              <small class="fw-semibold">Checklist Verifikator</small>
              <p class="mb-0 small text-muted">Dokumen checklist untuk memverifikasi kelengkapan dokumen pendukung</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
          <h6 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
              <line x1="12" y1="9" x2="12" y2="13"></line>
              <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            Tips & Catatan
          </h6>
        </div>
        <div class="card-body">
          <div class="alert alert-warning border-0 bg-light text-dark small">
            <strong>Perhatian:</strong>
            <ul class="mb-0 mt-2">
              <li>Pastikan browser mengizinkan pop-up untuk membuka PDF</li>
              <li>Simpan file PDF dengan nama yang sesuai untuk arsip</li>
              <li>Cetak menggunakan kertas A4 untuk hasil terbaik</li>
              <li>Periksa kembali data sebelum mencetak</li>
            </ul>
          </div>
          <div class="text-center">
            <a href="<?= BASEURL ?>user/riwayat" class="btn btn-outline-primary btn-sm">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              Lihat Riwayat Lengkap
            </a>
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
    showAlertFromSession();
});

// Initialize event handlers
// Initialize event handlers
function initializeEventHandlers() {


    // Cetak single PDF
    $(document).on('click', '.cetak-single', function() {
        const jenis = $(this).data('jenis');
        const kwitansiId = $(this).data('kwitansi-id');
        const noKwitansi = $(this).data('no-kwitansi');

        cetakSingle(jenis, kwitansiId, noKwitansi);
    });
}



// Single PDF print with better handling
function cetakSingle(jenis, kwitansiId, noKwitansi) {
    const url = `${BASEURL}user/cetak/${jenis}/${kwitansiId}`;
    const fileName = `${jenis}_${noKwitansi}.pdf`;

    // Try to open in new tab
    const newWindow = window.open(url, `pdf_${jenis}_${kwitansiId}`);

    // If popup blocked, show download link
    if (!newWindow || newWindow.closed || typeof newWindow.closed === 'undefined') {
        showAlert(`
            Popup diblokir.
            <a href="${url}" target="_blank" class="alert-link">Klik di sini untuk membuka PDF</a>
            atau
            <a href="${url}" download="${fileName}" class="alert-link">download langsung</a>
        `, 'warning');
    }
}

// Show alert from session (jika ada error dari controller)
function showAlertFromSession() {
    <?php if(isset($_SESSION['error_message'])): ?>
        showAlert('<?= $_SESSION['error_message'] ?>', 'danger');
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['success_message'])): ?>
        showAlert('<?= $_SESSION['success_message'] ?>', 'success');
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
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

// Add loading state to print buttons
$(document).on('click', 'a[href*="cetak/"]', function() {
    const originalText = $(this).html();
    $(this).html('<span class="spinner-border spinner-border-sm me-1"></span>Membuka...').prop('disabled', true);

    setTimeout(() => {
        $(this).html(originalText).prop('disabled', false);
    }, 2000);
});
</script>


<link rel="stylesheet" href="assets/css/cetak_user.css">
<?php
}