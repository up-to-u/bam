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


if ($submit=='update' && ($update_id>0)) {
	$prefix = addslashes($_POST[prefix]);
	$name = addslashes($_POST[name]);
	$surname = addslashes($_POST[surname]);
	$prefix_en = addslashes($_POST[prefix_en]);
	$name_en = addslashes($_POST[name_en]);
	$surname_en = addslashes($_POST[surname_en]);
	$tel = addslashes($_POST[tel]);
	$mobile = addslashes($_POST[mobile]);
	$email = addslashes($_POST[email]);
	$contact_address = addslashes($_POST[contact_address]);
	$login = addslashes($_POST[userName]);
	$password = addslashes($_POST[password]);
	$code = addslashes($_POST[code]);
	$login = addslashes($_POST[login]);
	$position = addslashes($_POST[position]);
	$img1_url = addslashes($_POST[img1_url]);
	$mark_del = intval($_POST[mark_del]);
	$department_id = intval($_POST[department_id]);
	$wsql = '';
	if ($img1_url!='') {
		$ext_src = '.jpg';
		$new_name = 'person/'.$update_id.$ext_src;
		$success = false;
		$src_image = $img1_url;
		if (file_exists($src_image)) {		
			if (file_exists($new_name)) unlink($new_name);
			$x = copy($src_image, $new_name);
			unlink($src_image);
			if ($x) {
				echo '<font color="green"><b>รูปภาพของท่านได้ถูกบันทึกเรียบร้อยแล้ว</b></font><br>';
				$wsql .= ", `img`='$new_name' ";
				$success = true;
			}
		}		
	}
	
	
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
	department_id = '$department_id', 
	email = '$email', 
	prefix = '$prefix', 
	name = '$name', 
	surname = '$surname', 
	prefix_en = '$prefix_en', 
	name_en = '$name_en', 
	surname_en = '$surname_en', 
	tel = '$tel', 
	mobile = '$mobile', 
	contact_address = '$contact_address',
	userName = '$login', 
	position = '$position',
	mark_del = '$mark_del',
	last_modify = now() 
	$wsql
	WHERE user_id = '$update_id' ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	


	if ($qx) {
		mysqli_commit($connect);		
		echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
	} else {
		mysqli_rollback($connect);			
		echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
	}
	echo '<script>$(document).ready(function(){$(".alert").fadeTo(2000, 500).slideUp(500, function(){ $(".alert").slideUp(500); });});</script>';			
	
} else if ($submit=='add') {
	$prefix = addslashes($_POST[prefix]);
	$name = addslashes($_POST[name]);
	$surname = addslashes($_POST[surname]);
	$prefix_en = addslashes($_POST[prefix_en]);
	$name_en = addslashes($_POST[name_en]);
	$surname_en = addslashes($_POST[surname_en]);
	$tel = addslashes($_POST[tel]);
	$mobile = addslashes($_POST[mobile]);
	$email = addslashes($_POST[email]);
	$contact_address = addslashes($_POST[contact_address]);
	$login = addslashes($_POST[userName]);
	$password = addslashes($_POST[password]);
	$code = addslashes($_POST[code]);
	$position = addslashes($_POST[position]);
	$department_id = intval($_POST[department_id]);
	$img1_url = addslashes($_POST[img1_url]);
	$error = 0;	$sql = "SELECT COUNT(*) FROM user WHERE (code='$code' OR userName='$login' OR email='$email') AND mark_del = '0' ";	$result2 = mysqli_query($connect, $sql);	if ($row2 = mysqli_fetch_array($result2)) {		$error = 1;	}	if ($error==0) {
		$qx = true;	
		mysqli_autocommit($connect,FALSE);
		
		$sql = "INSERT INTO user (prefix, name, surname, prefix_en, name_en, surname_en, department_id, tel, mobile, email, contact_address, userName, password, code, position, status, create_date) VALUES 
		('$prefix', '$name', '$surname', '$prefix_en', '$name_en', '$surname_en', '$department_id', '$tel', '$mobile', '$email', '$contact_address', '$login', '$password', '$code', '$position', '0', now()) ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);	
		$update_id = mysqli_insert_id($connect);
		if ($img1_url!='') {
			$ext_src = '.jpg';
			$new_name = 'person/'.$update_id.$ext_src;
			$success = false;
			$src_image = $img1_url;
			if (file_exists($src_image)) {		
				if (file_exists($new_name)) unlink($new_name);
				$x = copy($src_image, $new_name);
				unlink($src_image);
				if ($x) {
					echo '<font color="green"><b>รูปภาพของท่านได้ถูกบันทึกเรียบร้อยแล้ว</b></font><br>';
					$sql = "UPDATE user SET `img`='$new_name' WHERE user_id = '$update_id' ";
					$q = mysqli_query($connect, $sql);
					$qx = ($qx and $q);	
					$success = true;
				}
			}		
		}	
		
		if ($qx) {
			mysqli_commit($connect);		
			echo '<div class="container"><b><div class="alert alert-success">ระบบได้บันทึกข้อมูลเรียบร้อยแล้ว</div></b><br></div>';
		} else {
			mysqli_rollback($connect);			
			echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
		}
	} else {		echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด รหัสพนักงาน/Login/Email ซ้ำกับข้อมูลในระบบ ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';	}	
}   else if (($edit_id>0)) {

	$sql2="SELECT * FROM `user` WHERE user_id = '$edit_id' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	
	$mark_del = $row2['mark_del'];
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
					<button type='button' class="btn btn-primary" onClick="document.location='user.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			

	
	<form method='post' action='user.php'>  
	<b class='text-primary'>ข้อมูลส่วนตัว</b><br><br>

	<div class="form-group">
	  <label>ชื่อ สกุล</label>
		<div class="row">
			<div class="col-xs-1"><input type="text" class="form-control" placeholder="คำนำหน้า" name='prefix' value='<?=$row2[prefix]?>'></div>
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="ชื่อ" name='name' value='<?=$row2[name]?>'></div>
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="นามสกุล" name='surname' value='<?=$row2[surname]?>'></div>
		</div>	  
		<br>
		<div class="row">
			<div class="col-xs-1"><input type="text" class="form-control" placeholder="Prefix" name='prefix_en' value='<?=$row2[prefix_en]?>'></div>
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="Name" name='name_en' value='<?=$row2[name_en]?>'></div>
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="Surname" name='surname_en' value='<?=$row2[surname_en]?>'></div>
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
	<div class="form-group">
	  <label>ที่ติดต่อ</label>
	  <textarea class="form-control" name='contact_address' placeholder="ที่ติดต่อ" rows='3'><?=$row2[contact_address]?></textarea>
	</div>

	<br>
	<b class='text-primary'>ข้อมูลการทำงาน</b><br><br>

	<div class="form-group">
	  <label>Login</label>
		<div class="row">
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="login" name='login' value='<?=$row2[userName]?>'></div>
		</div>	  
	</div>

	<div class="form-group">
	  <label>รหัสพนักงาน</label>
		<div class="row">
			<div class="col-xs-2"><input type="text" class="form-control" readonly placeholder="รหัสพนักงาน" name='code' value='<?=$row2[code]?>'></div>
		</div>		
	</div>
	<div class="form-group">
	  <label>ตำแหน่ง</label>
	  <input type="text" class="form-control" name='position' placeholder="ตำแหน่ง" value='<?=$row2[position]?>'>
	</div>	
	<div class="form-group">
	  <label>ฝ่าย</label>
	  <select name='department_id' class="form-control">
		<option value='0'>--- เลือก ---</option>
<?
$sql="SELECT * FROM department ORDER BY department_no, department_name";
$result=mysqli_query($connect, $sql);
while ($row = mysqli_fetch_array($result)) {
?>
		<option value='<?=$row[department_id]?>' <?if ($row2[department_id]==$row[department_id]) echo 'selected'?>><?=$row[department_name]?></option>
<?
}
?>
		</select>
	</div>
	
	
	
		
			
	<br>
	<b class='text-primary'>Permission</b><br><br>
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
	<button type='button' class="btn btn-primary" onClick="document.location='user.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
	<button type='submit' name='submit' value='update' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
	</form>
	
	
	
		</div>
	</div>
</div>
		  
<?
	}	

	echo template_footer();
	exit;
	
} else if ($action=='add') {
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
					<span class="caption-subject font-green sbold uppercase">เพิ่มผู้ใช้งาน</span>
					<span class="caption-helper"></span>
				</div>
				<div class="actions">
					<button type='button' class="btn btn-primary" onClick="document.location='user.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
				</div>
			</div>
			
	<form method='post' action='user.php'>  
	<b class='text-primary'>ข้อมูลส่วนตัว</b><br><br>

	<div class="form-group">
	  <label>ชื่อ สกุล</label>
		<div class="row">
			<div class="col-xs-1"><input type="text" class="form-control" placeholder="คำนำหน้า" name='prefix' value='<?=$row2[prefix]?>' required></div>
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="ชื่อ" name='name' value='<?=$row2[name]?>' required></div>
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="นามสกุล" name='surname' value='<?=$row2[surname]?>' required></div>
		</div>	  
		<br>
		<div class="row">
			<div class="col-xs-1"><input type="text" class="form-control" placeholder="Prefix" name='prefix_en' value='<?=$row2[prefix_en]?>'></div>
			<div class="col-xs-3"><input type="text" class="form-control" placeholder="Name" name='name_en' value='<?=$row2[name_en]?>'></div>
			<div class="col-xs-4"><input type="text" class="form-control" placeholder="Surname" name='surname_en' value='<?=$row2[surname_en]?>'></div>
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
	<div class="form-group">
	  <label>ที่ติดต่อ</label>
	  <textarea class="form-control" name='contact_address' placeholder="ที่ติดต่อ" rows='3'><?=$row2[contact_address]?></textarea>
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
	<div class="form-group">
	  <label>ฝ่าย</label>
	  <select name='department_id' class="form-control">
		<option value='0'>--- เลือก ---</option>
<?

$sql="SELECT * FROM department ORDER BY department_no, department_name";
$result2=mysqli_query($connect, $sql);
while ($row2 = mysqli_fetch_array($result2)) {
?>
		<option value='<?=$row2[department_id]?>'><?=$row2[department_name]?></option>
<?
}
?>
		</select>
	</div>	
	<br>

	<button type='button' class="btn btn-primary" onClick="document.location='user.php'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>
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

<?
$n = array();
$wsql ='';
$search_code = $_GET['search_code'];
$submit = $_GET['submit'];

if ($submit=='search') {		
	if ($search_code!='') $wsql .= " AND user.code LIKE '%$search_code%'";
	$sql="SELECT COUNT(*) AS num FROM `user` WHERE status = 0 $wsql";		
	$result=mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	$n[0] = $row['num'];
}
?>

<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">User List <?if ($n[0]>0) echo "<span class='badge badge-danger'>$n[0]</span>";?></a></li>
		<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false">ค้นหาข้อมูล</a></li>		
	</ul>
	<div class="tab-content">
		<br>
		<div class="tab-pane active" id="tab1">

<?
	$view_per_page = 50;
	$page = $_GET['p'];

	if (!is_numeric($page)) $page = 1;
	$param = '';

	$display_page = '';
	$sql = "SELECT COUNT(*) AS num FROM `user` WHERE user.status = '0' $wsql ";
	$result = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($result)) {
		$total_item = $row['num'];
		$total_page = ceil($total_item/$view_per_page);
		if (($page=='')||($page<1)||($page>$total_page)) $page=1;
		$limit_start=$view_per_page*($page-1);

		if ($total_page < 30) {
			$display_page='';
			for ($i=1;$i<=$total_page;$i++) {
				if ($i==$page)
					$display_page.='<span class="pagecurrent">'.$i.'</span>';
				else
					$display_page.='<a class="displaypageNum" href="?p='.$i.'&'.$param.'">'.$i.'</a> ';
			}	
		} else {
			$max_display = $page+15;
			$min_display = $page-15;
			if ($min_display<1) { $min_display = 1; $max_display = $min_display+30;}
			if ($max_display>$total_page) { $max_display = $total_page; $min_display = $max_display-30;}
			$display_page='';
			for ($i=$min_display;$i<=$max_display;$i++) {
				if ($i==$page)
					$display_page.='<span class="pagecurrent">'.$i.'</span> ';
				else if ($i==$min_display && $i>1)
					$display_page.='<a class="displaypageNum" href="?p='.$i.'&'.$param.'">&lt;&lt;</a> ';
				else if ($i==$max_display && $i<$total_page)
					$display_page.='<a class="displaypageNum" href="?p='.$i.'&'.$param.'">&gt;&gt;</a> ';
				else
					$display_page.='<a class="displaypageNum" href="?p='.$i.'&'.$param.'">'.$i.'</a> ';
			}	
		}
	}
