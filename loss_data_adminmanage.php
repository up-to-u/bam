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
			
			
			
			<table class='table table-striped' >
		<thead>
			<tr class="info">
				<td width='1%' align='center'><b>ลำดับ</b></td>
						<td width='20%' align='center'><b>รายชื่อฝ่าย</b> </td>
						<td width='10%' align='center'><b>ผลการรายงาน</b></td>
						<td width='10%' align='center'><b>ผลอนุมัติ</b></td>
						<td width='15%' align='center'><b>ผู้ทำรายการ</b></td>
						<td width='5%' align='center'><b>รอการอนุมัติ</b></td>
						<td width='5%' align='center'><b>ส่งกลับแก้ไข</b></td>
						<td width='5%' align='center'><b>ดำเนินการแล้วเสร็จ</b></td>
						<td width='5%' align='center'><b>จำนวนทั้งสิ้น</b></td>
						<td width='5%' align='center'><b>ทำรายการ</b></td>
			</tr>
		</thead>
		<tbody>
		<?php
		$department_list = [];
		$sql_dep="SELECT department_id,department_name,parent_id,department_level_id,is_branch FROM department where mark_del ='0' ORDER BY is_branch,department_id";
		$result_dep=mysqli_query($connect, $sql_dep);
		while ($row_dep = mysqli_fetch_array($result_dep)){
			if ($row_dep['department_level_id']=='4') {
				$department_list[$row_dep['department_id']][$row_dep['department_id']] = $row_dep['department_name'];
			}
			else if($row_dep['department_level_id']=='5' and $row_dep['is_branch']=='1' ){
				$department_list[$row_dep['parent_id']][$row_dep['department_id']] = $row_dep['department_name'];
			}
		}
	
		
			?>
		
		<?
		$sql_list="SELECT department_id,loss_data_doc_month,loss_create,loss_have,status_lossapprove,approveddate,user_id,approve_name,status_approve,loss_data_doc.loss_data_doc_id
		FROM loss_data_doc 
		LEFT JOIN department ON department.department_id = loss_data_doc.loss_dep
		LEFT JOIN loss_data_doc_list ON loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
		WHERE department.mark_del = '0' and	loss_data_doc.loss_year = '$yearsearch'
		ORDER BY is_branch,department_id,loss_data_doc_month";
		$result_list=mysqli_query($connect, $sql_list);
		$loss_data_list = [];
		$loss_data_statuslist = [];
		while ($row_list = mysqli_fetch_array($result_list)){
			$loss_data_list[$row_list['department_id']] = [ 'loss_data_doc_id' => $row_list['loss_data_doc_id'],'loss_create' => $row_list['loss_create'], 'approve_name' => $row_list['approve_name'],'approved_date' => $row_list['approved_date'], 'user_id' => $row_list['user_id'],'loss_have' => $row_list['loss_have'], 'approveddate' => $row_list['approveddate'],'status_lossapprove' => $row_list['status_lossapprove']];
			$loss_data_statuslist[$row_list['department_id']][$row_list['status_approve']] +=1;	
			
		
		}	
		
		
			
			
		var_dump($loss_data_statuslist);
		$num = 0;
		foreach($department_list as $dep_id => $department_list2){
		foreach($department_list2 as $dep_id2 => $dep_name){
			$num++;
				$loss_data = $loss_data_list[$dep_id2];
				$lossid = $loss_data['loss_data_doc_id'];
				if ($loss_data['loss_have']=='0'){
						$txt ='<font color="green">ไม่มีเหตุการณ์ความเสียหาย</font>';
				} else if ($loss_data['loss_have']=='1') {
						$txt ='<font color="red">มีเหตุการณ์ความเสียหาย</font>';
				} else if ($loss_data['loss_have']==NULL) {
						$txt ='<font color="blue">ยังไม่ได้รายงาน</font>';
				}
				
				if ($loss_data['status_lossapprove']=='0'){
						$txtapprove ='<font color="red">รออนุมัติ</font>';
				} else if ($loss_data['status_lossapprove']=='1') {
						$txtapprove ='<font color="green">อนุมัติแล้ว  <br> ณ วันที่ '.mysqldate2th_date($loss_data['approveddate']).'</font>';
				} else {
						$txtapprove='';
				}
				if ($loss_data['user_id']!='')
				{
						$txtuser = get_user_name($loss_data['user_id']);
						$txtapprove_name = ($loss_data['approve_name']);
				} else {
						$txtuser ='';
						$txtapprove_name ='';
				}
				
				$total = $loss_data_statuslist[$dep_id2][0] + $loss_data_statuslist[$dep_id2][2] + $loss_data_statuslist[$dep_id2][1] ; 
			
		?>
		<tr>
		<td align='center'><? echo $num; ?></td>
		<td><? echo $dep_name; ?></td>
		<td><?php echo $txt;?></td>
		<td><?php echo $txtapprove;?></td>
		<td>ผู้รายงาน : <?php echo $txtuser;?><br> ผู้อนุมัติ :  <?php echo $txtapprove_name?></td>
		<td align='center'><?=$loss_data_statuslist[$dep_id2][0]?></td>
		<td align='center'><?=$loss_data_statuslist[$dep_id2][2]?></td>
		<td align='center'><?=$loss_data_statuslist[$dep_id2][1]?></td>
		<td align='center'><?=$total;?></td>
		<td><?if ($total>'0'){?>
			<form target="_blank" action ='loss_data_adminmanage.php?action=more&loss_data_doc=<?= $lossid; ?>' method="post">
				<input type='hidden' name='department_name' value='<? echo	$row1['department_name'];?>'>
				<button type='submit' class="btn btn-success btn-block"><i class="glyphicon glyphicon-list-alt"></i> รายการเพิ่มเติม</button>
			</form>
			<?} if ($total==NULL && $loss_data['loss_have'] !=NULL ) {?>
				<form action ='loss_data_adminmanage.php' method="post">
				<input type='hidden' name='action_1' value='changestatus'>
				<input type='hidden' name='changestatus_id' value='<?= $lossid; ?>'>
				<button type='submit' class="btn btn-danger btn-block"> ถอยสถานะ </button>
			<?}?></td>
		</tr>
		
		<?
			
		}
		}
		?>	
		</tbody>
	 </table>	
			</div>
		<?}?>
	<? }?>
	<?/* form */ ?>
	
	
	<? if($_POST['action_1']=='changestatus'){
		$changestatus_id = $_POST['changestatus_id'];
		$connect->autocommit(FALSE);
		$qx = true;	
		$stmt = $connect->prepare("DELETE FROM loss_data_doc WHERE loss_data_doc_id = ?");
		if ($stmt) {
			$stmt->bind_param('i',$changestatus_id);
			$q = $stmt->execute();
			$qx = ($qx and $q);	
			
			if ($qx) {
				$connect->commit();	
				savelog('LOSS-MANAGE-Delete-STATUS|loss_data_doc_list_id|'.$loss_data_doc_list_id);
				echo "<script>  alert('ถอยสถานะเรียบร้อยแล้ว ');  </script>  ";
			} else {
			$connect->rollback();
			}
			} else {
			echo 'x'.$connect->error;
			}
			}
			?>
			
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
			<br> <?= get_user_division_name($row['doclist_user_id']); ?>
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
			</style>
			
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
			<div class="col-lg-3" id="showPerformanceDetail" style="margin-top:35px;">
			
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
			
			<div class="col-lg-12"><label class="margins-top-10">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="comment_app" class="form-control" name="comment_app" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>
			
			<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ปิดรายการ</label><input type="text" class="form-control datepicker" name='end_date' readonly id='end_date'></div>
			
			<div class="col-lg-12"><label class="margins-top-10">ความเห็นฝ่ายบริหารความเสี่ยง</label><textarea id="edit_comment_risk" class="form-control" name="edit_comment_risk" rows="3" cols="50" style="min-height:80px;" readonly></textarea></div>
			
			
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
			<script>
			$(document).ready(function() {
			var table = $('#dataTableList').DataTable({
			responsive: true
			});
			
			new $.fn.dataTable.FixedHeader(table);
			});
			</script>
			
			<script>
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
			document.getElementById("showPerformanceDetail").innerHTML = "";
			if (checkLossLevel(strCheck) == 1) {
			$("#showPerformanceDetail").append('<a    class="btn" style="background-color: #00B050; color:#FFFFFF; width:120px; cursor: default;" > ต่ำ</a>');
			} else if (checkLossLevel(strCheck) == 2) {
			$("#showPerformanceDetail").append('<a    class="btn" style="background-color: #FFFF00; color:#000000; width:120px; cursor: default;" > ปานกลาง</a>');
			} else if (checkLossLevel(strCheck) == 3) {
			$("#showPerformanceDetail").append('<a    class="btn" style="background-color: #FF9400; color:#FFFFFF; width:120px; cursor: default;" > สูง</a>');
			} else if (checkLossLevel(strCheck) == 4) {
			$("#showPerformanceDetail").append('<a    class="btn" style="background-color: #FF0000;; color:#FFFFFF; width:120px; cursor: default;" > สูงมาก</a>');
			}
			} else {
			var strCheck = chances + effects
			document.getElementById("showPerformanceDetail").innerHTML = "";
			$("#showPerformanceDetail").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
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
			var urlDownload = "/attech_file/" + attech_names;
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
			
			if (edit_chances != 0 && edit_effects != 0) {
			var strCheck = edit_chances + edit_effects
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
			var strCheck = edit_chances + edit_effects
			document.getElementById("showPerformance").innerHTML = "";
			$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
			}
			
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
			var urlDownload = "/attech_file/" + edit_attech_names;
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
			$('#edit_chance').on('change', function() {
			if ($('#edit_chance').val() != 0 && $('#edit_effect').val() != 0) {
			var strCheck = $('#edit_chance').val() + $('#edit_effect').val()
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
			var strCheck = $('#edit_chance').val() + $('#edit_effect').val()
			document.getElementById("showPerformance").innerHTML = "";
			$("#showPerformance").append('<a    class="btn" style="background-color: #EEF1F5;; color:#7A7877; width:120px; cursor: default;" > ระดับความเสียง </a>');
			}
			
			
			});
			
			$('#edit_effect').on('change', function() {
			if ($('#edit_chance').val() != 0 && $('#edit_effect').val() != 0) {
			var strCheck = $('#edit_chance').val() + $('#edit_effect').val()
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
			var strCheck = $('#edit_chance').val() + $('#edit_effect').val()
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
			
			<?}?>
			<?
			echo template_footer();
			?>							
						