<?php
if(!defined('BASEURL')){
  die("HAYOoo.. mau ngapain? (-_-')");
}
else{
?>#judul('Input Kwitansi - PPTK')
<div class="container-fluid mt-4">

  <!-- Alert Container -->
  <div id="alertContainer"></div>

  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
        <div class="card bg-<?= $data['kwitansi']['status'] === 'ditolak' ? 'danger' : 'primary' ?> text-white" data-aos="fade-down">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title mb-2">
                            <?= $data['kwitansi']['status'] === 'ditolak' ? 'Revisi Data Kwitansi' : 'Input Data Kwitansi' ?>
                        </h3>
                        <p class="card-text mb-0 opacity-75">
                            No. Kwitansi: <strong><?= htmlspecialchars($data['kwitansi']['no_kwitansi']) ?></strong> |
                            Status:
                            <span class="badge bg-<?=
                                $data['kwitansi']['status'] === 'draft' ? 'warning' :
                                ($data['kwitansi']['status'] === 'verifikasi' ? 'info' : 'danger')
                            ?>">
                                <?=
                                    $data['kwitansi']['status'] === 'draft' ? 'Draft' :
                                    ($data['kwitansi']['status'] === 'verifikasi' ? 'Menunggu Verifikasi' : 'Ditolak - Perlu Revisi')
                                ?>
                            </span>
                        </p>

                        <!-- Tampilkan keterangan penolakan jika status ditolak -->
                        <?php if($data['kwitansi']['status'] === 'ditolak' && !empty($data['kwitansi']['keterangan'])): ?>
                        <div class="mt-2 p-2 bg-white bg-opacity-10 rounded">
                            <small>
                                <strong>Alasan Penolakan:</strong><br>
                                <?= htmlspecialchars($data['kwitansi']['keterangan']) ?>
                            </small>
                        </div>
                        <?php endif; ?>
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

  <div class="row g-4">
    <!-- Left Column - Form Input -->
    <div class="col-xl-8">
      <!-- Data Kegiatan -->
      <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
        <div class="card-header bg-transparent border-0 pb-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <polyline points="16 18 22 12 16 6"></polyline>
              <polyline points="8 6 2 12 8 18"></polyline>
            </svg>
            Data Kegiatan
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label small text-muted fw-semibold">Kegiatan</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['kwitansi']['nama_kegiatan']) ?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label small text-muted fw-semibold">Kode Rekening</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['kwitansi']['kode_rekening']) ?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label small text-muted fw-semibold">Jenis Belanja</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['kwitansi']['nama_belanja']) ?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label small text-muted fw-semibold">Jenis Kwitansi</label>
                <input type="text" class="form-control" value="<?= $data['kwitansi']['jenis_kwitansi'] === 'perjalanan_dinas' ? 'Perjalanan Dinas' : 'Penyediaan Barang/Jasa' ?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label small text-muted fw-semibold">Jenis TTD</label>
                <input type="text" class="form-control" value="<?= $data['kwitansi']['jenis_ttd'] === '3_ttd' ? '3 Tanda Tangan' : '4 Tanda Tangan' ?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label small text-muted fw-semibold">PPTK</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['kwitansi']['nama_pegawai']) ?> (<?= htmlspecialchars($data['kwitansi']['nip']) ?>)" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Input Kwitansi -->
      <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header bg-transparent border-0 pb-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Data Kwitansi
          </h5>
        </div>
        <div class="card-body">
          <form id="kwitansiForm">
            <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">

            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Uraian SPJ <span class="text-danger">*</span></label>
                  <textarea class="form-control" name="uraian_spj" rows="3" placeholder="Masukkan uraian SPJ..."
                            <?= !in_array($data['kwitansi']['status'], ['draft', 'ditolak']) ? 'readonly' : '' ?>><?= htmlspecialchars($data['kwitansi']['uraian_spj'] ?? '') ?></textarea>
                  <div class="form-text">Deskripsikan kegiatan yang dilakukan secara detail</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Total Uang (Rp) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control" name="total_uang"
                           value="<?= $data['kwitansi']['total_uang'] ? number_format($data['kwitansi']['total_uang'], 0, '', '') : '' ?>"
                           placeholder="0" min="0" step="1000"
                           <?= !in_array($data['kwitansi']['status'], ['draft', 'ditolak']) ? 'readonly' : '' ?>>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Triwulan <span class="text-danger">*</span></label>
                  <select class="form-select" name="triwulan" <?= !in_array($data['kwitansi']['status'], ['draft', 'ditolak']) ? 'disabled' : '' ?>>
                    <option value="">Pilih Triwulan</option>
                    <option value="I" <?= ($data['kwitansi']['triwulan'] ?? '') === 'I' ? 'selected' : '' ?>>I (Satu)</option>
                    <option value="II" <?= ($data['kwitansi']['triwulan'] ?? '') === 'II' ? 'selected' : '' ?>>II (Dua)</option>
                    <option value="III" <?= ($data['kwitansi']['triwulan'] ?? '') === 'III' ? 'selected' : '' ?>>III (Tiga)</option>
                    <option value="IV" <?= ($data['kwitansi']['triwulan'] ?? '') === 'IV' ? 'selected' : '' ?>>IV (Empat)</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Tanggal Kwitansi <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" name="tgl_kwitansi"
                         value="<?= $data['kwitansi']['tgl_kwitansi'] ?? '' ?>"
                         <?= !in_array($data['kwitansi']['status'], ['draft', 'ditolak']) ? 'readonly' : '' ?>>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Terbilang</label>
                  <input type="text" class="form-control" id="terbilangPreview"
                         value="<?= htmlspecialchars($data['kwitansi']['terbilang'] ?? '') ?>" readonly
                         style="background-color: #f8f9fa; font-style: italic;">
                  <div class="form-text">Auto-generated dari total uang</div>
                </div>
              </div>

              <!-- Penyedia (hanya untuk jenis penyedia) -->
              <?php if($data['kwitansi']['jenis_kwitansi'] === 'penyedia'): ?>
              <div class="col-12">
                  <div class="mb-3">
                      <label class="form-label small text-muted fw-semibold">Penyedia Barang/Jasa <span class="text-danger">*</span></label>

                      <!-- Radio options untuk memilih cara input -->
                      <div class="mb-3">
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="penyedia_option" id="penyedia_existing" value="existing" checked>
                              <label class="form-check-label small" for="penyedia_existing">Pilih dari daftar</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="penyedia_option" id="penyedia_new" value="new">
                              <label class="form-check-label small" for="penyedia_new">Input manual</label>
                          </div>
                      </div>

                      <!-- Existing Penyedia Dropdown -->
                      <div id="existingPenyediaSection">
                          <select class="form-select" name="penyedia_id" id="penyedia_id" <?= !in_array($data['kwitansi']['status'], ['draft', 'ditolak']) ? 'disabled' : '' ?>>
                              <option value="">Pilih Penyedia</option>
                              <?php foreach($data['penyedia_list'] as $penyedia): ?>
                                  <option value="<?= $penyedia['id'] ?>"
                                          <?= ($data['kwitansi']['penyedia_id'] ?? '') == $penyedia['id'] ? 'selected' : '' ?>>
                                      <?= htmlspecialchars($penyedia['nama_perusahaan']) ?> - <?= htmlspecialchars($penyedia['nama_penyedia']) ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      </div>

                      <!-- New Penyedia Form -->
                      <div id="newPenyediaSection" style="display: none;">
                          <div class="card border-primary">
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label class="form-label small text-muted fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" name="new_nama_perusahaan" id="new_nama_perusahaan" placeholder="PT. Contoh Perusahaan">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label class="form-label small text-muted fw-semibold">Nama Penanggung Jawab <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" name="new_nama_penyedia" id="new_nama_penyedia" placeholder="Nama lengkap penanggung jawab">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label class="form-label small text-muted fw-semibold">Jabatan</label>
                                              <input type="text" class="form-control" name="new_jabatan" id="new_jabatan" placeholder="Direktur / Manager">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <label class="form-label small text-muted fw-semibold">&nbsp;</label>
                                              <button type="button" class="btn btn-success w-100" id="btnSavePenyedia">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                      <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                      <polyline points="7 3 7 8 15 8"></polyline>
                                                  </svg>
                                                  Simpan Penyedia Baru
                                              </button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="form-text">
                          Pilih penyedia dari daftar atau input manual jika belum terdaftar
                      </div>
                  </div>
              </div>
              <?php endif; ?>

              <!-- Pejabat - Kuasa Pengguna Anggaran -->
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Kuasa Pengguna Anggaran <span class="text-danger">*</span></label>
                  <select class="form-select" name="pejabat_id" <?= !in_array($data['kwitansi']['status'], ['draft', 'ditolak']) ? 'disabled' : '' ?>>
                    <option value="">Pilih Kuasa Pengguna Anggaran</option>
                    <?php foreach($data['pejabat_list'] as $pejabat): ?>
                      <option value="<?= $pejabat['id'] ?>"
                              <?= ($data['kwitansi']['pejabat_id'] ?? '') == $pejabat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pejabat['nama']) ?> - <?= htmlspecialchars($pejabat['jabatan']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Pejabat - Bendahara Pengeluaran -->
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label small text-muted fw-semibold">Bendahara Pengeluaran <span class="text-danger">*</span></label>
                  <select class="form-select" name="bendahara_id" <?= !in_array($data['kwitansi']['status'], ['draft', 'ditolak']) ? 'disabled' : '' ?>>
                    <option value="">Pilih Bendahara Pengeluaran</option>
                    <?php foreach($data['bendahara_list'] as $bendahara): ?>
                      <option value="<?= $bendahara['id'] ?>"
                              <?= ($data['kwitansi']['bendahara_id'] ?? '') == $bendahara['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($bendahara['nama']) ?> - <?= htmlspecialchars($bendahara['jabatan']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <?php if(in_array($data['kwitansi']['status'], ['draft', 'ditolak'])): ?>
                        <!-- IMPROVED ACTION BUTTONS FOR MOBILE -->
            <div class="row mt-4">
              <div class="col-12">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <button type="button" class="btn btn-outline-secondary btn-mobile" onclick="window.location.href='<?= BASEURL ?>user/home'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    <span class="d-none d-md-inline">Kembali</span>
                    <span class="d-inline d-md-none">Kembali</span>
                  </button>
                  <button type="button" class="btn btn-primary btn-mobile" id="btnSimpan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    </svg>
                    <span class="d-none d-md-inline">Simpan Draft</span>
                    <span class="d-inline d-md-none">Simpan</span>
                  </button>
                  <button type="button" class="btn btn-success btn-mobile" id="btnSubmit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    </svg>
                    <span class="d-none d-md-inline">Submit Verifikasi</span>
                    <span class="d-inline d-md-none">Submit</span>
                  </button>
                </div>
              </div>
            </div>
            <?php else: ?>
            <div class="row mt-4">
              <div class="col-12">
                <div class="alert alert-info">
                  <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="12" y1="16" x2="12" y2="12"></line>
                      <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>Kwitansi sedang dalam proses verifikasi. Tidak dapat dilakukan perubahan.</span>
                  </div>
                </div>
                <div class="d-flex gap-2 justify-content-end">
                  <a href="<?= BASEURL ?>user/home" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M3 9l9-7 9 7"></path>
                      <path d="M9 22V12h6v10"></path>
                    </svg>
                    Kembali ke Dashboard
                  </a>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>

    <!-- Right Column - Dokumen & Info -->
    <div class="col-xl-4">
      <!-- Dokumen Persyaratan -->
      <div class="card border-0 shadow-sm mb-4" data-aos="fade-left">
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
              Dokumen Persyaratan
            </h5>
            <span class="badge bg-<?= $data['doc_completeness']['is_complete'] ? 'success' : 'warning' ?>">
              <?= $data['doc_completeness']['total_uploaded'] ?>/<?= $data['doc_completeness']['total_required'] ?>
            </span>
          </div>
        </div>
        <div class="card-body">
          <?php if(empty($data['required_documents'])): ?>
            <div class="text-center text-muted py-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <p class="mb-0 small">Tidak ada persyaratan dokumen</p>
            </div>
          <?php else: ?>
            <div class="list-group list-group-flush">
              <?php foreach($data['required_documents'] as $doc): ?>
                <div class="list-group-item px-0 py-3 border-0">
                  <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 mt-1">
                      <?php if($doc['dokumen_id']): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-success">
                          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                          <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                      <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning">
                          <circle cx="12" cy="12" r="10"></circle>
                          <line x1="12" y1="8" x2="12" y2="12"></line>
                          <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                      <?php endif; ?>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-1 small fw-semibold"><?= htmlspecialchars($doc['nama_syarat']) ?></h6>
                      <?php if($doc['dokumen_id']): ?>
                        <div class="d-flex align-items-center justify-content-between">
                          <small class="text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <circle cx="12" cy="12" r="10"></circle>
                              <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <?= date('d M Y H:i', strtotime($doc['uploaded_at'])) ?>
                          </small>
                          <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary btn-sm view-document"
                                    data-file="<?= htmlspecialchars($doc['file']) ?>"
                                    data-name="<?= htmlspecialchars($doc['nama_syarat']) ?>">
                              Lihat
                            </button>
                            <?php if(in_array($data['kwitansi']['status'], ['draft', 'ditolak'])): ?>
                              <button type="button" class="btn btn-outline-danger btn-sm delete-document"
                                      data-dokumen-id="<?= $doc['dokumen_id'] ?>"
                                      data-name="<?= htmlspecialchars($doc['nama_syarat']) ?>">
                                Hapus
                              </button>
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php else: ?>
                        <?php if(in_array($data['kwitansi']['status'], ['draft', 'ditolak'])): ?>
                          <div class="mt-2">
                            <input type="file" class="form-control form-control-sm upload-document"
                                   data-syarat-id="<?= $doc['syarat_id'] ?>"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text small">Max 10MB (PDF, JPG, PNG)</div>
                          </div>
                        <?php else: ?>
                          <small class="text-danger">Belum diupload</small>
                        <?php endif; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">Progress Upload</small>
                <small class="text-muted">
                  <?= $data['doc_completeness']['total_uploaded'] ?>/<?= $data['doc_completeness']['total_required'] ?>
                  (<?= round(($data['doc_completeness']['total_uploaded'] / max($data['doc_completeness']['total_required'], 1)) * 100) ?>%)
                </small>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-<?= $data['doc_completeness']['is_complete'] ? 'success' : 'warning' ?>"
                     style="width: <?= ($data['doc_completeness']['total_uploaded'] / max($data['doc_completeness']['total_required'], 1)) * 100 ?>%">
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Informasi Penting -->
      <div class="card border-0 shadow-sm" data-aos="fade-left" data-aos-delay="100">
        <div class="card-header bg-transparent border-0">
          <h5 class="card-title mb-0 fw-bold text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="16" x2="12" y2="12"></line>
              <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Informasi
          </h5>
        </div>
        <div class="card-body">
          <div class="alert alert-info border-0 bg-light text-black">
            <small>
              <strong>Perhatian:</strong><br>
              • Pastikan semua data terisi dengan benar<br>
              • Upload semua dokumen persyaratan<br>
              • Data tidak dapat diubah setelah disubmit untuk verifikasi<br>
              • Simpan Draft hanya untuk menyimpan data yang telah diubah
            </small>
          </div>

          <?php if($data['kwitansi']['status'] === 'draft' && !$data['doc_completeness']['is_complete']): ?>
            <div class="alert alert-warning border-0">
              <small>
                <strong>Peringatan:</strong><br>
                Dokumen belum lengkap. Harap upload semua dokumen persyaratan sebelum submit.
              </small>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal View Document -->
<div class="modal fade pt-10" id="viewDocumentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentModalTitle">View Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div id="documentViewer">
          <!-- Document will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <a href="#" class="btn btn-primary" id="downloadDocument">Download</a>
      </div>
    </div>
  </div>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';
let currentKwitansiId = <?= $data['kwitansi']['Id'] ?>;

// Initialize
$(document).ready(function() {
    initializeEventHandlers();
    updateTerbilangPreview();
    togglePenyediaInput();
});

// Initialize event handlers
function initializeEventHandlers() {
    // Auto update terbilang when total uang changes
    $('input[name="total_uang"]').on('input', updateTerbilangPreview);

    // Save draft
    $('#btnSimpan').on('click', saveDraft);

    // Submit for verification
    $('#btnSubmit').on('click', submitKwitansi);

    // Penyedia option toggle
    $('input[name="penyedia_option"]').on('change', function() {
        togglePenyediaInput();
    });

    // Save new penyedia
    $('#btnSavePenyedia').on('click', saveNewPenyedia);

    // Document upload
    $(document).on('change', '.upload-document', handleDocumentUpload);

    // Document view
    $(document).on('click', '.view-document', viewDocument);

    // Document delete
    $(document).on('click', '.delete-document', deleteDocument);
}

// Update terbilang preview
function updateTerbilangPreview() {
    const totalUang = $('input[name="total_uang"]').val();

    if (totalUang) {
        // Remove dots (thousand separators) and any other non-digit characters except decimal point
        const cleanedValue = totalUang.toString().replace(/\./g, '');
              // cleanedValue = Number(cleanedValue);
        // Check if it's a valid number after cleaning
        if (!isNaN(cleanedValue) && cleanedValue !== '') {
            const amount = parseInt(cleanedValue);
            if (amount === 0) {
                $('#terbilangPreview').val('Nol Rupiah');
            } else {
                const terbilang = convertToTerbilang(amount);
                $('#terbilangPreview').val(terbilang + ' Rupiah');
            }
        } else {
            $('#terbilangPreview').val('');
        }
    } else {
        $('#terbilangPreview').val('');
    }
}

function togglePenyediaInput() {
    const option = $('input[name="penyedia_option"]:checked').val();

    if (option === 'existing') {
        $('#existingPenyediaSection').show();
        $('#newPenyediaSection').hide();
        $('#penyedia_id').prop('required', true);
        $('#new_nama_perusahaan').prop('required', false);
        $('#new_nama_penyedia').prop('required', false);
    } else {
        $('#existingPenyediaSection').hide();
        $('#newPenyediaSection').show();
        $('#penyedia_id').prop('required', false);
        $('#new_nama_perusahaan').prop('required', true);
        $('#new_nama_penyedia').prop('required', true);
    }
}

function cleanupStuckModals() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('.modal').hide();
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
}

// Optimized terbilang function
function convertToTerbilang(number) {
    if (number === 0) return 'Nol';
    if (number > 9999999999999999) return 'Angka terlalu besar';

    const bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

    function convertRecursive(num) {
        if (num < 12) return bilangan[num];
        if (num < 20) return bilangan[num - 10] + ' Belas';
        if (num < 100) {
            const puluh = Math.floor(num / 10);
            const sisa = num % 10;
            return bilangan[puluh] + ' Puluh' + (sisa > 0 ? ' ' + bilangan[sisa] : '');
        }
        if (num < 200) return 'Seratus ' + convertRecursive(num - 100);
        if (num < 1000) {
            const ratus = Math.floor(num / 100);
            const sisa = num % 100;
            return bilangan[ratus] + ' Ratus' + (sisa > 0 ? ' ' + convertRecursive(sisa) : '');
        }
        if (num < 2000) return 'Seribu ' + convertRecursive(num - 1000);
        if (num < 1000000) {
            const ribu = Math.floor(num / 1000);
            const sisa = num % 1000;
            return convertRecursive(ribu) + ' Ribu' + (sisa > 0 ? ' ' + convertRecursive(sisa) : '');
        }
        if (num < 1000000000) {
            const juta = Math.floor(num / 1000000);
            const sisa = num % 1000000;
            return convertRecursive(juta) + ' Juta' + (sisa > 0 ? ' ' + convertRecursive(sisa) : '');
        }
        if (num < 1000000000000) {
            const milyar = Math.floor(num / 1000000000);
            const sisa = num % 1000000000;
            return convertRecursive(milyar) + ' Milyar' + (sisa > 0 ? ' ' + convertRecursive(sisa) : '');
        }
        if (num < 1000000000000000) {
            const triliun = Math.floor(num / 1000000000000);
            const sisa = num % 1000000000000;
            return convertRecursive(triliun) + ' Triliun' + (sisa > 0 ? ' ' + convertRecursive(sisa) : '');
        }

        const kuadriliun = Math.floor(num / 1000000000000000);
        const sisa = num % 1000000000000000;
        return convertRecursive(kuadriliun) + ' Kuadriliun' + (sisa > 0 ? ' ' + convertRecursive(sisa) : '');
    }

    // Clean up the result
    let result = convertRecursive(number);

    // Fix common issues
    result = result
        .replace(/Satu Puluh/g, 'Sepuluh')
        .replace(/Satu Belas/g, 'Sebelas')
        .replace(/\s+/g, ' ')
        .trim();

    return result;
}

// Save new penyedia
function saveNewPenyedia() {
    const namaPerusahaan = $('#new_nama_perusahaan').val().trim();
    const namaPenyedia = $('#new_nama_penyedia').val().trim();
    const jabatan = $('#new_jabatan').val().trim();

    if (!namaPerusahaan) {
        showAlert('Nama perusahaan harus diisi', 'danger');
        $('#new_nama_perusahaan').focus();
        return;
    }

    if (!namaPenyedia) {
        showAlert('Nama penanggung jawab harus diisi', 'danger');
        $('#new_nama_penyedia').focus();
        return;
    }

    $.ajax({
        url: BASEURL + "user/kwitansi",
        method: 'POST',
        data: {
            a: 'create_penyedia',
            csrf_token: '<?= $data['csrf_token'] ?>',
            nama_perusahaan: namaPerusahaan,
            nama_penyedia: namaPenyedia,
            jabatan: jabatan
        },
        dataType: 'json',
        beforeSend: function() {
            $('#btnSavePenyedia').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');
        },
        success: function(res) {
            if (res.status === "success") {
                showAlert(res.message, 'success');

                // Add new penyedia to dropdown
                const newOption = new Option(
                    `${res.penyedia_data.nama_perusahaan} - ${res.penyedia_data.nama_penyedia}`,
                    res.penyedia_data.id,
                    true,
                    true
                );
                $('#penyedia_id').append(newOption);

                // Switch back to existing selection
                $('input[name="penyedia_option"][value="existing"]').prop('checked', true);
                togglePenyediaInput();

                // Clear new penyedia form
                $('#new_nama_perusahaan').val('');
                $('#new_nama_penyedia').val('');
                $('#new_jabatan').val('');
                

            } else {
                showAlert(res.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error('Save penyedia error:', error);
            showAlert('Terjadi kesalahan saat menyimpan penyedia', 'danger');
        },
        complete: function() {
            $('#btnSavePenyedia').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Penyedia Baru');
        }
    });
}

// Save draft
function saveDraft() {
    const formData = new FormData($('#kwitansiForm')[0]);
    formData.append('a', 'simpan');

    // Handle penyedia selection
    const penyediaOption = $('input[name="penyedia_option"]:checked').val();
    if (penyediaOption === 'new') {
        showAlert('Harap simpan penyedia baru terlebih dahulu sebelum menyimpan draft', 'warning');
        return;
    }

    $.ajax({
        url: BASEURL + "user/kwitansi",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            $('#btnSimpan').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');
        },
        success: function(res) {
            if (res.status === "success") {
                showAlert(res.message, 'success');
                // Refresh page untuk update status jika dari ditolak ke draft
                if (<?= $data['kwitansi']['status'] === 'ditolak' ? 'true' : 'false' ?>) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                showAlert(res.message, 'danger');
                $('html, body').animate({ scrollTop: 0 }, 1000);
                if (res.errors) {
                    displayFormErrors(res.errors);
                    $('html, body').animate({ scrollTop: 0 }, 1000);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Save error:', error);
            showAlert('Terjadi kesalahan saat menyimpan', 'danger');
            $('html, body').animate({ scrollTop: 0 }, 500);
        },
        complete: function() {
            $('#btnSimpan').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Draft');
        }
    });
}

// Submit for verification
function submitKwitansi() {
    if (!confirm('Apakah Anda yakin ingin submit kwitansi untuk verifikasi? Data tidak dapat diubah setelah disubmit.')) {
        return;
    }

    $.ajax({
        url: BASEURL + "user/kwitansi",
        method: 'POST',
        data: {
            a: 'submit',
            csrf_token: '<?= $data['csrf_token'] ?>'
        },
        dataType: 'json',
        beforeSend: function() {
            $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Submitting...');
        },
        success: function(res) {
            if (res.status === "success") {
                showAlert(res.message, 'success');
                setTimeout(() => {
                    window.location.href = '<?= BASEURL ?>user/home';
                }, 2000);
                $('html, body').animate({ scrollTop: 0 }, 500);
            } else {
                showAlert(res.message, 'danger');
                $('html, body').animate({ scrollTop: 0 }, 500);
            }
        },
        error: function(xhr, status, error) {
            console.error('Submit error:', error);
            showAlert('Terjadi kesalahan saat submit', 'danger');
            $('html, body').animate({ scrollTop: 0 }, 500);
        },
        complete: function() {
            $('#btnSubmit').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Submit untuk Verifikasi');
        }
    });
}

// Handle document upload
function handleDocumentUpload(e) {
    const fileInput = e.target;
    const syaratId = $(fileInput).data('syarat-id');
    const file = fileInput.files[0];

    if (!file) return;

    const formData = new FormData();
    formData.append('a', 'upload_dokumen');
    formData.append('csrf_token', '<?= $data['csrf_token'] ?>');
    formData.append('syarat_id', syaratId);
    formData.append('dokumen_file', file);

    $.ajax({
        url: BASEURL + "user/kwitansi",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            $(fileInput).prop('disabled', true);
            $(fileInput).closest('.list-group-item').find('.btn-group').html('<span class="spinner-border spinner-border-sm"></span>');
        },
        success: function(res) {
            if (res.status === "success") {
                showAlert(res.message, 'success');
                $('html, body').animate({ scrollTop: 0 }, 500);
                // Refresh documents list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);

            } else {
              $('html, body').animate({ scrollTop: 0 }, 500);
                showAlert(res.message, 'danger');
                fileInput.value = '';
            }
        },
        error: function(xhr, status, error) {
            console.error('Upload error:', error);
            $('html, body').animate({ scrollTop: 0 }, 500);
            showAlert('Terjadi kesalahan saat upload', 'danger');
            fileInput.value = '';
        },
        complete: function() {
            $(fileInput).prop('disabled', false);
        }
    });
}

// View document
function viewDocument() {
    const fileName = $(this).data('file');
    const docName = $(this).data('name');

    $('#documentModalTitle').text(docName);
    $('#downloadDocument').attr('href', BASEURL + 'assets/img/dokumen/' + fileName);

    // Check file type and display accordingly
    if (fileName.toLowerCase().endsWith('.pdf')) {
        $('#documentViewer').html(`
            <embed src="${BASEURL}assets/img/dokumen/${fileName}" type="application/pdf" width="100%" height="500px">
        `);
    } else {
        $('#documentViewer').html(`
            <img src="${BASEURL}assets/img/dokumen/${fileName}" class="img-fluid" alt="${docName}" style="max-height: 500px;">
        `);
    }

    $('#viewDocumentModal').modal('show');
    cleanupStuckModals();
}

// Delete document
function deleteDocument() {
    const dokumenId = $(this).data('dokumen-id');
    const docName = $(this).data('name');

    if (!confirm(`Apakah Anda yakin ingin menghapus dokumen "${docName}"?`)) {
        return;
    }

    $.ajax({
        url: BASEURL + "user/kwitansi",
        method: 'POST',
        data: {
            a: 'hapus_dokumen',
            csrf_token: '<?= $data['csrf_token'] ?>',
            dokumen_id: dokumenId
        },
        dataType: 'json',
        beforeSend: function() {
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
        },
        success: function(res) {
            if (res.status === "success") {
                showAlert(res.message, 'success');
                $('html, body').animate({ scrollTop: 0 }, 500);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert(res.message, 'danger');
                $('html, body').animate({ scrollTop: 0 }, 500);
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete error:', error);
            showAlert('Terjadi kesalahan saat menghapus', 'danger');
            $('html, body').animate({ scrollTop: 0 }, 500);
        }
    });
}

// Display form errors
function displayFormErrors(errors) {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    $.each(errors, function(field, message) {
        const element = $(`[name="${field}"]`);
        element.addClass('is-invalid');
        element.after(`<div class="invalid-feedback">${message}</div>`);
    });
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
<link rel="stylesheet" href="assets/css/kwitansi_user.css">
<?php
}