<?
include('inc/include.inc.php');
include('csa_function.php');

echo template_header();

echo "<label style='color:gray; font-size:10px;' class='pull-right'> csa V3.1 - 21/10/64 </label>";

$sql2="SELECT * FROM csa_permission WHERE user_id = '$user_id' ";
$result2=mysqli_query($connect, $sql2);
if ($row2 = mysqli_fetch_array($result2)) {	
	if ($row2['is_write']==0) {
		echo '<font color="red">ขออภัย ท่านไม่มีสิทธิเข้าใช้งานระบบนี้</font>';
		echo template_footer();
		exit;		
	}
} else {
	echo '<font color="red">ขออภัย ท่านไม่มีสิทธิเข้าใช้งานระบบนี้</font>';
	echo template_footer();
	exit;		
}

$edit_id = intval($_GET['edit_id']);
$edit_year = intval($_GET['edit_year']);

$update_id = intval($_POST['update_id']);
$update_csa_id = intval($_POST['update_csa_id']);
$update_kri_id = intval($_POST['update_kri_id']);
$update_ap_id = intval($_POST['update_ap_id']);
$submit = $_POST['submit'];
$action = $_GET['action'];
$del_pid = intval($_GET['del_pid']);
$del_law_id = intval($_GET['del_law_id']);
$del_authorize_id = intval($_GET['del_authorize_id']);$del_authorize_id2 = intval($_GET['del_authorize_id2']);


if ($del_pid>0) {

	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "UPDATE csa_plan SET mark_del = 1 WHERE csa_plan_id = '$del_pid' ";
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

	
} else if ($del_authorize_id >0) {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "DELETE FROM csa_authorize WHERE csa_authorize_id = '$del_authorize_id' ";
	
	//echo $sql;
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);	
		savelog('RCSA-DELETE-AUTH ['.$del_authorize_id.']');
		echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
	}	
} else if ($del_authorize_id2 >0) {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "DELETE FROM csa_authorize_approver WHERE csa_authorize_id = '$del_authorize_id2' ";
	
	//echo $sql;
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);	
		savelog('RCSA-DELETE-AUTH2 ['.$del_authorize_id.']');
		echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
	}	
} else if ($del_law_id>0) {

	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "UPDATE csa_law_data SET mark_del = 1 WHERE csa_law_data_id = '$del_law_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
	}

	
}

if ($submit == 'unlock' && $update_id>0) {
	
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "UPDATE csa_department SET is_confirm = 0, confirm_date = NULL WHERE csa_department_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	/*$sql = "UPDATE csa_action_plan SET is_confirm = 0, confirm_date = NULL WHERE csa_department_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	*/

	$sql = "UPDATE csa SET is_confirm = 0, confirm_date = NULL WHERE csa_department_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	$sql = "UPDATE csa_kri SET is_confirm = 0, confirm_date = NULL WHERE csa_department_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	
	
	if ($qx) {
		mysqli_commit($connect);	
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
	} else {
		mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
	}
	
} else if ($submit == 'add_comment' && $update_id>0) {
	
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$csa_year = intval($_POST['csa_year4']);
	$csa_comment = addslashes($_POST['csa_comment']);
	$sql = "INSERT INTO csa_comment (csa_department_id, csa_year, comment, create_date)
			VALUES ('$update_id', '$csa_year','$csa_comment', now())";
	//echo $sql;		
 	$q = mysqli_query($connect, $sql);
	$insert_id = mysqli_insert_id($connect);
	$qx = ($qx and $q);	
	
	if ($qx) {
		mysqli_commit($connect);
		savelog('RCSA-ADD-COMMENT ['.$insert_id.']');
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
	} else {
		mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
	}
 
} else if (($submit == 'save' || $submit == 'add_uid' || $submit == 'add_uid2') && $update_id>0) {
	
	$csa_year = intval($_POST['csa_year']);
	$is_enable = intval($_POST['is_enable']);
	$department_name = addslashes($_POST['department_name']);
	
	// prepare
	$edit_id = $update_id;
	$edit_year = $csa_year;	
	
	if ($csa_year>0) {
	
		$qx = true;	
		mysqli_autocommit($connect, FALSE);
		
		if ($submit=='add_uid') {
			$authorize_uid = addslashes($_POST['authorize_uid']);			if ($authorize_uid!='') {				$sql = "INSERT INTO csa_authorize (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$authorize_uid')";
				$q = mysqli_query($connect, $sql);
				$qx = ($qx and $q);				}			
		} else if ($submit=='add_uid2') {
			$authorize_uid2 = addslashes($_POST['authorize_uid2']);			if ($authorize_uid2!='') {				$sql = "INSERT INTO csa_authorize_approver (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$authorize_uid2')";
				$q = mysqli_query($connect, $sql);
				$qx = ($qx and $q);	
			} 				} 		
		
		$sql = "UPDATE csa_department SET 
		csa_year='$csa_year', 
		is_enable='$is_enable',  
		department_name='$department_name'
		WHERE csa_department_id = '$update_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		$update_id = mysqli_insert_id($connect);
		
		if ($qx) {
			mysqli_commit($connect);	
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
		} else {
			mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
		}	
	}	
	
} else if ($submit == 'delete' && $update_id>0) {
	
	$qx = true;	
	mysqli_autocommit($connect, FALSE);
	
	$sql = "UPDATE csa_department SET 	mark_del = '1'
	WHERE csa_department_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	
	
	if ($qx) {
		mysqli_commit($connect);	
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
	} else {
		mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
	}	
	
} else if (($submit == 'save_csa' || $submit == 'add_plan' || $submit == 'add_law') && $update_csa_id>0) {
	
	$activity_name = addslashes($_POST['activity_name']);
	$objective = addslashes($_POST['objective']);
	$risk_name = addslashes($_POST['risk_name']);
	$csa_year = intval($_POST['csa_year']);
	$date_begin = addslashes($_POST['date_begin']);
	$date_end = addslashes($_POST['date_end']);
	$section = addslashes($_POST['section']);
	$owner = addslashes($_POST['owner']);
	$is_risk = intval($_POST['is_risk']);
	$factor = intval($_POST['factor']);
	$factor_other = addslashes($_POST['factor_other']);
	$frequency = intval($_POST['frequency']);
	$impact = intval($_POST['impact']);
	$impact_financial = intval($_POST['impact_financial']);
	$impact_image = intval($_POST['impact_image']);
	$impact_operation = intval($_POST['impact_operation']);
	$impact_law = intval($_POST['impact_law']);
	$impact_safety = intval($_POST['impact_safety']);
	$frequency_acc = intval($_POST['frequency_acc']);
	$impact_acc = intval($_POST['impact_acc']);
	$impact_financial_acc = intval($_POST['impact_financial_acc']);
	$impact_image_acc = intval($_POST['impact_image_acc']);
	$impact_operation_acc = intval($_POST['impact_operation_acc']);
	$impact_law_acc = intval($_POST['impact_law_acc']);
	$impact_safety_acc = intval($_POST['impact_safety_acc']);
	$risk_type = intval($_POST['risk_type']);
	$risk_type_other = addslashes($_POST['risk_type_other']);
	$control_other = addslashes($_POST['control_other']);
	$control_approach = intval($_POST['control_approach']);
	$control_owner = addslashes($_POST['control_owner']);
	$risk_remain = addslashes($_POST['risk_remain']);
	$plan_name = addslashes($_POST['plan_name']);
	$plan_level1 = addslashes($_POST['plan_level1']);
	$plan_level2 = addslashes($_POST['plan_level2']);
	$plan_level3 = addslashes($_POST['plan_level3']);
	$plan_level4 = addslashes($_POST['plan_level4']);
	$plan_level5 = addslashes($_POST['plan_level5']);
	$plan_target = addslashes($_POST['plan_target']);
	$plan_level_target = intval($_POST['plan_level_target']);
	$deadline = addslashes($_POST['deadline']);
	if ($date_begin=='') $date_begin = '0000-00-00';
	if ($date_end=='') $date_end = '0000-00-00';
	if ($deadline=='') $deadline = '0000-00-00';
	$is_finish = 0;
	$control_list = '';
	
	$control = $_POST['csa_control'];
	if (count($control)>0) {
		$control_list = implode(',', $control);
	}
	
	if ($activity_name!='' && $csa_year>0) {
	
		$qx = true;	
		mysqli_autocommit($connect, FALSE);

		if ($submit=='add_plan') {
			$sql = "INSERT INTO csa_plan (csa_id) VALUES ('$update_csa_id')";
			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);				
		} 
		if ($submit=='add_law') {
			$sql = "INSERT INTO csa_law_data (csa_id, create_date) VALUES ('$update_csa_id', now())";
			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);				
		} 		

		$plan_cnt=0;
		$plan_finish=1;
		$sql = "SELECT * FROM csa_plan WHERE csa_id = '$update_csa_id' AND mark_del = '0'";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {				
			$id = $row1['csa_plan_id'];
			$csa_activity_name = addslashes($_POST['activity_name_'.$id]);
			$csa_target = addslashes($_POST['target_'.$id]);
			$csa_weight = intval($_POST['weight_'.$id]);
			$csa_budget = doubleval($_POST['budget_'.$id]);
			$csa_remark = addslashes($_POST['remark_'.$id]);			
			$m1 = intval($_POST['m1_'.$id]);
			$m2 = intval($_POST['m2_'.$id]);
			$m3 = intval($_POST['m3_'.$id]);
			$m4 = intval($_POST['m4_'.$id]);
			$m5 = intval($_POST['m5_'.$id]);
			$m6 = intval($_POST['m6_'.$id]);
			$m7 = intval($_POST['m7_'.$id]);
			$m8 = intval($_POST['m8_'.$id]);
			$m9 = intval($_POST['m9_'.$id]);
			$m10 = intval($_POST['m10_'.$id]);
			$m11 = intval($_POST['m11_'.$id]);
			$m12 = intval($_POST['m12_'.$id]);

			$sql = "UPDATE csa_plan SET 
				activity_name = '$csa_activity_name',
				target = '$csa_target',
				weight = '$csa_weight',
				m1 = '$m1',
				m2 = '$m2',
				m3 = '$m3',
				m4 = '$m4',
				m5 = '$m5',
				m6 = '$m6',
				m7 = '$m7',
				m8 = '$m8',
				m9 = '$m9',
				m10 = '$m10',
				m11 = '$m11',
				m12 = '$m12',
				budget = '$csa_budget',
				remark = '$csa_remark',
				last_modify = now()				
			WHERE
				csa_plan_id = '$id' ";

			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);	
			
			if ($csa_activity_name=='' || $csa_target=='' || $csa_weight==0) $plan_finish = 0;
			$plan_cnt++;
		}
		
		$is_plan = 0;
		// old //if ($frequency_acc+$impact_acc>=5) $is_plan = 1;
		if ($control_approach > 1) $is_plan = 1;
		if ($is_risk==0) {
			$is_finish = 1;
		} else {
			if ($frequency>0 && $impact>0 && $frequency_acc>0 && $impact_acc>0 && $control_approach>0 && $owner!='') {
				if ($is_plan==0) {
					$is_finish = 1;
				} else {
					if ($plan_cnt>0 && $plan_finish==1) {
						$is_finish = 1;
					}
				}
			}
			if ($risk_name=='') $is_finish = 0; // new
		}
		
		$sql = "SELECT * FROM csa_law_data WHERE csa_id = '$update_csa_id' AND mark_del = '0'";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {				
			$id = $row1['csa_law_data_id'];
			$csa_law = intval($_POST['csa_law_id_'.$id]);
			$csa_law_desc = addslashes($_POST['csa_law_desc_'.$id]);

			$sql = "UPDATE csa_law_data SET 
				csa_law_id = '$csa_law',
				description = '$csa_law_desc'
			WHERE
				csa_law_data_id = '$id' ";

			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);	
			//if ($csa_law==0) $plan_finish = 0;
		}
		
		// อัพเดท รายการเสี่ยง ให้อัพเดท kri ด้วย
		$sql = "UPDATE csa_kri SET risk_name = '$risk_name', control_owner='$control_owner' WHERE csa_id = '$update_csa_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);
		
		$sql = "UPDATE csa SET 
		activity_name = '$activity_name', 
		objective = '$objective', 
		risk_name = '$risk_name', 
		date_begin='$date_begin', 
		date_end='$date_end', 
		section='$section',
		owner='$owner',
		is_risk='$is_risk',
		is_plan='$is_plan',
		is_finish='$is_finish',
		plan_name='$plan_name',
		plan_level1='$plan_level1',
		plan_level2='$plan_level2',
		plan_level3='$plan_level3',
		plan_level4='$plan_level4',
		plan_level5='$plan_level5',
		plan_level_target='$plan_level_target',
		plan_target='$plan_target',
		factor='$factor',
		factor_other='$factor_other',
		risk_type='$risk_type',
		risk_type_other='$risk_type_other',
		control='$control_list',
		control_other='$control_other',
		frequency='$frequency',
		impact='$impact',
		impact_financial='$impact_financial',
		impact_image='$impact_image',
		impact_operation='$impact_operation',
		impact_law='$impact_law',
		impact_safety='$impact_safety',
		frequency_acc='$frequency_acc',
		impact_acc='$impact_acc',
		impact_financial_acc='$impact_financial_acc',
		impact_image_acc='$impact_image_acc',
		impact_operation_acc='$impact_operation_acc',
		impact_law_acc='$impact_law_acc',
		impact_safety_acc='$impact_safety_acc',
		control_approach='$control_approach',
		control_owner='$control_owner',
		risk_remain='$risk_remain',
		deadline='$deadline'
		WHERE csa_id = '$update_csa_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		
		if ($qx) {
			mysqli_commit($connect);	
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
		} else {
			mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
		}
	}	
	
} else if ($submit == 'save_kri' && $update_kri_id>0) {
	
	$sequence = addslashes($_POST['sequence']);
	$risk_name = addslashes($_POST['risk_name']);
	$index_no = addslashes($_POST['index_no']);
	$source = addslashes($_POST['source']);
	$description = addslashes($_POST['description']);
	$unit = addslashes($_POST['unit']);
	$frequency = addslashes($_POST['frequency']);
	$level_acceptable = addslashes($_POST['level_acceptable']);
	$level_alert = addslashes($_POST['level_alert']);
	$level_problem = addslashes($_POST['level_problem']);
	$level_acceptable_desc = addslashes($_POST['level_acceptable_desc']);
	$level_alert_desc = addslashes($_POST['level_alert_desc']);
	$level_problem_desc = addslashes($_POST['level_problem_desc']);
	$owner = addslashes($_POST['owner']);
	$control_owner = addslashes($_POST['control_owner']);
	$csa_id = intval($_POST['csa_id']);
	$kri_type = addslashes($_POST['kri_type']);
	$is_kri_risk = intval($_POST['kri_risk']);
	
		$qx = true;	
		mysqli_autocommit($connect, FALSE);

		$sql = "UPDATE csa_kri SET 
		csa_id = '$csa_id', 
		sequence = '$sequence', 
		risk_name='$risk_name', 
		index_no='$index_no', 
		source='$source',
		description='$description',
		unit='$unit',
		frequency='$frequency',
		level_acceptable='$level_acceptable',
		level_alert='$level_alert',
		level_problem='$level_problem',
		level_acceptable_desc='$level_acceptable_desc',
		level_alert_desc='$level_alert_desc',
		level_problem_desc='$level_problem_desc',		
		owner='$owner',
		control_owner='$control_owner',
		kri_type='$kri_type',
		kri_risk='$is_kri_risk',
		last_modify = now()
		WHERE kri_id = '$update_kri_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		
		if ($qx) {
			mysqli_commit($connect);	
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
		} else {
			mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
		$edit_kri_id = $update_kri_id;
	}	
	
} else if ($submit == 'save_ap' && $update_ap_id>0) {
	
	/*$sequence = addslashes($_POST['sequence']);
	$risk_name = addslashes($_POST['risk_name']);
	$risk_remain = addslashes($_POST['risk_remain']);
	$damage = addslashes($_POST['damage']);
	$problem = addslashes($_POST['problem']);
	$solution = addslashes($_POST['solution']);
	$responsible = addslashes($_POST['responsible']);
	$duration = addslashes($_POST['duration']);
	$support = addslashes($_POST['support']);
	$owner = addslashes($_POST['owner']);
	$head = addslashes($_POST['head']);	
	$csa_id = intval($_POST['csa_id']);
	
		$qx = true;	
		mysqli_autocommit($connect, FALSE);


		$sql = "UPDATE csa_action_plan SET 
		csa_id = '$csa_id', 
		sequence = '$sequence', 
		risk_name='$risk_name', 
		risk_remain='$risk_remain', 
		damage='$damage',
		problem='$problem',
		solution='$solution',
		responsible='$responsible',
		duration='$duration',
		support='$support',
		owner='$owner',
		head='$head',
		last_modify = now()
		WHERE ap_id = '$update_ap_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		
		if ($qx) {
			mysqli_commit($connect);	
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
		} else {
			mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
		$edit_ap_id = $update_ap_id;
	}	*/
	
} else if ($action == 'add') {		
?>

<link rel="stylesheet" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.css">
<script type="text/javascript" src="timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="timepicker/jquery.ui.timepicker.js?v=0.3.3"></script>
<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<script>
	$(function () {
		$('.datepicker').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true, 
			locale: {format: 'YYYY-MM-DD'}
		});			
	});	
	

	function toggle(oInput) {
		var aInputs = document.getElementsByTagName('input');
		for (var i=0;i<aInputs.length;i++) {
			if (aInputs[i] != oInput) {
            aInputs[i].checked = oInput.checked;
			}
		}
	}	
