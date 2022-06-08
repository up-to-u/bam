<?
include('inc/include.inc.php');
echo template_header();
$action = $_GET['action'];
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
	$loss_value = $_POST['edit_loss_value'];
	$chance = $_POST['edit_chance'];
	$effect = $_POST['edit_effect'];
	$damageLevel = $_POST['edit_effect'] . $_POST['edit_chance'];
	$dep_id_1 = $_POST['edit_dep_id_1'];
	$dep_id_2 = $_POST['edit_dep_id_2'];
	$dep_id_3 = $_POST['edit_dep_id_3'];

	$attech_name = ($_FILES["edit_attech_name"]["name"]);
	$uploadOk = 1;
	if ($attech_name != "" || $attech_name != null) {
		$target_dir = "attech_file/";
		$target_file = $target_dir . basename($_FILES["edit_attech_name"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		$new_name = $user_id . '_ATTECH' . date('Ymdhis') . '.' . $imageFileType;
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

	if ($uploadOk != 0) {
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
		damageLevel=?,
		dep_id_1=?,
		dep_id_2=?,
		dep_id_3=?,
		attech_name=?,
		doclist_user_id=?
		WHERE loss_data_doc_list_id=? ");

		if ($stmt) {
			$stmt->bind_param(
				'ssssssiiisiiisiiisii',
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
				$damageLevel,
				$dep_id_1,
				$dep_id_2,
				$dep_id_3,
				$attech_name,
				$user_id,
				$loss_data_doc_list_id
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();

				echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');
				window.location.href='loss_data_list.php?listId=" . $listId . "&m=" . $m . "&y=" . $y . "';
				</script>";
			} else {
				$connect->rollback();
			}
			//	$stmt->close();
			//	$conn->close();
		} else {
			$error = "<script>alert('เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้');
			window.location.href='loss_data_list.php?listId=" . $listId . "&m=" . $m . "&y=" . $y . "';</script>";
		}
	}
}

if ($action == 'approve') {
	$buttonapprove = $_POST['buttonapprove'];
	$loss_data_doc_list_id = $_POST['loss_data_doc_list_id'];
	$connect->autocommit(FALSE);
	$qx = true;
	$stmt = $connect->prepare("UPDATE `loss_data_doc_list` SET `status_approve` = ? WHERE `loss_data_doc_list_id` = ? ");
	if ($stmt) {
		$stmt->bind_param('ii', $buttonapprove, $loss_data_doc_list_id);
		$q = $stmt->execute();
		$qx = ($qx and $q);

		if ($qx) {
			savelog('loss-approvedstatuschange-list');
			$connect->commit();
			echo "<script>  alert('บันทึกรายการเรียบร้อยแล้ว ');  </script>  ";
		} else {
			$connect->rollback();
		}
	} else {
		echo 'x' . $connect->error;
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
		color: #27A4B0;
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
			</div><br>
			<div align="center">
				<?php
				if ($_GET['listId'] != NULL) {
					echo "<span style='font-size: 20px;margin:10px;'>ข้อมูลเหตุการณ์ประจำเดือน " . month_name($_GET['m']) . " ปี " . $_GET['y'] . "</span><br>";
				} elseif ($_GET['statusListId'] != NULL) {
					if ($_GET['statusListId'] == 0) {
						echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลรออนุมัติทั้งหมด</span><br>";
					} elseif ($_GET['statusListId'] == 2) {
						echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลส่งกลับแก้ไขทั้งหมด</span><br>";
					} elseif ($_GET['statusListId'] == 1) {
						echo "<span style='font-size: 20px;margin:10px;'>รายการอนุมัติแล้วทั้งหมด</span><br>";
					}
				}								?>
				<form action='export.php' method="post">
					<button type="submit" class="pull-right btn btn-dark" style="margin: 10px;"> <span class="glyphicon glyphicon-download-alt"></span> Export Data</button>
				</form>

			</div>
			<br>
			<div class="form-group">
				<div class="row">
					<div class="col-lg-12 col-xs-12">
						<div class='well'>
							<div class='row' style="margin-left: 3px;margin-right: 3px;">
								<!-- start table -->

								<table id="dataTableList" class="table table-striped table-bordered" style="width:100%">
									<thead>
										<tr>
											<th style='width:2%'>ลำดับ</th>
											<th style='width:20%'>เหตุการณ์</th>
											<th style='width:20%'>รายงานโดย</th>
											<th align="center" style='width:10%'>วันที่บันทึก</th>
											<th width="150">ระดับความเสียหาย</th>
											<? if ($_GET['listId'] != NULL) { ?> <th width="150">สถานะจากผู้อนุมัติ</th> <? } ?>
											<? if ($_GET['statusListId'] == 1) { ?> <th style='width:10%'>สถานะจากฝ่ายความเสี่ยง</th> <? } ?>
											<? if ($_GET['statusListId'] == 1) { ?> <th style='width:10%'>วันที่ปิด CASE</th> <? } ?>
											<th style='width:20%'>จัดการ</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($_GET['listId'] != NULL) {
											$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.loss_data_doc_id ='" . $_GET['listId'] . "' and loss_data_doc.loss_dep =$dep_id";
										} elseif ($_GET['statusListId'] != NULL) {
											$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.status_approve ='" . $_GET['statusListId'] . "'and loss_data_doc.loss_dep =$dep_id";
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
												<td style="vertical-align: middle;"><?= $row['incidence']; ?>
												<td style="vertical-align: middle;"><?= get_user_name($row['doclist_user_id']); ?>
												</td>
												<td style="vertical-align: middle;"><?= mysqldate2th_datetime($row['loss_data_doc_createdate']); ?></td>
												<?php
												$numImpact = $row['effect'] . $row['chance'];
												if (checkLossLevel((int)$numImpact) == 1) {
													echo '<td align="center" style="vertical-align: middle; background-color: #00B050; color:#FFFFFF;"> ต่ำ </td>';
												} else if (checkLossLevel((int)$numImpact) == 2) {
													echo '<td align="center" style="vertical-align: middle; background-color: #FFFF00;color:#000000;"> ปานกลาง</td>';
												} else if (checkLossLevel((int)$numImpact) == 3) {
													echo '<td align="center" style="vertical-align: middle; background-color: #FFC000;color:#000000;"> สูง </td>';
												} else if (checkLossLevel((int)$numImpact) == 4) {
													echo '<td align="center" style="vertical-align: middle; background-color: #FF0000;color:#FFFFFF;"> สูงมาก </td>';
												} else {
													echo '<td align="center"> - </td>';
												}
												?>
												<? if ($_GET['listId'] != NULL) { ?>
													<td width="150">
														<? if ($status_approve == '0') { ?>รออนุมัติ <? } ?>
													<? if ($status_approve == '1') { ?>อนุมัติแล้ว <? } ?>
												<? if ($status_approve == '2') { ?>แก้ไข <? } ?></td>
												<? } ?>
												<? if ($_GET['statusListId'] == 1) { ?> <td>
														<? if ($status_risk_approve == '0') { ?>รออนุมัติ<? } ?>
														<? if ($status_risk_approve == '1') { ?>ดำเนินการแล้วเสร็จ <? } ?>
													<? if ($status_risk_approve == '2') { ?>แก้ไข <? } ?></td> <? } ?>

												<? if ($_GET['statusListId'] == 1) { ?> <td><?= mysqldate2th_datetime($row['end_date']); ?></td> <? } ?>

												<td width="250">

													<button name='submit' class="btn btn-success showDetailData" data-happen_date="<?= $row['happen_date']; ?>" data-checked_date="<?= $row['checked_date']; ?>" data-incidence="<?= $row['incidence']; ?>" data-incidence_detail="<?= $row['incidence_detail']; ?>" data-cause="<?= $row['cause']; ?>" data-user_effect="<?= $row['user_effect']; ?>" data-damage_type="<?= $row['damage_type']; ?>" data-incidence_type="<?= $row['incidence_type']; ?>" data-loss_type="<?= $row['loss_type']; ?>" data-control="<?= $row['control']; ?>" data-loss_value="<?= $row['loss_value']; ?>" data-chance="<?= $row['chance']; ?>" data-effect="<?= $row['effect']; ?>" data-damageLevel="<?= $row['damageLevel']; ?>" data-related_dep_id="<?= $row['related_dep_id']; ?>" data-dep_id_1="<?= $row['dep_id_1']; ?>" data-dep_id_2="<?= $row['dep_id_2']; ?>" data-dep_id_3="<?= $row['dep_id_3']; ?>" data-comment_app="<?= $row['comment_app']; ?>" data-approved_date="<?= $row['approved_date']; ?>" data-status_approve="<?= $row['status_approve']; ?>" data-comment_risk="<?= $row['comment_risk']; ?>" data-status_risk_approve="<?= $row['status_risk_approve']; ?>" data-riskcomment_date="<?= $row['riskcomment_date']; ?>" data-attech_name="<?= $row['attech_name']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-list-alt'></i> รายละเอียด</button>
													<? if ($status_approve == '2') { ?>
														<form action="loss_data_list.php?statusListId=<?= $_GET['statusListId'] ?>&action=approve" method="post" target="_blank" style="display: inline;">
															<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
															<button type='submit' name='buttonapprove' value='0' class="btn btn-danger"> ส่งอนุมัติ</button>
														</form>
													<? } ?>
													<? if ($status_approve == '1') { ?>
														<form action="pdf.php" method="post" target="_blank" style="display: inline;">
															<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
															<button type='submit' class="btn btn-dark" style="background-color: #949596; color:#FFFFFF; width:110px;"> <span class="glyphicon glyphicon-print"></span> พิมพ์</button>
														</form>
													<? } ?>
													<? if ($status_approve == '0') { ?>

														<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
														<button name='submit' class="btn btn-success editDetailData" style="width: 110px;" edit-data-id="<?= $row['loss_data_doc_list_id']; ?>" edit-data-happen_date="<?= $row['happen_date']; ?>" edit-data-checked_date="<?= $row['checked_date']; ?>" edit-data-incidence="<?= $row['incidence']; ?>" edit-data-incidence_detail="<?= $row['incidence_detail']; ?>" edit-data-cause="<?= $row['cause']; ?>" edit-data-user_effect="<?= $row['user_effect']; ?>" edit-data-damage_type="<?= $row['damage_type']; ?>" edit-data-incidence_type="<?= $row['incidence_type']; ?>" edit-data-loss_type="<?= $row['loss_type']; ?>" edit-data-control="<?= $row['control']; ?>" edit-data-loss_value="<?= $row['loss_value']; ?>" edit-data-chance="<?= $row['chance']; ?>" edit-data-effect="<?= $row['effect']; ?>" edit-data-damageLevel="<?= $row['damageLevel']; ?>" edit-data-related_dep_id="<?= $row['related_dep_id']; ?>" edit-data-dep_id_1="<?= $row['dep_id_1']; ?>" edit-data-dep_id_2="<?= $row['dep_id_2']; ?>" edit-data-dep_id_3="<?= $row['dep_id_3']; ?>" edit-data-comment_app="<?= $row['comment_app']; ?>" edit-data-approved_date="<?= $row['approved_date']; ?>" edit-data-status_approve="<?= $row['status_approve']; ?>" edit-data-comment_risk="<?= $row['comment_risk']; ?>" edit-data-status_risk_approve="<?= $row['status_risk_approve']; ?>" edit-data-riskcomment_date="<?= $row['riskcomment_date']; ?>" edit-data-attech_name="<?= $row['attech_name']; ?>" data-toggle="modal" data-target="#editModalSendCase"><i class='glyphicon glyphicon-pencil'></i> แก้ไขข้อมูล</button>

													<? } ?>
												</td>

											</tr>

										<?php } ?>

									</tbody>

								</table>

								<!-- end table -->

							</div>

						</div>
						<div align="center"><br> <a href="loss_data.php">
								<span class="glyphicon glyphicon-menu-left"></span><span class="glyphicon glyphicon-menu-left"></span> ย้อนกลับ
							</a>
						</div>
					</div>
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
				<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-pencil"></span> แก้ไขข้อมูล</h4>
			</div>
			<div class="modal-body" align="left">

				<form method='post' onsubmit="return validateForm()" action='loss_data_list.php' enctype="multipart/form-data">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-xs-12">

								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์</label><input type="text" class="form-control datepicker" name='edit_happen_date' readonly id='edit_happen_date'></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='edit_checked_date' readonly id='edit_checked_date'></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">เหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control" name='edit_incidence' id='edit_incidence'></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="edit_incidence_detail" class="form-control" name="edit_incidence_detail" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="edit_cause" class="form-control" name="edit_cause" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><input type="text" class="form-control" name='edit_user_effect' id='edit_user_effect'></div>
										<div class="col-lg-12"> <label class="margins-top-10">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='edit_damage_type' id='edit_damage_type' class="form-control">
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
										<div class="col-lg-12"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='edit_incidence_type' id='edit_incidence_type' class="form-control">
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

										<div class="col-lg-3"> <label class="margins-top-10 col-xs-12" style="margin-left: -13px;">Loss :<span style="color: red;">*</span> </label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type1" value="1"> Actual Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type2" value="2"> Potential Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type3" value="3"> Near-Missed
											</label></div>

										<div class="col-lg-12"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><input type="text" class="form-control" name='edit_control' id='edit_control'></div>
										<div class="col-lg-4"><label class="margins-top-10">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" name='edit_loss_value' id='edit_loss_value'></div>
										<div class="col-lg-4"><label class="margins-top-10">โอกาส<span style="color: red;">*</span></label><select name='edit_chance' id='edit_chance' class="form-control">
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor4 = 4;
												$makdel4 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =?  and mark_del =?";
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor4, $makdel4);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {

												?>
													<option value="<?= $row1['factor_no'] ?>"><?= $row1['factor'] ?></option>
												<? } ?>
											</select></div>
										<div class="col-lg-4"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><select name='edit_effect' id='edit_effect' class="form-control">
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor3 = 3;
												$makdel3 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =? and mark_del =?";

												$z = 0;
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor3, $makdel3);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {

												?>
													<option value="<?= $row1['factor_no'] ?>"><?= $row1['factor'] ?></option>
												<?		} ?>
											</select></div>

										<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 1</label><select name='edit_dep_id_1' id='edit_dep_id_1' class="form-control">
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
										<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 2</label><select name='edit_dep_id_2' id='edit_dep_id_2' class="form-control">
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
										<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 3</label><select name='edit_dep_id_3' id='edit_dep_id_3' class="form-control">
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
										<div class="col-lg-12"><label class="margins-top-10"><br> <a href="#" id="edit_attech_name_download" style="text-decoration: none;"><span class="glyphicon glyphicon-download-alt" style="margin-left: 20px;"></span> >> ดาวน์โหลดเอกสาร << </label></a></label>
											<input type="file" class="form-control" name='edit_attech_name'>
											<input type='hidden' name='edit_data_id' id='edit_data_id'>
											<input type='hidden' name='listId' id='listId' value="<?= $_GET['listId']; ?>">
											<input type='hidden' name='m' id='m' value="<?= $_GET['m']; ?>">
											<input type='hidden' name='y' id='y' value="<?= $_GET['y']; ?>">
										</div>
										<? if ($status_approve != '0') { ?>
											<div class="col-lg-12"><label class="margins-top-10">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="edit_comment_app" class="form-control" name="edit_comment_app" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>
											<? if ($status_risk_approve == '3') { ?>
												<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='edit_end_date' readonly id='edit_end_date'></div>
											<? } ?>
											<div class="col-lg-12"><label class="margins-top-10">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="comment_risk" class="form-control" name="comment_risk" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>
										<? } ?>

									</div>
									<? if ($status_approve != '3') { ?>
										<div align="center" style="margin-top: 30px;">
											<button type='submit' name='submitLossDataList' value="submitLossDataList" class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
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
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์</label><input type="text" class="form-control datepicker" name='happen_date' readonly id='happen_date' style="cursor: default;"></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='checked_date' readonly id='checked_date' style="cursor: default;"></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">เหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control" name='incidence' id='incidence' style="background-color: #EEF1F5;cursor: default;" readonly></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detail" class="form-control" name="incidence_detail" rows="3" cols="50" style="min-height:80px; background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="cause" class="form-control" name="cause" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><input type="text" class="form-control" name='user_effect' id='user_effect' style="background-color: #EEF1F5;cursor: default;" readonly></div>
										<div class="col-lg-12"> <label class="margins-top-10">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_type' id='damage_type' class="form-control" style="background-color: #EEF1F5; cursor: default;" readonly disabled>
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

										<div class="col-lg-3"> <label class="margins-top-10 col-xs-12" style="margin-left: -13px;">Loss : <span style="color: red;">*</span></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type1" value="1" disabled> Actual Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type2" value="2" disabled> Potential Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type3" value="3" disabled> Near-Missed
											</label></div>

										<div class="col-lg-12"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><input type="text" class="form-control" style="cursor: default;" name='control' id='control' disabled></div>
										<div class="col-lg-3"><label class="margins-top-10">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" style="cursor: default;" name='loss_value' id='loss_value' disabled></div>
										<div class="col-lg-3"><label class="margins-top-10">โอกาส<span style="color: red;">*</span></label><select name='chance' id='chance' class="form-control" style="cursor: default;" disabled>
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor4 = 4;
												$makdel4 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =?  and mark_del =?";
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor4, $makdel4);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {

												?>
													<option value="<?= $row1['factor_no'] ?>"><?= $row1['factor'] ?></option>
												<? } ?>
											</select></div>
										<div class="col-lg-3"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><select name='effect' id='effect' class="form-control" style="cursor: default;" disabled>
												<option value="0">- - - เลือก - - - </option>
												<?
												$iFactor3 = 3;
												$makdel3 = 0;
												$sqlFactor = "SELECT * FROM loss_factor Where parent_id =? and mark_del =?";

												$z = 0;
												$stmt = $connect->prepare($sqlFactor);
												$stmt->bind_param("ii", $iFactor3, $makdel3);
												$stmt->execute();
												$result = $stmt->get_result();
												while ($row1 = mysqli_fetch_array($result)) {

												?>
													<option value="<?= $row1['factor_no'] ?>"><?= $row1['factor'] ?></option>
												<?		} ?>
											</select></div>
											<div class="col-lg-3" id="showPerformance" style="margin-top:35px;">

</div>
										<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 1</label><select name='dep_id_1' id='dep_id_1' class="form-control" style="cursor: default;" disabled>
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
											</select></div>
										<div class="col-lg-12"><label class="margins-top-10"><br><a href="#" id="attech_name" style="text-decoration: none;"><span class="glyphicon glyphicon-download-alt" style="margin-left: 20px;"></span> >> ดาวน์โหลดเอกสาร << </label></a>

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



<? echo template_footer(); ?>

<script>
	$(document).ready(function() {
		var table = $('#dataTableList').DataTable({
			responsive: true
		});

		new $.fn.dataTable.FixedHeader(table);
	});
</script>
<script>
	$(document).ready(function() {
		var table = $('#exampleDataTable').DataTable({
			responsive: true
		});

		new $.fn.dataTable.FixedHeader(table);
	});
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

		if (chances != 0 && effects != 0) {
			var strCheck = chances + effects
			document.getElementById("showPerformance").innerHTML = "";
			if (checkLossLevel(strCheck) == 1) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #00B050; color:#FFFFFF; width:120px; cursor: default;" > ต่ำ</a>');
			} else if (checkLossLevel(strCheck) == 2) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #FFFF00; color:#000000; width:120px; cursor: default;" > ปานกลาง</a>');
			} else if (checkLossLevel(strCheck) == 3) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #FFC000; color:#FFFFFF; width:120px; cursor: default;" > สูง</a>');
			} else if (checkLossLevel(strCheck) == 4) {
				$("#showPerformance").append('<a    class="btn" style="background-color: #FF0000;; color:#FFFFFF; width:120px; cursor: default;" > สูงมาก</a>');
			}
		}else  {
			var strCheck = chances + effects
			document.getElementById("showPerformance").innerHTML = "";
			$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
		}


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
		$('#loss_value').val(loss_values);
		$('#chance').val(chances);
		$('#effect').val(effects);

		
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
		var urlDownload = "/bam/attech_file/" + attech_names;
		$('#attech_name').attr({
			target: '_blank',
			href: urlDownload
		});

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
		$('#edit_loss_value').val(edit_loss_values);
		$('#edit_chance').val(edit_chances);
		$('#edit_effect').val(edit_effects);
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
		var urlDownload = "/bam/attech_file/" + edit_attech_names;
		$('#edit_attech_name_download').attr({
			target: '_blank',
			href: urlDownload
		});

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
		}else if ($('#edit_damage_type').val() == "0") {
			alert("กรุณาเลือกประเภทความเสียหาย");
			return false;
		}else if ($('#edit_incidence_type').val() == "0") {
			alert("กรุณาเลือกประเภทเหตุการณ์ความเสียหาย");
			return false;
		}else if ($('#edit_control').val() == "") {
			alert("กรุณาระบุข้อมูลการควบคุมที่มีอยู่");
			return false;
		}else if ($('#edit_loss_value').val() == "") {
			alert("กรุณาระบุมูลค่าความเสียหาย");
			return false;
		}else if ($('#edit_chance').val() == "0") {
			alert("กรุณาระบุโอกาส");
			return false;
		}else if ($('#edit_effect').val() == "0") {
			alert("กรุณาระบุผลกระทบ");
			return false;
		}
		return true;
	}
</script>
<script>
	function checkLossLevel($parameters) {
		
		if ($parameters == '00') {
			return 0;
		}else if ($parameters == 11) {
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