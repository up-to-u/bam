<?php
session_start();

$ldap_server = 'ldap://smepc22231.smebank.local'; //'ldap://192.168.222.31';
$eservice_smtp_server = 'smeinf054.smebank.local'; //'192.168.155.54';	
$eservice_email = 'coreportal@smebank.co.th';
$eservice_email_password = '12QWaszx';
$eservice_email_name = 'coreportal@smebank.co.th';

$actual_path = (dirname($_SERVER['PHP_SELF']) != '\\') ? dirname($_SERVER['PHP_SELF']) : "";
$actual_link = $http . "" . $_SERVER['SERVER_NAME'] . "" . $actual_path . "/";

function check_permission($p, $t = true)
{
	global $user_id, $dep_id, $connect;
	if ($p == '' || $p == 0) return false;
	if ($user_id == 0 || $dep_id == 0) return false;

	$max_p = 16;
	$pms = array();
	$sql2 = 'SELECT *	FROM `system_permission` WHERE user_id = "' . $user_id . '" OR department_id = "' . $dep_id . '"';
	$result2 = mysqli_query($connect, $sql2);
	while ($row2 = mysqli_fetch_array($result2)) {
		for ($j = 1; $j <= $max_p; $j++) {
			if ($row2['p_' . $j] == 1) {
				$pms[$j] = 1;
			}
		}
	}
	if ($pms[$p] == 1) return true;

	if ($t) {
		template_header();
?>
		<script language='JavaScript'>
			//			document.location='index.php'
		</script>
		<br>
		<b>
			<font color='red'>ท่านไม่มีสิทธิในการใช้งานระบบนี้</font>
		</b>
		<br>

<?php
		template_footer();
		die;
	} else {
		return false;
	}
}

function form_input_filter($n, $len = 255)
{
	if (strlen($n) > 5000) {
		$n = substr($n, 0, 5000);
	}
	//	return addslashes($n);	
	return sql_filter($n);
	//	$n = script_filter($n);
	//	$n = sql_filter($n);
	//	return $n;
}

function sql_filter($s)
{
	global $connect;
	return mysqli_real_escape_string($connect, $s);
}

function get_auto_id($table)
{
	global $database, $sql, $connect;
	$sql = "SHOW TABLE STATUS LIKE '$table' ";
	$result = mysql_db_query($database, $sql, $connect);
	$row = mysqli_fetch_array($result);
	return $row['Auto_increment'];
}

function mail_service($from, $to, $cc, $bcc, $subject, $message, $attach_name = '', $attach_location = '', $is_urgent = false)
{
	global $debug;

	if ($debug == 1) {
		echo "<div style='background: #ffcc99'>";
		echo "<b>MAIL SERVICE</b><BR>";
		echo "<b>FROM :</b> $from<BR>";
		echo "<b>TO :</b> " . implode(',', $to) . "<BR>";
		echo "<b>CC :</b> " . implode(',', $cc) . "<BR>";
		echo "<b>SUBJECT :</b> $subject<BR><br>";
		echo "</div>";
		echo "$message<BR>";
		return true;
	} else {
		$subject = '=?utf-8?B?' . base64_encode($subject) . '?=';
		$x = phpmailer($to, $cc, $subject, $message, $from, false, false, $is_urgent);
		if ($x) {
			return true;
		}
	}
	return false;
}

function phpmailer($to, $cc, $subject, $body, $from, $is_ssl = false, $is_auten = false, $is_urgent = false)
{
	global $eservice_email, $eservice_email_password, $eservice_smtp_server, $eservice_email_name;
	require_once('phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();
	$mail->IsSMTP();                      // telling the class to use SMTP
	$mail->SMTPDebug = 0;
	$mail->CharSet = 'text/html; charset=UTF-8;';

	//	$mail->Host = gethostbyname($eservice_smtp_server);
	$mail->Host = $eservice_smtp_server;
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

	if ($is_auten) {
		$mail->SMTPAuth = true;
		$mail->Username = $eservice_email;
		$mail->Password = $eservice_email_password;
	} else {
		$mail->SMTPAuth = false;
	}

	if ($is_ssl) {
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';
	} else {
		$mail->Port = 25;
		$mail->SMTPSecure = 'none';
	}

	if ($is_urgent) {
		$mail->Priority = 1;
		$mail->AddCustomHeader("X-MSMail-Priority: High");
		$mail->AddCustomHeader("Importance: High");
	}

	if ($from != '') {
		$mail->SetFrom($from, $from);
	} else {
		$mail->SetFrom($eservice_email_name, $eservice_email_name);
	}
	$mail->Subject = $subject;
	$mail->IsHTML(true);
	$mail->Body = $body;

	foreach ($to as $t) {
		$mail->AddAddress($t, $t);
	}
	foreach ($cc as $c) {
		$mail->AddCC($c, $c);
	}

	if (!$mail->Send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		return false;
	} else {
		return true;
	}
}

function get_user_dep_id($uid)
{
	global $connect;
	$sql = "SELECT department_id FROM user WHERE user_id = '$uid' ";
	$qry = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($qry)) {
		return $row['department_id'];
	}
	return 0;
}

