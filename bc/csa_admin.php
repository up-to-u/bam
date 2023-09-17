<?
include('inc/include.inc.php');
include('csa_function.php');
echo template_header();

$view_dep_id = intval($_GET['view_dep_id']);
$view_id = intval($_GET['view_id']);
$q_id = intval($_GET['q_id']);
$print_q_id = intval($_GET['print_q_id']);
$print_s21_id = intval($_GET['print_s21_id']);
$print_s22_id = intval($_GET['print_s22_id']);
$period = intval($_GET['period']);
$comment_id = intval($_GET['comment_id']);
$view_year = intval($_GET['view_year']);


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
$del_authorize_id = intval($_GET['del_authorize_id']);
$del_authorize_id2 = intval($_GET['del_authorize_id2']);

if ($q_id>0) {
	$sql = "SELECT * 
		FROM csa_department c
		WHERE 
			c.csa_department_id = ? AND 
			c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $q_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];

			$sql = "SELECT 
				csa_q_topic_id, q_help
			FROM csa_questionnaire_topic
			WHERE 
				parent_id <> '0' AND
				mark_del = '0' ";
			$result1 = mysqli_query($connect, $sql);
			while ($row1 = mysqli_fetch_array($result1)) {
?>
<input type='hidden' id='q<?=$row1['csa_q_topic_id']?>' value='<?=html2text($row1['q_help'])?>'>
<?
			}
						
?>
<style>
.csa_qtopic {
}
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
input[type=radio] {
    border: 0px;
    width: 100%;
    height: 20px;
}
</style>
<script>

function show_hint(n) {
	tp = '<B>'+$('#t1_'+n).html()+" "+$('#t2_'+n).html()+'</B>';
	v = $('#q'+n).val();
	/*$('#modal_title').html("ข้อมูลเพิ่มเติม");*/
	$('#modal_title').html(tp);
	$('#modal_desc').html(v);
	var modal = $('#kt_modal_1');
	modal.modal('show');
}
$(function () {
	$("textarea").keyup(function(e) {
		while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
			$(this).height($(this).height()+1);
		};
	}).keyup();
	
	$(".r1").mousedown(function () {
		$(this).attr('previous-value', $(this).prop('checked'));
	});

	$(".r1").click(function () {
		var previousValue = $(this).attr('previous-value');
		if (previousValue == 'true')
			$(this).prop('checked', false);
	});
	
});  
</script>

<div class="modal fade bs-modal-lg" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal_title">คำแนะนำ</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body" id="modal_desc">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-doc font-green"></i>
					<span class="caption-subject font-green sbold uppercase">ตอบแบบประเมิน ส่วนที่ 1</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			<form method='post' action='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>' id='f1' onsubmit='return checkform()'>

			<table class='table table-hover'>
			<thead>
			<tr align='center' style='font-weight: bold; color:#ffffff' bgcolor='#20689f'>
				<td width='5%'>ข้อ</td>
				<td width='60%'>รายการ</td>
				<td width='5%'></td>
				<td width='10%'>มีการปฏิบัติครบถ้วน</td>
				<td width='10%'>มีการปฏิบัติบางส่วน</td>
				<td width='10%'>ไม่มีการปฏิบัติ</td>
			</tr>
			</thead>
			<tbody>
<?
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
			<tr style='font-weight: bold' bgcolor='#fee404'>
				<td qid='<?=$row2['csa_q_topic_id']?>' class='csa_qtopic' style='font-size: 20px;'><?=$row2['q_no']?></td>
				<td qid='<?=$row2['csa_q_topic_id']?>' class='csa_qtopic' style='font-size: 20px;'><?=$row2['q_name']?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
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
				<td qid='<?=$row3['csa_q_topic_id']?>' class='csa_qtopic' id='t1_<?=$row3['csa_q_topic_id']?>'><?=$row3['q_no']?></td>
				<td qid='<?=$row3['csa_q_topic_id']?>' class='csa_qtopic' id='t2_<?=$row3['csa_q_topic_id']?>' style='cursor:pointer' onClick='show_hint("<?=$row3['csa_q_topic_id']?>")'>
					<?=$row3['q_name']?>
				</td>
				<td style='cursor:pointer' onClick='show_hint("<?=$row3['csa_q_topic_id']?>")'><button type='button' class='btn btn-default'><i class='fa fa-search'></i></button></td>
				<td align='center'><input type='radio' class='r1' name='q_<?=$row3['csa_q_topic_id']?>' value='3' <?if ($v0==3) echo 'checked'?> <?=$lock_tag?>></td>
				<td align='center'><input type='radio' class='r1' name='q_<?=$row3['csa_q_topic_id']?>' value='2' <?if ($v0==2) echo 'checked'?> <?=$lock_tag?>></td>
				<td align='center'><input type='radio' class='r1' name='q_<?=$row3['csa_q_topic_id']?>' value='1' <?if ($v0==1) echo 'checked'?> <?=$lock_tag?>></td>
			</tr>
<?				
			}
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
			<br>
		
			<b>อธิบายเพิ่มเติม </b><br>
			<textarea class='form-control' rows='3' name='qc_0'><?=$q_result[0][1]?></textarea><br>
			<br>
			หมายเหตุ : ชุดคำถามอ้างอิงจาก สำนักงานคณะกรรมการกำกับหลักทรัพย์และตลาดหลักทรัพย์ (ก.ล.ต.)


			<br>
			<br>
			<br>
			<input type='hidden' name='update_id' value='<?=$q_id?>'>
			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			
			<button type='submit' name='submit' value='save_q' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>
			</form>
		</div>
	</div>