</script>


<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">เพิ่มฝ่าย</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			
<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">เพิ่มข้อมูลรายการเดียว</a></li>
		<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="true">เพิ่มข้อมูลเป็นกลุ่ม</a></li>
	</ul>
	<div class="tab-content" >
		<br>
		<div class="tab-pane active" id="tab1">
	
			<form method='post' action='csa_admin.php'>  			<div class="form-group">
			  <label>ฝ่าย</label>				<select class="form-control" name='department_id' required>					<option value=''>-- เลือก --</option><?	$sql = "SELECT * FROM department where mark_del = 0 ORDER BY department_no, department_name";	$result2 = mysqli_query($connect, $sql);	while ($row2 = mysqli_fetch_array($result2)) { ?>						<option value='<?=$row2['department_id']?>'><?=$row2['department_name']?></option><?	}?>
				</select><!--			  -->
			</div>	
			<div class="form-group">
			  <label>ส่วนงานย่อย (ถ้ามี)</label>
			  <input type="text" class="form-control" name='department_name' placeholder="ส่วนงานย่อย (ถ้ามี)" value=''>
			</div>	
			<div class="form-group">
			  <label>ปี พ.ศ.</label>
			  <input type="text" class="form-control" name='csa_year' placeholder="ปี พ.ศ." value='<?=date('Y')+543?>'>
			</div>	
			<br>
			<br>			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			<button type='submit' name='submit' value='add' class="btn btn-success"><i class='fa fa-plus-circle'></i> เพิ่ม</button>			</form>			
		</div>
		<div class="tab-pane" id="tab2">			<form method='post' action='csa_admin.php'>  			<div class="form-group">			  <label>ปี พ.ศ.</label>			  <input type="text" class="form-control" name='csa_year1' placeholder="ปี พ.ศ." value='<?=date('Y')+543?>'>			</div>				<br>		
			<div class="form-group">
				<label>ฝ่าย</label><br>
				<input type='checkbox' id='checkall' onClick='toggle(this)'> เลือกทั้งหมด <hR><?
	$sql = "SELECT * FROM department where mark_del = 0 ORDER BY department_no, department_name";
	$result2 = mysqli_query($connect, $sql);	$row_per_col = mysqli_num_rows($result2) / 3;
	$i = 0;	$j = 0;	$col=array();
	while ($row2 = mysqli_fetch_array($result2)) { 		$i++;		if ($i>=$row_per_col) {			$j++;			$i=0;		}		$col[$j][] = array($row2['department_id'], $row2['department_name']);	}		?>				<div class='row'><? for ($j=0; $j<3; $j++) {		foreach ($col[$j] as $d) {?>								<div class='col-md-4'>					<input type='checkbox' name='dep[]'  value='<?=$d[0]?>'> <?=$d[1]?><br>				</div><? 		}	}?>									</div>				<br>			</div>				<br>			<br>			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			<button type='submit' name='submit' value='add' class="btn btn-success"><i class='fa fa-plus-circle'></i> เพิ่ม</button>			</form>			
	</div>
</div>
</div>	

		</div>
	</div>
</div>
		  
<?

	echo template_footer();
	exit;

} else if ($submit == 'add') {		

	//$authorize_uid = addslashes($_POST['authorize_uid']);
	$csa_year = intval($_POST['csa_year']);	$department_id = intval($_POST['department_id']);
	$department_name = addslashes($_POST['department_name']);
	
	if ($department_id!='' && $csa_year>0) {
	
		$qx = true;	
		mysqli_autocommit($connect, FALSE);
		
		$sql = "INSERT INTO csa_department (department_id, department_name, csa_year, is_enable) VALUES 
		('$department_id', '$department_name', '$csa_year',1) ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		$update_id = mysqli_insert_id($connect);
		
		if ($qx) {
			mysqli_commit($connect);	
?>
<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>
<?			
		} else {
			mysqli_rollback($connect);
?>
<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
<?			
		}	
	} else {
		$dep = $_POST['dep'];
		$cnt = count($dep);
		if($cnt > 0) {
			$qx = true;	
			mysqli_autocommit($connect, FALSE);			
			
			$csa_year1 = intval($_POST['csa_year1']);
			$i=0;
			foreach ($dep as $d) {				$d = intval($d);				if ($d>0) {
					$sql = "INSERT INTO csa_department (department_id, department_name, csa_year, is_enable) VALUES 
					('$d', '', '$csa_year1' ,1) ";
					$q = mysqli_query($connect, $sql);
					$qx = ($qx and $q);	
				}				
			}

			if ($qx) {
				mysqli_commit($connect);	
	?>
	<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว ได้แก่</b></font><br><br>
	<?								$sql = "SELECT department_name FROM department WHERE mark_del = 0 AND department_id IN (".implode($dep, ',').") ORDER BY department_no, department_name";				$result2 = mysqli_query($connect, $sql);
				while ($row2 = mysqli_fetch_array($result2)) { 
					echo '- '.$row2['department_name'].'<BR>';
				}
				echo "<BR><BR>";
			} else {
				mysqli_rollback($connect);
	?>
	<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>
	<?			
			}			

			
		}
	}
}

$edit_csa_id = intval($_GET['edit_csa_id']);
$edit_kri_id = intval($_GET['edit_kri_id']);
$edit_ap_id = intval($_GET['edit_ap_id']);

