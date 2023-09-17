<?
include('inc/include.inc.php');
echo template_header();
$today =date("Y-m-d");
$action = $_GET['action'];
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


$sqlCountChart = "SELECT count(loss_data_doc_list_id) as numChart FROM loss_data_doc_list";
$resultCountChart = mysqli_query($connect, $sqlCountChart);
$rowCountChart = mysqli_fetch_array($resultCountChart);
$numCountChart = $rowCountChart['numChart']+10;


?>
<style>
	.margins-3 {
		margin-top: 15px;
		margin-bottom: 15px;
	}

	.margins-top-10 {
		margin-top: 10px;
	}
</style>



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

<script src='js/plotly-2.12.1.min.js'></script>
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

	.panel-default>.panel-heading {
		color: #ffffff;
		background-color: #004c85;
		border-color: #ddd;
	}

	a {
		font-family: 'Prompt', sans-serif !important;
	}
	.centered-element {
  margin: 0;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
}
</style>
<link rel="stylesheet" href="dist/css/bootstrap-select.css">
<!-- <script src="dist/js/bootstrap-select.js"></script> -->
<link href="./jquery-ui-1.12.0/jquery-ui.css" rel="stylesheet">
<script src="./jquery-ui-1.12.0/jquery-ui.js"></script>

<!--<script src="js/loader-chart.js"></script>-->
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
	$(function() {

		$(".r1").mousedown(function() {
			$(this).attr('previous-value', $(this).prop('checked'));
		});

		$(".r1").click(function() {
			var previousValue = $(this).attr('previous-value');
			if (previousValue == 'true')
				$(this).prop('checked', false);
		});
	});
</script>

<div class="row">
	<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 31/05/65</label>
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<p class="caption-subject font-green sbold uppercase">ทะเบียนสรุปรายงานประจำปี</p>

					<span class="caption-helper"> <select name='searchYear' id='searchYear' class="form-control">
							<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
							<?php
							$year = Date("Y");
							for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
								<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?></option>";
							<?php  } ?>
						</select><br>
				</div></span>
			</div>
			<!----------chart 1--------->

			<div id="columnchart_values" style="min-height: 500px;"></div>
			<!---------end chart 1 ----->
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">ทะเบียนสรุปรายงานประจำเดือน</span>
					<span class="caption-helper"></span>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-2">
					<select name='searchMonths' id='searchMonths' class="form-control">
						<option value="0">- - - กรุณาเลือกเดือน - - -</option>

						<? $sql1 = "SELECT *	FROM month ";
						$result1 = mysqli_query($connect, $sql1);
						while ($row1 = mysqli_fetch_array($result1)) {	?>
							<option value='<?= $row1['month_id'] ?>'><?= $row1['month_name'] ?></option>
						<?	} ?>
					</select>
				</div>
				<div class="col-lg-2">
					<select name='searchYears' id='searchYears' class="form-control">
						<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
						<?php
						$year = Date("Y");
						for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
							<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?></option>";
						<?php  } ?>
					</select>
				</div>
			</div>


			<!-- strat main contain -->
			<div class="row">
				<div class="col-lg-12">
					<!----------chart 2--------->
					<div id="chart_div" style="width: 100%; height: 500px;"></div>
					<!----------end chart 2--------->
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
					<hr>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 blue">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup"> People<br>
								</span>
								<p id="dmgType1"></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 red">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup"> External Event<br>

								</span>
								<p id="dmgType2"></p>
							</div>

						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 yellow">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">System<br>
								</span>
								<p id="dmgType3"></p>
							</div>

						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 green ">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup"> Process<br>
								</span>
								<p id="dmgType4"></p>
							</div>

						</div>
					</div>
				</div>

				<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
					<hr>
				</div>
				<div class="col-lg-12">
					<!-- chart 4 -->
					<div id="chart_div1" style="width: 100%; height: 500px;"></div>
					<!-- end chart 4 -->
					<hr>
				</div>

				<?

				$sql = "SELECT * FROM loss_factor WHERE parent_id = '2' ORDER BY loss_factor_id ASC";
				$result1 = mysqli_query($connect, $sql);
				$i = 0;
				while ($row = mysqli_fetch_array($result1)) {
					$i++;
				?>

					<div class="col-xs-12 col-lg-12">
						<form action='export_Let.php?' method="post">
							<input type="hidden" name="letID<?= $i; ?>" value="<?= $row['loss_factor_id'] ?>">
							<button type='submit' name='exportLet<?= $i; ?>' value='exportLet<?= $i; ?>' class="btn btn-info btn-lg btn-block " style="margin-bottom: 20px; text-align: left; background-color: <?= setColorBotton($i); ?> ; <?php if($i=='1' || $i=='2'  ){}else{ echo "color:#000000;"; }  ?>border-color: #FFFFFF; "><i class='glyphicon glyphicon-download-alt'></i>&emsp; <?= $row['factor'] ?></button>
						</form>

					</div>
				<? } ?>
				<form action='export_Let.php?' method="post">
					<div class="col-xs-12 col-lg-12">
						<input type="hidden" name="letID8" value="0">
						<button type='submit' name='exportLet8' value='exportLet8' class="btn btn-info btn-lg btn-block " style="margin-bottom: 20px; text-align: left;background-color: #585858;"><i class='glyphicon glyphicon-download-alt'></i>&emsp; ทั้งหมด</button>
					</div>
				</form>
				<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">

					<hr>
				</div>
			</div>
		</div>
		<!--start box bar chart graph 1-->
		<div class="row">
			<div class="col-lg-12 col-lg-12 col-sm-12">
				<div class="portlet light tasks-widget bordered">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-share font-dark hide"></i>
							<p class="caption-subject font-green sbold uppercase">แสดงผลการรายงาน Loss Data เป็นรายปี
							</p>
							<!-- 			
					<span class="caption-helper"> 
						<select name='searchYear' id='searchYear' class="form-control">
							<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
							<?php
							$year = Date("Y");
							for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
								<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?></option>";
							<?php  } ?>
						</select><br>
				</div></span> -->
						</div>
					</div>
					<!-------------->
					<div class="row">
						<div class="col-lg-2">
							<select name='sYearBar' id='sYearBar' class="form-control" style="margin-top: 12px;">
								<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
								<?php
								$year = Date("Y");
								for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
									<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?></option>";
								<?php  } ?>
							</select>
						</div>
						<div class="col-lg-1" align="center">
							<p>ถึง</p>
						</div>

						<div class="col-lg-2">
							<select name='eYearBar' id='eYearBar' class="form-control" style="margin-top: 12px;">
								<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
								<?php
								$year = Date("Y");
								for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
									<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?></option>";
								<?php  } ?>
							</select>
						</div>

					</div>
					<div id="bar_chart" style="width: 100%;"></div>
					<!-------------->
				</div>
			</div>
		</div>
	</div>
	<!--end box bar chart graph 1-->
