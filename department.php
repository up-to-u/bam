<?
include('inc/include.inc.php');
echo template_header();

$action = $_GET[action];
$edit_id = $_GET[edit_id];
$del_id = $_GET[del_id];
$update_id = $_POST[update_id];
$submit = $_POST[submit];


if ($submit=='update' && is_numeric($update_id)) {
	$department_no = addslashes($_POST[department_no]);
	$department_name = addslashes($_POST[department_name]);

	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "UPDATE department SET
	`department_no` = '$department_no', 
	`department_name` = '$department_name', 
	`last_modify` = now() 
	WHERE department_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
	}
	echo '<script>$(document).ready(function(){$(".alert").fadeTo(2000, 500).slideUp(500, function(){ $(".alert").slideUp(500); });});</script>';			

} else if ($submit=='add') {
	$department_no = addslashes($_POST[department_no]);
	$department_name = addslashes($_POST[department_name]);

	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "INSERT INTO department (`department_no`, `department_name`, `create_date`) VALUES ('$department_no', '$department_name',  now()) ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
	}
	echo '<script>$(document).ready(function(){$(".alert").fadeTo(2000, 500).slideUp(500, function(){ $(".alert").slideUp(500); });});</script>';			

	
} else if (is_numeric($del_id)) {

	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "UPDATE department SET `mark_del` = 1, `last_modify` = now() WHERE department_id = '$del_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class="container"><b><div class="alert alert-success">ระบบได้ลบข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
	}
	echo '<script>$(document).ready(function(){$(".alert").fadeTo(2000, 500).slideUp(500, function(){ $(".alert").slideUp(500); });});</script>';			
	
} else if (is_numeric($edit_id)) {

	$sql2="SELECT * FROM `department` WHERE department_id = '$edit_id' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	
?>

<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title">แก้ไขฝ่าย</h2>
	</header>
	<div class="panel-body">
	

	<form method='post' action='department.php'>
		<div class="box-body">
		<div class="form-group">
		  <label>รหัส</label>
		  <input type="text" class="form-control" name='department_no' placeholder="รหัส" maxlength='5' value='<?=$row2[department_no]?>'>
		</div>
		<div class="form-group">
		  <label>ชื่อฝ่าย/ส่วนงาน</label>
		  <input type="text" class="form-control" name='department_name' placeholder="ฝ่าย/ส่วนงาน" value='<?=$row2[department_name]?>'>
		</div>

		<br>
		<input type='hidden' name='update_id' value='<?=$row2[department_id]?>'>
		<button type='button' class="btn btn-primary" onClick="document.location='department.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
		<button type='submit' name='submit' value='update' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
	</form>
	</div>
</section>

		  
<?
	}	

	echo template_footer();
	exit;
	
} else if ($action=='add') {
?>

<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title">เพิ่มฝ่าย</h2>
	</header>
	<div class="panel-body">

	<form method='post' action='department.php'>
	<div class="form-group">
	  <label>รหัส</label>
	  <input type="text" class="form-control" name='department_no' placeholder="รหัส" maxlength='5'>
	</div>
	<div class="form-group">
	  <label>ชื่อฝ่าย/ส่วนงาน</label>
	  <input type="text" class="form-control" name='department_name' placeholder="ฝ่าย/ส่วนงาน">
	</div>

	<br>

	<button type='button' class="btn btn-primary" onClick="document.location='department.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='add' class="btn btn-danger"><i class='fa fa-plus-circle'></i> เพิ่ม</button>
	</form>
	
	</div>
</section>
		  
<?

	echo template_footer();
	exit;
} 

?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-grid font-green"></i>
					<span class="caption-subject font-green sbold uppercase">ฝ่าย/ส่วนงาน</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
				</div>
			</div>

<br>
  
<table class='table table-hover table-light'>
<thead>
<tr>
  <th width='5%'>No.</th>
  <th width='10%'>รหัส</th>
  <th width='70%'>ฝ่าย/ส่วนงาน</th>
  <th width='10%'></th>
</tr>
</thead>
<tbody>
<?
	$i = 1;
	$sql2="SELECT * FROM `department` WHERE mark_del = '0' ";
	$result2=mysqli_query($connect, $sql2);
	while ($row2 = mysqli_fetch_array($result2)) {
?>
<tr>
	<td width='5%'><?=$i++?></td>
	<td width='15%'><?=$row2[department_no]?></td>
	<td width='70%'><?=$row2[department_name]?></td>
	<td class="actions" width='10%' align='right'>
		<a href="department.php?edit_id=<?=$row2[department_id]?>"><i class="fa fa-pencil"></i></a>
		<a href="department.php?del_id=<?=$row2[department_id]?>" onClick='return confirm("Confirm Delete?")' class="delete-row"><i class="fa fa-trash-o"></i></a>
	</td>     
  
</tr>
<?		
	}	
?>

</tbody>
</table>			  
                
	<br>
	<button type="submit" class="btn btn-danger" onClick="document.location='department.php?action=add'"><i class='fa fa-plus-circle'></i> เพิ่ม</button>
	
	</div>
</section>

	

<?
echo template_footer();
?>