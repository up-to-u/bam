<?
include('inc/include.inc.php');
include('csa_function.php');

require_once 'phpexcel/PHPExcel.php';

echo template_header();

$print_id = intval($_GET['print_id']);
$view_dep = intval($_GET['view_dep']);
$view_year = intval($_GET['view_year']);
$view_env_id = intval($_GET['view_env_id']);

//echo "view year : $view_year";
//echo "<BR>view_env_id : $view_env_id";

if( ($view_year>0) && ($view_env_id > 0) ) {
	$mode = intval($_GET['mode']);
	$cond = "";
	if($mode == 1) {
		$cond = "AND ((department_name NOT LIKE 'สำนัก%'  ) OR (department_name LIKE 'สำนักกรรม%'  ))";
	} else if($mode == 2) {
		$cond = "AND ((department_name LIKE 'สำนักงาน%'  ) OR (department_name LIKE 'สำนักพหล%'  ))";
	}
	
	$sql = "
		SELECT department_name, csa_env.csa_env_topic_id, topic_name, sum(v) /count(v) as avg1 FROM csa_env_data 
		LEFT JOIN csa_env ON csa_env.csa_env_id = csa_env_data.csa_env_id
		LEFT JOIN csa_env_topic ON csa_env_topic.csa_env_topic_id = csa_env.csa_env_topic_id
		LEFT JOIN csa_department ON csa_department.csa_department_id = csa_env_data.csa_department_id
		WHERE csa_env_data.csa_year = ?
		  AND csa_env.csa_env_topic_id = ?
		  $cond
		GROUP BY  department_name";
	//echo "<BR>".$sql;	
	$stmt1 = $connect->prepare($sql);
	$stmt1->bind_param('ii', $view_year, $view_env_id);
	$stmt1->execute();
	$result = $stmt1->get_result();

	$arr = array();
	$row_arr = array();
	$topic_name = '';
	while ($row = $result->fetch_assoc()) { 
		if($topic_name == '') {
			$topic_name = $row['topic_name'];
		}	
	
		if($row['avg1'] >=4.5) {
			$arr[5][] = $row['department_name'];
			$row_arr[5] += 1;
		} else if($row['avg1'] >=3.5) {
			$arr[4][] = $row['department_name'];
			$row_arr[4] += 1;
		} else if($row['avg1'] >=2.5) {
			$arr[3][] = $row['department_name'];
			$row_arr[3] += 1;
		} else if($row['avg1'] >=1.5) {
			$arr[2][] = $row['department_name'];
			$row_arr[2] += 1;
		} else {
			$arr[1][] = $row['department_name'];
			$row_arr[1] += 1;
		} 		
	}	
	//print_r($row_arr);
	$max = 0;
	for($j=1;$j<=5;$j++) {
		if($row_arr[$j] > $max) {
			$max = $row_arr[$j];
		}	
	}	
	//echo "<BR> max : $max";
?>	
<H2><?= $topic_name ?></H2>
<table border='1' style='border-collapse: collapse;' width='1000'>
<tr valign='top' align='center'>
	<td width='200' class='cb' >ดีมาก</td>
	<td width='200' class='cb' >ดี</td>
	<td width='200' class='cb' >ปานกลาง</td>
	<td width='200' class='cb' >ควรปรับปรุง</td>
	<td width='200' class='cb' >ต้องปรับปรุงเร่งด่วน</td>
</tr>
<?
for($i=0; $i<=$max; $i++) {	?>
<tr valign='top' align='center'>
	<td width='200' class='cb' ><?= $arr[5][$i] ?></td>
	<td width='200' class='cb' ><?= $arr[4][$i] ?></td>
	<td width='200' class='cb' ><?= $arr[3][$i] ?></td>
	<td width='200' class='cb' ><?= $arr[2][$i] ?></td>
	<td width='200' class='cb' ><?= $arr[1][$i] ?></td>
</tr>	
	
<?
}	
?>

</table>
<?	
	exit;	
}

	$sql = "SELECT * FROM csa_department WHERE csa_department_id = '$view_dep' AND mark_del = '0' ";
	$result1 = mysqli_query($connect, $sql);
	if ($row1 = mysqli_fetch_array($result1)) {
		$is_confirm = $row1['is_confirm'];
		$view_year = $row1['csa_year'];
	}
	
	if ($view_year==0) {
		$view_year=date('Y')+543;
	}
?>
<div class="alert alert-info">
<div class='row'>
	<div class='col-md-2'>แสดงข้อมูล ของปี</div>
	<div class='col-md-2'>
	<select name='view_year' class="form-control" onChange='document.location="csa_report.php?view_year="+this.value'>
		<option value='<?=$view_year-2?>'><?=$view_year-2?></option>
		<option value='<?=$view_year-1?>'><?=$view_year-1?></option>
		<option value='<?=$view_year?>' selected><?=$view_year?></option>
		<option value='<?=$view_year+1?>'><?=$view_year+1?></option>
		<option value='<?=$view_year+2?>'><?=$view_year+2?></option>
	<select>
	</div>
	<!--<div class='col-md-3'>
<?
	$sql = "SELECT code FROM user WHERE user_id = '$user_id' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {
		$uid = $row2['code'];
		$sql = "SELECT * FROM csa_department 
				LEFT JOIN csa_authorize ON csa_department.csa_department_id = csa_authorize.csa_department_id
				WHERE csa_year = '$view_year' AND mark_del = '0' 
				  AND csa_authorize.csa_authorize_uid = '$uid' ";
		//echo $sql;
		$result1 = mysqli_query($connect, $sql);
?>
	<select name='view_dep' class="form-control" onChange='document.location="csa_report.php?view_year=<?=$view_year?>&view_dep="+this.value'>
		<option value=''>-เลือก-</option>
<?		
		while ($row1 = mysqli_fetch_array($result1)) {
?>
		<option value='<?=$row1['csa_department_id']?>' <?if ($view_dep==$row1['csa_department_id']) echo 'selected'?>><?=$row1['department_name']?></option>
<?
		}
?>
	<select>
<?		
	}
?>
	</div> -->
</div>
</div>
<br>
<br>

<?
	$step = 0;

	$sql = "SELECT COUNT(*) AS num FROM csa_env_data WHERE csa_year = '$view_year' AND csa_department_id = '$view_dep' AND v > 0 ";
	$result2 = mysqli_query($connect, $sql);
	$row2 = mysqli_fetch_array($result2);
	$n = $row2['num'];
	if ($n==25) $step = 1;	
	
	if ($step>=1) {
		$sql = "SELECT COUNT(*) AS num, SUM(is_finish) AS fin FROM csa WHERE csa_year = '$view_year' AND mark_del = '0' AND csa_department_id = '$view_dep' ";
		//echo $sql;
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);
		$n1 = $row2['num'];
		$n2 = $row2['fin'];
		if ($n1==$n2 && $n1>0) $step = 2;	
	}
		
	if ($step>=2) {
		$sql = "SELECT COUNT(*) AS num FROM csa_kri WHERE csa_year = '$view_year' AND mark_del = '0' AND csa_department_id = '$view_dep' ";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);
		$n = $row2['num'];
		if ($n>0) $step = 3;
	}
	
	/*if ($step>=3) {
		$sql = "SELECT COUNT(*) AS num FROM csa_action_plan WHERE csa_year = '$view_year' AND mark_del = '0' AND csa_department_id = '$view_dep' ";
		$result2 = mysqli_query($connect, $sql);
		$row2 = mysqli_fetch_array($result2);
		$n = $row2['num'];
		if ($n>0) $step = 4;
	}*/
	
	if ($step>=3 && $is_confirm==1) {
		$step = 4;
	}
	
	
?>
<script type="text/javascript" src="js/jquery.balloon.min.js"></script>

<script>