</div>

<!--start box graph 1-->
<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<p class="caption-subject font-green sbold uppercase">แสดงผลการรายงานความเสี่ยง LET1 - LET7
					</p>
					</p>
				</div>
			</div>
			<!-------------->
			<div class="row">
				<div class="col-lg-2">
					<input type="text" class="form-control datepicker" required name='statDatePie1' readonly id='statDatePie1' style="margin-top: 12px;" value='<?=$today?>'>
				</div>

				<div class="col-lg-1" align="center">
					<p>ถึง</p>
				</div>

				<div class="col-lg-2">
					<input type="text" class="form-control datepicker" required name='endDatePie1' readonly id='endDatePie1' style="margin-top: 12px;" value='<?=$today?>'>
				</div>

				<div class="col-lg-3">
					<select name='searchGroupPie1' id='searchGroupPie1' class="form-control" style="margin-top: 12px;">
						<option value="0">- - - กรุณาเลือกกลุ่มข้อมุลสำหรับการค้นหา - - -</option>
						<option value="1"> ฝ่ายงานทั้งหมด </option>
						<option value="2"> สำนักงานใหญ่ทั้งหมด </option>
						<option value="3"> ภูมิภาคทั้งหมด </option>
					</select>
				</div>
			</div>
			<div id="piechart1" style="width: 100%;"></div>
			<div id="piechart1_show" align="center" style="min-height: 300px; ">
			<br>	<br>	<br>
			<p  id="piechart1_text" style="color:#C11115 ;"></p>
			</div>

			<!-------------->
		</div>
	</div>
</div>
<!--end box graph 1-->
<!--start box graph 2-->
<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<p class="caption-subject font-green sbold uppercase">แสดงผลการรายงานประเภทความเสียหาย</p>
				</div>
			</div>
			<!-------------->
			<div class="row">
				<div class="col-lg-2">
					<input type="text" class="form-control datepicker" required name='statDatePie2' readonly id='statDatePie2' style="margin-top: 12px;" value='<?=$today?>'>
				</div>
				<div class="col-lg-1" align="center">
					<p>ถึง</p>
				</div>

				<div class="col-lg-2">
					<input type="text" class="form-control datepicker" required name='endDatePie2' readonly id='endDatePie2' style="margin-top: 12px;" value='<?=$today?>'>
				</div>

			</div>
			<div id="piechart2" style="width: 100%;"></div>
			<div id="piechart2_show" align="center" style="min-height: 300px; ">
			<br>	<br>	<br>
			<p  id="piechart2_text" style="color:#C11115 ;"></p>
			</div>
			<!-------------->
		</div>
	</div>
