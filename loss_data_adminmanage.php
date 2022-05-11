<?
	include('inc/include.inc.php');
	check_permission(2, true);
	echo template_header();
	$submit_bt = $_POST['submit_bt'];
	$yearsearch = $_POST['yearsearch'];
	$show_id = form_input_filter($_GET['show_id']);
	$edit_id = form_input_filter($_GET['edit_id']);
	$upfile_1 =$_POST['upfile_1'];
	$loss_data_doc_month_search = $_POST['loss_data_doc_month_search'];
	$yc=$_POST['yc'];
	$mark_change=$_POST['mark_change'];
	$mark_statuschange=$_POST['mark_statuschange'];
	
	
	

savelog('loss-openmanage-data');	
$p_list = array();
$p_name = array();
$sql2="SELECT *	FROM loss_impact where 	loss_impact_parent = 'fix' ";
$result2=mysqli_query($connect, $sql2);
while ($row2 = mysqli_fetch_array($result2)) {
	
	$p_list[$row2['loss_impact_value']] = 0;
	$p_name[$row2['loss_impact_value']] = $row2['loss_impact_name'];
}

if($mark_change!=''){
$lossid=$_POST['lossid'];
$approved_statuschange=$_POST['approved_statuschange'];
$connect->autocommit(FALSE);
		$qx = true;	
		$stmt = $connect->prepare("UPDATE `loss_data_doc` SET `approved_status` = ? , movestatus_date = now() WHERE `loss_data_doc_id` = ? ");
		if ($stmt) {
			$stmt->bind_param('ii',$approved_statuschange,$lossid);
			$q = $stmt->execute();
			$qx = ($qx and $q);	

			if ($qx) {
				savelog('loss-approvedstatuschange-list');
				$connect->commit();	
				echo "<script>  alert('ระบบได้เปลี่ยนสถานะรายการเรียบร้อยแล้ว ');  </script>  ";	
			} else {
				$connect->rollback();
			}
		} else {
			echo 'x'.$connect->error;
		}
if($approved_statuschange=='3'){
	$connect->autocommit(FALSE);
		$qx = true;	
		$stmt = $connect->prepare("UPDATE `loss_data_doc` SET `approved_status` = ? , approved_date = now() WHERE `loss_data_doc_id` = ? ");
		if ($stmt) {
			$stmt->bind_param('ii',$approved_statuschange,$lossid);
			$q = $stmt->execute();
			$qx = ($qx and $q);	
			if ($qx) {
				savelog('loss-approvedstatus-list');
				$connect->commit();	
			} else {
				$connect->rollback();
			}
		} else {
			echo 'x'.$connect->error;
		}
}	
		
}

if($mark_statuschange!=''){
$lossid=$_POST['lossid'];
$loss_have_statuschange=$_POST['loss_have_statuschange'];
$connect->autocommit(FALSE);
		$qx = true;	
		$stmt = $connect->prepare("UPDATE `loss_data_doc` SET `loss_have` = ? WHERE `loss_data_doc_id` = ? ");
		if ($stmt) {
			$stmt->bind_param('ii',$loss_have_statuschange,$lossid);
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

<?  if ($submit_bt=='saveapprove'){
	$approve_id=$_POST['head_code1'];
	$approve_name=$_POST['head_code1_name'];
	$lossid=$_POST['lossid'];
	
	$sql2="SELECT *	 FROM user where code ='$approve_id' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {
		$approve_name =$row2['name']." ".$row2['surname'];
	}
	$qx = true;	
	mysqli_autocommit($connect,FALSE);
	$sql="UPDATE `loss_data_doc` SET
	`approve_id` = '$approve_id',
	`approve_name` = '$approve_name'	
	WHERE loss_data_doc_id = '$lossid';";

$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);
	if ($qx) {
	mysqli_commit($connect);
	
	savelog('loss-UPDATEapprove-data');
	}	else {
		mysqli_rollback($connect);			
		echo '<div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div>';}	
}?>




<script src="dist/js/bootstrap.min.js"></script>
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="dist/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="dist/js/jquery.dataTables.js"></script>
<link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css">
<link href="assets/layouts/layout/css/layout.css" rel="stylesheet" type="text/css">
<link href="assets/layouts/layout/css/themes/light.css" rel="stylesheet" type="text/css" id="style_color">
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
<? if($edit_id=='') { ?>
	<form method='post'  action='loss_data_adminmanage.php' enctype="multipart/form-data">
		<div class='row' >
			<div class="col-lg-3"></div>
			<div class="col-lg-12">[หน้า ADMIN]
			<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V3.1 - 06/01/65</label>
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

	
		

<? }?>


