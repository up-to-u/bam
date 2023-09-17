<?
include('inc/include.inc.php');
include('csa_function.php');
echo template_header();

$submit = $_POST['submit'];
$action = $_GET['action'];
$edit_id = intval($_GET['edit_id']);

if ($edit_id>0) {
	
	$sql2 = "SELECT 
	a.*,
	aut0.auth_request_type_name AS auth_request_type_name_old,
	aut.auth_request_type_name,
	aut.is_csa_user,
	aut.is_csa_admin,			
	(SELECT CONCAT(prefix, name, ' ', surname) FROM user u WHERE u.code = a.user_code) AS uname,
	d.department_name AS csa_dep_name
	FROM auth_request a
	JOIN department d ON a.department_id = d.department_id
	LEFT JOIN auth_request_type aut0 ON a.auth_request_type_id_old = aut0.auth_request_type_id
	LEFT JOIN auth_request_type aut ON a.auth_request_type_id = aut.auth_request_type_id
	WHERE
		a.auth_request_id = '$edit_id' AND 
		a.mark_del = '0' ";
		
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$did = $row2['department_id'];
		$is_csa_user = $row2['is_csa_user'];
		$is_csa_admin = $row2['is_csa_admin'];
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-lock-open font-green"></i>
					<span class="caption-subject font-green sbold uppercase">จัดการ รายการคำขอ</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='user_request_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
			<form method='post' action='user_request_admin.php'>  
			<div class="row">
			<div class="col-md-4">
				<div class="form-group">
				  <label>รหัสพนักงาน</label>
				  <input type="text" class="form-control" name='request_user_code' readonly value='<?=$user_code?>'>
				</div>	
				<div class="form-group">
				  <label>ชื่อ นามสกุล</label>
				  <input type="text" class="form-control" readonly value='<?=$row2['uname']?>'>
				</div>	
				<div class="form-group">
				  <label>ฝ่ายประเมินความเสี่ยง</label>
				  <textarea rows='3' class="form-control" readonly><?=$row2['csa_dep_name']?></textarea>
				</div>	
				<div class="form-group">
				  <label>ประเภทคำขอ</label>
				  <input type="text" class="form-control" readonly value='<?=request_type($row2['auth_request_type'])?>'>
				</div>	
				<div class="form-group">
				  <label>สิทธิเดิม</label>
				  <input type="text" class="form-control" readonly value='<?=$row2['auth_request_type_name_old']?>'>
				</div>	
				<div class="form-group">
				  <label>สิทธิที่ต้องการ</label>
				  <input type="text" class="form-control" readonly value='<?=$row2['auth_request_type_name']?>'>
				</div>	
<?
	$error = 0;
	if ($is_csa_user==1 || $is_csa_admin==1) { 
?>
				<hr>
				<b>รายการประเมิน CSA ที่ต้องการ</b><br>
				<br>
<?
		$sql = "SELECT 
			csa_department.csa_department_id,
			csa_department.csa_year,
			department.department_name
		FROM csa_department 
		JOIN department ON csa_department.department_id3 = department.department_id
		WHERE 
		csa_department.department_id3 = '$did' AND 
		csa_department.mark_del = '0' ";
		$result1 = mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {
?>			
	<input type='radio' name='ref_csa_dep_id' value='<?=$row1['csa_department_id']?>' <?if ($row2['approve_status']>0) echo 'disabled'?>> <?=$row1['department_name']?> (ปี <?=$row1['csa_year']?>)<br>
<?			
			}
		} else {
			echo 'ยังไม่มีการสร้างรายการประเมิน กรุณาสร้างรายการประเมิน CSA ก่อน จึงจะพิจารณาอนุมัติได้';
			$error = 1;
		}
	}
	
	if ($error==0) {
?>				
				<br>
				<br>
				<hr>
				<b>การพิจารณา</b><br>
				<br>
				
				<div class="form-group">
				  <label>ผลการพิจารณา</label><br>
<? for ($i=0; $i<=2; $i++){ ?>				  
				  <label><input type="radio" name='approve_status' value='<?=$i?>' <? if ($row2['approve_status']==$i) echo 'checked'?> <?if ($row2['approve_status']>0) echo 'disabled'?>> <?=approve_status($i)?></label><br>
<?}?>				  
				</div>	
				<div class="form-group">
				  <label>ความเห็นประกอบ</label>
				  <textarea class="form-control" name='approve_comment' placeholder="ความเห็นประกอบ" rows='3' <?if ($row2['approve_status']>0) echo 'disabled'?>><?=$row2['approve_comment']?></textarea>
				</div>				
			</div>	
			</div>	
	
			<input type='hidden' name='update_id' value='<?=$edit_id?>'>
			<button type='button' class="btn btn-primary" onClick="document.location='user_request_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<? if ($row2['approve_status']==0) {?>			
			<button type='submit' name='submit' value='update' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
<? }?>			
<? } else {?>	
			<br>
			<br>
			<button type='button' class="btn btn-primary" onClick="document.location='user_request_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
			<br>
			<br>
<? }?>			
			</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
	}	

	echo template_footer();
	exit;
	
} else if ($submit == 'update') {
	$update_id = intval($_POST['update_id']);
	$approve_status = intval($_POST['approve_status']);
	$ref_csa_dep_id = intval($_POST['ref_csa_dep_id']);
	$approve_comment = addslashes($_POST['approve_comment']);
	
	if ($update_id>0) {
		if ($approve_status>=0) {

			$error = 0;
			$sql = "SELECT 
			a.*,
			aut.auth_request_type_name,
			aut.is_csa_user,
			aut.is_csa_admin,			
			aut.is_loss_admin,			
			aut0.is_csa_user AS is_csa_user_old,
			aut0.is_csa_admin AS is_csa_admin_old,			
			aut0.is_loss_admin AS is_loss_admin_old,			
			(SELECT CONCAT(prefix, name, ' ', surname) FROM user u WHERE u.code = a.user_code) AS uname,
			d.department_name AS csa_dep_name,
			csa_dep.csa_department_id
			FROM auth_request a
			JOIN department d ON a.department_id = d.department_id
			LEFT JOIN csa_department csa_dep ON a.department_id = csa_dep.department_id3
			LEFT JOIN auth_request_type aut0 ON a.auth_request_type_id_old = aut0.auth_request_type_id
			LEFT JOIN auth_request_type aut ON a.auth_request_type_id = aut.auth_request_type_id
			WHERE 
				a.auth_request_id = '$update_id' AND 
				a.mark_del = '0' ";
			$result2 = mysqli_query($connect, $sql);
			if ($row2 = mysqli_fetch_array($result2)) {
				$u_dep_id = $row2['department_id'];
				$csa_dep_id = $row2['csa_department_id'];
				$user_code = $row2['user_code'];
				$auth_request_type_id_old = $row2['auth_request_type_id_old'];
				$auth_request_type_id = $row2['auth_request_type_id'];
				$is_csa_user_old = $row2['is_csa_user_old'];
				$is_csa_admin_old = $row2['is_csa_admin_old'];
				$is_loss_admin_old = $row2['is_loss_admin_old'];
				$is_csa_user = $row2['is_csa_user'];
				$is_csa_admin = $row2['is_csa_admin'];
				$is_loss_admin = $row2['is_loss_admin'];
				
				$qx = true;	
				mysqli_autocommit($connect,FALSE);

				if ($approve_status==1) { // validate on approve
					if ($row2['auth_request_type']==1) { // add

						if ($is_csa_user==1 || $is_csa_admin==1) {
							if ($ref_csa_dep_id>0) {
								$error = check_auth_for_add($auth_request_type_id, $user_code, $ref_csa_dep_id);
								if ($error==0) {
									
									if ($is_csa_user==1) {
										$sql = "INSERT INTO csa_authorize (csa_department_id, csa_authorize_uid) VALUES ('$csa_dep_id', '$user_code')";
										$q = mysqli_query($connect, $sql);
										$qx = ($qx and $q);								
										echo "<font color='green'><b>ระบบได้เพิ่มสิทธิแล้ว</b></font><br>";
									}
									if ($is_csa_admin==1) {
										$sql = "INSERT INTO csa_authorize_approver (csa_department_id, csa_authorize_uid) VALUES ('$csa_dep_id', '$user_code')";
										$q = mysqli_query($connect, $sql);
										$qx = ($qx and $q);								
										echo "<font color='green'><b>ระบบได้เพิ่มสิทธิแล้ว</b></font><br>";
									}
								}
							} else {
								echo "<font color='red'><b>เกิดข้อผิดพลาด ท่านไม่ได้เลือกรายการประเมิน CSA</b></font><br>";
								$error = 9;
							}
							
						} else if ($is_loss_admin==1) {
							
							$sql = "UPDATE user SET auth_loss = 2 WHERE code = '$user_code' ";
							$q = mysqli_query($connect, $sql);
							$qx = ($qx and $q);								
							echo "<font color='green'><b>ระบบได้เพิ่มสิทธิแล้ว</b></font><br>";							
							
						}

						
					} else if ($row2['auth_request_type']==2) { // change
						$sql = "SELECT COUNT(*) AS num FROM csa_authorize WHERE csa_department_id = '$csa_dep_id' AND csa_authorize_uid = '$user_code'";
						$result2 = mysqli_query($connect, $sql);
						$row2 = mysqli_fetch_array($result2);	
						$cnt_user = intval($row2['num']);		
						
						$sql = "SELECT COUNT(*) AS num FROM csa_authorize_approver WHERE csa_department_id = '$csa_dep_id' AND csa_authorize_uid = '$user_code'";
						$result2 = mysqli_query($connect, $sql);
						$row2 = mysqli_fetch_array($result2);	
						$cnt_admin = intval($row2['num']);		
					
						//echo "[$is_csa_user_old][$is_csa_user] - [$cnt_user]<br>";
						//echo "[$is_csa_admin_old][$is_csa_admin] - [$cnt_admin]<br>";
						if ($is_csa_user_old==0 && $is_csa_user==1 && $is_csa_admin==0) { // add user
							if ($cnt_user==1) $error = 1;
						}
						if ($is_csa_user_old==1 && $is_csa_user==0) { // remove user
							if ($cnt_user==0) $error = 2;
						}
						if ($is_csa_admin_old==0 && $is_csa_admin==1 && $is_csa_user==0) { // add admin
							if ($cnt_admin==1) $error = 1;
						}
						if ($is_csa_admin_old==1 && $is_csa_admin==0) { // remove admin
							if ($cnt_admin==0) $error = 2;
						}
					
						if ($error==0) { // approve & change
							if ($is_csa_user_old==0 && $is_csa_user==1) { // add user
								$sql = "INSERT INTO csa_authorize (csa_department_id, csa_authorize_uid) VALUES 
								('$csa_dep_id', '$user_code')";
								$q = mysqli_query($connect, $sql);
								$qx = ($qx and $q);								
								echo "<font color='green'><b>ระบบได้เพิ่มสิทธิ user แล้ว</b></font><br>";
							}
							if ($is_csa_user_old==1 && $is_csa_user==0) { // remove user
								$sql = "DELETE FROM csa_authorize WHERE csa_department_id='$csa_dep_id' AND csa_authorize_uid='$user_code' ";
								$q = mysqli_query($connect, $sql);
								$qx = ($qx and $q);								
								echo "<font color='green'><b>ระบบได้ยกเลิกสิทธิ user แล้ว</b></font><br>";						
							}
							if ($is_csa_admin_old==0 && $is_csa_admin==1) { // add admin
								$sql = "INSERT INTO csa_authorize_approver (csa_department_id, csa_authorize_uid) VALUES 
								('$csa_dep_id', '$user_code')";
								$q = mysqli_query($connect, $sql);
								$qx = ($qx and $q);								
								echo "<font color='green'><b>ระบบได้เพิ่มสิทธิ admin แล้ว</b></font><br>";
							}
							if ($is_csa_admin_old==1 && $is_csa_admin==0) { // remove admin
								$sql = "DELETE FROM csa_authorize_approver WHERE csa_department_id='$csa_dep_id' AND csa_authorize_uid='$user_code' ";
								$q = mysqli_query($connect, $sql);
								$qx = ($qx and $q);								
								echo "<font color='green'><b>ระบบได้ยกเลิกสิทธิ admin แล้ว</b></font><br>";						
							}
						}

					} else if ($row2['auth_request_type']==3) { // remove
						$sql = "SELECT COUNT(*) AS num FROM csa_authorize WHERE csa_department_id = '$csa_dep_id' AND csa_authorize_uid = '$user_code'";
						$result2 = mysqli_query($connect, $sql);
						$row2 = mysqli_fetch_array($result2);	
						$cnt_user = intval($row2['num']);		
						$sql = "SELECT COUNT(*) AS num FROM csa_authorize_approver WHERE csa_department_id = '$csa_dep_id' AND csa_authorize_uid = '$user_code'";
						$result2 = mysqli_query($connect, $sql);
						$row2 = mysqli_fetch_array($result2);	
						$cnt_admin = intval($row2['num']);		
						
						if ($is_csa_user==1) { // remove user
							if ($cnt_user==0) $error = 2;
						}	
						if ($is_csa_admin==1) { // remove admin
							if ($cnt_admin==0) $error = 2;
						}	

						if ($is_loss_admin==1) {
							
							$sql = "UPDATE user SET auth_loss = '0' WHERE code = '$user_code' ";
							$q = mysqli_query($connect, $sql);
							$qx = ($qx and $q);					
							echo "<font color='green'><b>ระบบได้เพิ่มสิทธิแล้ว</b></font><br>";							
							
						}						

						if ($error==0) {
							if ($is_csa_user==1) { // remove user
								$sql = "DELETE FROM csa_authorize WHERE csa_department_id='$csa_dep_id' AND csa_authorize_uid='$user_code' ";
								$q = mysqli_query($connect, $sql);
								$qx = ($qx and $q);								
								echo "<font color='green'><b>ระบบได้ยกเลิกสิทธิ user แล้ว</b></font><br>";						
							}
							if ($is_csa_admin==1) { // remove admin
								$sql = "DELETE FROM csa_authorize_approver WHERE csa_department_id='$csa_dep_id' AND csa_authorize_uid='$user_code' ";
								$q = mysqli_query($connect, $sql);
								$qx = ($qx and $q);								
								echo "<font color='green'><b>ระบบได้ยกเลิกสิทธิ admin แล้ว</b></font><br>";						
							}
						}
					}
				}

				if ($error==0) {
					
					$sql = "UPDATE auth_request SET 
					approve_status = '$approve_status', 
					approve_comment = '$approve_comment',
					approve_user_id = '$user_id', 
					approve_date = now()
					WHERE 
					auth_request_id = '$update_id' ";
					
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
					mysqli_rollback($connect);
					if ($error==1) {
						echo "<font color='red'><b>เกิดข้อผิดพลาด รหัสพนักงาน ที่ขอเพิ่มสิทธิ มีในระบบแล้ว ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
					} else if ($error==2) {
						echo "<font color='red'><b>เกิดข้อผิดพลาด รหัสพนักงาน ที่ขอยกเลิกสิทธิ ยังไม่มีในระบบ ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
					} else if ($error==4) {
						echo "<font color='red'><b>เกิดข้อผิดพลาด มีสิทธิที่ขอในระบบแล้ว ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
					} else {
						echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
					}
				}
			}
		} else {
			echo "<font color='red'><b>เกิดข้อผิดพลาด ท่านระบุข้อมูลไม่ครบถ้วน ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
		}
	}
?>

<br>
<br>
<button type='button' class="btn btn-primary" onClick="document.location='user_request_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<br>

<?	
	echo template_footer();
	exit;
 
} 


	$auth_request_type = array();
	$sql = "SELECT * FROM auth_request_type WHERE mark_del = '0' ";
	$result = mysqli_query($connect, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$auth_request_type[] = array($row['auth_request_type_id'], $row['auth_request_type_name']);
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
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">รอดำเนินการ</a></li>
		<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="true">อนุมัติ</a></li>
		<li class=""><a href="#tab3" data-toggle="tab" aria-expanded="true">ไม่อนุมัติ</a></li>
	</ul>
	<div class="tab-content">
				<div class="tab-pane active" id="tab1">				
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%'>ลำดับ</td>
				<td width='5%'>รหัสพนักงาน</td>
				<td width='15%'>ชื่อ นามสกุล</td>
				<td width='10%'>ประเภทคำขอ</td>
				<td width='15%'>ฝ่าย</td>
				<td width='10%'>สิทธิ</td>
				<td width='12%'>วันที่ขอ</td>
				<td width='10%'>ผลการพิจารณา</td>
				<td width='12%'>วันที่พิจารณา</td>
			</tr>
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 
	a.*,
	aut.auth_request_type_name,
	(SELECT CONCAT(prefix, name, ' ', surname) FROM user u WHERE u.code = a.user_code LIMIT 1) AS uname,
	d.department_name AS csa_dep_name
	FROM auth_request a
	LEFT JOIN department d ON d.department_id = a.department_id
	LEFT JOIN auth_request_type aut ON a.auth_request_type_id = aut.auth_request_type_id
	WHERE 
		a.approve_status = '0' AND 
		a.mark_del = '0' ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr onClick='document.location="user_request_admin.php?edit_id=<?=$row2['auth_request_id']?>"' style='cursor:pointer'>
				<td><?=$i++?></td>
				<td><?=$row2['user_code']?></td>
				<td><?=$row2['uname']?></td>
				<td><?=request_type($row2['auth_request_type'])?></td>
				<td><?=$row2['csa_dep_name']?></td>
				<td><?=$row2['auth_request_type_name']?></td>
				<td><?=mysqldate2th_datetime($row2['create_date'])?></td>	
				<td><?=approve_status($row2['approve_status'])?></td>
				<td><?=mysqldate2th_datetime($row2['approve_date'])?></td>	
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='8'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
	}