$(function () {
	save_tab();
	
	$('.envrb').change(function() {
		
		var v= $('input[type=radio].envrb:checked');
		var vv = [];
		$(v).each(function(i){
			var tid = $(this).attr('id');
			var topic = parseInt(tid.substring(1, 3));
			var tv = parseInt($(this).val());
			if (vv[topic]>0) 
				vv[topic] += tv;
			else 
				vv[topic] = tv;
		});
		$('#tdiv1').html(test_env_js(vv[1]));
		$('#tdiv2').html(test_env_js(vv[2]));
		$('#tdiv3').html(test_env_js(vv[3]));
		$('#tdiv4').html(test_env_js(vv[4]));
		$('#tdiv5').html(test_env_js(vv[5]));
	});
	
	$('.help1').balloon({ 
		contents: "ไม่มีมาตรการควบคุมใดๆ เช่น ไม่มีข้อมูล / ไม่มีการดำเนินการใดๆ ให้เห็นเป็นรูปธรรม",
		position: "bottom left",
		css: {
			fontSize: "1.6rem",
		}	
	});	
	$('.help2').balloon({ 
		contents: "มีกิจกรรมการควบคุมแล้ว แต่ยังขาดการสื่อสารให้ทราบอย่างทั่วถึง/ ไม่มีการนำมาปฏิบัติงานจริง/มีการนำมาปฏิบัติจริงแล้วแต่ขาดการติดตาม ควบคุม ดูแลที่ดี/มีความตระหนักต่อระบบการควบคุมภายในค่อนข้างน้อย",
		position: "bottom left",
		css: {
			fontSize: "1.6rem",
			width: "50rem"
		}	
	});	
	$('.help3').balloon({ 
		contents: "มีกิจกรรมการควบคุมแล้ว แต่ยังไม่มีการปรับปรุงให้เป็นปัจจุบัน และมีประสิทธิภาพ/มีการติดตาม ควบคุมและการรายงานไม่ต่อเนื่องสม่ำเสมอ/ผู้บริหารให้ความสำคัญต่อระบบการควบคุมภายในดี",
		position: "bottom center",
		css: {
			fontSize: "1.6rem",
			width: "50rem"
		}	
	});	
	$('.help4').balloon({ 
		contents: "มีกิจกรรมการควบคุมที่ดี มีการนำไปปฏิบัติจริงได้อย่างเป็นระบบ/ผู้บริหารให้ความสำคัญต่อระบบการควบคุมภายในดีมาก/มีการติดตาม ควบคุม ดูแลและรายงานอย่างต่อเนื่องสม่ำเสมอ",
		position: "bottom center",
		css: {
			fontSize: "1.6rem",
			width: "50rem"
		}	
	});	
	$('.help5').balloon({ 
		contents: "มีกิจกรรมการควบคุมเป็นอย่างดี สามารถลดความเสี่ยงได้อย่างมีประสิทธิภาพ และบรรลุวัตถุประสงค์ตามที่กำหนดไว้",
		position: "bottom center",
		css: {
			fontSize: "1.6rem",
		}	
	});		
});  

function test_env_js(s) {
	/*if (s<=14) return 'ต้องปรับปรุงโดยเร่งด่วน';
	if (s<=24) return 'ควรปรับปรุง';
	if (s<=34) return 'ปานกลาง';
	if (s<=44) return 'ดี';
	return 'ดีมาก';	*/
	if (s<=7) return 'ต้องปรับปรุงโดยเร่งด่วน';
	if (s<=12) return 'ควรปรับปรุง';
	if (s<=17) return 'ปานกลาง';
	if (s<=22) return 'ดี';
	return 'ดีมาก';
	
}

function save_tab() {
	if (location.hash) {
	  $('a[href=\'' + location.hash + '\']').tab('show');
	}
	var activeTab = localStorage.getItem('activeTab');
	if (activeTab) {
	  $('a[href="' + activeTab + '"]').tab('show');
	}

	$('body').on('click', 'a[data-toggle=\'tab\']', function (e) {
	  e.preventDefault()
	  var tab_name = this.getAttribute('href')
	  if (history.pushState) {
		history.pushState(null, null, tab_name)
	  }
	  else {
		location.hash = tab_name
	  }
	  localStorage.setItem('activeTab', tab_name)

	  $(this).tab('show');
	  return false;
	});
	$(window).on('popstate', function () {
	  var anchor = location.hash ||
		$('a[data-toggle=\'tab\']').first().attr('href');
	  $('a[href=\'' + anchor + '\']').tab('show');
	});	
}
</script>

<style>
.t1 {
	color: black !important;
}
</style>
<?
		$env = array();
		$sql = "SELECT 
			SUM(csa_env_data.v) AS num, count(csa_env_data.v) as c,
			csa_env.csa_env_topic_id AS topic
		FROM csa_env_data 
		JOIN csa_env ON csa_env.csa_env_id = csa_env_data.csa_env_id
		WHERE 
			csa_env_data.csa_year = '$view_year' AND 
			csa_env_data.v > 0 
		GROUP BY
			csa_env.csa_env_topic_id";	
		
		$result2 = mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {	
			//echo "<BR> num : ".($row2['num']/$row2['c']);
			$env[$row2['topic']] = $row2['num']/$row2['c'];
		}
		
		$rs = array();
		$sql = "SELECT 
			csa_env_topic_id,
			result1,
			result2,
			result3,
			result4,
			result5
		FROM csa_env_topic ";
		$result2 = mysqli_query($connect, $sql);
		while ($row2 = mysqli_fetch_array($result2)) {	
			$rs[$row2['csa_env_topic_id']] = array(
				$row2['result1'],
				$row2['result2'],
				$row2['result3'],
				$row2['result4'],
				$row2['result5']
			);
		}
?>

<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
<br>
<form method='post' action='csa_report.php?view_dep=<?=$view_dep?>&view_year=<?=$view_year?>'>
<div class='alert alert-info'>
<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">แบบ ปค. ๔</a></li>
		<!--<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="true">แบบ ปค. ๕</a></li>-->
		<li class=""><a href="#tab3" data-toggle="tab" aria-expanded="true">สรุปผลแบบตาราง</a></li>
		<li class=""><a href="#tab4" data-toggle="tab" aria-expanded="true">Export</a></li>
	</ul>
	<div class="tab-content">
		<br>
		<div class="tab-pane active" id="tab1">

<div id='print_area'>
<div align='right'><b>แบบ ปค. ๔</b></div>		
		
<div align='center'><b>ธนาคารพัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย<BR>รายงานการประเมินองค์ประกอบการควบคุมภายใน<br>
สำหรับระยะเวลาการดำเนินงานสิ้นสุด  มกราคม <?= $view_year?> ถึง ธันวาคม <?= $view_year?></b></div>
<center>
<table border='1' style='border-collapse: collapse;' width='1200'>
<tr valign='top' align='center'>
	<td width='600' class='cb'>องค์ประกอบการควบคุมภายใน</td>
	<td width='600' class='cb'>ผลการประเมิน / ข้อสรุป</td>
