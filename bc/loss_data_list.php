<?
include('inc/include.inc.php');

echo template_header();
$action = $_GET['action'];
$statusListId = $_GET['statusListId'];
$m = $_POST['m'];
$y = $_POST['y'];
$listId = $_POST['listId'];
$today =date("Y-m-d");
if ($_POST['submit0'] != null) {
	$statusListId = "0";
} else if ($_POST['submit2'] != null) {
	$statusListId = "2";
} else if ($_POST['submit1'] != null) {

	$statusListId = "1";
}
if ($_POST['checkStatus'] != null) {
	$checkStatuss = "Y";
}


 $sql = "SELECT * FROM department 
WHERE department.department_id=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $dep_id);
$stmt->execute();
$res1 = $stmt->get_result();
if ($row_mem = $res1->fetch_assoc()) {
	$department_level_id = $row_mem['department_level_id'];
	$department_name = $row_mem['department_name'];
	$groupName = $row_mem['group_name'];
	$division_name = $row_mem['division_name'];
	$department_id = ($row_mem['department_id']);
if ($department_level_id=='5'){
	$dep_id = getParentID($row_mem['department_id']);
} elseif ($department_level_id=='4'){
	$dep_id = ($row_mem['department_id']);
}
}

//echo $dep_id;


if ($_POST['submitLossDataList'] == 'submitLossDataList') {

	$listId = $_POST['listId'];
	$m = $_POST['m'];
	$y = $_POST['y'];
	$loss_data_doc_list_id = $_POST['edit_data_id'];

	$happen_date = $_POST['edit_happen_date'];
	$checked_date = $_POST['edit_checked_date'];
	$incidence = $_POST['edit_incidence'];
	$incidence_detail = $_POST['edit_incidence_detail'];
	$cause = $_POST['edit_cause'];
	$user_effect = $_POST['edit_user_effect'];
	$damage_type = $_POST['edit_damage_type'];
	$incidence_type = $_POST['edit_incidence_type'];
	$loss_type = $_POST['edit_loss_type'];
	$control = $_POST['edit_control'];
	$loss_values = $_POST['edit_loss_value'];
	$loss_value = str_replace(",", "", $loss_values);
	$chance = $_POST['csa_likelihood_id1'];
	$effect = $_POST['csa_impact_id1'];

	$damageLevel = $effect.$chance;
	$displayRisk = $_POST['loss_text_level'];
	$dep_id_1 = $_POST['edit_dep_id_1'];
	$dep_id_2 = $_POST['edit_dep_id_2'];
	$dep_id_3 = $_POST['edit_dep_id_3'];
	$attech_name = $_FILES["edit_attech_name"]["name"];

	if (($_POST['edit_attech_name_file'] != null || $_POST['edit_attech_name_file'] != "") && ($attech_name == '' || $attech_name == null)) {
		$attech_name = $_POST['edit_attech_name_file'];
		$uploadOk = 1;
	} else {
		$attech_name = ($_FILES["edit_attech_name"]["name"]);
		$uploadOk = 1;
		if ($attech_name != "" || $attech_name != null) {
			$target_dir = "attech_file/";
			$target_file = $target_dir . basename($_FILES["edit_attech_name"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			$new_name = $user_id . '1_ATTECH' . date('Ymdhis') . '.' . $imageFileType;
			$target_file = $target_dir . $new_name;
			$check = getimagesize($_FILES["edit_attech_name"]["tmp_name"]);
			$attech_name = $new_name;

			if ($_FILES["edit_attech_name"]["size"] > 50000000) {
				echo "Sorry, your file is too large. ";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded. ";
				// if everything is ok, try to upload file
			} else {

				if (move_uploaded_file($_FILES["edit_attech_name"]["tmp_name"], $target_file)) {
				} else {
					echo "Sorry, there was an error uploading your file. ";
				}
			}
		}
	}
	$attech_name2 = $_FILES["edit_attech_name2"]["name"];
	if (($_POST['edit_attech_name_file2'] != null || $_POST['edit_attech_name_file2'] != "")  && ($attech_name2 == '' || $attech_name2 == null)) {
		$attech_name2 = $_POST['edit_attech_name_file2'];
		$uploadOk2 = 1;
	} else {
		$attech_name2 = ($_FILES["edit_attech_name2"]["name"]);
		$uploadOk2 = 1;
		if ($attech_name2 != "" || $attech_name2 != null) {
			$target_dir2 = "attech_file/";
			$target_file2 = $target_dir2 . basename($_FILES["edit_attech_name2"]["name"]);
			$imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
			$new_name2 = $user_id . '2_ATTECH' . date('Ymdhis') . '.' . $imageFileType2;
			$target_file2 = $target_dir . $new_name2;
			$check2 = getimagesize($_FILES["edit_attech_name2"]["tmp_name"]);
			$attech_name2 = $new_name2;

			if ($_FILES["edit_attech_name2"]["size"] > 50000000) {
				echo "Sorry, your file2 is too large. ";
				$uploadOk2 = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk2 == 0) {
				echo "Sorry, your file2 was not uploaded. ";
				// if everything is ok, try to upload file
			} else {

				if (move_uploaded_file($_FILES["edit_attech_name2"]["tmp_name"], $target_file2)) {
				} else {
					echo "Sorry, there was an error uploading your file2. ";
				}
			}
		}
	}

	if ($uploadOk != 0 || $uploadOk2 != 0) {
		$qx = true;

		$stmt = $connect->prepare("UPDATE loss_data_doc_list SET
		happen_date=?,
		checked_date=?,
		incidence=?,
		incidence_detail=?,
		cause=?,
		user_effect=?,
		damage_type=?,
		incidence_type=?,
		loss_type=?,
		control=?,
		loss_value=?,
		chance=?,
		effect=?,
		displayRisk=?,
		damageLevel=?,
		dep_id_1=?,
		dep_id_2=?,
		dep_id_3=?,
		attech_name=?,
		attech_name2=?,
		doclist_user_id=?
		WHERE loss_data_doc_list_id=? ");

		if ($stmt) {
			$stmt->bind_param(
				'ssssssiiisdiissiiissii',
				$happen_date,
				$checked_date,
				$incidence,
				$incidence_detail,
				$cause,
				$user_effect,
				$damage_type,
				$incidence_type,
				$loss_type,
				$control,
				$loss_value,
				$chance,
				$effect,
				$displayRisk,
				$damageLevel,
				$dep_id_1,
				$dep_id_2,
				$dep_id_3,
				$attech_name,
				$attech_name2,
				$user_id,
				$loss_data_doc_list_id
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();
				echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');</script>";
				if ($statusListId == '' && $listId == '') {
					echo '<script>window.location.replace("loss_data.php")</script>';
					savelog('LOSS-USER-UPDATELossList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|');
				}
			} else {
				$connect->rollback();
			}
			//	$stmt->close();
			//	$conn->close();
		} else {
			$error = "<script>alert('เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้'); </script>";
		}
	}
}

if ($_POST['buttonapproveSendCase'] == 'buttonapproveSendCase') {

	$buttonapprove = 0;
	$listId = $_POST['listId'];
	$m = $_POST['m'];
	$y = $_POST['y'];
	$loss_data_doc_list_id = $_POST['edit_data_id'];

	$happen_date = $_POST['edit_happen_date'];
	$checked_date = $_POST['edit_checked_date'];
	$incidence = $_POST['edit_incidence'];
	$incidence_detail = $_POST['edit_incidence_detail'];
	$cause = $_POST['edit_cause'];
	$user_effect = $_POST['edit_user_effect'];
	$damage_type = $_POST['edit_damage_type'];
	$incidence_type = $_POST['edit_incidence_type'];
	$loss_type = $_POST['edit_loss_type'];
	$control = $_POST['edit_control'];
	$loss_values = $_POST['edit_loss_value'];
	$loss_value = str_replace(",", "", $loss_values);
	$chance = $_POST['csa_likelihood_id1'];
	$effect = $_POST['csa_impact_id1'];

	$damageLevel = $effect.$chance;
	$displayRisk = $_POST['loss_text_level'];
	$dep_id_1 = $_POST['edit_dep_id_1'];
	$dep_id_2 = $_POST['edit_dep_id_2'];
	$dep_id_3 = $_POST['edit_dep_id_3'];

	$attech_name = $_FILES["edit_attech_name"]["name"];

	if (($_POST['edit_attech_name_file'] != null || $_POST['edit_attech_name_file'] != "") && ($attech_name == '' || $attech_name == null)) {
		$attech_name = $_POST['edit_attech_name_file'];
		$uploadOk = 1;
	} else {
		$attech_name = ($_FILES["edit_attech_name"]["name"]);
		$uploadOk = 1;
		if ($attech_name != "" || $attech_name != null) {
			$target_dir = "attech_file/";
			$target_file = $target_dir . basename($_FILES["edit_attech_name"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			$new_name = $user_id . '1_ATTECH' . date('Ymdhis') . '.' . $imageFileType;
			$target_file = $target_dir . $new_name;
			$check = getimagesize($_FILES["edit_attech_name"]["tmp_name"]);
			$attech_name = $new_name;

			if ($_FILES["edit_attech_name"]["size"] > 50000000) {
				echo "Sorry, your file is too large. ";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded. ";
				// if everything is ok, try to upload file
			} else {

				if (move_uploaded_file($_FILES["edit_attech_name"]["tmp_name"], $target_file)) {
				} else {
					echo "Sorry, there was an error uploading your file. ";
				}
			}
		}
	}
	$attech_name2 = $_FILES["edit_attech_name2"]["name"];
	if (($_POST['edit_attech_name_file2'] != null || $_POST['edit_attech_name_file2'] != "")  && ($attech_name2 == '' || $attech_name2 == null)) {
		$attech_name2 = $_POST['edit_attech_name_file2'];
		$uploadOk2 = 1;
	} else {
		$attech_name2 = ($_FILES["edit_attech_name2"]["name"]);
		$uploadOk2 = 1;
		if ($attech_name2 != "" || $attech_name2 != null) {
			$target_dir2 = "attech_file/";
			$target_file2 = $target_dir2 . basename($_FILES["edit_attech_name2"]["name"]);
			$imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
			$new_name2 = $user_id . '2_ATTECH' . date('Ymdhis') . '.' . $imageFileType2;
			$target_file2 = $target_dir . $new_name2;
			$check2 = getimagesize($_FILES["edit_attech_name2"]["tmp_name"]);
			$attech_name2 = $new_name2;

			if ($_FILES["edit_attech_name2"]["size"] > 50000000) {
				echo "Sorry, your file2 is too large. ";
				$uploadOk2 = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk2 == 0) {
				echo "Sorry, your file2 was not uploaded. ";
				// if everything is ok, try to upload file
			} else {

				if (move_uploaded_file($_FILES["edit_attech_name2"]["tmp_name"], $target_file2)) {
				} else {
					echo "Sorry, there was an error uploading your file2. ";
				}
			}
		}
	}
	if ($uploadOk != 0 || $uploadOk2 != 0) {
		$qx = true;

		$stmt = $connect->prepare("UPDATE loss_data_doc_list SET
		happen_date=?,
		checked_date=?,
		incidence=?,
		incidence_detail=?,
		cause=?,
		user_effect=?,
		damage_type=?,
		incidence_type=?,
		loss_type=?,
		control=?,
		loss_value=?,
		chance=?,
		effect=?,
		displayRisk=?,
		damageLevel=?,
		dep_id_1=?,
		dep_id_2=?,
		dep_id_3=?,
		status_approve=?,
		attech_name=?,
		attech_name2=?,
		doclist_user_id=?
		WHERE loss_data_doc_list_id=? ");

		if ($stmt) {
			$stmt->bind_param(
				'ssssssiiisdiissiiiissii',
				$happen_date,
				$checked_date,
				$incidence,
				$incidence_detail,
				$cause,
				$user_effect,
				$damage_type,
				$incidence_type,
				$loss_type,
				$control,
				$loss_value,
				$chance,
				$effect,
				$displayRisk,
				$damageLevel,
				$dep_id_1,
				$dep_id_2,
				$dep_id_3,
				$buttonapprove,
				$attech_name,
				$attech_name2,
				$user_id,
				$loss_data_doc_list_id
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();
				echo "<script>alert('ระบบได้บันทึกและส่งอนุมัติข้อมูลของท่านเรียบร้อย');</script>";
				if ($statusListId == '' && $listId == '') {
					echo '<script>window.location.replace("loss_data.php")</script>';
					savelog('LOSS-USER-UPDATELossList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|');
					savelog('LOSS-USER-SENTAPPROVELossList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|');
					
					
						$sql1 = "SELECT user_id FROM user 
						where department_id =$department_id and auth_loss ='2'";
						$result1 = mysqli_query($connect, $sql1);
						while ($row1 = mysqli_fetch_array($result1)) {
						$mailapprove = getEmail($row1['user_id']);
						if($mailapprove!=''){
						$email_from = 'noreply-lossdata@bam.co.th';
						$cc = array();		
						$bcc = array();		
						$to = array($mailapprove);

						$subject = 'ท่านมีรายงานเหตุการณ์ความเสียหายรออนุมัติใหม่ [LOSS DATA]';
						$body = 'เรียน ผู้อนุมัติเหตุการณ์ความเสียหาย '.$department_name.'<br><br>
						เนื่องจากวันที่ '.mysqldate2th_date($today).'ฝ่ายของท่านมีการรายงานเหตุการณ์ความเสียหาย กรุณาเข้าสู่ระบบเพื่อดำเนินการตรวจสอบและอนุมัติรายการ
						<br>จึงแจ้งมายังท่านเพื่อมราบ โปรดดำเนินและการอนุมัติต่อไป<br><br><a href="'.$url_prefix.'/loss_data_approve.php" target="_new"> คลิ๊กเพื่อเข้าสู่ระบบ</a> ';
						$x = @mail_service($email_from,$to,$cc,$bcc,$subject,$body,$attach_name,$attach_location);		
						if ($x) {
							echo "<font color='#00aa00'><b>ระบบได้ส่ง E-mail เรียบร้อยแล้ว</b></font><br>";
							} else {
								echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารส่ง E-mail ได้</b></font><br>";
								}		
								} else {
									echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ไม่พบ Email ผู้อนุมัติ กรุณาติดต่อเจ้าหน้าที่ฝ่ายบริหารความเสี่ยง</b></font><br>";
								}
						}
						
				}
			} else {
				$connect->rollback();
			}
			//	$stmt->close();
			//	$conn->close();
		} else {
			$error = "<script>alert('เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้'); </script>";
		}
	}
}

?>
<style>
	.margins-3 {
		margin-top: 15px;
		margin-bottom: 15px;
	}

	.margins-top-10 {
		margin-top: 10px;
		font-weight: bold;
		color: #004C85;
	}

	.font-green {
		color: #261ecd !important;
	}

	.dashboard-stat.green {
		background-color: #26c281 !important;
	}

	.label-main {
		font-weight: bold;
		color: #004C85;
	}

	.modal .modal-header {
		border-bottom: 1px solid #EFEFEF !important;
		background-color: #004C85 !important;
	}

	.box-matrix {
		width: 70px;
		height: 70px;
		align-items: center;
		text-align: center;
	}

	.box-matrix-border {
		border: solid 1px #323233;
	}

	.text-matrix {
		font-size: 14px;
		font-weight: 700;
	}

	.card-body.show {
		display: block;
	}

	.card {
		padding-bottom: 20px;
		box-shadow: 2px 2px 6px 0px rgb(200, 167, 216);

	}

	.radio {
		display: inline-block;
		border-radius: 0;
		box-sizing: border-box;
		cursor: pointer;
		color: #000;
		font-weight: 500;
		-webkit-filter: grayscale(0%);
		-moz-filter: grayscale(0%);
		-o-filter: grayscale(0%);
		-ms-filter: grayscale(0%);
		filter: grayscale(0%);

	}

	input[type="radio"] {
		-ms-transform: scale(2);
		/* IE 9 */
		-webkit-transform: scale(2);
		/* Chrome, Safari, Opera */
		transform: scale(2);
	}

	.radio:hover {
		box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.1);
	}

	.radio.selected {
		box-shadow: 0px 8px 16px 0px #EEEEEE;
		-webkit-filter: grayscale(0%);
		-moz-filter: grayscale(0%);
		-o-filter: grayscale(0%);
		-ms-filter: grayscale(0%);
		filter: grayscale(0%);

	}

	.radio1.selected {
		box-shadow: 0px 8px 16px 0px #EEEEEE;
		-webkit-filter: grayscale(0%);
		-moz-filter: grayscale(0%);
		-o-filter: grayscale(0%);
		-ms-filter: grayscale(0%);
		filter: grayscale(0%);
		border: 4px solid #56c024;

	}

	.radio2.selected {
		box-shadow: 0px 8px 16px 0px #EEEEEE;
		-webkit-filter: grayscale(0%);
		-moz-filter: grayscale(0%);
		-o-filter: grayscale(0%);
		-ms-filter: grayscale(0%);
		filter: grayscale(0%);
		border: 4px solid #e7505a;

	}

	.selected {
		background-color: #eff5fa;
	}

	.a {
		justify-content: center !important;
	}


	.btn {
		border-radius: 0px;

	}

	.btn,
	.btn:focus,
	.btn:active {
		outline: none !important;
		box-shadow: none !important;
	}

	.label-main {
		font-weight: bold;
		color: #004C85;
	}

	.dashboard-stat.green {
		background-color: #26c281 !important;
	}
</style>

<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">ข้อมูลรายการเหตุการณ์ </span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12" align='center' style='padding:30px'>
					<?
					if ($listId != NULL) {
						echo "<span style='font-size: 20px;margin:10px;'>ข้อมูลเหตุการณ์ประจำเดือน " . month_name($m) . " ปี " . $y . "</span><br>";
					} elseif ($statusListId != NULL) {
						if ($statusListId == 0) {
							echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลรออนุมัติทั้งหมด</span><br>";
						} elseif ($statusListId == 2) {
							echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลส่งกลับแก้ไขทั้งหมด</span><br>";
						} elseif ($statusListId == 1) {
							echo "<span style='font-size: 20px;margin:10px;'>รายการอนุมัติแล้วทั้งหมด</span><br>";
						}
					}								?>
					<?
					if (checkLossList($listId) > 0) {
					?>
						<!--<form action='export.php?listId=<?= $listId ?>&m=<?= $m; ?>&y=<?= $y; ?>' method="post">
							<button type="submit" class="pull-right btn btn-dark" style="margin: 10px;"> <span class="glyphicon glyphicon-download-alt"></span> Export Data</button>
						</form>-->
					<?  } 		?>


				</div>

				<div class="col-lg-12" style="overflow-x:auto;">
					<!-- start table -->

					<table id="exampleDataTable" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th style='width:5%'>ลำดับ</th>
								<th style='width:35%'>เหตุการณ์</th>
								<th style='width:10%'>รายงานโดย</th>
								<th style='width:5%'>วันที่บันทึก</th>
								<th style='width:5%'>ระดับความเสียหาย</th>
								<? if ($listId != NULL) { ?> <th style='width:10%'>สถานะจากผู้อนุมัติ</th> <? } ?>
								<? if ($statusListId == 1) { ?> <th style='width:10%'>สถานะจากฝ่ายความเสี่ยง</th> <? } ?>
								<? if ($statusListId == 1) { ?> <th style='width:10%'>วันที่ปิด CASE</th> <? } ?>
								<th style='width:10%'>จัดการ</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($statusListId == "0" || $statusListId == "1" || $statusListId == "2") {

								$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.loss_data_doc_id = $listId and loss_data_doc.loss_dep = $dep_id and loss_data_doc_list.status_approve = $statusListId";
							} elseif ($listId != NULL) {

								$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.loss_data_doc_id = $listId and loss_data_doc.loss_dep =$dep_id";
							} elseif ($statusListId != NULL) {

								$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.status_approve = $statusListId and loss_data_doc.loss_dep =$dep_id";
							}

							$z = 0;
							$stmt = $connect->prepare($sql);
							$stmt->execute();
							$result = $stmt->get_result();
							while ($row = mysqli_fetch_array($result)) {
								$status_approve = $row['status_approve'];
								$status_risk_approve = $row['status_risk_approve'];
								$z++;
							?>
								<tr>
									<td style="vertical-align: middle;"><?= $z; ?></td>
									<td style="vertical-align: middle;">
										<div class="col-lg-12"><?= $row['incidence']; ?></div>
									</td>
									<td style="vertical-align: middle;"><?= get_user_name($row['doclist_user_id']); ?></td>
									<td style="vertical-align: middle;"><?= mysqldate2th_datetime($row['loss_data_doc_createdate']); ?></td>

									<?php
									$numImpact = $row['effect'] . $row['chance'];
									if (checkLossLevel((int)$numImpact) == 1) {
										echo '<td align="center" style="vertical-align: middle; background-color: #00B050; color:#FFFFFF;"> ต่ำ </td>';
									} else if (checkLossLevel((int)$numImpact) == 2) {
										echo '<td align="center" style="vertical-align: middle; background-color: #FFFF00;color:#000000;"> ปานกลาง</td>';
									} else if (checkLossLevel((int)$numImpact) == 3) {
										echo '<td align="center" style="vertical-align: middle; background-color: #FF9400;color:#000000;"> สูง </td>';
									} else if (checkLossLevel((int)$numImpact) == 4) {
										echo '<td align="center" style="vertical-align: middle; background-color: #FF0000;color:#FFFFFF;"> สูงมาก </td>';
									} else {
										echo '<td align="center"> - </td>';
									}
									?>

									<? if ($listId != NULL) { ?>
										<td>
											<? if ($status_approve == '0') { ?>รออนุมัติ <? } ?>
										<? if ($status_approve == '1') { ?>อนุมัติแล้ว <? } ?>
									<? if ($status_approve == '2') { ?>แก้ไข <? } ?></td>
									<? } ?>
									<? if ($statusListId == 1) { ?> <td>
											<? if ($status_risk_approve == '0') { ?>รออนุมัติ<? } ?>
											<? if ($status_risk_approve == '1') { ?>ดำเนินการแล้วเสร็จ <? } ?>
										<? if ($status_risk_approve == '2') { ?>แก้ไข <? } ?></td> <? } ?>

									<? if ($statusListId == 1) { ?> <td><?= mysqldate2th_datetime($row['end_date']); ?></td> <? } ?>
									<td>
										<? if ($status_approve == '2') { ?>

										<? } else { ?>
											<button name='submit' class="btn btn-success showDetailData" data-happen_date="<?= $row['happen_date']; ?>" data-checked_date="<?= $row['checked_date']; ?>" data-incidence="<?= $row['incidence']; ?>" data-incidence_detail="<?= $row['incidence_detail']; ?>" data-cause="<?= $row['cause']; ?>" data-user_effect="<?= $row['user_effect']; ?>" data-damage_type="<?= $row['damage_type']; ?>" data-incidence_type="<?= $row['incidence_type']; ?>" data-loss_type="<?= $row['loss_type']; ?>" data-control="<?= $row['control']; ?>" data-loss_value="<?= $row['loss_value']; ?>" data-chance="<?= $row['chance']; ?>" data-effect="<?= $row['effect']; ?>" data-damageLevel="<?= $row['damageLevel']; ?>" data-related_dep_id="<?= $row['related_dep_id']; ?>" data-dep_id_1="<?= $row['dep_id_1']; ?>" data-dep_id_2="<?= $row['dep_id_2']; ?>" data-dep_id_3="<?= $row['dep_id_3']; ?>" data-comment_app="<?= $row['comment_app']; ?>" data-approved_date="<?= $row['approved_date']; ?>" data-status_approve="<?= $row['status_approve']; ?>" data-comment_risk="<?= $row['comment_risk']; ?>" data-status_risk_approve="<?= $row['status_risk_approve']; ?>" data-riskcomment_date="<?= $row['riskcomment_date']; ?>" data-attech_name="<?= $row['attech_name']; ?>" data-attech_name2="<?= $row['attech_name2']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-list-alt'></i> รายละเอียด</button>
										<? } ?>

										<? if ($status_approve == '2') { ?>
											<button name='submit' class="btn btn-primary editDetailData" style="width: 110px;" edit-data-id="<?= $row['loss_data_doc_list_id']; ?>" edit-data-happen_date="<?= $row['happen_date']; ?>" edit-data-checked_date="<?= $row['checked_date']; ?>" edit-data-incidence="<?= $row['incidence']; ?>" edit-data-incidence_detail="<?= $row['incidence_detail']; ?>" edit-data-cause="<?= $row['cause']; ?>" edit-data-user_effect="<?= $row['user_effect']; ?>" edit-data-damage_type="<?= $row['damage_type']; ?>" edit-data-incidence_type="<?= $row['incidence_type']; ?>" edit-data-loss_type="<?= $row['loss_type']; ?>" edit-data-control="<?= $row['control']; ?>" edit-data-loss_value="<?= $row['loss_value']; ?>" edit-data-chance="<?= $row['chance']; ?>" edit-data-effect="<?= $row['effect']; ?>" edit-data-damageLevel="<?= $row['damageLevel']; ?>" edit-data-related_dep_id="<?= $row['related_dep_id']; ?>" edit-data-dep_id_1="<?= $row['dep_id_1']; ?>" edit-data-dep_id_2="<?= $row['dep_id_2']; ?>" edit-data-dep_id_3="<?= $row['dep_id_3']; ?>" edit-data-comment_app="<?= $row['comment_app']; ?>" edit-data-approved_date="<?= $row['approved_date']; ?>" edit-data-status_approve="<?= $row['status_approve']; ?>" edit-data-comment_risk="<?= $row['comment_risk']; ?>" edit-data-status_risk_approve="<?= $row['status_risk_approve']; ?>" edit-data-riskcomment_date="<?= $row['riskcomment_date']; ?>" edit-data-attech_name="<?= $row['attech_name']; ?>" edit-data-attech_name2="<?= $row['attech_name2']; ?>" data-toggle="modal" data-target="#editModalSendCase"><i class='glyphicon glyphicon-pencil'></i> แก้ไขข้อมูล</button>


										<? } ?>
										<? if ($status_approve == '1') { ?>
											<form action="pdf.php" method="post" target="_blank" style="display: inline;">
												<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
												<button type='submit' class="btn btn-dark" style="background-color: #949596; color:#FFFFFF; width:110px;">
													<span class="glyphicon glyphicon-print"></span> พิมพ์</button>
											</form>
										<? } ?>
										<? if ($status_approve == '0') { ?>
											<!--	<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
														<button name='submit' class="btn btn-primary editDetailData" style="width: 110px;" 
														edit-data-id="<?= $row['loss_data_doc_list_id']; ?>" edit-data-happen_date="<?= $row['happen_date']; ?>"
														edit-data-checked_date="<?= $row['checked_date']; ?>" edit-data-incidence="<?= $row['incidence']; ?>"
														edit-data-incidence_detail="<?= $row['incidence_detail']; ?>" edit-data-cause="<?= $row['cause']; ?>"
														edit-data-user_effect="<?= $row['user_effect']; ?>" edit-data-damage_type="<?= $row['damage_type']; ?>"
														edit-data-incidence_type="<?= $row['incidence_type']; ?>" edit-data-loss_type="<?= $row['loss_type']; ?>"
														edit-data-control="<?= $row['control']; ?>" edit-data-loss_value="<?= $row['loss_value']; ?>"
														edit-data-chance="<?= $row['chance']; ?>" edit-data-effect="<?= $row['effect']; ?>"
														edit-data-damageLevel="<?= $row['damageLevel']; ?>" edit-data-related_dep_id="<?= $row['related_dep_id']; ?>"
														edit-data-dep_id_1="<?= $row['dep_id_1']; ?>" edit-data-dep_id_2="<?= $row['dep_id_2']; ?>"
														edit-data-dep_id_3="<?= $row['dep_id_3']; ?>" edit-data-comment_app="<?= $row['comment_app']; ?>"
														edit-data-approved_date="<?= $row['approved_date']; ?>" edit-data-status_approve="<?= $row['status_approve']; ?>"
														edit-data-comment_risk="<?= $row['comment_risk']; ?>" edit-data-status_risk_approve="<?= $row['status_risk_approve']; ?>"
														edit-data-riskcomment_date="<?= $row['riskcomment_date']; ?>" edit-data-attech_name="<?= $row['attech_name']; ?>"
														data-toggle="modal" data-target="#editModalSendCase"><i class='glyphicon glyphicon-pencil'></i> แก้ไขข้อมูล</button> -->

										<? } ?>
									</td>

								</tr>
							<? } ?>
						</tbody>

					</table>

					<!-- end table -->


				</div>
				<div class="col-lg-12 col-xs-12 col-sm-12"><br> <a href="loss_data.php" align='center'>
						<span class="glyphicon glyphicon-menu-left"></span><span class="glyphicon glyphicon-menu-left"></span> ย้อนกลับ
					</a>
				</div>
			</div>
		</div>

	</div>

</div>

<!-- start modal edit -->
<div id="editModalSendCase" class="modal fade" role="dialog">
	<div class="modal-dialog  modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="background-color:#27A4B0;color:#FFFFFF;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-pencil"></span> แก้ไขข้อมูล </h4>
			</div>
			<div class="modal-body" align="left">

				<form method='post' onsubmit="return validateForm()" action='loss_data_list.php' enctype="multipart/form-data">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-xs-12">

								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่เกิดเหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='edit_happen_date' readonly id='edit_happen_date'></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" name='edit_checked_date' readonly id='edit_checked_date'></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">เหตุการณ์<span style="color: red;">*</span></label><textarea id="edit_incidence" class="form-control" name="edit_incidence" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="edit_incidence_detail" class="form-control" name="edit_incidence_detail" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">สาเหตุ<span style="color: red;">*</span></label><textarea id="edit_cause" class="form-control" name="edit_cause" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label><textarea id="edit_user_effect" class="form-control" name="edit_user_effect" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-12"> <label class="margins-top-10 label-main"> ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='edit_damage_type' id='edit_damage_type' class="form-control">
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor1 = 1;
												$makdel1 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =? and mark_del =?";
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor1, $makdel1);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {
												?>
													<option value="<?= $row1['loss_factor_id'] ?>@<?= $row1['factor_no'] ?>"><?= $row1['factor'] ?></option>
												<?		} ?>
											</select></div>
										<div class="col-lg-12"><label class="margins-top-10 label-main">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='edit_incidence_type' id='edit_incidence_type' class="form-control">
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor2 = 2;
												$makdel2 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =? and mark_del =?";
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor2, $makdel2);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {
												?>
													<option value="<?= $row1['loss_factor_id'] ?>"><?= $row1['factor'] ?></option>
												<?		} ?>
											</select></div>

										<div class="col-lg-12"><label class="margins-top-10  label-main">ความเสียหาย<span style="color: red;">*</span> </label>
											<div class="col-lg-12"><label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type1" value="1">&nbsp; <b>Actual Loss : </b> ความเสียหายที่เกิดขึ้นจริงทั้งที่เป็นตัวเงิน และไม่เป็นตัวเงิน
												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type2" value="2">&nbsp; <b> Potential Loss : </b>ความเสียหายที่อาจเกิดขึ้น

												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type3" value="3">&nbsp; <b> Near-Missed : </b>ความเสียหายที่เกิดขึ้น หรืออาจเกิดขึ้น แต่สามารถป้องกันความเสียหายไว้ได้
												</label></div>
										</div>

										<div class="col-lg-12"><label class="margins-top-10 label-main">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><textarea id="edit_control" class="form-control" name="edit_control" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-3"><label class="margins-top-10 label-main">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control " name='edit_loss_value' id='edit_loss_value' oninput="validate(this)" onkeyup="javascript:this.value=Comma(this.value);" onkeypress="return CheckNumeric()"></div>
										<div class="col-lg-3"><label class="margins-top-10 label-main">โอกาส<span style="color: red;">*</span></label>
									
											<select name='csa_likelihood_id1' id='csa_likelihood_id1' class="form-control"  >
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_likelihood";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
	if ($row1['csa_likelihood_id'] == '1') {
		$bColor = "#5FB9A1";
	} else if ($row1['csa_likelihood_id'] == '2') {
		$bColor = "#FFC11E";
	} else if ($row1['csa_likelihood_id'] == '3') {
		$bColor = "#FF7A38";
	} else if ($row1['csa_likelihood_id'] == '4') {
		$bColor = "#EF4F51";
	} else if ($row1['csa_likelihood_id'] == '5') {
		$bColor = "#C11115";
	}
?>
						<option style="background-color: <?= $bColor ?>; color:#FFFFFF;" value='<?=$row1['csa_likelihood_id']?>' ><?=$row1['csa_likelihood_name']?></option>
<?
}
?>			  
					</select>
										</div>
										<div class="col-lg-3"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label>
										
										
											<select name='csa_impact_id1' id='csa_impact_id1' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_impact";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
	if ($row1['csa_impact_id'] == '1') {
		$bColor = "#5FB9A1";
	} else if ($row1['csa_impact_id'] == '2') {
		$bColor = "#FFC11E";
	} else if ($row1['csa_impact_id'] == '3') {
		$bColor = "#FF7A38";
	} else if ($row1['csa_impact_id'] == '4') {
		$bColor = "#EF4F51";
	} else if ($row1['csa_impact_id'] == '5') {
		$bColor = "#C11115";
	}
?>
						<option style="background-color: <?= $bColor ?>; color:#FFFFFF;" value='<?=$row1['csa_impact_id']?>' ><?=$row1['csa_impact_name']?></option>
<?
}
?>			  
					</select>
										</div>
										<div class="col-lg-2" style="margin-top:35px;">
											<div class='alert' id='risk_level_1_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
											<div id="risk_level_1_div_name"></div>
										</div>
											<div class="col-lg-1" style="margin-top:45px;">
										<span  class="glyphicon glyphicon-search " style="color: #004C85; cursor: pointer;" data-toggle="modal" data-target="#myModalMatrixEdit"></span>
									</div>
										<div class="col-lg-6"><label class="margins-top-10 label-main"><br>
												<div id="link1Edit"></div> <br>
											</label>
											<hr>
											<div id='edit_attech_name_fack'></div>
											<label>อัพโหลดไฟล์ใหม่ 1 </label>
											<input type="file" class="form-control" name='edit_attech_name'>


											<input type='hidden' name='edit_data_id' id='edit_data_id'>
											<input type='hidden' name='listId' id='listId' value="<?= $listId; ?>">
											<input type='hidden' name='m' id='m' value="<?= $m; ?>">
											<input type='hidden' name='y' id='y' value="<?= $y; ?>">
										</div>
										<div class="col-lg-6"><label class="margins-top-10 label-main"><br>
												<div id="link2Edit"></div> <br>
											</label>
											<hr>
											<div id='edit_attech_name_fack2'></div>
											<label>อัพโหลดไฟล์ใหม่ 2 </label><input type="file" class="form-control" name='edit_attech_name2'>

										</div>

										<div class="col-lg-12"><label class="margins-top-10 label-main">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="edit_comment_app" class="form-control" name="edit_comment_app" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>

										<!-- <div class="col-lg-3 col-xs-12"><label class="margins-top-10 label-main">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='edit_end_date' readonly id='edit_end_date'></div> -->

										<div class="col-lg-12"><label class="margins-top-10 label-main">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="edit_comment_risk" class="form-control" name="edit_comment_risk" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>


									</div>
									<? if ($status_approve != '3') { ?>
										<div align="center" style="margin-top: 30px;">
											<button type='submit' name='submitLossDataList' value="submitLossDataList" class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
											<button type='submit' name='buttonapproveSendCase' value='buttonapproveSendCase' class="btn btn-danger" style="margin-left: 20px;"><i class='glyphicon glyphicon-send'></i> &nbsp;ส่งอนุมัติ</button>
										</div>
									<? } ?>

								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- start modal -->

<div id="myModalSendCase" class="modal fade" role="dialog">
	<div class="modal-dialog  modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="background-color:#27A4B0;color:#FFFFFF;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-list-alt"></span> รายละเอียด</h4>
			</div>
			<div class="modal-body" align="left">

				<form method='post' action='loss_data.php' enctype="multipart/form-data">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-xs-12">

								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='happen_date' readonly id='happen_date' style="cursor: default;"></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" name='checked_date' readonly id='checked_date' style="cursor: default;"></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">เหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence" class="form-control" name="incidence" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detail" class="form-control" name="incidence_detail" rows="3" cols="50" style="min-height:80px; background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="cause" class="form-control" name="cause" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><textarea id="user_effect" class="form-control" name="user_effect" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12"> <label class="margins-top-10 label-main"> ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_type' id='damage_type' class="form-control" style="background-color: #EEF1F5; cursor: default;" readonly disabled>
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor1 = 1;
												$makdel1 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =? and mark_del =?";
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor1, $makdel1);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {
												?>
													<option value="<?= $row1['loss_factor_id'] ?>"><?= $row1['factor'] ?></option>
												<?		} ?>
											</select></div>
										<div class="col-lg-12"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='incidence_type' id='incidence_type' class="form-control" style="background-color: #EEF1F5; cursor: default;" readonly disabled>
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor2 = 2;
												$makdel2 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =? and mark_del =?";
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor2, $makdel2);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {
												?>
													<option value="<?= $row1['loss_factor_id'] ?>"><?= $row1['factor'] ?></option>
												<?		} ?>
											</select></div>

										<div class="col-lg-12"><label class="margins-top-10  label-main">ความเสียหาย<span style="color: red;">*</span> </label>
											<div class="col-lg-12"><label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type1" value="1" disabled>&nbsp; <b>Actual Loss : </b> ความเสียหายที่เกิดขึ้นจริงทั้งที่เป็นตัวเงิน และไม่เป็นตัวเงิน
												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type2" value="2" disabled>&nbsp; <b> Potential Loss : </b>ความเสียหายที่อาจเกิดขึ้น

												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type3" value="3" disabled>&nbsp; <b> Near-Missed : </b>ความเสียหายที่เกิดขึ้น หรืออาจเกิดขึ้น แต่สามารถป้องกันความเสียหายไว้ได้
												</label></div>
										</div>

										<div class="col-lg-12"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><textarea id="control" class="form-control" name="control" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
										<div class="col-lg-3"><label class="margins-top-10">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" style="cursor: default;" name='loss_value' id='loss_value' disabled oninput="validate(this)" onkeyup="javascript:this.value=Comma(this.value);" onkeypress="return CheckNumeric()"></div>
										<div class="col-lg-3"><label class="margins-top-10 label-main">โอกาส<span style="color: red;">*</span></label>
									
											<select name='csa_likelihood_id2' id='csa_likelihood_id2' class="form-control" disabled style="cursor: default;"  >
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_likelihood";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
	if ($row1['csa_likelihood_id'] == '1') {
		$bColor = "#5FB9A1";
	} else if ($row1['csa_likelihood_id'] == '2') {
		$bColor = "#FFC11E";
	} else if ($row1['csa_likelihood_id'] == '3') {
		$bColor = "#FF7A38";
	} else if ($row1['csa_likelihood_id'] == '4') {
		$bColor = "#EF4F51";
	} else if ($row1['csa_likelihood_id'] == '5') {
		$bColor = "#C11115";
	}