?>
			</tbody>
			</table>		</div>		
		<div class="tab-pane" id="tab2">				
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%'>ลำดับ</td>
				<td width='5%'>รหัสพนักงาน</td>
				<td width='15%'>ชื่อ นามสกุล</td>
				<td width='10%'>ประเภทคำขอ</td>
				<td width='15%'>ฝ่ายประเมินความเสี่ยง</td>
				<td width='10%'>สิทธิ</td>
				<td width='12%'>วันที่ขอ</td>
				<td width='10%'>ผลการพิจารณา</td>
				<td width='12%'>วันที่พิจารณา</td>
			</tr>
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 
	a.*,
	aut.auth_request_type_name,
	(SELECT CONCAT(prefix, name, ' ', surname) FROM user u WHERE u.code = a.user_code LIMIT 1) AS uname,
	d.department_name AS csa_dep_name
	FROM auth_request a
	LEFT JOIN department d ON d.department_id = a.department_id
	LEFT JOIN auth_request_type aut ON a.auth_request_type_id = aut.auth_request_type_id
	WHERE 
		a.approve_status = '1' AND 
		a.mark_del = '0' ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr onClick='document.location="user_request_admin.php?edit_id=<?=$row2['auth_request_id']?>"' style='cursor:pointer'>
				<td><?=$i++?></td>
				<td><?=$row2['user_code']?></td>
				<td><?=$row2['uname']?></td>
				<td><?=request_type($row2['auth_request_type'])?></td>
				<td><?=$row2['csa_dep_name']?></td>
				<td><?=$row2['auth_request_type_name']?></td>
				<td><?=mysqldate2th_datetime($row2['create_date'])?></td>	
				<td><?=approve_status($row2['approve_status'])?></td>
				<td><?=mysqldate2th_datetime($row2['approve_date'])?></td>	
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='8'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
	}