</div>
<?
		}			
	}
	echo template_footer();
	exit;		


} else if ($print_q_id>0) {
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
		$stmt->bind_param('i', $print_q_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];
?>

<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			
<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>	
<br>
<br>
<?=gen_print_part1($print_q_id)?>

<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			
<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>	

<?
		}			
	}
	echo template_footer();
	exit;		


} else if ($print_s21_id>0 && $period>0) {
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
		$stmt->bind_param('i', $print_s21_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];
	
?>
<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			
<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>	
<br>
<br>
<?=gen_print_part2_1($print_s21_id, $period)?>
			<br>
			<br>
<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			
<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>	
			
<?
		}
	}		
	echo template_footer();
	exit;		


} else if ($print_s22_id>0 && $period>0) {
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
		$stmt->bind_param('i', $print_s22_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_year = $row2['csa_year'];
			$csa_department_id = $row2['csa_department_id'];

?>
<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			
<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>	
<br>
<br>
<?=gen_print_part2_2($print_s22_id, $period)?>
			<br>
			<br>
<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?edit_id=<?=$csa_department_id?>&edit_year=<?=$csa_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>			
<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>	
			
<?
		}
	}		
	echo template_footer();
	exit;		



} else if ($view_id>0 && $period>0) {

	if ($submit=='save') {
		$sql = "SELECT 
			csa_department.* 
		FROM csa 
		JOIN csa_department ON csa.csa_department_id = csa_department.csa_department_id
		WHERE csa.csa_id = ?  ";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$stmt->bind_param('i', $view_id);
			$stmt->execute();
			$result2 = $stmt->get_result();
			if ($row2 = mysqli_fetch_assoc($result2)) {

				$csa_department_id = $row2['csa_department_id'];
				$csa_department_status_id = $row2['csa_department_status_id'];
		
				$update_id = intval($_POST['update_id']);
				$job_function_id = intval($_POST['job_function_id']);
				$job_function_other = ($_POST['job_function_other']);
				$strategy = ($_POST['strategy']);
				$csa_responsibility = intval($_POST['csa_responsibility']);
				$process = ($_POST['process']);
				$activity_obj = ($_POST['activity_obj']);
				$activity_event = ($_POST['activity_event']);
				$activity_cause = ($_POST['activity_cause']);
				$csa_risk_type = intval($_POST['csa_risk_type']);
				$csa_risk_type_other = ($_POST['csa_risk_type_other']);
				$csa_factor = intval($_POST['csa_factor']);
				$csa_factor_other = ($_POST['csa_factor_other']);
				$csa_control = implode(',', $_POST['csa_control']);
				$csa_control_other = ($_POST['csa_control_other']);
				$csa_impact_id1 = intval($_POST['csa_impact_id1']);
				$csa_likelihood_id1 = intval($_POST['csa_likelihood_id1']);
				$risk_level_1 = intval($_POST['risk_level_1']);
				$csa_impact_id2 = intval($_POST['csa_impact_id2']);
				$csa_likelihood_id2 = intval($_POST['csa_likelihood_id2']);
				$risk_level_2 = intval($_POST['risk_level_2']);
				if (isset($_POST['action_plan_type'])) {
					$action_plan_type = implode(',', $_POST['action_plan_type']);
				}				

				$activity_type = intval($_POST['activity_type']);
				$csa_audit_id = intval($_POST['csa_audit_id']);
				$activity_audit_require = intval($_POST['activity_audit_require']);
				
				$action_plan_activity1 = ($_POST['action_plan_activity1']);
				$action_plan_owner_position1 = intval($_POST['action_plan_owner_position1']);
				$action_plan_dep_id1 = ($_POST['action_plan_dep_id1']);
				$action_plan_process1 = ($_POST['action_plan_process1']);
				$action_plan_begin_date1 = ($_POST['action_plan_begin_date1']);
				$action_plan_end_date1 = ($_POST['action_plan_end_date1']);
				$action_plan_budget2 = doubleval(str_replace(',', '', $_POST['action_plan_budget2']));
				$action_plan_activity2 = ($_POST['action_plan_activity2']);
				$action_plan_dep_id2 = intval($_POST['action_plan_dep_id2']);
				$action_plan_process2 = ($_POST['action_plan_process2']);
				$action_plan_begin_date2 = ($_POST['action_plan_begin_date2']);
				$action_plan_end_date2 = ($_POST['action_plan_end_date2']);
				$is_finish = 0;	
				
				if ($update_id>0 && $job_function_id>0) {
					$qx = true;	
					mysqli_autocommit($connect,FALSE);
					/* mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  */

					if ($csa_impact_id1>0 && 
						$csa_likelihood_id1>0 && 
						$risk_level_1>0 && 
						$csa_impact_id2>0 && 
						$csa_likelihood_id2>0 && 
						$risk_level_2>0) {
							$is_finish = 1;
					}

					$action_plan_dep_id1_array = json_decode($action_plan_dep_id1, true);
					$d1_array = array();
					foreach ($action_plan_dep_id1_array as $d1) {
						$d1_array[] = intval($d1['id']);
					}
					$d1_list = implode(',', $d1_array);

					$sql = "UPDATE `csa` SET 
					`job_function_id`=?,
					`job_function_other`=?,
					`activity_type`=?,
					`csa_audit_id`=?,
					`strategy`=?,
					`csa_responsibility_id`=?,
					`process`=?,
					`objective`=?,
					`event`=?,
					`cause`=?,
					`risk_type`=?,
					`risk_type_other`=?,
					`control`=?,
					`control_other`=?,
					`factor`=?,
					`factor_other`=?, 
					`csa_impact_id1`=?,
					`csa_likelihood_id1`=?,
					`csa_risk_level1`=?,
					`csa_impact_id2`=?,
					`csa_likelihood_id2`=?,
					`csa_risk_level2`=?,
					`action_plan_type`=?,
					`action_plan_activity1`=?,
					`action_plan_owner_position1`=?,
					`action_plan_dep_id1`=?,
					`action_plan_process1`=?,
					`action_plan_begin_date1`=?,
					`action_plan_end_date1`=?,
					`action_plan_budget2`=?,
					`action_plan_activity2`=?,
					`action_plan_dep_id2`=?,
					`action_plan_process2`=?,
					`action_plan_begin_date2`=?,
					`action_plan_end_date2`=?,
					`is_finish`=?,
					`last_modify` = now()	
					WHERE
					csa_id = ? ";
					
					$stmt = $connect->prepare($sql);
					if ($stmt) {					
						$stmt->bind_param('isiisissssisssisiiiiiississssdsisssii', $job_function_id, $job_function_other, $activity_type, $csa_audit_id, $strategy, $csa_responsibility, $process, $activity_obj, 
							$activity_event, $activity_cause, $csa_risk_type, $csa_risk_type_other,$csa_control,$csa_control_other,$csa_factor,$csa_factor_other,
							$csa_impact_id1, $csa_likelihood_id1, $risk_level_1, $csa_impact_id2, $csa_likelihood_id2, $risk_level_2,
							$action_plan_type,$action_plan_activity1,$action_plan_owner_position1,$d1_list,$action_plan_process1,$action_plan_begin_date1,$action_plan_end_date1,
							$action_plan_budget2,$action_plan_activity2,$action_plan_dep_id2,$action_plan_process2,$action_plan_begin_date2,$action_plan_end_date2,
							$is_finish,$update_id);
						$q = $stmt->execute();
						$qx = ($qx and $q);	

						$qx = add_history($qx, $csa_department_id, $csa_department_status_id, 'แก้ไขส่วนที่ 2 โดย admin');

						if ($qx) {
							mysqli_commit($connect);		
							echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
							savelog('CSA-ADMIN-RISK-PART2-UPDATE|csa_id|'.$update_id.'|csa_department_id|'.$csa_department_id.'|');
						} else {
							mysqli_rollback($connect);			
							echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
						}
					} 
				} else {
					echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด คุณระบุข้อมูลไม่ครบถ้วน ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
				}
			}
		}
	}
	
	
	$sql = "SELECT 
		c.*,
		r.is_other as risk_is_other,
		r.risk_type_name,
		j.job_function_no,
		j.job_function_name,
		f1.csa_factor_id AS csa_factor_id1,
		f2.csa_factor_id AS csa_factor_id2,
		f2.is_other as factor_is_other,
		f2.factor as factor_name,
		rs.responsibility_desc as responsibility_desc
	FROM csa c
	LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
	LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
	LEFT JOIN csa_factor f1 ON c.factor = f1.csa_factor_id AND f1.mark_del = '0'
	LEFT JOIN csa_factor f2 ON f1.parent_id = f2.csa_factor_id  AND f2.mark_del = '0'
	LEFT JOIN csa_responsibility rs ON c.csa_responsibility_id = rs.csa_responsibility_id AND rs.mark_del = '0'
	WHERE 
		csa_id = ? ";	
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $view_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$is_confirm = 0;
			$csa_dep_id = $row2['csa_department_id'];

			$factor1 = $row2['csa_factor_id2'];
			$factor2 = $row2['csa_factor_id1'];
			
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
					c.csa_department_id = '$csa_dep_id' AND 
					c.mark_del = '0' ";
			$result1 = mysqli_query($connect, $sql);
			if ($row1 = mysqli_fetch_array($result1)) {
				$dep1 = $row1['dep_name1'];
				$dep2 = $row1['dep_name2'];
				$dep3 = $row1['dep_name3'];
				$is_confirm2 = $row1['is_confirm'];
				$dep_id = $row1['department_id3']; 
				$csa_year = $row1['csa_year']; 
							
			
				//$lock_tag = 'disabled';
				
				if ($row2['job_function_id']==999999) 
					$job_function = 'อื่นๆ : '.$row2['job_function_other'];
				else
					$job_function = $row2['job_function_no'].' '.$row2['job_function_name'];
				
				if ($row2['risk_is_other']==1) 
					$risk_type_name = $row2['risk_type_name'].': '.$row2['risk_type_other'];
				else
					$risk_type_name = $row2['risk_type_name'];
				
				if ($row2['factor_is_other']==1) 
					$factor_name = $row2['factor_name'].': '.$row2['factor_other'];
				else
					$factor_name = $row2['factor_name'];
				
				$control = '';
				$sql="SELECT * FROM csa_control WHERE mark_del = '0' AND csa_control_id IN ($row2[control])";
				$result1=mysqli_query($connect, $sql);
				while ($row1 = mysqli_fetch_array($result1)) {	
					if ($row1['is_other']==1) 
						$control .= '- '.$row1['control_name'].': '.$row2['control_other'].'<BR>';
					else
						$control .= '- '.$row1['control_name'].'<BR>';
				}
?>
<style>
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
.label1 {
	background-color : #fbe0c4;
	color: #004c85;
	font-weight: bold;
	width: 100%;
	padding: 5px;
	font-size: 18px;
}
.label2 {
	color: #004c85;
	font-weight: bold;
	width: 100%;
	padding: 5px;
	font-size: 18px;
}
</style>

<link href="jquery-ui-1.12.0/jquery-ui.css" rel="stylesheet">
<script src="jquery-ui-1.12.0/jquery-ui.js"></script>

<script language='JavaScript'>
var maxc = 1000000000;

<?
	echo "\n risk_mat = {}; \n";
	
	$d = array();
	$sql2="SELECT * FROM `csa_risk_matrix` ";
	$result3=mysqli_query($connect, $sql2);
	while ($row3 = mysqli_fetch_array($result3)) {
		$d[$row3['csa_impact_id']][$row3['csa_likelihood_id']] = $row3['csa_risk_level']; 
	}
?>
risk_mat = [
<?
	for ($i=1; $i<=5; $i++) {
		if ($i==1) 
			echo '[';
		else 
			echo ',[';
		for ($j=1; $j<=5; $j++) {
			if ($j>1) echo ',';
			echo $d[$i][$j];
		}
		echo "]\n";
	}
?>
];
function risk_level_color(r) {
	switch (r) {
		case 0: return '';
		case 1: return '#00ff00';
		case 2: return '#ffff00';
		case 3: return '#ff9900';
		case 4: return '#ff0000';
	}
}
function risk_level_name(r) {
	switch (r) {
		case 0: return '';
		case 1: return 'ต่ำ';
		case 2: return 'ปานกลาง';
		case 3: return 'สูง';
		case 4: return 'สูงมาก';
	}
}
function risk_level_acceptable(r) {
	if  (r>=3) 
		return 'ไม่เพียงพอ';
	else
		return 'เพียงพอ';
}
function risk_level_acceptable_color(r) {
	if  (r>=3) 
		return '#ff0000';
	else
		return '#00ff00';
}
function cal_level(w) {
	var i = parseInt($('#csa_impact_id'+w).val())-1;
	var j = parseInt($('#csa_likelihood_id'+w).val())-1;
	
	if (isNaN(i) || isNaN(j)) {
		$('#risk_level_'+w+'_div').css('background-color', '#ffffff');
		$('#risk_level_'+w+'_div').html('');
		$('#risk_level_'+w+'_txt').val(0);
		$('#risk_level_'+w+'_1_div').html('');
		$('#risk_level_'+w+'_1_div').css('background-color', '#ffffff');
	} else {
		lv = risk_mat[i][j];
		$('#risk_level_'+w+'_div').css('background-color', risk_level_color(lv));
		$('#risk_level_'+w+'_div').html(risk_level_name(lv));
		$('#risk_level_'+w+'_txt').val(lv);
		$('#risk_level_'+w+'_1_div').html(risk_level_acceptable(lv));
		$('#risk_level_'+w+'_1_div').css('background-color', risk_level_acceptable_color(lv));
	}

	check_action_plan_div();
}

function check_action_plan_div() {
	var i = parseInt($('#csa_impact_id2').val())-1;
	var j = parseInt($('#csa_likelihood_id2').val())-1;
	if (!isNaN(i) && !isNaN(j) && i>=0 && j>=0) {
		lv = risk_mat[i][j];
		if (lv>=3) {
			$('#action_plan_div').show();
		} else {
			$('#action_plan_div').hide();
		}
	}
}
function checkform() {
	var v = parseInt($('#job_function_id').val());
	if (v==999999 && $('#job_function_other').val()=='') {
		alert('กรุณาระบุ ขอบเขตหน้าที่ ความรับผิดชอบกลุ่มงาน อื่นๆ');
		$('#job_function_other').focus();
		return false;
	}

	var v = $('#activity_type').is(':checked');
	var v1 = $('#activity_audit_require').val();
	var v2 = $('#csa_audit_id').val();
	if (v1==1) {
		if (v2=='' || v2==0) {
			alert('กรุณาระบุ ประเด็นที่ตรวจพบ');
			$('#csa_audit_id').focus();
			return false;
		}
	}
	
	var v = $('div.checkbox-group.require :checkbox:checked').length;
	if (v==0) {
		alert('กรุณาระบุ การควบคุมที่มีอยู่');
		$('input[name^=csa_control]')[0].focus();
		return false;
	} else {
		var has_other = 0;
		$(".csa_control").each(function() {
			var is_other = $(this).attr('is_other');
			v = $(this).is(":checked");
			if (is_other==1 && v) has_other = 1;
		});			
		if (has_other>0 && $('#csa_control_other').val()=='') {
			alert('กรุณาระบุ การควบคุมที่มีอยู่อื่นๆ');
			$('#csa_control_other').focus();
			return false;
		}		
	}
	var i1 = parseInt($('#csa_impact_id1').val());
	var l1 = parseInt($('#csa_likelihood_id1').val());	
	var i2 = parseInt($('#csa_impact_id2').val());
	var l2 = parseInt($('#csa_likelihood_id2').val());	
	if (i2>i1) {
		alert('ผลกระทบหลังการควบคุม ต้องไม่เกินผลกระทบก่อนการควบคุม');
		$('#csa_impact_id2').focus();
		$('#csa_impact_id2').css("border","solid 1px red");
		return false;
	} else {
		$('#csa_impact_id2').css("border","");
	}
	if (l2>l1) {
		alert('โอกาสหลังการควบคุม ต้องไม่เกินโอกาสก่อนการควบคุม');
		$('#csa_likelihood_id2').focus();
		$('#csa_likelihood_id2').css("border","solid 1px red");
		return false;
	} else {
		$('#csa_likelihood_id2').css("border","");
	}

	var i = parseInt($('#csa_impact_id2').val())-1;
	var j = parseInt($('#csa_likelihood_id2').val())-1;
	if (!isNaN(i) && !isNaN(j) && i>=0 && j>=0) {
		lv = risk_mat[i][j];
		if (lv>=3) {
			var ar = $.map($('.action_plan_type:checked'), function(c){return c.value; });
			if (ar.length==0) {
				alert('กรุณาระบุการตอบสนองหรือจัดการความเสี่ยง');
				$('.action_plan_type').focus();
				return false;
			}
			if (ar.includes("1")) {
				if ($('#action_plan_dep_id1').val()=='' || $('#action_plan_dep_id1').val()=='[]') {
					alert('กรุณาระบุฝ่ายงานผู้รับผิดชอบ');
					$('#action_plan_dep_id0').focus();
					return false;
				}
				if ($('#action_plan_activity1').val()=='') {
					alert('กรุณาระบุชื่อแผนปฏิบัติการ');
					$('#action_plan_activity1').focus();
					return false;
				}
				if ($('#action_plan_owner_position1').val()=='') {
					alert('กรุณาระบุตำแหน่งผู้รับผิดชอบ');
					$('#action_plan_owner_position1').focus();
					return false;
				}
				if ($('#action_plan_process1').val()=='') {
					alert('กรุณาระบุกิจกรรม / ขั้นตอน');
					$('#action_plan_process1').focus();
					return false;
				}
				if ($('#action_plan_begin_date1').val()=='') {
					alert('กรุณาระบุวันที่เริ่มต้น');
					$('#action_plan_begin_date1').focus();
					return false;
				}
				if ($('#action_plan_end_date1').val()=='') {
					alert('กรุณาระบุวันที่สิ้นสุด');
					$('#action_plan_end_date1').focus();
					return false;
				}
			}
			if (ar.includes("2")) {
				if ($('#action_plan_budget2').val()=='') {
					alert('กรุณาระบุงบประมาณ');
					$('#action_plan_budget2').focus();
					return false;
				}
				if ($('#action_plan_activity2').val()=='') {
					alert('กรุณาระบุชื่อแผนปฏิบัติการ');
					$('#action_plan_activity2').focus();
					return false;
				}
				if ($('#action_plan_budget2').val()=='') {
					alert('กรุณาระบุฝ่ายงานผู้ว่าจ้าง');
					$('#action_plan_budget2').focus();
					return false;
				}
				if ($('#action_plan_dep_id2').val()=='') {
					alert('กรุณาระบุฝ่ายงานผู้ว่าจ้าง');
					$('#action_plan_dep_id2').focus();
					return false;
				}
				if ($('#action_plan_process2').val()=='') {
					alert('กรุณาระบุกิจกรรม / ขั้นตอน');
					$('#action_plan_process2').focus();
					return false;
				}
				if ($('#action_plan_begin_date2').val()=='') {
					alert('กรุณาระบุวันที่เริ่มต้น');
					$('#action_plan_begin_date2').focus();
					return false;
				}
				if ($('#action_plan_end_date2').val()=='') {
					alert('กรุณาระบุวันที่สิ้นสุด');
					$('#action_plan_end_date2').focus();
					return false;
				}
			}
		}
	}
	
	return true;
}
function check_csa_factor_other() {
	var has_other = $("#csa_factor2 option:selected").attr("is_other");
	if (has_other>0) {
		$('#csa_factor_other').show();
	} else {
		$('#csa_factor_other').hide();
	}		
}
function check_csa_control_other() {
	var has_other = 0;
	$(".csa_control").each(function() {
		var is_other = $(this).attr('is_other');
		v = $(this).is(":checked");
		if (is_other==1 && v) has_other = 1;
	});		
	if (has_other>0) {
		$('#csa_control_other_div').show();
		$("textarea").keyup();
	} else {
		$('#csa_control_other_div').hide();
	}
}
function month_name(m) {
	switch (parseInt(m)) {
		case 1: return 'ม.ค.';
		case 2: return 'ก.พ.';
		case 3: return 'มี.ค.';
		case 4: return 'เม.ย.';
		case 5: return 'พ.ค.';
		case 6: return 'มิ.ย.';
		case 7: return 'ก.ค.';
		case 8: return 'ส.ค.';
		case 9: return 'ก.ย.';
		case 10: return 'ต.ค.';
		case 11: return 'พ.ย.';
		case 12: return 'ธ.ค.';
	}
}
$(function () {
	save_tab();
	$('#job_function_id').change(function() {
		var j = $(this).val();
		if (j==999999) {
			$('#job_function_other_div').show();
		} else {
			$('#job_function_other_div').hide();
		}
		
	}).change();	


	$('#activity_type').change(function() {
		var v = $(this).is(':checked');
		if (v) {
			$('#csa_audit_div').show();
		} else {
			$('#csa_audit_div').hide();
			$('#csa_audit_id').val('');
		}
	}).change();	
	
	$('#csa_impact_id1, #csa_likelihood_id1').change(function() {
		cal_level("1");
	}).change();
	$('#csa_impact_id2, #csa_likelihood_id2').change(function() {
		cal_level("2");
	}).change();

	var csa_factor_old = parseInt($("#csa_factor_old").val());
	
	$('.csa_control').on('click', function() {
		check_csa_control_other();
	});	

	var csa_factor_old1 = parseInt($("#csa_factor_old1").val());
	var csa_factor_old2 = parseInt($("#csa_factor_old2").val());

	$("#csa_risk_type").change(function() {
		$("#csa_factor_div1").html('<select class="form-control" required disabled><option>--- กรุณารอ ---</option></select>');
		$("#csa_factor_div2").html('<select class="form-control" required disabled><option>--- โปรดเลือกตัวเลือกก่อน ---</option></select>');
		
		var rt = parseInt($("#csa_risk_type").val());
		$.post( "jobfunction_content.php", { action: 'risktype', parent:rt, data:csa_factor_old1, lv: 1})
		.done(function( data ) {
			$("#csa_factor_div1").html(data);
			$("#csa_factor_div2").html('<select class="form-control" required disabled><option>--- โปรดเลือกตัวเลือกก่อน ---</option></select>');
			
			$("#csa_factor1").change(function() {
				$("#csa_factor_div2").html('<select class="form-control" required disabled><option>--- กรุณารอ ---</option></select>');

				var d = parseInt($("#csa_factor1").val());
				$.post( "jobfunction_content.php", { action: 'risktype', parent:d, data:csa_factor_old2, lv: 2})
				.done(function( data ) {
					$("#csa_factor_div2").html(data);
					$('#csa_factor2').change(function() {
						check_csa_factor_other();
					}).change();	
				});
			}).change();
		});
		$('#csa_factor_other').hide();
	}).change();		
	
	check_csa_factor_other();
	check_csa_control_other();
	
	
	
	$("textarea").keyup(function(e) {
		if ($(this).val()!='') {
			while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
				$(this).height($(this).height()+1);
			};
		}
	}).keyup();	


	$(".datepicker-th").datepicker({ 
		changeMonth: true,
		changeYear: true,	
		yearRange: '-10:+10',
		dateFormat: 'yy-mm-dd', 
		dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
		dayNamesMin: ['อา','จ','อ','พ','พฤ','ศ','ส'],
		montdocames: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
		monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
	});	

	$('.key-numeric').bind("paste",function(e) {
		e.preventDefault();
	});
    $('.key-numeric').keypress(function(e) {
		var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
		if (verified) {e.preventDefault();}
    });	
    $('.key-numeric').keyup(function () {
		if(event.which >= 37 && event.which <= 40) return;
		if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
		   this.value = this.value.replace(/[^0-9\.]/g, '');
		}
		if (this.value>maxc) this.value=maxc;
		$(this).val(function(index, value) {
			/*return value
			.replace(/\D/g, "")
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");*/
			return value
			.replace(/[^\d.]+/g, "")
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});		
	});
	
	$('.key-numeric2').bind("paste",function(e) {
		e.preventDefault();
	});
    $('.key-numeric2').keypress(function(e) {
		var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
		if (verified) {e.preventDefault();}
    });	
    $('.key-numeric2').keyup(function () {
		if(event.which >= 37 && event.which <= 40) return;
		if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
		   this.value = this.value.replace(/[^0-9\.]/g, '');
		}
		$(this).val(function(index, value) {
			return value
			.replace(/\D/g, "");
		});		
	});	
	

    $('#action_plan_begin_date1, #action_plan_end_date1').change(function () {	
		if ($("#action_plan_begin_date1").val()!='' && $("#action_plan_end_date1").val()!='') {
			var d1 = $("#action_plan_begin_date1").val().split("-");
			var d2 = $("#action_plan_end_date1").val().split("-");
			var dd1 = new Date(d1[0], d1[1] - 1, d1[2]);
			var dd2 = new Date(d2[0], d2[1] - 1, d2[2]);
			if (dd2<dd1) {
				alert('เกิดข้อผิดพลาด ท่านเลือกวันที่ไม่ถูกต้อง');
				$("#action_plan_end_date1").val('');
				$("#action_plan_end_date1_div").html('');				
			}
		}		
		if ($("#action_plan_begin_date1").val()!='') {
			var d1 = $("#action_plan_begin_date1").val().split("-");
			var dd1 = new Date(d1[0], d1[1] - 1, d1[2]);
			$("#action_plan_begin_date1_div").html(d1[2]+' '+month_name(d1[1])+' '+((parseInt(d1[0])+543)));
		}
		if ($("#action_plan_end_date1").val()!='') {
			var d2 = $("#action_plan_end_date1").val().split("-");
			var dd2 = new Date(d2[0], d2[1] - 1, d2[2]);
			$("#action_plan_end_date1_div").html(d2[2]+' '+month_name(d2[1])+' '+((parseInt(d2[0])+543)));
		}
	}).change();
	
    $('#action_plan_begin_date2, #action_plan_end_date2').change(function () {	
		if ($("#action_plan_begin_date2").val()!='' && $("#action_plan_end_date2").val()!='') {
			var d1 = $("#action_plan_begin_date2").val().split("-");
			var d2 = $("#action_plan_end_date2").val().split("-");
			var dd1 = new Date(d1[0], d1[1] - 1, d1[2]);
			var dd2 = new Date(d2[0], d2[1] - 1, d2[2]);
			if (dd2<dd1) {
				alert('เกิดข้อผิดพลาด ท่านเลือกวันที่ไม่ถูกต้อง');
				$("#action_plan_end_date2").val('');
				$("#action_plan_end_date2_div").html('');
			}
		}		
		if ($("#action_plan_begin_date2").val()!='') {
			var d1 = $("#action_plan_begin_date2").val().split("-");
			var dd1 = new Date(d1[0], d1[1] - 1, d1[2]);
			$("#action_plan_begin_date2_div").html(d1[2]+' '+month_name(d1[1])+' '+((parseInt(d1[0])+543)));
		}
		if ($("#action_plan_end_date2").val()!='') {
			var d2 = $("#action_plan_end_date2").val().split("-");
			var dd2 = new Date(d2[0], d2[1] - 1, d2[2]);
			$("#action_plan_end_date2_div").html(d2[2]+' '+month_name(d2[1])+' '+((parseInt(d2[0])+543)));
		}		
	}).change();
	
	$('.action_plan_type').change(function() {
		$('#action_plan_div_1').hide();
		$('#action_plan_div_2').hide();
		$(".action_plan_type").each(function() {
			var v = 0;
			if($(this).is(':checked'))
				v = $(this).val();
				if (v==1) {
					$('#action_plan_div_1').show();
					init_dep();
					refresh_dep();
				} 
				if (v==2) {
					$('#action_plan_div_2').show();
				}			
		});	
	}).change();	
});  


var dep_data = [];
function is_exist_dep(id_) {
	for(let i=0;i<dep_data.length;i++){
		if(dep_data[i].id===id_){
			return true;
		}
	}
	return false;
}
	