function get_username($uid)
{
	global $connect;
	$sql = "SELECT * FROM user WHERE user_id = '$uid' ";
	$qry = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($qry)) {
		return $row['prefix'] . $row['name'] . ' ' . $row['surname'];
	}
	return '';
}

function time2int($t)
{
	list($t_h, $t_m, $s) = explode(':', $t);
	return ($t_h * 10000) + ($t_m * 100) + $s;
}

function time2sec($t)
{
	list($h, $m, $s) = explode(':', $t);
	return ($h * 3600) + ($m * 60) + $s;
}

function timediff($t1, $t2)
{
	return time2sec($t2) - time2sec($t1);
}

function resize_image($input, $output, $width)
{
	$images = $input;
	$new_images = $output;
	//	$width=200; //*** Fix Width & Heigh (Autu caculate) ***// 
	$size = GetimageSize($images);
	$height = round($width * $size[1] / $size[0]);

	if (stristr($input, 'png'))
		$images_orig = imagecreatefrompng($images);
	else if (stristr($input, 'gif'))
		$images_orig = imagecreatefromgif($images);
	else
		$images_orig = ImageCreateFromJPEG($images);

	$photoX = ImagesX($images_orig);
	$photoY = ImagesY($images_orig);
	$images_fin = ImageCreateTrueColor($width, $height);
	ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
	ImageJPEG($images_fin, $new_images);
	ImageDestroy($images_orig);
	ImageDestroy($images_fin);
}

function create_thumbnail($src_image, $dest_image, $ext_src, $sizew, $sizeh, $qual, $gd_ver = 1)
{
	if (!file_exists($src_image)) return "ERROR";
	$size = GetImageSize($src_image);
	$ratio = $size[0] / $size[1];

	if ($ratio >= 1) {
		$widthbig = $sizew;
		$heightbig = ($sizew / $size[0]) * $size[1];
	} else {
		$widthbig = ($sizeh / $size[1]) * $size[0];
		$heightbig = $sizeh;
	}

	if ($ext_src == ".jpg" || $ext_src == ".jpeg") {
		$source = ImageCreateFromJPEG($src_image);
	} else if ($ext_src == ".gif") {
		$source = imagecreatefromgif($src_image);
	} else if ($ext_src == ".png") {
		$source = imagecreatefrompng($src_image);
	} else {
		return "";
	}

	if ($gd_ver == 1) {
		$thumbb = ImageCreateTrueColor($sizew, $sizeh);
		$white = imagecolorallocate($thumbb, 255, 255, 255);
		imagefill($thumbb, 0, 0, $white);
		ImageCopyResampled($thumbb, $source, 0, 0, 0, 0, $widthbig, $heightbig, $size[0], $size[1]);
	} else {
		$thumbb = ImageCreate($sizew, $sizeh);
		$white = imagecolorallocate($thumbb, 255, 255, 255);
		imagefill($thumbb, 0, 0, $white);
		ImageCopyResized($thumbb, $source, 0, 0, 0, 0, $widthbig, $heightbig, $size[0], $size[1]);
	}
	ImageJPEG($thumbb, $dest_image, $qual);
	ImageDestroy($thumbb);
	return "$widthbig,$heightbig";
}

function mysqldate2en_date($mdate)
{
	if ($mdate == '') return '---';
	if ($mdate == '0000-00-00') return '---';

	return date('d/m/Y', strtotime($mdate));
}

function mysqldate2th_date($mdate)
{
	if ($mdate == '') return '---';
	if ($mdate == '0000-00-00' || $mdate == '0000-00-00 00:00:00') return '---';

	$i = strtotime($mdate);
	$d = date('d', $i) + 0;
	switch (date('m', $i)) {
		case 1:
			$m = 'ม.ค.';
			break;
		case 2:
			$m = 'ก.พ.';
			break;
		case 3:
			$m = 'มี.ค.';
			break;
		case 4:
			$m = 'เม.ย.';
			break;
		case 5:
			$m = 'พ.ค.';
			break;
		case 6:
			$m = 'มิ.ย.';
			break;
		case 7:
			$m = 'ก.ค.';
			break;
		case 8:
			$m = 'ส.ค.';
			break;
		case 9:
			$m = 'ก.ย.';
			break;
		case 10:
			$m = 'ต.ค.';
			break;
		case 11:
			$m = 'พ.ย.';
			break;
		case 12:
			$m = 'ธ.ค.';
			break;
	}
	$y = (date('Y', $i) + 543);
	return $d . ' ' . $m . ' ' . substr($y, 2, 2);
}

function mysqldate2th_datetime($d)
{

	list($mdate, $mtime) = explode(' ', $d);
	if ($mdate == '') return '---';
	if ($mdate == '0000-00-00') return '---';

	$i = strtotime($mdate);
	$d = date('d', $i) + 0;
	switch (date('m', $i)) {
		case 1:
			$m = 'ม.ค.';
			break;
		case 2:
			$m = 'ก.พ.';
			break;
		case 3:
			$m = 'มี.ค.';
			break;
		case 4:
			$m = 'เม.ย.';
			break;
		case 5:
			$m = 'พ.ค.';
			break;
		case 6:
			$m = 'มิ.ย.';
			break;
		case 7:
			$m = 'ก.ค.';
			break;
		case 8:
			$m = 'ส.ค.';
			break;
		case 9:
			$m = 'ก.ย.';
			break;
		case 10:
			$m = 'ต.ค.';
			break;
		case 11:
			$m = 'พ.ย.';
			break;
		case 12:
			$m = 'ธ.ค.';
			break;
	}
	$y = (date('Y', $i) + 543);
	return $d . ' ' . $m . ' ' . substr($y, 2, 2) . ' - ' . $mtime;
}

