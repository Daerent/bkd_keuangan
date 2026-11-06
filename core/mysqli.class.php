<?php 
class Mysql{
    private static $table, $mysqli,
    $listTable = array(),
    $listColumn = array(),
    $simbolmysqli = array('*', '%', '>', '<', '=', '!', '-', '|', '/', '&', '!=', '>=', '<=', '+=', '-=', '^-=', '/=', '*=',
    '%=', '&=', '|*=', '<>', '=='),
    $logicmysqli = array('ALL', 'AND', 'ANY', 'BETWEEN', 'EXISTS', 'IN', 'LIKE', 'NOT', 'OR', 'SOME'),
    $simbol = array('~'=>'', '`'=>'', '!'=>'', '#'=>'', '$'=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', ')'=>'', '-'=>'', '+'=>'', '='=>'', '{'=>'', '}'=>'', '['=>'', ']'=>'', ':'=>'', ';'=>'', '"'=>'', "'"=>'', '<'=>'', '>'=>'', ','=>'', '.'=>'', '?'=>'', '/'=>'', '|'=>'', '\\'=>'', '('=>''),
    $backup = false,
    $mode = 'd',
    $db = null, 
    $enmethod = null;
    public static $lastId = null;
    ## Constructor class MySQL - melakukan inisialisasi dan validasi keamanan
    function __construct(){
        self::$enmethod = "AES-256-CBC";
        if(isset($_GET[0]) && strtolower($_GET[0]) === 'core'){
            die("HAYOoo.. mau ngapain? (-_- ')");
        }
        elseif(!isset($_COOKIE['spider']) && !isset($_SESSION['F_SND'])){
            die("HAYOoo.. mau ngapain? (-_- ')");
        }
        else{
            require __DIR__.'/../pengaturan.php';
            if(isset($_PENGATURAN['MODE'])){
                self::$mode = strtolower($_PENGATURAN['MODE'][0]);
            }
        }
    }
    ## Mengembalikan koneksi mysqli yang sedang aktif
    #@return mysqli|null - objek koneksi mysqli atau null jika tidak ada
    public static function getCon(){
        if(!empty(self::$mysqli)){
            return self::$mysqli;
        }
        return null;
    }
        ## Setting koneksi mysqli dan caching daftar table
    #@param array $mysqli - array berisi koneksi database ['con'=>objek_mysqli, 'name'=>'nama_database']
    # Contoh penggunaan:
    # $this->mysql->setCon($this->database->mysqli);
    # $this->mysql->setCon($this->database->getMysqli(1));
    
    public function setCon($mysqli){
        try{
            if(!empty($mysqli)){
                if(isset($mysqli['con'])){
                    self::$mysqli = $mysqli['con']['con'];
                    self::$db = $mysqli['con']['name'];
                    $this->simpanTable(self::$mysqli);
                }
                else{
                    throw new Exception('Error : koneksi mysqli harus dari class database | $this->database->mysqli | $this->database->getMysqli($ke) ! ');
                }
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
     ## Memproses data bertipe (typed data) untuk prepared statement
    #@param array $data - data input format ['kolom.tipe'=>'value']
    #@param array &$rows - reference untuk menyimpan nama kolom
    #@param array &$isidt - reference untuk menyimpan nilai data
    #@param string &$tipedt - reference untuk menyimpan tipe data binding
    #@param string $ermsg - pesan error jika format salah
    # Contoh penggunaan internal:
    # $kolom = []; $nilai = []; $tipe = '';
    # $this->bongkar(['nama.s'=>'John', 'umur.i'=>25], $kolom, $nilai, $tipe, 'Format data salah');
    private function bongkar(array $data, array &$rows, array &$isidt, string &$tipedt, $ermsg){
        foreach($data as $i => $v){
            $head = explode('.', $i);
            if(sizeof($head) == 2){
                switch(strtolower($head[1])){
                    # tipe data adalah integer
                    case'i':
                        $d = $this->sqlEscape($v);
                        array_push($rows, $head[0]);
                        array_push($isidt, $d);
                        $tipedt .= 'i';
                        break;
                    # tipe data adalah string
                    case 's':
                        $d = $this->sqlEscape($v);
                        array_push($rows, $head[0]);
                        array_push($isidt, $d);
                        $tipedt .= 's';
                        break;
                    # tipe data adalah blob
                    case 'b':
                        array_push($rows, $head[0]);
                        array_push($isidt, $this->encodeBlob($v));
                        $tipedt .= 's';
                        break;
                    # tipe data adalah float
                    case 'd':
                        $d = $this->sqlEscape($v);
                        array_push($rows, $head[0]);
                        array_push($isidt, $d);
                        $tipedt .= 'd';
                        break;
                    case 'j':
                        $d = json_encode($v);
                        array_push($rows, $head[0]);
                        array_push($isidt, $d);
                        $tipedt .= 's';
                        break;
                }
            }
            else{
                throw new Exception($ermsg);
            }
        }
    }

     ## Menyimpan data baru ke dalam tabel database
    #@param array $data - data yang akan disimpan format ['kolom.tipe'=>'value']
    #@param string|null $table - nama tabel (optional jika sudah diset di class)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return bool|string - true jika berhasil, error message jika gagal
    # Contoh penggunaan:
    # $data = ['nama.s'=>'John', 'email.s'=>'john@example.com', 'umur.i'=>25];
    # $this->mysql->simpan($data, 'users');
    public function simpan($data, $table = null, $mysqli = null){   
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            
            $isidata = array($table);
            
            if($this->cekKosong($isidata) === true){
                if(is_array($data)){
                    $jumdt = count($data);
                    $tanya = array_fill(0, $jumdt, '?');
                    $tanya = implode(', ', $tanya);
                    $rows = array();
                    $isidt = array();
                    $tipedt = '';
                    $ermsg = 'Error: key data pada fungsi simpan harus berupa nama.tipe !';
                    $this->bongkar($data, $rows, $isidt, $tipedt, $ermsg);
                    if(is_string($table)){
                        if(in_array($table, self::$listTable)){
                            $this->cekKolom($table);
                            $jumrows = count($rows);
                            if(sizeof(self::$listColumn) > 0){
                                $banding = array_intersect(self::$listColumn, $rows);
                                if(sizeof($banding) != sizeof($rows)){
                                    $kol = array_diff($rows, self::$listColumn);
                                    $nt = implode(', ', $kol);
                                    throw new Exception("Error : kolom pada tabel ' $table ' tidak sesuai ! Kolom ' $nt ' tidak ada pada tabel ' $table ' !");
                                }
                            }
                            else{
                                throw new Exception('Error : tabel '.$table.' tidak memiliki kolom !');
                            }
                            $header = implode(', ', $rows);
                            $simpan = $mysqli->prepare("INSERT INTO $table($header) VALUES($tanya)");
                            $simpan-> bind_param($tipedt, ...$isidt);
                            $simpan->execute();
                            if($simpan->affected_rows == 1){
                                self::$lastId = $simpan->insert_id;
                                return true;
                            }
                            else{
                                return $mysqli->error;
                            }
                        }
                        else{
                            throw new Exception('Error : tabel tidak diketahui !');
                        }
                    }
                    else{
                        throw new Exception('Error : parameter table pada simpan harus berupa string !');
                    }
                }
                else{
                    throw new Exception('Error: parameter data pada fungsi simpan harus array !<br>Tipe yang dimasukan adalah "'.gettype($data).'"');
                }
            }
            else{
                throw new Exception('Error: parameter simpan tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
     ## Menghapus data berdasarkan ID dari tabel
    #@param mixed $id - ID data yang akan dihapus (bisa string atau array of IDs)
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return bool|array - true/false atau array of boolean untuk multiple ID
    # Contoh penggunaan:
    # $this->mysql->hapus(5, 'users'); // hapus single ID
    # $this->mysql->hapus([1,2,3], 'users'); // hapus multiple ID
    public function hapus($id, $table = null, $mysqli = null){
        $berhasil = false;
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            if(is_array($table)){
                throw new Exception('Error : tipe parameter table harus berupa string !');
            }
            $isi = array($id, $table);
            if($this->cekKosong($isi) === true){
                if(is_array($id) && is_string($table)){
                    $berhasil = array();
                    foreach($id as $i => $v){
                        if(is_int($i)){
                            if(in_array($table, self::$listTable)){
                                $d = $this->sqlEscape($v);
                                $cek = $this->cekId($d, $table);
                                if($cek === true){
                                    $hapus = $mysqli->prepare("DELETE FROM $table WHERE id = ?");
                                    $hapus->bind_param('s', $d);
                                    if($hapus->execute()){
                                        if($hapus->affected_rows == 1){
                                            array_push($berhasil, true);
                                        }
                                        else{
                                            array_push($berhasil, false);
                                        }
                                    }
                                    else{
                                        throw new Exception('Error : '.$mysqli->error);
                                    }
                                }
                                else{
                                    throw new Exception("Error : '$d' tidak ada pada tabel '$table' !");
                                }
                            }
                            else{
                                throw new Exception('Error : tabel tidak diketahui !');
                            }
                        }
                        else{
                            throw new Exception('Error : parameter id pada hapus harus berupa array tunggal !<br> tipe yang dimasukan adalah "'.gettype($i).'"');
                        }
                    }
                }
                elseif(is_string($id) && is_string($table)){
                    if(in_array($table, self::$listTable)){
                        $d = $this->sqlEscape($id);
                        if($this->cekId($d, $table) === true){
                            $hapus = $mysqli->prepare("DELETE FROM $table WHERE id = ?");
                            $hapus->bind_param('s', $d);
                            if($hapus->execute()){
                                if($hapus->affected_rows == 1){
                                    $berhasil = true;
                                }
                                else{
                                    $berhasil = false;
                                }
                            }
                            else{
                                throw new Exception('Error : '.$mysqli->error);
                            }
                        }
                        else{
                            throw new Exception("Error : '$id' tidak ditemukan pada tabel '$table' !");
                        }
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                else{
                    throw new Exception('Error : tipe '.gettype($id).' tidak didukung pada fungsi hapus !');
                }
            }
            else{
                throw new Exception('Error : parameter hapus tidak lengkap !');
            }
            return $berhasil;
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }  
    }
      ## Menghapus data berdasarkan kondisi WHERE yang kompleks
    #@param array|null $where - kondisi WHERE format ['kolom'=>'operator']
    #@param array|null $whereval - nilai kondisi format ['tipeData.nilai']
    #@param array|string|null $logic - operator logika (AND/OR) antara kondisi
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return bool - true jika berhasil, false jika gagal
    # Contoh penggunaan:
    # $where = ['umur'=>'>=', 'status'=>'='];
    # $whereval = ['i.25', 's.aktif'];
    # $logic = 'AND';
    # $this->mysql->hapusKondisi($where, $whereval, $logic, 'users');
    public function hapusKondisi($where = null, $whereval = null, $logic = null, $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            if(is_array($table)){
                throw new Exception('Error : tipe parameter table harus berupa string !');
            }
            $isicek = array($where, $whereval, $table);
            if($this->cekKosong($isicek) === true){
                if(is_array($where) && is_array($whereval)){ 
                    if(is_string($table)){
                        $this->cekKolom($table);

                        $jumwhere = sizeof($where);
                        if(is_string($logic) && $jumwhere > 0){
                            $islog = strtoupper($logic);
                            $logic = array_fill(0, ($jumwhere-1), $islog);
                        }
                        if(is_array($logic)){
                            $jumlogic = sizeof($logic);
                        }
                        else{
                            $jumlogic = 0;
                        }
                        $jumval   = sizeof($whereval);
                        $kurang = $jumwhere -1;
                        if($jumwhere == $jumval && $jumlogic == $kurang ){
                            $benar = false;
                            $wherequery = '';
                            if($jumlogic > 0 && $jumwhere > 1){
                                $x = 0;
                                # pengecekan $logic
                                $logika = array();
                                foreach($logic as $i => $v){
                                    if(is_string($i)){
                                        $benar = false;
                                        throw new Exception('Error : tipe key tidak boleh string pada parameter logic !');
                                    }
                                    else{
                                        $a = strtoupper($v);
                                        if(in_array($a, self::$logicmysqli)){
                                            array_push($logika, $this->sqlEscape($a));
                                        }
                                        else{
                                            $benar = false;
                                            throw new Exception('Error : value pada parameter logic tidak diizinkan !');
                                        }
                                    }
                                }
                                $jumlogika = count($logika);
                                # pengecekan dan penggabungan $where menjadi string
                                foreach($where as $i => $v){
                                    if((int)$i > 0){
                                        $benar = false;
                                        throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                    }
                                    else{
                                        if(in_array($v, self::$simbolmysqli)){
                                            $benar = true;
                                            if($x == $jumlogika){    
                                                $wherequery .= $i.' '.$v.' ? ';
                                            }
                                            else{
                                                $wherequery .= $i.' '.$v.' ? '.$logika[$x].' ';
                                            }
                                            $x++;
                                        }
                                        else{
                                            $benar = false;
                                            throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                        }
                                    }
                                }
                            }
                            else{
                                foreach($where as $i => $v){
                                    if((int)$i > 0){
                                        $benar = false;
                                        throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                    }
                                    else{
                                        if(in_array($v, self::$simbolmysqli)){
                                            $benar = true;
                                            $wherequery .= $i.' '.$v.' ? ';
                                        }
                                        else{
                                            $benar = false;
                                            throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                        }
                                    }
                                }
                            }

                            $isiparam = array();
                            $tipedt = '';
                            # pengecekan isi dari $whereval
                            foreach($whereval as $i => $v){
                                if(is_string($i)){
                                    $benar = false;
                                    throw new Exception('Error : tipe key tidak boleh string !');
                                }
                                else{
                                    $benar = true;
                                    $tp = strpos($v, '.');
                                    if($tp == 1){
                                        $dt = substr($v, 0, 1);
                                        switch(strtolower($dt)){
                                            case 'i':
                                                $tipedt .= 'i';
                                                $nl = substr($v, $tp+1);
                                                $l = $this->sqlEscape($nl);
                                                array_push($isiparam, $l);
                                                break;
                                            case 's':
                                                $tipedt .= 's';
                                                $nl = substr($v, $tp+1);
                                                $l = $this->sqlEscape($nl);
                                                array_push($isiparam, $l);
                                                break;
                                            case 'd':
                                                $tipedt .= 'd';
                                                $nl = substr($v, $tp+1);
                                                $l = $this->sqlEscape($nl);
                                                array_push($isiparam, $l);
                                                break;
                                            default:
                                                $benar = false;
                                                throw new Exception('Error : key pada parameter whereval tidak diizinkan !<br>yang diizinkan hanya "i", "s", "d"');
                                        }
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter whereval harus tipeData.data !');
                                    }
                                }
                            }
                            if(in_array($table, self::$listTable)){
                                $ubah = $mysqli->prepare("DELETE FROM $table WHERE $wherequery ");
                                $ubah-> bind_param($tipedt, ...$isiparam);
                                $ubah->execute();
                                if($ubah->affected_rows == 1){
                                    return true;
                                }
                                else{
                                    return false;
                                }
                            }
                            else{
                                throw new Exception('Error : tabel tidak diketahui !');
                            }
                        }
                    }
                    else{
                        throw new Exception('Error : parameter set pada hapusKondisi harus berupa array !');
                    }
                }
                else{
                     throw new Exception('Error : tipe "'.gettype($where).'" & "'.gettype($whereval).'" & "'.gettype($logic).'" tidak didukung pada hapusKondisi !</br> tipe yang didukung adalah "array" & "array" & "array"');
                }
            }
            else{
                throw new Exception('Error : parameter hapusKondisi tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
    ## Mengubah data berdasarkan ID dalam tabel
    #@param string $id - ID data yang akan diubah
    #@param array $set - data yang akan diubah format ['kolom.tipe'=>'nilai_baru']
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return bool|string - true jika berhasil, false jika gagal, atau pesan khusus
    # Contoh penggunaan:
    # $data = ['nama.s'=>'John Doe', 'email.s'=>'johndoe@example.com'];
    # $this->mysql->ubah(5, $data, 'users');
    public function ubah($id, $set, $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            if(is_array($table)){
                throw new Exception('Error : tipe parameter table harus berupa string !');
            }
            if(is_array($id)){
                throw new Exception('Error : tipe parameter id harus berupa string !');
            }
            $isicek = array($id, $table);
            if($this->cekKosong($isicek) === true){
                if(is_array($set) && is_string($table)){
                    $this->cekKolom($table);
                    $jumdt = count($set);
                    $isihead = array();
                    $isiparam = array();
                    $tipedt = '';
                    $ermsg = 'Error: key pada parameter set pada fungsi ubah harus berupa nama.tipe !';
                    $this->bongkar($set, $isihead, $isiparam, $tipedt, $ermsg);
                    if(sizeof(self::$listColumn) > 0){
                        $l = array_intersect(self::$listColumn, $isihead);
                        if(sizeof($l) != sizeof($isihead)){
                            throw new Exception("Error : kolom pada $table tidak sesuai !");
                        }
                    }
                    else{
                        throw new Exception("Error : tabel $table tidak memiliki kolom !");
                    }
                    $sets = '';
                    $jumset = count($isihead) - 1;
                    for($i = 0; $i < count($isihead); $i++){
                        if($i == $jumset){
                            $sets .= $isihead[$i].'= ? ';
                        }
                        else{
                            $sets .= $isihead[$i].'= ?, ';
                        }
                    }
                    $tipedt .= 's';
                    $q = $this->sqlEscape($id);
                    array_push($isiparam, $q);
                    if(in_array($table, self::$listTable)){
                        $cek = $this->cekId($q, $table);
                        if($cek === true){
                            $ubah = $mysqli->prepare("UPDATE $table SET $sets WHERE id = ? ");
                            $ubah-> bind_param($tipedt, ...$isiparam);
                            $ubah->execute();
                            if($ubah->affected_rows == 1){
                                return true;
                            }
                            elseif($ubah->affected_rows > 1){
                                return 'lebih dari 1 data yang terubah';
                            }
                            else{
                                return false;
                            }
                        }
                        else{
                            throw new Exception("Error : data tidak ditemukan pada tabel $table !");
                        }
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                else{
                    throw new Exception('Error : parameter set pada ubah harus berupa array !');
                }
            }
            else{
                throw new Exception('Error : parameter ubah tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }

    ## Mengubah data berdasarkan kondisi WHERE yang kompleks
    #@param array $set - data yang akan diubah format ['kolom.tipe'=>'nilai_baru']
    #@param array|null $where - kondisi WHERE format ['kolom'=>'operator']
    #@param array|null $whereval - nilai kondisi format ['tipeData.nilai']
    #@param array|string|null $logic - operator logika (AND/OR) antara kondisi
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return bool|string - true jika berhasil, false jika gagal, atau pesan khusus
    # Contoh penggunaan:
    # $set = ['status.s'=>'nonaktif'];
    # $where = ['last_login'=>'<', 'role'=>'='];
    # $whereval = ['d.2023-01-01', 's.user'];
    # $logic = 'AND';
    # $this->mysql->ubahKondisi($set, $where, $whereval, $logic, 'users');
    public function ubahKondisi($set, $where = null, $whereval = null, $logic = null, $table=null, $mysqli=null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            if(is_array($table)){
                throw new Exception('Error : tipe parameter table harus berupa string !');
            }
            $isicek = array($where, $whereval, $table);
            if($this->cekKosong($isicek) === true){
                if(is_array($where) && is_array($whereval)){ 
                    if(is_array($set) && is_string($table)){
                        $this->cekKolom($table);

                        $jumwhere = sizeof($where);
                        if(is_string($logic) && $jumwhere > 0){
                            $islog = strtoupper($logic);
                            $logic = array_fill(0, ($jumwhere-1), $islog);
                        }
                        if(is_array($logic)){
                            $jumlogic = sizeof($logic);
                        }
                        else{
                            $jumlogic = 0;
                        }
                        $jumval   = sizeof($whereval);
                        $kurang = $jumwhere -1;
                        if($jumwhere == $jumval && $jumlogic == $kurang ){
                            $benar = false;
                            $wherequery = '';
                            if($jumlogic > 0 && $jumwhere > 1){
                                $x = 0;
                                # pengecekan $logic
                                $logika = array();
                                foreach($logic as $i => $v){
                                    if(is_string($i)){
                                        $benar = false;
                                        throw new Exception('Error : tipe key tidak boleh string pada parameter logic !');
                                    }
                                    else{
                                        $a = strtoupper($v);
                                        if(in_array($a, self::$logicmysqli)){
                                            array_push($logika, $this->sqlEscape($a));
                                        }
                                        else{
                                            $benar = false;
                                            throw new Exception('Error : value pada parameter logic tidak diizinkan !');
                                        }
                                    }
                                }
                                $jumlogika = count($logika);
                                # pengecekan dan penggabungan $where menjadi string
                                foreach($where as $i => $v){
                                    if((int)$i > 0){
                                        $benar = false;
                                        throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                    }
                                    else{
                                        if(in_array($v, self::$simbolmysqli)){
                                            $benar = true;
                                            if($x == $jumlogika){    
                                                $wherequery .= $i.' '.$v.' ? ';
                                            }
                                            else{
                                                $wherequery .= $i.' '.$v.' ? '.$logika[$x].' ';
                                            }
                                            $x++;
                                        }
                                        else{
                                            $benar = false;
                                            throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                        }
                                    }
                                }
                            }
                            else{
                                foreach($where as $i => $v){
                                    if((int)$i > 0){
                                        $benar = false;
                                        throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                    }
                                    else{
                                        if(in_array($v, self::$simbolmysqli)){
                                            $benar = true;
                                            $wherequery .= $i.' '.$v.' ? ';
                                        }
                                        else{
                                            $benar = false;
                                            throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                        }
                                    }
                                }
                            }

                            $jumdt = count($set);
                            $isihead = array();
                            $isiparam = array();
                            $tipedt = '';
                            $ermsg = 'Error: key pada parameter set pada fungsi ubahKondisi harus berupa nama.tipe !';
                            $this->bongkar($set, $isihead, $isiparam, $tipedt, $ermsg);
                            if(sizeof(self::$listColumn) > 0){
                                $l = array_intersect(self::$listColumn, $isihead);
                                if(sizeof($l) != sizeof($isihead)){
                                    throw new Exception("Error : kolom pada $table tidak sesuai !");
                                }
                            }
                            else{
                                throw new Exception("Error : tabel $table tidak memiliki kolom !");
                            }
                            # pengecekan isi dari $whereval
                            foreach($whereval as $i => $v){
                                if(is_string($i)){
                                    $benar = false;
                                    throw new Exception('Error : tipe key tidak boleh string !');
                                }
                                else{
                                    $benar = true;
                                    $tp = strpos($v, '.');
                                    if($tp == 1){
                                        $dt = substr($v, 0, 1);
                                        switch(strtolower($dt)){
                                            case 'i':
                                                $tipedt .= 'i';
                                                $nl = substr($v, $tp+1);
                                                $l = $this->sqlEscape($nl);
                                                array_push($isiparam, $l);
                                                break;
                                            case 's':
                                                $tipedt .= 's';
                                                $nl = substr($v, $tp+1);
                                                $l = $this->sqlEscape($nl);
                                                array_push($isiparam, $l);
                                                break;
                                            case 'd':
                                                $tipedt .= 'd';
                                                $nl = substr($v, $tp+1);
                                                $l = $this->sqlEscape($nl);
                                                array_push($isiparam, $l);
                                                break;
                                            default:
                                                $benar = false;
                                                throw new Exception('Error : key pada parameter whereval tidak diizinkan !<br>yang diizinkan hanya "i", "s", "d"');
                                        }
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter whereval harus tipeData.data !');
                                    }
                                }
                            }
                            $sets = '';
                            $jumset = count($isihead) - 1;
                            for($i = 0; $i < count($isihead); $i++){
                                if($i == $jumset){
                                    $sets .= $isihead[$i].'= ? ';
                                }
                                else{
                                    $sets .= $isihead[$i].'= ?, ';
                                }
                            }
                            if(in_array($table, self::$listTable)){
                                $ubah = $mysqli->prepare("UPDATE $table SET $sets WHERE $wherequery ");
                                $ubah-> bind_param($tipedt, ...$isiparam);
                                $ubah->execute();
                                if($ubah->affected_rows == 1){
                                    return true;
                                }
                                elseif($ubah->affected_rows > 1){
                                    return 'lebih dari 1 data yang terubah';
                                }
                                else{
                                    return false;
                                }
                            }
                            else{
                                throw new Exception('Error : tabel tidak diketahui !');
                            }
                        }
                    }
                    else{
                        throw new Exception('Error : parameter set pada ubah harus berupa array !');
                    }
                }
                else{
                     throw new Exception('Error : tipe "'.gettype($where).'" & "'.gettype($whereval).'" & "'.gettype($logic).'" tidak didukung pada ubahKondisi !</br> tipe yang didukung adalah "array" & "array" & "array"');
                }
            }
            else{
                throw new Exception('Error : parameter ubah tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
    ## Memeriksa apakah ID tertentu ada dalam tabel
    #@param mixed $id - ID yang akan dicek (bisa string atau array of IDs)
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return bool|array - true/false atau array of boolean untuk multiple ID
    # Contoh penggunaan:
    # $exists = $this->mysql->cekId(5, 'users'); // cek single ID
    # $exists = $this->mysql->cekId([1,2,3], 'users'); // cek multiple ID
    public function cekId($id, $table = null, $mysqli = null){
        try{
            $ketemu = false;
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            if(is_array($table)){
                throw new Exception('Error : tipe parameter table harus berupa string !');
            }
            $isicek = array($id, $table);
            if($this->cekKosong($isicek) === true){
                if(is_array($id) && is_string($table)){
                    $ketemu = array();
                    foreach($id as $i => $v){
                        if(is_int($i)){
                            if(in_array($table, self::$listTable)){
                                $cek = $mysqli->prepare("SELECT id FROM $table WHERE id = ? ");
                                $ids = $this->sqlEscape($v);
                                $cek->bind_param('s', $ids);
                                if($cek->execute()){
                                    $hasil = $cek->get_result();
                                    if($hasil->num_rows > 0){
                                        array_push($ketemu, true);
                                    }
                                    else{
                                        array_push($ketemu, false);
                                    }
                                }
                                else{
                                    throw new Exception('Error : '.$mysqli->error);
                                }
                            }
                            else{
                                throw new Exception('Error : tabel tidak diketahui !');
                            }
                        }
                        else{
                            throw new Exception('Error : parameter id pada cekId harus berupa array tunggal !');
                        }
                    }
                }
                elseif(is_string($id) && is_string($table)){
                    if(in_array($table, self::$listTable)){
                        $id = $this->sqlEscape($id);
                        $cek = $mysqli->prepare("SELECT id FROM $table WHERE id = ? ");
                        $cek->bind_param('s', $id);
                        if($cek->execute()){
                            $hasil = $cek->get_result();
                            if($hasil->num_rows > 0){
                                $ketemu = true;
                            }
                            else{
                                $ketemu = false;
                            }
                        }
                        else{
                            throw new Exception('Error : '.$mysqli->error);
                        }
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                else{
                    throw new Exception('Error : tipe '.gettype($id).' tidak didukung pada fungsi cekId !');
                }
                return $ketemu;
            }
            else{
                throw new Exception('Error : parameter cekData tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
      ## Mengambil semua data dari tabel tanpa kondisi
    #@param string|array|null $table - nama tabel atau array of table names (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result|array - hasil query atau array of results untuk multiple tables
    # Contoh penggunaan:
    # $data = $this->mysql->ambilSemua('users'); // ambil dari single table
    # $data = $this->mysql->ambilSemua(['users','products']); // ambil dari multiple tables
    public function ambilSemua($table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            $isicek = array($table);
            if($this->cekKosong($isicek) === true){
                $hasil = null;
                if(is_array($table)){
                    $hasil = array();
                    foreach($table as $i => $v){
                        $tb = $this->sqlEscape($v);
                        if(in_array($tb, self::$listTable)){
                            $ambil = $mysqli->prepare("SELECT * FROM $tb");
                            $ambil->execute();
                            $hasil[$v] = $ambil->get_result();
                        }
                        else{
                            throw new Exception('Error : tabel tidak diketahui !');
                        }
                    }
                }
                elseif(is_string($table)){
                    $tb = $this->sqlEscape($table);
                    if(in_array($tb, self::$listTable)){
                        $ambil = $mysqli->prepare("SELECT * FROM $tb");
                        $ambil->execute();
                        $hasil = $ambil->get_result();
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                else{
                    throw new Exeption('Error : tipe table pada fungsi ambilSemua tidak diizinkan !<br> tipe yang dimasukan adalah "'.gettype($table).'", yang dibutuhkan "string" atau "array"');
                }
                return $hasil;
            }
            else{
                throw new Exception('Error : parameter pada fungsi ambil tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
     ## Mengambil semua data dari tabel dengan pengurutan (sorting)
    #@param string|null $kolom - nama kolom untuk sorting (optional, default kolom pertama)
    #@param string $ord - urutan sorting (ASC/DESC, default ASC)
    #@param string|array|null $table - nama tabel atau array of table names (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result|array - hasil query atau array of results untuk multiple tables
    # Contoh penggunaan:
    # $data = $this->mysql->ambilSemuaSort('nama', 'ASC', 'users'); // urutkan by nama ASC
    # $data = $this->mysql->ambilSemuaSort('tanggal', 'DESC', 'users'); // urutkan by tanggal DESC
    public function ambilSemuaSort($kolom = null, $ord = 'ASC', $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            $isicek = array($table);
            if($this->cekKosong($isicek) === true){
                $hasil = null;
                $kl = null;
                $this->cekKolom($table);
                if(!empty($kolom)){
                    if(in_array($kolom, self::$listColumn)){
                        $kl = $kolom;
                    }
                }
                else{
                    $kl = self::$listColumn[0];
                }
                if(is_array($table)){
                    $hasil = array();
                    foreach($table as $i => $v){
                        $tb = $this->sqlEscape($v);
                        if(in_array($tb, self::$listTable)){
                            $ambil = $mysqli->prepare("SELECT * FROM $tb ORDER BY $kl $ord");
                            $ambil->execute();
                            $hasil[$v] = $ambil->get_result();
                        }
                        else{
                            throw new Exception('Error : tabel tidak diketahui !');
                        }
                    }
                }
                elseif(is_string($table)){
                    $tb = $this->sqlEscape($table);
                    if(in_array($tb, self::$listTable)){
                        $ambil = $mysqli->prepare("SELECT * FROM $tb ORDER BY $kl $ord");
                        $ambil->execute();
                        $hasil = $ambil->get_result();
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                else{
                    throw new Exeption('Error : tipe table pada fungsi ambilSemua tidak diizinkan !<br> tipe yang dimasukan adalah "'.gettype($table).'", yang dibutuhkan "string" atau "array"');
                }
                return $hasil;
            }
            else{
                throw new Exception('Error : parameter pada fungsi ambil tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
     ## Mengambil kolom tertentu dari tabel tanpa kondisi
    #@param string|array $pilihan - nama kolom atau array of column names yang akan diambil
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result - hasil query
    # Contoh penggunaan:
    # $data = $this->mysql->ambil('nama', 'users'); // ambil single kolom
    # $data = $this->mysql->ambil(['nama','email'], 'users'); // ambil multiple kolom
    public function ambil($pilihan, $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            elseif($table == null && self::$table == null){
                throw new Exception('Error : tidak ada tabel yang dipilih !');
            }
            elseif(!is_string($table)){
                throw new Exception('Error : tipe parameter tabel harus string !');
            }
            
            $isicek = array($pilihan, $table);
            if($this->cekKosong($isicek) === true){
                if(in_array($table, self::$listTable)){
                    $this->cekKolom($table);
                    if(is_array($pilihan)){
                        $pilih = '';
                        $x = 0;
                        $a = count($pilihan) - 1;
                        foreach($pilihan as $i => $v){
                            if(is_string($i)){
                                throw new Exception('Error : parameter pilihan harus berupa array tunggal tanpa key !');
                            }
                            else{
                                if(in_array($v, self::$listColumn)){
                                    if($x === $a){
                                        $pilih .= $this->sqlEscape($v);
                                    }
                                    else{
                                        $pilih .= $this->sqlEscape($v). ', ';
                                    }
                                    $x++;
                                }
                                else{
                                    throw new Exception("Error : kolom '$v' tidak ada pada tabel '$table' !");
                                }
                            }
                        }
                        if(!empty($pilih)){
                            $ambil = $mysqli->prepare("SELECT $pilih FROM $table");
                            $ambil->execute();
                            return $ambil->get_result();
                        }
                    }
                    elseif(is_string($pilihan)){
                        if(in_array($pilihan, self::$listColumn)){
                            $pilih = $this->sqlEscape($pilihan);
                            $ambil = $mysqli->prepare("SELECT $pilih FROM $table");
                            $ambil->execute();
                            return $ambil->get_result();
                        }
                        else{
                            throw new Exception("Error : parameter pilihan '$pilihan' tidak ada dalam tabel '$table' !");
                        }
                    }
                    else{
                        throw new Exception('Error : tipe "'.gettype($pilihan).'" pada parameter pilihan tidak diizinkan !<br>tipe yang diizinkan untuk parameter pilihan : array, string ');
                    }
                }
                else{
                    throw new Exception("Error : tabel '$table' tidak diketahui !");
                }
            }
            else{
                throw new Exception('Error : masih ada yang kosong pada fungsi ambil !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }

     ## Mengambil kolom tertentu dari tabel dengan pengurutan (sorting)
    #@param string|array $pilihan - nama kolom atau array of column names yang akan diambil
    #@param string $kolom - nama kolom untuk sorting
    #@param bool $order - true untuk ASC, false untuk DESC (default true)
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result|string - hasil query atau error message
    # Contoh penggunaan:
    # $data = $this->mysql->ambilOrder('nama', 'tanggal', true, 'users'); // urutkan by tanggal ASC
    # $data = $this->mysql->ambilOrder(['nama','email'], 'id', false, 'users'); // urutkan by id DESC
    public function ambilOrder($pilihan, $kolom, $order = true, $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            elseif($table == null && self::$table == null){
                throw new Exception('Error : tidak ada tabel yang dipilih !');
            }
            elseif(!is_string($table)){
                throw new Exception('Error : tipe parameter tabel harus string !');
            }
            
            $isicek = array($pilihan, $table, $pilihan);
            
            if($this->cekKosong($isicek) === true){
                if(in_array($table, self::$listTable)){
                    $this->cekKolom($table);
                    if(is_array($pilihan)){
                        $pilih = '';
                        $x = 0;
                        $a = count($pilihan) - 1;
                        foreach($pilihan as $i => $v){
                            if(is_string($i)){
                                throw new Exception('Error : parameter pilihan harus berupa array tunggal tanpa key !');
                            }
                            else{
                                if(in_array($v, self::$listColumn)){
                                    if($x === $a){
                                        $pilih .= $this->sqlEscape($v);
                                    }
                                    else{
                                        $pilih .= $this->sqlEscape($v). ', ';
                                    }
                                    $x++;
                                }
                                else{
                                    throw new Exception("Error : kolom '$v' tidak ada pada tabel '$table' !");
                                }
                            }
                        }
                        if(!empty($pilih)){
                            if($order){
                                $orde = 'ASC';
                            }
                            else{
                                $orde = 'DESC';
                            }
                            $ambil = $mysqli->prepare("SELECT $pilih FROM $table ORDER BY $kolom $orde");
                            if($ambil->execute()){
                                return $ambil->get_result();
                            }
                            else{
                                return $mysqli->error;
                            }
                        }
                    }
                    elseif(is_string($pilihan)){
                        if(in_array($pilihan, self::$listColumn)){
                            if($order){
                                $orde = 'ASC';
                            }
                            else{
                                $orde = 'DESC';
                            }
                            $pilih = $this->sqlEscape($pilihan);
                            $ambil = $mysqli->prepare("SELECT $pilih FROM $table ORDER BY $kolom $orde");
                            if($ambil->execute()){
                                return $ambil->get_result();
                            }
                            else{
                                return $mysqli->error;
                            }
                        }
                        else{
                            throw new Exception("Error : parameter pilihan '$pilihan' tidak ada dalam tabel '$table' !");
                        }
                    }
                    else{
                        throw new Exception('Error : tipe "'.gettype($pilihan).'" pada parameter pilihan tidak diizinkan !<br>tipe yang diizinkan untuk parameter pilihan : array, string ');
                    }
                }
                else{
                    throw new Exception("Error : tabel '$table' tidak diketahui !");
                }
            }
            else{
                throw new Exception('Error : masih ada yang kosong pada fungsi ambil !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
    ## Mengambil data dengan kondisi WHERE yang kompleks
    #@param array|null $where - kondisi WHERE format ['kolom'=>'operator']
    #@param array|null $whereval - nilai kondisi format ['tipeData.nilai']
    #@param array|string|null $logic - operator logika (AND/OR) antara kondisi
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result - hasil query
    # Contoh penggunaan:
    # $where = ['umur'=>'>=', 'status'=>'='];
    # $whereval = ['i.25', 's.aktif'];
    # $logic = 'AND';
    # $data = $this->mysql->ambilKondisi($where, $whereval, $logic, 'users');
    public function ambilKondisi($where = null, $whereval = null, $logic = null, $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            elseif($table == null && self::$table == null){
                throw new Exception('Error : tidak ada table yang dipilih !');
            }
            
            if(is_array($table)){
                throw new Exception("Error : tipe tabel harus berupa string !");
            }
            $isicek = array($where, $whereval, $table);
            if($this->cekKosong($isicek) === true){
                if(is_array($where) && is_array($whereval)){ 
                    #  || (is_object($where) && is_object($whereval) && is_object($logic))
                    $jumwhere = sizeof($where);
                    if(is_string($logic) && $jumwhere > 0){
                        $islog = strtoupper($logic);
                        $logic = array_fill(0, ($jumwhere-1), $islog);
                    }
                    if(is_array($logic)){
                        $jumlogic = sizeof($logic);
                    }
                    else{
                        $jumlogic = 0;
                    }
                    $jumval   = sizeof($whereval);
                    $kurang = $jumwhere -1;
                    if($jumwhere == $jumval && $jumlogic == $kurang ){
                        $benar = false;
                        if($jumlogic > 0 && $jumwhere > 1){
                            $x = 0;
                            # pengecekan $logic
                            $logika = array();
                            foreach($logic as $i => $v){
                                if(is_string($i)){
                                    $benar = false;
                                    throw new Exception('Error : tipe key tidak boleh string pada parameter logic !');
                                }
                                else{
                                    $a = strtoupper($v);
                                    if(in_array($a, self::$logicmysqli)){
                                        array_push($logika, $this->sqlEscape($a));
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter logic tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        
                        if(is_string($table)){
                            $a = $this->sqlEscape($table);
                            $this->cekKolom($a);
                            foreach($where as $iw => $vw){
                                if(!in_array($iw, self::$listColumn)){
                                    throw new Exception("Error : kolom '$iw' tidak ada pada tabel '$a' !");
                                }
                            }
                        }
                        $wherequery = '';
                        if($jumlogic > 0 && $jumwhere > 1){
                            $jumlogika = count($logika);
                            # pengecekan dan penggabungan $where menjadi string
                            foreach($where as $i => $v){
                                if((int)$i > 0){
                                    $benar = false;
                                    throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                }
                                else{
                                    if(in_array($v, self::$simbolmysqli)){
                                        $benar = true;
                                        if($x == $jumlogika){    
                                            $wherequery .= $i.' '.$v.' ? ';
                                        }
                                        else{
                                            $wherequery .= $i.' '.$v.' ? '.$logika[$x].' ';
                                        }
                                        $x++;
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        else{
                            foreach($where as $i => $v){
                                if((int)$i > 0){
                                    $benar = false;
                                    throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                }
                                else{
                                    if(in_array($v, self::$simbolmysqli)){
                                        $benar = true;
                                        $wherequery .= $i.' '.$v.' ? ';
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        $isiparam = array();
                        $tipedt = '';
                        # pengecekan isi dari $whereval
                        foreach($whereval as $i => $v){
                            if(is_string($i)){
                                $benar = false;
                                throw new Exception('Error : tipe key tidak boleh string !');
                            }
                            else{
                                $benar = true;
                                $tp = strpos($v, '.');
                                if($tp == 1){
                                    $dt = substr($v, 0, 1);
                                    switch(strtolower($dt)){
                                        case 'i':
                                            $tipedt .= 'i';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        case 's':
                                            $tipedt .= 's';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        case 'd':
                                            $tipedt .= 'd';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        default:
                                            $benar = false;
                                            throw new Exception('Error : key pada parameter whereval tidak diizinkan !<br>yang diizinkan hanya "i", "s", "d"');
                                    }
                                }
                                else{
                                    $benar = false;
                                    throw new Exception('Error : value pada parameter whereval harus tipeData.data !');
                                }
                            }
                        }
                        
                        # proses pengambilan data
                        if($benar === true){
                            if(is_string($table)){
                                $tb = $this->sqlEscape($table);
                                if(in_array($tb, self::$listTable)){
                                    $ambil = $mysqli->prepare("SELECT * FROM $tb WHERE $wherequery ");
                                    $ambil->bind_param($tipedt, ...$isiparam);
                                    $ambil->execute();
                                    $hasil = $ambil->get_result();
                                    $where = null;
                                    $whereval = null;
                                    $logic = null;
                                    $table = null;
                                    return $hasil;
                                }
                                else{
                                    throw new Exception('Error : tabel tidak diketahui !');
                                }
                            }
                            else{
                                throw new Exception('Error : tipe '.gettype($table).' tidak diizinkan untuk parameter table !');
                            }
                            
                        }
                        else{
                            throw new Exception('Error : masih ada yang salah pada fungsi ambilKondisi !');
                        }
                        
                    }
                    else{
                        throw new Exception('Error : jumlah parameter "where" & "whereval" & "logic" tidak sesuai !');
                    }
                }
                else{
                    throw new Exception('Error : tipe "'.gettype($where).'" & "'.gettype($whereval).'" & "'.gettype($logic).'" tidak didukung pada ambilKondisi !</br> tipe yang didukung adalah "array" & "array" & "array"');
                }
            }
            else{
                throw new Exception('Error : parameter ambilKondisi tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
       ## Mengambil data dengan kondisi WHERE dan pengurutan (sorting)
    #@param array|null $where - kondisi WHERE format ['kolom'=>'operator']
    #@param array|null $whereval - nilai kondisi format ['tipeData.nilai']
    #@param array|string|null $logic - operator logika (AND/OR) antara kondisi
    #@param string $kolom - kolom untuk sorting
    #@param bool $order - true untuk ASC, false untuk DESC (default true)
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result - hasil query
    # Contoh penggunaan:
    # $where = ['umur'=>'>=', 'status'=>'='];
    # $whereval = ['i.25', 's.aktif'];
    # $logic = 'AND';
    # $data = $this->mysql->ambilKondisiOrder($where, $whereval, $logic, 'nama', true, 'users');
    public function ambilKondisiOrder($where = null, $whereval = null, $logic = null, $kolom = null, $order=true, $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            elseif($table == null && self::$table == null){
                throw new Exception('Error : tidak ada table yang dipilih !');
            }
            
            if(is_array($table)){
                throw new Exception("Error : tipe tabel harus berupa string !");
            }
            $isicek = array($where, $whereval, $table, $kolom);
            if($this->cekKosong($isicek) === true){
                if(is_array($where) && is_array($whereval)){ 
                    #  || (is_object($where) && is_object($whereval) && is_object($logic))
                    $jumwhere = sizeof($where);
                    if(is_string($logic) && $jumwhere > 0){
                        $islog = strtoupper($logic);
                        $logic = array_fill(0, ($jumwhere-1), $islog);
                    }
                    if(is_array($logic)){
                        $jumlogic = sizeof($logic);
                    }
                    else{
                        $jumlogic = 0;
                    }
                    $jumval   = sizeof($whereval);
                    $kurang = $jumwhere -1;
                    if($jumwhere == $jumval && $jumlogic == $kurang ){
                        $benar = false;
                        if($jumlogic > 0 && $jumwhere > 1){
                            $x = 0;
                            # pengecekan $logic
                            $logika = array();
                            foreach($logic as $i => $v){
                                if(is_string($i)){
                                    $benar = false;
                                    throw new Exception('Error : tipe key tidak boleh string pada parameter logic !');
                                }
                                else{
                                    $a = strtoupper($v);
                                    if(in_array($a, self::$logicmysqli)){
                                        array_push($logika, $this->sqlEscape($a));
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter logic tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        
                        if(is_string($table)){
                            $a = $this->sqlEscape($table);
                            $this->cekKolom($a);
                            foreach($where as $iw => $vw){
                                if(!in_array($iw, self::$listColumn)){
                                    throw new Exception("Error : kolom '$iw' tidak ada pada tabel '$a' !");
                                }
                            }
                        }
                        $wherequery = '';
                        if($jumlogic > 0 && $jumwhere > 1){
                            $jumlogika = count($logika);
                            # pengecekan dan penggabungan $where menjadi string
                            foreach($where as $i => $v){
                                if((int)$i > 0){
                                    $benar = false;
                                    throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                }
                                else{
                                    if(in_array($v, self::$simbolmysqli)){
                                        $benar = true;
                                        if($x == $jumlogika){    
                                            $wherequery .= $i.' '.$v.' ? ';
                                        }
                                        else{
                                            $wherequery .= $i.' '.$v.' ? '.$logika[$x].' ';
                                        }
                                        $x++;
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        else{
                            foreach($where as $i => $v){
                                if((int)$i > 0){
                                    $benar = false;
                                    throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                }
                                else{
                                    if(in_array($v, self::$simbolmysqli)){
                                        $benar = true;
                                        $wherequery .= $i.' '.$v.' ? ';
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        $isiparam = array();
                        $tipedt = '';
                        # pengecekan isi dari $whereval
                        foreach($whereval as $i => $v){
                            if(is_string($i)){
                                $benar = false;
                                throw new Exception('Error : tipe key tidak boleh string !');
                            }
                            else{
                                $benar = true;
                                $tp = strpos($v, '.');
                                if($tp == 1){
                                    $dt = substr($v, 0, 1);
                                    switch(strtolower($dt)){
                                        case 'i':
                                            $tipedt .= 'i';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        case 's':
                                            $tipedt .= 's';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        case 'd':
                                            $tipedt .= 'd';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        default:
                                            $benar = false;
                                            throw new Exception('Error : key pada parameter whereval tidak diizinkan !<br>yang diizinkan hanya "i", "s", "d"');
                                    }
                                }
                                else{
                                    $benar = false;
                                    throw new Exception('Error : value pada parameter whereval harus tipeData.data !');
                                }
                            }
                        }
                        
                        # proses pengambilan data
                        if($benar === true){
                            if(is_string($table)){
                                $tb = $this->sqlEscape($table);
                                if(in_array($tb, self::$listTable)){
                                    if($order){
                                        $perintah = "SELECT * FROM $tb WHERE $wherequery ORDER BY $kolom ASC ";
                                    }
                                    else{
                                        $perintah = "SELECT * FROM $tb WHERE $wherequery ORDER BY $kolom DESC ";
                                    }
                                    $ambil = $mysqli->prepare($perintah);
                                    $ambil->bind_param($tipedt, ...$isiparam);
                                    $ambil->execute();
                                    $hasil = $ambil->get_result();
                                    $where = null;
                                    $whereval = null;
                                    $logic = null;
                                    $table = null;
                                    return $hasil;
                                }
                                else{
                                    throw new Exception('Error : tabel tidak diketahui !');
                                }
                            }
                            else{
                                throw new Exception('Error : tipe '.gettype($table).' tidak diizinkan untuk parameter table !');
                            }
                            
                        }
                        else{
                            throw new Exception('Error : masih ada yang salah pada fungsi ambilKondisiOrder !');
                        }
                        
                    }
                    else{
                        throw new Exception('Error : jumlah parameter "where" & "whereval" & "logic" tidak sesuai !');
                    }
                }
                else{
                    throw new Exception('Error : tipe "'.gettype($where).'" & "'.gettype($whereval).'" & "'.gettype($logic).'" tidak didukung pada ambilKondisiOrder !</br> tipe yang didukung adalah "array" & "array" & "array"');
                }
            }
            else{
                throw new Exception('Error : parameter ambilKondisiOrder tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    
    ## Mengambil kolom tertentu dengan kondisi WHERE yang kompleks
    #@param string|array $pilihan - kolom yang akan diambil (bisa string atau array)
    #@param array|null $where - kondisi WHERE format ['kolom'=>'operator']
    #@param array|null $whereval - nilai kondisi format ['tipeData.nilai']
    #@param array|string|null $logic - operator logika (AND/OR) antara kondisi
    #@param string|null $table - nama tabel (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result - hasil query
    # Contoh penggunaan:
    # $pilihan = ['nama','email'];
    # $where = ['status'=>'=', 'umur'=>'>'];
    # $whereval = ['s.aktif', 'i.25'];
    # $logic = 'AND';
    # $data = $this->mysql->ambilBeberapa($pilihan, $where, $whereval, $logic, 'users');
    public function ambilBeberapa($pilihan, $where = null, $whereval = null, $logic = null, $table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            if(is_string($table) && !empty($table) && !in_array($table, self::$listTable)){
                throw new Exception("Error : tabel '$table' tidak diketahui !");
            }
            if(count($where) == 1 && empty($logic)){
                $isicek = array($where, $table);
            }
            else{
                $isicek = array($pilihan, $where, $table);
            }
            if($this->cekKosong($isicek) === true){
                if(is_array($where) && is_array($whereval)){ #  || (is_object($where) && is_object($whereval) && is_object($logic))
                    $jumwhere = count($where);
                    if(is_string($logic) && !empty($logic)){
                        $logic = array_fill(0, ($jumwhere-1), $logic);
                        $jumlogic = count($logic);
                    }
                    elseif(is_array($logic)){
                        $jumlogic = count($logic);
                    }
                    else{
                        $jumlogic = 0;
                    }
                    $jumval   = count($whereval);
                    if($jumwhere == $jumval && ($jumwhere - 1) == $jumlogic){
                        $benar = false;
                        $x = 0;
                        # pengecekan $logic
                        if($jumlogic > 0 && $jumwhere > 1){
                            $logika = array();
                            foreach($logic as $i => $v){
                                if(is_string($i)){
                                    $benar = false;
                                    throw new Exception('Error : tipe key tidak boleh berupa string pada parameter logic !');
                                }
                                else{
                                    $a = strtoupper(trim($v));
                                    if(in_array($a, self::$logicmysqli)){
                                        $w = $this->sqlEscape($a);
                                        array_push($logika, $w);
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter logic tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        
                        if(is_array($table)){
                            foreach($table as $i => $v){
                                if(is_string($i)){
                                    throw new Exception("Error : parameter tabel harus array tunggal !");
                                }
                                else{
                                    $a = $this->sqlEscape($v);
                                    $this->cekKolom($a);
                                    foreach($where as $iw => $vw){
                                        if(!in_array($iw, self::$listColumn)){
                                            throw new Exception("Error : kolom '$iw' tidak ada pada tabel '$a' !");
                                        }
                                    }
                                }
                            }
                        }
                        elseif(is_string($table)){
                            $a = $this->sqlEscape($table);
                            $this->cekKolom($a);
                            foreach($where as $iw => $vw){
                                if(!in_array($iw, self::$listColumn)){
                                    throw new Exception("Error : kolom '$iw' tidak ada pada tabel '$a' !");
                                }
                            }
                        }
                        $wherequery = '';
                        if($jumlogic > 0 && $jumwhere > 1){
                            $jumlogika = count($logika);
                            # pengecekan dan penggabungan $where menjadi string
                            foreach($where as $i => $v){
                                if((int)$i > 0){
                                    $benar = false;
                                    throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                }
                                else{
                                    if(in_array($v, self::$simbolmysqli)){
                                        $benar = true;
                                        if($x == $jumlogika){    
                                            $wherequery .= $i.' '.$v.' ? ';
                                        }
                                        else{
                                            $wherequery .= $i.' '.$v.' ? '.$logika[$x].' ';
                                        }
                                        $x++;
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        else{
                            # pengecekan dan penggabungan $where menjadi string
                            foreach($where as $i => $v){
                                if((int)$i > 0){
                                    $benar = false;
                                    throw new Exception('Error : key pada parameter where tidak boleh ada angka !');
                                }
                                else{
                                    if(in_array($v, self::$simbolmysqli)){
                                        $benar = true;
                                        $wherequery .= $i.' '.$v.' ? ';
                                    }
                                    else{
                                        $benar = false;
                                        throw new Exception('Error : value pada parameter where tidak diizinkan !');
                                    }
                                }
                            }
                        }
                        $isiparam = array();
                        $tipedt = '';
                        # pengecekan isi dari $whereval
                        foreach($whereval as $i => $v){
                            if(is_string($i)){
                                $benar = false;
                                throw new Exception('Error : tipe key tidak boleh string !');
                            }
                            else{
                                $benar = true;
                                $tp = strpos($v, '.');
                                if($tp == 1){
                                    $dt = substr($v, 0, 1);
                                    switch(strtolower($dt)){
                                        case 'i':
                                            $tipedt .= 'i';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        case 's':
                                            $tipedt .= 's';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        case 'd':
                                            $tipedt .= 'd';
                                            $nl = substr($v, $tp+1);
                                            $l = $this->sqlEscape($nl);
                                            array_push($isiparam, $l);
                                            break;
                                        default:
                                            throw new Exception('Error : key pada parameter whereval tidak diizinkan !<br>yang diizinkan hanya "i", "s", "d"');
                                    }
                                }
                                else{
                                    throw new Exception('Error : value pada parameter whereval harus tipeData.data !');
                                }
                            }
                        }
                        $pilih = '';
                        $x = 0;
                        # pengecekan $pilihan
                        if(is_array($pilihan)){
                            $p = array_values($pilihan);
                            foreach($p as $i => $v){
                                if(!in_array($v, self::$listColumn)){
                                    throw new Exception("Kolom $v tidak ada pada tabel $table !");
                                }
                            }
                            $pilih = implode(', ', $p);
                        }
                        elseif(is_string($pilihan)){
                            if(!in_array($pilihan, self::$listColumn)){
                                throw new Exception("Kolom $v tidak ada pada tabel $table !");
                            }
                            $pilih = $this->sqlEscape($pilihan);
                        }
                        else{
                            throw new Exception('Error : tipe '.gettype($pilihan).' tidak diizinkan untuk parameter pilihan !');
                        }
                        
                        # proses pengambilan data
                        if($benar === true){
                            if(is_string($table)){
                                $tb = $this->sqlEscape($table);
                                if(in_array($tb, self::$listTable)){
                                    $ambil = $mysqli->prepare("SELECT $pilih FROM $tb WHERE $wherequery ");
                                    $ambil->bind_param($tipedt, ...$isiparam);
                                    $ambil->execute();
                                    $hasil = $ambil->get_result();
                                    return $hasil;
                                }
                                else{
                                    throw new Exception('Error : tabel tidak diketahui !');
                                }
                            }
                            else{
                                throw new Exception('Error : tipe '.gettype($pilihan).' tidak diizinkan untuk parameter table !');
                            }
                            
                        }
                        else{
                            throw new Exception('Error : masih ada yang salah pada fungsi ambilBeberapa !');
                        }
                    }
                    else{
                        throw new Exception('Error : jumlah parameter "where" & "whereval" & "logic" tidak sesuai !');
                    }
                }
                else{
                    throw new Exception('Error : tipe "'.gettype($pilihan).'" & "'.gettype($where).'" & "'.gettype($whereval).'" & "'.gettype($logic).'" tidak didukung pada ambilBeberapa !</br> tipe yang didukung adalah "array" & "array" & "array" & "array"');
                }
            }
            else{
                throw new Exception('Error : parameter ambilBeberapa tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }

     ## Mengambil satu data berdasarkan ID dari tabel
    #@param mixed $id - ID data yang akan diambil (bisa string, int, atau array)
    #@param string|array|null $table - nama tabel atau array of table names (optional)
    #@param string|array|null $type - tipe data ID ('s','i','d') atau array of types (optional, default 's')
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result|array - hasil query atau array of results untuk multiple tables/IDs
    # Contoh penggunaan:
    # $data = $this->mysql->ambilSatuId(5, 'users', 'i'); // ambil by integer ID
    # $data = $this->mysql->ambilSatuId('abc123', 'users'); // ambil by string ID
    # $data = $this->mysql->ambilSatuId([1,2,3], 'users'); // ambil multiple IDs
    public function ambilSatuId($id, $table = null, $type=null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }
            $isicek = array($id, $table);
            if($this->cekKosong($isicek) === true){
                if(empty($type)){
                    $type = 's';
                }
                $hasil = null;
                if(is_array($table) && is_array($id)){
                    if(count($table) === count($id)){
                        if(is_int($type) || is_object($type)){
                            throw new Exception('Error : Type id harus berupa array !');
                        }
                        if(is_array($type) && count($id) !== count($type)){
                            throw new Exception('Error : jumlah Type id tidak sama dengan jumlah id !');
                        }
                        $hasil = array();
                        $t = array();
                        foreach($table as $i => $v){
                            $a = $this->sqlEscape($v);
                            if(in_array($a, self::$listTable)){
                                array_push($t, $a);
                            }
                            else{
                                throw new Exception('Error : tabel tidak diketahui ! ');
                            }
                        }
                        
                        $d = array();
                        foreach($id as $i => $v){
                            $b = $this->sqlEscape($v);
                            array_push($d, $b);
                        }
                        for($e = 0; $e < count($t); $e++){
                            $p = $this->sqlEscape($t[$e]);
                            if(in_array($p, self::$listTable)){
                                $x = $this->sqlEscape($d[$e]);
                                $ambil = $mysqli->prepare("SELECT * FROM $t[$e] WHERE id = ? ");
                                if(is_array($type)){
                                    $ambil->bind_param($type[$e], $x);
                                }
                                else{
                                    $ambil->bind_param($type, $x);
                                }
                                $ambil->execute();
                                $hasil[$t[$e]] = $ambil->get_result();
                            }
                            else{
                                throw new Exception('Error : tabel tidak diketahui ! ');
                            }
                        }
                        return $hasil;
                    }
                    else{
                        throw new Exception('Error : jumlah parameter table tidak sama dengan jumlah parameter id !');
                    }
                }
                elseif(is_string($table) && is_string($id)){
                    if(is_array($type) || is_int($type) || is_object($type)){
                        throw new Exception('Error : Type id harus berupa string !');
                    }
                    $table = $this->sqlEscape($table);
                    if(in_array($table, self::$listTable)){
                        $id = $this->sqlEscape($id);
                        $ambil = $mysqli->prepare("SELECT * FROM $table WHERE id = ? ");
                        $ambil->bind_param($type, $id);
                        $ambil->execute();
                        $hasil = $ambil->get_result();
                        return $hasil;
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                elseif(is_string($table) && is_array($id)){
                    $t = $this->sqlEscape($table);
                    if(in_array($t, self::$listTable)){
                        $hasil = array();
                        foreach($id as $i => $v){
                            if(is_string($i)){
                                throw new Exception("Error : parameter id hanya boleh berupa array tunggal tanpa key !");
                            }
                            else{
                                $a = $this->sqlEscape($v);
                                $ambil = $mysqli->prepare("SELECT * FROM $table WHERE id = ? ");
                                $ambil->bind_param('s', $a);
                                $ambil->execute();
                                $hasil[] = $ambil->get_result();
                            }
                        }
                        return $hasil;
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                elseif(is_string($table) && is_int($id)){
                    if(is_array($type) || is_int($type) || is_object($type)){
                        throw new Exception('Error : Type id harus berupa string !');
                    }
                    $table = $this->sqlEscape($table);
                    if(in_array($table, self::$listTable)){
                        $id = $this->sqlEscape($id);
                        $ambil = $mysqli->prepare("SELECT * FROM $table WHERE id = ? ");
                        $ambil->bind_param($type, $id);
                        $ambil->execute();
                        $hasil = $ambil->get_result();
                        return $hasil;
                    }
                    else{
                        throw new Exception('Error : tabel tidak diketahui !');
                    }
                }
                else{
                    throw new Exception('Error : tipe parameter id tidak diizinkan \''.gettype($id).'\' jika parameter tabel adalah string pada fungsi ambilSatuId ! ');
                }
            }
            else{
                throw new Exception('Error : parameter ambilSatuId tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }

      ## Mengencode data BLOB menjadi string base64
    #@param mixed $data - data BLOB yang akan diencode
    #@return string - data yang sudah diencode base64
    # Contoh penggunaan internal:
    # $blobData = $this->encodeBlob($fileContent);
    protected function encodeBlob($data){
        return base64_encode($data);
    }
    
      ## Memeriksa apakah data array/object mengandung nilai kosong
    #@param mixed $data - data yang akan dicek (bisa array, object, atau string)
    #@return bool - true jika semua data tidak kosong, false jika ada yang kosong
    # Contoh penggunaan internal:
    # $isComplete = $this->cekKosong(['nama','email','umur']);
    # $isComplete = $this->cekKosong('data string');
    protected function cekKosong($data){
        $berisi = false;
        if(is_array($data) || is_object($data)){
            $isicek = array();
            foreach($data as $index => $isi){
                if(!empty($isi)){
                    array_push($isicek, true);
                }
                else{
                    array_push($isicek, false);
                }
            }
            if(in_array(false, $isicek)){
                $berisi = false;
            }
            else{
                $berisi = true;
            }
        }
        else{
            if(!empty(trim($data))){
                $berisi = true;
            }
            else{
                $berisi = false;
            }
        }
        return $berisi;
    }
    
     ## Membersihkan dan mengamankan string dari SQL injection dan karakter berbahaya
    #@param mixed $text - teks atau array yang akan dibersihkan
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mixed - teks atau array yang sudah dibersihkan
    #@throws Exception - jika tidak ada koneksi mysqli
    # Contoh penggunaan:
    # $clean = $this->mysql->sqlEscape($userInput);
    # $cleanArray = $this->mysql->sqlEscape(['nama'=>$name, 'email'=>$email]);
    public function sqlEscape($text, $mysqli = null){
        if($mysqli == null && self::$mysqli != null){
            $mysqli = self::$mysqli;
        }
        elseif($mysqli == null && self::$mysqli == null){
            throw new Exception('Error : sqlEscape butuh mysqli !');
            return false;
        }
        
        if($mysqli != null){
            if(is_array($text)){
                $isi = array();
                foreach($text as $i => $v){
                    if(empty($v)){
                        $p = $v;
                    }
                    else{
                        $p = $mysqli->real_escape_string(stripslashes(htmlspecialchars(trim($v))));
                    }
                    $isi[$i] = $p;
                }
                return $isi;
            }
            else{
                if(empty($text)){
                    return $text;
                }
                return $mysqli->real_escape_string(stripslashes(htmlspecialchars(trim($text))));
            }
        }
        else{
            throw new Exception('Error : sqlEscape butuh mysqli !');
            return false;
        }
    }
    
    ## Mengambil dan menyimpan daftar tabel dari database ke cache
    #@param mysqli $mysqli - koneksi database
    # Contoh penggunaan internal:
    # $this->simpanTable($mysqli); // caching daftar tabel
    protected static function simpanTable($mysqli){
        if($mysqli != null){
            $ambil = $mysqli->query("SHOW TABLES");
            while($hasil = $ambil->fetch_array()){
                self::$listTable[] = $hasil[0];
            }
        }
    }
    
     ## Mengambil dan menyimpan daftar kolom dari tabel tertentu ke cache
    #@param string $table - nama tabel yang akan dicek kolomnya
    # Contoh penggunaan internal:
    # $this->cekKolom('users'); // caching kolom tabel users
    protected static function cekKolom($table){
        if(is_string($table) && !empty(self::$mysqli)){
            self::$listColumn = array();
            $ambil = self::$mysqli->query("SHOW COLUMNS FROM `$table`");
            while($hasil = $ambil->fetch_array()){
                self::$listColumn[] = $hasil[0];
            }
        }
    }
    
     ## Menghitung jumlah data/records dalam tabel
    #@param string|array|null $table - nama tabel atau array of table names (optional)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return int|array - jumlah data atau array of counts untuk multiple tables
    # Contoh penggunaan:
    # $total = $this->mysql->jumlahData('users'); // hitung data di tabel users
    # $totals = $this->mysql->jumlahData(['users','products']); // hitung multiple tables
    public function jumlahData($table = null, $mysqli = null){
        try{
            if($mysqli == null && self::$mysqli != null){
                $mysqli = self::$mysqli;
            }
            elseif($mysqli == null && self::$mysqli == null){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if($table == null && self::$table != null){
                $table = self::$table;
            }

            $isicek = array($table);
            if($this->cekKosong($isicek) === true){
                $hasil = null;
                if(is_array($table)){
                    $hasil = array();
                    foreach($table AS $k => $v){
                        if(is_int($k)){
                            $a = $this->sqlEscape($v);
                            if(in_array($a, self::$listTable)){
                                $ambil = $mysqli->prepare("SELECT COUNT(*) AS jum FROM $a");
                                $ambil->execute();
                                $isi = $ambil->get_result();
                                $j = $isi->fetch_assoc();
                                $hasil[$v] = $j['jum'];
                            }
                            else{
                                throw new Exception('Error : tabel tidak diketahui ! ');
                            }
                        }
                        else{
                            throw new Exception('Error : array tabel harus berupa array(\'table\') ! ');
                        }
                    }
                    return $hasil;
                }
                elseif(is_string($table)){
                    $a = $this->sqlEscape($table);
                    $ambil = $mysqli->prepare("SELECT COUNT(*) AS jum FROM $a");
                    $ambil->execute();
                    $isi = $ambil->get_result();
                    $j = $isi->fetch_assoc();
                    return $j['jum'];
                }
                else{
                    throw new Exception('Error : tipe table tidak diizinkan pada fungsi jumlahData !');
                }
            }
            else{
                throw new Exception('Error : parameter jumlahData tidak lengkap !');
            }
        }
        catch(Exception $ex){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $ex->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }

    ## Mengembalikan daftar semua kolom dari tabel tertentu
    #@param string $table - nama tabel
    #@return array - daftar nama kolom
    # Contoh penggunaan:
    # $kolom = $this->mysql->semuaKolom('users'); // dapatkan daftar kolom tabel users
    public function semuaKolom($table){
        $this->cekKolom($table);
        return self::$listColumn;
    }
     ## Mengembalikan daftar semua tabel dalam database
    #@param mysqli $mysqli - koneksi database
    #@return array - daftar nama tabel
    # Contoh penggunaan:
    # $tables = $this->mysql->semuaTable($mysqli); // dapatkan daftar semua tabel
    public function semuaTable($mysqli){
        $table = array();
        if($mysqli != null){
            $ambil = $mysqli->query("SHOW TABLES");
            while($hasil = $ambil->fetch_array()){
                $table[] = $hasil[0];
            }
        }
        return $table;
    }
    
     ## Menghapus semua simbol khusus dari teks menggunakan mapping simbol
    #@param string $text - teks yang akan dibersihkan dari simbol
    #@return string - teks yang sudah dibersihkan dari simbol
    # Contoh penggunaan:
    # $cleanText = $this->mysql->hapusSemuaSimbol('Hello@World#$%'); // returns 'HelloWorld'
    public function hapusSemuaSimbol($text){
        $text = strtr($text, self::$simbol);
        return $text;
    }

    ## Menjalankan query SQL custom langsung ke database
    #@param string|null $text - query SQL yang akan dijalankan
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_result|bool - hasil query atau false jika gagal
    # Contoh penggunaan:
    # $result = $this->mysql->query("SELECT * FROM users WHERE age > 25");
    # $result = $this->mysql->query("UPDATE users SET status='active' WHERE id=1"); 
    public function query($text = null, $mysqli = null){
        try{
            if($this->cekKosong($text) === true){
                if($mysqli == null && self::$mysqli != null){
                    $mysqli = self::$mysqli;
                }
                elseif($mysqli == null && self::$mysqli == null){
                    throw new Exception('Error : tidak ada koneksi ke database !');
                }
                // $text = $this->sqlEscape($text);
                return $mysqli->query($text);
            }
            else{
                throw new Exception('Error : parameter query tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
     ## Membuat prepared statement untuk query SQL
    #@param string|null $text - query SQL dengan placeholder (?)
    #@param mysqli|null $mysqli - koneksi database (optional)
    #@return mysqli_stmt - prepared statement object
    # Contoh penggunaan:
    # $stmt = $this->mysql->prepare("SELECT * FROM users WHERE id = ?");
    # $stmt->bind_param('i', $id);
    # $stmt->execute();
    public function prepare($text = null, $mysqli = null){
        try{
            if($this->cekKosong($text) === true){
                if($mysqli == null && self::$mysqli != null){
                    $mysqli = self::$mysqli;
                }
                elseif($mysqli == null && self::$mysqli == null){
                    throw new Exception('Error : tidak ada koneksi ke database !');
                }
                $pre = $mysqli->prepare($text);
                return $pre;
            }
            else{
                throw new Exception('Error : parameter prepare tidak lengkap !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
     ## Set tabel aktif untuk operasi database (static method)
    #@param string $table - nama tabel yang akan di-set sebagai aktif
    #@throws Exception - jika tabel tidak ditemukan dalam database
    # Contoh penggunaan:
    # Mysql::setTable('users'); // set tabel users sebagai aktif
    public static function setTable(string $table){
        $ada = false;
        if(!empty(self::$listTable) && in_array($table, self::$listTable)){
            $ada = true;
        }
        else{
            self::simpanTable(self::$mysqli);
            if(in_array($table, self::$listTable)){
                $ada = true;
            }
        }
        if($ada){
            self::$table = $table;
        }
        else{
            throw new Exception('Error : Table \''.$table.'\' tidak ada dalam database !');
        }
    }
    ## Mengambil instance class table yang terkait dengan tabel tertentu
    #@param string $table - nama tabel yang akan di-load
    #@return object - instance class table
    #@throws Exception - jika tabel tidak ditemukan atau file table tidak ada
    # Contoh penggunaan:
    # $userTable = $this->mysql->table('users'); // load class table users
    public static function table($table){
        try{
            if(empty(self::$mysqli)){
                throw new Exception('Error : tidak ada koneksi ke database !');
            }
            if(!empty($table)){
                self::simpanTable(self::$mysqli);
                if(!empty(self::$listTable) && in_array($table, self::$listTable)){
                    $filenm = realpath(__DIR__.'/../table/'.self::$db.'/.table_'.$table);
                    if(file_exists($filenm)){
                        self::$table = $table;
                        $class = getClass($filenm);
                        return new $class();
                    }
                    else{
                        throw new Exception('Error : File table \''.$table.'\' tidak ada ! '.$filenm);
                    }
                }
                else{
                    throw new Exception('Error : Table \''.$table.'\' Tidak Ada Pada Database \''.self::$db.'\' !');
                }
            }
            else{
                throw new Exception('Error : Tidak ada table yang digunakan pada fungsi mysql->table() !');
            }
        }
        catch(Exception $e){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            if(self::$mode == 'd'){
                echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
            }
        }
    }
    ## Generate ID unik secara incremental berdasarkan tabel
    #@param string|null $table - nama tabel (optional, default tabel aktif)
    #@return string - ID unik yang di-generate
    # Contoh penggunaan:
    # $newId = Mysql::randomId('users'); // generate ID untuk tabel users
    # $newId = Mysql::randomId(); // generate ID untuk tabel aktif
    public static function randomId($table = null): string {
        if(!empty($table)){
            $tab = $table;
        }
        else{
            $tab = self::$table;
        }
        if(in_array($tab, self::$listTable)){
            $idFile = __DIR__.'/../asets/cache/.table_'.$tab;
            if(file_exists($idFile)){
                $text = file_get_contents($idFile);
            }
            else{
                $text = '';
            }
            $textSize = strlen($text);
            $alpha = range('a', 'z');
            $number = range(0, 9);
            if($textSize == 0){
                $text .= $alpha[0];
            }
            else{
                $last = $text[$textSize-1];
                $split = str_split($text);
                $posAlpha = array_search($last, $alpha);
                $posNumber = array_search($last, $number);
                $lastCount = 0;
                for($charKe = 0; $charKe < sizeof($split); $charKe++){
                    if($lastCount < $textSize){
                        $posCharAlpha = array_search($text[$charKe], $alpha);
                        $posCharNum = array_search($text[$charKe], $number);
                        if($posCharAlpha !== false){
                            if($posCharAlpha >= sizeof($alpha)-1){
                                $text[$charKe] = $alpha[0];
                                $lastCount++;
                                continue;
                            }
                            elseif($posCharAlpha < sizeof($alpha)){
                                $text[$charKe] = $alpha[$posCharAlpha+1];
                            }
                        }
                        else{
                            if($posCharNum >= sizeof($number)-1){
                                $text[$charKe] = $number[0];
                                $lastCount++;
                                continue;
                            }
                            elseif($posCharNum < sizeof($number)){
                                $text[$charKe] = $number[$posCharNum+1];
                            }
                        }
                        break;
                    }
                    else{
                        break;
                    }
                }
                if($lastCount == $textSize){
                    if($posAlpha !== false){
                        $text .= $number[0];
                    }
                    elseif($posNumber !== false){
                        $text .= $alpha[0];
                    }
                }
            }
            file_put_contents($idFile, $text);
            self::$lastId = $text;
            return $text;
        }
        else{
            trigger_error('Error random Id : table \''.$tab.'\' Tidak diketahui !');
        }
    }
     ## Enkripsi string menggunakan AES-256-CBC
    #@param string $string - string yang akan dienkripsi
    #@param string $key - kunci enkripsi (default: 'AllInAliens')
    #@param string $iv - initialization vector (default: 'AllInOneInAliens')
    #@return string - string yang sudah dienkripsi dan encoded base64
    #@throws Exception - jika parameter tidak valid
    # Contoh penggunaan:
    # $encrypted = Mysql::encrypt('secret data', 'mykey', 'myiv12345678901');
    public static function encrypt(string $string, string $key='AllInAliens', string $iv='AllInOneInAliens'):string{
        // openssl_encrypt(data, method, key, 0, iv);
        try{
            if(empty($string)){
                throw new Exception('Error Encrypt: Tidak ada string yang akan di encrypt !');
            }
            else if(empty($key)){
                throw new Exception('Error Encrypt: Tidak ada kunci untuk encrypt ! ');
            }
            else if(empty($iv)){
                throw new Exception('Error Encrypt: Tidak ada vektor inisialisasi (iv) untuk encrypt! ');
            }
            else if(strlen($iv) < 16 || strlen($iv) > 16){
                throw new Exception('Error Encrypt: Panjang string vektor inisialisasi (iv) harus 16 !');
            }
            else{
                $out = openssl_encrypt($string, self::$enmethod, $key, 0, $iv);
                if($out !== false){
                    return base64_encode($out);
                }
                else{
                    return '';
                }
            }
        } 
        catch (Exception $e) {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
        }
    }
      ## Dekripsi string yang sebelumnya dienkripsi dengan encrypt()
    #@param string $string - string yang akan didekripsi
    #@param string $key - kunci enkripsi (default: 'AllInAliens')
    #@param string $iv - initialization vector (default: 'AllInOneInAliens')
    #@return string - string yang sudah didekripsi
    #@throws Exception - jika parameter tidak valid
    # Contoh penggunaan:
    # $decrypted = Mysql::decrypt($encryptedData, 'mykey', 'myiv12345678901');
    public static function decrypt(string $string, string $key='AllInAliens', string $iv='AllInOneInAliens'):string{
        try{
            if(empty($string)){
                throw new Exception('Error Encrypt: Tidak ada string yang akan di decrypt !');
            }
            else if(empty($key)){
                throw new Exception('Error Encrypt: Tidak ada kunci untuk decrypt ! ');
            }
            else if(empty($iv)){
                throw new Exception('Error Encrypt: Tidak ada vektor inisialisasi (iv) untuk decrypt! ');
            }
            else if(strlen($iv) < 16 || strlen($iv) > 16){
                throw new Exception('Error Encrypt: Panjang string vektor inisialisasi (iv) harus 16 !');
            }
            else{
                if(($in = base64_decode($string)) !== false){
                    $out = openssl_decrypt($in, self::$enmethod, $key, 0, $iv);
                    if($out !== false && strlen($out) > 0){
                        return $out;
                    }
                    else{
                        return '';
                    }
                }
                else{
                    return '';
                }
            }
        } 
        catch (Exception $e) {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            $files = $caller['file'];
            $lines = $caller['line'];
            echo $e->getMessage()."<br>pada : ". $files ." baris : ". $lines;
        }
    }
}