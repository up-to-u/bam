<?
include('inc/include.inc.php');
echo template_header();

$action = intval($_GET['action']);
$edit_id = intval($_GET['edit_id']);
$del_id = intval($_GET['del_id']);
$update_id = intval($_POST['update_id']);
$submit = $_POST['submit'];
if ($submit == 'deleteSignature' && $_POST['deleteUserId'] != "") {
	$update_id = $_POST['deleteUserId'];
	$fileNameS = $_POST['deleteFileName'];

	$pathRemove = 'signature_file/' . $fileNameS;
	unlink($pathRemove);

	$sql = "UPDATE user SET	signature = '', 
	last_modify = now() 
	WHERE user_id = '$update_id'";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);
	if ($qx) {
		mysqli_commit($connect);

		echo "<font color='green'><b>ระบบได้ลบข้อมูลลายเซ็นของท่านเรียบร้อยแล้ว</b></font>";
	}
}
if ($submit == 'editSignature' && $_FILES["edit_signature"]["name"] != "") {
	$update_id = $_POST['editUserId'];
	$editSignature = $_POST['editSignature'];

	$target_dir = "signature_file/";
	$target_file = $target_dir . basename($_FILES["edit_signature"]["name"]);

	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	$new_name = $update_id . '_signature.' . $imageFileType;
	$target_file = $target_dir . $new_name;
	$check = getimagesize($_FILES["edit_signature"]["tmp_name"]);
	$uploadOk = 1;
	// if (file_exists($target_file)) {
	// 	echo "Sorry, file already exists. ";
	// 	$uploadOk = 0;
	// }
	if ($_FILES["edit_signature"]["size"] > 500000) {
		echo "Sorry, your file is too large. ";
		$uploadOk = 0;
	}
	if (
		$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif"
	) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded. ";
		// if everything is ok, try to upload file
	} else {

		if (move_uploaded_file($_FILES["edit_signature"]["tmp_name"], $target_file)) {
		} else {
			echo "Sorry, there was an error uploading your file. ";
		}
	}

	if ($uploadOk != 0) {

		$pathRemove = 'signature_file/' . $editSignature;
		$checkMove = unlink($pathRemove);

		$sql = "UPDATE user SET	signature = '$new_name', 
			last_modify = now() 
			WHERE user_id = '$update_id'";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);
		if ($qx) {
			mysqli_commit($connect);

			echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font>";
		}
	}
}

if ($submit == 'update' && $update_id > 0) {
	$prefix = addslashes($_POST['prefix']);
	$name = addslashes($_POST['name']);
	$surname = addslashes($_POST['surname']);
	$tel = addslashes($_POST['tel']);
	$mobile = addslashes($_POST['mobile']);
	$email = addslashes($_POST['email']);
	$contact_address = addslashes($_POST['contact_address']);
	$login = addslashes($_POST['login']);
	$password = addslashes($_POST['password']);
	$code = addslashes($_POST['code']);
	$position = addslashes($_POST['position']);
	$img1_url = addslashes($_POST['img1_url']);
	$floor = addslashes($_POST['floor']);
	$nickname = addslashes($_POST['nickname']);
	$line_user_id = addslashes($_POST['line_user_id']);
	$dep_id = addslashes($_POST['department_id']);
	$signatures = addslashes($_FILES["signature"]["name"]);
	$uploadOk = 1;


	if ($signatures != "" || $signatures != null) {
		$target_dir = "signature_file/";
		$target_file = $target_dir . basename($_FILES["signature"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		$new_name = $update_id . '_signature.' . $imageFileType;
		$target_file = $target_dir . $new_name;
		$check = getimagesize($_FILES["signature"]["tmp_name"]);

		// if (file_exists($target_file)) {
		// 	echo "Sorry, file already exists. ";
		// 	$uploadOk = 0;
		// }
		if ($_FILES["signature"]["size"] > 500000) {
			echo "Sorry, your file is too large. ";
			$uploadOk = 0;
		}
		if (
			$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif"
		) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded. ";
			// if everything is ok, try to upload file
		} else {

			if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file)) {
			} else {
				echo "Sorry, there was an error uploading your file. ";
			}
		}
	}


	$wsql = '';

	$qx = true;
	mysqli_autocommit($connect, FALSE);

	savelog('profile-update-profile');


	if ($uploadOk != 0) {
		if ($_POST['signature'] != '' || $_POST['signature'] != null) {
			$signatures = $_POST['signature'];
		} else {
			$signatures = $new_name;
		}
		$sql = "UPDATE user SET
	prefix = '$prefix', 
	name = '$name', 
	surname = '$surname',
	email = '$email',
	nickname = '$nickname',
	department_id = '$dep_id', 
	tel = '$tel', 
	mobile = '$mobile', 
	position = '$position', 
	contact_address = '$contact_address', 
	signature = '$signatures', 
	last_modify = now() 
	WHERE user_id = '$update_id' ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);
		if ($qx) {

			mysqli_commit($connect);
			echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font>";
		} else {
			echo "<font color='red'><b>ไม่สามารถบันทึกข้อมูลได้กรุณาตรวจสอบลายเซ็นให้ถูกต้อง</b></font>";
		}

		$upload_folder = 'contact/';
		$upfile = $_FILES['image']['name'];
		$upfile_source = $_FILES['image']['tmp_name'];
		$upfile_location_physical = '';
		$upfile_location_sql = '';

		if ($upfile != '') {

			if ($upfile_source != '') {

				$hash_folder = substr(md5($upfile), 0, 3) . '/' . substr(md5($upfile), 3, 3) . '/';
				mkdir_fix($upload_folder . $hash_folder, 0777, true);
				chmod($upload_folder . $hash_folder, 0777);
				$upfile_location_sql = $hash_folder . $upfile;
				$upfile_location_physical = $upload_folder . $upfile_location_sql;
				$copy_success = copy($upfile_source, $upfile_location_physical);

				if ($copy_success) {
					$qx = true;
					mysqli_autocommit($connect, FALSE);

					$upload_sql = "UPDATE  user SET
	`attach_file_name`= '$upfile',
	`attach_file_location`= '$upfile_location_physical'
	WHERE user_id  ='$update_id'";
					$q = mysqli_query($connect, $upload_sql);
					mysqli_commit($connect);
				}
			} else {
				echo '<font color="red"><b>เกิดข้อผิดพลาด ขนาด file ของท่านใหญ่เกินกำหนด</b></font>';
			}
		}
	} else {
		mysqli_rollback($connect);
		echo "<b><font color='red'>ระบบไม่สามารถบันทึกข้อมูลได้</font></b><br><br>";
	}
}

