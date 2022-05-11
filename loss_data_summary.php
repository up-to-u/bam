<script language="javascript">
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

<?
	include('inc/include.inc.php');
	echo template_header();
	$submit_bt = $_POST['submit_bt'];
	$show_id = $_GET['show_id'];
	$edit_id = $_GET['edit_id'];
	$loss_data_doc_month_search = $_POST['loss_data_doc_month_search'];
	
	
$p_list = array();
$p_name = array();
$sql2="SELECT *	FROM loss_impact where 	loss_impact_parent = 'fix' ";
$result2=mysqli_query($connect, $sql2);
while ($row2 = mysqli_fetch_array($result2)) {
	
	$p_list[$row2['loss_impact_value']] = 0;
	$p_name[$row2['loss_impact_value']] = $row2['loss_impact_name'];
	
}
	
?>



<?

$month_id=$_POST['month_id'];
if ($submit_bt =='savemonth'){
$qx = true;	
	mysqli_autocommit($connect,FALSE);
 	$sql = "DELETE FROM loss_time ";
	$q = mysqli_query($connect, $sql);
	$qx = ($qx and $q);	
	
	if ($qx) {
			mysqli_commit($connect);		
		
		} else {
			mysqli_rollback($connect);			
			}	
		
		
	
	if (count($month_id)>0) {		
		foreach ($month_id as $month_id) {	

		$sql = "INSERT INTO loss_time (month_time_id) VALUES ('$month_id') ";
		$q = mysqli_query($connect, $sql);
		$qx = ($qx and $q);

}


	if ($qx) {
			mysqli_commit($connect);		
			echo '<div class="container"><b><div class="alert alert-success">เปิดรายงานเดือนดังกล่าวเรียบร้อยแล้ว</div></b><br></div>';
		} else {
			mysqli_rollback($connect);			
			echo '<div class="container"><b><div class="alert alert-danger">เกิดข้อผิดพลาด ระบบไม่สามารถบันทึกข้อมูลได้</div></b><br></div>';
		}	
		

	}		
		
		
}

?><script src="dist/js/bootstrap.min.js"></script>
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
 <script type="text/javascript" src="js/loader.js"></script>

<script language='JavaScript'>
	$(document).ready(function() {
		$('.datepicker').daterangepicker({
			
			singleDatePicker: true,
			showDropdowns: true, 
			locale: {      format: 'YYYY-MM-DD'
			}
		});		
	});
</script>


<script language="JavaScript">
	function chk(){
		
		var p1=parseFloat(document.frm.P1.value);
		var p2=parseFloat(document.frm.P2.value);
		var p3=parseFloat(document.frm.P3.value);
		var p4=parseFloat(document.frm.P4.value);
		var p5=parseFloat(document.frm.P5.value);
		var o1=parseFloat(document.frm.O1.value);
		
		var maxP =  Math.max(p1,p2,p3,p4,p5);
		document.frm.C.value=o1+maxP; //---- เปลี่ยนเอาจะ + - * /
		
	}
</script>


<style>

/* print styles */
@media print {
  a[href]:after { content: none !important; }
  img[src]:after { content: none !important; }

.btn{display:none;}
.t2 {
  text-decoration-line: underline;
  text-decoration-style: dashed;
  text-decoration-color: #555;	
}

table.tb1 tr td{
	font-size: 5px;
}
table.tb2 tr td{
	font-size: 5px;
	vertical-align :middle;
}



}



</style>


<style>
a{text-decoration:none;
color: black;}
</style>

	<div class='row' >

        		