</div>
<!--end box graph 2-->
<!--start box graph 3-->
<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<p class="caption-subject font-green sbold uppercase">แสดงผลการรายงานความเสียหาย</p>
				</div>
			</div>
			<!-------------->
			<div class="row">
				<div class="col-lg-2">
					<input type="text" class="form-control datepicker" required name='statDatePie3' readonly id='statDatePie3' style="margin-top: 12px;" value='<?=$today?>'>
				</div>
				<div class="col-lg-1" align="center">
					<p>ถึง</p>
				</div>

				<div class="col-lg-2">
					<input type="text" class="form-control datepicker" required name='endDatePie3' readonly id='endDatePie3' style="margin-top: 12px;" value='<?=$today?>'>
				</div>
			</div>
			<div id="piechart3" style="width: 100%;"></div>
			<div id="piechart3_show" align="center" style="min-height: 300px; ">
			<br>	<br>	<br>
			<p  id="piechart3_text" style="color:#C11115 ;"></p>
			</div>
			<!-------------->
		</div>
	</div>
</div>

<!--end box graph 3-->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	function checkScal($c){
    return $c;
	}
	var checkScall = checkScal(<?=$numCountChart?>);
if(checkScall>10){
	checkScall =checkScal(<?=$numCountChart?>);
}else{
	checkScall =10;
}
	var today = new Date();
	var lastDayOfMonths = new Date(today.getFullYear(), today.getMonth() + 1, 0);
	var conver = lastDayOfMonths.toString().split(" ");
	var dateNew = today.toLocaleDateString("en-US").split("/");
	var currentYearPie = dateNew[2];
	var currentMonthPie = dateNew[0].padStart(2, '0');
	var currentDayPie = dateNew[1].padStart(2, '0');
    var lastDayOfMonth = conver[2];

	var sDate = currentYearPie + "-" + currentMonthPie + "-01";
	var eDate = currentYearPie + "-" + currentMonthPie + "-" + lastDayOfMonth;

	var currentYear = parseInt(dateNew[2]) + 543;

	showChart(currentYear);
	$('#searchYear').on('change', function() {
		showChart(this.value);

	});

	function showChart(y) {

		years = y;
		const dbParam = JSON.stringify({
			"years": years
		});

		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObj = JSON.parse(this.responseText);

			google.charts.load("current", {
				packages: ['corechart']
			});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				var data = google.visualization.arrayToDataTable([
					['Genre', 'LET 1', 'LET 2', 'LET 3', 'LET 4', 'LET 5', 'LET 6', 'LET 7', {
						role: 'annotation'
					}],
					['มกราคม', myObj['object1'][0].count_incidence, myObj['object2'][0].count_incidence, myObj['object3'][0].count_incidence, myObj['object4'][0].count_incidence, myObj['object5'][0].count_incidence, myObj['object6'][0].count_incidence, myObj['object7'][0].count_incidence, ''],
					['กุมภาพันธ์', myObj['object1'][1].count_incidence, myObj['object2'][1].count_incidence, myObj['object3'][1].count_incidence, myObj['object4'][1].count_incidence, myObj['object5'][1].count_incidence, myObj['object6'][1].count_incidence, myObj['object7'][1].count_incidence, ''],
					['มีนาคม', myObj['object1'][2].count_incidence, myObj['object2'][2].count_incidence, myObj['object3'][2].count_incidence, myObj['object4'][2].count_incidence, myObj['object5'][2].count_incidence, myObj['object6'][2].count_incidence, myObj['object7'][2].count_incidence, ''],
					['เมษายน', myObj['object1'][3].count_incidence, myObj['object2'][3].count_incidence, myObj['object3'][3].count_incidence, myObj['object4'][3].count_incidence, myObj['object5'][3].count_incidence, myObj['object6'][3].count_incidence, myObj['object7'][3].count_incidence, ''],
					['พฤษภาคม', myObj['object1'][4].count_incidence, myObj['object2'][4].count_incidence, myObj['object3'][4].count_incidence, myObj['object4'][4].count_incidence, myObj['object5'][4].count_incidence, myObj['object6'][4].count_incidence, myObj['object7'][4].count_incidence, ''],
					['มิถุนายน', myObj['object1'][5].count_incidence, myObj['object2'][5].count_incidence, myObj['object3'][5].count_incidence, myObj['object4'][5].count_incidence, myObj['object5'][5].count_incidence, myObj['object6'][5].count_incidence, myObj['object7'][5].count_incidence, ''],
					['กรกฎาคม', myObj['object1'][6].count_incidence, myObj['object2'][6].count_incidence, myObj['object3'][6].count_incidence, myObj['object4'][6].count_incidence, myObj['object5'][6].count_incidence, myObj['object6'][6].count_incidence, myObj['object7'][6].count_incidence, ''],
					['สิงหาคม', myObj['object1'][7].count_incidence, myObj['object2'][7].count_incidence, myObj['object3'][7].count_incidence, myObj['object4'][7].count_incidence, myObj['object5'][7].count_incidence, myObj['object6'][7].count_incidence, myObj['object7'][7].count_incidence, ''],
					['กันยายน', myObj['object1'][8].count_incidence, myObj['object2'][8].count_incidence, myObj['object3'][8].count_incidence, myObj['object4'][8].count_incidence, myObj['object5'][8].count_incidence, myObj['object6'][8].count_incidence, myObj['object7'][8].count_incidence, ''],
					['ตุลาคม', myObj['object1'][9].count_incidence, myObj['object2'][9].count_incidence, myObj['object3'][9].count_incidence, myObj['object4'][9].count_incidence, myObj['object5'][9].count_incidence, myObj['object6'][9].count_incidence, myObj['object7'][9].count_incidence, ''],
					['พฤศจิกายน', myObj['object1'][10].count_incidence, myObj['object2'][10].count_incidence, myObj['object3'][10].count_incidence, myObj['object4'][10].count_incidence, myObj['object5'][10].count_incidence, myObj['object6'][10].count_incidence, myObj['object7'][10].count_incidence, ''],
					['ธันวาคม', myObj['object1'][11].count_incidence, myObj['object2'][11].count_incidence, myObj['object3'][11].count_incidence, myObj['object4'][11].count_incidence, myObj['object5'][11].count_incidence, myObj['object6'][11].count_incidence, myObj['object7'][11].count_incidence, '']
				]);
				var options = {
					width: '100%',
					height: '100%',
					legend: {
						position: 'top',
						maxLines: 3
					},
					vAxis: {
						title: 'จำนวนเหตุการณ์',
						viewWindow: {
        min: 0,
        max: checkScall
    },
					
					},
					bar: {
						groupWidth: '75%'
					},
					isStacked: true,
					series: {
						0: {
							color: '#276678'
						},
						1: {
							color: '#1687A7'
						},
						2: {
							color: '#D3E0EA'
						},
						3: {
							color: '#AEE1E1'
						},
						4: {
							color: '#D3E0DC'
						},
						5: {
							color: '#ECE2E1'
						},
						6: {
							color: '#FCD1D1'
						},

					}
				};

				var view = new google.visualization.DataView(data);
				var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
				chart.draw(view, options);
			}

		}
		xmlhttp.open("POST", "api/lossDataChart.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);
	}
