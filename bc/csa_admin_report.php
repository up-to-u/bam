<?
include('inc/include.inc.php');
include('csa_function.php');
echo template_header();

	$view = intval($_GET['view']);
	$view_year = intval($_GET['view_year']);
	if ($view_year==0) {
		$view_year=date('Y')+543;
	}

	$st = array();
	$sql = "SELECT 
		COUNT(*) AS num,
		csa_department_status_id
	FROM csa_department c
	LEFT JOIN department d1 ON c.department_id = d1.department_id
	LEFT JOIN department d2 ON c.department_id2 = d2.department_id
	LEFT JOIN department d3 ON c.department_id3 = d3.department_id
	WHERE 
		c.is_enable='1' AND
		c.csa_year = ? AND 
		c.mark_del = '0' 
	GROUP BY
		csa_department_status_id ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $view_year);
		$stmt->execute();
		$result1 = $stmt->get_result();
		while ($row1 = mysqli_fetch_assoc($result1)) {
			$st[$row1['csa_department_status_id']] = intval($row1['num']);
		}
	}	

	
?>
<div class="row">
	<div class="col-md-8">
		<table>
			<tr>
				<td>แสดงข้อมูล ของปี</td><td width='15'></td>
				<td>
					<select name='view_year' class="form-control" onChange='document.location="csa_admin_report.php?view_year="+this.value'>
						<option value='<?=$view_year-2?>'><?=$view_year-2?></option>
						<option value='<?=$view_year-1?>'><?=$view_year-1?></option>
						<option value='<?=$view_year?>' selected><?=$view_year?></option>
						<option value='<?=$view_year+1?>'><?=$view_year+1?></option>
						<option value='<?=$view_year+2?>'><?=$view_year+2?></option>
					<select>
				</td>
			</tr>
		</table>
	</div>
</div>
<br>



<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">สถานะรายการประเมินความเสี่ยง</span>
					<span class="caption-helper"></span>
				</div>
			</div>

				<div class="row">
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a class="dashboard-stat dashboard-stat-v2 red" href="csa_admin.php">
						<div class="visual">
							<i class="fa fa-hourglass-3"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?=intval($st[0]+$st[1])?>"><?=intval($st[0]+$st[1])?></span>
							</div>
							<div class="desc">อยู่ระหว่างดำเนินการ</div>
						</div>
					</a>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a class="dashboard-stat dashboard-stat-v2 yellow" href="csa_admin.php">
						<div class="visual">
							<i class="fa fa-check-square-o"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?=intval($st[2])?>"><?=intval($st[2])?></span>
							</div>
							<div class="desc"> อนุมัติรายการ</div>
						</div>
					</a>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a class="dashboard-stat dashboard-stat-v2 green" href="csa_admin.php">
						<div class="visual">
							<i class="fa fa-check-square-o"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?=intval($st[3])?>"><?=intval($st[3])?></span>
							</div>
							<div class="desc"> ดำเนินการแล้วเสร็จ</div>
						</div>
					</a>
				</div>
				
				</div>
		</div>
	</div>
</div>

<a name='chart_begin'></a>
<br>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>-->
<script src="js/jspdf.min.js"></script>


<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">กราฟสถิติ</span>
					<span class="caption-helper"></span>
				</div>
			</div>

<div class="tiles">
	<div class="tile <?= ($view==1) ? 'bg-red-sunglo' : 'bg-grey-cascade' ?>" onClick='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=1#chart_begin"'>
		<div class="tile-body">
			<i class="fa fa-bar-chart"></i>
		</div>
		<div class="tile-object">
			<div class="name"> แบบสอบถาม</div>
			
		</div>
	</div>
	<div class="tile <?= ($view==2) ? 'bg-red-sunglo' : 'bg-grey-cascade' ?>" onClick='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=2#chart_begin"'>
		<div class="tile-body">
			<i class="fa fa-bar-chart"></i>
		</div>
		<div class="tile-object">
			<div class="name"> ผลการประเมิน CSA</div>
		</div>
	</div>
	<div class="tile <?= ($view==3) ? 'bg-red-sunglo' : 'bg-grey-cascade' ?>" onClick='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=3#chart_begin"'>
		<div class="tile-body">
			<i class="fa fa-pie-chart"></i>
		</div>
		<div class="tile-object">
			<div class="name"> แยกประเภทความเสี่ยง</div>
		</div>
	</div>
<!--	<div class="tile <?= ($view==4) ? 'bg-red-sunglo' : 'bg-grey-cascade' ?>" onClick='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=4#chart_begin"'>
		<div class="tile-body">
			<i class="fa fa-table"></i>
		</div>
		<div class="tile-object">
			<div class="name"> แยกประเภทความเสี่ยง</div>
		</div>
	</div>-->
	<div class="tile <?= ($view==5) ? 'bg-red-sunglo' : 'bg-grey-cascade' ?>" onClick='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=5#chart_begin"'>
		<div class="tile-body">
			<i class="fa fa-table"></i>
		</div>
		<div class="tile-object">
			<div class="name"> Risk Matrix</div>
		</div>
	</div>
<!--	<div class="tile <?= ($view==6) ? 'bg-red-sunglo' : 'bg-grey-cascade' ?>" onClick='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=6#chart_begin"'>
		<div class="tile-body">
			<i class="fa fa-bar-chart"></i>
		</div>
		<div class="tile-object">
			<div class="name"> ฝ่ายที่ทำประเมิน</div>
		</div>-->
		<div class="tile <?= ($view==9) ? 'bg-red-sunglo' : 'bg-grey-cascade' ?>" onClick='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=9#chart_begin"'>
		<div class="tile-body">
			<i class="fa fa-file-excel-o"></i>
		</div>
		<div class="tile-object">
			<div class="name"> Export XLS</div>
		</div>
	</div>	
</div>
<br>
<br>

