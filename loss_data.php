<?
include('inc/include.inc.php');
echo template_header();

				$sql="SELECT department_name, group_name,department_level_no FROM user join department_level on department_level.department_level_id = user.level WHERE user_id=?";
				$stmt = $connect->prepare($sql);
				$stmt->bind_param("s",$user_id);
				$stmt->execute();
				$res1 = $stmt->get_result();
				if ($row_mem = $res1->fetch_assoc()) {
					$deptName = $row_mem['department_name'];
					$groupName = $row_mem['group_name'];
					$levelName = $row_mem['department_level_no'];
				}

?>
<style>
	.margins-3 {
		margin-top: 15px;
		margin-bottom: 15px;
	}

	.margins-top-10 {
		margin-top: 10px;
	}
	
</style>


<div class="row">
	<div class="col-lg-12 col-lg-12 col-sm-12">
		<div class="portlet light tasks-widget bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-share font-dark hide"></i>
					<span class="caption-subject font-green sbold uppercase">แจ้งเหตุการณ์ความเสียหาย</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<!---------------->

			<div class="row" style="margin-top: 20px;margin-bottom: 20px;">
			<div class="col-lg-3"> 
			
			</div>
			<div class="col-lg-3"> 
			<b>สายงาน :</b> <?= $groupName; ?>
			</div>
			<div class="col-lg-3"> 
			<b>ฝ่าย :</b> <?= $deptName; ?>
			</div>
			<div class="col-lg-3"> 
			<b>กลุ่มงาน :</b>  <?= $levelName; ?>
			</div>
