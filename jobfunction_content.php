<?
include('inc/include.inc.php');
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0');


$action = $_POST['action'];

if ($action=='d') {
	$parent = intval($_POST['parent']);
	$data = intval($_POST['data']);
	$lv = intval($_POST['lv']);
	
	if ($lv==1 && $parent>0) {
?>
	  <label>ฝ่าย</label>
	  <select name='department_id2' id='department_l2' class="form-control" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT * FROM department WHERE parent_id = '$parent' AND mark_del = '0' ORDER BY department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?></option>
<?
		}
?>
		</select>
<?	} else if ($lv==2 && $parent>0) {
?>
	  <label>กลุ่ม</label>
	  <select name='department_id3' id='department_l3' class="form-control" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT * FROM department WHERE parent_id = '$parent' AND mark_del = '0' ORDER BY department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?></option>
<?
		}
?>
		</select>
<?	} 
	exit;
	
} else if ($action=='jf_m') {
	$parent = intval($_POST['parent']);
	$data = intval($_POST['data']);
	$lv = intval($_POST['lv']);
	
	if ($lv==1 && $parent>0) {
?>
	  <label>ฝ่าย</label><br>
	  <select name='department_id2' id='department_l2' class="form-control" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT * FROM department WHERE parent_id = '$parent' AND mark_del = '0' ORDER BY department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?></option>
<?
		}
?>
		</select>
<?	} else if ($lv==2 && $parent>0) {
?>
	  <label>กลุ่ม</label><br>
<?
		$sql="SELECT * FROM department WHERE parent_id = '$parent' AND mark_del = '0' ORDER BY department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
		<label style='margin: 0px'><input type='checkbox' name='department_id3[]' id='department_l3' value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'checked'?>> <?=$row1['department_name']?></label><br>
<?
		}
	} 
	exit;	
	
} else if ($action=='jf_list') {
	$parent = intval($_POST['parent']);
	$data = intval($_POST['data']);
	$lv = intval($_POST['lv']);
	
	if ($lv==1 && $parent>0) {
?>
	  <select name='department_id2' id='department_l2' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
		department.*,
		(SELECT COUNT(*) FROM job_function WHERE job_function.department_id2 = department.department_id) AS num		
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY 
		department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?> (<?=$row1['num']?>)</option>
<?
		}
?>
		</select>
		
<?	} else if ($lv==2 && $parent>0) {
?>
	  <select name='department_id3' id='department_l3' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
			department.*,
			(SELECT COUNT(*) FROM job_function WHERE job_function.department_id3 = department.department_id) AS num
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?> (<?=$row1['num']?>)</option>
<?
		}
?>
		</select>
<?	}
	exit;	
	
} else if ($action=='response_list') {
	$parent = intval($_POST['parent']);
	$data = intval($_POST['data']);
	$y = intval($_POST['y']);
	$lv = intval($_POST['lv']);
	
	if ($lv==1 && $parent>0) {
?>
	  <select name='department_id2' id='department_l2' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
		department.*,
		(SELECT COUNT(*) FROM csa_responsibility WHERE csa_year = '$y' AND csa_responsibility.department_id2 = department.department_id) AS num		
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY 
		department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?> (<?=$row1['num']?>)</option>
<?
		}
?>
		</select>
		
<?	} else if ($lv==2 && $parent>0) {
?>
	  <select name='department_id3' id='department_l3' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
			department.*,
			(SELECT COUNT(*) FROM csa_responsibility WHERE csa_year = '$y' AND csa_responsibility.department_id3 = department.department_id) AS num
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?> (<?=$row1['num']?>)</option>
<?
		}
?>
		</select>
<?	}
	exit;	
	
} else if ($action=='audit_list') {
	$parent = intval($_POST['parent']);
	$data = intval($_POST['data']);
	$y = intval($_POST['y']);
	$lv = intval($_POST['lv']);
	
	if ($lv==1 && $parent>0) {
?>
	  <select name='department_id2' id='department_l2' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
		department.*,
		(SELECT COUNT(*) FROM csa_audit WHERE csa_year = '$y' AND csa_audit.department_id2 = department.department_id) AS num		
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY 
		department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?> (<?=$row1['num']?>)</option>
<?
		}
?>
		</select>
		
<?	} else if ($lv==2 && $parent>0) {
?>
	  <select name='department_id3' id='department_l3' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
			department.*,
			(SELECT COUNT(*) FROM csa_audit WHERE csa_year = '$y' AND csa_audit.department_id3 = department.department_id) AS num
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?> (<?=$row1['num']?>)</option>
<?
		}
?>
		</select>
<?	}
	exit;	
	
} else if ($action=='user_list') {
	$parent = intval($_POST['parent']);
	$data = intval($_POST['data']);
	$y = intval($_POST['y']);
	$lv = intval($_POST['lv']);
	
	if ($lv==1 && $parent>0) {
?>
	  <select name='department_id2' id='department_l2' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
		department.*
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY 
		department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?></option>
<?
		}
?>
		</select>
		
<?	} else if ($lv==2 && $parent>0) {
?>
	  <select name='department_id3' id='department_l3' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
		$sql="SELECT 
			department.*,
			(SELECT COUNT(*) FROM user WHERE mark_del = 0 AND department_id = department.department_id) AS num
		FROM department 
		WHERE 
		department.parent_id = '$parent' AND 
		department.mark_del = '0' 
		ORDER BY department.department_id";
		$result1=mysqli_query($connect, $sql);
		while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$data) echo 'selected'?>><?=$row1['department_name']?> (<?=$row1['num']?>)</option>
<?
		}
?>
		</select>
<?	}
	exit;	
	
} else if ($action=='risktype') {
	$parent = intval($_POST['parent']);
	$data = intval($_POST['data']);
	
	if ($parent>0) {
?>
			  <label style='font-weight: bold'>ปัจจัยเสี่ยง</label>
			  <select name='csa_factor' id='csa_factor' class="form-control" required>
				<option value=''>--- เลือก ---</option>
<?
	$sql="SELECT * FROM csa_factor WHERE csa_risk_type_id='$parent' AND mark_del='0' ORDER BY factor_no, csa_factor_id";
	$result1=mysqli_query($connect, $sql);
	while ($row1 = mysqli_fetch_array($result1)) {

		echo '<optgroup label="'.$row1['factor_no'].' '.$row1['factor'].'">';


		$sql="SELECT * FROM csa_factor WHERE parent_id='$row1[csa_factor_id]' AND mark_del='0'  ORDER BY factor_no, csa_factor_id";
		$result2=mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<option value='<?=$row2['csa_factor_id']?>'  is_other='<?=$row2['is_other']?>' <?if ($row2['csa_factor_id']==$data) echo 'selected'?>><?=$row2['factor_no']?> <?=$row2['factor']?></option>
<?
		}
		echo '</optgroup>';
	}
?>			  
			  </select>
<?	} else {?>
	
			<label style='font-weight: bold'>ปัจจัยเสี่ยง</label>
			<select name='csa_factor' id='csa_factor' class="form-control" required disabled>
			<option value=''>--- โปรดเลือกประเภทความเสี่ยงก่อน ---</option>	
			</select>
<?	}
	exit;
} 

?>