function init_dep() {
	var v = $('#action_plan_dep_id1').val();
	dep_data = JSON.parse(v);
}
function refresh_dep() {
	$('#action_plan_dep_id1_div ul').empty();
	$.each(dep_data, function(i, obj) {
		$("#action_plan_dep_id1_div ul").append('<li><a href="JavaScript:del_dep('+obj.id+')">'+obj.name+'</a></li>');
	});	
	$('#action_plan_dep_id1').val(JSON.stringify(dep_data));
}
function del_dep(i) {
<? if ($lock_tag=='disabled') echo 'return false;'?> 
	
	jQuery(dep_data).each(function (index){
			if(dep_data[index].id == i){
				dep_data.splice(index,1); 
				return false; 
			}
	});
	refresh_dep();
}
function add_dep() {
	var v = $('#action_plan_dep_id0').val();
	if (v!='') {
		if (!is_exist_dep(v)) {
			var t = $("#action_plan_dep_id0 option:selected").text();
			dep_data.push(
				{id: v, name: t}
			);		
			refresh_dep();
			$('#action_plan_dep_id0').val('');
		} else {
			alert('เกิดข้อผิดพลาด ท่านเพิ่มฝ่ายงานซ้ำ');
			$('#action_plan_dep_id0').focus();
			return false;
		}
	} else {
		alert('กรุณาระบุเลือกฝ่ายงานก่อน');
		$('#action_plan_dep_id0').focus();
		return false;
	}
}


function toggle_risk_mat() {
  if ( $( "#risk_mat" ).first().is( ":hidden" ) ) {
    $( "#risk_mat" ).slideDown( "slow" );
  } else {
    $( "#risk_mat" ).slideUp();
  }
}
function save_tab() {
	if (location.hash) {
	  $('a[href=\'' + location.hash + '\']').tab('show');
	}
	var activeTab = localStorage.getItem('activeTab');
	if (activeTab) {
	  $('a[href="' + activeTab + '"]').tab('show');
	}
	$('body').on('click', 'a[data-toggle=\'tab\']', function (e) {
	  e.preventDefault();
	  var tab_name = this.getAttribute('href');
	  if (history.pushState) {
		history.pushState(null, null, tab_name);
	  }
	  else {
		location.hash = tab_name;
	  }
	  localStorage.setItem('activeTab', tab_name);
	  $(this).tab('show');
	  return false;
	});
	$(window).on('popstate', function () {
	  var anchor = location.hash ||
		$('a[data-toggle=\'tab\']').first().attr('href');
	  $('a[href=\'' + anchor + '\']').tab('show');
	});	
}
function activaTab(tab){
  $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};
</script>
<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-doc font-green"></i>
					<span class="caption-subject font-green sbold uppercase">รายการประเมิน</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_dep_id=<?=$csa_dep_id?>&period=<?=$period?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			<div class=''>
			<b>
				<?=$dep1?><br>
				<?=$dep2?><br>
				<?=$dep3?></b>
			</div>
			<br>
<div class='row'>
	<div class='col-md-8'>					
	<div class='note note-danger' style='font-size:13px'>
	<?=gen_audit_history($csa_dep_id)?>
	</div>
	</div>	
</div>	
			<form method='post' action='csa_admin.php?view_id=<?=$view_id?>&period=<?=$period?>' id='f1' onsubmit='return checkform()'>
			<div class="form-group">
			  <label  class='label1'>ขอบเขตหน้าที่ความรับผิดชอบของกลุ่มงาน</label>
			  <select name='job_function_id' id='job_function_id' class="form-control" required <?=$lock_tag?>>
				<option value=''>--- เลือก ---</option>
<?
	$sql2="SELECT 
		d.*,
		d1.department_name AS dep1,
		d2.department_name AS dep2,
		d3.department_name AS dep3
	FROM `job_function` d
	LEFT JOIN `department` d1 ON  d.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  d.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  d.department_id3 = d3.department_id AND d3.mark_del = '0'
	WHERE 
		d.parent_id = '0' AND
		d.mark_del = '0' AND
		d.department_id3 = '$dep_id'
	ORDER BY 
		job_function_no, job_function_id";
	$result1=mysqli_query($connect, $sql2);
	while ($row1 = mysqli_fetch_array($result1)) {
?>			  
			<option value='<?=$row1['job_function_id']?>' <?if ($row2['job_function_id']==$row1['job_function_id']) echo 'selected'?>><?=$row1['job_function_name']?></option>
<?
	}
?>
			<option value='999999' <?if ($row2['job_function_id']==999999) echo 'selected'?>>อื่นๆ โปรดระบุ</option>
			  </select>
			</div>	
			<div class="form-group" id='job_function_other_div' style='display:none'>
				<input type="text" class="form-control" placeholder="ระบุกรณี อื่นๆ" name='job_function_other' id='job_function_other' value='<?=$row2['job_function_other']?>' <?=$lock_tag?>>
			</div>	

			<div class="form-group">
			  <label  class='label1'>ประเด็นที่ตรวจพบ </label>

<?
$sql="SELECT * FROM csa_audit WHERE csa_year = '$csa_year' AND department_id3='$dep_id' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
if (mysqli_num_rows($result1)>0) {
?>				
				<input type='checkbox' name='activity_type' id='activity_type' value='1' <?if ($row2['activity_type']==1) echo 'checked'?>  <?=$lock_tag?>> รายการประเมินตามประเด็นที่ตรวจพบ<br>
				<input type='hidden' name='activity_audit_require' id='activity_audit_require' value='1'>


				<div id='csa_audit_div'>
				<select name='csa_audit_id' id='csa_audit_id' class="form-control"  <?=$lock_tag?>>
				<option value=''>--- เลือก ---</option>
<?
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['csa_audit_id']?>' <?if ($row1['csa_audit_id']==$row2['csa_audit_id']) echo 'selected'?>><?=$row1['audit_desc']?></option>
<?} ?>
				</select>				
				</div>
<?} else {?>
				<input type='checkbox' name='activity_type' id='activity_type' value='1' disabled  <?=$lock_tag?>> รายการประเมินตามประเด็นที่ตรวจพบ<br>
				<input type='hidden' name='activity_audit_require' id='activity_audit_require' value='0'>
<?} ?>
			</div>
			
			<div class="form-group">
			  <label  class='label1'>กลยุทธ์องค์กร</label>
				<textarea class="form-control" placeholder="กลยุทธ์องค์กร" name='strategy' rows='2' required <?=$lock_tag?>><?=$row2['strategy']?></textarea>
			</div>			
			<div class="form-group">
			  <label  class='label1'>กระบวนการปฏิบัติงาน</label>
				<textarea class="form-control" placeholder="กระบวนการปฏิบัติงาน" name='process' rows='2' required <?=$lock_tag?>><?=$row2['process']?></textarea>
			</div>			
			<div class="form-group">
			  <label  class='label1'>วัตถุประสงค์</label>
				<textarea class="form-control" placeholder="วัตถุประสงค์" name='activity_obj' rows='2' required <?=$lock_tag?>><?=$row2['objective']?></textarea>
			</div>						
			<div class="form-group">
			  <label  class='label1'>เหตุการณ์ความเสี่ยง</label>
				<textarea class="form-control" placeholder="เหตุการณ์ความเสี่ยง" name='activity_event' rows='2' required <?=$lock_tag?>><?=$row2['event']?></textarea>
			</div>						
			<div class="form-group">
			  <label  class='label1'>สาเหตุที่ทำให้เกิดความเสี่ยง</label>
				<textarea class="form-control" placeholder="สาเหตุที่ทำให้เกิดความเสี่ยง" name='activity_cause' rows='2' required <?=$lock_tag?>><?=$row2['cause']?></textarea>
			</div>						
			<div class="form-group">
			  <label  class='label1'>ประเภทความเสี่ยง</label>
			  <select name='csa_risk_type' id='csa_risk_type' class="form-control" required <?=$lock_tag?>>
				<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_risk_type WHERE mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['csa_risk_type_id']?>' <?if ($row2['risk_type']==$row1['csa_risk_type_id']) echo 'selected'?>><?=$row1['risk_type_name']?></option>
<?
}
?>			  
			  </select>
			</div>	
			<div class="form-group">
			  <label  class='label1'>ปัจจัยเสี่ยง</label>

			<input type='hidden' id='csa_factor_old1' value='<?=$factor1?>'>
			<input type='hidden' id='csa_factor_old2' value='<?=$factor2?>'>
			  
			  <div id='csa_factor_div1'>
			  <select name='csa_factor' id='csa_factor' class="form-control" required>
				<option value=''>--- เลือก ---</option>
			  </select>
			  </div>
			  <div id='csa_factor_div2'>
			  <select name='csa_factor' id='csa_factor' class="form-control" required>
				<option value=''>--- เลือก ---</option>
			  </select>
			  </div>
			</div>	
			
			<div class="form-group" id='csa_factor_other' style='display:none'>
				<input type="text" class="form-control" placeholder="โปรดระบุปัจจัยเสี่ยงอื่นๆ" name='csa_factor_other' value='<?=$row2['factor_other']?>'>
			</div>
			
			<div class="form-group">
			  <label  class='label1'>การควบคุมที่มีอยู่</label><br>
			  <div class="checkbox-group require">
<?
$control_array = explode(',', $row2['control']);
$sql="SELECT * FROM csa_control WHERE mark_del = '0' ORDER BY is_other, csa_control_id ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<label style='margin: 0px'><input type='checkbox' name='csa_control[]' class='csa_control' value='<?=$row1['csa_control_id']?>' is_other='<?=$row1['is_other']?>' <?if (in_array($row1['csa_control_id'], $control_array)) echo 'checked'?> <?=$lock_tag?>> <?=$row1['control_name']?></label><br>
<?
}
?>			  
			  </select>
				</div>
			</div>
			<div class="form-group" id='csa_control_other_div' style='display:none'>
				<textarea class="form-control" placeholder="โปรดระบุการควบคุมที่มีอยู่อื่นๆ" name='csa_control_other' id='csa_control_other'><?=$row2['control_other']?></textarea>
			</div>		
			<br>			
			<br>			
			<div class='row'>
			<div class='col-lg-4 col-md-6 col-sm-10 col-xs-12'>
				<div class="">
					<b style='font-size: 20px'>การประเมินความเสี่ยง <u>ก่อน</u> การควบคุม</b><br><br>
				<div class="form-group">
					<label  class='label2'>โอกาส</label>
					<select name='csa_likelihood_id1' id='csa_likelihood_id1' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_likelihood";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_likelihood_id']?>' <?if ($row2['csa_likelihood_id1']==$row1['csa_likelihood_id']) echo 'selected'?>><?=$row1['csa_likelihood_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label  class='label2'>ผลกระทบ</label>
					<select name='csa_impact_id1' id='csa_impact_id1' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_impact";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_impact_id']?>' <?if ($row2['csa_impact_id1']==$row1['csa_impact_id']) echo 'selected'?>><?=$row1['csa_impact_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label  class='label2'>ระดับความเสี่ยง</label>
					<div class='alert' id='risk_level_1_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>
				<div class="form-group">
					<label  class='label2'>ผลการประเมินการควบคุมที่มีอยู่</label>
					<div class='alert' id='risk_level_1_1_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>
				</div>				
			</div>	
			<div class='col-md-1'></div>
			<div class='col-lg-4 col-md-6 col-sm-10 col-xs-12'>
				<div class="">
					<b style='font-size: 20px'>การประเมินความเสี่ยง <u>หลัง</u> การควบคุม</b><br><br>
				<div class="form-group">
					<label  class='label2'>โอกาส</label>
					<select name='csa_likelihood_id2' id='csa_likelihood_id2' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_likelihood";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_likelihood_id']?>' <?if ($row2['csa_likelihood_id2']==$row1['csa_likelihood_id']) echo 'selected'?>><?=$row1['csa_likelihood_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label  class='label2'>ผลกระทบ</label>
					<select name='csa_impact_id2' id='csa_impact_id2' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_impact";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_impact_id']?>' <?if ($row2['csa_impact_id2']==$row1['csa_impact_id']) echo 'selected'?>><?=$row1['csa_impact_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label  class='label2'>ระดับความเสี่ยง</label>
					<div class='alert' id='risk_level_2_div' style='background:#ffffff; padding: 10; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>
				<div class="form-group">
					<label  class='label2'>ผลการประเมินการควบคุมที่มีอยู่</label>
					<div class='alert' id='risk_level_2_1_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>
				</div>			
				
			<br>
			<br>
			</div>
			</div>

			<button type='button' class="btn btn-default" data-toggle="modal" href="#basic"><i class='fa fa-search'></i> ดู Matrix</button>
			<br>
			<br>


<?
$action_plan_type_array = explode(',', $row2['action_plan_type']);
$json1 = array();
if ($row2['action_plan_dep_id1']!='') {
	$sql="SELECT department_id, department_name FROM department WHERE department_level_id = '4' AND mark_del = '0' AND department_id IN (".$row2['action_plan_dep_id1'].")";
	$result1=mysqli_query($connect, $sql);
	while ($row1 = mysqli_fetch_array($result1)) {
		$json1[] = array(
			'id'=> $row1['department_id'],
			'name'=> $row1['department_name']
		);
	}
}

