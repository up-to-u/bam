<script>

var maxc = 10000000000;
		$(document).ready(function() {
		 $('.key-numeric').bind("cut copy paste",function(e) {
  e.preventDefault();
 });
    $('.key-numeric').keypress(function(e) {
  var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
  if (verified) {e.preventDefault();}
    }); 
    $('.key-numeric').keyup(function () {
  if(event.which >= 37 && event.which <= 40) return;
  if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
     this.value = this.value.replace(/[^0-9\.]/g, '');
  }
  if (this.value>maxc) this.value=maxc;
  $(this).val(function(index, value) {
   return value
   .replace(/\D/g, "")
   .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  });  
 });
 
		
	});
</script>
<?
include('inc/include.inc.php');
$action1 = $_POST['action1'];
$action = $_POST['action'];
$action2 = $_POST['action2'];
$d = $_POST['data'];
$d1 = $_POST['data1'];
$d2 = $_POST['data2'];
$d3 = $_POST['data3'];
$d4 = $_POST['data4'];
if ($action=='type_loss12') {
?>
	<select name="type_loss2" id="type_loss2" class="form-control"><option value="">--โปรดเลือก--</option>
	<?
	$sql = "SELECT * FROM loss_impact  where loss_impact_parent ='ET$d'  ORDER BY loss_impact_value ";
	$result2 = mysqli_query($connect, $sql);
	while ($row2 = mysqli_fetch_array($result2)) {?>
	<option value="<?=$row2['loss_impact_value']?>" <? if ($rowsh['type_loss1']==$row2['loss_impact_value']){ echo "selected";}?> >
	<?=$row2['loss_impact_name']?> </option>';
	<?
	}}?>
</select>


<? if ($action1=='open') {?>
	<div id='open1'name='open1'  >
												
											<div class="col-lg-2" style='padding-top:10px;'>วงเงิน  (บาท)<font color='red'>*</font>
											<input type="text" value='<?=$rowsh['money'];?>'  required  name="money"id="money" class="form-control key-numeric" placeholder='ระบุวงเงิน'   ></div>
											<div class="col-lg-2"style='padding-top:10px;'>คชจ. ในการเรียกค่าเสียหายคืน (บาท)<font color='red'>*</font><input type="text" value='<?=$rowsh['money_back'];?>'  required   name="money_back" class="form-control key-numeric" placeholder='ระบุค่าใช้จ่ายในการเรียกคืน'   ></div>
											<div class="col-lg-3"style='padding-top:10px;'>จำนวนเงินที่ได้รับจากการประกันภัยหรือเรียกคืนได้   (บาท)<font color='red'>*</font><input type="text" value='<?=$rowsh['money_get'];?>' name="money_get"  class="form-control key-numeric"placeholder='ระบุเงินที่ได้รับ'  class="form-control "  ></div>
											</div>
<?}?>

<? if ($action1=='close1') {?>
	<div id='open1'name='open1'  ></div>
<?}?>

<? if ($action2=='openN2') {  ?>
	<div id='N2div'>
											<div class="col-lg-2">  
													<select  class='form-control' id='N2'  name ='N2'>
													<option  value=''>สาเหตุ/ปัจจัย ลำดับที่ 2</option><?
														$sql1="SELECT * FROM loss_impact  
														where loss_impact_parent ='N1-N5' and loss_impact_value !=$d1  ORDER BY loss_impact_value ";
														$result1=mysqli_query($connect, $sql1);
														while ($row1 = mysqli_fetch_array($result1)) {?>
														<option  value='<?=$row1['loss_impact_value']?>' <? if ($rowsh['N2']==$row1['loss_impact_value']){ echo "selected";}?> ><?=$row1['loss_impact_name']?></option>
														<?}?>	  </select>
											</div></div>
											
											
<?}?>
 <script type="text/javascript">
$(function() {	
			$("#N2").change(function() {
				var N1 = $("#N1").val();	
				var N2 = $("#N2").val();				
	if(N2!=""){
			$.post( "loss_content.php", { action2:'openN3', data2: N2 , data1: N1})
					.done(function( data ) {
						$("#N3div").html(data);
					});}
		});	
});	
</script>




