<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Cetak extends _Controller
{
    public function __construct()
    {
        parent::__construct();
        $con = $this->database->mysqli;
        $this->mysql->setCon($con);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if ($_SESSION['user_role'] !== 'user') {
            header('HTTP/1.1 403 Forbidden');
            echo "Access denied - PPTK only";
            exit;
        }
    }

    private function validateCsrf($token)
    {
        return !empty($_SESSION['csrf_token']) &&
               !empty($token) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Get data kwitansi lengkap untuk cetak
     */
    private function getDataKwitansiLengkap($kwitansi_id, $pegawai_id)
    {
        $query = "SELECT k.Id, k.no_kwitansi, k.status,
                         kg.jenis_kwitansi, kg.jenis_ttd,
                         k.uraian_spj, k.total_uang, k.terbilang, k.triwulan, k.tgl_kwitansi, k.created_at,
                         kg.nama_kegiatan, kg.rekening_id,
                         r.kode_rekening, b.nama as nama_belanja,
                         p.nama_pegawai as pptk_nama, p.nip as pptk_nip,
                         pj.nama_perusahaan, pj.nama_penyedia, pj.jabatan as penyedia_jabatan,
                         pe.nama as pejabat_nama, pe.jabatan as pejabat_jabatan, pe.nip as pejabat_nip,
                         pb.nama as bendahara_nama, pb.jabatan as bendahara_jabatan, pb.nip as bendahara_nip
                  FROM kwitansi k
                  LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                  LEFT JOIN rekening r ON kg.rekening_id = r.id
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  LEFT JOIN pegawai p ON k.pegawai_id = p.id
                  LEFT JOIN penyedia pj ON k.penyedia_id = pj.id
                  LEFT JOIN pejabat pe ON k.pejabat_id = pe.id
                  LEFT JOIN pejabat pb ON k.bendahara_id = pb.id
                  WHERE k.Id = ? AND k.pegawai_id = ? AND k.status = 'disetujui'";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("ii", $kwitansi_id, $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get dokumen untuk checklist
     */
    private function getDokumenChecklist($kwitansi_id, $rekening_id)
    {
        $query = "SELECT sv.id, sv.nama_syarat, sv.urutan,
                         d.id as dokumen_id, d.nama_dokumen, d.file, d.uploaded_at
                  FROM list_doc ld
                  LEFT JOIN syarat_ver sv ON ld.syarat_id = sv.id
                  LEFT JOIN dokumentasi d ON d.syarat_id = sv.id AND d.kwitansi_id = ?
                  WHERE ld.rekening_id = ?
                  ORDER BY sv.urutan ASC";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("ii", $kwitansi_id, $rekening_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $dokumen = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dokumen[] = $row;
            }
        }
        return $dokumen;
    }

    /**
     * Get list kwitansi yang bisa dicetak (disetujui) - PERBAIKAN: ambil jenis dari kegiatan
     */
    private function getKwitansiCetak($pegawai_id)
    {
        $query = "SELECT k.Id, k.no_kwitansi, k.tgl_kwitansi, k.total_uang,
                         kg.jenis_kwitansi,
                         kg.nama_kegiatan, r.kode_rekening
                  FROM kwitansi k
                  LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                  LEFT JOIN rekening r ON kg.rekening_id = r.id
                  WHERE k.pegawai_id = ? AND k.status = 'disetujui'
                  ORDER BY k.created_at DESC";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $kwitansi = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $kwitansi[] = $row;
            }
        }
        return $kwitansi;
    }

    /**
     * Hitung jumlah kwitansi aktif untuk badge di navbar
     */
    private function hitungActiveKwitansi($pegawai_id)
    {
        $query = "SELECT COUNT(*) as total
                  FROM kwitansi
                  WHERE pegawai_id = ?
                  AND status IN ('draft', 'verifikasi')";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    /**
     * Get template data untuk navbar
     */
    private function getTemplateData($pegawai_id)
    {
        return [
            'active_kwitansi_count' => $this->hitungActiveKwitansi($pegawai_id),
            'user_name' => $_SESSION['nama_pegawai'] ?? 'PPTK',
            'username' => $_SESSION['username'] ?? 'User'
        ];
    }

    /**
     * Format tanggal Indonesia
     */
    private function formatTanggalIndonesia($date)
    {
        $bulan = array(
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );

        $timestamp = strtotime($date);

        $tanggal = date('j', $timestamp);
        $bulan_num = date('n', $timestamp) - 1;
        $tahun = date('Y', $timestamp);

        return $tanggal . ' ' . $bulan[$bulan_num] . ' ' . $tahun;
    }

    /**
     * Helper function untuk format triwulan di checklist
     */
    private function getTriwulanDisplayChecklist($triwulan)
    {
        $triwulan_mapping = [
            'I' => 'I (satu)',
            'II' => 'II (dua)',
            'III' => 'III (tiga)',
            'IV' => 'IV (empat)'
        ];

        if (is_numeric($triwulan)) {
            $triwulan_romawi = ['I', 'II', 'III', 'IV'];
            $triwulan_terbilang = ['satu', 'dua', 'tiga', 'empat'];
            $triwulan_idx = intval($triwulan) - 1;

            if (isset($triwulan_romawi[$triwulan_idx]) && isset($triwulan_terbilang[$triwulan_idx])) {
                return $triwulan_romawi[$triwulan_idx] . ' (' . $triwulan_terbilang[$triwulan_idx] . ')';
            }
        }

        if (isset($triwulan_mapping[$triwulan])) {
            return $triwulan_mapping[$triwulan];
        }

        return $triwulan ?: '-';
    }

    /**
     * Show Cetak page for PPTK - List kwitansi yang bisa dicetak
     */
    public function index()
    {
        $pegawai_id = $_SESSION['user_id'];

        $kwitansiCetak = $this->getKwitansiCetak($pegawai_id);
        $templateData = $this->getTemplateData($pegawai_id);

        $contentData = [
            'csrf_token' => $_SESSION['csrf_token'],
            'kwitansi_list' => $kwitansiCetak
        ];

        $this->view->template(['user'=> $templateData]);
        $this->view->setDir('user');
        $this->view->templateShow(['cetak' => $contentData]);
    }

    /**
     * Cetak PDF Kwitansi - dengan parameter function
     */
    public function kwitansi($id = '')
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $kwitansi_id = intval($id);

            if ($kwitansi_id <= 0) {
                throw new Exception('ID kwitansi tidak valid');
            }

            $data_kwitansi = $this->getDataKwitansiLengkap($kwitansi_id, $pegawai_id);

            if (!$data_kwitansi) {
                throw new Exception('Data kwitansi tidak ditemukan atau tidak memiliki akses');
            }

            $this->generatePDFKwitansi($data_kwitansi);

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            $this->view->pindah('user/cetak');
        }
    }

    /**
     * Cetak PDF Checklist Verifikator - dengan parameter function
     */
    public function checklist($id = '')
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $kwitansi_id = intval($id);

            if ($kwitansi_id <= 0) {
                throw new Exception('ID kwitansi tidak valid');
            }

            $data_kwitansi = $this->getDataKwitansiLengkap($kwitansi_id, $pegawai_id);

            if (!$data_kwitansi) {
                throw new Exception('Data kwitansi tidak ditemukan atau tidak memiliki akses');
            }

            $dokumen_list = $this->getDokumenChecklist($kwitansi_id, $data_kwitansi['rekening_id']);

            $this->generatePDFChecklist($data_kwitansi, $dokumen_list);

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            $this->view->pindah('user/cetak');
        }
    }

    /**
     * Generate PDF Kwitansi
     */
    private function generatePDFKwitansi($data)
    {
        $pdf = $this->pdf;
        $pageWidth = 210 - (15 * 2);
        $col1_w = 40;
        $col2_w = 5;

        $pdf->AddPage();
        $pdf->setMargins(15, 10, 15);

        $pdf->setFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'K W I T A N S I', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->setFont('Arial', '', 10);
        $pdf->SetX(120);
        $pdf->Cell(35, 6, 'Kode Rekening', 0, 0, 'R');
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->setFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, $data['kode_rekening'], 0, 1);

        $pdf->SetX(15);
        $pdf->Ln();

        $pdf->setFont('Arial', '', 10);
        $pdf->Cell($col1_w, 8, 'Sudah Terima Dari', 0, 0);
        $pdf->Cell($col2_w, 8, ':', 0, 0);
        $pdf->Cell(0, 8, 'Pengguna Anggaran Badan Kepegawaian Daerah Provinsi Sulawesi Tengah', 0, 1);

        $pdf->Ln(5);

        $pdf->Cell($col1_w, 8, 'Terbilang ', 0, 0);
        $pdf->Cell($col2_w, 8, ':', 0, 0);
        $pdf->setFont('Arial', 'BI', 10);
        $pdf->Cell(0, 8, $data['terbilang'], 0, 1, 'C');

        $pdf->Ln(5);

        $pdf->setFont('Arial', '', 10);
        $pdf->Cell($col1_w, 6, 'Untuk Pembayaran', 0, 0);
        $pdf->Cell($col2_w, 6, ':', 0, 0);

        $triwulan_display = $this->getTriwulanDisplayChecklist($data['triwulan']);
        $uraian_pembayaran = $data['uraian_spj'] . ' Pada Sub Kegiatan ' . $data['nama_kegiatan'] .' selang Triwulan ' . $triwulan_display . ' Tahun Anggaran ' . date('Y');

        $pdf->MultiCell($pageWidth - $col1_w - $col2_w, 6, $uraian_pembayaran, 0, 'L');
        $pdf->Ln(0);

        $pdf->Ln(5);
        $pdf->setFont('Arial', '', 10);
        $pdf->Cell($col1_w, 8, 'Uang Sejumlah', 0, 0);
        $pdf->Cell($col2_w, 8, ':', 0, 0);
        $pdf->setFont('Arial', 'BI', 12);

        $rp_width = 11;
        $nilai_width = 60;
        $pdf->Cell($rp_width, 8, 'Rp.', 0, 0, 'L');
        $pdf->Cell($nilai_width, 8, number_format($data['total_uang'], 0, ',', '.'), 0, 1, 'L');

        $pdf->Ln(10);

        $pdf->setFont('Arial', '', 10);
        $pdf->SetX(100);
        $pdf->Cell(0, 6, 'Palu, ' . $this->formatTanggalIndonesia($data['tgl_kwitansi']), 0, 1, 'R');

        $pdf->SetX(15);

        $pdf->Ln();

        if ($data['jenis_ttd'] === '4_ttd') {
            $colWidth = $pageWidth / 3;

            $pdf->setFont('Arial', 'B', 10);
            $pdf->Cell($colWidth, 6, '', 0, 0, 'C');
            $pdf->Cell($colWidth, 6, 'Lunas Bayar', 0, 0, 'C');
            $pdf->Cell($colWidth, 6, 'Yang Menerima', 0, 1, 'C');

            $pdf->Cell($colWidth, 6, 'Pejabat Pelaksana Teknis Kegiatan', 0, 0, 'C');
            $pdf->Cell($colWidth, 6, 'Bendahara Pengeluaran', 0, 0, 'C');

            $penyedia_perusahaan = $data['nama_perusahaan'] ?: 'Tidak ada Nama Perusahaan';
            $pdf->Cell($colWidth, 6, $penyedia_perusahaan, 0, 1, 'C');

            $pdf->Ln(20);

            $pdf->setFont('Arial', 'BU', 10);
            $pdf->Cell($colWidth, 6, $data['pptk_nama'], 0, 0, 'C');
            $pdf->Cell($colWidth, 6, $data['bendahara_nama'], 0, 0, 'C');
            $pdf->Cell($colWidth, 6, $data['nama_penyedia'] ?: '-', 0, 1, 'C');

            $pdf->setFont('Arial', 'B', 8);
            $pdf->Cell($colWidth, 4, 'NIP: ' . $data['pptk_nip'], 0, 0, 'C');
            $pdf->Cell($colWidth, 4, 'NIP: ' . $data['bendahara_nip'], 0, 0, 'C');
            $pdf->Cell($colWidth, 4, $data['penyedia_jabatan'] ?: 'Tidak ada Jabatan', 0, 1, 'C');

        } else {
            $colWidth = $pageWidth / 2;

            $pdf->setFont('Arial', 'B', 10);
            $pdf->Cell($colWidth, 6, '', 0, 0, 'C');
            $pdf->Cell($colWidth, 6, 'Lunas Bayar', 0, 1, 'C');

            $pdf->Cell($colWidth, 6, 'Pejabat Pelaksana Teknis Kegiatan', 0, 0, 'C');
            $pdf->Cell($colWidth, 6, 'Bendahara Pengeluaran', 0, 1, 'C');

            $pdf->Ln(20);

            $pdf->setFont('Arial', 'BU', 10);
            $pdf->Cell($colWidth, 6, $data['pptk_nama'], 0, 0, 'C');
            $pdf->Cell($colWidth, 6, $data['bendahara_nama'], 0, 1, 'C');

            $pdf->setFont('Arial', 'B', 8);
            $pdf->Cell($colWidth, 4, 'NIP: ' . $data['pptk_nip'], 0, 0, 'C');
            $pdf->Cell($colWidth, 4, 'NIP: ' . $data['bendahara_nip'], 0, 1, 'C');
        }

        $pdf->Ln(10);
        $pdf->setFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, 'SETUJU BAYAR :', 0, 1, 'C');
        $pdf->Ln(15);

        $pdf->setFont('Arial', 'BU', 10);
        $pdf->Cell(0, 6, $data['pejabat_nama'], 0, 1, 'C');

        $pdf->setFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, 'NIP: ' . $data['pejabat_nip'], 0, 1, 'C');

        $filename = 'kwitansi_' . $data['no_kwitansi'] . '.pdf';
        $pdf->Output('I', $filename, true);
        exit;
    }

    /**
     * Generate PDF Checklist Verifikator
     */
    private function generatePDFChecklist($data, $dokumen_list)
    {
        $pdf = $this->pdf;
        $pageWidth = 210 - (15 * 2);
        $cell_h = 8;
        $col_label_w = 40;
        $col_separator_w = 5;

        $pdf->AddPage();
        $pdf->setMargins(15, 10, 15);

        $pdf->setFont('Arial', '', 10);

        $triwulan_display = $this->getTriwulanDisplayChecklist($data['triwulan']);

        $indent=10;
        $pdf->setX($indent+5);

        $pdf->Cell($col_label_w, 6, 'SUB KEGIATAN', 0, 0);
        $pdf->Cell($col_separator_w, 6, ':', 0, 0);
        $pdf->MultiCell($pageWidth - $col_label_w - $col_separator_w, 6, $data['nama_kegiatan'], 0, 'L');
        $pdf->Ln(0);

        $pdf->Cell($col_label_w, 6, 'BELANJA', 0, 0);
        $pdf->Cell($col_separator_w, 6, ':', 0, 0);
        $pdf->MultiCell($pageWidth - $col_label_w - $col_separator_w, 6, $data['nama_belanja'], 0, 'L');
        $pdf->Ln(0);

        $pdf->Cell($col_label_w, 6, 'URAIAN SPJ', 0, 0);
        $pdf->Cell($col_separator_w, 6, ':', 0, 0);
        $pdf->MultiCell($pageWidth - $col_label_w - $col_separator_w, 6, $data['uraian_spj'], 0, 'L');
        $pdf->Ln(0);

        $pdf->Cell($col_label_w, 6, 'NILAI', 0, 0);
        $pdf->Cell($col_separator_w, 6, ':', 0, 0);
        $pdf->setFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, 'Rp ' . number_format($data['total_uang'], 0, ',', '.'), 0, 1);
        $pdf->setFont('Arial', '', 10);

        $pdf->Cell($col_label_w, 6, 'TRIWULAN', 0, 0);
        $pdf->Cell($col_separator_w, 6, ':', 0, 0);
        $pdf->Cell(0, 6, $triwulan_display, 0, 1);

        $pdf->Ln(10);

        $pdf->setFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'CHEK LIST KELENGKAPAN DOKUMEN', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->setFont('Arial', 'B', 10);

        $col_no_w = 10;
        $col_dokumen_w = 95;
        $col_ada_tidak_w = 30;
        $col_keterangan_w = $pageWidth - $col_no_w - $col_dokumen_w - $col_ada_tidak_w;

        $pdf->Cell($col_no_w, $cell_h, 'No', 1, 0, 'C');
        $pdf->Cell($col_dokumen_w, $cell_h, 'Dokumen', 1, 0, 'C');
        $pdf->Cell($col_ada_tidak_w, $cell_h, 'Ada/Tidak', 1, 0, 'C');
        $pdf->Cell($col_keterangan_w, $cell_h, 'Keterangan', 1, 1, 'C');

        $pdf->setFont('Arial', '', 9);
        $no = 1;
        foreach ($dokumen_list as $dokumen) {
            $pdf->Cell($col_no_w, $cell_h, $no++, 1, 0, 'C');
            $pdf->Cell($col_dokumen_w, $cell_h, $dokumen['nama_syarat'], 1, 0);

            $check = $dokumen['dokumen_id'] ? 'Ada' : 'Tidak Ada';
            $pdf->Cell($col_ada_tidak_w, $cell_h, $check, 1, 0, 'C');
            $pdf->Cell($col_keterangan_w, $cell_h, '', 1, 1);
        }

        $pdf->Ln(15);

        $pdf->setFont('Arial', 'B', 10);

        $col_jabatan_w = 50;
        $col_paraf_w = 60;
        $col_tanggal_w = $pageWidth - $col_jabatan_w - $col_paraf_w;

        $pdf->Cell($col_jabatan_w, $cell_h, 'Jabatan', 1, 0, 'C');
        $pdf->Cell($col_paraf_w, $cell_h, 'Paraf', 1, 0, 'C');
        $pdf->Cell($col_tanggal_w, $cell_h, 'Tanggal', 1, 1, 'C');

        $pdf->setFont('Arial', '', 10);
        $jabatan_list = ['Verifikator 1', 'Verifikator 2', 'Verifikator 3', 'Operator'];
        foreach ($jabatan_list as $jabatan) {
            $pdf->Cell($col_jabatan_w, $cell_h, $jabatan, 1, 0);
            $pdf->Cell($col_paraf_w, $cell_h, '', 1, 0, 'C');
            $pdf->Cell($col_tanggal_w, $cell_h, '', 1, 1, 'C');
        }

        $filename = 'checklist_' . $data['no_kwitansi'] . '.pdf';
        $pdf->Output('I', $filename, true);
        exit;
    }
}