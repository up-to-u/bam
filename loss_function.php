<?php

function gen_risk_matrix_loss($e,$c) {

	global $connect;


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

?>			

<div class='row'>

<div class='col-md-6'>

<table class='' border='0' id='table_mat'>

<tr>

	<td colspan='8' align='center'></b><br></td>

</tr>

<tr>

  <td rowspan='6' width='100'><img src='images/risk_matrix_axis_y.png'></td>

  <td width='60' align='center' style='text-align:center'></td>

  <td width='400' colspan='5' align='center' style='text-align:center'></td>

</tr>

<?

	$axis_y = array('Insignificant<br>1', 'Minor<br>2', 'Moderate<br>3', 'Major<br>4', 'Catastrophic<br>5');


    $checkValue = 0;
	for ($i=5; $i>=1; $i--) {

?>

<tr>

	<td width='60' align='center' style='font-size:11px'><?=$axis_y[$i-1]?> </td>

<?		for ($j=1; $j<=5; $j++) {
if($i == $e && $j == $c){
    $checkValue = ' display: block; ';
    
}else{
 $checkValue = ' display: none; ';
}
			$l = $d[$i][$j];

			$b = '#444444';

?>	

	<td width='80' align='center' bgcolor='<?=risk_level_color($l)?>' style='border: 1px solid <?=$b?>; height: 70px; font-weight:bold; font-size: 13px' ><?=risk_level_name($l)?> <div style="position: absolute; width:76px;height:72px; margin-top:-45px;margin-left:-2px; border: 3px; border:  5px solid; border-color: #ffffff; <?=$checkValue?> "  ></div> </td>

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

<tr>

  <td></td>

  <td></td>

  <td colspan='5' align='center'><img src='images/risk_matrix_axis_x.png'></td>

</tr>

</table>

<br>

</div>

</div>

<?	

}





function gen_change_history($csa_dep_id) {

	global $connect;



?>



<style>

table.change_history thead tr td{

	font-weight: bold;

}	

table.change_history tr td{

	font-size: 15px;

}

.table-sm tbody tr td{

	padding: 5px;

}

.tr_sm tr, .tr_sm td {

	padding: 2px !important;

}

</style>		

		<div class='row'><div class='col-xl-8 col-lg-10 col-md-12 col-sm-12'>		

			<b>ประวัติการเปลี่ยนแปลง</b><br>

			<table class='table table-hover'>

			<thead>

			<tr>

				<td width='28%'>การดำเนินการ</td>				

				<td width='27%'>สถานะ</td>

				<td width='20%'>วันที่</td>

				<td width='25%'>ผู้ดำเนินการ</td>

			</tr>

			</thead>

			<tbody>		

<?

		$sql = "SELECT 

			csa_department_change_history.*,

			s2.csa_department_status_id as s2_code,

			s2.csa_department_status_name as s2_name,

			u.code AS ucode,			CONCAT(u.prefix, u.name, ' ', u.surname) AS uname

		FROM csa_department_change_history

		JOIN csa_department_status s2 ON csa_department_change_history.to_status = s2.csa_department_status_id

		LEFT JOIN user u ON csa_department_change_history.user_id = u.user_id

		WHERE csa_department_id = '$csa_dep_id' 

		ORDER BY csa_department_change_history.create_date";

		$result2 = mysqli_query($connect, $sql);

		if (mysqli_num_rows($result2)>0) {

			while ($row2 = mysqli_fetch_array($result2)) {

?>

		<tr class='tr_sm'>

			<td><?=$row2['remark']?></td>			<td><?=$row2['s2_name']?></td>

			<td><?=mysqldate2th_datetime($row2['create_date'])?></td>

			<td><?=$row2['ucode']?> <?=$row2['uname']?></td>

		</tr>

<?

			}

		} else {

			echo '<tr><td colspan="4">-ไม่มี-</td></tr>';

		}

?>							

			</tbody>

			</table></div>			</div>			

<?

}



function gen_print_part1($csa_department_id) {

	global $connect;



	$sql = "SELECT 

			c.*,

			d.department_name AS d1,

			d2.department_name AS d2

		FROM csa_department c

		LEFT JOIN department d ON c.department_id3 = d.department_id

		LEFT JOIN department d2 ON c.department_id2 = d2.department_id

		WHERE 

			c.csa_department_id = ? AND 

			c.mark_del = '0' ";

	$stmt = $connect->prepare($sql);

	if ($stmt) {					

		$stmt->bind_param('i', $csa_department_id);

		$stmt->execute();

		$result2 = $stmt->get_result();

		if ($row2 = mysqli_fetch_assoc($result2)) {

			$is_head1_confirm = $row2['is_head1_confirm'];

?>



<div id='print_area'>

<style type="text/css" media="print">

@media screen {

  div.divHeader {

    display: none;

  }	

  div.divFooter {

    display: none;

  }

}

@media print {

  div.divHeader {

	  display:block;

    position: fixed;

    top: 400;

    left: 100;

	font-size: 100px;

	font-weight: bold;



    transform: rotate(45deg);

    transform-origin: right, top;

    -ms-transform: rotate(45deg);

    -ms-transform-origin:right, top;

    -webkit-transform: rotate(45deg);

    -webkit-transform-origin:right, top;

	

	color: rgba(200, 200, 200, 0.2);

  }  

  div.divFooter {

    position: fixed;

    bottom: 0;

  }

}	

</style>

<?	if ($is_head1_confirm==0) {?>

<div class="divHeader">ฉบับร่าง รอการยืนยัน</div>

<? }?>



<div align='center'><b><u><?=$row2['d2']?> <?=$row2['d1']?></u><br>

แบบสอบถามความเพียงพอของการควบคุมภายใน<br></b></div>

<br>

<?

	$check_icon = '<img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB40lEQVRoge2YyytFQRzHf14bG+9HQlKyUsrOlo2FEitZWFL+EDaSjYWFNVEW9hZ2hOtdQspCipIsvB/fX8fkup3H/M4915zTnU99lnfOZ+ZMc+deIovFYrH4Uw034CMcNdwipgbuw68fP+G40SIBHH9Av/Hpkxgz2KWFV7zyg2K8nWrhIXnHK9/hsKFGTzj+iILjlbdmMt2pg8ekH8+uGyl1QXfbpLtHzhFrHOm2iVU8b5tEx0v3fH7Et8JFOAlLIg5nGuBpQGymKVilM3gXvEn74AosjjC+nnK48j3wwWWA5YgmwfEnwviUbvwIfPUZaAkWZRHfCM9CxGttmwlybnhBAy7Awn+K34WVug9w2zZezsMCQXwTPM9lPLMqfMCc5iSaQ8TvSOOZUnJ+skkeNBswiTArr73n3SiD28IHzniMFWbls4pX8HElPaOnXeIvTMQr+FtSunpT5JxO7fBS+Fl+6xVRxSta4JUw5B6+xSFe0QavhUES+aiMbNt40QHvchQvPirD0knO9khkvKKbnL/1Ehmv6IVPAYF+hvqGjZp++ELy+C1YbqDXlT74TAlb+UwGyP+3g3KTYrTymQyS/yRiufKZeE0iEfGKIfp7heDrQWLiFfwm+O60Rs613GKxWPKIbyG+IL61xh4kAAAAAElFTkSuQmCC" alt="X" width="18"/>';

	/*$check_icon = '<img src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC+UlEQVRogc2ay2sVMRSHs6h1o6AVfOALoe115X/gQlwKoijWunIhKtTb4ruKC6EbF2rbf8bSnaVgtaUq1IXgqxtxYVuwXsVqqZ7fTCJxnMnkNZMc+ChcktxvcjOZnDNlzE9sJ84Qo8Q48YZYIn5ylvhn47xNL+8TNLYQ/cQM8duSaaLJx6otdhEjxDcH8SwtYpjYWaX4OmKA+OpRPAsm5S6x3rd8g3hZoXiWF0S3L/kTrNpZL2KZOO4qf5b4FUBesEpctJW/EFA8S9NUHstmNQJx+Zc4pivfSXyJQDoL7sP9ZfLtrN7dxpTnLN3OC2MwAskyrhXJ4ynYikBQZyntyLuAkQjkdHmQlcdhyufZpmpaLHMAbEYgBe4Qu4kJjbaX5AtwORL74rrks4GYLGn/TDTGDbEWWD5vZ9lIPFH0gfNWNOyNUF7EyZK+PWg0GlD+qkJ+M0sfXKr+SIKSPDWE/C2F/CaWpptlYzxC43cB5K8o5DHzs5rjoFDAFi0E5onb/K9p38ue5MECOq0YCrxiaWKP2EO8N+g7qJDXXTYyP0wv4DH/Ijn2Eh80+t70LP/3AkyW0L4CgS7iY0Ef7NcDCnnTZSPzGQO8NehwTyGCZONTjfIguYlNt1HVOm5IFwH5fkVb22Ujk2yjNg+yPoXYAWKKpUWBonCdeUHyILM5SmB2zykEVeFj5gWnMOA2ZneYQ6XgdED5Ne6ehO2g2IKPaMr7WjaCKXlwl4TmO3G4Znnwz32I9MwloUc6eqhG+f9SSsSw46B5F+Fzzcvcz5spH2UVlDwO8vGqmHmAqnXh66kbnr5giJirQB6okqCkbFeWBYVklpWUFhExF3cbZfIi8GYktvL6UV15EecjEAcux5YoXjGpDoVagTcjywHkcR8aL5uiQMZV5+6E3abTl7yINpZmV1X+GuJFd7tveTlQS33I/L4MwVio+df6DyA4TKHE/ZTZ5RPogyMxTpUddYrnBarEKLTiQDhGvGZptWOFs8g/G+Ntengf5/gDI7RFJjPvBdwAAAAASUVORK5CYII=" alt="X" width="24"/>';*/

	/* $check_icon = 'X'; */



	$q_result = array();

	$sql = "SELECT * FROM csa_questionnaire_data WHERE csa_department_id = '$csa_department_id' ";

	$result2 = mysqli_query($connect, $sql);

	while ($row2 = mysqli_fetch_array($result2)) {

		$q_result[$row2['csa_q_topic_id']] = array($row2['v'], $row2['v_other']);

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

?>

			<b><?=$row2['q_no']?> <?=$row2['q_name']?></b><br>

			<table border='1' style='border-collapse: collapse;'>

			<thead>

			<tr align='center' style='font-weight: bold'>

				<td width='70%'>คำถาม</td>

				<td width='10%'>มีการปฏิบัติ<br>ครบถ้วน</td>

				<td width='10%'>มีการปฏิบัติ<br>บางส่วน</td>

				<td width='10%'>ไม่มี<br>การปฏิบัติ</td>

			</tr>

			</thead>

			<tbody>

<?

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

				$v0 = $q_result[$row3['csa_q_topic_id']][0];

				$v1 = $q_result[$row3['csa_q_topic_id']][1];

?>

			<tr class='tr_sm'>

				<td><?=$row3['q_no']?> <?=$row3['q_name']?></td>

				<td align='center'><?if ($v0==3) echo $check_icon?></td>

				<td align='center'><?if ($v0==2) echo $check_icon?></td>

				<td align='center'><?if ($v0==1) echo $check_icon?></td>

			</tr>

<?			}?>

			</tbody>

			</table>

			<br>

<?

		}

	} 

?>



<b>คำอธิบายเพิ่มเติม</b><br>

<div style='width: 900px; word-wrap:break-word; word-break:break-all'><u><?=html2text($q_result[0][1])?></u></div>



<br>

<br>

<div align='right'>

(..................................................................................)<br>

ผู้จัดทำ ..................................................................................<br>

ตำแหน่ง  ..................................................................................<br>

วันที่ .................. เดือน ................................. พ.ศ. ...................<br>

</div>



</div>

<?

			

		}

	}		

}



