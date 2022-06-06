<?
include('inc/include.inc.php');
//check_permission(1, true);
echo template_header();

$max_p = 20;
$action = $_GET['action'];
$edit_id = intval($_GET['edit_id']);
$mark_del = intval($_POST[mark_del]);
$del_id = intval($_GET['del_id']);
$update_id = intval($_POST['update_id']);
$submit = $_POST['submit'];
$depid = intval($_GET['depid']);


if ($submit=='update' && ($update_id>0)) {
	$prefix = ($_POST['prefix']);	$name = ($_POST['name']);	$surname = ($_POST['surname']);	$tel = ($_POST['tel']);	$mobile = ($_POST['mobile']);	$email = ($_POST['email']);	$login = ($_POST['login']);	$password = ($_POST['password']);	$code = ($_POST['code']);	$position = ($_POST['position']);	$department_id = intval($_POST['department_id']);	$department_id = intval($_POST['department_id']);
	$auth_csa = intval($_POST['auth_csa']);
	$auth_loss = intval($_POST['auth_loss']);
	list($div_name, $dep_name, $group_name) = get_dep_name($department_id);

	
	$qx = true;	
	mysqli_autocommit($connect,FALSE);


	$p = array();
	$sql2="SELECT * FROM `permission` ";
	$result2=mysqli_query($connect, $sql2);
	while ($row2 = mysqli_fetch_array($result2)) {
		if ($_POST['p_'.$row2['permission_id']]==1) 
			$t = 1;
		else 
			$t = 0;
		$p[$row2['permission_id']]=$t;
	}
	
	$sql = "SELECT * FROM system_permission WHERE department_id = 0 AND user_id = '$update_id' ";
	$result2=mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {
		$sql = "UPDATE system_permission SET ";
		for ($i=1; $i<=$max_p; $i++) {
			if ($i>1) $sql.=', ';
			$sql.= " p_$i = '".intVal($p[$i])."' ";
		}
		$sql .= " WHERE department_id = 0 AND user_id = '$update_id' ";
	} else {
		$sql = "INSERT INTO system_permission (department_id, user_id ";
		for ($i=1; $i<=$max_p; $i++) {
			$sql.= ", p_$i ";
		}
		$sql .= ") VALUES ('0', '$update_id' ";
		for ($i=1; $i<=$max_p; $i++) {
			$sql.= ",'".intVal($p[$i])."'";
		}
		$sql .= ")";
	}
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	
	
	$sql = "UPDATE user SET
	department_id = ?, 
	email = ?, 
	prefix = ?, 
	name = ?, 
	surname = ?, 
	division_name = ?, 
	department_name = ?, 
	group_name = ?, 
	tel = ?, 
	mobile = ?, 
	userName = ?, 
	position = ?,
	auth_csa = ?,
	auth_loss = ?,
	mark_del = ?,
	last_modify = now() 
	WHERE user_id = ? ";
	
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('issssssssssssssi', $department_id,$email,$prefix,$name,$surname,$div_name,$dep_name,$group_name, 
				$tel,$mobile,$login,$position,$auth_csa,$auth_loss,$mark_del,$update_id);
		$q = $stmt->execute();
		$qx = ($qx and $q);	

		if ($qx) {
			mysqli_commit($connect);		
			echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
			savelog('CSA-ADMIN-USER-UPDATE|user_id|'.$update_id.'|');
		} else {
			mysqli_rollback($connect);			
			echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
		}
	}
	
} else if ($submit=='add') {
	$prefix = ($_POST['prefix']);
	$name = ($_POST['name']);
	$surname = ($_POST['surname']);
	$tel = ($_POST['tel']);
	$mobile = ($_POST['mobile']);
	$email = ($_POST['email']);
	$contact_address = ($_POST['contact_address']);
	$login = ($_POST['userName']);
	$password = ($_POST['password']);
	$code = ($_POST['code']);
	$position = ($_POST['position']);
	$department_id = intval($_POST['department_id']);

	$error = 0;
	$sql = "SELECT COUNT(*) FROM user WHERE (code=? OR userName=? OR email=?) AND mark_del = '0' ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('sss', $code, $login, $email);
		$stmt->execute();
		$result2 = $stmt->get_result();
		$row2 = mysqli_fetch_assoc($result2);
		if ($row2['num']==0) {
			$error = 1;
		}
	}
	
	
	if ($error==0) {		list($div_name, $dep_name, $group_name) = get_dep_name($department_id);
		
		$qx = true;	
		mysqli_autocommit($connect,FALSE);

		$sql = "INSERT INTO user (prefix, name, surname, department_id, division_name, department_name, group_name, tel, mobile, email, userName, password, code, position, create_date) VALUES 
		(?,?,?,?,?,?,?,?,?,?,?,?,?,?,now()) ";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$stmt->bind_param('ssssssssssssss',$prefix,$name,$surname,$department_id,$div_name,$dep_name,$group_name,$tel,$mobile,$email,$login,$password,$code,$position);
			$q = $stmt->execute();
			$qx = ($qx and $q);	
			$insert_id = mysqli_insert_id($connect);

			if ($qx) {
				mysqli_commit($connect);		
				echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
				savelog('CSA-ADMIN-USER-ADD|user_id|'.$update_id.'|');
			} else {
				mysqli_rollback($connect);			
				echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
			}
		}
	} else {		echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด รหัสพนักงาน/Login/Email ซ้ำกับข้อมูลในระบบ ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';	}	
}   else if (($edit_id>0)) {

	$sql2="SELECT * FROM `user` WHERE user_id = '$edit_id' ";
	$result=mysqli_query($connect, $sql2);
	if ($row = mysqli_fetch_array($result)) {	
	$mark_del = $row['mark_del'];
?>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/jquery.fileupload.css">
<script src="js/vendor/jquery.ui.widget.js"></script>
<script src="js/jquery.iframe-transport.js"></script>
<script src="js/jquery.fileupload.js"></script>
<script>
	$(function () {
		//'use strict';
		// Change this to the location of your server-side upload handler:
		var url = 'upload_api/php/';
		$('#fileupload').fileupload({
			url: url,
			dataType: 'json',
			submit: function (e, data) {
				$('#progress .progress-bar').css('width', '0%');
				$('#progress').css('display', '');
			},
			done: function (e, data) {
				$.each(data.result.files, function (index, file) {                
					$('#im').attr("src", url+'files/'+file.name);
					$('#img1_url').val(url+'files/'+file.name);
				});
				
				$('#progress').css('display', 'none');
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).on('fileuploadfail', function (e, data) {
			$.each(data.files, function (index, file) {
				var error = $('<span class="text-danger"/>').text('File upload failed.');
				alert(data.errorThrown+"/"+data);
			});
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	});	
</script>


<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-user font-green"></i>
					<span class="caption-subject font-green sbold uppercase">แก้ไขผู้ใช้งาน</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='user.php?depid=<?=$depid?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='user.php?depid=<?=$depid?>'>  
	<b class='text-primary'>ข้อมูลส่วนตัว</b><br><br>

	<div class="form-group">
	  <label>ชื่อ สกุล</label>
		<div class="row">
			<div class="col-xs-1"><input type="text" class="form-control" placeholder="คำนำหน้า" name='prefix' value='<?=$row['prefix']?>'></div>
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="ชื่อ" name='name' value='<?=$row['name']?>'></div>
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="นามสกุล" name='surname' value='<?=$row['surname']?>'></div>
		</div>	  
	</div>
	<div class="form-group">
	  <label>โทรศัพท์</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="Tel." name='tel' value='<?=$row['tel']?>'></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>มือถือ</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="mobile" name='mobile' value='<?=$row['mobile']?>'></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>Email</label>
	  <input type="text" class="form-control" name='email' placeholder="email" value='<?=$row['email']?>'>
	</div>	

	<br>
	<b class='text-primary'>ข้อมูลการทำงาน</b><br><br>

	<div class="form-group">
	  <label>Login</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="login" name='login' value='<?=$row['userName']?>'></div>
		</div>	  
	</div>

	<div class="form-group">
	  <label>รหัสพนักงาน</label>
		<div class="row">
			<div class="col-xs-2"><input type="text" class="form-control" readonly placeholder="รหัสพนักงาน" name='code' value='<?=$row['code']?>'></div>
		</div>		
	</div>
	<div class="form-group">
	  <label>ตำแหน่ง</label>
	  <input type="text" class="form-control" name='position' placeholder="ตำแหน่ง" value='<?=$row['position']?>'>
	</div>	
	<div class="form-group">
	  <label>ฝ่าย</label>
	  <select name='department_id' class="form-control">
		<option value='0'>--- เลือก ---</option>
<?
	$sql="SELECT department_id, department_name FROM `department` WHERE parent_id = '0' AND mark_del = '0' ";
	$result1=mysqli_query($connect, $sql);
	while ($row1 = mysqli_fetch_array($result1)) {
		if ($row1['department_id']==$row['department_id']) 
			$s = 'selected';
		else
			$s = '';
		echo '		<option value="'.$row1['department_id'].'" style="font-weight:bold; background-color:#eeeeee" '.$s.'>'.$row1['department_name']."</option>\n";

		$sql="SELECT department_id, department_name FROM `department` WHERE parent_id = '$row1[department_id]' AND mark_del = '0' ";
		$result2=mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {
			if ($row2['department_id']==$row['department_id']) 
				$s = 'selected';
			else
				$s = '';
			echo '		<option value="'.$row2['department_id'].'" style="font-weight:bold" '.$s.'> &nbsp;&nbsp;&nbsp; '.$row2['department_name']."</option>\n";
			
			$sql="SELECT department_id, department_name FROM `department` WHERE parent_id = '$row2[department_id]' AND mark_del = '0' ";
			$result3=mysqli_query($connect, $sql);
			while ($row3 = mysqli_fetch_array($result3)) {
				if ($row3['department_id']==$row['department_id']) 
					$s = 'selected';
				else
					$s = '';
				
				echo '		<option value="'.$row3['department_id'].'" style="font-weight:bold" '.$s.'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$row3['department_name']."</option>\n";
				
				$sql="SELECT department_id, department_name FROM `department` WHERE parent_id = '$row3[department_id]' AND mark_del = '0' ";
				$result4=mysqli_query($connect, $sql);
				while ($row4 = mysqli_fetch_array($result4)) {
				if ($row4['department_id']==$row['department_id']) 
					$s = 'selected';
				else
					$s = '';
					
					echo '		<option value="'.$row4['department_id'].'" '.$s.'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$row4['department_name']."</option>\n";
				}
			}
		}
	}
?>
		</select>
	</div>
	
	
	
		
			
	<br>
	<b class='text-primary'>Permission</b><br><br>
	<div class="form-group">
	  <label style='font-weight: bold'>CSA</label>
	  <select name='auth_csa' id='auth_csa' class="form-control">
		<option value=''>--- เลือก ---</option>
		<option value='0' <?if ($row['auth_csa']==0) echo 'selected'?>>None</option>
		<option value='1' <?if ($row['auth_csa']==1) echo 'selected'?>>User</option>
		<option value='2' <?if ($row['auth_csa']==2) echo 'selected'?>>Approve</option>
	  </select>
	</div>	
	<div class="form-group">
	  <label style='font-weight: bold'>Loss Data</label>
	  <select name='auth_loss' id='auth_loss' class="form-control">
		<option value=''>--- เลือก ---</option>
		<option value='0' <?if ($row['auth_loss']==0) echo 'selected'?>>None</option>
		<option value='1' <?if ($row['auth_loss']==1) echo 'selected'?>>User</option>
		<option value='2' <?if ($row['auth_loss']==2) echo 'selected'?>>Approve</option>
	  </select>
	</div>	
	
	<b>Admin</b><br>
	<div class="form-group">
<?
		$p = array();
		$sql2="SELECT * FROM `system_permission` WHERE user_id = '$edit_id' ";
		$result2=mysqli_query($connect, $sql2);
		while ($row2 = mysqli_fetch_array($result2)) {
			for ($i=1; $i<=$max_p; $i++) {
				if ($row2['p_'.$i]==1) $p[$i] = 1;
			}
		}
		
		$sql2="SELECT * FROM `permission` ORDER BY permission_id ";
		$result2=mysqli_query($connect, $sql2);
		while ($row2 = mysqli_fetch_array($result2)) {
?>
			<div class="row">
				<div class='col-md-12'><label><input type="checkbox" name='p_<?=$row2['permission_id']?>' value='1' <?if ($p[$row2['permission_id']]==1) echo 'checked'?>> <?=$row2['permission_name']?></label>
				</div>
			</div>
<?		} ?>
	</div>	
	<br>
	
	<div class="form-group"><b class='text-primary'>สภาพพนักงาน </b><br><br> 
	<label><input type="checkbox" value='1' name='mark_del' <?if ($mark_del=='1') echo 'checked'?> > Delete </label>

				</div>
	
	<input type='hidden' name='update_id' value='<?=$edit_id?>'>
	<button type='button' class="btn btn-primary" onClick="document.location='user.php?depid=<?=$depid?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='update' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
	}	

	echo template_footer();
	exit;
	
} else if ($action=='add' && $depid>0) {

	$sql="SELECT 
		d3.department_id as d3_id, 
		d2.department_id as d2_id,
		d1.department_id as d1_id, 
		d3.department_name as d3_name, 
		d2.department_name as d2_name, 
		d1.department_name as d1_name 
	FROM `department` d1 
	LEFT JOIN `department` d2 ON  d1.parent_id = d2.department_id AND d2.mark_del = '0'
	LEFT JOIN `department` d3 ON  d2.parent_id = d3.department_id AND d3.mark_del = '0'
	
	WHERE d1.department_id = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $depid);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			$d3 = $row2['d3_id'];
			$d2 = $row2['d2_id'];
			$d1 = $row2['d1_id'];
			$d3_name = $row2['d3_name'];
		}
	}	
	$wsql =" AND department_id = '$d1' ";
	
?>

<script language='JavaScript'>
$(function () {
	$("#department_l1").change(function() {
		var d = parseInt($("#department_l1").val());
		var d2 = parseInt(<?=$d2?>);
		var d3 = parseInt(<?=$d1?>);
		if (d>0) {
			$.post( "jobfunction_content.php", { action: 'user_list', parent: d, data: d2, lv: 1 })
			.done(function( data ) {
				$("#d_lv2").html(data);
				$("#d_lv3").html("<select name='department_id3' id='department_l3' class='form-control input-sm' disabled></select>");
				$("#department_l2").change(function() {
					var d = parseInt($("#department_l2").val());
					if (d>0) {
						$.post( "jobfunction_content.php", { action: 'user_list', parent:d, data: d3, lv: 2})
						.done(function( data ) {
							$("#d_lv3").html(data);
							
							$("#department_l3").change(function() {
								var d = parseInt($("#department_l3").val());
								if (d>0) {
									document.location='user.php?depid='+d;
								}
							});
							
						});	
					}
				}).change();	
			});	
		}
	}).change();	
	$('.csa_editable').on('click', function() {
		var id = $(this).attr('did');
		document.location="user.php?depid=<?=$depid?>&edit_id="+id;
	});	
	
});  

</script>
<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class=" icon-user font-green"></i>
					<span class="caption-subject font-green sbold uppercase">เพิ่มผู้ใช้งาน</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='user.php?depid=<?=$depid?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			
	<form method='post' action='user.php?depid=<?=$depid?>'>  
	<b class='text-primary'>ข้อมูลส่วนตัว</b><br><br>

	<div class="form-group">
	  <label>ชื่อ สกุล</label>
		<div class="row">
			<div class="col-xs-1"><input type="text" class="form-control" placeholder="คำนำหน้า" name='prefix' value='<?=$row2[prefix]?>' required></div>
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="ชื่อ" name='name' value='<?=$row2[name]?>' required></div>
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="นามสกุล" name='surname' value='<?=$row2[surname]?>' required></div>
		</div>	  
	</div>
	<div class="form-group">
	  <label>โทรศัพท์</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="Tel." name='tel' value='<?=$row2[tel]?>'></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>มือถือ</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="mobile" name='mobile' value='<?=$row2[mobile]?>'></div>
		</div>		
	</div>	
	<div class="form-group">
	  <label>Email</label>
	  <input type="text" class="form-control" name='email' placeholder="email" value='<?=$row2[email]?>'>
	</div>	
	<br>
	<b class='text-primary'>ข้อมูลการทำงาน</b><br><br>

	<div class="form-group">
	  <label>Login</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="login" name='userName' value='<?=$row2[userName]?>' required></div>
		</div>	  
	</div>
	<div class="form-group">
	  <label>Password</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="password" name='password' value='<?=$row2[password]?>' required></div>
		</div>	  
	</div>
	<div class="form-group">
	  <label>รหัสพนักงาน</label>
		<div class="row">
			<div class="col-xs-2"><input type="text" class="form-control" placeholder="รหัสพนักงาน" name='code' value='<?=$row2[code]?>' required></div>
		</div>		
	</div>
	<div class="form-group">
	  <label>ตำแหน่ง</label>
	  <input type="text" class="form-control" name='position' placeholder="ตำแหน่ง" value='<?=$row2[position]?>'>
	</div>	

<div class='row'>
	<div class='col-md-1'><label>สายงาน</label></div>
	<div class='col-md-3'>
	  <select name='department_id' id='department_l1' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
$sql="SELECT 
department.*
FROM department 
WHERE 
department_level_id = 3 AND 
department.mark_del = '0' 
ORDER BY 
department.department_id";

$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$d3) echo 'selected'?>><?=$row1['department_name']?></option>
<?
}
?>
		</select>
	</div>
