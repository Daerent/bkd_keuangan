<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Dashboard extends _Controller
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

    /**
     * Get statistics for dashboard cards
     */
    private function getDashboardStats()
    {
        $stats = [
            'total_kwitansi' => 0,
            'pending_verifikasi' => 0,
            'disetujui' => 0,
            'ditolak' => 0,
            'total_pptk' => 0,
            'total_kegiatan' => 0
        ];

        try {
            $kwitansiQuery = "SELECT
                                COUNT(*) as total,
                                COUNT(CASE WHEN status = 'verifikasi' THEN 1 END) as pending,
                                COUNT(CASE WHEN status = 'disetujui' THEN 1 END) as disetujui,
                                COUNT(CASE WHEN status = 'ditolak' THEN 1 END) as ditolak
                              FROM kwitansi";

            $kwitansiResult = $this->mysql->query($kwitansiQuery);
            if ($kwitansiResult && $kwitansiResult->num_rows > 0) {
                $kwitansiData = $kwitansiResult->fetch_assoc();
                $stats['total_kwitansi'] = $kwitansiData['total'] ?? 0;
                $stats['pending_verifikasi'] = $kwitansiData['pending'] ?? 0;
                $stats['disetujui'] = $kwitansiData['disetujui'] ?? 0;
                $stats['ditolak'] = $kwitansiData['ditolak'] ?? 0;
            }

            $pptkQuery = "SELECT COUNT(*) as total
                          FROM pegawai
                          WHERE role = 'user' AND is_active = 1";
            $pptkResult = $this->mysql->query($pptkQuery);
            if ($pptkResult && $pptkResult->num_rows > 0) {
                $pptkData = $pptkResult->fetch_assoc();
                $stats['total_pptk'] = $pptkData['total'] ?? 0;
            }

            $kegiatanQuery = "SELECT COUNT(*) as total FROM kegiatan";
            $kegiatanResult = $this->mysql->query($kegiatanQuery);
            if ($kegiatanResult && $kegiatanResult->num_rows > 0) {
                $kegiatanData = $kegiatanResult->fetch_assoc();
                $stats['total_kegiatan'] = $kegiatanData['total'] ?? 0;
            }

        } catch (Exception $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Get recent activities (last 10 kwitansi created)
     * REVISI: Ambil jenis_kwitansi dari table kegiatan
     */
    private function getRecentActivities()
    {
        $activities = [];

        try {
            $query = "SELECT
                        k.Id as kwitansi_id,
                        k.no_kwitansi,
                        k.status,
                        kg.jenis_kwitansi,  -- REVISI: Ambil dari kegiatan
                        k.total_uang,
                        k.created_at,
                        p.nama_pegawai as pptk,
                        kg.nama_kegiatan
                      FROM kwitansi k
                      LEFT JOIN pegawai p ON k.pegawai_id = p.id
                      LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id  -- REVISI: Join dengan kegiatan
                      ORDER BY k.created_at DESC
                      LIMIT 10";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $activities[] = $row;
                }
            }

        } catch (Exception $e) {
            error_log("Recent activities error: " . $e->getMessage());
        }

        return $activities;
    }

    /**
     * Get monthly statistics for charts
     */
    private function getMonthlyStats()
    {
        $monthlyData = [];

        try {
            $query = "SELECT
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        COUNT(*) as total,
                        COUNT(CASE WHEN status = 'disetujui' THEN 1 END) as disetujui,
                        COUNT(CASE WHEN status = 'ditolak' THEN 1 END) as ditolak,
                        COUNT(CASE WHEN status = 'verifikasi' THEN 1 END) as pending,
                        COALESCE(SUM(CASE WHEN status = 'disetujui' THEN total_uang ELSE 0 END), 0) as total_uang_disetujui
                      FROM kwitansi
                      WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                      GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                      ORDER BY month ASC";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $monthlyData[] = $row;
                }
            }

        } catch (Exception $e) {
            error_log("Monthly stats error: " . $e->getMessage());
        }

        return $monthlyData;
    }

    /**
     * Get top PPTK with most kwitansi
     */
    private function getTopPPTK()
    {
        $topPPTK = [];

        try {
            $query = "SELECT
                        p.nama_pegawai,
                        p.nip,
                        COUNT(k.Id) as total_kwitansi,
                        COUNT(CASE WHEN k.status = 'disetujui' THEN 1 END) as disetujui,
                        COUNT(CASE WHEN k.status = 'ditolak' THEN 1 END) as ditolak,
                        COUNT(CASE WHEN k.status = 'verifikasi' THEN 1 END) as pending
                      FROM pegawai p
                      LEFT JOIN kwitansi k ON p.id = k.pegawai_id
                      WHERE p.role = 'user' AND p.is_active = 1
                      GROUP BY p.id, p.nama_pegawai, p.nip
                      ORDER BY total_kwitansi DESC
                      LIMIT 5";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $topPPTK[] = $row;
                }
            }

        } catch (Exception $e) {
            error_log("Top PPTK error: " . $e->getMessage());
        }

        return $topPPTK;
    }

    /**
     * Get pending verifikasi for urgent attention
     * REVISI: Ambil jenis_kwitansi dari table kegiatan
     */
    private function getPendingVerifikasi()
    {
        $pending = [];

        try {
            $query = "SELECT
                        k.Id as kwitansi_id,
                        k.no_kwitansi,
                        k.total_uang,
                        k.created_at,
                        p.nama_pegawai as pptk,
                        kg.nama_kegiatan,
                        kg.jenis_kwitansi  -- REVISI: Ambil dari kegiatan
                      FROM kwitansi k
                      LEFT JOIN pegawai p ON k.pegawai_id = p.id
                      LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id  -- REVISI: Join dengan kegiatan
                      WHERE k.status = 'verifikasi'
                      ORDER BY k.created_at ASC
                      LIMIT 5";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $pending[] = $row;
                }
            }

        } catch (Exception $e) {
            error_log("Pending verifikasi error: " . $e->getMessage());
        }

        return $pending;
    }

    /**
     * Get status distribution for pie chart
     */
    private function getStatusDistribution()
    {
        $distribution = [];

        try {
            $query = "SELECT
                        status,
                        COUNT(*) as count
                      FROM kwitansi
                      GROUP BY status";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $distribution[$row['status']] = $row['count'];
                }
            }

        } catch (Exception $e) {
            error_log("Status distribution error: " . $e->getMessage());
        }

        return $distribution;
    }

    /**
     * Get PPTK performance data for charts
     */
    private function getPPTKPerformance()
    {
        $performance = [];

        try {
            $query = "SELECT
                        p.nama_pegawai,
                        COUNT(k.Id) as total_kwitansi,
                        COUNT(CASE WHEN k.status = 'disetujui' THEN 1 END) as disetujui,
                        COUNT(CASE WHEN k.status = 'ditolak' THEN 1 END) as ditolak,
                        COUNT(CASE WHEN k.status = 'verifikasi' THEN 1 END) as pending
                      FROM pegawai p
                      LEFT JOIN kwitansi k ON p.id = k.pegawai_id
                      WHERE p.role = 'user' AND p.is_active = 1
                      GROUP BY p.id, p.nama_pegawai
                      ORDER BY total_kwitansi DESC
                      LIMIT 5";

            $result = $this->mysql->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $performance[] = $row;
                }
            }

        } catch (Exception $e) {
            error_log("PPTK performance error: " . $e->getMessage());
        }

        return $performance;
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
     * Show Dashboard page
     */
    public function index()
    {
        if(isset($_POST['a'])){
            $pil = $_POST['a'];
            switch ($pil) {
                case 'get_stats':
                    $this->getStatsAJAX();
                    break;
                case 'get_monthly_data':
                    $this->getMonthlyDataAJAX();
                    break;
                case 'get_chart_data':
                    $this->getChartDataAJAX();
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
            if ($_SESSION['user_role'] !== 'admin') {
                header('HTTP/1.1 403 Forbidden');
                echo "Access denied";
                exit;
            }

            $stats = $this->getDashboardStats();
            $recentActivities = $this->getRecentActivities();
            $monthlyStats = $this->getMonthlyStats();
            $topPPTK = $this->getTopPPTK();
            $pendingVerifikasi = $this->getPendingVerifikasi();
            $statusDistribution = $this->getStatusDistribution();
            $pptkPerformance = $this->getPPTKPerformance();


            $templateData = [
                'countPendingVerifikasi' => $this->hitungPendingManual(),
                'stats' => $this->getDashboardStats()
            ];

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'stats' => $stats,
                'recent_activities' => $recentActivities,
                'monthly_stats' => $monthlyStats,
                'top_pptk' => $topPPTK,
                'pending_verifikasi_list' => $pendingVerifikasi,
                'status_distribution' => $statusDistribution,
                'pptk_performance' => $pptkPerformance
            ];

            $this->view->template(['admin'=> $templateData]);
            $this->view->setDir('admin');
            $this->view->templateShow(['dashboard'=> $contentData]);
        }
    }

    /**
     * AJAX endpoint for real-time stats
     */
    public function getStatsAJAX()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $stats = $this->getDashboardStats();

            echo json_encode([
                'status' => 'success',
                'data' => $stats
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
     * AJAX endpoint for monthly chart data
     */
    public function getMonthlyDataAJAX()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $monthlyData = $this->getMonthlyStats();

            echo json_encode([
                'status' => 'success',
                'data' => $monthlyData
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
     * AJAX endpoint for all chart data
     */
    public function getChartDataAJAX()
    {
        try {
            if ($_SESSION['user_role'] !== 'admin') {
                throw new Exception('Unauthorized');
            }

            $monthlyData = $this->getMonthlyStats();
            $statusDistribution = $this->getStatusDistribution();
            $pptkPerformance = $this->getPPTKPerformance();

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'monthly' => $monthlyData,
                    'status_distribution' => $statusDistribution,
                    'pptk_performance' => $pptkPerformance
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