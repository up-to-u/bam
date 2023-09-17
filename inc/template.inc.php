<?php

function template_header() {
	global $user_id, $dep_id, $company_id, $login, $password, $ui_mode, $img, $connect, $user_name;

	if ($user_id==0 || $user_id==0) {
		$script_name = urlencode($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']);
		echo '<script language="JavaScript">document.location="admin.php?action=logout&ref='.$script_name.'";</script>';
		exit;
	}
	if ($dep_id=='' || $dep_id == 0) {
		$current_filename = strrchr($_SERVER['PHP_SELF'], '/');
		$cf = substr($current_filename, 1);
		if ($cf!='profile.php') {
			echo '<script language="JavaScript">document.location="profile.php";</script>';
			exit;
		}
	}

	$max_p = 21;
	$p = array();
	$sql2="SELECT *	FROM `system_permission` WHERE user_id = '$user_id' OR department_id = '$dep_id' ";		
	$result2=mysqli_query($connect, $sql2);
	while ($row2 = mysqli_fetch_array($result2)) {
		for ($j=1; $j<=$max_p; $j++) {		
			if ($row2['p_'.$j]==1) {
				$p[$j] = 1;
			}
		}		
	}
	
	$sql="SELECT * FROM `user` WHERE `user_id` = '$user_id' ";
	$result=mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($result)) {
		$img = !isset($row['img']) ? 'images/no_photo.png' : $row['img'];
		$user_name = $row['name'].' '.$row['surname'];
		$user_email = $row['email'];
	}

	template_header2($p);
}

function template_footer() {
	global $ui_mode;
	template_footer2();
}