if ($edit_csa_id>0 && $edit_id>0) {
	$sql = "SELECT * FROM csa WHERE csa_id = '$edit_csa_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$is_confirm = $row2['is_confirm'];
?>

<link rel="stylesheet" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.css">
<script type="text/javascript" src="timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="js/jquery.balloon.min.js"></script>
<script type="text/javascript" src="timepicker/jquery.ui.timepicker.js?v=0.3.3"></script>
<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<script>
var pf = [
	[0,19,20,21,24,25],
	[0,12,13,15,22,23],
	[0, 7, 8, 9,14,18],
	[0, 3, 4, 6,11,17],
	[0, 1, 2, 5,10,16]
];
var control_approach = <?=$row2['control_approach']?>;

function risk_profile (i, f) {
	return pf[f][i]; //f i
}

function check_step() {
	var is_finish = false;
	$('#is_finish').css("display", "none");
	
	//var v1 = $('#csa_objective').val();
	var v2 = $('#csa_factor_int').val();
	var v3 = $('#csa_factor_ext').val();
	var v4 = $('#csa_risk_event').val();
	var v5 = $('#csa_risk_type').val();
	var v6 = parseInt($('#frequency').val());
	var v7 = parseInt($('#impact').val());
	var v8 = 0;
	if (v6>0 && v7>0) {
		 v8 = risk_profile(v6, (5-v7));
	}		

	var v9 = $('#control_approach').val();
	var v10 = $('#risk_remain').val();
	var v11 = $('#control_user_id1').val();
	
	if (v2!=0 && v3!=0 && v4!=0 && v5!=0 && v6!=0 && v7!=0 && v9!=0 && v10!='' && v11!=0) {
		is_finish = true;
	}		

	if (is_finish) {
		$('#is_finish').css("display", "");
		$('#is_finish_value').val(1);
	} else {
		$('#is_finish_value').val(0);
	}
}
/*	old
	function cal_risk_current() {
		var s = parseInt($('#frequency').val())+parseInt($('#impact').val());
		var g = 0;
		var c = '';
		if (s<=4) {
			g = 'ต่ำ';
			c = '#56ff5b';
			$('#risk_acceptable').css('background-color', '#56ff5b');
			$('#risk_acceptable').html('ต่ำ');
		} else if (s<=6) {
			g = 'ปานกลาง';
			c = '#ffe256';
			$('#risk_acceptable').css('background-color', '#ffe256');
			$('#risk_acceptable').html('ปานกลาง');
		} else if (s<=8) {
			g = 'สูง';
			c = '#ffaa56';
			$('#risk_acceptable').css('background-color', '#ffe256');
			$('#risk_acceptable').html('ปานกลาง');
		} else  {
			g = 'สูงมาก';
			c = '#ff5656';
			$('#risk_acceptable').css('background-color', '#ffe256');
			$('#risk_acceptable').html('ปานกลาง');
		}				
		$('#risk_current').css('background-color', c);
		$('#risk_current').html(g);
	}
*/
	function cal_risk_current() {
		var impact = Math.max(parseInt($('#impact_financial').val()),parseInt($('#impact_image').val()),parseInt($('#impact_operation').val()),parseInt($('#impact_law').val()),parseInt($('#impact_safety').val()));
		if(impact > 0) {
			$('#impact').val(impact);
			$('#impact_show').val(impact);
		}else{
			$('#impact').val($('#impact_show').val());
		}
		var s = parseInt($('#frequency').val())+parseInt($('#impact').val());
		var g = 0;
		var c = '';
		if (s<=4) {
			g = 'ต่ำ';
			c = '#56ff5b';
			$('#risk_acceptable').css('background-color', '#56ff5b');
			$('#risk_acceptable').html('ต่ำ');
		} else if (s<=6) {
			g = 'ปานกลาง';
			c = '#ffe256';
			$('#risk_acceptable').css('background-color', '#ffe256');
			$('#risk_acceptable').html('ปานกลาง');
		} else if (s<=8) {
			g = 'สูง';
			c = '#ffaa56';
			$('#risk_acceptable').css('background-color', '#ffe256');
			$('#risk_acceptable').html('ปานกลาง');
		} else  {
			g = 'สูงมาก';
			c = '#ff5656';
			$('#risk_acceptable').css('background-color', '#ffe256');
			$('#risk_acceptable').html('ปานกลาง');
		}				
		$('#risk_current').css('background-color', c);
		$('#risk_current').html(g);
	}
/*	old
	function cal_risk_after() {
		var s = parseInt($('#frequency_acc').val())+parseInt($('#impact_acc').val());
		var g = 0;
		var c = '';
		
		if (s<=4) {
			g = 'ต่ำ';
			c = '#56ff5b';
			$('#risk_plan_div').hide();
		} else if (s<=6) {
			g = 'ปานกลาง';
			c = '#ffe256';
			$('#risk_plan_div').show();
		} else if (s<=8) {
			g = 'สูง';
			c = '#ffaa56';
			$('#risk_plan_div').show();
		} else  {
			g = 'สูงมาก';
			c = '#ff5656';
			$('#risk_plan_div').show();
		}		
		$('#risk_after').css('background-color', c);
		$('#risk_after').html(g);
	}
*/
	function cal_risk_after() {		
		var impact_acc = Math.max(parseInt($('#impact_financial_acc').val()),parseInt($('#impact_image_acc').val()),parseInt($('#impact_operation_acc').val()),parseInt($('#impact_law_acc').val()),parseInt($('#impact_safety_acc').val()));
		if(impact_acc > 0) {
			$('#impact_acc').val(impact_acc);
			$('#impact_acc_show').val(impact_acc);
		}else{
			$('#impact_acc').val($('#impact_acc_show').val());
		}
		
		var s = parseInt($('#frequency_acc').val())+parseInt($('#impact_acc').val());
		var g = 0;
		var c = '';
		if (s<=4) {
			g = 'ต่ำ';
			c = '#56ff5b';
			
			$('#high_risk_div').hide();
			$("#control_approach").empty();
			$("#control_approach").append($("<option/>", {"value": "0", "text": "- เลือก -"}));
			$("#control_approach").append($("<option/>", {"value": "1", "text": "การยอมรับความเสี่ยงที่เกิดขึ้น (ACCEPT/Take)"}));
			$("#control_approach").find('option[value="2"]').remove();
			$("#control_approach").find('option[value="3"]').remove();
			$("#control_approach").find('option[value="4"]').remove();
			
			//$('#control_approach option[value=1]').prop('disabled', false);
			//$('#control_approach option[value=1]').css('background-color', '#FFFFFF');
			//$('#control_approach option[value=3]').prop('disabled', false);	
			//$('#control_approach option[value=3]').css('background-color', '#FFFFFF');
		} else if (s<=6) {
			g = 'ปานกลาง';
			c = '#ffe256';
			
			$('#high_risk_div').hide();
			$("#control_approach").empty();
			$("#control_approach").append($("<option/>", {"value": "0", "text": "- เลือก -"}));
			$("#control_approach").find('option[value="1"]').remove();
			$("#control_approach").append($("<option/>", {"value": "2", "text": "การลด/การควบคุมความเสี่ยง (REDUCTION/Treat) เช่น แผนจัดการความเสี่ยง หรือการปรับปรุงระบบการทำงาน"}));
			$("#control_approach").append($("<option/>", {"value": "3", "text": "หลีกเลี่ยงความเสี่ยง (AVOID/Terminate)"}));
			$("#control_approach").append($("<option/>", {"value": "4", "text": "การถ่ายโอนความเสี่ยงให้ผู้อื่นช่วยแบ่งความรับผิดชอบ (SHARING/transfer) เช่น ทำประกัน จ้างผู้ให้บริการภายนอก"}));
			
			//$('#control_approach option[value=1]').prop('disabled', false);
			//$('#control_approach option[value=1]').css('background-color', '#FFFFFF');
			//$('#control_approach option[value=3]').prop('disabled', false);	
			//$('#control_approach option[value=3]').css('background-color', '#FFFFFF');
		} else if (s<=8) {
			g = 'สูง';
			c = '#ffaa56';
			
			$('#high_risk_div').show();
			$("#control_approach").empty();
			$("#control_approach").append($("<option/>", {"value": "0", "text": "- เลือก -"}));
			$("#control_approach").find('option[value="1"]').remove();
			$("#control_approach").append($("<option/>", {"value": "2", "text": "การลด/การควบคุมความเสี่ยง (REDUCTION/Treat) เช่น แผนจัดการความเสี่ยง หรือการปรับปรุงระบบการทำงาน"}));
			$("#control_approach").append($("<option/>", {"value": "3", "text": "หลีกเลี่ยงความเสี่ยง (AVOID/Terminate)"}));
			$("#control_approach").append($("<option/>", {"value": "4", "text": "การถ่ายโอนความเสี่ยงให้ผู้อื่นช่วยแบ่งความรับผิดชอบ (SHARING/transfer) เช่น ทำประกัน จ้างผู้ให้บริการภายนอก"}));
			
			//$('#control_approach option[value=1]').prop('disabled', true);
			//$('#control_approach option[value=1]').css('background-color', '#b5b7ba');
			//$('#control_approach option[value=3]').prop('disabled', true);	
			//$('#control_approach option[value=3]').css('background-color', '#b5b7ba');
		} else  {
			g = 'สูงมาก';
			c = '#ff5656';
			
			$('#high_risk_div').show();
			$("#control_approach").empty();
			$("#control_approach").append($("<option/>", {"value": "0", "text": "- เลือก -"}));
			$("#control_approach").find('option[value="1"]').remove();
			$("#control_approach").append($("<option/>", {"value": "2", "text": "การลด/การควบคุมความเสี่ยง (REDUCTION/Treat) เช่น แผนจัดการความเสี่ยง หรือการปรับปรุงระบบการทำงาน"}));
			$("#control_approach").append($("<option/>", {"value": "3", "text": "หลีกเลี่ยงความเสี่ยง (AVOID/Terminate)"}));
			$("#control_approach").append($("<option/>", {"value": "4", "text": "การถ่ายโอนความเสี่ยงให้ผู้อื่นช่วยแบ่งความรับผิดชอบ (SHARING/transfer) เช่น ทำประกัน จ้างผู้ให้บริการภายนอก"}));
			
			//$('#control_approach option[value=1]').prop('disabled', true);
			//$('#control_approach option[value=1]').css('background-color', '#b5b7ba');
			//$('#control_approach option[value=3]').prop('disabled', true);			
			//$('#control_approach option[value=3]').css('background-color', '#b5b7ba');
		}	
				
		$('#risk_after').css('background-color', c);
		$('#risk_after').html(g);
		
		$('#risk_plan_div').hide();
	}
	
	function cal_risk_plan(){
		var v = parseInt($('#control_approach').val())
		if(v > 1) {
			$('#risk_plan_div').show();
		} else {
			$('#risk_plan_div').hide();
		}
	}
	
/*	old
	$(function () {
		$('.datepicker').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true, 
			locale: {format: 'YYYY-MM-DD'}
		});	


		$('#is_risk').on('change', function() {
			check_risk();
		}).change();		
		
		$('#frequency, #impact').on('change', function() {
			cal_risk_current();
		}).change();			
		$('#frequency_acc, #impact_acc').on('change', function() {
			cal_risk_after();
		}).change();	
	});	
*/	
	$(function () {
		$('.datepicker').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true, 
			locale: {format: 'YYYY-MM-DD'}
		});	

		$('#is_risk').on('change', function() {
			check_risk();
		}).change();		
		
		$('#frequency, #impact_financial, #impact_image, #impact_operation, #impact_law, #impact_safety').on('change', function() {
			cal_risk_current();
		}).change();			
		
		$('#frequency_acc, #impact_acc, #impact_financial_acc, #impact_image_acc, #impact_operation_acc, #impact_law_acc, #impact_safety_acc').on('change', function() { 
			cal_risk_after();
		}).change();
		
		$('#control_approach').on('change', function() { 
			cal_risk_plan();
		}).change();
		
		$('#risk_type').on('change', function() { 
			check_risk_type();
		}).change();
		$('#factor').on('change', function() { 
			check_factor();
		}).change();		
		
		$('#img1').balloon({ 
			html: true,
			contents: "<img src='img/csa4.jpg' width='700' height='300'>",
			position: "bottom right"
		});	
		$('#img2').balloon({ 
			html: true,
			contents: "<img src='img/csa2.jpg' width='1200' height='700'>",
			position: "bottom center",
			backgroundColor: "#000"
		});	
		$('#img3').balloon({ 
			html: true,
			contents: "<img src='img/csa4.jpg' width='700' height='300'>",
			position: "bottom right"
		});	
		$('#img4').balloon({ 
			html: true,
			contents: "<img src='img/csa2.jpg' width='1200' height='700'>",
			position: "bottom center",
			backgroundColor: "#000"
		});		

		$('#csa_control11').on('click', function() {
			if( $('#csa_control11').prop("checked") == true) {
				$('#control_other').show();
			} else {
				$('#control_other').hide();
				$('#control_other').val('');
			}				
		});
		
		$('#control_approach').val(control_approach);
		if(control_approach > 1) {
			$('#risk_plan_div').show();
		} else {
			$('#risk_plan_div').hide();
		}
	});	
	
	function check_risk_type() {
		if ($('#risk_type').val() == 35) {
			$('#risk_type_other').show();
		} else {
			$('#risk_type_other').hide();
			$('#risk_type_other').val('');
		}
	}
	function check_factor() {
		if ($('#factor').val() == 35) {
			$('#factor_other').show();
		} else {
			$('#factor_other').hide();
			$('#factor_other').val('');
		}
	}	
	
	function check_risk() {
		if ($('#is_risk').is(":checked")) {
			$('#risk_div').show();
		} else {
			$('#risk_div').hide();
		}
	}
	
</script>


<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">รายการความเสี่ยง</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$edit_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			
	<form method='post' action='csa_admin.php?edit_csa_id=<?=$edit_csa_id?>&edit_id=<?=$edit_id?>#bottom'>  

	<div class="form-group">
	  <label>ปี พ.ศ.</label>
	  <input type="text" class="form-control" name='csa_year' readonly value='<?=$row2['csa_year']?>'>
	</div>	
	<div class="form-group">
	  <label>ฝ่ายรับผิดชอบ</label>
	  <input type="text" class="form-control" name='department_name' readonly value='<?=$row2['department_name']?>'>
	</div>	
	<div class="form-group">
	  <label>ส่วนงาน</label>
	  <input type="text" class="form-control" name='section' value='<?=$row2['section']?>'>
	</div>	
	<div class="form-group">
	  <label>ผู้รับผิดชอบ</label>
	  <input type="text" class="form-control" name='owner' placeholder="ผู้รับผิดชอบ / ฝ่ายรับผิดชอบ" value='<?=$row2['owner']?>'>
	</div>
<!--	
	<div class="form-group">
	  <label>วันที่เริ่มต้นกิจกรรม</label>
	  <input type="text" class="form-control datepicker" name='date_begin' placeholder="วันที่เริ่มต้นกิจกรรม" value='<?=$row2['date_begin']?>' readonly>
	</div>	
	<div class="form-group">
	  <label>วันที่สิ้นสุดกิจกรรม</label>
	  <input type="text" class="form-control datepicker" name='date_end' placeholder="วันที่สิ้นสุดกิจกรรม" value='<?=$row2['date_end']?>' readonly>
	</div>
-->
	<br>
	<div class="form-group">
	  <label>กิจกรรม/ภาระหน้าที่</label>
	  <input type="text" class="form-control" name='activity_name' placeholder="กิจกรรมเสี่ยง" value='<?=$row2['activity_name']?>'>
	</div>
	<div class="form-group">
	  <label>วัตถุประสงค์ของงาน</label>
	  <input type="text" class="form-control" name='objective' placeholder="วัตถุประสงค์ของงาน" value='<?=$row2['objective']?>'>
	</div>
	<div class="form-group">
	<label>ข้อสังเกตจากหน่วยงานกำกับ</label>
	<table class='table table-hover table-light'>
	<thead>
	<tr>
		<td width='5%'>ลำดับ</td>
		<td width='15%'>วันที่</td>
		<td width='80%'>ข้อสังเกต / ข้อ Comment</td>
	</tr>
	</thead>
	<tbody>
<?
		$sql3 = "SELECT * FROM csa_comment WHERE csa_department_id = '".$row2['csa_department_id']."' AND csa_year='".$row2['csa_year']."' ORDER BY create_date desc";
		// echo $sql3;
		$result3 = mysqli_query($connect, $sql3);
		$i = 1;
		if (mysqli_num_rows($result3)>0) {
			while ($row3 = mysqli_fetch_array($result3)) {
?>
			<tr  style='cursor: pointer;'>
				<td><?= $i ?></td>
				<td><?=$row3['create_date']?></td>
				<td><?=htlm2text($row3['comment'])?></td>
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
?>
	</tbody>
	</table>
	</div>
	
	<br>
	<div class="form-group">
	  <label>ความเสี่ยง</label><br>
	  <input type="checkbox" name='is_risk' id='is_risk' value='1' <?if ($row2['is_risk']==1) echo 'checked'?>> กิจกรรมนี้มีความเสี่ยง
	</div>
	<hr>
	
	<div id='risk_div' style='display:none'>
	
	<div class="form-group">
	  <label>ความเสี่ยงของงาน</label>
	  <input type="text" class="form-control" name='risk_name' placeholder="ความเสี่ยงของงาน" value='<?=$row2['risk_name']?>'>
	</div>	

<!--
	<b>การปฏิบัติตามกฎหมาย</b><br>
			<div class='table-responsive'>
			<table class="table table-hover table-condensed">
			<thead>
			<tr>
				<td width='3%'></td>
				<td width='45%'></td>
				<td width='45%'></td>
				<td width='3%'></td>
			</tr>
			</thead>
			<tbody>
<? 

		$j=1;
		$sql = "SELECT * FROM csa_law_data
		WHERE 
			mark_del = '0' AND 
			csa_id = '$edit_csa_id' 
		ORDER BY 
			create_date ";		
			
		//echo "xxx : $sql";	
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
			<tr>
				<td width='3%'><?=$j++?></td>
				<td width='45%'>
					<select name='csa_law_id_<?=$row1['csa_law_data_id']?>' class='form-control input-sm csa_law_id' <?=$lock_tag?> onChange='check_csa_law(this, <?=$row1['csa_law_data_id']?> )'>
						<option value='0'>--- เลือก ---</option>
<?
				$sql = "SELECT * FROM csa_law WHERE mark_del = 0 ";
				$result = mysqli_query($connect, $sql);
				$display = "";
				while ($row = mysqli_fetch_array($result)) {
?>
						<option value='<?=$row['csa_law_id']?>' <?if ($row1['csa_law_id']==$row['csa_law_id']) echo 'selected';?>><?=$row['law_name']?></option>
<?				
					if($row1['csa_law_id'] != 18) {$display = "style='display:none;'";}
					//echo "xxx ".$row['csa_law_id']." : ".$display;

} ?>

					</select>
<?				
				
?>					
					<input type="text" class='form-control input-sm' id="csa_law_other_<?=$row1['csa_law_data_id']?>" name="csa_law_other_<?=$row1['csa_law_data_id']?>" placeholder='กรุณาระบุ ชื่อคำสั่ง/ประกาศ' value='<?=$row1['other_law']?>' <?=$lock_tag?> <? echo $display; ?>>
				</td>
				<td width='45%'><input type="text" class='form-control input-sm' name="csa_law_desc_<?=$row1['csa_law_data_id']?>" placeholder='ความเสี่ยงของการปฎิบัติ' value='<?=$row1['description']?>' <?=$lock_tag?>></td>
				<td width='3%' align='right'><?if (!$is_lock) {?><a href="csa.php?edit_id=<?=$edit_id?>&del_law_id=<?=$row1['csa_law_data_id']?>&view_dep=<?=$view_dep?>#bottom2" onClick='return confirm("Confirm Delete?")' class="delete-row">delete</a><? } ?></td>
			</tr>
<?
			} 
		} else {
?>
			<tr>
				<td colspan='19'> ยังไม่มีข้อมูล </td>
			</tr>
<?				
		}
?>
			</tbody>
			</table>
			</div>	
