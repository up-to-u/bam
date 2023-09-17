<?
$user_id = get_user_id();$user_code = get_user_code();
$dep_id = get_dep_id();
$member_id = get_person_id();

function get_person_id($t='cookie') {
	if ($t=='cookie') 
		return $_COOKIE['person_id'];
	else {
		session_start();
		return $_SESSION['person_id'];
	}
}
function get_user_id($t='cookie') {
	if ($t=='cookie') {
		return $_COOKIE['user_id'];
	} else {
		session_start();
		return $_SESSION['user_id'];
	}
}
function get_user_code($t='cookie') {
	if ($t=='cookie') {
		return $_COOKIE['user_code'];
	} else {
		session_start();
		return $_SESSION['user_code'];
	}
}
function get_dep_id($t='cookie') {
	if ($t=='cookie') 
		return $_COOKIE['dep_id'];
	else {
		session_start();
		return $_SESSION['dep_id'];
	}
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 12; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


function hash_password($pass) {
	return password_hash($pass, PASSWORD_DEFAULT);
}

function ver_password($pass_input, $pass_db) {
	return password_verify($pass_input, $pass_db);
}
	

function send_forgotpassword($email, $name, $pass) {
	global $email_from;
	
	$to = array($email);
	$cc = array();						
	$bcc = array();		
	$subject = 'ระบบ RMS ได้ Reset Password ของคุณ';
	$body = 'เรียน '.$name.'<br>
<br>
ระบบได้ทำการ Reset Password ของคุณแล้ว<br>
โดย Password ใหม่ของคุณที่จะใช้ในการ Login ครั้งต่อไป คือ :<br>
<br>
'.$pass.'<br>
<br>
<br>
กรุณา Login เข้าสู่ระบบ และเปลี่ยนรหัสผ่าน<br>
<a href="'.$host.'/admin.php" target="_new">เข้าสู่ระบบ</a> ';

	$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
	if ($x) {
		return true;
	} else {
		return false;
	}	
}

?>