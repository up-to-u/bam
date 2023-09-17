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

<link rel="stylesheet" href="dist/css/bootstrap-select.css">
<script src="dist/js/bootstrap-select.js"></script>
<link href="jquery-ui-1.12.0/jquery-ui.css" rel="stylesheet">
<script src="jquery-ui-1.12.0/jquery-ui.js"></script>

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
    <!-- Accordion CSS -->
    <link rel="stylesheet" href="./Collapse/style.css">
    
    <!--Only for demo purpose - no need to add.-->
    <link rel="stylesheet" type="text/css" href="./Collapse/
	demo.css" />
<script src='js/plotly-2.12.1.min.js'></script>
<div class="row">
	<label style="color:gray; font-size:10px;" class="pull-right"> Loss Data V1 - 31/05/65</label>
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">ทะเบียนสรุปรายงานประจำปี</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<!-- strat main contain -->
			<div class="row">
			<div class="col-lg-5">
				<!--------strat collapse contain-------->
				<br>	<br>
				<?

													$sql = "SELECT * FROM loss_factor WHERE parent_id = '2' ORDER BY loss_factor_id ASC";
													$result1 = mysqli_query($connect, $sql);
													$i = 0;
													while ($row = mysqli_fetch_array($result1)) {
														$i++;
													?>
			<section class="accordion" >
  <input type="checkbox" name="collapse"  id="LET<?= $i; ?>"  <?php if($i == '1'){ echo "checked";} ?>>
  <h2 class="handle">
    <label  style="height:70px;"  for="LET<?= $i; ?>"><?= $row['factor']; ?></label>
  </h2>
  <div class="content" >
  
  <div id='pieChart<?= $i; ?>' ><!-- Plotly chart will be drawn inside this DIV --></div>
    
  </div>

</section>
<?		} ?>
<!--------end collapse contain-------->
			</div>
			<div class="col-lg-7">
<!--------strat chart contain-------->

<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
<div class="col-lg-12">
					<div id='myDivMonth'>
			
						
		
					</div>
					<div class="row" style="margin-top: -30px ;">
					<div class="col-lg-1"></div>
					<div class="col-lg-5">
						<select name='searchMonths' id='searchMonths' class="form-control">
					<option value="0">- - - กรุณาเลือกเดือน - - -</option>
					
					<? $sql1 = "SELECT *	FROM month 
												 ";
													$result1 = mysqli_query($connect, $sql1);
													while ($row1 = mysqli_fetch_array($result1)) {	?>
														<option value='<?= $row1['month_id'] ?>'><?= $row1['month_name'] ?></option>
													<?	} ?>
				</select>
						</div>
			<div class="col-lg-5"> 
				<select name='searchYears' id='searchYears' class="form-control">
					<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
					<?php
					$year = Date("Y");
					for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
						<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?> (<?= $i; ?>)</option>";
					<?php  } ?>
				</select></div>
				<div class="col-lg-1"></div>
					</div>
				</div>				
			
<div class="col-lg-12">	<br><br><br>
					<div id='myDiv' >
						
				
					</div>
					<div class="row" style="margin-top: -30px ;">
					<div class="col-lg-4"></div>
			<div class="col-lg-4"> <select name='searchYear' id='searchYear' class="form-control">
					<option value="0">- - - กรุณาเลือกปี พ.ศ. - - -</option>
					<?php
					$year = Date("Y");
					for ($i =  ($year + 1); $i >=  ($year - 20); $i--) { ?>
						<option value='<?= $i + 543; ?>'>&nbsp;&nbsp;พ.ศ. <?= $i + 543; ?> (<?= $i; ?>)</option>";
					<?php  } ?>
				</select></div>
			<div class="col-lg-4"></div>
					</div>
				</div>
				
			</div>
			
			<div class="row" style="margin-top:200px;">
			</div>
			<!--------end chart contain-------->
			</div>
			</div>
			
						<!-- end main contain -->
		</div>
	</div>
</div>




<? echo template_footer(); ?>
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
<script>
// pieChart1
var data = [{
  type: "pie",
  values: [2, 3, 4, 4],
  labels: ["คน", "ขั้นตอน", "ระบบ", "คำสั่ง"],
  textinfo: "label+percent",
  insidetextorientation: "radial"
}]

var layout = [{
  height: 700,
  width: 700
}]

Plotly.newPlot('pieChart1', data, layout)

// pieChart2
var data = [{
  type: "pie",
  values: [2, 3, 4, 4],
  labels: ["คน", "ขั้นตอน", "ระบบ", "คำสั่ง"],
  textinfo: "label+percent",
  insidetextorientation: "radial"
}]

var layout = [{
  height: 700,
  width: 700
}]

Plotly.newPlot('pieChart2', data, layout)

// pieChart3
var data = [{
  type: "pie",
  values: [2, 3, 4, 4],
  labels: ["คน", "ขั้นตอน", "ระบบ", "คำสั่ง"],
  textinfo: "label+percent",
  insidetextorientation: "radial"
}]

var layout = [{
  height: 700,
  width: 700
}]

Plotly.newPlot('pieChart3', data, layout)

// pieChart4
var data = [{
  type: "pie",
  values: [2, 3, 4, 4],
  labels: ["คน", "ขั้นตอน", "ระบบ", "คำสั่ง"],
  textinfo: "label+percent",
  insidetextorientation: "radial"
}]

var layout = [{
  height: 700,
  width: 700
}]

Plotly.newPlot('pieChart4', data, layout)

// pieChart5
var data = [{
  type: "pie",
  values: [2, 3, 4, 4],
  labels: ["คน", "ขั้นตอน", "ระบบ", "คำสั่ง"],
  textinfo: "label+percent",
  insidetextorientation: "radial"
}]

var layout = [{
  height: 700,
  width: 700
}]

Plotly.newPlot('pieChart5', data, layout)

// pieChart6
var data = [{
  type: "pie",
  values: [2, 3, 4, 4],
  labels: ["คน", "ขั้นตอน", "ระบบ", "คำสั่ง"],
  textinfo: "label+percent",
  insidetextorientation: "radial"
}]

var layout = [{
  height: 700,
  width: 700
}]

Plotly.newPlot('pieChart6', data, layout)

// pieChart7
var data = [{
  type: "pie",
  values: [2, 3, 4, 4],
  labels: ["คน", "ขั้นตอน", "ระบบ", "คำสั่ง"],
  textinfo: "label+percent",
  insidetextorientation: "radial"
}]

var layout = [{
  height: 700,
  width: 700
}]

Plotly.newPlot('pieChart7', data, layout)
</script>