<?/* form */ ?>

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
										<td width='5px' align='center'><b>ลำดับ</b> </td>
										<td width='3%' align='center'><b>รหัส</b> </td>
										<td width='20%' align='center'><b>รายชื่อฝ่าย</b> </td>
										<td width='15%' align='center'> <b>ผลการรายงาน</b></td>
										<td width='10%' align='center'> <b>รายงานผลโดย</b></td>
										<td width='10%' align='center'> <b>ผู้อนุมัติ</b></td>
										<td width='10%' align='center'> <b>วันที่ส่งขอคำขออนุมัติ</b></td>
										<td width='10%' align='center'> <b>สถานะ</b></td>
										<td width='10%' align='center'> <b>วันที่อนุมัติ</b></td>
										<td width='2%' align='center'> <b>จำนวนรายการ</b></td>
										<td width='15%' align='center'> <b>ปรับสถานะ</b></td>									
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
					<td><b><? $department_id = $row1['department_id']; echo	$row1['department_name'];?></b> </td>

					<?  $sql25 =  "SELECT  loss_data_doc.* ,count(loss_data_report.loss_data_doc_id) as num ,loss_data_doc.loss_data_doc_id as lossid FROM loss_data_doc  left join loss_data_report on  `loss_data_report`.`loss_data_doc_id` = `loss_data_doc`.`loss_data_doc_id`
					where `loss_data_doc`.`loss_dep` =  $department_id and loss_data_doc.loss_year = '$yearsearch' and  loss_data_doc.loss_data_doc_month='$loss_data_doc_month_search' ";
					$result25 = mysqli_query($connect, $sql25);
					$row25 = mysqli_fetch_array($result25);
					{$loss_have = $row25['loss_have'];
					$loss_user = $row25['loss_user'];
					$lossid = $row25['lossid'];?>
					<td align='left'><?
					if($loss_have =='0') {echo "ไม่มีเหตุการณ์ความเสียหาย  ";}
					if ($loss_have =='1') { echo"<font color='red'>มีเหตุการณ์ความเสียหาย  </font>";}
					if ($loss_have =='') {echo "<font color='blue'>ยังไม่ได้รายงาน </font>";}?>
					
					<form method='post'  action='loss_data_adminmanage.php' enctype="multipart/form-data">
					<input type="hidden"  name="lossid"  value='<?=$lossid?>'>
					<select  class='form-control' id='loss_have_statuschange'  name='loss_have_statuschange'>
										<option value='0' <?if($loss_have=='0'){echo "selected";}?>>ไม่มีเหตุการณ์ความเสียหาย</option>
										<option value='1' <?if($loss_have=='1'){echo "selected";}?>>มีเหตุการณ์ความเสียหาย</option>
					</select>
					<button type="submit" name='mark_statuschange'  value='1' class="btn btn-info btn-block">  ok</button>
					</form>
					<?}?>
					
					</td>		
					<td><?	$sql2="SELECT name,surname FROM user WHERE user_id = '$loss_user' ";
											$result2=mysqli_query($connect, $sql2);
											if ($row2 = mysqli_fetch_array($result2)) {
											echo $row2['name']."  ".$row2['surname'];}?></td>		
					<td>
					<form method='post'  action='loss_data_adminmanage.php' enctype="multipart/form-data">
					<div class="col-md-12">
					<?=$row25['approve_name']?>
					<input type="text" required value="<?=$row25['approve_id']?>" class="form-control" maxlength='5' minlength='5' name="head_code1" >		
					<button type='submit' name='submit_bt' value='saveapprove'  class="btn btn-info btn-block"> บันทึก</button></div>
					<input type='hidden' name='lossid' value='<?=$lossid;?>'></form></td>
					
					
					<td><?=mysqldate2th_date($row25['approved_sentdate'])?></td>
					<td><?if( $row25['approved_status']=='2'){echo "อยู่ระหว่างการรออนุมัติ";}?>
						<?if( $row25['approved_status']=='3'){echo "อนุมัติแล้ว";}?>
						<?if( $row25['approved_status']=='4'){echo "ไม่อนุมัติ";}?></td>
					<td><?=mysqldate2th_date($row25['approved_date'])?></td>
					<td><?=$row25['num']?></td>
					<td><form method='post'  action='loss_data_adminmanage.php' enctype="multipart/form-data">
					<input type="hidden"  name="lossid"  value='<?=$lossid?>'>
					<select  class='form-control' id='approved_statuschange'  required name='approved_statuschange'>
										<option value='1' <?if( $row25['approved_status']=='1'){echo "selected";}?>  >อยู่ระหว่างการรายงาน</option>
										<option value='2' <?if( $row25['approved_status']=='2'){echo "selected";}?>  >อยู่ระหว่างการรออนุมัติ</option>
										<option value='3' <?if( $row25['approved_status']=='3'){echo "selected";}?>  >อนุมัติแล้ว</option>
										<option value='4' <?if( $row25['approved_status']=='4'){echo "selected";}?>  >ไม่อนุมัติ</option>
								</select>
								<button type="submit" name='mark_change'  value='1' class="btn btn-info btn-block">  ok</button>
					</form></td>
					
					<? }?>
					</tr>

								 </tbody>
					 </table>
			  		</div>
					
					
					
				
					
<?}?>
<?
echo template_footer();
?>
									