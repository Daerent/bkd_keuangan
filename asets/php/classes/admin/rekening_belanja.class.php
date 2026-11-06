
<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Rekening_belanja extends _Controller
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
        return ['kode_rekening', 'nama_belanja'];
    }

    /**
     * Get columns for display (join rekening + belanja)
     */
    private function getColumns()
    {
        return ['r.id', 'r.kode_rekening', 'b.nama as nama_belanja', 'b.id as belanja_id'];
    }

    /**
     * Check if kode_rekening + nama_belanja combination already exists
     */
    private function checkDuplicateRekeningBelanja($kodeRekening, $namaBelanja, $excludeId = 0)
    {
        // Cek apakah kombinasi kode_rekening + nama_belanja sudah ada
        $query = "SELECT r.id, r.kode_rekening, b.nama as nama_belanja
                  FROM rekening r
                  JOIN belanja b ON r.id = b.rekening_id
                  WHERE r.kode_rekening = '" . $this->mysql->sqlEscape($kodeRekening) . "'
                  AND b.nama = '" . $this->mysql->sqlEscape($namaBelanja) . "'";

        if ($excludeId > 0) {
            $query .= " AND r.id != " . $excludeId;
        }

        $result = $this->mysql->query($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // Exact duplicate found
        }
        return null;
    }

    /**
     * Check if kode_rekening already exists (for warning only)
     */
    private function checkExistingKodeRekening($kodeRekening, $excludeId = 0)
    {
        $query = "SELECT r.id, r.kode_rekening, b.nama as nama_belanja
                  FROM rekening r
                  JOIN belanja b ON r.id = b.rekening_id
                  WHERE r.kode_rekening = '" . $this->mysql->sqlEscape($kodeRekening) . "'";

        if ($excludeId > 0) {
            $query .= " AND r.id != " . $excludeId;
        }

        $result = $this->mysql->query($query);
        if ($result && $result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data; // Return semua data dengan kode rekening yang sama
        }
        return null;
    }

    /**
     * Validate and prepare data for rekening + belanja
     */
    private function validateAndPrepareData()
    {
        $data = [];
        $errors = [];

        // Validate required fields
        if (empty($_POST['kode_rekening'])) {
            $errors['kode_rekening'] = 'Kode Rekening is required';
        }
        if (empty($_POST['nama_belanja'])) {
            $errors['nama_belanja'] = 'Nama Belanja is required';
        }

        // Check for EXACT duplicate (kode + nama belanja sama)
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $kodeRekening = trim($_POST['kode_rekening']);
        $namaBelanja = trim($_POST['nama_belanja']);
        // $kodeRekeningClean = str_replace('.', '', $kodeRekening);

        if (!empty($kodeRekening) && !empty($namaBelanja)) {
            $exactDuplicate = $this->checkDuplicateRekeningBelanja($kodeRekening, $namaBelanja, $id);
            if ($exactDuplicate) {
                $errors['kode_rekening'] = 'Kode rekening dan nama belanja sudah ada';
                $errors['nama_belanja'] = 'Data persis sama sudah terdaftar';
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

        // Check for kode rekening existing (hanya untuk warning)
        if (!empty($kodeRekening) && empty($errors)) {
            $existingKode = $this->checkExistingKodeRekening($kodeRekening, $id);
            if ($existingKode && !isset($_POST['force_save'])) {
                $warningResponse = [
                    'status' => 'warning',
                    'message' => 'Kode rekening sudah ada dengan nama belanja berbeda',
                    'existing_data' => $existingKode, // Array of existing data
                    'new_data' => [
                        'kode_rekening' => $kodeRekening,
                        'nama_belanja' => $namaBelanja
                    ]
                ];
                echo json_encode($warningResponse);
                exit;
            }
        }

        // Prepare data untuk disimpan
        $data = [
            'kode_rekening' => $kodeRekening,
            'nama_belanja' => $namaBelanja
        ];

        return $data;
    }


       private function saveRekeningBelanja($data, $id = 0)
    {
        // Start transaction untuk memastikan konsistensi data
        $this->mysql->query("START TRANSACTION");

        try {
            $kodeRekening = $data['kode_rekening'];
            $namaBelanja = $data['nama_belanja'];

            if ($id > 0) {
                // UPDATE MODE - Update existing record

                // 1. Update rekening
                $rekeningData = ['kode_rekening.s' => $kodeRekening];
                $this->mysql->ubah($id, $rekeningData, 'rekening');

                // 2. Update belanja - CARI BERDASARKAN rekening_id
                $where = ['rekening_id' => '='];
                $whereval = ['i.' . $id];
                $belanjaData = $this->mysql->ambilBeberapa(['id'], $where, $whereval, null, 'belanja');

                if ($belanjaData && $belanjaData->num_rows > 0) {
                    $belanjaRow = $belanjaData->fetch_assoc();
                    $belanjaUpdate = ['nama.s' => $namaBelanja];
                    $this->mysql->ubah($belanjaRow['id'], $belanjaUpdate, 'belanja');

                    $message = 'Data rekening & belanja updated successfully';
                } else {
                    // Jika tidak ada data belanja, buat baru
                    $belanjaData = [
                        'nama.s' => $namaBelanja,
                        'rekening_id.i' => $id
                    ];
                    $this->mysql->simpan($belanjaData, 'belanja');
                    $message = 'Data rekening updated & new belanja created successfully';
                }

            } else {
                // INSERT MODE - Create new records

                // 1. Simpan ke tabel rekening - ID otomatis tersimpan di self::$lastId
                $rekeningData = ['kode_rekening.s' => $kodeRekening];
                $simpanRekening = $this->mysql->simpan($rekeningData, 'rekening');

                if (!$simpanRekening) {
                    throw new Exception('Gagal untuk memasukan ke dalam rekening tabel');
                }

                // 2. Dapatkan ID rekening yang baru saja diinsert
                $rekeningId = $this->mysql::$lastId;

                if (!$rekeningId || $rekeningId <= 0) {
                    throw new Exception('Gagal mendapatkan rekening ID setelah dimasukkan');
                }

                // 3. Simpan ke tabel belanja dengan rekening_id
                $belanjaData = [
                    'nama.s' => $namaBelanja,
                    'rekening_id.i' => $rekeningId  // Gunakan .i untuk integer
                ];

                $simpanBelanja = $this->mysql->simpan($belanjaData, 'belanja');

                if (!$simpanBelanja) {
                    throw new Exception('Gagal memasukkan ke tabel belanja');
                }

                $message = 'Data rekening & belanja suksess tersimpan';
            }

            // Commit transaction
            $this->mysql->query("COMMIT");

            return [
                'status' => 'success',
                'message' => $message,
                // 'last_id' => $this->mysql::$lastId // Return last ID untuk reference
            ];

        } catch (Exception $e) {
            // Rollback transaction jika ada error
            $this->mysql->query("ROLLBACK");
            throw $e;
        }
    }

        /**
         * Helper function untuk insert dan mendapatkan ID
         */
        private function insertAndGetId($data, $table)
        {
            // Insert data
            $result = $this->mysql->simpan($data, $table);

            if ($result) {
                // Dapatkan ID terakhir yang diinsert
                $lastIdQuery = "SELECT LAST_INSERT_ID() as last_id";
                $lastIdResult = $this->mysql->query($lastIdQuery);

                if ($lastIdResult && $lastIdResult->num_rows > 0) {
                    $row = $lastIdResult->fetch_assoc();
                    return $row['last_id'];
                }
            }

            return false;
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
     * Show Rekening & Belanja page inside admin template
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
            $this->view->templateShow(['rekening'=> $contentData]);
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
                $searchableFields = ['r.kode_rekening', 'b.nama'];

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
                          FROM rekening r
                          JOIN belanja b ON r.id = b.rekening_id
                          $where";
            $countResult = $this->mysql->query($countQuery);
            $totalRows = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $perPage);

            // Get data with pagination (join rekening + belanja)
            $mainQuery = "SELECT r.id, r.kode_rekening, b.nama as nama_belanja, b.id as belanja_id
                         FROM rekening r
                         JOIN belanja b ON r.id = b.rekening_id
                         $where
                         ORDER BY r.kode_rekening ASC, b.nama ASC
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
            error_log("Rekening_belanja data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Save or update rekening + belanja
     */
    public function simpan()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            // Validate and prepare data
            $data = $this->validateAndPrepareData();

            // Save to both tables
            $result = $this->saveRekeningBelanja($data, $id);

            echo json_encode($result);

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Get single rekening + belanja for editing
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

            // Get data from join table
            $query = "SELECT r.id, r.kode_rekening, b.nama as nama_belanja, b.id as belanja_id
                     FROM rekening r
                     JOIN belanja b ON r.id = b.rekening_id
                     WHERE r.id = $id";

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
     * Delete a rekening + belanja by ID
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

            // Start transaction
            $this->mysql->query("START TRANSACTION");

            try {
                $where = ['rekening_id' => '='];
                $whereval = ['i.' . $id];
                $this->mysql->hapusKondisi($where, $whereval, null, 'belanja');


                $this->mysql->hapus($_POST['id'], 'rekening');

                // Commit transaction
                $this->mysql->query("COMMIT");

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data rekening & belanja deleted successfully'
                ]);

            } catch (Exception $e) {
                // Rollback transaction jika ada error
                $this->mysql->query("ROLLBACK");
                throw $e;
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