</tr>
<tr>
<td width='600' class='cb'>๑. สภาพแวดล้อมการควบคุม <BR>
&nbsp;&nbsp;- ทัศนคติของผู้บริหารและบุคลากร ที่เอื้อต่อการควบคุมภายใน<br>
&nbsp;&nbsp;-การให้ความสำคัญกับการ มีศีลธรรม จรรยาบรรณและความซื่อสัตย์ ของผู้บริหาร กรณีถ้าพบว่าบุคลากรมีการประพฤติปฏิบัติที่ไม่เหมาะสม จะมีการพิจารณาดำเนินการตามควรแก่กรณี <br>
&nbsp;&nbsp;- เจ้าหน้าที่ผู้ปฏิบัติงานมีความรู้ความสามารถเหมาะสมกับงาน <br>
&nbsp;&nbsp;- เจ้าหน้าที่ผู้ปฏิบัติงานได้รับทราบข้อมูลและการวินิจฉัยสิ่งที่ตรวจพบหรือสิ่งที่ต้องตรวจสอบ<br>
&nbsp;&nbsp;- ปรัชญาและรูปแบบการทำงานของผู้บริหารเหมาะสมต่อการพัฒนาการควบคุมภายในและดำรงไว้ซึ่งการควบคุมภายในที่มีประสิทธิผล<br>
&nbsp;&nbsp;- โครงสร้างองค์กร การมอบอำนาจหน้าที่ความรับผิดชอบและจำนวนผู้ปฏิบัติงานเหมาะสมกับงานที่ปฏิบัติ <br>
&nbsp;&nbsp;- นโยบายและการปฏิบัติด้านบุคลากรเหมาะสมในการจูงใจและสนับสนุนผู้ปฏิบัติงาน<br></td>
<td valign='top' align='center' width='600' style='word-wrap: break-word;'>[<?=test_env_report($env[1])?>]<br><?=$rs[1][test_env2_report($env[1])]?></td>
</tr>
<tr>
<td width='600' class='cb'>๒. การประเมินความเสี่ยง (Risk Assessment) <BR>
&nbsp;&nbsp;- การกำหนดวัตถุประสงค์ระดับองค์กรที่ชัดเจน<br>
&nbsp;&nbsp;- วัตถุประสงค์ระดับองค์กรและวัตถุประสงค์ระดับกิจกรรมสอดคล้องกันในการที่จะทำงานให้สำเร็จด้วยงบประมาณและทรัพยากรที่กำหนดไว้อย่างเหมาะสม <br>
&nbsp;&nbsp;- การระบุความเสี่ยงทั้งจากปัจจัยภายในและภายนอกที่อาจมีผลกระทบต่อการบรรลุวัตถุประสงค์ขององค์กร <br>
&nbsp;&nbsp;- การวิเคราะห์ความเสี่ยงและการบริหารความเสี่ยงที่เหมาะสม <br>
&nbsp;&nbsp;- กลไกที่ชี้ให้เห็นถึงความเสี่ยงที่เกิดจากการเปลี่ยนแปลง เช่น การเปลี่ยนแปลงวิธีการจัดการ เป็นต้น	<br></td>
<td valign='top' align='center' width='600' style='word-wrap: break-word;'>[<?=test_env_report($env[2])?>]<br><?=$rs[2][test_env2_report($env[2])]?></td>
</tr>
<tr>
<td width='600' class='cb' valign='top'>๓. กิจกรรมการควบคุม (Control Activities) <BR>
&nbsp;&nbsp;- นโยบายและวิธีปฏิบัติงานที่ทำให้มั่นใจว่า เมื่อนำไปปฏิบัติแล้วจะเกิดผลสำเร็จตามที่ฝ่ายบริหารกำหนดไว้ <br>
&nbsp;&nbsp;- กิจกรรมเพื่อการควบคุมจะชี้ให้ผู้ปฏิบัติงานเห็นความเสี่ยงที่อาจเกิดขึ้นในการปฏิบัติงาน เพื่อให้เกิดความระมัดระวังและสามารถปฏิบัติงานให้สำเร็จตามวัตถุประสงค์	<br></td>
<td valign='top' align='center' width='600' style='word-wrap: break-word;'>[<?=test_env_report($env[3])?>]<br><?=$rs[3][test_env2_report($env[3])]?></td>
</tr>
<tr>
<td width='600' class='cb' valign='top'>๔. ข้อมูล ข่าวสารและการสื่อสาร (Information & Communication) <BR>
&nbsp;&nbsp;- ระบบข้อมูลสารสนเทศที่เกี่ยวเนื่องกับการปฏิบัติงาน การรายงานทางการเงินและการดำเนินงาน การปฏิบัติตามนโยบายและระเบียบปฏิบัติต่างๆ ที่ใช้ในการควบคุมและดำเนินกิจกรรมขององค์กร รวมทั้งข้อมูลสารสนเทศที่ได้จากภายนอกองค์กร<br>
&nbsp;&nbsp;- การสื่อสารข้อมูลสารสนเทศต่างๆไปยังผู้บริหารและผู้ใช้ภายในองค์กร ในรูปแบบที่ช่วยให้ผู้รับข้อมูลสารสนเทศปฏิบัติหน้าที่ตามความรับผิดชอบได้อย่างมี ประสิทธิภาพและประสิทธิผล และให้ความมั่นใจว่า มีการติดต่อสื่อสารภายในและภายนอกองค์กร ที่มีผลทำ ให้องค์กรบรรลุวัตถุประสงค์และเป้าหมาย	<br></td>
<td valign='top' align='center' width='600' style='word-wrap: break-word;'>[<?=test_env_report($env[4])?>]<br><?=$rs[4][test_env2_report($env[4])]?></td>
</tr>
<tr>
<td width='600' class='cb' valign='top'>๕. การติดตาม (Monitoring)  <BR>
&nbsp;&nbsp;-  การติดตามประเมินผลการควบคุมภายในและประเมินคุณภาพการปฏิบัติงานโดยกำหนดวิธีปฏิบัติงานเพื่อติดตามการปฏิบัติตามระบบการควบคุมภายในอย่างต่อเนื่องและเป็นส่วนหนึ่งของกระบวนการปฏิบัติงานตามปกติของฝ่ายบริหาร<br>
&nbsp;&nbsp;- การประเมินผลแบบรายครั้ง(Separate Evaluation) เป็นครั้งคราว กรณีพบจุดอ่อนหรือข้อบกพร่องควรกำหนดวิธีปฏิบัติเพื่อให้ความมั่นใจว่า ข้อตรวจพบจากการตรวจสอบและการสอบทานได้รับการพิจารณาสนองตอบ และมีการวินิจฉัยสั่งการให้ดำเนินการแก้ไข ข้อบกพร่องทันที	<br></td>
<td valign='top' align='center' width='600' style='word-wrap: break-word;'>[<?=test_env_report($env[5])?>]<br><?=$rs[5][test_env2_report($env[5])]?></td>
</tr>


</table>
</center>
<p>
<div style='color:black;margin-left:100px;padding:10px;'><b>ผลการประเมินโดยรวม<br><BR></b>
....................................................................................................................................................................................................<BR><BR>
....................................................................................................................................................................................................<BR><BR>
....................................................................................................................................................................................................<BR>
<BR>
<div align='right'>
ลายมือชื่อ ..................................................<br>
   (................................................)<br>
ตำแหน่ง ................................................<br>
วันที่ ........................................<br>
</div>
</div>

</div>
<button type="button" class="btn btn-primary" onClick='window.open ("form_print.php?id=print_area","print_form", "addressbar=no,scrollbars=yes,resizable=no,status=no, fullscreen=no,location=no, toolbar=no, menubar=no,top=0,left=0,width=750,height=750");'><i class="fa fa-print"></i> พิมพ์</button>	


		</div>
		<div class="tab-pane" id="tab2">	
<div align='right'><b>แบบ ปค. ๕</b></div>	
<div align='center'><b><?=$dep_name?><BR>รายงานการประเมินผลการควบคุมภายใน<br>
สำหรับระยะเวลาการดำเนินงานสิ้นสุด  <?=$print_year?></b></div>

<center>
<table border='1' style='border-collapse: collapse;' width='1200'>
<tr valign='top' align='center'>
	<td width='400' class='cb'>ภารกิจตามกฎหมายที่จัดตั้งหน่วยงานของรัฐ<BR>หรือภารกิจตามแผนการดำเนินการ<BR>
							   หรือภารกิจอื่นๆ ที่สำคัญของหน่วยงานของรัฐ/<BR>
							   วัตถุประสงค์  </td>
	<td width='100' class='cb'>ความเสี่ยง</td>
	<td width='100' class='cb'>การควบคุมภายในที่มีอยู่</td>
	<td width='100' class='cb'>กาประเมินผลการควบคุมภายใน</td>
	<td width='100' class='cb'>ความเสี่ยงที่มีอยู่</td>
	<td width='100' class='cb'>การปรับปรุงการควบคุมภายใน</td>
	<td width='100' class='cb'>หน่วยงานที่รับผิดชอบ/กำหนดเสร็จ</td>
</tr>
</table>
</center>

		</div>
	<div class="tab-pane" id="tab3">

<?
	// แสดงคะแนนค่าเฉลี่ยของผลการประเมินการควบคุมภายในแต่ละหน่วยงาน
	$sql = "
		SELECT department_name, csa_env.csa_env_topic_id, topic_name, sum(v) /count(v) as avg1 FROM csa_env_data 
		LEFT JOIN csa_env ON csa_env.csa_env_id = csa_env_data.csa_env_id
		LEFT JOIN csa_env_topic ON csa_env_topic.csa_env_topic_id = csa_env.csa_env_topic_id
		LEFT JOIN csa_department ON csa_department.csa_department_id = csa_env_data.csa_department_id
		WHERE csa_env_data.csa_year = ? 
		GROUP BY department_name
		ORDER BY department_name";
	$stmt1 = $connect->prepare($sql);
	$stmt1->bind_param('i', $view_year);
	$stmt1->execute();
	$result = $stmt1->get_result();
	$arr_chart = [];
	while ($row = $result->fetch_assoc()) {
		$arr_chart[$row['department_name']] = $row['avg1'];
		$avg_sum += $row['avg1'];
	}
	$avg = $avg_sum / count($arr_chart);
	
?>
	
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart', 'bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
		['หน่วยงาน', 'ค่าการประเมิน', 'ค่าเฉลี่ย'],
		
		<?
			if($arr_chart){
				foreach($arr_chart as $dep => $val){
		?>
				[ '<?=$dep?>' , <?=$val?> , <?=$avg?> ],
		<?
				}
			}else{
		?>
				['',0,0]
		<?
			}
		?>
		
        ]);

        var options = {
			title : 'ค่าเฉลี่ยของผลการประเมินการควบคุมภายในแต่ละหน่วยงาน',
			//width: 900,
			//height: 500,
			hAxis: {
				title: 'หน่วยงาน',
				textStyle: {
					fontSize: 10,
				},
				titleTextStyle: {
					color: '#1a237e',
					fontSize: 14,
					bold: true,
					italic: true
				},
			},
			vAxis: {
				title: 'ค่าการประเมิน',
				minValue: 0,
				format: 'decimal',
				textStyle: {
					color: '#1a237e',
					fontSize: 12,
				},
				titleTextStyle: {
					color: '#1a237e',
					fontSize: 14,
					bold: true
				},
			},
			seriesType: 'bars',
			series: {1: {type: 'line'}},
			tooltip: {trigger: 'selection'},
			//selectionMode: 'multiple',
			//aggregationTarget: 'category',
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_control'));
        chart.draw(data, options);
      }