?>			

			<div id='action_plan_div' style='display:none'>
				<hr>
				<div style='background: #dddddd; padding: 15px; font-size: 20px'><b>แผนปฏิบัติการ (Action Plan)</b></div><br><br>
				<div class="form-group">
				  <label  class='label1'>การตอบสนองหรือจัดการความเสี่ยง</label><br>
				</div>
				
				<div class='row'>
					<div class='col-md-4' style=''>
					<div class='bg-blue bg-font-blue margin-bottom-10' style='padding: 10px; '>
					<label style='margin: 0px; font-size:20px'><input type='checkbox' name='action_plan_type[]' class='action_plan_type' style='zoom: 2;' value='1' <?if (in_array(1, $action_plan_type_array)) echo 'checked'?> <?=$lock_tag?>> ดำเนินการโดยฝ่ายงาน</label><br>
					</div>
					<div id='action_plan_div_1' class='border-blue margin-bottom-10' style="padding: 20px; border: 2px solid #fff;">
						<div class="form-group">
						<div class="row">
							<div class="col-md-12"><label class='label1'>ฝ่ายงานผู้รับผิดชอบ</label></div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="action_plan_dep_id1_div">
									<ul>
									</ul>
								</div>							
								<input type='hidden' id='action_plan_dep_id1' name='action_plan_dep_id1' value='<?=json_encode($json1)?>'>
							</div>
						</div>
						<div class="row">
						<div class="col-md-10">
							<select name='action_plan_dep_id0' id='action_plan_dep_id0' class="form-control" <?=$lock_tag?>>
							<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM department WHERE department_level_id = '4' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
							<option value='<?=$row1['department_id']?>' <?if ($row2['action_plan_dep_id1']==$row1['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<? } ?>				
							</select>
						</div>					
						<div class="col-md-1"><button type='button' class='btn btn-icon-only red' onClick='add_dep()' <?=$lock_tag?>><i class='fa fa-plus'></i></button></div>					
						</div>					
						</div>					
						<div class="form-group">
							<label  class='label1'>ชื่อแผนปฏิบัติการ</label>
							<textarea class="form-control" placeholder="ชื่อแผนปฏิบัติการ" name='action_plan_activity1' id='action_plan_activity1' rows='3' <?=$lock_tag?>><?=$row2['action_plan_activity1']?></textarea>
						</div>				
						<div class="form-group">
						  <label  class='label1'>ตำแหน่งผู้รับผิดชอบ</label>
						  <select name='action_plan_owner_position1' id='action_plan_owner_position1' class="form-control" <?=$lock_tag?>>
							<option value=''>--- เลือก ---</option>
							<option value='1' <?if ($row2['action_plan_owner_position1']==1) echo 'selected'?>>ผู้จัดการ</option>
							<option value='2' <?if ($row2['action_plan_owner_position1']==2) echo 'selected'?>>ผู้อำนวยการ</option>
							<option value='3' <?if ($row2['action_plan_owner_position1']==3) echo 'selected'?>>สายงาน</option>
						  </select>
						</div>
						<div class="form-group">
						  <label  class='label1'>กิจกรรม / ขั้นตอน</label>
							<textarea class="form-control" placeholder="กิจกรรม / ขั้นตอน" name='action_plan_process1' id='action_plan_process1' rows='2' <?=$lock_tag?>><?=$row2['action_plan_process1']?></textarea>
						</div>					
						<div class="form-group">
							<label  class='label1'>วันที่เริ่มต้น</label>
							<div class='row'>
							<div class='col-md-6' id='action_plan_begin_date1_div'></div>
							<div class='col-md-6'><input type="text" class="form-control datepicker-th" readonly placeholder="วันที่เริ่มต้น" name='action_plan_begin_date1' id='action_plan_begin_date1' value='<?=$row2['action_plan_begin_date1']?>' <?=$lock_tag?>></div>
							</div>
						</div>				
						<div class="form-group">
							<label  class='label1'>วันที่สิ้นสุด</label>
							<div class='row'>
							<div class='col-md-6' id='action_plan_end_date1_div'></div>
							<div class='col-md-6'><input type="text" class="form-control datepicker-th" readonly placeholder="วันที่สิ้นสุด" name='action_plan_end_date1' id='action_plan_end_date1' value='<?=$row2['action_plan_end_date1']?>' <?=$lock_tag?>></div>
							</div>
						</div>									
					</div>
					</div>
					<div class='col-md-1'></div>
					<div class='col-md-4'>
					<div class='bg-yellow-lemon bg-font-yellow-lemon margin-bottom-10' style='padding: 10px; '>
					<label style='margin: 0px; font-size:20px'><input type='checkbox' name='action_plan_type[]' class='action_plan_type' style='zoom: 2;' value='2' <?if (in_array(2, $action_plan_type_array)) echo 'checked'?> <?=$lock_tag?>> ว่าจ้าง Outsource</label><br>
					</div>
					<div id='action_plan_div_2' class='border-yellow-lemon' style="padding: 20px; border: 2px solid #fff;">
						<div class="form-group">
							<label  class='label1'>งบประมาณ</label>
							<input type="text" class="form-control key-numeric" placeholder="งบประมาณ" name='action_plan_budget2' id='action_plan_budget2' value='<?=number_filter2($row2['action_plan_budget2'])?>' <?=$lock_tag?>>
						</div>				
						<div class="form-group">
							<label  class='label1'>ชื่อแผนปฏิบัติการ</label>
							<textarea type="text" class="form-control" placeholder="ชื่อแผนปฏิบัติการ" name='action_plan_activity2' id='action_plan_activity2' rows='3' <?=$lock_tag?>><?=$row2['action_plan_activity2']?></textarea>
						</div>		
						<div class="form-group">
						  <label  class='label1'>ฝ่ายงานผู้ว่าจ้าง</label>
							<select name='action_plan_dep_id2' id='action_plan_dep_id2' class="form-control" <?=$lock_tag?>>
							<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM department WHERE department_level_id = '4' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
							<option value='<?=$row1['department_id']?>' <?if ($row2['action_plan_dep_id2']==$row1['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<? } ?>				
							</select>
						</div>
						<div class="form-group">
						  <label  class='label1'>กิจกรรม / ขั้นตอน</label>
							<textarea class="form-control" placeholder="กิจกรรม / ขั้นตอน" name='action_plan_process2' id='action_plan_process2' rows='2' <?=$lock_tag?>><?=$row2['action_plan_process2']?></textarea>
						</div>
						<div class="form-group">
							<label  class='label1'>วันที่เริ่มต้น</label>
							<div class='row'>
							<div class='col-md-6' id='action_plan_begin_date2_div'></div>
							<div class='col-md-6'><input type="text" class="form-control datepicker-th" readonly placeholder="วันที่เริ่มต้น" name='action_plan_begin_date2' id='action_plan_begin_date2' value='<?=$row2['action_plan_begin_date2']?>' <?=$lock_tag?>></div>
							</div>
						</div>
						<div class="form-group">
							<label  class='label1'>วันที่สิ้นสุด</label>
							<div class='row'>
							<div class='col-md-6' id='action_plan_end_date2_div'></div>
							<div class='col-md-6'><input type="text" class="form-control datepicker-th" readonly placeholder="วันที่สิ้นสุด" name='action_plan_end_date2' id='action_plan_end_date2' value='<?=$row2['action_plan_end_date2']?>' <?=$lock_tag?>></div>
							</div>
						</div>
					</div>
					</div>
				</div>
				
			
				<br>
				<br>
			</div>


			
			<input type='hidden' id='csa_factor_old' value='<?=$row2['factor']?>'>
			<input type='hidden' name='risk_level_1' id='risk_level_1_txt' value=''>
			<input type='hidden' name='risk_level_2' id='risk_level_2_txt' value=''>
			<input type='hidden' name='update_id' value='<?=$view_id?>'>
			<button type='submit' name='submit' value='save' class="btn btn-success"><i class='fa fa-save'></i> บันทึกข้อมูล</button>


<script language='JavaScript'>
function getCellCenter(table, row, column) {
  var tableRow = $(table).find('tr')[row];
  var tableCell = $(tableRow).find('td')[column];
  var offset = $(tableCell).offset();
  var width = $(tableCell).innerWidth();
  var height = $(tableCell).innerHeight();
  return {
    x: offset.left + width / 2,
    y: offset.top + height / 2
  }
}

function drawArrow(start, end) {
  /*var canvas = document.createElement('canvas');*/
  var canvas = document.getElementById('canvas1');
  canvas.width = $('body').innerWidth();
  canvas.height = $('body').innerHeight();
  $(canvas).css('position', 'absolute');
  $(canvas).css('z-index', '99999');
  $(canvas).css('pointer-events', 'none');
  $(canvas).css('top', '0');
  $(canvas).css('left', '0');
  $(canvas).css('opacity', '1');
  $('body').append(canvas);
  
  var ctx = canvas.getContext('2d');
  ctx.fillStyle = 'white';
  ctx.strokeStyle = 'white';
  
  ctx.beginPath();
  ctx.moveTo(start.x, start.y);
  ctx.lineTo(end.x, end.y);
  ctx.lineWidth = 6;
  ctx.stroke();
  
  ctx.beginPath();  
  ctx.arc(start.x, start.y, 8, 0, Math.PI * 2, false);
  ctx.lineWidth = 6;
  ctx.fill();
  /*ctx.stroke();*/
  ctx.beginPath();  
  var angle = Math.atan2(end.y - start.y, end.x - start.x);
  ctx.translate(end.x, end.y);
  ctx.rotate(angle);
  ctx.moveTo(0, 0);
  ctx.lineTo(-20, -10);
  ctx.lineTo(-20, 10);
  ctx.lineTo(0, 0);
  ctx.fill();

  ctx.setTransform(1, 0, 0, 1, 0, 0);  
  return canvas;
}

function drawArrowOnTable(table, startRow, startColumn, endRow, endColumn) {
  drawArrow(
    getCellCenter($(table), startRow, startColumn),
    getCellCenter($(table), endRow, endColumn)
  );
}

function draw_arrow_matrix(y1, x1, y2, x2) {
	var start_x = 0;
	var start_y = 2;
	drawArrowOnTable('#table_mat', start_y+y1, start_x+x1, start_y+y2, start_x+x2);
}

$(function () {
	$('#basic').on('shown.bs.modal', function (e) {
		var i1 = parseInt($('#csa_impact_id1').val());
		var l1 = parseInt($('#csa_likelihood_id1').val());
		var i2 = parseInt($('#csa_impact_id2').val());
		var l2 = parseInt($('#csa_likelihood_id2').val());
		if (i1>0 && l1>0 && i2>0 && l2>0) {
			draw_arrow_matrix(5-i1,l1,5-i2,l2);
			$('#canvas1').show();
		}
	});

	$('#basic').on('hide.bs.modal', function (e) {
		$('#canvas1').hide();
	});
});  	
</script>	

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Risk Matrix</h4>
			</div>
			<canvas id="canvas1" style='position:absolute'></canvas>
			<div class="modal-body"><?=gen_risk_mat()?></div>
			<div class="modal-footer">
				<button type="button" class="btn dark btn-outline" data-dismiss="modal">ปิด</button>
			</div>
		</div>
	</div>
</div>
			
			</form>
		</div>
		<br>
			
			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_dep_id=<?=$csa_dep_id?>&period=<?=$period?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
		
		</div>
	</div>
</div>
<?			
			}
		}
	}
	echo template_footer();
	exit;	
	
} else if ($view_dep_id>0 && $period>0) {
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
		$stmt->bind_param('i', $view_dep_id);
		$stmt->execute();
		$result1 = $stmt->get_result();
		if ($row1 = mysqli_fetch_assoc($result1)) {			
			$csa_year = $row1['csa_year'];

			if ($period==1) {
				$is_confirm = $row1['is_confirm'];
				$rj_date = $row1['reject_date'];
				$rj_reason = $row1['reject_reason'];
			} else if ($period==2) {
				$is_confirm = $row1['is_confirm2'];
				$rj_date = $row1['reject_date2'];
				$rj_reason = $row1['reject_reason2'];
			} 
			if ($rj_date!='' && $rj_date!='0000-00-00 00:00:00' && $rj_reason!='') {
				$is_reject = 1;
				$reject_reason = $rj_reason;
			} else {
				$is_reject = 0;
				$reject_reason = '';
			}
			
			$is_finish_all = true;	
?>
	
<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">ส่วนที่ 2 แบบประเมินการควบคุมภายใน ครั้งที่ <?=$period?></span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_year=<?=$csa_year?>&edit_id=<?=$view_dep_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			<br>
<?		


			if ($is_confirm==1) {
?>
			<div class='row'>
			<div class='col-md-1'><i class='fa fa-check-circle' style='font-size:80px; color: green'></i></div>
			<div class='col-md-11'>
				<b>
				<?=$row1['dep_name2']?><br>
				<?=$row1['dep_name3']?></b>
			</div>
			</div>
			<br>
<?			} else { ?>
			<div class='row'>
			<div class='col-md-12'>
				<b>
				<?=$row1['dep_name2']?><br>
				<?=$row1['dep_name3']?></b>
			</div>
			</div>
			<br>
<?
			}
			
			$j_list = array();
			$j_list[0] = array();
			$j_list[1] = array();
			$j_detail_list = array();
			$is_pass_all = false;
			$sql = "SELECT 
				job_function_id, 
				job_function_other 
			FROM csa 
			WHERE 
				csa_year = '$csa_year' AND 
				csa_period = '$period' AND 
				mark_del = '0' AND 
				csa_department_id = '$row1[csa_department_id]' ";
			$result2 = mysqli_query($connect, $sql);
			while ($row2 = mysqli_fetch_array($result2)) {
				$j_list[0][] = $row2['job_function_id'];
				$j_list[1][] = $row2['job_function_other'];
			}
			$sql = "SELECT 
				* 
			FROM
				job_function 
			WHERE 
				(is_require = '1') AND
				mark_del = '0' AND 
				department_id3 = '$row1[department_id3]' 
			ORDER BY 
				job_function_no, job_function_id";
			$result2 = mysqli_query($connect, $sql);
			if (mysqli_num_rows($result2)>0) {
				$is_pass_all = true; 
				while ($row2 = mysqli_fetch_array($result2)) {
					if (in_array($row2['job_function_id'], $j_list[0])) { 
						$j_detail_list[] = '<font color="green"><i class="fa fa-check"></i></font> '.$row2['job_function_name'].'<BR>';
					} else {
						$j_detail_list[] = '<font color="red"><i class="fa fa-times"></i></font> '.$row2['job_function_name'].'<BR>';
						$is_pass_all = false;
					}
				}

				$oth = array_keys($j_list[0], 999999);
				foreach ($oth as $k) {
					$i1 = $j_list[1][$k];
					$j_detail_list[] = '<font color="green"><i class="fa fa-check"></i></font> อื่นๆ '.$i1.'<BR>';
				}				
?>
					<div class='row'>
					<div class='col-md-8'>					
					<div class='note note-<?=($is_pass_all==true) ? 'success' : 'danger'?>' style=''>
					<b>Job Function ที่จำเป็นต้องประเมิน</b><br>
<?					
					foreach ($j_detail_list as $j) {
						echo $j;
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
?>				
			
			<table class='table table-hover'>
			<thead>
			<tr style='font-weight: bold'>
				<td width='3%'>ลำดับ</td>
				<td width='52%'>กิจกรรม</td>
				<td width='20%'>ประเภทความเสี่ยง</td>
				<td width='10%'>ระดับความเสี่ยง</td>
				<td width='15%'>สถานะ</td>
			</tr>
			</thead>
			<tbody>
<?
				$j_list = array(); 
				$i=1;	
				$sql = "SELECT 
					c.*,
					r.is_other as risk_is_other,
					r.risk_type_name,
					j.job_function_no,
					j.job_function_name,
					a.audit_desc
				FROM csa c
				LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
				LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
				LEFT JOIN csa_audit a ON c.activity_type=1 AND c.csa_audit_id = a.csa_audit_id AND a.mark_del = '0'
				WHERE 
					c.csa_year = '$csa_year' AND 
					c.csa_period = '$period' AND 
					c.mark_del = '0' AND 
					c.csa_department_id = '$row1[csa_department_id]' ";
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
						
						if ($row2['is_finish']==0) $is_finish_all = false;

						$risk_level = risk_level_name($row2['csa_risk_level2']);
						$risk_level_bg = risk_level_color($row2['csa_risk_level2']);

						if ($row2['activity_type']==1) {
							$css = 'bgcolor="#ffdddd"';
							$job_function.='<BR>('.$row2['audit_desc'].')';
						} else
							$css = '';						
						
?>
			<tr onClick='document.location="csa_admin.php?view_id=<?=$row2['csa_id']?>&view_year=<?=$csa_year?>&period=<?=$period?>"' style='cursor: pointer;' <?=$css?>>
				<td><?=$i++?></td>
				<td><?=$job_function?></td>
				<td><?=$risk_type_name?></td>
				<td bgcolor='<?=$risk_level_bg?>' align='center'><?=$risk_level?></td>
				<td><font color='<?=csa_status_color($row2['is_finish'])?>' style='font-weight: bold'><?=csa_status($row2['is_finish'])?></font></td>
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

		
			
<? if ($is_reject==1) {?>	

		<div class='row'>
		<div class='col-md-8'>					
		<div class='note note-danger' style='font-size:13px'>
		<b>เหตุผลการส่งคืน / สิ่งที่ต้องแก้ไข</b><br>
		<?=$reject_reason?>
		</div>

<?}?>

	<br>
	<?=gen_comment_history_part($view_dep_id, $period, false)?>
	<br>
	<button type='button' class="btn btn-warning" onClick="document.location='csa_admin.php?comment_id=<?=$view_dep_id?>&view_year=<?=$csa_year?>&period=<?=$period?>'"><i class='fa fa-commenting'></i> บันทึกความเห็นขอแก้ไข</button>
	<br>
	<br>


		<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_year=<?=$csa_year?>&edit_id=<?=$view_dep_id?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<? if ($is_confirm==0) {
		echo 'ยังไม่ได้ยืนยันข้อมูล';
	} else {
		echo 'ยืนยันข้อมูลแล้ว';
	}
?>
			<br>
			<br>
			<br>
		</div>
		</div>
			<br>
		</div>
	</div>
</div>

<?
		}
	}
		
	echo template_footer();
	exit;	
	
} else if ($comment_id>0) {
	$sql = "SELECT 
			c.*,
			d1.department_name AS dep_name1,
			d2.department_name AS dep_name2,
			d3.department_name AS dep_name3
		FROM csa_department c
		LEFT JOIN department d1 ON c.department_id = d1.department_id
		LEFT JOIN department d2 ON c.department_id2 = d2.department_id
		LEFT JOIN department d3 ON c.department_id3 = d3.department_id
		JOIN csa_authorize_approver ca ON c.csa_department_id = ca.csa_department_id
		WHERE 
			c.csa_department_id = ? AND 
			c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $comment_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$dep1 = $row2['dep_name1'];
			$dep2 = $row2['dep_name2'];
			$dep3 = $row2['dep_name3'];
			$csa_year = $row2['csa_year'];
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-doc font-green"></i>
					<span class="caption-subject font-green sbold uppercase">บันทึกความเห็นขอแก้ไข การประเมิน CSA ประจำปี <?=$csa_year?> ส่วนที่ 2 ครั้งที่ <?=$period?></span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_dep_id=<?=$comment_id?>&period=<?=$period?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			<div class='well'>
			<b>
				<?=$dep2?><br>
				<?=$dep3?></b>
			</div>
			<br>
			
			<form method='post' action='csa_admin.php?period=<?=$period?>' id='f1'>
			<div class="form-group">
			  <label  class='label1'>ความเห็น ฝ่ายบริหารความเสี่ยง</label>
				<textarea class="form-control" placeholder="โปรดระบุเหตุผล" name='csa_comment' rows='5' required></textarea>
			</div>						

			<input type='hidden' name='update_id' value='<?=$comment_id?>'>
			<input type='hidden' name='csa_comment_period' value='<?=$period?>'>
			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_dep_id=<?=$comment_id?>&period=<?=$period?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
			<button type='submit' name='submit' value='add_comment' class="btn btn-danger"><i class='fa fa-comment'></i> ยืนยันการส่งความเห็น</button>
			</form>
		</div>
	</div>
</div>
<?
		}
	}
	echo template_footer();
	exit;	
	
} else if ($submit=='add_comment') {
	$update_id = intval($_POST['update_id']);
	$csa_comment = trim($_POST['csa_comment']);
	$csa_comment_period = trim($_POST['csa_comment_period']);
	
	if ($update_id>0 && $csa_comment!='' && $csa_comment_period>0) {
		$sql = "SELECT * 
			FROM csa_department c
			WHERE 
				c.csa_department_id = ? AND 
				c.mark_del = '0' ";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$stmt->bind_param('i', $update_id);
			$stmt->execute();
			$result2 = $stmt->get_result();
			if ($row2 = mysqli_fetch_assoc($result2)) {
				$csa_department_id = $row2['csa_department_id'];
				$csa_department_status_id = $row2['csa_department_status_id'];

				if ($csa_comment_period==1) { /* ส่วนที่ 1 */
					send_back_comment_notification_1($csa_department_id, html2text($csa_comment));
				} else if ($csa_comment_period==2) { /* ส่วนที่ 2 */
					send_back_comment_notification_2($csa_department_id, html2text($csa_comment));
				}
				

				$qx = true;	
				mysqli_autocommit($connect,FALSE);


				$sql = "INSERT INTO csa_comment (csa_department_id, comment, period, is_close, user_id, user_code, create_date)
						VALUES ('$update_id', '$csa_comment','$csa_comment_period', '0', '$user_id', '$user_code' , now())";
				$q = mysqli_query($connect, $sql);
				$qx = ($qx and $q);	

				$qx = add_history($qx, $csa_department_id, $csa_department_status_id, 'บันทึกความเห็น โดย admin');
				$edit_id = $update_id;
				
				if ($qx) {
					mysqli_commit($connect);		
					echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูล เรียบร้อยแล้ว</div></b><br></div>';
					savelog('CSA-ADMIN-RISK-COMMENT|csa_department_id|'.$update_id.'|');
				} else {
					mysqli_rollback($connect);			
					echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
				}
			} else {
				echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ไม่พบรายการประเมิน ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
			}
		}
	} else {
		echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ท่านบันทึกข้อมูลไม่สมบูรณ์ ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
	}