function mysqldate2th_time($d)
{
	list($mdate, $mtime) = explode(' ', $d);
	return $mtime;
}

function mysqldate2th_date2($mdate)
{
	if ($mdate == '') return '---';
	if ($mdate == '0000-00-00') return '---';

	$i = strtotime($mdate);
	$d = date('d', $i) + 0;
	switch (date('m', $i)) {
		case 1:
			$m = 'มกราคม';
			break;
		case 2:
			$m = 'กุมภาพันธ์';
			break;
		case 3:
			$m = 'มีนาคม';
			break;
		case 4:
			$m = 'เมษายน';
			break;
		case 5:
			$m = 'พฤษภาคม';
			break;
		case 6:
			$m = 'มิถุนายน';
			break;
		case 7:
			$m = 'กรกฎาคม';
			break;
		case 8:
			$m = 'สิงหาคม';
			break;
		case 9:
			$m = 'กันยายน';
			break;
		case 10:
			$m = 'ตุลาคม';
			break;
		case 11:
			$m = 'พฤศจิกายน';
			break;
		case 12:
			$m = 'ธันวาคม';
			break;
	}
	$y = (date('Y', $i) + 543);
	return $d . ' ' . $m . ' ' . $y;
}

function mysqldate2th_date3($mdate)
{
	if ($mdate == '') return '---';
	if ($mdate == '0000-00-00') return '---';

	$i = strtotime($mdate);
	$d = date('d', $i) + 0;
	switch (date('m', $i)) {
		case 1:
			$m = 'ม.ค.';
			break;
		case 2:
			$m = 'ก.พ.';
			break;
		case 3:
			$m = 'มี.ค.';
			break;
		case 4:
			$m = 'เม.ย.';
			break;
		case 5:
			$m = 'พ.ค.';
			break;
		case 6:
			$m = 'มิ.ย.';
			break;
		case 7:
			$m = 'ก.ค.';
			break;
		case 8:
			$m = 'ส.ค.';
			break;
		case 9:
			$m = 'ก.ย.';
			break;
		case 10:
			$m = 'ต.ค.';
			break;
		case 11:
			$m = 'พ.ย.';
			break;
		case 12:
			$m = 'ธ.ค.';
			break;
	}
	$y = (date('Y', $i) + 543);
	$wd = date('D', $i);
	return weekday_name($wd) . ' - ' . $d . ' ' . $m . ' ' . substr($y, 2, 2);
}

function mysqldate2th_date4($mdate)
{
	if ($mdate == '') return '---';
	if ($mdate == '0000-00-00') return '---';
	$i = strtotime($mdate);
	switch (date('N', $i)) {
		case 1:
			$dd = 'จันทร์';
			break;
		case 2:
			$dd = 'อังคาร';
			break;
		case 3:
			$dd = 'พุธ';
			break;
		case 4:
			$dd = 'พฤหัสบดี';
			break;
		case 5:
			$dd = 'ศุกร์';
			break;
		case 6:
			$dd = 'เสาร์';
			break;
		case 7:
			$dd = 'อาทิตย์';
			break;
	}
	switch (date('m', $i)) {
		case 1:
			$m = 'มกราคม';
			break;
		case 2:
			$m = 'กุมภาพันธ์';
			break;
		case 3:
			$m = 'มีนาคม';
			break;
		case 4:
			$m = 'เมษายน';
			break;
		case 5:
			$m = 'พฤษภาคม';
			break;
		case 6:
			$m = 'มิถุนายน';
			break;
		case 7:
			$m = 'กรกฎาคม';
			break;
		case 8:
			$m = 'สิงหาคม';
			break;
		case 9:
			$m = 'กันยายน';
			break;
		case 10:
			$m = 'ตุลาคม';
			break;
		case 11:
			$m = 'พฤศจิกายน';
			break;
		case 12:
			$m = 'ธันวาคม';
			break;
	}
	$d = intVal(date('d', $i));
	$y = (date('Y', $i) + 543);
	$wd = date('D', $i);
	return 'วัน' . $dd . 'ที่ ' . $d . ' ' . $m . ' พ.ศ. ' . $y;
}

