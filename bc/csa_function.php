<?php

function add_history($qx, $csa_department_id, $to_status, $remark) {	
	global $user_code, $user_id, $connect;		
	
	$sql = "INSERT INTO csa_department_change_history 	(csa_department_id, to_status, remark, user_id, user_code, create_date)
 	VALUES 	('$csa_department_id', '$to_status', '$remark', '$user_id', '$user_code', now()) ";	
	$q = mysqli_query($connect, $sql);	
	$qx = ($qx and $q);			
	return $qx;
}

function send_back_comment_notification_1($csa_department_id, $comment) { /* ส่วนที่ 1 */
	global $email_from, $connect, $url_prefix, $user_code, $user_id;

	$to = array();
	$department_name = '';
	$csa_year = '';
	$sql = "SELECT 
		user.email,
		d.department_name,
		c_dep.csa_year
	FROM csa_authorize_approver c
	JOIN csa_department c_dep ON c.csa_department_id = c_dep.csa_department_id
	JOIN department d ON c_dep.department_id3 = d.department_id
	JOIN user ON user.code = c.csa_authorize_uid
	WHERE 
		c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		while ($row2 = mysqli_fetch_assoc($result2)) {
			$to[] = trim($row2['email']);
			$department_name = $row2['department_name'];
			$csa_year = $row['csa_year'];
		}
	}	
	if ($csa_year=='' || $csa_year==0) $csa_year = date('Y')+543;

	if (count($to)>0) {
		$cc = array();						
		$bcc = array();		
		$subject = 'ผู้ดูแลระบบมีความเห็นเพื่อให้แก้ไข แบบประเมิน CSA ส่วนที่ 1';
		$body = 'เรียน ผู้อนุมัติประเมิน CSA '.$department_name.'<br>
	<br>
	เนื่องจากฝ่ายบริหารความเสี่ยง ได้ตรวจสอบ แบบประเมิน CSA ของ '.$department_name.' <br>
	และมีความเห็นเพื่อการปรับปรุงแบบประเมิน ส่วนที่ 1 ดังนี้<br>
	'.$comment.'<br>
	<br>
	จึงแจ้งมายังท่านเพื่อโปรดดำเนินการตามความเห็นต่อไป<br>
	<br>
	<a href="'.$url_prefix.'csa_approve.php?q_id='.$csa_department_id.'&view_year='.$csa_year.'" target="_new">แสดงข้อมูล</a> ';

		$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
		if ($x) {
			echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
		} else {
			echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
		}		
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ไม่พบ Email ผู้ทำประเมิน ระบบไม่สามารถเมลได้</b></font><br>";
	}	
}

function send_back_comment_notification_2($csa_department_id, $comment) { /* ส่วนที่ 2 */
	global $email_from, $connect, $url_prefix, $user_code, $user_id;

	$to = array();
	$department_name = '';
	$csa_year = '';
	$sql = "SELECT 
		user.email,
		d.department_name,
		c_dep.csa_year
	FROM csa_authorize c
	JOIN csa_department c_dep ON c.csa_department_id = c_dep.csa_department_id
	JOIN department d ON c_dep.department_id3 = d.department_id
	JOIN user ON user.code = c.csa_authorize_uid
	WHERE 
		c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		while ($row2 = mysqli_fetch_assoc($result2)) {
			$to[] = trim($row2['email']);
			$department_name = $row2['department_name'];
			$csa_year = $row['csa_year'];
		}
	}
	if ($csa_year=='' || $csa_year==0) $csa_year = date('Y')+543;

	if (count($to)>0) {
		$cc = array();						
		$bcc = array();		
		$subject = 'ผู้ดูแลระบบมีความเห็นเพื่อให้แก้ไข แบบประเมิน CSA ส่วนที่ 2';
		$body = 'เรียน ผู้ประเมิน CSA '.$department_name.'<br>
	<br>
	เนื่องจากฝ่ายบริหารความเสี่ยง ได้ตรวจสอบ แบบประเมิน CSA ของ '.$department_name.' <br>
	และมีความเห็นเพื่อการปรับปรุงแบบประเมิน ส่วนที่ 2 ดังนี้<br>
	'.$comment.'<br>
	<br>
	จึงแจ้งมายังท่านเพื่อโปรดดำเนินการตามความเห็นต่อไป<br>
	<br>
	<a href="'.$url_prefix.'csa_user.php?view_dep_id='.$csa_department_id.'&view_year='.$csa_year.'" target="_new">แสดงข้อมูล</a> ';

		$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
		if ($x) {
			echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
		} else {
			echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
		}		
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ไม่พบ Email ผู้ทำประเมิน ระบบไม่สามารถเมลได้</b></font><br>";
	}
}

function send_confirm_head_notification($csa_department_id, $uid) {
	global $email_from, $connect, $url_prefix, $user_code, $user_id;

	$department_name = '';
	$sql = "SELECT 
		c_dep.csa_year,
		d.department_name
	FROM csa_department c_dep 
	JOIN department d ON c_dep.department_id3 = d.department_id
	WHERE 
		c_dep.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$department_name = $row2['department_name'];
			$csa_year = $row['csa_year'];
		}
	}


	$to = array();
	$sql = "SELECT 
		user.email,
		d.department_name
	FROM csa_authorize_approver c
	JOIN csa_department c_dep ON c.csa_department_id = c_dep.csa_department_id
	JOIN department d ON c_dep.department_id3 = d.department_id
	JOIN user ON user.code = c.csa_authorize_uid
	WHERE 
		c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		while ($row2 = mysqli_fetch_assoc($result2)) {
			$to[] = $row2['email'];
		}
	}

	$cc = array();						
	$bcc = array();		
	$subject = 'แจ้งอนุมัติผลการประเมิน CSA';
	$body = 'เรียน ฝ่ายบริหารความเสี่ยง ผลการประเมิน '.$department_name.'<br>
<br>
ผู้อนุมัติของ'.$department_name.' ได้อนุมัติผลการประเมินแล้ว<br>
จึงแจ้งมายังท่านเพื่อโปรดดำเนินการต่อไป<br>
<br>
<a href="'.$url_prefix.'csa_admin.php?edit_id='.$csa_department_id.'&edit_year='.$csa_year.'" target="_new">แสดงข้อมูล</a> ';

	$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
	if ($x) {
		echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
	}	
}

function send_confirm_notification($csa_department_id, $uid, $period) {
	global $email_from, $connect, $url_prefix, $user_code, $user_id;

	$to = array();
	$department_name = '';

	$sql = "SELECT 
		user.email,
		d.department_name
	FROM csa_authorize_approver c
	JOIN csa_department c_dep ON c.csa_department_id = c_dep.csa_department_id
	JOIN department d ON c_dep.department_id3 = d.department_id
	JOIN user ON user.code = c.csa_authorize_uid
	WHERE 
		c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		while ($row2 = mysqli_fetch_assoc($result2)) {
			$to[] = $row2['email'];
			$department_name = $row2['department_name'];
		}
	}
	
	$cc = array();						
	$bcc = array();		
	$subject = 'แจ้งยืนยันผลการประเมิน CSA ครั้งที่ '.$period;
	$body = 'เรียน ผอ./ผู้อนุมัติ ผลการประเมิน '.$department_name.'<br>