?>

	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_dep_id=<?=$update_id?>&period=<?=$csa_comment_period?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
<?	
	echo template_footer();
	exit;		
}


if ($submit=='update_setting') {
	$update_setting_year = intval($_POST['update_setting_year']);
	
	if ($update_setting_year>0) {
		$sql = "SELECT csa_department_id
		FROM csa_department c
		WHERE 
			c.csa_year = '$update_setting_year' AND 
			c.mark_del = '0' ";
//	 AND c.csa_department_status_id < 2 AND c.is_enable = '1' AND

		$result2 = mysqli_query($connect, $sql);
		if (mysqli_num_rows($result2)>0) {

			$qx = true;	
			mysqli_autocommit($connect, FALSE);
			
			$id_list = array();
			while ($row2 = mysqli_fetch_array($result2)) {
				$id = $row2['csa_department_id'];
				$id_list[] = $id;
				
				$i_e = intval($_POST['i_e_'.$id]);
				$i_p1 = intval($_POST['i_p1_'.$id]);
				$i_p21 = intval($_POST['i_p21_'.$id]);
				$i_p22 = intval($_POST['i_p22_'.$id]);

				$i_p1r = intval($_POST['i_p1r_'.$id]);
				$i_p21r = intval($_POST['i_p21r_'.$id]);
				$i_p22r = intval($_POST['i_p22r_'.$id]);
				
				$sql = "UPDATE csa_department SET 
is_enable='$i_e',  
is_enable_part1='$i_p1',  
is_enable_period1='$i_p21',  
is_enable_period2='$i_p22',
is_enable_part1_require='$i_p1r',  
is_enable_period1_require='$i_p21r',  
is_enable_period2_require='$i_p22r'
				WHERE csa_department_id = '$id' ";
				$q = mysqli_query($connect, $sql);
				$qx = ($qx and $q);	
				
			}

			if ($qx) {
				mysqli_commit($connect);		
				echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
				
				foreach ($id_list as $i) {
					savelog('CSA-ADMIN-SETTING|csa_department_id|'.$i.'|');
				}
			} else {
				mysqli_rollback($connect);			
				echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
			}


		}
	}
	
} else if ($submit=='save_q') {
	$update_id = intval($_POST['update_id']);

	$sql = "SELECT * 
		FROM csa_department c
		WHERE 
			c.csa_department_id = ? AND 
			c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $update_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$csa_department_id = $row2['csa_department_id'];
			$csa_department_status_id = $row2['csa_department_status_id'];
			$qx = true;	
			mysqli_autocommit($connect,FALSE);
			$q_finish = 1;

			$sql = "DELETE FROM csa_questionnaire_data WHERE csa_department_id='$csa_department_id' ";
			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);	

			$qc_0 = $_POST['qc_0'];
			$sql = "INSERT INTO csa_questionnaire_data (csa_department_id, csa_q_topic_id, v_other) VALUES (?,'0',?)";
			$stmt = $connect->prepare($sql);
			if ($stmt) {					
				$stmt->bind_param('is', $csa_department_id, $qc_0);
				$q = $stmt->execute();
				$qx = ($qx and $q);	
			}

			$sql = "SELECT * FROM csa_questionnaire_topic WHERE parent_id = '0' AND mark_del = '0' ";
			$result1 = mysqli_query($connect, $sql);
			while ($row1 = mysqli_fetch_array($result1)) {
				$sql = "SELECT * FROM csa_questionnaire_topic WHERE parent_id = '$row1[csa_q_topic_id]' AND mark_del = '0' ";
				$result3 = mysqli_query($connect, $sql);
				while ($row3 = mysqli_fetch_array($result3)) {
					$topic_id = $row3['csa_q_topic_id'];
					$v = intval($_POST['q_'.$topic_id]);
					$sql = "INSERT INTO csa_questionnaire_data (csa_department_id, csa_q_topic_id, v) VALUES ('$csa_department_id', '$topic_id', '$v')";
					$q = mysqli_query($connect, $sql);
					$qx = ($qx and $q);	
					
					if ($v==0) $q_finish = 0;
				}
			}

			$sql = "UPDATE csa_department 
			SET q_date = NOW(), q_finish = '$q_finish' 
			WHERE csa_department_id='$csa_department_id' ";
			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);	

			$qx = add_history($qx, $csa_department_id, $csa_department_status_id, 'แก้ไขส่วนที่ 1 โดย admin');
			
			
			if ($qx) {
				mysqli_commit($connect);		
				echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูล เรียบร้อยแล้ว</div></b><br></div>';
				savelog('CSA-ADMIN-RISK-PART1-UPDATE|csa_department_id|'.$update_id.'|');
			} else {
				mysqli_rollback($connect);			
				echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
			}
		}
	}

} else if ($del_authorize_id >0) {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "DELETE FROM csa_authorize WHERE csa_authorize_id = '$del_authorize_id' ";
	
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);	
		savelog('CSA-ADMIN-DELETE-AUTH-USER|csa_authorize_id|'.$del_authorize_id.'|');
		echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
	}	
} else if ($del_authorize_id2 >0) {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$sql = "DELETE FROM csa_authorize_approver WHERE csa_authorize_id = '$del_authorize_id2' ";
	
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	

	if ($qx) {
		mysqli_commit($connect);	
		savelog('CSA-ADMIN-DELETE-AUTH-APPROVER|csa_authorize_id|'.$del_authorize_id2.'|');
		echo '<div class=""><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class=""><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
	}	
	
} else if (($submit == 'save' || $submit == 'add_uid' || $submit == 'add_uid2' || $submit=='save_approve' || $submit=='reverse') && $update_id>0) {
	
	$csa_year = intval($_POST['csa_year']);
	$is_enable = intval($_POST['is_enable']);
	$is_enable_part1 = intval($_POST['is_enable_part1']);
	$is_enable_period1 = intval($_POST['is_enable_period1']);
	$is_enable_period2 = intval($_POST['is_enable_period2']);
	$is_enable_part1_require = intval($_POST['is_enable_part1_require']);
	$is_enable_period1_require = intval($_POST['is_enable_period1_require']);
	$is_enable_period2_require = intval($_POST['is_enable_period2_require']);
	$department_id = intval($_POST['department_id']);
	$department_id2 = intval($_POST['department_id2']);
	$department_id3 = intval($_POST['department_id3']);
	$remark = addslashes($_POST['remark']);
	$head2_comment = addslashes($_POST['head2_comment']);
	$risk_comment = addslashes($_POST['risk_comment']);
	$is_head2_confirm = intval($_POST['is_head2_confirm']);
	$is_risk_confirm = intval($_POST['is_risk_confirm']);
	
	// prepare
	$edit_id = $update_id;
	$edit_year = $csa_year;	
	
	if ($csa_year>0) {
	
		$qx = true;	
		mysqli_autocommit($connect, FALSE);
		$wsql = '';
		
		if ($submit=='add_uid') {
			$authorize_uid = addslashes($_POST['authorize_uid']);
			if ($authorize_uid!='') {
				$sql = "INSERT INTO csa_authorize (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$authorize_uid')";
				$q = mysqli_query($connect, $sql);
				$qx = ($qx and $q);	
				savelog('CSA-ADMIN-ADD-AUTH-USER|csa_authorize_uid|'.$authorize_uid.'|');
			}
		} else if ($submit=='add_uid2') {
			$authorize_uid2 = addslashes($_POST['authorize_uid2']);
			if ($authorize_uid2!='') {
				$sql = "INSERT INTO csa_authorize_approver (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$authorize_uid2')";
				$q = mysqli_query($connect, $sql);
				$qx = ($qx and $q);	
				savelog('CSA-ADMIN-ADD-AUTH-APPROVER|csa_authorize_uid|'.$authorize_uid2.'|');
			} 		
		} else if ($submit=='save_approve') {		
			if ($is_head2_confirm==1 && $is_risk_confirm==1) {
				$sql = "SELECT * FROM csa_department WHERE csa_department_id = ? ";
				$stmt = $connect->prepare($sql);
				if ($stmt) {					
					$stmt->bind_param('i', $update_id);
					$stmt->execute();
					$result2 = $stmt->get_result();
					if ($row2 = mysqli_fetch_assoc($result2)) {
						$old_status = $row2['csa_department_status_id'];
						
						if ($old_status<3) {
							$to_status = 3;
							$qx = add_history($qx, $update_id, $to_status, 'อนุมัติ โดยฝ่ายบริหารความเสี่ยง');
							$wsql = ", csa_department_status_id = '$to_status' ";
							savelog('CSA-ADMIN-RISK-APPROVER|csa_department_id|'.$update_id.'|');
						}
					}
				}
			}
			
		} else if ($submit=='reverse') {	
			$csa_department_status_id = intval($_POST['csa_department_status_id']);
			$csa_department_status_id_old = intval($_POST['csa_department_status_id_old']);

			$sql = "SELECT * FROM csa_department WHERE csa_department_id = ? ";
			$stmt = $connect->prepare($sql);
			if ($stmt) {					
				$stmt->bind_param('i', $update_id);
				$stmt->execute();
				$result2 = $stmt->get_result();
				if ($row2 = mysqli_fetch_assoc($result2)) {
					$old_status = $row2['csa_department_status_id'];
					
					if ($old_status==$csa_department_status_id_old) {
						if ($csa_department_status_id>$csa_department_status_id_old) {
							echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ท่านไม่สามารถเลือกสถานะไปข้างหน้าได้</div></b></div>';
							
						} else if ($csa_department_status_id==$csa_department_status_id_old) {
							echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ท่านเลือกสถานะไม่ถูกต้อง</div></b></div>';
							
						} else if ($csa_department_status_id_old==3) {
							if ($csa_department_status_id==0) {
								$to_status = $csa_department_status_id;
								$qx = add_history($qx, $update_id, $to_status, 'ย้อนสถานะ โดย Admin');
								$wsql = ", csa_department_status_id='$to_status',is_confirm='0',is_head1_confirm='0',is_head2_confirm='0',is_risk_confirm='0' ";
							} else if ($csa_department_status_id==1) {
								$to_status = $csa_department_status_id;
								$qx = add_history($qx, $update_id, $to_status, 'ย้อนสถานะ โดย Admin');
								$wsql = ", csa_department_status_id='$to_status',is_confirm='0',is_head1_confirm='0',is_head2_confirm='0',is_risk_confirm='0' ";
							} else if ($csa_department_status_id==2) {
								$to_status = $csa_department_status_id;
								$qx = add_history($qx, $update_id, $to_status, 'ย้อนสถานะ โดย Admin');
								$wsql = ", csa_department_status_id='$to_status',is_head2_confirm='0',is_risk_confirm='0' ";
							}
						} else if ($csa_department_status_id_old==2) {
							if ($csa_department_status_id==0) {
								$to_status = $csa_department_status_id;
								$qx = add_history($qx, $update_id, $to_status, 'ย้อนสถานะ โดย Admin');
								$wsql = ", csa_department_status_id='$to_status',is_confirm='0',is_head1_confirm='0',is_head2_confirm='0',is_risk_confirm='0' ";
							} else if ($csa_department_status_id==1) {
								$to_status = $csa_department_status_id;
								$qx = add_history($qx, $update_id, $to_status, 'ย้อนสถานะ โดย Admin');
								$wsql = ", csa_department_status_id='$to_status',is_confirm='0',is_head1_confirm='0',is_head2_confirm='0',is_risk_confirm='0' ";
							}
						} else if ($csa_department_status_id_old==1) {
							if ($csa_department_status_id==0) {
								$to_status = $csa_department_status_id;
								$qx = add_history($qx, $update_id, $to_status, 'ย้อนสถานะ โดย Admin');
								$wsql = ", csa_department_status_id='$to_status',is_confirm='0',is_head1_confirm='0',is_head2_confirm='0',is_risk_confirm='0' ";
							}
						}
						savelog('CSA-ADMIN-REVERSE|csa_department_id|'.$update_id.'|from_st|'.$csa_department_status_id_old.'|to_st|'.$csa_department_status_id.'|');
					}
				}
			}
		} else {
			savelog('CSA-ADMIN-UPDATE|csa_department_id|'.$update_id.'|');
		}

		
		$sql = "UPDATE csa_department SET 
		csa_year='$csa_year', 
		is_enable='$is_enable',  
		is_enable_part1='$is_enable_part1',  
		is_enable_period1='$is_enable_period1',  
		is_enable_period2='$is_enable_period2',  
		is_enable_part1_require='$is_enable_part1_require',  
		is_enable_period1_require='$is_enable_period1_require',  
		is_enable_period2_require='$is_enable_period2_require',  
		remark='$remark',
		head2_comment='$head2_comment',
		risk_comment='$risk_comment',
		is_head2_confirm='$is_head2_confirm',
		is_risk_confirm='$is_risk_confirm'
		$wsql 
		WHERE csa_department_id = '$update_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		$update_id = mysqli_insert_id($connect);

/*
		department_id='$department_id',  
		department_id2='$department_id2',  
		department_id3='$department_id3',  
*/
		
		if ($qx) {
			mysqli_commit($connect);		
			echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
		} else {
			mysqli_rollback($connect);			
			echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
		}
	}	
	
} else if ($submit == 'delete' && $update_id>0) {
	
	$qx = true;	
	mysqli_autocommit($connect, FALSE);
	
	$sql = "UPDATE csa_department SET 
	mark_del = '1'
	WHERE csa_department_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	
	
	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
		savelog('CSA-ADMIN-DELETE|csa_department_id|'.$update_id.'|');
	} else {
		mysqli_rollback($connect);			
		echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
	}
	
} else if ($action == 'setting' && $view_year>0) {		
?>
<script>
$(function () {
	$("#i_e_all").change(function(){
		var status = $(this).is(":checked") ? true : false;
		$(".enb").prop("checked",status);
	});
	$("#i_p1_all").change(function(){
		var status = $(this).is(":checked") ? true : false;
		$(".p1").prop("checked",status);
	});
	$("#i_p1r_all").change(function(){
		var status = $(this).is(":checked") ? true : false;
		$(".p1r").prop("checked",status);
	});
	$("#i_p21_all").change(function(){
		var status = $(this).is(":checked") ? true : false;
		$(".p21").prop("checked",status);
	});
	$("#i_p21r_all").change(function(){
		var status = $(this).is(":checked") ? true : false;
		$(".p21r").prop("checked",status);
	});
	$("#i_p22_all").change(function(){
		var status = $(this).is(":checked") ? true : false;
		$(".p22").prop("checked",status);
	});
	$("#i_p22r_all").change(function(){
		var status = $(this).is(":checked") ? true : false;
		$(".p22r").prop("checked",status);
	});
	
});  
</script>

<style>
.rb {
	background-color: #eeeeee;
}
</style>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">เปิด/ปิด รายการประเมิน</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_year=<?=$view_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			
			
			<form method='post' action='csa_admin.php'>  
			<br><b>เลือก เปิด/ปิด จากรายการประเมิน ที่อยู่ระหว่างดำเนินการ</b><br>
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%' rowspan='2'>ลำดับ</td>
				<td width='20%' rowspan='2'>สายงาน</td>
				<td width='20%' rowspan='2'>ฝ่าย</td>
				<td width='20%' rowspan='2'>กลุ่ม</td>
				<td width='4%' rowspan='2'>แสดง</td>
				<td width='8%' colspan='2' align='center'>ส่วนที่ 1</td>
				<td width='16%' colspan='4' align='center'>ส่วนที่ 2</td>
			</tr>
			<tr>
				<td width='4%' align='center' class='rb'>1E</td>
				<td width='4%' align='center'>1R</td>
				<td width='4%' align='center' class='rb'>2.1E</td>
				<td width='4%' align='center'>2.1R</td>
				<td width='4%' align='center' class='rb'>2.2E</td>
				<td width='4%' align='center'>2.2R</td>
			</tr>	
			</thead>
			<tbody>
<?
	$is_all=array();
	for ($i=0; $i<6; $i++) 
		$is_all[$i] = true;
	
	$i=1;	
	$sql = "SELECT 
		c.*,
		d1.department_id AS dep_id1,
		d1.department_no AS dep_no1,
		d1.department_name AS dep_name1,
		d2.department_id AS dep_id2,
		d2.department_no AS dep_no2,
		d2.department_name AS dep_name2,
		d3.department_id AS dep_id3,
		d3.department_no AS dep_no3,
		d3.department_name AS dep_name3,
		st.csa_department_status_color,
		st.csa_department_status_name
	FROM csa_department c
	LEFT JOIN `department` d1 ON  c.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  c.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  c.department_id3 = d3.department_id AND d3.mark_del = '0'
	LEFT JOIN csa_department_status st ON st.csa_department_status_id = c.csa_department_status_id
	WHERE 
		c.csa_year = '$view_year' AND 
		c.mark_del = '0' AND
		c.csa_department_status_id < 2
	ORDER BY
		dep_no1,
		dep_name1,
		dep_no2,
		dep_name2,
		dep_no3,
		dep_name3";
//		is_enable = '1' AND
		
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
			$id = $row2['csa_department_id'];
			
			if ($row2['is_enable']==0) $is_all[0] = false;
			if ($row2['is_enable_part1']==0) $is_all[1] = false;
			if ($row2['is_enable_part1_require']==0) $is_all[2] = false;
			if ($row2['is_enable_period1']==0) $is_all[3] = false;
			if ($row2['is_enable_period1_require']==0) $is_all[4] = false;
			if ($row2['is_enable_period2']==0) $is_all[5] = false;
			if ($row2['is_enable_period2_require']==0) $is_all[6] = false;
?>
			<tr>
				<td><?=$i++?></td>
				<td><?=$row2['dep_name1']?></td>
				<td><?=$row2['dep_name2']?></td>
				<td><?=$row2['dep_name3']?></td>
				<td align='center'><input type='checkbox' name='i_e_<?=$id?>' class='enb' value='1' <?if ($row2['is_enable']==1) echo 'checked'?>></td>
				<td align='center' class='rb'><input type='checkbox' name='i_p1_<?=$id?>' class='p1' value='1' <?if ($row2['is_enable_part1']==1) echo 'checked'?>></td>
				<td align='center'><input type='checkbox' name='i_p1r_<?=$id?>' class='p1r' value='1' <?if ($row2['is_enable_part1_require']==1) echo 'checked'?>></td>
				<td align='center' class='rb'><input type='checkbox' name='i_p21_<?=$id?>' class='p21' value='1' <?if ($row2['is_enable_period1']==1) echo 'checked'?>></td>
				<td align='center'><input type='checkbox' name='i_p21r_<?=$id?>' class='p21r' value='1' <?if ($row2['is_enable_period1_require']==1) echo 'checked'?>></td>
				<td align='center' class='rb'><input type='checkbox' name='i_p22_<?=$id?>' class='p22' value='1' <?if ($row2['is_enable_period2']==1) echo 'checked'?>></td>
				<td align='center'><input type='checkbox' name='i_p22r_<?=$id?>' class='p22r' value='1' <?if ($row2['is_enable_period2_require']==1) echo 'checked'?>></td>
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
			<tfoot>
			<tr>
				<td colspan='4' align='right'>เลือกทั้งหมด</td>
				<td align='center'><input type='checkbox' id='i_e_all' <?if ($is_all[0]==true) echo 'checked'?>></td>
				<td align='center' class='rb'><input type='checkbox' id='i_p1_all' <?if ($is_all[1]==true) echo 'checked'?>></td>
				<td align='center'><input type='checkbox' id='i_p1r_all' <?if ($is_all[2]==true) echo 'checked'?>></td>
				<td align='center' class='rb'><input type='checkbox' id='i_p21_all' <?if ($is_all[3]==true) echo 'checked'?>></td>
				<td align='center'><input type='checkbox' id='i_p21r_all' <?if ($is_all[4]==true) echo 'checked'?>></td>
				<td align='center' class='rb'><input type='checkbox' id='i_p22_all' <?if ($is_all[5]==true) echo 'checked'?>></td>
				<td align='center'><input type='checkbox' id='i_p22r_all' <?if ($is_all[6]==true) echo 'checked'?>></td>
			</tr>			
			</tfoot>			
			</table>	

			<br>
			<br>
			<input type='hidden' name='update_setting_year' value='<?=$view_year?>'>
			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_year=<?=$view_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
			<button type='submit' name='submit' value='update_setting' class="btn btn-success"><i class='fa fa-save'></i> บันทึกข้อมูล</button>
			</form>


		</div>
	</div>
</div>
		  
<?

	echo template_footer();
	exit;


} else if ($action == 'add' && $view_year>0) {		
	$dep_list = array();
	$sql = "SELECT * FROM department WHERE mark_del = 0 AND parent_id<>0 ";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) { 
		$dep_list[$row2['department_id']] = $row2['parent_id'];
	}
