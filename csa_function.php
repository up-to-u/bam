<?php
$email_from = 'rms@bam.co.th';
function add_history($qx, $csa_department_id, $to_status, $remark) {	global $user_code, $user_id, $connect;		$sql = "INSERT INTO csa_department_change_history 	(csa_department_id, to_status, remark, user_id, user_code, create_date) 	VALUES 	('$csa_department_id', '$to_status', '$remark', '$user_id', '$user_code', now()) ";	$q = mysqli_query($connect, $sql);	$qx = ($qx and $q);			return $qx;}
function send_confirm_head_notification($csa_department_id, $uid) {
	global $email_from;
	
	$to = array($user_co_email);
	$cc = array();						
	$bcc = array();		
	$subject = 'แจ้งอนุมัติผลการประเมิน';
	$body = 'เรียน ฝ่ายบริหารความเสี่ยง ผลการประเมิน ฝ่ายงาน xxx<br>
<br>
ผู้อนุมัติของฝ่ายงาน xxx ได้ จัดอนุมัติผลการประเมินแล้ว<br>
จึงแจ้งมายังท่านเพื่อโปรดดำเนินการต่อไป<br>
<br>
<a href="'.$host.'/csa_approve.php?" target="_new">แสดงข้อมูล</a> ';

	$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
	if ($x) {
		echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
	}	
}

function send_confirm_notification($csa_department_id, $uid) {
	global $email_from;
	
	$to = array($user_co_email);
	$cc = array();						
	$bcc = array();		
	$subject = 'แจ้งยืนยันผลการประเมิน';
	$body = 'เรียน ผอ./ผู้อนุมัติ ผลการประเมิน ฝ่ายงาน xxx<br>
<br>
ผู้ทำแบบประเมินของฝ่ายงาน xxx ได้ จัดทำการประเมิน และยืนยันผลแล้ว<br>
จึงแจ้งมายังท่านเพื่อโปรดดำเนินการต่อไป<br>
<br>
<a href="'.$host.'/csa_approve.php?" target="_new">แสดงข้อมูล</a> ';

	$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
	if ($x) {
		echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
	}	
}
function send_unlock_notification($csa_department_id, $uid, $reject_reason) {
	global $email_from;
	
	$to = array($user_co_email);
	$cc = array();						
	$bcc = array();		
	$subject = 'แจ้งขอให้แก้ไขผลการประเมิน';
	$body = 'เรียน ผู้จัดทำการประเมิน ฝ่ายงาน xxx<br>
<br>
ตามที่ ผอ./ผู้อนุมัติผลการประเมินของฝ่ายงาน xxx ได้พิจารณาการประเมินของท่านแล้ว<br>
พบว่ามีต้องแก้ไขดังนี้<br>
'.$reject_reason.'<BR>
<br>
จึงแจ้งมายังท่านเพื่อโปรดดำเนินการแก้ไขให้ถูกต้อง และยืนยันนำส่งต่อไป<br>
<br>
<a href="'.$host.'/csa_user.php?" target="_new">แสดงข้อมูล</a> ';

	$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
	if ($x) {
		echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
	}	
}

function get_head_csa_uid($csa_department_id) {
	global $connect;
	$sql = "SELECT 
			u.user_id
		FROM csa_department c
		LEFT JOIN department d1 ON c.department_id = d1.department_id
		LEFT JOIN department d2 ON c.department_id2 = d2.department_id
		LEFT JOIN user u ON u.department_id = d2.department_id AND u.auth_csa=2
		WHERE 
			c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result1 = $stmt->get_result();
		if ($row1 = mysqli_fetch_assoc($result1)) {	
			return $row1['user_id'];
		}
	}	
	return 0;
}
function get_head_csa_ucode($csa_department_id) {
	global $connect;
	$u_list = array();
	$sql = "SELECT 
			u.code
		FROM csa_department c
		LEFT JOIN department d1 ON c.department_id = d1.department_id
		LEFT JOIN department d2 ON c.department_id2 = d2.department_id
		LEFT JOIN user u ON u.department_id = d2.department_id AND u.auth_csa=2
		WHERE 
			c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result1 = $stmt->get_result();
		while ($row1 = mysqli_fetch_assoc($result1)) {	
			$u_list[] = $row1['code'];
		}
	}	
	return $u_list;
}