<?
	if ($_GET['dep_type']=='') {
		$dep_type = 2;
	} else {
		$dep_type = intval($_GET['dep_type']);
	}
	$dep_type_name = dep_type($dep_type);
	$select_dep_id = intval($_GET['select_dep_id']);
		
	
	if ($view==1) {?>

<div class='row'>
	<div class='col-md-2'>หน่วยงาน</div>
	<div class='col-md-3'>
		<select name='dep_type' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type="+this.value+"#chart_begin"'>
			<option value='2' <?if ($dep_type==2) echo 'selected'?>><?=dep_type(2)?></option>
			<option value='0' <?if ($dep_type==0) echo 'selected'?>><?=dep_type(0)?></option>
			<option value='1' <?if ($dep_type==1) echo 'selected'?>><?=dep_type(1)?></option>
			<option value='3' <?if ($dep_type==3) echo 'selected'?>>เลือกฝ่ายงาน</option>
		<select>
	</div>
</div>
<? if ($dep_type==3) {?>
<div class='row'>
	<div class='col-md-2'>ฝ่ายงาน</div>
	<div class='col-md-3'>
		<select name='select_dep_id' id='select_dep_id' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type=<?=$dep_type?>&select_dep_id="+this.value+"#chart_begin"'>
		<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM department WHERE department_level_id = '4' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
		<option value='<?=$row1['department_id']?>' <?if ($select_dep_id==$row1['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<? } ?>				
		</select>

	</div>
</div>
<? }?>
<br>
<br>
<br>

<?		
		if ($dep_type!=3 || ($dep_type==3 && $select_dep_id>0)) {
			
			$wsql = '';
			if ($dep_type==0 || $dep_type==1) {
				$wsql = " AND dep.is_branch = '$dep_type' ";
			} else if ($dep_type==3 && $select_dep_id>0) {
				$wsql = " AND dep2.department_id = '$select_dep_id' ";
			}	

			$topic = array();
			$sql = "SELECT 
				t1.q_no AS t1no,
				t2.csa_q_topic_id AS csa_q_topic_id,
				t2.q_no	AS t2no
			FROM csa_questionnaire_topic t1
			JOIN csa_questionnaire_topic t2 ON t2.parent_id = t1.csa_q_topic_id AND t2.mark_del = '0'
			WHERE 
				t1.parent_id = '0' AND
				t1.mark_del = '0' 
			ORDER BY
				t2.q_no ";
			$result2 = mysqli_query($connect, $sql);
			while ($row2 = mysqli_fetch_array($result2)) {
				$topic[] = array($row2['csa_q_topic_id'], $row2['t2no']);
			}
			$data = array();
			$sql = "SELECT 
				SUM(IF(v=1, 1, 0)) AS v1, 
				SUM(IF(v=2, 1, 0)) AS v2, 
				SUM(IF(v=3, 1, 0)) AS v3, 
				COUNT(*) AS num,
				csa_q_topic_id
			FROM csa_questionnaire_data csa_q
			JOIN csa_department csa_dep ON csa_q.csa_department_id = csa_dep.csa_department_id
			JOIN department dep ON csa_dep.department_id3 = dep.department_id
			JOIN department dep2 ON dep.parent_id = dep2.department_id
			WHERE 
				csa_dep.mark_del = '0' AND
				csa_dep.is_enable = '1' AND
				csa_dep.csa_year = '$view_year' 
				$wsql
			GROUP BY 
			csa_q_topic_id ";
			$result2 = mysqli_query($connect, $sql);
			while ($row2 = mysqli_fetch_array($result2)) {
				$data[$row2['csa_q_topic_id']] = array($row2['v1'], $row2['v2'], $row2['v3'], $row2['num']);
			}	
		
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load("current", {packages:["corechart"]});
	/*google.charts.load('current', {'packages':['bar']});*/

	google.charts.setOnLoadCallback(drawChart1);
	var data1;
	var options1 = {
		title: 'ส่วนที่ 1 - จำนวนผู้ตอบแบบสอบถามในแต่ละข้อ',
		colors: ['#b22727', '#f8cb2e', '#006e7f'],	
		'chartArea': {'width': '70%', 'height': '60%'},	  
		height: 320
	};

	function drawChart1() {
		data1 = google.visualization.arrayToDataTable([
		['ข้อ', 'ไม่มี', 'บางส่วน', 'ครบถ้วน'],
<?
$csv = array();
$csv_data = array();
$csv['title'] = 'ส่วนที่ 1 - จำนวนผู้ตอบแบบสอบถามในแต่ละข้อ';
$csv['header'] = array('ข้อ', 'ไม่มี', 'บางส่วน', 'ครบถ้วน');
foreach ($topic as $t) {
	$id = $t[0];
	echo "['".$t[1]."', ".intval($data[$id][0]).", ".intval($data[$id][1]).", ".intval($data[$id][2])."],";
	$csv_data[] = array($t[1],intval($data[$id][0]),intval($data[$id][1]),intval($data[$id][2]));
}
$csv['data'] = $csv_data;
$csv['hash'] = md5(serialize($csv_data));
?>		  
		]);
		resize();
	}	



  

	function resize () {
		var chart1 = new google.visualization.ColumnChart(document.getElementById("chart_div1"));

		//var chart1 = new google.charts.Bar(document.getElementById('chart_div1'));
		var btnSave1 = document.getElementById('save-pdf1');

		google.visualization.events.addListener(chart1, 'ready', function () {
			btnSave1.disabled = false;
		});

		btnSave1.addEventListener('click', function () {
			var doc = new jsPDF('l', 'mm', [297, 210]); /*new jsPDF(); */
			var divHeight = $('#chart_div1').height();
			var divWidth = $('#chart_div1').width();
			var ratio = divHeight / divWidth;
			var width =  doc.internal.pageSize.width;
			var height = ratio * width;
			doc.addImage(chart1.getImageURI(), 'JPEG', 0, 0, width-20, height-10);
			doc.save('chart.pdf');
		}, false);
		
		var view1 = new google.visualization.DataView(data1);
		chart1.draw(view1, options1);
		//chart1.draw(data1, google.charts.Bar.convertOptions(options1));
	}

	function openWindowWithPost(url, data) {
		var form = document.createElement("form");
		form.target = "_blank";
		form.method = "POST";
		form.action = url;
		form.style.display = "none";

		for (var key in data) {
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = key;
			input.value = data[key];
			form.appendChild(input);
		}

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}

	$(function () {	
		$(window).resize(function() {
			resize ();
		});	
		
		$('#export_csv1').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data1').val()
			});
		});		
		$('#export_csv2').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data2').val()
			});
		});		
		$('#export_csv3').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data3').val()
			});
		});		
	});	
</script>

			<div class="row">
			<div class="col-md-12">
			<div id="chart_div1" style="width: 100%; height: 320px;"></div>
			</div>			
			</div>			
			<br>
			<button id="save-pdf1" type="button" disabled class='btn btn-default'>Save as PDF</button>
			<button type="button" id='export_csv1' class='btn btn-default'>Export Data as Excel</button>
			<input type='hidden' id='c_data1' value='<?=json_encode($csv)?>'>
			<br>
			<br>

<hr/>
<?
			$csv = array();
			$csv_data = array();
			$csv['title'] = 'ส่วนที่ 1 - สัดส่วนการตอบแบบสอบถามแบบสอบถาม';
			$csv['header'] = array('ข้อ', 'จำนวน', '%');

			$data = array();
			$sql = "SELECT 
				SUM(IF(v=1, 1, 0)) AS v1, 
				SUM(IF(v=2, 1, 0)) AS v2, 
				SUM(IF(v=3, 1, 0)) AS v3
			FROM csa_questionnaire_data csa_q
			JOIN csa_department csa_dep ON csa_q.csa_department_id = csa_dep.csa_department_id
			JOIN department dep ON csa_dep.department_id3 = dep.department_id
			JOIN department dep2 ON dep.parent_id = dep2.department_id
			WHERE 
				csa_dep.mark_del = '0' AND
				csa_dep.is_enable = '1' AND
				csa_dep.csa_year = '$view_year' 
				$wsql";
			$result2 = mysqli_query($connect, $sql);
			if ($row2 = mysqli_fetch_array($result2)) {
				$data[1] = $row2['v1'];
				$data[2] = $row2['v2'];
				$data[3] = $row2['v3'];
				$total = intval($data[1])+intval($data[2])+intval($data[3]);
				if ($total>0) {
					$pc1 = $data[1]/$total*100;
					$pc2 = $data[2]/$total*100;
					$pc3 = $data[3]/$total*100;
				} else {
					$pc1 = 0;
					$pc2 = 0;
					$pc3 = 0;
				}
				
				$csv_data[] = array('ไม่มีการปฏิบัติ', intval($data[1]), $pc1);
				$csv_data[] = array('มีการปฏิบัติบางส่วน', intval($data[2]), $pc2);
				$csv_data[] = array('มีการปฏิบัติครบถ้วน', intval($data[3]), $pc3);
			}				
			$csv['data'] = $csv_data;
			$csv['hash'] = md5(serialize($csv_data));

?>

    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart12);
      function drawChart12() {
        var data12 = google.visualization.arrayToDataTable([
          ['ข้อ', 'จำนวน'],
          ['ไม่มีการปฏิบัติ', <?=intval($data[1])?>], 
		  ['มีการปฏิบัติบางส่วน', <?=intval($data[2])?>], 
		  ['มีการปฏิบัติครบถ้วน', <?=intval($data[3])?>],
        ]);

        var options12 = {
          title: 'ส่วนที่ 1 - สัดส่วนการตอบแบบสอบถามแบบสอบถาม',
          slices: {  1: {offset: 0.05},
                    2: {offset: 0.05},
                    3: {offset: 0.1}
          },
		  chartArea: {width: '90%', height: '80%'},	  
		  legend: { textStyle: { fontSize: 14  }},
		  colors: ['#b22727', '#f8cb2e', '#006e7f'],
		  width: '100%'
        };

        var chart12 = new google.visualization.PieChart(document.getElementById('piechart'));		
		var btnSave2 = document.getElementById('save-pdf2');

		google.visualization.events.addListener(chart12, 'ready', function () {
			btnSave2.disabled = false;
		});

		btnSave2.addEventListener('click', function () {
//			var doc = new jsPDF();
			var doc = new jsPDF('l', 'mm', [297, 210]);
			var divHeight = $('#piechart').height();
			var divWidth = $('#piechart').width();
			var ratio = divHeight / divWidth;
			var width =  doc.internal.pageSize.width;
			var height = ratio * width;
			doc.addImage(chart12.getImageURI(), 'JPEG', 0, 0, width-20, height-10);
			doc.save('chart.pdf');
		}, false);
		
        chart12.draw(data12, options12);
      }
    </script>
    <div id="piechart" style="width: 100%; height: 500px;"></div>			
	<button id="save-pdf2" type="button" disabled class='btn btn-default'>Save as PDF</button>
	<button type="button" id='export_csv2' class='btn btn-default'>Export Data as Excel</button>
	<input type='hidden' id='c_data2' value='<?=json_encode($csv)?>'>

	<hr>
