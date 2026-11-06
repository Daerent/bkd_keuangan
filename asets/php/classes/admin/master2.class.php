<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
class Master2 extends _Controller
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
    private function getSearchableFields($tipe)
    {
        switch ($tipe) {
            case 'user':
                return ['nama_pegawai', 'nip', 'jabatan', 'role'];
            case 'penyedia':
                return ['nama_perusahaan', 'nama_penyedia', 'jabatan'];
            case 'pejabat':
                return ['nama', 'jabatan', 'nip'];
            default:
                return [];
        }
    }

    // Tambahkan method untuk mendapatkan opsi search field
    public function get_search_fields()
    {
        try {
            if (!isset($_POST['tipe'])) {
                throw new Exception('Type parameter is required');
            }

            $tipe = $_POST['tipe'];
            $searchableFields = $this->getSearchableFields($tipe);

            echo json_encode([
                'status' => 'success',
                'data' => $searchableFields
            ]);
        } catch (Exception $e) {
            error_log("Master2 get_search_fields error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    // Tambahkan method debug di controller
    public function debug_csrf()
    {
        echo "Session CSRF: " . ($_SESSION['csrf_token'] ?? 'NOT SET') . "<br>";
        echo "POST CSRF: " . ($_POST['csrf_token'] ?? 'NOT SENT') . "<br>";
        echo "Session ID: " . session_id() . "<br>";
            exit;
        }


    /**
     * Check if username already exists
     */
    private function usernameExists($username)
    {
        $result = $this->mysql->ambilkondisi(['username' => '='],["s.$username"],'and','pegawai');
        return $result && $result->num_rows > 0;
    }



    /**
     * Get columns based on type
     */
    private function getColumns($tipe)
    {
        switch ($tipe) {
            case 'user':
                return ['id', 'username', 'nama_pegawai', 'nip', 'jabatan', 'role', 'is_active'];
            case 'penyedia':
                return ['id', 'nama_perusahaan', 'nama_penyedia', 'jabatan'];
            case 'pejabat':
                return ['id', 'nama', 'jabatan', 'nip'];
            default:
                throw new Exception('Invalid type');
        }
    }
       /**
     * Helper function to get table name based on type
     */
    private function getTableName($tipe)
    {
        switch ($tipe) {
            case 'user':
                return 'pegawai';
            case 'penyedia':
                return 'penyedia';
            case 'pejabat':
                return 'pejabat';
            default:
                throw new Exception('Invalid type');
        }
    }


    /**
     * Validate and prepare data based on type
     */
    private function validateAndPrepareData($tipe)
    {
        $data = [];
        $errors = [];

        switch ($tipe) {
            case 'user':
                // Validate required fields
                if (empty($_POST['nama_pegawai'])) {
                    $errors['nama_pegawai'] = 'Nama is required';
                }
                if (empty($_POST['nip'])) {
                    $errors['nip'] = 'NIP is required';
                }
                if (empty($_POST['jabatan'])) {
                    $errors['jabatan'] = 'Jabatan is required';
                }
                if (empty($_POST['role']) || !in_array($_POST['role'], ['admin', 'user'])) {
                    $errors['role'] = 'Valid role is required';
                }
                // For new users, check if username generation is needed
                $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
                if ($id === 0) {



                    $data['password.s'] = password_hash('password123', PASSWORD_DEFAULT);
                    $data['is_active.s'] = 1; // Default to active
                } else {
                    // Existing user - only update password if provided
                    if (!empty($_POST['password'])) {
                        $data['password.s'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }

                    // Update is_active status
                    $data['is_active.s'] = isset($_POST['is_active']) ? 1 : 0;
                }

                // Prepare data
                $data['nama_pegawai.s'] = $this->mysql->sqlEscape(trim($_POST['nama_pegawai']));
                $data['nip.s'] = $this->mysql->sqlEscape(trim($_POST['nip']));
                $data['jabatan.s'] = $this->mysql->sqlEscape(trim($_POST['jabatan']));
                $data['role.s'] = $this->mysql->sqlEscape(trim($_POST['role']));
                break;

            case 'penyedia':
                // Validate required fields
                if (empty($_POST['nama_perusahaan'])) {
                    $errors['nama_perusahaan'] = 'Nama Perusahaan is required';
                }
                if (empty($_POST['nama_penyedia'])) {
                    $errors['nama_penyedia'] = 'Nama Penyedia is required';
                }
                if (empty($_POST['jabatan'])) {
                    $errors['jabatan'] = 'Jabatan is required';
                }

                // Prepare data
                $data['nama_perusahaan.s'] = $this->mysql->sqlEscape(trim($_POST['nama_perusahaan']));
                $data['nama_penyedia.s'] = $this->mysql->sqlEscape(trim($_POST['nama_penyedia']));
                $data['jabatan.s'] = $this->mysql->sqlEscape(trim($_POST['jabatan']));
                break;

            case 'pejabat':
                // Validate required fields
                if (empty($_POST['nama'])) {
                    $errors['nama'] = 'Nama is required';
                }
                if (empty($_POST['jabatan'])) {
                    $errors['jabatan'] = 'Jabatan is required';
                }

                // Prepare data (NIP is optional)
                $data['nama.s'] = $this->mysql->sqlEscape(trim($_POST['nama']));
                $data['jabatan.s'] = $this->mysql->sqlEscape(trim($_POST['jabatan']));
                $data['nip.s'] = $this->mysql->sqlEscape(trim($_POST['nip'] ?? ''));
                break;

            default:
                throw new Exception('Invalid type');
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

        return $data;
    }
    private function hitungPendingManual()
    {
        $query = "SELECT COUNT(*) as total
                  FROM kwitansi
                  WHERE status = 'verifikasi'
                  ";

        $result = $this->mysql->query($query);
        if ($result && $row = $result->fetch_row()) {
            return $row[0];
        }
        return 0;
    }
    //_______________________________________________________\\

    /**
     * Show Master User page inside admin template
     */
    public function index()
    {
        if(isset($_POST['a'])){
            // Validate CSRF for POST requests
            if (!empty($_POST['a']) && in_array($_POST['a'], ['simpan', 'hapus']) &&
                !$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                // $b = $this->debug_csrf();
                // echo $b;
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
            $this->view->templateShow(['master_user'=> $contentData]);
        }
    }




    public function data()
    {
        try {
            if (!isset($_POST['tipe'])) {
                throw new Exception('Tipe parameter dibutuhkan');
            }

            $tipe = $_POST['tipe'];
            $table = $this->getTableName($tipe);
            $columnsArray = $this->getColumns($tipe);
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
                $searchableFields = $this->getSearchableFields($tipe);

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
            $countResult = $this->mysql->query($countQuery); // No parameters array
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $perPage);

            // Get data with pagination
            $mainQuery = "SELECT $columns FROM $table $where
                         ORDER BY id DESC
                         LIMIT $perPage OFFSET $offset";

            $result = $this->mysql->query($mainQuery); // No parameters array
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
            error_log("Master2 data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Save or update data based on type
     */
    public function simpan()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak memiliki autoritas untuk masuk');
            }

            if (!isset($_POST['tipe'])) {
                throw new Exception('Tipa parameter dibutuhkan');
            }

            $tipe = $_POST['tipe'];
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $table = $this->getTableName($tipe);

            // Validate and prepare data based on type
            $data = $this->validateAndPrepareData($tipe);

            if ($id > 0) {
                // Update existing record
                $this->mysql->ubah($id, $data, $table);
                $message = 'Data updated suksess';
            } else {
                // Insert new record
                $this->mysql->simpan($data, $table);
                $message = 'Data disimpan dengan suksess';
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
     * Get single record for editing
     */
    public function get()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak memiliki autoritas untuk masuk');
            }

            if (!isset($_POST['tipe'])) {
                throw new Exception('Tipa parameter dibutuhkan');
            }

            $id = intval($_POST['id'] ?? 0);
            $tipe = $_POST['tipe'];

            if ($id <= 0) {
                throw new Exception('ID tidak ditemukan');
            }

            $table = $this->getTableName($tipe);
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
     * Delete a record by ID
     */
    public function hapus()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Tidak memiliki autoritas untuk masuk');
            }

            if (!isset($_POST['tipe'])) {
                throw new Exception('Tipe parameter dibutuhkan');
            }

            $id = intval($_POST['id'] ?? 0);
            $tipe = $_POST['tipe'];

            if ($id <= 0) {
                throw new Exception('ID tidak ada');
            }

            $table = $this->getTableName($tipe);
            $cek = $this->mysql->ambilSatuId($id, $table);

            if($cek->num_rows == 1) {
                $this->mysql->hapus($_POST['id'], $table);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Suksess menghapus Data'
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