<? if ($action2=='openN3') {?>
	<div id='N3div'>
											<div class="col-lg-2"> 
													<select  class='form-control ' id='N3' name ='N3'>
													<option  value=''>สาเหตุ/ปัจจัย ลำดับที่ 3</option><?
														$sql1="SELECT * FROM loss_impact  
														where loss_impact_parent ='N1-N5' and loss_impact_value !=$d2 and loss_impact_value !=$d1  ORDER BY loss_impact_value ";
														$result1=mysqli_query($connect, $sql1);
														while ($row1 = mysqli_fetch_array($result1)) {?>
														<option  value='<?=$row1['loss_impact_value']?>' <? if ($rowsh['N3']==$row1['loss_impact_value']){ echo "selected";}?> ><?=$row1['loss_impact_name']?></option>
														<?}?>	  </select>
											</div></div>
											
											
<?}?>

 <script type="text/javascript">
$(function() {	
			$("#N3").change(function() {
				var N1 = $("#N1").val();	
				var N2 = $("#N2").val();	
				var N3 = $("#N3").val();		
	if(N3!=""){
			$.post( "loss_content.php", { action2:'openN4', data2: N2 , data1: N1, data3: N3})
					.done(function( data ) {
						$("#N4div").html(data);
					});}
		});	
});	
</script>

<? if ($action2=='openN4') {?>
	<div id='N4div' name='N4div'>
											<div class="col-lg-2">  
													<select  class='form-control' id='N4' name ='N4'>
													<option  value=''>สาเหตุ/ปัจจัย ลำดับที่ 4</option><?
														$sql1="SELECT * FROM loss_impact  where loss_impact_parent ='N1-N5'
															and loss_impact_value !=$d2
															and loss_impact_value !=$d1 
															and loss_impact_value !=$d3 
															ORDER BY loss_impact_value ";
														$result1=mysqli_query($connect, $sql1);
														while ($row1 = mysqli_fetch_array($result1)) {?>
														<option  value='<?=$row1['loss_impact_value']?>' <? if ($rowsh['N4']==$row1['loss_impact_value']){ echo "selected";}?> ><?=$row1['loss_impact_name']?></option>
														<?}?>	  </select>
											</div></div>
											
											
<?}?>

 <script type="text/javascript">
$(function() {	
			$("#N4").change(function() {
				var N1 = $("#N1").val();	
				var N2 = $("#N2").val();	
				var N3 = $("#N3").val();
				var N4 = $("#N4").val();
				
	if(N4!=""){
			$.post( "loss_content.php", { action2:'openN5', data2: N2 , data1: N1, data3: N3, data4: N4})
					.done(function( data ) {
						$("#N5div").html(data);
					});}
		});	
});	
</script>

<? if ($action2=='openN5') {?>
	<div id='N5div' name='N5div'>
											<div class="col-lg-2">  
													<select  class='form-control '   id='N5' name ='N5'>
													<option  value=''>สาเหตุ/ปัจจัย ลำดับที่ 5</option><?
														$sql1="SELECT * FROM loss_impact  where loss_impact_parent ='N1-N5'
														and loss_impact_value !=$d2
														and loss_impact_value !=$d1 
														and loss_impact_value !=$d3 
														and loss_impact_value !=$d4 
														ORDER BY loss_impact_value ";
														$result1=mysqli_query($connect, $sql1);
														while ($row1 = mysqli_fetch_array($result1)) {?>
														<option  value='<?=$row1['loss_impact_value']?>' <? if ($rowsh['N5']==$row1['loss_impact_value']){ echo "selected";}?> ><?=$row1['loss_impact_name']?></option>
														<?}?>	  </select>
											</div></div>
											
											
<?}?>





<?
if ($action=='editor' and $d !=''){
$sql2="SELECT *	 FROM user where code ='$d' ";
	$result2=mysqli_query($connect, $sql2);
	if ($row2 = mysqli_fetch_array($result2)) {	?>
<input type="text"class="form-control" name="editor_name" readonly required value="<?= $row2['name']." ".$row2['surname'];?>" />
<input type="hidden"class="form-control" name="emaileditor"required value="<?= $row2['email'];?>" />
<input type="hidden"class="form-control" name="editorcode"required value="<?= $row2['user_id'];?>" />
<?	}else{?><input type="text"class="form-control" name="editor_name" readonly required value="ไม่พบรายชื่อพนักงาน" />

<?}}?>