</script>
<center><div id="chart_control" style="width: 1500px; height: 500px;"></div></center>
<br><br>

<center>
<table border='1' style='border-collapse: collapse;' width='1200'>
<tr valign='top' align='center'>
	<td width='400' class='cb' rowspan='2' valign='middle'>องค์ประกอบของการควบคุม</td>
	<td width='100' class='cb' colspan='2'>ผลการประเมิน</td>
</tr>
<tr valign='top' align='center'>
	<td width='100' class='cb'>สำนักงานใหญ่ </td>
	<td width='100' class='cb'>เขต</td>
</tr>

<?	
	$sql1 = "SELECT department_name, csa_env_topic_id, count(v) as c1, sum(v) as s1  FROM `csa_env_data` 
			LEFT JOIN csa_env ON csa_env.csa_env_id = csa_env_data.csa_env_id
			LEFT JOIN csa_department ON csa_department.csa_department_id = csa_env_data.csa_department_id
			WHERE ((department_name NOT LIKE 'สำนัก%'  ) OR (department_name LIKE 'สำนักกรรม%'  ))
			AND csa_env_data.csa_year = '$view_year'
			GROUP BY csa_env_topic_id";
			
		$result2 = mysqli_query($connect, $sql1);
		$env_array = array();
		while ($row2 = mysqli_fetch_array($result2)) {			
			$env_array[$row2['csa_env_topic_id']] = number_format($row2['s1']/$row2['c1'],2);
		}
		
$sql = "SELECT department_name, csa_env_topic_id, count(v) as c1, sum(v) as s1  FROM `csa_env_data` 
		LEFT JOIN csa_env ON csa_env.csa_env_id = csa_env_data.csa_env_id
		LEFT JOIN csa_department ON csa_department.csa_department_id = csa_env_data.csa_department_id
		WHERE ((department_name LIKE 'สำนักงาน%'  ) OR (department_name LIKE 'สำนักพหล%'  ))
		AND csa_env_data.csa_year = '$view_year' 
		GROUP BY csa_env_topic_id";
		$result2 = mysqli_query($connect, $sql);
		$env_array2 = array();
		while ($row2 = mysqli_fetch_array($result2)) {			
			$env_array2[$row2['csa_env_topic_id']] = number_format($row2['s1']/$row2['c1'],2);
		}		
		for($i=1; $i<=5; $i++) { 	
?>
<tr valign='top'>
	<td width='400' class='cb' valign='middle'><a href="csa_report.php?view_env_id=<?= $i ?>&view_year=<?= $view_year ?>" target='_BLANK'><?= envi($i) ?></a></td>
	<td width='100' class='cb' align='center'><a href="csa_report.php?view_env_id=<?= $i ?>&view_year=<?= $view_year ?>&mode=1" target='_BLANK'><? echo result_env($env_array[$i])." (".$env_array[$i].")";?></a></td>
	<td width='100' class='cb' align='center'><a href="csa_report.php?view_env_id=<?= $i ?>&view_year=<?= $view_year ?>&mode=2" target='_BLANK'><? echo result_env($env_array2[$i])." (".$env_array2[$i].")";?></a></td>
	</tr>
<?	} ?>	

</table>
</center>

<BR><BR>
<?
	$sql = "SELECT risk_type, count(csa_id) as cnt FROM CSA 
			WHERE ((department_name NOT LIKE 'สำนัก%'  ) OR (department_name LIKE 'สำนักกรรม%'  ))
			  AND csa_year = '$view_year'
			  AND mark_del='0' 
			GROUP BY risk_type";
	$result1 = mysqli_query($connect, $sql);
	$cnt1 = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		if($row1['risk_type'] == 0) {
			continue;
		} else {	
		   $cnt_array[1][$row1['risk_type']] = $row1['cnt'];
		   $cnt1 += $row1['cnt'];
		}  
	}	
	
	$sql = "SELECT risk_type, count(csa_id) as cnt FROM CSA 
			WHERE ((department_name LIKE 'สำนักงาน%'  ) OR (department_name LIKE 'สำนักพหล%'  ))
			  AND csa_year = '$view_year'
			  AND mark_del='0' 
			GROUP BY risk_type";		
	$result1 = mysqli_query($connect, $sql);
	$cnt2 = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		if($row1['risk_type'] == 0) {
			continue;
		} else {
			$cnt_array[2][$row1['risk_type']] = $row1['cnt'];
			$cnt2 += $row1['cnt'];
		}
	}	

	$sql = "SELECT * FROM csa_risk_type ";
	$result1 = mysqli_query($connect, $sql);
	$risk_type_array = array();
	$risk_subtype_array = array();
	while ($row1 = mysqli_fetch_array($result1)) {
		if($row1['is_leaf_node'] == 0) {
			$risk_type_array[$row1['csa_risk_type_id']] = $row1['risk_type_name'];
		} else {
			$risk_subtype_array[$row1['parent_id']][1][] = $row1['csa_risk_type_id'];
			$risk_subtype_array[$row1['parent_id']][2][] = $row1['risk_type_name'];
		}	
	}	
	$j = 1;
	$k1 = array_keys($risk_type_array);
	$k2 = array_keys($risk_subtype_array);
?>
<div class="row">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
		['ประเภทการตอบกลับ', 'จำนวน'],

<? 	$max = 0;
	for($i=0; $i<count($k1); $i++) {
		$v1 = 0;
		$v2 = 0;	
		for($j=0; $j < count($risk_subtype_array[$k1[$i]][2]); $j++) {
			$v1 += $cnt_array[1][ $risk_subtype_array[$k1[$i]][1][$j] ];
			$v2 += $cnt_array[2][ $risk_subtype_array[$k1[$i]][1][$j] ];
		}		
		if(($v1+$v2) > $max ) {	
			$max = $v1+$v2;
			$max_id = $k1[$i]; 
		}
?>			
			[ '<?= $risk_type_array[$k1[$i]] ?>' ,  <?= $v1+$v2 ?> ],
<? 
	} 
?>		
        ]);

        var options = {
          title: 'ประเภทความเสี่ยง',
		  pieSliceText: 'value-and-percentage',
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart5'));

        chart.draw(data, options);
      }
    </script>
 <center><div id="piechart5" style="width: 900px; height: 500px;"></div></center>
 
 <?
	$sql = "SELECT csa.risk_type, count(csa_id) as cnt, parent_id, risk_type_name FROM CSA 
			LEFT JOIN csa_risk_type ON csa_risk_type.csa_risk_type_id = csa.risk_type
			WHERE csa_year = '$view_year'
			  AND parent_id = '$max_id'
			  AND csa.mark_del='0' 
			GROUP BY csa.risk_type
			ORDER BY cnt DESC";
	//echo $sql;		
	$result1 = mysqli_query($connect, $sql);
?>
 
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['', 'หน่วย : งานย่อย', { role: 'annotation' } ],
<?	for($i=1; $i<=3; $i++) {	
		$row1 = mysqli_fetch_array($result1);	?>
		['<?= $row1['risk_type_name'] ?>' ,  <?= $row1['cnt'] ?>, '<?= $row1['cnt'] ?>'],
<?	}	?> 
      ]);
		   
      var options = {
        title: 'รายละเอียดผลประเมินความเสี่ยง 3 อันดับแรก',
        chartArea: {width: '25%'},
        hAxis: {
          title: 'จำนวนงานย่อย',
          minValue: 0
        },
        vAxis: {
          title: 'ประเภทความเสี่ยง'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));

      chart.draw(data, options);
    }
 </script>
	<center><div id="chart_div2" style='margin-left:200px;'></div></center>
 <BR><BR>
</div>

<center>
<table border='1' style='border-collapse: collapse;' width='1200'>
<tr valign='top' align='center'>
	<td width='400' class='cb' rowspan='2' valign='middle'>ประเภทความเสี่ยง</td>
	<td width='160' class='cb' colspan='2'>สนญ</td>
	<td width='160' class='cb' colspan='2'>เขต</td>
	<td width='160' class='cb' colspan='2'>รวม</td>