?>
						<option style="background-color: <?= $bColor ?>; color:#FFFFFF;" value='<?=$row1['csa_likelihood_id']?>' ><?=$row1['csa_likelihood_name']?></option>
<?
}
?>			  
					</select>
										</div>
										<div class="col-lg-3"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label>
										
										
											<select name='csa_impact_id2' id='csa_impact_id2' class="form-control"  disabled style="cursor: default;"  >
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_impact";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
	if ($row1['csa_impact_id'] == '1') {
		$bColor = "#5FB9A1";
	} else if ($row1['csa_impact_id'] == '2') {
		$bColor = "#FFC11E";
	} else if ($row1['csa_impact_id'] == '3') {
		$bColor = "#FF7A38";
	} else if ($row1['csa_impact_id'] == '4') {
		$bColor = "#EF4F51";
	} else if ($row1['csa_impact_id'] == '5') {
		$bColor = "#C11115";
	}
?>
						<option style="background-color: <?= $bColor ?>; color:#FFFFFF;" value='<?=$row1['csa_impact_id']?>' ><?=$row1['csa_impact_name']?></option>
<?
}
?>			  
					</select>
										</div>
										<div class="col-lg-2"  style="margin-top:35px;">
										<div class='alert' id='risk_level_2_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
									</div>
										<div class="col-lg-1" style="margin-top:45px;">
											<span class="glyphicon glyphicon-search modalMatrix" style="color: #004C85; cursor: pointer;" data-toggle="modal" data-target="#myModalMatrix"></span>
										</div>
										<!-- <div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 1</label><select name='dep_id_1' id='dep_id_1' class="form-control" style="cursor: default;" disabled>
												<option value="0"> - - - -</option>
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
										<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 2</label><select name='dep_id_2' id='dep_id_2' class="form-control" style="cursor: default;" disabled>
												<option value="0"> - - - -</option>
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
										<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 3</label><select name='dep_id_3' id='dep_id_3' class="form-control" style="cursor: default;" disabled>
												<option value="0"> - - - -</option>
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
											</select></div> -->
										<div class="col-lg-6" align="center"><label class="margins-top-10">
												<br>
												<div id="link1"></div>
										</div>
										<div class="col-lg-6" align="center"><label class="margins-top-10"><br>
												<div id="link2"></div>

										</div>
										<? if ($status_approve != '0') { ?>
											<div class="col-lg-12"><label class="margins-top-10">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="comment_app" class="form-control" name="comment_app" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>
											<? if ($status_risk_approve == '3') { ?>
												<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='end_date' readonly id='end_date'></div>
											<? } ?>
											<div class="col-lg-12"><label class="margins-top-10">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="edit_comment_risk" class="form-control" name="edit_comment_risk" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>
										<? } ?>

									</div>



								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>



	</div>

