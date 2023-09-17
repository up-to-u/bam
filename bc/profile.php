<?

include('inc/include.inc.php');

echo template_header();



$action = intval($_GET['action']);

$edit_id = intval($_GET['edit_id']);

$del_id = intval($_GET['del_id']);

$update_id = intval($_POST['update_id']);

$action = $_GET['action'];

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
} else if ($submit == 'editSignature' && $_FILES["edit_signature"]["name"] != "") {


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

	if ($_FILES["edit_signature"]["size"] > 1000000000000) {

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
		$pathRemove = 'signature_file/' . $editSignature;

		$checkMove = unlink($pathRemove);


		if (move_uploaded_file($_FILES["edit_signature"]["tmp_name"], $target_file)) {
		} else {

			echo "Sorry, there was an error uploading your file. ";
		}
	}



	if ($uploadOk != 0) {



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
} else if ($submit == 'update' && $update_id > 0) {

	$prefix = ($_POST['prefix']);

	$name = ($_POST['name']);

	$surname = ($_POST['surname']);

	$tel = ($_POST['tel']);

	$mobile = ($_POST['mobile']);

	$email = ($_POST['email']);

	$position = ($_POST['position']);

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

		if ($_FILES["signature"]["size"] > 1000000000000) {

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



	savelog('USER-UPDATEPROFILE|user_id|' . $user_id . '|');



	if ($uploadOk != 0) {

		if ($_POST['signature'] != '' || $_POST['signature'] != null) {

			$signatures = $_POST['signature'];
		} else {

			$signatures = $new_name;
		}



		$sql = "UPDATE `user` SET 

		`prefix`=?,

		`name`=?,

		`surname`=?,

		`tel`=?,

		`mobile`=?,

		`signature`=?,

		last_modify = now() 

		WHERE

		user_id = ? ";



		$stmt = $connect->prepare($sql);

		if ($stmt) {

			$stmt->bind_param('ssssssi', $prefix, $name, $surname, $tel, $mobile, $signatures, $update_id);

			$q = $stmt->execute();

			$qx = ($qx and $q);



			if ($qx) {

				mysqli_commit($connect);

				echo "<font color='green'><b>ระบบได้บันทึกข้อมูลของท่านแล้ว</b></font>";
			} else {

				echo "<font color='red'><b>ไม่สามารถบันทึกข้อมูลได้กรุณาตรวจสอบลายเซ็นให้ถูกต้อง</b></font>";
			}
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
} else if ($submit == 'update_password' && $update_id > 0) {

	$pass_old = ($_POST['pass_old']);

	$pass_new = ($_POST['pass_new']);

	$pass_confirm = ($_POST['pass_confirm']);



	if ($pass_old != '' && $pass_new != '' && $pass_confirm != '') {

		if ($pass_old != $pass_new) {

			if ($pass_new == $pass_confirm) {



				$sql = "SELECT * FROM user WHERE user_id = ? ";

				$stmt = $connect->prepare($sql);

				if ($stmt) {

					$stmt->bind_param('i', $user_id);

					$stmt->execute();

					$result = $stmt->get_result();

					if ($row = mysqli_fetch_assoc($result)) {



						if (ver_password($pass_old, $row['password'])) {

							$newpass2 = hash_password($pass_new);



							$qx = true;

							mysqli_autocommit($connect, FALSE);



							$sql = "UPDATE `user` SET 

							`password` = ?, 

							`password_change_date` = NOW(), 

							`password_expire_date` = DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY) 

							WHERE user_id = ? ";

							$stmt = $connect->prepare($sql);

							if ($stmt) {

								$stmt->bind_param('si', $newpass2, $user_id);

								$q = $stmt->execute();

								$qx = ($qx and $q);



								if ($qx) {

									set_password_expire('0');

									mysqli_commit($connect);

									//echo "<font color='green'><b>ระบบได้บันทึกข้อมูลรหัสผ่านใหม่ของท่านแล้ว กรุณาใช้รหัสผ่านใหม่ในการ login ครั้งถัดไป</b></font>";

									savelog('USER-CHANGEPASSWD|user_id|' . $user_id . '|');



									echo '<script language="JavaScript">document.location="admin.php?action=logout";</script>';

									header("Location: admin.php?action=logout");

									exit;
								} else {

									mysqli_rollback($connect);
								}
							} else {

								echo "<b><font color='red'>เกิดข้อผิดพลาด</font></b><br><br>";
							}
						} else {

							echo "<b><font color='red'>เกิดข้อผิดพลาด รหัสผ่านเดิมไม่ถูกต้อง</font></b><br><br>";
						}
					} else {

						echo "<b><font color='red'>เกิดข้อผิดพลาด ไม่พบผู้ใช้งานในระบบ กรุณา login ใหม่</font></b><br><br>";
					}
				} else {

					echo "<b><font color='red'>เกิดข้อผิดพลาด</font></b><br><br>";
				}
			} else {

				echo "<b><font color='red'>เกิดข้อผิดพลาด รหัสยืนยันไม่ถูกต้อง</font></b><br><br>";
			}
		} else {

			echo "<b><font color='red'>เกิดข้อผิดพลาด รหัสผ่านใหม่ ต้องไม่เหมือนรหัสผ่านเดิม</font></b><br><br>";
		}
	} else {

		echo "<b><font color='red'>เกิดข้อผิดพลาด ท่านไม่ได้ระบุข้อมูลให้ครบถ้วน ระบบไม่สามารถบันทึกข้อมูลได้</font></b><br><br>";
	}
}



if ($action == 'changepassword') {

	$is_password_expire = get_password_expire();

?>

	<script language='JavaScript'>
		function checkform() {

			if ($('#pass_old').val() == '') {

				alert('กรุณาระบุ รหัสผ่านเดิม');

				$('#pass_old').focus();

				return false;

			}

			if ($('#pass_new').val() == '') {

				alert('กรุณาระบุ รหัสผ่านใหม่');

				$('#pass_new').focus();

				return false;

			}

			if ($('#pass_new').val().length < 8) {

				alert('รหัสใหม่ ไม่น้อยกว่า 8 ตัวอักษร');

				$('#pass_new').focus();

				return false;

			}

			if ($('#pass_new').val() != $('#pass_confirm').val()) {

				alert('รหัสผ่านใหม่ และยืนยันรหัสผ่านใหม่ ไม่ตรงกัน');

				$('#pass_confirm').focus();

				return false;

			}



			if (confirm("เมื่อเปลี่ยนรหัสผ่านแล้ว ระบบจะ Logout และให้ท่าน Login ใหม่ กรุณายืนยัน?")) {

				return true;

			}



			return false;

		}
	</script>



	<div class="row">

		<div class="col-lg-12 col-lg-12 col-sm-12">

			<div class="portlet light tasks-widget bordered">

				<div class="portlet-title">

					<div class="caption">

						<i class="icon-share font-dark hide"></i>

						<span class="caption-subject font-dark bold uppercase">เปลี่ยนรหัสผ่าน</span>

						<span class="caption-helper"></span>

					</div>

				</div>



				เมื่อเปลี่ยนรหัสผ่านแล้ว ระบบจะ Logout และท่านจำเป็นจะต้อง Login ใหม่<br>

				<? if ($is_password_expire) { ?>

					<b>
						<font color='red'>รหัสผ่านของคุณหมดอายุแล้ว กรุณาเปลี่ยนรหัสผ่านทันที</font>
					</b><br>

				<? } ?>



				<br>

				<form method='post' action='profile.php' enctype="multipart/form-data" onsubmit='return checkform()'>

					<div class="form-group">

						<div class="row">

							<div class="col-lg-2">

								<label>รหัสผ่านเดิม</label>

								<input type="password" class="form-control" placeholder="รหัสผ่านเดิม" name='pass_old' id='pass_old' value='' maxlength='20' required>

							</div>

						</div>

					</div>

					<div class="form-group">

						<div class="row">

							<div class="col-lg-2">

								<label>รหัสผ่านใหม่</label>

								<input type="password" class="form-control" placeholder="รหัสผ่านใหม่" name='pass_new' id='pass_new' value='' maxlength='20' required>

							</div>

						</div>

					</div>

					<div class="form-group">

						<div class="row">

							<div class="col-lg-2">

								<label>ยืนยัน รหัสผ่านใหม่</label>

								<input type="password" class="form-control" placeholder="ยืนยัน รหัสผ่านใหม่" name='pass_confirm' id='pass_confirm' value='' maxlength='20' required>

							</div>

						</div>

					</div>

					<br>

					<br>



					<input type='hidden' name='update_id' value='<?= $user_id ?>'>

					<button type='submit' name='submit' value='update_password' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>

					<a href='profile.php' class='btn btn-default'><i class='fa fa-arrow-left'></i> ย้อนกลับ</a>

				</form>

			</div>

		</div>

	</div>



	<?

	echo template_footer();

	exit;
}





$sql = "SELECT * FROM `user` WHERE user_id = ? ";

$stmt = $connect->prepare($sql);

if ($stmt) {

	$stmt->bind_param('i', $user_id);

	$stmt->execute();

	$result2 = $stmt->get_result();

	if ($row2 = mysqli_fetch_assoc($result2)) {

	?>



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

								<div class="col-lg-2 col-xs-12"><label>คำนำหน้า</label><input type="text" class="form-control" placeholder="คำนำหน้า" name='prefix' value='<?= $row2['prefix'] ?>'></div>

								<div class="col-lg-3 col-xs-12"><label>ชื่อ</label><input type="text" class="form-control" placeholder="ชื่อ" name='name' value='<?= $row2['name'] ?>'></div>

								<div class="col-lg-3 col-xs-12"><label>สกุล</label><input type="text" class="form-control" placeholder="นามสกุล" name='surname' value='<?= $row2['surname'] ?>'></div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<div class="col-lg-3"><label>ตำแหน่ง</label><input type="text" class="form-control" placeholder="ตำแหน่ง" name='position' value='<?= $row2['position'] ?>' readonly></div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<div class="col-lg-2"><label>โทรศัพท์</label><input type="text" class="form-control" placeholder="Tel." name='tel' value='<?= $row2['tel'] ?>'></div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<div class="col-lg-2"><label>มือถือ</label><input type="text" class="form-control" placeholder="mobile" name='mobile' value='<?= $row2['mobile'] ?>'></div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<div class="col-lg-3"><label>อีเมล</label><input type="text" class="form-control" placeholder="email" name='email' value='<?= $row2['email'] ?>' readonly></div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<?php if ($row2['signature'] == '' || $row2['signature'] == null) { ?>

									<div class="col-lg-2"><label>ลายเซ็น (signature)</label><input type="file" class="form-control" placeholder="signature" name='signature'></div>

								<?php } else {  ?>

									<div class="col-lg-2" align="center"><label>ลายเซ็น (signature)</label> <br><a class="confirmEditSig" data-edit-sig="<?= $row2['signature'] ?>" data-edit-id="<?= $row2['user_id'] ?>" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-check" style="font-size: 21px; padding-top:9px;"></span></a> &emsp;|&emsp; <a class="confirmDelete" data-delete-id="<?= $row2['user_id'] ?>" data-delete-file="<?= $row2['signature'] ?>" data-toggle="modal" data-target="#myModalDelete"><span class="glyphicon glyphicon-trash" style="font-size: 17px; padding-top:9px; color:#DD4F42;"></span></a> </div>

									<input type="hidden" class="form-control" name='signature' value="<?= $row2['signature'] ?>">

								<?php } ?>

							</div>

						</div>

						<br>

						<br>

						<div class="box-footer">

							<input type='hidden' name='update_id' value='<?= $row2['user_id'] ?>'>

							<button type='submit' name='submit' value='update' class="btn btn-success"><i class='fa fa-save'></i> บันทึก</button>

							<a href='profile.php?action=changepassword' class='btn btn-danger'><i class='fa fa-lock'></i> เปลี่ยนรหัสผ่าน</a>

						</div>



					</form>

				</div>

			</div>

		</div>

<?

	}
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