</tr>
<tr valign='top' align='center'>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>	
</tr>
<?

	
	
	for($i=0; $i<count($k1); $i++) {	?>
		<tr><td><?= $risk_type_array[$k1[$i]]?></td></tr>
<?		$ratio1 = 0;
		$ratio2 = 0;
		
		$v1 = 0;
		$v2 = 0;
		for($j=0; $j < count($risk_subtype_array[$k1[$i]][2]); $j++) {
			$v1 += $cnt_array[1][ $risk_subtype_array[$k1[$i]][1][$j] ];
			$v2 += $cnt_array[2][ $risk_subtype_array[$k1[$i]][1][$j] ];
		}	
		
		for($j=0; $j < count($risk_subtype_array[$k1[$i]][2]); $j++) {	?>
			<tr><td>&nbsp;&nbsp;&nbsp;<?= $risk_subtype_array[$k1[$i]][2][$j]?> </td>
			<td align='center'><?if( $cnt_array[1][ $risk_subtype_array[$k1[$i]][1][$j] ] > 0) {
					echo $cnt_array[1][ $risk_subtype_array[$k1[$i]][1][$j] ]; } else { echo "0"; } 
				?></td>
			<td align='center'>
<? 					$tmp1 = number_format( ($cnt_array[1][ $risk_subtype_array[$k1[$i]][1][$j] ]/$v1)*100, 2);
					echo  $tmp1 ?></td>
			<td align='center'><?if( $cnt_array[2][ $risk_subtype_array[$k1[$i]][1][$j] ] > 0) {
					echo $cnt_array[2][ $risk_subtype_array[$k1[$i]][1][$j] ]; } else { echo "0"; } 
				?></td>
			<td align='center'>
<?					$tmp2 =  number_format( ($cnt_array[2][ $risk_subtype_array[$k1[$i]][1][$j] ]/$v2)*100, 2);
					echo  $tmp2; ?></td>
				
			<td align='center'><?echo ($cnt_array[1][ $risk_subtype_array[$k1[$i]][1][$j] ] + $cnt_array[2][ $risk_subtype_array[$k1[$i]][1][$j] ]);  ?></td>
			<td align='center'><?echo number_format(($cnt_array[1][ $risk_subtype_array[$k1[$i]][1][$j] ] + $cnt_array[2][ $risk_subtype_array[$k1[$i]][1][$j] ] )/($v1+$v2)*100,2);  ?></td>	
			</tr>
<?		
			
			$ratio1 += $tmp1;
			$ratio2 += $tmp1;
		}	?>
			<tr><td align='right'> รวม&nbsp;&nbsp;</td>
				<td align='center'><?= $v1 ?></td><td align='center'><? //$ratio1?></td>
				<td align='center'><?= $v2 ?></td><td align='center'><? //$ratio2?></td>
				<td align='center'><?= $v1+$v2 ?></td><td align='center'><? //$ratio1+$ratio2 ?></td>
			</tr>
<?	}	?>
		<tr><td align='right'>รวมทั้งหมด&nbsp;&nbsp;</td><td align='center'><?= $cnt1 ?></td><td></td><td align='center'><?= $cnt2 ?></td><td></td><td align='center'><?= $cnt1+$cnt2 ?></td><td></td></tr>
<??>

</table>
</center>
	
<BR><BR>
<center>
<table border='1' style='border-collapse: collapse;' width='1200'>
<tr valign='top' align='center'>
	<td width='400' class='cb' rowspan='2' valign='middle'>ข้อกฎหมาย<BR>ที่เกี่ยวข้องกับการปฏิบัติงาน</td>
	<td width='160' class='cb' colspan='2'>สนญ</td>
	<td width='160' class='cb' colspan='2'>เขต</td>
	<td width='160' class='cb' colspan='2'>รวม</td>
</tr>
<tr valign='top' align='center'>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>	
</tr>
<?
	$sql = "SELECT department_name, csa_law_data.csa_law_id as id, law_name, count(csa_law_data_id) as cnt FROM csa_law_data
		LEFT JOIN csa ON csa.csa_id = csa_law_data.csa_id
		LEFT JOIN csa_law ON csa_law.csa_law_id = csa_law_data.csa_law_id
		WHERE csa_law_data.mark_del = 0 
		AND csa_law_data.csa_law_id != 0
		AND csa_year = '$view_year'
		AND ((department_name NOT LIKE 'สำนัก%'  ) OR (department_name LIKE 'สำนักกรรม%'  ))
		GROUP BY csa_law_data.csa_law_id";
	$result1 = mysqli_query($connect, $sql);
	$law_head_array = array();
	$sum_head = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		$law_head_array[$row1['id']] = $row1['cnt'];
		$sum_head += $row1['cnt'];
	}	
	//print_r($law_head_array);
	$sql = "SELECT department_name, csa_law_data.csa_law_id as id, law_name, count(csa_law_data_id) as cnt FROM csa_law_data
		LEFT JOIN csa ON csa.csa_id = csa_law_data.csa_id
		LEFT JOIN csa_law ON csa_law.csa_law_id = csa_law_data.csa_law_id
		WHERE csa_law_data.mark_del = 0 
		AND csa_law_data.csa_law_id != 0
		AND csa_year = '$view_year'
		AND ((department_name LIKE 'สำนักงาน%'  ) OR (department_name LIKE 'สำนักพหล%'  ))
		GROUP BY csa_law_data.csa_law_id";
	$result1 = mysqli_query($connect, $sql);
	$law_nothead_array = array();
	$sum_nothead = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		$law_nothead_array[$row1['id']] = $row1['cnt'];
		$sum_nothead += $row1['cnt'];
	}	

	$sql = "SELECT csa_law_data.csa_law_id, law_name, count(csa_law_data_id) as cnt FROM csa_law_data
			LEFT JOIN csa ON csa.csa_id = csa_law_data.csa_id
			LEFT JOIN csa_law ON csa_law.csa_law_id = csa_law_data.csa_law_id
			WHERE csa_law_data.mark_del = 0 AND csa_law_data.csa_law_id != 0
			AND csa_year = '$view_year'
			GROUP BY csa_law_data.csa_law_id";
	$result1 = mysqli_query($connect, $sql);
	while ($row1 = mysqli_fetch_array($result1)) {	?>
		<tr><td ><?= $row1['law_name']?></td>
			<td align='center'>
<? 			if($law_head_array[$row1['csa_law_id']] > 0) {echo $law_head_array[$row1['csa_law_id']];} else {echo '0';}?></td>
			<td align='center'><?= number_format(($law_head_array[$row1['csa_law_id']]/$sum_head)*100, 2)?></td>
			<td align='center'>
<? 			if($law_nothead_array[$row1['csa_law_id']] > 0) {echo $law_nothead_array[$row1['csa_law_id']];} else {echo '0';}?></td>
			<td align='center'><?= number_format(($law_nothead_array[$row1['csa_law_id']]/$sum_head)*100, 2)?></td>
			<td align='center'>
<?= 		($law_head_array[$row1['csa_law_id']] + $law_nothead_array[$row1['csa_law_id']])?></td>
			<td align='center'><?= number_format((($law_head_array[$row1['csa_law_id']]+ $law_nothead_array[$row1['csa_law_id']])/($sum_head+$sum_nothead))*100, 2)?></td>
<?	}	
?>
	<tr><td align='right'>รวมทั้งหมด&nbsp;</td><td align='center'><?= $sum_head ?></td><td></td>
				 <td align='center'><?= $sum_nothead ?></td><td></td>
				 <td align='center'><?= $sum_head+$sum_nothead ?></td><td></td>
	</tr>
</table>	
</center>
	
<BR><BR>
<?
$sql = "SELECT factor, count(csa_id) as cnt FROM CSA 
			WHERE ((department_name NOT LIKE 'สำนัก%'  ) OR (department_name LIKE 'สำนักกรรม%'  ))
			  AND csa_year = '$view_year'
			  AND mark_del='0' 
			GROUP BY factor";
	$result1 = mysqli_query($connect, $sql);
	$cnt_array = array();
	$cnt1 = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		if($row1['factor'] == 0) {
			continue;
		} else {	
		   $cnt_array[1][$row1['factor']] = $row1['cnt'];
		   $cnt1 += $row1['cnt'];
		}  
	}	
	
	$sql = "SELECT factor, count(csa_id) as cnt FROM CSA 
			WHERE ((department_name LIKE 'สำนักงาน%'  ) OR (department_name LIKE 'สำนักพหล%'  ))
			  AND csa_year = '$view_year'
			  AND mark_del='0' 
			GROUP BY factor";		
				
	$result1 = mysqli_query($connect, $sql);
	$cnt2 = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		if($row1['factor'] == 0) {
			continue;
		} else {
			$cnt_array[2][$row1['factor']] = $row1['cnt'];
			$cnt2 += $row1['cnt'];
		}
	}
	/*echo "<PRE>";
	print_r($cnt_array[2]);
	echo "</PRE>";*/
	

	$sql = "SELECT * FROM csa_factor ";
	$result1 = mysqli_query($connect, $sql);
	$risk_factor_array = array();
	$risk_subfactor_array = array();
	while ($row1 = mysqli_fetch_array($result1)) {
		if($row1['is_leaf_node'] == 0) {
			$risk_factor_array[$row1['csa_factor_id']] = $row1['factor'];
		} else {
			$risk_subfactor_array[$row1['parent_id']][1][] = $row1['csa_factor_id'];
			$risk_subfactor_array[$row1['parent_id']][2][] = $row1['factor'];
		}	
	}	
	$j = 1;
	$k1 = array_keys($risk_factor_array);
	$k2 = array_keys($risk_subfactor_array);