?>

<script language='JavaScript'>
	$(function () {
		$("#department_l1").change(function() {
			var d = parseInt($("#department_l1").val());
			if (d>0) {
				$.post( "jobfunction_content.php", { action: 'd', parent: d, lv: 1 })
				.done(function( data ) {
					$("#d_lv2").html(data);
					$("#d_lv3").html("<div class='form-group' id='d_lv3'><label>กลุ่ม</label><select name='department_id3' id='department_l3' class='form-control' disabled></select></div>");
					$("#department_l2").change(function() {
						var d = parseInt($("#department_l2").val());
						if (d>0) {
							$.post( "jobfunction_content.php", { action: 'd', parent: d, lv: 2 })
							.done(function( data ) {
								$("#d_lv3").html(data);
							});	
						}
					});				
				});	
			}
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
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_year=<?=$view_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
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
	
			<form method='post' action='csa_admin.php'>  
	<div class="form-group">
	  <label>สายงาน</label>
	  <select name='department_id' id='department_l1' class="form-control" required>
		<option value='0'>--- เลือก ---</option>
<?

$sql="SELECT * FROM department WHERE department_level_id = 3 ORDER BY department_id";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$department_id) echo 'selected'?>><?=$row1['department_name']?></option>
<?
}
?>
		</select>
	</div>
	<div class="form-group" id='d_lv2'>
	  <label>ฝ่าย</label>
	  <select name='department_id2' id='department_l2' class="form-control" disabled>
	  </select>
	</div>	
	<div class="form-group" id='d_lv3'>
	  <label>กลุ่ม</label>
	  <select name='department_id3' id='department_l3' class="form-control" disabled>
	  </select>
	</div>
	
			<div class="form-group">
			  <label>หมายเหตุ</label>
			  <input type="text" class="form-control" name='remark' placeholder="หมายเหตุ" value=''>
			</div>	
			<div class="form-group">
			  <label>ปี พ.ศ.</label>
			  <input type="text" class="form-control" name='csa_year' placeholder="ปี พ.ศ." value='<?=$view_year?>'>
			</div>	
			<br>
			<br>
			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_year=<?=$view_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
			<button type='submit' name='submit' value='add' class="btn btn-success"><i class='fa fa-plus-circle'></i> เพิ่ม</button>
			</form>
			
		</div>
		<div class="tab-pane" id="tab2">
			<form method='post' action='csa_admin.php'>  
			<div class="form-group">
			  <label>ปี พ.ศ.</label>
			  <input type="text" class="form-control" name='csa_year1' placeholder="ปี พ.ศ." value='<?=$view_year?>'>
			</div>	
			<br>
		
			<div class="form-group">
				<label>ฝ่าย</label><br>
				<input type='checkbox' id='checkall' onClick='toggle(this)'> เลือกทั้งหมด 
				<table class='table table-hover table-light'>
				<thead>
				<tr>
				  <th width='5%'></th>
				  <th width='30%'>สายงาน</th>
				  <th width='30%'>ฝ่าย</th>
				  <th width='30%'>กล่ม</th>
				</tr>
				</thead>
				<tbody>
<?

	$sql = "SELECT 
		d.*,
		d1.department_id AS dep_id1,
		d1.department_no AS dep_no1,
		d1.department_name AS dep_name1,
		d2.department_id AS dep_id2,
		d2.department_no AS dep_no2,
		d2.department_name AS dep_name2
	FROM department d
	JOIN department d1 ON d.parent_id = d1.department_id AND d1.mark_del = 0
	JOIN department d2 ON d1.parent_id = d2.department_id AND d2.mark_del = 0
	WHERE 
		d.mark_del = 0 AND 
		d.department_level_id = 5 
	ORDER BY 
		dep_no2,
		dep_name2,
		dep_no1,
		dep_name1,
		d.department_no, 
		d.department_name";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) { 
?>
			<tr>
				<td><input type='checkbox' name='dep[]'  value='<?=$row2['department_id']?>|<?=$row2['dep_id1']?>|<?=$row2['dep_id2']?>'></td>
				<td><?=$row2['dep_name2']?></td>
				<td><?=$row2['dep_name1']?></td>
				<td><?=$row2['department_name']?></td>
			</tr>
<?	
	}
?>
				</table>
				</div>
				<br>
			<br>
			<br>
			<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php?view_year=<?=$view_year?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
			<button type='submit' name='submit' value='add' class="btn btn-success"><i class='fa fa-plus-circle'></i> เพิ่ม</button>
			</form>

		</div>	
			
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

	$csa_year = intval($_POST['csa_year']);
	$department_id = intval($_POST['department_id']);
	$department_id2 = intval($_POST['department_id2']);
	$department_id3 = intval($_POST['department_id3']);
	$remark = addslashes($_POST['remark']);
	
	if ($department_id>0 && $department_id2>0 && $department_id3>0 && $csa_year>0) {

		$sql = "SELECT COUNT(*) AS num FROM csa_department WHERE csa_year=? AND department_id3=? AND mark_del=0 ";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$stmt->bind_param('ii', $csa_year, $department_id3);
			$stmt->execute();
			$result2 = $stmt->get_result();
			$row2 = mysqli_fetch_assoc($result2);
			if ($row2['num']==0) {
	
				$qx = true;	
				mysqli_autocommit($connect, FALSE);

				$to_status = 0;
				
				$sql = "INSERT INTO csa_department (department_id, department_id2, department_id3, remark, csa_year, is_enable, is_enable_part1, is_enable_period1, is_enable_period2, is_enable_part1_require, is_enable_period1_require, is_enable_period2_require, csa_department_status_id, create_date) VALUES 
				('$department_id', '$department_id2', '$department_id3', '$remark', '$csa_year',1, 0,1,0, 1,1,1, '$to_status', now() ) ";
				$q = mysqli_query($connect, $sql);
				$qx = ($qx and $q);	
				$update_id = mysqli_insert_id($connect);
				
				$ucode_head = get_head_csa_ucode($update_id);
				foreach ($ucode_head as $u) {
					$u = trim($u);
					if ($u!='') {
						$sql = "INSERT INTO csa_authorize_approver (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$u')";
						$q = mysqli_query($connect, $sql);
						$qx = ($qx and $q);	
					}
				}

				$ucode_user = get_user_csa_ucode($update_id);
				foreach ($ucode_user as $u) {
					$u = trim($u);
					if ($u!='') {
						$sql = "INSERT INTO csa_authorize (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$u')";
						$q = mysqli_query($connect, $sql);
						$qx = ($qx and $q);	
					}
				}

				$qx = add_history($qx, $update_id, $to_status, 'สร้างการประเมินโดย Admin');
			
				
				if ($qx) {
					mysqli_commit($connect);		
					echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
				} else {
					mysqli_rollback($connect);			
					echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
				}
			} else {
				echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ท่านเลือกกลุ่มงานซ้ำกับที่มีอยู่ในระบบ</div></b><br></div>';
			}
		}
		
	} else {
		$dep = $_POST['dep'];
		$dep = (isset($dep) && is_array($dep)) ? $dep : [];
		
		
		
		$cnt = count($dep);
		if($cnt > 0) {
			$qx = true;	
			mysqli_autocommit($connect, FALSE);			
		
			$to_status = 0;
			
			$csa_year1 = intval($_POST['csa_year1']);
			$i=0;
			foreach ($dep as $d) {
				list($d0, $d1, $d2) = explode('|', $d);
				if ($d0>0 && $d1>0 && $d2>0) {

					$sql = "SELECT COUNT(*) AS num FROM csa_department WHERE csa_year=? AND department_id3=? AND mark_del=0 ";
					$stmt = $connect->prepare($sql);
					if ($stmt) {					
						$stmt->bind_param('ii', $csa_year1, $d0);
						$stmt->execute();
						$result2 = $stmt->get_result();
						$row2 = mysqli_fetch_assoc($result2);
						if ($row2['num']==0) {

							$sql = "INSERT INTO csa_department (department_id, department_id2, department_id3, remark, csa_year, csa_department_status_id, is_enable, is_enable_part1, is_enable_period1, is_enable_period2, is_enable_part1_require, is_enable_period1_require, is_enable_period2_require, create_date) 
							VALUES 
							('$d2', '$d1', '$d0', '', '$csa_year1', '$to_status',1, 0,1,0, 1,1,1, now() ) ";
							$q = mysqli_query($connect, $sql);
							$qx = ($qx and $q);	
							$update_id = mysqli_insert_id($connect);

							$ucode_head = get_head_csa_ucode($update_id);
							foreach ($ucode_head as $u) {
								$u = trim($u);
								if ($u!='') {
									$sql = "INSERT INTO csa_authorize_approver (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$u')";
									$q = mysqli_query($connect, $sql);
									$qx = ($qx and $q);	
								}
							}

							$ucode_user = get_user_csa_ucode($update_id);
							foreach ($ucode_user as $u) {
								$u = trim($u);
								if ($u!='') {
									$sql = "INSERT INTO csa_authorize (csa_department_id, csa_authorize_uid) VALUES ('$update_id', '$u')";
									$q = mysqli_query($connect, $sql);
									$qx = ($qx and $q);	
								}
							}

							$qx = add_history($qx, $update_id, $to_status, 'สร้างการประเมินโดย Admin');
						}
					}
				}				
			}

			if ($qx) {
				mysqli_commit($connect);	
	?>
	<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว ได้แก่</b></font><br><br>
	<?

				
				$sql = "SELECT department_name FROM department WHERE mark_del = 0 AND department_id IN (".implode(',' , $dep).") ORDER BY department_no, department_name";
				$result2 = mysqli_query($connect, $sql);
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


if ($edit_id>0) {
	$sql = "SELECT 
			c.*,
			d1.department_name AS dep_name1,
			d2.department_name AS dep_name2,
			d3.department_name AS dep_name3,
			st.csa_department_status_color,
			st.csa_department_status_name
		FROM csa_department c
		LEFT JOIN department d1 ON c.department_id = d1.department_id
		LEFT JOIN department d2 ON c.department_id2 = d2.department_id
		LEFT JOIN department d3 ON c.department_id3 = d3.department_id
		LEFT JOIN csa_authorize_approver ca ON c.csa_department_id = ca.csa_department_id
		LEFT JOIN csa_department_status st ON st.csa_department_status_id = c.csa_department_status_id
		WHERE 
			c.csa_department_id = ? AND 
			c.mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $edit_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$q_finish = $row2['q_finish'];
			$is_confirm = $row2['is_confirm'];
			$is_confirm2 = $row2['is_confirm2'];
			$csa_department_status_id = $row2['csa_department_status_id'];
			$csa_department_id = $row2['csa_department_id'];
			$department_id3 = $row2['department_id3'];	
			$csa_year = $row2['csa_year'];	
			$head2_comment = $row2['head2_comment'];	
			$risk_comment = $row2['risk_comment'];	
			$is_head1_confirm = $row2['is_head1_confirm'];	
			$is_head2_confirm = $row2['is_head2_confirm'];	
			$is_risk_confirm = $row2['is_risk_confirm'];	
?>

<link href="jquery-ui-1.12.0/jquery-ui.css" rel="stylesheet">
<script src="jquery-ui-1.12.0/jquery-ui.js"></script>
<script>
function check_comment() {
	if ($('#csa_comment1').val().trim()=='' && $('#csa_comment2').val().trim()=='') {
		alert('กรุณาระบุ ความเห็นก่อนเพิ่ม');
		
		$('#csa_comment1').focus();
		return false;
	}
	
	return true;
}

$(function () {
/*	var d1 = parseInt($("#d1").val());
	var d2 = parseInt($("#d2").val());
	var d3 = parseInt($("#d3").val());
	
	$("#department_l1").change(function() {
		var d = parseInt($("#department_l1").val());
		$.post( "jobfunction_content.php", { action: 'd', parent: d, data:d2, lv: 1 })
		.done(function( data ) {
			$("#d_lv2").html(data);
			$("#d_lv3").html("<div class='form-group' id='d_lv3'><label>กลุ่ม</label><select name='department_id3' id='department_l3' class='form-control' disabled></select></div>");
			$("#department_l2").change(function() {
				var d = parseInt($("#department_l2").val());
				if (d>0) {
					$.post( "jobfunction_content.php", { action: 'd', parent:d, data: d3, lv: 2 })
					.done(function( data ) {
						$("#d_lv3").html(data);
					});	
					
					$('option:not(:selected)').attr('disabled', true);
				}
			}).change();				
		});	
	}).change();	*/
	
	save_tab();
	

	$("textarea").keyup(function(e) {
		if ($(this).val()!='') {
			while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
				$(this).height($(this).height()+1);
			};
		}
	}).keyup();
	
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
	  e.preventDefault();
	  var tab_name = this.getAttribute('href');
	  if (history.pushState) {
		history.pushState(null, null, tab_name);
	  }
	  else {
		location.hash = tab_name;
	  }
	  localStorage.setItem('activeTab', tab_name);
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
<div class='well'>
	<b>
	<?=$row2['dep_name2']?><br>
	<?=$row2['dep_name3']?></b><br>
	Approver : <?
	$u = get_user_profile(get_head_csa_uid($edit_id));
	if (count($u)>0) {
		echo $u[0];
	} else {
		echo '-ไม่มีข้อมูล-';
	}
?>
</div>	
			
<form method='post' action='csa_admin.php'>  
	
<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">แก้ไขข้อมูลหลัก</a></li>
		<li class=""><a href="#tab5" data-toggle="tab" aria-expanded="true">สิทธิการใช้งาน</a></li>
		<li class=""><a href="#tab3" data-toggle="tab" aria-expanded="true">ข้อมูลการประเมิน</a></li>
		<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="true">ข้อมูลอื่นที่เกี่ยวข้อง</a></li>
		<li class=""><a href="#tab4" data-toggle="tab" aria-expanded="true">การพิจารณาอนุมัติ</a></li>
		<li class=""><a href="#tab6" data-toggle="tab" aria-expanded="true">ปลดล็อค/ถอยสถานะ</a></li>
		<!--<li class=""><a href="#tab7" data-toggle="tab" aria-expanded="true">พิมพ์รายงาน</a></li>-->
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">	

	<input type='hidden' id='d1' value='<?=$row2['department_id']?>'>
	<input type='hidden' id='d2' value='<?=$row2['department_id2']?>'>
	<input type='hidden' id='d3' value='<?=$row2['department_id3']?>'>	
	
	<div class="form-group">
	  <label>ปี พ.ศ.</label>
	  <input type="text" class="form-control" name='csa_year' placeholder="ปี พ.ศ." value='<?=$row2['csa_year']?>' readonly>
	</div>	
<!--	<div class="form-group">
	  <label>สายงาน</label>
	  <select name='department_id' id='department_l1' class="form-control" required>
		<option value='0'>--- เลือก ---</option>
<?

$sql="SELECT * FROM department WHERE department_level_id = 3 ORDER BY department_id";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$row2['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<?
}
?>
		</select>
	</div>
	<div class="form-group" id='d_lv2'>
	  <label>ฝ่าย</label>
	  <select name='department_id2' id='department_l2' class="form-control" disabled>
	  </select>
	</div>	
	<div class="form-group" id='d_lv3'>
	  <label>กลุ่ม</label>
	  <select name='department_id3' id='department_l3' class="form-control" disabled>
	  </select>
	</div>-->
	<div class="form-group">
	  <label>หมายเหตุ</label>
	  <input type="text" class="form-control" name='remark' placeholder="หมายเหตุ" value='<?=$row2['remark']?>'>
	</div>		
	<br>
	<div class="form-group">
	  <label>การเปิดใช้งาน</label><br>
	  <input type="checkbox" name='is_enable' id='is_enable' value='1' <?if ($row2['is_enable']==1) echo 'checked'?>> เปิดให้เห็น/ให้ประเมินได้<br>
	<br>
	<br>

		<div class='row'>
		<div class='col-md-5'>
		<table class='table'>
		<tr valign='top'>
			<td width='40%'></td>
			<td width='15%'>Enable</td>
			<td width='15%'>Require</td>
		</tr>
		<tr>
			<td>ส่วนที่ 1</td>
			<td><input type="checkbox" name='is_enable_part1' id='is_enable_part1' value='1' <?if ($row2['is_enable_part1']==1) echo 'checked'?>></td>
			<td><input type="checkbox" name='is_enable_part1_require' id='is_enable_part1_require' value='1' <?if ($row2['is_enable_part1_require']==1) echo 'checked'?>></td>
		</tr>
		<tr>
			<td>ส่วนที่ 2 ครั้งที่ 1</td>
			<td><input type="checkbox" name='is_enable_period1' id='is_enable_period1' value='1' <?if ($row2['is_enable_period1']==1) echo 'checked'?>></td>
			<td><input type="checkbox" name='is_enable_period1_require' id='is_enable_period1_require' value='1' <?if ($row2['is_enable_period1_require']==1) echo 'checked'?>></td>
		</tr>
		<tr>
			<td>ส่วนที่ 2 ครั้งที่ 2</td>
			<td><input type="checkbox" name='is_enable_period2' id='is_enable_period2' value='1' <?if ($row2['is_enable_period2']==1) echo 'checked'?>></td>
			<td><input type="checkbox" name='is_enable_period2_require' id='is_enable_period2_require' value='1' <?if ($row2['is_enable_period2_require']==1) echo 'checked'?>></td>
		</tr>
		</table>
		</div>
		</div>
		<br>
	</div>
	<br>
	<BR><BR>
	<br>
	<br>

	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='save' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>	
	<button type='submit' name='submit' value='delete' class='btn btn-danger' onClick='return confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")' id='confirm_btn'><i class='fa fa-times'></i> ลบรายการ</button>
	
<? /* if ($row2['is_confirm']==1) { ?>	
	<button type='submit' name='submit' value='unlock' class="btn btn-danger" onClick='return confirm("โปรดยืนยันการปลดล็อค")'><i class='fa fa-unlock'></i> ปลดล็อค</button>
<? } */?>	
		
		</div>
		
<div class="tab-pane " id="tab5">	
	<div class="form-group">
	<div class='row'>
		<div class='col-md-5' style='margin: 20px'>
			<table class='table table-hover change_history'>
			<thead>
			<tr>
				<td colspan='3'>รหัสผู้มีสิทธิ ประเมิน</td>
			</tr>
			</thead>
			<tbody>
<? 

		$j=1;
		$sql = "SELECT 
			csa_authorize.*,
			u.userName,
			CONCAT(u.prefix, u.name, ' ', u.surname) AS uname
		FROM csa_authorize
		LEFT JOIN user u ON csa_authorize.csa_authorize_uid = u.code
		WHERE 
			csa_authorize.csa_department_id = '$edit_id' ";				
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
			<tr class='tr_sm'>
				<td width='5%'><?=$j++?></td>
				<td width='15%'><?= $row1['csa_authorize_uid']?></td>
				<td width='50%'><?= $row1['uname']?></td>
				<td width='25%'><?= $row1['userName']?></td>
				<td width='5%' align='right'><?if (!$is_lock) {?><a href="csa_admin.php?edit_id=<?=$edit_id?>&del_authorize_id=<?=$row1['csa_authorize_id']?>&view_dep=<?=$view_dep?>#bottom2" onClick='return confirm("Confirm Delete?")' class="delete-row">ลบ</a><? } ?></td>
			</tr>
<?
			} 
		} else {
?>
			<tr>
				<td colspan='4'> ยังไม่มีข้อมูล </td>
			</tr>
<?				
		}
?>
			</tbody>
			</table>
			<div class='row'>
				<div class='col-md-6'><input type="text" class="form-control" name='authorize_uid' placeholder="รหัสพนักงาน" value=''></div>
				<div class='col-md-6'><button type='submit' name='submit' value='add_uid' class="btn btn-primary"><i class='fa fa-plus-circle'></i> เพิ่มผู้ประเมิน</button></div>
			</div>
		</div>		
	</div>
	</div>	
	<br>
	<br>
	<br>
	<div class="form-group">
	<div class='row'>	
		<div class='col-md-5' style='margin: 20px'>
			<table class='table table-hover change_history'>
			<thead>
			<tr>
				<td colspan='4'>รหัสผู้มีสิทธิ อนุมัติรายการ</td>
			</tr>
			</thead>
			<tbody>
<? 

		$j=1;
		$sql = "SELECT 
			csa_authorize_approver.*,
			u.userName,
			CONCAT(u.prefix, u.name, ' ', u.surname) AS uname
		FROM csa_authorize_approver
		LEFT JOIN user u ON csa_authorize_approver.csa_authorize_uid = u.code
		WHERE 
			csa_authorize_approver.csa_department_id = '$edit_id' ";		
			
		$result1=mysqli_query($connect, $sql);
		if (mysqli_num_rows($result1)>0) {
			while ($row1 = mysqli_fetch_array($result1)) {	
?>
			<tr class='tr_sm'>
				<td width='5%'><?=$j++?></td>
				<td width='15%'><?= $row1['csa_authorize_uid']?></td>
				<td width='50%'><?= $row1['uname']?></td>
				<td width='25%'><?= $row1['userName']?></td>
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
			<div class='row'>
				<div class='col-md-6'><input type="text" class="form-control" name='authorize_uid2' placeholder="รหัสพนักงาน" value=''></div>
				<div class='col-md-6'><button type='submit' name='submit' value='add_uid2' class="btn btn-primary"><i class='fa fa-plus-circle'></i> เพิ่มผู้อนุมัติ</button></div>
			</div>
		</div>
	</div>
	</div>
</div>
		
		
		<div class="tab-pane " id="tab3">	

<?
				$j_list = array();
				$j_detail_list = array();
				$is_pass_all = false;
				$sql = "SELECT job_function_id FROM csa WHERE mark_del = '0' AND csa_department_id = '$csa_department_id' ";
				$result3 = mysqli_query($connect, $sql);
				while ($row3 = mysqli_fetch_array($result3)) {
					$j_list[] = $row3['job_function_id'];
				}
				
				$cnt_require = 0;
				$sql = "SELECT * FROM job_function 
				WHERE 
					(is_require = '1') AND
					mark_del = '0' AND 
					department_id3 = '$department_id3' 
				ORDER BY 
					job_function_no, job_function_id";
				$result3 = mysqli_query($connect, $sql);
				if (mysqli_num_rows($result3)>0) {
					$is_pass_all = true; 
					while ($row3 = mysqli_fetch_array($result3)) {
						if (in_array($row3['job_function_id'], $j_list)) { 
							$j_detail_list[] = '<font color="green"><i class="fa fa-check"></i></font> '.$row3['job_function_no'].' '.$row3['job_function_name'].'<BR>';
						} else {
							$j_detail_list[] = '<font color="red"><i class="fa fa-times"></i></font> '.$row3['job_function_no'].' '.$row3['job_function_name'].'<BR>';
							$is_pass_all = false;
						}
						$cnt_require++;
					}
				}

				$cnt_total = array();
				$cnt_finish = array();
				$cnt_action_plan = array();
				$sql = "
				SELECT  
					SUM(case when is_finish = 1 then 1 else 0 end) AS cnt_finish,
					SUM(case when csa_risk_level2 >=3 then 1 else 0 end) AS cnt_action_plan,
					COUNT(*) AS cnt_total,
					csa_period
				FROM csa c
				WHERE 
					c.csa_year = '$csa_year' AND 
					c.mark_del = '0' AND 
					c.csa_department_id = '$edit_id' 
				GROUP BY 
					c.csa_period ";
				$result3 = mysqli_query($connect, $sql);
				while ($row3 = mysqli_fetch_array($result3)) {
					$p = $row3['csa_period'];
					$cnt_finish[$p] = $row3['cnt_finish'];
					$cnt_total[$p] = $row3['cnt_total'];
					$cnt_action_plan[$p] = $row3['cnt_action_plan'];
				}
?>		
				
<br>
<div class='row'>
<div class='col-md-12'>
		<b>ส่วนที่ 1</b><br>
		<div class='well'>
			<? if ($q_finish==1) {?>
			<i class='fa fa-check-circle' style='font-size:20px; color: green'></i> ประเมินครบ<br><br>
			<? } else {?>
			<i class='fa fa-warning' style='font-size:20px; color: #bdb000'></i> ยังประเมินไม่ครบ<br>
			<? }?>
			<br>
			<a href='csa_admin.php?q_id=<?=$csa_department_id?>' class="btn btn-default"><i class='fa fa-search'></i> แสดงแบบประเมิน</a>
			<a href='csa_admin.php?print_q_id=<?=$csa_department_id?>' class="btn btn-default"><i class='fa fa-print'></i> พิมพ์แบบประเมิน</a>
		</div>
</div>
</div>
		<br>

<div class='row'>
<div class='col-md-6'>
		<b>ส่วนที่ 2 - ครั้งที่ 1</b><br>
		<div class='well'>
			<? if ($is_confirm==1) {?>
			<i class='fa fa-check-circle' style='font-size:20px; color: green'></i> ยืนยันแล้ว<br>
			<? } else {?>
			<i class='fa fa-warning' style='font-size:20px; color: #bdb000'></i> อยู่ระหว่างจัดทำ<br>
			<? }?>
			กิจกรรมทั้งหมด : <?=intval($cnt_total[1])?><br>
			กิจกรรมที่บังคับ : <?=$cnt_require?><br>
			กิจกรรมที่เสร็จ : <?=intval($cnt_finish[1])?><br>
			กิจกรรมที่ต้องทำ Action Plan : <?=intval($cnt_action_plan[1])?><br>	
			<br>
			<a href='csa_admin.php?view_dep_id=<?=$csa_department_id?>&period=1' class="btn btn-default"><i class='fa fa-search'></i> แสดงแบบประเมิน</a><br>
			<br>
			<br>
<? if ($cnt_total[1]>0) { ?>
			<a href='csa_admin.php?print_s21_id=<?=$csa_department_id?>&period=1' class="btn btn-default"><i class='fa fa-print'></i> พิมพ์รายงานประเมินผลการควบคุมภายใน</a><br>
<? } else {?>			
			<button class="btn btn-default" disabled type='button'><i class='fa fa-print'></i> พิมพ์รายงานประเมินผลการควบคุมภายใน</button>
			กิจกรรมนี้ ยังไม่มีรายการประเมิน<br>
<? }?>			
<? if ($cnt_action_plan[1]>0) { ?>
			<a href='csa_admin.php?print_s22_id=<?=$csa_department_id?>&period=1' class="btn btn-default"><i class='fa fa-print'></i> พิมพ์แผนปฏิบัติการ (Action Plan)</a><br>
<? } else {?>			
			<button class="btn btn-default" disabled type='button'><i class='fa fa-print'></i> พิมพ์แผนปฏิบัติการ (Action Plan)</button>
			กิจกรรมนี้ ไม่มี Action Plan<br>
<? }?>			
			
		</div>		
		<br>
		<br>
		<?=gen_comment_history_part($csa_department_id, 1, true)?>
</div>
<div class='col-md-6'>

		<b>ส่วนที่ 2 - ครั้งที่ 2</b><br>
		<div class='well'>
			<? if ($is_confirm2==1) {?>
			<i class='fa fa-check-circle' style='font-size:20px; color: green'></i> ยืนยันแล้ว<br>
			<? } else {?>
			<i class='fa fa-warning' style='font-size:20px; color: #bdb000'></i> อยู่ระหว่างจัดทำ<br>
			<? }?>
			กิจกรรมทั้งหมด : <?=intval($cnt_total[2])?><br>
			กิจกรรมที่บังคับ : <?=$cnt_require?><br>
			กิจกรรมที่เสร็จ : <?=intval($cnt_finish[2])?><br>
			กิจกรรมที่ต้องทำ Action Plan : <?=intval($cnt_action_plan[2])?><br>	
			<br>
			<a href='csa_admin.php?view_dep_id=<?=$csa_department_id?>&period=2' class="btn btn-default"><i class='fa fa-search'></i> แสดงแบบประเมิน</a><br>
			<br>
			<br>
<? if ($cnt_total[2]>0) { ?>
			<a href='csa_admin.php?print_s21_id=<?=$csa_department_id?>&period=2' class="btn btn-default"><i class='fa fa-print'></i> พิมพ์รายงานประเมินผลการควบคุมภายใน</a><br>
<? } else {?>			
			<button class="btn btn-default" disabled type='button'><i class='fa fa-print'></i> พิมพ์รายงานประเมินผลการควบคุมภายใน</button>
			กิจกรรมนี้ ยังไม่มีรายการประเมิน<br>
<? }?>			
<? if ($cnt_action_plan[2]>0) { ?>
			<a href='csa_admin.php?print_s22_id=<?=$csa_department_id?>&period=2' class="btn btn-default"><i class='fa fa-print'></i> พิมพ์แผนปฏิบัติการ (Action Plan)</a><br>
<? } else {?>			
			<button class="btn btn-default" disabled type='button'><i class='fa fa-print'></i> พิมพ์แผนปฏิบัติการ (Action Plan)</button>
			กิจกรรมนี้ ไม่มี Action Plan<br>
<? }?>			
			
		</div>
		<br>
		<br>
		<?=gen_comment_history_part($csa_department_id, 2, true)?>			

</div>
</div>
		<br>
		<br>

			

		</div>

		
		<div class="tab-pane" id="tab4">	
			<br>

			<div class='row'>
			<div class='col-md-4'>
				<div class="form-group">
				  <label><b>ความเห็นสายงาน / มติที่ประชุม</b></label><br>
				  <textarea class="form-control" name='head2_comment' id='head2_comment' rows='4'><?=$head2_comment?></textarea>
				</div>	
			</div>
			</div>
			<input type='checkbox' name='is_head2_confirm' value='1' <?if ($is_head2_confirm==1) echo 'checked'?>> สายงาน / มติที่ประชุม พิจารณาอนุมัติ<br>
			<br>
			<br>
			<div class='row'>
			<div class='col-md-4'>
				<div class="form-group">
				  <label><b>ความฝ่ายบริหารความเสี่ยง</b></label><br>
				  <textarea class="form-control" name='risk_comment' id='risk_comment' rows='4'><?=$risk_comment?></textarea>
				</div>	
			</div>
			</div>
			<input type='checkbox' name='is_risk_confirm' value='1' <?if ($is_risk_confirm==1) echo 'checked'?>> ความฝ่ายบริหารความเสี่ยง พิจารณาอนุมัติ<br>
			<br>
			<br>
			<button type='submit' name='submit' value='save_approve' class="btn btn-success"><i class='fa fa-save'></i> บันทึกผลการพิจารณา</button>	
			<br>
			<br>


<hr>
<br>			
<br>			
<?=gen_change_history($edit_id)?>
			<br>
			<br>




		</div>	
		
		<div class="tab-pane " id="tab2">	

			<br>
			<b>รายงานผลการตรวจสอบ</b><br>
<table class='table table-hover'>
<thead>
<tr>
  <th width='5%'>No.</th>
  <th width='15%'>วันที่ตรวจ</th>
  <th width='45%'>รายงานผลการตรวจสอบ</th>
  <th width='18%'>ฝ่าย</th>
  <th width='18%'>กลุ่ม</th>
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
		d.csa_year = '$row2[csa_year]' AND
		d.department_id3 = '$department_id3' AND
		d.mark_del = '0' 
	ORDER BY 
		audit_date";
	$result1=mysqli_query($connect, $sql2);
	while ($row1 = mysqli_fetch_array($result1)) {
?>
<tr class='tr_sm'>
	<td width='3%' did='<?=$row1['csa_audit_id']?>' class='csa_editable'><?=$i++?></td>
	<td width='15' did='<?=$row1['csa_audit_id']?>' class='csa_editable'><?=mysqldate2th_date($row1['audit_date'])?></td>
	<td width='45' did='<?=$row1['csa_audit_id']?>' class='csa_editable'><?=$row1['audit_desc']?></td>
	<td width='18%' did='<?=$row1['csa_audit_id']?>' class='csa_editable'><?=$row1['dep2']?></td>
	<td width='18%' did='<?=$row1['csa_audit_id']?>' class='csa_editable'><?=$row1['dep3']?></td>
</tr>
<?
	}	
?>
</tbody>
</table>	
			<br>
			<button type='button' class='btn btn-default' onClick='document.location="csa_audit.php?depid=<?=$department_id3?>&view_year=<?=$row2['csa_year']?>"'>ไปที่หน้าแก้ไขข้อมูล รายงานผลการตรวจสอบ</button>
			<br>
			<br>
			<br>
<b>Job Function</b><br>
<table class='table table-hover'>
<thead>
<tr>
  <th width='5%'>No.</th>
  <th width='5%'>รหัส</th>
  <th width='45%'>Job Function</th>
  <th width='21%'>ฝ่าย</th>
  <th width='21%'>กลุ่ม</th>
  <th width='3%'>REQ</th>
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
	FROM `job_function` d
	LEFT JOIN `department` d1 ON  d.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  d.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  d.department_id3 = d3.department_id AND d3.mark_del = '0'
	WHERE 
		d.department_id3 = '$department_id3' AND
		d.mark_del = '0' 
	ORDER BY 
		job_function_no, job_function_name";
	$result1=mysqli_query($connect, $sql2);
	while ($row1 = mysqli_fetch_array($result1)) {
?>
<tr class='tr_sm'>
	<td width='3%' class='csa_editable'><?=$i++?></td>
	<td width='3%' class='csa_editable'><?=$row1['job_function_no']?></td>
	<td width='45' class='csa_editable'><?=$row1['job_function_name']?></td>
	<td width='21%' class='csa_editable'><?=$row1['dep2']?></td>
	<td width='21%' class='csa_editable'><?=$row1['dep3']?></td>
	<td width='3%' class='csa_editable'><?=($row1['is_require']==1) ? '<i class="fa fa-check"></i>' : ''?></td>
</tr>
<?
	}	
?>
</tbody>
</table>			
			<br>
			<button type='button' class='btn btn-default' onClick='document.location="job_function.php?depid=<?=$department_id3?>&view_year=<?=$row2['csa_year']?>"'>ไปที่หน้าแก้ไขข้อมูล Job Function</button>
			<br>
			<br>
			<br>

		</div>
		
		<div class="tab-pane " id="tab6">	
			<br>
	
		<b>ปลดล็อค/ถอยสถานะ</b><br>
		<div class='row'>
		<div class='col-md-4'>		
			<div class="form-group">
			  <label>ไปยังสถานะ</label>
			  <select name='csa_department_status_id' id='csa_department_status_id' class="form-control" required <?=$lock_tag?>>
<?
$sql="SELECT * FROM csa_department_status ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['csa_department_status_id']?>' <?if ($row2['csa_department_status_id']==$row1['csa_department_status_id']) echo 'selected'?>><?=$row1['csa_department_status_name']?></option>
<?
}
?>			  
			  </select>
			</div>		
		</div>
		</div>
		<input type='hidden' name='csa_department_status_id_old' value='<?=$row2['csa_department_status_id']?>'>	
		<button type='submit' name='submit' value='reverse' class="btn btn-danger" onclick='return confirm("กรุณายืนยันว่าต้องการสถานะใช่หรือไม่");'><i class='fa fa-history'></i> ปลดล็อค ถอยสถานะ</button>	
<br>
<br>
<br>
<br>

			<br>
		</div>		
		
		<div class="tab-pane " id="tab7">	

			<br>
			<br>
		</div>
		
		
</div>
</form>
		  
<?
		}
	}
	
	echo template_footer();
	exit;

}





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
</div>
<br>



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
.rb {
	background-color: #eeeeee;
}
</style>	

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
					<button type="submit" class="btn btn-default" onClick="document.location='csa_admin.php?action=setting&view_year=<?=$view_year?>'"><i class='fa fa-gear'></i> เปิด/ปิด</button>
					<button type="submit" class="btn btn-danger" onClick="document.location='csa_admin.php?action=add&view_year=<?=$view_year?>'"><i class='fa fa-plus-circle'></i> เพิ่มฝ่าย</button>
				</div>
			</div>

			<br><b>อยู่ระหว่างดำเนินการ</b><br>
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%' rowspan='2'>ลำดับ</td>
				<td width='20%' rowspan='2'>สายงาน</td>
				<td width='20%' rowspan='2'>ฝ่าย</td>
				<td width='20%' rowspan='2'>กลุ่ม</td>
				<td width='6%' colspan='2' align='center'>ส่วนที่ 1</td>
				<td width='12%' colspan='4' align='center'>ส่วนที่ 2</td>
				<td width='18%' rowspan='2'>วันที่สร้าง</td>
			</tr>
			<tr>
				<td width='3%' class='rb'>1E</td>
				<td width='3%'>1R</td>
				<td width='3%' class='rb'>2.1E</td>
				<td width='3%'>2.1R</td>
				<td width='3%' class='rb'>2.2E</td>
				<td width='3%'>2.2R</td>
			</tr>	
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 
		c.*,
		d1.department_id AS dep_id1,
		d1.department_no AS dep_no1,
		d1.department_name AS dep_name1,
		d2.department_id AS dep_id2,
		d2.department_no AS dep_no2,
		d2.department_name AS dep_name2,
		d3.department_id AS dep_id3,
		d3.department_no AS dep_no3,
		d3.department_name AS dep_name3,
		st.csa_department_status_color,
		st.csa_department_status_name
	FROM csa_department c
	LEFT JOIN `department` d1 ON  c.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  c.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  c.department_id3 = d3.department_id AND d3.mark_del = '0'
	LEFT JOIN csa_department_status st ON st.csa_department_status_id = c.csa_department_status_id
	WHERE 
		c.csa_year = '$view_year' AND 
		c.mark_del = '0' AND
		is_enable = '1' AND
		c.csa_department_status_id < 2
	ORDER BY
		dep_no1,
		dep_name1,
		dep_no2,
		dep_name2,
		dep_no3,
		dep_name3";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr class='tr_sm' onClick='document.location="csa_admin.php?edit_id=<?=$row2['csa_department_id']?>&edit_year=<?= $view_year ?>"' style='cursor: pointer'>
				<td><?=$i++?></td>
				<td><?=$row2['dep_name1']?></td>
				<td><?=$row2['dep_name2']?></td>
				<td><?=$row2['dep_name3']?></td>
				<td class='rb'><?=$row2['is_enable_part1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_part1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period2']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period2_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=mysqldate2th_datetime($row2['create_date'])?></td>
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='11'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
	}
