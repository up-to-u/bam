<?
include('inc/include.inc.php');

echo template_header();

$sql2="SELECT * FROM loss_permission WHERE user_id = '$user_id' ";
$result2=mysqli_query($connect, $sql2);
if ($row2 = mysqli_fetch_array($result2)) {	
	if ($row2['is_manage']==0) {
		echo '<font color="red">ขออภัย ท่านไม่มีสิทธิเข้าใช้งานระบบนี้</font>';
		echo template_footer();
		exit;		
	}
} else {
	echo '<font color="red">ขออภัย ท่านไม่มีสิทธิเข้าใช้งานระบบนี้</font>';
	echo template_footer();
	exit;		
}

$action = $_GET[action];
$edit_id = $_GET[edit_id];
$del_id = $_GET[del_id];
$update_id = $_POST[update_id];
$submit = $_POST[submit];

if ($submit=='update' && is_numeric($update_id)) {
	$is_w = intVal($_POST['is_w']);
	$is_m = intVal($_POST['is_m']);

	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "UPDATE loss_permission SET
		is_write = '$is_w', 
		is_manage = '$is_m'
	WHERE loss_permission_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	
	
	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div>';
	}
	
} else if ($submit=='add') {
	$add_user_code = addslashes($_POST['add_user_code']);
	$add_w = intVal($_POST['add_w']);
	$add_m = intVal($_POST['add_m']);
	
	if($add_user_code != '') {
		$sql2="SELECT * FROM user WHERE code = '$add_user_code' AND status = 0 ";
		$result2=mysqli_query($connect, $sql2);
		if ($row2 = mysqli_fetch_array($result2)) {	

			$uid = $row2['user_id'];

			$qx = true;	
			mysqli_autocommit($connect,FALSE);
			
			$sql = "INSERT INTO loss_permission (user_id, is_write, is_manage, create_date) VALUES 
			('$uid', '$add_w', '$add_m', now()) ";
			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);	

			if ($qx) {
				mysqli_commit($connect);		
				echo '<div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div>';
			} else {
				mysqli_rollback($connect);			
				echo '<div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div>';
			}
		}
	}	
	
} else if (is_numeric($del_id)) {

	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "DELETE FROM loss_permission WHERE loss_permission_id = '$del_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div>';
	}
			

} else if ($edit_id>0) {

	$sql2="SELECT * 
			FROM loss_permission 
			JOIN user ON loss_permission.user_id = user.user_id
			WHERE loss_permission_id = '$edit_id' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-user font-green"></i>
					<span class="caption-subject font-green sbold uppercase">แก้ไข สิทธิ์</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='loss_permission.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='loss_permission.php'>  
	<div class="form-group">
	  <label>รหัส</label>
	  <div class='alert alert-info'><?=$row2['code']?></div>
	</div>	
	<div class="form-group">
	  <label>ชื่อ</label>
	  <div class='alert alert-info'><?=$row2['prefix']?><?=$row2['name']?> <?=$row2['surname']?></div>
	</div>	
	<div class="form-group">
	  <label>สิทธิ์</label><br>
	  <label><input type="checkbox" name='is_w' value='1' <?if ($row2['is_write']==1) echo 'checked'?>> Admin</label><br>
	  <label><input type="checkbox" name='is_m' value='1' <?if ($row2['is_manage']==1) echo 'checked'?>> กำหนดสิทธิ</label><br>
	</div>	
	<br>
	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='loss_permission.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='update' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
	</form>
	
		</div>
	</div>
</div>
		  
<?
	}	

	echo template_footer();
	exit;
	
}
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-user font-green"></i>
					<span class="caption-subject font-green sbold uppercase">กำหนดสิทธิ</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			
			
	<form method='post' action='loss_permission.php' enctype="multipart/form-data">  
	<div class="form-group">
	  <label>เพิ่มโดยใส่รหัสพนักงาน</label>
	  <div class='row'>
	  <div class='col-md-2'><input type="text" class="form-control" name='add_user_code' placeholder="รหัสพนักงาน" value=''></div>
	  <div class='col-md-1'></div>
	  <div class='col-md-1'><input type="checkbox" name='add_w' value='1'>Admin</div>
	  <div class='col-md-2'><input type="checkbox" name='add_m' value='1'>กำหนดสิทธิ</div>
	  </div>
	</div>	
	<button type='submit' name='submit' value='add' class="btn btn-danger"><i class='fa fa-plus-circle'></i> เพิ่ม</button>
	</form>
	<br>
			<div class="table-responsive">
			<table class='table table-hover'>
			<thead>
				<tr>
					<td width='10%'></td>
					<td width='20%'>รหัส</td>
					<td width='30%'>ชื่อ</td>
					<td width='10%'>Admin</td>
					<td width='10%'>กำหนดสิทธิ</td>
					<td width='10%' align='right'></td>
				</tr>
			</thead>
			<tbody>
<?
			$n=1;
			$sql = "SELECT * 
			FROM loss_permission 
			JOIN user ON loss_permission.user_id = user.user_id
			WHERE 
				1
			ORDER BY 
				user.code ";
			$result = mysqli_query($connect, $sql);
			if (mysqli_num_rows($result)>0) {
				while ($row = mysqli_fetch_array($result)) {				
?>
<tr>
	<td><?=$n++?></td>
	<td><a href='loss_permission.php?edit_id=<?=$row['loss_permission_id']?>'><?=$row['code']?></a></td>
	<td><a href='loss_permission.php?edit_id=<?=$row['loss_permission_id']?>'><?=$row['prefix']?><?=$row['name']?> <?=$row['surname']?></a></td>
	<td><?if ($row['is_write']==1) echo '<i class="fa fa-check"></i>'?></td>
	<td><?if ($row['is_manage']==1) echo '<i class="fa fa-check"></i>'?></td>
	<td align='right'><a href="loss_permission.php?del_id=<?=$row['loss_permission_id']?>" onclick='return confirm("Confirm Delete?");'>ลบ</a></td>
</tr>
<?
				}
			} else {
?>						
				<tr>
					<td colspan='6'>- ไม่มีข้อมูล -</td>
				</tr>
<?			
			}
?>
</tbody>
</table>		  
	
		</div>
		</div>
	</div>
</div>	

<?
echo template_footer();
?>