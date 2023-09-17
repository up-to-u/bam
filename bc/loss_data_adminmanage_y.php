<?
	include('inc/include.inc.php');
	check_permission(2, true);
	echo template_header();
	$submit_bt = $_POST['submit_bt'];

	$loss_data_doc_month_search = $_POST['loss_data_doc_month_search'];
	$yc=$_POST['yc'];
	$month_year_search=date('Y')+543;
$yearsearch=$_POST['yearsearch'];
	if ($yearsearch=='') {
	$yearsearch=date('Y')+543;
	}



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
	<form method='post'  action='loss_data_adminmanage_y.php' enctype="multipart/form-data">
		<div class='row' >
			<div class="col-lg-3"></div>
			<div class="col-lg-12">[หน้า ADMIN]
			<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 14/07/65</label>

				<b><center>สรุปเหตุการณ์ความเสียหาย (Loss data) ประจำปี  <?=$yearsearch?></center></b><hr>
	 
					<div id='ask'>
						<div class='row' >
							<input type='hidden' name='dep_id' value='<?=$dep_id?>'>
							
								
								<div class='col-md-2'style='padding-top:5px;'>
								
								
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
									</div></div></div></div></div>
	</form>
<hr></div>			
<? if( $yearsearch!=''){?>
<div id="printableArea">
<b>หมายเหตุ : </b>
<font color="red"><span class="glyphicon glyphicon-remove"></span></font> อยู่ระหว่างรออนุมัติ  <font color="green"><span class="glyphicon glyphicon-ok"></span></font> อนุมัติแล้ว <hr>
  <table  class='table table-striped' >
								<thead>
									<tr  class="info">
										<td width='5px' align='center'><b>ลำดับ</b> </td>
										<td width='250px' align='center'><b>รายชื่อฝ่าย</b> </td>
										<td width='90px' align='center'> <b>ม.ค.  </b></td>
										<td width='90px'align='center'><b>ก.พ.</b></td>
										<td width='90px'align='center'><b>มี.ค</b></td>
										<td width='90px' align='center'><b>เม.ย.</b></td>
										<td width='90px'align='center'><b>พ.ค.</b></td>
										<td width='90px'align='center'><b>มิ.ย.</b></td>
										<td width='90px'align='center'><b>ก.ค.</b></td>
										<td width='90px'align='center'><b>ส.ค.</b></td>
										<td width='90px'align='center'><b>ก.ย.</b></td>
										<td width='90px'align='center'><b>ต.ค.</b></td>
										<td width='90px'align='center'><b>พ.ย.</b></td>
										<td width='90px'align='center'><b>ธ.ค.</b></td>
									</tr>
								</thead>
								  <tbody>
								    </tbody>
									
									<?	$sql1="SELECT * FROM department where mark_del !='1'  AND department_level_id = '4' ORDER BY `department`.`is_branch` ASC   ";
					$result1=mysqli_query($connect, $sql1);
					$j = 1;
					while ($row1 = mysqli_fetch_array($result1)){	
					?>
					<tr>
					<td width='10px' align='center'> <?=$j; $j++;?></td>
					<td width='200px' align='left'><b><? $department_id = $row1['department_id']; echo	$row1['department_name'];?></b> </td>
					<? $i=1;
					for ($i=1; $i<=12; $i++) { ?>

					<?   $sqls = "SELECT  *   FROM loss_data_doc 
					where `loss_data_doc`.`loss_data_doc_month` = $i and `loss_data_doc`.`loss_dep` =  $department_id  and loss_data_doc.loss_year = '$yearsearch' ";
					
					
						$results = mysqli_query($connect, $sqls);
						$rows = mysqli_fetch_array($results) ;
					 $loss_have = $rows['loss_have'];
					 if   ($loss_have =='1' or $loss_have =='0'){ $color_bg='#effafd'; } else { $color_bg='white'; } ?>
					
					<td width='90px'  align='center'><p style="font-size:12px">
					<?    $sql2 =  "SELECT status_lossapprove  FROM loss_data_doc 
					where `loss_data_doc`.`loss_data_doc_month` = $i and `loss_data_doc`.`loss_dep` =  $department_id and loss_data_doc.loss_year = '$yearsearch' ";
					$result2 = mysqli_query($connect, $sql2);
					$row2 = mysqli_fetch_array($result2);
					
					{ $status_lossapprove = $row2['status_lossapprove'];}?>
					<? if($status_lossapprove=='1'){ echo'<font color="green"><span class="glyphicon glyphicon-ok"></span></font>';}?>
					<? if($status_lossapprove=='0'){ echo'<font color="red"><span class="glyphicon glyphicon-remove"></span></font>';}?>
					</p></td>
					<?}?>
										
									
								
									</tr>
					<?}?>
					
								
					 </table>
			  		</div>
<?}?>
<?}?>

<?/* form */ ?>

<?
echo template_footer();
?>							
