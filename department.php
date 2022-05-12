<?include('inc/include.inc.php');echo template_header();$action = $_GET['action'];$edit_id = intval($_GET['edit_id']);$del_id = intval($_GET['del_id']);$update_id = intval($_POST['update_id']);$submit = $_POST['submit'];if ($submit=='update' && $update_id>0) {	$department_no = addslashes($_POST['department_no']);	$department_name = addslashes($_POST['department_name']);	$department_level_id = intval($_POST['department_level_id']);	$qx = true;		mysqli_autocommit($connect,FALSE);		$sql = "UPDATE department SET	`department_no` = '$department_no', 	`department_name` = '$department_name', 	`department_level_id` = '$department_level_id', 	`last_modify` = now() 	WHERE department_id = '$update_id' ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('ssii', $department_no, $department_name, $department_level_id, $update_id);		$stmt->execute();		$q = $stmt->execute();		$qx = ($qx and $q);			if ($qx) {			mysqli_commit($connect);					echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';		} else {			mysqli_rollback($connect);						echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';		}	}	} else if ($submit=='add') {	$department_no = addslashes($_POST['department_no']);	$department_name = addslashes($_POST['department_name']);	$department_level_id = intval($_POST['department_level_id']);	$add_id = intval($_POST['add_id']);	$qx = true;		mysqli_autocommit($connect,FALSE);		$sql = "INSERT INTO department (`parent_id`, `department_no`, `department_name`, `department_level_id`, `create_date`) VALUES (?,?,?,?,now()) ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('issi', $add_id, $department_no, $department_name, $department_level_id);		$q = $stmt->execute();		$qx = ($qx and $q);			if ($qx) {			mysqli_commit($connect);					echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';		} else {			mysqli_rollback($connect);						echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';		}	}	} else if ($del_id>0) {	$qx = true;		mysqli_autocommit($connect,FALSE);		$sql = "UPDATE department SET `mark_del` = 1, `last_modify` = now() WHERE department_id = ? ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('i', $del_id);		$stmt->execute();		$q = $stmt->execute();		$qx = ($qx and $q);					if ($qx) {			mysqli_commit($connect);					echo '<div class="container"><b><div class="alert alert-success">ระบบได้ลบข้อมูลเรียบร้อยแล้ว</div></b><br></div>';		} else {			mysqli_rollback($connect);						echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';		}	}	} else if ($edit_id>0) {	$sql="SELECT * FROM `department` WHERE department_id = ? ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('i', $edit_id);		$stmt->execute();		$result2 = $stmt->get_result();		if ($row2 = mysqli_fetch_assoc($result2)) {?><script language='JavaScript'>function confirm_delete() {	if (confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")) {		document.location = "department.php?del_id=<?=$row2['department_id']?>";	}}</script><section class="panel">	<header class="panel-heading">		<h2 class="panel-title">แก้ไขฝ่าย</h2>	</header>	<div class="panel-body">		<form method='post' action='department.php'>		<div class="box-body">		<div class="form-group">		  <label>รหัส</label>		  <input type="text" class="form-control" name='department_no' placeholder="รหัส" maxlength='5' value='<?=$row2['department_no']?>'>		</div>		<div class="form-group">		  <label>ชื่อฝ่าย/ส่วนงาน</label>		  <input type="text" class="form-control" name='department_name' placeholder="ฝ่าย/ส่วนงาน" value='<?=$row2['department_name']?>'>		</div>		<div class="form-group">		  <label>ระดับ</label>		  <select name='department_level_id' class="form-control">			<option value='0'>--- เลือก ---</option><?$sql="SELECT * FROM department_level ORDER BY department_level_id";$result1=mysqli_query($connect, $sql);while ($row1 = mysqli_fetch_array($result1)) {?>			<option value='<?=$row1['department_level_id']?>' <?if ($row1['department_level_id']==$row2['department_level_id']) echo 'selected'?>><?=$row1['department_level_name']?></option><?}?>			</select>		</div>		<br>		<input type='hidden' name='update_id' value='<?=$row2['department_id']?>'>		<button type='button' class="btn btn-primary" onClick="document.location='department.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>		<button type='submit' name='submit' value='update' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		<button type='button' name='button' class='btn btn-danger' onClick='confirm_delete()' id='confirm_btn'><i class='fa fa-times'></i> ลบ</button>	</form>	</div></section>		  <?		}	}		echo template_footer();	exit;	} else if ($action=='add') {	$add_id = intval($_GET['add_id']);	if ($add_id==0) 		$department_level_id = 3;	else		$department_level_id = 4;?><section class="panel">	<header class="panel-heading">		<h2 class="panel-title">เพิ่มฝ่าย</h2>	</header>	<div class="panel-body">	<form method='post' action='department.php'>	<div class="form-group">	  <label>รหัส</label>	  <input type="text" class="form-control" name='department_no' placeholder="รหัส" maxlength='5'>	</div>	<div class="form-group">	  <label>ชื่อฝ่าย/ส่วนงาน</label>	  <input type="text" class="form-control" name='department_name' placeholder="ฝ่าย/ส่วนงาน">	</div>	<div class="form-group">	  <label>ระดับ</label>	  <select name='department_level_id' class="form-control">		<option value='0'>--- เลือก ---</option><?$sql="SELECT * FROM department_level ORDER BY department_level_id";$result1=mysqli_query($connect, $sql);while ($row1 = mysqli_fetch_array($result1)) {?>			<option value='<?=$row1['department_level_id']?>' <?if ($row1['department_level_id']==$department_level_id) echo 'selected'?>><?=$row1['department_level_name']?></option><?}?>		</select>	</div>	<br>	<input type='hidden' name='add_id' value='<?=$add_id?>'>	<button type='button' class="btn btn-primary" onClick="document.location='department.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>	<button type='submit' name='submit' value='add' class="btn btn-danger"><i class='fa fa-plus-circle'></i> เพิ่ม</button>	</form>		</div></section>		  <?	echo template_footer();	exit;} ?><div class="row">	<div class="col-lg-12 col-xs-12 col-sm-12">		<div class="portlet light tasks-widget bordered">			<div class="portlet-title">				<div class="caption">					<i class="icon-grid font-green"></i>					<span class="caption-subject font-green sbold uppercase">ฝ่าย/ส่วนงาน</span>					<span class="caption-helper"></span>				</div>				<div class="actions">				</div>			</div><br> <script language='JavaScript'>$(function () {	$('.csa_editable').on('click', function() {		var id = $(this).attr('did');		document.location="department.php?edit_id="+id;	});		});  </script><style>.csa_editable {	cursor: pointer}</style> <table class='table table-hover table-light'><thead><tr>  <th width='5%'>No.</th>  <th width='5%'>รหัส</th>  <th width='60%'>ฝ่าย/ส่วนงาน</th>  <th width='20%'>ระดับ</th>  <th width='10%'></th></tr></thead><tbody><?	$i = 1;	$sql2="SELECT 		d.*,		dl.department_level_name	FROM `department` d	LEFT JOIN `department_level` dl ON  		d.department_level_id = dl.department_level_id AND 		dl.mark_del = '0'	WHERE 		d.parent_id = '0' AND		d.mark_del = '0' ";	$result2=mysqli_query($connect, $sql2);	while ($row2 = mysqli_fetch_array($result2)) {?><tr style='font-weight: bold' bgcolor='#eeeeee'>	<td width='5%' did='<?=$row2['department_id']?>' class='csa_editable'><?=$i++?></td>	<td width='5%' did='<?=$row2['department_id']?>' class='csa_editable'><?=$row2['department_no']?></td>	<td width='60%' did='<?=$row2['department_id']?>' class='csa_editable'><?=$row2['department_name']?></td>	<td width='20%' did='<?=$row2['department_id']?>' class='csa_editable'><?=$row2['department_level_name']?></td>	<td><button type='button' class="btn btn-default btn-xs" onClick='document.location="department.php?action=add&add_id=<?=$row2['department_id']?>"'><i class='fa fa-plus'></i> เพิ่ม</button></td></tr><?			$sql = "SELECT 				d.*,				dl.department_level_name			FROM `department` d			LEFT JOIN `department_level` dl ON  				d.department_level_id = dl.department_level_id AND 				dl.mark_del = '0'			WHERE 				d.parent_id = '$row2[department_id]' AND				d.mark_del = '0' ";			$result3 = mysqli_query($connect, $sql);			while ($row3 = mysqli_fetch_array($result3)) {?><tr>	<td width='5%' did='<?=$row3['department_id']?>' class='csa_editable'><?=$i++?></td>	<td width='5%' did='<?=$row3['department_id']?>' class='csa_editable'><?=$row3['department_no']?></td>	<td width='60%' did='<?=$row3['department_id']?>' class='csa_editable'><?=$row3['department_name']?></td>	<td width='20%' did='<?=$row3['department_id']?>' class='csa_editable'><?=$row3['department_level_name']?></td>	<td></td></tr><?			}	}	?></tbody></table>			                  	<br>	<button type="submit" class="btn btn-danger" onClick="document.location='department.php?action=add'"><i class='fa fa-plus-circle'></i> เพิ่ม</button>		</div></section>	<?echo template_footer();?>