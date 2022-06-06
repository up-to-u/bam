<?
include('inc/include.inc.php');
include('csa_function.php');
echo template_header();

$submit = $_POST['submit'];
$action = $_GET['action'];
$del_csa_dep_id = intval($_GET['del_csa_dep_id']);

if ($del_csa_dep_id>0) {
	
	$sql = "SELECT COUNT(*) FROM auth_request WHERE auth_request_id='$del_csa_dep_id' AND user_id = '$user_id' AND mark_del = '0' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {
		$qx = true;	
		mysqli_autocommit($connect,FALSE);
		
		$sql = "UPDATE auth_request SET mark_del = 1 WHERE auth_request_id='$del_csa_dep_id' AND user_id = '$user_id' AND mark_del = '0' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	

		if ($qx) {
			mysqli_commit($connect);
			savelog('RCSA-UPDATE-PLAN ['.$del_pid.']');
			echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
		} else {
			mysqli_rollback($connect);			
			echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
		}
	}	
}			
			
if ($submit == 'add') {
	
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$auth_request_type = 1;
	$request_permission = intval($_POST['request_permission']);
	$request_csa_dep = intval($_POST['request_csa_dep']);
	$request_user_code = addslashes($_POST['request_user_code']);
	
	if ($request_user_code!='' && $request_permission>0 && $request_csa_dep>0) {
		$error = 0;
		
		$sql = "SELECT COUNT(*) AS num FROM auth_request WHERE 
		user_code = '$request_user_code' AND 
		auth_request_type = '$auth_request_type' AND
		auth_request_type_id = '$request_permission' AND
		csa_department_id = '$request_csa_dep' AND 
		approve_status = '0' AND
		mark_del = '0' ";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);
		if ($row2[0]>0) $error = 1;
		
		if ($error==0) {
			$sql = "INSERT INTO auth_request (user_code, user_id, auth_request_type, auth_request_type_id, csa_department_id, create_date)
					VALUES ('$request_user_code', '$user_id', '$auth_request_type','$request_permission', '$request_csa_dep', now())";
			
			$q = mysqli_query($connect, $sql);
			$insert_id = mysqli_insert_id($connect);
			$qx = ($qx and $q);	
		
			if ($qx) {
				mysqli_commit($connect);
				echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
			} else {
				mysqli_rollback($connect);
				echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}
		} else {
			echo "<font color='red'><b>เกิดข้อผิดพลาด รายการคำขอของท่านเคยมีในระบบแล้ว ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
		}
	} else {
		echo "<font color='red'><b>เกิดข้อผิดพลาด ท่านระบุข้อมูลไม่ครบถ้วน ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
	}
?>

<br>
<br>
<button type='button' class="btn btn-primary" onClick="document.location='user_request.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<br>

<?	
	echo template_footer();
	exit;
 
} else if ($submit == 'change') {
	
	$qx = true;	
	mysqli_autocommit($connect,FALSE);

	$auth_request_type = 2;
	$request_permission0 = intval($_POST['request_permission0']);
	$request_permission = intval($_POST['request_permission']);
	$request_csa_dep = intval($_POST['request_csa_dep']);
	$request_user_code = addslashes($_POST['request_user_code']);

	if ($request_user_code!='' && $request_permission0!=$request_permission && $request_permission0>0 && $request_permission>0) {
		$error = 0;
		
		$sql = "SELECT COUNT(*) AS num FROM auth_request WHERE 
		user_code = '$request_user_code' AND 
		auth_request_type = '$auth_request_type' AND
		auth_request_type_id = '$request_permission' AND
		csa_department_id = '$request_csa_dep' AND 
		approve_status = '0' AND
		mark_del = '0' ";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);
		if ($row2[0]>0) $error = 1;
		
		$sql = "SELECT * FROM auth_request_type WHERE auth_request_type_id = '$request_permission0' ";
		$result2 = mysqli_query($connect, $sql);
		if ($row2 = mysqli_fetch_array($result2)) {		
			$is_csa_user_old = $row2['is_csa_user'];
			$is_csa_admin_old = $row2['is_csa_admin'];
		}
		$sql = "SELECT COUNT(*) AS num FROM csa_authorize WHERE csa_department_id = '$request_csa_dep' AND csa_authorize_uid = '$request_user_code'";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);	
		$cnt_user = intval($row2['num']);		
		$sql = "SELECT COUNT(*) AS num FROM csa_authorize_approver WHERE csa_department_id = '$request_csa_dep' AND csa_authorize_uid = '$request_user_code'";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);	
		$cnt_admin = intval($row2['num']);	
		if ($is_csa_user_old==1 && $cnt_user==0) {
			$error = 2;
		}
		if ($is_csa_admin_old==1 && $cnt_admin==0) {
			$error = 2;
		}
		
		
		if ($error==0) {		
			$sql = "INSERT INTO auth_request (user_code, user_id, auth_request_type, auth_request_type_id_old, auth_request_type_id, csa_department_id, create_date)
				VALUES ('$request_user_code', '$user_id', '$auth_request_type', '$request_permission0','$request_permission', '$request_csa_dep', now())";
			
			$q = mysqli_query($connect, $sql);
			$insert_id = mysqli_insert_id($connect);
			$qx = ($qx and $q);	
		
			if ($qx) {
				mysqli_commit($connect);
				echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว1</b></font><br><br>";
			} else {
				mysqli_rollback($connect);
				echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}
		} else {
			if ($error==1) {
				echo "<font color='red'><b>เกิดข้อผิดพลาด รายการคำขอของท่านเคยมีในระบบแล้ว ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			} else if ($error==2) {
				echo "<font color='red'><b>เกิดข้อผิดพลาด ท่านไม่มีสิทธิเดิมในระบบ ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}
		}			
	} else {
		echo "<font color='red'><b>เกิดข้อผิดพลาด ท่านระบุข้อมูลไม่ครบถ้วน ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
	}