?>
			</tbody>
			</table>			
			
			<br>
			<br>
			<b>อนุมัติรายการ</b><br>
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%' rowspan='2'>ลำดับ</td>
				<td width='20%' rowspan='2'>สายงาน</td>
				<td width='20%' rowspan='2'>ฝ่าย</td>
				<td width='20%' rowspan='2'>กลุ่ม</td>
				<td width='6%' colspan='2' align='center'>ส่วนที่ 1</td>
				<td width='12%' colspan='4' align='center'>ส่วนที่ 2</td>
				<td width='18%' rowspan='2'>สถานะ</td>
			</tr>
			<tr>
				<td width='3%' class='rb'>1E</td>
				<td width='3%'>1R</td>
				<td width='3%' class='rb'>2.1E</td>
				<td width='3%'>2.1R</td>
				<td width='3%' class='rb'>2.2E</td>
				<td width='3%'>2.2R</td>
			</tr>	
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 
		c.*,
		d1.department_id AS dep_id1,
		d1.department_no AS dep_no1,
		d1.department_name AS dep_name1,
		d2.department_id AS dep_id2,
		d2.department_no AS dep_no2,
		d2.department_name AS dep_name2,
		d3.department_id AS dep_id3,
		d3.department_no AS dep_no3,
		d3.department_name AS dep_name3,
		st.csa_department_status_color,
		st.csa_department_status_name
	FROM csa_department c
	LEFT JOIN `department` d1 ON  c.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  c.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  c.department_id3 = d3.department_id AND d3.mark_del = '0'
	LEFT JOIN csa_department_status st ON st.csa_department_status_id = c.csa_department_status_id
	WHERE 
		c.csa_year = '$view_year' AND 
		c.mark_del = '0' AND
		is_enable = '1' AND
		c.csa_department_status_id = 2
	ORDER BY
		dep_no1,
		dep_name1,
		dep_no2,
		dep_name2,
		dep_no3,
		dep_name3";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr class='tr_sm' onClick='document.location="csa_admin.php?edit_id=<?=$row2['csa_department_id']?>&edit_year=<?= $view_year ?>"' style='cursor: pointer'>
				<td><?=$i++?></td>
				<td><?=$row2['dep_name1']?></td>
				<td><?=$row2['dep_name2']?></td>
				<td><?=$row2['dep_name3']?></td>
				<td class='rb'><?=$row2['is_enable_part1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_part1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period2']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period2_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><font color='<?=$row2['csa_department_status_color']?>'><?=$row2['csa_department_status_name']?></font></td>
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='11'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
	}
