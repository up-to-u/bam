<style>
@import url('https://fonts.googleapis.com/css2?family=Prompt:wght@200;300&display=swap');

body{
	font-family: 'Prompt', sans-serif;
}
</style>
<?php

function template_header() {
	global $user_id, $dep_id, $company_id, $login, $password, $ui_mode, $img ,$ui_calendar ,$ul_calendar_ck, $connect;

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
	global $img, $dep_id, $user_id;
	global $connect;
/*	$hash_cookie = $_COOKIE['hash'];
	$hash_new = md5($user_id.'S#A*D#@$er%ewr%@6Q)#$');
	if ($hash_cookie!=$hash_new) {
		header('Location: worklist.php?action=logout');
	}*/
	
	header('Content-Type: text/html; charset=utf-8');
	$img = 'images/no_photo.png';
?>
<html lang="en">
	 <head>
		<meta charset="utf-8" />
		<title>ระบบบริหารความเสี่ยง | บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="SME Bank" name="description" />
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
	</head>
	<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
		<div class="page-wrapper">
			<div class="page-header navbar navbar-fixed-top">
				<div class="page-header-inner ">
					<div class="page-logo">
						<a href="index.php"><img src="images/logo.png" alt="logo" height='40' class="logo-default" /></a>
							<div class="menu-toggler sidebar-toggler"><span>&nbsp;</span></div>
					</div>
					<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"><span></span></a>
					<div class="top-menu">
						<ul class="nav navbar-nav pull-right">
							<li class="dropdown dropdown-user">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
									<img alt="" class="img-circle" src="<?=$img?>" />
									<span class="username username-hide-on-mobile"><?=$user_name?></span>
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-default">
									<li><a href="profile.php"><i class="icon-user"></i> ข้อมูลส่วนตัว </a></li>
<?	if ($dep_id>0) {?>				<li><a href="index.php"><i class="icon-calendar"></i> โต๊ะทำงาน </a></li><?}?>
									<li><a href="worklist.php?action=logout"><i class="icon-power"></i> ออกจากระบบ </a>
									</li>
								</ul>
							</li>
							<li class="dropdown dropdown-quick-sidebar-toggler">
								<a href="javascript:;" class="dropdown-toggle">
									<i class="icon-logout"></i>
								</a>
								</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clearfix"> </div>
			<div class="page-container">
				<div class="page-sidebar-wrapper">
					<div class="page-sidebar navbar-collapse collapse">
						<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
							<li class="sidebar-toggler-wrapper hide">
								<div class="sidebar-toggler">
									<span></span>
								</div>
							</li>
<?
	$current_filename = strrchr($_SERVER['PHP_SELF'], '/');
	$cf = substr($current_filename, 1);
	
	$item = array();
	$item[] = array(
		'name' => 'ข้อมูลส่วนตัว',
		'icon' => 'icon-note',
		'type' => 1,
		'url' => 'profile.php'
	);		$item[] = array(
		'name' => 'ยื่นขอสิทธิ์',
		'icon' => 'icon-lock-open',
		'type' => 1,
		'url' => 'user_request.php'
	);			$item[] = array(
		'name' => 'รายชื่อพนักงาน',
		'icon' => 'icon-users',
		'type' => 1,
		'url' => 'user_directory.php'
	);	
	$item[] = array(
			'name' => 'แจ้งเหตุการณ์ความเสียหาย ',
			'icon' => 'fa fa-bolt',
			'type' => 1,
			'url' => 'loss_data.php'
		);	
	$item[] = array(			'name' => 'ประเมินความเสี่ยง CSA',			'icon' => 'icon-layers',			'list' => array(				'csa_user.php' => 'ประเมิน CSA',				/*'csa.php' => 'CSA',*/				'csa_approve.php' => 'อนุมัติรายการ CSA'			)	);		
	generate_menu($item, $cf);	

	$k = array_keys($permission);
	array_multisort($k);	
	foreach ($k as $kk) {
		menu_item_newUI($kk, $cf);
	}
	
	$item = array();
	$item[] = array(
		'name' => 'ออกจากระบบ',
		'icon' => 'icon-power',
		'type' => 1,
		'url' => 'index.php?action=logout'
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
	<? ?>               <div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
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
					</div><? ?>					
				</div>				
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
					'loss_data_admin.php' => 'จัดการรายการเหตุการณ์',
					'loss_data_adminmanage.php' => 'ปรับสถานะรายการ',
					'loss_data_report.php' => 'ทะเบียนสรุปรายงานประจำปี',
					'loss_data_graph.php' => 'รายงานแผนภาพ'
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
					'csa_permission.php' => 'กำหนดสิทธิ์',					'csa_admin_q.php' => 'แบบสอบถาม',						'csa_admin_factor.php' => 'ปัจจัยเสี่ยง',					'job_function.php' => 'Job Function'	
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
				'department.php' => 'ฝ่ายงาน',				'department_level.php' => 'ระดับฝ่ายงาน',				'user_request_admin.php' => 'คำร้องยื่นขอสิทธิ'
				)
		);		 
		generate_menu($item,$current_filename);
	}
	
	
	$menu_count++;
}
?>