</script>
<script>
	function months_name($mm) {
		if (currentMonthPie == '1') {
			return monthName = 'มกราคม';
		} else if (currentMonthPie == '2') {
			return monthName = 'กุมภาพันธ์';
		} else if (currentMonthPie == '3') {
			return monthName = 'มีนาคม';
		} else if (currentMonthPie == '4') {
			return monthName = 'เมษายน';
		} else if (currentMonthPie == '5') {
			return monthName = 'พฤษภาคม';
		} else if (currentMonthPie == '6') {
			return monthName = 'มิถุนายน';
		} else if (currentMonthPie == '7') {
			return monthName = 'กรกฎาคม';
		} else if (currentMonthPie == '8') {
			return monthName = 'สิงหาคม';
		} else if (currentMonthPie == '9') {
			return monthName = 'กันยายน';
		} else if (currentMonthPie == '10') {
			return monthName = 'ตุลาคม';
		} else if (currentMonthPie == '11') {
			return monthName = 'พฤศจิกายน';
		} else if (currentMonthPie == '12') {
			return monthName = 'ธันวาคม';
		}
	}
</script>
<!-- chart 2 -->
<script type="text/javascript">
	var currentMonthPie = new Date().getMonth();
	var currentYearPie = new Date().getFullYear() + 543;
	var monthName = months_name(currentMonthPie);

	showChartPie(currentMonthPie, currentYearPie, monthName);
	var currentMonthPies = currentYearPie;
	$('#searchMonths').on('change', function() {
		if (this.value == '1') {
			monthName = 'มกราคม';
		} else if (this.value == '2') {
			monthName = 'กุมภาพันธ์';
		} else if (this.value == '3') {
			monthName = 'มีนาคม';
		} else if (this.value == '4') {
			monthName = 'เมษายน';
		} else if (this.value == '5') {
			monthName = 'พฤษภาคม';
		} else if (this.value == '6') {
			monthName = 'มิถุนายน';
		} else if (this.value == '7') {
			monthName = 'กรกฎาคม';
		} else if (this.value == '8') {
			monthName = 'สิงหาคม';
		} else if (this.value == '9') {
			monthName = 'กันยายน';
		} else if (this.value == '10') {
			monthName = 'ตุลาคม';
		} else if (this.value == '11') {
			monthName = 'พฤศจิกายน';
		} else if (this.value == '12') {
			monthName = 'ธันวาคม';
		}
		showChartPie(this.value, $('#searchYears').val(), monthName);

	});

	$('#searchYears').on('change', function() {
		if (this.value == '1') {
			monthName = 'มกราคม';
		} else if (this.value == '2') {
			monthName = 'กุมภาพันธ์';
		} else if (this.value == '3') {
			monthName = 'มีนาคม';
		} else if (this.value == '4') {
			monthName = 'เมษายน';
		} else if (this.value == '5') {
			monthName = 'พฤษภาคม';
		} else if (this.value == '6') {
			monthName = 'มิถุนายน';
		} else if (this.value == '7') {
			monthName = 'กรกฎาคม';
		} else if (this.value == '8') {
			monthName = 'สิงหาคม';
		} else if (this.value == '9') {
			monthName = 'กันยายน';
		} else if (this.value == '10') {
			monthName = 'ตุลาคม';
		} else if (this.value == '11') {
			monthName = 'พฤศจิกายน';
		} else if (this.value == '12') {
			monthName = 'ธันวาคม';
		}
		showChartPie($('#searchMonths').val(), this.value, monthName);

	});

	function showChartPie(m, y, nMonth) {
		monthPie = m;
		yearPie = y;
		monthNamePie = nMonth;
		const dbParam = JSON.stringify({
			"monthPie": monthPie,
			"yearPie": yearPie
		});

		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObj = JSON.parse(this.responseText);



			google.charts.load('current', {
				'packages': ['corechart']
			});
			google.charts.setOnLoadCallback(drawVisualization);

			function drawVisualization() {

				var data = google.visualization.arrayToDataTable([
					['เดือน', 'LET1', 'LET2', 'LET3', 'LET4', 'LET5', 'LET6', 'LET7'],
					[monthNamePie + ' ' + yearPie, myObj['object1'][0].count_incidence,
						myObj['object2'][0].count_incidence,
						myObj['object3'][0].count_incidence,
						myObj['object4'][0].count_incidence,
						myObj['object5'][0].count_incidence,
						myObj['object6'][0].count_incidence,
						myObj['object7'][0].count_incidence
					]
				]);

				var options = {
					title: 'สรุปรายงานประจำเดือนแบ่งตามประเภทเหตุการณ์ความเสียหาย',
					vAxis: {
						title: 'จำนวนเหตุการณ์',
						viewWindow: {
        min: 0,
        max: checkScall
    },
					},
					

					seriesType: 'bars',
					series: {
						7: {
							type: 'line'
						}
					},
					series: {
						0: {
							color: '#276678'
						},
						1: {
							color: '#1687A7'
						},
						2: {
							color: '#D3E0EA'
						},
						3: {
							color: '#AEE1E1'
						},
						4: {
							color: '#D3E0DC'
						},
						5: {
							color: '#ECE2E1'
						},
						6: {
							color: '#FCD1D1'
						},



					}
				};

				var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
			//chart 3 

			document.getElementById("dmgType1").innerHTML = myObj['objectDmgType1'][0].dmgType;
			document.getElementById("dmgType2").innerHTML = myObj['objectDmgType2'][0].dmgType;
			document.getElementById("dmgType3").innerHTML = myObj['objectDmgType3'][0].dmgType;
			document.getElementById("dmgType4").innerHTML = myObj['objectDmgType4'][0].dmgType;

			// end chart 3 

			// chart 4 

			google.charts.load('current', {
				'packages': ['corechart']
			});
			google.charts.setOnLoadCallback(drawVisualization4);

			function drawVisualization4() {
				// Some raw data (not necessarily accurate)
				var data = google.visualization.arrayToDataTable([
					['เดือน', 'ต่ำ', 'ปานกลาง', 'สูง', 'สูงมาก'],
					[monthNamePie + ' ' + yearPie, myObj['objectDisplayRisk1'][0].dispRisk,
						myObj['objectDisplayRisk2'][0].dispRisk,
						myObj['objectDisplayRisk3'][0].dispRisk,
						myObj['objectDisplayRisk4'][0].dispRisk
					]
				]);

				var options = {
					title: 'สรุปรายงานประจำเดือนแบ่งตามความเสี่ยง',
					vAxis: {
						title: 'จำนวนเหตุการณ์',
						viewWindow: {
        min: 0,
        max: checkScall
    },
					},

					seriesType: 'bars',
					series: {
						6: {
							type: 'line'
						}
					},
					series: {
						0: {
							color: '#00B050'
						},
						1: {
							color: '#FFFF00'
						},
						2: {
							color: '#FF9400'
						},
						3: {
							color: '#FF0000'
						},

					}
				};

				var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
				chart.draw(data, options);
			}

			// end chart 4 

		}

		xmlhttp.open("POST", "api/lossDataChart2.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);



	}
</script>
<!-- end chart 2 -->
<script>
	function month_name($m) {
		switch ($m) {
			case 1:
				return 'มกราคม';
			case 2:
				return 'กุมภาพันธ์';
			case 3:
				return 'มีนาคม';
			case 4:
				return 'เมษายน';
			case 5:
				return 'พฤษภาคม';
			case 6:
				return 'มิถุนายน';
			case 7:
				return 'กรกฎาคม';
			case 8:
				return 'สิงหาคม';
			case 9:
				return 'กันยายน';
			case 10:
				return 'ตุลาคม';
			case 11:
				return 'พฤศจิกายน';
			case 12:
				return 'ธันวาคม';
		}
	}
</script>
<script type="text/javascript">
	function showChartPie(m, y, nMonth) {
		monthPie = m;
		yearPie = y;
		monthNamePie = nMonth;
		const dbParam = JSON.stringify({
			"monthPie": monthPie,
			"yearPie": yearPie
		});

		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObj = JSON.parse(this.responseText);



			google.charts.load('current', {
				'packages': ['corechart']
			});
			google.charts.setOnLoadCallback(drawVisualization);

			function drawVisualization() {

				var data = google.visualization.arrayToDataTable([
					['เดือน', 'LET1', 'LET2', 'LET3', 'LET4', 'LET5', 'LET6', 'LET7'],
					[monthNamePie + ' ' + yearPie, myObj['object1'][0].count_incidence,
						myObj['object2'][0].count_incidence,
						myObj['object3'][0].count_incidence,
						myObj['object4'][0].count_incidence,
						myObj['object5'][0].count_incidence,
						myObj['object6'][0].count_incidence,
						myObj['object7'][0].count_incidence
					]
				]);

				var options = {
					title: 'สรุปรายงานประจำเดือนแบ่งตามประเภทเหตุการณ์ความเสียหาย',
					vAxis: {
						title: 'จำนวนเหตุการณ์'
					},

					seriesType: 'bars',
					series: {
						7: {
							type: 'line'
						}
					},
					series: {
						0: {
							color: '#276678'
						},
						1: {
							color: '#1687A7'
						},
						2: {
							color: '#D3E0EA'
						},
						3: {
							color: '#AEE1E1'
						},
						4: {
							color: '#D3E0DC'
						},
						5: {
							color: '#ECE2E1'
						},
						6: {
							color: '#FCD1D1'
						},



					}
				};

				var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
			//chart 3 

			document.getElementById("dmgType1").innerHTML = myObj['objectDmgType1'][0].dmgType;
			document.getElementById("dmgType2").innerHTML = myObj['objectDmgType2'][0].dmgType;
			document.getElementById("dmgType3").innerHTML = myObj['objectDmgType3'][0].dmgType;
			document.getElementById("dmgType4").innerHTML = myObj['objectDmgType4'][0].dmgType;

			// end chart 3 

			// chart 4 

			google.charts.load('current', {
				'packages': ['corechart']
			});
			google.charts.setOnLoadCallback(drawVisualization4);

			function drawVisualization4() {

				// Some raw data (not necessarily accurate)
			var data = google.visualization.arrayToDataTable([
					['เดือน', 'ต่ำ', 'ปานกลาง', 'สูง', 'สูงมาก'],
					[monthNamePie + ' ' + yearPie, myObj['objectDisplayRisk1'][0].dispRisk,
						myObj['objectDisplayRisk2'][0].dispRisk,
						myObj['objectDisplayRisk3'][0].dispRisk,
						myObj['objectDisplayRisk4'][0].dispRisk
					]
				]);


				var options = {
					title: 'สรุปรายงานประจำเดือนแบ่งตามความเสี่ยง',
					vAxis: {
						title: 'จำนวนเหตุการณ์'
					},

					seriesType: 'bars',
					series: {
						6: {
							type: 'line'
						}
					},
					series: {
						0: {
							color: '#00B050'
						},
						1: {
							color: '#FFFF00'
						},
						2: {
							color: '#FF9400'
						},
						3: {
							color: '#FF0000'
						},

					}
				};

				var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
				chart.draw(data, options);
			}

			// end chart 4 

		}

		xmlhttp.open("POST", "api/lossDataChart2.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);
	}
</script>
<script>
	var curYear = new Date().getFullYear() + 543;
	var curYear5 = new Date().getFullYear() + 539;
	showBar1(curYear5, curYear);
	$('#sYearBar').on('change', function() {

		if (this.value > $('#eYearBar').val()) {

		} else if ($('#eYearBar').val() < this.value) {
			alert('ปีเริ่มต้นต้องมีค่าน้อยกว่าปีสุดท้าย');
			showBar1(curYear5, curYear);
			$("#sYearBar")[0].selectedIndex = 0;
			$("#eYearBar")[0].selectedIndex = 0;
			return false;
		} else if (this.value != 0 && $('#eYearBar').val() != 0) {
			showBar1(this.value, $('#eYearBar').val());
		}

	});
	$('#eYearBar').on('change', function() {

		if (this.value > $('#sYearBar').val()) {
			showBar1($('#sYearBar').val(), this.value);
		} else if ($('#sYearBar').val() > this.value) {
			alert('ปีสุดท้ายต้องมีค่ามากกว่าปีเริ่มต้น');
			showBar1(curYear5, curYear);
			$("#sYearBar")[0].selectedIndex = 0;
			$("#eYearBar")[0].selectedIndex = 0;
			return false;
		} else if (this.value != 0 && $('#eYearBar').val() != 0) {
			showBar1($('#sYearBar').val(), this.value);
		}
	});




	function showBar1(sYearBars, eYearBars) {

		const dbParam = JSON.stringify({
			"sYearBars": sYearBars,
			"eYearBars": eYearBars
		});


		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObjBar1 = JSON.parse(this.responseText);
;

			google.charts.load('current', {
				packages: ['corechart', 'bar']
			});
			google.charts.setOnLoadCallback(drawBasic);

			function drawBasic() {


				var data = google.visualization.arrayToDataTable(myObjBar1);

				var options = {
					title: ' ',
					hAxis: {
						title: ' ',
						viewWindow: {
							min: [7, 30, 0],
							max: [17, 30, 0]
						}
					},
					vAxis: {
						title: ' '
					}
				};

				var chart = new google.visualization.ColumnChart(
					document.getElementById('bar_chart'));

				chart.draw(data, options);
			}
		}

		xmlhttp.open("POST", "api/lossDataChartBar.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("xBar1=" + dbParam);

	}
</script>
<!--start pie 1 -->
<script>
	$('#statDatePie1').on('change', function() {
		showPie1(this.value, $('#endDatePie1').val(), $('#searchGroupPie1').val());
	});
	$('#endDatePie1').on('change', function() {
		showPie1($('#statDatePie1').val(), this.value, $('#searchGroupPie1').val());
	});
	$('#searchGroupPie1').on('change', function() {
		showPie1($('#statDatePie1').val(), $('#endDatePie1').val(), this.value);
	});

	showPie1(sDate, eDate, '2565');

	function showPie1(statDatePie1, endDatePie1, searchGroupPie1) {

		const dbParam = JSON.stringify({
			"statDatePie1": statDatePie1,
			"endDatePie1": endDatePie1,
			"searchGroupPie1": searchGroupPie1
		});

		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObjPie1 = JSON.parse(this.responseText);

			google.charts.load('current', {
				'packages': ['corechart']
			});
			google.charts.setOnLoadCallback(drawChartPie1);
		
			if(myObjPie1['object1'][0].numCount == '0' && myObjPie1['object2'][0].numCount == '0' && myObjPie1['object3'][0].numCount == '0' && myObjPie1['object4'][0].numCount == '0' && myObjPie1['object5'][0].numCount == '0' && myObjPie1['object6'][0].numCount == '0' && myObjPie1['object7'][0].numCount == '0' ){
				$('#piechart1').hide();
				$('#piechart1_show').show();
				$('#piechart1_text').show();
				document.getElementById("piechart1_text").innerHTML = "ไม่พบข้อมูลรายงานความเสี่ยง LET1 - LET7";
			}else{
				
				$('#piechart1').show();
				$('#piechart1_show').hide();
			   $('#piechart1_text').hide();
			}
				function drawChartPie1() {
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['LET1', myObjPie1['object1'][0].numCount],
					['LET2', myObjPie1['object2'][0].numCount],
					['LET3', myObjPie1['object3'][0].numCount],
					['LET4', myObjPie1['object4'][0].numCount],
					['LET5', myObjPie1['object5'][0].numCount],
					['LET6', myObjPie1['object6'][0].numCount],
					['LET7', myObjPie1['object7'][0].numCount]
				]);

				// Optional; add a title and set the width and height of the chart
				var options = {
					'title': '',
					'width': '100%',
					'height': 800,
					colors: ['#276678', '#1687A7', '#D3E0EA', '#AEE1E1', '#D3E0DC', '#ECE2E1', '#FCD1D1'],
					   pieSliceTextStyle: {
            color: 'black',
			fontSize:20
        }
				};

				// Display the chart inside the <div> element with id="piechart"
				var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
				chart.draw(data, options);
			}
		
			
		}

		xmlhttp.open("POST", "api/lossChartPie1.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("xPie1=" + dbParam);

	}
