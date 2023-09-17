<?
include('inc/include.inc.php');
include('csa_function.php');
echo template_header();

	$view_year = intval($_GET['view_year']);
	if ($view_year==0) {
		$view_year=date('Y')+543;
	}

	$sql = "SELECT 
			COUNT(*) AS num_total,
			SUM(IF(is_confirm=1, 1, 0)) AS num_confirm
		FROM csa_department c
		LEFT JOIN department d1 ON c.department_id = d1.department_id
		LEFT JOIN department d2 ON c.department_id2 = d2.department_id
		LEFT JOIN department d3 ON c.department_id3 = d3.department_id
		LEFT JOIN csa_authorize ca ON c.csa_department_id = ca.csa_department_id
		LEFT JOIN csa_department_status st ON st.csa_department_status_id = c.csa_department_status_id
		WHERE 
			c.is_enable='1' AND
			c.csa_year = ? AND 
			c.mark_del = '0' AND 
			ca.csa_authorize_uid = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('is', $view_year, $user_code);
		$stmt->execute();
		$result1 = $stmt->get_result();
		$row1 = mysqli_fetch_assoc($result1);
		
		$num_total = intval($row1['num_total']);
		$num_confirm = intval($row1['num_confirm']);
		$num_notconfirm = $num_total-$num_confirm;
		if ($num_notconfirm<0) $num_notconfirm=0;
	}	
	
	$sql = "SELECT 
		COUNT(*) AS num_total,
		SUM(IF(q_finish=1, 1, 0)) AS num_q_finish,
		SUM(IF(csa_department_status_id>1, 1, 0)) AS num_approve
	FROM csa_department c
	LEFT JOIN department d1 ON c.department_id = d1.department_id
	LEFT JOIN department d2 ON c.department_id2 = d2.department_id
	LEFT JOIN department d3 ON c.department_id3 = d3.department_id
	LEFT JOIN csa_authorize_approver ca ON c.csa_department_id = ca.csa_department_id
	WHERE 
		c.is_enable='1' AND
		c.csa_year = ? AND 
		c.mark_del = '0' AND 
		ca.csa_authorize_uid = ? ";
	$stmt = $connect->prepare($sql);
	if ($stmt) {					
		$stmt->bind_param('is', $view_year, $user_code);
		$stmt->execute();
		$result1 = $stmt->get_result();
		$row1 = mysqli_fetch_assoc($result1);
		
		$num_approve_total = intval($row1['num_total']);
		$num_approve_qfinish = intval($row1['num_q_finish']);
		$num_approve_app = intval($row1['num_approve']);
		
		$num_approve_notapp = $num_approve_total-$num_approve_app;
		if ($num_approve_notapp<0) $num_approve_notapp=0;
	}	
?>
<div class="row">
	<div class="col-md-8">
		<table>
			<tr>
				<td>แสดงข้อมูล ของปี</td><td width='15'></td>
				<td>
					<select name='view_year' class="form-control" onChange='document.location="csa_dashboard.php?view_year="+this.value'>
						<option value='<?=$view_year-2?>'><?=$view_year-2?></option>
						<option value='<?=$view_year-1?>'><?=$view_year-1?></option>
						<option value='<?=$view_year?>' selected><?=$view_year?></option>
						<option value='<?=$view_year+1?>'><?=$view_year+1?></option>
						<option value='<?=$view_year+2?>'><?=$view_year+2?></option>
					<select>
				</td>
			</tr>
		</table>
	</div>
</div>
<br>



<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">งานจัดทำประเมินความเสี่ยงของคุณ</span>
					<span class="caption-helper"></span>
				</div>
			</div>

				<div class="row">

				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a class="dashboard-stat dashboard-stat-v2 red" href="csa_user.php">
						<div class="visual">
							<i class="fa fa-hourglass-3"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?=$num_notconfirm?>"><?=$num_notconfirm?></span>
							</div>
							<div class="desc">อยู่ระหว่างทำประเมิน</div>
						</div>
					</a>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a class="dashboard-stat dashboard-stat-v2 green" href="csa_user.php">
						<div class="visual">
							<i class="fa fa-check-square-o"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?=$num_confirm?>"><?=$num_confirm?></span>
							</div>
							<div class="desc"> ทำประเมินเรียบร้อยแล้ว</div>
						</div>
					</a>
				</div>
				</div>
		</div>
	</div>
</div>

				<br>

<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">งานอนุมัติรายการประเมินความเสี่ยงของคุณ</span>
					<span class="caption-helper"></span>
				</div>
			</div>
				<div class="row">
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a class="dashboard-stat dashboard-stat-v2 red" href="csa_approve.php">
						<div class="visual">
							<i class="fa fa-download"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?=$num_approve_notapp?>"><?=$num_approve_notapp?></span></div>
							<div class="desc">การประเมินที่รออนุมัติ</div>
						</div>
					</a>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a class="dashboard-stat dashboard-stat-v2 green" href="csa_approve.php">
						<div class="visual">
							<i class="fa fa-check-square-o"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="<?=$num_approve_app?>"><?=$num_approve_app?></span>
							</div>
							<div class="desc"> การประเมินที่อนุมัติแล้ว</div>
						</div>
					</a>
				</div>
				</div>
		</div>
	</div>
</div>
			
<?



echo template_footer();
?>