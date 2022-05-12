<?php
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header("content-type: application/x-javascript; charset=tis-620");



	$data=$_GET['data'];
	$old=$_GET['old'];
	$val=$_GET['val'];

	require_once('../inc/connect.php'); 

     
	if ($data=='contract') { 
		echo "<select name='add_b2_period_id' style='width:300' onChange=\"dochange('period', this.value);\">\n";
		echo "<option value=''>------ เลือก ------</option>\n";
		$sql="SELECT * FROM contract_period WHERE contract_id = '$val'";	
		mysql_db_query($database, "SET NAMES 'tis620'", $connect);
		$result=mysql_db_query($database, $sql, $connect);
		while ($row = mysql_fetch_array($result)) {
			if ($row[period_id]==$old) 
				$selected = "selected";				
			else
				$selected = "";
			
			echo "<option value='$row[period_id]' $selected>$row[period_name] - $row[period_desc]</option>\n";
		}
		echo "</select>\n";

     } else if ($data=='period') {
		if ($val>0) {
			$sql="SELECT * FROM contract_period WHERE period_id = '$val'";	
			mysql_db_query($database, "SET NAMES 'tis620'", $connect);
			$result=mysql_db_query($database, $sql, $connect);
			if ($row = mysql_fetch_array($result)) {
				$v = $row[value];
				echo "<input type='text' name='add_b2_budget_total' size='20' maxlength='16' value='$v' onkeyup='isNumber(this)' onChange='isNumber(this)'>\n";			
			}
		}
     } else	if ($data=='edit_contract') { 
		echo "<select name='edit_b2_period_id' style='width:300' onChange=\"dochange('edit_period', this.value);\">\n";
		echo "<option value=''>------ เลือก ------</option>\n";
		$sql="SELECT * FROM contract_period WHERE contract_id = '$val'";	
		mysql_db_query($database, "SET NAMES 'tis620'", $connect);
		$result=mysql_db_query($database, $sql, $connect);
		while ($row = mysql_fetch_array($result)) {
			if ($row[period_id]==$old) 
				$selected = "selected";				
			else
				$selected = "";
			
			echo "<option value='$row[period_id]' $selected>$row[period_name] - $row[period_desc]</option>\n";
		}
		echo "</select>\n";

     } else if ($data=='edit_period') {
		if ($val>0) {
			$sql="SELECT * FROM contract_period WHERE period_id = '$val'";	
			mysql_db_query($database, "SET NAMES 'tis620'", $connect);
			$result=mysql_db_query($database, $sql, $connect);
			if ($row = mysql_fetch_array($result)) {
				$v = $row[value];
				echo "<input type='text' name='edit_b2_budget_total' size='20' maxlength='16' value='$v' onkeyup='isNumber(this)' onChange='isNumber(this)'>\n";			
			}
		}
	} 
?>