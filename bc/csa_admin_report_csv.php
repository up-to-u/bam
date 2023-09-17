<?php
$csv_data = $_POST['csv_data'];
if (isset($csv_data)) {
	$d = json_decode($csv_data, true);
	$title = $d['title'];
	$header = $d['header'];
	$hcount = count($header);
	$data = $d['data'];
	$hash = $d['hash'];
	$hash2 = md5(serialize($data));
	/*if ($hash2==$hash) {*/
	if (true) {

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename="rms_export_'.date('Y-m-d_His').'.xls"');
		include('inc/include.inc.php');		
?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<HTML>
<HEAD>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
</HEAD><BODY>
<?=$title?><BR>
<TABLE x:str BORDER="1">
<tr valign='top'>
<? foreach ($header as $h) { ?>
	<td><?=$h?></td>
<? } ?>
</tr>
<? foreach ($data as $d) { ?>
<tr valign='top'>
<? 	for ($i=0; $i<$hcount; $i++) { ?>
	<td><?=$d[$i]?></td>
<? 	} ?>
</tr>
<? } ?>

</TABLE>
</BODY>
</HTML>
<?
	} else {
		echo 'เกิดข้อผิดพลาด รหัส Hash ไม่ถูกต้อง กรุณา ปิด / refresh และ download ใหม่';
	}
}
?>