</div>
<div class='row'>
	<div class='col-md-1'><label>ฝ่าย</label></div>
	<div class='col-md-3' id='d_lv2'>
	  <select name='department_id2' id='department_l2' class="form-control input-sm" disabled>
	  </select>
	</div>	
</div>
<div class='row'>
	<div class='col-md-1'><label>กลุ่ม</label></div>
	<div class='col-md-3' id='d_lv3'>
	  <select name='department_id3' id='department_l3' class="form-control input-sm" disabled>
	  </select>
	</div>	
</div>
	<br>

	<button type='button' class="btn btn-primary" onClick="document.location='user.php?depid=<?=$depid?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='add' class="btn btn-danger"><i class='fa fa-plus-circle'></i> เพิ่ม</button>

	</form>
		</div>
	</div>
</div>
		  
<?

	echo template_footer();
	exit;
} 

?>

<style>
#blog-pager {
	clear:both;margin:0px auto;
	text-align: left; 
	padding: 0px;
}
a.displaypageNum,.showpage a,.pagecurrent{
	font-size: 14px;
	padding: 4px 8px;
	margin-right:5px; 
	color: #666; 
	background-color:#eee;
}
.displaypageNum a:hover,.showpage a:hover, .pagecurrent{
	background:#359BED;
	text-decoration:none;
	color: #fff;
}
#blog-pager .pagecurrent{
	font-weight:bold;color: #fff;
	background:#359BED;
}
</style>


