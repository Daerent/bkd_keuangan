<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Riwayat extends _Controller
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

        if ($_SESSION['user_role'] !== 'user') {
            header('HTTP/1.1 403 Forbidden');
            echo "Access denied - PPTK only";
            exit;
        }
    }

    private function validateCsrf($token)
    {
        return !empty($_SESSION['csrf_token']) &&
               !empty($token) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    private function getSearchableFields()
    {
        return ['k.no_kwitansi', 'kg.nama_kegiatan', 'k.status'];
    }

    private function getColumns()
    {
        return [
            'k.Id as kwitansi_id',
            'k.no_kwitansi',
            'kg.jenis_kwitansi',
            'kg.jenis_ttd',
            'k.status',
            'k.uraian_spj',
            'k.total_uang',
            'k.triwulan',
            'k.tgl_kwitansi',
            'k.created_at',
            'kg.nama_kegiatan',
            'r.kode_rekening',
            'b.nama as nama_belanja',
            'p.nama_pegawai',
            'p.nip',
            'pj.nama_penyedia',
            'pe.nama as nama_pejabat',
            'pb.nama as nama_bendahara'
        ];
    }

    private function getRiwayatKwitansi($pegawai_id, $page = 1, $perPage = 10, $search = '', $searchField = '')
    {
        $where = "WHERE k.pegawai_id = ? AND k.status = 'disetujui'";
        $params = [$pegawai_id];
        $paramTypes = "i";

        if (!empty($search)) {
            $searchableFields = $this->getSearchableFields();

            if (!empty($searchField) && in_array($searchField, $searchableFields)) {
                $where .= " AND $searchField LIKE ?";
                $params[] = "%$search%";
                $paramTypes .= "s";
            } else {
                $whereConditions = [];
                foreach ($searchableFields as $field) {
                    $whereConditions[] = "$field LIKE ?";
                    $params[] = "%$search%";
                    $paramTypes .= "s";
                }
                $where .= " AND (" . implode(' OR ', $whereConditions) . ")";
            }
        }

        $offset = ($page - 1) * $perPage;

        $countQuery = "SELECT COUNT(*) as total
                      FROM kwitansi k
                      LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                      LEFT JOIN rekening r ON kg.rekening_id = r.id
                      LEFT JOIN belanja b ON r.id = b.rekening_id
                      LEFT JOIN pegawai p ON k.pegawai_id = p.id
                      LEFT JOIN penyedia pj ON k.penyedia_id = pj.id
                      LEFT JOIN pejabat pe ON k.pejabat_id = pe.id
                      LEFT JOIN pejabat pb ON k.bendahara_id = pb.id
                      $where";

        $stmt = $this->mysql->prepare($countQuery);
        if (count($params) > 1) {
            $stmt->bind_param($paramTypes, ...$params);
        } else {
            $stmt->bind_param($paramTypes, $params[0]);
        }
        $stmt->execute();
        $countResult = $stmt->get_result();
        $totalRows = $countResult->fetch_assoc()['total'];
        $totalPages = ceil($totalRows / $perPage);

        $mainQuery = "SELECT k.Id as kwitansi_id, k.no_kwitansi,
                             kg.jenis_kwitansi, kg.jenis_ttd,
                             k.status, k.uraian_spj, k.total_uang, k.triwulan, k.tgl_kwitansi, k.created_at,
                             kg.nama_kegiatan, r.kode_rekening, b.nama as nama_belanja,
                             p.nama_pegawai, p.nip,
                             pj.nama_penyedia,
                             pe.nama as nama_pejabat, pe.jabatan as jabatan_pejabat,
                             pb.nama as nama_bendahara, pb.jabatan as jabatan_bendahara
                      FROM kwitansi k
                      LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                      LEFT JOIN rekening r ON kg.rekening_id = r.id
                      LEFT JOIN belanja b ON r.id = b.rekening_id
                      LEFT JOIN pegawai p ON k.pegawai_id = p.id
                      LEFT JOIN penyedia pj ON k.penyedia_id = pj.id
                      LEFT JOIN pejabat pe ON k.pejabat_id = pe.id
                      LEFT JOIN pejabat pb ON k.bendahara_id = pb.id
                      $where
                      ORDER BY k.created_at DESC
                      LIMIT ? OFFSET ?";

        $params[] = $perPage;
        $params[] = $offset;
        $paramTypes .= "ii";

        $stmt = $this->mysql->prepare($mainQuery);
        $stmt->bind_param($paramTypes, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        return [
            'data' => $rows,
            'total_rows' => $totalRows,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'per_page' => $perPage
        ];
    }

    private function getDetailKwitansi($kwitansi_id, $pegawai_id)
    {
        $query = "SELECT k.Id, k.no_kwitansi,
                         kg.jenis_kwitansi, kg.jenis_ttd,  
                         k.status, k.uraian_spj, k.total_uang, k.terbilang, k.triwulan, k.tgl_kwitansi, k.created_at,
                         kg.nama_kegiatan, kg.rekening_id,
                         r.kode_rekening, b.nama as nama_belanja,
                         p.nama_pegawai, p.nip,
                         pj.nama_perusahaan, pj.nama_penyedia, pj.jabatan as jabatan_penyedia,
                         pe.nama as nama_pejabat, pe.jabatan as jabatan_pejabat, pe.nip as nip_pejabat,
                         pb.nama as nama_bendahara, pb.jabatan as jabatan_bendahara, pb.nip as nip_bendahara
                  FROM kwitansi k
                  LEFT JOIN kegiatan kg ON k.kegiatan_id = kg.id
                  LEFT JOIN rekening r ON kg.rekening_id = r.id
                  LEFT JOIN belanja b ON r.id = b.rekening_id
                  LEFT JOIN pegawai p ON k.pegawai_id = p.id
                  LEFT JOIN penyedia pj ON k.penyedia_id = pj.id
                  LEFT JOIN pejabat pe ON k.pejabat_id = pe.id
                  LEFT JOIN pejabat pb ON k.bendahara_id = pb.id
                  WHERE k.Id = ? AND k.pegawai_id = ? AND k.status = 'disetujui'";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("ii", $kwitansi_id, $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    private function getDokumenKwitansi($kwitansi_id)
    {
        $query = "SELECT d.id, d.nama_dokumen, d.file, d.uploaded_at,
                         sv.nama_syarat, sv.urutan
                  FROM dokumentasi d
                  LEFT JOIN syarat_ver sv ON d.syarat_id = sv.id
                  WHERE d.kwitansi_id = ?
                  ORDER BY sv.urutan ASC";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("i", $kwitansi_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $dokumen = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dokumen[] = $row;
            }
        }
        return $dokumen;
    }

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

    private function getTemplateData($pegawai_id)
    {
        return [
            'active_kwitansi_count' => $this->hitungActiveKwitansi($pegawai_id),
            'user_name' => $_SESSION['nama_pegawai'] ?? 'PPTK',
            'username' => $_SESSION['username'] ?? 'User'
        ];
    }

    public function index()
    {
        if(isset($_POST['a'])){
            if (!empty($_POST['a']) && in_array($_POST['a'], ['get_data', 'get_detail']) &&
                !$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid CSRF token'
                ]);
                exit;
            }

            $pil = $_POST['a'];
            switch ($pil) {
                case 'get_data':
                    $this->getData();
                    break;
                case 'get_detail':
                    $this->getDetail();
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

            $riwayatData = $this->getRiwayatKwitansi($pegawai_id, 1, 10);
            $templateData = $this->getTemplateData($pegawai_id);

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'initial_data' => $riwayatData['data'],
                'pagination' => [
                    'total_rows' => $riwayatData['total_rows'],
                    'total_pages' => $riwayatData['total_pages'],
                    'current_page' => $riwayatData['current_page'],
                    'per_page' => $riwayatData['per_page']
                ]
            ];

            $this->view->template(['user'=> $templateData]);
            $this->view->setDir('user');
            $this->view->templateShow(['riwayat' => $contentData]);
        }
    }

    public function getData()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];

            $page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
            $perPage = isset($_POST['per_page']) ? max(1, intval($_POST['per_page'])) : 10;
            $search = isset($_POST['search']) ? $this->mysql->sqlEscape(trim($_POST['search'])) : '';
            $searchField = isset($_POST['search_field']) ? $this->mysql->sqlEscape(trim($_POST['search_field'])) : '';

            $riwayatData = $this->getRiwayatKwitansi($pegawai_id, $page, $perPage, $search, $searchField);

            echo json_encode([
                'status' => 'success',
                'message' => 'Data fetched successfully',
                'data' => $riwayatData['data'],
                'pagination' => [
                    'current_page' => $riwayatData['current_page'],
                    'per_page' => $riwayatData['per_page'],
                    'total_rows' => $riwayatData['total_rows'],
                    'total_pages' => $riwayatData['total_pages']
                ]
            ]);
        } catch (Exception $e) {
            error_log("Riwayat data error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public function getDetail()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $kwitansi_id = intval($_POST['id'] ?? 0);

            if ($kwitansi_id <= 0) {
                throw new Exception('Invalid ID');
            }

            $kwitansiDetail = $this->getDetailKwitansi($kwitansi_id, $pegawai_id);

            if (!$kwitansiDetail) {
                throw new Exception('Data tidak ditemukan atau akses ditolak');
            }

            $dokumen = $this->getDokumenKwitansi($kwitansi_id);

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'kwitansi' => $kwitansiDetail,
                    'dokumen' => $dokumen
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