function gen_print_part2_1($csa_department_id) {

	global $connect;



	$sql = "SELECT 

		c.*,

		d1.department_name AS dep_name1,

		d2.department_name AS dep_name2,

		d3.department_name AS dep_name3

	FROM csa_department c

	LEFT JOIN department d1 ON c.department_id = d1.department_id

	LEFT JOIN department d2 ON c.department_id2 = d2.department_id

	LEFT JOIN department d3 ON c.department_id3 = d3.department_id

	WHERE 

		c.is_enable='1' AND

		c.csa_department_id = ? AND 

		c.mark_del = '0' ";

	$stmt = $connect->prepare($sql);

	if ($stmt) {					

		$stmt->bind_param('i', $csa_department_id);

		$stmt->execute();

		$result2 = $stmt->get_result();

		if ($row2 = mysqli_fetch_assoc($result2)) {

			$csa_year = $row2['csa_year'];

			$csa_department_id = $row2['csa_department_id'];

			$is_head1_confirm = $row2['is_head1_confirm'];

			

			$sql = "SELECT 

				c.*,

				r.is_other as risk_is_other,

				r.risk_type_name,

				j.job_function_no,

				j.job_function_name

			FROM csa c

			LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'

			LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'

			WHERE 

				c.csa_year = '$csa_year' AND 

				c.mark_del = '0' AND 

				c.csa_department_id = '$csa_department_id' ";

			$result2 = mysqli_query($connect, $sql);

			if (mysqli_num_rows($result2)>0) {

?>



<div id='print_area'>

<style type="text/css" media="print">

@page { 

	size: landscape;

}

td, th {

    padding: 3;

}

@media screen {

  div.divHeader {

    display: none;

  }	

  div.divFooter {

    display: none;

  }

}

@media print {

  div.divHeader {

    position: fixed;

    top: 280;

    left: 230;

	font-size: 100px;

	font-weight: bold;



    transform: rotate(35deg);

    transform-origin: right, top;

    -ms-transform: rotate(35deg);

    -ms-transform-origin:right, top;

    -webkit-transform: rotate(35deg);

    -webkit-transform-origin:right, top;

	

	color: rgba(200, 200, 200, 0.2);

  }  

  div.divFooter {

    position: fixed;

    bottom: 0;

  }

}	



</style>

<?	if ($is_head1_confirm==0) {?>

<div class="divHeader">ฉบับร่าง รอการยืนยัน</div>

<? }?>



<div align='center'><b><u>

<?=$row2['dep_name2']?><br>

<?=$row2['dep_name3']?></u></b></div><br>

<br>

<table border='1' style='border-collapse: collapse;'>

<thead>

<tr align='center' style='font-weight: bold'>

	<td width='18%'>ขอบเขตหน้าที่ความรับผิดชอบของกลุ่มงาน</td>

	<td width='18%'>เหตุการณ์ความเสี่ยง</td>

	<td width='18%'>สาเหตุที่ทำให้<br>เกิดความเสี่ยง</td>

	<td width='17%'>ประเภทความเสี่ยง<br>และปัจจัยเสี่ยง</td>

	<td width='20%'>การควบคุมที่มีอยู่</td>

	<td width='9%'>ระดับ<br>ความเสี่ยง</td>

</tr>

</thead>

<tbody>

<?		

$i=1;		

				while ($row2 = mysqli_fetch_array($result2)) {

					$j_list[] = $row2['job_function_id'];

					if ($row2['job_function_id']==999999) 

						$job_function = 'อื่นๆ : '.$row2['job_function_other'];

					else

						$job_function = $row2['job_function_name'];

					

					if ($row2['risk_is_other']==1) 

						$risk_type_name = $row2['risk_type_other'];

					else

						$risk_type_name = $row2['risk_type_name'];

					



					$risk_level = risk_level_name($row2['csa_risk_level2']);



					$control_list = '';

					$sql="SELECT * FROM csa_control WHERE csa_control_id IN (".$row2['control'].")";

					$result1=mysqli_query($connect, $sql);

					while ($row1 = mysqli_fetch_array($result1)) {

//						$control_list.='-'.$row1['control_name'].'<br>';

						if ($row1['is_other']==1) 

							$control_list .= '- '.$row1['control_name'].': <br>'.html2text($row2['control_other']).'<BR>';

						else

							$control_list .= '- '.$row1['control_name'].'<BR>';						

					}

					/*$job_function*/	

?>

			<tr valign='top'>

				<td class='f1'><?=$i++?>. <?=$job_function?><br>

				<br>

				วัตถุประสงค์<br><?=html2text($row2['objective'])?>

				</td>

				<td class='f1'><?=html2text($row2['event'])?></td>

				<td class='f1'><?=html2text($row2['cause'])?></td>

				<td class='f1'><?=$risk_type_name?></td>

				<td class='f1'><?=$control_list?></td>

				<td class='f1'><?=$risk_level?></td>

			</tr>

<?

				}

?>

			</tbody>

			</table>

			<br>

<table width='100%'>			

<tr>

<td width='40%'>

<td width='30%' align='right'>

(......................................................)<br>

ผู้จัดทำ ......................................................<br>

ตำแหน่ง  ......................................................<br>

วันที่ ......................................................<br>

</td>

<td width='30%' align='right'>

(......................................................)<br>

ผู้อนุมัติ ......................................................<br>

ตำแหน่ง  ......................................................<br>

วันที่ ......................................................<br>

</td>

</tr>

</table>

		

</div>

<?

			}		

		}		

	}		

}



