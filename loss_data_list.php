<?
include('inc/include.inc.php');
echo template_header();

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
					<span class="caption-subject font-green sbold uppercase">ข้อมูลรายการเหตุการณ์</span>
					<span class="caption-helper"></span>
				</div>
			</div><br>
			<div align="center">
								<?php
	if($_GET['listId'] > 0){
		echo "<span style='font-size: 20px;margin:10px;'>ข้อมูลเหตุการณ์ประจำเดือน ".month_name($_GET['m']). " ปี ".$_GET['y']."</span><br>";
	}elseif($_GET['statusListId'] > 0 ){
	 if($_GET['statusListId'] == 1){
       echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลรออนุมัติทั้งหมด</span><br>";
	 }elseif($_GET['statusListId'] == 2){
		echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลส่งกลับแก้ไขทั้งหมด</span><br>";
	 }elseif($_GET['statusListId'] == 3){
		echo "<span style='font-size: 20px;margin:10px;'>รายการข้อมูลที่ดำเนินการเสร็จแล้วทั้งหมด</span><br>";
	 }
	}
								?>
								</div>
								<br>
			<div class="form-group">
				
				<div class="row">
			
					<div class="col-lg-12 col-xs-12" >
						<div class='well'>
					
							<div class='row' style="margin-left: 3px;margin-right: 3px;">
								<!-- start table -->
							
								<table id="dataTableList" class="table table-striped table-bordered" style="width:100%">
									<thead>
										<tr>
											<th width="60">ลำดับ</th>

											<th>เหตุการณ์</th>
											<th align="center" width="90">วันที่บันทึก</th>
											<th width="150">ระดับความเสียหาย</th>
											<th width="100">จัดการ</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if($_GET['listId'] > 0){
											$sql = "SELECT * FROM loss_data_doc_list join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id where loss_data_doc_list.loss_data_doc_id ='".$_GET['listId']."'";
										}elseif($_GET['statusListId'] > 0 ){
											$sql = "SELECT * FROM loss_data_doc_list join loss_data_doc on loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id where loss_data_doc_list.status_approve ='".$_GET['statusListId']."'";
										}
                                       $z = 0;
										$stmt = $connect->prepare($sql);
										$stmt->execute();
										$result = $stmt->get_result();
										while ($row = mysqli_fetch_array($result)) {
											$z++;						
										?>
											<tr>
												<td><?= $z; ?></td>

												<td><?= $row['incidence']; ?>
													
												</td>
												<td><?= $row['loss_data_doc_createdate']; ?></td>
												<?php 
												$numImpact = $row['effect'].$row['chance']; 
											if(checkLossLevel((int)$numImpact) == 1 ){
echo '<td align="center" style="background-color: #00B050; color:#FFFFFF;"> ต่ำ </td>';
											}else if(checkLossLevel((int)$numImpact) == 2){
												echo '<td align="center" style="background-color: #FFFF00;color:#000000;"> ปานกลาง</td>';
											}else if(checkLossLevel((int)$numImpact) == 3){
												echo '<td align="center" style="background-color: #FFC000;color:#000000;"> สูง </td>';
											}else if(checkLossLevel((int)$numImpact) == 4){
												echo '<td align="center" style="background-color: #FF0000;color:#FFFFFF;"> สูงมาก </td>';
											}else {
												echo '<td align="center"> - </td>';
											}
												?>
												<td width="100">
 <button type='submit' name='submit' value='loss_data_list' class="btn btn-success" data-toggle="modal" data-target="#myModalSendCase"><i class='glyphicon glyphicon-list-alt'></i> รายละเอียด</button>
												</td>
	

											</tr>

										<?php } ?>

									</tbody>

								</table>

								<!-- end table -->

							</div>
							
						</div>
						<div align="center"><br> <a href="loss_data.php">
						<span class="glyphicon glyphicon-menu-left"></span><span class="glyphicon glyphicon-menu-left"></span> ย้อนกลับ
         
						</a>
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
					<h4 class="modal-title" style="font-family: 'Prompt', sans-serif;"> <span class="glyphicon glyphicon-list-alt"></span> รายละเอียดข้อมูล</h4>
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
											<div class="col-lg-3"><label class="margins-top-10">ประเภทเหตุการณ์ความเสียหาย<span style="color: red;">*</span></label><select name='department_id' id='department_id' class="form-control">
													<option value="0"> - - - - </option>
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

											<div class="col-lg-2"> <label class="margins-top-10 col-xs-12" style="margin-left: -13px;">Loss : </label><label class="radio-inline"><input type="radio" name="optradio"> Actual Loss
												</label></div>
											<div class="col-lg-2"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="optradio"> Potential Loss
												</label></div>
											<div class="col-lg-2"> <label style="height:44px;"></label><label class="radio-inline"><input type="radio" name="optradio"> Near-Missed
												</label></div>

											<div class="col-lg-6"><label class="margins-top-10">การควบคุมที่มีอยู่<span style="color: red;">*</span></label><input type="text" class="form-control" name='signature'></div>
											<div class="col-lg-4"><label class="margins-top-10">มูลค่าความเสียหาย<span style="color: red;">*</span></label><input type="text" class="form-control" name='surname' value='<?= $row2['surname'] ?>'></div>
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
													<option value="0"> - - - - </option>
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
		var table = $('#dataTableList').DataTable({
			responsive: true
		});

		new $.fn.dataTable.FixedHeader(table);
	});
</script>