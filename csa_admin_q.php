<?
include('inc/include.inc.php');
include('csa_function.php');

echo template_header();

echo "<label style='color:gray; font-size:10px;' class='pull-right'>csa</label>";

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
$copy_to = intval($_GET['copy_to']);
$update_id = intval($_POST['update_id']);
$submit = $_POST['submit'];
$action = $_GET['action'];


if ($submit=='update') {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$q_no = addslashes($_POST['q_no']);	
	$q_name = addslashes($_POST['q_name']);	
	$q_help = addslashes($_POST['q_help']);	
	if ($q_no!='' && $q_name!='') {
		$sql = "UPDATE csa_questionnaire_topic SET q_no='$q_no', q_name='$q_name', q_help='$q_help' WHERE csa_q_topic_id = '$update_id' ";
		
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
	
		if ($qx) {
			mysqli_commit($connect);
			echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
		} else {
			mysqli_rollback($connect);
			echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
		}		
	}
	
} else if ($submit=='add_topic') {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$q_year = intval($_POST['q_year']);	
	$add_q_no = addslashes($_POST['add_q_no']);	
	$add_q_name = addslashes($_POST['add_q_name']);	
	$add_q_help = addslashes($_POST['add_q_help']);	
	if ($q_year>0 && $add_q_no!='' && $add_q_name!='') {
		$sql = "INSERT INTO csa_questionnaire_topic (q_year, q_no, q_name, q_help, parent_id, create_date)
				VALUES ('$q_year', '$add_q_no', '$add_q_name', '$add_q_helpe', 0, now())";
		
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
	}
	
} else if ($submit=='add_subtopic') {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$add_parent_id = intval($_POST['add_parent_id']);	
	$add_q_year = intval($_POST['add_q_year']);	
	$add_q_no = addslashes($_POST['add_q_no']);	
	$add_q_name = addslashes($_POST['add_q_name']);	
	$add_q_help = addslashes($_POST['add_q_help']);	
	if ($add_parent_id>0 && $add_q_year>0 && $add_q_no!='' && $add_q_name!='') {
		$sql = "INSERT INTO csa_questionnaire_topic (q_year, q_no, q_name, q_help, parent_id, create_date)
				VALUES ('$add_q_year', '$add_q_no', '$add_q_name', '$add_q_help', '$add_parent_id', now())";
		
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
	}
	
} else if (($add_id>0)) {

	$sql2="SELECT * FROM `csa_questionnaire_topic` WHERE csa_q_topic_id = '$add_id' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	
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
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='csa_admin_q.php?view_year=<?=$view_year?>'>  

	<div class="form-group">
	  <label>ลำดับ</label>
		<div class="row">
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="ลำดับ." name='add_q_no' value=''></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>คำถาม</label>
		<div class="row">
			<div class="col-xs-4"><textarea class="form-control" placeholder="คำถาม" name='add_q_name' rows='3'></textarea></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>คำอธิบายขยายความ</label>
		<div class="row">
			<div class="col-xs-4"><textarea class="form-control" placeholder="คำอธิบายขยายความ" name='add_q_help' rows='6'></textarea></div>
		</div>		
	</div>	
	<br>
	<br>
	<input type='hidden' name='add_q_year' value='<?=$view_year?>'>
	<input type='hidden' name='add_parent_id' value='<?=$add_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='add_subtopic' class="btn btn-success"><i class='fa fa-plus-circle'></i> ยืนยันการเพิ่มหัวข้อย่อย</button>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type='submit' name='submit' value='delete' class='btn btn-danger' onClick='return confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")' id='confirm_btn'><i class='fa fa-times'></i> ลบ</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
	}	

	echo template_footer();
	exit;
	
} else if (($edit_id>0)) {

	$sql2="SELECT * FROM `csa_questionnaire_topic` WHERE csa_q_topic_id = '$edit_id' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	
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
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='csa_admin_q.php?view_year=<?=$view_year?>'>  

	<div class="form-group">
	  <label>ลำดับ</label>
		<div class="row">
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="ลำดับ." name='q_no' value='<?=$row2['q_no']?>'></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>คำถาม</label>
		<div class="row">
			<div class="col-xs-4"><textarea class="form-control" placeholder="คำถาม" name='q_name' rows='3'><?=$row2['q_name']?></textarea></div>
		</div>		
	</div>		
	<div class="form-group">
	  <label>คำอธิบายขยายความ</label>
		<div class="row">
			<div class="col-xs-4"><textarea class="form-control" placeholder="คำอธิบายขยายความ" name='q_help' rows='6'><?=$row2['q_help']?></textarea></div>
		</div>		
	</div>	
	<br>
	<br>
	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='update' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type='submit' name='submit' value='delete' class='btn btn-danger' onClick='return confirm("โปรดยืนยันว่าคุณแน่ใจที่จะลบ")' id='confirm_btn'><i class='fa fa-times'></i> ลบ</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
	}	

	echo template_footer();
	exit;
	
} else if (($copy_to>0)) {

	 {	
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					
					<span class="caption-subject font-green sbold uppercase">คัดลอกจากปีอื่น มายังปี <?=$copy_to?></span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php?view_year=<?= $copy_to ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

			<table class='table table-hover table-light'>
			<thead>
			<tr>
				<td width='5%'></td>
				<td width='5%'>ปี</td>
				<td width='90%'>จำนวนหัวข้อ</td>
			</tr>
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT COUNT(*) AS num, q_year FROM `csa_questionnaire_topic` WHERE q_year <> '$copy_to'
GROUP BY q_year 
ORDER BY q_year";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
	<tr>
		<td><input type='radio' name='copy_from_year' value='<?=$row2['q_year']?>'></td>
		<td><?=$row2['q_year']?></td>
		<td><?=$row2['num']?></td>
		</td>
	</tr>
<?
		}
	} else {		
?>			
	<tr>
		<td colspan='2'>-ยังไม่มีข้อมูล-</td>
	</tr>
<?
	}