?>

<br>
<br>
<button type='button' class="btn btn-primary" onClick="document.location='user_request.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<br>

<?	
	echo template_footer();
	exit;
 
} else if ($submit == 'remove') {
	
	$qx = true;	
	mysqli_autocommit($connect,FALSE);

	$auth_request_type = 3;
	$request_permission = intval($_POST['request_permission']);
	$request_csa_dep = intval($_POST['request_csa_dep']);
	$request_user_code = addslashes($_POST['request_user_code']);
	
	if ($request_user_code!='' && $request_permission>0) {
		$error = 0;
		
		$sql = "SELECT COUNT(*) AS num FROM auth_request WHERE 
		user_code = '$request_user_code' AND 
		auth_request_type = '$auth_request_type' AND
		auth_request_type_id = '$request_permission' AND
		csa_department_id = '$request_csa_dep' AND 
		approve_status = '0' AND
		mark_del = '0' ";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);
		if ($row2[0]>0) $error = 1;

		$sql = "SELECT * FROM auth_request_type WHERE auth_request_type_id = '$request_permission' ";
		$result2 = mysqli_query($connect, $sql);
		if ($row2 = mysqli_fetch_array($result2)) {		
			$is_csa_user_old = $row2['is_csa_user'];
			$is_csa_admin_old = $row2['is_csa_admin'];
		}
		$sql = "SELECT COUNT(*) AS num FROM csa_authorize WHERE csa_department_id = '$request_csa_dep' AND csa_authorize_uid = '$request_user_code'";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);	
		$cnt_user = intval($row2['num']);		
		$sql = "SELECT COUNT(*) AS num FROM csa_authorize_approver WHERE csa_department_id = '$request_csa_dep' AND csa_authorize_uid = '$request_user_code'";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);	
		$cnt_admin = intval($row2['num']);	
		if ($is_csa_user_old==1 && $cnt_user==0) {
			$error = 2;
		}
		if ($is_csa_admin_old==1 && $cnt_admin==0) {
			$error = 2;
		}
		
		if ($error==0) {		
			$sql = "INSERT INTO auth_request (user_code, user_id, auth_request_type, auth_request_type_id, csa_department_id, create_date)
					VALUES ('$request_user_code', '$user_id', '$auth_request_type','$request_permission', '$request_csa_dep', now())";
			
			$q = mysqli_query($connect, $sql);
			$insert_id = mysqli_insert_id($connect);
			$qx = ($qx and $q);	
		
			if ($qx) {
				mysqli_commit($connect);
				echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
			} else {
				mysqli_rollback($connect);
				echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}
		} else {
			if ($error==1) {
				echo "<font color='red'><b>เกิดข้อผิดพลาด รายการคำขอของท่านเคยมีในระบบแล้ว ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			} else if ($error==2) {
				echo "<font color='red'><b>เกิดข้อผิดพลาด ท่านไม่มีสิทธิเดิมในระบบ ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}
		}				
	} else {
		echo "<font color='red'><b>เกิดข้อผิดพลาด ท่านระบุข้อมูลไม่ครบถ้วน ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
	}
?>

<br>
<br>
<button type='button' class="btn btn-primary" onClick="document.location='user_request.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<br>