</script>
<!--end pie 1 -->
<!--start pie 2 -->
<script>
	$('#statDatePie2').on('change', function() {
		showPie2(this.value, $('#endDatePie2').val(), $('#searchGroupPie2').val());
	});
	$('#endDatePie2').on('change', function() {
		showPie2($('#statDatePie2').val(), this.value, $('#searchGroupPie2').val());
	});
	$('#searchGroupPie2').on('change', function() {
		showPie2($('#statDatePie2').val(), $('#endDatePie2').val(), this.value);
	});

	showPie2(sDate, eDate, '2565');

	function showPie2(statDatePie2, endDatePie2, searchGroupPie2) {

		const dbParam = JSON.stringify({
			"statDatePie2": statDatePie2,
			"endDatePie2": endDatePie2,
			"searchGroupPie2": searchGroupPie2
		});

		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObjPie2 = JSON.parse(this.responseText);

			google.charts.load('current', {
				'packages': ['corechart']
			});
			google.charts.setOnLoadCallback(drawChartPie2);

			if(myObjPie2['object1'][0].numCount == '0' && myObjPie2['object2'][0].numCount == '0' && myObjPie2['object3'][0].numCount == '0' && myObjPie2['object4'][0].numCount == '0' ){
				$('#piechart2').hide();
				$('#piechart2_show').show();
				$('#piechart2_text').show();
				document.getElementById("piechart2_text").innerHTML = "ไม่พบข้อมูลรายงานประเภทความเสียหาย";
			}else{
				
				$('#piechart2').show();
				$('#piechart2_show').hide();
			   $('#piechart2_text').hide();
			}
			function drawChartPie2() {
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['ความเสี่ยงจากบุคลากร (People)', myObjPie2['object1'][0].numCount],
					['ความเสี่ยงจากเหตุการ์ณภายนอก (External Event)', myObjPie2['object2'][0].numCount],
					['ความเสี่ยงจากระบบ (System)', myObjPie2['object3'][0].numCount],
					['ความเสี่ยงจากการดำเนินงาน (Process)', myObjPie2['object4'][0].numCount]
				]);

				// Optional; add a title and set the width and height of the chart
				var options = {
					'title': '',
					'width': '100%',
					'height': 800,
					colors: ['#D0C7CD', '#BDCBCA', '#8A857C', '#FFCDBD'],
					pieSliceTextStyle: {
            color: 'black',
			fontSize:20
        }
				};

				// Display the chart inside the <div> element with id="piechart"
				var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
				chart.draw(data, options);
			}
		}


		xmlhttp.open("POST", "api/lossChartPie2.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("xPie2=" + dbParam);



	}
