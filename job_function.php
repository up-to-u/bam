<?include('inc/include.inc.php');echo template_header();$action = $_GET['action'];$edit_id = intval($_GET['edit_id']);$del_id = intval($_GET['del_id']);$update_id = intval($_POST['update_id']);$submit = $_POST['submit'];if ($submit=='update' && $update_id>0) {	$job_function_no = addslashes($_POST['job_function_no']);	$job_function_name = addslashes($_POST['job_function_name']);	$department_id = intval($_POST['department_id']);	$qx = true;		mysqli_autocommit($connect,FALSE);		$sql = "UPDATE job_function SET	`job_function_no` = ?, 	`job_function_name` = ?, 	`department_id` = ?, 	`last_modify` = now() 	WHERE job_function_id = ? ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('ssii', $job_function_no, $job_function_name, $department_id, $update_id);		$stmt->execute();		$q = $stmt->execute();		$qx = ($qx and $q);			if ($qx) {			mysqli_commit($connect);					echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';		} else {			mysqli_rollback($connect);						echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';		}	} 	} else if ($submit=='add') {	$job_function_no = addslashes($_POST['job_function_no']);	$job_function_name = addslashes($_POST['job_function_name']);	$department_id = intval($_POST['department_id']);	$add_id = intval($_POST['add_id']);	$qx = true;		mysqli_autocommit($connect,FALSE);		$sql = "INSERT INTO `job_function` (`parent_id`, `job_function_no`, `job_function_name`, `department_id`, `create_date`) VALUES 	(?,?,?,?,now()) ";		$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('issi', $add_id, $job_function_no, $job_function_name, $department_id);		$q = $stmt->execute();		$qx = ($qx and $q);			if ($qx) {			mysqli_commit($connect);					echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';		} else {			mysqli_rollback($connect);						echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';		}	}	} else if ($del_id>0) {	$qx = true;		mysqli_autocommit($connect,FALSE);		$sql = "UPDATE job_function SET `mark_del` = 1, `last_modify` = now() WHERE job_function_id = ? ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('i', $del_id);		$stmt->execute();		$q = $stmt->execute();		$qx = ($qx and $q);					if ($qx) {			mysqli_commit($connect);					echo '<div class="container"><b><div class="alert alert-success">ระบบได้ลบข้อมูลเรียบร้อยแล้ว</div></b><br></div>';		} else {			mysqli_rollback($connect);						echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';		}	}	} else if ($edit_id>0) {	$sql="SELECT * FROM `job_function` WHERE job_function_id = ? ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('i', $edit_id);		$stmt->execute();		$result2 = $stmt->get_result();		if ($row2 = mysqli_fetch_assoc($result2)) {?><script language='JavaScript'>function confirm_delete() {	if (confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")) {		document.location = "job_function.php?del_id=<?=$row2['job_function_id']?>";	}}</script><section class="panel">	<header class="panel-heading">		<h2 class="panel-title">แก้ไขฝ่าย</h2>	</header>	<div class="panel-body">		<form method='post' action='job_function.php'>		<div class="box-body">		<div class="form-group">		  <label>รหัส</label>		  <input type="text" class="form-control" name='job_function_no' required placeholder="รหัส" maxlength='5' value='<?=$row2['job_function_no']?>'>		</div>		<div class="form-group">		  <label>ชื่อJob Function</label>		  <input type="text" class="form-control" name='job_function_name' required placeholder="Job Function" value='<?=$row2['job_function_name']?>'>		</div>		<div class="form-group">		  <label>ฝ่าย</label>		  <select name='department_id' class="form-control" required>			<option value='0'>--- เลือก ---</option><?$sql="SELECT * FROM department ORDER BY department_id";$result1=mysqli_query($connect, $sql);while ($row1 = mysqli_fetch_array($result1)) {?>			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$row2['department_id']) echo 'selected'?>><?=$row1['department_name']?></option><?}?>			</select>		</div>		<br>		<input type='hidden' name='update_id' value='<?=$row2['job_function_id']?>'>		<button type='button' class="btn btn-primary" onClick="document.location='job_function.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>		<button type='submit' name='submit' value='update' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		<button type='button' name='button' class='btn btn-danger' onClick='confirm_delete()' id='confirm_btn'><i class='fa fa-times'></i> ลบ</button>	</form>	</div></section>		  <?		}	}		echo template_footer();	exit;	} else if ($action=='add') {	$add_id = intval($_GET['add_id']);?><section class="panel">	<header class="panel-heading">		<h2 class="panel-title">เพิ่มฝ่าย</h2>	</header>	<div class="panel-body">	<form method='post' action='job_function.php'>	<div class="form-group">	  <label>รหัส</label>	  <input type="text" class="form-control" name='job_function_no' placeholder="รหัส" maxlength='5' required>	</div>	<div class="form-group">	  <label>ชื่อJob Function</label>	  <input type="text" class="form-control" name='job_function_name' placeholder="Job Function" required>	</div>	<div class="form-group">	  <label>ฝ่าย</label>	  <select name='department_id' class="form-control" required>		<option value='0'>--- เลือก ---</option><?$sql="SELECT * FROM department ORDER BY department_id";$result1=mysqli_query($connect, $sql);while ($row1 = mysqli_fetch_array($result1)) {?>			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$department_id) echo 'selected'?>><?=$row1['department_name']?></option><?}?>		</select>	</div>	<br>	<input type='hidden' name='add_id' value='<?=$add_id?>'>	<button type='button' class="btn btn-primary" onClick="document.location='job_function.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>	<button type='submit' name='submit' value='add' class="btn btn-danger"><i class='fa fa-plus-circle'></i> เพิ่ม</button>	</form>		</div></section>		  <?	echo template_footer();	exit;} ?><div class="row">	<div class="col-lg-12 col-xs-12 col-sm-12">		<div class="portlet light tasks-widget bordered">			<div class="portlet-title">				<div class="caption">					<i class="icon-grid font-green"></i>					<span class="caption-subject font-green sbold uppercase">Job Function</span>					<span class="caption-helper"></span>				</div>				<div class="actions">				</div>			</div><br> <script language='JavaScript'>$(function () {	$('.csa_editable').on('click', function() {		var id = $(this).attr('did');		document.location="job_function.php?edit_id="+id;	});		});  </script><style>.csa_editable {	cursor: pointer}</style> <table class='table table-hover table-light'><thead><tr>  <th width='5%'>No.</th>  <th width='5%'>รหัส</th>  <th width='60%'>Job Function</th>  <th width='20%'>ฝ่าย</th>  <th width='10%'></th></tr></thead><tbody><?	$i = 1;	$sql2="SELECT 		d.*,		dl.department_name	FROM `job_function` d	LEFT JOIN `department` dl ON  		d.department_id = dl.department_id AND 		dl.mark_del = '0'	WHERE 		d.parent_id = '0' AND		d.mark_del = '0' ";	$result2=mysqli_query($connect, $sql2);	while ($row2 = mysqli_fetch_array($result2)) {?><tr style='font-weight: bold' bgcolor='#eeeeee'>	<td width='5%' did='<?=$row2['job_function_id']?>' class='csa_editable'><?=$i++?></td>	<td width='5%' did='<?=$row2['job_function_id']?>' class='csa_editable'><?=$row2['job_function_no']?></td>	<td width='60%' did='<?=$row2['job_function_id']?>' class='csa_editable'><?=$row2['job_function_name']?></td>	<td width='20%' did='<?=$row2['job_function_id']?>' class='csa_editable'><?=$row2['department_name']?></td>	<td><button type='button' class="btn btn-default btn-xs" onClick='document.location="job_function.php?action=add&add_id=<?=$row2['job_function_id']?>"'><i class='fa fa-plus'></i> เพิ่ม</button></td></tr><?			$sql = "SELECT 				d.*,				dl.department_name			FROM `job_function` d			LEFT JOIN `department` dl ON  				d.department_id = dl.department_id AND 				dl.mark_del = '0'			WHERE 				d.parent_id = '$row2[job_function_id]' AND				d.mark_del = '0' ";			$result3 = mysqli_query($connect, $sql);			while ($row3 = mysqli_fetch_array($result3)) {?><tr>	<td width='5%' did='<?=$row3['job_function_id']?>' class='csa_editable'><?=$i++?></td>	<td width='5%' did='<?=$row3['job_function_id']?>' class='csa_editable'><?=$row3['job_function_no']?></td>	<td width='60%' did='<?=$row3['job_function_id']?>' class='csa_editable'><?=$row3['job_function_name']?></td>	<td width='20%' did='<?=$row3['job_function_id']?>' class='csa_editable'><?=$row3['department_name']?></td>	<td></td></tr><?			}	}	?></tbody></table>			                  	<br>	<button type="submit" class="btn btn-danger" onClick="document.location='job_function.php?action=add'"><i class='fa fa-plus-circle'></i> เพิ่ม</button>		</div></section>	<?echo template_footer();?>