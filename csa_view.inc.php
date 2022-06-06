<?
$is_confirm = 1;

if ($view_id>0) {

	$sql = "SELECT 
		c.*,
		r.is_other as risk_is_other,
		r.risk_type_name,
		j.job_function_no,
		j.job_function_name,
		f.is_other as factor_is_other,
		f.factor as factor_name,
		rs.responsibility_desc as responsibility_desc
	FROM csa c
	LEFT JOIN csa_risk_type r ON c.risk_type = r.csa_risk_type_id AND r.mark_del = '0'
	LEFT JOIN job_function j ON c.job_function_id = j.job_function_id AND j.mark_del = '0'
	LEFT JOIN csa_factor f ON c.factor = f.csa_factor_id AND f.mark_del = '0'
	LEFT JOIN csa_responsibility rs ON c.csa_responsibility_id = rs.csa_responsibility_id AND rs.mark_del = '0'
	WHERE 
		c.csa_id = ? ";	
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('i', $view_id);
		$stmt->execute();
		$result2 = $stmt->get_result();
		if ($row2 = mysqli_fetch_assoc($result2)) {
			if ($is_view_admin==1) $is_confirm = 1;
			
			$sql = "SELECT 
					c.*,
					d1.department_name AS dep_name1,
					d2.department_name AS dep_name2,
					d3.department_name AS dep_name3
				FROM csa_department c
				LEFT JOIN department d1 ON c.department_id = d1.department_id
				LEFT JOIN department d2 ON c.department_id2 = d2.department_id
				LEFT JOIN department d3 ON c.department_id3 = d3.department_id
				LEFT JOIN csa_authorize ca ON c.csa_department_id = ca.csa_department_id
				WHERE 
					c.csa_department_id = '$row2[csa_department_id]' AND 
					c.mark_del = '0' ";
			$result1 = mysqli_query($connect, $sql);
			if ($row1 = mysqli_fetch_array($result1)) {
				$dep1 = $row1['dep_name1'];
				$dep2 = $row1['dep_name2'];
				$dep3 = $row1['dep_name3'];
				//$is_confirm = $row1['is_confirm'];
			}				
			
			$lock_tag = '';
			if ($is_confirm==1){
				$lock_tag = 'disabled';
			}

			
			if ($row2['job_function_id']==9999) 
				$job_function = 'อื่นๆ : '.$row2['job_function_other'];
			else
				$job_function = $row2['job_function_no'].' '.$row2['job_function_name'];
			
			if ($row2['risk_is_other']==1) 
				$risk_type_name = $row2['risk_type_name'].': '.$row2['risk_type_other'];
			else
				$risk_type_name = $row2['risk_type_name'];
			
			if ($row2['factor_is_other']==1) 
				$factor_name = $row2['factor_name'].': '.$row2['factor_other'];
			else
				$factor_name = $row2['factor_name'];
			
			$control = '';
			$sql="SELECT * FROM csa_control WHERE csa_control_id IN ($row2[control])";
			$result1=mysqli_query($connect, $sql);
			while ($row1 = mysqli_fetch_array($result1)) {	
				if ($row1['is_other']==1) 
					$control .= '- '.$row1['control_name'].': '.$row2['control_other'].'<BR>';
				else
					$control .= '- '.$row1['control_name'].'<BR>';
			}
?>


<script language='JavaScript'>

<?
	echo "\n risk_mat = {}; \n";
	
	$d = array();
	$sql2="SELECT * FROM `csa_risk_matrix` ";
	$result3=mysqli_query($connect, $sql2);
	while ($row3 = mysqli_fetch_array($result3)) {
		$d[$row3['csa_impact_id']][$row3['csa_likehood_id']] = $row3['csa_risk_level']; 
	}
?>

risk_mat = [
<?
	for ($i=1; $i<=5; $i++) {
		if ($i==1) 
			echo '[';
		else 
			echo ',[';
		for ($j=1; $j<=5; $j++) {
			if ($j>1) echo ',';
			echo $d[$i][$j];
		}
		echo "]\n";
	}
?>
];

function risk_level_color(r) {
	switch (r) {
		case 0: return '';
		case 1: return '#9dff9c';
		case 2: return '#f5ff9c';
		case 3: return '#ffd29c';
		case 4: return '#ff9c9c';
	}
}
function risk_level_name(r) {
	switch (r) {
		case 0: return '';
		case 1: return 'ต่ำ';
		case 2: return 'ปานกลาง';
		case 3: return 'สูง';
		case 4: return 'สูงมาก';
	}
}
function risk_level_acceptable(r) {
	if  (r>=3) 
		return 'ไม่เพียงพอ';
	else
		return 'เพียงพอ';
}
function risk_level_acceptable_color(r) {
	if  (r>=3) 
		return '#ff9c9c';
	else
		return '#9dff9c';
}

function cal_level(w) {
	var i = parseInt($('#csa_impact_id'+w).val())-1;
	var j = parseInt($('#csa_likehood_id'+w).val())-1;
	
	if (isNaN(i) || isNaN(j)) {
		$('#risk_level_'+w+'_div').css('background-color', '#ffffff');
		$('#risk_level_'+w+'_div').html('');
		$('#risk_level_'+w+'_txt').val(0);
		$('#risk_level_'+w+'_1_div').html('');
		$('#risk_level_'+w+'_1_div').css('background-color', '#ffffff');
	} else {
		lv = risk_mat[i][j];
		$('#risk_level_'+w+'_div').css('background-color', risk_level_color(lv));
		$('#risk_level_'+w+'_div').html(risk_level_name(lv));
		$('#risk_level_'+w+'_txt').val(lv);
		$('#risk_level_'+w+'_1_div').html(risk_level_acceptable(lv));
		$('#risk_level_'+w+'_1_div').css('background-color', risk_level_acceptable_color(lv));
	}
}

$(function () {
	save_tab();
	
	$('#csa_impact_id1, #csa_likehood_id1').change(function() {
		cal_level("1");
	}).change();
	$('#csa_impact_id2, #csa_likehood_id2').change(function() {
		cal_level("2");
	}).change();

});  


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

function activaTab(tab){
  $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};
</script>



			<div class=''>
			<b>
				<?=$dep1?><br>
				<?=$dep2?><br>
				<?=$dep3?></b>
			</div>
			<br>
<div class="tabbable-custom ">
	<ul class="nav nav-tabs " id='myTab'>
		<li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true">การประเมิน</a></li>
		<!--<li class=""><a href="#tab2" data-toggle="tab" aria-expanded="true"></a></li>-->
		<li class=""><a href="#tab3" data-toggle="tab" aria-expanded="true">Risk Matrix</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">	

			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>Job Function</div>
				<div class="col-md-8"><?=$job_function?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>ชื่อกิจกรรม</div>
				<div class="col-md-8"><?=$row2['activity_name']?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>ขอบเขตหน้าที่ความรับผิดชอบ</div>
				<div class="col-md-8"><?=$row2['responsibility_desc']?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>ชื่อกิจกรรม</div>
				<div class="col-md-8"><?=$row2['activity_name']?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>วัตถุประสงค์</div>
				<div class="col-md-8"><?=$row2['objective']?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>เหตุการณ์ความเสี่ยง</div>
				<div class="col-md-8"><?=$row2['event']?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>สาเหตุที่ทำให้เกิดความเสี่ยง</div>
				<div class="col-md-8"><?=$row2['cause']?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>ประเภทความเสี่ยง</div>
				<div class="col-md-8"><?=$risk_type_name?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>ปัจจัยเสี่ยง</div>
				<div class="col-md-8"><?=$factor_name?></div>
			</div>
			<div class="row">
				<div class="col-md-2" style='font-weight: bold'>การควบคุมที่มีอยู่</div>
				<div class="col-md-8"><?=$control?></div>
			</div>
			<br>
			<br>
<? if ($is_confirm==0){?>
			<button type='button' class="btn btn-default btn-sm" onClick="document.location='csa_user.php?edit_id=<?=$view_id?>&view_year=<?=$view_year?>'"><i class='fa fa-edit'></i> แก้ไขข้อมูลกิจกรรม</button>
<?}?>
		
			<br>
			<br>
			<hr>
	
			<form method='post' action='csa_user.php?view_id=<?=$view_id?>&view_year=<?=$view_year?>' id='f1'>
			<br>
			<div class='row'>
			<div class='col-lg-4 col-md-6 col-sm-10 col-xs-12'>

				<div class="">
					<b>การประเมินความเสี่ยง <u>ก่อน</u> การควบคุม</b><br><br>

				<div class="form-group">
					<label>ผลกระทบ</label>
					<select name='csa_impact_id1' id='csa_impact_id1' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_impact";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_impact_id']?>' <?if ($row2['csa_impact_id1']==$row1['csa_impact_id']) echo 'selected'?>><?=$row1['csa_impact_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label>โอกาส</label>
					<select name='csa_likehood_id1' id='csa_likehood_id1' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_likehood";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_likehood_id']?>' <?if ($row2['csa_likehood_id1']==$row1['csa_likehood_id']) echo 'selected'?>><?=$row1['csa_likehood_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label>ระดับความเสี่ยง</label>
					<div class='alert' id='risk_level_1_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>
				<div class="form-group">
					<label>ผลการประเมินการควบคุมที่มีอยู่</label>
					<div class='alert' id='risk_level_1_1_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>

				</div>				
			</div>	
			<div class='col-md-1'></div>

			<div class='col-lg-4 col-md-6 col-sm-10 col-xs-12'>
				<div class="">
					<b>การประเมินความเสี่ยง <u>หลัง</u> การควบคุม</b><br><br>
				<div class="form-group">
					<label>ผลกระทบ</label>
					<select name='csa_impact_id2' id='csa_impact_id2' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_impact";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_impact_id']?>' <?if ($row2['csa_impact_id2']==$row1['csa_impact_id']) echo 'selected'?>><?=$row1['csa_impact_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label>โอกาส</label>
					<select name='csa_likehood_id2' id='csa_likehood_id2' class="form-control" <?=$lock_tag?>>
						<option value=''>--- เลือก ---</option>
<?
$sql="SELECT * FROM csa_likehood";
$result1=mysqli_query($connect, $sql);
while ($row1 = mysqli_fetch_array($result1)) {
?>
						<option value='<?=$row1['csa_likehood_id']?>' <?if ($row2['csa_likehood_id2']==$row1['csa_likehood_id']) echo 'selected'?>><?=$row1['csa_likehood_name']?></option>
<?
}
?>			  
					</select>
				</div>
				<div class="form-group">
					<label>ระดับความเสี่ยง</label>
					<div class='alert' id='risk_level_2_div' style='background:#ffffff; padding: 10; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>
				<div class="form-group">
					<label>ผลการประเมินการควบคุมที่มีอยู่</label>
					<div class='alert' id='risk_level_2_1_div' style='background:#ffffff; padding: 10px; height: 40px; font-weight: bold; border: 1px solid #eeeeee'></div>
				</div>

				</div>			
				
			<br>
			</div>
			</div>
			
			<input type='hidden' name='risk_level_1' id='risk_level_1_txt' value=''>
			<input type='hidden' name='risk_level_2' id='risk_level_2_txt' value=''>
			<input type='hidden' name='update_id' value='<?=$view_id?>'>
<?if ($is_confirm==0){?>			
			<button type='submit' name='submit' value='save_assetment' class="btn btn-success btn-sm"><i class='fa fa-save'></i> บันทึกผลประเมิน</button>
<?}?>
			<button type='button' class="btn btn-default btn-sm" onClick='activaTab("tab3")'><i class='fa fa-search'></i> ดู Matrix</button>
			
			
			</form>
		</div>
		

		
		<div class="tab-pane" id="tab3">	
		<br>
<?
	$d = array();
	$sql2="SELECT * FROM `csa_risk_matrix` ";
	$result1=mysqli_query($connect, $sql2);
	while ($row1 = mysqli_fetch_array($result1)) {
		$d[$row1['csa_impact_id']][$row1['csa_likehood_id']] = $row1['csa_risk_level']; 
	}
	
	$lv = array();
	for ($i=1; $i<=5; $i++) {
		$lv[] = risk_level_name($i);
	}
?>
<br>

<div class='row'>
<div class='col-md-6'>

<table class='' border='0'>
<tr>
	<td colspan='8' align='center'><b>ตารางแสดงผลการวัดระดับความเสี่ยง (Risk Matrix)</b><br><br></td>
</tr>
<tr>
  <td rowspan='6' width='100'><img src='images/risk_matrix_axis_y.png'></td>
  <td width='60' align='center' style='text-align:center'></td>
  <td width='400' colspan='5' align='center' style='text-align:center'></td>
</tr>
<?
	$axis_y = array('Insignificant<br>1', 'Minor<br>2', 'Moderate<br>3', 'Major<br>4', 'Catastrophic<br>5');
	for ($i=5; $i>=1; $i--) {
?>
<tr>
	<td width='60' align='center' style='font-size:11px'><?=$axis_y[$i-1]?></td>
<?		for ($j=1; $j<=5; $j++) {
			$l = $d[$i][$j];
			/*if ($l<3) 
				$b = '#33bb33';
			else
				$b = '#ff3333';*/
			
			$b = '#444444';
?>	
	<td width='80' align='center' bgcolor='<?=risk_level_color($l)?>' style='border: 1px solid <?=$b?>; height: 70px; font-weight:bold; font-size: 13px' ><?=risk_level_name($l)?></td>
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
<!--<div class='col-md-6'>
	<table border='1'>
	<tr style='font-size:13px; font-weight: bold' align='center'>
		<td width='70'>Impact</td>
		<td width='70'>Likehood</td>
		<td width='70'>Risk Matrix</td>
	</tr>
<?	for ($i=1; $i<=5; $i++) {?>
<?		for ($j=1; $j<=5; $j++) {
			$l = $d[$i][$j];
?>
	<tr>
		<td style='font-size:13px' align='center'><?=$i?></td>
		<td style='font-size:13px' align='center'><?=$j?></td>
		<td bgcolor='<?=risk_level_color($l)?>' style='border: 1px solid; font-weight:bold; font-size: 13px' align='center'><?=risk_level_name($l)?></td>
	</tr>

<?		}
?>	
<?	}?>
	</table>
</div>-->
</div>
<!--<hr><img src='images/risk_matrix.png' class="img-responsive">-->
		<br>
		</div>
	</div>
</div>
<br>

			
<?			if ($is_confirm==0){?>			
			<a href="csa_user.php?view_year=<?=$view_year?>&del_csa_id=<?=$view_id?>" class="btn btn-danger btn-sm"  onClick='return confirm("โปรดยืนยันว่าต้องการลบกิจกรรม?")' class="delete-row"><i class='fa fa-trash'></i> ลบกิจกรรม</a>
<?			}
			
		}
	}
}
?>