</script>
<!--end pie 2 -->
<!--start pie 3 -->
<script>
	$('#statDatePie3').on('change', function() {
		showPie3(this.value, $('#endDatePie3').val(), $('#searchGroupPie3').val());
	});
	$('#endDatePie3').on('change', function() {
		showPie3($('#statDatePie3').val(), this.value, $('#searchGroupPie3').val());
	});
	$('#searchGroupPie3').on('change', function() {
		showPie3($('#statDatePie3').val(), $('#endDatePie3').val(), this.value);
	});

	showPie3(sDate, eDate, '2565');

	function showPie3(statDatePie3, endDatePie3, searchGroupPie3) {

		const dbParam = JSON.stringify({
			"statDatePie3": statDatePie3,
			"endDatePie3": endDatePie3,
			"searchGroupPie3": searchGroupPie3
		});

		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onload = function() {
			const myObjPie3 = JSON.parse(this.responseText);

			google.charts.load('current', {
				'packages': ['corechart']
			});
			google.charts.setOnLoadCallback(drawChart);

			if(myObjPie3['object1'][0].numCount == '0' && myObjPie3['object2'][0].numCount == '0' && myObjPie3['object3'][0].numCount == '0'){
				$('#piechart3').hide();
				$('#piechart3_show').show();
				$('#piechart3_text').show();
				document.getElementById("piechart3_text").innerHTML = "ไม่พบข้อมูลรายงานความเสียหาย";
			}else{
				
				$('#piechart3').show();
				$('#piechart3_show').hide();
			   $('#piechart3_text').hide();
			}
			function drawChart() {
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['Actual Loss', myObjPie3['object1'][0].numCount],
					['Potential Loss ', myObjPie3['object2'][0].numCount],
					['Near-Missed ', myObjPie3['object3'][0].numCount]

				]);

				// Optional; add a title and set the width and height of the chart
				var options = {
					'title': '',
					'width': '100%',
					'height': 800,
					colors: ['#D1C8CD', '#C5D7DC', '#FFB49C'],
					pieSliceTextStyle: {
            color: 'black',
			fontSize:20
        }
				};

				// Display the chart inside the <div> element with id="piechart"
				var chart = new google.visualization.PieChart(document.getElementById('piechart3'));
				chart.draw(data, options);
			}
		}
		xmlhttp.open("POST", "api/lossChartPie3.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("xPie3=" + dbParam);

	}
</script>

<!--end pie 3 -->
<?php 
function setColorBotton($parameter){
	if($parameter=='1'){
return '#276678';
	}else if($parameter=='2'){
		return '#1687A7';
	}else if($parameter=='3'){
		return '#D3E0EA';
	}else if($parameter=='4'){
		return '#AEE1E1';
	}else if($parameter=='5'){
		return '#D3E0DC';
	}else if($parameter=='6'){
		return '#ECE2E1';
	}else if($parameter=='7'){
		return '#FCD1D1';
	}
}
?>
<? echo template_footer(); ?>