<?	
	echo template_footer();
	exit;
 
} 

	$view_year = date('Y')+543;

	$auth_request_type = array();
	$sql = "SELECT * FROM auth_request_type WHERE mark_del = '0' ";
	$result = mysqli_query($connect, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$auth_request_type[] = array($row['auth_request_type_id'], $row['auth_request_type_name']);
	}
	
	$csa_dep_list = array();
	$sql = "SELECT 
		c.*,
		d.department_name AS dep_name1,
		d2.department_name AS dep_name2
	FROM csa_department c
	JOIN department d ON c.department_id3 = d.department_id 
	JOIN department d2 ON c.department_id2 = d2.department_id 
	WHERE 
		c.csa_year = '$view_year' AND 
		c.mark_del = '0' 
	ORDER BY
		dep_name2,
		dep_name1";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) {	
		$csa_dep_list[] = array($row2['csa_department_id'], $row2['dep_name2'].' - '.$row2['dep_name1'], $row2['department_name']);
	}
?>

<script>
$(function () {
	save_tab();
});  


function save_tab() {
	if (location.hash) {
	  $('a[href=\'' + location.hash + '\']').tab('show');
	}
	var activeTab = localStorage.getItem('activeTab');
	if (activeTab) {
	  $('a[href="' + activeTab + '"]').tab('show');
	}

	$('body').on('click', 'a[data-toggle=\'tab\']', function (e) {
	  e.preventDefault()
	  var tab_name = this.getAttribute('href')
	  if (history.pushState) {
		history.pushState(null, null, tab_name)
	  }
	  else {
		location.hash = tab_name
	  }
	  localStorage.setItem('activeTab', tab_name)

	  $(this).tab('show');
	  return false;
	});
	$(window).on('popstate', function () {
	  var anchor = location.hash ||
		$('a[data-toggle=\'tab\']').first().attr('href');
	  $('a[href=\'' + anchor + '\']').tab('show');
	});	
}
</script>

<br>
		
	
<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">ขอเพิ่มสิทธิ์</a></li>
		<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="true">ขอเปลี่ยนแปลงสิทธิ์</a></li>
		<li class=""><a href="#tab3" data-toggle="tab" aria-expanded="true">ขอยกเลิกสิทธิ์</a></li>		<li class=""><a href="#tab4" data-toggle="tab" aria-expanded="true">ประวัติรายการคำขอ</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">					<form method='post' action='user_request.php'>  
			<div class="row">			<div class="col-md-4">				<div class="form-group">				  <label>รหัสพนักงาน</label>				  <input type="text" class="form-control" name='request_user_code' readonly value='<?=$user_code?>'>				</div>					<div class="form-group">				  <label>สิทธิที่ต้องการ</label>				  <select name='request_permission' class="form-control" required>					<option value=''>--เลือก--</option>
<? foreach ($auth_request_type as $a) {?>										<option value='<?=$a[0]?>'><?=$a[1]?></option>
<?}?>									  </select>				</div>	
				<div class="form-group">				  <label>ฝ่ายประเมินความเสี่ยง ที่ต้องการ (<?=$view_year?>)</label>				  <select name='request_csa_dep' class="form-control" required>					<option value=''>--เลือก--</option>
<? foreach ($csa_dep_list as $c) {?>										<option value='<?=$c[0]?>'><?=$c[1]?> <?=$c[2]?></option>
<?}?>									  </select>				</div>					<br>				<button type='submit' name='submit' class='btn btn-primary' value='add'>ยื่นคำร้อง</button>				<br>				<br>			</div>				</div>				</form>
		</div>
		<div class="tab-pane" id="tab2">				<form method='post' action='user_request.php'>  
			<div class="row">			<div class="col-md-4">				<div class="form-group">				  <label>รหัสพนักงาน</label>				  <input type="text" class="form-control" name='request_user_code' readonly value='<?=$user_code?>'>				</div>					<div class="form-group">				  <label>สิทธิเดิม</label>				  <select name='request_permission0' class="form-control" required>					<option value=''>--เลือก--</option><? foreach ($auth_request_type as $a) {?>					
					<option value='<?=$a[0]?>'><?=$a[1]?></option>
<?}?>
				  </select>				</div>					<div class="form-group">				  <label>สิทธิใหม่</label>				  <select name='request_permission' class="form-control" required>					<option value=''>--เลือก--</option><? foreach ($auth_request_type as $a) {?>					
					<option value='<?=$a[0]?>'><?=$a[1]?></option>
<?}?>	
				  </select>				</div>	
				<div class="form-group">
				  <label>ฝ่ายประเมินความเสี่ยง ที่ต้องการ (<?=$view_year?>)</label>
				  <select name='request_csa_dep' class="form-control" required>
					<option value=''>--เลือก--</option>
