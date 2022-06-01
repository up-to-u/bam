<?
	include('inc/include.inc.php');
	echo template_header();

	$month_id=$_POST['month_id'];
	$month_year=$_POST['month_year'];
	$submit_bt=$_POST['submit_bt'];

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

			$sql = "INSERT INTO loss_time (month_time_id,month_year_id) VALUES ('$month_id','$month_year') ";
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
}}

?>



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

<form method='post'  action='loss_data_admin.php' enctype="multipart/form-data">
<div class='row' >

<div class="col-lg-3"></div>
<div class="col-lg-12">[หน้า ADMIN] <label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1.1 - 30/05/65</label>
 <div class='well' >
 <div class='row'>
 <div class='col-md-12' > <b>กำหนดสิทธิการเข้ารายงานเหตุการณ์ประจำเดือน </b></div>
 <div class='col-md-12' style='padding-top:10px'>
				<?
				$sql12="SELECT
				month.*,
				month.month_id as mid ,loss_time.month_time_id as sid
				FROM month
				LEFT JOIN loss_time ON
				loss_time.month_time_id =  month.month_id
				ORDER BY month.month_id ";
				$result12=mysqli_query($connect, $sql12);
				while ($row12 = mysqli_fetch_array($result12)) {
				echo $yid = $row12['yid'];?>
				<div class='col-md-3'><label><input type="checkbox" name='month_id[]' value='<?=$row12['month_id']?>'<?if ($row12['sid'] == $row12['month_id'] ) echo 'checked'?>>
				<?=$row12['month_name']?></label> </div>

				<? }  ?>  
				</div>
<div class='col-md-12' > <hr><b>กำหนดสิทธิการเข้ารายงานเหตุการณ์ประจำปี  </b></div>
				<div class='col-md-12' style='padding-top:10px'>
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
				<select name='month_year' class="form-control" >
				<option value='<?=$month_year-2?>' <?if($yid2==$month_year-2){echo "selected";}?>><?=$month_year-2?></option>
				<option value='<?=$month_year-1?>' <?if($yid2==$month_year-1){echo "selected";}?>><?=$month_year-1?></option>
				<option value='<?=$month_year?>' <?if($yid2==$month_year){echo "selected";}?> ><?=$month_year?></option>
				<option value='<?=$month_year+1?>' <?if($yid2==$month_year+1){echo "selected";}?>><?=$month_year+1?></option>
				<option value='<?=$month_year+2?>' <?if($yid2==$month_year+2){echo "selected";}?> ><?=$month_year+2?></option>
				</select>
				</div>

<div class='col-md-12'  style='padding-top:20px;'><button type='submit' name='submit_bt' value='savemonth' class="btn btn-primary btn-block">บันทึก</button></div>

  </div>
 </div>
 
<?
echo template_footer();
?>
									