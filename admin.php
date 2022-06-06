<?
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');

	include_once("inc/connect.php");
	include_once("inc/function.inc.php");
	header('Content-Type: text/html; charset=utf-8');

	$submit = $_POST['submit'];
	$action=$_GET['action'];
	$ref=$_GET['ref'];
	$fwd = $_POST['fwd'];	
	if ($action=='logout') {
		$s = $_GET['s'];
		set_logout();
		$user_id = '';
		$dep_code = '';
		$hash = '';	
	}
	
	$ie_error = false;
	if (preg_match('/MSIE\s(?P<v>\d+)/i', @$_SERVER['HTTP_USER_AGENT'], $B) && $B['v'] <= 8) {
		$ie_error = true;
	}

	if ($_SERVER['HTTP_HOST']=='localhost') $authen_mode = 0;  
	$authen_mode = 0;  // 0=db, 1=ad


	if ($submit=='login') {
		
		if (true) { // captcha	
			$login = trim(substr(form_input_filter($_POST['login']), 0, 20));
			$password = trim(substr(form_input_filter($_POST['password']), 0, 20));
			
			if ($login!='' && $password!=''){
				if (true) { // XX12345 - preg_match ("/^[a-zA-Z]{2}[0-9]+$/",$login)
					
					if ($authen_mode==0) {
						
						$sql="SELECT * FROM user 
						LEFT JOIN department ON user.department_id = department.department_id 
						WHERE 
						user.userName='$login' AND 
						user.mark_del=0
						LIMIT 1 ";	

						$result=mysqli_query($connect, $sql);
						if ($row = mysqli_fetch_array($result)) {		
							$sql="SELECT * FROM user LEFT JOIN department ON user.department_id = department.department_id WHERE user.userName='$login' AND password = '$password' AND user.mark_del=0 LIMIT 1 ";						
							$result=mysqli_query($connect, $sql);
							if ($row = mysqli_fetch_array($result)) {		
								set_login($row['user_id'], $row['code'], $row['department_id'], $row['department_no'], md5($row['user_id'].'S#A*D#@$er%ewr%@6Q)#$'), $t='cookie');
								
								if ($row['department_id']==0) {
									header("Location: dashboard.php");
									exit;
								} else if ($fwd!='') {
									if (strpos($fwd, 'https://')>0) $fwd = 'https://'.$_SERVER['SERVER_NAME'].$fwd;
									header("Location: $fwd");
									exit;
								} else {
									header("Location: dashboard.php");				
									exit;
								}
							} else {
								$msg = "เกิดข้อผิดพลาด รหัสผ่านไม่ถูกต้อง<br>";
							}
						} else {
							$msg = "เกิดข้อผิดพลาด login ไม่ถูกต้อง<br>";
						}

						
					} else if ($authen_mode==1) { /* ad */
					
						$login = strtoupper(trim($login));
						$info = array();
						$x = ad_authen($login, $password, $info);
						$code = $info['code'];
						
						if ($x) {
							$pass = false;					
							
							if ($code=='' || $code==0) {
								$msg = "เกิดข้อผิดพลาดภายใน ไม่พบรหัสพนักงานจาก AD<br>";
							} else {
								$sql="SELECT COUNT(*) AS num FROM user WHERE user.code='$code' AND user.mark_del=0 ";
								$result=mysqli_query($connect, $sql);
								$row = mysqli_fetch_array($result);
								if ($row['num']==0) {
									mysqli_autocommit($connect,FALSE);		
									$qx = true;						
									
									//$dep_no = 2301;
									$dep_id = 0;
									$sql="SELECT * FROM department WHERE department_no = '$dep_no' ";						
									$result1=mysqli_query($connect, $sql);
									if ($row1 = mysqli_fetch_array($result1)) {
										$dep_id = $row1['dep_id'];
									}
									if (!is_numeric($info['code'])) $info['code'] = 0;
									//if ($dep_id==0) $dep_id = 38;
									
									$sql="INSERT INTO `user` (`code`, `userName` , `password`, `name`, `surname`, `name_en`, `surname_en`, `pid`, `tel`, `mobile`, `position`, `department_id`, `level` , `mark_del`, `create_date`) VALUES 
									('$info[code]', '$login', '', '$info[name]', '$info[surname]', '$info[name_en]', '$info[surname_en]', '$info[pid]', '$info[tel]', '', '$info[position]', '$dep_id', '$info[level]', '0', now())";
									$q = mysqli_query($connect, $sql);
									$qx = ($qx and $q);						
									if ($qx) {
										mysqli_commit($connect);	
										$pass = true;
									} else {
										mysqli_rollback($connect); 
										$msg = "เกิดข้อผิดพลาดภายใน (db1)<br>";
									}
								} else if ($row['num']>0) {
									$pass = true;
								}
							}
						
							if ($pass) {
								$sql="SELECT * FROM user LEFT JOIN department ON user.department_id = department.department_id WHERE user.userName='$login' AND user.mark_del=0 LIMIT 1 ";						
								$result=mysqli_query($connect, $sql);
								if ($row = mysqli_fetch_array($result)) {						
									set_login($row['user_id'], $row['code'], $row['department_id'], $row['department_no'], md5($row['user_id'].'S#A*D#@$er%ewr%@6Q)#$'), $t='cookie');
									
									if ($row['department_id']==0) {
										header("Location: profile.php");
										exit;
									} else if ($fwd!='') {
										if (strpos($fwd, 'https://')>0) $fwd = 'https://'.$_SERVER['SERVER_NAME'].$fwd;
										header("Location: $fwd");
										exit;
									} else {
										header("Location: dashboard.php");
										exit(0);
									}
								} else {
									$msg = "เกิดข้อผิดพลาดภายใน (db2)<br>";
								}
							}
						} else {
							$msg = "login หรือ password ไม่ถูกต้อง (AD)<br>";
						}
					}
				} else {
					$msg = "login ไม่ถูกต้อง (ผิดรูปแบบ)<br>";
				}
			} else {
				$msg = "กรุณาใส่ login และ password<br>";
			}
		} else {
			$msg = "กรุณากดที่ฉันไม่ใช่โปรแกรมอัตโนัติ (i'm not robot)<br>";
		}
		
	} else if ($submit=='forgot_submit') {
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>ระบบบริหารความเสี่ยง | บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</title>
	<!-- base:css -->
	<link rel="stylesheet" href="inc/theme/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="inc/theme/vendors/css/vendor.bundle.base.css">
	<link rel="stylesheet" href="inc/theme/css/style.css">
	<link rel="shortcut icon" href="inc/theme/images/logo.png" />
	<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <style>
@import url('https://fonts.googleapis.com/css2?family=Prompt:wght@200;300&display=swap');

body{
	font-family: 'Prompt', sans-serif;
}
</style>
</head>

<body>
  <div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="inc/theme/images/logo.png" width='50px' alt="logo">
              </div>
              <h4>ระบบบริหารความเสี่ยง </h4>
              <h6 class="font-weight-light">Risk management system</h6>
              <form class="login-form" action="admin.php" method="post" novalidate="novalidate"  onSubmit='//return cf()'>
              <input type='hidden' name='fwd' value='<?=$ref?>'>
              <?if ($msg!=''){?>				
              <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span><?=$msg?></span>
              </div>
          <?}?>	
				<br>
                <div class="mt-4 "> 
					<h4><i class='fa fa-check-circle-o'></i> ระบบได้ส่งรหัสผ่านไปยัง Email ของท่านแล้ว</h4>
					<br>
					<br>
                  <a href='admin.php'>กลับหน้า Login</a>
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-none d-lg-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">2021 สงวนสิทธิ์ บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="inc/theme/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="inc/theme/js/off-canvas.js"></script>
  <script src="inc/theme/js/hoverable-collapse.js"></script>
  <script src="inc/theme/js/template.js"></script>
  <!-- endinject -->
</body>
</html>


<?			
		
	} else if ($action=='forgotpassword') {
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ระบบบริหารความเสี่ยง | บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</title>
  <!-- base:css -->
  <link rel="stylesheet" href="inc/theme/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="inc/theme/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="inc/theme/css/style.css">
  <link rel="shortcut icon" href="inc/theme/images/logo.png" />
  <style>
@import url('https://fonts.googleapis.com/css2?family=Prompt:wght@200;300&display=swap');

body{
	font-family: 'Prompt', sans-serif;
}
</style>
</head>

<body>
  <div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="images/bam_logo.png" style='width:360px' alt="logo">
              </div>
              <h4 style='font-size: 28px; font-weight: bold'>ระบบบริหารความเสี่ยง </h4>
              <h6 class="font-weight-light" style='font-size: 20px'>Risk management system</h6>
              <form class="login-form" action="admin.php" method="post" novalidate="novalidate"  onSubmit='//return cf()'>
              <input type='hidden' name='fwd' value='<?=$ref?>'>
              <?if ($msg!=''){?>				
              <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span><?=$msg?></span>
              </div>
          <?}?>	
				<br>
                <div class="form-group">
                  <label for="exampleInputEmail" style='font-size: 16px;'>ชื่อเข้าสู่ระบบ Username</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input  class="form-control form-control-lg border-left-0" type="text" autocomplete="off" placeholder="ชื่อเข้าสู่ระบบ" name="login" required="" aria-required="true" data-msg-required="โปรดระบุ login"/> 
                 
                  </div>
                </div>
                <div class="mb-2 d-flex">
						<button type="submit" name="submit" value='forgot_submit'class="btn btn-facebook auth-form-btn flex-grow mr-1"><i class='fa fa-sign-in'></i> &nbsp; ขอรหัสผ่านใหม่ </button>
                </div>
				
					<br>
                <div class="mt-4 "> 
                  <a href='admin.php'>ยกเลิก</a>
                   <? if ($ie_error) { ?>		
			<div class='alert alert-danger'>เกิดข้อผิดพลาด เนื่องจากท่านที่ใช้ Internet Explorer รุ่นเก่า โปรดแจ้งฝ่ายวิศวกรรมฯ ให้ช่วย update version หรือเปลี่ยนไปใช้ Chrome/Firefox แทน</div>
<? } ?>	
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-none d-lg-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">2021 สงวนสิทธิ์ บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="inc/theme/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="inc/theme/js/off-canvas.js"></script>
  <script src="inc/theme/js/hoverable-collapse.js"></script>
  <script src="inc/theme/js/template.js"></script>
  <!-- endinject -->
</body>
</html>


<?	
		exit;
	} 
	
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ระบบบริหารความเสี่ยง | บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</title>
  <!-- base:css -->
  <link rel="stylesheet" href="inc/theme/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="inc/theme/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="inc/theme/css/style.css">
  <link rel="shortcut icon" href="inc/theme/images/logo.png" />
  <style>
