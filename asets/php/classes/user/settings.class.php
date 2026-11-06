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

        // Hanya user (PPTK) yang boleh akses
        if ($_SESSION['user_role'] !== 'user') {
            header('HTTP/1.1 403 Forbidden');
            echo "Access denied - PPTK only";
            exit;
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
     * Get current PPTK data
     */
    private function getPPTKData()
    {
        $pptkId = $_SESSION['user_id'] ?? 0;

        if ($pptkId <= 0) {
            return null;
        }

        // Menggunakan ambilSatuId untuk mengambil data berdasarkan ID
        $userData = $this->mysql->ambilSatuId($pptkId, 'pegawai', 'i');

        if ($userData && $userData->num_rows > 0) {
            return $userData->fetch_assoc();
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

        // Validate current password for security
        if (!empty($_POST['password'])) {
            // Verify current password
            $currentPassword = $_POST['current_password'] ?? '';
            if (empty($currentPassword)) {
                $errors['current_password'] = 'Kata sandi saat ini diperlukan untuk mengubah kata sandi';
            } else {
                // Verify current password from database
                $pptkData = $this->getPPTKData();
                if (!$pptkData || !password_verify($currentPassword, $pptkData['password'])) {
                    $errors['current_password'] = 'Kata sandi saat ini salah';
                }
            }

            // Validate new password
            if (strlen($_POST['password']) < 6) {
                $errors['password'] = 'Kata sandi harus minimal 6 karakter';
            }
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $errors['password_confirm'] = 'Konfirmasi kata sandi tidak cocok';
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

    //_______________________________________________________\\

    /**
     * Show Settings page for PPTK
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
            $pegawai_id = $_SESSION['user_id'];

            // Get PPTK data
            $pptkData = $this->getPPTKData();
            if (!$pptkData) {
                header('HTTP/1.1 404 Not Found');
                echo "PPTK data tidak ditemukan";
                exit;
            }

            $templateData = $this->getTemplateData($pegawai_id);

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'pptk_data' => $pptkData
            ];

            $this->view->template(['user'=> $templateData]);
            $this->view->setDir('user');
            $this->view->templateShow(['settings'=> $contentData]);
        }
    }

    /**
     * Get PPTK profile data (AJAX)
     */
    public function getProfile()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];

            $pptkData = $this->getPPTKData();
            if (!$pptkData) {
                throw new Exception('PPTK data tidak ditemukan');
            }

            // Remove sensitive data
            unset($pptkData['password']);

            echo json_encode([
                'status' => 'success',
                'data' => $pptkData
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
     * Update PPTK profile
     */
    public function updateProfile()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $table = 'pegawai';

            // Validate and prepare data
            $data = $this->validateAndPrepareData();

            // Update the profile
            $success = $this->mysql->ubah($pegawai_id, $data, $table);

            if ($success) {
                // Update session data
                if (isset($data['nama_pegawai.s'])) {
                    $_SESSION['nama_pegawai'] = trim($_POST['nama_pegawai']);
                    $_SESSION['user_name'] = trim($_POST['nama_pegawai']);
                }
                if (isset($data['nip.s'])) {
                    $_SESSION['user_nip'] = trim($_POST['nip']);
                }

                $response = [
                    'status' => 'success',
                    'message' => 'Profil berhasil diperbarui'
                ];

                // Add warning if password was changed
                if (!empty($_POST['password'])) {
                    $response['warning'] = 'Kata sandi berhasil diubah. Silakan masuk kembali demi keamanan.';
                }

                echo json_encode($response);
            } else {
                throw new Exception('Gagal memperbarui profil');
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