?>
	</tbody>
	</table>	
	
	
	<br>
	<br>
	<input type='hidden' name='copy_to_year' value='<?=$copy_to?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_q.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='confirm_copy' class="btn btn-success"><i class='fa fa-save'></i> คัดลอก</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
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
		document.location="csa_admin_q.php?view_year=<?=$view_year?>&edit_id="+id;
	});	
	
});  
</script>

<style>
.csa_qtopic {
	cursor: pointer
}
</style>

<div class="row">
	<div class="col-md-8">
		<table>
			<tr>
				<td>แสดงข้อมูล ของปี</td><td width='15'></td>
				<td>
					<select name='view_year' class="form-control" onChange='document.location="csa_admin_q.php?view_year="+this.value'>
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
					<button type="button" class="btn btn-primary" onClick="document.location='csa_admin_q.php?copy_to=<?=$view_year?>'"><i class='fa fa-copy'></i> คัดลอกจากปีอื่น</button>
					<button type="button" class="btn btn-primary" onClick="$('#add_row').toggle()"><i class='fa fa-plus-circle'></i> เพิ่มหัวข้อ</button>
				</div>
			</div>

			<form method='post' action='csa_admin_q.php'> 
			<input type='hidden' name='q_year' value='<?=$view_year?>'>
			<table class='table table-hover table-light'>
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
	$sql = "SELECT 		*	FROM csa_questionnaire_topic	WHERE 		q_year = '$view_year' AND 		parent_id = '0' AND
		mark_del = '0' 	ORDER BY		q_no, q_name ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr style='font-weight: bold' bgcolor='#eeeeee'>
				<td qid='<?=$row2['csa_q_topic_id']?>' class='csa_qtopic'><?=$row2['q_no']?></td>				<td qid='<?=$row2['csa_q_topic_id']?>' class='csa_qtopic'><?=$row2['q_name']?></td>
				<td><button type='button' class="btn btn-default btn-xs" onClick='document.location="csa_admin_q.php?view_year=<?=$view_year?>&add_id=<?=$row2['csa_q_topic_id']?>"'><i class='fa fa-plus'></i> เพิ่ม</button></td>
			</tr>
<?
			$sql = "SELECT 
				*
			FROM csa_questionnaire_topic
			WHERE 
				q_year = '$view_year' AND 
				parent_id = '$row2[csa_q_topic_id]' AND
				mark_del = '0' 
			ORDER BY
				q_no, q_name ";
			$result3 = mysqli_query($connect, $sql);
			while ($row3 = mysqli_fetch_array($result3)) {
?>
			<tr>
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