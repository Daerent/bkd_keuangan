<?php 
defined('PROTOCOLS') OR die("HAYOoo.. mau ngapain? (-_- ')");
$httphost = (intval($_SERVER['HTTP_HOST']) > 0 || strpos($_SERVER['HTTP_HOST'], 'localhost') > -1)? 'localhost':$_SERVER['HTTP_HOST'];
if($httphost === 'localhost'){
	// untuk offline / localhost
	// jika database lebih dari 1, gunakan seperti dibawah ini:
	// $_PENGATURAN['DB'][0]['HOST_DB'] = 'localhost';
	// $_PENGATURAN['DB'][0]['USER_DB'] = 'root';
	// $_PENGATURAN['DB'][0]['PASS_DB'] = '';
	// $_PENGATURAN['DB'][0]['DATA_DB'] = 'sampel';

	// $_PENGATURAN['DB'][1]['HOST_DB'] = 'localhost';
	// $_PENGATURAN['DB'][1]['USER_DB'] = 'root';
	// $_PENGATURAN['DB'][1]['PASS_DB'] = '';
	// $_PENGATURAN['DB'][1]['DATA_DB'] = 'sampel1';

	$_PENGATURAN['HOST_DB'] = 'localhost';
	$_PENGATURAN['USER_DB'] = 'root';
	$_PENGATURAN['PASS_DB'] = '';
	$_PENGATURAN['DATA_DB'] = 'kwitansi';
	$_PENGATURAN['PORT_DB'] = 3306;

	$dr = explode(DIRECTORY_SEPARATOR, __DIR__);

	$_PENGATURAN['ROOT_DIR'] = $dr[sizeof($dr) - 1];

	$_PENGATURAN['AUTO_UPDATE'] = false;
	if(intval($_SERVER['SERVER_PORT']) == 80){
		$httphost = 'localhost';
	}
	else{
		$httphost = $_SERVER['HTTP_HOST'];
	}
}
else{
	// untuk online
	/**
	 * Jika ingin menggunakan lebih dari 1 database, 
	 * tambahkan ['DB'][0] sebelum menuliskan ['HOST_DB'] dan sebagainya
	 * 'DB' adalah index untuk mengetahui kalau menggunakan lebih dari 1 database
	 * 0 adalah index dari database yang akan dihubungkan
	 * 
	 * untuk penggunaan pada class, $this->database->getMysqli(0)
	 * dimana 0 adalah koneksi kedalam database yang pertama, begitupun
	 * dengan pdo, menggunakan $this->database->getPdo(0) untuk koneksi yang pertama
	 * 
	 * jika ingin menggunakan koneksi kedua dari database,
	 * gunakan index yang sesuai dengan koneksi yang dibuat dibawah
	 * contoh penggunaan koneksi database yang lebih dari 1 database
	 * 
	 * 
	 * untuk koneksi database yang pertama
	 * $_PENGATURAN['DB'][0]['HOST_DB'] = 'localhost';
	 * $_PENGATURAN['DB'][0]['USER_DB'] = 'root';
	 * $_PENGATURAN['DB'][0]['PASS_DB'] = '';
	 * $_PENGATURAN['DB'][0]['DATA_DB'] = 'sample';
	 * 
	 * untuk koneksi database yang kedua
	 * $_PENGATURAN['DB'][1]['HOST_DB'] = 'localhost';
	 * $_PENGATURAN['DB'][1]['USER_DB'] = 'root';
	 * $_PENGATURAN['DB'][1]['PASS_DB'] = '';
	 * $_PENGATURAN['DB'][1]['DATA_DB'] = 'yanglain';
	 */
	
	$_PENGATURAN['HOST_DB'] = '';
	$_PENGATURAN['USER_DB'] = '';
	$_PENGATURAN['PASS_DB'] = '';
	$_PENGATURAN['DATA_DB'] = '';
	$_PENGATURAN['PORT_DB'] = 3306;
	$_PENGATURAN['AUTO_UPDATE'] = false;

	
}
$_PENGATURAN['IMPORTING'] = false;
$_PENGATURAN['DIR_UMUM'] = 'umum';
$_PENGATURAN['404_ON_ERROR'] = true;
$_PENGATURAN['404_FILE'] = 'er_404.php';
$_PENGATURAN['ALLOWED_ASSETS'] = ['css', 'js', 'img', 'font', 'doc', 'QrData'];
$_PENGATURAN['MODE'] = 'development';
$_PENGATURAN['TABEL_CLASS'] = false;

$_PENGATURAN['MENU'] = [];
// sample postValue : $_POST['a'] = 'a'
$_PENGATURAN['MENU']['postValue'] = 'a';
// sample extendTitle : 'judul awal judul tambahan'
// 'judul awal' : #judul() pada file halaman
// 'judul tambahan' : judul tambahan ketika halaman berubah
$_PENGATURAN['MENU']['extendTitle'] = ' ';