<? if ($is_confirm==0) { ?>	
	<a name='bottom2'></a>
	<button type='submit' name='submit' value='add_law' class="btn btn-primary btn-xs"><i class='fa fa-plus-circle'></i> เพิ่มการปฏิบัติตามกฎหมาย</button>
<? }?>	
<br>
<hr>
<br>	
-->
	<div class="form-group">
	  <label>ประเภทความเสี่ยง</label>
		<select name='risk_type' id='risk_type' class='form-control'>
			<option value='0'>--- เลือก ---</option>
<?
	$sql = "SELECT * FROM csa_risk_type WHERE parent_id = 0 ";
	$result1 = mysqli_query($connect, $sql);
	while ($row1 = mysqli_fetch_array($result1)) {
?>
<optgroup label="<?=$row1['risk_type_name']?>">
<?		
		$sql = "SELECT * FROM csa_risk_type WHERE parent_id = '$row1[csa_risk_type_id]' ";
		$result = mysqli_query($connect, $sql);
		while ($row = mysqli_fetch_array($result)) {
?>
			<option value='<?=$row['csa_risk_type_id']?>' <?if ($row['csa_risk_type_id']==$row2['risk_type']) echo 'selected';?>><?=$row['risk_type_name']?></option>
<?		} ?>
</optgroup>
<?	} ?>
		</select>
		<input type="text" class='form-control input-sm' id="risk_type_other" name="risk_type_other" placeholder='อื่นๆ กรุณาระบุ' value='<?=$row2['risk_type_other']?>' <?=$lock_tag?> <?if ($row2['risk_type']!='35') echo "style='display:none'";?>>
	</div>
	
	<div class="form-group">
	  <label>ปัจจัยเสี่ยง</label>
		<select name='factor' id='factor' class='form-control'>
			<option value='0'>--- เลือก ---</option>
<?
	$sql = "SELECT * FROM csa_factor WHERE parent_id = 0 ";
	$result1 = mysqli_query($connect, $sql);
	while ($row1 = mysqli_fetch_array($result1)) {
?>			
<optgroup label="<?=$row1['factor']?>">
<?		
		$sql = "SELECT * FROM csa_factor WHERE parent_id = '$row1[csa_factor_id]' ";
		$result = mysqli_query($connect, $sql);
		while ($row = mysqli_fetch_array($result)) {
?>
			<option value='<?=$row['csa_factor_id']?>' <?if ($row['csa_factor_id']==$row2['factor']) echo 'selected';?>><?=$row['factor']?></option>
<?		} ?>
</optgroup>
<?	} ?>

		</select>
		<input type="text" class='form-control input-sm' id="factor_other" name="factor_other" placeholder='อื่นๆ กรุณาระบุ' value='<?=$row2['factor_other']?>' <?=$lock_tag?> <?if ($row2['factor']!='35') echo "style='display:none'";?>>
	</div>
	
	<br><hr>
	<div class="form-group">
		<label>ผลกระทบก่อนมีการควบคุม</label>&nbsp; <img id='img2' src='img/help-icon.jpg' />
	</div>
	<div class='row'>
		<div class='col-md-6'>	
			<div class="form-group">
			  <label>โอกาสที่จะเกิดเหตุการณ์ (Likelihood) </label>&nbsp; <img id='img1' src='img/help-icon.jpg' />
				<select name='frequency' id='frequency' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['frequency']) echo 'selected';?>>ระดับ <?=$i?> = <?= level_frequency($i) ?></option>
		<?		} ?>
				</select>	
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านการเงิน (บาท)</label>
				<select name='impact_financial' id='impact_financial' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_financial']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านภาพพจน์</label>
				<select name='impact_image' id='impact_image' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_image']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านการปฏิบัติงาน</label>
				<select name='impact_operation' id='impact_operation' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_operation']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านกฎหมาย กฎเกณฑ์</label>
				<select name='impact_law' id='impact_law' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_law']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านความปลอดภัย</label>
				<select name='impact_safety' id='impact_safety' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_safety']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
	</div>
	<div class='row'>
		<div class='col-md-3'>
			<div class="form-group">
			  <label>สรุปผลกระทบก่อนมีการควบคุม </label>
				<input type='hidden' name='impact' id='impact' value=''>
				<select name='impact_show' id='impact_show' class='form-control' disabled>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
	</div>
	<div class='row'>
		<div class='col-md-3'>	
			<div class="form-group">
			  <label>ระดับความเสี่ยงก่อนมีการควบคุม</label>
			  <div style='border: 1px solid; height: 30px; font-weight: bold' id='risk_current' align='center'></div>
			</div>		
		</div>
		<!--
		<div class='col-md-2'>	
			<div class="form-group">
			  <label>ระดับความเสี่ยงที่ยอมรับได้</label>
			  <div style='border: 1px solid; height: 30px; font-weight: bold' id='risk_acceptable' align='center'></div>
			</div>		
		</div>
		-->
	</div>
	<hr><br>
	
	<div class="form-group">
	  <label>การควบคุมที่มีอยู่</label><br>
<?
	$control_list = explode(',', $row2['control']);
	
	$sql = "SELECT * FROM csa_control";
	$result1 = mysqli_query($connect, $sql);
	$i = 1;
	while ($row1 = mysqli_fetch_array($result1)) {
?>		
		<label><input type='checkbox' id="csa_control<?= $i++ ?>" name='csa_control[]' value='<?=$row1['csa_control_id']?>' <?if (in_array($row1['csa_control_id'], $control_list)) echo 'checked';?>> <?=$row1['control_name']?></label><br>
<?	} ?>
	<input type="text" class='form-control input-sm' id="control_other" name="control_other" placeholder='อื่นๆ กรุณาระบุ' value='<?=$row2['control_other']?>' <?=$lock_tag?> <?if (!in_array(11, $control_list)) echo "style='display:none'";?>>
	</div>
	
	<br><hr>
	<div class="form-group">
		<label>ผลกระทบหลังการควบคุม </label>&nbsp; <img id='img4' src='img/help-icon.jpg' />
	</div>
	<div class='row'>
		<div class='col-md-6'>	
			<div class="form-group">
			  <label>โอกาสที่จะเกิดเหตุการณ์ (Likelihood)</label>&nbsp; <img id='img3' src='img/help-icon.jpg'  />
				<select name='frequency_acc' id='frequency_acc' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['frequency_acc']) echo 'selected';?>>ระดับ <?=$i?> = <?= level_frequency($i) ?></option>
		<?		} ?>
				</select>	
			</div>	
		</div>	
	</div>
	<div class='row'>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านการเงิน (บาท)</label>
				<select name='impact_financial_acc' id='impact_financial_acc' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_financial_acc']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านภาพพจน์</label>
				<select name='impact_image_acc' id='impact_image_acc' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_image_acc']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านการปฏิบัติงาน</label>
				<select name='impact_operation_acc' id='impact_operation_acc' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_operation_acc']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านกฎหมาย กฎเกณฑ์</label>
				<select name='impact_law_acc' id='impact_law_acc' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_law_acc']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
		<div class='col-md-2'>
			<div class="form-group">
			  <label>ด้านความปลอดภัย</label>
				<select name='impact_safety_acc' id='impact_safety_acc' class='form-control'>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_safety_acc']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
	</div>
	<div class='row'>
		<div class='col-md-3'>
			<div class="form-group">
			  <label>สรุปผลกระทบหลังการควบคุม</label>
				<input type='hidden' name='impact_acc' id='impact_acc' value=''>
				<select name='impact_acc_show' id='impact_acc_show' class='form-control' disabled>
					<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=5; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['impact_acc']) echo 'selected';?>><?=$i?> - <?= level_value($i) ?></option>
		<?		} ?>
				</select>	
			</div>		
		</div>
	</div>
	<div class='row'>
		<div class='col-md-3'>	
			<div class="form-group">
			  <label>ระดับความเสี่ยงหลังการควบคุม</label>
			  <div style='border: 1px solid; height: 30px; font-weight: bold' id='risk_after' align='center'></div>
			</div>		
		</div>
	</div>	
	<hr><br>
	
	<div class="form-group">
	  <label>ความเสี่ยงที่ยังมีอยู่</label>
		<textarea name='risk_remain' id='risk_remain' class='form-control' rows='2'><?=$row2['risk_remain']?></textarea>	
	</div>
	
	<div class="form-group">
	  <label>ผู้รับผิดชอบในการจัดการความเสี่ยง</label>
	  <input type="text" class="form-control" name='control_owner' placeholder="ผู้รับผิดชอบในการจัดการความเสี่ยง" value='<?=$row2['control_owner']?>'>
	</div>

	<div class="form-group">
		<label>วิธีการจัดการความเสี่ยง</label>
		<div id='high_risk_div' style='color:red; display:none'><!--ความเสี่ยงปัจจุบันอยู่ในระดับสูง/สูงมาก กรุณาเลือก ลดความเสี่ยง หรือ โอนย้ายความเสี่ยง--></div>
		<select name='control_approach' id='control_approach' class='form-control'>
			<option value='0'>- เลือก -</option>
<?		for ($i=1; $i<=4; $i++) { ?>
			<option value='<?=$i?>' <?if ($i==$row2['control_approach']) echo 'selected';?>><?=control_approach($i)?></option>
<?		} ?>
		</select>
		<div style='color:red;'><!--หมายเหตุ : กรณีระดับความเสี่ยงอยู่ในระดับ "สูง" หรือ "สูงมาก" กำหนดให้เลือกเฉพาะ Transfer / Treat เพื่อจัดทำแผนลดความเสี่ยง--></div>
	</div>

	<div id='risk_plan_div' style='display:none'>
	<br><hr>
	<b>แผนลดความเสี่ยง</b><br><br>
	<div class="row">
	  <div class='col-md-2'><label>ชื่อแผนงาน</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='plan_name' placeholder="ชื่อแผนงาน" value='<?=$row2['plan_name']?>'></div>
	</div>	
			<div class='table-responsive'>
			<table class="table table-hover table-condensed">
			<thead>
			<tr>
				<th width='3%' rowspan='2'></th>
				<th width='15%' rowspan='2'>กิจกรรมดำเนินการ</th>
				<th width='15%' rowspan='2'>เป้าหมาย/<br>ความสำเร็จ<br>ของการดำเนินการ</th>
				<th width='5%' rowspan='2'>น้ำหนัก <br>(%)</th>
				<th width='36%' colspan='12'>ระยะเวลาดำเนินการ<br>ในเดือนที่ (<?=$row2['csa_year']+1?>)</th>
				<th width='9%' rowspan='2'>งบประมาณ/<br>ค่าใช้จ่าย <br>(ถ้ามี)</th>
				<th width='9%' rowspan='2'>หมายเหตุ</th>
				<th width='3%' rowspan='2'></th>
			</tr>
			<tr>
				<td width='3%'>1</td>
				<td width='3%'>2</td>
				<td width='3%'>3</td>
				<td width='3%'>4</td>
				<td width='3%'>5</td>
				<td width='3%'>6</td>
				<td width='3%'>7</td>
				<td width='3%'>8</td>
				<td width='3%'>9</td>
				<td width='3%'>10</td>
				<td width='3%'>11</td>
				<td width='3%'>12</td>
			</tr>
			</thead>
			<tbody>
<? 

		$j=1;
		$sql = "SELECT * FROM csa_plan 
		WHERE 
			mark_del = '0' AND 
			csa_id = '$edit_csa_id' 
		ORDER BY 
			create_date ";		
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
			<tr>
				<td width='3%'><?=$j++?></td>
				<td width='15%'><input type="text" class='form-control input-sm' name="activity_name_<?=$row1['csa_plan_id']?>" placeholder='กิจกรรมดำเนินการ' value='<?=$row1['activity_name']?>' <?=$lock_tag?>></td>
				<td width='15%'><input type="text" class='form-control input-sm' name="target_<?=$row1['csa_plan_id']?>" placeholder='เป้าหมาย' value='<?=$row1['target']?>' <?=$lock_tag?>></td>
				<td width='5%'><input type="text" class='form-control input-sm' name="weight_<?=$row1['csa_plan_id']?>" placeholder='น้ำหนัก' value='<?=$row1['weight']?>' <?=$lock_tag?>></td>
<? for ($i=1; $i<=12; $i++){?>				
				<td width='3%'><input type='checkbox' name='m<?=$i?>_<?=$row1['csa_plan_id']?>' value='1' <?if ($row1['m'.$i]==1) echo 'checked'?>></td>
<?}?>				
				<td width='9%'><input type="text" class='form-control input-sm' name="budget_<?=$row1['csa_plan_id']?>" placeholder='งบประมาณ' value='<?=$row1['budget']?>' <?=$lock_tag?>></td>
				<td width='9%'><input type="text" class='form-control input-sm' name="remark_<?=$row1['csa_plan_id']?>" placeholder='หมายเหตุ' value='<?=$row1['remark']?>' <?=$lock_tag?>></td>
				<td width='3%' align='right'><?if (!$is_lock) {?><a href="csa_admin.php?edit_csa_id=<?=$edit_csa_id?>&del_pid=<?=$row1['csa_plan_id']?>&edit_id=<?=$edit_id?>#bottom" onClick='return confirm("Confirm Delete?")' class="delete-row">ลบ</a><? } ?></td>
			</tr>
<?
			} 
		} else {
?>
			<tr>
				<td colspan='19'> ยังไม่มีข้อมูล </td>
			</tr>
<?				
		}
?>
	</tbody>
	</table>
	</div>	
<? if ($is_confirm==0) { ?>	
	<a name='bottom'></a>
	<button type='submit' name='submit' value='add_plan' class="btn btn-primary btn-xs"><i class='fa fa-plus-circle'></i> เพิ่มแผนจัดการความเสี่ยง</button>
<? }?>	

<br>
<br>
<hr>
<br>

<!--	<b>การปฏิบัติตามกฎหมาย</b><br>
			<div class='table-responsive'>
			<table class="table table-hover table-condensed">
			<thead>
			<tr>
				<td width='3%'></td>
				<td width='45%'></td>
				<td width='45%'></td>
				<td width='3%'></td>
			</tr>
			</thead>
			<tbody>
<? 

		$j=1;
		$sql = "SELECT * FROM csa_law_data
		WHERE 
			mark_del = '0' AND 
			csa_id = '$edit_csa_id' 
		ORDER BY 
			create_date ";		
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
			<tr>
				<td width='3%'><?=$j++?></td>
				<td width='45%'>
					<select name='csa_law_id_<?=$row1['csa_law_data_id']?>' class='form-control  input-sm' <?=$lock_tag?>>
						<option value='0'>--- เลือก ---</option>