function mysqldate2th_date5($mdate)
{
	if ($mdate == '') return '---';
	if ($mdate == '0000-00-00') return '---';

	$i = strtotime($mdate);
	$d = date('d', $i) + 0;
	switch (date('m', $i)) {
		case 1:
			$m = 'มกราคม';
			break;
		case 2:
			$m = 'กุมภาพันธ์';
			break;
		case 3:
			$m = 'มีนาคม';
			break;
		case 4:
			$m = 'เมษายน';
			break;
		case 5:
			$m = 'พฤษภาคม';
			break;
		case 6:
			$m = 'มิถุนายน';
			break;
		case 7:
			$m = 'กรกฎาคม';
			break;
		case 8:
			$m = 'สิงหาคม';
			break;
		case 9:
			$m = 'กันยายน';
			break;
		case 10:
			$m = 'ตุลาคม';
			break;
		case 11:
			$m = 'พฤศจิกายน';
			break;
		case 12:
			$m = 'ธันวาคม';
			break;
	}
	$y = (date('Y', $i) + 543);
	return $d . ' ' . $m . ' พ.ศ. ' . $y;
}
function month_name($m)
{
	switch ($m) {
		case 1:
			return 'มกราคม';
		case 2:
			return 'กุมภาพันธ์';
		case 3:
			return 'มีนาคม';
		case 4:
			return 'เมษายน';
		case 5:
			return 'พฤษภาคม';
		case 6:
			return 'มิถุนายน';
		case 7:
			return 'กรกฎาคม';
		case 8:
			return 'สิงหาคม';
		case 9:
			return 'กันยายน';
		case 10:
			return 'ตุลาคม';
		case 11:
			return 'พฤศจิกายน';
		case 12:
			return 'ธันวาคม';
	}
}

function month_name2($m)
{
	switch ($m) {
		case 1:
			$m = 'ม.ค.';
			break;
		case 2:
			$m = 'ก.พ.';
			break;
		case 3:
			$m = 'มี.ค.';
			break;
		case 4:
			$m = 'เม.ย.';
			break;
		case 5:
			$m = 'พ.ค.';
			break;
		case 6:
			$m = 'มิ.ย.';
			break;
		case 7:
			$m = 'ก.ค.';
			break;
		case 8:
			$m = 'ส.ค.';
			break;
		case 9:
			$m = 'ก.ย.';
			break;
		case 10:
			$m = 'ต.ค.';
			break;
		case 11:
			$m = 'พ.ย.';
			break;
		case 12:
			$m = 'ธ.ค.';
			break;
	}
	return $m;
}

function weekday_name($w)
{
	switch ($w) {
		case 'Mon':
			return 'จันทร์';
		case 'Tue':
			return 'อังคาร';
		case 'Wed':
			return 'พุธ';
		case 'Thu':
			return 'พฤหัสบดี';
		case 'Fri':
			return 'ศุกร์';
		case 'Sat':
			return 'เสาร์';
		case 'Sun':
			return 'อาทิตย์';
	}
}
function weekday_name2($w)
{
	switch ($w) {
		case '0':
			return 'อาทิตย์';
		case '1':
			return 'จันทร์';
		case '2':
			return 'อังคาร';
		case '3':
			return 'พุธ';
		case '4':
			return 'พฤหัสบดี';
		case '5':
			return 'ศุกร์';
		case '6':
			return 'เสาร์';
	}
}
function weekday_num($w)
{
	switch ($w) {
		case 'Mon':
			return 1;
		case 'Tue':
			return 2;
		case 'Wed':
			return 3;
		case 'Thu':
			return 4;
		case 'Fri':
			return 5;
		case 'Sat':
			return 6;
		case 'Sun':
			return 7;
	}
}


function number_filter($n)
{
	//$n = floor(($n*100))/100;
	return number_format($n, 2, '.', ',');
}

function percent_filter($n, $total)
{
	if ($total > 0 && $n > 0) {
		$p = $n / $total * 100;
		if (is_int($p)) return $p . '%';
		return number_format($p, 2, '.', ',') . '%';
	}
	return '';
}

function number_filter2($n)
{
	if (round($n) == $n) {
		return number_format($n, 0, '.', ',');
	} else {
		//$n = floor(($n*100))/100;
		return number_format($n, 2, '.', ',');
	}
}

function fill_zero($n, $len)
{
	return str_repeat('0', $len - strlen($n)) . $n;
}

