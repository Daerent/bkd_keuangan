<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Listdoc extends _Controller
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

    /**
     * Get all rekening for dropdown
     */
    private function getAllRekening()
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
     * Get all syarat for dropdown
     */
    private function getAllSyarat()
    {
        $query = "SELECT id, nama_syarat, urutan
                 FROM syarat_ver
                 ORDER BY urutan ASC, nama_syarat ASC";

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
     * Check if combination already exists
     */
    private function checkDuplicateListDoc($rekeningId, $syaratId, $excludeId = 0)
    {
        $query = "SELECT id, rekening_id, syarat_id
                  FROM list_doc
                  WHERE rekening_id = " . intval($rekeningId) . "
                  AND syarat_id = " . intval($syaratId);

        if ($excludeId > 0) {
            $query .= " AND id != " . $excludeId;
        }

        $result = $this->mysql->query($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Check if syarat already exists by name
     */
    private function checkExistingSyarat($namaSyarat)
    {
        $query = "SELECT id FROM syarat_ver WHERE nama_syarat = '" . $this->mysql->sqlEscape($namaSyarat) . "'";
        $result = $this->mysql->query($query);
        return $result && $result->num_rows > 0;
    }

    /**
     * Validate and prepare data for list_doc dengan syarat baru
     */
    private function validateAndPrepareData()
    {
        $errors = [];

        // Validate required fields
        if (empty($_POST['rekening_id'])) {
            $errors['rekening_id'] = 'Rekening is required';
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $rekeningId = intval($_POST['rekening_id']);

        // Handle syarat mode
        $syaratMode = $_POST['syarat_mode'] ?? 'existing';
        $syaratId = null;

        if ($syaratMode === 'existing') {
            // Mode existing - gunakan syarat_id
            if (empty($_POST['syarat_id'])) {
                $errors['syarat_id'] = 'Syarat verifikasi is required';
            } else {
                $syaratId = intval($_POST['syarat_id']);
            }
        } else {
            // Mode new - buat syarat baru
            if (empty($_POST['new_syarat_urutan']) || empty($_POST['new_syarat_nama'])) {
                $errors['syarat_id'] = 'Urutan dan nama syarat baru harus diisi';
            } else {
                // Simpan syarat baru
                $syaratData = [
                    'nama_syarat.s' => $this->mysql->sqlEscape(trim($_POST['new_syarat_nama'])),
                    'urutan.i' => intval($_POST['new_syarat_urutan'])
                ];

                // Cek duplikat nama syarat
                $existingSyarat = $this->checkExistingSyarat(trim($_POST['new_syarat_nama']));
                if ($existingSyarat) {
                    $errors['syarat_id'] = 'Nama syarat sudah ada dalam database';
                } else {
                    $this->mysql->simpan($syaratData, 'syarat_ver');
                    $syaratId = $this->mysql::$lastId;
                }
            }
        }

        // Check for duplicate combination hanya jika syaratId sudah ada
        if ($rekeningId > 0 && $syaratId > 0) {
            $duplicate = $this->checkDuplicateListDoc($rekeningId, $syaratId, $id);
            if ($duplicate) {
                $errors['rekening_id'] = 'Kombinasi rekening dan syarat sudah ada';
                $errors['syarat_id'] = 'Data sudah terdaftar';
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
            'rekening_id.i' => $rekeningId,
            'syarat_id.i' => $syaratId
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
     * Show ListDoc page inside admin template
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
                case 'get_rekening_syarat':
                    $this->getRekeningSyarat();
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

            // Get data for dropdowns
            $rekenings = $this->getAllRekening();
            $syarats = $this->getAllSyarat();

            $templateData = [
                'countPendingVerifikasi' => $this->hitungPendingManual()
            ];

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'rekenings' => $rekenings,
                'syarats' => $syarats,
            ];

            $this->view->template(['admin'=> $templateData]);
            $this->view->setDir('admin');
            $this->view->templateShow(['listdoc'=> $contentData]);
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

            // Build WHERE clause for search dengan JOIN
            $where = '';

            if (!empty($search)) {
                $searchableFields = ['r.kode_rekening', 'b.nama', 's.nama_syarat'];

                if (!empty($searchField) && in_array($searchField, $searchableFields)) {
                    $where = "WHERE $searchField LIKE '%$search%'";
                } else {
                    $whereConditions = [];
                    foreach ($searchableFields as $field) {
                        $whereConditions[] = "$field LIKE '%$search%'";
                    }
                    $where = "WHERE " . implode(' OR ', $whereConditions);
                }
            }

            // Get total count
            $countQuery = "SELECT COUNT(*) as total
                          FROM list_doc ld
                          JOIN rekening r ON ld.rekening_id = r.id
                          JOIN belanja b ON r.id = b.rekening_id
                          JOIN syarat_ver s ON ld.syarat_id = s.id
                          $where";

            $countResult = $this->mysql->query($countQuery);
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $perPage);

            // Get data with pagination (join semua tabel)
            $mainQuery = "SELECT ld.id,
                                 r.id as rekening_id, r.kode_rekening,
                                 b.nama as nama_belanja,
                                 s.id as syarat_id, s.nama_syarat, s.urutan
                         FROM list_doc ld
                         JOIN rekening r ON ld.rekening_id = r.id
                         JOIN belanja b ON r.id = b.rekening_id
                         JOIN syarat_ver s ON ld.syarat_id = s.id
                         $where
                         ORDER BY r.kode_rekening ASC, s.urutan ASC, s.nama_syarat ASC
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
            error_log("List_doc data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Save or update list_doc
     */
    public function simpan()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id < 0) {
                throw new Exception('Invalid ID');
            }
            $table = 'list_doc';

            // Validate and prepare data
            $data = $this->validateAndPrepareData();

            if ($id > 0) {
                // Update existing record
                $this->mysql->ubah($id, $data, $table);
                $message = 'Data relasi rekening-syarat updated successfully';
            } else {
                // Insert new record
                $this->mysql->simpan($data, $table);
                $message = 'Data relasi rekening-syarat saved successfully';
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
     * Get single list_doc for editing
     */
    public function get()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('Invalid ID');
            }

            // Get data dengan join
            $query = "SELECT ld.id, ld.rekening_id, ld.syarat_id,
                             r.kode_rekening, b.nama as nama_belanja,
                             s.nama_syarat, s.urutan
                     FROM list_doc ld
                     JOIN rekening r ON ld.rekening_id = r.id
                     JOIN belanja b ON r.id = b.rekening_id
                     JOIN syarat_ver s ON ld.syarat_id = s.id
                     WHERE ld.id = $id";

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
     * Delete a list_doc by ID
     */
    public function hapus()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('Invalid ID');
            }

            $table = 'list_doc';
            $cek = $this->mysql->ambilSatuId($_POST['id'], $table);

            if($cek->num_rows == 1) {
                $this->mysql->hapus($_POST['id'], $table);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data relasi rekening-syarat deleted successfully'
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
     * Get rekening and syarat for dropdowns (AJAX)
     */
    public function getRekeningSyarat()
    {
        try {
            $rekenings = $this->getAllRekening();
            $syarats = $this->getAllSyarat();

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'rekenings' => $rekenings,
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
}