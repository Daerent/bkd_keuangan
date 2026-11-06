<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Syarat extends _Controller
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
        return ['nama_syarat'];
    }

    /**
     * Get columns for syarat_ver
     */
    private function getColumns()
    {
        return ['id', 'nama_syarat', 'urutan'];
    }

    /**
     * Check if nama_syarat already exists
     */
    private function checkDuplicateSyarat($namaSyarat, $excludeId = 0)
    {
        // Prepare where conditions
        $where = ['nama_syarat' => '='];
        $whereval = ['s.' . $namaSyarat];

        // Add exclude condition if needed
        if ($excludeId > 0) {
            $where['id'] = '!=';
            $whereval[] = 'i.' . $excludeId;
        }

        // Get specific columns using ambilBeberapa
        $result = $this->mysql->ambilBeberapa(
            ['id', 'nama_syarat'],
            $where,
            $whereval,
            null,
            'syarat_ver'
        );

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get next available urutan
     */
    private function getNextUrutan()
    {
        $query = "SELECT MAX(urutan) as max_urutan FROM syarat_ver";
        $result = $this->mysql->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($row['max_urutan'] ?? 0) + 1;
        }
        return 1;
    }

    /**
     * Validate and prepare data for syarat_ver
     */
    private function validateAndPrepareData()
    {
        $data = [];
        $errors = [];

        // Validate required fields
        if (empty($_POST['nama_syarat'])) {
            $errors['nama_syarat'] = 'Nama Syarat dibutuhkan';
        }

        // Check for duplicate nama_syarat
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $namaSyarat = trim($_POST['nama_syarat']);

        if (!empty($namaSyarat)) {
            $duplicate = $this->checkDuplicateSyarat($namaSyarat, $id);
            if ($duplicate) {
                $errors['nama_syarat'] = 'Nama syarat sudah ada';
            }
        }

        // Validate urutan (optional)
        $urutan = isset($_POST['urutan']) ? intval($_POST['urutan']) : 0;
        if ($urutan < 0) {
            $errors['urutan'] = 'Urutan tidak boleh negatif';
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
            'nama_syarat.s' => $this->mysql->sqlEscape($namaSyarat)
        ];

        // Handle urutan - jika tidak diisi, auto generate
        if ($urutan > 0) {
            $data['urutan.i'] = $urutan;
        } else if ($id === 0) {
            // Hanya generate otomatis untuk data baru
            $data['urutan.i'] = $this->getNextUrutan();
        }

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
     * Show SyaratVer page inside admin template
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
                echo "Access denied";
                exit;
            }

            $templateData = [
                'countPendingVerifikasi' => $this->hitungPendingManual()
            ];

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
            ];

            $this->view->template(['admin'=> $templateData]);
            $this->view->setDir('admin');
            $this->view->templateShow(['syarat_ver'=> $contentData]);
        }
    }

    public function data()
    {
        try {
            $table = 'syarat_ver';
            $columnsArray = $this->getColumns();
            $columns = is_array($columnsArray) ? implode(', ', $columnsArray) : $columnsArray;

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
                $searchableFields = $this->getSearchableFields();

                if (!empty($searchField) && in_array($searchField, $searchableFields)) {
                    // Search in specific field - directly insert into query
                    $where = "WHERE $searchField LIKE '%$search%'";
                } else {
                    // Search in all searchable fields - directly insert into query
                    $whereConditions = [];
                    foreach ($searchableFields as $field) {
                        $whereConditions[] = "$field LIKE '%$search%'";
                    }
                    $where = "WHERE " . implode(' OR ', $whereConditions);
                }
            }

            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM $table $where";
            $countResult = $this->mysql->query($countQuery);
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $perPage);

            // Get data with pagination
            $mainQuery = "SELECT $columns FROM $table $where
                         ORDER BY urutan ASC, nama_syarat ASC
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
            error_log("SyaratVer data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Save or update syarat_ver
     */
    public function simpan()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $table = 'syarat_ver';

            // Validate and prepare data
            $data = $this->validateAndPrepareData();

            if ($id > 0) {
                // Update existing record
                $this->mysql->ubah($id, $data, $table);
                $message = 'Data syarat verifikasi update dengan suksess';
            } else {
                // Insert new record
                $this->mysql->simpan($data, $table);
                $message = 'Data syarat verifikasi disimpan dengan suksess';
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
     * Get single syarat_ver for editing
     */
    public function get()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak memiliki autoritas');
            }

            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('ID tidak ada');
            }

            $table = 'syarat_ver';
            $result = $this->mysql->ambilSatuId($id, $table);

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
     * Delete a syarat_ver by ID
     */
    public function hapus()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak memiliki autoritas');
            }

            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('ID tidak ada');
            }

            // Check if syarat is used in list_doc
            $checkUsageQuery = "SELECT COUNT(*) as used_count
                               FROM list_doc
                               WHERE syarat_id = $id";
            $usageResult = $this->mysql->query($checkUsageQuery);

            if ($usageResult && $usageResult->num_rows > 0) {
                $usageCount = $usageResult->fetch_assoc()['used_count'];
                if ($usageCount > 0) {
                    throw new Exception('Tidak dapat menghapus syarat karena sudah digunakan dalam ' . $usageCount . ' rekening');
                }
            }

            $table = 'syarat_ver';
            $cek = $this->mysql->ambilSatuId($id, $table);

            if($cek->num_rows == 1) {
                $this->mysql->hapus($id, $table);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data syarat verifikasi deleted successfully'
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
     * Get all syarat for dropdown (used in ListDoc controller)
     */
    public function getAllSyarat()
    {
        try {

            $result = $this->mysql->ambilSemuaSort('urutan', 'ASC', 'syarat_ver');
            $syarats = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $syarats[] = $row;
                }
            }

            echo json_encode([
                'status' => 'success',
                'data' => $syarats
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