<br>
ผู้ทำแบบประเมิน '.$department_name.' ได้จัดทำการประเมินความเสี่ยง CSA ครั้งที่ '.$period.'<br>
และได้ยืนยันผลประเมินในระบบแล้ว จึงแจ้งมายังท่านเพื่อโปรดดำเนินการต่อไป<br>
<br>
<a href="'.$url_prefix.'csa_approve.php?" target="_new">แสดงข้อมูล</a> ';

	$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
	if ($x) {
		echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
	}	
}
function send_unlock_notification($csa_department_id, $uid, $reject_reason, $period) {
	global $email_from, $connect, $url_prefix, $user_code, $user_id;
	$to = array();
	$department_name = '';

	$sql = "SELECT 
		user.email,
		d.department_name,
		c_dep.csa_year
	FROM csa_authorize c
	JOIN csa_department c_dep ON c.csa_department_id = c_dep.csa_department_id
	JOIN department d ON c_dep.department_id3 = d.department_id
	JOIN user ON user.code = c.csa_authorize_uid
	WHERE 
		c.csa_department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		while ($row2 = mysqli_fetch_assoc($result2)) {
			$to[] = trim($row2['email']);
			$department_name = $row2['department_name'];
			$csa_year = $row['csa_year'];
		}
	}
	
	$cc = array();						
	$bcc = array();		
	$subject = 'แจ้งขอให้แก้ไขผลการประเมิน CSA ครั้งที่ '.$period;
	$body = 'เรียน ผู้จัดทำการประเมิน '.$department_name.'<br>
<br>
ตามที่ท่านได้ทำแบบประเมิน CSA ครั้งที่ '.$period.' ของ '.$department_name.' <br>
ผู้อนุมัติ ได้พิจารณารายการประเมินของท่านแล้ว<br>
พบว่ามีต้องแก้ไขดังนี้<br>
'.html2text($reject_reason).'<BR>
<br>
จึงแจ้งมายังท่านเพื่อโปรดดำเนินการแก้ไขให้ถูกต้อง และยืนยันนำส่งต่อไป<br>
<br>
<a href="'.$url_prefix.'csa_user.php?" target="_new">แสดงข้อมูล</a> ';

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
<table class='' border='0' id='table_mat'>
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

function gen_audit_table($dep_id, $csa_dep_id, $csa_year, $period) {
	global $connect;
	$is_pass_all = true; 

	$a_list = array();
	$a_detail_list = array();
	
	$sql = "SELECT 
		csa_audit_id
	FROM csa 
	WHERE 
		csa_year = '$csa_year' AND 
		mark_del = '0' AND 
		csa_department_id = '$csa_dep_id'  AND
		csa_period = '$period' ";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) {
		$a_list[] = $row2['csa_audit_id'];
	}

	$sequence = 1;
	$sql = "SELECT * FROM
		csa_audit
	WHERE 
		mark_del = '0' AND 
		department_id3 = '$dep_id'";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
			
			if (in_array($row2['csa_audit_id'], $a_list)) {
				$tmp = '<font color="green"><i class="fa fa-check-circle"></i></font> '.$row2['audit_desc'].'<BR>';
			} else {
				$tmp = '<font color="red"><i class="fa fa-times-circle"></i></font> '.$row2['audit_desc'].'<BR>';
				$is_pass_all = false; 
			}
			$a_detail_list[] = $tmp;
			$sequence++;
		}
	}		
?>

	<div class='row'>
	<div class='col-md-8'>					
	<div class='note note-<?=($is_pass_all==true) ? 'success' : 'danger'?>'>
	<b>ประเด็นที่ตรวจพบ </b><br>
<?					
	if (count($a_detail_list)>0) {
		foreach ($a_detail_list as $j) {
			echo $j;
		}
	} else { ?>
	 - ไม่มีข้อมูล- -
<?	} ?>
	</div>
	</div>
	<div class='col-md-4'>					
	<div style=''>
	ความหมายสัญลักษณ์ :<br>
	<font color="green"><i class="fa fa-check"></i></font> : สร้างรายการประเมินแล้ว<br>
	<font color="red"><i class="fa fa-times"></i></font> : ยังไม่ได้สร้างรายการประเมิน
	</div>
	</div>
	</div>
<?
	return $is_pass_all;
}

function gen_job_function($dep_id, $csa_dep_id, $csa_year, $period) {
	global $connect;
	
	$j_list = array();
	$j_list[0] = array();
	$j_list[1] = array();
	$order = array();
	$j_detail_list = array();
	$is_pass_all = false;
	$i = 1;
	
	$sql = "SELECT 
		job_function_id, job_function_other 
	FROM csa 
	WHERE 
		csa_year = '$csa_year' AND 
		mark_del = '0' AND 
		csa_department_id = '$csa_dep_id'  AND
		csa_period = '$period'
	ORDER BY 
		csa_id";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) {
		$j_list[0][] = $row2['job_function_id'];
		$j_list[1][] = $row2['job_function_other'];
		$order[$row2['job_function_id']][] = $i; 
		$i++;
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
		job_function_id";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		$is_pass_all = true; 
		while ($row2 = mysqli_fetch_array($result2)) {
			
			$sequence = $order[$row2['job_function_id']][0];
			
			if (in_array($row2['job_function_id'], $j_list[0])) { 
				$tmp = '<font color="green"><i class="fa fa-check-circle"></i></font> '.$sequence.' '.$row2['job_function_name'].'<BR>';
				$j_detail_list[] = array(
					'seq' => $sequence,
					'v' => $tmp,
					't' => 0
				);
				
			} else {
				$tmp = '<font color="red"><i class="fa fa-times-circle"></i></font> '.$sequence.' '.$row2['job_function_name'].'<BR>';
				$is_pass_all = false;
				$sequence = 999;
				$j_detail_list[] = array(
					'seq' => $sequence,
					'v' => $tmp,
					't' => 1
				);
			}
		}
		
		$oth = array_keys($j_list[0], 999999);
		foreach ($oth as $k => $v) {
			$i1 = $j_list[1][$v];
			
			$sequence = $order[999999][$k];
			$tmp = '<font color="green"><i class="fa fa-check-circle"></i></font> '.$sequence.' อื่นๆ '.$i1.'<BR>';

			$j_detail_list[] = array(
				'seq' => $sequence,
				'v' => $tmp,
				't' => 0
			);			
		}
		
	usort($j_detail_list, function ($item1, $item2) {
		return $item1['seq'] >= $item2['seq'];
	});

		
?>
	<div class='row'>
	<div class='col-md-8'>					
	<div class='note note-<?=($is_pass_all==true) ? 'success' : 'danger'?>'>
	<b>ขอบเขตหน้าที่ ความรับผิดชอบกลุ่มงาน</b><br>
<?					
		foreach ($j_detail_list as $j) {
			echo $j['v'];
		}
?>
	</div>
	</div>
	<div class='col-md-4'>					
	<div style=''>
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

function is_has_audit_history($csa_dep_id) {
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
		
			$sql2="SELECT 
				COUNT(*) AS num
			FROM `csa_audit` d
			WHERE 
				d.csa_year = '$csa_year' AND
				d.department_id3 = '$department_id3' AND
				d.mark_del = '0' ";
			$result1=mysqli_query($connect, $sql2);
			$row1 = mysqli_fetch_array($result1);
			if ($row1['num']>0) return true;
		}
	}
	return false;
}