?>
			</tbody>
			</table>		</div>		
		<div class="tab-pane" id="tab3">				
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%'>ลำดับ</td>
				<td width='5%'>รหัสพนักงาน</td>
				<td width='15%'>ชื่อ นามสกุล</td>
				<td width='10%'>ประเภทคำขอ</td>
				<td width='15%'>ฝ่ายประเมินความเสี่ยง</td>
				<td width='10%'>สิทธิ</td>
				<td width='12%'>วันที่ขอ</td>
				<td width='10%'>ผลการพิจารณา</td>
				<td width='12%'>วันที่พิจารณา</td>
			</tr>
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 
	a.*,
	aut.auth_request_type_name,
	(SELECT CONCAT(prefix, name, ' ', surname) FROM user u WHERE u.code = a.user_code LIMIT 1) AS uname,
	d.department_name AS csa_dep_name
	FROM auth_request a
	LEFT JOIN department d ON d.department_id = a.department_id
	LEFT JOIN auth_request_type aut ON a.auth_request_type_id = aut.auth_request_type_id
	WHERE 
		a.approve_status = '2' AND 
		a.mark_del = '0' ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr onClick='document.location="user_request_admin.php?edit_id=<?=$row2['auth_request_id']?>"' style='cursor:pointer'>
				<td><?=$i++?></td>
				<td><?=$row2['user_code']?></td>
				<td><?=$row2['uname']?></td>
				<td><?=request_type($row2['auth_request_type'])?></td>
				<td><?=$row2['csa_dep_name']?> <?=$row2['csa_section_name']?></td>
				<td><?=$row2['auth_request_type_name']?></td>
				<td><?=mysqldate2th_datetime($row2['create_date'])?></td>	
				<td><?=approve_status($row2['approve_status'])?></td>
				<td><?=mysqldate2th_datetime($row2['approve_date'])?></td>	
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='8'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
	}