function num2string($num)
{
	$num = number_format($num, 2, '.', '');

	$digit = array('หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
	$unit = array('สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน');

	if ($num == 0) return 'ศูนย์บาทถ้วน';
	if (strpos($num, '.') == 0) $num .= '.00';

	$tmp = substr($num, 0, strpos($num, '.'));
	while (strlen($tmp) > 6) {
		$cut = strlen($tmp) % 6;
		if ($cut == 0) $cut = 6;
		$data = substr($tmp, 0, $cut);
		for ($i = 0; $i < strlen($data) - 2; $i++) {
			if ($data[$i] == 0)
				continue;
			$ans .= $digit[$data[$i] - 1] . $unit[strlen($data) - $i - 2];
		}
		$ans .= num2string_2digit(substr($data, strlen($data) - 2)) . 'ล้าน';
		$tmp = substr($tmp, $cut);
	}

	for ($i = 0; $i < strlen($tmp) - 2; $i++) {
		if ($tmp[$i] == 0) continue;
		$ans .= $digit[$tmp[$i] - 1] . $unit[strlen($tmp) - $i - 2];
	}

	$ans .= num2string_2digit(substr($tmp, strlen($tmp) - 2)) . 'บาท';
	$tmp = substr($num, strpos($num, '.') + 1);

	if (strlen($tmp) == 1 && $tmp > 0) {
		$tmp .= '0';
	}

	if (substr($tmp, 0, 2) == '00') return $ans . 'ถ้วน';
	return $ans . num2string_2digit($tmp) . 'สตางค์';
}

function num2string_2digit($num)
{
	$digit = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
	$ans = '';
	$num = sprintf('%d', $num);

	if (strlen($num) == 1 && $num > 0) {
		return $digit[$num];
	}

	if ($num[0] == 2)
		$ans .= 'ยี่';
	else if ($num[0] > 2)
		$ans .= $digit[$num[0]];

	if ($num[0] > 0) $ans .= 'สิบ';
	if ($num[1] > 1)
		$ans .= $digit[$num[1]];
	else if ($num[1] == 1)
		$ans .= 'เอ็ด';

	return $ans;
}

function clickableUrls($html)
{
	return $result = preg_replace(
		'%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s',
		'<a href="$1" target="_blank">$1</a>',
		$html
	);
}

function create_notification($uid, $msg, $owner_id = 0, $type, $due_date = '0000-00-00')
{
	global $connect;
	global $debug;
	if (count($uid) > 0) {
		$msg = addslashes($msg);

		mysqli_autocommit($connect, FALSE);
		$qx = true;
		$uid = array_unique($uid);

		foreach ($uid as $u) {
			$sql = "INSERT INTO `notification` (notification_type, user_id, owner_id, msg, due_date, create_date, is_read) VALUES ('$type', '$u', '$owner_id', '$msg', '$due_date', now(), '0') ";
			$q = mysqli_query($connect, $sql);
			$qx = ($qx and $q);
		}


		if ($qx) {
			if ($debug == 1) {
				echo "<div style='background: #ff99cc'>";
				echo "<b>NOTIFICATION</b><BR>";
				echo "<b>FROM :</b> $owner_id<BR>";
				echo "<b>TO :</b> " . implode(',', $uid) . "<BR>";
				echo "</div>";
				echo "$msg<BR>";
				echo "<BR><BR>";
			}
			mysqli_commit($connect);
			return true;
		} else {
			echo $sql;
			mysqli_rollback($connect);
		}
	}
	return false;
}

function imap_connect()
{
}

function get_imap_location()
{
	//$mailbox = "{mail.depa.or.th:143/novalidate-cert}INBOX";
	return '{imap.mail.go.th:993/imap/ssl}';
}


function get_notification_unread()
{
	global $connect;
	global $user_id;
	$sql = "SELECT COUNT(*) AS num FROM notification WHERE user_id = '$user_id' AND is_read = 0 ";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return intVal($row[num]);
}

function get_mail_unread()
{
	return 0;
	global $connect;
	global $user_id;

	$sql = "SELECT * FROM `user` WHERE `user_id` = $user_id ";
	$result = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($result)) {
		$user = $row[email];
		$password = $row[imap_password];
	}

	if ($user != '' && $password != '') {
		$mailbox = get_imap_location();
		imap_timeout(10);
		if (!$mbx = imap_open($mailbox, $user, $password)) {
			return -1;
		}
		$emails = imap_search($mbx, 'UNSEEN');
		$cnt = count($emails);
		imap_close($mbx);

		$arr_data[0] = $cnt;
		$arr_data[1] = time();
		return $arr_data;
	}
	return array(-1);
}

function format_size($size)
{
	$sizes = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
	if ($size == 0) {
		return ('n/a');
	} else {
		return (round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]);
	}
}

function mkdir_fix($path, $m, $r)
{
	$parts = explode('/', $path);
	$trail = '';
	foreach ($parts as $part) {
		if ($part != '') {
			$trail .= $part . '/';
			if ($part != '..' && !file_exists($trail)) mkdir($trail, $m, true);
		}
	}
}

function check_is_int($int)
{
	// First check if it's a numeric value as either a string or number
	if (is_numeric($int) === TRUE) {
		// It's a number, but it has to be an integer
		if ((int)$int == $int) {
			return TRUE;
			// It's a number, but not an integer, so we fail
		} else {
			return FALSE;
		}
		// Not a number
	} else {
		return FALSE;
	}
}

function set_person_login($person_id, $t = 'cookie')
{
	global $domain;

	if ($t == 'cookie') {
		$login_time = time() + (60 * 60 * 24);
		setcookie('person_id', $person_id, $login_time, '/', $domain);
		$_COOKIE['person_id'] = $person_id;
	} else {
		session_start();
		$_SESSION['person_id'] = $person_id;
	}
}
function set_person_logout($t = 'cookie')
{
	global $domain;

	if ($t == 'cookie') {
		$login_time = time() + (3600 * 24);
		setcookie('person_id', '', $login_time, '/', $domain);
		$_COOKIE['person_id'] = '';
	} else {
		session_start();
		$_SESSION['person_id'] = '';
	}
}


function set_login($user_id, $user_code, $dep_id, $dep_code, $hash, $t = 'cookie')
{
	global $domain;

	if ($t == 'cookie') {
		$login_time = time() + (60 * 60 * 24);
		setcookie('user_id', $user_id, $login_time, '/', $domain);
		setcookie('dep_id', $dep_id, $login_time, '/', $domain);
		setcookie('user_code', $user_code, $login_time, '/', $domain);
		setcookie('dep_code', $dep_code, $login_time, '/', $domain);
		setcookie('hash', $hash, $login_time, '/', $domain);

		$_COOKIE['user_id'] = $user_id;
		$_COOKIE['dep_id'] = $dep_id;
		$_COOKIE['user_code'] = $user_code;
		$_COOKIE['dep_code'] = $dep_code;
		$_COOKIE['hash'] = $hash;
	} else {
		session_start();
		$_SESSION['user_id'] = $user_id;
		$_SESSION['dep_id'] = $dep_id;
		$_SESSION['user_code'] = $user_code;
		$_SESSION['dep_code'] = $dep_code;
		$_SESSION['hash'] = $hash;
	}
}