function gen_audit_history($csa_dep_id, $is_show_all_event = true) {
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
			$is_available = false;

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
				$is_available = true;
			}
			if ($is_show_all_event || $is_available) { 
?>
<style>
table.change_history thead tr td{
	font-weight: bold;
}	
table.change_history tr td{
	font-size: 16px;
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
<b style='font-weight: 18px'>ประเด็นที่ตรวจพบ</b><br>
<table class='table table-hover '>
<thead>
<tr>
  <td width='15%'>วันที่ตรวจ</td>
  <td width='50%'>ประเด็นที่ตรวจพบ</td>
  <td width='30%'>ผู้ตรวจสอบ</td>
  <td width='5%'>ปิดแล้ว</td>
</tr>
</thead>
<tbody>
<?
				if ($is_available) {
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
}

/*function gen_comment_history($csa_dep_id) {
	global $connect;
?>

<div class='row'>
<div class='col-xl-6 col-lg-8 col-md-10 col-sm-12'>				
	<b>ประวัติ รายการที่ขอให้แก้ไขจาก ฝ่ายบริหารความเสี่ยง</b><br>
	<table class='table table-hover tr_sm change_history'>
	<thead>
	<tr>
		<td width='5%'>ลำดับ</td>
		<td width='15%'>วันที่</td>
		<td width='40%'>ส่วนที่ 1</td>
		<td width='40%'>ส่วนที่ 2</td>
	</tr>
	</thead>
	<tbody>
<?
$sql = "SELECT * FROM csa_department WHERE csa_department_id = '$csa_dep_id' ";
$result2 = mysqli_query($connect, $sql);
if ($row2 = mysqli_fetch_array($result2)) {	
	$sql = "SELECT * FROM csa_comment WHERE csa_department_id = '$csa_dep_id' ORDER BY create_date DESC";
	$result3 = mysqli_query($connect, $sql);
	$i = 1;
	if (mysqli_num_rows($result3)>0) {
		while ($row3 = mysqli_fetch_array($result3)) {
?>
	<tr class='tr_sm'>
		<td><?= $i ?></td>
		<td><?=mysqldate2th_datetime($row3['create_date'])?></td>
		<td><?=html2text($row3['comment1'])?></td>
		<td><?=html2text($row3['comment2'])?></td>
	</tr>
<?
			$i++;
		}
	} else {		
?>			
	<tr>
		<td colspan='4'>-ยังไม่มีข้อมูล-</td>
	</tr>
<?
	}
}
?>			
	</tbody>
	</table>
</div>
</div>
<?	
}*/

function gen_comment_history_part($csa_dep_id, $part, $full=false) {
	global $connect;
	
	if ($part==1 || $part==2) {
?>

<div class='row'>
	<div class='<?if ($full) { echo 'col-md-12'; } else { echo 'col-xl-6 col-lg-8 col-md-10 col-sm-12'; }?>'>				
	<b>ประวัติการที่ขอให้แก้ไขส่วนที่ <?=$part?> จากฝ่ายบริหารความเสี่ยง</b><br>
	<table class='table table-hover tr_sm change_history'>
	<thead>
	<tr>
		<td width='5%'>ลำดับ</td>
		<td width='20%'>วันที่</td>
		<td width='75%'>ความเห็น</td>
	</tr>
	</thead>
	<tbody>
<?
	$sql = "SELECT * FROM csa_department WHERE csa_department_id = '$csa_dep_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$sql = "SELECT * 
		FROM csa_comment 
		WHERE 
			csa_department_id = '$csa_dep_id' AND
			period = '$part'
		ORDER BY create_date DESC";
		$result3 = mysqli_query($connect, $sql);
		$i = 1;
		if (mysqli_num_rows($result3)>0) {
			while ($row3 = mysqli_fetch_array($result3)) {
?>
	<tr class='tr_sm'>
		<td><?= $i ?></td>
		<td><?=mysqldate2th_datetime($row3['create_date'])?></td>
		<td><?=html2text($row3['comment'])?></td>
	</tr>
<?
				$i++;
			}
		} else {		
?>			
	<tr>
		<td colspan='3'>-ยังไม่มีข้อมูล-</td>
	</tr>
<?
		}
	}
}
?>			
	</tbody>
	</table>
</div>
</div>
<?	
}

	
function gen_change_history($csa_dep_id) {
	global $connect;

?>

<style>
table.change_history thead tr td{
	font-weight: bold;
}	
table.change_history tr td{
	font-size: 15px;
}
.table-sm tbody tr td{
	padding: 5px;
}
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
</style>		
		<div class='row'><div class='col-xl-8 col-lg-10 col-md-12 col-sm-12'>		
			<b>ประวัติการเปลี่ยนแปลง</b><br>
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='28%'>การดำเนินการ</td>				
				<td width='27%'>สถานะ</td>
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

function gen_print_part1($csa_department_id, $display_name = true, $display_date = false, $is_xls = false) {
	global $connect;

	$sql = "SELECT 
			c.*,
			d.department_name AS d1,
			d2.department_name AS d2
		FROM csa_department c
		LEFT JOIN department d ON c.department_id3 = d.department_id
		LEFT JOIN department d2 ON c.department_id2 = d2.department_id
		WHERE 
			c.csa_department_id = ? AND 
			c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$is_head1_confirm = $row2['is_head1_confirm'];
			$csa_year = $row2['csa_year'];
			$confirm_date = $row2['confirm_date'];
			$head1_confirm_date = $row2['head1_confirm_date'];

			$u1_name = '......................................................';
			$u1_position = '......................................................';
			$u1_date = '.......... เดือน ....................... พ.ศ. .............';
			if ($display_name) {
				$u1 = get_user_csa($csa_department_id);
				if (count($u1)>0) {
					$u1_name = '<u>'.$u1[0][1].'</u>';
					$u1_position = '<u>'.$u1[0][2].'</u>';
				}
			}
			if ($display_date && $confirm_date!='' && $confirm_date!='0000-00-00 00:00:00') {
				$u1_date = '<u>'.mysqldate2th_date($confirm_date).'</u>';
			}
			$u2_name = '......................................................';
			$u2_position = '......................................................';
			$u2_date = '.......... เดือน ....................... พ.ศ. .............';
			if ($display_name) {
				$u2 = get_approver_csa($csa_department_id);
				if (count($u2)>0) {
					$u2_name = '<u>'.$u2[0][1].'</u>';
					$u2_position = '<u>'.$u2[0][2].'</u>';
				}
			}		
			if ($display_date && $head1_confirm_date!='' && $head1_confirm_date!='0000-00-00 00:00:00') {
				$u2_date = '<u>'.mysqldate2th_date($head1_confirm_date).'</u>';
			}
?>

<div id='print_area'>
<style type="text/css" media="print">
@media screen {
  div.divHeader {
    display: none;
  }	
  div.divFooter {
    display: none;
  }
}
@media print {
  div.divHeader {
	  display:block;
    position: fixed;
    top: 400;
    left: 100;
	font-size: 100px;
	font-weight: bold;

    transform: rotate(45deg);
    transform-origin: right, top;
    -ms-transform: rotate(45deg);
    -ms-transform-origin:right, top;
    -webkit-transform: rotate(45deg);
    -webkit-transform-origin:right, top;
	
	color: rgba(200, 200, 200, 0.2);
  }  
  div.divFooter {
    position: fixed;
    bottom: 0;
  }
}	
</style>
<?	if ($is_head1_confirm==0) {?>
<div class="divHeader">ฉบับร่าง รอการอนุมัติ</div>
<? }?>

<div align='center'><b><u><?=$row2['d2']?> <?=$row2['d1']?></u><br>
แบบสอบถามความเพียงพอของการควบคุมภายใน<br>
ประจำปี <?=$csa_year?><br></b></div>
<br>
<?
	if ($is_xls==true) {
		$check_icon = 'X';
	} else {
		$check_icon = '<img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB40lEQVRoge2YyytFQRzHf14bG+9HQlKyUsrOlo2FEitZWFL+EDaSjYWFNVEW9hZ2hOtdQspCipIsvB/fX8fkup3H/M4915zTnU99lnfOZ+ZMc+deIovFYrH4Uw034CMcNdwipgbuw68fP+G40SIBHH9Av/Hpkxgz2KWFV7zyg2K8nWrhIXnHK9/hsKFGTzj+iILjlbdmMt2pg8ekH8+uGyl1QXfbpLtHzhFrHOm2iVU8b5tEx0v3fH7Et8JFOAlLIg5nGuBpQGymKVilM3gXvEn74AosjjC+nnK48j3wwWWA5YgmwfEnwviUbvwIfPUZaAkWZRHfCM9CxGttmwlybnhBAy7Awn+K34WVug9w2zZezsMCQXwTPM9lPLMqfMCc5iSaQ8TvSOOZUnJ+skkeNBswiTArr73n3SiD28IHzniMFWbls4pX8HElPaOnXeIvTMQr+FtSunpT5JxO7fBS+Fl+6xVRxSta4JUw5B6+xSFe0QavhUES+aiMbNt40QHvchQvPirD0knO9khkvKKbnL/1Ehmv6IVPAYF+hvqGjZp++ELy+C1YbqDXlT74TAlb+UwGyP+3g3KTYrTymQyS/yRiufKZeE0iEfGKIfp7heDrQWLiFfwm+O60Rs613GKxWPKIbyG+IL61xh4kAAAAAElFTkSuQmCC" alt="X" width="18"/>';
	}
	/*$check_icon = '<img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC+UlEQVRogc2ay2sVMRSHs6h1o6AVfOALoe115X/gQlwKoijWunIhKtTb4ruKC6EbF2rbf8bSnaVgtaUq1IXgqxtxYVuwXsVqqZ7fTCJxnMnkNZMc+ChcktxvcjOZnDNlzE9sJ84Qo8Q48YZYIn5ylvhn47xNL+8TNLYQ/cQM8duSaaLJx6otdhEjxDcH8SwtYpjYWaX4OmKA+OpRPAsm5S6x3rd8g3hZoXiWF0S3L/kTrNpZL2KZOO4qf5b4FUBesEpctJW/EFA8S9NUHstmNQJx+Zc4pivfSXyJQDoL7sP9ZfLtrN7dxpTnLN3OC2MwAskyrhXJ4ynYikBQZyntyLuAkQjkdHmQlcdhyufZpmpaLHMAbEYgBe4Qu4kJjbaX5AtwORL74rrks4GYLGn/TDTGDbEWWD5vZ9lIPFH0gfNWNOyNUF7EyZK+PWg0GlD+qkJ+M0sfXKr+SIKSPDWE/C2F/CaWpptlYzxC43cB5K8o5DHzs5rjoFDAFi0E5onb/K9p38ue5MECOq0YCrxiaWKP2EO8N+g7qJDXXTYyP0wv4DH/Ijn2Eh80+t70LP/3AkyW0L4CgS7iY0Ef7NcDCnnTZSPzGQO8NehwTyGCZONTjfIguYlNt1HVOm5IFwH5fkVb22Ujk2yjNg+yPoXYAWKKpUWBonCdeUHyILM5SmB2zykEVeFj5gWnMOA2ZneYQ6XgdED5Ne6ehO2g2IKPaMr7WjaCKXlwl4TmO3G4Znnwz32I9MwloUc6eqhG+f9SSsSw46B5F+Fzzcvcz5spH2UVlDwO8vGqmHmAqnXh66kbnr5giJirQB6okqCkbFeWBYVklpWUFhExF3cbZfIi8GYktvL6UV15EecjEAcux5YoXjGpDoVagTcjywHkcR8aL5uiQMZV5+6E3abTl7yINpZmV1X+GuJFd7tveTlQS33I/L4MwVio+df6DyA4TKHE/ZTZ5RPogyMxTpUddYrnBarEKLTiQDhGvGZptWOFs8g/G+Ntengf5/gDI7RFJjPvBdwAAAAASUVORK5CYII=" alt="X" width="24"/>';*/

	$q_result = array();
	$sql = "SELECT * FROM csa_questionnaire_data WHERE csa_department_id = '$csa_department_id' ";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) {
		$q_result[$row2['csa_q_topic_id']] = array($row2['v'], $row2['v_other']);
	}
		
	$i=1;	
	$sql = "SELECT 
		*
	FROM csa_questionnaire_topic
	WHERE 
		parent_id = '0' AND
		mark_del = '0' 
	ORDER BY
		q_no, q_name ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<b><?=$row2['q_no']?> <?=$row2['q_name']?></b><br>
			<table border='1' style='border-collapse: collapse;'>
			<thead>
			<tr align='center' style='font-weight: bold'>
				<td width='70%'>คำถาม</td>
				<td width='10%'>มีการปฏิบัติ<br>ครบถ้วน</td>
				<td width='10%'>มีการปฏิบัติ<br>บางส่วน</td>
				<td width='10%'>ไม่มี<br>การปฏิบัติ</td>
			</tr>
			</thead>
			<tbody>
<?
			$sql = "SELECT 
				*
			FROM csa_questionnaire_topic
			WHERE 
				parent_id = '$row2[csa_q_topic_id]' AND
				mark_del = '0' 
			ORDER BY
				q_no, q_name ";
			$result3 = mysqli_query($connect, $sql);
			while ($row3 = mysqli_fetch_array($result3)) {
				$v0 = $q_result[$row3['csa_q_topic_id']][0];
				$v1 = $q_result[$row3['csa_q_topic_id']][1];
?>
			<tr class='tr_sm'>
				<td><?=$row3['q_no']?> <?=$row3['q_name']?></td>
				<td align='center'><?if ($v0==3) echo $check_icon?></td>
				<td align='center'><?if ($v0==2) echo $check_icon?></td>
				<td align='center'><?if ($v0==1) echo $check_icon?></td>
			</tr>
<?			}?>
			</tbody>
			</table>
			<br>
<?
		}
	} 
