<?
	include('inc/include.inc.php');
	check_permission(2, true);
	echo template_header();
	$submit_bt = $_POST['submit_bt'];
	$yearsearch = $_POST['yearsearch'];
	$action = form_input_filter($_GET['action']);
	$upfile_1 =$_POST['upfile_1'];
	$loss_data_doc_month_search = $_POST['loss_data_doc_month_search'];
	$yc=$_POST['yc'];

?>

<link rel="stylesheet" type="text/css" href="dist/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="dist/js/jquery.dataTables.js"></script>
<link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.css">
<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
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

<style>
table, th, td {
  border: 1px solid #D0D5D8;
}
</style>
<? if($action=='') { ?>
	<form method='post'  action='loss_data_adminmanage.php' enctype="multipart/form-data">
		<div class='row' >
			<div class="col-lg-3"></div>
			<div class="col-lg-12">[หน้า ADMIN]
			<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 31/05/65</label>
				<b><center>เหตุการณ์ความเสียหาย (Loss data) ประจำเดือน   </center></b><hr>
	 
					<div class='well' id='ask'>
						<div class='row' >
							<input type='hidden' name='dep_id' value='<?=$dep_id?>'>
							<div class="col-lg-7" style='padding-top:5px;'>
								<select  required class='form-control' id='loss_data_doc_month_search' name='loss_data_doc_month_search'>
								<option value=''>- โปรดระบุเดือน-</option>
									<? $sql1="SELECT * FROM month";
										$result1=mysqli_query($connect, $sql1);
										while ($row1 = mysqli_fetch_array($result1)) {	?>
										<option value='<?=$row1['month_id']?>' <?if($row1['month_id']== $loss_data_doc_month_search) echo "selected";?> ><?=$row1['month_name']?></option>
									<?	}?>
								</select></div>
								
								<div class='col-md-2'style='padding-top:5px;'>
								
								<?
								$month_year_search=date('Y')+543;
								if ($yearsearch=='') {
								$yearsearch=date('Y')+543;
								}?>
								<select  class='form-control' id='yearsearch' name='yearsearch'>
									<option value='<?=$month_year_search-4?>' <?if($yearsearch==$month_year_search-4){echo "selected";}?> ><?=$month_year_search-4?></option>
									<option value='<?=$month_year_search-3?>'  <?if($yearsearch==$month_year_search-3){echo "selected";}?> ><?=$month_year_search-3?></option>
									<option value='<?=$month_year_search-2?>' <?if($yearsearch==$month_year_search-2){echo "selected";}?> ><?=$month_year_search-2?></option>
									<option value='<?=$month_year_search-1?>'  <?if($yearsearch==$month_year_search-1){echo "selected";}?> ><?=$month_year_search-1?></option>
									<option value='<?=$month_year_search?>'  <?if($yearsearch==$month_year_search){echo "selected";}?> ><?=$month_year_search?></option>
								</select>
								</div>
								<div class="col-lg-3" style='padding-top:5px;'>
									<div id="ok" name="ok">
										<button type='submit' name='submit_bt' value='searchact' class="btn btn-primary btn-block"><i class='fa fa-search'></i> ค้นหา</button>
									</div></div></div></div></div></div>
	</form>


				<?if ($loss_data_doc_month_search!=''){?>
				<div align='center'><b><?if ($loss_data_doc_month_search=='') { echo "สรุปผลเหตุการณ์ความเสียหาย (Loss data) ทั้งหมด ";}
				else 
				{echo" สรุปผลรายงานความผิดปกติประจำเดือน";				
				$sql11="SELECT * FROM month where month_id =$loss_data_doc_month_search ";
				$result11=mysqli_query($connect, $sql11);
				$row11 = mysqli_fetch_array($result11);{	
				echo	$row11['month_name']." ";}}
				if($yearsearch!=''){	echo $yearsearch;}
					?>					
				</b><hr></div>			
			<div id="printableArea">
  <table  class='table table-striped' border='1' >
								<thead>
									<tr class="info" >
										<td width='1%' align='center'><b>ลำดับ</b></td>
										<td width='3%' align='center'><b>รหัสฝ่าย</b></td>
										<td width='20%' align='center'><b>รายชื่อฝ่าย</b> </td>
										<td width='15%' align='center'><b>ผลการรายงาน</b></td>
										<td width='10%' align='center'><b>รายงานผลโดย</b></td>
										<td width='10%' align='center'><b>ผู้อนุมัติ</b></td>
										<td width='5%' align='center'><b>รอการอนุมัติ</b></td>
										<td width='5%' align='center'><b>ส่งกลับแก้ไข</b></td>
										<td width='5%' align='center'><b>ดำเนินการแล้วเสร็จ</b></td>
										<td width='5%' align='center'><b>จำนวนทั้งสิ้น</b></td>
										<td width='5%' align='center'><b>ทำรายการ</b></td>										
									</tr>
								</thead>
				 <tbody>
			<?	$sql1="SELECT department_id,department_name FROM department where mark_del !='1' ORDER BY `department`.`is_branch`   ";
					$result1=mysqli_query($connect, $sql1);
					$j = 1;
					while ($row1 = mysqli_fetch_array($result1)){	
					?>
					<tr>
					<td align='center'> <?=$j; $j++;?></td>
					<td><b><? echo $department_id = $row1['department_id'];?></b> </td>
					<td><b><? echo	$row1['department_name'];?></b> </td>
					<?  $sql25 =  "SELECT  loss_data_doc.* ,count(loss_data_doc_list.loss_data_doc_id) as num ,
					loss_data_doc.loss_data_doc_id as lossid FROM loss_data_doc 
					join loss_data_doc_list on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
                    where `loss_data_doc`.`loss_dep` =  $department_id and loss_data_doc.loss_year = '$yearsearch' 
                    and  loss_data_doc.loss_data_doc_month='$loss_data_doc_month_search'";
					$result25 = mysqli_query($connect, $sql25);
					$row25 = mysqli_fetch_array($result25);
					{$loss_have = $row25['loss_have'];
					$user_idloss = $row25['user_id'];
					$lossid = $row25['lossid'];?>
					<td align='left'><?
					if($loss_have =='0') {echo "ไม่มีเหตุการณ์ความเสียหาย  ";}
					if ($loss_have =='1') { echo"<font color='red'>มีเหตุการณ์ความเสียหาย  </font>";}
					if ($loss_have =='') {echo "<font color='blue'>ยังไม่ได้รายงาน </font>";}?><?}?>
					</td>		
					<td><?if ($user_idloss!=''){echo get_user_name($user_idloss);}?></td>
					<td><?=$row25['approve_name']?></td>
					<td><?php $i3 = 0;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc  on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ?";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("ii", $i3,$department_id);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?></td>
					<td><?php $i3 = 2;
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
									?></td>
					<td><?php $i3 = 1;
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
									?></td>
					<td><?php 
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE  loss_data_doc.loss_dep = ?";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("i",$department_id);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?></td>
					<td>
					<form action ='loss_data_adminmanage.php?action=more&loss_data_doc=<?= $lossid; ?>' method="post">
					<input type='hidden' name='department_name' value='<? echo	$row1['department_name'];?>'>
					<button type='submit' class="btn btn-success"><i class="glyphicon glyphicon-list-alt"></i> รายการเพิ่มเติม</button>
					</form></td>
					<? }?>
					</tr>
					</tbody>
					</table>
			  		</div>
<?}?>
<? }?>
<?/* form */ ?>


