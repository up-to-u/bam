<?
include('inc/include.inc.php');
echo template_header();

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
.label-main{
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
.btn-primary{
    background-color: #42A5F5 !important;
    border-color: #42A5F5 !important;
}
.panel-default > .panel-heading {
    color: #ffffff;
    background-color: #004c85;
    border-color: #ddd;
}
a{
font-family: 'Prompt', sans-serif !important;
}
</style>

<div class="row">
	<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 31/05/65</label>
	
<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">ทะเบียนสรุปรายงานประจำปี</span><hr>
					<span class="caption-helper"> <select name='searchYear' id='searchYear' class="form-control">
					<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
					<?php
					$year = Date("Y");
					for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
						<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?></option>";
					<?php  } ?>
				</select><br></div></span>
			</div>
					<div  id='myDiv' >
						
				
					</div>	
</div></div>


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
				</select></div>
			</div>	
					
			
			<!-- strat main contain -->
			<div class="row">
			<div class="col-lg-12">
				<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['เดือน', 'LET1', 'LET2', 'LET3', 'LET4', 'LET5', 'LET6', 'LET7'],
          ['มกราคม 2565',  1,      3,         5,             2,           1,      4 ,1]
        ]);

        var options = {
          title : 'สรุปรายงานประจำเดือนแบ่งตามประเภทเหตุการณ์ความเสียหาย ประจำเดือน มกราคม 2565',
          vAxis: {title: 'จำนวนเหตุการณ์'},

          seriesType: 'bars',
          series: {7: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
  </body>
</html>

			</div>
		</div>
		


			
			
			<div class="row">
			<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12"><hr></div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 blue">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup"> People<br>
									<?php $i1 = 0;
									$sqlCount1 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_year=?";
									$stmt = $connect->prepare($sqlCount1);
									$stmt->bind_param("iii", $i1, $department_id,$view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									</span> </div>
							
							
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 red">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup"> External Event<br>
									<?php $i2 = 2;
									$sqlCount2 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_year=? ";
									$stmt = $connect->prepare($sqlCount2);
									$stmt->bind_param("iii", $i2, $department_id,$view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									</span> </div>
							
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 yellow">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup">System<br>
									<?php $i3 = 1;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_year=?";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("iii", $i3, $department_id,$view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									</span> </div>
							
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat dashboard-stat-v2 green ">
						<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
						<div class="details">
							<div class="number"> <span data-counter="counterup"> Process<br>
									<?php $i3 = 1;
									$sqlCount3 = "SELECT COUNT(*) AS num FROM loss_data_doc_list 
									join loss_data_doc on loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id 
									WHERE loss_data_doc_list.status_approve =? and loss_data_doc.loss_dep = ? and loss_data_doc.loss_year=?";
									$stmt = $connect->prepare($sqlCount3);
									$stmt->bind_param("iii", $i3, $department_id,$view_year);
									$stmt->execute();
									$res = $stmt->get_result();
									if ($rows = $res->fetch_assoc()) {
										echo $rows['num'];
									}
									?>
									</span> </div>
							
						</div>
					</div>
				</div>
				
				<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12"><hr></div>
				<div class="col-lg-12">
				<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['เดือน', 'ต่ำ', 'ปานกลาง', 'สูง', 'สูงมาก'],
          ['มกราคม 2565',  1,      3,         5,             2]
        ]);

        var options = {
          title : 'สรุปรายงานประจำเดือนแบ่งตามความเสี่ยง',
          vAxis: {title: 'จำนวนเหตุการณ์'},

          seriesType: 'bars',
          series: {6: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div1" style="width: 100%; height: 500px;"></div>
  </body>
</html>

			</div>
				
				
				
	<?

													$sql = "SELECT * FROM loss_factor WHERE parent_id = '2' ORDER BY loss_factor_id ASC";
													$result1 = mysqli_query($connect, $sql);
													$i = 0;
													while ($row = mysqli_fetch_array($result1)) {
														$i++;
													?>
																									
				<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
				<div class="container-fluid">
		
<div id="faq" role="tablist" aria-multiselectable="true">

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="questionOne" style='padding: 20px 15px;'>
<h5 class="panel-title">
<a data-toggle="collapse" data-parent="#faq" href="#answer<?=$i?>" aria-expanded="false" aria-controls="answerOne"><?= $row['factor']; ?></a>
</h5>
</div>

<div id="answer<?=$i?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="questionOne">
<div class="panel-body">
 แสดงอะไรดี
</div>
</div>
</div>
													

</div>
</div>
</div>
<?}?>
							<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12"><hr></div>
			
	</div>
</div>

<script>
	var currentYear = new Date().getFullYear() + 543;
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

			var trace1 = {
				x: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
				y: [myObj['object1'][0].count_incidence,
					myObj['object1'][1].count_incidence,
					myObj['object1'][2].count_incidence,
					myObj['object1'][3].count_incidence,
					myObj['object1'][4].count_incidence,
					myObj['object1'][5].count_incidence,
					myObj['object1'][6].count_incidence,
					myObj['object1'][7].count_incidence,
					myObj['object1'][8].count_incidence,
					myObj['object1'][9].count_incidence,
					myObj['object1'][10].count_incidence,
					myObj['object1'][11].count_incidence
				],
				name: 'LET 1',
				type: 'bar'

			};

			var trace2 = {
				x: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
				y: [myObj['object2'][0].count_incidence,
					myObj['object2'][1].count_incidence,
					myObj['object2'][2].count_incidence,
					myObj['object2'][3].count_incidence,
					myObj['object2'][4].count_incidence,
					myObj['object2'][5].count_incidence,
					myObj['object2'][6].count_incidence,
					myObj['object2'][7].count_incidence,
					myObj['object2'][8].count_incidence,
					myObj['object2'][9].count_incidence,
					myObj['object2'][10].count_incidence,
					myObj['object2'][11].count_incidence
				],
				name: 'LET 2',
				type: 'bar'
			};

			var trace3 = {
				x: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
				y: [myObj['object3'][0].count_incidence,
					myObj['object3'][1].count_incidence,
					myObj['object3'][2].count_incidence,
					myObj['object3'][3].count_incidence,
					myObj['object3'][4].count_incidence,
					myObj['object3'][5].count_incidence,
					myObj['object3'][6].count_incidence,
					myObj['object3'][7].count_incidence,
					myObj['object3'][8].count_incidence,
					myObj['object3'][9].count_incidence,
					myObj['object3'][10].count_incidence,
					myObj['object3'][11].count_incidence
				],
				name: 'LET 3',
				type: 'bar'
			};

			var trace4 = {
				x: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
				y: [myObj['object4'][0].count_incidence,
					myObj['object4'][1].count_incidence,
					myObj['object4'][2].count_incidence,
					myObj['object4'][3].count_incidence,
					myObj['object4'][4].count_incidence,
					myObj['object4'][5].count_incidence,
					myObj['object4'][6].count_incidence,
					myObj['object4'][7].count_incidence,
					myObj['object4'][8].count_incidence,
					myObj['object4'][9].count_incidence,
					myObj['object4'][10].count_incidence,
					myObj['object4'][11].count_incidence
				],
				name: 'LET 4',
				type: 'bar'
			};

			var trace5 = {
				x: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
				y: [myObj['object5'][0].count_incidence,
					myObj['object5'][1].count_incidence,
					myObj['object5'][2].count_incidence,
					myObj['object5'][3].count_incidence,
					myObj['object5'][4].count_incidence,
					myObj['object5'][5].count_incidence,
					myObj['object5'][6].count_incidence,
					myObj['object5'][7].count_incidence,
					myObj['object5'][8].count_incidence,
					myObj['object5'][9].count_incidence,
					myObj['object5'][10].count_incidence,
					myObj['object5'][11].count_incidence
				],
				name: 'LET 5',
				type: 'bar'
			};

			var trace6 = {
				x: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
				y: [myObj['object6'][0].count_incidence,
					myObj['object6'][1].count_incidence,
					myObj['object6'][2].count_incidence,
					myObj['object6'][3].count_incidence,
					myObj['object6'][4].count_incidence,
					myObj['object6'][5].count_incidence,
					myObj['object6'][6].count_incidence,
					myObj['object6'][7].count_incidence,
					myObj['object6'][8].count_incidence,
					myObj['object6'][9].count_incidence,
					myObj['object6'][10].count_incidence,
					myObj['object6'][11].count_incidence
				],
				name: 'LET 6',
				type: 'bar'
			};

			var trace7 = {
				x: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
				y: [myObj['object7'][0].count_incidence,
					myObj['object7'][1].count_incidence,
					myObj['object7'][2].count_incidence,
					myObj['object7'][3].count_incidence,
					myObj['object7'][4].count_incidence,
					myObj['object7'][5].count_incidence,
					myObj['object7'][6].count_incidence,
					myObj['object7'][7].count_incidence,
					myObj['object7'][8].count_incidence,
					myObj['object7'][9].count_incidence,
					myObj['object7'][10].count_incidence,
					myObj['object7'][11].count_incidence
				],
				name: 'LET 7',
				type: 'bar'
			};

			var data = [trace1, trace2, trace3, trace4, trace5, trace6, trace7];

			var layout = {
				barmode: 'stack'
			};


			Plotly.newPlot('myDiv', data, layout);
		}

		xmlhttp.open("POST", "api/lossDataChart.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);

	}
</script>
<script>
	var currentMonthPie = new Date().getMonth();
	var currentYearPie = new Date().getFullYear() + 543;

	showChartPie(currentMonthPie,currentYearPie);
	$('#searchMonths').on('change', function() {
		showChartPie(this.value,$('#searchYears').val());

	});
	
	$('#searchYears').on('change', function() {
		showChartPie($('#searchMonths').val(),this.value);
	
	});
	
	function showChartPie(m,y) {
		monthPie = m;
		yearPie = y;
		const dbParam = JSON.stringify({
			"monthPie": monthPie,
			"yearPie": yearPie
		});

		const xmlhttp = new XMLHttpRequest();



		xmlhttp.onload = function() {

			const myObjPie = JSON.parse(this.responseText);
			var data = [
  {
    x: ['LET1', 'LET2', 'LET3', 'LET4', 'LET5', 'LET6', 'LET7'],
    y: [myObjPie['object1'][0].numCount, myObjPie['object2'][0].numCount, myObjPie['object3'][0].numCount,myObjPie['object4'][0].numCount, myObjPie['object5'][0].numCount, myObjPie['object6'][0].numCount,myObjPie['object7'][0].numCount],
    type: 'bar'
  }
];
			Plotly.newPlot('myDivMonth', data, layout);
		}

		xmlhttp.open("POST", "api/lossDataChartPie.php");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("x=" + dbParam);

	}
</script>
<? echo template_footer(); ?>