<?
				$sql = "SELECT * FROM csa_law WHERE mark_del = 0 ";
				$result = mysqli_query($connect, $sql);
				while ($row = mysqli_fetch_array($result)) {
?>
						<option value='<?=$row['csa_law_id']?>' <?if ($row1['csa_law_id']==$row['csa_law_id']) echo 'selected';?>><?=$row['law_name']?></option>
<?				} ?>

					</select>
				</td>
				<td width='45%'><input type="text" class='form-control input-sm' name="csa_law_desc_<?=$row1['csa_law_data_id']?>" placeholder='ความเสี่ยงของการปฎิบัติ' value='<?=$row1['description']?>' <?=$lock_tag?>></td>
				<td width='3%' align='right'><?if (!$is_lock) {?><a href="csa_admin.php?edit_csa_id=<?=$edit_csa_id?>&edit_id=<?=$edit_id?>&del_law_id=<?=$row1['csa_law_data_id']?>&view_dep=<?=$view_dep?>#bottom2" onClick='return confirm("Confirm Delete?")' class="delete-row">ลบ</a><? } ?></td>
			</tr>
<?
			} 
		} else {
?>
			<tr>
				<td colspan='19'> ยังไม่มีข้อมูล </td>
			</tr>
<?				
		}
?>
			</tbody>
			</table>
			</div>
<? if ($is_confirm==0) { ?>	
	<a name='bottom2'></a>
	<button type='submit' name='submit' value='add_law' class="btn btn-primary btn-xs"><i class='fa fa-plus-circle'></i> เพิ่มการปฏิบัติตามกฎหมาย</button>
<? }?>	
<br>
<hr>
<br>
<br>-->
<!--
<b>ตัวชี้วัดความสำเร็จ </b><br><br>
	<div class="row">
	  <div class='col-md-2'><label>ระดับ 1</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='plan_level1' placeholder="ระดับ 1" value='<?=$row2['plan_level1']?>'></div>
	</div>	
	<div class="row">
	  <div class='col-md-2'><label>ระดับ 2</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='plan_level2' placeholder="ระดับ 2" value='<?=$row2['plan_level2']?>'></div>
	</div>	
	<div class="row">
	  <div class='col-md-2'><label>ระดับ 3</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='plan_level3' placeholder="ระดับ 3" value='<?=$row2['plan_level3']?>'></div>
	</div>	
	<div class="row">
	  <div class='col-md-2'><label>ระดับ 4</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='plan_level4' placeholder="ระดับ 4" value='<?=$row2['plan_level4']?>'></div>
	</div>	
	<div class="row">
	  <div class='col-md-2'><label>ระดับ 5</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='plan_level5' placeholder="ระดับ 5" value='<?=$row2['plan_level5']?>'></div>
	</div>	
	<div class="row">
	  <div class='col-md-2'><label>ระดับเป้าหมาย</label></div>
	  <div class='col-md-2'>
		<select name='plan_level_target' id='plan_level_target' class='form-control'>
			<option value='0'>- เลือก -</option>
<?		for ($i=1; $i<=5; $i++) { ?>
			<option value='<?=$i?>' <?if ($i==$row2['plan_level_target']) echo 'selected';?>><?=$i?></option>
<?		} ?>
		</select>		  
	  </div>
	</div>	
	<div class="row">
	  <div class='col-md-2'><label>เป้าหมายความสำเร็จ</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='plan_target' placeholder="เป้าหมายความสำเร็จ" value='<?=$row2['plan_target']?>'></div>
	</div>	
	<div class="row">
	  <div class='col-md-2'><label>กำหนดเสร็จ</label></div>
	  <div class='col-md-5'><input type="text" class="form-control" name='deadline' placeholder="กำหนดเสร็จ" value='<?=$row2['deadline']?>'></div>
	</div>
-->

</div>	
	
	<br>
	<br>
<!--	
<?
//$data = gen_riskprofile_url($edit_csa_id, $csa_id);
//echo "[$data]";
?>
<img src='risk_map.php?d=<?=$data?>'>	-->
	</div>	

	


	<br>
	<br>
	<br>

	<input type='hidden' name='update_csa_id' value='<?=$edit_csa_id?>'>
	<button type='button' class="btn btn-default" onClick="document.location='csa_admin.php?edit_id=<?=$edit_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<? if ($is_confirm==0) { ?>	
	<button type='submit' name='submit' value='save_csa' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
<? }?>	

	</form>
		</div>
	</div>
</div>
		  
<?
	}
	
	echo template_footer();
	exit;

} else if ($edit_kri_id>0 && $edit_id>0) {
	$sql = "SELECT * FROM csa_kri WHERE kri_id = '$edit_kri_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$is_confirm = $row2['is_confirm'];
		
		$kri_risk = [];
		$sql = "SELECT csa_id,risk_name,control_owner FROM csa WHERE csa_department_id = '$edit_id' and risk_name != '' AND mark_del = '0' ";
		$result = mysqli_query($connect, $sql);
		while ($row = mysqli_fetch_array($result)) {
			$kri_risk[$row['csa_id']] = [
				'risk_name' => $row['risk_name'],
				'control_owner' => $row['control_owner']
			];
		}
?>
<script type="text/javascript" src="js/jquery.balloon.min.js"></script>
<script>
	var kri_risk = <?=json_encode($kri_risk)?>;
	
	$(function () {
		$("#select_risk").change(function(){
			var select_risk = $('#select_risk').val();
			var selected_risk = kri_risk[select_risk];
			
			$('#control_owner').val(selected_risk['control_owner']);
			$('#risk_name').val(selected_risk['risk_name']);
			$('#csa_id').val(select_risk);
		});
		
		$('#source_help').balloon({ 
			contents: "หมายถึง ฝ่ายงานที่ส่งข้อมูลเพื่อสนับสนุนการเก็บข้อมูล KRI",
			position: "bottom right",
			css: {
				fontSize: "1.6rem",
			}	
		});	
		$('#description_help').balloon({ 
			contents: "หมายถึง อธิบายความสัมพันธ์ของตัวชี้วัดความเสี่ยง (KRI) ที่มาของข้อมูล เช่น Checker ตรวจพบข้อผิดพลาดจากรายงานประจำเดือน",
			position: "bottom right",
			css: {
				fontSize: "1.6rem",
			}	
		});	
		$('#unit_help').balloon({ 
			contents: "หมายถึง กำหนดหน่วยวัดเป็น จำนวน, รายการ, วัน, ระยะเวลา, ร้อยละ เป็นต้น",
			position: "bottom right",
			css: {
				fontSize: "1.6rem",
			}	
		});	
		$('#frequency_help').balloon({ 
			contents: "หมายถึง  กำหนดระยะเวลาเป็น รายเดือน รายไตรมาส",
			position: "bottom right",
			css: {
				fontSize: "1.6rem",
			}	
		});

		$("#level_acceptable").change(function(){
			var level_acceptable = $('#level_acceptable').val();
			if(level_acceptable == 3){
				$('#level_acceptable_other').css("display","");
			}else{
				$('input[name="level_acceptable_desc"]').val("");
				$('#level_acceptable_other').css("display","none");
			}
		});
		
		$("#level_alert").change(function(){
			var level_alert = $('#level_alert').val();
			if(level_alert == 3){
				$('#level_alert_other').css("display","");
			}else{
				$('input[name="level_alert_desc"]').val("");
				$('#level_alert_other').css("display","none");
			}
		});
		
		$("#level_problem").change(function(){
			var level_problem = $('#level_problem').val();
			if(level_problem == 3){
				$('#level_problem_other').css("display","");
			}else{
				$('input[name="level_problem_desc"]').val("");
				$('#level_problem_other').css("display","none");
			}
		});
		
	});	
	
</script>
<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">แก้ไข KRI</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$edit_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			
			<form method='post' action='csa_admin.php?edit_id=<?=$edit_id?>#bottom'>  
			<div class="form-group">
			  <label>ลำดับที่</label>
			  <input type="text" class="form-control" name='sequence' placeholder="ลำดับที่" value='<?=$row2['sequence']?>'>
			</div>
			<!--<div class="form-group">
			  <label>KRI ของกิจกรรมเสี่ยง</label>
				<select name='csa_id' class='form-control'>
					<option value='0'>--- เลือก ---</option>
				<?
					$sql = "SELECT * FROM csa WHERE csa_department_id = '$row2[csa_department_id]' AND is_risk = '1' ";
					$result1 = mysqli_query($connect, $sql);
					while ($row1 = mysqli_fetch_array($result1)) {
				?>
							<option value='<?=$row1['csa_id']?>' <?if ($row1['csa_id']==$row2['csa_id']) echo 'selected';?>><?=$row1['activity_name']?></option>
				<?	} ?>

				</select>
			</div>		-->
			<!--<div class="form-group">
			  <label>ชื่อความเสี่ยง</label>
			  <input type="text" class="form-control" name='risk_name' placeholder="ชื่อความเสี่ยง" value='<?=$row2['risk_name']?>'>
			</div>	-->
			
			<div class="form-group">
			  <label>ความเสี่ยงของงาน</label>
				<select name='select_risk' id='select_risk' class='form-control'>
				<option value='0' >--- เลือก ---</option>
				<? foreach($kri_risk as $csa_id => $data_risk){ ?>
				<option value='<?=$csa_id?>' <?if ($csa_id==$row2['csa_id']) echo 'selected';?>><?=$data_risk['risk_name']?></option>
				<? } ?>
				</select>
			</div>
			
			<div class="form-group">
			  <label>ชื่อตัวชี้วัดความเสี่ยง (KRI)	</label>
			  <input type="text" class="form-control" name='index_no' placeholder="ชื่อดัชนี" value='<?=$row2['index_no']?>'>
			</div>
			
			<div class="form-group">
			  <label>ชนิดของ KRI</label>
				<select name='kri_type' class='form-control'>
					<option value='leading' <?if ($row2['kri_type']=='leading') echo 'selected';?>>Leading KRI</option>
					<option value='lagging' <?if ($row2['kri_type']=='lagging') echo 'selected';?>>Lagging KRI</option>
				</select>
			</div>
			
			<div class="form-group">
			  <input type="checkbox" name='kri_risk' id='kri_risk' value='1' <?if ($row2['kri_risk']==1) echo 'checked'?>> ข้อมูลอ้างอิง
			</div>
			
			<div class="form-group">
			  <label>แหล่งที่มาของข้อมูล</label>
			  <input type="text" class="form-control" name='source' placeholder="แหล่งที่มาของข้อมูล" value='<?=$row2['source']?>'>
			</div>		
			<div class="form-group">
			  <label>คำอธิบายความสัมพันธ์ของดัชนีชี้วัดกับรายการความเสี่ยง</label>
			  <textarea class="form-control" name='description' placeholder="คำอธิบายความสัมพันธ์ของดัชนีชี้วัดกับรายการความเสี่ยง" rows='3'><?=$row2['description']?></textarea>
			</div>
			<div class="form-group">
			  <label>หน่วยวัด</label>
			  <input type="text" class="form-control" name='unit' placeholder="หน่วยวัด" value='<?=$row2['unit']?>'>
			</div>
			
			<div class="form-group">
			  <label>ความถี่ของการเก็บข้อมูล</label>
			  <!--<input type="text" class="form-control" name='frequency' placeholder="ความถี่ของการเก็บข้อมูล" value='<?=$row2['frequency']?>'>-->
				<select name='frequency' class='form-control'>
					<option value='รายเดือน' <?if (strpos($row2['frequency'], 'เดือน') !== false) echo 'selected';?>>รายเดือน</option>
					<option value='รายไตรมาส' <?if (strpos($row2['frequency'], 'ไตรมาส') !== false) echo 'selected';?>>รายไตรมาส</option>
					<option value='รายปี' <?if (strpos($row2['frequency'], 'ปี') !== false) echo 'selected';?>>รายปี</option>
				</select>
			</div>
			<div class="form-group">
			  <label>ระดับทีปลอดภัย</label>
				<select name='level_acceptable' id='level_acceptable' class='form-control'>
				<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=3; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['level_acceptable']) echo 'selected';?>><?=level_acceptable($i)?></option>
		<?		}
				if($row2['level_acceptable']==3){
					$display_acceptable = "";
				}else{
					$display_acceptable = "style='display:none'";
				}
		?>
				</select>
				<div id="level_acceptable_other" <?=$display_acceptable?>>
					<input type="text" class="form-control" name='level_acceptable_desc' id='level_acceptable_desc' placeholder="รายละเอียด" value='<?=$row2['level_acceptable_desc']?>'>
				</div>
			</div>	
			<div class="form-group">
			  <label>ระดับเฝ้าระวัง</label>
				<select name='level_alert' id='level_alert' class='form-control'>
				<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=3; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['level_alert']) echo 'selected';?>><?=level_alert($i)?></option>
		<?		}
				if($row2['level_alert']==3){
					$display_alert = "";
				}else{
					$display_alert = "style='display:none'";
				}
		?>
				</select>
				<div id="level_alert_other" <?=$display_alert?>>
					<input type="text" class="form-control" name='level_alert_desc' id='level_alert_desc' placeholder="รายละเอียด" value='<?=$row2['level_alert_desc']?>'>
				</div>
			</div>	
			<div class="form-group">
			  <label>ระดับแจ้งเตือน</label>
				<select name='level_problem' id='level_problem' class='form-control'>
				<option value='0'>- เลือก -</option>
		<?		for ($i=1; $i<=3; $i++) { ?>
					<option value='<?=$i?>' <?if ($i==$row2['level_problem']) echo 'selected';?>><?=level_problem($i)?></option>
		<?		}
				if($row2['level_problem']==3){
					$display_problem = "";
				}else{
					$display_problem = "style='display:none'";
				}
		?>
				</select>
				<div id="level_problem_other" <?=$display_problem?>>
					<input type="text" class="form-control" name='level_problem_desc' id='level_problem_desc' placeholder="รายละเอียด" value='<?=$row2['level_problem_desc']?>'>
				</div>
			</div>
			<div class="form-group">
			  <label>ผู้รับผิดชอบ</label>
			  <input type="text" class="form-control" name='control_owner' id='control_owner' placeholder="ผู้รับผิดชอบ" value='<?=$row2['control_owner']?>' readonly>
			  <input type="hidden" class="form-control" name='csa_id' id='csa_id' value='<?=$row2['csa_id']?>'>
			  <input type="hidden" class="form-control" name='risk_name' id='risk_name' value='<?=$row2['risk_name']?>'>
			</div>
			<div class="form-group">
			  <label>ผู้จัดเก็บข้อมูล</label>
			  <input type="text" class="form-control" name='owner' placeholder="ผู้จัดเก็บข้อมูล" value='<?=$row2['owner']?>'>
			</div>
			<br>
			<br>
			<br>

			<button type='button' class="btn btn-default" onClick="document.location='csa_admin.php?edit_id=<?=$edit_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
		<? if ($is_confirm==0) { ?>	
			<input type='hidden' name='update_kri_id' value='<?=$edit_kri_id?>'>
			<button type='submit' name='submit' value='save_kri' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
		<? }?>	

			</form>
		</div>
	</div>
