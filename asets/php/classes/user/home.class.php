<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Home extends _Controller
{
    public function __construct()
    {
        parent::__construct();
        $con = $this->database->mysqli;
        $this->mysql->setCon($con);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Hanya user (PPTK) yang boleh akses
        if ($_SESSION['user_role'] !== 'user') {
            header('HTTP/1.1 403 Forbidden');
            echo "Access denied - PPTK only";
            exit;
        }
    }

    //****************** HELPER FUNCTION (SISTEM REVISI) *********************//

    /**
     * Cek apakah PPTK memiliki kwitansi aktif (draft/verifikasi)
     */
    private function hasActiveKwitansi($pegawai_id)
    {
        $query = "SELECT COUNT(*) as total
                  FROM kwitansi
                  WHERE pegawai_id = ?
                  AND status IN ('draft', 'verifikasi','ditolak')";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] > 0;
        }
        return false;
    }

    /**
     * Get kwitansi aktif PPTK - PERBAIKAN: ambil jenis_kwitansi dan jenis_ttd dari kegiatan
     */
    private function getActiveKwitansi($pegawai_id)
    {
        $query = "SELECT k.Id, k.no_kwitansi, k.status,
                         kg.jenis_kwitansi, kg.jenis_ttd,
                         kg.nama_kegiatan, r.kode_rekening, b.nama as nama_belanja,
                         k.created_at, k.tgl_kwitansi, k.total_uang
                  FROM kwitansi k
                  LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                  LEFT JOIN rekening r ON kg.rekening_id = r.id
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  WHERE k.pegawai_id = ?
                  AND k.status IN ('draft', 'verifikasi')
                  ORDER BY k.created_at DESC
                  LIMIT 1";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get kegiatan yang di-assign ke PPTK ini - SUDAH BENAR
     */
    private function getAssignedKegiatan($pegawai_id)
    {
        $query = "SELECT k.id, k.nama_kegiatan, k.jenis_kwitansi, k.jenis_ttd,
                         r.kode_rekening, b.nama as nama_belanja,
                         (SELECT COUNT(*) FROM kwitansi kw
                          WHERE kw.kegiatan_id = k.id
                          AND kw.pegawai_id = ?) as total_kwitansi
                  FROM kegiatan k
                  LEFT JOIN rekening r ON k.rekening_id = r.id
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  WHERE k.pptk_id = ?
                  ORDER BY k.nama_kegiatan ASC";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("ii", $pegawai_id, $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $kegiatans = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $kegiatans[] = $row;
            }
        }
        return $kegiatans;
    }

    /**
     * Get statistics for dashboard
     */
    private function getDashboardStatistics($pegawai_id)
    {
        $stats = [
            'total_kegiatan' => 0,
            'draft_count' => 0,
            'verifikasi_count' => 0,
            'disetujui_count' => 0,
            'ditolak_count' => 0,
            'active_kwitansi_count' => 0
        ];

        // Hitung total kegiatan yang di-assign
        $kegiatanQuery = "SELECT COUNT(*) as total
                         FROM kegiatan
                         WHERE pptk_id = ?";
        $stmt = $this->mysql->prepare($kegiatanQuery);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $kegiatanResult = $stmt->get_result();

        if ($kegiatanResult && $kegiatanResult->num_rows > 0) {
            $row = $kegiatanResult->fetch_assoc();
            $stats['total_kegiatan'] = $row['total'];
        }

        // Hitung status kwitansi
        $kwitansiQuery = "SELECT status, COUNT(*) as count
                         FROM kwitansi
                         WHERE pegawai_id = ?
                         GROUP BY status";

        $stmt = $this->mysql->prepare($kwitansiQuery);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                switch ($row['status']) {
                    case 'draft':
                        $stats['draft_count'] = $row['count'];
                        $stats['active_kwitansi_count'] += $row['count'];
                        break;
                    case 'verifikasi':
                        $stats['verifikasi_count'] = $row['count'];
                        $stats['active_kwitansi_count'] += $row['count'];
                        break;
                    case 'disetujui':
                        $stats['disetujui_count'] = $row['count'];
                        break;
                    case 'ditolak':
                        $stats['ditolak_count'] = $row['count'];
                        break;
                }
            }
        }

        return $stats;
    }

    /**
     * Get riwayat kwitansi (disetujui/ditolak) - PERBAIKAN: ambil jenis dari kegiatan
     */
    private function getKwitansiHistory($pegawai_id)
    {
        $query = "SELECT k.Id, k.no_kwitansi, k.status,
                         kg.jenis_kwitansi,  
                         kg.nama_kegiatan, r.kode_rekening, b.nama as nama_belanja,
                         k.total_uang, k.tgl_kwitansi, k.created_at
                  FROM kwitansi k
                  LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                  LEFT JOIN rekening r ON kg.rekening_id = r.id
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  WHERE k.pegawai_id = ?
                  AND k.status IN ('disetujui', 'ditolak')
                  ORDER BY k.created_at DESC
                  LIMIT 5";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $history = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
        }
        return $history;
    }

    /**
     * Hitung jumlah kwitansi aktif
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
     * Generate nomor kwitansi otomatis
     */
    private function generateNoKwitansi()
    {
        $prefix = 'KWT';
        $timestamp = time();
        $random = rand(1000, 9999);
        return $prefix . $timestamp . $random;
    }

    /**
     * Get template data
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
     * Main dashboard method - SISTEM REVISI
     */
    public function index()
    {
        if(isset($_POST['a'])){
            $pil = $_POST['a'];
            switch ($pil) {
                case 'get_kegiatan':
                    $this->getKegiatanData();
                    break;
                case 'create_kwitansi':
                    $this->createKwitansi();
                    break;
                default:
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Invalid action'
                    ]);
                    exit;
            }
        }
        else{
            $pegawai_id = $_SESSION['user_id'];


            if ($this->hasActiveKwitansi($pegawai_id)) {
                $this->view->pindah('user/kwitansi');
                exit;
            }

            // Get data untuk dashboard
            $statistics = $this->getDashboardStatistics($pegawai_id);
            $assignedKegiatan = $this->getAssignedKegiatan($pegawai_id);
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id); // Akan return null karena sudah di-cek
            $kwitansiHistory = $this->getKwitansiHistory($pegawai_id);

            $templateData = $this->getTemplateData($pegawai_id);

            $contentData = [
                'statistics' => $statistics,
                'assigned_kegiatan' => $assignedKegiatan,
                'active_kwitansi' => $activeKwitansi,
                'kwitansi_history' => $kwitansiHistory,
                'user_name' => $_SESSION['nama_pegawai'] ?? 'PPTK'
            ];

            $this->view->template(['user'=>$templateData]);
            $this->view->setDir('user');
            $this->view->templateshow(['dashboard' => $contentData]);
        }
    }

    /**
     * Get kegiatan data via AJAX
     */
    public function getKegiatanData()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $assignedKegiatan = $this->getAssignedKegiatan($pegawai_id);

            echo json_encode([
                'status' => 'success',
                'data' => $assignedKegiatan
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch kegiatan: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Create new kwitansi dari kegiatan yang dipilih - SISTEM REVISI
     */
    public function createKwitansi()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $kegiatan_id = intval($_POST['kegiatan_id'] ?? 0);

            if ($kegiatan_id <= 0) {
                throw new Exception('Invalid kegiatan ID');
            }


            if ($this->hasActiveKwitansi($pegawai_id)) {
                throw new Exception('Anda masih memiliki kwitansi yang belum selesai. Selesaikan atau batalkan kwitansi tersebut sebelum membuat yang baru.');
            }


            $kegiatanQuery = "SELECT k.id, k.nama_kegiatan, k.jenis_kwitansi, k.jenis_ttd,
                                     r.id as rekening_id
                              FROM kegiatan k
                              LEFT JOIN rekening r ON k.rekening_id = r.id
                              WHERE k.id = ? AND k.pptk_id = ?";

            $stmt = $this->mysql->prepare($kegiatanQuery);
            $stmt->bind_param("ii", $kegiatan_id, $pegawai_id);
            $stmt->execute();
            $kegiatanResult = $stmt->get_result();

            if (!$kegiatanResult || $kegiatanResult->num_rows === 0) {
                throw new Exception('Kegiatan tidak ditemukan atau akses ditolak');
            }

            $kegiatan = $kegiatanResult->fetch_assoc();


            $kwitansiData = [
                'no_kwitansi.s' => $this->generateNoKwitansi(),
                'pegawai_id.i' => $pegawai_id,
                'kegiatan_id.i' => $kegiatan_id,
                'status.s' => 'draft',
                'total_uang.d' => 0.00,
                'tgl_kwitansi.s' => date('Y-m-d'),
                'created_at.s' => date('Y-m-d H:i:s')
            ];

            // Simpan ke database
            $this->mysql->simpan($kwitansiData, 'kwitansi');

            echo json_encode([
                'status' => 'success',
                'message' => 'Kwitansi berhasil dibuat',
                'redirect_url' => BASEURL . 'user/kwitansi'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}