?>

<center>
<div class="row">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
		['ประเภทการตอบกลับ', 'จำนวน'],

<? 	$max = 0;
	for($i=0; $i<count($k1); $i++) {
		$v1 = 0; 
		$v2 = 0; 
		
		for($j=0; $j < count($risk_subfactor_array[$k1[$i]][2]); $j++) {
			$v1 += $cnt_array[1][ $risk_subfactor_array[$k1[$i]][1][$j] ];
			$v2 += $cnt_array[2][ $risk_subfactor_array[$k1[$i]][1][$j] ];
		}
		if(($v1+$v2) > $max ) {	
			$max = $v1+$v2;
			$max_id = $k1[$i]; 
		}
?>			
			[ '<?= $risk_factor_array[$k1[$i]] ?>' ,  <?= $v1+$v2 ?> ],
<? 
	} 
?>		
        ]);

        var options = {
          title: 'ปัจจัยเสี่ยง',
		  pieSliceText: 'value-and-percentage',
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart6'));

        chart.draw(data, options);
      }
    </script>
 <center><div id="piechart6" style="width: 900px; height: 500px;"></div></center>

<?
	$sql = "SELECT csa.factor, count(csa_id) as cnt, parent_id, csa_factor.factor as factor_name FROM CSA 
			LEFT JOIN csa_factor ON csa_factor.csa_factor_id = csa.factor
			WHERE csa_year = '$view_year'
			  AND parent_id = '$max_id'
			  AND csa.mark_del='0' 
			GROUP BY csa.factor
			ORDER BY cnt DESC";
	$result1 = mysqli_query($connect, $sql);
?>
 
 <script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['', 'หน่วย : งานย่อย', { role: 'annotation' }],
<?	for($i=1; $i<=3; $i++) {	
		$row1 = mysqli_fetch_array($result1);	?>
		['<?= $row1['factor_name'] ?>' ,  <?= $row1['cnt'] ?> , '<?= $row1['cnt'] ?>'],
<?	}	?> 
      ]);

      var options = {
        title: 'รายละเอียดผลประเมินความเสี่ยง 3 อันดับแรก',
        chartArea: {width: '20%'},
        hAxis: {
          title: 'จำนวนงานย่อย',
          minValue: 0
        },
        vAxis: {
          title: 'ประเภทความเสี่ยง'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

      chart.draw(data, options);
    }
 </script>
 <center><div id="chart_div"></div></center>
 <BR><BR>
</div>

<table border='1' style='border-collapse: collapse;' width='1200'>
<tr valign='top' align='center'>
	<td width='400' class='cb' rowspan='2' valign='middle'>รายละเอียดปัจจัยเสี่ยง</td>
	<td width='160' class='cb' colspan='2'>สนญ</td>
	<td width='160' class='cb' colspan='2'>เขต</td>
	<td width='160' class='cb' colspan='2'>รวม</td>
</tr>
<tr valign='top' align='center'>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>	
</tr>
<?

	
	
for($i=0; $i<count($k1); $i++) {	?>
		<tr><td><?= $risk_factor_array[$k1[$i]]?></td></tr>
<?		$v1 = 0; $ratio1 = 0;
		$v2 = 0; $ratio2 = 0;
		
		for($j=0; $j < count($risk_subfactor_array[$k1[$i]][2]); $j++) {
			$v1 += $cnt_array[1][ $risk_subfactor_array[$k1[$i]][1][$j] ];
			$v2 += $cnt_array[2][ $risk_subfactor_array[$k1[$i]][1][$j] ];
		}	
		
		for($j=0; $j < count($risk_subfactor_array[$k1[$i]][2]); $j++) {	
			if($v1 == 0) $v1 = 1;
			if($v2 == 0) $v2 = 1;
		?>
			<tr><td>&nbsp;&nbsp;&nbsp;<?= $risk_subfactor_array[$k1[$i]][2][$j]?> </td>
			<td align='center'>
			<?if( $cnt_array[1][ $risk_subfactor_array[$k1[$i]][1][$j] ] > 0) {
					echo $cnt_array[1][ $risk_subfactor_array[$k1[$i]][1][$j] ]; } else { echo "0"; } 
				?></td>
			<td align='center'>
<? 					$tmp1 = number_format( ($cnt_array[1][ $risk_subfactor_array[$k1[$i]][1][$j] ]/$v1)*100, 2);
					echo  $tmp1 ?></td>
			<td align='center'>
			<?if( $cnt_array[2][ $risk_subfactor_array[$k1[$i]][1][$j] ] > 0) {
					echo $cnt_array[2][ $risk_subfactor_array[$k1[$i]][1][$j] ]; } else { echo "0"; } 
				?></td>
			<td align='center'>
<?					$tmp2 =  number_format( ($cnt_array[2][ $risk_subfactor_array[$k1[$i]][1][$j] ]/$v2)*100, 2);
					echo  $tmp2; ?></td>
				
			<td align='center'><?echo ($cnt_array[1][ $risk_subfactor_array[$k1[$i]][1][$j] ] + $cnt_array[2][ $risk_subfactor_array[$k1[$i]][1][$j] ]);  ?></td>
			<td align='center'><?echo number_format(($cnt_array[1][ $risk_subfactor_array[$k1[$i]][1][$j] ] + $cnt_array[2][ $risk_subfactor_array[$k1[$i]][1][$j] ] )/($v1+$v2)*100,2);  ?></td>	
			</tr>
<?		
			
			$ratio1 += $tmp1;
			$ratio2 += $tmp1;
		}	?>
			<tr><td align='right'> รวม&nbsp;&nbsp;</td>
				<td align='center'><?= $v1 ?></td><td align='center'><? //$ratio1?></td>
				<td align='center'><?= $v2 ?></td><td align='center'><? //$ratio2?></td>
				<td align='center'><?= $v1+$v2 ?></td><td align='center'><? //$ratio1+$ratio2 ?></td>
			</tr>
<?	}	?>
		<tr><td align='right'>รวมทั้งหมด&nbsp;&nbsp;</td><td align='center'><?= $cnt1 ?></td><td></td><td align='center'><?= $cnt2 ?></td><td></td><td align='center'><?= $cnt1+$cnt2 ?></td><td></td></tr>	
	
</table>	
</center>

<BR><BR>
<?
$sql = "SELECT (frequency+impact) as current_risk, count(csa_id) as cnt FROM csa 
			WHERE ((department_name NOT LIKE 'สำนัก%'  ) OR (department_name LIKE 'สำนักกรรม%'  ))
			AND csa_year = '$view_year'
			AND mark_del='0'
			AND (frequency+impact)  != '0'
			GROUP BY current_risk";
	$result1 = mysqli_query($connect, $sql);
	$current_array1 = array();
	$cnt1 = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		$current_array1[$row1['current_risk']] = $row1['cnt'];
		$cnt1 += $row1['cnt'];
	}	
	/*echo "<pre>";
	print_r($current_array1);
	echo "</pre>";*/
	
	$sql = "SELECT (frequency+impact) as current_risk, count(csa_id) as cnt FROM csa 
			WHERE ((department_name LIKE 'สำนักงาน%'  ) OR (department_name LIKE 'สำนักพหล%'  ))
			AND csa_year = '$view_year'
			AND mark_del='0'
			AND (frequency+impact)  != '0'
			GROUP BY current_risk";
	$result1 = mysqli_query($connect, $sql);
	$current_array2 = array();
	$cnt2 = 0;
	while ($row1 = mysqli_fetch_array($result1)) {
		$current_array2[$row1['current_risk']] = $row1['cnt'];
		$cnt2 += $row1['cnt'];
	}	
	/*echo "<pre>";
	print_r($current_array2);
	echo "</pre>";	*/

	$risk_current11 = $current_array1[1]+$current_array1[2]+$current_array1[3]+$current_array1[4];
	$risk_current21 = $current_array1[5]+$current_array1[6];
	$risk_current31 = $current_array1[7]+$current_array1[8];
	$risk_current41 = $current_array1[9]+$current_array1[10];
	
	$risk_current12 = $current_array2[1]+$current_array2[2]+$current_array2[3]+$current_array2[4];
	$risk_current22 = $current_array2[5]+$current_array2[6];
	$risk_current32 = $current_array2[7]+$current_array2[8];
	$risk_current42 = $current_array2[9]+$current_array2[10];