<?////////////////////////////////////////////////////?>

<?if($action=='more'){
	$loss_data_doc=$_GET['loss_data_doc'];
	$department_name=$_POST['department_name'];?>


<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 31/05/65</label>
	

<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">ตรวจสอบเหตุการณ์ความเสียหาย ฝ่าย <?=$department_name?></span>
					<span class="caption-helper"></span>
				</div>
			</div><br>
			<div class="form-group">
				<div class="row">
					<div class="col-lg-12 col-xs-12">

							<div class='row' style="margin-left: 3px;margin-right: 3px;">
								<!-- start table -->

								<table id="dataTableList" class="table table-striped table-bordered" style="width:100%">
									<thead>
										<tr>
											<th style='width:5%'>ลำดับ</th>
											<th style='width:20%'>เหตุการณ์</th>
											<th style='width:20%' width="150">รายงานโดย</th>
											<th style='width:10%'>วันที่บันทึก</th>
											<th style='width:10%'>ระดับความเสียหาย</th>
											<th style='width:10%'>สถานะจากฝ่ายความเสี่ยง</th>
											<th style='width:10%'>วันที่ปิด CASE</th>
											<th style='width:20%'>จัดการ</th>
										</tr>
									</thead>
									
									<tbody>
										<?php
											$sql = "SELECT * FROM loss_data_doc_list 
											join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
											where  loss_data_doc.loss_data_doc_id='$loss_data_doc'  ";
																					
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
												<td>
												<?if($status_risk_approve=='0'){?>รออนุมัติ<?}?>
												<?if($status_risk_approve=='1'){?>ดำเนินการแล้วเสร็จ <?}?>
												<?if($status_risk_approve=='2'){?>แก้ไข <?}?></td>
												
												<td><?= mysqldate2th_datetime($row['end_date']); ?></td>
											
												<td width="250">

													<button name='submit' class="btn btn-success showDetailData" data-happen_date="<?= $row['happen_date']; ?>" data-checked_date="<?= $row['checked_date']; ?>" data-incidence="<?= $row['incidence']; ?>" data-incidence_detail="<?= $row['incidence_detail']; ?>" data-cause="<?= $row['cause']; ?>" data-user_effect="<?= $row['user_effect']; ?>" data-damage_type="<?= $row['damage_type']; ?>" data-incidence_type="<?= $row['incidence_type']; ?>" data-loss_type="<?= $row['loss_type']; ?>" data-control="<?= $row['control']; ?>" data-loss_value="<?= $row['loss_value']; ?>" data-chance="<?= $row['chance']; ?>" data-effect="<?= $row['effect']; ?>" data-damageLevel="<?= $row['damageLevel']; ?>" data-related_dep_id="<?= $row['related_dep_id']; ?>" data-dep_id_1="<?= $row['dep_id_1']; ?>" data-dep_id_2="<?= $row['dep_id_2']; ?>" data-dep_id_3="<?= $row['dep_id_3']; ?>" data-comment_app="<?= $row['comment_app']; ?>" data-approved_date="<?= $row['approved_date']; ?>" data-status_approve="<?= $row['status_approve']; ?>" data-comment_risk="<?= $row['comment_risk']; ?>" data-status_risk_approve="<?= $row['status_risk_approve']; ?>" data-riskcomment_date="<?= $row['riskcomment_date']; ?>" data-attech_name="<?= $row['attech_name']; ?>" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-list-alt'></i> รายละเอียด</button>
													
													<?if($status_risk_approve=='1'){?>
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
<div align="center"><br> <a href="loss_data_adminmanage.php">
								<span class="glyphicon glyphicon-menu-left"></span><span class="glyphicon glyphicon-menu-left"></span> ย้อนกลับ
							</a>
						</div>
					
						
					</div>
					
				</div>
			</div>

		</div>
	</div>
</div>

<!-- start modal -->
<form method='post' action='loss_data.php' enctype="multipart/form-data">
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
											<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์</label><input type="text" class="form-control datepicker" name='happen_date' readonly id='happen_date'></div>
											<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ</label><input type="text" class="form-control datepicker" name='checked_date' readonly id='checked_date'></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">เหตุการณ์</label><input type="text" class="form-control" name='incidence' id='incidence'></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="incidence_detail" class="form-control" name="incidence_detail" rows="3" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="cause" class="form-control" name="cause" rows="3" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><input type="text" class="form-control" name='user_effect' id='user_effect'></div>
											<div class="col-lg-3"> <label class="margins-top-10">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='damage_type' id='damage_type' class="form-control">
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
											<div class="col-lg-3"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='incidence_type' id='incidence_type' class="form-control">
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

											<div class="col-lg-2"> <label class="margins-top-10 col-xs-12" style="margin-left: -13px;">Loss : </label><label class="radio-inline"><input type="radio" name="loss_type" value="1"> Actual Loss
												</label></div>
											<div class="col-lg-2"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" value="2"> Potential Loss
												</label></div>
											<div class="col-lg-2"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="loss_type" value="3"> Near-Missed
												</label></div>

											<div class="col-lg-6"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><input type="text" class="form-control" name='control' id='control'></div>
											<div class="col-lg-4"><label class="margins-top-10">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" name='loss_value' id='loss_value'></div>
											<div class="col-lg-4"><label class="margins-top-10">โอกาส<span style="color: red;">*</span></label><select name='chance' id='chance' class="form-control">
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
											<div class="col-lg-4"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><select name='effect' id='effect' class="form-control">
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

											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 1</label><select name='dep_id_1' id='dep_id_1' class="form-control">
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
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 2</label><select name='dep_id_2' id='dep_id_2' class="form-control">
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
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 3</label><select name='dep_id_3' id='dep_id_3' class="form-control">
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
											<div class="col-lg-12"><label class="margins-top-10"><br> ดาวน์โหลดเอกสารแนบ  >> เปิดไฟล์ << </label>
												<input type='hidden' name='loss_data_doc_id' id='loss_data_doc_id'>
											</div>
											<div class="col-lg-12"><label class="margins-top-10">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="comment_app" class="form-control" name="comment_app" rows="3" cols="50" style="min-height:80px;" readonly ></textarea></div>
											<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='end_date' readonly id='end_date'></div>
											
											<div class="col-lg-12"><label class="margins-top-10">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="comment_risk" class="form-control" name="comment_risk" rows="3" cols="50" style="min-height:80px;"  ></textarea></div>
											
											
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

<?}?>
<?
echo template_footer();
?>							
