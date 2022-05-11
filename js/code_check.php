<?
$pv_type = $_GET['pv_type'];
$pv_doc_no = $_POST['pv_doc_no'];

if ($pv_doc_no!='') {
	include_once("../inc/connect.php");
	
	if ($pv_type==2) {
		$sql="SELECT COUNT(*) AS num FROM payment_voucher 
		WHERE 
		`pv_code` = '$pv_doc_no' AND 
		`pv_type` = '2' AND 
		`mark_del` = '0' ";			
		$result = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($result);
		if ($row[num]==0) {
			echo 'true';
			exit;
		}
	} else {
		$sql="SELECT COUNT(*) AS num FROM payment_voucher 
		WHERE 
		`pv_code` = '$pv_doc_no' AND 
		`pv_type` = '1' AND 
		`mark_del` = '0' ";			
		$result = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($result);
		if ($row[num]==0) {
			echo 'true';
			exit;
		}
	}
} 

echo 'false';

?>