function gen_print_part2_2($csa_department_id) {

	global $connect;

?>



<div id='print_area'>

<style type="text/css" media="print">

@page { 

	size: landscape;

}

@media print {

  div.divHeader {

    position: fixed;

    top: 280;

    left: 230;

	font-size: 100px;

	font-weight: bold;



    transform: rotate(35deg);

    transform-origin: right, top;

    -ms-transform: rotate(35deg);

    -ms-transform-origin:right, top;

    -webkit-transform: rotate(35deg);

    -webkit-transform-origin:right, top;

	

	color: rgba(200, 200, 200, 0.2);

  }  

  div.divFooter {

    position: fixed;

    bottom: 0;

  }

}	

</style>



<?

	$sql = "SELECT 

		c.*,

		d1.department_name AS dep_name1,

		d2.department_name AS dep_name2,

		d3.department_name AS dep_name3

	FROM csa_department c

	LEFT JOIN department d1 ON c.department_id = d1.department_id

	LEFT JOIN department d2 ON c.department_id2 = d2.department_id

	LEFT JOIN department d3 ON c.department_id3 = d3.department_id

	WHERE 

		c.is_enable='1' AND

		c.csa_department_id = ? AND 

		c.mark_del = '0' ";

	$stmt = $connect->prepare($sql);

	if ($stmt) {					

		$stmt->bind_param('i', $csa_department_id);

		$stmt->execute();

		$result2 = $stmt->get_result();

		if ($row2 = mysqli_fetch_assoc($result2)) {

			$csa_year = $row2['csa_year'];

			$csa_department_id = $row2['csa_department_id'];

			$dep_name = $row2['dep_name2'].'<BR>'.$row2['dep_name3'];

			$is_head1_confirm = $row2['is_head1_confirm'];

			

			$sql = "SELECT 

				c.*,

				r.is_other as risk_is_other,

				r.risk_type_name,

				j.job_function_no,

				j.job_function_name

			FROM csa c

			LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'

			LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'

			WHERE 

				c.csa_risk_level2 >= 3 AND

				c.csa_year = '$csa_year' AND 

				c.mark_del = '0' AND 

				c.csa_department_id = '$csa_department_id' ";

			$result2 = mysqli_query($connect, $sql);

			if (mysqli_num_rows($result2)>0) {

				while ($row2 = mysqli_fetch_array($result2)) {

					$j_list[] = $row2['job_function_id'];

					if ($row2['job_function_id']==999999) 

						$job_function = 'อื่นๆ : '.$row2['job_function_other'];

					else

						$job_function = $row2['job_function_name'];

					

					if ($row2['risk_is_other']==1) 

						$risk_type_name = $row2['risk_type_other'];

					else

						$risk_type_name = $row2['risk_type_name'];

					



					$risk_level = risk_level_name($row2['csa_risk_level2']);



					$control_list = '';

					$sql="SELECT * FROM csa_control WHERE csa_control_id IN (".$row2['control'].")";

					$result1=mysqli_query($connect, $sql);

					while ($row1 = mysqli_fetch_array($result1)) {

						$control_list.='-'.$row1['control_name'].'<br>';

					}

						

					$action_plan_type_array = explode(',', $row2['action_plan_type']);

					

?>

<?	if ($is_head1_confirm==0) {?>

<div class="divHeader">ฉบับร่าง รอการยืนยัน</div>

<? }?>



<div align='center'><b><u><?=$dep_name?></u></b></div><br>

<b>ขอบเขตหน้าที่ความรับผิดชอบของกลุ่มงาน : </b><br>

<?=html2text($job_function)?><br>

<br>

<b>เหตุการณ์ความเสี่ยง :</b><br>

<?=html2text($row2['event'])?><br>

<br>

<b>ระดับความเสี่ยง : </b><?=$risk_level?><br>

<br>

<?

					foreach ($action_plan_type_array as $p) {

						if ($p==1) {

							$action_plan_type = '<font color="#ff0000"><b>ดำเนินการโดยฝ่ายงาน</b></font>';

?>

<b>การตอบสนองความเสี่ยง : </b><?=$action_plan_type?><br>

<b>ชื่อแผนปฏิบัติการ :</b><br>

<?=html2text($row2['action_plan_activity1'])?><br>

<b>วันที่เริ่มต้น :</b> <?=mysqldate2th_date($row2['action_plan_begin_date1'])?><br>

<b>วันที่สิ้นสุด :</b> <?=mysqldate2th_date($row2['action_plan_end_date1'])?><br>

<b>กิจกรรม หรือ ขั้นตอน :</b><br>

<?=html2text($row2['action_plan_process1'])?><br>

<br>

<?						



						} else if ($p==2) {

							$action_plan_type = '<font color="#0070c0"><b>ว่าจ้าง Outsource</b></font>';

?>

<b>การตอบสนองความเสี่ยง : </b><?=$action_plan_type?><br>

<b>งบประมาณ (บาท) : </b> <?=number_filter($row2['action_plan_budget2'])?><br>

<b>ชื่อแผนปฏิบัติการ :</b><br>

<?=html2text($row2['action_plan_activity1'])?><br>

<b>วันที่เริ่มต้น :</b> <?=mysqldate2th_date($row2['action_plan_begin_date1'])?><br>

<b>วันที่สิ้นสุด :</b> <?=mysqldate2th_date($row2['action_plan_end_date1'])?><br>

<b>กิจกรรม หรือ ขั้นตอน :</b><br>

<?=html2text($row2['action_plan_process1'])?><br>

<br>

<?						

						}

					}

?>

			<br>

			<br>



<table width='100%'>			

<tr>

<td width='40%'>

<td><div width='30%' align='right'>

(......................................................)<br>

ผู้จัดทำ ......................................................<br>

ตำแหน่ง  ......................................................<br>

วันที่ ......................................................<br>

</td>

<td width='30%' align='right'>

(......................................................)<br>

ผู้อนุมัติ ......................................................<br>

ตำแหน่ง  ......................................................<br>

วันที่ ......................................................<br>

</td>

</tr>

</table>

<div class='break'></div>

<?

				}

			}		

		}		

	}

?>

</div>

<?	

}