?>
			</tbody>
			</table>		</div>
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


function check_existing_auth($permission_id, $u_code, $u_dep_id) {
	global $connect;
	$error = 0;
	
	$sql = "SELECT * FROM auth_request_type WHERE auth_request_type_id = '$permission_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {		
		$is_csa_user_old = $row2['is_csa_user'];
		$is_csa_admin_old = $row2['is_csa_admin'];
		$is_loss_admin_old = $row2['is_loss_admin'];
	}

	$sql = "SELECT 
		COUNT(*) AS num 
	FROM csa_authorize 
	JOIN csa_department ON csa_authorize.csa_department_id = csa_department.csa_department_id AND csa_department.mark_del = 0
	WHERE 
		csa_department.department_id3 = '$u_dep_id' AND 
		csa_authorize.csa_authorize_uid = '$u_code'";
	$result2 = mysqli_query($connect, $sql);
	$row2 = mysqli_fetch_array($result2);	
	$cnt_user = intval($row2['num']);
	
	$sql = "SELECT 
		COUNT(*) AS num 
	FROM csa_authorize_approver 
	JOIN csa_department ON csa_authorize_approver.csa_department_id = csa_department.csa_department_id AND csa_department.mark_del = 0
	WHERE 
		csa_department.department_id3 = '$u_dep_id' AND 
		csa_authorize_approver.csa_authorize_uid = '$u_code'";
	$result2 = mysqli_query($connect, $sql);
	$row2 = mysqli_fetch_array($result2);	
	$cnt_admin = intval($row2['num']);		
	
	
	$sql = "SELECT * FROM user WHERE code = '$u_code'";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {
		if (($is_csa_user_old==1 || $is_csa_admin_old==1) && $row2['auth_csa']==0 && $cnt_user==0 && $cnt_admin==0) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=2; /* add permission - no exist permission */
		}
		if ($is_csa_user_old==1 && $cnt_user==0 && ($row2['auth_csa']!=1 || $row2['auth_csa']!=3)) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=3; /* invalid permission */
		}
		if ($is_csa_admin_old==1 && $cnt_admin==0 && ($row2['auth_csa']!=2 || $row2['auth_csa']!=3)) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=3; /* invalid permission */
		}
		if (($is_loss_admin_old==1) && $row2['auth_loss']==0) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=2; /* no exist permission */
		}
		if ($is_loss_admin_old==1 && ($row2['auth_loss']!=2 || $row2['auth_csa']!=3)) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=3; /* invalid permission */
		}