?>
			</tbody>
			</table>			
				
				
			<br>
			<br>
			<b>ดำเนินการแล้วเสร็จ</b><br>
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%' rowspan='2'>ลำดับ</td>
				<td width='20%' rowspan='2'>สายงาน</td>
				<td width='20%' rowspan='2'>ฝ่าย</td>
				<td width='20%' rowspan='2'>กลุ่ม</td>
				<td width='6%' colspan='2' align='center'>ส่วนที่ 1</td>
				<td width='12%' colspan='4' align='center'>ส่วนที่ 2</td>
				<td width='18%' rowspan='2'>สถานะ</td>
			</tr>
			<tr>
				<td width='3%' class='rb'>1E</td>
				<td width='3%'>1R</td>
				<td width='3%' class='rb'>2.1E</td>
				<td width='3%'>2.1R</td>
				<td width='3%' class='rb'>2.2E</td>
				<td width='3%'>2.2R</td>
			</tr>	
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 
		c.*,
		d1.department_id AS dep_id1,
		d1.department_no AS dep_no1,
		d1.department_name AS dep_name1,
		d2.department_id AS dep_id2,
		d2.department_no AS dep_no2,
		d2.department_name AS dep_name2,
		d3.department_id AS dep_id3,
		d3.department_no AS dep_no3,
		d3.department_name AS dep_name3,
		st.csa_department_status_color,
		st.csa_department_status_name
	FROM csa_department c
	LEFT JOIN `department` d1 ON  c.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  c.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  c.department_id3 = d3.department_id AND d3.mark_del = '0'
	LEFT JOIN csa_department_status st ON st.csa_department_status_id = c.csa_department_status_id
	WHERE 
		c.csa_year = '$view_year' AND 
		c.mark_del = '0' AND
		is_enable = '1' AND
		c.csa_department_status_id = 3
	ORDER BY
		dep_no1,
		dep_name1,
		dep_no2,
		dep_name2,
		dep_no3,
		dep_name3";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr class='tr_sm' onClick='document.location="csa_admin.php?edit_id=<?=$row2['csa_department_id']?>&edit_year=<?= $view_year ?>"' style='cursor: pointer'>
				<td><?=$i++?></td>
				<td><?=$row2['dep_name1']?></td>
				<td><?=$row2['dep_name2']?></td>
				<td><?=$row2['dep_name3']?></td>
				<td class='rb'><?=$row2['is_enable_part1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_part1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period2']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period2_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><font color='<?=$row2['csa_department_status_color']?>'><?=$row2['csa_department_status_name']?></font></td>
			</tr>
<?
		}
	} else {		
?>			
			<tr>
				<td colspan='11'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
	}
?>
			</tbody>
			</table>			
	
	
			<br>
			<br>
			<b>ปิดใช้งาน</b><br>
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='4%' rowspan='2'>ลำดับ</td>
				<td width='20%' rowspan='2'>สายงาน</td>
				<td width='20%' rowspan='2'>ฝ่าย</td>
				<td width='20%' rowspan='2'>กลุ่ม</td>
				<td width='6%' colspan='2' align='center'>ส่วนที่ 1</td>
				<td width='12%' colspan='4' align='center'>ส่วนที่ 2</td>
				<td width='18%' rowspan='2'>สถานะ</td>
			</tr>
			<tr>
				<td width='3%' class='rb'>1E</td>
				<td width='3%'>1R</td>
				<td width='3%' class='rb'>2.1E</td>
				<td width='3%'>2.1R</td>
				<td width='3%' class='rb'>2.2E</td>
				<td width='3%'>2.2R</td>
			</tr>	
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 
		c.*,
		d1.department_id AS dep_id1,
		d1.department_no AS dep_no1,
		d1.department_name AS dep_name1,
		d2.department_id AS dep_id2,
		d2.department_no AS dep_no2,
		d2.department_name AS dep_name2,
		d3.department_id AS dep_id3,
		d3.department_no AS dep_no3,
		d3.department_name AS dep_name3,
		st.csa_department_status_color,
		st.csa_department_status_name
	FROM csa_department c
	LEFT JOIN `department` d1 ON  c.department_id = d1.department_id AND d1.mark_del = '0'
	LEFT JOIN `department` d2 ON  c.department_id2 = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  c.department_id3 = d3.department_id AND d3.mark_del = '0'
	LEFT JOIN csa_department_status st ON st.csa_department_status_id = c.csa_department_status_id
	WHERE 
		c.csa_year = '$view_year' AND 
		c.mark_del = '0' AND
		is_enable = '0'
	ORDER BY
		dep_no1,
		dep_name1,
		dep_no2,
		dep_name2,
		dep_no3,
		dep_name3";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr class='tr_sm' onClick='document.location="csa_admin.php?edit_id=<?=$row2['csa_department_id']?>&edit_year=<?= $view_year ?>"' style='cursor: pointer'>
				<td><?=$i++?></td>
				<td><?=$row2['dep_name1']?></td>
				<td><?=$row2['dep_name2']?></td>
				<td><?=$row2['dep_name3']?></td>
				<td class='rb'><?=$row2['is_enable_part1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_part1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period1']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period1_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td class='rb'><?=$row2['is_enable_period2']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><?=$row2['is_enable_period2_require']==1 ? '<font color="green"><i class="fa fa-check-circle"></i></font>' : '<font color="red"><i class="fa fa-times"></i></font>'?></td>
				<td><font color='<?=$row2['csa_department_status_color']?>'><?=$row2['csa_department_status_name']?></font></td>
			</tr>
<?
		}
	} else {		
?>			
	<tr>
		<td colspan='11'>-ยังไม่มีข้อมูล-</td>
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