<?
			
			$csv = array();
			$csv_data = array();
			$csv['title'] = 'ส่วนที่ 1 - สัดส่วนการตอบแบบสอบถามแบบสอบถาม';
			$csv['header'] = array('ลำดับ', 'ข้อ', 'ไม่มีการปฏิบัติ', 'มีการปฏิบัติบางส่วน', 'มีการปฏิบัติครบถ้วน');

			$data = array();
			$sql = "SELECT 
				SUM(IF(v=1, 1, 0)) AS v1, 
				SUM(IF(v=2, 1, 0)) AS v2, 
				SUM(IF(v=3, 1, 0)) AS v3, 
				COUNT(*) AS num,
				topic2.q_no,
                topic2.q_name
			FROM csa_questionnaire_data csa_q
            JOIN csa_questionnaire_topic topic ON csa_q.csa_q_topic_id = topic.csa_q_topic_id
            JOIN csa_questionnaire_topic topic2 ON topic2.csa_q_topic_id = topic.parent_id
			JOIN csa_department csa_dep ON csa_q.csa_department_id = csa_dep.csa_department_id
			JOIN department dep ON csa_dep.department_id3 = dep.department_id
			JOIN department dep2 ON dep.parent_id = dep2.department_id
			WHERE 
				csa_dep.mark_del = '0' AND
				csa_dep.is_enable = '1' AND
				csa_dep.csa_year = '$view_year' 
				$wsql
			GROUP BY 
			topic2.q_no,  topic2.q_name;";
			$result2 = mysqli_query($connect, $sql);
			while ($row2 = mysqli_fetch_array($result2)) {
				$vtotal = $row2['v1']+$row2['v2']+$row2['v3'];
				if ($vtotal>0) {
					$v1 = number_filter2(round($row2['v1']/$vtotal*100, 2));
					$v2 = number_filter2(round($row2['v2']/$vtotal*100, 2));
					$v3 = number_filter2(round($row2['v3']/$vtotal*100, 2));
				} else {
					$v1 = 0;
					$v2 = 0;
					$v3 = 0;
				}
				
				$data[$row2['q_no']] = array($v1, $v2, $v3, $row2['q_name']);
				$csv_data[] = array($row2['q_no'], $row2['q_name'], $v1, $v2, $v3);
			
			}	
			$csv['data'] = $csv_data;
			$csv['hash'] = md5(serialize($csv_data));
			
?>	
  <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['ข้อ', 'ไม่มีการปฏิบัติ', 'มีการปฏิบัติบางส่วน', 'มีการปฏิบัติครบถ้วน' ],
<? foreach ($data as $k=>$d) { ?>
       ['ข้อ <?=$k?>', <?=doubleval($d[0])?>, <?=doubleval($d[1])?>, <?=doubleval($d[2])?>],
<? } ?>		
      ]);


	  
      var view = new google.visualization.DataView(data);
      view.setColumns([0, 
						1, { calc: "stringify", sourceColumn: 1, type: "string", role: "annotation" },
						2, { calc: "stringify", sourceColumn: 2, type: "string", role: "annotation" },
						3, { calc: "stringify", sourceColumn: 3, type: "string", role: "annotation" },
                       ]);

      var options = {
		title: 'ส่วนที่ 1 (คิดเป็น %)',
		bar: { groupWidth: '75%' },
		legend: { position: 'bottom', textStyle: { fontSize: 14  }},
		colors: ['#b22727', '#f8cb2e', '#006e7f'],		
	  'chartArea': {'width': '60%', 'height': '60%'},
		isStacked: true,
		hAxis: { viewWindow:{ max:100, min:0}},
	};

      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
		var btnSave3 = document.getElementById('save-pdf3');

		google.visualization.events.addListener(chart, 'ready', function () {
			btnSave3.disabled = false;
		});

		btnSave3.addEventListener('click', function () {
//			var doc = new jsPDF();
			var doc = new jsPDF('l', 'mm', [297, 210]); /*new jsPDF(); */
			var divHeight = $('#barchart_values').height();
			var divWidth = $('#barchart_values').width();
			var ratio = divHeight / divWidth;
			var width =  doc.internal.pageSize.width;
			var height = doc.internal.pageSize.height;
			height = ratio * width;
			doc.addImage(chart.getImageURI(), 'JPEG', 0, 0, width-20, height-10);
			doc.save('chart.pdf');
		}, false);	  
      chart.draw(view, options);
  }
  </script>
	<div id="barchart_values" style="width: 100%; height: 400px;"></div>	
	<button id="save-pdf3" type="button" disabled class='btn btn-default'>Save as PDF</button>
	<button type="button" id='export_csv3' class='btn btn-default'>Export Data as Excel</button>
	<input type='hidden' id='c_data3' value='<?=json_encode($csv)?>'>
	
			
			
<?		
		}
	} else if ($view==2) {
?>

<div class='row'>
	<div class='col-md-2'>หน่วยงาน</div>
	<div class='col-md-3'>
		<select name='dep_type' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type="+this.value+"#chart_begin"'>
			<option value='2' <?if ($dep_type==2) echo 'selected'?>><?=dep_type(2)?></option>
			<option value='0' <?if ($dep_type==0) echo 'selected'?>><?=dep_type(0)?></option>
			<option value='1' <?if ($dep_type==1) echo 'selected'?>><?=dep_type(1)?></option>
			<option value='3' <?if ($dep_type==3) echo 'selected'?>>เลือกฝ่ายงาน</option>
		<select>
	</div>
</div>
<? if ($dep_type==3) {?>
<div class='row'>
	<div class='col-md-2'>ฝ่ายงาน</div>
	<div class='col-md-3'>
		<select name='select_dep_id' id='select_dep_id' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type=<?=$dep_type?>&select_dep_id="+this.value+"#chart_begin"'>
		<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM department WHERE department_level_id = '4' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
		<option value='<?=$row1['department_id']?>' <?if ($select_dep_id==$row1['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<? } ?>				
		</select>

	</div>
</div>
<? }?>
<br>
<br>
<br>


<?
		if ($dep_type!=3 || ($dep_type==3 && $select_dep_id>0)) {
			
			$wsql = '';
			if ($dep_type==0 || $dep_type==1) {
				$wsql = " AND dep.is_branch = '$dep_type' ";
			} else if ($dep_type==3 && $select_dep_id>0) {
				$wsql = " AND dep2.department_id = '$select_dep_id' ";
			}

			$csv = array();
			$csv_data = array();
			$csv['title'] = 'ส่วนที่ 2 - ระดับความเสี่ยง ก่อนและหลังการควบคุม';
			$csv['header'] = array('ระดับความเสี่ยง', 'ก่อนการควบคุม', '%', 'หลังการควบคุม', '%');

			$data2 = array();
			$sql = "SELECT 
				SUM(IF(csa_risk_level1=1, 1, 0)) AS v11, 
				SUM(IF(csa_risk_level1=2, 1, 0)) AS v12, 
				SUM(IF(csa_risk_level1=3, 1, 0)) AS v13, 
				SUM(IF(csa_risk_level1=4, 1, 0)) AS v14, 
				SUM(IF(csa_risk_level2=1, 1, 0)) AS v21, 
				SUM(IF(csa_risk_level2=2, 1, 0)) AS v22, 
				SUM(IF(csa_risk_level2=3, 1, 0)) AS v23, 
				SUM(IF(csa_risk_level2=4, 1, 0)) AS v24, 
				COUNT(*) AS num
			FROM csa
			JOIN csa_department csa_dep ON csa.csa_department_id = csa_dep.csa_department_id
			JOIN department dep ON csa_dep.department_id3 = dep.department_id
			JOIN department dep2 ON dep.parent_id = dep2.department_id
			WHERE 
			csa.mark_del = '0' AND
			csa_dep.mark_del = '0' AND
			csa_dep.is_enable = '1' AND
			csa_dep.csa_year = '$view_year'
			$wsql
			";
			$result2 = mysqli_query($connect, $sql);
			if ($row2 = mysqli_fetch_array($result2)) {
				$data2 = $row2;

				for ($i=1; $i<=4; $i++) {
					$total = intval($data2['v1'.$i])+intval($data2['v2'.$i]);
					if ($total>0) {
						$pc1 = $data2['v1'.$i]/$total*100;
						$pc2 = $data2['v2'.$i]/$total*100;
					} else {
						$pc1 = 0;
						$pc2 = 0;
					}
					$csv_data[] = array(risk_level_name($i), intval($data2['v1'.$i]), $pc1, intval($data2['v2'.$i]), $pc2);
				}
			}
			$csv['data'] = $csv_data;
			$csv['hash'] = md5(serialize($csv_data));
	
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	function openWindowWithPost(url, data) {
		var form = document.createElement("form");
		form.target = "_blank";
		form.method = "POST";
		form.action = url;
		form.style.display = "none";

		for (var key in data) {
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = key;
			input.value = data[key];
			form.appendChild(input);
		}

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}
	
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart2);
	var data2;
	var options2 = {
		title: 'ส่วนที่ 2 - ระดับความเสี่ยง ก่อนและหลังการควบคุม',
		'chartArea': {'width': '65%', 'height': '80%'},
		colors: ['#d6d5a8', '#51557e'],
		annotations: {
			alwaysOutside: false,
			textStyle: {
				fontSize: 11
			}
		},
		legend: {position: 'right', textStyle: {fontSize: 12}},	  
		height: 320,
		width: '100%'
	};

	function drawChart2() {
		data2 = google.visualization.arrayToDataTable([
		['ระดับความเสี่ยง', 'ก่อนการควบคุม', 'หลังการควบคุม'],
		['ต่ำ',  <?=intval($data2['v11'])?>, <?=intval($data2['v21'])?>],
		['ปานกลาง', <?=intval($data2['v12'])?>, <?=intval($data2['v22'])?>],
		['สูง', <?=intval($data2['v13'])?>, <?=intval($data2['v23'])?>],
		['สูงมาก', <?=intval($data2['v14'])?>, <?=intval($data2['v24'])?>],
		]);

		
		resize();
	}



	function resize () {

  var groupData = google.visualization.data.group(
    data2,
    [{column: 0, modifier: function () {return 'total'}, type:'string'}],
    [{column: 1, aggregation: google.visualization.data.sum, type: 'number'}],
    [{column: 2, aggregation: google.visualization.data.sum, type: 'number'}]
  );

  var formatPercent = new google.visualization.NumberFormat({
    pattern: '#,##0.0%'
  });

  var formatShort = new google.visualization.NumberFormat({
    pattern: 'short'
  });

  var view = new google.visualization.DataView(data2);
  view.setColumns([0, 
	1, {
    calc: function (dt, row) {
		var amount =  formatShort.formatValue(dt.getValue(row, 1));
		var p = 0;
		if ((dt.getValue(row, 1)+dt.getValue(row, 2))==0)
			p = 0;
		else 
			p = dt.getValue(row, 1) / (dt.getValue(row, 1)+dt.getValue(row, 2));

		var percent = formatPercent.formatValue(p);
		return amount + ' (' + percent + ')';
    }, type: 'string', role: 'annotation' },
	2, {
    calc: function (dt, row) {
		var amount =  formatShort.formatValue(dt.getValue(row, 2));
		var p = 0;
		if ((dt.getValue(row, 1)+dt.getValue(row, 2))==0)
			p = 0;
		else 
			p = dt.getValue(row, 2) / (dt.getValue(row, 1)+dt.getValue(row, 2));

		var percent = formatPercent.formatValue(p);
		return amount + ' (' + percent + ')';
    }, type: 'string', role: 'annotation' }
  ]);
			
		var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div2'));

		var btnSave4 = document.getElementById('save-pdf4');

		google.visualization.events.addListener(chart2, 'ready', function () {
			btnSave4.disabled = false;
		});

		btnSave4.addEventListener('click', function () {
			var doc = new jsPDF('l', 'mm', [297, 210]);
			var divHeight = $('#chart_div2').height();
			var divWidth = $('#chart_div2').width();
			var ratio = divHeight / divWidth;
			var width =  doc.internal.pageSize.width;
			var height = ratio * width;
			doc.addImage(chart2.getImageURI(), 'JPEG', 0, 0, width-20, height-10);
			doc.save('chart.pdf');
		}, false);	
		
		chart2.draw(view, options2);		
		
	}

	$(function () {	
		$(window).resize(function() {
			resize ();
		});	
		
		$('#export_csv4').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data4').val()
			});
		});			
	});	