/*			echo 'request_permission0=['.$request_permission0.'] request_permission=['.$request_permission.']<br>';
		echo 'auth_csa=['.$row2['auth_csa'].'] auth_loss=['.$row2['auth_loss'].']<br>';
		echo 'is_csa_user_old=['.$is_csa_user_old.'] is_csa_admin_old=['.$is_csa_admin_old.'] is_loss_admin_old=['.$is_loss_admin_old.']<br>';
		echo "[$sql] error=[$error]<br>";*/
	}
	return $error;
	/* 
		1=dup 
		2=not exist
		3=invalid
	*/
}

function check_auth_for_add($permission_id, $u_code, $csa_dep_id) {
	global $connect;
	$error = 0;
	
	$sql = "SELECT * FROM auth_request_type WHERE auth_request_type_id = '$permission_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {		
		$is_csa_user = $row2['is_csa_user'];
		$is_csa_admin = $row2['is_csa_admin'];
		$is_loss_admin = $row2['is_loss_admin'];
	}

	$sql = "SELECT 
		COUNT(*) AS num 
	FROM csa_authorize 
	WHERE 
		csa_authorize.csa_department_id = '$csa_dep_id' AND 
		csa_authorize.csa_authorize_uid = '$u_code'";
	$result2 = mysqli_query($connect, $sql);
	$row2 = mysqli_fetch_array($result2);	
	$cnt_user = intval($row2['num']);
	
	$sql = "SELECT 
		COUNT(*) AS num 
	FROM csa_authorize_approver 
	WHERE 
		csa_authorize_approver.csa_department_id = '$csa_dep_id' AND 
		csa_authorize_approver.csa_authorize_uid = '$u_code'";
	$result2 = mysqli_query($connect, $sql);
	$row2 = mysqli_fetch_array($result2);	
	$cnt_admin = intval($row2['num']);		
	
	
	$sql = "SELECT * FROM user WHERE code = '$u_code'";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {
		if ($is_csa_user==1 && (($row2['auth_csa']==1 || $row2['auth_csa']==3) && $cnt_user>0)) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=4; /* dup permission */
		}
		if ($is_csa_admin==1 && (($row2['auth_csa']==2 || $row2['auth_csa']==3) && $cnt_admin>0)) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=4; /* dup permission */
		}
		if ($is_loss_admin==1 && ($row2['auth_loss']==2 || $row2['auth_loss']==3)) { /* 0=none, 1=user, 2=approver, 3=both */
			$error=4; /* no exist permission */
		}
	}
	return $error;
	/* 
		4=dup permission
	*/
}
?>