<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Verifikasi extends _Controller
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
    }

    private function validateCsrf($token)
    {
        return !empty($_SESSION['csrf_token']) &&
               !empty($token) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    private function getSearchableFields()
    {
        return ['p.nama_pegawai', 'kg.nama_kegiatan', 'r.kode_rekening', 'b.nama'];
    }

    /**
     * Get counts for all status cards
     */
    private function getCardCounts()
    {
        $query = "SELECT
                    COUNT(CASE WHEN status = 'verifikasi' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN status = 'disetujui' THEN 1 END) as disetujui_count,
                    COUNT(CASE WHEN status = 'ditolak' THEN 1 END) as ditolak_count
                  FROM kwitansi";

        $result = $this->mysql->query($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return [
            'pending_count' => 0,
            'disetujui_count' => 0,
            'ditolak_count' => 0
        ];
    }

    /**
     * Get status counts for cards
     */
    private function getStatusCounts()
    {
        $counts = $this->getCardCounts();

        return [
            'disetujui_count' => $counts['disetujui_count'],
            'ditolak_count' => $counts['ditolak_count']
        ];
    }

    /**
     * Get detail kwitansi with all related data - PERBAIKAN: ambil jenis dari kegiatan
     */
    private function getKwitansiDetail($kwitansiId)
    {
        $query = "SELECT
                    k.*,
                    p.nama_pegawai as pptk,
                    p.nip as nip_pptk,
                    kg.nama_kegiatan,
                    kg.jenis_kwitansi,  
                    kg.jenis_ttd,       
                    kg.rekening_id,
                    r.kode_rekening,
                    b.nama as nama_belanja,
                    pny.nama_perusahaan,
                    pny.nama_penyedia,
                    pny.jabatan as jabatan_penyedia,
                    pj.nama as nama_pejabat,
                    pj.jabatan as jabatan_pejabat,
                    pj.nip as nip_pejabat,
                    pb.nama as nama_bendahara,
                    pb.jabatan as jabatan_bendahara,
                    pb.nip as nip_bendahara
                  FROM kwitansi k
                  LEFT JOIN pegawai p ON k.pegawai_id = p.id
                  LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                  LEFT JOIN rekening r ON kg.rekening_id = r.id
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  LEFT JOIN penyedia pny ON k.penyedia_id = pny.id
                  LEFT JOIN pejabat pj ON k.pejabat_id = pj.id
                  LEFT JOIN pejabat pb ON k.bendahara_id = pb.id
                  WHERE k.Id = " . intval($kwitansiId);

        $result = $this->mysql->query($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    /**
     * Get syarat list with upload status for a kwitansi
     */
    private function getSyaratWithStatus($kwitansiId)
    {
        $query = "SELECT
                    sv.id as syarat_id,
                    sv.nama_syarat,
                    sv.urutan,
                    ld.id as list_doc_id,
                    d.id as dokumen_id,
                    d.nama_dokumen,
                    d.file as dokumen_file,
                    CASE WHEN d.id IS NOT NULL THEN 1 ELSE 0 END as is_uploaded
                  FROM kwitansi k
                  JOIN kegiatan kg ON k.kegiatan_id = kg.id
                  JOIN rekening r ON kg.rekening_id = r.id
                  JOIN list_doc ld ON r.id = ld.rekening_id
                  JOIN syarat_ver sv ON ld.syarat_id = sv.id
                  LEFT JOIN dokumentasi d ON (d.kwitansi_id = k.Id AND d.syarat_id = sv.id)
                  WHERE k.Id = " . intval($kwitansiId) . "
                  ORDER BY sv.urutan ASC, sv.nama_syarat ASC";

        $result = $this->mysql->query($query);
        $syarats = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $syarats[] = $row;
            }
        }

        return $syarats;
    }

    /**
     * Update kwitansi status
     */
    private function updateStatus($id, $status, $alasan = null)
    {
        $data = [
            'status.s' => $status
        ];

        if ($alasan) {
            $data['keterangan.s'] = $this->mysql->sqlEscape($alasan);
        }

        return $this->mysql->ubah($id, $data, 'kwitansi');
    }

    private function hitungPendingManual()
    {
        $query = "SELECT COUNT(*) as total
                  FROM kwitansi
                  WHERE status = 'verifikasi'";

        $result = $this->mysql->query($query);
        if ($result && $row = $result->fetch_row()) {
            return $row[0];
        }
        return 0;
    }

    /**
     * Show Verifikasi page inside admin template
     */
    public function index()
    {
        if(isset($_POST['a'])){
            if (!empty($_POST['a']) && in_array($_POST['a'], ['approve', 'reject']) &&
                !$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid CSRF token'
                ]);
                exit;
            }

            $pil = $_POST['a'];
            switch ($pil) {
                case 'data':
                    $this->data();
                    break;
                case 'approve':
                    $this->approve();
                    break;
                case 'reject':
                    $this->reject();
                    break;
                case 'get_detail':
                    $this->getDetail();
                    break;
                case 'get_counts':
                    $this->getCounts();
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
            if ($_SESSION['user_role'] !== 'admin') {
                header('HTTP/1.1 403 Forbidden');
                echo "Access denied";
                exit;
            }

            $statusCounts = $this->getStatusCounts();

            $templateData = [
                'countPendingVerifikasi' => $this->hitungPendingManual()
            ];

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'disetujui_count' => $statusCounts['disetujui_count'],
                'ditolak_count' => $statusCounts['ditolak_count']
            ];

            $this->view->template(['admin'=> $templateData]);
            $this->view->setDir('admin');
            $this->view->templateShow(['verifikasi'=> $contentData]);
        }
    }

    /**
     * Get counts for AJAX card updates
     */
    public function getCounts()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak memiliki autoritas');
            }

            $counts = $this->getCardCounts();

            echo json_encode([
                'status' => 'success',
                'counts' => [
                    'pending' => $counts['pending_count'] ?? 0,
                    'disetujui' => $counts['disetujui_count'] ?? 0,
                    'ditolak' => $counts['ditolak_count'] ?? 0
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
                'counts' => [
                    'pending' => 0,
                    'disetujui' => 0,
                    'ditolak' => 0
                ]
            ]);
        }
        exit;
    }

    /**
     * Get all kwitansi with status 'verifikasi' for main table - PERBAIKAN: ambil jenis dari kegiatan
     */
    public function data()
    {
        try {
            $page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
            $perPage = isset($_POST['per_page']) ? max(1, intval($_POST['per_page'])) : 10;
            $offset = ($page - 1) * $perPage;

            $search = isset($_POST['search']) ? $this->mysql->sqlEscape(trim($_POST['search'])) : '';
            $searchField = isset($_POST['search_field']) ? $this->mysql->sqlEscape(trim($_POST['search_field'])) : '';

            $where = "WHERE k.status = 'verifikasi'";

            if (!empty($search)) {
                $searchableFields = $this->getSearchableFields();

                if (!empty($searchField) && in_array($searchField, $searchableFields)) {
                    $where .= " AND $searchField LIKE '%$search%'";
                } else {
                    $whereConditions = [];
                    foreach ($searchableFields as $field) {
                        $whereConditions[] = "$field LIKE '%$search%'";
                    }
                    $where .= " AND (" . implode(' OR ', $whereConditions) . ")";
                }
            }

            $countQuery = "SELECT COUNT(*) as total
                          FROM kwitansi k
                          LEFT JOIN pegawai p ON k.pegawai_id = p.id
                          LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                          LEFT JOIN rekening r ON kg.rekening_id = r.id
                          LEFT JOIN belanja b ON r.id = b.rekening_id
                          $where";
            $countResult = $this->mysql->query($countQuery);
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $perPage);

            $mainQuery = "SELECT k.Id as kwitansi_id, k.no_kwitansi, p.nama_pegawai as pptk,
                         kg.nama_kegiatan, r.kode_rekening, b.nama as nama_belanja,
                         kg.jenis_kwitansi, kg.jenis_ttd, k.total_uang, k.tgl_kwitansi, k.created_at
                         FROM kwitansi k
                         LEFT JOIN pegawai p ON k.pegawai_id = p.id
                         LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                         LEFT JOIN rekening r ON kg.rekening_id = r.id
                         LEFT JOIN belanja b ON r.id = b.rekening_id
                         $where
                         ORDER BY k.created_at DESC
                         LIMIT $perPage OFFSET $offset";

            $result = $this->mysql->query($mainQuery);
            $rows = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Data fetched successfully',
                'data' => $rows,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total_rows' => $totalRows,
                    'total_pages' => $totalPages
                ]
            ]);
        } catch (Exception $e) {
            error_log("Verifikasi data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Get detail for verifikasi page
     */
    public function getDetail()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $kwitansiId = intval($_POST['id'] ?? 0);

            if ($kwitansiId <= 0) {
                throw new Exception('Invalid ID');
            }

            $kwitansi = $this->getKwitansiDetail($kwitansiId);
            if (!$kwitansi) {
                throw new Exception('Data kwitansi tidak ditemukan');
            }

            $syarats = $this->getSyaratWithStatus($kwitansiId);

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'kwitansi' => $kwitansi,
                    'syarats' => $syarats
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Approve kwitansi - PERBAIKAN: parameter ID
     */
    public function approve()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $kwitansiId = intval($_POST['id'] ?? 0);
            $alasan = trim($_POST['alasan'] ?? ' ');

            if ($kwitansiId <= 0) {
                throw new Exception('Invalid ID');
            }

            $success = $this->updateStatus($kwitansiId, 'disetujui', $alasan);

            if ($success) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Kwitansi berhasil disetujui'
                ]);
            } else {
                throw new Exception('Gagal menyetujui kwitansi');
            }

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Reject kwitansi
     */
    public function reject()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $kwitansiId = intval($_POST['id'] ?? 0);
            $alasan = trim($_POST['alasan'] ?? '');

            if ($kwitansiId <= 0) {
                throw new Exception('Invalid ID');
            }

            if (empty($alasan)) {
                throw new Exception('Alasan penolakan harus diisi');
            }

            $success = $this->updateStatus($kwitansiId, 'ditolak', $alasan);

            if ($success) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Kwitansi berhasil ditolak'
                ]);
            } else {
                throw new Exception('Gagal menolak kwitansi');
            }

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}