</div>


<?	
	}
	echo template_footer();
	exit;

	
} /* else if ($edit_ap_id>0) {
	$sql = "SELECT * FROM csa_action_plan WHERE ap_id = '$edit_ap_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$is_confirm = $row2['is_confirm'];
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">แก้ไข ACTION PLAN</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$edit_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			
	<form method='post' action='csa_admin.php?edit_id=<?=$edit_id?>#bottom'>  
	<div class="form-group">
	  <label>ลำดับที่</label>
	  <input type="text" class="form-control" name='sequence' placeholder="ลำดับที่" value='<?=$row2['sequence']?>'>
	</div>
	<div class="form-group">
	  <label>Action Plan ของกิจกรรมเสี่ยง</label>
		<select name='csa_id' class='form-control'>
			<option value='0'>--- เลือก ---</option>
<?
	$sql = "SELECT * FROM csa WHERE csa_department_id = '$edit_id' AND is_risk = '1' ";
	$result1 = mysqli_query($connect, $sql);
	while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['csa_id']?>' <?if ($row1['csa_id']==$row2['csa_id']) echo 'selected';?>><?=$row1['activity_name']?></option>
<?	} ?>

		</select>
	</div>	
	<div class="form-group">
	  <label>ชื่อความเสี่ยง</label>
	  <input type="text" class="form-control" name='risk_name' placeholder="ชื่อความเสี่ยง" value='<?=$row2['risk_name']?>'>
	</div>	
	<div class="form-group">
	  <label>ระดับความเสี่ยงที่เหลืออยู่	</label>
	  <input type="text" class="form-control" name='risk_remain' placeholder="ระดับความเสี่ยงที่เหลืออยู่" value='<?=$row2['risk_remain']?>'>
	</div>
	<div class="form-group">
	  <label>ความเสียหายที่อาจจะเกิดขึ้น</label>
	  <input type="text" class="form-control" name='damage' placeholder="ความเสียหายที่อาจจะเกิดขึ้น" value='<?=$row2['damage']?>'>
	</div>		
	<div class="form-group">
	  <label>ปัญหาของการควบคุมในปัจจุบัน</label>
	  <input type="text" class="form-control" name='problem' placeholder="ปัญหาของการควบคุมในปัจจุบัน" value='<?=$row2['problem']?>'>
	</div>		
	<div class="form-group">
	  <label>แผนการปรับปรุง/แนวทางการควบคุม</label>
	  <textarea class="form-control" name='solution' placeholder="แผนการปรับปรุง/แนวทางการควบคุม" rows='3'><?=$row2['solution']?></textarea>
	</div>
	<div class="form-group">
	  <label>ผู้รับผิดชอบดำเนินการ</label>
	  <input type="text" class="form-control" name='responsible' placeholder="ผู้รับผิดชอบดำเนินการ" value='<?=$row2['responsible']?>'>
	</div>	
	<div class="form-group">
	  <label>ระยะเวลาที่แผนจะแล้วเสร็จ</label>
	  <input type="text" class="form-control" name='duration' placeholder="ระยะเวลาที่แผนจะแล้วเสร็จ" value='<?=$row2['duration']?>'>
	</div>	
	<div class="form-group">
	  <label>ความต้องการสนับสนุน</label>
	  <input type="text" class="form-control" name='support' placeholder="ความต้องการสนับสนุน" value='<?=$row2['support']?>'>
	</div>	
	<div class="form-group">
	  <label>ผู้รับผิดชอบ</label>
	  <input type="text" class="form-control" name='owner' placeholder="ผู้รับผิดชอบ" value='<?=$row2['owner']?>'>
	</div>	
	<div class="form-group">
	  <label>ผู้บริหารหน่วยงาน</label>
	  <input type="text" class="form-control" name='head' placeholder="ผู้บริหารหน่วยงาน" value='<?=$row2['head']?>'>
	</div>	
	<br>
	<br>
	<br>

	<button type='button' class="btn btn-default" onClick="document.location='csa_admin.php?edit_id=<?=$edit_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>

<? if ($is_confirm==0) { ?>	
	<input type='hidden' name='update_ap_id' value='<?=$edit_ap_id?>'>
	<button type='submit' name='submit' value='save_ap' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
<? }?>	

	</form>
		</div>
	</div>
</div>


<?	
	}
	echo template_footer();
	exit;

	
} */

if ($edit_id>0) {	$sql = "SELECT 		c.*,		d.department_name AS dep_name	FROM csa_department c	JOIN department d ON c.department_id = d.department_id 	WHERE 		c.csa_department_id = '$edit_id' AND 		c.mark_del = '0' ";	
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
?>

<link rel="stylesheet" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.css">
<script type="text/javascript" src="timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="timepicker/jquery.ui.timepicker.js?v=0.3.3"></script>
<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

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

<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<br>
<br>

			
<form method='post' action='csa_admin.php'>  
	
<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">แก้ไขข้อมูลหลัก</a></li>
		<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="true">พิมพ์รายงาน</a></li>
		<li class=""><a href="#tab3" data-toggle="tab" aria-expanded="true">ดูข้อมูล</a></li>
		<li class=""><a href="#tab4" data-toggle="tab" aria-expanded="true">ข้อสังเกต / ข้อ Comment</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">	
	
	<div class="form-group">
	  <label>ฝ่าย</label>
	  <input type="text" class="form-control" value='<?=$row2['dep_name']?>' readonly>
	</div>	
	<div class="form-group">
	  <label>ส่วนงาน</label>
	  <input type="text" class="form-control" name='department_name' placeholder="ส่วนงาน (ถ้ามี)" value='<?=$row2['department_name']?>'>
	</div>	
	<div class="form-group">
	  <label>ปี พ.ศ.</label>
	  <input type="text" class="form-control" name='csa_year' placeholder="ปี พ.ศ." value='<?=$edit_year?>'>
	</div>		<div class="form-group">	  <label>เปิดใช้งาน</label><br>	  <input type="checkbox" name='is_enable' id='is_enable' value='1' <?if ($row2['is_enable']==1) echo 'checked'?>> เปิดให้ประเมินความเสี่ยง	</div>	<br>	<div class="form-group">
	<div class='row'>
		<div class='col-md-4 alert alert-info' style='margin: 20px'>

			<b>รหัสผู้มีสิทธิ ประเมิน</b>
			<div class='table-responsive' >
				<table class="table table-hover table-condensed" >
				<thead>
				<tr>
					<td width='5%'></td>
					<td width='90%'></td>
				</tr>
				</thead>
				<tbody>
<? 

		$j=1;
		$sql = "SELECT * FROM csa_authorize
		WHERE 
			csa_department_id = '$edit_id' ";		
			
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
			<tr>
				<td width='5%'><?=$j++?></td>
				<td width='90%'><?= $row1['csa_authorize_uid']?></td>
				<td width='5%' align='right'><?if (!$is_lock) {?><a href="csa_admin.php?edit_id=<?=$edit_id?>&del_authorize_id=<?=$row1['csa_authorize_id']?>&view_dep=<?=$view_dep?>#bottom2" onClick='return confirm("Confirm Delete?")' class="delete-row">ลบ</a><? } ?></td>
			</tr>
<?
			} 
		} else {
?>
			<tr>
				<td colspan='3'> ยังไม่มีข้อมูล </td>
			</tr>
<?				
		}
?>
			</tbody>
			</table>
			</div>						<br>			<div class='row'>				<div class='col-md-6'><input type="text" class="form-control input-sm" name='authorize_uid' placeholder="รหัสพนักงาน" value=''></div>				<div class='col-md-6'><button type='submit' name='submit' value='add_uid' class="btn btn-primary btn-sm"><i class='fa fa-plus-circle'></i> เพิ่มผู้ประเมิน</button></div>			</div>
		</div>			</div>	</div>		<div class="form-group">	<div class='row'>			<div class='col-md-4 alert alert-warning' style='margin: 20px'>
			<b>รหัสผู้มีสิทธิ อนุมัติรายการ</b>
			<div class='table-responsive' >
				<table class="table table-hover table-condensed" >
				<thead>
				<tr>
					<td width='5%'></td>
					<td width='90%'></td>
				</tr>
				</thead>
				<tbody>
<? 

		$j=1;
		$sql = "SELECT * FROM csa_authorize_approver
		WHERE 
			csa_department_id = '$edit_id' ";		
			
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
			<tr>
				<td width='5%'><?=$j++?></td>
				<td width='90%'><?= $row1['csa_authorize_uid']?></td>
				<td width='5%' align='right'><?if (!$is_lock) {?><a href="csa_admin.php?edit_id=<?=$edit_id?>&del_authorize_id2=<?=$row1['csa_authorize_id']?>&view_dep=<?=$view_dep?>#bottom2" onClick='return confirm("Confirm Delete?")' class="delete-row">ลบ</a><? } ?></td>
			</tr>
<?
			} 
		} else {
?>
			<tr>
				<td colspan='3'> ยังไม่มีข้อมูล </td>
			</tr>
<?				
		}
?>
			</tbody>
			</table>
			</div>						<br>			<div class='row'>				<div class='col-md-6'><input type="text" class="form-control input-sm" name='authorize_uid2' placeholder="รหัสพนักงาน" value=''></div>				<div class='col-md-6'><button type='submit' name='submit' value='add_uid2' class="btn btn-primary btn-sm"><i class='fa fa-plus-circle'></i> เพิ่มผู้อนุมัติ</button></div>			</div>
		</div>
	</div>
	</div>
	
	<BR><BR>
	
		
	<br>
	<br>

	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='save' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>		<button type='submit' name='submit' value='delete' class='btn btn-danger' onClick='return confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")' id='confirm_btn'><i class='fa fa-times'></i> ลบรายการ</button>	
<? if ($row2['is_confirm']==1) { ?>	
	<button type='submit' name='submit' value='unlock' class="btn btn-danger" onClick='return confirm("โปรดยืนยันการปลดล็อค")'><i class='fa fa-unlock'></i> ปลดล็อค</button>
<? } ?>	
		
		</div>
		
		<div class="tab-pane " id="tab2">	
<?	
	$sql = "SELECT * FROM csa_department WHERE csa_department_id = '$edit_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$is_confirm = $row2['is_confirm'];
		$print_year = $row2['csa_year'];
		$dep_name = $row2['department_name'];
?>

	
	<div id='print_area'>	
<style>
.cb {
	background-color: #dddddd;
	font-weight: bold;
}
.pagebreak { 

	page-break-before: always; 
}

.pp {
	word-wrap: break-word; 
	word-break: break-all;
}
</style>	
	<u><b>ผลการประเมินความเสี่ยง <?=$dep_name?> ปี <?=$print_year?></b></u><br>
	<b>รายการความเสี่ยง</b><br>