@import url('https://fonts.googleapis.com/css2?family=Prompt:wght@200;300&display=swap');

body{
	font-family: 'Prompt', sans-serif;
}
</style>
</head>

<body>
  <div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="images/bam_logo.png" style='width:360px' alt="logo">
              </div>
              <h4 style='font-size: 28px; font-weight: bold'>ระบบบริหารความเสี่ยง </h4>
              <h6 class="font-weight-light" style='font-size: 20px'>Risk management system</h6>
              <form class="login-form" action="admin.php" method="post" novalidate="novalidate"  onSubmit='//return cf()'>
              <input type='hidden' name='fwd' value='<?=$ref?>'>
              <?if ($msg!=''){?>				
              <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span><?=$msg?></span>
              </div>
          <?}?>	
					<br>
                <div class="form-group">
                  <label for="exampleInputEmail" style='font-size: 16px;'>ชื่อเข้าสู่ระบบ Login name</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input  class="form-control form-control-lg border-left-0" type="text" autocomplete="off" placeholder="ชื่อเข้าสู่ระบบ" name="login" required="" aria-required="true" data-msg-required="โปรดระบุ login"/> 
                 
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword" style='font-size: 16px;'>รหัสผ่าน Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input class="form-control form-control-lg border-left-0" type="password" autocomplete="off" placeholder="Password" name="password" required="" aria-required="true" data-msg-required="โปรดระบุ Password"/> 
                                      
                  </div>
                </div>
                <div class="mb-2 d-flex">
                <button type="submit" name="submit" value='login'class="btn btn-facebook auth-form-btn flex-grow mr-1"><i class='fa fa-sign-in'></i> &nbsp; Login </button>
                 
                 
                </div>
					<br>
                <div class="mt-4 "> 
                  <a href='admin.php?action=forgotpassword'>ลืมรหัสผ่าน</a>
                   <? if ($ie_error) { ?>		
			<div class='alert alert-danger'>เกิดข้อผิดพลาด เนื่องจากท่านที่ใช้ Internet Explorer รุ่นเก่า โปรดแจ้งฝ่ายวิศวกรรมฯ ให้ช่วย update version หรือเปลี่ยนไปใช้ Chrome/Firefox แทน</div>
<? } ?>	
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-none d-lg-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">2021 สงวนสิทธิ์ บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="inc/theme/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="inc/theme/js/off-canvas.js"></script>
  <script src="inc/theme/js/hoverable-collapse.js"></script>
  <script src="inc/theme/js/template.js"></script>
  <!-- endinject -->
</body>
</html>
