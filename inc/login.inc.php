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
?>