?>

<b>คำอธิบายเพิ่มเติม</b><br>
<div style='width: 900px; word-wrap:break-word; word-break:break-all'><u><?=html2text($q_result[0][1])?></u></div>

<br>
<br>
<div align='left' style='margin-left: 550px; width: 450px'>
(...................................................................)<br>
ผู้จัดทำ <?=$u2_name?><br>
ตำแหน่ง  <?=$u2_position?><br>
วันที่ <?=$u2_date?><br>
</div>

</div>
<?
			
		}
	}		
}

function gen_print_part2_1($csa_department_id, $period, $display_name = true, $display_date = false) {
	global $connect;

	$sql = "SELECT 
		c.*,
		d1.department_name AS dep_name1,
		d2.department_name AS dep_name2,
		d3.department_name AS dep_name3
	FROM csa_department c
	LEFT JOIN department d1 ON c.department_id = d1.department_id
	LEFT JOIN department d2 ON c.department_id2 = d2.department_id
	LEFT JOIN department d3 ON c.department_id3 = d3.department_id
	WHERE 
		c.is_enable='1' AND
		c.csa_department_id = ? AND 
		c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];
			$is_head1_confirm = $row2['is_head1_confirm'];
			$confirm_date = $row2['confirm_date'];
			$head1_confirm_date = $row2['head1_confirm_date'];

			$u1_name = '......................................................';
			$u1_position = '......................................................';
			$u1_date = '......................................................';
			if ($display_name) {
				$u1 = get_user_csa($csa_department_id);
				if (count($u1)>0) {
					$u1_name = '<u>'.$u1[0][1].'</u>';
					$u1_position = '<u>'.$u1[0][2].'</u>';
				}
			}
			if ($display_date && $confirm_date!='' && $confirm_date!='0000-00-00 00:00:00') {
				$u1_date = '<u>'.mysqldate2th_date($confirm_date).'</u>';
			}
			$u2_name = '......................................................';
			$u2_position = '......................................................';
			$u2_date = '......................................................';
			if ($display_name) {
				$u2 = get_approver_csa($csa_department_id);
				if (count($u2)>0) {
					$u2_name = '<u>'.$u2[0][1].'</u>';
					$u2_position = '<u>'.$u2[0][2].'</u>';
				}
			}		
			if ($display_date && $head1_confirm_date!='' && $head1_confirm_date!='0000-00-00 00:00:00') {
				$u2_date = '<u>'.mysqldate2th_date($head1_confirm_date).'</u>';
			}

			
			$sql = "SELECT 
				c.*,
				r.is_other as risk_is_other,
				r.risk_type_name,
				j.job_function_no,
				j.job_function_name
			FROM csa c
			LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
			LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
			WHERE 
				c.csa_year = '$csa_year' AND 
				c.csa_period = '$period' AND 
				c.mark_del = '0' AND 
				c.csa_department_id = '$csa_department_id' ";
			$result2 = mysqli_query($connect, $sql);
			if (mysqli_num_rows($result2)>0) {
?>

<div id='print_area'>
<style type="text/css" media="print">
@page { 
	size: landscape;
}
td, th {
    padding: 3;
}
@media screen {
  div.divHeader {
    display: none;
  }	
  div.divFooter {
    display: none;
  }
}
@media print {
  div.divHeader {
    position: fixed;
    top: 280;
    left: 230;
	font-size: 100px;
	font-weight: bold;

    transform: rotate(35deg);
    transform-origin: right, top;
    -ms-transform: rotate(35deg);
    -ms-transform-origin:right, top;
    -webkit-transform: rotate(35deg);
    -webkit-transform-origin:right, top;
	
	color: rgba(200, 200, 200, 0.2);
  }  
  div.divFooter {
    position: fixed;
    bottom: 0;
  }
}	

</style>
<?	if ($is_head1_confirm==0) {?>
<div class="divHeader">ฉบับร่าง รอการอนุมัติ</div>
<? }?>

<div align='center'><b><u>
<?=$row2['dep_name2']?><br>
<?=$row2['dep_name3']?><br>
รายงานการประเมินผลการควบคุมภายใน (CSA) ประจำปี  <?=$csa_year?> ครั้งที่ <?=$period?><br></u></b></div><br>
<br>
<table border='1' style='border-collapse: collapse;'>
<thead>
<tr align='center' style='font-weight: bold'>
	<td width='18%'>ขอบเขตหน้าที่ความรับผิดชอบ<br>ของกลุ่มงาน</td>
	<td width='18%'>เหตุการณ์ความเสี่ยง</td>
	<td width='18%'>สาเหตุที่ทำให้<br>เกิดความเสี่ยง</td>
	<td width='17%'>ประเภทความเสี่ยง<br>และปัจจัยเสี่ยง</td>
	<td width='20%'>การควบคุมที่มีอยู่</td>
	<td width='9%'>ระดับ<br>ความเสี่ยง</td>
</tr>
</thead>
<tbody>
<?		
$i=1;		
				while ($row2 = mysqli_fetch_array($result2)) {
					$j_list[] = $row2['job_function_id'];
					if ($row2['job_function_id']==999999) 
						$job_function = 'อื่นๆ : '.$row2['job_function_other'];
					else
						$job_function = $row2['job_function_name'];
					
					if ($row2['risk_is_other']==1) 
						$risk_type_name = $row2['risk_type_other'];
					else
						$risk_type_name = $row2['risk_type_name'];
					

					$risk_level = risk_level_name($row2['csa_risk_level2']);

					$control_list = '';
					$sql="SELECT * FROM csa_control WHERE csa_control_id IN (".$row2['control'].")";
					$result1=mysqli_query($connect, $sql);
					while ($row1 = mysqli_fetch_array($result1)) {
//						$control_list.='-'.$row1['control_name'].'<br>';
						if ($row1['is_other']==1) 
							$control_list .= '- '.$row1['control_name'].': <br>'.html2text($row2['control_other']).'<BR>';
						else
							$control_list .= '- '.$row1['control_name'].'<BR>';						
					}
					/*$job_function*/	
?>
			<tr valign='top'>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$i++?>. <?=$job_function?><br>
				<br>
				วัตถุประสงค์<br><?=html2text($row2['objective'])?>
				</td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=html2text($row2['event'])?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=html2text($row2['cause'])?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$risk_type_name?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$control_list?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$risk_level?></td>
			</tr>
<?
				}
?>
			</tbody>
			</table>
			<br>
<table width='100%'>			
<tr>
<td width='40%'>
<td width='30%' align='left'>
(......................................................)<br>
ผู้จัดทำ <?=$u1_name?><br>
ตำแหน่ง  <?=$u1_position?><br>
วันที่ <?=$u1_date?><br>
</td>
<td width='30%' align='left'>
(......................................................)<br>
ผู้อนุมัติ <?=$u2_name?><br>
ตำแหน่ง  <?=$u2_position?><br>
วันที่ <?=$u2_date?><br>
</td>
</tr>
</table>
		
</div>
<?
			} else {
				echo '- รายการประเมินนี้ ยังไม่มีข้อมูล -';
			}			
		}		
	}		
}