function htlm2text($h) {

	$h = strip_tags($h);

	$h = str_replace("\n", '<br>', $h);

	$h = str_replace(' ', '&nbsp;', $h);

	return $h;

}





function csa_status($r) {

	switch ($r) {

		case 0: return 'อยู่ระหว่างจัดทำ';

		case 1: return 'ประเมินแล้ว';

	}

}

function csa_status_color($r) {

	switch ($r) {

		case 0: return 'red';

		case 1: return 'green';

	}

}





function risk_level_color($r) {

	switch ($r) {

		case 0: return '';

/*		case 1: return '#9dff9c';

		case 2: return '#f5ff9c';

		case 3: return '#ffd29c';

		case 4: return '#ff9c9c';*/

		case 1: return '#00ff00';

		case 2: return '#ffff00';

		case 3: return '#ff9900';

		case 4: return '#ff0000';

	}

}

function risk_level_name($r) {

	switch ($r) {

		case 0: return '';

		case 1: return 'ต่ำ';

		case 2: return 'ปานกลาง';

		case 3: return 'สูง';

		case 4: return 'สูงมาก';

	}

}





function auditor_name($n) {

	switch ($n) {

		case 0: return 'Internal Audit';

		case 1: return 'BOT';

		case 2: return 'EY';

		case 3: return 'Others';

	}

}

?>