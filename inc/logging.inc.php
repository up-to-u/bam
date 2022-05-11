<?
$logging_location = 'log4848/';
$logging_filename_prefix = 'cportal_log_';
$logging_dest = $logging_location.$logging_filename_prefix;

function savelog($msg) { 
	global $logging_dest, $user_id;
	logToFile($msg);
}

function logToFile($msg) { 
	global $logging_dest, $user_id;
	
	$ym = date('Ym');
	$full_file_name = $logging_dest.$ym.'.log';
	$fd = fopen($full_file_name, 'a');
	$str = '['.date('Y-m-d h:i:s', mktime()).']['.$user_id.'] '.$msg; 
	fwrite($fd, $str . "\n");
	fclose($fd);
}


?>