<div id="printableArea"><center><H1>สรุปผลการรายงานเหตุการณ์ความผิดปกติ (Incendent)</H1></center>
<hr>
<table  class='tb1 table ' border="1"  >
								<thead>
									<tr  style="background-color: #c5e9f2">
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
										<td width='10px'align='center'><b>รวม</b></td>
									</tr>
								</thead>
								  <tbody>
								    </tbody>
									
									<?	$sql1="SELECT *	FROM loss_impact where 	loss_impact_parent = 'fix'  ";
					$result1=mysqli_query($connect, $sql1);
					$j = 1;
					while ($row1 = mysqli_fetch_array($result1)){	
					?>
					<tr>
					<td width='10px' align='center'> <?=$j; $j++;?></td>
					<td width='200px' align='left'><b><? $loss_impact_value = $row1['loss_impact_value']; echo	$row1['loss_impact_name'];?></b> </td>
					<? $i=1;
					for ($i=1; $i<=12; $i++) { ?>

					<?   $sqls = "SELECT COUNT(*) AS num  FROM loss_data_report  join loss_data_doc on  `loss_data_doc`.`loss_data_doc_id` = `loss_data_report`.`loss_data_doc_id`
						join   month on month.month_id = loss_data_doc.loss_data_doc_month
						WHERE loss_data_report.loss_data_status = '$loss_impact_value' and  loss_data_doc_month = '$i'";
						$results = mysqli_query($connect, $sqls);
						$rows = mysqli_fetch_array($results) ; ?>
					<td align='center'><?=$rows['num'];?></td><?}?>
					
					
					<td align='center'> <?  $sql25 = "SELECT COUNT(*) AS num  FROM loss_data_report  join loss_data_doc on  `loss_data_doc`.`loss_data_doc_id` = `loss_data_report`.`loss_data_doc_id`
						join   month on month.month_id = loss_data_doc.loss_data_doc_month
						WHERE loss_data_report.loss_data_status = '$loss_impact_value'  ";
					$result25 = mysqli_query($connect, $sql25);
					$row25 = mysqli_fetch_array($result25);{echo $row25['num'];}?></td><? }?>
								
					
					
					</tr><td colspan='2' align='center'><b>รวม</b></td>
									<? $i=1;
					for ($i=1; $i<=12; $i++) { ?>
					<td align='center'><?   $sql2 =  "SELECT COUNT(*) AS num  FROM loss_data_report  join loss_data_doc on  `loss_data_doc`.`loss_data_doc_id` = `loss_data_report`.`loss_data_doc_id`
						join   month on month.month_id = loss_data_doc.loss_data_doc_month
						WHERE  loss_data_doc_month = '$i' ";
					$result2 = mysqli_query($connect, $sql2);
					$row2 = mysqli_fetch_array($result2);
					
					{echo $row2['num'];}?></td>
					<?}?>
					<td colspan='2' align='center'><b>
					
					<?  $sql125 =  "SELECT COUNT(*) AS num  FROM loss_data_report   ";
					$result125 = mysqli_query($connect, $sql125);
					$row125 = mysqli_fetch_array($result125);
					
					{echo $row125['num'];}?>
					</b></td>		
					 </table>
			  		</div>
					
					
					<div class="col-lg-12">
                            
                                      <center>  <b>ระบบการบันทึกข้อมูลรายงานเหตุการณ์ความผิดปกติ </b></center> <hr>
										
								 <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['เดือน', 'จำนวนที่พบเหตุการณ์ความผิดปกติ (รายการ)'],
		  
		  
		  <? $i=1;
					for ($i=1; $i<=12; $i++) { ?>

					<?  $sqls = "SELECT COUNT(*) AS num  FROM loss_data_report  join loss_data_doc on  `loss_data_doc`.`loss_data_doc_id` = `loss_data_report`.`loss_data_doc_id`
						join   month on month.month_id = loss_data_doc.loss_data_doc_month
						WHERE  loss_data_doc_month = '$i'";
						$results = mysqli_query($connect, $sqls);
						$rows = mysqli_fetch_array($results) ; ?>
				['<?  $sql25 = "SELECT * FROM month WHERE month.month_id = '$i'  ";
					$result25 = mysqli_query($connect, $sql25);
					$row25 = mysqli_fetch_array($result25);{echo $row25['month_name'];}?>',<?=$rows['num'];?>],<?}?>
          
         
        ]);

        var options = {
          title: '',
          hAxis: {title: '',  titleTextStyle: {color: '#000000'}},
          vAxis: {minValue: 0},
		  areaOpacity:0.3,
		  chartArea: {left:0,top:40,bottom:40,right:10, width:"100%",height:"350"},
		  legend : {position: 'top', textStyle: {color: 'black', fontSize: 13}}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div20'));
        chart.draw(data, options);
      }
    </script>
	  <div id="chart_div20"style="width: 100%; height: 400px;"></div>

                                </div>
								
					</div>
<?
echo template_footer();
?>
									