function gen_print_part2_1_xls($csa_department_id, $period, $display_name = true, $display_date = false) {
	global $connect;

	$sql = "SELECT 
		c.*,
		d1.department_name AS dep_name1,
		d2.department_name AS dep_name2,
		d3.department_name AS dep_name3
	FROM csa_department c
	LEFT JOIN department d1 ON c.department_id = d1.department_id
	LEFT JOIN department d2 ON c.department_id2 = d2.department_id
	LEFT JOIN department d3 ON c.department_id3 = d3.department_id
	WHERE 
		c.is_enable='1' AND
		c.csa_department_id = ? AND 
		c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];
			$is_head1_confirm = $row2['is_head1_confirm'];
			$confirm_date = $row2['confirm_date'];
			$head1_confirm_date = $row2['head1_confirm_date'];

			$u1_name = '......................................................';
			$u1_position = '......................................................';
			$u1_date = '......................................................';
			if ($display_name) {
				$u1 = get_user_csa($csa_department_id);
				if (count($u1)>0) {
					$u1_name = '<u>'.$u1[0][1].'</u>';
					$u1_position = '<u>'.$u1[0][2].'</u>';
				}
			}
			if ($display_date && $confirm_date!='' && $confirm_date!='0000-00-00 00:00:00') {
				$u1_date = '<u>'.mysqldate2th_date($confirm_date).'</u>';
			}
			$u2_name = '......................................................';
			$u2_position = '......................................................';
			$u2_date = '......................................................';
			if ($display_name) {
				$u2 = get_approver_csa($csa_department_id);
				if (count($u2)>0) {
					$u2_name = '<u>'.$u2[0][1].'</u>';
					$u2_position = '<u>'.$u2[0][2].'</u>';
				}
			}		
			if ($display_date && $head1_confirm_date!='' && $head1_confirm_date!='0000-00-00 00:00:00') {
				$u2_date = '<u>'.mysqldate2th_date($head1_confirm_date).'</u>';
			}

			
			$sql = "SELECT 
				c.*,
				r.is_other as risk_is_other,
				r.risk_type_name,
				j.job_function_no,
				j.job_function_name
			FROM csa c
			LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
			LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
			WHERE 
				c.csa_year = '$csa_year' AND 
				c.csa_period = '$period' AND 
				c.mark_del = '0' AND 
				c.csa_department_id = '$csa_department_id' ";
			$result2 = mysqli_query($connect, $sql);
			if (mysqli_num_rows($result2)>0) {
?>

<div id='print_area'>
<style type="text/css" media="print">
@page { 
	size: landscape;
}
td, th {
    padding: 3;
}
@media screen {
  div.divHeader {
    display: none;
  }	
  div.divFooter {
    display: none;
  }
}
@media print {
  div.divHeader {
    position: fixed;
    top: 280;
    left: 230;
	font-size: 100px;
	font-weight: bold;

    transform: rotate(35deg);
    transform-origin: right, top;
    -ms-transform: rotate(35deg);
    -ms-transform-origin:right, top;
    -webkit-transform: rotate(35deg);
    -webkit-transform-origin:right, top;
	
	color: rgba(200, 200, 200, 0.2);
  }  
  div.divFooter {
    position: fixed;
    bottom: 0;
  }
}	

</style>
<?	if ($is_head1_confirm==0) {?>
<div class="divHeader">ฉบับร่าง รอการอนุมัติ</div>
<? }?>

<div align='center'><b><u>
<?=$row2['dep_name2']?><br>
<?=$row2['dep_name3']?><br>
รายงานการประเมินผลการควบคุมภายใน (CSA) ประจำปี  <?=$csa_year?> ครั้งที่ <?=$period?><br></u></b></div><br>
<br>
<table border='1' style='border-collapse: collapse;'>
<thead>
<tr align='center' style='font-weight: bold'>
	<td width='18%'>ขอบเขตหน้าที่ความรับผิดชอบ<br>ของกลุ่มงาน</td>
	<td width='18%'>เหตุการณ์ความเสี่ยง</td>
	<td width='18%'>สาเหตุที่ทำให้<br>เกิดความเสี่ยง</td>
	<td width='17%'>ประเภทความเสี่ยง<br>และปัจจัยเสี่ยง</td>
	<td width='20%'>การควบคุมที่มีอยู่</td>
	<td width='9%'>ระดับ<br>ความเสี่ยง</td>
</tr>
</thead>
<tbody>
<?		
$i=1;		
				while ($row2 = mysqli_fetch_array($result2)) {
					$j_list[] = $row2['job_function_id'];
					if ($row2['job_function_id']==999999) 
						$job_function = 'อื่นๆ : '.$row2['job_function_other'];
					else
						$job_function = $row2['job_function_name'];
					
					if ($row2['risk_is_other']==1) 
						$risk_type_name = $row2['risk_type_other'];
					else
						$risk_type_name = $row2['risk_type_name'];
					

					$risk_level = risk_level_name($row2['csa_risk_level2']);

					$control_list = '';
					$sql="SELECT * FROM csa_control WHERE csa_control_id IN (".$row2['control'].")";
					$result1=mysqli_query($connect, $sql);
					while ($row1 = mysqli_fetch_array($result1)) {
//						$control_list.='-'.$row1['control_name'].'<br>';
						if ($row1['is_other']==1) 
							$control_list .= '- '.$row1['control_name'].': <br>'.html2text($row2['control_other']).'<BR>';
						else
							$control_list .= '- '.$row1['control_name'].'<BR>';						
					}
					/*$job_function*/	
?>
			<tr valign='top'>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$i++?>. <?=$job_function?><br>
				<br>
				วัตถุประสงค์<br><?=html2text($row2['objective'])?>
				</td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=html2text($row2['event'])?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=html2text($row2['cause'])?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$risk_type_name?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$control_list?></td>
				<td class='f1' style='word-wrap:break-word; word-break:break-all'><?=$risk_level?></td>
			</tr>
<?
				}
?>
			</tbody>
			</table>
			<br>
<table width='100%'>			
<tr>
<td></td>
<td></td>
<td></td>
<td width='30%' align='left'>
(......................................................)<br>
ผู้จัดทำ <?=$u1_name?><br>
ตำแหน่ง  <?=$u1_position?><br>
วันที่ <?=$u1_date?><br>
</td>
<td width='30%' align='left'>
(......................................................)<br>
ผู้อนุมัติ <?=$u2_name?><br>
ตำแหน่ง  <?=$u2_position?><br>
วันที่ <?=$u2_date?><br>
</td>
</tr>
</table>
		
</div>
<?
			} else {
				echo '- รายการประเมินนี้ ยังไม่มีข้อมูล -';
			}			
		}		
	}		
}

