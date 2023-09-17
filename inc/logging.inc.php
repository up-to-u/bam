<? 
$logging_location = 'log/';
$logging_filename_prefix = 'rms_log_';
$logging_dest = $logging_location . $logging_filename_prefix;

function savelog($msg) {
    global $logging_dest, $user_id;
    $ym = date('Ym');
    $full_file_name = $logging_dest . $ym . '.log';
	$m = $user_id . '|' . $msg;
    logToFile($full_file_name, $m);
}

function savelogin_history($uid) {
    global $logging_location;
    $ym = date('Ym');
    $full_file_name = $logging_location.'login_'.$ym.'.log';
    logToFile($full_file_name, $uid.'|'.get_client_ip());
}

function saveforgotpass_history($uid) {
    global $logging_location;
    $ym = date('Ym');
    $full_file_name = $logging_location.'forgotpass_'.$ym.'.log';
    logToFile($full_file_name, $uid);
}

function logToFile($f, $msg) {
    $fd = fopen($f, 'a');
    fwrite($fd, date('Y-m-d h:i:s').'|'.$msg. "\n");
    fclose($fd);
}

?>