</div>
			<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"> <div class="dashboard-stat dashboard-stat-v2 green" >
					<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
					<div class="details">
						<div class="number"> <span data-counter="counterup">รอการอนุมัติ 
						<?php 	$i1=1;
								$sqlCount1="SELECT COUNT(*) AS num FROM loss_data_doc_list WHERE status_approve =?";
								$stmt = $connect->prepare($sqlCount1);
								$stmt->bind_param("i",$i1);
								$stmt->execute();
								$res = $stmt->get_result();
								if ($rows = $res->fetch_assoc()) {
									echo $rows['num'];
								}
								?>	
						คำขอ</span> </div>
						<div class="desc" style="margin-top: 13px;"><a href="loss_data_list.php?statusListId=1"><< เรียกดูขอมูลเพิ่มเติม >></a></div>
					</div>
				</div> 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"> <div class="dashboard-stat dashboard-stat-v2 green" >
					<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
					<div class="details">
						<div class="number"> <span data-counter="counterup">ส่งกลับแก้ไข 	<?php 	$i2=2;
								$sqlCount2="SELECT COUNT(*) AS num FROM loss_data_doc_list WHERE status_approve =?";
								$stmt = $connect->prepare($sqlCount2);
								$stmt->bind_param("i",$i2);
								$stmt->execute();
								$res = $stmt->get_result();
								if ($rows = $res->fetch_assoc()) {
									echo $rows['num'];
								}
								?>
							 คำขอ</span> </div>
						<div class="desc" style="margin-top: 13px;"><a href="loss_data_list.php?statusListId=2"><< เรียกดูขอมูลเพิ่มเติม >></a></div>
					</div>
				</div> 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"> <div class="dashboard-stat dashboard-stat-v2 green">
					<div class="visual"> <i class="fa fa-shopping-cart"></i> </div>
					<div class="details">
						<div class="number"> <span data-counter="counterup">ดำเนินการแล้วเสร็จ  
						<?php 	$i3=3;
								$sqlCount3="SELECT COUNT(*) AS num FROM loss_data_doc_list WHERE status_approve =?";
								$stmt = $connect->prepare($sqlCount3);
								$stmt->bind_param("i",$i3);
								$stmt->execute();
								$res = $stmt->get_result();
								if ($rows = $res->fetch_assoc()) {
									echo $rows['num'];
								}
								?>
								 คำขอ</span> </div>
						<div class="desc" style="margin-top: 13px;"><a href="loss_data_list.php?statusListId=3"><< เรียกดูขอมูลเพิ่มเติม >></a></div>
					</div>
				</div> 
			</div>
			</div>
			<!---------------->
			<form method='post' action='profile.php' enctype="multipart/form-data">
				<div class="form-group" style="margin-top: 21px;">
					<div class="row">

						<div class="col-lg-12 col-xs-12">
							<div class='well'>

								<div class='row'>

									<div class="col-lg-3 margins-3">
										<div class="form-group">
											<label for="username" class="col-xs-12 col-lg-8 control-label " style="margin-top: 8px;">รายงานความเสียหายประจำเดือน : </label>
											<div class="col-xs-12 col-lg-4">
												<select name='department_level_id' class="form-control" style="display: inline;">
													<option value='0'> - - - </option>
													<option value="1"> มกราคม</option>
													<option value="2"> กุมภาพันธ์</option>
													<option value="3"> มีนาคม</option>
													<option value="4"> เมษายน</option>
													<option value="5"> พฤษภาคม</option>
													<option value="6"> มิถุนายน</option>
													<option value="7"> กรกฎาคม</option>
													<option value="8"> สิงหาคม</option>
													<option value="9"> กันยายน</option>
													<option value="10"> ตุลาคม</option>
													<option value="11"> พฤศจิกายน</option>
													<option value="12"> ธันวาคม</option>
												</select>
											</div>
										</div>

									</div>
									<div class="col-lg-2 margins-3">
										<div class="form-group">
											<label for="username" class="col-xs-12 col-lg-3 control-label " style="margin-top: 8px;">ปี : </label>
											<div class="col-xs-12 col-lg-9">
												<select name='department_level_id' class="form-control" style="margin-bottom:5px;">
													<option value='0'> - - - - - -</option>
													<?php
													$year = Date("Y") + 543;
													for ($k =  ($year + 1); $k >=  ($year - 20); $k--) {
														echo "<option value='" . $k . "'>  พ.ศ. $k </option>";
													}

													?>
												</select>
											</div>
										</div>

									</div>


									<div class="col-lg-2 margins-3" style="margin-left: 16px;">
										<label class="radio-inline" style="padding-top: 5px;"><input type="radio" name="optradio"> พบเหตุการณ์ความเสียหาย
										</label>
									</div>
									<div class="col-lg-3 margins-3" style="margin-left: 16px; margin-bottom:10px;">
										<label class="radio-inline" style="padding-top: 5px;"><input type="radio" name="optradio"> ไม่พบเหตุการณ์ความเสียหาย
										</label>
									</div>
									<div class="col-lg-1 margins-3 " align="center">
										<button type='submit' name='submit' value='update' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก
										</button>
									</div>


								</div>
							</div>
						</div>
					</div>
				</div>
			</form>

			<div class="form-group" style="margin-top: -30px;">
				<div class="row">
					<div class="col-lg-12 col-xs-12">
						<div class='well'>
							<div class='row' style="margin-left: 3px;margin-right: 3px;">
								<!-- start table -->
								<table id="exampleDataTable" class="table table-striped table-bordered" style="width:100%">
									<thead>
										<tr>
											<th>ช่วงเหตุการณ์</th>
											<th>ผลการรายงาน</th>
											<th align="center" width="90">วันที่บันทึก</th>
											<th>จัดการ</th>
										</tr>
									</thead>
									<tbody>

									<?php 
