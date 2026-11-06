<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Relasi_kegiatan extends _Controller
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

    //***************************FUNCTION FOR HELPER**********************//
    private function validateCsrf($token)
    {
        return !empty($_SESSION['csrf_token']) &&
               !empty($token) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Get all PPTK for dropdown (users with role 'user')
     */
    private function getAllPPTK()
    {
        $query = "SELECT id, nama_pegawai, nip
                  FROM pegawai
                  WHERE role = 'user' AND is_active = 1
                  ORDER BY nama_pegawai ASC";

        $result = $this->mysql->query($query);
        $pptks = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pptks[] = $row;
            }
        }

        return $pptks;
    }

    /**
     * Get all kegiatan with rekening info for dropdown
     */
    private function getAllKegiatanWithRekening()
    {
        $query = "SELECT k.id, k.nama_kegiatan, r.kode_rekening, b.nama as nama_belanja,
                         k.pptk_id, k.jenis_kwitansi, k.jenis_ttd
                  FROM kegiatan k
                  LEFT JOIN rekening r ON k.rekening_id = r.id
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  ORDER BY k.nama_kegiatan ASC";

        $result = $this->mysql->query($query);
        $kegiatans = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $kegiatans[] = $row;
            }
        }


        return $kegiatans;
    }

    /**
     * Validate and prepare data for relasi PPTK-Kegiatan
     */
    private function validateAndPrepareData()
    {
        $data = [];
        $errors = [];

        // Validate required fields
        if (empty($_POST['kegiatan_id'])) {
            $errors['kegiatan_id'] = 'Kegiatan is required';
        }
        if (empty($_POST['pptk_id'])) {
            $errors['pptk_id'] = 'PPTK is required';
        }
        if (empty($_POST['jenis_kwitansi'])) {
            $errors['jenis_kwitansi'] = 'Jenis Kwitansi is required';
        }

        $kegiatanId = intval($_POST['kegiatan_id']);
        $pptkId = intval($_POST['pptk_id']);

        // Check if rekening has list_doc requirements
        if ($kegiatanId > 0) {
            $rekeningId = $this->getRekeningIdFromKegiatan($kegiatanId);
            if ($rekeningId && !$this->checkRekeningHasListDoc($rekeningId)) {
                $errors['kegiatan_id'] = 'Rekening untuk kegiatan ini belum memiliki persyaratan dokumen. Silahkan tambahkan persyaratan terlebih dahulu di rekening ini.';
            }
        }

        // If there are validation errors, throw exception
        if (!empty($errors)) {
            $errorResponse = [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ];
            echo json_encode($errorResponse);
            exit;
        }

        // Prepare data for update kegiatan
        $data = [
            'pptk_id.i' => $pptkId,
            'jenis_kwitansi.s' => $this->mysql->sqlEscape($_POST['jenis_kwitansi']),
            'jenis_ttd.s' => $this->mysql->sqlEscape($_POST['jenis_ttd'] ?? '3_ttd')
        ];

        return $data;
    }

    /**
     * Check if rekening has list_doc requirements
     */
    private function checkRekeningHasListDoc($rekeningId)
    {
        $query = "SELECT COUNT(*) as total
                  FROM list_doc
                  WHERE rekening_id = " . intval($rekeningId);

        $result = $this->mysql->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] > 0;
        }
        return false;
    }

    /**
     * Get rekening_id from kegiatan
     */
    private function getRekeningIdFromKegiatan($kegiatanId)
    {
        $result = $this->mysql->ambilSatuId($kegiatanId, 'kegiatan', 'i');
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['rekening_id'];
        }
        return null;
    }

    // /**
    //  * Check if PPTK has pending kwitansi for any kegiatan
    //  */
    // private function hasPPTKPendingKwitansi($pptkId)
    // {
    //     $query = "SELECT COUNT(*) as total
    //               FROM kwitansi
    //               WHERE pegawai_id = $pptkId
    //               AND status IN ('draft', 'verifikasi')";

    //     $result = $this->mysql->query($query);
    //     if ($result && $result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    //         return $row['total'] > 0;
    //     }
    //     return false;
    // }

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

    //_______________________________________________________\\

    /**
     * Show Relasi Kegiatan page inside admin template
     */
    public function index()
    {
        if(isset($_POST['a'])){
            // Validate CSRF for POST requests
            if (!empty($_POST['a']) && in_array($_POST['a'], ['simpan', 'hapus']) &&
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
                case 'simpan':
                    $this->simpan();
                    break;
                case 'get':
                    $this->get();
                    break;
                case 'hapus':
                    $this->hapus();
                    break;
                case 'get_kegiatan_options':
                    $this->get_kegiatan_options();
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
            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                header('HTTP/1.1 403 Forbidden');
                echo "Access denied";
                exit;
            }

            $templateData = [
                'countPendingVerifikasi' => $this->hitungPendingManual()
            ];

            // Get data for dropdowns
            $pptks = $this->getAllPPTK();
            $kegiatans = $this->getAllKegiatanWithRekening();

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'pptks' => $pptks,
                'kegiatans' => $kegiatans
            ];

            $this->view->template(['admin'=> $templateData]);
            $this->view->setDir('admin');
            $this->view->templateShow(['relasi_kegiatan'=> $contentData]);
        }
    }

    /**
     * Get all relasi kegiatan data
     */
    public function data()
    {
        try {
            // Get pagination parameters
            $page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
            $perPage = isset($_POST['per_page']) ? max(1, intval($_POST['per_page'])) : 10;
            $offset = ($page - 1) * $perPage;

            // Get search parameters
            $search = isset($_POST['search']) ? $this->mysql->sqlEscape(trim($_POST['search'])) : '';
            $searchField = isset($_POST['search_field']) ? $this->mysql->sqlEscape(trim($_POST['search_field'])) : '';

            // Build WHERE clause for search
            $where = 'WHERE 1=1';

            if (!empty($search)) {
                $searchableFields = ['p.nama_pegawai', 'k.nama_kegiatan', 'k.jenis_kwitansi', 'k.jenis_ttd'];

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

            // Get total count
            $countQuery = "SELECT COUNT(*) as total
                          FROM kegiatan k
                          LEFT JOIN pegawai p ON k.pptk_id = p.id
                          LEFT JOIN rekening r ON k.rekening_id = r.id
                          LEFT JOIN belanja b ON r.id = b.rekening_id
                          $where AND k.pptk_id IS NOT NULL";

            $countResult = $this->mysql->query($countQuery);
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $perPage);

            // Get data with pagination
            $mainQuery = "SELECT k.id as kegiatan_id, k.nama_kegiatan,
                         p.nama_pegawai, p.id as pptk_id,
                         r.kode_rekening, b.nama as nama_belanja,
                         k.jenis_kwitansi, k.jenis_ttd, k.pptk_id
                         FROM kegiatan k
                         LEFT JOIN pegawai p ON k.pptk_id = p.id
                         LEFT JOIN rekening r ON k.rekening_id = r.id
                         LEFT JOIN belanja b ON r.id = b.rekening_id
                         $where AND k.pptk_id IS NOT NULL
                         ORDER BY k.nama_kegiatan ASC
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
            error_log("RelasiKegiatan data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Save or update relasi PPTK-Kegiatan
     */
    public function simpan()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $kegiatanId = intval($_POST['kegiatan_id'] ?? 0);

            if ($kegiatanId <= 0) {
                throw new Exception('Invalid Kegiatan ID');
            }

            // Validate and prepare data
            $data = $this->validateAndPrepareData();



            // Update kegiatan record
            $success = $this->mysql->ubah($kegiatanId, $data, 'kegiatan');

            if ($success) {
                $response = [
                    'status' => 'success',
                    'message' => 'Relasi PPTK-Kegiatan berhasil disimpan'
                ];
            } else {
                throw new Exception('Gagal menyimpan relasi');
            }

            echo json_encode($response);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Get single relasi for editing
     */
    public function get()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $kegiatanId = intval($_POST['id'] ?? 0);

            if ($kegiatanId <= 0) {
                throw new Exception('Invalid ID');
            }

            // Get data from kegiatan table
            $query = "SELECT k.id as kegiatan_id, k.nama_kegiatan, k.pptk_id,
                     k.jenis_kwitansi, k.jenis_ttd,
                     p.nama_pegawai, r.kode_rekening, b.nama as nama_belanja
                     FROM kegiatan k
                     LEFT JOIN pegawai p ON k.pptk_id = p.id
                     LEFT JOIN rekening r ON k.rekening_id = r.id
                     LEFT JOIN belanja b ON r.id = b.rekening_id
                     WHERE k.id = $kegiatanId";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode([
                    'status' => 'success',
                    'data' => $row
                ]);
            } else {
                throw new Exception('Data not found');
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
     * Get updated kegiatan options for dropdown (after save/delete)
     */
    public function get_kegiatan_options()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            // Get all kegiatan with updated data
            $kegiatans = $this->getAllKegiatanWithRekening();

            echo json_encode([
                'status' => 'success',
                'data' => $kegiatans
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
     * Remove relasi (set pptk_id to NULL)
     */
    public function hapus()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $kegiatanId = intval($_POST['id'] ?? 0);

            if ($kegiatanId <= 0) {
                throw new Exception('Invalid ID');
            }

            // Get data sebelum hapus untuk info
            $query = "SELECT k.nama_kegiatan, p.nama_pegawai
                      FROM kegiatan k
                      LEFT JOIN pegawai p ON k.pptk_id = p.id
                      WHERE k.id = $kegiatanId";

            $result = $this->mysql->query($query);
            if (!$result || $result->num_rows === 0) {
                throw new Exception('Data not found');
            }

            $data = $result->fetch_assoc();
            $kegiatanName = $data['nama_kegiatan'];
            $pptkName = $data['nama_pegawai'];

            // Remove relasi dengan set pptk_id to NULL
            $updateData = [
                'pptk_id.s' => null,
                'jenis_kwitansi.s' => null,
                'jenis_ttd.s' => null
            ];

            $success = $this->mysql->ubah($kegiatanId, $updateData, 'kegiatan');

            if ($success) {
                echo json_encode([
                    'status' => 'success',
                    'message' => "Relasi PPTK <strong>{$pptkName}</strong> dengan Kegiatan <strong>{$kegiatanName}</strong> berhasil dihapus",
                    'deleted_data' => [
                        'pptk_name' => $pptkName,
                        'kegiatan_name' => $kegiatanName
                    ]
                ]);
            } else {
                throw new Exception('Gagal menghapus relasi');

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