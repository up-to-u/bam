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
  
  <div id="printableArea">
	<center>
	<b>ทะเบียนการรายงานเหตุการณ์ความผิดปกติ (Incendent) 
	<?php
		if($yearsearch!=''){
			echo "ประจำปี ".$yearsearch;
		}
	?>
	</b>
	</center>
	<hr>
	<table class='table table-striped' >
		<thead>
			<tr class="info">
				<td width='5px' align='center'><b>ลำดับ</b> </td>
				<td width='250px' align='center'><b>รายชื่อฝ่าย</b> </td>
				<td width='90px' align='center'><b>ม.ค.  </b></td>
				<td width='90px' align='center'><b>ก.พ.</b></td>
				<td width='90px' align='center'><b>มี.ค</b></td>
				<td width='90px' align='center'><b>เม.ย.</b></td>
				<td width='90px' align='center'><b>พ.ค.</b></td>
				<td width='90px' align='center'><b>มิ.ย.</b></td>
				<td width='90px' align='center'><b>ก.ค.</b></td>
				<td width='90px' align='center'><b>ส.ค.</b></td>
				<td width='90px' align='center'><b>ก.ย.</b></td>
				<td width='90px' align='center'><b>ต.ค.</b></td>
				<td width='90px' align='center'><b>พ.ย.</b></td>
				<td width='90px' align='center'><b>ธ.ค.</b></td>
				<td width='10px' align='center'><b>รวม</b></td>
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
		$sql_list="SELECT department_id,loss_data_doc_month,loss_create,loss_have,status_lossapprove
		FROM loss_data_doc LEFT JOIN department ON department.department_id = loss_data_doc.loss_dep
		WHERE department.mark_del = '0' and	loss_data_doc.loss_year = '$yearsearch'
		ORDER BY is_branch,department_id,loss_data_doc_month";
		$result_list=mysqli_query($connect, $sql_list);
		$loss_data_list = [];
		$loss_data_total['all'] = 0;
		while ($row_list = mysqli_fetch_array($result_list)){
			$loss_data_list[$row_list['department_id']][$row_list['loss_data_doc_month']] = [ 'loss_create' => $row_list['loss_create'], 'approved_date' => $row_list['approved_date'], 'loss_have' => $row_list['loss_have'],'status_lossapprove' => $row_list['status_lossapprove']];
			// รวมฝ่าย
			if($loss_data_total['department'][$row_list['department_id']]){
				$loss_data_total['department'][$row_list['department_id']] += 1;
			}else{
				$loss_data_total['department'][$row_list['department_id']] = 1;
			}
			
			// รวมเดือน
			if($loss_data_total['month'][$row_list['loss_data_doc_month']]){
				$loss_data_total['month'][$row_list['loss_data_doc_month']] += 1;
			}else{
				$loss_data_total['month'][$row_list['loss_data_doc_month']] = 1;
			}
			
			// รวมทั้งหมด
			$loss_data_total['all'] += 1;
		}
		
		$num = 0;
		foreach($department_list as $dep_id => $department_list2){
			
			foreach($department_list2 as $dep_id2 => $dep_name){
			$num++;
		?>
			<tr>
				<td width='10px' align='center'><?php echo $num; ?></td>
				<td width='200px' align='left'><b><?php echo $dep_name; ?></b></td>
				<?php 
				$i=1;
				for ($i=1; $i<=12; $i++) {
					$loss_data = $loss_data_list[$dep_id2][$i];	
					
					if ($loss_data['status_lossapprove']=='1')
					{
						$img ='<font color="green"><span class="glyphicon glyphicon-ok"></span></font>';
					} else {
						$img ='<font color="red"><span class="glyphicon glyphicon-remove"></span></font>';
					}
				?>
					<td width='90px' bgcolor='<?=$color_bg;?>' align='center'>
						<p style="font-size:12px">
						<?php
							echo $img;
						?>
						</p>
					</td>
				<?php
				}
				?>
				<td align='center'>
				<?php echo number_format($loss_data_total['department'][$dep_id2]); ?>
				</td>
		<?php
			}
		}
		?>		
			</tr>
			<tr>
				<td colspan='2' align='center'><b>รวม</b></td>
				<?php
				$i=1;
				for ($i=1; $i<=12; $i++)
				{
				?>
					<td align='center'>
					<?php echo number_format($loss_data_total['month'][$i]); ?>
					</td>
				<?php
				}
				?>
				<td align='center'><b><?php echo number_format($loss_data_total['all']); ?></b></td>	
			</tr>
		</tbody>
	 </table>
</div>
</div>
<?}?>
<?}?>

<?/* form */ ?>

<?
echo template_footer();
?>							