function set_dep_id($did, $t = 'cookie')
{
	global $domain;

	if ($t == 'cookie') {
		$login_time = time() + (60 * 60 * 24);
		setcookie('dep_id', $did, $login_time, '/', $domain);
		$_COOKIE['dep_id'] = $did;
	} else {
		session_start();
		$_SESSION['dep_id'] = $did;
	}
}

function set_login_log($uid)
{
	if ($uid == 0) $uid = get_user_id();
	if ($uid > 0) {
		$ip = getIp();
		$agent = addslashes($_SERVER['HTTP_USER_AGENT']);
		$sql = "INSERT INTO history_login (user_id, ip, create_date, user_agent) VALUES ('$uid', '$ip', now(), '$agent') ";
		mysqli_query($connect, $sql);
	}
}

function set_logout($t = 'cookie')
{
	global $domain;

	if ($t == 'cookie') {
		$login_time = time() + (3600 * 24);
		setcookie('user_id', '', $login_time, '/', $domain);
		setcookie('dep_id', '', $login_time, '/', $domain);
		setcookie('user_code', '', $login_time, '/', $domain);
		setcookie('dep_code', '', $login_time, '/', $domain);
		setcookie('login', '', $login, '/', $domain);
		setcookie('password', '', $login_time, '/', $domain);
		setcookie('hash', '', $login_time, '/', $domain);

		$_COOKIE['user_id'] = '';
		$_COOKIE['dep_id'] = '';
		$_COOKIE['dep_code'] = '';
		$_COOKIE['hash'] = '';
		$_COOKIE['user_code'] = '';
	} else {
		session_start();
		$_SESSION['user_id'] = '';
		$_SESSION['dep_id'] = '';
		$_SESSION['user_code'] = '';
		$_SESSION['dep_code'] = '';
		$_SESSION['hash'] = '';
	}
}

