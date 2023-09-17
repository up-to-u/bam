<?
include('inc/include.inc.php');
echo template_header();
$view_year = intval($_GET['view_year']);
if ($_GET['view_year'] == 0) {
	$view_year = date('Y') + 543;
}
 $view_month = intval($_GET['view_month']);
if ($_GET['view_month'] == 0) {
	$view_month = 'ALL';
}
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

				if ($loss_have == "1" && $connect) {


					$sqlCount1 = "SELECT loss_data_doc_id FROM loss_data_doc WHERE  user_id = ? and loss_create = CURRENT_DATE() ORDER BY  loss_data_doc_id DESC  LIMIT 1";
					$stmt = $connect->prepare($sqlCount1);
					$stmt->bind_param("i", $user_id);
					$stmt->execute();
					$res = $stmt->get_result();
					if ($rows = $res->fetch_assoc()) {
						$select_doclist_id = $rows['loss_data_doc_id'];
				
					}
				
					echo "<script>
					jQuery(document).ready(function(e) {
						
						jQuery('#myModalSendCase').modal();
					});
					</script>";
				} else if ($loss_have == "0" && $connect) {
					echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');</script>";
				}
			} else {
				$connect->rollback();
			}
		} else {
			$error = "<script>alert('เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้');</script>";
		}
	}
}
if ($_POST['submitLossDataList'] == 'submitLossDataList') {
	$loss_data_doc_id_auto =$_POST['loss_data_doc_id_auto'];
	if($loss_data_doc_id_auto != ""){
		$loss_data_doc_id = $loss_data_doc_id_auto;
	}else{
		$loss_data_doc_id = $_POST['loss_data_doc_id'];
	}

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
    $loss_values = $_POST['loss_value'];
	$loss_value = str_replace(",","",$loss_values);
	$chance = $_POST['chance'];
	$effect = $_POST['effect'];
if(checkLossLevel($effect.$chance) == "1"){
	$displayRisk = "ต่ำ";
}else if(checkLossLevel($effect.$chance) == "2"){
	$displayRisk = "ปานกลาง";
}else if(checkLossLevel($effect.$chance) == "3"){
	$displayRisk = "สูง";
}else if(checkLossLevel($effect.$chance) == "4"){
	$displayRisk = "สูงมาก";
}

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
		$new_name = $user_id . '1_ATTECH' . date('Ymdhis') . '.' . $imageFileType;
		$target_file = $target_dir . $new_name;
		$check = getimagesize($_FILES["attech_name"]["tmp_name"]);
		$attech_name = $new_name;

		if ($_FILES["attech_name"]["size"] > 50000000) {
			echo "Sorry, your file1 is too large. ";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file1 was not uploaded. ";
			// if everything is ok, try to upload file
		} else {

			if (move_uploaded_file($_FILES["attech_name"]["tmp_name"], $target_file)) {
			} else {
				echo "Sorry, there was an error uploading your file1. ";
			}
		}
	}

	$attech_name2 = ($_FILES["attech_name2"]["name"]);
	$uploadOk2 = 1;
	if ($attech_name2 != "" || $attech_name2 != null) {
		$target_dir2 = "attech_file/";
		$target_file2 = $target_dir2 . basename($_FILES["attech_name2"]["name"]);
		$imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
		$new_name2 = $user_id . '2_ATTECH' . date('Ymdhis') . '.' . $imageFileType2;
		$target_file2 = $target_dir . $new_name2;
		$check2 = getimagesize($_FILES["attech_name2"]["tmp_name"]);
		$attech_name2 = $new_name2;

		if ($_FILES["attech_name2"]["size"] > 50000000) {
			echo "Sorry, your file2 is too large. ";
			$uploadOk2 = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk2 == 0) {
			echo "Sorry, your file2 was not uploaded. ";
			// if everything is ok, try to upload file
		} else {

			if (move_uploaded_file($_FILES["attech_name2"]["tmp_name"], $target_file2)) {
			} else {
				echo "Sorry, there was an error uploading your file2. ";
			}
		}
	}


	if ($uploadOk != 0 || $uploadOk2 != 0) {
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
		`displayRisk`,
		`damageLevel`,
		`dep_id_1`,
		`dep_id_2`,
		`dep_id_3`,
		`attech_name`,
		`attech_name2`,
		`doclist_user_id`) 
		VALUES
		(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		if ($stmt) {
			$stmt->bind_param(
				'issssssiiisdiissiiissi',
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
				$displayRisk,
				$damageLevel,
				$dep_id_1,
				$dep_id_2,
				$dep_id_3,
				$attech_name,
				$attech_name2,
				$user_id
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();
				echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');
				document.location.href('loss_data.php');
				</script>";


				$qx = true;
				$loss_have = 1;
				$stmt = $connect->prepare("UPDATE loss_data_doc SET
					loss_have=?
					WHERE loss_data_doc_id=? ");

				if ($stmt) {
					$stmt->bind_param(
						'ii',
						$loss_have,
						$loss_data_doc_id
					);
					$q = $stmt->execute();
					$qx = ($qx and $q);

					if ($qx) {
						$connect->commit();
					}
				}
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
<style>
h4{
	font-family: "prompt", sans-serif !important;

}
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

	.btn-primary {
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

		border-radius: 5px !important;
		background-color: #FAFAFA;
		margin: 50px;


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
    -ms-transform: scale(2); /* IE 9 */
    -webkit-transform: scale(2); /* Chrome, Safari, Opera */
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

	.font-green {
		color: #261ecd !important;
	}
	
	.portlet.light > .portlet-title > .caption > .caption-subject {
    font-size: 20px !important;
}
	.modal .modal-header {
		border-bottom: 1px solid #EFEFEF !important;
		background-color: #004C85 !important;
	}
	.box-matrix {
	width:70px;
	height:70px;
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
<script>

$(function () {

	$(".r1").mousedown(function () {
		$(this).attr('previous-value', $(this).prop('checked'));
	});

	$(".r1").click(function () {
		var previousValue = $(this).attr('previous-value');
		if (previousValue == 'true')
			$(this).prop('checked', false);
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
				
				<div class="col-xs-12 col-lg-6">

						<select class='form-control input-lg' id='loss_data_doc_month' name='loss_data_doc_month' required style="display: inline;">
							<option value=''>--- เดือน ---</option>

							<? $sql1 = "SELECT *	FROM month 
												JOIN loss_time ON loss_time.month_time_id =  month.month_id
												ORDER BY 	month.month_id  ";
							$result1 = mysqli_query($connect, $sql1);
							while ($row1 = mysqli_fetch_array($result1)) {	?>
								<option value='<?= $row1['month_id'] ?>'><?= $row1['month_name'] ?> </option>
							<?	} ?>
						</select>
						<input type='hidden' name='loss_year' value='<?= $month_year ?>'>
						<input type="hidden" name="loss_dep" value="<?= $department_id; ?>">

					</div>
					
					<div class="col-xs-12 col-lg-6">

						<select class='form-control input-lg' id='' name='' style="display: inline;">
							<option value=''><?= $month_year ?></option>
						</select>
					
					</div>
					
					<div class="col-xs-12 col-lg-12">
					<div class="card-body show ">
						<div class="radio-group row justify-content-between px-3 text-center a">
							<div class="col-auto mr-sm-2 mx-1 card-block  py-0 text-center radio radio1 " >
								<label>
									<div class="flex-row">
										<div class="col">
											<div class="pic"><input type="radio"  name='loss_have' value='0' required class="img-checker r1"> <img class="irc_mut img-fluid" src="images/ok.png" width="100" height="100"> </div>
											<p style='font-size: 20px;'><b><u>
														<font color='green'>ไม่พบ</font></u>เหตุการณ์</b></p>
										</div>
									</div>
								</label>
							</div>
							<div class="col-auto ml-sm-2 mx-1 card-block  py-0 text-center radio radio2 " >
								<label>
									<div class="flex-row">
										<div class="col">
											<div class="pic"><input type="radio" name='loss_have' value='1' required class="img-checker r1"> <img class="irc_mut img-fluid" src="images/risks.png" width="100" height="100"> </div>
											<p style='font-size: 20px;'><b><u>
														<font color='red'>พบ</font></u>เหตุการณ์</b></p>
										</div>
									</div>
								</label>
							</div>
						</div>
					
					
					</div>
					<div class="col-xs-12 col-lg-12">
						<button type='submit' name='submitLossDoc' value='submitLossDoc' class="btn btn-info btn-lg btn-block "><i class='fa fa-save'></i> บันทึก</button>
					</div>
					</div>

					


				</div>
			</form>



		</div>
	</div>





	<!---->

	<div class="col-lg-12 col-lg-12 col-sm-12">
		<table>
			<tr>
				<td>แสดงข้อมูล </td>
				<td width='15'></td>
				<td>
				

						<select class='form-control input-lg' id='loss_data_doc_month' onChange='document.location="loss_data.php?view_year=<?=$_GET["view_year"]?>&view_month="+this.value' >
							<option value='ALL'>--- เดือนทั้งหมด ---</option>

							<? $sql1 = "SELECT * FROM month  ORDER BY 	month.month_id  ";
							$result1 = mysqli_query($connect, $sql1);
							while ($row1 = mysqli_fetch_array($result1)) {	?>
								<option value='<?= $row1['month_id'] ?>'<?if($_GET['view_month']==$row1['month_id']){ echo" selected";}?> ><?= $row1['month_name'] ?></option>
							<?	} ?>
						</select>

				</td>
				<td>
					<select name='view_year' class="form-control input-lg" onChange='document.location="loss_data.php?view_month=<?=$_GET["view_month"]?>&view_year="+this.value'>
						<option value='<?= $view_year - 2 ?>'><?= $view_year - 2 ?></option>
						<option value='<?= $view_year - 1 ?>'><?= $view_year - 1 ?></option>
						<option value='<?= $view_year ?>' selected><?= $view_year ?></option>
						<option value='<?= $view_year + 1 ?>'><?= $view_year + 1 ?></option>
						<option value='<?= $view_year + 2 ?>'><?= $view_year + 2 ?></option>
						<select>
				</td>
			</tr>
		</table>
		<br>
	</div>



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

			<!-- <div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 blue">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">รอการอนุมัติ
									<?php $i1 = 0;
									$sqlCount1 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_year=?";
									$stmt = $connect->prepare($sqlCount1);
									$stmt->bind_param("iii", $i1, $department_id, $view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;">
								<form action="loss_data_list.php?statusListId=0" method="post" style="display: inline;">
									<input type="hidden" value="<?= $listId; ?>" name="listId" id="listId">
									<input type="hidden" value="<?= $m; ?>" name="m" id="m">
									<input type="hidden" value="<?= $y; ?>" name="y" id="y">
									<button type='submit' class="btn btn-success"> Click ดูข้อมูลเพิ่มเติม</button>
								</form>
							</div>

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
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_year=? ";
									$stmt = $connect->prepare($sqlCount2);
									$stmt->bind_param("iii", $i2, $department_id, $view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;">
								<form action="loss_data_list.php?statusListId=2" method="post" style="display: inline;">
									<input type="hidden" value="<?= $listId; ?>" name="listId" id="listId">
									<input type="hidden" value="<?= $m; ?>" name="m" id="m">
									<input type="hidden" value="<?= $y; ?>" name="y" id="y">
									<button type='submit' class="btn btn-danger"> Click ดูข้อมูลเพิ่มเติม</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 green">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">อนุมัติแล้ว
									<?php $i3 = 1;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_year=?";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("iii", $i3, $department_id, $view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;">
								<form action="loss_data_list.php?statusListId=1" method="post" style="display: inline;">
									<input type="hidden" value="<?= $listId; ?>" name="listId" id="listId">
									<input type="hidden" value="<?= $m; ?>" name="m" id="m">
									<input type="hidden" value="<?= $y; ?>" name="y" id="y">

									<button type='submit' class="btn btn-danger" style='background-color: #51dca2; border-color: #51dca2;'> Click ดูข้อมูลเพิ่มเติม</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div> -->

			<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
				<div class="form-group">

					<div class="col-lg-12 col-xs-12">

						<!-- start table -->
						<table id="exampleDataTable" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th style='width:15%'>ช่วงเหตุการณ์</th>
									<th style='width:20%'>ผลการรายงาน</th>
									
									<th style='width:10%'>จำนวนรายการ</th>
									<th style='width:10%'>สถานะรายงาน</th>
									<th style='width:30%'>จัดการ</th>
								</tr>
							</thead>
							<tbody>

								<?php
								
								$sql = "SELECT * FROM loss_data_doc where loss_dep= ? and loss_year=? ";
								if($view_month!='ALL'){
									$sql = $sql."and loss_data_doc_month='$view_month'";
									}
								$sql = $sql." order by loss_data_doc_month ASC";
								
								//echo $sql;
								$stmt = $connect->prepare($sql);
								$stmt->bind_param("ii", $department_id, $view_year);
								$stmt->execute();
								$result = $stmt->get_result();
								while ($row = mysqli_fetch_array($result)) {
									$loss_data_doc_id  = $row['loss_data_doc_id'];
								?>
									<tr>
										<td style="vertical-align: middle;"><?= ' ' . month_name($row['loss_data_doc_month']) . ' พ.ศ. ' . $row['loss_year']; ?></td>


										<? if ($row['loss_have'] == '1') { ?>
											<td style="vertical-align: middle; ">
												<span class="glyphicon glyphicon-exclamation-sign" style="color:#f56a75; margin-left:5px;"></span><span style="color:#f56a75;"><b> พบเหตุการณ์ความเสียหาย</b></span>
											<? } else if ($row['loss_have'] == '0') {  ?>
											<td style="vertical-align: middle; ">
												<span class="glyphicon glyphicon-ok-sign" style="padding-top:2px; color:#1bcf84; margin-left:5px;"></span><span style="color: #1bcf84;"><b> ไม่พบเหตุการณ์ความเสียหาย</b></span>
					</div>
				<? }  ?>
				</td>
				
				<td style="vertical-align: middle;">
					<? $i = 0;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
													join loss_data_doc  on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
													WHERE  loss_data_doc.loss_data_doc_id = ? and loss_data_doc_list.status_approve =? ";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("ii", $loss_data_doc_id, $i);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										$c0 =  $rows['num'];
									}
					?>
					<? $i2 = 2;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
													join loss_data_doc  on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
													WHERE  loss_data_doc.loss_data_doc_id = ? and loss_data_doc_list.status_approve =? ";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("ii", $loss_data_doc_id, $i2);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										$c2 =  $rows['num'];
									}
					?>
					<? $i1 = 1;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
													join loss_data_doc  on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
													WHERE  loss_data_doc.loss_data_doc_id = ? and loss_data_doc_list.status_approve =? ";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("ii", $loss_data_doc_id, $i1);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										$c1 =  $rows['num'];
									}
					?>
					<form action="loss_data_list.php" method="post" style="display: inline;">
						<input type="hidden" value="<?= $row['loss_data_doc_id']; ?>" name="listId" id="listId">
						<input type="hidden" value="<?= $row['loss_data_doc_month']; ?>" name="m" id="m">
						<input type="hidden" value="<?= $row['loss_year']; ?>" name="y" id="y">
						<input type="hidden" value="y" name="checkStatus" id="checkStatus">

						<button type="submit" name="submit0" value="0" class="btn btn-primary btn-block" style='text-align: left;'><span class="badge"><?= $c0; ?></span> รออนุมัติ </button>
						<button type="submit" name="submit2" value="2" class="btn btn-danger btn-block" style='text-align: left;'><span class="badge"><?= $c2; ?></span> แก้ไข </button>
						<button type="submit" name="submit1" value="1" class="btn btn-success btn-block" style='background-color: #26c281; border-color: #26c281; text-align: left;'><span class="badge"><?= $c1; ?></span> อนุมัติแล้ว </button>
					</form>
				</td>
				<td style="vertical-align: middle;">
					<? if ($row['status_lossapprove'] == '1') {
										echo "<font color='#1bcf84'><b> อนุมัติแล้ว<br>วันที่ " . mysqldate2th_date($row['approveddate']);
									} else {
										echo " <font color='red'><b> รอการอนุมัติ ";
									} ?></b></font>
				</td>
				<td style="vertical-align: middle;" width="300">

					<?php $i1 = 0;
									$sqlCount1 = "SELECT COUNT(*) AS num FROM loss_time
														WHERE month_time_id =? and month_year_id =?";
									$stmt = $connect->prepare($sqlCount1);
									$stmt->bind_param("ii", $row['loss_data_doc_month'], $view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										$num = $rows['num'];
									}
					?>


					<? if ($num > 0) { ?><button class="btn btn-primary confirmSendCase" data-doc-id="<?= $row['loss_data_doc_id']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-plus'></i> รายงานเหตุการณ์</button> <? } ?>
					<?php if (checkLossList($row['loss_data_doc_id']) > 0) { ?>
						<form action="loss_data_list.php" method="post" style="display: inline;">
							<input type="hidden" value="<?= $row['loss_data_doc_id']; ?>" name="listId" id="listId">
							<input type="hidden" value="<?= $row['loss_data_doc_month']; ?>" name="m" id="m">
							<input type="hidden" value="<?= $row['loss_year']; ?>" name="y" id="y">
							<button type='submit' class="btn btn-success"><i class="glyphicon glyphicon-list-alt"></i> ดูเพิ่มเติม</button>
						</form>
					<?php } else {  ?>
						<button disabled type='submit' class="btn btn-dark" style="background-color: #949596; color:#FFFFFF;"><i class="glyphicon glyphicon-list-alt"></i> ดูเพิ่มเติม</button>
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
											<input type="hidden" name="loss_data_doc_id_auto" id="loss_data_doc_id_auto" value="<?=$select_doclist_id;?>">
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่เกิดเหตุการณ์<span style="color: red;">*</span> </label><input type="text" class="form-control datepicker" required name='happen_date' readonly id='happen_date'></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" name='checked_date' readonly id='checked_date'></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">เหตุการณ์<span style="color: red;">*</span></label><textarea class="form-control" name='incidence' id='incidence' rows="2" cols="50" style="min-height:80px;"></textarea> </div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detail" class="form-control" name="incidence_detail" rows="2" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">สาเหตุ<span style="color: red;">*</span></label><textarea id="cause" class="form-control" name="cause" rows="2" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label><textarea class="form-control" name='user_effect' id='user_effect' rows="2" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-12"> <label class="margins-top-10 label-main">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_type' id='damage_type' class="form-control">
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
											<div class="col-lg-12"><label class="margins-top-10 label-main">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='incidence_type' id='incidence_type' class="form-control">
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
											<div class="col-lg-12"><label class="margins-top-10  label-main" >ความเสียหาย<span style="color: red;">*</span> </label> 
											<div class="col-lg-12"><label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type1" value="1" checked>&nbsp;  <b>Actual Loss : </b> ความเสียหายที่เกิดขึ้นจริงทั้งที่เป็นตัวเงิน และไม่เป็นตัวเงิน
												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type2" value="2">&nbsp; <b> Potential Loss : </b>ความเสียหายที่อาจเกิดขึ้น

												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type3" value="3">&nbsp; <b> Near-Missed : </b>ความเสียหายที่เกิดขึ้น หรืออาจเกิดขึ้น แต่สามารถป้องกันความเสียหายไว้ได้
												</label></div>
</div>
											<div class="col-lg-12"><label class="margins-top-10 label-main">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><textarea class="form-control" name='control' id='control' rows="2" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-3"><label class="margins-top-10 label-main">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" value="0" class="form-control" name='loss_value' id='loss_value' onkeypress="return CheckNumeric()"></div>
											<div class="col-lg-3"><label class="margins-top-10 label-main">โอกาส<span style="color: red;">*</span></label><select name='chance' id='chance' class="form-control">
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
											<div class="col-lg-3"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label><select name='effect' id='effect' class="form-control">
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

											<div class="col-lg-2" id="showPerformance" style="margin-top:35px;"></div>
											<div class="col-lg-1" style="margin-top:45px;">
										<span class="glyphicon glyphicon-search modalMatrix" style="color: #004C85; cursor: pointer;" data-toggle="modal" data-target="#myModalMatrix"></span>
									</div>
											<!--
											<div class="col-lg-4"><label class="margins-top-10 label-main">ฝ่ายงานที่เกี่ยวข้อง 1</label><select name='dep_id_1' id='dep_id_1' class="form-control">
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
											<div class="col-lg-4"><label class="margins-top-10 label-main">ฝ่ายงานที่เกี่ยวข้อง 2</label><select name='dep_id_2' id='dep_id_2' class="form-control">
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
											<div class="col-lg-4"><label class="margins-top-10 label-main">ฝ่ายงานที่เกี่ยวข้อง 3</label><select name='dep_id_3' id='dep_id_3' class="form-control">
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
												
												-->
												
											<div class="col-lg-6"><label class="margins-top-10 label-main">เอกสารแนบ 1</label>
												<input type="file" class="form-control" name='attech_name'>
												<input type='hidden' name='loss_data_doc_id' id='loss_data_doc_id'>
											</div>
											<div class="col-lg-6"><label class="margins-top-10 label-main">เอกสารแนบ 2</label>
												<input type="file" class="form-control" name='attech_name2'>
												<input type='hidden' name='loss_data_doc_id2' id='loss_data_doc_id2'>
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
<!-- Modal --> 
<div id="myModalMatrix" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:#EFEFEF;">ตารางแสดงผลการวัดระดับความเสี่ยง (Risk Matrix)</h4>
      </div>
      <div class="modal-body" align="center">
		  <div id="showMatrix">
		  
		  </div>
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
<? echo template_footer(); ?>
<script>
$(".modalMatrix").on("click", function() {

document.getElementById("showMatrix").innerHTML = "";
var xEffect = $('#effect').val();
var ychance = $('#chance').val();

var dataMitrix  = "";
 dataMitrix = `<table align="center">
		   <tr>
			   <td rowspan="7"><img src="images/risk_matrix_axis_y.png"></td>
			   <td class="box-matrix" align="center" style="font-size: 10px; ">Catastrophic<br>5</td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400; ">`;
			   if(xEffect == "5" && ychance =="1"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			}  

			   dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400; ">`;
			   if(xEffect == "5" && ychance =="2"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			}  

			   dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400; ">`;
			   
			   if(xEffect == "5" && ychance =="3"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			}  

			   dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF0000; ">`;
			   
			   if(xEffect == "5" && ychance =="4"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			}  

			   dataMitrix += `<p class="text-matrix">สูงมาก</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF0000; ">`;
			   
			   if(xEffect == "5" && ychance =="5"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			   
			}  
			   
			   dataMitrix += `<p class="text-matrix">สูงมาก</p></td>
		   </tr>`;
		   dataMitrix += `<tr>
		   <td class="box-matrix" align="center" style="font-size: 10px;">Major<br>4</td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400;">`;
			   if(xEffect == "4" && ychance =="1"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			}  

			dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400;">`;
			   
			   if(xEffect == "4" && ychance =="2"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			} 

			dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400;">`;
			   
			   if(xEffect == "4" && ychance =="3"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			   
			} 
			  
			dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF0000;">`;
			   
			   if(xEffect == "4" && ychance =="4"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			} 

			dataMitrix += `<p class="text-matrix">สูงมาก</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF0000;">`;
			   
			   if(xEffect == "4" && ychance =="5"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			} 

			dataMitrix += `<p class="text-matrix">สูงมาก</p></td>
		   </tr>
		   <tr>
			    <td class="box-matrix" align="center" style="font-size: 10px;">Moderate<br>3</td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FFFF00;">`;

			if(xEffect == "3" && ychance =="1"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}   

			   dataMitrix += `<p class="text-matrix">ปานกลาง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FFFF00;">`;
		    if(xEffect == "3" && ychance =="2"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}      
			dataMitrix += `<p class="text-matrix">ปานกลาง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400;">`;
			   if(xEffect == "3" && ychance =="3"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}      

			dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400;">`;
			   if(xEffect == "3" && ychance =="4"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}     
			 
			dataMitrix += `<p class="text-matrix">สูง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400;">`;
			   if(xEffect == "3" && ychance =="5"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}     
			dataMitrix += `<p class="text-matrix">สูง</p></td>
		   </tr>
		   <tr>`;
		   dataMitrix += `<td class="box-matrix" align="center" style="font-size: 10px;">Minor<br>2</td>
			   <td class="box-matrix box-matrix-border" style="background-color: #00B050;">`;
			   if(xEffect == "2" && ychance =="1"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}   

			dataMitrix += `<p class="text-matrix">ต่ำ</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #00B050;">`;
			   if(xEffect == "2" && ychance =="2"){
				dataMitrix += ` <div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}   
			dataMitrix += `<p class="text-matrix">ต่ำ</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FFFF00;">`;
			   if(xEffect == "2" && ychance =="3"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}   
			dataMitrix += `<p class="text-matrix">ปานกลาง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FFFF00;">`;
			   if(xEffect == "2" && ychance =="4"){
				dataMitrix += `<div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}   
			dataMitrix += `<p class="text-matrix">ปานกลาง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FF9400;">`;
			   if(xEffect == "2" && ychance =="5"){
				dataMitrix += ` <div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;

			}   

			dataMitrix += `
			   <p class="text-matrix">สูง</p></td>
		   </tr>`;
		   dataMitrix += `<tr>
			    <td class="box-matrix" align="center" style="font-size: 10px;">Insignificant<br>1</td>
			   <td class="box-matrix box-matrix-border" style="background-color: #00B050;">`;
			   if(xEffect == "1" && ychance =="1"){
				dataMitrix += ` <div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			   }

			   dataMitrix += `<p class="text-matrix">ต่ำ</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #00B050;">`;
			   if(xEffect == "1" && ychance =="2"){
				dataMitrix += ` <div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			   }

			   dataMitrix += `<p class="text-matrix">ต่ำ</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FFFF00;">`;
			   if(xEffect == "1" && ychance =="3"){
				dataMitrix += ` <div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			   }

			   dataMitrix += `<p class="text-matrix">ปานกลาง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FFFF00;">`;
			   if(xEffect == "1" && ychance =="4"){
				dataMitrix += ` <div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			   }

			   dataMitrix += `<p class="text-matrix">ปานกลาง</p></td>
			   <td class="box-matrix box-matrix-border" style="background-color: #FFFF00;">`;
			   if(xEffect == "1" && ychance =="5"){
				dataMitrix += ` <div style="position: absolute; width:70px;height:70px; margin-top:-5px; border: 3px; border:  5px solid; border-color: #ffffff;"  ></div>`;
			   }

			   dataMitrix += `<p class="text-matrix">ปานกลาง</p></td>
		   </tr>
		   <tr>
			  <td></td>
		   <td align="center" style="font-size: 10px;" >1<br>Very Low</td>
  <td align="center" style="font-size: 10px;">2<br>Low</td>
  <td align="center" style="font-size: 10px;">3<br>Medium</td>
  <td align="center" style="font-size: 10px;">4<br>High</td>
  <td align="center" style="font-size: 10px;">5<br>Very High</td>
		   </tr>
		   <tr>
		  
		   </tr>
		   <tr>
		   <td colspan="7" align="right" ><img src="images/risk_matrix_axis_x.png" style="margin-right: 20px;"></td>

		   </tr>
	   </table>`;
$("#showMatrix").append(dataMitrix);

});
</script>
<script>
	$(document).ready(function() {
		$('.numeric').numeric({
			altDecimal: ".",
			negative: true,
			decimalPlaces: 2
		});
	});
	! function(e) {
		"function" == typeof define && define.amd ? define(["jquery"], e) : e(window.jQuery)
	}(function(e) {
		e.fn.numeric = function(t, n) {
			"boolean" == typeof t && (t = {
				decimal: t,
				negative: !0,
				decimalPlaces: -1
			}), void 0 === (t = t || {}).negative && (t.negative = !0);
			var i = !1 === t.decimal ? "" : t.decimal || ".",
				a = !1 === t.altDecimal ? "" : t.altDecimal || i,
				r = !0 === t.negative,
				c = void 0 === t.decimalPlaces ? -1 : t.decimalPlaces;
			return n = "function" == typeof n ? n : function() {}, this.data("numeric.decimal", i).data("numeric.altDecimal", a).data("numeric.negative", r).data("numeric.callback", n).data("numeric.decimalPlaces", c).keypress(e.fn.numeric.keypress).keyup(e.fn.numeric.keyup).blur(e.fn.numeric.blur)
		}, e.fn.numeric.keypress = function(t) {
			var n = e.data(this, "numeric.decimal"),
				i = e.data(this, "numeric.negative"),
				a = e.data(this, "numeric.decimalPlaces"),
				r = e.data(this, "numeric.altDecimal"),
				c = t.charCode ? t.charCode : t.keyCode ? t.keyCode : 0;
			if (13 == c && "input" == this.nodeName.toLowerCase()) return !0;
			if (13 == c) return !1;
			if (35 == c || 36 == c || 37 == c) return !1;
			var l = !1;
			if (t.ctrlKey && 97 == c || t.ctrlKey && 65 == c) return !0;
			if (t.ctrlKey && 120 == c || t.ctrlKey && 88 == c) return !0;
			if (t.ctrlKey && 99 == c || t.ctrlKey && 67 == c) return !0;
			if (t.ctrlKey && 122 == c || t.ctrlKey && 90 == c) return !0;
			if (t.ctrlKey && 118 == c || t.ctrlKey && 86 == c || t.shiftKey && 45 == c) return !0;
			if (c < 48 || c > 57) {
				var u = e(this).val();
				if (0 !== e.inArray("-", u.split("")) && i && 45 == c && (0 === u.length || 0 === parseInt(e.fn.getSelectionStart(this), 10))) return !0;
				n && c == n.charCodeAt(0) && -1 != e.inArray(n, u.split("")) && (l = !1), 8 != c && 9 != c && 13 != c && 35 != c && 36 != c && 37 != c && 39 != c && 46 != c ? l = !1 : void 0 !== t.charCode && (t.keyCode == t.which && 0 !== t.which ? (l = !0, 46 == t.which && (l = !1)) : 0 !== t.keyCode && 0 === t.charCode && 0 === t.which && (l = !0)), (n && c == n.charCodeAt(0) || r && c == r.charCodeAt(0)) && (l = -1 == e.inArray(n, u.split("")))
			} else if (l = !0, n && a > 0) {
				var s = e.fn.getSelectionStart(this),
					d = e.fn.getSelectionEnd(this),
					o = e.inArray(n, e(this).val().split(""));
				s === d && o >= 0 && s > o && e(this).val().length > o + a && (l = !1)
			}
			return l
		}, e.fn.numeric.keyup = function(t) {
			var n = e(this).val();
			if (n && n.length > 0) {
				var i = e.fn.getSelectionStart(this),
					a = e.fn.getSelectionEnd(this),
					r = e.data(this, "numeric.decimal"),
					c = e.data(this, "numeric.negative"),
					l = e.data(this, "numeric.decimalPlaces"),
					u = e.data(this, "numeric.altDecimal");
				if ("" !== r && null !== r) 0 === (g = e.inArray(r, n.split(""))) && (this.value = "0" + n, i++, a++), 1 == g && "-" == n.charAt(0) && (this.value = "-0" + n.substring(1), i++, a++), n = this.value;
				for (var s = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "-", r], d = n.length, o = d - 1; o >= 0; o--) {
					var f = n.charAt(o);
					0 !== o && "-" == f ? n = n.substring(0, o) + n.substring(o + 1) : 0 !== o || c || "-" != f || (n = n.substring(1));
					for (var m = !1, h = 0; h < s.length; h++)
						if (f == s[h]) {
							m = !0;
							break
						} m || f != u || (n = n.substring(0, o) + r + n.substring(o + 1), m = !0), m && " " != f || (n = n.substring(0, o) + n.substring(o + 1))
				}
				var g, v = e.inArray(r, n.split(""));
				if (v > 0)
					for (var y = d - 1; y > v; y--) {
						n.charAt(y) == r && (n = n.substring(0, y) + n.substring(y + 1))
					}
				if (r && l > 0)(g = e.inArray(r, n.split(""))) >= 0 && (n = n.substring(0, g + l + 1), a = Math.min(n.length, a));
				this.value = n, e.fn.setSelection(this, [i, a])
			}
		}, e.fn.numeric.blur = function() {
			var t = e.data(this, "numeric.decimal"),
				n = e.data(this, "numeric.callback"),
				i = e.data(this, "numeric.negative"),
				a = this.value;
			"" !== a && (new RegExp("^" + (i ? "-?" : "") + "\\d+$|^" + (i ? "-?" : "") + "\\d*" + t + "\\d+$").exec(a) || n.apply(this))
		}, e.fn.removeNumeric = function() {
			return this.data("numeric.decimal", null).data("numeric.altDecimal", null).data("numeric.negative", null).data("numeric.callback", null).data("numeric.decimalPlaces", null).unbind("keypress", e.fn.numeric.keypress).unbind("keyup", e.fn.numeric.keyup).unbind("blur", e.fn.numeric.blur)
		}, e.fn.getSelectionStart = function(e) {
			if ("number" !== e.type) {
				if (e.createTextRange && document.selection) {
					var t = document.selection.createRange().duplicate();
					return t.moveEnd("character", e.value.length), "" == t.text ? e.value.length : Math.max(0, e.value.lastIndexOf(t.text))
				}
				try {
					return e.selectionStart
				} catch (e) {
					return 0
				}
			}
		}, e.fn.getSelectionEnd = function(e) {
			if ("number" !== e.type) {
				if (e.createTextRange && document.selection) {
					var t = document.selection.createRange().duplicate();
					return t.moveStart("character", -e.value.length), t.text.length
				}
				return e.selectionEnd
			}
		}, e.fn.setSelection = function(e, t) {
			if ("number" == typeof t && (t = [t, t]), t && t.constructor == Array && 2 == t.length)
				if ("number" === e.type) e.focus();
				else if (e.createTextRange) {
				var n = e.createTextRange();
				n.collapse(!0), n.moveStart("character", t[0]), n.moveEnd("character", t[1] - t[0]), n.select()
			} else {
				e.focus();
				try {
					e.setSelectionRange && e.setSelectionRange(t[0], t[1])
				} catch (e) {}
			}
		}
	});

	$('#happen_date,#checked_date').on('change', function() {
		if ($('#happen_date').val() != '' && $('#checked_date').val() != '') {
			var d1 = $("#happen_date").val().split("-");
			var d2 = $("#checked_date").val().split("-");

			var dd1 = new Date(d1[0], d1[1] - 1, d1[2]);
			var dd2 = new Date(d2[0], d2[1] - 1, d2[2]);

			if (dd2 < dd1) {
				alert("กรุณาตรวจสอบวันที่เกิดเหตุการณ์และวันที่ตรวจพบ");
				$('#checked_date').val('');
			}
		}
	});

	$("textarea").keyup(function(e) {
		if ($(this).val() != '') {
			while ($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
				$(this).height($(this).height() + 1);
			};
		}
	}).keyup();

	$(document).ready(function() {
		$('.radio-group .radio').click(function() {
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

		$('#loss_data_doc_id_auto').val("");
		$('#loss_data_doc_id').val(docId);


	});


	function validateForm() {
		selected = document.querySelector('input[name="loss_type"]:checked').value;

		if ($('#happen_date').val() == '') {
			alert("กรุณาระบุวันที่เกิดเหตุการณ์");
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

	$('#effect').on('change', function() {
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
	function showEventForm() {
		$('#myModalSendCase').modal('show');
	}
</script>


<script>
	 function CheckNumeric() {
        return event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode == 46;
    }
function formatNumber(e){
  var rex = /(^\d{2})|(\d{1,3})(?=\d{1,3}|$)/g,
      val = this.value.replace(/^0+|\.|,/g,""),
      res;
      
  if (val.length) {
    res = Array.prototype.reduce.call(val, (p,c) => c + p)            // reverse the pure numbers string
               .match(rex)                                            // get groups in array
               .reduce((p,c,i) => i - 1 ? p + "," + c : p + "." + c); // insert (.) and (,) accordingly
    res += /\.|,/.test(res) ? "" : ".0";                              // test if res has (.) or (,) in it
    this.value = Array.prototype.reduce.call(res, (p,c) => c + p);    // reverse the string and display
  }
}

var ni = document.getElementById("loss_value");

ni.addEventListener("keyup", formatNumber);
</script>