</script>

			<div class="row">
			<div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<div id="chart_div2" style="width: 100%; height: 320px;"></div>
			</div>			
			</div>			
			<br>
			<button id="save-pdf4" type="button" disabled class='btn btn-default'>Save as PDF</button>
			<button type="button" id='export_csv4' class='btn btn-default'>Export Data as Excel</button>
			<input type='hidden' id='c_data4' value='<?=json_encode($csv)?>'>
			<br>
			<br>


<?
		} else {
?>
			<br><br><br><br><br><br><br><br><br>
<?			
		}
	} else if ($view==3) {
?>

<div class='row'>
	<div class='col-md-2'>หน่วยงาน</div>
	<div class='col-md-3'>
		<select name='dep_type' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type="+this.value+"#chart_begin"'>
			<option value='2' <?if ($dep_type==2) echo 'selected'?>><?=dep_type(2)?></option>
			<option value='0' <?if ($dep_type==0) echo 'selected'?>><?=dep_type(0)?></option>
			<option value='1' <?if ($dep_type==1) echo 'selected'?>><?=dep_type(1)?></option>
		<select>
	</div>
</div>
<br>
<br>
<br>

<?	
		$wsql = '';
		if ($dep_type==0 || $dep_type==1) {
			$wsql = " AND dep.is_branch = '$dep_type' ";
		}
		
		$data = array();
		$sql = "SELECT 
			r.risk_type_name,
            r.csa_risk_type_id,
			COUNT(*) AS num
		FROM csa
		JOIN csa_department csa_dep ON csa.csa_department_id = csa_dep.csa_department_id
		JOIN csa_risk_type r ON csa.risk_type = r.csa_risk_type_id
		JOIN department dep ON csa_dep.department_id3 = dep.department_id
		WHERE 
			csa.mark_del = '0' AND
			csa_dep.mark_del = '0' AND
			csa_dep.is_enable = '1' AND
			csa_dep.csa_year = '$view_year'
			$wsql
        GROUP BY 
        r.risk_type_name,
        r.csa_risk_type_id;";
		$result2 = mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {
			$data[] = array($row2['risk_type_name'], $row2['num'], $row2['csa_risk_type_id']);
		}
		
		$risk_type_color = array(
			1=>'#09015f', 
			2=>'#af0069', 
			3=>'#55b3b1', 
			4=>'#f6c065', 
			5=>'#d6b0b1', 
			6=>'#3b5360'
		);
?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
	function openWindowWithPost(url, data) {
		var form = document.createElement("form");
		form.target = "_blank";
		form.method = "POST";
		form.action = url;
		form.style.display = "none";

		for (var key in data) {
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = key;
			input.value = data[key];
			form.appendChild(input);
		}

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}	
	
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Risk Type', 'จำนวน'],
<?
	$csv = array();
	$csv_data = array();
	$csv['title'] = 'ผลการประเมินความเสี่ยง CSA จำแนกตามประเภทความเสี่ยง '.$dep_type_name;
	$csv['header'] = array('Risk Type', 'จำนวน');

$color_list = array();
foreach ($data as $d) {
	echo "['".$d[0]."', ".intval($d[1])."],";
	$color_list[] = $risk_type_color[$d[2]];

	$csv_data[] = array($d[0], intval($d[1]));
}
if (count($color_list)>0) 
	$c = '"'.implode('","', $color_list).'"';
else 
	$c = "'#09015f', '#af0069', '#55b3b1', '#f6c065', '#d6b0b1', '#3b5360'";

	$csv['data'] = $csv_data;
	$csv['hash'] = md5(serialize($csv_data));
	
?>	
        ]);

        var options = {
          title: 'ผลการประเมินความเสี่ยง CSA จำแนกตามประเภทความเสี่ยง <?=$dep_type_name?>',
		  'chartArea': {'width': '100%', 'height': '80%'},
		  legend: { textStyle: { fontSize: 14  }},
		  colors: [<?=$c?>],
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
		var btnSave6 = document.getElementById('save-pdf6');

		google.visualization.events.addListener(chart, 'ready', function () {
			btnSave6.disabled = false;
		});

		btnSave6.addEventListener('click', function () {
			var doc = new jsPDF('l', 'mm', [297, 210]);
			var divHeight = $('#piechart_3d').height();
			var divWidth = $('#piechart_3d').width();
			var ratio = divHeight / divWidth;
			var width =  doc.internal.pageSize.width;
			var height = ratio * width;
			doc.addImage(chart.getImageURI(), 'JPEG', 0, 0, width-20, height-10);
			doc.save('chart.pdf');
		}, false);	
		
        chart.draw(data, options);
      }
	  

	$(function () {	
		$('#export_csv5').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data5').val()
			});
		});				
		
		$('#export_csv6').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data6').val()
			});
		});		
	});		  
    </script>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
	<button id="save-pdf6" type="button" disabled class='btn btn-default'>Save as PDF</button>
	<button type="button" id='export_csv5' class='btn btn-default'>Export Data as Excel</button>
	<input type='hidden' id='c_data5' value='<?=json_encode($csv)?>'>
	<br>		
	<br>		
	<br>		
	<br>		

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawStacked);