$sql2 = "SELECT * FROM `user` WHERE user_id = '$user_id' ";
$result2 = mysqli_query($connect, $sql2);
if ($row2 = mysqli_fetch_array($result2)) {
?>

	<style type="text/css">
		#image-preview {
			width: 200px;

			overflow: hidden;
			background-color: #ffffff;
			color: #ecf0f1;


			position: relative;

			padding: 10px;



		}

		#image-preview input {
			line-height: 150px;
			font-size: 250px;
			position: absolute;
			opacity: 0;
			z-index: 10;
		}

		#image-preview label {
			position: absolute;
			z-index: 5;
			opacity: 0.8;
			cursor: pointer;
			background-color: #bdc3c7;
			width: 150px;
			height: 50px;
			font-size: 20px;
			line-height: 50px;
			text-transform: uppercase;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			margin: auto;
			text-align: center;
		}

		.col-xs-1,
		.col-sm-1,
		.col-md-1,
		.col-lg-1,
		.col-xs-2,
		.col-sm-2,
		.col-md-2,
		.col-lg-2,
		.col-xs-3,
		.col-sm-3,
		.col-md-3,
		.col-lg-3,
		.col-xs-4,
		.col-sm-4,
		.col-md-4,
		.col-lg-4,
		.col-xs-5,
		.col-sm-5,
		.col-md-5,
		.col-lg-5,
		.col-xs-6,
		.col-sm-6,
		.col-md-6,
		.col-lg-6,
		.col-xs-7,
		.col-sm-7,
		.col-md-7,
		.col-lg-7,
		.col-xs-8,
		.col-sm-8,
		.col-md-8,
		.col-lg-8,
		.col-xs-9,
		.col-sm-9,
		.col-md-9,
		.col-lg-9,
		.col-xs-10,
		.col-sm-10,
		.col-md-10,
		.col-lg-10,
		.col-xs-11,
		.col-sm-11,
		.col-md-11,
		.col-lg-11,
		.col-xs-12,
		.col-sm-12,
		.col-md-12,
		.col-lg-12 {
			position: relative;
			min-height: 1px;
			padding-left: 15px;
			padding-top: 10px;
			padding-right: 15px;
		}
	</style>
	<div class="row">
		<div class="col-lg-12 col-lg-12 col-sm-12">
			<div class="portlet light tasks-widget bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-share font-dark hide"></i>
						<span class="caption-subject font-dark bold uppercase">แก้ไขข้อมูลส่วนตัว</span>
						<span class="caption-helper"></span>
					</div>

				</div>

				<form method='post' action='profile.php' enctype="multipart/form-data">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-xs-12">

								<div class="form-group">
									<div class="row">
										<div class="col-lg-2 col-xs-12"><label>คำนำหน้า</label><input type="text" class="form-control" placeholder="คำนำหน้า" name='prefix' value='<?= $row2['prefix'] ?>'></div>
										<div class="col-lg-3 col-xs-12"><label>ชื่อ</label><input type="text" class="form-control" placeholder="ชื่อ" name='name' value='<?= $row2['name'] ?>'></div>
										<div class="col-lg-3 col-xs-12"><label>สกุล</label><input type="text" class="form-control" placeholder="นามสกุล" name='surname' value='<?= $row2['surname'] ?>'></div>
										<div class="col-lg-3"> <label>ตำแหน่ง</label><input type="text" class="form-control" placeholder="ตำแหน่ง" name='position' value='<?= $row2['position'] ?>'></div>
										<div class="col-lg-3"><label>สาขา / ฝ่าย</label><select name='department_id' id='department_id' class="form-control">
												<option value="0">กรุณาระบุ สาขา / ฝ่าย </option>
												<?
												$sql = "SELECT * FROM department 
