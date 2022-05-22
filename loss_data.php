<?
include('inc/include.inc.php');
echo template_header();

?>
<style>
	.margins-3 {
		margin-top: 15px;
		margin-bottom: 15px;
	}	.margins-top-10 {
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
										<label class="radio-inline" style="padding-top: 5px;"><input type="radio" name="optradio">  พบเหตุการณ์ความเสียหาย
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
								<table id="example" class="table table-striped table-bordered" style="width:100%">
									<thead>
										<tr>
											<th>ช่วงเหตุการณ์</th>

											<th>ผลการรายงาน</th>
											<th align="center" width="90">วันที่บันทึก</th>
											<th>สถานะ</th>
											<th>จัดการ</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT * FROM loss_data_doc";
										$result = mysqli_query($connect, $sql);
										while ($row = mysqli_fetch_array($result)) {


										?>
											<tr>
												<td><?= ' ' . month_name($row['loss_data_doc_month']) . ' พ.ศ. ' . $row['loss_year']; ?></td>

												<td><?php $row['loss_have'];
													if ($row['loss_have'] == '0') { ?>
														 <span class="glyphicon glyphicon-exclamation-sign" style="color: #E31D2D;"></span><span> พบเหตุการณ์ความเสียหาย</span>
													<?php } else if ($row['loss_have'] == '1') {  ?>
														<span class="glyphicon glyphicon-ok-sign" style="color: #004C85;"></span><span> ไม่พบเหตุการณ์ความเสียหาย</span>
													<?php }  ?>
												</td>
												<td><?= $row['loss_create']; ?></td>
												<td><?= $row['approved_status']; ?></td>
												<td width="150"> <button type='submit' name='submit' value='update' class="btn btn-primary" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-share'></i> รายงานเหตุการณ์
													</button></td>

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
										<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่เกิดเหตุการณ์</label><input type="text" class="form-control"  name='prefix' value='<?= $row2['prefix'] ?>'></div>
										<div class="col-lg-3 col-xs-12"><label class="margins-top-10">วันที่ตรวจพบ</label><input type="text" class="form-control"  name='name' value='<?= $row2['name'] ?>'></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">เหตุการณ์</label><input type="text" class="form-control"  name='surname' value='<?= $row2['surname'] ?>'></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">รายละเอียดเหตุการณ์<span style="color: red;">*</span></label><textarea id="w3review" class="form-control" name="w3review" rows="3" cols="50" style="min-height:80px;">

</textarea></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">สาเหตุ<span style="color: red;">*</span></label><textarea id="fdf" class="form-control" name="sdd" rows="3" cols="50" style="min-height:80px;"></textarea></div>
										<div class="col-lg-6 col-xs-12"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><input type="text" class="form-control"  name='surname' value='<?= $row2['surname'] ?>'></div>
										<div class="col-lg-3"> <label class="margins-top-10">ประเภทความเสียหาย<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
												<option value="0" >กรุณาระบุ สาขา / ฝ่าย </option>
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
										<div class="col-lg-3"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
												<option value="0">กรุณาระบุ สาขา / ฝ่าย </option>
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
										
										<div class="col-lg-2">	<label class="margins-top-10 col-xs-12" style="margin-left: -13px;">Loss : </label><label class="radio-inline"><input type="radio" name="optradio"> Actual Loss
										</label></div>
										<div class="col-lg-2">	<label style="height:44px;"></label><label class="radio-inline" ><input type="radio" name="optradio"> Potential Loss
										</label></div>
										<div class="col-lg-2">	<label style="height:44px;"></label><label class="radio-inline" ><input type="radio" name="optradio"> Near-Missed
										</label></div>
										
											<div class="col-lg-6"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><input type="text" class="form-control"  name='signature'></div>
											<div class="col-lg-4"><label class="margins-top-10">มูลค่าความเสียหาย<span style="color: red;">*</span></label><input type="text" class="form-control"  name='surname' value='<?= $row2['surname'] ?>'></div>
											<div class="col-lg-4"><label class="margins-top-10">โอกาส<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
												<option value="0">มูลค่าความเสียหาย<span style="color: red;">*</span></option>
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
											<div class="col-lg-4"><label class="margins-top-10">ผลกระทบ<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
												<option value="0">กรุณาระบุ สาขา / ฝ่าย </option>
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
											<div class="col-lg-3"><label class="margins-top-10">ฝ่ายงานที่เกี่ยวข้อง</label><select name='department_id' id='department_id' class="form-control">
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
											<div class="col-lg-3"><label class="margins-top-10">ฝ่ายงานที่ 1</label><select name='department_id' id='department_id' class="form-control">
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
											<div class="col-lg-3"><label class="margins-top-10">ฝ่ายงานที่ 2</label><select name='department_id' id='department_id' class="form-control">
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
											<div class="col-lg-3"><label class="margins-top-10">ฝ่ายงานที่ 3</label><select name='department_id' id='department_id' class="form-control">
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
											<div class="col-lg-12"><label class="margins-top-10">ความเห็นผู้มีอำนาจอนุมัติ</label><textarea id="fdf" class="form-control" name="sdd" rows="3" cols="50" style="min-height:80px;"></textarea></div>
									</div>
								</div>
								<div align="center" style="margin-top: 30px;">
									<input type='hidden' name='update_id'  >
									<button type='submit' name='submit'  class="btn btn-danger"><i class='fa fa-save'></i> บันทึก</button>
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
		var table = $('#example').DataTable({
			responsive: true
		});

		new $.fn.dataTable.FixedHeader(table);
	});
</script>