?>
		
			<table class='table table-hover table-light'>
			<thead>
			<tr>
			  <th width='3%'>No.</th>
			  <th width='10%'>รหัส</th>
			  <th width='20%'>ชื่อ</th>
			  <th width='15%'>ตำแหน่ง</th>			  <th width='25%'>สังกัดฝ่าย</th>			  <th width='10%'>Login</th>
			</tr>
			</thead>
			<tbody>
<?
	$i = 1;
	$sql2="SELECT 		user.*,		department.department_name
	FROM `user` 
	LEFT JOIN department ON user.department_id = department.department_id	WHERE 
	user.status = '0' AND	user.mark_del = '0'
	ORDER BY department_no, department_name, user.user_id DESC
	LIMIT $limit_start, $view_per_page ";
	$result2=mysqli_query($connect, $sql2);
	while ($row2 = mysqli_fetch_array($result2)) {
?>
<tr onClick='document.location="user.php?edit_id=<?=$row2['user_id']?>"' style='cursor:pointer'>
	<td width='3%'><?=$i++?></td>
	<td width='10%'><?=$row2['code']?></td>
	<td width='20%'><?=$row2['prefix']?><?=$row2['name']?> <?=$row2['surname']?></td>
	<td width='15%'><?=$row2['position']?></td>	<td width='25%'><?=$row2['department_name']?></td>	<td width='10%'><?=$row2['userName']?></td>
</tr>
<?		
	}	
?>
			</tbody>
			</tbody>
			</table>			  
			<br>
หน้า <br><br><div id='blog-pager'><?=$display_page?></div><br><br><a href='user.php?action=add' class='btn btn-default'>เพิ่มผู้ใช้งาน</a><br>				<br>				
	
	
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
?>