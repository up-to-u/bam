<?
	include('inc/include.inc.php');
	include('csa_function.php');

/* prod */
	$email_from = 'rms@bam.co.th';
	$to = array('thanida.k@bam.co.th');

/* test */

	$email_from = 'moooping@gmail.com';
	$to = array('moooping@gmail.com');
	
	$cc = array();						
	$bcc = array();		
	$subject = 'ทดสอบ Email จากระบบ RMS';
	$body = ' ทดสอบ Email <BR>
<br>
ด้านล่างนี้ คือข้อความสดทอบ <br>
<br>
<font color="red">Test ตัวอักษรสีแดง</font><br>
<a href="https://www.bam.co.th" target="_new">Test Link</a><br>
';

	$x = @mail_service($email_from, $to, $cc, $bcc, $subject, $body, $attach_name, $attach_location);		
	if ($x) {
		echo "<font color='#00aa00'><b>ระบบได้ส่งเมลแล้ว</b></font><br>";
	} else {
		echo "<font color='#aa0000'><b>เกิดข้อผิดพลาด ระบบไม่สามารถเมลได้</b></font><br>";
	}	
	
?>