<?
	$sql = "SELECT * FROM csa WHERE 
	csa_year = '$print_year' AND 
	is_risk = '1' AND 
	mark_del = '0' AND 
	csa_department_id = '$edit_id' 
	AND is_plan = '1'
	ORDER BY sequence";
	//
	//echo $sql;
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) {
		$csa_id = $row2['csa_id'];
		$risk_current = $row2['frequency'] + $row2['impact'];		
		if ($risk_current<=4) {
			$risk_current_label = 'ต่ำ';
			$risk_acc_label = 'ต่ำ';
		} else if ($risk_current<=6) {
			$risk_current_label = 'ปานกลาง';
			$risk_acc_label = 'ปานกลาง';
		} else if ($risk_current<=8) {
			$risk_current_label = 'สูง';
			$risk_acc_label = 'ปานกลาง';
		} else {
			$risk_current_label = 'สูงมาก';
			$risk_acc_label = 'ปานกลาง';
		}
		
		$risk_after = $row2['frequency_acc'] + $row2['impact_acc'];
		if ($risk_after<=4) {
			$risk_after_label = 'ต่ำ';
		} else if ($risk_after<=6) {
			$risk_after_label = 'ปานกลาง';
		} else if ($risk_after<=8) {
			$risk_after_label = 'สูง';
		} else {
			$risk_after_label = 'สูงมาก';
		}

		
		$factor = 'ไม่ระบุ';
		$risk_type = 'ไม่ระบุ';
		
		$sql = "SELECT * FROM csa_factor WHERE csa_factor_id = '$row2[factor]' ";
		$result = mysqli_query($connect, $sql);
		if ($row = mysqli_fetch_array($result)) $factor = $row['factor'];		
		$sql = "SELECT * FROM csa_risk_type WHERE csa_risk_type_id = '$row2[risk_type]' ";
		$result = mysqli_query($connect, $sql);
		if ($row = mysqli_fetch_array($result)) $risk_type = $row['risk_type_name'];
		
?>

<table border='1' style='border-collapse: collapse;' width='1300'>
<tr valign='top'>
	<td width='180' class='cb'>ฝ่ายงาน/สำนัก/เขต/สาขา</td>
	<td width='620'><?=$dep_name?></td>
	<td width='70' align='center' class='cb'>ระดับ</td>
	<td width='230' align='center' class='cb'>ตัวชี้วัดความสำเร็จ</td>
	<td width='220' colspan='2' align='center' class='cb'>ระดับความสำเร็จ</td>
</tr>
<tr valign='top'>
	<td class='cb'>ส่วนงาน</td>
	<td><?=$row2['section']?></td>
	<td class='cb' align='center'>1</td>
	<td><?=$row2['plan_level1']?></td>
	<td width='90' class='cb'>ปัจจุบัน</td>
	<td width='130'><?=$risk_current_label?></td>
</tr>
<tr valign='top'>
	<td class='cb'>งาน</td>
	<td><?=$row2['activity_name']?></td>
	<td class='cb' align='center'>2</td>
	<td><?=$row2['plan_level2']?></td>
	<td class='cb'>ที่ยอมรับได้</td>
	<td><?=$risk_acc_label?></td>
</tr>
<tr valign='top'>
	<td class='cb'>ความเสี่ยงที่ยังมีอยู่</td>
	<td><?=$row2['risk_remain']?></td>
	<td class='cb' align='center'>3</td>
	<td><?=$row2['plan_level3']?></td>
	<td class='cb'>ที่ต้องการ</td>
	<td><?=$risk_after_label?></td>
</tr>
<tr valign='top'>
	<td class='cb'>ชื่อแผนงาน</td>
	<td><?=$row2['plan_name']?></td>
	<td class='cb' align='center'>4</td>
	<td><?=$row2['plan_level4']?></td>
	<td colspan='2' class='cb' align='center'>เป้าหมายความสำเร็จ</td>
</tr>
<tr valign='top'>
	<td class='cb'>ผู้รับผิดชอบ</td>
	<td><?=$row2['control_owner']?></td>
	<td class='cb' align='center'>5</td>
	<td><?=$row2['plan_level5']?></td>
	<td colspan='2' rowspan='2'><?=$row2['plan_target']?></td>
</tr>
<tr valign='top'>
	<td class='cb'>กำหนดเสร็จ</td>
	<td><?=mysqldate2th_date($row2['deadline'])?></td>
	<td class='cb' align='center'>เป้าหมาย</td>
	<td>ตัวชี้วัดระดับ <?=$row2['plan_level_target']?></td>
</tr>
</table>
<table border='1' style='border-collapse: collapse;' width='1300'>
<thead>
<tr valign='top' align='center'>
	<td width='310' class='cb' rowspan='2'>กิจกรรมดำเนินการ</td>
	<td width='310' class='cb' rowspan='2'>เป้าหมาย / <br>ความสำเร็จของการดำเนินการ</td>
	<td width='60' class='cb' rowspan='2'>%<br>น้ำหนัก</td>
	<td width='300' class='cb' colspan='12'>ระยะเวลาดำเนินการ</td>
	<td width='150' class='cb' rowspan='2'>งบประมาณ / <br>ค่าใช้จ่าย (ถ้ามี)</td>
	<td width='140' class='cb' rowspan='2'>หมายเหตุ</td>
</tr>
<tr valign='top' align='center'>
<? for ($i=1; $i<=12; $i++){?>				
	<td width='27' class='cb'><?=month_name2($i)?></td>
<?}?>	
</tr>
</thead>
<tbody>
<?
		$sql = "SELECT * FROM csa_plan 
		WHERE 
			mark_del = '0' AND 
			csa_id = '$csa_id' 
		ORDER BY 
			create_date ";		
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
<tr valign='top'>
	<td><?=$row1['activity_name']?></td>
	<td><?=$row1['target']?></td>
	<td align='right'><?=$row1['weight']?></td>
<? for ($i=1; $i<=12; $i++){?>				
	<td align='center'><?if ($row1['m'.$i]==1) echo 'x'?></td>
<?}?>	
	<td align='right'><?=$row1['budget']?></td>
	<td><?=$row1['remark']?></td>
</tr>
<?
			} 
		}
?>
</tbody>
</table>
<div class="pagebreak"></div>
<?		
	}
?>
	<b>KRI</b><br>
<?
		$sql = "SELECT * FROM csa_kri
		WHERE 
			mark_del = '0' AND
			csa_department_id = '$edit_id' 
		ORDER BY 
			create_date ";		
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>	
<table border='1' style='border-collapse: collapse;' width='1300'>
<tr valign='top'>
	<td width='70' class='cb'>ลำดับที่</td>
	<td width='160'><?=$row1['sequence']?></td>
	<td width='70' class='cb'>รายการ</td>
	<td width='1000'><?=$row1['risk_name']?></td>
</tr>
</table>
<br>
<table border='1' style='border-collapse: collapse;' width='1300'>
<tr valign='top'>
	<td colspan='8' class='cb' align='center'>รายละเอียดดัชดีวัดความเสี่ยง</td>
</tr>
<tr valign='top'>
	<td width='100' class='cb'>ชื่อดัชนี</td>
	<td width='300' colspan='3'><?=$row1['index_no']?></td>
	<td width='100' class='cb'>แหล่งที่มา<br>ของข้อมูล</td>
	<td width='500' colspan='3'><?=$row1['source']?></td>
</tr>
<tr valign='top' align='center'>
	<td width='300' class='cb' colspan='3' rowspan='2'>คำอธิบายความสัมพันธ์ของดัชนีชี้วัดกับรายการความเสี่ยง</td>
	<td width='100' class='cb' rowspan='2'>หน่วยวัด</td>
	<td width='100' class='cb' rowspan='2'>ความถี่<br>ของการเก็บข้อมูล</td>
	<td width='500' colspan='3' class='cb'>ระดับของการวัดติดตาม (Scale)</td>
</tr>	
<tr valign='top' align='center'>
	<td width='120' class='cb'>ระดับที่ยอมรับได้</td>
	<td width='120' class='cb'>ระดับแจ้งเตือน</td>
	<td width='260' class='cb'>ระดับที่เป็นปัญหา</td>
</tr>
<tr valign='top'>
	<td width='300' colspan='3'><?=$row1['description']?></td>
	<td width='100'><?=$row1['unit']?></td>
	<td width='100'><?=$row1['frequency']?></td>
	<td width='120'><?if($row1['level_acceptable'] <3) { echo level_acceptable($row1['level_acceptable'])."<BR>"; }?>  <?= $row1['level_acceptable_desc']?></td>
	<td width='120'><?if($row1['level_alert'] <3) { echo level_alert($row1['level_alert'])."<BR>"; } ?>  <?= $row1['level_alert_desc']?></td>
	<td width='260'><?if($row1['level_problem'] <3) { echo level_problem($row1['level_problem'])."<BR>"; } ?>  <?= $row1['level_problem_desc']?></td>
</tr>
</table>
<div class="pagebreak"></div>
<?
			}
		}
?>
	<!--<b>Action Plan</b><br> -->
<?
/*		$sql = "SELECT * FROM csa_action_plan
		WHERE 
			mark_del = '0' AND
			csa_department_id = '$edit_id' 
		ORDER BY 
			create_date ";		
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>	
<table border='1' style='border-collapse: collapse;' width='1300'>
<tr valign='top'>
	<td width='70' class='cb'>ลำดับที่</td>
	<td width='160'><?=$row1['sequence']?></td>
	<td width='70' class='cb'>รายการ</td>
	<td width='1000'><?=$row1['risk_name']?></td>
</tr>
</table>
<br>
<table border='1' style='border-collapse: collapse;' width='1300'>
<tr valign='top' align='center'>
	<td width='500' class='cb' colspan='5'>ความเสียหายที่อาจจะเกิดขึ้น</td>
	<td width='500' class='cb' colspan='5'>แผนการปรับปรุง/แนวทางการควบคุม</td>
	<td width='300' class='cb' colspan='3'>ผู้รับผิดชอบดำเนินการ</td>
</tr>
<tr valign='top'>
	<td width='500' colspan='5' rowspan='3'><?=$row1['damage']?></td>
	<td width='500' colspan='3' rowspan='5'><?=$row1['solution']?></td>
	<td width='300' colspan='3'><?=$row1['responsible']?></td>
</tr>
<tr valign='top'>
	<td width='300' class='cb' colspan='3' align='center'>ระยะเวลาที่แผนจะแล้วเสร็จ</td>
</tr>
<tr valign='top'>
	<td width='300' colspan='3'><?=$row1['duration']?></td>
</tr>
<tr valign='top'>
	<td width='500' class='cb' colspan='5' align='center'>ปัญหาของการควบคุมในปัจจุบัน</td>
	<td width='300' class='cb' colspan='3' align='center'>ความต้องการสนับสนุน</td>
</tr>
<tr valign='top'>
	<td width='500' colspan='5' rowspan='3'><?=$row1['problem']?></td>
	<td width='300' colspan='3'><?=$row1['support']?></td>
</tr>
</table>
<div class="pagebreak"></div>
<?
			}
		} */
		
		
		$env = array();
		$sql = "SELECT 
			SUM(csa_env_data.v) AS num,
			csa_env.csa_env_topic_id AS topic
		FROM csa_env_data 
		JOIN csa_env ON csa_env.csa_env_id = csa_env_data.csa_env_id
		WHERE 
			csa_env_data.csa_year = '$print_year' AND 
			csa_env_data.csa_department_id = '$edit_id' AND 
			csa_env_data.v > 0 
		GROUP BY
			csa_env.csa_env_topic_id";
		$result2 = mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {
			$env[$row2['topic']] = $row2['num']/10;
		}
		$rs = array();
		$sql = "SELECT 
			csa_env_topic_id,
			result1,
			result2,
			result3,
			result4,
			result5
		FROM csa_env_topic ";
		$result2 = mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {	
			$rs[$row2['csa_env_topic_id']] = array(
				$row2['result1'],
				$row2['result2'],
				$row2['result3'],
				$row2['result4'],
				$row2['result5']
			);
		}		
?>
<div align='center'><b>แบบรายงานการประเมินองค์ประกอบการควบคุมภายใน<br>
<?=$dep_name?> ปี <?=$print_year?></b></div>
<div align='right'><b>แบบ ปค 4</b></div>
		
<table border='1' style='border-collapse: collapse;' width='1300'>
<tr valign='top' align='center'>
	<td width='350' class='cb'>รายการ</td>
	<td width='400' class='cb'>องค์ประกอบการควบคุมภายใน</td>
	<td width='550' class='cb'>ผลการประเมิน</td>
</tr>
<tr valign='top'>
	<td>1. สภาพแวดล้อมการควบคุม (Control Environment)</td>
	<td class='pp'>
- ทัศนคติของผู้บริหารและบุคลากร ที่เอื้อต่อการควบคุมภายใน<br>
-การให้ความสำคัญกับการ มีศีลธรรม จรรยาบรรณและความซื่อสัตย์ ของผู้บริหาร กรณีถ้าพบว่าบุคลากรมีการประพฤติปฏิบัติที่ไม่เหมาะสม จะมีการพิจารณาดำเนินการตามควรแก่กรณี <br>
- เจ้าหน้าที่ผู้ปฏิบัติงานมีความรู้ความสามารถเหมาะสมกับงาน <br>
- เจ้าหน้าที่ผู้ปฏิบัติงานได้รับทราบข้อมูลและการวินิจฉัยสิ่งที่ตรวจพบหรือสิ่งที่ต้องตรวจสอบ<br>
- ปรัชญาและรูปแบบการทำงานของผู้บริหารเหมาะสมต่อการพัฒนาการควบคุมภายในและดำรงไว้ซึ่งการควบคุมภายในที่มีประสิทธิผล<br>
- โครงสร้างองค์กร การมอบอำนาจหน้าที่ความรับผิดชอบและจำนวนผู้ปฏิบัติงานเหมาะสมกับงานที่ปฏิบัติ <br>
- นโยบายและการปฏิบัติด้านบุคลากรเหมาะสมในการจูงใจและสนับสนุนผู้ปฏิบัติงาน<br>
	</td>
	<td align='center' width='550' style='word-wrap: break-word;'>[<?=test_env($env[1])?>]<br><?=$rs[1][test_env2($env[1])]?></td>
</tr>
<tr valign='top'>
	<td>2. การประเมินความเสี่ยง (Risk Assessment)</td>
	<td class='pp'>
- การกำหนดวัตถุประสงค์ระดับองค์กรที่ชัดเจน<br>
- วัตถุประสงค์ระดับองค์กรและวัตถุประสงค์ระดับกิจกรรมสอดคล้องกันในการที่จะทำงานให้สำเร็จด้วยงบประมาณและทรัพยากรที่กำหนดไว้อย่างเหมาะสม <br>
- การระบุความเสี่ยงทั้งจากปัจจัยภายในและภายนอกที่อาจมีผลกระทบต่อการบรรลุวัตถุประสงค์ขององค์กร <br>
- การวิเคราะห์ความเสี่ยงและการบริหารความเสี่ยงที่เหมาะสม <br>
- กลไกที่ชี้ให้เห็นถึงความเสี่ยงที่เกิดจากการเปลี่ยนแปลง เช่น การเปลี่ยนแปลงวิธีการจัดการ เป็นต้น	<br>
	</td>
	<td align='center' width='550' style='word-wrap: break-word;'>[<?=test_env($env[2])?>]<br><?=$rs[2][test_env2($env[2])]?></td>
</tr>
<tr valign='top'>
	<td>3. กิจกรรมการควบคุม (Control Activities)</td>
	<td class='pp'>
- นโยบายและวิธีปฏิบัติงานที่ทำให้มั่นใจว่า เมื่อนำไปปฏิบัติแล้วจะเกิดผลสำเร็จตามที่ฝ่ายบริหารกำหนดไว้ <br>
- กิจกรรมเพื่อการควบคุมจะชี้ให้ผู้ปฏิบัติงานเห็นความเสี่ยงที่อาจเกิดขึ้นในการปฏิบัติงาน เพื่อให้เกิดความระมัดระวังและสามารถปฏิบัติงานให้สำเร็จตามวัตถุประสงค์	<br>
	</td>
	<td align='center' width='550' style='word-wrap: break-word;'>[<?=test_env($env[3])?>]<br><?=$rs[3][test_env2($env[3])]?></td>
</tr>
<tr valign='top'>
	<td>4. ข้อมูล ข่าวสารและการสื่อสาร (Information & Communication)</td>
	<td class='pp'>
- ระบบข้อมูลสารสนเทศที่เกี่ยวเนื่องกับการปฏิบัติงาน การรายงานทางการเงินและการ
ดำเนินงาน การปฏิบัติตามนโยบายและระเบียบปฏิบัติต่างๆ ที่ใช้ในการควบคุมและดำเนินกิจกรรมขององค์กร รวมทั้งข้อมูลสารสนเทศที่ได้จากภายนอกองค์กร<br>
- การสื่อสารข้อมูลสารสนเทศต่างๆไปยังผู้บริหารและผู้ใช้ภายในองค์กร ในรูปแบบที่ช่วยให้ผู้รับข้อมูลสารสนเทศปฏิบัติหน้าที่ตามความรับผิดชอบได้อย่างมี ประสิทธิภาพและประสิทธิผล และให้ความมั่นใจว่า มีการติดต่อสื่อสารภายในและภายนอกองค์กร ที่มีผลทำ ให้องค์กรบรรลุวัตถุประสงค์และเป้าหมาย	<br>
	</td>
	<td align='center' width='550' style='word-wrap: break-word;'>[<?=test_env($env[4])?>]<br><?=$rs[4][test_env2($env[4])]?></td>