</div>
<!-- Start Modal -->
<div id="myModalMatrix" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="color:#EFEFEF;">ตารางแสดงผลการวัดระดับความเสี่ยง (Risk Matrix)</h4>
			</div>
			<div class="modal-body" align="center">
			<div id="modalPopupDetail"></div>
			<table>
	<tr>
		<td>
		<b>เกณฑ์พิจารณาระดับความรุนแรงของผลกระทบ</b><br>
<b>ที่มีมูลค่าความเสียหายเป็นตัวเงิน</b><br>
											</td>
											</tr>
											<tr>
		<td>
		1 = น้อยมาก ความเสียหาย <= 10,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		2 = น้อย ความเสียหาย > 10,000 - 100,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		3 = ปานกลาง ความเสียหาย > 100,000 - 500,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		4 = สูง ความเสียหาย > 500,000 - 1,000,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		5 = สูงมาก ความเสียหาย > 1,000,000 บาท<br>
											</td>
											</tr>
											</table>
	 <br>
      </div>

			</div>

		</div>

	</div>
</div>
<!--End Modal -->


<!-- Start Modal -->
<div id="myModalMatrixEdit" class="modal fade" role="dialog" style="z-index: 99999999;" >
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:#EFEFEF;">ตารางแสดงผลการวัดระดับความเสี่ยง (Risk Matrix)</h4>
      </div>
      <div class="modal-body" align="center">
	  <div id="modalPopupEdit"></div>
	  <table>
	<tr>
		<td>
		<b>เกณฑ์พิจารณาระดับความรุนแรงของผลกระทบ</b><br>