function template_header2($permission) {
	global $img, $dep_id, $user_id, $user_name;
	global $connect;
	$hash_cookie = $_COOKIE['hash'];
	$hash_new = md5($user_id.'S#A*D#@$er%ewr%@6Q)#$');
	if ($hash_cookie!=$hash_new) {
		header('Location: admin.php?action=logout');
	}
	
	header('Content-Type: text/html; charset=utf-8');
	$img = 'images/no_photo.png';
	
	$enable_timeout = false;
	$is_password_expire = get_password_expire();

	$current_filename = strrchr($_SERVER['PHP_SELF'], '/');
	$cf = substr($current_filename, 1);
	
	if ($is_password_expire) {
		if ($cf!='profile.php') {
			header("Location: profile.php?action=changepassword");
			exit;
		}		
	}
	
?>
<html lang="en">
	 <head>
		<meta charset="utf-8" />
		<title>ระบบบริหารความเสี่ยง | บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="BAM" name="description" />
		<meta content="" name="author" />
		<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />-->
		<link rel="shortcut icon" href="inc/theme/images/logo.png" />
		<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/css/components-rounded.css" rel="stylesheet" id="style_components" type="text/css" />
		<link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/layouts/layout/css/layout.css" rel="stylesheet" type="text/css" />
		<link href="assets/layouts/layout/css/themes/light.css" rel="stylesheet" type="text/css" id="style_color" />
		<link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
		<link href="inc/bam.css" rel="stylesheet" type="text/css" />
		<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<style>
/* @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@200;300&display=swap'); */

/* thai */
@font-face {
  font-family: 'Prompt';
  font-style: normal;
  font-weight: 200;
  font-display: swap;
  src: url(font/Prompt200_thai.woff2) format('woff2');
  unicode-range: U+0E01-0E5B, U+200C-200D, U+25CC;
}
/* thai */
@font-face {
  font-family: 'Prompt';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(font/Prompt300_thai.woff2) format('woff2');
  unicode-range: U+0E01-0E5B, U+200C-200D, U+25CC;
}
/* latin-ext */
@font-face {
  font-family: 'Prompt';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(font/Prompt300_latin_ext.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Prompt';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(font/Prompt300_latin.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
body{
	font-family: 'Prompt', sans-serif;
}
</style>

<? if ($enable_timeout) {?>
<script>    
var idleMax = 15;
var idleTime = 0;
(function ($) {
    $(document).ready(function () {
        $('*').bind('mousemove keydown scroll', function () {
            idleTime = 0; 
            var idleInterval = setInterval("timerIncrement()", 60000); 
       });
        $("body").trigger("mousemove");
    });
}) (jQuery)
function timerIncrement() {
     idleTime = idleTime + 1;
     if (idleTime > idleMax) { 
         window.location="index.php?action=logout";
     }
 }
</script>
	</head>

<?	
}

$is_sidebar_open = true;
if (!$is_sidebar_open) {
	$c1 = 'page-sidebar-closed';
	$c2 = 'page-sidebar-menu-closed';
} else {
	$c1 = '';
	$c2 = '';
}

?>	
	<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white <?=$c1?>">
		<div class="page-wrapper">
			<div class="page-header navbar navbar-fixed-top">
				<div class="page-header-inner ">
					<div class="page-logo">
						<a href="index.php"><img src="images/logo.png" alt="logo" height='40' class="logo-default" /></a>
							<div class="sidebar-toggler" style='float: right; cursor: pointer; padding-top: 10px'> <!--menu-toggler -->
							<span class="svg-icon svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Navigation/Angle-double-left.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<polygon points="0 0 24 0 24 24 0 24"></polygon>
										<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)"></path>
										<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)"></path>
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>                            
                        </div>
						
					
					</div>
					<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"><span></span></a>
					<div class="top-menu">
						<ul class="nav navbar-nav pull-right">
							<li class="dropdown">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
									<span class="username username-hide-on-mobile"><i class='fa fa-download'></i> คู่มือการใช้งาน</span>
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-default">
									<li><a href="#"><i class='fa fa-file-pdf-o'></i>  คู่มือสำหรับผู้ใช้งาน </a></li>
									</li>
								</ul>
								<!--<a href='#'><i class='fa fa-download'></i> คู่มือการใช้งาน</a>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
							</li>
							<li class="dropdown dropdown-user">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
									<img alt="" class="img-circle" src="<?=$img?>" />
									<span class="username username-hide-on-mobile"><?=$user_name?></span>
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-default">
									<li><a href="profile.php"><i class="icon-user"></i> ข้อมูลส่วนตัว </a></li>
									<li><a href="index.php?action=logout"><i class="icon-power"></i> ออกจากระบบ </a>
									</li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="index.php?action=logout" class="">
									<i class="icon-power"></i>
								</a>
								</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clearfix"> </div>
			<div class="page-container">
				<div class="page-sidebar-wrapper ">
					<div class="page-sidebar navbar-collapse collapse">
						<ul class="page-sidebar-menu  page-header-fixed  <?=$c2?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
							<li class="sidebar-toggler-wrapper hide">
								<div class="sidebar-toggler">
									<span></span>
								</div>
							</li>
<?
	
	$item = array();
	$item[] = array(
		'name' => 'หน้าหลัก',
		'icon' => 'icon-home',
		'type' => 1,
		'url' => 'dashboard.php'
	);	
	

	$item[] = array(
		'name' => 'เหตุการณ์ความเสียหาย',
		'icon' => 'fa fa-bolt',
		'list' => array(
			'loss_data.php' => 'แจ้งเหตุการณ์ความเสียหาย',
			'loss_data_approve.php' => 'อนุมัติเหตุการณ์ความเสียหาย'			
		)	
	);
	
	
	$item[] = array(
		'name' => 'ประเมินความเสี่ยง CSA',
		'icon' => 'icon-layers',
		'list' => array(
			'csa_dashboard.php' => 'หน้าหลัก CSA', 
			'csa_user.php' => 'ประเมิน CSA', 
			'csa_approve.php' => 'อนุมัติรายการ CSA'			
		)	
	);		
	generate_menu($item, $cf);	

	$k = array_keys($permission);
	array_multisort($k);	
	foreach ($k as $kk) {
		menu_item_newUI($kk, $cf);
	}
	
	$item = array();
/*	$item[] = array(
		'name' => 'ข้อมูลส่วนตัว',
		'icon' => 'icon-note',
		'type' => 1,
		'url' => 'profile.php'
	);	
	$item[] = array(
		'name' => 'ออกจากระบบ',
		'icon' => 'icon-power',
		'type' => 1,
		'url' => 'index.php?action=logout'
	);		*/


	$item = array();
	$item[] = array(
		'name' => 'ยื่นขอสิทธิ์',
		'icon' => 'icon-lock-open',
		'type' => 1,
		'url' => 'user_request.php'
	);			
	generate_menu($item, $cf);		
?>	
						</ul>
					</div>
				</div>
				<div class="page-content-wrapper">
					<div class="page-content">
<?
	}