</tr>
<tr valign='top'>
	<td>5. การติดตาม (Monitoring)</td>
	<td class='pp'>
-  การติดตามประเมินผลการควบคุมภายในและประเมินคุณภาพการปฏิบัติงานโดยกำหนดวิธีปฏิบัติงานเพื่อติดตามการปฏิบัติตามระบบการควบคุมภายในอย่างต่อเนื่องและเป็นส่วนหนึ่งของกระบวนการปฏิบัติงานตามปกติของฝ่ายบริหาร<br>
- การประเมินผลแบบรายครั้ง(Separate Evaluation) เป็นครั้งคราว กรณีพบจุดอ่อนหรือข้อบกพร่องควรกำหนดวิธีปฏิบัติเพื่อให้ความมั่นใจว่า ข้อตรวจพบจากการตรวจสอบและการสอบทานได้รับการพิจารณาสนองตอบ และมีการวินิจฉัยสั่งการให้ดำเนินการแก้ไข ข้อบกพร่องทันที	<br>
	</td>
	<td align='center' width='550' style='word-wrap: break-word;'>[<?=test_env($env[5])?>]<br><?=$rs[5][test_env2($env[5])]?></td>
</tr>
</table>

<br>
<div align='right'>
ลงชื่อ ..................................................<br>
   (................................................)<br>
ตำแหน่ง ................................................<br>
วันที่ ........................................<br>
</div>
</div>
	<br>
	<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>			
<?	}?>	


		
		</div>
		
		
		<div class="tab-pane " id="tab3">
			<b>รายการความเสี่ยง</b><br>
<?
	$sql = "SELECT * FROM csa_department WHERE csa_department_id = '$edit_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$is_confirm = $row2['is_confirm'];
		$print_year = $row2['csa_year'];
		$dep_name = $row2['department_name'];
?>		
		<div class='table-responsive'>
			<table class='table table-hover table-light'>
			<thead>
			<tr>
				<td width='5%'>ลำดับ</td>
				<td width='50%'>รายการความเสี่ยง</td>
				<td width='15%'>ฝ่ายรับผิดชอบ</td>
				<td width='15%'>ผู้รับผิดชอบ</td>
				<!--<td width='10%'>เริ่มต้น</td>
				<td width='10%'>สิ้นสุด</td>-->
				<td width='10%'>กิจกรรม<br>เสี่ยง</td>
				<td width='10%'>เสร็จสิ้น</td>
			</tr>
			</thead>
			<tbody>
<?
		$i=1;	
		$sql = "SELECT * FROM csa WHERE csa_year = '$print_year' AND mark_del = '0' AND csa_department_id = '$edit_id' ";
		$result2 = mysqli_query($connect, $sql);
		if (mysqli_num_rows($result2)>0) {
			while ($row2 = mysqli_fetch_array($result2)) {
				//if ($is_confirm==1) echo 'background-color: #ffdddd'
?>
			<tr onClick='document.location="csa_admin.php?edit_csa_id=<?=$row2['csa_id']?>&edit_id=<?=$edit_id?>"' style='cursor: pointer;'>
				<td><?=$i++?></td>
				<td><?=$row2['activity_name']?></td>
				<td><?=$row2['department_name']?></td>
				<td><?=$row2['owner']?></td>
				<!--<td><?=mysqldate2th_date($row2['date_begin'])?></td>					
				<td><?=mysqldate2th_date($row2['date_end'])?></td>	-->			
				<td><?if ($row2['is_risk']==1) echo '<i class="fa fa-check"></i>'?></td>				
				<td><?if ($row2['is_finish']==1) echo '<i class="fa fa-check"></i>'?></td>				
			</tr>
<?
			}
		} else {		
?>			
			<tr>
				<td colspan='6'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?		} ?>
		</tbody>
		</table>

		<br><hr><br>
		<b>KRI</b><br>
<?
	$sql = "SELECT * FROM csa_kri WHERE csa_year = '$print_year' AND mark_del = '0' AND csa_department_id = '$edit_id' ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
			$kri_list[] = $row2;
		}
	}
?>
	<table class='table table-hover table-light'>
	<thead>
	<tr>
		<td width='5%'>ลำดับ</td>
		<td width='65%'>ความเสี่ยงของงาน</td>
		<td width='15%'>ผู้รับผิดชอบ</td>
		<td width='15%'>ผู้จัดเก็บข้อมูล</td>
		<td></td>
	</tr>
	</thead>
	<tbody>
<?
	if(!empty($kri_list)){
	
		foreach($kri_list as $data){
?>
			<tr onClick='document.location="csa_admin.php?edit_kri_id=<?=$data['kri_id']?>&edit_id=<?=$edit_id?>"' style='cursor: pointer;'>
				<td><?=$data['sequence']?></td>
				<td><?=$data['risk_name']?></td>
				<td><?=$data['control_owner']?></td>
				<td><?=$data['owner']?></td>
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='4'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?	} ?>
	</tbody>
	</table>
	
<!-- DataTable -->
<link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css">

<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript" charset="utf8"></script>
<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript" charset="utf8"></script>
<script language='JavaScript'>
	$(document).ready(function() {
		
		$('.reportTable').DataTable({
			responsive: {
				details: {
					renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
						tableClass: 'ui table'
					} )
				}
			},
			dom: 'Bfrtip',
			bAutoWidth: false,
			buttons: [{
				extend: 'excel',
				charset: 'UTF-8',
				bom: true,
				filename: 'รายงานความเสี่ยง KRI',
				text: 'Export'
			}],
			ordering: false,
			searching: false
		});
		
	});
</script>
	
	<hr><br><br>
	<table class='reportTable table table-hover table-light'>
	<thead>
	<tr>
		<td>ลำดับ</td>
		<td>ความเสี่ยงของงาน</td>
		<td>ตัวชี้วัดความเสี่ยง</td>
		<td>Type of KRI</td>
		<td>ระดับปลอดภัย</td>
		<td>ระดับเฝ้าระวัง</td>
		<td>ระดับแจ้งเตือน</td>
		<td>ความถี่ของการเก็บข้อมูล</td>
		<td>ผู้รับผิดชอบ</td>
		<td>ผู้จัดเก็บข้อมูล</td>
	</tr>
	</thead>
	<tbody>
<?
	if(!empty($kri_list)){
	
		foreach($kri_list as $data){
?>
			<tr>
				<td><?=$data['sequence']?></td>
				<td><?=$data['risk_name']?></td>
				<td><?=$data['index_no']?></td>
				<td><?=$data['kri_type']?></td>
				<td>
				<?
					if($data['level_acceptable'] == 3){
						echo $data['level_acceptable_desc'];
					}else{
						echo level_acceptable($data['level_acceptable']);
					}
				?>
				</td>
				<td>
				<?
					if($data['level_alert'] == 3){
						echo $data['level_alert_desc'];
					}else{
						echo level_alert($data['level_alert']);
					}
				?>
				</td>
				<td>
				<?
					if($data['level_problem'] == 3){
						echo $data['level_problem_desc'];
					}else{
						echo level_problem($data['level_problem']);
					}
				?>
				</td>
				<td><?=$data['frequency']?></td>
				<td><?=$data['control_owner']?></td>
				<td><?=$data['owner']?></td>
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='10'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?	} ?>
	</tbody>
	</table>
	
<br>
<br>
<!--			<b>ACTION PLAN</b><br>			
			<table class='table table-hover table-light'>
			<thead>
			<tr>
				<td width='4%'>ลำดับ</td>
				<td width='76%'>รายการความเสี่ยง</td>
				<td width='20%'>ผู้รับผิดชอบจัดเก็บข้อมูล</td>
			</tr>
			</thead>
			<tbody> -->
<?
/*	$i=1;	
	$sql = "SELECT * FROM csa_action_plan WHERE csa_year = '$print_year' AND mark_del = '0' AND csa_department_id = '$edit_id' ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr onClick='document.location="csa_admin.php?edit_ap_id=<?=$row2['ap_id']?>&edit_id=<?=$edit_id?>"' style='cursor: pointer;'>
				<td><?=$row2['sequence']?></td>
				<td><?=$row2['risk_name']?></td>
				<td><?=$row2['owner']?></td>
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='8'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
	} */
?>
<!--			</tbody>
			</table>	-->		
			
<?
	}
?>
		</div>
		
	</div>
		<div class="tab-pane" id="tab4">	
			<table class='table table-hover table-light'>
			<thead>
			<tr>
				<td width='5%'>ลำดับ</td>
				<td width='15%'>วันที่</td>
				<td width='80%'>ข้อสังเกต / ข้อ Comment</td>
			</tr>
			</thead>
			<tbody>
<?
	$sql = "SELECT * FROM csa_department WHERE csa_department_id = '$edit_id' AND csa_year='$edit_year' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$sql = "SELECT * FROM csa_comment WHERE csa_department_id = '$edit_id' AND csa_year='$edit_year' ORDER BY create_date DESC";
		$result3 = mysqli_query($connect, $sql);
		$i = 1;
		if (mysqli_num_rows($result3)>0) {
			while ($row3 = mysqli_fetch_array($result3)) {
?>
			<tr  style='cursor: pointer;'>
				<td><?= $i ?></td>
				<td><?=$row3['create_date']?></td>
				<td><?=htlm2text($row3['comment'])?></td>
			</tr>
<?
				$i++;
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
			</table>
	<div class="form-group">
	  <label>ฝ่าย</label>
	  <input type="text" class="form-control" name='department_name4' readonly placeholder="ฝ่าย" value='<?=$row2['department_name']?>'>
	</div>	
	<div class="form-group">
	  <label>ปี พ.ศ.</label>
	  <input type="text" class="form-control" name='csa_year4' readonly placeholder="ปี พ.ศ." value='<?=$row2['csa_year']?>'>
	</div>	
	<div class="form-group">
	  <label>รหัสพนักงาน ผู้รับผิดชอบ</label>
	  <input type="text" class="form-control" name='authorize_uid4' readonly placeholder="รหัสพนักงาน" value='<?=$row2['authorize_uid']?>'>
	</div>
	<div class="form-group">
	  <label>ข้อคิดเห็น</label><br>
	  <textarea class="form-control" name='csa_comment' rows='5'> </textarea>
	</div>	
		
	<br>
	<br>

	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='add_comment' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>	
<?
}
?>
		</div>	
</div>
</form>
		  
<?
	}
	
	echo template_footer();
	exit;

}





	$view_year = intval($_GET['view_year']);
	if ($view_year==0) {
		$view_year=date('Y')+543;
	}

?>	
<div class="row">
	<div class="col-md-8">
		<table>
			<tr>
				<td>แสดงข้อมูล ของปี</td><td width='15'></td>
				<td>
					<select name='view_year' class="form-control" onChange='document.location="csa_admin.php?view_year="+this.value'>
						<option value='<?=$view_year-2?>'><?=$view_year-2?></option>
						<option value='<?=$view_year-1?>'><?=$view_year-1?></option>
						<option value='<?=$view_year?>' selected><?=$view_year?></option>
						<option value='<?=$view_year+1?>'><?=$view_year+1?></option>
						<option value='<?=$view_year+2?>'><?=$view_year+2?></option>
					<select>
				</td>
			</tr>
		</table>
	</div>
	<!-- Document Manual -->
	<div class='col-md-3 pull-right'>
	<?php
		$pathFile = "manual/rcsa_admin.pdf";

		if(file_exists($pathFile))
			echo "<a target='_blank' href='$pathFile' alt='การใช้งานระบบ'><i class='fa fa-book'></i> คู่มือการใช้งานระบบ Admin </a>";
	?>
	</div>
</div>
<br>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">ฝ่ายที่ประเมินความเสี่ยง</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type="submit" class="btn btn-danger" onClick="document.location='csa_admin.php?action=add'"><i class='fa fa-plus-circle'></i> เพิ่มฝ่าย</button>
				</div>
			</div>

			<form action='project58.php?edit_csa_id=<?=$edit_csa_id?>&edit_topic=<?=$edit_topic?>&state=1' name='Post' method='post'>
			<input type='hidden' name='update_id' value='<?=$row[program_id]?>'>
			
			<table class='table table-hover table-light'>
			<thead>
			<tr>
				<td width='4%'>ลำดับ</td>
				<td width='31%'>ฝ่ายรับผิดชอบ</td>				<td width='20%'>ส่วนงาน</td>
				<td width='5%'>เปิดใช้งาน</td>
				<td width='5%'>ยืนยัน</td>
				<td width='15%'>วันที่ยืนยัน</td>
				<td width='10%'>ผู้ประเมิน</td>
			</tr>
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 		c.*,		d.department_name AS dep_name	FROM csa_department c	JOIN department d ON c.department_id = d.department_id 	WHERE 		c.csa_year = '$view_year' AND 		c.mark_del = '0' 	ORDER BY		d.department_no,		d.department_name ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
	<tr onClick='document.location="csa_admin.php?edit_id=<?=$row2['csa_department_id']?>&edit_year=<?= $view_year ?>"' style='cursor: pointer'>
		<td><?=$i++?></td>
		<td><?=$row2['dep_name']?></td>		<td><?=$row2['department_name']?></td>
		<td><?if ($row2['is_enable']==1) echo '<i class="fa fa-check"></i>'?></td>	
		<td><?if ($row2['is_confirm']==1) echo '<i class="fa fa-check"></i>'?></td>	
		<td><?=mysqldate2th_datetime($row2['confirm_date'])?></td>	
		<td>
<?
		$sql = "SELECT * FROM csa_authorize WHERE csa_department_id = '$row2[csa_department_id]' ORDER BY csa_authorize_uid";
		//echo $sql;
		$result3 = mysqli_query($connect, $sql);
		if (mysqli_num_rows($result3)>0) {
			while ($row3 = mysqli_fetch_array($result3)) {
				echo $row3['csa_authorize_uid'].'<BR>';
				
			}
		}
?>		
		</td>
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
	</table>
	
	</div>
	<br>
	<br>
<?
echo template_footer();
?>