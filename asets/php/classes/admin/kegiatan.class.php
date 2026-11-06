<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Kegiatan extends _Controller
{
    public function __construct()
    {
        parent::__construct();
        $con = $this->database->mysqli;
        $this->mysql->setCon($con);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // HANYA generate token jika benar-benar belum ada
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("CSRF Token Generated: " . $_SESSION['csrf_token']);
        } else {
            error_log("CSRF Token Already Exists: " . $_SESSION['csrf_token']);
        }
    }

    //***************************FUNCTION FOR HELPER**********************//
    private function validateCsrf($token) 
    {
        return !empty($_SESSION['csrf_token']) &&
               !empty($token) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    // Helper function untuk mendapatkan field yang bisa dicari
    private function getSearchableFields()
    {
        return ['nama_kegiatan', 'kode_rekening'];
    }

    /**
     * Get columns for display (join dengan rekening)
     */
     private function getColumns()
    {
        return ['k.id', 'k.nama_kegiatan', 'r.kode_rekening', 'b.nama as nama_belanja', 'r.id as rekening_id'];
    }

    /**
     * Check if nama_kegiatan already exists
     */
    private function checkDuplicateKegiatan($namaKegiatan, $excludeId = 0)
    {
        $query = "SELECT id, nama_kegiatan
          FROM kegiatan
          WHERE nama_kegiatan = '" . $this->mysql->sqlEscape($namaKegiatan) . "'";

        if ($excludeId > 0) {
            $query .= " AND id != " . intval($excludeId);
        }

        $result = $this->mysql->query($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get all rekening for dropdown
     */
    private function getAllRekeningWithBelanja()
    {
        $query = "SELECT r.id, r.kode_rekening, b.nama as nama_belanja
                  FROM rekening r
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  ORDER BY r.kode_rekening ASC";

        $result = $this->mysql->query($query);
        $rekenings = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rekenings[] = $row;
            }
        }

        return $rekenings;
    }

    /**
     * Validate and prepare data for kegiatan
     */
    private function validateAndPrepareData()
    {
        $data = [];
        $errors = [];

        // Validate required fields
        if (empty($_POST['nama_kegiatan'])) {
            $errors['nama_kegiatan'] = 'Nama Kegiatan dibutuhkan';
        }
        if (empty($_POST['rekening_id'])) {
            $errors['rekening_id'] = 'Rekening dibutuhkan';
        }

        // Check for duplicate nama_kegiatan
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $namaKegiatan = trim($_POST['nama_kegiatan']);

        if (!empty($namaKegiatan)) {
            $duplicate = $this->checkDuplicateKegiatan($namaKegiatan, $id);
            if ($duplicate) {
                $errors['nama_kegiatan'] = 'Nama kegiatan sudah ada';
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

        // Prepare data
        $data = [
            'nama_kegiatan.s' => $this->mysql->sqlEscape($namaKegiatan),
            'rekening_id.i' => intval($_POST['rekening_id'])
        ];

        return $data;
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

    //_______________________________________________________\\

    /**
     * Show Kegiatan page inside admin template
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
                echo "Access ditolak anda bukan Admin!";
                exit;
            }

            $templateData = [
                'countPendingVerifikasi' => $this->hitungPendingManual()
            ];

            // Get rekening data for dropdown
            $rekenings = $this->getAllRekeningWithBelanja();

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'rekenings' => $rekenings
            ];

            $this->view->template(['admin'=> $templateData]);
            $this->view->setDir('admin');
            $this->view->templateShow(['kegiatan'=> $contentData]);
        }
    }

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
            $where = '';

            if (!empty($search)) {
                $searchableFields = ['k.nama_kegiatan', 'r.kode_rekening', 'b.nama'];

                if (!empty($searchField) && in_array($searchField, $searchableFields)) {
                    // Search in specific field
                    $where = "WHERE $searchField LIKE '%$search%'";
                } else {
                    // Search in all searchable fields
                    $whereConditions = [];
                    foreach ($searchableFields as $field) {
                        $whereConditions[] = "$field LIKE '%$search%'";
                    }
                    $where = "WHERE " . implode(' OR ', $whereConditions);
                }
            }

            // Get total count
            $countQuery = "SELECT COUNT(*) as total
                          FROM kegiatan k
                          LEFT JOIN rekening r ON k.rekening_id = r.id
                          LEFT JOIN belanja b ON r.id = b.rekening_id
                          $where";
            $countResult = $this->mysql->query($countQuery);
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $perPage);

            // Get data with pagination (join dengan rekening + belanja)
            $mainQuery = "SELECT k.id, k.nama_kegiatan, r.kode_rekening,
                         COALESCE(b.nama, '-') as nama_belanja,
                         r.id as rekening_id
                         FROM kegiatan k
                         LEFT JOIN rekening r ON k.rekening_id = r.id
                         LEFT JOIN belanja b ON r.id = b.rekening_id
                         $where
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
            error_log("Kegiatan data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Save or update kegiatan
     */
    public function simpan()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak diizinkan masuk');
            }

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $table = 'kegiatan';

            // Validate and prepare data
            $data = $this->validateAndPrepareData();

            if ($id > 0) {
                // Update existing record
                $this->mysql->ubah($id, $data, $table);
                $message = 'Data kegiatan terupdate dengan suksess';
            } else {
                // Insert new record
                $this->mysql->simpan($data, $table);
                $message = 'Data kegiatan tersimpan dengan suksess';
            }

            echo json_encode([
                'status' => 'success',
                'message' => $message
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
     * Get single kegiatan for editing
     */
    public function get()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak diizinkan masuk');
            }

            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('ID tidak ditemukan');
            }

            // Get data from join table
            $query = "SELECT k.id, k.nama_kegiatan, k.rekening_id,
                     r.kode_rekening, b.nama as nama_belanja
                     FROM kegiatan k
                     LEFT JOIN rekening r ON k.rekening_id = r.id
                     LEFT JOIN belanja b ON r.id = b.rekening_id
                     WHERE k.id = $id";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode([
                    'status' => 'success',
                    'data' => $row
                ]);
            } else {
                throw new Exception('Data tidak ditemukan');
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
     * Delete a kegiatan by ID
     */
    public function hapus()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak diizinkan masuk');
            }

            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('ID tidak ditemukan');
            }

            $table = 'kegiatan';
            $cek = $this->mysql->ambilSatuId($_POST['id'], $table);

            if($cek->num_rows == 1) {
                $this->mysql->hapus($_POST['id'], $table);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Suksess menghapus data kegiatan'
                ]);
            } else {
                throw new Exception('Data tidak ditemukan');
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