function check_login($login, $password)
{
	//	return ldap_authen($login, $password);
	//echo "[]";
	return ad_authen($login, $password);
}
function ad_authen($login, $password, &$info)
{
	$info = array('name' => 'Ratchapong', 		'surname' => 'Santimataneedol', 		'name_en' => 'Ratchapong', 		'surname_en' => 'Santimataneedol', 		'tel' => $telephonenumber, 		'email' => $email, 		'code' => '60236', 		'pid' => $pid, 		'department_name' => $department, 		'level' => $level, 		'position' => $position);
	return true;
}/*
function ldap_authen($login, $password) {
	global $ldap_server;
	$server = $ldap_server;
	
	$success = false;
	$admin='cn=manager,cn=internal,dc=sipa,dc=or,dc=th';
	//$passwd='NNRHe4VNRpi5hHL9';
	$passwd='AYsOp8Rm1CbMErjL';
	$base_dn = 'dc=sipa,dc=or,dc=th';

	$ds=ldap_connect($server);
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ds, LDAP_OPT_REFERRALS,0);

	if ($ds) {
		$r=ldap_bind($ds, $admin, $passwd);
		if(!$r) die('<b>เกิดข้อผิดพลาด</b><br><br>ไม่สามารถเชื่อมต่อ ldap server ได้ กรุณาติดต่อเจ้าหน้าที่ดูแลระบบ<br>');
		$search_filter = 'uid='.$login;		
		$results = ldap_search($ds, $base_dn, $search_filter);
		$member_list = ldap_get_entries($ds, $results);
		
		$pwd = '{sha}'.base64_encode(sha1($password,true));
		$ldap_pass = $member_list[0]['userpassword'][0];
		if ($ldap_pass==$pwd) $success = true;
		ldap_close($ds);
	} else {
		die('Unable to connect to LDAP server');
	}
	return $success;
}

function ldap_change_password($login, $new_password) {
	global $ldap_server;
	$server = $ldap_server;
	
	$success = false;
	$admin='cn=manager,cn=internal,dc=sipa,dc=or,dc=th';
	//$passwd='NNRHe4VNRpi5hHL9';
	$passwd='AYsOp8Rm1CbMErjL';
	$base_dn = 'dc=sipa,dc=or,dc=th';

	$ds=ldap_connect($server);
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ds, LDAP_OPT_REFERRALS,0);

	if ($ds) {
		$r=ldap_bind($ds, $admin, $passwd);
		if(!$r) die("ldap_bind failed<br>");
		$search_filter = 'uid='.$login;		
		$results = ldap_search($ds, $base_dn, $search_filter);
		$member_list = ldap_get_entries($ds, $results);
		
		$pwd = '{sha}'.base64_encode(sha1($new_password,true));
		$newEntry = array('userpassword' => $pwd);
		
		if (ldap_mod_replace($ds, $base_dn, $newEntry)) $success = true;
		ldap_close($ds);
	} else {
		die("Unable to connect to LDAP server");
	}
	return $success;
}
*//*function ad_authen($login, $password, &$info) {	
	global $ldap_server;
	$server = $ldap_server; // port 389
	
	$success = false;
		
	$admin='CN=coreportal coreportal,OU=Special_Mail,OU=SMEBANK_Mail,DC=smebank,DC=local';
	$passwd='Smebank@63';
	
	$base_dn = 'dc=smebank,dc=local';

	$ds=ldap_connect($server);
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ds, LDAP_OPT_REFERRALS,0);
	
	if ($ds) {
		
		$r=ldap_bind($ds, $admin, $passwd);
		if(!$r) {
			echo 'ERROR : '.ldap_error($ds).'['.ldap_errno($ds).']<BR>';
			exit;
			die('Invalid Admin login / password <br>');
		}
		
		$search_filter = 'sAMAccountName='.$login;		
		$results = ldap_search($ds, $base_dn, $search_filter);
		$member_list = ldap_get_entries($ds, $results);
		ldap_unbind($r);
		
		list($name, $surname) = explode(' ', $member_list[0]['description'][0]);
		list($name_en, $surname_en) = explode(' ', $user_name_en = $member_list[0]['cn'][0]);
		$telephonenumber = $member_list[0]['telephonenumber'][0];
		$code = $member_list[0]['initials'][0];
		$pid = $member_list[0]['employeeid'][0];
		list($tmp, $email) = explode(':', $member_list[0]['proxyaddresses'][0]);
		$department = $member_list[0]['department'][0];
		$position = $member_list[0]['title'][0];
		$level = intval($member_list[0]['physicaldeliveryofficename'][0]);
		
		$info = array(
			'name' => $name, 
			'surname' => $surname, 
			'name_en' =>$name_en, 
			'surname_en' =>$surname_en, 
			'tel' => $telephonenumber, 
			'email' => $email, 
			'code' => $code, 
			'pid' => $pid, 
			'department_name' =>$department, 
			'level' =>$level, 
			'position' =>$position
		);	
		
		$user_dn = $member_list[0]['distinguishedname'][0];
		$r=ldap_bind($ds, $user_dn, $password);
		if(!$r) {
			$success = false;
		} else {
			$success = true;
		}
		ldap_unbind($r);
		ldap_close($ds);
	} else {
		die('Unable to connect to LDAP server');
	}
	return $success;
}	
/*
function ad_getinfo($code) {	
	if ($_SERVER['HTTP_HOST']=='localhost') {
		$name = 'ทดสอบ';
		$surname = 'ทดสอบ';
		$email = 'ratchapong@smebank.co.th';
		
		$info = array(
			'name' => $name, 
			'surname' => $surname, 
			'name_en' =>$name_en, 
			'surname_en' =>$surname_en, 
			'tel' => $telephonenumber, 
			'email' => $email, 
			'code' => $code, 
			'pid' => $pid, 
			'department_name' =>$department, 
			'level' =>$level, 
			'position' =>$position
		);				
		return $info;		
	} else {
		$server='ldap://192.168.222.31';
		$admin='CN=coreportal,OU=Mail,OU=SMEbank_Groups,DC=smebank,DC=local';
		$passwd='12345678';
		
//		$admin='CN=Ratchapong Santimataneedol,OU=ฝ่ายสนับสนุนการเข้าถึงของผู้ประกอบการ(SMEs Core Portal),OU=HQ,OU=SMEBANK_ORG,DC=smebank,DC=local';
//		$passwd='rs60236.';
		$admin='CN=coreportal coreportal,OU=Special_Mail,OU=SMEBANK_Mail,DC=smebank,DC=local';
		$passwd='12345678';
		
		$base_dn = 'dc=smebank,dc=local';

		$ds=ldap_connect($server);
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
		ldap_set_option($ds, LDAP_OPT_REFERRALS,0);
		

		if ($ds) {
			$info = array();
			
			$r=ldap_bind($ds, $admin, $passwd);
			if(!$r) {
				echo 'ERROR : '.ldap_error($ds).'['.ldap_errno($ds).']<BR>';
				exit;
				die('Invalid Admin login / password <br>');
			}
			
			$search_filter = 'initials='.$code;		
			$results = ldap_search($ds, $base_dn, $search_filter);
			$member_list = ldap_get_entries($ds, $results);
			ldap_unbind($r);
			
			list($name, $surname) = explode(' ', $member_list[0]['description'][0]);
			list($name_en, $surname_en) = explode(' ', $user_name_en = $member_list[0]['cn'][0]);
			$telephonenumber = $member_list[0]['telephonenumber'][0];
			$code = $member_list[0]['initials'][0];
			$pid = $member_list[0]['employeeid'][0];
			list($tmp, $email) = explode(':', $member_list[0]['proxyaddresses'][0]);
			$department = $member_list[0]['department'][0];
			$position = $member_list[0]['title'][0];
			$level = intval($member_list[0]['physicaldeliveryofficename'][0]);
			
			$info = array(
				'name' => $name, 
				'surname' => $surname, 
				'name_en' =>$name_en, 
				'surname_en' =>$surname_en, 
				'tel' => $telephonenumber, 
				'email' => $email, 
				'code' => $code, 
				'pid' => $pid, 
				'department_name' =>$department, 
				'level' =>$level, 
				'position' =>$position
			);			

			ldap_unbind($r);
			ldap_close($ds);
		} else {
			die('Unable to connect to LDAP server');
		}
		return $info;
	}
}	

function ad_change_password($login, $password) {
	$success = false;
//	$server='ldaps://ad.depa.or.th';
	$server='ldaps://192.168.80.9';
	$admin='cn=administrator,cn=users,dc=sipa,dc=or,dc=th';
	$passwd='12QWaszx';
	$base_dn = 'dc=sipa,dc=or,dc=th';

	$ds=ldap_connect($server, 636);
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ds, LDAP_OPT_REFERRALS,0);

	if ($ds) {
		$r=ldap_bind($ds, $admin, $passwd);
		if(!$r) {
			echo "ERROR : ".ldap_error($ds).'<BR>';
			die('Invalid Admin login / password');
		}
		$search_filter = 'sAMAccountName='.$login;		
		$results = ldap_search($ds, $base_dn, $search_filter);
		$member_list = ldap_get_entries($ds, $results);
		ldap_unbind($r);
		
		$user_dn = $member_list[0]['distinguishedname'][0];
		$newPass = pwd_enc($password);
		$user['unicodePwd'] = $newPass;		
		$result = ldap_mod_replace($ds, $user_dn, $user);
	
		if ($result) 
			$success = true;
		else {
			echo "เกิดข้อผิดพลาด : ".ldap_error($ds);
			$success = false;
		}
		
		ldap_unbind($r);
		ldap_close($ds);
	} else {
		die('Unable to connect to LDAP server');
	}
	return $success;
}
*/
function pwd_enc($p)
{
	$newpassword = "\"" . $p . "\"";
	$len = strlen($newpassword);
	for ($i = 0; $i < $len; $i++) 		$newpass .= "$newpassword[$i]\000";
	//		$newpass .= "{$newpassword{$i}}\000";
	return $newpass;
}

