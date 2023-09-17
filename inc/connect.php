<?
	error_reporting(E_ALL & ~E_WARNING  & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); 


	if ($_SERVER['HTTP_HOST']!='localhost') {
		$con_hostname = 'localhost';
		$con_username = 'arisadev_bam';
		$con_password = 'E2jGy14r(Q0iY;';
		$database = 'arisadev_bam';
		$debug = 1;	
		$url_prefix = 'https://bam.arisadev.com/';
		$domain = '.arisadev.com';
	} else {
		$con_hostname = 'localhost';
		$con_username = 'root';
		$con_password = '';
		$database = 'bam';
		$debug = 1;	
		$url_prefix = 'http://localhost/bam/';
		$domain = '';
	}

	$email_from = 'rms@bam.co.th';

	

$connect = mysqli_connect($con_hostname,$con_username,$con_password, $database);
if (mysqli_connect_errno()) {
	echo 'Failed to connect to MySQL: '.mysqli_connect_error();
	exit;
}
mysqli_set_charset($connect, 'utf8');

?>