function gen_print_part2_2($csa_department_id, $period, $display_name = true, $display_date = false) {
	global $connect;
?>

<div id='print_area'>
<style type="text/css" media="print">
@page { 
	size: landscape;
}
@media print {
  div.divHeader {
    position: fixed;
    top: 280;
    left: 230;
	font-size: 100px;
	font-weight: bold;

    transform: rotate(35deg);
    transform-origin: right, top;
    -ms-transform: rotate(35deg);
    -ms-transform-origin:right, top;
    -webkit-transform: rotate(35deg);
    -webkit-transform-origin:right, top;
	
	color: rgba(200, 200, 200, 0.2);
  }  
  div.divFooter {
    position: fixed;
    bottom: 0;
  }
}	
</style>

<?
	$sql = "SELECT 
		c.*,
		d1.department_name AS dep_name1,
		d2.department_name AS dep_name2,
		d3.department_name AS dep_name3
	FROM csa_department c
	LEFT JOIN department d1 ON c.department_id = d1.department_id
	LEFT JOIN department d2 ON c.department_id2 = d2.department_id
	LEFT JOIN department d3 ON c.department_id3 = d3.department_id
	WHERE 
		c.is_enable='1' AND
		c.csa_department_id = ? AND 
		c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];
			$dep_name = $row2['dep_name2'].'<BR>'.$row2['dep_name3'];
			$is_head1_confirm = $row2['is_head1_confirm'];
			$confirm_date = $row2['confirm_date'];
			$head1_confirm_date = $row2['head1_confirm_date'];
			
			$u1_name = '......................................................';
			$u1_position = '......................................................';
			$u1_date = '......................................................';
			if ($display_name) {
				$u1 = get_user_csa($csa_department_id);
				if (count($u1)>0) {
					$u1_name = '<u>'.$u1[0][1].'</u>';
					$u1_position = '<u>'.$u1[0][2].'</u>';
				}
			}
			if ($display_date && $confirm_date!='' && $confirm_date!='0000-00-00 00:00:00') {
				$u1_date = '<u>'.mysqldate2th_date($confirm_date).'</u>';
			}
			$u2_name = '......................................................';
			$u2_position = '......................................................';
			$u2_date = '......................................................';
			if ($display_name) {
				$u2 = get_approver_csa($csa_department_id);
				if (count($u2)>0) {
					$u2_name = '<u>'.$u2[0][1].'</u>';
					$u2_position = '<u>'.$u2[0][2].'</u>';
				}
			}		
			if ($display_date && $head1_confirm_date!='' && $head1_confirm_date!='0000-00-00 00:00:00') {
				$u2_date = '<u>'.mysqldate2th_date($head1_confirm_date).'</u>';
			}
			
			$sql = "SELECT 
				c.*,
				r.is_other as risk_is_other,
				r.risk_type_name,
				j.job_function_no,
				j.job_function_name
			FROM csa c
			LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
			LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
			WHERE 
				c.csa_risk_level2 >= 3 AND
				c.csa_year = '$csa_year' AND 
				c.csa_period = '$period' AND 
				c.mark_del = '0' AND 
				c.csa_department_id = '$csa_department_id' ";
			$result2 = mysqli_query($connect, $sql);
			if (mysqli_num_rows($result2)>0) {
				while ($row2 = mysqli_fetch_array($result2)) {
					$j_list[] = $row2['job_function_id'];
					if ($row2['job_function_id']==999999) 
						$job_function = 'อื่นๆ : '.$row2['job_function_other'];
					else
						$job_function = $row2['job_function_name'];
					
					if ($row2['risk_is_other']==1) 
						$risk_type_name = $row2['risk_type_other'];
					else
						$risk_type_name = $row2['risk_type_name'];
					

					$risk_level = risk_level_name($row2['csa_risk_level2']);

					$control_list = '';
					$sql="SELECT * FROM csa_control WHERE csa_control_id IN (".$row2['control'].")";
					$result1=mysqli_query($connect, $sql);
					while ($row1 = mysqli_fetch_array($result1)) {
						$control_list.='-'.$row1['control_name'].'<br>';
					}
						
					$action_plan_type_array = explode(',', $row2['action_plan_type']);
					
?>
<?	if ($is_head1_confirm==0) {?>
<div class="divHeader">ฉบับร่าง รอการอนุมัติ</div>
<? }?>

<div align='center'><b><u><?=$dep_name?>
<br>การประเมินแผนปฏิบัติการ (CSA) ประจำปี  <?=$csa_year?> ครั้งที่ <?=$period?></u></b></div><br>
<b>ขอบเขตหน้าที่ความรับผิดชอบของกลุ่มงาน : </b><br>
<?=html2text($job_function)?><br>
<br>
<b>เหตุการณ์ความเสี่ยง :</b><br>
<?=html2text($row2['event'])?><br>
<br>
<b>ระดับความเสี่ยง : </b><?=$risk_level?><br>
<br>
<?
					foreach ($action_plan_type_array as $p) {
						if ($p==1) {
							$action_plan_type = '<font color="#ff0000"><b>ดำเนินการโดยฝ่ายงาน</b></font>';
?>
<b>การตอบสนองความเสี่ยง : </b><?=$action_plan_type?><br>
<b>ชื่อแผนปฏิบัติการ :</b><br>
<?=html2text($row2['action_plan_activity1'])?><br>
<b>วันที่เริ่มต้น :</b> <?=mysqldate2th_date($row2['action_plan_begin_date1'])?><br>
<b>วันที่สิ้นสุด :</b> <?=mysqldate2th_date($row2['action_plan_end_date1'])?><br>
<b>กิจกรรม หรือ ขั้นตอน :</b><br>
<?=html2text($row2['action_plan_process1'])?><br>
<br>
<?						

						} else if ($p==2) {
							$action_plan_type = '<font color="#0070c0"><b>ว่าจ้าง Outsource</b></font>';
?>
<b>การตอบสนองความเสี่ยง : </b><?=$action_plan_type?><br>
<b>งบประมาณ (บาท) : </b> <?=number_filter($row2['action_plan_budget2'])?><br>
<b>ชื่อแผนปฏิบัติการ :</b><br>
<?=html2text($row2['action_plan_activity1'])?><br>
<b>วันที่เริ่มต้น :</b> <?=mysqldate2th_date($row2['action_plan_begin_date1'])?><br>
<b>วันที่สิ้นสุด :</b> <?=mysqldate2th_date($row2['action_plan_end_date1'])?><br>
<b>กิจกรรม หรือ ขั้นตอน :</b><br>
<?=html2text($row2['action_plan_process1'])?><br>
<br>
<?						
						}
					}
?>
			<br>
			<br>

<table width='100%'>			
<tr>
<td width='40%'>
<td><div width='30%' align='left'>
(......................................................)<br>
ผู้จัดทำ <?=$u1_name?><br>
ตำแหน่ง  <?=$u1_position?><br>
วันที่ <?=$u1_date?><br>
</td>
<td width='30%' align='left'>
(......................................................)<br>
ผู้อนุมัติ <?=$u2_name?><br>
ตำแหน่ง  <?=$u2_position?><br>
วันที่ <?=$u2_date?><br>
</td>
</tr>
</table>
<div class='break'></div>
<?
				}
			} else {
?>
<?	if ($is_head1_confirm==0) {?>
<div class="divHeader">ฉบับร่าง รอการอนุมัติ</div>
<? }?>

<div align='center'><b><u><?=$dep_name?>
<br>การประเมินแผนปฏิบัติการ (CSA) ประจำปี  <?=$csa_year?> ครั้งที่ <?=$period?></u></b></div><br>
<br>
<div style='font-weight: 22px'>- รายการประเมินนี้ ไม่มีการจัดทำแผนปฏิบัติการ -</div><br>
<br>
<?				
			}	
		}		
	}
