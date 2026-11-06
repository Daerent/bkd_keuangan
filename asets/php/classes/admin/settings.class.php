<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Settings extends _Controller
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
     * Get current admin data
     */
    private function getAdminData()
    {
        $adminId = $_SESSION['user_id'] ?? 0;

        if ($adminId <= 0) {
            return null;
        }

        // Menggunakan ambilSatuId untuk mengambil data admin berdasarkan ID
        $result = $this->mysql->ambilSatuId($adminId, 'pegawai', 'i');

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    /**
     * Validate and prepare data for update
     */
    private function validateAndPrepareData()
    {
        $errors = [];

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

        // Validate password if provided
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $errors['password_confirm'] = 'Password confirmation does not match';
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
            'nama_pegawai.s' => $this->mysql->sqlEscape(trim($_POST['nama_pegawai'])),
            'nip.s' => $this->mysql->sqlEscape(trim($_POST['nip'])),
            'jabatan.s' => $this->mysql->sqlEscape(trim($_POST['jabatan']))
        ];

        // Add password if provided
        if (!empty($_POST['password'])) {
            $data['password.s'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
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
     * Show Settings page
     */
    public function index()
    {
        if(isset($_POST['a'])){
            // Validate CSRF for POST requests
            if (!empty($_POST['a']) && in_array($_POST['a'], ['update_profile']) &&
                !$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid CSRF token'
                ]);
                exit;
            }

            $pil = $_POST['a'];
            switch ($pil) {
                case 'update_profile':
                    $this->updateProfile();
                    break;
                case 'get_profile':
                    $this->getProfile();
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
            // Check if user is logged in
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
                header('HTTP/1.1 403 Forbidden');
                echo "Access denied";
                exit;
            }

            // Get admin data
            $adminData = $this->getAdminData();
            if (!$adminData) {
                header('HTTP/1.1 404 Not Found');
                echo "Admin data not found";
                exit;
            }

            $templateData = [
                'countPendingVerifikasi' => $this->hitungPendingManual()
            ];

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'admin_data' => $adminData
            ];

            $this->view->template(['admin'=> $templateData]);
            $this->view->setDir('admin');
            $this->view->templateShow(['settings'=> $contentData]);
        }
    }

    /**
     * Get admin profile data (AJAX)
     */
    public function getProfile()
    {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $adminData = $this->getAdminData();
            if (!$adminData) {
                throw new Exception('Admin data tidak ditemukan');
            }

            // Remove sensitive data
            unset($adminData['password']);

            echo json_encode([
                'status' => 'success',
                'data' => $adminData
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
     * Update admin profile
     */
    public function updateProfile()
    {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $adminId = $_SESSION['user_id'];
            $table = 'pegawai';

            // Validate and prepare data
            $data = $this->validateAndPrepareData();

            // Update the profile
            $success = $this->mysql->ubah($adminId, $data, $table);

            if ($success) {
                // Update session data if nama_pegawai changed
                if (isset($data['nama_pegawai.s'])) {
                    $_SESSION['user_name'] = trim($_POST['nama_pegawai']);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Profile updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update profile');
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