function drawStacked() {
      //var data = new google.visualization.DataTable();
	var data = google.visualization.arrayToDataTable([
		['ประเภทความเสี่ยง', 'สำนักงานใหญ่', 'ภาค'],
<?
		$csv = array();
		$csv_data = array();
		$csv['title'] = 'จำนวน แยกตามประเภทความเสี่ยง '.$dep_type_name;
		$csv['header'] = array('ประเภทความเสี่ยง', 'สำนักงานใหญ่', '%', 'ภาค', '%');

		$risk_type_list = array();
		$sql = "SELECT * FROM csa_risk_type r WHERE mark_del = 0 ";
		$result2 = mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {
			$d = array();
			$sql = "SELECT 
				COUNT(*) AS num,
				dep.is_branch
			FROM csa
			JOIN csa_department csa_dep ON csa.csa_department_id = csa_dep.csa_department_id
			JOIN department dep ON csa_dep.department_id3 = dep.department_id
			WHERE 
				csa.risk_type = '$row2[csa_risk_type_id]' AND
				csa.mark_del = '0' AND
				csa_dep.mark_del = '0' AND
				csa_dep.is_enable = '1' AND
				csa_dep.csa_year = '$view_year'
				$wsql
			GROUP BY 
				is_branch";
			$result3 = mysqli_query($connect, $sql);
			while ($row3 = mysqli_fetch_array($result3)) {		
				$d[$row3['is_branch']] = $row3['num'];
			}

			preg_match('#\((.*?)\)#', $row2['risk_type_name'], $match);
			$risk_type_name = $match[1];
			//$risk_type_name = str_replace('(', '(', $row2['risk_type_name']);
			
			$risk_type_list[] = array($risk_type_name, $row2['risk_type_name']);
			echo "['".$risk_type_name."', ".intval($d[0]).", ".intval($d[1])."],";
			
			
			$total = intval(intval($d[0])+ intval($d[1]));
			if ($total>0) {
				$pc1 = $d[0]/$total*100;
				$pc2 = $d[1]/$total*100;
			} else {
				$pc1 = 0;
				$pc2 = 0;
			}			
			$csv_data[] = array($risk_type_name, intval($d[0]), $pc1, intval($d[1]), $pc2);
		}	
		
		$csv['data'] = $csv_data;
		$csv['hash'] = md5(serialize($csv_data));		
?>
		]);

      var options = {
        title: 'จำนวน แยกตามประเภทความเสี่ยง <?=$dep_type_name?>',
        isStacked: false,
        width: '100%',
		top: 55,
		height: 350,
        chartArea: {
			width: '65%',
        	height: '70%',
			top:50,
        },
        hAxis: {
          textStyle : {
            fontSize: 12 
		  },
          slantedText:false,
          slantedTextAngle: 45,
		  viewWindowMode: 'pretty', 
          
        },
		colors: ['#7f8000', '#fe9900'],
		annotations: {
        alwaysOutside: false,
        textStyle: {
            fontSize: 10
        }
		},
		legend: {position: 'right', textStyle: {fontSize: 12}}
      };


  var groupData = google.visualization.data.group(
    data,
    [{column: 0, modifier: function () {return 'total'}, type:'string'}],
    [{column: 1, aggregation: google.visualization.data.sum, type: 'number'}],
    [{column: 2, aggregation: google.visualization.data.sum, type: 'number'}]
  );

  var formatPercent = new google.visualization.NumberFormat({
    pattern: '#,##0.0%'
  });

  var formatShort = new google.visualization.NumberFormat({
    pattern: 'short'
  });

  var view = new google.visualization.DataView(data);
	view.setColumns([0, 
	1, {
	calc: function (dt, row) {
		var amount =  formatShort.formatValue(dt.getValue(row, 1));
		var p = 0;
		if ((dt.getValue(row, 1)+dt.getValue(row, 2))==0)
			p = 0;
		else 
			p = dt.getValue(row, 1) / (dt.getValue(row, 1)+dt.getValue(row, 2));

		var percent = formatPercent.formatValue(p);
		return amount + ' (' + percent + ')';
    }, type: 'string', role: 'annotation' },
	2, {
    calc: function (dt, row) {
		var amount =  formatShort.formatValue(dt.getValue(row, 2));
		var p = 0;
		if ((dt.getValue(row, 1)+dt.getValue(row, 2))==0)
			p = 0;
		else 
			p = dt.getValue(row, 2) / (dt.getValue(row, 1)+dt.getValue(row, 2));

		var percent = formatPercent.formatValue(p);
		return amount + ' (' + percent + ')';
    }, type: 'string', role: 'annotation' }
  ]);

		var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div2'));

		var btnSave5 = document.getElementById('save-pdf5');

		google.visualization.events.addListener(chart2, 'ready', function () {
			btnSave5.disabled = false;
		});

		btnSave5.addEventListener('click', function () {
			var doc = new jsPDF('l', 'mm', [297, 210]);
			var divHeight = $('#chart_div2').height();
			var divWidth = $('#chart_div2').width();
			var ratio = divHeight / divWidth;
			var width =  doc.internal.pageSize.width;
			var height = ratio * width;
			doc.addImage(chart2.getImageURI(), 'JPEG', 0, 0, width-20, height-10);
			doc.save('chart.pdf');
		}, false);	
	  
      chart2.draw(view, options);
}
</script>

			<div class="row">
			<div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<div id="chart_div2" style="width: 100%; height: 350px;"></div>
			</div>			
			</div>
			<br>
			<br>
			<div class="row">
			<div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<b>ความหมาย</b><br>
			<table class='table'>

<?
	foreach ($risk_type_list as $r=>$v) {
		echo '<tr><td>'.$v[0].'</td><td>'.$v[1].'</td></tr>';
	}
?>
			</table>
			<br>
			<button id="save-pdf5" type="button" disabled class='btn btn-default'>Save as PDF</button>
			<button type="button" id='export_csv6' class='btn btn-default'>Export Data as Excel</button>
			<input type='hidden' id='c_data6' value='<?=json_encode($csv)?>'>
			</div>			
			</div>
			<br>
			<br>
			<br>
		</div>
	</div>
</div>			



