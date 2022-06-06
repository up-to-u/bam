<?
include('inc/include.inc.php');
include('csa_function.php');
echo template_header();

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

$view_year = intval($_GET['view_year']);
$edit_id = intval($_GET['edit_id']);
$add_id = intval($_GET['add_id']);
$del_id = intval($_GET['del_id']);
$update_id = intval($_POST['update_id']);
$submit = $_POST['submit'];
$action = $_GET['action'];


if ($submit=='update') {
	
	$q_no = ($_POST['q_no']);	
	$q_name = ($_POST['q_name']);	
	$q_help = ($_POST['q_help']);	
	if ($q_no!='' && $q_name!='') {
		$qx = true;	
		mysqli_autocommit($connect,FALSE);
		
		$sql = "UPDATE `csa_questionnaire_topic` SET 
			q_no=?, 
			q_name=?, 
			q_help=?
		WHERE
			csa_q_topic_id = ? ";
		
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
		
			$stmt->bind_param('sssi', $q_no, $q_name, $q_help, $update_id);
			$q = $stmt->execute();
			$qx = ($qx and $q);	
		
			if ($qx) {
				mysqli_commit($connect);
				echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
				savelog('CSA-ADMIN-RISK-QTOPIC-UPDATE|csa_q_topic_id|'.$update_id.'|');
			} else {
				mysqli_rollback($connect);
				echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}		
		}
	}
	
} else if ($submit=='add_topic') {
	$add_q_no = ($_POST['add_q_no']);	
	$add_q_name = ($_POST['add_q_name']);	
	$add_q_help = ($_POST['add_q_help']);	
	if ($add_q_no!='' && $add_q_name!='') {
		$sql = "INSERT INTO csa_questionnaire_topic (q_no, q_name, q_help, parent_id, create_date)
				VALUES (?,?,?,0,now())";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$qx = true;	
			mysqli_autocommit($connect,FALSE);
			
			$stmt->bind_param('sss', $add_q_no, $add_q_name, $add_q_help);
			$q = $stmt->execute();
			$qx = ($qx and $q);	
			$insert_id = mysqli_insert_id($connect);
		
			if ($qx) {
				mysqli_commit($connect);
				echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
				savelog('CSA-ADMIN-RISK-QTOPIC-ADD|csa_q_topic_id|'.$insert_id.'|');
			} else {
				mysqli_rollback($connect);
				echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}		
		}
	}
	
} else if ($submit=='add_subtopic') {
	$add_parent_id = intval($_POST['add_parent_id']);	
	$add_q_no = ($_POST['add_q_no']);	
	$add_q_name = ($_POST['add_q_name']);	
	$add_q_help = ($_POST['add_q_help']);	
	if ($add_parent_id>0 && $add_q_no!='' && $add_q_name!='') {
		$sql = "INSERT INTO csa_questionnaire_topic (q_no, q_name, q_help, parent_id, create_date)
				VALUES (?,?,?,?,now())";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$qx = true;	
			mysqli_autocommit($connect,FALSE);
			
			$stmt->bind_param('sssi', $add_q_no, $add_q_name, $add_q_help, $add_parent_id);
			$q = $stmt->execute();
			$qx = ($qx and $q);	
			
			$insert_id = mysqli_insert_id($connect);
		
			if ($qx) {
				mysqli_commit($connect);
				echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
				savelog('CSA-ADMIN-RISK-QTOPIC-ADD|csa_q_topic_id|'.$insert_id.'|');
			} else {
				mysqli_rollback($connect);
				echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
			}		
		}
	}
	
} else if ($submit=='delete') {
	
	if ($update_id>0) {
		$qx = true;	
		mysqli_autocommit($connect,FALSE);
		
		$sql = "UPDATE csa_questionnaire_topic SET `mark_del` = 1 WHERE csa_q_topic_id = ? ";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$stmt->bind_param('i', $update_id);
			$stmt->execute();
			$q = $stmt->execute();
			$qx = ($qx and $q);	
			
			if ($qx) {
				mysqli_commit($connect);		
				echo '<div class="container"><b><div class="alert alert-success">ระบบได้ลบข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
				savelog('CSA-ADMIN-RISK-QTOPIC-DEL|csa_q_topic_id|'.$update_id.'|');
			} else {
				mysqli_rollback($connect);			
				echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลได้</div></b><br></div>';
			}
		}
	}
	
} else if (($add_id>0)) {
	
	$sql="SELECT * FROM `csa_questionnaire_topic` WHERE csa_q_topic_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $add_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-user font-green"></i>
					<span class="caption-subject font-green sbold uppercase">เพิ่ม หัวข้อย่อย</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='csa_admin_q.php'>  

	<div class="form-group">
	  <label>ลำดับ</label>
		<div class="row">
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="ลำดับ." name='add_q_no' value=''></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>คำถาม</label>
		<div class="row">
			<div class="col-md-8"><textarea class="form-control" placeholder="คำถาม" name='add_q_name' rows='5'></textarea></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>คำอธิบายขยายความ</label>
		<div class="row">
			<div class="col-md-8"><textarea class="form-control" placeholder="คำอธิบายขยายความ" name='add_q_help' rows='12'></textarea></div>
		</div>		
	</div>	
	<br>
	<br>
	<input type='hidden' name='add_parent_id' value='<?=$add_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='add_subtopic' class="btn btn-success"><i class='fa fa-plus-circle'></i> ยืนยันการเพิ่มหัวข้อย่อย</button>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type='submit' name='submit' value='delete' class='btn btn-danger' onClick='return confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")' id='confirm_btn'><i class='fa fa-times'></i> ลบ</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
		}
	}	

	echo template_footer();
	exit;
	
} else if (($edit_id>0)) {

	$sql="SELECT * FROM `csa_questionnaire_topic` WHERE csa_q_topic_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $edit_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {		
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-user font-green"></i>
					<span class="caption-subject font-green sbold uppercase">แก้ไขหัวข้อ</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='csa_admin_q.php'>  

	<div class="form-group">
	  <label>ลำดับ</label>
		<div class="row">
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="ลำดับ." name='q_no' value='<?=$row2['q_no']?>'></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>คำถาม</label>
		<div class="row">
			<div class="col-md-8"><textarea class="form-control" placeholder="คำถาม" name='q_name' rows='5'><?=$row2['q_name']?></textarea></div>
		</div>		
	</div>		
	<div class="form-group">
	  <label>คำอธิบายขยายความ</label>
		<div class="row">
			<div class="col-md-8"><textarea class="form-control" placeholder="คำอธิบายขยายความ" name='q_help' rows='12'><?=$row2['q_help']?></textarea></div>
		</div>		
	</div>	
	<br>
	<br>
	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='update' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type='submit' name='submit' value='delete' class='btn btn-danger' onClick='return confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")' id='confirm_btn'><i class='fa fa-times'></i> ลบ</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
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

<script language='JavaScript'>
$(function () {

	$('.csa_qtopic').on('click', function() {
		var id = $(this).attr('qid');
		document.location="csa_admin_q.php?edit_id="+id;
	});	
	
});  
</script>

<style>
.csa_qtopic {
	cursor: pointer
}
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
</style>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">แบบสอบถาม</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type="button" class="btn btn-primary" onClick="$('#add_row').toggle()"><i class='fa fa-plus-circle'></i> เพิ่มหัวข้อ</button>
				</div>
			</div>

			<form method='post' action='csa_admin_q.php'> 
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='10%'>ข้อ</td>
				<td width='80%'>รายการ</td>
				<td width='10%'></td>
			</tr>
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 		*	FROM csa_questionnaire_topic	WHERE 		parent_id = '0' AND
		mark_del = '0' 	ORDER BY		q_no, q_name ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr style='font-weight: bold' bgcolor='#eeeeee'>
				<td qid='<?=$row2['csa_q_topic_id']?>' class='csa_qtopic'><?=$row2['q_no']?></td>				<td qid='<?=$row2['csa_q_topic_id']?>' class='csa_qtopic'><?=$row2['q_name']?></td>
				<td><button type='button' class="btn btn-default btn-xs" onClick='document.location="csa_admin_q.php?add_id=<?=$row2['csa_q_topic_id']?>"'><i class='fa fa-plus'></i> เพิ่ม</button></td>
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
?>
			<tr class='tr_sm'>
				<td qid='<?=$row3['csa_q_topic_id']?>' class='csa_qtopic'><?=$row3['q_no']?></td>
				<td qid='<?=$row3['csa_q_topic_id']?>' class='csa_qtopic'><?=$row3['q_name']?></td>
				<td></td>
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
			<tr id='add_row' style='display:none'>
				<td>
					เพิ่ม<br>
					<input type='text' class="form-control input-sm" name='add_q_no'>
				</td>
				<td><br>
					<input type='text' class="form-control input-sm" name='add_q_name'></td>
				<td></td>
				<td><button type="submit" name='submit' value='add_topic' class="btn btn-success btn-sm"><i class='fa fa-save'></i> บันทึก</button></td>
			</tr>
			</tbody>
			</table>
			</form>
	
	</div>
	<br>
	<br>
<?
echo template_footer();
?>