<? foreach ($csa_dep_list as $c) {?>					
					<option value='<?=$c[0]?>'><?=$c[1]?> <?=$c[2]?></option>
<?}?>					
				  </select>
				</div>								<br>				<button type='submit' name='submit' class='btn btn-primary' value='change'>ยื่นคำร้อง</button>				<br>				<br>			</div>				</div>				</form>
		</div>
		<div class="tab-pane" id="tab3">				<form method='post' action='user_request.php'>  
			<div class="row">			<div class="col-md-4">				<div class="form-group">				  <label>รหัสพนักงาน</label>				  <input type="text" class="form-control" name='request_user_code' readonly value='<?=$user_code?>'>				</div>					<div class="form-group">				  <label>สิทธิที่ต้องการยกเลิก</label>				  <select name='request_permission' class="form-control" required>					<option value=''>--เลือก--</option><? foreach ($auth_request_type as $a) {?>					
					<option value='<?=$a[0]?>'><?=$a[1]?></option>
<?}?>	
				  </select>				</div>
				<div class="form-group">
				  <label>ฝ่ายประเมินความเสี่ยง ที่ต้องการ (<?=$view_year?>)</label>
				  <select name='request_csa_dep' class="form-control" required>
					<option value=''>--เลือก--</option>
<? foreach ($csa_dep_list as $c) {?>					
					<option value='<?=$c[0]?>'><?=$c[1]?> <?=$c[2]?></option>
<?}?>					
				  </select>
				</div>								<br>				<button type='submit' name='submit' class='btn btn-primary' value='remove'>ยื่นคำร้อง</button>				<br>				<br>			</div>				</div>				</form>
		</div>						<div class="tab-pane" id="tab4">				<table class='table table-hover table-light'>			<thead>			<tr>				<td width='4%'>ลำดับ</td>				<td width='5%'>รหัสพนักงาน</td>				<td width='15%'>ชื่อ นามสกุล</td>				<td width='10%'>ประเภทคำขอ</td>
				<td width='15%'>ฝ่ายประเมินความเสี่ยง</td>				<td width='10%'>สิทธิที่ขอ</td>				<td width='12%'>วันที่ขอ</td>				<td width='10%'>ผลการพิจารณา</td>				<td width='12%'>วันที่พิจารณา</td>			</tr>			</thead>			<tbody><?	$i=1;		$sql = "SELECT 
	a.*,
	aut.auth_request_type_name,
	(SELECT CONCAT(prefix, name, ' ', surname) FROM user u WHERE u.code = a.user_code) AS uname,
	c.department_name AS csa_section_name,
	d.department_name AS csa_dep_name
	FROM auth_request a
	JOIN csa_department c ON a.csa_department_id = c.csa_department_id
	JOIN department d ON c.department_id3 = d.department_id
	LEFT JOIN auth_request_type aut ON a.auth_request_type_id = aut.auth_request_type_id
	WHERE 
		a.user_id = '$user_id' AND 
		a.mark_del = '0' 
	ORDER BY 
		a.create_date DESC";
	$result2 = mysqli_query($connect, $sql);	if (mysqli_num_rows($result2)>0) {		while ($row2 = mysqli_fetch_array($result2)) {?>			<tr>				<td><?=$i++?></td>				<td><?=$row2['user_code']?></td>
				<td><?=$row2['uname']?></td>
				<td><?=request_type($row2['auth_request_type'])?></td>
				<td><?=$row2['csa_dep_name']?> <?=$row2['csa_section_name']?></td>
				<td><?=$row2['auth_request_type_name']?></td>				<td><?=mysqldate2th_datetime($row2['create_date'])?></td>					<td><?=approve_status($row2['approve_status'])?></td>
				<td>
<?
			if ($row2['approve_status']==0) {?>
				<a href="user_request.php?del_csa_dep_id=<?=$row2['auth_request_id']?>" onClick='return confirm("Confirm Delete?")' class="btn btn-primary btn-xs"><i class='fa fa-times'></i> ยกเลิก</a>
			<? } else {?>				
				<?=mysqldate2th_datetime($row2['approve_date'])?>
			<? } ?>				
				</td>	
			</tr><?		}	} else {		?>						<tr>				<td colspan='8'>-ยังไม่มีข้อมูล-</td>			</tr><?	}?>			</tbody>			</table>		</div>
	</div></div>
		  
<?
echo template_footer();

function request_type($r) {
	switch ($r) {
		case 0: return '';
		case 1: return 'ขอเพิ่มสิทธิ์';
		case 2: return 'ขอเปลี่ยนแปลงสิทธิ์';
		case 3: return 'ขอยกเลิกสิทธิ์';
	}
}
function approve_status($r) {
	switch ($r) {
		case 0: return 'รอพิจารณา';
		case 1: return 'อนุมัติ';
		case 2: return 'ไม่อนุมัติ';
	}
}
?>