<?	} else if ($view==4) { ?>

<div class='row'>
	<div class='col-md-2'>หน่วยงาน</div>
	<div class='col-md-3'>
		<select name='dep_type' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type="+this.value+"#chart_begin"'>
			<option value='2' <?if ($dep_type==2) echo 'selected'?>><?=dep_type(2)?></option>
			<option value='0' <?if ($dep_type==0) echo 'selected'?>><?=dep_type(0)?></option>
			<option value='1' <?if ($dep_type==1) echo 'selected'?>><?=dep_type(1)?></option>
			<option value='3' <?if ($dep_type==3) echo 'selected'?>>เลือกฝ่ายงาน</option>
		<select>
	</div>
</div>
<? if ($dep_type==3) {?>
<div class='row'>
	<div class='col-md-2'>ฝ่ายงาน</div>
	<div class='col-md-3'>
		<select name='select_dep_id' id='select_dep_id' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type=<?=$dep_type?>&select_dep_id="+this.value+"#chart_begin"'>
		<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM department WHERE department_level_id = '4' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
		<option value='<?=$row1['department_id']?>' <?if ($select_dep_id==$row1['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<? } ?>				
		</select>

	</div>
</div>
<? }?>
<br>
<br>
<br>

<?		
		if ($dep_type!=3 || ($dep_type==3 && $select_dep_id>0)) {
			
			$wsql = '';
			if ($dep_type==0 || $dep_type==1) {
				$wsql = " AND dep.is_branch = '$dep_type' ";
			} else if ($dep_type==3 && $select_dep_id>0) {
				$wsql = " AND dep2.department_id = '$select_dep_id' ";
			}			
			
			$tb1 = array();
			$sql = "SELECT * FROM csa_risk_type r ";
			$result2 = mysqli_query($connect, $sql);
			while ($row2 = mysqli_fetch_array($result2)) {
				$d = array();
				$sql = "SELECT 
					COUNT(*) AS num,
					dep.is_branch
				FROM csa
				JOIN csa_department csa_dep ON csa.csa_department_id = csa_dep.csa_department_id
				JOIN department dep ON csa_dep.department_id3 = dep.department_id
				JOIN department dep2 ON dep.parent_id = dep2.department_id
				WHERE 
					csa.risk_type = '$row2[csa_risk_type_id]' AND
					csa.mark_del = '0' AND
					csa_dep.mark_del = '0' AND
					csa_dep.is_enable = '1' AND
					csa_dep.csa_year = '$view_year'
					$wsql
				GROUP BY 
					is_branch";
				$result3 = mysqli_query($connect, $sql);
				while ($row3 = mysqli_fetch_array($result3)) {		
					$d[$row3['is_branch']] = $row3['num'];
				}
				$tb1[] = array($row2['risk_type_name'], intval($d[0]), intval($d[1]));
			}	
?>

			<table class='table table-hover '>
			<thead>
			<tr style='font-weight: bold'>
			  <td width='50%' rowspan='2'>ประเภทความเสี่ยง</td>
			  <td width='40%' colspan='2' align='center'>ร้อยละ</td>
			</tr>
			<tr style='font-weight: bold' align='center'>
			  <td width='20%'>สำนักงานใหญ่</td>
			  <td width='20%'>ภาค</td>
			</tr>
			</thead>
			<tbody>
<?	foreach ($tb1 as $t) {
		$t_all = $t[1]+$t[2];
		$t1 = $t_all>0 ? number_filter2($t[1]/$t_all*100).'%': '0%';
		$t2 = $t_all>0 ? number_filter2($t[2]/$t_all*100).'%': '0%';
?>
			<tr>
				<td><?=$t[0]?></td>
				<td align='center'><?=$t1?></td>
				<td align='center'><?=$t2?></td>
			</tr>
<?	}?>
			</tbody>
			</table>

<?
		}
	
	} else if ($view==5) {
?>

<div class='row'>
	<div class='col-md-2'>หน่วยงาน</div>
	<div class='col-md-3'>
		<select name='dep_type' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type="+this.value+"#chart_begin"'>
			<option value='2' <?if ($dep_type==2) echo 'selected'?>><?=dep_type(2)?></option>
			<option value='0' <?if ($dep_type==0) echo 'selected'?>><?=dep_type(0)?></option>
			<option value='1' <?if ($dep_type==1) echo 'selected'?>><?=dep_type(1)?></option>
			<option value='3' <?if ($dep_type==3) echo 'selected'?>>เลือกฝ่ายงาน</option>
		<select>
	</div>
</div>
<? if ($dep_type==3) {?>
<div class='row'>
	<div class='col-md-2'>ฝ่ายงาน</div>
	<div class='col-md-3'>
		<select name='select_dep_id' id='select_dep_id' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&dep_type=<?=$dep_type?>&select_dep_id="+this.value+"#chart_begin"'>
		<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM department WHERE department_level_id = '4' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
		<option value='<?=$row1['department_id']?>' <?if ($select_dep_id==$row1['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<? } ?>				
		</select>

	</div>
</div>
<? }?>
<br>
<br>
<br>

<?		
		if ($dep_type!=3 || ($dep_type==3 && $select_dep_id>0)) {
			
			$wsql = '';
			if ($dep_type==0 || $dep_type==1) {
				$wsql = " AND dep.is_branch = '$dep_type' ";
			} else if ($dep_type==3 && $select_dep_id>0) {
				$wsql = " AND dep2.department_id = '$select_dep_id' ";
			}

			$d = array();
			$sql2="SELECT * FROM `csa_risk_matrix` ";
			$result1=mysqli_query($connect, $sql2);
			while ($row1 = mysqli_fetch_array($result1)) {
				$d[$row1['csa_impact_id']][$row1['csa_likelihood_id']] = $row1['csa_risk_level']; 
			}
			$lv = array();
			for ($i=1; $i<=5; $i++) {
				$lv[] = risk_level_name($i);
			}
			
			$d_count1 = array();
			$d_count2 = array();
			$sql2="SELECT 
		csa_impact_id1, csa_likelihood_id1, csa_risk_level1, 
		csa_impact_id2, csa_likelihood_id2, csa_risk_level2 
	FROM `csa` 
	JOIN csa_department csa_dep ON csa.csa_department_id = csa_dep.csa_department_id
	JOIN department dep ON csa_dep.department_id3 = dep.department_id
	JOIN department dep2 ON dep.parent_id = dep2.department_id
	WHERE 
	csa.mark_del = '0' AND
	csa_dep.mark_del = '0' AND
	csa_dep.is_enable = '1' AND
	csa_dep.csa_year = '$view_year'
	$wsql
	";
			$result1=mysqli_query($connect, $sql2);
			while ($row1 = mysqli_fetch_array($result1)) {
/*				$d_count1[$row1['csa_impact_id1']][$row1['csa_likelihood_id1']] += $row1['csa_risk_level1']; 
				$d_count2[$row1['csa_impact_id2']][$row1['csa_likelihood_id2']] += $row1['csa_risk_level2']; */
				
				if ($d_count1[$row1['csa_impact_id1']][$row1['csa_likelihood_id1']]=='') $d_count1[$row1['csa_impact_id1']][$row1['csa_likelihood_id1']] = 0;
				if ($d_count2[$row1['csa_impact_id2']][$row1['csa_likelihood_id2']]=='') $d_count2[$row1['csa_impact_id2']][$row1['csa_likelihood_id2']] = 0;
				
				$d_count1[$row1['csa_impact_id1']][$row1['csa_likelihood_id1']]++; 
				$d_count2[$row1['csa_impact_id2']][$row1['csa_likelihood_id2']]++; 
			}			
?>	
<style>
.d1 {
	color: #000000;
}	
</style>
<div class='row'>
<div class='col-md-6'>
<table class='' border='0' id='table_mat'>
<tr>
	<td colspan='8' align='center'><b>ตารางแสดงผลการวัดระดับความเสี่ยง <font color='red'><u>ก่อน</u></font> การควบคุม</b><br><br></td>
</tr>
<tr>
  <td rowspan='6' width='100' style='font-size: 11px'>ผลกระทบ<br>Impact<!--<img src='images/risk_matrix_axis_y.png'>--></td>
  <td width='60' align='center' style='text-align:center'></td>
  <td width='350' colspan='5' align='center' style='text-align:center'></td>
</tr>
<?
	$axis_y = array('Insignificant<br>1', 'Minor<br>2', 'Moderate<br>3', 'Major<br>4', 'Catastrophic<br>5');
	for ($i=5; $i>=1; $i--) {
?>
<tr>
	<td width='60' align='center' style='font-size:11px'><?=$axis_y[$i-1]?></td>
<?		for ($j=1; $j<=5; $j++) {
			$l = $d[$i][$j];
			$b = '#444444';
			
			$desc = '('.number_filter2(intval($d_count1[$i][$j])).')';
?>	
	<td width='70' align='center' bgcolor='<?=risk_level_color($l)?>' style='border: 1px solid <?=$b?>; height: 60px; font-weight:bold; font-size: 13px' ><?=risk_level_name($l)?><br><div class='d1'><?=$desc?></div></td>
<?		}?>	
</tr>
<?
	}	
?>
<tr style='font-size:11px' align='center'>
  <td></td>
  <td></td>
  <td>1<br>Very Low</td>
  <td>2<br>Low</td>
  <td>3<br>Medium</td>
  <td>4<br>High</td>
  <td>5<br>Very High</td>
</tr>
<tr><td colspan='2' height='10'></td></tr>
<tr>
  <td></td>
  <td></td>
  <td colspan='5' align='center' style='font-size: 11px'>โอกาส / Likelihood / Frequency</td>
</tr>
</table>
<br>
</div>
<div class='col-md-6'>
<table class='' border='0' id='table_mat'>
<tr>
	<td colspan='8' align='center'><b>ตารางแสดงผลการวัดระดับความเสี่ยง <font color='green'><u>หลัง</u></font> การควบคุม</b><br><br></td>
</tr>
<tr>
  <td rowspan='6' width='100' style='font-size: 11px'>ผลกระทบ<br>Impact<!--<img src='images/risk_matrix_axis_y.png'>--></td>
  <td width='60' align='center' style='text-align:center'></td>
  <td width='350' colspan='5' align='center' style='text-align:center'></td>
</tr>
<?
	$axis_y = array('Insignificant<br>1', 'Minor<br>2', 'Moderate<br>3', 'Major<br>4', 'Catastrophic<br>5');
	for ($i=5; $i>=1; $i--) {
?>
<tr>
	<td width='60' align='center' style='font-size:11px'><?=$axis_y[$i-1]?></td>
<?		for ($j=1; $j<=5; $j++) {
			$l = $d[$i][$j];
			$b = '#444444';
			
			$desc = '('.number_filter2(intval($d_count2[$i][$j])).')';
?>	
	<td width='70' align='center' bgcolor='<?=risk_level_color($l)?>' style='border: 1px solid <?=$b?>; height: 60px; font-weight:bold; font-size: 13px' ><?=risk_level_name($l)?><br><div class='d1'><?=$desc?></div></td>
<?		}?>	
</tr>
<?
	}	
?>
<tr style='font-size:11px' align='center'>
  <td></td>
  <td></td>
  <td>1<br>Very Low</td>
  <td>2<br>Low</td>
  <td>3<br>Medium</td>
  <td>4<br>High</td>
  <td>5<br>Very High</td>
</tr>
<tr><td colspan='2' height='10'></td></tr>
<tr>
  <td></td>
  <td></td>
  <td colspan='5' align='center' style='font-size: 11px'>โอกาส / Likelihood / Frequency</td>
</tr>
</table>
<br>
</div>
</div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
	function openWindowWithPost(url, data) {
		var form = document.createElement("form");
		form.target = "_blank";
		form.method = "POST";
		form.action = url;
		form.style.display = "none";

		for (var key in data) {
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = key;
			input.value = data[key];
			form.appendChild(input);
		}

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}	
	
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart1);
      function drawChart1() {
        var data1 = google.visualization.arrayToDataTable([
          ['ID', 'Impact', 'Likelihood', 'Risk Level', 'จำนวน'],
		  ['',   -1,  -1,  1, 0],
		  ['',   -1,  -1,  4, 0],
<?
	$csv = array();
	$csv_data = array();
	$csv['title'] = 'ผลการวัดระดับความเสี่ยง ก่อน และ หลัง การควบคุม';
	$csv['header'] = array('Impact', 'Likelihood', 'Risk Level', 'จำนวนก่อนการควบคุม', 'จำนวนหลังการควบคุม');


	for ($i=5; $i>=1; $i--) {
		for ($j=1; $j<=5; $j++) {
			$l = intval($d[$i][$j]);
			$dd = intval($d_count1[$i-1][$j-1]);
			$dd2 = intval($d_count2[$i-1][$j-1]);
			if ($dd>0) echo "['$dd',   $j,  $i,  $l,  $dd],";
			
			
			$csv_data[] = array($i, $j, risk_level_name($l), intval($dd), intval($dd2));
		}
	}
	$csv['data'] = $csv_data;
	$csv['hash'] = md5(serialize($csv_data));		
	
?>
        ]);
        var options1 = {
			colorAxis: {colors: ['#00ff00', '#ffff00', '#ff9900', '#ff0000'],
				legend : { position: 'none'}
			},
			vAxis: { title: "ผลกระทบ / Impact", viewWindow:{ max:6, min:0}, gridlines: { color: '#efefef'}, textStyle: {color: 'none'} },
			hAxis: { title: "โอกาส / Likelihood / Frequency", viewWindow:{ max:6, min:0}, gridlines: { color: '#efefef'}, textStyle: {color: 'none'} },
        };
        var chart1 = new google.visualization.BubbleChart(document.getElementById('chart_div1'));
        chart1.draw(data1, options1);
      }     
	  
	  
	  google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart2);
      function drawChart2() {
        var data2 = google.visualization.arrayToDataTable([
          ['ID', 'Impact', 'Likelihood', 'Risk Level', 'จำนวน'],
		  ['',   -1,  -1,  1, 0],
		  ['',   -1,  -1,  4, 0],
<?
	for ($i=5; $i>=1; $i--) {
		for ($j=1; $j<=5; $j++) {
			$l = intval($d[$i][$j]);
			$dd = intval($d_count2[$i-1][$j-1]);
			if ($dd>0) echo "['$dd',   $j,  $i,  $l,  $dd],";
		}
	}
?>
        ]);
        var options2 = {
			colorAxis: {colors: ['#00ff00', '#ffff00', '#ff9900', '#ff0000'],
				legend : { position: 'none'}
			},
			vAxis: { title: "ผลกระทบ / Impact", viewWindow:{ max:6, min:0}, gridlines: { color: '#efefef'}, textStyle: {color: 'none'}},
			hAxis: { title: "โอกาส / Likelihood / Frequency", viewWindow:{ max:6, min:0}, gridlines: { color: '#efefef'}, textStyle: {color: 'none'} },    
        };
        var chart2 = new google.visualization.BubbleChart(document.getElementById('chart_div2'));
        chart2.draw(data2, options2);
      }
	  

	$(function () {	
		$('#export_csv7').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data7').val()
			});
		});			
	});		  
    </script>

