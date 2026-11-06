<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Processing extends _Controller {
    function __construct() {
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

    /**
     * Handle login process - UPDATED FOR NIP LOGIN
     */
    public function masuk() {
        // Check if it's an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        // Validate CSRF token
        if (!empty($_POST['csrf_token']) && !$this->validateCsrfToken($_POST['csrf_token'])) {
            $errorMessage = 'Invalid security token';

            if ($isAjax) {
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                exit;
            } else {
                $_SESSION['login_error'] = $errorMessage;
                $this->view->pindah('/', false);
                return;
            }
        }

        // Validate input
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $errorMessage = 'NIP dan password harus diisi';

            if ($isAjax) {
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                exit;
            } else {
                $_SESSION['login_error'] = $errorMessage;
                $this->view->pindah('/', false);
                return;
            }
        }

        $nip = trim($_POST['username']);
        $password = $_POST['password'];

        try {
            // UPDATED QUERY: Login menggunakan NIP bukan username
            $query = "SELECT id, username, password, nama_pegawai, role, is_active, nip
                      FROM pegawai
                      WHERE nip = ? AND is_active = 1";
            $stmt = $this->mysql->prepare($query);

            if (!$stmt) {
                throw new Exception('Database query preparation failed');
            }

            $stmt->bind_param('s', $nip);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows !== 1) {
                $errorMessage = 'NIP atau password salah';

                if ($isAjax) {
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    exit;
                } else {
                    $_SESSION['login_error'] = $errorMessage;
                    $this->view->pindah('/', false);
                    return;
                }
            }

            $userData = $result->fetch_assoc();
            $stmt->close();

            // Verify password
            if ($this->verifyPassword($password, $userData['password'], $userData['id'])) {
                // Set session variables
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['username'] = $userData['username'];
                $_SESSION['user_role'] = $userData['role'];
                $_SESSION['nama_pegawai'] = $userData['nama_pegawai'];
                $_SESSION['nip'] = $userData['nip']; // Store NIP in session
                $_SESSION['logged_in'] = true;

                session_regenerate_id(true);
                unset($_SESSION['login_error']);

                if ($isAjax) {
                    echo json_encode(['status' => 'success', 'redirect' => $this->getRedirectUrl($userData['role'])]);
                    exit;
                } else {
                    $this->redirectBasedOnRole($userData['role']);
                }
            } else {
                $errorMessage = 'NIP atau password salah';

                if ($isAjax) {
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    exit;
                } else {
                    $_SESSION['login_error'] = $errorMessage;
                    $this->view->pindah('/', false);
                    return;
                }
            }

        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $errorMessage = 'Terjadi kesalahan saat login';

            if ($isAjax) {
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                exit;
            } else {
                $_SESSION['login_error'] = $errorMessage;
                $this->view->pindah('/', false);
                return;
            }
        }
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl($role) {
        switch ($role) {
            case 'admin':
                return BASEURL . 'admin/dashboard';
            case 'user':
                return BASEURL . 'user/home';
            default:
                return BASEURL;
        }
    }

    /**
     * Verify password against hash
     */
    private function verifyPassword($password, $hash, $userId) {
        if (password_verify($password, $hash)) {
            return true;
        }

        // Fallback for plaintext passwords (legacy support)
        if ($password === $hash) {
            $this->upgradePasswordHash($userId, $password);
            return true;
        }

        return false;
    }

    /**
     * Upgrade plaintext password to hash
     */
    private function upgradePasswordHash($userId, $password) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $updateData = ['password.s' => $newHash];
            $success = $this->mysql->ubah($userId, $updateData, 'pegawai');

            if (!$success) {
                error_log("Password upgrade failed for user ID: " . $userId);
            }
        } catch (Exception $e) {
            error_log("Password upgrade failed: " . $e->getMessage());
        }
    }

    /**
     * Validate CSRF token
     */
    private function validateCsrfToken($token) {
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($role) {
        switch ($role) {
            case 'admin':
                $this->view->pindah('admin/dashboard', false);
                break;
            case 'user':
                $this->view->pindah('user/home', false);
                break;
            default:
                $this->view->pindah('/', false);
                break;
        }
    }

    /**
     * Handle logout process
     */
    public function keluar() {
        // Clear all session data
        $_SESSION = [];

        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy session
        session_destroy();

        // Redirect to login page
        $this->view->pindah('/', false);
    }

    /**
     * Utility function to hash passwords
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}