function template_footer2() {
?>
					</div>
				</div>
				<a href="javascript:;" class="page-quick-sidebar-toggler">
					<i class="icon-login"></i>
				</a>
               <!--<div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
					<div class="page-quick-sidebar">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> USER<span class="badge badge-danger">0</span></a>
							</li>
							<li>
								<a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> Notifications <span class="badge badge-success">0</span></a>
							</li>

						</ul>
						<div class="tab-content">
							<div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
								
							</div>
							<div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
								
							</div>
						</div>
					</div>
				</div>-->				
			</div>
			<div class="page-footer">
			</div>
		</div>
		<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
		<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
		<script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
	</body>
</html>
<?
}

function generate_menu($d, $current_filename) {
	foreach ($d as $dd) {
		$name = $dd['name'];
		$icon = $dd['icon'];
		$type = $dd['type'];	
		$active = false;
		if ($type==1) {
			$url = $dd['url'];	
?>
	<li class="nav-item <?if ($current_filename==$url) echo 'active open'?> ">
		<a href="<?=$url?>" class="nav-link">
			<i class="<?=$icon?>"></i>
			<span class="title"><?=$name?></span>
			<?if ($current_filename==$url) {?>
			<span class="selected"></span>			
			<?} else{?>
			<?} ?>
		</a>
	</li>
<?		} else {  
			$list = $dd['list'];
			if (array_key_exists($current_filename, $list)) $active = true;
?>
	<li class="nav-item  <?if ($active) echo 'active open'?>">
		<a href="javascript:void(0)" class="nav-link nav-toggle">
			<i class="<?=$icon?>"></i>
			<span class="title"> <?=$name?></span>
			<i class="icon-arrow"></i>
			<span class="arrow <?if ($active) echo 'open'?>"></span>
			<?if ($active) echo '<span class="selected"></span>'?>
		</a>
		<ul class="sub-menu">
<?			foreach ($list as $k => $v) { ?>
			<li <?if ($current_filename==$k) echo 'class="active open"'?>>
				<a href="<?=$k?>" class="nav-link ">
					<span class="title"> <?=$v?> </span>
					<?if ($current_filename==$k) echo '<span class="selected"></span>'?>
				</a>
			</li>
<?			}?>			
		</ul>
	</li>
<?php	
		}
	}
}

function menu_item_newUI($p, $current_filename) {
	global $user_id, $dep_id, $company_id, $menu_count, $connect;

	if ($p==2) { 
	
		$item = array();
		$item[] = array(
				'name' => 'จัดการ Lossdata',
				'icon' => 'icon-user-following',
				'list' => array(
					'loss_data_risk.php' => 'ตรวจสอบรายการเหตุการณ์',
					'loss_data_admin.php' => 'กำหนดสิทธิการเข้ารายงาน',
					'loss_data_adminmanage.php' => 'สรุปรายงานประจำเดือน',
					'loss_data_adminmanage_y.php' => 'สรุปรายงานประจำเดือนรายปี',
					'loss_data_chart.php' => 'ทะเบียนสรุปรายงานประจำปี',
					'loss_admin_master.php' => 'จัดการชุดข้อมูล'
			)
		);
		generate_menu($item,$current_filename);  
		
	} 
	else if ($p==3) { 
		
		$item = array();
		$item[] = array(
				'name' => 'จัดการ CSA',
				'icon' => 'icon-user-following',
				'list' => array(
					'csa_admin.php' => 'CSA Admin',
					'csa_admin_report.php' => 'CSA Report',
					'csa_permission.php' => 'กำหนดสิทธิ์',					
					'csa_admin_q.php' => 'แบบสอบถาม',						
					'csa_admin_factor.php' => 'ปัจจัยเสี่ยง',
					'csa_admin_control.php' => 'การควบคุม',
					'csa_admin_risk_type.php' => 'ประเภทความเสี่ยง',					
					'job_function.php' => 'Job Function',
					'csa_audit.php' => 'ประเด็นที่ตรวจพบ',
					'csa_admin_risk_matrix.php' => 'Risk Matrix'	
			)
		);
		generate_menu($item,$current_filename);  
		
	}
	else if ($p==1) { 
		$item = array();

		$item[] = array(
			'name' => 'การตั้งค่าระบบ',
			'icon' => 'icon-settings',
			'list' => array(
				'user.php' => 'ผู้ใช้งาน',
				'department.php' => 'ฝ่ายงาน',				
				'department_level.php' => 'ระดับฝ่ายงาน',				
				'user_request_admin.php' => 'คำร้องยื่นขอสิทธิ'
				)
		);		 
		generate_menu($item,$current_filename);
	}
	
	
	$menu_count++;
}
?>