?>
</div>
<?	
}

function gen_print_part2_2_xls($csa_department_id, $period, $display_name = true, $display_date = false) {
	global $connect;

	$sql = "SELECT 
		c.*,
		d1.department_name AS dep_name1,
		d2.department_name AS dep_name2,
		d3.department_name AS dep_name3
	FROM csa_department c
	LEFT JOIN department d1 ON c.department_id = d1.department_id
	LEFT JOIN department d2 ON c.department_id2 = d2.department_id
	LEFT JOIN department d3 ON c.department_id3 = d3.department_id
	WHERE 
		c.is_enable='1' AND
		c.csa_department_id = ? AND 
		c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $csa_department_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];
			$dep_name = $row2['dep_name2'].'<BR>'.$row2['dep_name3'];
			$is_head1_confirm = $row2['is_head1_confirm'];
			$confirm_date = $row2['confirm_date'];
			$head1_confirm_date = $row2['head1_confirm_date'];
			
			$u1_name = '......................................................';
			$u1_position = '......................................................';
			$u1_date = '......................................................';
			if ($display_name) {
				$u1 = get_user_csa($csa_department_id);
				if (count($u1)>0) {
					$u1_name = '<u>'.$u1[0][1].'</u>';
					$u1_position = '<u>'.$u1[0][2].'</u>';
				}
			}
			if ($display_date && $confirm_date!='' && $confirm_date!='0000-00-00 00:00:00') {
				$u1_date = '<u>'.mysqldate2th_date($confirm_date).'</u>';
			}
			$u2_name = '......................................................';
			$u2_position = '......................................................';
			$u2_date = '......................................................';
			if ($display_name) {
				$u2 = get_approver_csa($csa_department_id);
				if (count($u2)>0) {
					$u2_name = '<u>'.$u2[0][1].'</u>';
					$u2_position = '<u>'.$u2[0][2].'</u>';
				}
			}		
			if ($display_date && $head1_confirm_date!='' && $head1_confirm_date!='0000-00-00 00:00:00') {
				$u2_date = '<u>'.mysqldate2th_date($head1_confirm_date).'</u>';
			}
			
			$sql = "SELECT 
				c.*,
				r.is_other as risk_is_other,
				r.risk_type_name,
				j.job_function_no,
				j.job_function_name
			FROM csa c
			LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
			LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
			WHERE 
				c.csa_risk_level2 >= 3 AND
				c.csa_year = '$csa_year' AND 
				c.csa_period = '$period' AND 
				c.mark_del = '0' AND 
				c.csa_department_id = '$csa_department_id' ";
			$result2 = mysqli_query($connect, $sql);
			if (mysqli_num_rows($result2)>0) {
				while ($row2 = mysqli_fetch_array($result2)) {
					$j_list[] = $row2['job_function_id'];
					if ($row2['job_function_id']==999999) 
						$job_function = 'อื่นๆ : '.$row2['job_function_other'];
					else
						$job_function = $row2['job_function_name'];
					
					if ($row2['risk_is_other']==1) 
						$risk_type_name = $row2['risk_type_other'];
					else
						$risk_type_name = $row2['risk_type_name'];
					

					$risk_level = risk_level_name($row2['csa_risk_level2']);

					$control_list = '';
					$sql="SELECT * FROM csa_control WHERE csa_control_id IN (".$row2['control'].")";
					$result1=mysqli_query($connect, $sql);
					while ($row1 = mysqli_fetch_array($result1)) {
						$control_list.='-'.$row1['control_name'].'<br>';
					}
						
					$action_plan_type_array = explode(',', $row2['action_plan_type']);
					
?>
<?	if ($is_head1_confirm==0) {?>
<div class="divHeader">ฉบับร่าง รอการอนุมัติ</div>
<? }?>

<table>
<tr><td align='center' style='width:1000px' colspan='2'>
<b><u><?=$dep_name?>
<br>การประเมินแผนปฏิบัติการ (CSA) ประจำปี  <?=$csa_year?> ครั้งที่ <?=$period?></u></b></td></tr>
<tr><td align='left' colspan='2'><b>ขอบเขตหน้าที่ความรับผิดชอบของกลุ่มงาน : </b></td></tr>
<tr><td align='left' colspan='2'><?=html2text($job_function)?></td></tr>
<tr><td colspan='2'><br></td></tr>
<tr><td align='left' colspan='2'><b>เหตุการณ์ความเสี่ยง :</b></td></tr>
<tr><td align='left' colspan='2'><?=html2text($row2['event'])?><br></td></tr>
<tr><td colspan='2'><br></td></tr>
<tr><td align='left' colspan='2'><b>ระดับความเสี่ยง : </b><?=$risk_level?></td></tr>
<tr><td colspan='2'><br></td></tr>
<?
					foreach ($action_plan_type_array as $p) {
						if ($p==1) {
							$action_plan_type = '<font color="#ff0000"><b>ดำเนินการโดยฝ่ายงาน</b></font>';
?>
<tr><td align='left' colspan='2'><b>การตอบสนองความเสี่ยง : </b><?=$action_plan_type?></td></tr>
<tr><td align='left' colspan='2'><b>ชื่อแผนปฏิบัติการ :</b></td></tr>
<tr><td align='left' colspan='2'><?=html2text($row2['action_plan_activity1'])?></td></tr>
<tr><td align='left' colspan='2'><b>วันที่เริ่มต้น :</b> <?=mysqldate2th_date($row2['action_plan_begin_date1'])?></td></tr>
<tr><td align='left' colspan='2'><b>วันที่สิ้นสุด :</b> <?=mysqldate2th_date($row2['action_plan_end_date1'])?></td></tr>
<tr><td align='left' colspan='2'><b>กิจกรรม หรือ ขั้นตอน :</b></td></tr>
<tr><td align='left' colspan='2'><?=html2text($row2['action_plan_process1'])?></td></tr>
<tr><td colspan='2'><br></td></tr>
<?						

						} else if ($p==2) {
							$action_plan_type = '<font color="#0070c0"><b>ว่าจ้าง Outsource</b></font>';
?>
<tr><td align='left' colspan='2'><b>การตอบสนองความเสี่ยง : </b><?=$action_plan_type?></td></tr>
<tr><td align='left' colspan='2'><b>งบประมาณ (บาท) : </b> <?=number_filter($row2['action_plan_budget2'])?></td></tr>
<tr><td align='left' colspan='2'><b>ชื่อแผนปฏิบัติการ :</b></td></tr>
<tr><td align='left' colspan='2'><?=html2text($row2['action_plan_activity1'])?></td></tr>
<tr><td align='left' colspan='2'><b>วันที่เริ่มต้น :</b> <?=mysqldate2th_date($row2['action_plan_begin_date1'])?></td></tr>
<tr><td align='left' colspan='2'><b>วันที่สิ้นสุด :</b> <?=mysqldate2th_date($row2['action_plan_end_date1'])?></td></tr>
<tr><td align='left' colspan='2'><b>กิจกรรม หรือ ขั้นตอน :</b></td></tr>
<tr><td align='left' colspan='2'><?=html2text($row2['action_plan_process1'])?></td></tr>
</table>
<br>
<?						
						}
					}
?>
			<br>
			<br>

<table width='1000'>			
<tr>
<td><div width='500' align='left'>
(......................................................)<br>
ผู้จัดทำ <?=$u1_name?><br>
ตำแหน่ง  <?=$u1_position?><br>
วันที่ <?=$u1_date?><br>
</td>
<td width='500' align='left'>
(......................................................)<br>
ผู้อนุมัติ <?=$u2_name?><br>
ตำแหน่ง  <?=$u2_position?><br>
วันที่ <?=$u2_date?><br>
</td>
</tr>
</table>
<div class='break'></div>
<?
				}
			} else {
?>
<?	if ($is_head1_confirm==0) {?>
<div class="divHeader">ฉบับร่าง รอการอนุมัติ</div>
<? }?>

<div align='center'><b><u><?=$dep_name?>
<br>การประเมินแผนปฏิบัติการ (CSA) ประจำปี  <?=$csa_year?> ครั้งที่ <?=$period?></u></b></div><br>
<br>
<div style='font-weight: 22px'>- รายการประเมินนี้ ไม่มีการจัดทำแผนปฏิบัติการ -</div><br>
<br>
<?				
			}	
		}		
	}
}

