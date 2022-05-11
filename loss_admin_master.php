<?
include('inc/include.inc.php');
include('csa_function.php');

echo template_header();

echo "<label style='color:gray; font-size:10px;' class='pull-right'>loss</label>";



$view_year = intval($_GET['view_year']);
$edit_id = intval($_GET['edit_id']);
$add_id = intval($_GET['add_id']);
$update_id = intval($_POST['update_id']);
$submit = $_POST['submit'];
$action = $_GET['action'];


if ($submit=='update') {
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	
	$factor_no = addslashes($_POST['factor_no']);	
	$factor = addslashes($_POST['factor']);	
	if ($factor_no!='' && $factor!='') {
		$sql = "UPDATE loss_factor SET factor_no='$factor_no', factor='$factor' WHERE loss_factor_id = '$update_id' ";
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
	
	$add_factor_no = addslashes($_POST['add_factor_no']);	
	$add_factor = addslashes($_POST['add_factor']);	
	if ($add_factor_no!='' && $add_factor!='') {
		$sql = "INSERT INTO loss_factor (factor_no, factor, parent_id, is_leaf_node, create_date)
				VALUES ('$add_factor_no', '$add_factor', 0, '0', now())";
		
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
	$add_factor_no = addslashes($_POST['add_factor_no']);	
	$add_factor = addslashes($_POST['add_factor']);	
	if ($add_parent_id>0 && $add_factor_no!='' && $add_factor!='') {
		$sql = "INSERT INTO loss_factor (factor_no, factor, parent_id, is_leaf_node, create_date)
				VALUES ('$add_factor_no', '$add_factor', '$add_parent_id', '1', now())";
		
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

	$sql2="SELECT * FROM `loss_factor` WHERE loss_factor_id = '$add_id' ";
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
					<button type='button' class="btn btn-primary" onClick="document.location='loss_admin_master.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='loss_admin_master.php'>  

	<div class="form-group">
	  <label>ลำดับ</label>
		<div class="row">
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="ลำดับ." name='add_factor_no' value=''></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>รายการ</label>
		<div class="row">
			<div class="col-xs-4"><textarea class="form-control" placeholder="รายการ" name='add_factor' rows='4'></textarea></div>
		</div>		
	</div>	
	<br>
	<br>
	<input type='hidden' name='add_parent_id' value='<?=$add_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='loss_admin_master.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
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

	$sql2="SELECT * FROM `loss_factor` WHERE loss_factor_id = '$edit_id' ";
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
					<button type='button' class="btn btn-primary" onClick="document.location='loss_admin_master.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='loss_admin_master.php'>  

	<div class="form-group">
	  <label>ลำดับ</label>
		<div class="row">
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="ลำดับ." name='factor_no' value='<?=$row2['factor_no']?>'></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>รายการ</label>
		<div class="row">
			<div class="col-xs-4"><textarea class="form-control" placeholder="รายการ" name='factor' rows='4'><?=$row2['factor']?></textarea></div>
		</div>		
	</div>	
	<br>
	<br>
	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='loss_admin_master.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
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
	
} 


?>	

<script language='JavaScript'>
$(function () {

	$('.loss_qtopic').on('click', function() {
		var id = $(this).attr('qid');
		document.location="loss_admin_master.php?&edit_id="+id;
	});	
	
});  
</script>

<style>
.loss_qtopic {
	cursor: pointer
}
</style>


<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-bond font-green"></i>
					<span class="caption-subject font-green sbold uppercase">ข้อมูลชุด</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type="button" class="btn btn-primary" onClick="$('#add_row').toggle()"><i class='fa fa-plus-circle'></i> เพิ่มหัวข้อ</button>
				</div>
			</div>

			<form method='post' action='loss_admin_master.php'> 
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
	$sql = "SELECT 		*	FROM loss_factor	WHERE 		parent_id = '0' AND
		mark_del = '0' 	ORDER BY		factor_no, factor ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr style='font-weight: bold' bgcolor='#eeeeee'>
				<td qid='<?=$row2['loss_factor_id']?>' class='loss_qtopic'><?=$row2['factor_no']?></td>				<td qid='<?=$row2['loss_factor_id']?>' class='loss_qtopic'><?=$row2['factor']?></td>
				<td><button type='button' class="btn btn-default btn-xs" onClick='document.location="loss_admin_master.php?&add_id=<?=$row2['loss_factor_id']?>"'><i class='fa fa-plus'></i> เพิ่ม</button></td>
			</tr>
<?
			$sql = "SELECT 
				*
			FROM loss_factor
			WHERE 
				parent_id = '$row2[loss_factor_id]' AND
				mark_del = '0' 
			ORDER BY
				factor_no, factor ";
			$result3 = mysqli_query($connect, $sql);
			while ($row3 = mysqli_fetch_array($result3)) {
?>
			<tr>
				<td qid='<?=$row3['loss_factor_id']?>' class='loss_qtopic'><?=$row3['factor_no']?></td>
				<td qid='<?=$row3['loss_factor_id']?>' class='loss_qtopic'><?=$row3['factor']?></td>
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
				<td>เพิ่ม</td>
				<td><input type='text' class="form-control input-sm" name='add_factor_no'></td>
				<td><input type='text' class="form-control input-sm" name='add_factor'></td>
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