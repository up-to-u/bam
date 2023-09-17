<?
include('inc/include.inc.php');
echo template_header();
$action = $_GET['action'];
$today =date("Y-m-d");
$view_year = intval($_GET['view_year']);
	if ($view_year==0) {
		$view_year=date('Y')+543;
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
if ($_POST['submitLossRiskUpdate'] == 'submitLossRiskUpdate') {
	$loss_data_doc_list = $_POST['date_id'];
	$comment_app = $_POST['comment_app'];
	$comment_risk = $_POST['comment_risk'];
	$end_date = $_POST['end_date'];
	// echo $end_date;
	// die();
		$qx = true;

		$stmt = $connect->prepare("UPDATE loss_data_doc_list SET
		comment_risk=?, riskcomment_date=Now(),end_date=?
		WHERE loss_data_doc_list_id=? ");

		if ($stmt) {
			$stmt->bind_param(
				'ssi',
				$comment_risk,
				$end_date,
				$loss_data_doc_list
			
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();
				$status_risk = 1;
				$statusListId = 1;
				savelog('LOSS-RISK-UPDATE-LossReportList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|');
				echo "<script>alert('ระบบได้บันทึกข้อมูลของท่านแล้ว');
				window.location.href='loss_data_risk.php';
				</script>";
			} else {
				$connect->rollback();
			}
			//	$stmt->close();
			//	$conn->close();
		
	}
	
}

if ($_POST['submitLossUpdateEdit'] == 'submitLossUpdateEdit') {
	
	$status_approveEdit = 2;
	$listId = $_POST['listId'];
	$m = $_POST['m'];
	$y = $_POST['y'];
	$loss_data_doc_list_id = $_POST['edit_data_id'];

	
	$comment_appEdit = $_POST['edit_comment_risk'];
	$attech_name = $_FILES["edit_attech_name"]["name"];

		$qx = true;

		$stmt = $connect->prepare("UPDATE loss_data_doc_list SET
		comment_risk=?,
		approved_date=now(), 
		status_approve=?,
		status_risk_approve=0
		WHERE loss_data_doc_list_id=? ");

		if ($stmt) {
			$stmt->bind_param(
				'sii',
				$comment_appEdit,
				$status_approveEdit,
				$loss_data_doc_list_id
			);
			$q = $stmt->execute();
			$qx = ($qx and $q);

			if ($qx) {
				$connect->commit();
				savelog('LOSS-RISK-UPDATE-LossReportList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|');
				echo "<script>alert('ระบบได้ส่งกลับข้อมูลเพื่อแก้ไขเรียบร้อย');</script>";
				
				 $mailuser = getEmail(getUserdoc($loss_data_doc_list_id));
					
						if($mailuser!=''){
						$email_from = 'noreply-lossdata@bam.co.th';
						$cc = array();		
						$bcc = array();		
						$to = array($mailuser);

						$subject = 'ท่านมีรายงานเหตุการณ์ความเสียหายที่ต้องแก้ไข [LOSS DATA]';
						$body = 'เรียน ผู้รายงานเหตุการณ์ความเสียหาย '.$department_name.'<br><br>
						เนื่องจากวันที่ '.mysqldate2th_date($today).'ฝ่ายบริหารความเสี่ยงมีความเห็นเกี่ยวกับการรายงานเหตุการณ์ความเสียหายที่ท่านทำการรายงาน
						กรุณาเข้าสู่ระบบเพื่อดำเนินการตรวจสอบและแก้ไขรายการ
						<br>จึงแจ้งมายังท่านเพื่อมราบ โปรดดำเนินต่อไป<br><br><a href="'.$url_prefix.'/loss_data.php" target="_new"> คลิกเพื่อเข้าสู่ระบบ</a> ';
						$x = @mail_service($email_from,$to,$cc,$bcc,$subject,$body,$attach_name,$attach_location);		
						if ($x) {
							echo "<font color='#00aa00'><b>ระบบได้ส่ง E-mail เรียบร้อยแล้ว</b></font><br>";
							} else {
								echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารส่ง E-mail ได้</b></font><br>";
								}		
								} else {
									echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ไม่พบ  E-mail ผู้รายงานกรุณาตั้งค่า  E-mail ให้เจ้าหน้าที่ </b></font><br>";
								}
								
				if ($statusListId == '' && $listId == '') {
					//echo $mailuser = getEmail(getUserdoc($loss_data_doc_list_id));
					//exit();
					echo '<script>window.location.replace("loss_data_risk.php")</script>';
				}
			} else {
				$connect->rollback();
			}
			//	$stmt->close();
			//	$conn->close();
			//	$conn->close();
		} else {
			$error = "<script>alert('เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้'); </script>";
		}
	}


if($action=='approve'){
	 $buttonapprove = $_POST['buttonapprove'];
	$loss_data_doc_list_id = $_POST['loss_data_doc_list_id'];
	if($buttonapprove=='1'){ $status_approve=1;}
	if($buttonapprove=='2'){ $status_approve=2;}
	$connect->autocommit(FALSE);
		$qx = true;	
		$stmt = $connect->prepare("UPDATE `loss_data_doc_list` SET `status_risk_approve` = ? , `status_approve` = ? WHERE `loss_data_doc_list_id` = ? ");
		if ($stmt) {
			$stmt->bind_param('iii',$buttonapprove,$status_approve,$loss_data_doc_list_id);
			$q = $stmt->execute();
			$qx = ($qx and $q);	

			if ($qx) {
				$connect->commit();	
			savelog('LOSS-RISK-UPDATESTATUS-LossReportList|loss_data_doc_list_id|'.$loss_data_doc_list_id.'|'.'|STATUS|'.$status_approve);
				echo "<script>  alert('บันทึกรายการเรียบร้อยแล้ว ');  </script>  ";
			} else {
				$connect->rollback();
			}
		} else {
			echo 'x'.$connect->error;
		}
		
}

if($action=='delete'){
	$loss_data_doc_list_id = $_POST['loss_data_doc_list_id'];
	$connect->autocommit(FALSE);
		$qx = true;	
		$stmt = $connect->prepare("DELETE FROM loss_data_doc_list WHERE `loss_data_doc_list`.`loss_data_doc_list_id` = ?");
		if ($stmt) {
			$stmt->bind_param('i',$loss_data_doc_list_id);
			$q = $stmt->execute();
			$qx = ($qx and $q);	

			if ($qx) {
				$connect->commit();	
			savelog('LOSS-RISK-Delete-LossReportList|loss_data_doc_list_id|'.$loss_data_doc_list_id);
				echo "<script>  alert('ลบรายการเรียบร้อยแล้ว ');  </script>  ";
			} else {
				$connect->rollback();
			}
		} else {
			echo 'x'.$connect->error;
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
					<span class="caption-subject font-green sbold uppercase">ตรวจสอบเหตุการณ์ความเสียหาย</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<!---------------->

			<div class="row">
			<div class="col-lg-12 col-lg-12 col-sm-12">
		<table>
			<tr>
				<td>แสดงข้อมูล ของปี</td><td width='15'></td>
				<td>
					<select name='view_year' class="form-control" onChange='document.location="loss_data_risk.php?view_year="+this.value'>
						<option value='<?=$view_year-2?>'><?=$view_year-2?></option>
						<option value='<?=$view_year-1?>'><?=$view_year-1?></option>
						<option value='<?=$view_year?>' selected><?=$view_year?></option>
						<option value='<?=$view_year+1?>'><?=$view_year+1?></option>
						<option value='<?=$view_year+2?>'><?=$view_year+2?></option>
					<select>
				</td>
			</tr>
		</table>
		<br>
	</div>
	
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 red">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">CASE ที่ยังไม่ปิด
									<?php 
									/*$sqlCount1 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_risk_approve !=1 and  loss_data_doc_list.status_approve = 1 and 			loss_data_doc.loss_year=$view_year";*/
								
								
								$sqlCount1 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE  loss_data_doc_list.status_risk_approve !=1 and 
									loss_data_doc.loss_year=$view_year";
								
								
									$stmt = $connect->prepare($sqlCount1);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
									<div class="desc" style="margin-top: 13px;">
							<form action="loss_data_risk.php?view_year=<?=$view_year?>" method="post" style="display: inline;">
							<input type="hidden" value="1" name="statusListId" id="statusListId">
							<input type="hidden" value="0" name="status_risk" id="status_risk">
							<input type="hidden" value="<?=$m;?>" name="m" id="m">
							<input type="hidden" value="<?=$y;?>" name="y" id="y">
							<button type='submit' class="btn btn-danger">  Click ดูข้อมูลเพิ่มเติม</button>
							</form></div>
							
							<div class="desc" style="margin-top: 13px;"><a href="loss_data_risk.php?statusListId=1&status_risk=0" style="color: #FFFFFF;">
									<< เรียกดูข้อมูลเพิ่มเติม>>
								</a></div>
						</div>
					</div>
				</div>
				
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 green">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">CASE ที่ปิด 
									<? 
								/*	$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_risk_approve =1 and loss_data_doc_list.status_approve = 1 and 												loss_data_doc.loss_year=$view_year";*/
								
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_risk_approve =1 and loss_data_doc.loss_year=$view_year";
								
									$stmt = $connect->prepare($sqlCount3);								
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									คำขอ</span> </div>
							<div class="desc" style="margin-top: 13px;">
							<form action="loss_data_risk.php?view_year=<?=$view_year?>" method="post" style="display: inline;">
							<input type="hidden" value="1" name="statusListId" id="statusListId">
							<input type="hidden" value="1" name="status_risk" id="status_risk">
							<input type="hidden" value="<?=$m;?>" name="m" id="m">
							<input type="hidden" value="<?=$y;?>" name="y" id="y">
							<button type='submit' class="btn btn-danger"  style='background-color: #51dca2; border-color: #51dca2;'>   Click ดูข้อมูลเพิ่มเติม</button>
							</form></div>
							
						</div>
					</div>
				</div>
			</div>
			<!---------------->
			

		</div>
	</div>
</div>


<?  $statusListId = $_POST['statusListId'];
$status_risk = $_POST['status_risk'];
$listId = $_POST['listId'];

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
				if ($status_risk !=NULL) {
					if ($status_risk != 1) {
						echo "<span style='font-size: 20px;margin:10px;'>CASE ที่ยังไม่ปิด</span><br>";
					} elseif ($status_risk == 1) {
						echo "<span style='font-size: 20px;margin:10px;'>CASE ที่ปิด</span><br>";
					} 
				}								?>

				<form action='export_risk.php?listId=<?=$listId?>&m=<?=$m?>&y=<?=$y?>' method="post">
				<input type="hidden" name="y_risk" value="<?=$_GET['view_year'];?>">
				<input type="hidden" name="s_risk"  value="<?=$_POST['status_risk'];?>">
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
											<th style='width:5%'>ลำดับ</th>
											<th style='width:20%'>เหตุการณ์</th>
											<th style='width:20%' width="150">รายงานโดย</th>
											<th style='width:20%' width="150">ฝ่าย</th>
											<th style='width:10%'>วันที่บันทึก</th>
											<th style='width:10%'>ระดับความเสียหาย</th>
											<th style='width:10%'>สถานะรายงานประจำเดือน</th>
											<th style='width:20%'>จัดการ</th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										if ($statusListId !=NULL) {
										/*	$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											join department on department.department_id = loss_data_doc.loss_dep 
											where loss_data_doc_list.status_approve = 1  and loss_data_doc.loss_year='$view_year'";*/
											
											
											$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											join department on department.department_id = loss_data_doc.loss_dep 
											where  loss_data_doc.loss_year='$view_year'";
											
											
										if ($status_risk=='0'){
											$sql = $sql."and loss_data_doc_list.status_risk_approve !=1";
										} else {
											$sql = $sql."and loss_data_doc_list.status_risk_approve =1";
										}
										}
										//echo $sql;
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
													<br> <?= get_user_division_name($row['doclist_user_id']); ?>
												</td>
												<td style="vertical-align: middle;"><?= $row['department_name']; ?>
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
												
												<td><?if ($row['status_lossapprove'] == '0') { echo"<font color='red'> รออนุมัติ</font>";}?>
											<?if ($row['status_lossapprove'] == '1') { echo "<font color='green'> อนุมัติรายงานประจำเดือนแล้ว<br>วันที่ ".mysqldate2th_date($row['approveddate']); }?></td>
												
												<td width="250">

													<button name='submit' class="btn btn-success showDetailData" date-id="<?= $row['loss_data_doc_list_id']; ?>" data-happen_date="<?= $row['happen_date']; ?>" data-checked_date="<?= $row['checked_date']; ?>" data-incidence="<?= $row['incidence']; ?>" data-incidence_detail="<?= $row['incidence_detail']; ?>" data-cause="<?= $row['cause']; ?>" data-user_effect="<?= $row['user_effect']; ?>" data-damage_type="<?= $row['damage_type']; ?>" data-incidence_type="<?= $row['incidence_type']; ?>" data-loss_type="<?= $row['loss_type']; ?>" data-control="<?= $row['control']; ?>" data-loss_value="<?= $row['loss_value']; ?>" data-chance="<?= $row['chance']; ?>" data-effect="<?= $row['effect']; ?>" data-damageLevel="<?= $row['damageLevel']; ?>" data-related_dep_id="<?= $row['related_dep_id']; ?>" data-dep_id_1="<?= $row['dep_id_1']; ?>" data-dep_id_2="<?= $row['dep_id_2']; ?>" data-dep_id_3="<?= $row['dep_id_3']; ?>" data-comment_app="<?= $row['comment_app']; ?>" data-approved_date="<?= $row['approved_date']; ?>" data-status_approve="<?= $row['status_approve']; ?>" data-comment_risk="<?= $row['comment_risk']; ?>" data-status_risk_approve="<?= $row['status_risk_approve']; ?>" data-riskcomment_date="<?= $row['riskcomment_date']; ?>" data-attech_name="<?= $row['attech_name']; ?>" data-attech_name2="<?= $row['attech_name2']; ?>" data-end-date="<?= $row['end_date']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-list-alt'></i> รายละเอียด</button>
													<?if($status_risk_approve!='1'){?>
													<form action="loss_data_risk.php?action=approve" method="post" target="_blank" style="display: inline;">
													<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
													<input type="hidden" name="statusListId" value="<?=$statusListId?>">
													<button type='submit' name='buttonapprove' value='1' class="btn btn-info" >  อนุมัติ</button>
													
													</form>
													
													
													<?}?>
													<form action="loss_data_risk.php?action=approve" method="post" target="_blank" style="display: inline;">
													<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
													<input type="hidden" name="statusListId" value="<?=$statusListId?>">
													<a type='submit' class="btn btn-danger editDetailData" style="width: 110px;" edit-data-id="<?= $row['loss_data_doc_list_id']; ?>" edit-data-happen_date="<?= $row['happen_date']; ?>" edit-data-checked_date="<?= $row['checked_date']; ?>" edit-data-incidence="<?= $row['incidence']; ?>" edit-data-incidence_detail="<?= $row['incidence_detail']; ?>" edit-data-cause="<?= $row['cause']; ?>" edit-data-user_effect="<?= $row['user_effect']; ?>" edit-data-damage_type="<?= $row['damage_type']; ?>" edit-data-incidence_type="<?= $row['incidence_type']; ?>" edit-data-loss_type="<?= $row['loss_type']; ?>" edit-data-control="<?= $row['control']; ?>" edit-data-loss_value="<?= $row['loss_value']; ?>" edit-data-chance="<?= $row['chance']; ?>" edit-data-effect="<?= $row['effect']; ?>" edit-data-damageLevel="<?= $row['damageLevel']; ?>" edit-data-related_dep_id="<?= $row['related_dep_id']; ?>" edit-data-dep_id_1="<?= $row['dep_id_1']; ?>" edit-data-dep_id_2="<?= $row['dep_id_2']; ?>" edit-data-dep_id_3="<?= $row['dep_id_3']; ?>" edit-data-comment_app="<?= $row['comment_app']; ?>" edit-data-approved_date="<?= $row['approved_date']; ?>" edit-data-status_approve="<?= $row['status_approve']; ?>" edit-data-comment_risk="<?= $row['comment_risk']; ?>" edit-data-status_risk_approve="<?= $row['status_risk_approve']; ?>" edit-data-riskcomment_date="<?= $row['riskcomment_date']; ?>" edit-data-attech_name="<?= $row['attech_name']; ?>"  edit-data-attech_name2="<?= $row['attech_name2']; ?>"  data-toggle="modal" data-target="#editModalSendCase"><i class='glyphicon glyphicon-send'></i> ส่งแก้ไข</a>
													</form>
													<?if($status_risk_approve=='1'){?>
													<form action="pdf.php" method="post" target="_blank" style="display: inline;">
													<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
													<button type='submit' class="btn btn-dark" style="background-color: #949596; color:#FFFFFF;"> <span class="glyphicon glyphicon-print"></span> พิมพ์</button>
													</form>
													<?}?>
													
													<form action="loss_data_risk.php?action=delete" method="post" target="_blank" style="display: inline;">
													<input type="hidden" name="loss_data_doc_list_id" value="<?= $row['loss_data_doc_list_id']; ?>">
													<button type='submit' class="btn btn-dark" style="background-color: #e91e63; color:#FFFFFF;"  onclick="return confirm('ยืนยันการทำรายการ?')">ลบรายการ</button>
													</form>
													
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

<!-- start modal edit -->
<div id="editModalSendCase" class="modal fade" role="dialog">
	<div class="modal-dialog  modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="background-color:#27A4B0;color:#FFFFFF;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-pencil"></span> ส่งแก้ไข</h4>
			</div>
			<div class="modal-body" align="left">

				<form method='post' onsubmit="return validateForm()" action='loss_data_risk.php' enctype="multipart/form-data">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-xs-12">

								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่เกิดเหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='edit_happen_date' readonly id='edit_happen_date' style="background-color: #FFFFFF; cursor: default;" readonly disabled></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10 label-main">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" name='edit_checked_date' readonly id='edit_checked_date' style="background-color: #FFFFFF; cursor: default;" readonly disabled></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">เหตุการณ์<span style="color: red;">*</span></label><textarea id="edit_incidence" class="form-control" name="edit_incidence" rows="3" cols="50" style="min-height:80px;" style="background-color: #FFFFFF; cursor: default;" readonly disabled></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="edit_incidence_detail" class="form-control" name="edit_incidence_detail" rows="3" cols="50" style="min-height:80px;" style="background-color: #FFFFFF; cursor: default;" readonly disabled></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">สาเหตุ<span style="color: red;">*</span></label><textarea id="edit_cause" class="form-control" name="edit_cause" rows="3" cols="50" style="min-height:80px;" style="background-color: #FFFFFF; cursor: default;" readonly disabled></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10 label-main">ผลกระทบ<span style="color: red;">*</span></label><textarea id="edit_user_effect" class="form-control" name="edit_user_effect" rows="3" cols="50" style="min-height:80px;" style="background-color: #FFFFFF; cursor: default;" readonly disabled></textarea></div>
										<div class="col-lg-12"> <label class="margins-top-10 label-main"> ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='edit_damage_type' id='edit_damage_type' class="form-control" style="background-color: #FFFFFF; cursor: default;" readonly disabled>
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
										<div class="col-lg-12"><label class="margins-top-10 label-main">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='edit_incidence_type' id='edit_incidence_type' class="form-control" style="background-color: #FFFFFF; cursor: default;" readonly disabled>
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
											<div class="col-lg-12"><label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type1" value="1" readonly disabled >&nbsp;  <b>Actual Loss : </b> ความเสียหายที่เกิดขึ้นจริงทั้งที่เป็นตัวเงิน และไม่เป็นตัวเงิน
												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type2" value="2" readonly disabled>&nbsp; <b> Potential Loss : </b>ความเสียหายที่อาจเกิดขึ้น

												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="edit_loss_type" id="edit_loss_type3" value="3" readonly disabled >&nbsp; <b> Near-Missed : </b>ความเสียหายที่เกิดขึ้น หรืออาจเกิดขึ้น แต่สามารถป้องกันความเสียหายไว้ได้
												</label></div>
</div>

										<div class="col-lg-12"><label class="margins-top-10 label-main">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><textarea id="edit_control" class="form-control" name="edit_control" rows="3" cols="50" style="min-height:80px;" style="background-color: #FFFFFF; cursor: default;" readonly disabled></textarea></div>
										<div class="col-lg-3"><label class="margins-top-10 label-main">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control " name='edit_loss_value' id='edit_loss_value' oninput="validate(this)" onkeyup="javascript:this.value=Comma(this.value);" onkeypress="return CheckNumeric()" style="background-color: #FFFFFF; cursor: default;" readonly disabled></div>
										<div class="col-lg-3"><label class="margins-top-10 label-main">โอกาส<span style="color: red;">*</span></label>
									
											<select name='csa_likelihood_id1' id='csa_likelihood_id1' class="form-control" style="background-color: #FFFFFF; cursor: default;" readonly disabled  >
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
										
										
											<select name='csa_impact_id1' id='csa_impact_id1' class="form-control" <?=$lock_tag?> style="background-color: #FFFFFF; cursor: default;" readonly disabled>
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
													<div class="row">		
													<div class="col-lg-12">			
										<!-- <div class="col-lg-6" ><label class="margins-top-10 label-main"><br> 
										<div id="link1Edit"></div>	<br>
									</label>
									<hr>
									<div id='edit_attech_name_fack'></div>
									<label class="margins-top-10 label-main">อัพโหลดไฟล์ใหม่ 1 </label>
											<input type="file" class="form-control" name='edit_attech_name'>

											
											
										</div>
										<div class="col-lg-6"><label class="margins-top-10 label-main"><br> 
										<div id="link2Edit"></div>	<br>
									</label>
									<hr>
									<div id='edit_attech_name_fack2'></div>
									<label class="margins-top-10 label-main">อัพโหลดไฟล์ใหม่ 2 </label><input type="file" class="form-control" name='edit_attech_name2'>
										
										</div> -->
													</div>
													</div>
											<div class="col-lg-12"><label class="margins-top-10 label-main">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="edit_comment_app" class="form-control" name="edit_comment_app" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>
										
												<!-- <div class="col-lg-3 col-xs-12"><label class="margins-top-10 label-main">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='edit_end_date' readonly id='edit_end_date'></div> -->
									
											<div class="col-lg-12"><label class="margins-top-10 label-main">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="edit_comment_risk" class="form-control" name="edit_comment_risk" rows="3" cols="50" style="min-height:80px;" ></textarea></div>
									

									</div>
								
										<div align="center" style="margin-top: 30px;">
											<input type='hidden' name='edit_data_id' id='edit_data_id'>
											<input type='hidden' name='listId' id='listId' value="<?= $listId; ?>">
											<input type='hidden' name='m' id='m' value="<?= $m; ?>">
											<input type='hidden' name='y' id='y' value="<?= $y; ?>">
											<button type='submit' name='submitLossUpdateEdit' value='submitLossUpdateEdit' class="btn btn-danger" ><i class='glyphicon glyphicon-send'></i> &nbsp;ส่งแก้ไขข้อมูล</button>
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

	<!---------------->
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

				<form method='post' action='loss_data_risk.php' enctype="multipart/form-data">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-xs-12">

								<div class="form-group">
									<div class="row">
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control datepicker" name='happen_date' disabled readonly id='happen_date' style="cursor: default;"></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" disabled name='checked_date' readonly id='checked_date' style="cursor: default;"></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">เหตุการณ์<span style="color: red;">*</span></label><input type="text" class="form-control" name='incidence' id='incidence' style="background-color: #FFFFFF;cursor: default;" readonly></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detail" class="form-control" name="incidence_detail" rows="3" cols="50" style="min-height:80px; background-color: #FFFFFF;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="cause" class="form-control" name="cause" rows="3" cols="50" style="min-height:80px;background-color: #FFFFFF;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><textarea id="user_effect" class="form-control" name="user_effect" rows="3" cols="50" style="min-height:80px;background-color: #FFFFFF;cursor: default;" readonly></textarea></div>
										<div class="col-lg-12"> <label class="margins-top-10">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_type' id='damage_type' class="form-control" style="background-color: #FFFFFF; cursor: default;" readonly disabled>
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
										<div class="col-lg-12"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='incidence_type' id='incidence_type' class="form-control" style="background-color: #FFFFFF; cursor: default;" readonly disabled>
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
											<div class="col-lg-12"><label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type1" value="1" disabled>&nbsp;  <b>Actual Loss : </b> ความเสียหายที่เกิดขึ้นจริงทั้งที่เป็นตัวเงิน และไม่เป็นตัวเงิน
												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type2" value="2" disabled>&nbsp; <b> Potential Loss : </b>ความเสียหายที่อาจเกิดขึ้น

												</label></div>
											<div class="col-lg-12"> <label style="height:20px;"></label><label class="radio-inline"><input type="radio" name="loss_type" id="loss_type3" value="3" disabled>&nbsp; <b> Near-Missed : </b>ความเสียหายที่เกิดขึ้น หรืออาจเกิดขึ้น แต่สามารถป้องกันความเสียหายไว้ได้
												</label></div>
											</div>
										<div class="col-lg-12"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><textarea class="form-control" name='control' id='control' rows="2" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-3"><label class="margins-top-10">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" style="cursor: default;" name='loss_value' id='loss_value' oninput="validate(this)" onkeyup="javascript:this.value=Comma(this.value);" onkeypress="return CheckNumeric()" disabled></div>
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
										<? if ($status_approve != '0') { ?>
											<div class="col-lg-12"><label class="margins-top-10" >ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="comment_app" class="form-control" name="comment_app" rows="3" cols="50" style="min-height:80px;" readonly ></textarea></div>
										
												<div class="col-lg-3 col-xs-12"><label class="margins-top-10" style="color:#27A4B0;" >วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='end_date' readonly id='end_date' style="background-color: #FFFFFF;border-color:#27A4B0;"></div>
										
											<div class="col-lg-12"><label class="margins-top-10" style="color:#27A4B0;">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="comment_risk" class="form-control" name="comment_risk" rows="3" cols="50" style="min-height:80px;border-color:#27A4B0;"  ></textarea></div>
										<? } ?>

									</div>

									<div align="center" style="margin-top: 30px;">
<input type="hidden" id="date_id" name="date_id">
<button type='submit' name='submitLossRiskUpdate' value="submitLossRiskUpdate" class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
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
	<!-- Modal -->
	<div id="myModal" class="modal fade" role="dialog">
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
		var endDate = $(this).attr('data-end-date');
		var endDates = endDate.substring(0, 10);
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

		$('#end_date').val(endDates);
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
		if(edit_attech_names == "" || edit_attech_names == null){
	
	document.getElementById("link1Edit").innerHTML = "";
	$("#link1Edit").append('<a href="#" id="edit_attech_names" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');

}else{

document.getElementById("link1Edit").innerHTML = "";
	$("#link1Edit").append('<a href="/attech_file/'+edit_attech_names+'" target="_blank"  download id="edit_attech_names" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 1 </label></a>');
	
	document.getElementById("edit_attech_name_fack").innerHTML = "";
	$("#edit_attech_name_fack").append('<input type="hidden" name="edit_attech_name_file" value="'+edit_attech_names+'">');
}

if(edit_attech_names2 == "" || edit_attech_names2 == null){
	document.getElementById("link2Edit").innerHTML = "";
	$("#link2Edit").append('<a href="#" id="edit_attech_names2" style="text-decoration: none;color:#AAAAAA;cursor: default;"><span class="glyphicon glyphicon-download-alt" style="cursor: default; color:#AAAAAA; margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

	
}else{
	

	document.getElementById("link2Edit").innerHTML = "";
	$("#link2Edit").append('<a href="/attech_file/'+edit_attech_names2+'" target="_blank"  download id="edit_attech_names2" ><span class="glyphicon glyphicon-download-alt" style=" margin-left: 20px;"></span> ดาวน์โหลดเอกสาร 2 </label></a>');

	document.getElementById("edit_attech_name_fack2").innerHTML = "";
     $("#edit_attech_name_fack2").append('<input type="hidden" name="edit_attech_name_file2" value="'+edit_attech_names2+'">');
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


