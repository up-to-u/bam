<?
include('inc/include.inc.php');
$action = $_POST['action'];
$d = $_POST['data'];

if ($action=='head_code1' and $d !=''){
$sql2="SELECT *	 FROM user where code ='$d' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	?>
<input type="text"class="form-control" name="head_code1_name" readonly required value="<?= $row2['name']." ".$row2['surname'];?>" />
<input type="hidden"class="form-control" name="emailapprove"required value="<?= $row2['email'];?>" />
<?	}else{?><input type="text"class="form-control" name="head_code1_name" readonly required value="ไม่พบรายชื่อพนักงาน" />

<?}}?>