?>
<center>
<div class="row">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
		['ประเภทการตอบกลับ', 'จำนวน'],

		
			[ 'ความเสี่ยงต่ำ' 	 ,  <?= $risk_current11 + $risk_current12 ?> ],
			[ 'ความเสี่ยงปานกลาง' ,  <?= $risk_current21 + $risk_current22 ?> ],
			[ 'ความเสี่ยงสูง' 	 ,  <?= $risk_current31 + $risk_current32 ?> ],
			[ 'ความเสี่ยงสูงมาก' 	 ,  <?= $risk_current41 + $risk_current42 ?> ],
	
        ]);

        var options = {
          title: 'ประเภทความเสี่ยง',
		  pieSliceText: 'value-and-percentage',
		  colors: ['green', 'yellow', 'orange', 'red']
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart7'));

        chart.draw(data, options);
      }
    </script>
 <center>   <div id="piechart7" style="width: 900px; height: 500px;"></div>
</div>

<table border='1' style='border-collapse: collapse;' width='1200'>
<tr valign='top' align='center'>
	<td width='400' class='cb' rowspan='2' valign='middle'>ระดับความเสี่ยง</td>
	<td width='160' class='cb' colspan='2'>สนญ</td>
	<td width='160' class='cb' colspan='2'>เขต</td>
	<td width='160' class='cb' colspan='2'>รวม</td>
</tr>
<tr valign='top' align='center'>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>
	<td width='80' class='cb'>จำนวน</td>
	<td width='80' class='cb'>ร้อยละ</td>	
</tr>

<tr><td>&nbsp;ความเสี่ยงต่ำ</td>
	<td align='center'><?= $risk_current11 ?></td>
	<td align='center'><?=  number_format(($risk_current11/$cnt1)*100,2 ) ?></td>
	<td align='center'><?= $risk_current12 ?></td>
	<td align='center'><?=  number_format(($risk_current12/$cnt2)*100,2 ) ?></td>	
	<td align='center'><?= $risk_current11 + $risk_current12 ?></td>
	<td align='center'><?= number_format((($risk_current11 + $risk_current12)/($cnt1+$cnt2))*100,2 ) ?></td>		
</tr>
<tr><td>&nbsp;ความเสี่ยงปานกลาง</td>
	<td align='center'><?= $risk_current21 ?></td>
	<td align='center'><?=  number_format(($risk_current21/$cnt1)*100,2 ) ?></td>
	<td align='center'><?= $risk_current22 ?></td>
	<td align='center'><?=  number_format(($risk_current22/$cnt2)*100,2 ) ?></td>	
	<td align='center'><?= $risk_current21 + $risk_current22 ?></td>
	<td align='center'><?= number_format((($risk_current21 + $risk_current22)/($cnt1+$cnt2))*100,2 ) ?></td>		
</tr>
<tr><td>&nbsp;ความเสี่ยงสูง</td>
	<td align='center'><?= $risk_current31 ?></td>
	<td align='center'><?=  number_format(($risk_current31/$cnt1)*100,2 ) ?></td>
	<td align='center'><?= $risk_current32 ?></td>
	<td align='center'><?=  number_format(($risk_current32/$cnt2)*100,2 ) ?></td>	
	<td align='center'><?= $risk_current31 + $risk_current32 ?></td>
	<td align='center'><?= number_format((($risk_current31 + $risk_current32)/($cnt1+$cnt2))*100,2 ) ?></td>		
</tr>
<tr><td>&nbsp;ความเสี่ยงสูงมาก</td>
	<td align='center'><?= $risk_current41 ?></td>
	<td align='center'><?=  number_format(($risk_current41/$cnt1)*100,2 ) ?></td>
	<td align='center'><?= $risk_current42 ?></td>
	<td align='center'><?=  number_format(($risk_current42/$cnt2)*100,2 ) ?></td>	
	<td align='center'><?= $risk_current41 + $risk_current42 ?></td>
	<td align='center'><?= number_format((($risk_current41 + $risk_current42)/($cnt1+$cnt2))*100,2 ) ?></td>			
</tr>
</table>
</center>
	
		</div>
		<div class="tab-pane " id="tab4">
		
			<FORM action='csa_report.php' method='POST'>
				<input type="hidden" name="export" value="export_data" />
				<div align='center'><input type='submit' name='export_btn' value='export data'></div>
			</FORM>
			<BR><BR>
			<!--
			<FORM action='csa_report.php' method='POST'>
			<input type="hidden" name="export_kri" value="export_kri" />
			<div align='center'><input type='submit' name='export_btn2' value='export KRI'></div>
			</FORM>
			-->