$sql="SELECT * FROM loss_data_doc order by loss_create DESC";
$stmt = $connect->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = mysqli_fetch_array($result)) {
										?>
											<tr >
												<td style="vertical-align: middle;"><?= ' ' . month_name($row['loss_data_doc_month']) . ' พ.ศ. ' . $row['loss_year']; ?></td>

												<td style="vertical-align: middle;"><?php $row['loss_have'];
													if ($row['loss_have'] == '0') { ?>
														<span class="glyphicon glyphicon-exclamation-sign" style="color: #E31D2D;"></span><span> พบเหตุการณ์ความเสียหาย</span>
													<?php } else if ($row['loss_have'] == '1') {  ?>
														<span class="glyphicon glyphicon-ok-sign" style="color: #004C85;"></span><span> ไม่พบเหตุการณ์ความเสียหาย</span>
													<?php }  ?>
												</td>
												<td style="vertical-align: middle;"><?= $row['loss_create']; ?></td>
										
												<td style="vertical-align: middle;" width="300">
 <button type='submit' name='submit' value='update' class="btn btn-primary" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-share'></i> รายงานเหตุการณ์</button>


 <?php if (checkLossList($row['loss_data_doc_id']) > 0 ){ ?>

 
	<form action="loss_data_list.php" methed="post" style="display: inline;">
 <input type="hidden" value="<?= $row['loss_data_doc_id']; ?>" name="listId" id="listId">
 <input type="hidden" value="<?= $row['loss_data_doc_month']; ?>" name="m" id="m">
 <input type="hidden" value="<?= $row['loss_year']; ?>" name="y" id="y">
 <button type='submit'  class="btn btn-success" ><i class="glyphicon glyphicon-list-alt"></i> รายการเพิ่มเติม</button>
 </form>
 <?php }else{  ?>
	<button disabled type='submit'  class="btn btn-dark" style="background-color: #949596; color:#FFFFFF;" ><i class="glyphicon glyphicon-list-alt" ></i> รายการเพิ่มเติม</button>
<?php } ?>

</td>
												
											</tr>

										<?php } ?>

									</tbody>

								</table>

								<!-- end table -->

							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- start modal -->
<form method='post' action='profile.php' enctype="multipart/form-data">
	<div id="myModalSendCase" class="modal fade" role="dialog">
		<div class="modal-dialog  modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background-color:#004C85;color:#FFFFFF;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-list-alt"></span> รายงานความเสียหาย</h4>
				</div>
				<div class="modal-body" align="left">

					<form method='post' action='profile.php' enctype="multipart/form-data">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-12 col-xs-12">

									<div class="form-group">
										<div class="row">
											<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์</label><input type="text" class="form-control" name='prefix' value='<?= $row2['prefix'] ?>'></div>
											<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ</label><input type="text" class="form-control" name='name' value='<?= $row2['name'] ?>'></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">เหตุการณ์</label><input type="text" class="form-control" name='surname' value='<?= $row2['surname'] ?>'></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="w3review" class="form-control" name="w3review" rows="3" cols="50" style="min-height:80px;">

