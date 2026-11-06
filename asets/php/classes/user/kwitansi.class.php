<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");

class Kwitansi extends _Controller
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
     * Cek apakah user memiliki kwitansi aktif yang bisa dikerjakan
     */
    private function getActiveKwitansi($pegawai_id)
    {
        $query = "SELECT k.Id, k.no_kwitansi, k.status,
                         kg.jenis_kwitansi, kg.jenis_ttd,  -- AMBIL DARI KEGIATAN
                         k.uraian_spj, k.total_uang, k.terbilang, k.triwulan, k.tgl_kwitansi,
                         k.penyedia_id, k.pejabat_id, k.bendahara_id, k.keterangan,
                         kg.nama_kegiatan, kg.rekening_id,
                         r.kode_rekening, b.nama as nama_belanja,
                         p.nama_pegawai, p.nip,
                         pj.nama_penyedia as nama_penyedia, pj.jabatan as jabatan_penyedia,
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
                  WHERE k.pegawai_id = ?
                  AND k.status IN ('draft', 'verifikasi', 'ditolak')
                  ORDER BY
                    CASE
                      WHEN k.status = 'ditolak' THEN 1
                      WHEN k.status = 'draft' THEN 2
                      WHEN k.status = 'verifikasi' THEN 3
                    END,
                    k.created_at DESC
                  LIMIT 1";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("i", $pegawai_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get list dokumen yang required untuk rekening tertentu
     */
    private function getRequiredDocuments($rekening_id, $kwitansi_id)
    {
        $query = "SELECT ld.id as list_doc_id, sv.id as syarat_id, sv.nama_syarat, sv.urutan,
                         d.id as dokumen_id, d.nama_dokumen, d.file, d.uploaded_at
                  FROM list_doc ld
                  LEFT JOIN syarat_ver sv ON ld.syarat_id = sv.id
                  LEFT JOIN dokumentasi d ON d.syarat_id = sv.id AND d.kwitansi_id = ?
                  WHERE ld.rekening_id = ?
                  ORDER BY sv.urutan ASC";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("ii", $kwitansi_id, $rekening_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $documents = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $documents[] = $row;
            }
        }
        return $documents;
    }

    /**
     * Validate and prepare kwitansi data
     */
    private function validateAndPrepareKwitansiData()
    {
        $data = [];
        $errors = [];

        // Validate required fields sesuai sheet INPUT excel
        if (empty($_POST['uraian_spj'])) {
            $errors['uraian_spj'] = 'Uraian SPJ is required';
        }
        if (empty($_POST['total_uang']) || floatval($_POST['total_uang']) <= 0) {
            $errors['total_uang'] = 'Total uang harus lebih dari 0';
        }
        if (empty($_POST['triwulan'])) {
            $errors['triwulan'] = 'Triwulan is required';
        }
        if (empty($_POST['tgl_kwitansi'])) {
            $errors['tgl_kwitansi'] = 'Tanggal kwitansi is required';
        }

        if (empty($_POST['pejabat_id'])) {
            $errors['pejabat_id'] = 'Kuasa Pengguna Anggaran is required';
        }

        if (empty($_POST['bendahara_id'])) {
            $errors['bendahara_id'] = 'Bendahara Pengeluaran is required';
        }

        // Validasi tambahan untuk jenis penyedia
        $activeKwitansi = $this->getActiveKwitansi($_SESSION['user_id']);
        if ($activeKwitansi && $activeKwitansi['jenis_kwitansi'] === 'penyedia' && empty($_POST['penyedia_id'])) {
            $errors['penyedia_id'] = 'Penyedia is required untuk kwitansi penyediaan';
        }

        if (!empty($errors)) {
            $errorResponse = [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ];
            echo json_encode($errorResponse);
            exit;
        }


        $total_uang = $_POST['total_uang'];

        // Hapus semua titik (pemisah ribuan)
        $total_uang = str_replace('.', '', $total_uang);

        // Ubah ke float
        $total_uang = floatval($total_uang);
        $terbilang = $this->generateTerbilang($total_uang);

        // Prepare data untuk update
        $data = [
            'uraian_spj.s' => $this->mysql->sqlEscape($_POST['uraian_spj']),
            'total_uang.d' => $total_uang,
            'terbilang.s' => $this->mysql->sqlEscape($terbilang),
            'triwulan.s' => $this->mysql->sqlEscape($_POST['triwulan']),
            'tgl_kwitansi.s' => $this->mysql->sqlEscape($_POST['tgl_kwitansi']),
            'pejabat_id.i' => intval($_POST['pejabat_id']),
            'bendahara_id.i' => intval($_POST['bendahara_id'])
        ];

        // Add optional field untuk penyedia (hanya untuk jenis penyedia)
        if (!empty($_POST['penyedia_id'])) {
            $data['penyedia_id.i'] = intval($_POST['penyedia_id']);
        }

        return $data;
    }



    /**
     * Generate terbilang dari angka
     */
    private function generateTerbilang($angka)
    {
        if ($angka == 0) return 'Nol Rupiah';
        if ($angka > 9999999999999999) return 'Angka terlalu besar';

        $bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        $convertRecursive = function($num) use (&$convertRecursive, $bilangan) {
            if ($num < 12) return $bilangan[$num];
            if ($num < 20) return $bilangan[$num - 10] . ' Belas';
            if ($num < 100) {
                $puluh = floor($num / 10);
                $sisa = $num % 10;
                return $bilangan[$puluh] . ' Puluh' . ($sisa > 0 ? ' ' . $bilangan[$sisa] : '');
            }
            if ($num < 200) return 'Seratus ' . $convertRecursive($num - 100);
            if ($num < 1000) {
                $ratus = floor($num / 100);
                $sisa = $num % 100;
                return $bilangan[$ratus] . ' Ratus' . ($sisa > 0 ? ' ' . $convertRecursive($sisa) : '');
            }
            if ($num < 2000) return 'Seribu ' . $convertRecursive($num - 1000);
            if ($num < 1000000) {
                $ribu = floor($num / 1000);
                $sisa = $num % 1000;
                return $convertRecursive($ribu) . ' Ribu' . ($sisa > 0 ? ' ' . $convertRecursive($sisa) : '');
            }
            if ($num < 1000000000) {
                $juta = floor($num / 1000000);
                $sisa = $num % 1000000;
                return $convertRecursive($juta) . ' Juta' . ($sisa > 0 ? ' ' . $convertRecursive($sisa) : '');
            }
            if ($num < 1000000000000) {
                $milyar = floor($num / 1000000000);
                $sisa = $num % 1000000000;
                return $convertRecursive($milyar) . ' Milyar' . ($sisa > 0 ? ' ' . $convertRecursive($sisa) : '');
            }
            if ($num < 1000000000000000) {
                $triliun = floor($num / 1000000000000);
                $sisa = $num % 1000000000000;
                return $convertRecursive($triliun) . ' Triliun' . ($sisa > 0 ? ' ' . $convertRecursive($sisa) : '');
            }

            $kuadriliun = floor($num / 1000000000000000);
            $sisa = $num % 1000000000000000;
            return $convertRecursive($kuadriliun) . ' Kuadriliun' . ($sisa > 0 ? ' ' . $convertRecursive($sisa) : '');
        };

        // Convert the number
        $result = $convertRecursive($angka);

        // Fix common issues
        $result = str_replace(
            ['Satu Puluh', 'Satu Belas'],
            ['Sepuluh', 'Sebelas'],
            $result
        );

        // Clean up multiple spaces
        $result = preg_replace('/\s+/', ' ', $result);
        $result = trim($result);

        return $result . ' Rupiah';
    }



     private function getPenyediaById($penyediaId)
    {
        try {
            $result = $this->mysql->ambilSatuId($penyediaId, 'penyedia', 'i');
            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            return null;
        } catch (Exception $e) {
            error_log("Get Penyedia Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all penyedia for dropdown
     */
    private function getAllPenyedia()
    {
        $result = $this->mysql->ambilSemuaSort('nama_perusahaan', 'ASC', 'penyedia');
        $penyedia = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $penyedia[] = $row;
            }
        }
        return $penyedia;
    }

    /**
     * Get all pejabat for dropdown (Kuasa Pengguna Anggaran)
     */
    private function getAllPejabat()
    {
        $query = "SELECT id, nama, jabatan, nip
                  FROM pejabat
                  WHERE jabatan LIKE '%Kuasa Pengguna Anggaran%' OR jabatan LIKE '%KPA%'
                  ORDER BY nama ASC";

        $result = $this->mysql->query($query);
        $pejabat = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pejabat[] = $row;
            }
        }
        return $pejabat;
    }

    /**
     * Get all bendahara for dropdown (Bendahara Pengeluaran)
     */
    private function getAllBendahara()
    {
        $query = "SELECT id, nama, jabatan, nip
                  FROM pejabat
                  WHERE jabatan LIKE '%Bendahara Pengeluaran%' OR jabatan LIKE '%Bendahara%'
                  ORDER BY nama ASC";

        $result = $this->mysql->query($query);
        $bendahara = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bendahara[] = $row;
            }
        }
        return $bendahara;
    }

    /**
     * Cek kelengkapan dokumen sebelum submit
     */
    private function checkDocumentCompleteness($kwitansi_id, $rekening_id)
    {
        $query = "SELECT COUNT(*) as total_required,
                         SUM(CASE WHEN d.id IS NOT NULL THEN 1 ELSE 0 END) as total_uploaded
                  FROM list_doc ld
                  LEFT JOIN syarat_ver sv ON ld.syarat_id = sv.id
                  LEFT JOIN dokumentasi d ON d.syarat_id = sv.id AND d.kwitansi_id = ?
                  WHERE ld.rekening_id = ?";

        $stmt = $this->mysql->prepare($query);
        $stmt->bind_param("ii", $kwitansi_id, $rekening_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return [
                'total_required' => $row['total_required'],
                'total_uploaded' => $row['total_uploaded'],
                'is_complete' => $row['total_required'] == $row['total_uploaded']
            ];
        }

        return ['total_required' => 0, 'total_uploaded' => 0, 'is_complete' => false];
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
     * Show Kwitansi page for PPTK
     */
    public function index()
    {
        if(isset($_POST['a'])){
            if (!empty($_POST['a']) && in_array($_POST['a'], ['simpan', 'submit', 'upload_dokumen', 'create_penyedia']) &&
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
                case 'simpan':
                    $this->simpan();
                    break;
                case 'submit':
                    $this->submit();
                    break;
                case 'get_dokumen':
                    $this->getDokumen();
                    break;
                case 'upload_dokumen':
                    $this->uploadDokumen();
                    break;
                case 'hapus_dokumen':
                    $this->hapusDokumen();
                    break;
                case 'create_penyedia':
                    $this->createPenyedia();
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

            // Cek apakah ada kwitansi aktif
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id);

            if (!$activeKwitansi) {
                $this->view->pindah('user/home');
                exit;
            }

            // Get additional data
            $requiredDocuments = $this->getRequiredDocuments($activeKwitansi['rekening_id'], $activeKwitansi['Id']);
            $penyediaList = $this->getAllPenyedia();
            $pejabatList = $this->getAllPejabat();
            $bendaharaList = $this->getAllBendahara();

            // Cek kelengkapan dokumen
            $docCompleteness = $this->checkDocumentCompleteness(
                $activeKwitansi['Id'],
                $activeKwitansi['rekening_id']
            );

            $templateData = $this->getTemplateData($pegawai_id);

            $contentData = [
                'csrf_token' => $_SESSION['csrf_token'],
                'kwitansi' => $activeKwitansi,
                'required_documents' => $requiredDocuments,
                'penyedia_list' => $penyediaList,
                'pejabat_list' => $pejabatList,
                'bendahara_list' => $bendaharaList,
                'doc_completeness' => $docCompleteness
            ];

            $this->view->template(['user'=> $templateData]);
            $this->view->setDir('user');
            $this->view->templateShow(['kwitansi' => $contentData]);
        }
    }

    /**
     * Get kwitansi data via AJAX
     */
    public function getData()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id);

            if (!$activeKwitansi) {
                throw new Exception('Tidak ada kwitansi aktif');
            }

            echo json_encode([
                'status' => 'success',
                'data' => $activeKwitansi
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
     * Save kwitansi data (draft)
     */
     public function simpan()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id);

            if (!$activeKwitansi) {
                throw new Exception('Tidak ada kwitansi aktif');
            }

            if (!in_array($activeKwitansi['status'], ['draft', 'ditolak'])) {
                throw new Exception('Kwitansi sudah disubmit, tidak bisa diedit');
            }

            $id = $activeKwitansi['Id'];
            $table = 'kwitansi';

            // Validate and prepare data
            $data = $this->validateAndPrepareKwitansiData();

            // Jika status sebelumnya 'ditolak', ubah menjadi 'draft' untuk revisi
            if ($activeKwitansi['status'] === 'ditolak') {
                $data['status.s'] = 'draft';
                $data['keterangan.s'] = ' '; // Clear keterangan penolakan
            }

            // Update existing record
            $success = $this->mysql->ubah($id, $data, $table);

            if ($success) {
                $message = 'Data kwitansi berhasil disimpan';
                if ($activeKwitansi['status'] === 'ditolak') {
                    $message = 'Revisi kwitansi berhasil disimpan. Status kembali ke Draft.';
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => $message
                ]);
            } else {
                throw new Exception('Gagal menyimpan data kwitansi');
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
     * Handle manual penyedia input (create new penyedia)
     */
    public function createPenyedia()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];

            // Validate CSRF
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                throw new Exception('Invalid CSRF token');
            }

            // Validate required fields
            if (empty($_POST['nama_perusahaan'])) {
                throw new Exception('Nama perusahaan is required');
            }
            if (empty($_POST['nama_penyedia'])) {
                throw new Exception('Nama penyedia is required');
            }

            // Prepare data for new penyedia
            $data = [
                'nama_perusahaan.s' => $this->mysql->sqlEscape($_POST['nama_perusahaan']),
                'nama_penyedia.s' => $this->mysql->sqlEscape($_POST['nama_penyedia']),
                'jabatan.s' => $this->mysql->sqlEscape($_POST['jabatan'] ?? '')
            ];

            // Insert new penyedia
            $success = $this->mysql->simpan($data, 'penyedia');

            if ($success) {
                $newPenyediaId = $this->mysql::$lastId;
                $penyediaData = $this->getPenyediaById($newPenyediaId);

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Penyedia berhasil ditambahkan',
                    'penyedia_id' => $newPenyediaId,
                    'penyedia_data' => [
                        'id' => $newPenyediaId,
                        'nama_perusahaan' => $_POST['nama_perusahaan'],
                        'nama_penyedia' => $_POST['nama_penyedia'],
                        'jabatan' => $_POST['jabatan'] ?? ''
                    ]
                ]);
            } else {
                throw new Exception('Gagal menambahkan penyedia');
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
     * Submit kwitansi untuk verifikasi
     */
    public function submit()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id);

            if (!$activeKwitansi) {
                throw new Exception('Tidak ada kwitansi aktif');
            }

            if (!in_array($activeKwitansi['status'], ['draft', 'ditolak'])) {
                throw new Exception('Kwitansi sudah disubmit');
            }

            // Cek kelengkapan data (SEMUA FIELD WAJIB)
            $requiredFields = [
                'uraian_spj', 'total_uang', 'triwulan', 'tgl_kwitansi',
                'pejabat_id', 'bendahara_id'
            ];

            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($activeKwitansi[$field])) {
                    $missingFields[] = $field;
                }
            }

            // Untuk jenis penyedia, cek juga penyedia_id
            if ($activeKwitansi['jenis_kwitansi'] === 'penyedia' && empty($activeKwitansi['penyedia_id'])) {
                $missingFields[] = 'penyedia_id';
            }

            if (!empty($missingFields)) {
                throw new Exception('Data kwitansi belum lengkap. Field yang harus diisi: ' . implode(', ', $missingFields));
            }

            // Cek kelengkapan dokumen
            $docCompleteness = $this->checkDocumentCompleteness(
                $activeKwitansi['Id'],
                $activeKwitansi['rekening_id']
            );

            if (!$docCompleteness['is_complete']) {
                throw new Exception(
                    'Dokumen belum lengkap. ' .
                    $docCompleteness['total_uploaded'] . ' dari ' .
                    $docCompleteness['total_required'] . ' dokumen sudah diupload.'
                );
            }

            // Update status menjadi verifikasi
            $data = [
                'status.s' => 'verifikasi'
            ];

            $success = $this->mysql->ubah($activeKwitansi['Id'], $data, 'kwitansi');

            if ($success) {
                $message = 'Kwitansi berhasil disubmit untuk verifikasi';
                if ($activeKwitansi['status'] === 'ditolak') {
                    $message = 'Revisi kwitansi berhasil disubmit ulang untuk verifikasi';
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => $message
                ]);
            } else {
                throw new Exception('Gagal submit kwitansi');
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
     * Get dokumen list via AJAX
     */
    public function getDokumen()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id);

            if (!$activeKwitansi) {
                throw new Exception('Tidak ada kwitansi aktif');
            }

            $requiredDocuments = $this->getRequiredDocuments($activeKwitansi['rekening_id'], $activeKwitansi['Id']);

            echo json_encode([
                'status' => 'success',
                'data' => $requiredDocuments
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
     * Upload dokumen
     */
    public function uploadDokumen()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id);

            if (!$activeKwitansi) {
                throw new Exception('Tidak ada kwitansi aktif');
            }

            if (!in_array($activeKwitansi['status'],['draft','ditolak'])) {
                throw new Exception('Kwitansi sudah disubmit, tidak bisa upload dokumen');
            }

            if (empty($_POST['syarat_id'])) {
                throw new Exception('Syarat ID tidak valid');
            }

            if (empty($_FILES['dokumen_file'])) {
                throw new Exception('File dokumen tidak ditemukan');
            }

            $syarat_id = intval($_POST['syarat_id']);
            $kwitansi_id = $activeKwitansi['Id'];
            $file = $_FILES['dokumen_file'];

            // Validasi file
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error uploading file: ' . $file['error']);
            }

            if ($file['size'] > 10 * 1024 * 1024) {
                throw new Exception('File terlalu besar. Maksimal 10MB');
            }

            $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Format file tidak didukung. Hanya PDF, JPEG, PNG yang diizinkan');
            }

            // Generate unique filename
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = 'dokumen_' . $kwitansi_id . '_syarat_' . $syarat_id . '_' . time() . '.' . $fileExtension;
            $uploadPath = 'asets/img/dokumen/' . $fileName;

            // Create upload directory if not exists
            if (!is_dir('asets/img/dokumen')) {
                mkdir('asets/img/dokumen', 0777, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Gagal menyimpan file');
            }

            // Get syarat name
            $syaratResult = $this->mysql->ambilSatuId($syarat_id, 'syarat_ver', 'i');
            $syaratName = $syaratResult ? $syaratResult->fetch_assoc()['nama_syarat'] : 'Dokumen';
 
            // Check if document already exists
            $where = [
                'kwitansi_id' => '=',
                'syarat_id' => '='
            ];
            $whereval = [
                'i.' . $kwitansi_id,
                'i.' . $syarat_id
            ];

            $existingResult = $this->mysql->ambilKondisi($where, $whereval, 'AND', 'dokumentasi');

            if ($existingResult && $existingResult->num_rows > 0) {
                // Update existing document
                $existingDoc = $existingResult->fetch_assoc();
                $data = [
                    'nama_dokumen.s' => $syaratName,
                    'file.s' => $fileName,
                    'uploaded_at.s' => date('Y-m-d H:i:s')
                ];
                $success = $this->mysql->ubah($existingDoc['id'], $data, 'dokumentasi');
            } else {
                // Insert new document
                $data = [
                    'nama_dokumen.s' => $syaratName,
                    'file.s' => $fileName,
                    'kwitansi_id.i' => $kwitansi_id,
                    'syarat_id.i' => $syarat_id,
                    'uploaded_at.s' => date('Y-m-d H:i:s')
                ];
                $success = $this->mysql->simpan($data, 'dokumentasi');
            }

            if ($success) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Dokumen berhasil diupload',
                    'file_name' => $fileName
                ]);
            } else {
                throw new Exception('Gagal menyimpan data dokumen');
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
     * Hapus dokumen
     */
    public function hapusDokumen()
    {
        try {
            $pegawai_id = $_SESSION['user_id'];
            $activeKwitansi = $this->getActiveKwitansi($pegawai_id);

            if (!$activeKwitansi) {
                throw new Exception('Tidak ada kwitansi aktif');
            }

            if (!in_array($activeKwitansi['status'],['draft','ditolak'])) {
                throw new Exception('Kwitansi sudah disubmit, tidak bisa hapus dokumen');
            }

            if (empty($_POST['dokumen_id'])) {
                throw new Exception('Dokumen ID tidak valid');
            }

            $dokumen_id = intval($_POST['dokumen_id']);

            // Get file info before delete
            $fileResult = $this->mysql->ambilSatuId($dokumen_id, 'dokumentasi', 'i');

            if (!$fileResult || $fileResult->num_rows === 0) {
                throw new Exception('Dokumen tidak ditemukan');
            }

            $fileInfo = $fileResult->fetch_assoc();

            // Verify ownership
            if ($fileInfo['kwitansi_id'] != $activeKwitansi['Id']) {
                throw new Exception('Akses ditolak - dokumen tidak terkait dengan kwitansi aktif');
            }

            // Delete from database
            $success = $this->mysql->hapus($_POST['dokumen_id'], 'dokumentasi');

            if ($success) {
                // Delete physical file
                $filePath = 'asets/img/dokumen/' . $fileInfo['file'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Dokumen berhasil dihapus'
                ]);
            } else {
                throw new Exception('Gagal menghapus dokumen');
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