function get_user_csa_uid($csa_department_id) {
	global $connect;
	$u_list = array();
	$sql = "SELECT 
			u.user_id
		FROM csa_department c
		LEFT JOIN department d3 ON c.department_id3 = d3.department_id
		LEFT JOIN user u ON u.department_id = d3.department_id AND u.auth_csa=1
		WHERE 
			c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result1 = $stmt->get_result();
		while ($row1 = mysqli_fetch_assoc($result1)) {	
			$u_list[] = $row1['user_id'];
		}
	}	
	return $u_list;
}
function get_user_csa_ucode($csa_department_id) {
	global $connect;
	$u_list = array();
	$sql = "SELECT 
			u.code
		FROM csa_department c
		LEFT JOIN department d3 ON c.department_id3 = d3.department_id
		LEFT JOIN user u ON u.department_id = d3.department_id AND u.auth_csa=1
		WHERE 
			c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result1 = $stmt->get_result();
		while ($row1 = mysqli_fetch_assoc($result1)) {	
			$u_list[] = $row1['code'];
		}
	}	
	return $u_list;
}

function get_head_loss_uid($csa_department_id) {
	global $connect;
	$sql = "SELECT 
			u.user_id
		FROM csa_department c
		LEFT JOIN department d1 ON c.department_id = d1.department_id
		LEFT JOIN department d2 ON c.department_id2 = d2.department_id
		LEFT JOIN department d3 ON c.department_id3 = d3.department_id
		LEFT JOIN user u ON u.department_id = d2.department_id AND u.auth_loss=2
		WHERE 
			c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result1 = $stmt->get_result();
		if ($row1 = mysqli_fetch_assoc($result1)) {	
			return $row1['user_id'];
		}
	}	
	return 0;
}

function get_head_csa_email($csa_department_id) {
	$head_uid = get_head_csa_uid($csa_department_id);
	return get_user_email($head_uid);
}
function get_head_loss_email($csa_department_id) {
	$head_uid = get_head_loss_uid($csa_department_id);
	return get_user_email($head_uid);
}
function get_user_email($user_id) {
	global $connect;
	
	$sql = "SELECT email FROM user WHERE user_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			return $row2['email'];
		}
	}		
	return '';
}
function get_user_profile_array($uid) {
	global $connect;
	$u_list = array();
	$sql = "SELECT * FROM user WHERE user_id IN (".implode(',', $uid).") ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $uid);
		$stmt->execute();
		$result2 = $stmt->get_result();
		while ($row2 = mysqli_fetch_assoc($result2)) {
			$u_list[] = array($row2['prefix'].$row2['name'].' '.$row2['surname'], $row2['department_id'], $row2['email']);
		}
	}	
	return $u_list;
}

function get_user_profile($uid) {
	global $connect;

	if ($uid>0) {
		$sql = "SELECT * FROM user WHERE user_id = ? ";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$stmt->bind_param('i', $uid);
			$stmt->execute();
			$result2 = $stmt->get_result();
			if ($row2 = mysqli_fetch_assoc($result2)) {
				return array($row2['prefix'].$row2['name'].' '.$row2['surname'], $row2['department_id'], $row2['email']);
			}
		}	
	}		
	return array();
}

function gen_risk_mat() {
	global $connect;
	
	$d = array();
	$sql2="SELECT * FROM `csa_risk_matrix` ";
	$result1=mysqli_query($connect, $sql2);
	while ($row1 = mysqli_fetch_array($result1)) {
		$d[$row1['csa_impact_id']][$row1['csa_likelihood_id']] = $row1['csa_risk_level']; 
	}
	$lv = array();
	for ($i=1; $i<=5; $i++) {
		$lv[] = risk_level_name($i);
	}
?>			
<div class='row'>
<div class='col-md-6'>
<table class='' border='0'>
<tr>
	<td colspan='8' align='center'><b>ตารางแสดงผลการวัดระดับความเสี่ยง (Risk Matrix)</b><br><br></td>
</tr>
<tr>
  <td rowspan='6' width='100'><img src='images/risk_matrix_axis_y.png'></td>
  <td width='60' align='center' style='text-align:center'></td>
  <td width='400' colspan='5' align='center' style='text-align:center'></td>
</tr>
<?
	$axis_y = array('Insignificant<br>1', 'Minor<br>2', 'Moderate<br>3', 'Major<br>4', 'Catastrophic<br>5');
	for ($i=5; $i>=1; $i--) {
?>
<tr>
	<td width='60' align='center' style='font-size:11px'><?=$axis_y[$i-1]?></td>
<?		for ($j=1; $j<=5; $j++) {
			$l = $d[$i][$j];
			$b = '#444444';
?>	
	<td width='80' align='center' bgcolor='<?=risk_level_color($l)?>' style='border: 1px solid <?=$b?>; height: 70px; font-weight:bold; font-size: 13px' ><?=risk_level_name($l)?></td>
<?		}?>	
</tr>
<?
	}	
?>
<tr style='font-size:11px' align='center'>
  <td></td>
  <td></td>
  <td>1<br>Very Low</td>
  <td>2<br>Low</td>
  <td>3<br>Medium</td>
  <td>4<br>High</td>
  <td>5<br>Very High</td>
</tr>
<tr>
  <td></td>
  <td></td>
  <td colspan='5' align='center'><img src='images/risk_matrix_axis_x.png'></td>
</tr>
</table>
<br>
</div>
</div>
<?	
}

