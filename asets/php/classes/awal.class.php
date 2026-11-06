<?php 
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Awal extends _Controller{
	function __construct() {
        parent::__construct();
        $con = $this->database->mysqli;
        $this->mysql->setCon($con);

        // Initialize session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        // Check if user is already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['user_role']);
            return;
        }

        // Pass any error messages to the view
        $data['error_message'] = $_SESSION['login_error'] ?? 'Tidak ada error';
        unset($_SESSION['login_error']);

        $this->view->setdir('login');
        $this->view->show('login', $data);
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
}