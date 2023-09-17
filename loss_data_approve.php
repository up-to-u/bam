<?
include('inc/include.inc.php');
echo template_header();
$statusListId = $_GET['statusListId'];
$today =date("Y-m-d");

$view_year = intval($_GET['view_year']);
if ($view_year == 0) {
	$view_year = date('Y') + 543;
}

$action = $_GET['action'];
$sql = "SELECT user.*,user.department_name,department.department_level_id, user.group_name,user.division_name,user.auth_loss 
FROM user 
join department on  department.department_id = user.department_id 
WHERE user.user_id=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res1 = $stmt->get_result();
if ($row_mem = $res1->fetch_assoc()) {
	 $department_id = ($row_mem['department_id']);
	$department_level_id = $row_mem['department_level_id'];
	$department_name = $row_mem['department_name'];
	$groupName = $row_mem['group_name'];
	$division_name = $row_mem['division_name'];
/*if ($department_level_id=='5'){
	 $department_id = getParentID($row_mem['department_id']);
} elseif ($department_level_id=='4'){
	 $department_id = ($row_mem['department_id']);
	
}*/
	$auth_loss = $row_mem['auth_loss'];
}

if($auth_loss=='2'){

if ($_POST['submitLossUpdate'] == 'submitLossUpdate') {
	$loss_data_doc_list_id = $_POST['date_id'];
	$comment_app = $_POST['comment_app'];
	$qx = true;

	$stmt = $connect->prepare("UPDATE loss_data_doc_list SET
		comment_app=?,approved_date=now()
		WHERE loss_data_doc_list_id=? ");

	if ($stmt) {
		$stmt->bind_param(
			'si',
			$comment_app,
			$loss_data_doc_list_id
		);
		$q = $stmt->execute();
		$qx = ($qx and $q);

		if ($qx) {
			$connect->commit();
			savelog('LOSS-APPROVER-UPDATE-LossReportList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|');
						
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

if ($_POST['submitLossUpdateEdit'] == 'submitLossUpdateEdit') {
	$loss_data_doc_idEdit = $_POST['date_idEdit'];
	$comment_appEdit = $_POST['comment_appEdit'];
	$status_approveEdit = "2";
	$qx = true;
	$stmt = $connect->prepare("UPDATE loss_data_doc_list SET
		comment_app=?,approved_date=now(), status_approve=?
		WHERE loss_data_doc_list_id=?  ");

	if ($stmt) {
		$stmt->bind_param(
			'sii',
			$comment_appEdit,
			$status_approveEdit,
			$loss_data_doc_idEdit

		);
		$q = $stmt->execute();
		$qx = ($qx and $q);

		if ($qx) {
			$connect->commit();

			
		savelog('LOSS-APPROVER-UPDATE-LossReportList|loss_data_doc_list_id|'.$loss_data_doc_idEdit.'|');	
	
		$mailuser = getEmail(getUserdoc($loss_data_doc_idEdit));
					
						if($mailuser!=''){
						$email_from = 'noreply-lossdata@bam.co.th';
						$cc = array();		
						$bcc = array();		
						$to = array($mailuser);

						$subject = 'ท่านมีรายงานเหตุการณ์ความเสียหายที่ต้องแก้ไข [LOSS DATA]';
						$body = 'เรียน ผู้รายงานเหตุการณ์ความเสียหาย '.$department_name.'<br><br>
						เนื่องจากวันที่ '.mysqldate2th_date($today).'ผู้อนุมัติฝ่ายของท่านมีความเห็นเกี่ยวกับการรายงานเหตุการณ์ความเสียหายที่ท่านทำการรายงาน
						กรุณาเข้าสู่ระบบเพื่อดำเนินการตรวจสอบและแก้ไขรายการ
						<br>จึงแจ้งมายังท่านเพื่อมราบ โปรดดำเนินต่อไป<br><br><a href="'.$url_prefix.'/loss_data.php" target="_new"> คลิ๊กเพื่อเข้าสู่ระบบ</a> ';
						$x = @mail_service($email_from,$to,$cc,$bcc,$subject,$body,$attach_name,$attach_location);		
						if ($x) {
							echo "<font color='#00aa00'><b>ระบบได้ส่ง E-mail เรียบร้อยแล้ว</b></font><br>";
							} else {
								echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารส่ง E-mail ได้</b></font><br>";
								}		
								} else {
									echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ไม่พบ Email ผู้รายงานกรุณาติดต่อเจ้าหน้าที่ฝ่ายบริหารความเสี่ยง</b></font><br>";
								}
		echo "<script>alert('ระบบได้ส่งกลับข้อมูลเพื่อแก้ไขเรียบร้อย');
				window.location.href='loss_data_approve.php?statusListId=1';
				</script>";
								
		} else {
			$connect->rollback();
		}
		//	$stmt->close();
		//	$conn->close();

	}
}
if ($_POST['submitapprovereport'] == 'submitapprovereport') {
	$lossid = $_POST['lossid'];
	echo $approve_name = get_user_name($user_id);
	$qx = true;

	$stmt = $connect->prepare("UPDATE loss_data_doc SET
		status_lossapprove=1,
		approve_id=$user_id,
		approve_name= '$approve_name',
		approveddate=now()
		WHERE loss_data_doc_id=? ");

	if ($stmt) {
		$stmt->bind_param(
			'i',
			$lossid
		);
		$q = $stmt->execute();
		$qx = ($qx and $q);

		if ($qx) {
			$connect->commit();

			echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');
				window.location.href='loss_data_approve.php?statusListId=1';
				</script>";
				
			savelog('LOSS-APPROVER-UPDATE-LossReport|loss_data_doc_id|'.$lossid.'|');	
				
								
		} else {
			$connect->rollback();
		}
		//	$stmt->close();
		//	$conn->close();

	}
}

if ($_GET['confirm'] == 'approve') {
	$buttonapprove = $_POST['buttonapprove'];
	$loss_data_doc_list_id = $_POST['loss_data_doc_list_id'];
	$connect->autocommit(FALSE);
	$qx = true;
	$stmt = $connect->prepare("UPDATE `loss_data_doc_list` SET `status_approve` = ?  ,approved_date=now() WHERE `loss_data_doc_list_id` = ? ");
	if ($stmt) {
		$stmt->bind_param('ii', $buttonapprove, $loss_data_doc_list_id);
		$q = $stmt->execute();
		$qx = ($qx and $q);

		if ($qx) {
			
			$connect->commit();
			echo $listId = $listId;
			echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');
				window.location.href='loss_data_approve.php?statusListId=1';
				</script>";
			savelog('LOSS-APPROVER-Approve-LossReportList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|');	
			
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

	.font-green {
		color: #261ecd !important;
	}

	.btn-primary {
		background-color: #42A5F5 !important;
		border-color: #42A5F5 !important;
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

<? if ($action == '') { ?>

	<div class="row">
		<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V2 - 13/09/66</label>
		<div class="col-lg-12 col-lg-12 col-sm-12">
			<div class="portlet light tasks-widget bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-share font-dark hide"></i>
						<span class="caption-subject font-green sbold uppercase">การอนุมัติเหตุการณ์ประจำเดือน </span>
						<span class="caption-helper"></span>
					</div>
				</div><br>

				<div class="col-lg-12 col-lg-12 col-sm-12">
					<table>
						<tr>
							<td>แสดงข้อมูล ของปี</td>
							<td width='15'></td>
							<td>
								<select name='view_year' class="form-control" onChange='document.location="loss_data_approve.php?view_year="+this.value'>
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
				<div align="center">

					<table id="exampleDataTable" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th style='width:15%'>ช่วงเหตุการณ์</th>
								<th style='width:20%'>ผลการรายงาน</th>
								<th style='width:10%'>จำนวนรายการ</th>
								<th style='width:10%'>ผลอนุมัติ</th>
								<th style='width:30%'>จัดการ</th>
							</tr>
						</thead>
						<tbody>

							<?php
							$sql = "SELECT * FROM loss_data_doc where loss_dep= ? and loss_year=?  order by loss_data_doc_month ASC";
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
										<td style="vertical-align: middle;">
											<span class="glyphicon glyphicon-exclamation-sign" style="color:#f56a75; margin-left:5px;"></span><span style="color:#f56a75;"><b> พบเหตุการณ์ความเสียหาย</b></span>
										<? } else if ($row['loss_have'] == '0') {  ?>
										<td style="vertical-align: middle;">
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
				<form action="loss_data_approve.php?action=show" method="post" style="display: inline;">
					<!-- <input type="hidden" value="<?= $row['loss_data_doc_id']; ?>" name="listId" id="listId">
															<input type="hidden" value="<?= $row['loss_data_doc_month']; ?>" name="m" id="m">
															<input type="hidden" value="<?= $row['loss_year']; ?>" name="y" id="y"> -->
					<!-- <button type="submit"  class="btn btn-primary btn-block" style='text-align: left;'><span class="badge"><?= $c0; ?></span> รออนุมัติ </button>
												<button type="submit" class="btn btn-danger btn-block" style='text-align: left;'><span class="badge"><?= $c2; ?></span> แก้ไข </button>    
												<button type="submit"  class="btn btn-success btn-block" style='background-color: #26c281; border-color: #26c281; text-align: left;'><span class="badge"><?= $c1; ?></span> อนุมัติแล้ว </button>   -->
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
				<form action="loss_data_approve.php" method="post" style="display: inline;">
					<input type="hidden" value="<?= $row['loss_data_doc_id']; ?>" name="lossid">
					<? if ($row['status_lossapprove'] == '0') { ?><button type='submit' name='submitapprovereport' value='submitapprovereport' class="btn btn-success">
							<i class="glyphicon glyphicon-list-alt"></i> อนุมัติรายงานประจำเดือน</button> <? } ?>
					<? if ($row['status_lossapprove'] == '1') {
									echo "<font color='green'> อนุมัติแล้ว<br>วันที่ " . mysqldate2th_date($row['approveddate']);
								} ?>
				</form>
			</td>
			<td style="vertical-align: middle;" width="300">

				<?php if (checkLossList($row['loss_data_doc_id']) > 0) { ?>
					<form action="loss_data_approve.php?action=show" method="post" style="display: inline;">
						<input type="hidden" value="<?= $row['loss_data_doc_id']; ?>" name="listId" id="listId">
						<input type="hidden" value="<?= $row['loss_data_doc_month']; ?>" name="m" id="m">
						<input type="hidden" value="<?= $row['loss_year']; ?>" name="y" id="y">
						<input type="hidden" value="show" name="action">
						<button type='submit' class="btn btn-primary"><i class="glyphicon glyphicon-list-alt"></i> ดูเพิ่มเติม</button>
					</form>
				<?php } ?>

			</td>

			</tr>

		<?php } ?>

		</tbody>

		</table>

			</div>
		</div>

	<? }
$listId = $_POST['listId'];
if ($action == 'show' && $listId != '') {

	$m = $_POST['m'];
	$y = $_POST['y']; ?>
		<div class="row">

			<div align="center"><br>
				<a href="loss_data_approve.php">
					<span class="glyphicon glyphicon-menu-left"></span><span class="glyphicon glyphicon-menu-left"></span> ย้อนกลับ
				</a>
			</div>

			<div class="col-lg-12 col-lg-12 col-sm-12">
				<div class="portlet light tasks-widget bordered">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-share font-dark hide"></i>
							<span class="caption-subject font-green sbold uppercase">ตรวจสอบรายการเหตุการณ์ความเสียหาย</span>
							<span class="caption-helper"></span>
						</div>
					</div>
					<!---------------->
					<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
						<div class="col-lg-3">
						</div>
						<div class="col-lg-3">

							<b>สายงาน :</b> <?= $groupName; ?>
						</div>
						<div class="col-lg-3">
							<b>ฝ่าย :</b> <?= str_replace('ฝ่าย','',$department_name); ?>
						</div>
						<div class="col-lg-3">
							<b>กลุ่มงาน :</b> <?= $division_name; ?>
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
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_data_doc_id = ?";
											$stmt = $connect->prepare($sqlCount1);
											$stmt->bind_param("iii", $i1, $department_id, $listId);
											$stmt->execute();
											$res = $stmt->get_result();
											if ($rows = $res->fetch_assoc()) {
												echo $rows['num'];
											}
											?>
											คำขอ</span> </div>
									<div class="desc" style="margin-top: 13px;">
										<form action="loss_data_approve.php?statusListId=0&action=show" method="post" style="display: inline;">
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
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_data_doc_id = ?";
											$stmt = $connect->prepare($sqlCount2);
											$stmt->bind_param("iii", $i2, $department_id, $listId);
											$stmt->execute();
											$res = $stmt->get_result();
											if ($rows = $res->fetch_assoc()) {
												echo $rows['num'];
											}
											?>
											คำขอ</span> </div>
									<div class="desc" style="margin-top: 13px;">
										<div class="desc" style="margin-top: 13px;">
											<form action="loss_data_approve.php?statusListId=2&action=show" method="post" style="display: inline;">
												<input type="hidden" value="<?= $listId; ?>" name="listId" id="listId">
												<input type="hidden" value="<?= $m; ?>" name="m" id="m">
												<input type="hidden" value="<?= $y; ?>" name="y" id="y">

												<button type='submit' class="btn btn-danger"> Click ดูข้อมูลเพิ่มเติม</button>
											</form>
										</div>
									</div>
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
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_data_doc_id = ?";
											$stmt = $connect->prepare($sqlCount3);
											$stmt->bind_param("iii", $i3, $department_id, $listId);
											$stmt->execute();
											$res = $stmt->get_result();
											if ($rows = $res->fetch_assoc()) {
												echo $rows['num'];
											}
											?>
											คำขอ</span> </div>
									<div class="desc" style="margin-top: 13px;">
										<form action="loss_data_approve.php?statusListId=1&action=show" method="post" style="display: inline;">
											<input type="hidden" value="<?= $listId; ?>" name="listId" id="listId">
											<input type="hidden" value="<?= $m; ?>" name="m" id="m">
											<input type="hidden" value="<?= $y; ?>" name="y" id="y">
											<button type='submit' class="btn btn-green" style='background-color: #51dca2; border-color: #51dca2;'> Click ดูข้อมูลเพิ่มเติม</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>




		<?


		$statusListId = $_GET['statusListId'];

		if ($statusListId != NULL || $checkStatuss = "Y") { ?>



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
							if ($_POST['listId'] != NULL) {
								echo "<span style='font-size: 20px;margin:10px;'>ข้อมูลเหตุการณ์ประจำเดือน " . month_name($_POST['m']) . " ปี " . $_POST['y'] . "</span><br>";
							} elseif ($statusListId != NULL) {
								if ($statusListId == 0) {
									echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลรออนุมัติทั้งหมด</span><br>";
								} elseif ($statusListId == 2) {
									echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลส่งกลับแก้ไขทั้งหมด</span><br>";
								} elseif ($statusListId == 1) {
									echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลที่อนุมัติแล้วทั้งหมด</span><br>";
								}
							}								?>
							<!-- <form action = 'export.php' method="post">
				<button type="submit" class="pull-right btn btn-dark"style="margin: 10px;"> <span class="glyphicon glyphicon-download-alt"></span> Export Data</button>
				</form> -->

						</div>
						<br>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-12 col-xs-12">
									<div class=''>
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

														<th style='width:20%'>จัดการ</th>
													</tr>
												</thead>

												<tbody>
													<?php
													$m = $_POST['m'];
													$y = $_POST['y'];
													$listId = $_POST['listId'];
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
													if ($statusListId == "0" || $statusListId == "1" || $statusListId == "2") {

														$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.loss_data_doc_id = $listId and loss_data_doc.loss_dep = $department_id and loss_data_doc_list.status_approve = $statusListId";
													} elseif ($listId != NULL) {

														$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.loss_data_doc_id = $listId and loss_data_doc.loss_dep =$department_id  and loss_data_doc_list.status_approve = $statusListId";
													} elseif ($statusListId != NULL) {

														$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where loss_data_doc_list.status_approve = $statusListId and loss_data_doc.loss_dep =$department_id  and loss_data_doc_list.status_approve = $statusListId";
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
															<td style="vertical-align: middle;"><?= get_user_name($row['doclist_user_id']); ?><br> <?= get_user_division_name($row['doclist_user_id']); ?>
															</td>
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
															<td width="250">

																<button name='submit' class="btn btn-success showDetailData" date-id="<?= $row['loss_data_doc_list_id']; ?>" data-happen_date="<?= $row['happen_date']; ?>" data-checked_date="<?= $row['checked_date']; ?>" data-incidence="<?= $row['incidence']; ?>" data-incidence_detail="<?= $row['incidence_detail']; ?>" data-cause="<?= $row['cause']; ?>" data-user_effect="<?= $row['user_effect']; ?>" data-damage_type="<?= $row['damage_type']; ?>" data-incidence_type="<?= $row['incidence_type']; ?>" data-loss_type="<?= $row['loss_type']; ?>" data-control="<?= $row['control']; ?>" data-loss_value="<?= $row['loss_value']; ?>" data-chance="<?= $row['chance']; ?>" data-effect="<?= $row['effect']; ?>" data-damageLevel="<?= $row['damageLevel']; ?>" data-related_dep_id="<?= $row['related_dep_id']; ?>" data-dep_id_1="<?= $row['dep_id_1']; ?>" data-dep_id_2="<?= $row['dep_id_2']; ?>" data-dep_id_3="<?= $row['dep_id_3']; ?>" data-comment_app="<?= $row['comment_app']; ?>" data-approved_date="<?= $row['approved_date']; ?>" data-status_approve="<?= $row['status_approve']; ?>" data-comment_risk="<?= $row['comment_risk']; ?>" data-status_risk_approve="<?= $row['status_risk_approve']; ?>" data-riskcomment_date="<?= $row['riskcomment_date']; ?>" data-attech_name="<?= $row['attech_name']; ?>" data-attech_name2="<?= $row['attech_name2']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-list-alt'></i> รายละเอียด</button>
																<? if ($status_approve == '0') { ?>
																	<form action="loss_data_approve.php?confirm=approve&action=show" method="post" target="_blank" style="display: inline;">
																		<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
																		<button type='submit' name='buttonapprove' value='1' class="btn btn-info"> อนุมัติ</button>
																	</form>
																	<form action="loss_data_approve.php?confirm=approve&action=show" method="post" target="_blank" style="display: inline;">
																		<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
																		<a type='submit' class="btn btn-danger showDetailDataEdit" name='buttonapprove' value='2' date-idEdit="<?= $row['loss_data_doc_list_id']; ?>" data-happen_dateEdit="<?= $row['happen_date']; ?>" data-checked_dateEdit="<?= $row['checked_date']; ?>" data-incidenceEdit="<?= $row['incidence']; ?>" data-incidence_detailEdit="<?= $row['incidence_detail']; ?>" data-causeEdit="<?= $row['cause']; ?>" data-user_effectEdit="<?= $row['user_effect']; ?>" data-damage_typeEdit="<?= $row['damage_type']; ?>" data-incidence_typeEdit="<?= $row['incidence_type']; ?>" data-loss_typeEdit="<?= $row['loss_type']; ?>" data-controlEdit="<?= $row['control']; ?>" data-loss_valueEdit="<?= $row['loss_value']; ?>" data-chanceEdit="<?= $row['chance']; ?>" data-effectEdit="<?= $row['effect']; ?>" data-damageLevelEdit="<?= $row['damageLevel']; ?>" data-related_dep_idEdit="<?= $row['related_dep_id']; ?>" data-dep_id_1Edit="<?= $row['dep_id_1']; ?>" data-dep_id_2Edit="<?= $row['dep_id_2']; ?>" data-dep_id_3Edit="<?= $row['dep_id_3']; ?>" data-comment_appEdit="<?= $row['comment_app']; ?>" data-approved_dateEdit="<?= $row['approved_date']; ?>" data-status_approveEdit="<?= $row['status_approve']; ?>" data-comment_riskEdit="<?= $row['comment_risk']; ?>" data-status_risk_approveEdit="<?= $row['status_risk_approve']; ?>" data-riskcomment_dateEdit="<?= $row['riskcomment_date']; ?>" data-attech_nameEdit="<?= $row['attech_name']; ?>" data-attech_nameEdit2="<?= $row['attech_name2']; ?>" data-toggle="modal" data-target="#myModalSendCaseEdit"> ส่งแก้ไข</a>
																	</form>
																<? } ?>
																<? if ($status_approve == '1') { ?>
																	<form action="pdf.php" method="post" target="_blank" style="display: inline;">
																		<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
																		<button type='submit' class="btn btn-dark " style="background-color: #949596; color:#FFFFFF;"> <span class="glyphicon glyphicon-print"></span> พิมพ์</button>
																	</form>
																<? } ?>
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

			<? } ?><? } ?>
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
													<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่เกิดเหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='happen_date' disabled readonly id='happen_date' style="cursor: default;"></div>
													<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" disabled name='checked_date' readonly id='checked_date' style="cursor: default;"></div>
													<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">เหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence" class="form-control" name="incidence" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
													<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detail" class="form-control" name="incidence_detail" rows="3" cols="50" style="min-height:80px; background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
													<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">สาเหตุ<span style="color: red;">*</span></label><textarea id="cause" class="form-control" name="cause" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
													<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label><textarea id="user_effect" class="form-control" name="user_effect" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
													<div class="col-lg-12"> <label class="margins-top-10 label-main">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_type' id='damage_type' class="form-control" style="background-color: #EEF1F5; cursor: default;" readonly disabled>
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
													<div class="col-lg-12"><label class="margins-top-10 label-main">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='incidence_type' id='incidence_type' class="form-control" style="background-color: #EEF1F5; cursor: default;" readonly disabled>
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

													<!-- <div class="col-lg-3"> <label class="margins-top-10 col-xs-12 label-main" style="margin-left: -13px;">Loss : <span style="color: red;">*</span></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type1" value="1" disabled> Actual Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type2" value="2" disabled> Potential Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type3" value="3" disabled> Near-Missed
											</label></div> -->
													<div class="col-lg-12"><label class="margins-top-10  label-main">ความเสียหาย<span style="color: red;">*</span> </label>
														<div class="col-lg-12"><label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type1" value="1" disabled>&nbsp; <b>Actual Loss : </b> ความเสียหายที่เกิดขึ้นจริงทั้งที่เป็นตัวเงิน และไม่เป็นตัวเงิน
															</label></div>
														<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type2" value="2" disabled>&nbsp; <b> Potential Loss : </b>ความเสียหายที่อาจเกิดขึ้น

															</label></div>
														<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type3" value="3" disabled>&nbsp; <b> Near-Missed : </b>ความเสียหายที่เกิดขึ้น หรืออาจเกิดขึ้น แต่สามารถป้องกันความเสียหายไว้ได้
															</label></div>
													</div>

													<div class="col-lg-12"><label class="margins-top-10 label-main">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><textarea class="form-control" name='control' id='control' rows="2" cols="50" style="min-height:80px;cursor: default;" disabled></textarea></div>
													<div class="col-lg-3"><label class="margins-top-10 label-main">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" style="cursor: default;" name='loss_value' id='loss_value' oninput="validate(this)" onkeyup="javascript:this.value=Comma(this.value);" onkeypress="return CheckNumeric()"  disabled></div>
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
													<div class="col-lg-6" align="center"><label class="margins-top-10"><br>
													<div id="link1" ></div>

													</div>
													<div class="col-lg-6" align="center"><label class="margins-top-10"><br>
													<div id="link2" ></div>

													</div>

													<div class="col-lg-12"><label class="margins-top-10 label-main ">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="comment_app" class="form-control" name="comment_app" rows="3" cols="50" style="min-height:80px;border-color:#27A4B0;"></textarea></div>
													<? if ($status_risk_approve == '3') { ?>
														<div class="col-lg-3 col-xs-12"><label class="margins-top-10 label-main">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='end_date' readonly id='end_date'></div>
													<? } ?>
													<div class="col-lg-12"><label class="margins-top-10 label-main">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="comment_risk" class="form-control" name="comment_risk" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>


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
	</div>
	<!---------------->



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

	<!-- start modal -->

	<div id="myModalSendCaseEdit" class="modal fade" role="dialog">
		<div class="modal-dialog  modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background-color:#27A4B0;color:#FFFFFF;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-list-alt"></span> ส่งกลับแก้ไข</h4>
				</div>
				<div class="modal-body" align="left">

					<form method='post' action='loss_data_approve.php' enctype="multipart/form-data">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-12 col-xs-12">

									<div class="form-group">
										<div class="row">
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่เกิดเหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='happen_dateEdit' disabled readonly id='happen_dateEdit' style="cursor: default;"></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" disabled name='checked_dateEdit' readonly id='checked_dateEdit' style="cursor: default;"></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">เหตุการณ์<span style="color: red;">*</span></label><textarea id="incidenceEdit" class="form-control" name="incidenceEdit" rows="3" cols="50" style="min-height:80px; background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detailEdit" class="form-control" name="incidence_detailEdit" rows="3" cols="50" style="min-height:80px; background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">สาเหตุ<span style="color: red;">*</span></label><textarea id="causeEdit" class="form-control" name="causeEdit" rows="3" cols="50" style="min-height:80px;background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
											<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label><textarea id="user_effectEdit" class="form-control" name="user_effectEdit" rows="3" cols="50" style="min-height:80px; background-color: #EEF1F5;cursor: default;" readonly></textarea></div>
											<div class="col-lg-12"> <label class="margins-top-10 label-main">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_typeEdit' id='damage_typeEdit' class="form-control" style="background-color: #EEF1F5; cursor: default;" readonly disabled>
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
											<div class="col-lg-12"><label class="margins-top-10 label-main">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='incidence_typeEdit' id='incidence_typeEdit' class="form-control" style="background-color: #EEF1F5; cursor: default;" readonly disabled>
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

											<!-- <div class="col-lg-3"> <label class="margins-top-10 col-xs-12 label-main" style="margin-left: -13px;">Loss : <span style="color: red;">*</span></label><label class="radio-inline"><input type="radio" name="loss_typeEdit" id="loss_type1Edit" value="1" disabled> Actual Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_typeEdit" id="loss_type2Edit" value="2" disabled> Potential Loss
											</label></div>
										<div class="col-lg-3"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_typeEdit" id="loss_type3Edit" value="3" disabled> Near-Missed
											</label></div> -->
											<div class="col-lg-12"><label class="margins-top-10  label-main">ความเสียหาย<span style="color: red;">*</span> </label>
												<div class="col-lg-12"><label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_typeEdit" id="loss_type1Edit" value="1" disabled>&nbsp; <b>Actual Loss : </b> ความเสียหายที่เกิดขึ้นจริงทั้งที่เป็นตัวเงิน และไม่เป็นตัวเงิน
													</label></div>
												<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_typeEdit" id="loss_type2Edit" value="2" disabled>&nbsp; <b> Potential Loss : </b>ความเสียหายที่อาจเกิดขึ้น

													</label></div>
												<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_typeEdit" id="loss_type3Edit" value="3" disabled>&nbsp; <b> Near-Missed : </b>ความเสียหายที่เกิดขึ้น หรืออาจเกิดขึ้น แต่สามารถป้องกันความเสียหายไว้ได้
													</label></div>
											</div>
											<div class="col-lg-12"><label class="margins-top-10 label-main">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><textarea class="form-control" name='controlEdit' id='controlEdit' rows="2" cols="50" style="min-height:80px;cursor: default;" disabled></textarea></div>
											<div class="col-lg-3"><label class="margins-top-10 label-main">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" style="cursor: default;" name='loss_valueEdit' id='loss_valueEdit' oninput="validate(this)" onkeyup="javascript:this.value=Comma(this.value);" onkeypress="return CheckNumeric()"  disabled></div>
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
											<div class="col-lg-6" align="center"><label class="margins-top-10"><br>
											<div id="link1Edit"></div>	

											</div>
											<div class="col-lg-6" align="center"><label class="margins-top-10"><br>
											<div id="link2Edit"></div>	

											</div>
											<div class="col-lg-12"><label class="margins-top-10 label-main ">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="comment_appEdit" class="form-control" name="comment_appEdit" rows="3" cols="50" style="min-height:80px;border-color:#27A4B0;"></textarea></div>
											<? if ($status_risk_approve == '3') { ?>
												<div class="col-lg-3 col-xs-12"><label class="margins-top-10 label-main">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='end_dateEdit' readonly id='end_dateEdit'></div>
											<? } ?>
											<div class="col-lg-12"><label class="margins-top-10 label-main">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="comment_riskEdit" class="form-control" name="comment_riskEdit" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>


										</div>

										<div align="center" style="margin-top: 30px;">
											<input type="hidden" id="date_idEdit" name="date_idEdit">
											<button type='submit' name='submitLossUpdateEdit' value="submitLossUpdateEdit" class="btn btn-danger"><i class='fa fa-save'></i> ส่งกลับแก้ไข</button>
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
	</div>
	<!---------------->

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
			var attech_names2 = $(this).attr('data-attech_name2');

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
			$('#attech_name').val(attech_names);

			cal_level("2");
		           var impact_id=effects;
					var likelihood_id=chances;
					$.post( "loss_data_matrix.php", { action: 'loss_data_matrix', data1: impact_id ,data2: likelihood_id })
.done(function( data ) {
$("#modalPopupDetail").html(data);
});

			if(attech_names == "" || attech_names == null){
	
	document.getElementById("link1").innerHTML = "";
	$("#link1").append('<a href="#" id="attech_name" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

}else{

document.getElementById("link1").innerHTML = "";
	$("#link1").append('<a href="/attech_file/'+attech_names+'" target="_blank"  download id="attech_name" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

}

if(attech_names2 == "" || attech_names2 == null){
	document.getElementById("link2").innerHTML = "";
	$("#link2").append('<a href="#" id="attech_names2" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

	
}else{
	document.getElementById("link2").innerHTML = "";
	$("#link2").append('<a href="/attech_file/'+attech_names2+'" target="_blank"  download id="attech_names2" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

}
		});

		$(".showDetailDataEdit").on("click", function() {
			var dateIdEdit = $(this).attr('date-idEdit');
			var happen_datesEdit = $(this).attr('data-happen_dateEdit');
			var checked_datesEdit = $(this).attr('data-checked_dateEdit');
			var incidencesEdit = $(this).attr('data-incidenceEdit');
			var incidence_detailsEdit = $(this).attr('data-incidence_detailEdit');
			var causesEdit = $(this).attr('data-causeEdit');
			var user_effectsEdit = $(this).attr('data-user_effectEdit');
			var damage_typesEdit = $(this).attr('data-damage_typeEdit');
			var incidence_typesEdit = $(this).attr('data-incidence_typeEdit');
			var loss_typesEdit = $(this).attr('data-loss_typeEdit');
			var controlsEdit = $(this).attr('data-controlEdit');
			var loss_valuesEdit = $(this).attr('data-loss_valueEdit');
			var chancesEdit = $(this).attr('data-chanceEdit');
			var effectsEdit = $(this).attr('data-effectEdit');
			var damageLevelsEdit = $(this).attr('data-damageLevelEdit');
			var related_dep_idsEdit = $(this).attr('data-related_dep_idEdit');
			var dep_id_1sEdit = $(this).attr('data-dep_id_1Edit');
			var dep_id_2sEdit = $(this).attr('data-dep_id_2Edit');
			var dep_id_3sEdit = $(this).attr('data-dep_id_3Edit');
			var comment_appsEdit = $(this).attr('data-comment_appEdit');
			var approved_datesEdit = $(this).attr('data-approved_dateEdit');
			var status_approvesEdit = $(this).attr('data-status_approveEdit');
			var comment_risksEdit = $(this).attr('data-comment_riskEdit');
			var status_risk_approvesEdit = $(this).attr('data-status_risk_approveEdit');
			var riskcomment_datesEdit = $(this).attr('data-riskcomment_dateEdit');
			var attech_namesEdit = $(this).attr('data-attech_nameEdit');
			var attech_nameEdit2 = $(this).attr('data-attech_nameEdit2');

			$('#date_idEdit').val(dateIdEdit);
			$('#happen_dateEdit').val(happen_datesEdit);
			$('#checked_dateEdit').val(checked_datesEdit);
			$('#incidenceEdit').val(incidencesEdit);
			$('#incidence_detailEdit').val(incidence_detailsEdit);
			$('#causeEdit').val(causesEdit);
			$('#user_effectEdit').val(user_effectsEdit);
			$('#damage_typeEdit').val(damage_typesEdit);
			$('#incidence_typeEdit').val(incidence_typesEdit);
			$('#loss_typeEdit').val(loss_typesEdit);

			if (loss_typesEdit.trim() == 1) {
				$("#loss_type1Edit").prop("checked", true);
			} else if ((loss_typesEdit.trim() == 2)) {
				$("#loss_type2Edit").prop("checked", true);
			} else if ((loss_typesEdit.trim() == 3)) {
				$("#loss_type3Edit").prop("checked", true);
			}


			$('#controlEdit').val(controlsEdit);
			var partsEdit = parseFloat(loss_valuesEdit).toFixed(2).toString().split(".");
           var loss_valuesEdit = partsEdit[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (partsEdit[1] ? "." + partsEdit[1] : "");
			$('#loss_valueEdit').val(loss_valuesEdit);
	
			$('#csa_likelihood_id1').val(chancesEdit);
		$('#csa_impact_id1').val(effectsEdit);
			$('#damageLevelEdit').val(damageLevelsEdit);
			$('#related_dep_idEdit').val(related_dep_idsEdit);
			$('#dep_id_1Edit').val(dep_id_1sEdit);
			$('#dep_id_2Edit').val(dep_id_2sEdit);
			$('#dep_id_3Edit').val(dep_id_3sEdit);
			$('#comment_appEdit').val(comment_appsEdit);
			$('#approved_dateEdit').val(approved_datesEdit);
			$('#status_approveEdit').val(status_approvesEdit);
			$('#comment_riskEdit').val(comment_risksEdit);
			$('#status_risk_approveEdit').val(status_risk_approvesEdit);
			$('#riskcomment_dateEdit').val(riskcomment_datesEdit);
			$('#attech_nameEdit').val(attech_namesEdit);
			$('#attech_nameEdit2').val(attech_nameEdit2);

			cal_level("1");
		           var impact_id=effectsEdit;
					var likelihood_id=chancesEdit;
					$.post( "loss_data_matrix.php", { action: 'loss_data_matrix', data1: impact_id ,data2: likelihood_id })
.done(function( data ) {
$("#modalPopupEdit").html(data);
});

			if(attech_namesEdit == "" || attech_namesEdit == null){
	
	document.getElementById("link1Edit").innerHTML = "";
	$("#link1Edit").append('<a href="#" id="attech_namesEdit" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

}else{

document.getElementById("link1Edit").innerHTML = "";
	$("#link1Edit").append('<a href="/attech_file/'+attech_namesEdit+'" target="_blank"  download id="attech_namesEdit" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

}

if(attech_nameEdit2 == "" || attech_nameEdit2 == null){
	document.getElementById("link2Edit").innerHTML = "";
	$("#link2Edit").append('<a href="#" id="attech_nameEdit2" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

	
}else{
	document.getElementById("link2Edit").innerHTML = "";
	$("#link2Edit").append('<a href="/attech_file/'+attech_nameEdit2+'" target="_blank"  download id="attech_nameEdit2" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

}

		});

		
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

<?} else {?>
<font color='red'><b> ขออภัยค่ะ ท่านไม่มีสิทธิ์ในการอนุมัติเหตุการณ์ความเสียหาย </b></font>

<?}?>

<?function getEmail($u)
{
	include('inc/connect.php');
	$sql = "SELECT email FROM user WHERE user_id='" . $u . "'";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return $row['email'];
}

function getUserdoc($u)
{
	include('inc/connect.php');
	$sql = "SELECT doclist_user_id FROM loss_data_doc_list WHERE loss_data_doc_list_id='" . $u . "'";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return $row['doclist_user_id'];
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