function get_user_csa($csa_dep_id) {
	global $connect;
	
	$u = array();
	$sql = "SELECT 
		csa_authorize.*,
		u.userName,
		u.position,
		u.email,
		CONCAT(u.prefix, u.name, ' ', u.surname) AS uname
	FROM csa_authorize
	LEFT JOIN user u ON csa_authorize.csa_authorize_uid = u.code
	WHERE 
		csa_authorize.csa_department_id = '$csa_dep_id' ";				
	$result1=mysqli_query($connect, $sql);
	if (mysqli_num_rows($result1)>0) {
		while ($row1 = mysqli_fetch_array($result1)) {	
			$u[] = array($row1['csa_authorize_uid'], $row1['uname'], $row1['position'], $row1['email'], $row1['userName']);
		}
	}
	return $u;	
}

function get_approver_csa($csa_dep_id) {
	global $connect;

	$u = array();
	$sql = "SELECT 
		csa_authorize_approver.*,
		u.userName,
		u.position,
		u.email,
		CONCAT(u.prefix, u.name, ' ', u.surname) AS uname
	FROM csa_authorize_approver
	LEFT JOIN user u ON csa_authorize_approver.csa_authorize_uid = u.code
	WHERE 
		csa_authorize_approver.csa_department_id = '$csa_dep_id' ";				
	$result1=mysqli_query($connect, $sql);
	if (mysqli_num_rows($result1)>0) {
		while ($row1 = mysqli_fetch_array($result1)) {	
			$u[] = array($row1['csa_authorize_uid'], $row1['uname'], $row1['position'], $row1['email'], $row1['userName']);
		}
	}		
	return $u;	
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

function risk_level_acceptable($r) {
	if ($r>=3) 
		return 'ไม่เพียงพอ';
	else
		return 'เพียงพอ';
}
?>