<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">User List <?if ($n[0]>0) echo "<span class='badge badge-danger'>$n[0]</span>";?></a></li>
		<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false">ค้นหาข้อมูล</a></li>		
	</ul>
	<div class="tab-content">
		<br>
		<div class="tab-pane active" id="tab1">

<?
$n = array();
$wsql ="";
$search_code = $_GET['search_code'];
$submit = $_GET['submit'];

if ($submit=='search') {		
	if ($search_code!='') $wsql .= " AND user.code LIKE '%$search_code%'";
	$sql="SELECT COUNT(*) AS num FROM `user` WHERE mark_del = 0 $wsql";		
	$result=mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	$n[0] = $row['num'];

?>
<style>
.csa_qtopic {
}
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
</style>	
			<table class='table table-hover'>
			<thead>
			<tr>
			  <th width='3%'>No.</th>
			  <th width='7%'>รหัส</th>
			  <th width='20%'>ชื่อ</th>
			  <th width='15%'>ตำแหน่ง</th>
			  <th width='20%'>สังกัดหน่วยงาน</th>
			  <th width='5%'>CSA</th>
			  <th width='5%'>Loss</th>
			  <th width='10%'>Login</th>
			</tr>
			</thead>
			<tbody>
<?
		$i = 1;
		$sql2="SELECT 
			user.*
		FROM `user` 
		WHERE 
			user.mark_del = '0'
			$wsql
		ORDER BY 
			department_name, 
			group_name, 
			user.code DESC ";
		$result2=mysqli_query($connect, $sql2);
		while ($row2 = mysqli_fetch_array($result2)) {
?>
<tr class='tr_sm' onClick='document.location="user.php?edit_id=<?=$row2['user_id']?>"' style='cursor:pointer'>
	<td width='3%'><?=$i++?></td>
	<td width='7%'><?=$row2['code']?></td>
	<td width='20%'><?=$row2['prefix']?><?=$row2['name']?> <?=$row2['surname']?></td>
	<td width='15%'><?=$row2['position']?></td>
	<td width='20%'><?=$row2['group_name']?><br><?=$row2['department_name']?><br><?=$row2['division_name']?></td>
	<td width='5%'><?=auth_label($row2['auth_csa'])?></td>
	<td width='5%'><?=auth_label($row2['auth_loss'])?></td>
	<td width='10%'><?=$row2['userName']?></td>
</tr>
<?		
		}	
?>
			</tbody>
			</tbody>
			</table>			  
			<br>
<?
	
} else {
	$d1=0;
	$d2=0;
	$d3=0;
	if ($depid>0) {
		$sql="SELECT 
			d3.department_id as d3_id, 
			d2.department_id as d2_id,
			d1.department_id as d1_id, 
			d3.department_name as d3_name, 
			d2.department_name as d2_name, 
			d1.department_name as d1_name 
		FROM `department` d1 
		LEFT JOIN `department` d2 ON  d1.parent_id = d2.department_id AND d2.mark_del = '0'
		LEFT JOIN `department` d3 ON  d2.parent_id = d3.department_id AND d3.mark_del = '0'
		
		WHERE d1.department_id = ? ";
		$stmt = $connect->prepare($sql);
		if ($stmt) {					
			$stmt->bind_param('i', $depid);
			$stmt->execute();
			$result2 = $stmt->get_result();
			if ($row2 = mysqli_fetch_assoc($result2)) {
				$d3 = $row2['d3_id'];
				$d2 = $row2['d2_id'];
				$d1 = $row2['d1_id'];
				$d3_name = $row2['d3_name'];
			}
		}	
	}
?>


<script language='JavaScript'>
$(function () {
	$("#department_l1").change(function() {
		var d = parseInt($("#department_l1").val());
		var d2 = parseInt(<?=$d2?>);
		var d3 = parseInt(<?=$d1?>);
		if (d>0) {
			$.post( "jobfunction_content.php", { action: 'user_list', parent: d, data: d2, lv: 1 })
			.done(function( data ) {
				$("#d_lv2").html(data);
				$("#d_lv3").html("<select name='department_id3' id='department_l3' class='form-control input-sm' disabled></select>");
				$("#department_l2").change(function() {
					var d = parseInt($("#department_l2").val());
					if (d>0) {
						$.post( "jobfunction_content.php", { action: 'user_list', parent:d, data: d3, lv: 2})
						.done(function( data ) {
							$("#d_lv3").html(data);
							
							$("#department_l3").change(function() {
								var d = parseInt($("#department_l3").val());
								if (d>0) {
									document.location='user.php?depid='+d;
								}
							});
							
						});	
					}
				}).change();	
			});	
		}
	}).change();	
	$('.csa_editable').on('click', function() {
		var id = $(this).attr('did');
		document.location="user.php?depid=<?=$depid?>&edit_id="+id;
	});	
	
});  

</script>


<div class='row'>
	<div class='col-md-1'><label>สายงาน</label></div>
	<div class='col-md-3'>
	  <select name='department_id' id='department_l1' class="form-control input-sm" required>
		<option value='0'>--- เลือก ---</option>
<?
$sql="SELECT 
department.*
FROM department 
WHERE 
department_level_id = 3 AND 
department.mark_del = '0' 
ORDER BY 
department.department_id";

$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
			<option value='<?=$row1['department_id']?>' <?if ($row1['department_id']==$d3) echo 'selected'?>><?=$row1['department_name']?></option>
<?
}
?>
		</select>
	</div>