function gen_job_function($dep_id, $csa_dep_id, $csa_year) {
	global $connect;
	
	$j_id_list = array();
	$j_list = array();
	$j_detail_list = array();
	$is_pass_all = false;
	$sql = "SELECT job_function_id FROM csa WHERE csa_year = '$csa_year' AND mark_del = '0' AND csa_department_id = '$csa_dep_id' ";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) {
		$j_list[] = $row2['job_function_id'];
	}

	$sql = "SELECT 
		* 
	FROM
		job_function 
	WHERE 
		(is_require = '1') AND
		mark_del = '0' AND 
		department_id3 = '$dep_id' 
	ORDER BY 
		job_function_no, job_function_id";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		$is_pass_all = true; 
		while ($row2 = mysqli_fetch_array($result2)) {
			if (in_array($row2['job_function_id'], $j_list)) { 
				$j_detail_list[] = '<font color="green"><i class="fa fa-check"></i></font> '.$row2['job_function_no'].' '.$row2['job_function_name'].'<BR>';
			} else {
				$j_detail_list[] = '<font color="red"><i class="fa fa-times"></i></font> '.$row2['job_function_no'].' '.$row2['job_function_name'].'<BR>';
				$is_pass_all = false;
				$j_id_list[] = $row2['job_function_id'];
			}
		}
?>
	<div class='row'>
	<div class='col-md-8'>					
	<div class='note note-<?=($is_pass_all==true) ? 'success' : 'danger'?>' style='font-size:13px'>
	<b>Job Function ที่จำเป็นต้องประเมิน</b><br>
<?					
		foreach ($j_detail_list as $j) {
			echo $j;
		}
?>
	</div>
	</div>
	<div class='col-md-4'>					
	<div style='font-size:13px'>
	ความหมายสัญลักษณ์ :<br>
	<font color="green"><i class="fa fa-check"></i></font> : สร้างรายการประเมินแล้ว<br>
	<font color="red"><i class="fa fa-times"></i></font> : ยังไม่ได้สร้างรายการประเมิน
	</div>
	</div>
	</div>
<?					
	}
	return $is_pass_all;
}

function gen_audit_history($csa_dep_id) {
	global $connect;
	$sql = "SELECT * FROM csa_department c WHERE c.csa_department_id = ? AND c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_dep_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {	
			$csa_year = $row2['csa_year'];	
			$department_id3 = $row2['department_id3'];				
?>
<style>
table.change_history thead tr td{
	font-weight: bold;
}	
table.change_history tr td{
	font-size: 12px;
}
.table-sm tbody tr td{
	padding: 5px;
}
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
</style>
<div class='row'>
<div class='col-xl-10 col-lg-10 col-md-10 col-sm-12'>	
<b>รายงานผลการตรวจสอบ</b><br>
<table class='table table-hover change_history'>
<thead>
<tr>
  <td width='15%'>วันที่ตรวจ</td>
  <td width='50%'>รายงานผลการตรวจสอบ</td>
  <td width='30%'>ผู้ตรวจสอบ</td>
  <td width='5%'>ปิดแล้ว</td>
</tr>
</thead>
<tbody>
<?
	$i = 1;
	$sql2="SELECT 
		d.*,
		d1.department_name AS dep1,
		d2.department_name AS dep2,
		d3.department_name AS dep3
	FROM `csa_audit` d
	LEFT JOIN `department` d1 ON  d.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  d.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  d.department_id3 = d3.department_id AND d3.mark_del = '0'
	WHERE 
		d.csa_year = '$csa_year' AND
		d.department_id3 = '$department_id3' AND
		d.mark_del = '0' 
	ORDER BY 
		audit_date";
	$result1=mysqli_query($connect, $sql2);
	if (mysqli_num_rows($result1)>0) {
		while ($row1 = mysqli_fetch_array($result1)) {
			if ($row1['auditor']==3) 
				$auditor = 'อื่นๆ : '.$row1['auditor_other'];
			else
				$auditor = auditor_name($row1['auditor']);
?>
<tr class='tr_sm'>
	<td width='15' did='<?=$row1['csa_audit_id']?>' class='csa_editable'><?=mysqldate2th_date($row1['audit_date'])?></td>
	<td width='50' did='<?=$row1['csa_audit_id']?>' class='csa_editable'><?=$row1['audit_desc']?></td>
	<td width='30%' did='<?=$row2['csa_audit_id']?>' class='csa_editable'><?=$auditor?></td>
	<td width='5%' did='<?=$row2['csa_audit_id']?>' class='csa_editable'><?if ($row1['is_close']==1) echo '<i class="fa fa-check"></i>'?></td>
</tr>
<?
		}
	} else {
		echo '<tr><td colspan="2">-ไม่มีข้อมูล-</td></tr>';
	}	
?>
</tbody>
</table>	
</div>			
</div>
<?	
		}
	}
}