WHERE 
	department.mark_del = 0 
ORDER BY 
	department.is_branch, 
	department.department_name";
												$result1 = mysqli_query($connect, $sql);
												while ($row1 = mysqli_fetch_array($result1)) {
												?>
													<option value="<?= $row1['department_id'] ?>" <? if ($row1['department_id'] == $row2['department_id']) echo 'selected' ?>><?= $row1['department_name'] ?></option>
												<?		} ?>
											</select></div>
										<div class="col-lg-2"><label>โทรศัพท์</label><input type="text" class="form-control" placeholder="Tel." name='tel' value='<?= $row2['tel'] ?>'></div>
										<div class="col-lg-2"><label>มือถือ</label><input type="text" class="form-control" placeholder="mobile" name='mobile' value='<?= $row2['mobile'] ?>'></div>
										<div class="col-lg-2"><label>อีเมล</label><input type="text" class="form-control" placeholder="email" name='email' value='<?= $row2['email'] ?>'></div>
										<?php if ($row2['signature'] == '' || $row2['signature'] == null) { ?>
											<div class="col-lg-2"><label>ลายเซ็น (signature)</label><input type="file" class="form-control" placeholder="signature" name='signature'></div>
										<?php } else {  ?>
											<div class="col-lg-2" align="center"><label>ลายเซ็น (signature)</label> <br><a class="confirmEditSig" data-edit-sig="<?= $row2['signature'] ?>" data-edit-id="<?= $row2['user_id'] ?>" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-check" style="font-size: 21px; padding-top:9px;"></span></a> &emsp;|&emsp; <a class="confirmDelete" data-delete-id="<?= $row2['user_id'] ?>" data-delete-file="<?= $row2['signature'] ?>" data-toggle="modal" data-target="#myModalDelete"><span class="glyphicon glyphicon-trash" style="font-size: 17px; padding-top:9px; color:#DD4F42;"></span></a> </div>
											<input type="hidden" class="form-control" name='signature' value="<?= $row2['signature'] ?>">
										<?php } ?>

									</div>
								</div>
								<div class="box-footer">
									<input type='hidden' name='update_id' value='<?= $row2['user_id'] ?>'>
									<button type='submit' name='submit' value='update' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
								</div>


							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
	</form>
<?
}

echo template_footer();
?>
<form method='post' action='profile.php' enctype="multipart/form-data">
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;">แก้ไขลายเซ็น</h4>
				</div>
				<div class="modal-body" align="center">
					<div>
						<img src="" id="imageEditShow" style="width: 350px;">
						<hr>
					</div>
					<span>กรุณาเลือกรูปภาพ : </span> &nbsp;<input type='file' name='edit_signature' id="edit_signature" style="display: inline;">
					<input type="hidden" name="editSignature" id="editSignature">
					<input type="hidden" name="editUserId" id="editUserId">
				</div>
				<div class="modal-footer">
					<div class="row" align="center">
						<button type='submit' name='submit' value='editSignature' class="btn btn-primary"><i class='fa fa-save'></i> ยืนยันการแก้ไข</button>
					</div>

				</div>
			</div>

		</div>
	</div>
</form>
<form method='post' action='profile.php' enctype="multipart/form-data">
	<div id="myModalDelete" class="modal" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background-color:#E73D4A;color:#FFFFFF;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-exclamation-sign"></span></h4>
				</div>
				<div class="modal-body" align="center">
					<span>กรุณายืนยันการลบข้อมูลรายเซ็น !</span>
					<input type="hidden" name="deleteUserId" id="deleteUserId">
					<input type="hidden" name="deleteFileName" id="deleteFileName">

				</div>
				<div class="modal-footer">
					<div class="row" align="center">
						<button type='submit' name='submit' value='deleteSignature' class="btn btn-danger"><i class='fa fa-save'></i> ยืนยันการลบข้อมูล</button>
					</div>

				</div>
			</div>

		</div>
	</div>
</form>
<script>
	$(".confirmDelete").on("click", function() {
		var deleteId = $(this).attr('data-delete-id');
		$('#deleteUserId').val(deleteId);
		var deleteFile = $(this).attr('data-delete-file');
		$('#deleteFileName').val(deleteFile);

	});
	$(".confirmEditSig").on("click", function() {
		var editId = $(this).attr('data-edit-id');
		$('#editUserId').val(editId);
		var editSignatures = $(this).attr('data-edit-sig');
		$('#editSignature').val(editSignatures);
		document.getElementById("imageEditShow").src = 'signature_file/' + editSignatures;

	});
</script>