</div>
<div class='row'>
	<div class='col-md-1'><label>ฝ่าย</label></div>
	<div class='col-md-3' id='d_lv2'>
	  <select name='department_id2' id='department_l2' class="form-control input-sm" disabled>
	  </select>
	</div>	
</div>
<div class='row'>
	<div class='col-md-1'><label>กลุ่ม</label></div>
	<div class='col-md-3' id='d_lv3'>
	  <select name='department_id3' id='department_l3' class="form-control input-sm" disabled>
	  </select>
	</div>	
</div>
<br>
<br>

<?

	if ($depid>0) {
		$i = 1;
?>
<style>
.csa_qtopic {
}
.tr_sm tr, .tr_sm td {
	padding: 2px !important;
}
</style>	
			<table class='table table-hover'>
			<thead>
			<tr>
			  <th width='3%'>No.</th>
			  <th width='7%'>รหัส</th>
			  <th width='20%'>ชื่อ</th>
			  <th width='15%'>ตำแหน่ง</th>			  <th width='20%'>สังกัดหน่วยงาน</th>
			  <th width='5%'>CSA</th>
			  <th width='5%'>Loss</th>			  <th width='10%'>Login</th>
			</tr>
			</thead>
			<tbody>
<tr class='tr_sm' bgcolor='#dddddd'>
	<td colspan='8'><b>สายงาน</b></td>
</tr>
<?
		$sql2="SELECT * FROM `user` WHERE mark_del = '0' AND department_id = '$d3' ORDER BY code DESC";
		$result2=mysqli_query($connect, $sql2);
		if (mysqli_num_rows($result2)>0) {
			while ($row2 = mysqli_fetch_array($result2)) {
?>
<tr class='tr_sm' bgcolor='#d4e5ff' onClick='document.location="user.php?edit_id=<?=$row2['user_id']?>&depid=<?=$depid?>"' style='cursor:pointer'>
	<td width='3%'><?=$i++?></td>
	<td width='7%'><?=$row2['code']?></td>
	<td width='20%'><?=$row2['prefix']?><?=$row2['name']?> <?=$row2['surname']?></td>
	<td width='15%'><?=$row2['position']?></td>	<td width='20%'><?=$row2['group_name']?><br><?=$row2['department_name']?><br><?=$row2['division_name']?></td>	<td width='5%'><?=auth_label($row2['auth_csa'])?></td>
	<td width='5%'><?=auth_label($row2['auth_loss'])?></td>
	<td width='10%'><?=$row2['userName']?></td>
</tr>
<?		
			}	
		} else {
?>
<tr class='tr_sm'>
	<td colspan='8'>-ไม่มี-</td>
</tr>
<?			
		}			
?>
<tr class='tr_sm' bgcolor='#dddddd'>
	<td colspan='8'><b>ผอ.ฝ่าย</b></td>
</tr>
<?
		$sql2="SELECT * FROM `user` WHERE mark_del = '0' AND department_id = '$d2' ORDER BY code DESC";
		$result2=mysqli_query($connect, $sql2);
		if (mysqli_num_rows($result2)>0) {
			while ($row2 = mysqli_fetch_array($result2)) {
?>
<tr class='tr_sm' bgcolor='#d4e5ff' onClick='document.location="user.php?edit_id=<?=$row2['user_id']?>&depid=<?=$depid?>"' style='cursor:pointer'>
	<td width='3%'><?=$i++?></td>
	<td width='7%'><?=$row2['code']?></td>
	<td width='20%'><?=$row2['prefix']?><?=$row2['name']?> <?=$row2['surname']?></td>
	<td width='15%'><?=$row2['position']?></td>	<td width='20%'><?=$row2['group_name']?><br><?=$row2['department_name']?><br><?=$row2['division_name']?></td>	<td width='5%'><?=auth_label($row2['auth_csa'])?></td>
	<td width='5%'><?=auth_label($row2['auth_loss'])?></td>
	<td width='10%'><?=$row2['userName']?></td>
</tr>
<?		
			}
		} else {
?>
<tr class='tr_sm'>
	<td colspan='8'>-ไม่มี-</td>
</tr>
<?			
		}			
?>
<tr class='tr_sm' bgcolor='#dddddd'>
	<td colspan='8'><b>พนักงาน</b></td>
</tr>
<?
		
		$sql2="SELECT * FROM `user` WHERE mark_del = '0' AND department_id = '$d1'
		ORDER BY code DESC";
		$result2=mysqli_query($connect, $sql2);
		while ($row2 = mysqli_fetch_array($result2)) {
?>
<tr class='tr_sm' onClick='document.location="user.php?edit_id=<?=$row2['user_id']?>&depid=<?=$depid?>"' style='cursor:pointer'>
	<td width='3%'><?=$i++?></td>
	<td width='7%'><?=$row2['code']?></td>
	<td width='20%'><?=$row2['prefix']?><?=$row2['name']?> <?=$row2['surname']?></td>
	<td width='15%'><?=$row2['position']?></td>	<td width='20%'><?=$row2['group_name']?><br><?=$row2['department_name']?><br><?=$row2['division_name']?></td>	<td width='5%'><?=auth_label($row2['auth_csa'])?></td>
	<td width='5%'><?=auth_label($row2['auth_loss'])?></td>
	<td width='10%'><?=$row2['userName']?></td>
</tr>
<?		
		}	
?>
			</tbody>
			</tbody>
			</table>			  
			<br>
			<br>
<a href='user.php?action=add&depid=<?=$depid?>' class='btn btn-default'>เพิ่มผู้ใช้งาน</a>
<?
		
	}
}
?>
<br>				<br>				
	
	
		</div>
		<div class="tab-pane" id="tab2">

			<form name='form2' method='get' action='user.php'>
			<b>ค้นหาข้อมูล</b><br><br>

				<div class='row'>
					<div class='col-md-1'><label>รหัสพนักงาน</label></div>
					<div class='col-md-2'><input type="text" class="form-control input-sm" name='search_code' id='search_code' maxlength='150' value='<?=$search_code?>'></div>
				</div>
				<br>
				<button type='submit' name='submit' value='search' class='btn btn-primary btn-sm'><i class='fa fa-search'></i> ค้นหา</button>
				<button type='button' class='btn btn-default btn-sm' onClick='document.location="user.php"'><i class='fa fa-refresh'></i> เคลียข้อมูลค้นหา</button>
				<br>
				<br>
				<br>
				<br>

			</form>		
		</div>
	</div>
</div>

<?
echo template_footer();
function get_dep_name($dep_id) {	global $connect;	$dep_name = '';	$group_name = '';

/*
	d = division
	d2 = department
	d3 = group
*/
		$sql="SELECT 		d.department_name as div_name,		d2.department_name AS dep_name,
		d3.department_name AS group_name	FROM department d 	LEFT JOIN department d2 ON d.parent_id = d2.department_id AND d2.mark_del = 0
	LEFT JOIN department d3 ON d2.parent_id = d3.department_id AND d3.mark_del = 0	WHERE 		d.department_id = ? ";	$stmt = $connect->prepare($sql);	if ($stmt) {							$stmt->bind_param('i', $dep_id);		$stmt->execute();		$result2 = $stmt->get_result();		if ($row2 = mysqli_fetch_assoc($result2)) {			$group_name = $row2['group_name'];			$dep_name = $row2['dep_name'];
			$div_name = $row2['div_name'];		}	}	return array($div_name, $dep_name, $group_name);}

function auth_label($a) {
	switch ($a) {
		case 0: return '';
		case 1: return 'USER';
		case 2: return 'APPROVE';
		case 3: return 'USER / APPROVE';
	}	
}
?>