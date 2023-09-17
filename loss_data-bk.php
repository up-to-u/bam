<?
include('inc/include.inc.php');
echo template_header();

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
	$sql = "SELECT count(loss_data_doc_id) AS cNum FROM loss_data_doc WHERE loss_data_doc_month=? AND loss_year=? AND loss_dep=? ";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param("iii", $loss_data_doc_month, $loss_year, $loss_dep);
	$stmt->execute();
	$resCheck = $stmt->get_result();
	if ($row_check = $resCheck->fetch_assoc()) {
		$checkInsert = $row_check['cNum'];
	}
	if ($checkInsert > 0) {
		echo "<script>alert('รายการซ้ำท่านได้แจ้งเหตุการณ์เดือน " . month_name($loss_data_doc_month) . " ปี " . $loss_year . " แล้ว ( เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้ ! )');</script>";
	} else {
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
}
if ($_POST['submitLossDataList'] == 'submitLossDataList') {
	$loss_data_doc_id = $_POST['loss_data_doc_id'];
	$happen_date = $_POST['happen_date'];
	$checked_date = $_POST['checked_date'];
	$incidence = $_POST['incidence'];
	$incidence_detail = $_POST['incidence_detail'];
	$cause = $_POST['cause'];
	$user_effect = $_POST['user_effect'];
	$damage_typeSplite = explode('@', $_POST['damage_type']);
	$damage_type  = $damage_typeSplite[0];
	$incidence_type = $_POST['incidence_type'];
	$loss_type = $_POST['loss_type'];
	$control = $_POST['control'];
	$loss_value = $_POST['loss_value'];
	$chance = $_POST['chance'];
	$effect = $_POST['effect'];
	$damageLevel = $_POST['effect'] . $_POST['chance'];
	$dep_id_1 = $_POST['dep_id_1'];
	$dep_id_2 = $_POST['dep_id_2'];
	$dep_id_3 = $_POST['dep_id_3'];

	$attech_name = ($_FILES["attech_name"]["name"]);
	$uploadOk = 1;
	if ($attech_name != "" || $attech_name != null) {
		$target_dir = "attech_file/";
		$target_file = $target_dir . basename($_FILES["attech_name"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		$new_name = $user_id . '_ATTECH' . date('Ymdhis') . '.' . $imageFileType;
		$target_file = $target_dir . $new_name;
		$check = getimagesize($_FILES["attech_name"]["tmp_name"]);
		$attech_name = $new_name;

		if ($_FILES["attech_name"]["size"] > 50000000) {
			echo "Sorry, your file is too large. ";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded. ";
			// if everything is ok, try to upload file
		} else {

			if (move_uploaded_file($_FILES["attech_name"]["tmp_name"], $target_file)) {
			} else {
				echo "Sorry, there was an error uploading your file. ";
			}
		}
	}

	if ($uploadOk != 0) {
		$qx = true;
		$stmt = $connect->prepare("INSERT INTO loss_data_doc_list
		(`loss_data_doc_id`,
		`happen_date`,
		`checked_date`,
		`incidence`,
		`incidence_detail`,
		`cause`,
		`user_effect`,
		`damage_type`,
		`incidence_type`,
		`loss_type`,
		`control`,
		`loss_value`,
		`chance`,
		`effect`,
		`damageLevel`,
		`dep_id_1`,
		`dep_id_2`,
		`dep_id_3`,
		`attech_name`,
		`doclist_user_id`) 
		VALUES
		(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		if ($stmt) {
			$stmt->bind_param(
				'issssssiiisiiisiiisi',
				$loss_data_doc_id,
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
				$user_id
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();
				echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');
				document.location.href('loss_data.php');
				</script>";
			} else {
				$connect->rollback();
			}
			//	$stmt->close();
			//	$conn->close();
		} else {
			$error = "<script>alert('เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้');
			document.location.href('loss_data.php');</script>";
		}
	}
}

?>
	<script language="JavaScript">
			function chkNum(ele)
			{
				var num = parseFloat(ele.value);
				ele.value = num.toFixed(2);
			}
		</script>
<style>
	.margins-3 {
		margin-top: 15px;
		margin-bottom: 15px;
	}

	.margins-top-10 {
		margin-top: 10px;
	}
	
	body {
    letter-spacing: 0.7px;
    background-color: #eee;
}

.container {
    margin-top: 100px;
    margin-bottom: 100px;
}

p {
    font-size: 14px;
}

.btn-primary{
    background-color: #42A5F5 !important;
    border-color: #42A5F5 !important;
}

.cursor-pointer {
    cursor: pointer;
    color: #42A5F5;
}

.pic {
    margin-top: 30px;
    margin-bottom: 20px;
}

.card-block {
    width: 200px;
    border: 1px solid lightgrey;
    border-radius: 5px !important;
    background-color: #FAFAFA;
    margin-bottom: 30px;
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
    -webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
    -o-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    filter: grayscale(100%);
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

</style>

<link rel="stylesheet" href="dist/css/bootstrap-select.css">
<script src="dist/js/bootstrap-select.js"></script>
<link href="jquery-ui-1.12.0/jquery-ui.css" rel="stylesheet">
<script src="jquery-ui-1.12.0/jquery-ui.js"></script>
<script language='JavaScript'>
	$(function() {
		$(".datepicker").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: '-10:+5',
			dateFormat: 'yy-mm-dd',
			dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
			dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
			montdocames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
			monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
		});
	});
</script>
<?
$sql13 = "SELECT month_year_id as yid2 FROM loss_time";
$result13 = mysqli_query($connect, $sql13);
$row13 = mysqli_fetch_array($result13);
$yid2 = $row13['yid2'];
$month_year = $yid2;
if ($yid2 == '') {
	$month_year = date('Y') + 543;
}
?>

<div class="row">
	<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 14/06/65</label>
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">แจ้งเหตุการณ์ความเสียหาย</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<!---------------->
			<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
				
				<div class="col-lg-4">

					<b>สายงาน :</b> <?= $division_name; ?>
				</div>
				<div class="col-lg-4">
					<b>ฝ่าย :</b> <?= $department_name; ?>
				</div>
				<div class="col-lg-4">
					<b>กลุ่มงาน :</b> <?= $groupName; ?>
				</div>
			</div>
			
			
<form method='post' action='loss_data.php' enctype="multipart/form-data">
    <div class="row justify-content-center">
               
                <div class="card-body show ">       
                    <div class="radio-group row justify-content-between px-3 text-center a">
                        <div class="col-auto mr-sm-2 mx-1 card-block  py-0 text-center radio  ">
                           <label> <div class="flex-row">
                                <div class="col">
                                    <div class="pic" ><input type="radio" name='loss_have' value='0' required class="img-checker">  <img  class="irc_mut img-fluid" src="images/ok.png" width="100" height="100"> </div>
                                    <p style='font-size: 20px;' ><b><u>ไม่พบ</u>เหตุการณ์</b></p>
                                </div>
                            </div></label>
                        </div>
                        <div class="col-auto ml-sm-2 mx-1 card-block  py-0 text-center radio  ">
                             <label><div class="flex-row">
                                <div class="col">
                                   <div class="pic"><input type="radio" name='loss_have' value='1' required class="img-checker"> <img  class="irc_mut img-fluid" src="images/risks.png"  width="100" height="100"> </div>
                                    <p style='font-size: 20px;'><b><u  >พบ</u>เหตุการณ์</b></p>
                                </div>
                            </div></label>
                        </div>
                    </div>
               
                </div>
				
											<div class="col-xs-12 col-lg-6">

												<select class='form-control input-lg' id='loss_data_doc_month' name='loss_data_doc_month' style="display: inline;">
												<option value=''>แจ้งเหตุการณ์ความเสียหายรายงานประจำเดือน </option>
												
													<? $sql1 = "SELECT *	FROM month 
												JOIN loss_time ON loss_time.month_time_id =  month.month_id
												ORDER BY 	month.month_id  ";
													$result1 = mysqli_query($connect, $sql1);
													while ($row1 = mysqli_fetch_array($result1)) {	?>
														<option value='<?= $row1['month_id'] ?>'><?= $row1['month_name'] ?> <?= $month_year ?></option>
													<?	} ?>
												</select>
												<input type='hidden' name ='loss_year' value='<?= $month_year ?>' >
												<input type="hidden" name="loss_dep" value="<?= $department_id; ?>">

											</div>
										
										<div class="col-xs-12 col-lg-6">
										<button type='submit' name='submitLossDoc' value='submitLossDoc' class="btn btn-danger btn-lg btn-block "><i class='fa fa-save'></i> บันทึก</button>
									</div>
								
									
    </div>
	</form>
	

		
				</div>
			</div>
			
			
			

	
<!---->

<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">รายการเหตุการณ์ความเสียหาย</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<!---------------->
			
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
									$stmt->bind_param("ii", $i1, $department_id);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;"><a href="loss_data_list.php?statusListId=0" style="color: #FFFFFF;">
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
									$stmt->bind_param("ii", $i2, $department_id);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;"><a href="loss_data_list.php?statusListId=2" style="color: #FFFFFF;">
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
									$stmt->bind_param("ii", $i3, $department_id);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;"><a href="loss_data_list.php?statusListId=1" style="color: #FFFFFF;">
									<< เรียกดูข้อมูลเพิ่มเติม>>
								</a></div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
			<div class="form-group" >
				
					<div class="col-lg-12 col-xs-12">

								<!-- start table -->
								<table id="exampleDataTable" class="table table-striped table-bordered" style="width:100%">
									<thead>
										<tr>
											<th style='width:20%'>ช่วงเหตุการณ์</th>
											<th style='width:30%'>ผลการรายงาน</th>
											<th style='width:10%'>รายงานโดย</th>
											<th style='width:10%'>วันที่บันทึก</th>
											<th style='width:10%'>จำนวนรายการ</th>
											<th style='width:30%'>จัดการ</th>
										</tr>
									</thead>
									<tbody>

										<?php
										$sql = "SELECT * FROM loss_data_doc where loss_dep= ? order by loss_create DESC";
										$stmt = $connect->prepare($sql);
										$stmt->bind_param("i", $department_id);
										$stmt->execute();
										$result = $stmt->get_result();
										while ($row = mysqli_fetch_array($result)) {
											$loss_data_doc_id  = $row['loss_data_doc_id'];
										?>
											<tr>
												<td style="vertical-align: middle;"><?= ' ' . month_name($row['loss_data_doc_month']) . ' พ.ศ. ' . $row['loss_year']; ?></td>

												
												<?if ($row['loss_have'] == '0') { ?>
												<td style="vertical-align: middle; background-color: #f56a75;">
												<span class="glyphicon glyphicon-exclamation-sign" style="color:#FFFFFF; margin-left:5px;"></span><span style="color:#FFFFFF;"> พบเหตุการณ์ความเสียหาย</span
												<? } else if ($row['loss_have'] == '1') {  ?>
												<td style="vertical-align: middle; background-color: #1bcf84;">
												<span class="glyphicon glyphicon-ok-sign" style="padding-top:2px; color:#FFFFFF; margin-left:5px;"></span><span style="color: #FFFFFF;"> ไม่พบเหตุการณ์ความเสียหาย</span></div>
												<?}  ?>
												</td>
												<td style="vertical-align: middle;"><?= get_user_name($row['user_id']) ?></td>
												<td style="vertical-align: middle;"><?= mysqldate2th_date($row['loss_create']); ?></td>
												<td style="vertical-align: middle;"><?php $i3 = 0;
													$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
													join loss_data_doc  on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
													WHERE  loss_data_doc.loss_data_doc_id = ?";
													$stmt = $connect->prepare($sqlCount3);
													$stmt->bind_param("i", $loss_data_doc_id);
													$stmt->execute();
													$res = $stmt->get_result();
													if ($rows = $res->fetch_assoc()) {
														echo $rows['num'];
													}
													?></td>
												<td style="vertical-align: middle;" width="300">
													<button class="btn btn-primary confirmSendCase" data-doc-id="<?= $row['loss_data_doc_id']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-share'></i> รายงานเหตุการณ์</button>
													<?php if (checkLossList($row['loss_data_doc_id']) > 0) { ?>
														<form action="loss_data_list.php" methed="post" style="display: inline;">
															<input type="hidden" value="<?= $row['loss_data_doc_id']; ?>" name="listId" id="listId">
															<input type="hidden" value="<?= $row['loss_data_doc_month']; ?>" name="m" id="m">
															<input type="hidden" value="<?= $row['loss_year']; ?>" name="y" id="y">
															<button type='submit' class="btn btn-success"><i class="glyphicon glyphicon-list-alt"></i> รายการเพิ่มเติม</button>
														</form>
													<?php } else {  ?>
														<button disabled type='submit' class="btn btn-dark" style="background-color: #949596; color:#FFFFFF;"><i class="glyphicon glyphicon-list-alt"></i> รายการเพิ่มเติม</button>
													<?php } ?>

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
			<!---------------->
			


<!-- start modal -->
<form method='post' onsubmit="return validateForm()" action='loss_data.php' enctype="multipart/form-data">
	<div id="myModalSendCase" class="modal fade" role="dialog">
		<div class="modal-dialog  modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background-color:#004C85;color:#FFFFFF;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-list-alt"></span> รายงานความเสียหาย</h4>
				</div>
				<div class="modal-body" align="left">

					<form method='post' action='loss_data.php' enctype="multipart/form-data">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-12 col-xs-12">

									<div class="form-group">
										<div class="row">
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์</label><input type="text" class="form-control datepicker" name='happen_date' readonly id='happen_date'></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='checked_date' readonly id='checked_date'></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10">เหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control" name='incidence' id='incidence'></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detail" class="form-control" name="incidence_detail" rows="3" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="cause" class="form-control" name="cause" rows="3" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><input type="text" class="form-control" name='user_effect' id='user_effect'></div>
											<div class="col-lg-12"> <label class="margins-top-10">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_type' id='damage_type' class="form-control">
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
											<div class="col-lg-12"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='incidence_type' id='incidence_type' class="form-control">
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

											<div class="col-lg-3"> <label class="margins-top-10 col-xs-12" style="margin-left: -13px;">Loss :<span style="color: red;">*</span> </label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type1" value="1" checked> Actual Loss
												</label></div>
											<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type2" value="2"> Potential Loss
												</label></div>
											<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type3" value="3"> Near-Missed
												</label></div>

											<div class="col-lg-12"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><input type="text" class="form-control" name='control' id='control'></div>
											<div class="col-lg-3"><label class="margins-top-10">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="number" value="0" min="0.01" step="0.01" class="form-control" name='loss_value' id='loss_value' OnChange="JavaScript:chkNum(this)"></div>
											<div class="col-lg-3"><label class="margins-top-10">โอกาส<span style="color: red;">*</span></label><select name='chance' id='chance' class="form-control">
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
														if ($row1['factor_no'] == '1') {
															$bColor = "#5FB9A1";
														} else if ($row1['factor_no'] == '2') {
															$bColor = "#FFC11E";
														} else if ($row1['factor_no'] == '3') {
															$bColor = "#FF7A38";
														} else if ($row1['factor_no'] == '4') {
															$bColor = "#EF4F51";
														} else if ($row1['factor_no'] == '5') {
															$bColor = "#C11115";
														}
													?>
														<option style="background-color: <?= $bColor ?>; color:#FFFFFF;" value="<?= $row1['factor_no'] ?>"><?= $row1['factor'] ?></option>
													<? } ?>
												</select></div>
											<div class="col-lg-3"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><select name='effect' id='effect' class="form-control">
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
														if ($row1['factor_no'] == '1') {
															$bColor = "#5FB9A1";
														} else if ($row1['factor_no'] == '2') {
															$bColor = "#FFC11E";
														} else if ($row1['factor_no'] == '3') {
															$bColor = "#FF7A38";
														} else if ($row1['factor_no'] == '4') {
															$bColor = "#EF4F51";
														} else if ($row1['factor_no'] == '5') {
															$bColor = "#C11115";
														}
													?>
														<option style="background-color: <?= $bColor ?>; color:#FFFFFF;" value="<?= $row1['factor_no'] ?>"><?= $row1['factor'] ?></option>
													<?		} ?>
												</select></div>

											<div class="col-lg-3" id="showPerformance" style="margin-top:35px;">

											</div>
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 1</label><select name='dep_id_1' id='dep_id_1' class="form-control">
													<option value="0"> - - - -</option>
													<?

													$sql = "SELECT * FROM department 
													WHERE 
														department.mark_del = 0 AND department_level_id = '4'
													ORDER BY 
														department.is_branch, 
														department.department_name";
													$result1 = mysqli_query($connect, $sql);
													while ($row1 = mysqli_fetch_array($result1)) {
													?>
														<option value="<?= $row1['department_id'] ?>" <? if ($row1['department_id'] == $row2['department_id']) echo 'selected' ?>><?= $row1['department_name'] ?></option>
													<?		} ?>
												</select></div>
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 2</label><select name='dep_id_2' id='dep_id_2' class="form-control">
													<option value="0"> - - - -</option>
													<?

													$sql = "SELECT * FROM department 
													WHERE 
														department.mark_del = 0 AND department_level_id = '4'
													ORDER BY 
														department.is_branch, 
														department.department_name";
													$result1 = mysqli_query($connect, $sql);
													while ($row1 = mysqli_fetch_array($result1)) {
													?>
														<option value="<?= $row1['department_id'] ?>" <? if ($row1['department_id'] == $row2['department_id']) echo 'selected' ?>><?= $row1['department_name'] ?></option>
													<?		} ?>
												</select></div>
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 3</label><select name='dep_id_3' id='dep_id_3' class="form-control">
													<option value="0"> - - - -</option>
													<?
													$sql = "SELECT * FROM department 
WHERE 
	department.mark_del = 0 AND department_level_id = '4'
ORDER BY 
	department.is_branch, 
	department.department_name";
													$result1 = mysqli_query($connect, $sql);
													while ($row1 = mysqli_fetch_array($result1)) {
													?>
														<option value="<?= $row1['department_id'] ?>" <? if ($row1['department_id'] == $row2['department_id']) echo 'selected' ?>><?= $row1['department_name'] ?></option>
													<?		} ?>
												</select></div>
											<div class="col-lg-12"><label class="margins-top-10">เอกสารแนบ</label>
												<input type="file" class="form-control" name='attech_name'>
												<input type='hidden' name='loss_data_doc_id' id='loss_data_doc_id'>
											</div>
										</div>
										<div align="center" style="margin-top: 30px;">

											<button type='submit' name='submitLossDataList' value="submitLossDataList" class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
										</div>


									</div>
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
$(document).ready(function () {
    $('.radio-group .radio').click(function () {
        $('.selected .fa').removeClass('fa-check');
        $('.radio').removeClass('selected');
        $(this).addClass('selected');
    });
});

	$(document).ready(function() {
		var table = $('#exampleDataTable').DataTable({
			responsive: true
		});

		new $.fn.dataTable.FixedHeader(table);
	});
	$(".confirmSendCase").on("click", function() {
		var docId = $(this).attr('data-doc-id');
		$('#loss_data_doc_id').val(docId);

	});


	function validateForm() {
		selected = document.querySelector('input[name="loss_type"]:checked').value;

		if ($('#checked_date').val() == '') {
			alert("กรุณาระบุวันที่ตรวจพบ");
			return false;
		} else if ($('#happen_date').val() > $('#checked_date').val()) {
			alert("กรุณาตรวจสอบวันที่เกิดเหตุการณ์และวันที่ตรวจพบ");
			return false;
		} else if ($('#incidence').val() == '') {
			alert("กรุณาระบุเหตุการณ์");
			return false;
		} else if ($('#incidence_detail').val() == "") {
			alert("กรุณาระบุรายละเอียดเหตุการณ์");
			return false;
		} else if ($('#cause').val() == "") {
			alert("กรุณาระบุสาเหตุ");
			return false;
		} else if ($('#user_effect').val() == "") {
			alert("กรุณาระบุผลกระทบ");
			return false;
		} else if ($('#damage_type').val() == "0") {
			alert("กรุณาเลือกประเภทความเสียหาย");
			return false;
		} else if ($('#incidence_type').val() == "0") {
			alert("กรุณาเลือกประเภทเหตุการณ์ความเสียหาย");
			return false;
		} else if ($('#control').val() == "") {
			alert("กรุณาระบุข้อมูลการควบคุมที่มีอยู่");
			return false;
		} else if ($('#loss_value').val() == "") {
			alert("กรุณาระบุมูลค่าความเสียหาย");
			return false;
		} else if ($('#chance').val() == "0") {
			alert("กรุณาระบุโอกาส");
			return false;
		} else if ($('#effect').val() == "0") {
			alert("กรุณาระบุผลกระทบ");
			return false;
		}
		return true;
	}
	$('#incidence_type').attr("disabled", true);
	$('#damage_type').on('change', function() {


		$arrayDamage = $('#damage_type').val().split('@');

		const dbParam = JSON.stringify({
			"limit": $arrayDamage[1]
		});
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObj = JSON.parse(this.responseText);

			if (myObj.length == 0) {
				$('#incidence_type').attr("disabled", true);
				document.getElementById("incidence_type").innerHTML = "";
				$("#incidence_type").append('<option value="0"> - - - เลือก - - -</option>');
			} else {

				$('#incidence_type').attr("disabled", false);
				document.getElementById("incidence_type").innerHTML = "";
				$("#incidence_type").append('<option value="0"> - - - เลือก - - -</option>');
				$('#incidence_type').prop('selectedIndex', 0);
				for (let x in myObj) {

					$("#incidence_type").append('<option value="' + myObj[x].loss_factor_id + '">' + myObj[x].factor + '</option>');
				}
			}

		}
		xmlhttp.open("POST", "api/lossDataOnchange.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);

	});

	$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
	$('#chance').on('change', function() {
		if ($('#chance').val() != 0 && $('#effect').val() != 0) {
			var strCheck = $('#chance').val() + $('#effect').val()
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
			var strCheck = $('#chance').val() + $('#effect').val()
			document.getElementById("showPerformance").innerHTML = "";
			$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
		}


	});

	$('#effect').on('change', function() {
		if ($('#chance').val() != 0 && $('#effect').val() != 0) {
			var strCheck = $('#chance').val() + $('#effect').val()
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
			var strCheck = $('#chance').val() + $('#effect').val()
			document.getElementById("showPerformance").innerHTML = "";
			$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
		}
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