<b>ที่มีมูลค่าความเสียหายเป็นตัวเงิน</b><br>
											</td>
											</tr>
											<tr>
		<td>
		1 = น้อยมาก ความเสียหาย <= 10,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		2 = น้อย ความเสียหาย > 10,000 - 100,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		3 = ปานกลาง ความเสียหาย > 100,000 - 500,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		4 = สูง ความเสียหาย > 500,000 - 1,000,000 บาท<br>
											</td>
											</tr>
											<tr>
		<td>
		5 = สูงมาก ความเสียหาย > 1,000,000 บาท<br>
											</td>
											</tr>
											</table>
	 <br>
      </div>
    </div>
  </div>
</div>
<!--End Modal -->

<script>
		// document.getElementById('#risk_level_1_div_name').innerHTML = "";
	$(".showDetailData").on("click", function() {

		var happen_dates = $(this).attr('data-happen_date');
		var checked_dates = $(this).attr('data-checked_date');
		var incidences = $(this).attr('data-incidence');
		var incidence_details = $(this).attr('data-incidence_detail');
		var causes = $(this).attr('data-cause');
		var user_effects = $(this).attr('data-user_effect');
		var damage_types = $(this).attr('data-damage_type');
		var incidence_types = $(this).attr('data-incidence_type');
		var loss_types = $(this).attr('data-loss_type');
		var controls = $(this).attr('data-control');
		var loss_values = $(this).attr('data-loss_value');
		var chances = $(this).attr('data-chance');
		var effects = $(this).attr('data-effect');
		var damageLevels = $(this).attr('data-damageLevel');
		var related_dep_ids = $(this).attr('data-related_dep_id');
		var dep_id_1s = $(this).attr('data-dep_id_1');
		var dep_id_2s = $(this).attr('data-dep_id_2');
		var dep_id_3s = $(this).attr('data-dep_id_3');
		var comment_apps = $(this).attr('data-comment_app');
		var approved_dates = $(this).attr('data-approved_date');
		var status_approves = $(this).attr('data-status_approve');
		var comment_risks = $(this).attr('data-comment_risk');
		var status_risk_approves = $(this).attr('data-status_risk_approve');
		var riskcomment_dates = $(this).attr('data-riskcomment_date');
		var attech_names = $(this).attr('data-attech_name');
		var attech_names2 = $(this).attr('data-attech_name2');

		$('#happen_date').val(happen_dates);
		$('#checked_date').val(checked_dates);
		$('#incidence').val(incidences);
		$('#incidence_detail').val(incidence_details);
		$('#cause').val(causes);
		$('#user_effect').val(user_effects);
		$('#damage_type').val(damage_types);
		$('#incidence_type').val(incidence_types);
		$('#loss_type').val(loss_types);

		if (loss_types.trim() == 1) {
			$("#loss_type1").prop("checked", true);
		} else if ((loss_types.trim() == 2)) {
			$("#loss_type2").prop("checked", true);
		} else if ((loss_types.trim() == 3)) {
			$("#loss_type3").prop("checked", true);
		}

		$('#control').val(controls);

		var parts = parseFloat(loss_values).toFixed(2).toString().split(".");
		var loss_values = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
		$('#loss_value').val(loss_values);

		$('#csa_likelihood_id2').val(chances);
		$('#csa_impact_id2').val(effects);
	
		$('#damageLevel').val(damageLevels);
		$('#related_dep_id').val(related_dep_ids);
		$('#dep_id_1').val(dep_id_1s);
		$('#dep_id_2').val(dep_id_2s);
		$('#dep_id_3').val(dep_id_3s);
		$('#comment_app').val(comment_apps);
		$('#approved_date').val(approved_dates);
		$('#status_approve').val(status_approves);
		$('#comment_risk').val(comment_risks);
		$('#status_risk_approve').val(status_risk_approves);
		$('#riskcomment_date').val(riskcomment_dates);

		cal_level("2");
		           var impact_id=effects;
					var likelihood_id=chances;
					$.post( "loss_data_matrix.php", { action: 'loss_data_matrix', data1: impact_id ,data2: likelihood_id })
.done(function( data ) {
$("#modalPopupDetail").html(data);
});

		if (attech_names == "" || attech_names == null) {

			document.getElementById("link1").innerHTML = "";
			$("#link1").append('<a href="#" id="attech_name" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

		} else {

			document.getElementById("link1").innerHTML = "";
			$("#link1").append('<a href="/attech_file/' + attech_names + '" target="_blank"  download id="attech_name" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

		}
		if (attech_names2 == "" || attech_names2 == null) {

			document.getElementById("link2").innerHTML = "";
			$("#link2").append('<a href="#" id="attech_names2" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

		} else {

			document.getElementById("link2").innerHTML = "";
			$("#link2").append('<a href="/attech_file/' + attech_names2 + '" target="_blank"  download id="attech_names2" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

		}


	});


	$(".editDetailData").on("click", function() {

		var edit_data_ids = $(this).attr('edit-data-id');
		var edit_happen_dates = $(this).attr('edit-data-happen_date');
		var edit_checked_dates = $(this).attr('edit-data-checked_date');
		var edit_incidences = $(this).attr('edit-data-incidence');
		var edit_incidence_details = $(this).attr('edit-data-incidence_detail');
		var edit_causes = $(this).attr('edit-data-cause');
		var edit_user_effects = $(this).attr('edit-data-user_effect');
		var edit_damage_types = $(this).attr('edit-data-damage_type');
		var edit_incidence_types = $(this).attr('edit-data-incidence_type');
		var edit_loss_types = $(this).attr('edit-data-loss_type');
		var edit_controls = $(this).attr('edit-data-control');
		var edit_loss_values = $(this).attr('edit-data-loss_value');
		var edit_chances = $(this).attr('edit-data-chance');
		var edit_effects = $(this).attr('edit-data-effect');
		var edit_damageLevels = $(this).attr('edit-data-damageLevel');
		var edit_related_dep_ids = $(this).attr('edit-data-related_dep_id');
		var edit_dep_id_1s = $(this).attr('edit-data-dep_id_1');
		var edit_dep_id_2s = $(this).attr('edit-data-dep_id_2');
		var edit_dep_id_3s = $(this).attr('edit-data-dep_id_3');
		var edit_comment_apps = $(this).attr('edit-data-comment_app');
		var edit_approved_dates = $(this).attr('edit-data-approved_date');
		var edit_status_approves = $(this).attr('edit-data-status_approve');
		var edit_comment_risks = $(this).attr('edit-data-comment_risk');
		var edit_status_risk_approves = $(this).attr('edit-data-status_risk_approve');
		var edit_riskcomment_dates = $(this).attr('edit-data-riskcomment_date');
		var edit_attech_names = $(this).attr('edit-data-attech_name');
		var edit_attech_names2 = $(this).attr('edit-data-attech_name2');

		$('#edit_data_id').val(edit_data_ids);
		$('#edit_happen_date').val(edit_happen_dates);
		$('#edit_checked_date').val(edit_checked_dates);
		$('#edit_incidence').val(edit_incidences);
		$('#edit_incidence_detail').val(edit_incidence_details);
		$('#edit_cause').val(edit_causes);
		$('#edit_user_effect').val(edit_user_effects);
		$('#edit_damage_type').val(edit_damage_types);
		$('#edit_incidence_type').val(edit_incidence_types);
		$('#edit_loss_type').val(edit_loss_types);

		if (edit_loss_types.trim() == 1) {
			$("#edit_loss_type1").prop("checked", true);
		} else if ((edit_loss_types.trim() == 2)) {
			$("#edit_loss_type2").prop("checked", true);
		} else if ((edit_loss_types.trim() == 3)) {
			$("#edit_loss_type3").prop("checked", true);
		}

		$('#edit_control').val(edit_controls);
		var partsEdit = parseFloat(edit_loss_values).toFixed(2).toString().split(".");
		var edit_loss_values = partsEdit[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (partsEdit[1] ? "." + partsEdit[1] : "");
		$('#edit_loss_value').val(edit_loss_values);
		$('#csa_likelihood_id1').val(edit_chances);
		$('#csa_impact_id1').val(edit_effects);
		var reFact = 0;
		var factor_nos = 0;
		if (edit_damage_types == '5') {
			factor_nos = '1';
		} else if (edit_damage_types == '6') {
			factor_nos = '2';
		} else if (edit_damage_types == '7') {
			factor_nos = '3';
		} else if (edit_damage_types == '8') {
			factor_nos = '4';
		}

		const dbParam = JSON.stringify({
			"limitStr": factor_nos

		});
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObj = JSON.parse(this.responseText);

			document.getElementById("edit_damage_type").innerHTML = "";
			$("#edit_damage_type").append('<option value="0"> - - - เลือก - - -</option>');
			$('#edit_damage_type').prop('selectedIndex', 0);
			var iCount = 0;
			var Checked = "";

			for (let x in myObj['object1']) {
				reFact = myObj['object1'][x].factor_no;


				if (edit_damage_types == myObj['object1'][x].loss_factor_id) {
					Checked = "selected";
				} else {
					Checked = "";
				}

				$("#edit_damage_type").append('<option value="' + myObj['object1'][x].loss_factor_id + '@' + myObj['object1'][x].factor_no + '"  ' + Checked + '>' + myObj['object1'][x].factor + '</option>');
			}


			document.getElementById("edit_incidence_type").innerHTML = "";
			$("#edit_incidence_type").append('<option value="0"> - - - เลือก - - -</option>');
			$('#edit_incidence_type').prop('selectedIndex', 0);
			var iCount2 = 0;

			for (let zx in myObj['object2']) {
				iCount2++;
				reFact = myObj['object2'][zx].loss_factor_id;


				if (edit_incidence_types == myObj['object2'][zx].loss_factor_id) {
					Checked2 = "selected";
				} else {
					Checked2 = "";
				}

				$("#edit_incidence_type").append('<option value="' + myObj['object2'][zx].loss_factor_id + '"  ' + Checked2 + '>' + myObj['object2'][zx].factor + '</option>');
			}


		}
		xmlhttp.open("POST", "api/lossDataOnchangeEdit.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);

		$('#edit_damageLevel').val(edit_damageLevels);
		$('#edit_related_dep_id').val(edit_related_dep_ids);
		$('#edit_dep_id_1').val(edit_dep_id_1s);
		$('#edit_dep_id_2').val(edit_dep_id_2s);
		$('#edit_dep_id_3').val(edit_dep_id_3s);
		$('#edit_comment_app').val(edit_comment_apps);
		$('#edit_approved_date').val(edit_approved_dates);
		$('#edit_status_approve').val(edit_status_approves);
		$('#edit_comment_risk').val(edit_comment_risks);
		$('#edit_status_risk_approve').val(edit_status_risk_approves);
		$('#edit_riskcomment_date').val(edit_riskcomment_dates);
	
		cal_level("1");
		           var impact_id=edit_effects;
					var likelihood_id=edit_chances;
					$.post( "loss_data_matrix.php", { action: 'loss_data_matrix', data1: impact_id ,data2: likelihood_id })
.done(function( data ) {
$("#modalPopupEdit").html(data);
});

		if (edit_attech_names == "" || edit_attech_names == null) {

			document.getElementById("link1Edit").innerHTML = "";
			$("#link1Edit").append('<a href="#" id="edit_attech_names" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

		} else {

			document.getElementById("link1Edit").innerHTML = "";
			$("#link1Edit").append('<a href="/attech_file/' + edit_attech_names + '" target="_blank"  download id="edit_attech_names" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

			document.getElementById("edit_attech_name_fack").innerHTML = "";
			$("#edit_attech_name_fack").append('<input type="hidden" name="edit_attech_name_file" value="' + edit_attech_names + '">');
		}

		if (edit_attech_names2 == "" || edit_attech_names2 == null) {
			document.getElementById("link2Edit").innerHTML = "";
			$("#link2Edit").append('<a href="#" id="edit_attech_names2" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');


		} else {


			document.getElementById("link2Edit").innerHTML = "";
			$("#link2Edit").append('<a href="/attech_file/' + edit_attech_names2 + '" target="_blank"  download id="edit_attech_names2" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

			document.getElementById("edit_attech_name_fack2").innerHTML = "";
			$("#edit_attech_name_fack2").append('<input type="hidden" name="edit_attech_name_file2" value="' + edit_attech_names2 + '">');
		}


	});


	function validateForm() {
		selected = document.querySelector('input[name="edit_loss_type"]:checked').value;

		if ($('#edit_checked_date').val() == '') {
			alert("กรุณาระบุวันที่ตรวจพบ");
			return false;
		} else if ($('#edit_happen_date').val() > $('#edit_checked_date').val()) {
			alert("กรุณาตรวจสอบวันที่เกิดเหตุการณ์และวันที่ตรวจพบ");
			return false;
		} else if ($('#edit_incidence').val() == '') {
			alert("กรุณาระบุเหตุการณ์");
			return false;
		} else if ($('#edit_incidence_detail').val() == "") {
			alert("กรุณาระบุรายละเอียดเหตุการณ์");
			return false;
		} else if ($('#edit_cause').val() == "") {
			alert("กรุณาระบุสาเหตุ");
			return false;
		} else if ($('#edit_user_effect').val() == "") {
			alert("กรุณาระบุผลกระทบ");
			return false;
		} else if ($('#edit_damage_type').val() == "0") {
			alert("กรุณาเลือกประเภทความเสียหาย");
			return false;
		} else if ($('#edit_incidence_type').val() == "0") {
			alert("กรุณาเลือกประเภทเหตุการณ์ความเสียหาย");
			return false;
		} else if ($('#edit_control').val() == "") {
			alert("กรุณาระบุข้อมูลการควบคุมที่มีอยู่");
			return false;
		} else if ($('#edit_loss_value').val() == "") {
			alert("กรุณาระบุมูลค่าความเสียหาย");
			return false;
		} else if ($('#edit_chance').val() == "0") {
			alert("กรุณาระบุโอกาส");
			return false;
		} else if ($('#edit_effect').val() == "0") {
			alert("กรุณาระบุผลกระทบ");
			return false;
		}
		return true;
	}


	$('#edit_damage_type').on('change', function() {
		$arrayDamage = $('#edit_damage_type').val().split('@');
		const dbParam = JSON.stringify({
			"limit": $arrayDamage[1]
		});
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObj = JSON.parse(this.responseText);
			// if (myObj.length == 0) {
			// 	alert('11');
			// 	$('#edit_incidence_type').attr("disabled", true);
			// 	document.getElementById("edit_incidence_type").innerHTML = "";
			// 	$("#edit_incidence_type").append('<option value="0"> - - - เลือก - - -</option>');
			// } else {
			$('#edit_incidence_type').attr("disabled", false);
			document.getElementById("edit_incidence_type").innerHTML = "";
			$("#edit_incidence_type").append('<option value="0"> - - - เลือก - - -</option>');
			$('#edit_incidence_type').prop('selectedIndex', 0);
			var iCount = 0;
			for (let x in myObj) {
				iCount++;

				$("#edit_incidence_type").append('<option value="' + myObj[x].loss_factor_id + '">' + myObj[x].factor + '</option>');
			}
			// }

		}
		xmlhttp.open("POST", "api/lossDataOnchange.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);

	});


	$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
	$('#chance').on('change', function() {
		if ($('#chance').val() != 0 && $('#effect').val() != 0) {
			var strCheck = $('#effect').val() + $('#chance').val()
			document.getElementById("showPerformance").innerHTML = "";
			if (checkLossLevel(strCheck) == 1) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #00B050; color:#FFFFFF; width:120px; cursor: default;" > ต่ำ</a>');
			} else if (checkLossLevel(strCheck) == 2) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #FFFF00; color:#000000; width:120px; cursor: default;" > ปานกลาง</a>');
			} else if (checkLossLevel(strCheck) == 3) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #FF9400; color:#FFFFFF; width:120px; cursor: default;" > สูง</a>');
			} else if (checkLossLevel(strCheck) == 4) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #FF0000;; color:#FFFFFF; width:120px; cursor: default;" > สูงมาก</a>');
			}
		} else {
			var strCheck = $('#effect').val() + $('#chance').val()
			document.getElementById("showPerformance").innerHTML = "";
			$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
		}


	});

	$('#edit_effect').on('change', function() {
		if ($('#edit_chance').val() != 0 && $('#edit_effect').val() != 0) {
			var strCheck = $('#edit_effect').val() + $('#edit_chance').val()
			document.getElementById("showPerformanceEdit").innerHTML = "";
			if (checkLossLevel(strCheck) == 1) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #00B050; color:#FFFFFF; width:120px; cursor: default;" > ต่ำ</a>');
			} else if (checkLossLevel(strCheck) == 2) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #FFFF00; color:#000000; width:120px; cursor: default;" > ปานกลาง</a>');
			} else if (checkLossLevel(strCheck) == 3) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #FF9400; color:#FFFFFF; width:120px; cursor: default;" > สูง</a>');
			} else if (checkLossLevel(strCheck) == 4) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #FF0000;; color:#FFFFFF; width:120px; cursor: default;" > สูงมาก</a>');
			}
		} else {
			var strCheck = $('#edit_effect').val() + $('#edit_chance').val()
			document.getElementById("showPerformanceEdit").innerHTML = "";
			$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
		}
	});
	$('#edit_chance').on('change', function() {
		if ($('#edit_chance').val() != 0 && $('#edit_effect').val() != 0) {
			var strCheck = $('#edit_effect').val() + $('#edit_chance').val()
			document.getElementById("showPerformanceEdit").innerHTML = "";
			if (checkLossLevel(strCheck) == 1) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #00B050; color:#FFFFFF; width:120px; cursor: default;" > ต่ำ</a>');
			} else if (checkLossLevel(strCheck) == 2) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #FFFF00; color:#000000; width:120px; cursor: default;" > ปานกลาง</a>');
			} else if (checkLossLevel(strCheck) == 3) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #FF9400; color:#FFFFFF; width:120px; cursor: default;" > สูง</a>');
			} else if (checkLossLevel(strCheck) == 4) {
				$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #FF0000;; color:#FFFFFF; width:120px; cursor: default;" > สูงมาก</a>');
			}
		} else {
			var strCheck = $('#edit_effect').val() + $('#edit_chance').val()
			document.getElementById("showPerformanceEdit").innerHTML = "";
			$("#showPerformanceEdit").append('<a  class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
		}
	});
