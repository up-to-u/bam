<?php
include_once('inc/include.inc.php');
echo template_header();



	$search_name = $_GET['search_name'];
	$search_lastname = $_GET['search_lastname'];
	$search_nick = $_GET['search_nick'];
	$search_email = $_GET['search_email'];
	$search_dep = $_GET['search_dep'];
	
	$wsql = '';
	if ($search_name!='') $wsql .= " AND name LIKE '%$search_name%' ";
	if ($search_lastname!='') $wsql .= " AND surname LIKE '%$search_lastname%' ";
	if ($search_nick!='') $wsql .= " AND nickname LIKE '%$search_nick%' ";
	if ($search_email!='') $wsql .= " AND email LIKE '%$search_email%' ";
	if ($search_dep!='') $wsql .= " AND department.department_name LIKE '%$search_dep%' ";
	
?>
<style type="text/css">

.pic {
  display: inline-block;
  width: 50px;
  height: 50px;
}

.box {
  display: inline-block;
  width: 150px;
  height: 150px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  margin: 0px;
  padding: 0px;
  border: 0px solid #eee;
  box-shadow: 0 0px 0px rgba(0, 0, 0, 0.3);  
}

.circle {
  display: inline-block;
  width: 250px;
  height: 250px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center top;
  margin: 0px;
  padding: 0px;
  border: 0px solid #eee;

  -webkit-border-radius: 99em;
  -moz-border-radius: 99em;
  border-radius: 99em;
  border: 2px solid #eee;
  box-shadow: 0 3px 2px rgba(0, 0, 0, 0.3);  
  
}
</style>

<?
	{

		$r = array();
		dep_list($r);
		
?>

	<table class="table">
	<thead>
	<tr>
		<td width='36%'><b>ชื่อฝ่าย</b></td>
		<td width='44%'></td>
		<td width='15%'><b></b></td>
	</tr>
	</thead>
	<tbody>
<? 
	$in_structure_list = array();
	$i=1;
	foreach ($r as $list) {
		$style1 = 'style="font-weight: bold; padding-left: '.(($list['level']*40)+5).'px; '.$style2.'"';
?>
	<tr style='background-color: #eeeeee'>
		<td <?=$style1?>><?=$list['name']?></td>  
		<td><?=$list['doc_code']?></td>
		<td><?=$list['head']?></td>
		<td align="center"></td>
		<td align="center"></td>
	</tr>
	<tr>
		<td colspan='6'>
		<table class="table table-hover">
		<tbody>

<?
		$sql="SELECT * 
		FROM `user` 
		WHERE  
			user.mark_del = '0' AND 
			user.department_id = '$list[id]'
		ORDER BY 
			user.name";
		$result=mysqli_query($connect, $sql);
		if ($result!='' && mysqli_num_rows($result)>0) {
			$j=1;
			while ($row = mysqli_fetch_array($result)) {	
				$in_structure_list[] = $row['user_id'];
?>
		<tr>
			<td width='3%'><?=$j++?></td>
			<td width='20%'><?=$row['prefix']?><?=$row['name']?> &nbsp;&nbsp;<?=$row['surname']?></td>
			<td width='7%'><?=$row['nickname']?></td>
			<td width='9%'><?=$row['mobile']?></td>
			<td width='9%'><?=$row['tel']?></td>
			<td width='23%' class="hidden-xs"><?=$row['position']?></td>
			<td width='12%' class="hidden-xs"><?=$row['user_level_name']?></td>
			<td width='12%' class="hidden-xs">
<? 
/*$x = get_head_sequence($row['user_id']);
if (count($x)>0) {
	echo get_username($x[0]);
}*/
?>
			</td>
			<td width='5%'></td>
		</tr>	
<?
			} 
		} else {
		} 
?>
		</tbody>
		</table>
		</td>
	</tr>
<?	} ?>
	</tbody>
	</table>
	
<?
	}
	
echo template_footer();

function status($i) {
	switch ($i) {
		case 0: return "ปฏิบัติงานปรกติ";
		case 1: return "ลาออก / พ้นสภาพ";
		case 2: return "ลาคลอด";
		case 3: return "ลาบวช";
		case 4: return "ลารับราชการทหาร";
	}
}


function dep_list(&$result, $parent_id=0, $l=0) {
	global $connect;
	$sql = "SELECT 
		department.*,
		CONCAT(user.prefix, user.name, ' ', user.surname) AS uname
	FROM department 
	LEFT JOIN user ON department.head_user_id = user.user_id
	WHERE 
		department.parent_id = '$parent_id' AND
		department.is_branch = '0' AND
		department.mark_del = '0' 
	ORDER BY
		department.department_level_id DESC,
		department.department_no,
		department.department_name";
	$qry = mysqli_query($connect, $sql);
	while ($row = mysqli_fetch_array($qry)) {
		$result[] = array(
		'id' => $row['department_id'], 
		'code' => $row['department_no'], 
		'doc_code' => $row['doc_code'], 
		'name' => $row['department_name'], 
		'head' => $row['uname'], 
		'name_en' => $row['department_name_en'], 
		'parent_id' => $parent_id, 
		'level' => $l,
		'level_type_id' => $row['department_level_id'],
		'level_type' => $row['department_level_name']
		);
		dep_list($result, $row['department_id'], $l+1);
	}
}

?>

