<?
include('inc/include.inc.php');
echo template_header();

$action = $_GET['action'];
$sql = "SELECT department_id,department_name, group_name,division_name FROM user WHERE user_id=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res1 = $stmt->get_result();
if ($row_mem = $res1->fetch_assoc()) {
	$department_name = $row_mem['department_name'];
	$groupName = $row_mem['group_name'];
	$division_name = $row_mem['division_name'];
	$department_id = $row_mem['department_id'];
}
if ($_POST['submitLossDoc'] == 'submitLossDoc') {
	$loss_data_doc_month = $_POST['loss_data_doc_month'];
	$loss_year = $_POST['loss_year'];
	$loss_have = $_POST['loss_have'];
	$loss_dep = $_POST['loss_dep'];
		$qx = true;	
		$stmt = $connect->prepare("INSERT INTO loss_data_doc (`loss_data_doc_month`,`loss_year`,`loss_have`,`loss_dep`,`approve_id`,`approve_name`,`user_id`) VALUES
		(?,?,?,?,?,?,?)");

		if ($stmt) {
			$stmt->bind_param(
				'iiiiisi',
				$loss_data_doc_month,
				$loss_year,
				$loss_have,
				$loss_dep,
				$approve_id,
				$approve_name,
				$user_id,
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);
		
			if ($qx) {
				$connect->commit();
				echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');</script>";
			
			} else {
				$connect->rollback();
			}
	
		} else {
			$error = "<script>alert('เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้');</script>";

		}
}
if ($_POST['submitLossUpdate'] == 'submitLossUpdate') {
	$loss_data_doc_id = $_POST['date_id'];
	$comment_app = $_POST['comment_app'];
	$approved_date = date('YYYY-mm-dd');
		$qx = true;

		$stmt = $connect->prepare("UPDATE loss_data_doc_list SET
		comment_app=?,approved_date=now()
		WHERE loss_data_doc_list_id=? ");

		if ($stmt) {
			$stmt->bind_param(
				'si',
				$comment_app,
				$loss_data_doc_id
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();

				echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');
				window.location.href='loss_data_approve.php?statusListId=1';
				</script>";
			} else {
				$connect->rollback();
			}
			//	$stmt->close();
			//	$conn->close();
		
	}
	
}

if($action=='approve'){
	$buttonapprove = $_POST['buttonapprove'];
	$loss_data_doc_list_id = $_POST['loss_data_doc_list_id'];
	$connect->autocommit(FALSE);
		$qx = true;	
		$stmt = $connect->prepare("UPDATE `loss_data_doc_list` SET `status_approve` = ? WHERE `loss_data_doc_list_id` = ? ");
		if ($stmt) {
			$stmt->bind_param('ii',$buttonapprove,$loss_data_doc_list_id);
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
			echo 'x'.$connect->error;
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
	}
</style>

<link rel="stylesheet" href="dist/css/bootstrap-select.css">
<script src="dist/js/bootstrap-select.js"></script>
<link href="jquery-ui-1.12.0/jquery-ui.css" rel="stylesheet">
<script src="jquery-ui-1.12.0/jquery-ui.js"></script>
<script language='JavaScript'>

$(function () {
 $(".datepicker").datepicker({ 
  changeMonth: true,
  changeYear: true, 
  yearRange: '-10:+5',
  dateFormat: 'yy-mm-dd', 
  dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
  dayNamesMin: ['อา','จ','อ','พ','พฤ','ศ','ส'],
  montdocames: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
  monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
 });
});  

</script>
<?
	$sql13="SELECT month_year_id as yid2 FROM loss_time";
	$result13=mysqli_query($connect, $sql13);
	$row13 = mysqli_fetch_array($result13);
	$yid2 = $row13['yid2'];
	$month_year=$yid2;
	if ($yid2=='') {
	$month_year=date('Y')+543;
	}
?>

<div class="row">
<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 31/05/65</label>
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">อนุมัติเหตุการณ์ความเสียหาย</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<!---------------->
			<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
				<div class="col-lg-3">
				</div>
				<div class="col-lg-3">
		
					<b>สายงาน :</b> <?= $division_name; ?>
				</div>
				<div class="col-lg-3">
					<b>ฝ่าย :</b> <?= $department_name; ?>
				</div>
				<div class="col-lg-3">
					<b>กลุ่มงาน :</b> <?= $groupName; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 blue">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">รอการอนุมัติ
									<?php $i1 = 0;
									$sqlCount1 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ?";
									$stmt = $connect->prepare($sqlCount1);
									$stmt->bind_param("ii", $i1,$department_id);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;"><a href="loss_data_approve.php?statusListId=0"  style="color: #FFFFFF;">
									<< เรียกดูข้อมูลเพิ่มเติม>>
								</a></div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 red">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">ส่งกลับแก้ไข 
							<?php $i2 = 2;
								$sqlCount2 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ?";
								$stmt = $connect->prepare($sqlCount2);
								$stmt->bind_param("ii", $i2,$department_id);
								$stmt->execute();
								$res = $stmt->get_result();
								if ($rows = $res->fetch_assoc()) {
								echo $rows['num'];
								}
																								?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;"><a href="loss_data_approve.php?statusListId=2"  style="color: #FFFFFF;">
									<< เรียกดูข้อมูลเพิ่มเติม>>
								</a></div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 green">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">รายการอนุมัติแล้ว
									<?php $i3 = 1;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ?";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("ii", $i3,$department_id);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;"><a href="loss_data_approve.php?statusListId=1"  style="color: #FFFFFF;">
									<< เรียกดูข้อมูลเพิ่มเติม>>
								</a></div>
						</div>
					</div>
				</div>
			</div>
			<!---------------->
			

		</div>
	</div>
</div>






<? $statusListId = $_GET['statusListId'];
if($statusListId!=NULL){?>



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
				if ($_GET['listId'] !=NULL) {
					echo "<span style='font-size: 20px;margin:10px;'>ข้อมูลเหตุการณ์ประจำเดือน " . month_name($_GET['m']) . " ปี " . $_GET['y'] . "</span><br>";
				} elseif ($_GET['statusListId'] !=NULL) {
					if ($_GET['statusListId'] == 0) {
						echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลรออนุมัติทั้งหมด</span><br>";
					} elseif ($_GET['statusListId'] == 2) {
						echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลส่งกลับแก้ไขทั้งหมด</span><br>";
					} elseif ($_GET['statusListId'] == 1) {
						echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลที่อนุมัติแล้วทั้งหมด</span><br>";
					}
				}								?>
				<form action = 'export.php' method="post">
				<button type="submit" class="pull-right btn btn-dark"style="margin: 10px;"> <span class="glyphicon glyphicon-download-alt"></span> Export Data</button>
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
											<th style='width:5%'>ลำดับ</th>
											<th style='width:20%'>เหตุการณ์</th>
											<th style='width:10%' width="150">รายงานโดย</th>
											<th style='width:10%'>วันที่บันทึก</th>
											<th style='width:10%' align='center'>ระดับความเสียหาย</th>
											<?if($_GET['statusListId'] == 1) {?> <th style='width:10%'>สถานะจากฝ่ายความเสี่ยง</th> <?}?>
											<?if($_GET['statusListId'] == 1) {?> <th style='width:10%'>วันที่ปิด CASE</th> <?}?>
											<th style='width:20%'>จัดการ</th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										if ($_GET['listId'] !=NULL) {
											$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.loss_data_doc_id ='" . $_GET['listId'] . "' and loss_data_doc.loss_dep =$dep_id";
										} elseif ($_GET['statusListId'] !=NULL) {
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
												<?if($_GET['statusListId'] == 1) {?> <td>
												<?if($status_risk_approve=='0'){?>รออนุมัติ<?}?>
												<?if($status_risk_approve=='1'){?>ดำเนินการแล้วเสร็จ <?}?>
												<?if($status_risk_approve=='2'){?>แก้ไข <?}?></td> <?}?>
												
												<?if($_GET['statusListId'] == 1) {?> <td><?= mysqldate2th_datetime($row['end_date']); ?></td> <?}?>
											
												<td width="250">

													<button name='submit' class="btn btn-success showDetailData" date-id="<?= $row['loss_data_doc_list_id']; ?>" data-happen_date="<?= $row['happen_date']; ?>" data-checked_date="<?= $row['checked_date']; ?>" data-incidence="<?= $row['incidence']; ?>" data-incidence_detail="<?= $row['incidence_detail']; ?>" data-cause="<?= $row['cause']; ?>" data-user_effect="<?= $row['user_effect']; ?>" data-damage_type="<?= $row['damage_type']; ?>" data-incidence_type="<?= $row['incidence_type']; ?>" data-loss_type="<?= $row['loss_type']; ?>" data-control="<?= $row['control']; ?>" data-loss_value="<?= $row['loss_value']; ?>" data-chance="<?= $row['chance']; ?>" data-effect="<?= $row['effect']; ?>" data-damageLevel="<?= $row['damageLevel']; ?>" data-related_dep_id="<?= $row['related_dep_id']; ?>" data-dep_id_1="<?= $row['dep_id_1']; ?>" data-dep_id_2="<?= $row['dep_id_2']; ?>" data-dep_id_3="<?= $row['dep_id_3']; ?>" data-comment_app="<?= $row['comment_app']; ?>" data-approved_date="<?= $row['approved_date']; ?>" data-status_approve="<?= $row['status_approve']; ?>" data-comment_risk="<?= $row['comment_risk']; ?>" data-status_risk_approve="<?= $row['status_risk_approve']; ?>" data-riskcomment_date="<?= $row['riskcomment_date']; ?>" data-attech_name="<?= $row['attech_name']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-list-alt'></i> รายละเอียด</button>
													<?if($status_approve=='0'){?>
													<form action="loss_data_approve.php?action=approve" method="post" target="_blank" style="display: inline;">
													<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
													<button type='submit' name='buttonapprove' value='1' class="btn btn-info" >  อนุมัติ</button>
													</form>
													<form action="loss_data_approve.php?action=approve" method="post" target="_blank" style="display: inline;">
													<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
													<button type='submit' class="btn btn-danger"  name='buttonapprove' value='2' >  ส่งแก้ไข</button>
													</form>
													<?}?>
													<?if($status_approve=='1'){?>
													<form action="pdf.php" method="post" target="_blank" style="display: inline;">
													<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
													<button type='submit' class="btn btn-dark" style="background-color: #949596; color:#FFFFFF;"> <span class="glyphicon glyphicon-print"></span> พิมพ์</button>
													</form>
													<?}?>
												</td>

											</tr>

										<?php } ?>

									</tbody>

								</table>

								<!-- end table -->

							</div>

						</div>
						
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<?}?>
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

				<form method='post' action='loss_data_approve.php' enctype="multipart/form-data">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-xs-12">

								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์</label><input type="text" class="form-control datepicker" name='happen_date' disabled readonly id='happen_date' style="cursor: default;"></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" disabled name='checked_date' readonly id='checked_date' style="cursor: default;"></div>
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
											<div class="col-lg-12"><label class="margins-top-10" style="color:#27A4B0;">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="comment_app" class="form-control" name="comment_app" rows="3" cols="50" style="min-height:80px;border-color:#27A4B0;" ></textarea></div>
											<? if ($status_risk_approve == '3') { ?>
												<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='end_date' readonly id='end_date'></div>
											<? } ?>
											<div class="col-lg-12"><label class="margins-top-10" >ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="comment_risk" class="form-control" name="comment_risk" rows="3" cols="50" style="min-height:80px;" readonly ></textarea></div>
										<? } ?>

									</div>

									<div align="center" style="margin-top: 30px;">
<input type="hidden" id="date_id" name="date_id">
<button type='submit' name='submitLossUpdate' value="submitLossUpdate" class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
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
</form>


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
		var dateId = $(this).attr('date-id');
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

		$('#date_id').val(dateId);
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
		$('#attech_name').val(attech_names);

	});
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