</script>


<script>
	function Comma(Num) { //function to add commas to textboxes
		Num += '';
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		x = Num.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1))
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		return x1 + x2;
	}

	function CheckNumeric() {
		return event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode == 46;
	}

	var validate = function(e) {
		var t = e.value;
		e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
	}

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}
</script>


<script>
	function checkLossLevel($parameters) {

		if ($parameters == '00') {
			return 0;
		} else if ($parameters == 11) {
			return 1;
		} else if ($parameters == 12) {
			return 1;
		} else if ($parameters == 13) {
			return 2;
		} else if ($parameters == 14) {
			return 2;
		} else if ($parameters == 15) {
			return 2;
		} else if ($parameters == 21) {
			return 1;
		} else if ($parameters == 22) {
			return 1;
		} else if ($parameters == 23) {
			return 2;
		} else if ($parameters == 24) {
			return 2;
		} else if ($parameters == 25) {
			return 3;
		} else if ($parameters == 31) {
			return 2;
		} else if ($parameters == 32) {
			return 2;
		} else if ($parameters == 33) {
			return 3;
		} else if ($parameters == 34) {
			return 3;
		} else if ($parameters == 35) {
			return 3;
		} else if ($parameters == 41) {
			return 3;
		} else if ($parameters == 42) {
			return 3;
		} else if ($parameters == 43) {
			return 3;
		} else if ($parameters == 44) {
			return 4;
		} else if ($parameters == 45) {
			return 4;
		} else if ($parameters == 51) {
			return 3;
		} else if ($parameters == 52) {
			return 3;
		} else if ($parameters == 53) {
			return 3;
		} else if ($parameters == 54) {
			return 4;
		} else if ($parameters == 55) {
			return 4;
		} else {
			return 0;
		}
	}