<div class='row'>
	<div class='col-md-6'><div id="chart_div1" style="width: 650px; height: 500px;"></div></div>
	<div class='col-md-6'><div id="chart_div2" style="width: 650px; height: 500px;"></div></div>
</div>
<br>
<button type="button" id='export_csv7' class='btn btn-default'>Export Data as Excel</button>
<input type='hidden' id='c_data7' value='<?=json_encode($csv)?>'>
<br>
<br>
<?		
		}

	} else if ($view==6) {


		$dep_count = array();
		$sql = 'SELECT 
			COUNT(*) AS num,
			d.department_level_id,
			dl.department_level_name
		FROM department d
		JOIN department_level dl ON d.department_level_id = dl.department_level_id
		GROUP BY 
		d.department_level_id,
		dl.department_level_name';
		$result2=mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {
			$dep_count[$row2['department_level_id']] = array($row2['num'], $row2['department_level_id'], $row2['department_level_name']);
		}
		//print_r($dep_count);
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["Copper", 8.94, "#b87333"],
        ["Silver", 10.49, "silver"],
        ["Gold", 19.30, "gold"],
        ["Platinum", 21.45, "color: #e5e4e2"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Density of Precious Metals, in g/cm^3",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }
  </script>
<div id="barchart_values" style="width: 900px; height: 300px;"></div>

<?	
	} else if ($view==9) {
		$report = intval($_GET['report']);
?>

<div class='row'>
	<div class='col-md-2'>Report</div>
	<div class='col-md-3'>
		<select name='dep_type' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&report="+this.value+"#chart_begin"'>
			<option value=''>--- เลือก ---</option>
			<option value='1' <?if ($report==1) echo 'selected'?>>ส่วนที่ 1</option>
			<option value='2' <?if ($report==2) echo 'selected'?>>ส่วนที่ 2</option>
		<select>
	</div>
</div>
<div class='row'>
	<div class='col-md-2'>หน่วยงาน</div>
	<div class='col-md-3'>
		<select name='dep_type' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&report=<?=$report?>&dep_type="+this.value+"#chart_begin"'>
			<option value='2' <?if ($dep_type==2) echo 'selected'?>><?=dep_type(2)?></option>
			<option value='0' <?if ($dep_type==0) echo 'selected'?>><?=dep_type(0)?></option>
			<option value='1' <?if ($dep_type==1) echo 'selected'?>><?=dep_type(1)?></option>
			<option value='3' <?if ($dep_type==3) echo 'selected'?>>เลือกฝ่ายงาน</option>
		<select>
	</div>
</div>
<? if ($dep_type==3) {?>
<div class='row'>
	<div class='col-md-2'>ฝ่ายงาน</div>
	<div class='col-md-3'>
		<select name='select_dep_id' id='select_dep_id' class="form-control" onChange='document.location="csa_admin_report.php?view_year=<?=$view_year?>&view=<?=$view?>&report=<?=$report?>&dep_type=<?=$dep_type?>&select_dep_id="+this.value+"#chart_begin"'>
		<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM department WHERE department_level_id = '4' AND mark_del = '0' ";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
		<option value='<?=$row1['department_id']?>' <?if ($select_dep_id==$row1['department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<? } ?>				
		</select>

	</div>
</div>
<? }?>
<br>
<br>
<br>

<?		
		if ($report>0) {
			$is_show = false;
			if ($dep_type!=3 || ($dep_type==3 && $select_dep_id>0)) {
				
				$wsql = '';
				if ($dep_type==0 || $dep_type==1) {
					$wsql = " AND dep.is_branch = '$dep_type' ";
					$is_show = true;
				} else if ($dep_type==3 && $select_dep_id>0) {
					$wsql = " AND dep2.department_id = '$select_dep_id' ";
					$is_show = true;
				} else if ($dep_type==2) {
					$is_show = true;
				}
			}
			
			if ($report==1 && $is_show) {

				$csv = array();
				$csv_data = array();
				$csv['title'] = 'แบบสอบถามความเพียงพอของการควบคุมภายใน';
				$csv['header'] = array('ข้อ', 'คำถาม', 'มีการปฏิบัติครบถ้วน', 'มีการปฏิบัติบางส่วน', 'ไม่มีการปฏิบัติ');

				$q_result = array();
				$sql = "SELECT 
					SUM(IF(v=1, 1, 0)) AS v1, 
					SUM(IF(v=2, 1, 0)) AS v2, 
					SUM(IF(v=3, 1, 0)) AS v3, 
					COUNT(*) AS num,
					csa_q_topic_id
				FROM csa_questionnaire_data csa_q
				JOIN csa_department csa_dep ON csa_q.csa_department_id = csa_dep.csa_department_id
				JOIN department dep ON csa_dep.department_id3 = dep.department_id
				JOIN department dep2 ON dep.parent_id = dep2.department_id
				WHERE 
					csa_dep.mark_del = '0' AND
					csa_dep.is_enable = '1' AND
					csa_dep.csa_year = '$view_year' 
					$wsql
				GROUP BY 
				csa_q_topic_id ";
				$result2 = mysqli_query($connect, $sql);
				while ($row2 = mysqli_fetch_array($result2)) {
					$q_result[$row2['csa_q_topic_id']] = array($row2['v1'], $row2['v2'], $row2['v3']);
				}
				
				$i=1;	
				$sql = "SELECT 
					*
				FROM csa_questionnaire_topic
				WHERE 
					parent_id = '0' AND
					mark_del = '0' 
				ORDER BY
					q_no, q_name ";
				$result2 = mysqli_query($connect, $sql);
				if (mysqli_num_rows($result2)>0) {
					while ($row2 = mysqli_fetch_array($result2)) {				
						$csv_data[] = array($row2['q_no'], $row2['q_name'], '', '');

						$sql = "SELECT 
							*
						FROM csa_questionnaire_topic
						WHERE 
							parent_id = '$row2[csa_q_topic_id]' AND
							mark_del = '0' 
						ORDER BY
							q_no, q_name ";
						$result3 = mysqli_query($connect, $sql);
						while ($row3 = mysqli_fetch_array($result3)) {
							$v1 = $q_result[$row3['csa_q_topic_id']][1];
							$v2 = $q_result[$row3['csa_q_topic_id']][2];
							$v3 = $q_result[$row3['csa_q_topic_id']][3];

							$csv_data[] = array($row3['q_no'], $row3['q_name'], intval($v1), intval($v2), intval($v3));
						}
					}
				}
				$csv['data'] = $csv_data;
				$csv['hash'] = md5(serialize($csv_data));	
				
?>
<script>
	function openWindowWithPost(url, data) {
		var form = document.createElement("form");
		form.target = "_blank";
		form.method = "POST";
		form.action = url;
		form.style.display = "none";

		for (var key in data) {
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = key;
			input.value = data[key];
			form.appendChild(input);
		}

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}
	$(function () {	
		$('#export_csv1').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data1').val()
			});
		});		
	});	
</script>	
			<button type="button" id='export_csv1' class='btn btn-default'>Export Data as Excel</button>
			<input type='hidden' id='c_data1' value='<?=json_encode($csv)?>'>
			<br>
			<br>
<?				
			} else if ($report==2 && $is_show) {

				$control_list = array();
				$control_list2 = array();
				$control_list3 = array();
				$sql = "SELECT csa_control_id,control_name,is_other FROM csa_control WHERE mark_del = '0'";
				$result2 = mysqli_query($connect, $sql);
				while ($row2 = mysqli_fetch_array($result2)) {
					$control_list[$row2['csa_control_id']] = array($row2['csa_control_id'], $row2['control_name'], $row2['is_other']);
					$control_list2[] = $row2['control_name'];
					$control_list3[] = $row2['csa_control_id'];
				}					
				
				$csv = array();
				$csv_data = array();
				$csv['title'] = 'ส่วนที่ 2';
				$hd = array('ลำดับ','สาย', 'ฝ่าย/สำนักงาน','กลุ่ม','ขอบเขตหน้าที่ความรับผิดชอบของกลุ่มงาน', 
				'กลยุทธ์องค์กร', 'กระบวนการปฏิบัติงาน', 'วัตถุประสงค์', 'เหตุการณ์ความเสี่ยง', 'สาเหตุที่ทำให้เกิดความเสี่ยง',
				'ประเภทความเสี่ยง', 'ปัจจัยเสี่ยง', 'โอกาส', 'ผลกระทบ', 'ระดับความเสี่ยง', 'ผลการประเมินความเสี่ยงก่อนการควบคุม');

				//'มี นโยบาย/ระเบียบ/คำสั่ง/คู่มือ ที่ชัดเจน', 'มีการกำหนดตัวชี้วัดผลการดำเนินงาน (KPIs)');

				$hd = array_merge($hd, $control_list2); 
				array_push($hd, 'โอกาส', 'ผลกระทบ', 'ระดับความเสี่ยง', 'ผลการประเมินการควบคุมที่มีอยู่', 'ความเห็นของฝ่ายบริหารความเสี่ยง');
				
				$csv['header'] = $hd;
				
				$i = 1;
				$sql = "SELECT 
					c.*,
					r.is_other as risk_is_other,
					r.risk_type_name,
					j.job_function_no,
					j.job_function_name,
					dep.department_name AS dep1,
					dep2.department_name AS dep2,
					dep3.department_name AS dep3,
					f.factor as factor_name,
					f.is_other as factor_is_other,
					csa_dep.risk_comment
				FROM csa c
				JOIN csa_department csa_dep ON c.csa_department_id = csa_dep.csa_department_id
				JOIN department dep ON csa_dep.department_id3 = dep.department_id
				JOIN department dep2 ON dep.parent_id = dep2.department_id				
				JOIN department dep3 ON dep2.parent_id = dep3.department_id				
				JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
				LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
				JOIN csa_factor f ON c.factor = f.csa_factor_id AND f.mark_del = '0'
				WHERE 
					c.mark_del = '0' AND				
					csa_dep.mark_del = '0' AND
					csa_dep.is_enable = '1' AND
					csa_dep.csa_year = '$view_year' 
					$wsql ";	
				$result2 = mysqli_query($connect, $sql);
				while ($row2 = mysqli_fetch_array($result2)) {
					$j_list[] = $row2['job_function_id'];
					if ($row2['job_function_id']==999999) 
						$job_function = 'อื่นๆ : '.$row2['job_function_other'];
					else
						$job_function = $row2['job_function_name'];
					
					if ($row2['risk_is_other']==1) 
						$risk_type_name = 'อื่นๆ : '.$row2['risk_type_other'];
					else
						$risk_type_name = $row2['risk_type_name'];

					if ($row2['factor_is_other']==1) 
						$factor_name = 'อื่นๆ : '.$row2['factor_other'];
					else
						$factor_name = $row2['factor_name'];

					$csv_row = array($i, $row2['dep3'], $row2['dep2'], $row2['dep1'], $job_function, 
					html2text($row2['strategy']), html2text($row2['process']), html2text($row2['objective']),
					html2text($row2['event']), html2text($row2['cause']), $risk_type_name, $factor_name,
					$row2['csa_impact_id1'], $row2['csa_likelihood_id1'], risk_level_name($row2['csa_risk_level1']), risk_level_acceptable($row2['csa_risk_level1'])
					);

					$control_output = array();
					$control_array = array();
					$control = trim($row2['control']);
					if ($control!='') {
						$control_array = explode(',', $control);						
					}
					
					foreach ($control_list3 as $c) {
						if (in_array($c, $control_array)) {
							$is_other = $control_list[$c][2];
							if ($is_other==1) {
								$control_output[] = trim($row2['control_other'])!='' ? trim($row2['control_other']) : 'X - ไม่ระบุ' ;
							} else {
								$control_output[] = 'X';
							}
						} else {
							$control_output[] = '';
						}
					}
					$csv_row = array_merge($csv_row, $control_output); 
					array_push($csv_row, $row2['csa_impact_id2'], $row2['csa_likelihood_id2'], risk_level_name($row2['csa_risk_level2']), risk_level_acceptable($row2['csa_risk_level2']), html2text($row2['risk_comment']));
				
					$csv_data[] = $csv_row;
					$i++;
					
				}	
				$csv['data'] = $csv_data;
				$csv['hash'] = md5(serialize($csv_data));				
				
/*				echo '<pre>';
				print_r($csv);
				echo '</pre>';*/
?>
<script>
	function openWindowWithPost(url, data) {
		var form = document.createElement("form");
		form.target = "_blank";
		form.method = "POST";
		form.action = url;
		form.style.display = "none";

		for (var key in data) {
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = key;
			input.value = data[key];
			form.appendChild(input);
		}

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}
	$(function () {	
		$('#export_csv1').click(function() { 
			openWindowWithPost("csa_admin_report_csv.php", {
				csv_data : $('#c_data1').val()
			});
		});		
	});	
</script>	
			<button type="button" id='export_csv1' class='btn btn-default'>Export Data as Excel</button>
			<input type='hidden' id='c_data1' value='<?=json_encode($csv)?>'>
			<br>
			<br>
<?				
//ลำดับ	สาย	ฝ่าย/สำนักงาน	กลุ่ม	ขอบเขตหน้าที่ความรับผิดชอบของกลุ่มงาน	กลยุทธ์องค์กร	กระบวนการปฏิบัติงาน	
//วัตถุประสงค์	เหตุการณ์ความเสี่ยง	สาเหตุที่ทำให้เกิดความเสี่ยง	ประเภทความเสี่ยง	ปัจจัยเสี่ยง	โอกาส	ผลกระทบ	ระดับความเสี่ยง	ผลการประเมินความเสี่ยงก่อนการควบคุม	มี นโยบาย/ระเบียบ/คำสั่ง/คู่มือ ที่ชัดเจน	มีการกำหนดตัวชี้วัดผลการดำเนินงาน (KPIs)	มีการมอบหมายอำนาจ การอนุมัติที่ชัดเจน	มีการสอบทานโดยหัวหน้างาน หรือผู้บริหาร	การใช้ Check List	อื่นๆ	โอกาส	ผลกระทบ	ระดับความเสี่ยง	ผลการประเมินการควบคุมที่มีอยู่	ความเห็นของฝ่ายบริหารความเสี่ยง
			}
		}

	
	}

echo template_footer();

function dep_type($n) {
	switch ($n) {
		case 0: return 'สำนักงานใหญ่';
		case 1: return 'ภาค';
		case 2: return 'สำนักงานใหญ่ และ ภาค';
	}
}
?>