function gen_change_history($csa_dep_id) {
	global $connect;

?>

<style>
table.change_history thead tr td{
	font-weight: bold;
}	
table.change_history tr td{
	font-size: 12px;
}
.table-sm tbody tr td{
	padding: 5px;
}
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
</style>		
		<div class='row'><div class='col-xl-6 col-lg-8 col-md-10 col-sm-12'>		
			<b>ประวัติการเปลี่ยนแปลง</b><br>
			<table class='table table-hover change_history'>
			<thead>
			<tr>
				<td width='28%'>การดำเนินการ</td>				<td width='27%'>สถานะ</td>
				<td width='20%'>วันที่</td>
				<td width='25%'>ผู้ดำเนินการ</td>
			</tr>
			</thead>
			<tbody>		
<?
		$sql = "SELECT 
			csa_department_change_history.*,
			s2.csa_department_status_id as s2_code,
			s2.csa_department_status_name as s2_name,
			u.code AS ucode,			CONCAT(u.prefix, u.name, ' ', u.surname) AS uname
		FROM csa_department_change_history
		JOIN csa_department_status s2 ON csa_department_change_history.to_status = s2.csa_department_status_id
		LEFT JOIN user u ON csa_department_change_history.user_id = u.user_id
		WHERE csa_department_id = '$csa_dep_id' 
		ORDER BY csa_department_change_history.create_date";
		$result2 = mysqli_query($connect, $sql);
		if (mysqli_num_rows($result2)>0) {
			while ($row2 = mysqli_fetch_array($result2)) {
?>
		<tr class='tr_sm'>
			<td><?=$row2['remark']?></td>			<td><?=$row2['s2_name']?></td>
			<td><?=mysqldate2th_datetime($row2['create_date'])?></td>
			<td><?=$row2['ucode']?> <?=$row2['uname']?></td>
		</tr>
<?
			}
		} else {
			echo '<tr><td colspan="4">-ไม่มี-</td></tr>';
		}
?>							
			</tbody>
			</table></div>			</div>			
<?
}



function htlm2text($h) {
	$h = strip_tags($h);
	$h = str_replace("\n", '<br>', $h);
	$h = str_replace(' ', '&nbsp;', $h);
	return $h;
}


function csa_status($r) {
	switch ($r) {
		case 0: return 'อยู่ระหว่างจัดทำ';
		case 1: return 'ประเมินแล้ว';
	}
}
function csa_status_color($r) {
	switch ($r) {
		case 0: return 'red';
		case 1: return 'green';
	}
}


function risk_level_color($r) {
	switch ($r) {
		case 0: return '';
/*		case 1: return '#9dff9c';
		case 2: return '#f5ff9c';
		case 3: return '#ffd29c';
		case 4: return '#ff9c9c';*/
		case 1: return '#00ff00';
		case 2: return '#ffff00';
		case 3: return '#ff9900';
		case 4: return '#ff0000';
	}
}
function risk_level_name($r) {
	switch ($r) {
		case 0: return '';
		case 1: return 'ต่ำ';
		case 2: return 'ปานกลาง';
		case 3: return 'สูง';
		case 4: return 'สูงมาก';
	}
}


function auditor_name($n) {
	switch ($n) {
		case 0: return 'Internal Audit';
		case 1: return 'BOT';
		case 2: return 'EY';
		case 3: return 'Others';
	}
}
?>