function getIp()
{
	$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if ($ip_address == NULL || $ip_address == '') {
		$ip_address = $_SERVER[REMOTE_ADDR];
	}
	return $ip_address;
}

function FileSizeConvert($bytes)
{
	$bytes = floatval($bytes);
	$arBytes = array(
		0 => array(
			'UNIT' => 'TB',
			'VALUE' => pow(1024, 4)
		),
		1 => array(
			'UNIT' => 'GB',
			'VALUE' => pow(1024, 3)
		),
		2 => array(
			'UNIT' => 'MB',
			'VALUE' => pow(1024, 2)
		),
		3 => array(
			'UNIT' => 'KB',
			'VALUE' => 1024
		),
		4 => array(
			'UNIT' => 'Bytes',
			'VALUE' => 1
		),
	);

	foreach ($arBytes as $arItem) {
		if ($bytes >= $arItem['VALUE']) {
			$result = $bytes / $arItem['VALUE'];
			//$result = str_replace('.', ',' , strval(round($result, 2))).' '.$arItem['UNIT'];
			$result = number_filter2($result, 2) . ' ' . $arItem['UNIT'];
			break;
		}
	}
	return $result;
}

function thainum($num)
{
	return str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), array('o', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'), $num);
};

function get_client_ip()
{
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}


function file_get_contents_curl($url, $data)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,1);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function html2text($h)
{
	$h = strip_tags($h);
	$h = str_replace("\n\n", "\n", $h);
	$h = str_replace("\n", '<br>', $h);
	$h = str_replace(" ", '&nbsp;', $h);
	return $h;
}

function checkLossList($h)
{
	include('inc/connect.php');
	$sql = "SELECT COUNT(*) AS num FROM loss_data_doc_list WHERE loss_data_doc_id='" . $h . "'";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return $row['num'];
}

function checkLossLevel($parameters)
{
    if ($parameters == 11) {
		return 1;
	} else if ($parameters == 12) {
		return 1;
	} else if ($parameters == 13) {
		return 2;
	} else if ($parameters == 14) {
		return 2;
	} else if ($parameters == 15) {
		return 2;
	} else if ($parameters == 21) {
		return 1;
	} else if ($parameters == 22) {
		return 1;
	} else if ($parameters == 23) {
		return 2;
	} else if ($parameters == 24) {
		return 2;
	} else if ($parameters == 25) {
		return 3;
	} else if ($parameters == 31) {
		return 2;
	} else if ($parameters == 32) {
		return 2;
	} else if ($parameters == 33) {
		return 3;
	} else if ($parameters == 34) {
		return 3;
	} else if ($parameters == 35) {
		return 3;
	} else if ($parameters == 41) {
		return 3;
	} else if ($parameters == 42) {
		return 3;
	} else if ($parameters == 43) {
		return 3;
	} else if ($parameters == 44) {
		return 4;
	} else if ($parameters == 45) {
		return 4;
	} else if ($parameters == 51) {
		return 3;
	} else if ($parameters == 52) {
		return 3;
	} else if ($parameters == 53) {
		return 3;
	} else if ($parameters == 54) {
		return 4;
	} else if ($parameters == 55) {
		return 4;
	} else {
		return 0;
	}
}

?>