</script>
<script>

$('#csa_impact_id1, #csa_likelihood_id1').change(function() {
						cal_level("1");
					var impact_id=$('#csa_impact_id1').val();
					var likelihood_id=$('#csa_likelihood_id1').val();
					$.post( "loss_data_matrix.php", { action: 'loss_data_matrix', data1: impact_id ,data2: likelihood_id })
.done(function( data ) {
$("#modalPopupEdit").html(data);
});
					
					}).change();



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
	var lv = 0;
	
	if (isNaN(i) || isNaN(j)) {
		$('#risk_level_'+w+'_div').css('background-color', '#ffffff');
		$('#risk_level_'+w+'_div').html('ระดับความเสี่ยง');

	} else {
		lv = risk_mat[i][j];

		$('#risk_level_'+w+'_div').css('background-color', risk_level_color(lv));
		$('#risk_level_'+w+'_div').html(risk_level_name(lv));
		$('#risk_level_'+w+'_div_name').html('');
		$('#risk_level_'+w+'_div_name').append('<input type="hidden" id="loss_text_level" name="loss_text_level" value="'+risk_level_name(lv)+'">');
	
	
	}
}

</script>
<? echo template_footer(); ?>
<?function getEmail($u)
{
	include('inc/connect.php');
	$sql = "SELECT email FROM user WHERE user_id='" . $u . "'";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return $row['email'];
}
?>
<?function getParentID($u)
{
	include('inc/connect.php');
	$sql = "SELECT parent_id FROM department WHERE department_id='" . $u . "'";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return $row['parent_id'];
}
?>