<?
	$export = $_POST['export'];
	$export_kri = $_POST['export_kri'];
	//echo "export : ".$export;
	if($export == 'export_data') {
		$sql = "SELECT *,csa_risk_type.risk_type_name, csa_factor.factor as f1  FROM CSA 
				LEFT JOIN csa_department ON csa_department.csa_department_id = csa.csa_department_id
				LEFT JOIN csa_risk_type ON csa_risk_type.csa_risk_type_id = csa.risk_type
				LEFT JOIN csa_factor ON csa_factor.csa_factor_id = csa.factor
				WHERE csa.csa_year= '$view_year' 
				  AND CSA.mark_del='0' 
				  AND csa_department.mark_del = '0'
				ORDER BY csa_id";
				
		//echo "<BR>".$sql;		
		$result1 = mysqli_query($connect, $sql);
		$data_array = array();
		$i = 0;
		
		// control
		$sql = "SELECT * FROM csa_control";
		$result3 = mysqli_query($connect, $sql);
		$control_arr = array();
		while ($row3 = mysqli_fetch_array($result3)) {
			$control_arr[$row3['csa_control_id']] = $row3['control_name'];
		}		
		
		while ($row1 = mysqli_fetch_array($result1)) {
			$data_array[$i][] = $i+1;
			//$data_array[$i][] = "[".$row1['csa_id']."]".$row1['department_name'];
			$data_array[$i][] = $row1['department_name'];
			$data_array[$i][] = $row1['section'];
			$data_array[$i][] = $row1['activity_name'];
			$data_array[$i][] = $row1['risk_name'];
			
			$sql = "SELECT *, law_name FROM csa_law_data 
					LEFT JOIN csa_law ON csa_law.csa_law_id = csa_law_data.csa_law_id
					WHERE csa_id = $row1[csa_id] AND csa_law_data.mark_del = '0' ";
			//echo "<BR>".$sql;		
			$result2 = mysqli_query($connect, $sql);
			$tmp = ''; $tmp2 = ''; $tmp3 = '';
			//$lfcr = chr(10) . chr(13);
			$lfcr = "\n";
			$j = 1;
			while ($row2 = mysqli_fetch_array($result2)) {
				$tmp .= "($j)".$row2['law_name'].$lfcr;
				$tmp2 .= "($j)".$row2['description'].$lfcr;
				$j++;
			}	
			$data_array[$i][] = $tmp;
			$data_array[$i][] = $tmp2;
			
			$data_array[$i][] = $row1['risk_type_name']; // ประเภทความเสี่ยง
			$data_array[$i][] = $row1['f1'];
			
			$data_array[$i][] = $row1['frequency'];
			$data_array[$i][] = $row1['impact'];
			
			$risk_current = $row1['frequency'] + $row1['impact'];		
			if ($risk_current<=4) {
				$risk_current_label = 'ต่ำ';
				$risk_acc_label = 'ต่ำ';
			} else if ($risk_current<=6) {
				$risk_current_label = 'ปานกลาง';
				$risk_acc_label = 'ปานกลาง';
			} else if ($risk_current<=8) {
				$risk_current_label = 'สูง';
				$risk_acc_label = 'ปานกลาง';
			} else {
				$risk_current_label = 'สูงมาก';
				$risk_acc_label = 'ปานกลาง';
			}			
			$data_array[$i][] = $risk_current_label;
			// $data_array[$i][] = $risk_acc_label;  // ระดับความเสี่ยงที่ยอมรับได้
			
			$control_list = explode(',', $row1['control']);
			for($k=0; $k< count($control_list); $k++) {
				$tmp3 .= $control_arr[ $control_list[$k] ]."\n";
			}	
			$tmp3 .= $row1['control_other'];
			$data_array[$i][] = $tmp3;
			
			$data_array[$i][] = control_approach( $row1['control_approach'] );
			
			$data_array[$i][] = $row1['frequency_acc'];
			$data_array[$i][] = $row1['impact_acc'];
			
			$risk_after = $row1['frequency_acc'] + $row1['impact_acc'];
			if ($risk_after<=4) {
				$risk_after_label = 'ต่ำ';
			} else if ($risk_after<=6) {
				$risk_after_label = 'ปานกลาง';
			} else if ($risk_after<=8) {
				$risk_after_label = 'สูง';
			} else {
				$risk_after_label = 'สูงมาก';
			}
			$data_array[$i][] = $risk_after_label;
			
			$data_array[$i][] = $row1['risk_remain'];
			$data_array[$i][] = $row1['control_owner'];
			
			$i++;
		}
				
		$objPHPExcel = new PHPExcel();
		$objPHPExcel ->getProperties()->setCreator("SMEBANK")
					 ->setLastModifiedBy("SMEBANK")
					 ->setTitle("SMEBANK")
					 ->setSubject("SMEBANK")
					 ->setDescription("SME Coreportal")
					 ->setKeywords("RCSA")
					 ->setCategory("RCSA");
					 
		$data = array(
					array ('ลำดับ', 'หน่วยงาน', 'ส่วนงาน', 'ภาระหน้าที่ที่ประเมินเบื้องต้นว่ามีความเสี่ยง', 'ความเสี่ยงของงาน','การปฎิบัติตามกฎหมาย','ความเสี่ยงด้านกฎหมายที่เกี่ยวข้อง','ประเภทความเสี่ยง',
				   'ปัจจัยเสี่ยง', 'โอกาสที่จะเกิดเหตุการณ์', 'ผลกระทบปัจจุบัน', 'ระดับความเสี่ยงปัจจุบัน', 'การควบคุมที่มีอยู่','วิธีการจัดการความเสี่ยง','โอกาสเกิดที่ต้องการ',
				   'ผลกระทบหลังการควบคุม','ระดับความเสี่ยงหลังการควบคุม','ความเสี่ยงที่ยังมีอยู่','ผู้รับผิดชอบ')
				   );
		/*
		array(
				array ('ลำดับ', 'หน่วยงาน', 'ส่วนงาน', 'ภาระหน้าที่ที่ประเมินเบื้องต้นว่ามีความเสี่ยง', 'ความเสี่ยงของงาน','การปฎิบัติตามกฎหมาย','ความเสี่ยงด้านกฎหมายที่เกี่ยวข้อง','ประเภทความเสี่ยง',
			   'ปัจจัยเสี่ยง', 'โอกาสที่จะเกิดเหตุการณ์', 'ผลกระทบปัจจุบัน', 'ระดับความเสี่ยงปัจจุบัน', 'ระดับความเสี่ยงที่ยอมรับได้', 'การควบคุมที่มีอยู่','วิธีการจัดการความเสี่ยง','โอกาสเกิดที่ต้องการ',
			   'ผลกระทบที่ยอมรับได้','ระดับความเสี่ยงที่ต้องการ','ความเสี่ยงที่ยังมีอยู่','ผู้รับผิดชอบ')
	   );
		*/
		for($i=0; $i<count($data_array); $i++) {
			$data[] = $data_array[$i];
		}

		$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A1');
		$objPHPExcel->getActiveSheet()->setTitle('Export');
		$objPHPExcel->setActiveSheetIndex(0);
?>
<style>
a.link :hover {
  background-color: yellow;
}
</style>
<?
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$output_file = 'csa_export/csa_export_'.date('Y-m-d_His').'.xlsx';
		$objWriter->save($output_file);
		echo "<BR><font color='red'>click to download</font> =[<a href='./$output_file' style=' background-color: yellow;'>".$output_file."]</a>";					 

		$sql = "SELECT department_name, csa_kri.* FROM csa_kri
				LEFT JOIN csa_department ON csa_department.csa_department_id = csa_kri.csa_department_id 
				WHERE csa_kri.mark_del = '0' 
				  AND csa_kri.csa_year= '$view_year' 
				ORDER BY csa_department_id, sequence";
				
		//echo "<BR>".$sql;		
		$result1 = mysqli_query($connect, $sql);
		$data_array = array();
		$i = 0;		
		
		while ($row1 = mysqli_fetch_array($result1)) {
			$data_array[$i][] = $i+1;
			//$data_array[$i][] = "[".$row1['csa_id']."]".$row1['department_name'];
			$data_array[$i][] = $row1['department_name'];
			$data_array[$i][] = $row1['sequence'];
			$data_array[$i][] = $row1['risk_name'];
			$data_array[$i][] = $row1['index_no'];
			$data_array[$i][] = $row1['source'];
			$data_array[$i][] = $row1['description']; // ประเภทความเสี่ยง
			$data_array[$i][] = $row1['unit'];
			
			if (strpos($row1['frequency'], 'เดือน') !== false){
				$data_array[$i][] = "รายเดือน";
			}elseif (strpos($row1['frequency'], 'ไตรมาส') !== false){
				$data_array[$i][] = "รายไตรมาส";
			}elseif (strpos($row1['frequency'], 'ปี') !== false){
				$data_array[$i][] = "รายปี";
			}else{
				$data_array[$i][] = "";
			}
						
			$data_array[$i][] = level_acceptable($row1['level_acceptable'])."\n".$row1['level_acceptable_desc'];
			$data_array[$i][] = level_alert($row1['level_alert'])."\n".$row1['level_alert_desc'];
			$data_array[$i][] = level_problem($row1['level_problem'])."\n".$row1['level_problem_desc'];
			
			$i++;
		}
		
		$objPHPExcel1 = new PHPExcel();
		$objPHPExcel1 ->getProperties()->setCreator("SMEBANK")
					 ->setLastModifiedBy("SMEBANK")
					 ->setTitle("SMEBANK")
					 ->setSubject("SMEBANK")
					 ->setDescription("SME Coreportal")
					 ->setKeywords("RCSA")
					 ->setCategory("RCSA");
					 
		$data = array(array ('#','ฝ่ายงาน/สำนัก/เขต/สาขา','ลำดับ','รายการ','ชื่อดัชนีชี้วัดความเสี่ยง (KRI)','แหล่งที่มาของข้อมูล','คำอธิบายความสัมพันธ์','หน่วยวัด','ความถี่ของการเก็บข้อมูล','ระดับปลอดภัย','ระดับเฝ้าระวัง','ระดับแจ้งเตือน' ) );
		//	array(array ('#','ฝ่ายงาน/สำนัก/เขต/สาขา','ลำดับ','รายการ','ชื่อดัชนีชี้วัดความเสี่ยง (KRI)','แหล่งที่มาของข้อมูล','คำอธิบายความสัมพันธ์','หน่วยวัด','ความถี่ของการเก็บข้อมูล','ระดับที่ยอมรับได้','ระดับแจ้งเตือน','ระดับที่เป็นปัญหา' ) );
		for($i=0; $i<count($data_array); $i++) {
			$data[] = $data_array[$i];
		}	

		$objPHPExcel1->getActiveSheet()->fromArray($data, null, 'A1');
		$objPHPExcel1->getActiveSheet()->setTitle('Export');
		$objPHPExcel1->setActiveSheetIndex(0);
?>
<style>
a.link :hover {
  background-color: yellow;
}
</style>
<?
		$objWriter1 = PHPExcel_IOFactory::createWriter($objPHPExcel1, 'Excel2007');
		$output_file = 'csa_export/csa_kri_export_'.date('Y-m-d_His').'.xlsx';
		$objWriter1->save($output_file);
		echo "<BR><BR><BR><font color='red'>click to download</font> =[<a href='./$output_file' style=' background-color: yellow;'>".$output_file."]</a>";			
			

	}
?>
	</div>
		
	<div class="tab-pane" id="tab5">
<?
	if ($view_year>0 && $view_dep>0) {
?>		
	<table class='table table-hover table-light'>
	<thead>
	<tr>
		<td width='10%'>ลำดับ</td>
		<td width='20%'>วันที่</td>
		<td width='70%'>ข้อคิดเห็น</td>
	</tr>
	</thead>
	<tbody>
<?
	$sql = "SELECT * FROM csa_department WHERE csa_department_id = '$view_dep' AND csa_year='$view_year' ";
	$result2 = mysqli_query($connect, $sql);
	if ($row2 = mysqli_fetch_array($result2)) {	
		$sql = "SELECT * FROM csa_comment WHERE csa_department_id = '$view_dep' AND csa_year='$view_year' ORDER BY create_date desc";
		//echo $sql;
		$result3 = mysqli_query($connect, $sql);
		$i = 1;
		if (mysqli_num_rows($result3)>0) {
			while ($row3 = mysqli_fetch_array($result3)) {
?>
			<tr  style='cursor: pointer;'>
				<td><?= $i ?></td>
				<td><?=$row3['create_date']?></td>
				<td><?=htlm2text($row3['comment'])?></td>
			</tr>
<?
				$i++;
			}
		} else {		
?>			
			<tr>
				<td colspan='8'>-ยังไม่มีข้อมูล-</td>
			</tr>
<?
		}
	}	
?>
	</tbody>
	</table>
<?	 
	}
?>
	</div>
	<br>
	<br>
	
	</div>
</div>
</div>
</form>

<?
echo template_footer();
?>