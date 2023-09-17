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
$update_id = intval($_POST['update_id']);
$submit = $_POST['submit'];
$action = $_GET['action'];


if ($submit=='delete') {
	
	if ($update_id>0) {
		$qx = true;	
		mysqli_autocommit($connect,FALSE);
		
		$sql = "UPDATE csa_factor SET mark_del='1' WHERE csa_factor_id = '$update_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
	
		if ($qx) {
			mysqli_commit($connect);
			echo "<font color='green'><b>ระบบได้ลบข้อมูลของท่านแล้ว</b></font><br><br>";
			savelog('CSA-ADMIN-RISK-FACTOR-DEL|csa_factor_id|'.$update_id.'|');
		} else {
			mysqli_rollback($connect);
			echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถลบข้อมูลของท่านได้</b></font><br><br>";
		}		
	}
	
} else if ($submit=='update') {
	$factor_no = addslashes($_POST['factor_no']);	
	$factor = addslashes($_POST['factor']);	
	$is_other = intval($_POST['is_other']);	
	$csa_risk_type_id = intval($_POST['csa_risk_type_id']);	
	
	if ($factor!='') {
		$qx = true;	
		mysqli_autocommit($connect,FALSE);
		
		$sql = "UPDATE csa_factor SET factor_no='$factor_no', factor='$factor', csa_risk_type_id='$csa_risk_type_id', is_other='$is_other' WHERE csa_factor_id = '$update_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
	
		if ($qx) {
			mysqli_commit($connect);
			echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
			savelog('CSA-ADMIN-RISK-FACTOR-UPDATE|csa_factor_id|'.$update_id.'|');
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
	$csa_risk_type_id = intval($_POST['csa_risk_type_id']);	
	$is_other = intval($_POST['is_other']);	
	if ($csa_risk_type_id>0 && $add_factor!='') {
		$sql = "INSERT INTO csa_factor (factor_no, factor, parent_id, csa_risk_type_id, is_leaf_node, is_other, create_date)
				VALUES ('$add_factor_no', '$add_factor', '0', '$csa_risk_type_id', '0', '$is_other', now())";
		
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		$insert_id = mysqli_insert_id($connect);
	
		if ($qx) {
			mysqli_commit($connect);
			echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
			savelog('CSA-ADMIN-RISK-FACTOR-ADD|csa_factor_id|'.$insert_id.'|');
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
	$is_other = intval($_POST['is_other']);	
	if ($add_parent_id>0 && $add_factor!='') {
		$sql = "INSERT INTO csa_factor (factor_no, factor, parent_id, is_leaf_node, is_other, create_date)
				VALUES ('$add_factor_no', '$add_factor', '$add_parent_id', '1', '$is_other', now())";
		
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		$insert_id = mysqli_insert_id($connect);
	
		if ($qx) {
			mysqli_commit($connect);
			echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font><br><br>";
			savelog('CSA-ADMIN-RISK-FACTOR-ADD|csa_factor_id|'.$insert_id.'|');
		} else {
			mysqli_rollback($connect);
			echo "<font color='red'><b>เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลของท่านได้</b></font><br><br>";
		}		
	}
	
} else if (($add_id>0)) {

	$sql2="SELECT * FROM `csa_factor` WHERE csa_factor_id = '$add_id' ";
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
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_factor.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='csa_admin_factor.php'>  

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
	<div class="form-group" id='d_lv3'>
	  <label>ตัวเลือก</label><br>
			<input type='checkbox' name='is_other' value='1' <?if ($row2['is_other']==1) echo 'checked'?>> แสดงตัวเลือกอื่นๆ<br>
	</div>		
	<br>
	<br>
	<input type='hidden' name='add_parent_id' value='<?=$add_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_factor.php?view_year=<?= $view_year ?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='add_subtopic' class="btn btn-success"><i class='fa fa-plus-circle'></i> ยืนยันการเพิ่มหัวข้อย่อย</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
	}	

	echo template_footer();
	exit;
	
} else if (($edit_id>0)) {

	$sql2="SELECT * FROM `csa_factor` WHERE csa_factor_id = '$edit_id' ";
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
					<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_factor.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='csa_admin_factor.php'>  

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
<? if ($row2['parent_id']==0) {?>	
	<div class="form-group">
	  <label>ประเภทความเสี่ยง</label>
		<div class="row">
			<div class="col-xs-4">	  
			  <select name='csa_risk_type_id' id='csa_risk_type_id' class="form-control">
				<option value='0'>--- เลือก ---</option>
<?

$sql="SELECT * FROM csa_risk_type WHERE mark_del = 0 ORDER BY risk_type_no, csa_risk_type_id";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
				<option value='<?=$row1['csa_risk_type_id']?>' <?if ($row1['csa_risk_type_id']==$row2['csa_risk_type_id']) echo 'selected'?>><?=$row1['risk_type_name']?></option>
<?
}
?>
				</select>
			</div>
		</div>
	</div>
<?}?>	
	<div class="form-group" id='d_lv3'>
	  <label>ตัวเลือก</label><br>
			<input type='checkbox' name='is_other' value='1' <?if ($row2['is_other']==1) echo 'checked'?>> แสดงตัวเลือกอื่นๆ<br>
	</div>	
	<br>
	<br>
	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='csa_admin_factor.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
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

	$('.csa_qtopic').on('click', function() {
		var id = $(this).attr('qid');
		document.location="csa_admin_factor.php?&edit_id="+id;
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
					<span class="caption-subject font-green sbold uppercase">ปัจจัยเสี่ยง</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type="button" class="btn btn-primary" onClick="$('#add_row').toggle()"><i class='fa fa-plus-circle'></i> เพิ่มหัวข้อ</button>
				</div>
			</div>

			<form method='post' action='csa_admin_factor.php'> 
			<input type='hidden' name='q_year' value='<?=$view_year?>'>
			<table class='table table-hover'>
			<thead>
			<tr>
				<td width='10%'>ข้อ</td>
				<td width='55%'>รายการ</td>
				<td width='25%'>ประเภทความเสี่ยง</td>
				<td width='10%'></td>
			</tr>
			</thead>
			<tbody>
<?
	$i=1;	
	$sql = "SELECT 		f.*,
		rt.risk_type_name	FROM csa_factor f
	JOIN csa_risk_type rt ON f.csa_risk_type_id = rt.csa_risk_type_id AND rt.mark_del = 0	WHERE 		f.parent_id = '0' AND
		f.mark_del = '0' 	ORDER BY		f.factor_no, f.csa_factor_id ";
	$result2 = mysqli_query($connect, $sql);
	if (mysqli_num_rows($result2)>0) {
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<tr style='font-weight: bold' bgcolor='#eeeeee'>
				<td qid='<?=$row2['csa_factor_id']?>' class='csa_qtopic'><?=$row2['factor_no']?></td>				<td qid='<?=$row2['csa_factor_id']?>' class='csa_qtopic'><?=$row2['factor']?></td>
				<td qid='<?=$row2['csa_factor_id']?>' class='csa_qtopic'><?=$row2['risk_type_name']?></td>
				<td><button type='button' class="btn btn-default btn-xs" onClick='document.location="csa_admin_factor.php?&add_id=<?=$row2['csa_factor_id']?>"'><i class='fa fa-plus'></i> เพิ่ม</button></td>
			</tr>
<?
			$sql = "SELECT 
				*
			FROM csa_factor
			WHERE 
				parent_id = '$row2[csa_factor_id]' AND
				mark_del = '0' 
			ORDER BY
				factor_no, csa_factor_id ";
			$result3 = mysqli_query($connect, $sql);
			while ($row3 = mysqli_fetch_array($result3)) {
?>
			<tr class='tr_sm'>
				<td qid='<?=$row3['csa_factor_id']?>' class='csa_qtopic'><?=$row3['factor_no']?></td>
				<td qid='<?=$row3['csa_factor_id']?>' class='csa_qtopic'><?=$row3['factor']?></td>
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
				<td>เพิ่ม<br><input type='text' class="form-control" name='add_factor_no'></td>
				<td><br><input type='text' class="form-control" name='add_factor'></td>
				<td><br>

			  <select name='csa_risk_type_id' id='csa_risk_type_id' class="form-control">
				<option value='0'>--- เลือก ---</option>
<?

$sql="SELECT * FROM csa_risk_type WHERE mark_del = 0 ORDER BY risk_type_no, csa_risk_type_id";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
				<option value='<?=$row1['csa_risk_type_id']?>' <?if ($row1['csa_risk_type_id']==$row2['csa_risk_type_id']) echo 'selected'?>><?=$row1['risk_type_name']?></option>
<?
}
?>
				</select>				
				
				</td>
				<td><br><button type="submit" name='submit' value='add_topic' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button></td>
			</tr>
			</tbody>
			</table>
			</form>
			<br>
			<button type="button" class="btn btn-primary" onClick="$('#add_row').toggle()"><i class='fa fa-plus-circle'></i> เพิ่มหัวข้อ</button>

	</div>
	<br>
	<br>
<?
echo template_footer();
?>