</textarea></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="fdf" class="form-control" name="sdd" rows="3" cols="50" style="min-height:80px;"></textarea></div>
											<div class="col-lg-6 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><input type="text" class="form-control" name='surname' value='<?= $row2['surname'] ?>'></div>
											<div class="col-lg-3"> <label class="margins-top-10">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
													<option value="0">- - - เลือก - - - </option>
													<?
													$iFactor7 = 7;
																$sqlFactor = "SELECT * FROM loss_factor Where parent_id =?";
														   $stmt = $connect->prepare($sqlFactor);
														   $stmt->bind_param("i",$iFactor7);
															$stmt->execute();
															$result = $stmt->get_result();
															while ($row1 = mysqli_fetch_array($result)) {
													?>
														<option value="<?= $row1['loss_factor_id'] ?>" ><?= $row1['factor'] ?></option>
													<?		} ?>
												</select></div>
											<div class="col-lg-3"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
											<option value="0">- - - เลือก - - - </option>
													<?
													$iFactor1 = 1;
																$sqlFactor = "SELECT * FROM loss_factor Where parent_id =?";
														   $stmt = $connect->prepare($sqlFactor);
														   $stmt->bind_param("i",$iFactor1);
															$stmt->execute();
															$result = $stmt->get_result();
															while ($row1 = mysqli_fetch_array($result)) {
													?>
														<option value="<?= $row1['loss_factor_id'] ?>" ><?= $row1['factor'] ?></option>
													<?		} ?>
												</select></div>

											<div class="col-lg-2"> <label class="margins-top-10 col-xs-12" style="margin-left: -13px;">Loss : </label><label class="radio-inline"><input type="radio" name="optradio"> Actual Loss
												</label></div>
											<div class="col-lg-2"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="optradio"> Potential Loss
												</label></div>
											<div class="col-lg-2"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="optradio"> Near-Missed
												</label></div>

											<div class="col-lg-6"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><input type="text" class="form-control" name='signature'></div>
											<div class="col-lg-4"><label class="margins-top-10">มูลค่าความเสียหาย (บาท)<span style="color: red;">*</span></label><input type="text" class="form-control" name='surname' value='<?= $row2['surname'] ?>'></div>
											<div class="col-lg-4"><label class="margins-top-10">โอกาส<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
											<option value="0">- - - เลือก - - - </option>
													<?
													$iFactor = 1;
																$sqlFactor = "SELECT * FROM loss_factor Where parent_id =?";
														
														   $z = 0;
														   $stmt = $connect->prepare($sqlFactor);
														   $stmt->bind_param("i",$iFactor);
															$stmt->execute();
															$result = $stmt->get_result();
															while ($row1 = mysqli_fetch_array($result)) {
											
													?>
														<option value="<?= $row1['loss_factor_id'] ?>" ><?= $row1['factor'] ?></option>
													<?		} ?>
												</select></div>
											<div class="col-lg-4"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
											<option value="0">- - - เลือก - - - </option>
													<?
													$iFactor = 1;
																$sqlFactor = "SELECT * FROM loss_factor Where parent_id =?";
														
														   $z = 0;
														   $stmt = $connect->prepare($sqlFactor);
														   $stmt->bind_param("i",$iFactor);
															$stmt->execute();
															$result = $stmt->get_result();
															while ($row1 = mysqli_fetch_array($result)) {
											
													?>
														<option value="<?= $row1['loss_factor_id'] ?>" ><?= $row1['factor'] ?></option>
													<?		} ?>
												</select></div>
										
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 1</label><select name='department_id' id='department_id' class="form-control">
													<option value="0"> - - - -</option>
													<?
													$sql = "SELECT * FROM department 
WHERE 
	department.mark_del = 0 
ORDER BY 
	department.is_branch, 
	department.department_name";
													$result1 = mysqli_query($connect, $sql);
													while ($row1 = mysqli_fetch_array($result1)) {
													?>
														<option value="<?= $row1['department_id'] ?>" <? if ($row1['department_id'] == $row2['department_id']) echo 'selected' ?>><?= $row1['department_name'] ?></option>
													<?		} ?>
												</select></div>
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 2</label><select name='department_id' id='department_id' class="form-control">
													<option value="0"> - - - -</option>
													<?
													$sql = "SELECT * FROM department 
WHERE 
	department.mark_del = 0 
ORDER BY 
	department.is_branch, 
	department.department_name";
													$result1 = mysqli_query($connect, $sql);
													while ($row1 = mysqli_fetch_array($result1)) {
													?>
														<option value="<?= $row1['department_id'] ?>" <? if ($row1['department_id'] == $row2['department_id']) echo 'selected' ?>><?= $row1['department_name'] ?></option>
													<?		} ?>
												</select></div>
											<div class="col-lg-4"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง 3</label><select name='department_id' id='department_id' class="form-control">
													<option value="0"> - - - -</option>
													<?
													$sql = "SELECT * FROM department 
WHERE 
	department.mark_del = 0 
ORDER BY 
	department.is_branch, 
	department.department_name";
													$result1 = mysqli_query($connect, $sql);
													while ($row1 = mysqli_fetch_array($result1)) {
													?>
														<option value="<?= $row1['department_id'] ?>" <? if ($row1['department_id'] == $row2['department_id']) echo 'selected' ?>><?= $row1['department_name'] ?></option>
													<?		} ?>
												</select></div>
										
										</div>
									</div>
									<div align="center" style="margin-top: 30px;">
										<input type='hidden' name='update_id'>
										<button type='submit' name='submit' class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
									</div>


								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
</form>


</div>

</div>

</div>
</div>
</form>

<? echo template_footer(); ?>

<script>
	$(document).ready(function() {
		var table = $('#exampleDataTable').DataTable({
			responsive: true
		});

		new $.fn.dataTable.FixedHeader(table);
	});
</script>