<?php
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="loss_export'.date('Y-m-d_His').'.xls"');
include('inc/include.inc.php');	

 $loss_data_doc_month_search = $_POST['loss_data_doc_month_search'];
 $yearsearch = $_POST['yearsearch'];

?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<HTML>
<HEAD>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
</HEAD><BODY>
<TABLE BORDER="1">
<tbody>
<div align="center" valign="middle" ><b><?if ($loss_data_doc_month_search=='') { echo "สรุปผลรายงานความเสียหายทั้งหมด ";}
				else {echo" สรุปรายงานเหตุการณ์ความผิดปกติ (incident) ประจำเดือน";
				$sql11="SELECT * FROM month where month_id =$loss_data_doc_month_search ";
				$result11=mysqli_query($connect, $sql11);
				$row11 = mysqli_fetch_array($result11);{	
				echo	$row11['month_name']." ".$yearsearch;}}?>							
				</b></div>
	
					
<tr style=' background-color: #b1e8ff' > 
					<td> ลำดับ</td>
					<td>รายงานประจำเดือน</td>
					<td>วันที่รายงาน</td>
					<td>วันที่เกิดเหตุการณ์</td>
					<td>วันที่พบเหตุการณ์</td>
					<td>วันที่เหตุการณ์สิ้นสุด/ปิดเรื่อง</td>
					<td>หน่วยงานที่เกิดเหตุการณ์ / หน่วยงานที่รายงาน</td>
					<td>หน่วยงานที่เกี่ยวข้อง</td>
					<td>ประเภทเหตุการณ์ความเสียหาย</td>
					<td>ประเภทเหตุการณ์ย่อย </td>
					<td>รายละเอียดของเหตุการณ์ </td>
					<td>ด้านการเงิน</td>
					<td>จำนวนเงิน</td>
					<td>Deduct ค่าเสียหายส่วนแรก </td>
					<td>ด้านภาพพจน์</td>
					<td>ด้านการปฏิบัติงาน</td>
					<td>ด้านกฏ ระเบียบข้อบังคับ</td>
					<td>ด้านความปลอดภัย</td>
					<td>โอกาสที่จะเกิดเหตุการณ์</td>
					<td>ระดับผลกระทบ</td>
					<td>ระดับความเสี่ยง</td>
					<td>สาเหตุ/ปัจจัย1 </td>
					<td>สาเหตุ/ปัจจัย2 </td>
					<td>รายละเอียดปัจจัย</td>					
					<td>มูลค่าประมาณการ </td>
					<td>มูลค่าเกิดจริง</td>
					<td>การประกันคุ้มครองความผิดปกติ </td>
					<td>วงเงิน </td>
					<td>ค่าใช้จ่ายในการเรียกค่าเสียหายคืน </td>
					<td>จำนวนเงินที่ได้รับจากการประกันภัยหรือเรียกคืนได้ </td>
					<td>การเกิดซ้ำ </td>
					<td>การดำเนินการ/การแก้ไข เพื่อป้องกันเหตุการณ์ที่อาจเกิดขึ้นในอนาคต</td>
					<td>ผลการแก้ไข (รายงานโดยฝ่ายที่เกี่ยวข้อง)</td>
					<td>สถานะเหตุการณ์</td>
					<td>เดือนที่ปิดรายการ</td>
					<td>ประเภทเหตุการณ์</td>
					<td>ผลกระทบ</td>
					<td>จัดกลุ่มหัวข้อ</td>
					<td>แหล่งทีมาข้อมูล</td>
					</tr>

<?	$x=1;
//and loss_data_report.mark_del='0'
		$sql1 = "SELECT * FROM loss_data_doc join month on month.month_id = loss_data_doc.loss_data_doc_month join loss_data_report on`loss_data_doc`.`loss_data_doc_id` = `loss_data_report`.`loss_data_doc_id`
		where loss_data_doc.loss_year = '$yearsearch' ";
			
		if($loss_data_doc_month_search!=''){
					$sql1 = $sql1." and loss_data_doc.loss_data_doc_month =$loss_data_doc_month_search ";}
			$result1 = mysqli_query($connect, $sql1);
			if (mysqli_num_rows($result1)>0) {$i=0;
			while ($row1 = mysqli_fetch_array($result1)) { $i++;
?>	
				<tr>
					<td><?=$i;?></td>
					<td><?=$row1['month_name']." ".$yearsearch;?> </td>
					<td><?=mysqldate2th_date($row1['create_date']);?></td>
					<td><?=mysqldate2th_date($row1['date1']);?></td>
					<td><?=mysqldate2th_date($row1['date2']);?></td>
					<td><?=mysqldate2th_date($row1['date3']);?></td>
					<td><?=$B0 = $row1['B0'];?></td>
					<td><?=$B1 = $row1['B1'];?></td>
					<td>ET <? echo $type_loss1 = $row1['type_loss1']; $type_loss2 = $row1['type_loss2'];?></td>
					<td><?$type_loss2 = $row1['type_loss2'];
					$sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='ET$type_loss1' and loss_impact_value = $type_loss2  ";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					echo $rowQ2['loss_impact_name'];}?> </td>
					
					<td><?=$row1['info_loss'];?></td>
							
					<?$P1 = $row1['P1'];
					 $sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='money' and loss_impact_value =$P1";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					$lv = $rowQ2['loss_impact_value']; if ($lv!='')
					{
											if ($lv=='1'){$bg = '#aaff71'; }if($lv=='2'){$bg = '#5cb322'; }
											if($lv=='3'){$bg = '#f6ff00'; }if($lv =='4'){$bg = '#ff8400'; }
											if($lv =='5'){$bg = '#ff0000'; } }else {$bg = 'white';}	?>
					<td style="background-color: <?echo $bg?> ;" ><?echo $rowQ2['loss_impact_name']; }?> </td>
					<td><?=$row1['moneys'];?></td>
					<td><?=$row1['deduct'];?></td>				
					<?$P2 = $row1['P2'];
					$sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='ภาพพจน์' and loss_impact_value =$P2";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)){
					$lv = $rowQ2['loss_impact_value']; 
					if ($lv!=''){			if ($lv=='1'){$bg = '#aaff71'; }if($lv=='2'){$bg = '#5cb322'; }
											if($lv=='3'){$bg = '#f6ff00'; }if($lv =='4'){$bg = '#ff8400'; }
											if($lv =='5'){$bg = '#ff0000'; } }else {$bg = 'white';}	?>
					<td style="background-color: <?echo $bg?> ;" ><?echo $rowQ2['loss_impact_name']; }?> </td>
					<?$P3 = $row1['P3'];
					 $sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='การปฏิบัติงาน' and loss_impact_value =$P3";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)){
					$lv = $rowQ2['loss_impact_value']; 
					if ($lv!=''){			if ($lv=='1'){$bg = '#aaff71'; }if($lv=='2'){$bg = '#5cb322'; }
											if($lv=='3'){$bg = '#f6ff00'; }if($lv =='4'){$bg = '#ff8400'; }
											if($lv =='5'){$bg = '#ff0000'; } }else {$bg = 'white';}	?>
											<td style="background-color: <?echo $bg?> ;" ><?echo $rowQ2['loss_impact_name']; }?> </td>
					
					<?$P4 = $row1['P4'];
					 $sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='ข้อบังคับ' and loss_impact_value =$P4";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {$lv = $rowQ2['loss_impact_value']; 
					if ($lv!='')
											{
											if ($lv=='1'){$bg = '#aaff71'; }if($lv=='2'){$bg = '#5cb322'; }
											if($lv=='3'){$bg = '#f6ff00'; }if($lv =='4'){$bg = '#ff8400'; }
											if($lv =='5'){$bg = '#ff0000'; } }else {$bg = 'white';}	?>
											<td style="background-color: <?echo $bg?>;" ><?echo $rowQ2['loss_impact_name']; }?> </td>
					
					<?$P5 = $row1['P5'];
					 $sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='ปลอดภัย' and loss_impact_value =$P5";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {$lv = $rowQ2['loss_impact_value']; 
					if ($lv!='')
											{
											if ($lv=='1'){$bg = '#aaff71'; }if($lv=='2'){$bg = '#5cb322'; }
											if($lv=='3'){$bg = '#f6ff00'; }if($lv =='4'){$bg = '#ff8400'; }
											if($lv =='5'){$bg = '#ff0000'; } }else {$bg = 'white';}	?>
											<td style="background-color: <?echo $bg?> ;" ><?echo $rowQ2['loss_impact_name']; }?> </td>
					<? $cd = $row1['C']; if ($cd!='')
											{
											if ($cd<='4'){$color_bg = 'green'; }if($cd=='5' or $cd=='6'){$color_bg = 'yellow'; }
											if($cd=='7' or $cd=='8'){$color_bg = 'orange'; }if($cd >='9'){$color_bg = 'red'; } }else {$color_bg = 'white';}
											?>	
											
										
											<? $O1 = $row1['O1'];
					$sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='โอกาส' and loss_impact_value =$O1";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					$lv = $rowQ2['loss_impact_value']; if ($lv!='')
											{
											if ($lv=='1'){$bg = '#aaff71'; }if($lv=='2'){$bg = '#5cb322'; }
											if($lv=='3'){$bg = '#f6ff00'; }if($lv =='4'){$bg = '#ff8400'; }
											if($lv =='5'){$bg = '#ff0000'; } }else {$bg = 'white';}	
											
					?><td style="background-color: <?echo $bg?> ;" ><? echo $row1['O1']; }?></td>
			
					<? $array = array( 0 => $P1 , 1=> $P2, 2 => $P3 , 3=> $P4,4 => $P5 );
					$max_key = max( array_values( $array ) ); ?>
			
					<td style="background-color: <? if ($max_key=='1'){$bg = '#aaff71'; }if($max_key=='2'){$bg = '#5cb322'; }
					if($max_key=='3'){$bg = '#f6ff00'; }if($max_key =='4'){$bg = '#ff8400'; }
					if($max_key =='5'){$bg = '#ff0000'; }
					echo $bg?> ;" ><?echo $max_key ?></td>
					<td style="background-color: <?echo $color_bg?> ;" ><?echo $cd?> </td>					
						
					<td><?$N1 = $row1['N1'];
					$sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='N1-N5' and loss_impact_value =$N1";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					echo $rowQ2['loss_impact_name']; }?></td>
					<td><?$N2 = $row1['N2'];
					 $sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='N1-N5' and loss_impact_value =$N2";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					echo $rowQ2['loss_impact_name']; }?></td>
					<td><?=$row1['cause_loss'];?></td>
					<td><?=$row1['val_guess'];?></td>
					<td><?=$row1['val_real'];?></td>
					<td><?=$row1['HV'];?> </td>
					<td><?=$row1['money'];?> </td>
					<td><?=$row1['money_back'];?> </td>
					<td><?=$row1['money_get'];?> </td>
					<td><?$reply_a = $row1['reply_a'];
					 $sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='การเกิดซ้ำ' and loss_impact_value =$reply_a";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					echo $rowQ2['loss_impact_name']; }?></td>
					<td><?=$row1['solution_loss'];?></td>
					<td><?=$row1['textedit'];?></td>
					<td><?$fix = $row1['fix'];
					 $sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='fix' and loss_impact_value =$fix";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					echo $rowQ2['loss_impact_name'];}?> </td>	
					<td><?$mc = $row1['mc'];
					 $sqlQ2="SELECT * FROM month  where month_id =$mc";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					while ($rowQ2 = mysqli_fetch_array($resultQ2)) {	
					echo $rowQ2['month_namey']; }?> <? echo $row1['yc'];?> </td>				
					<td><?$typel=$row1['typel'];
					$sqlt1="SELECT loss_impact_name FROM loss_impact  where loss_impact_parent ='typel' and loss_impact_value ='$typel'";
					$resultt1=mysqli_query($connect, $sqlt1);
					while ($rowt1 = mysqli_fetch_array($resultt1)) {	
					echo $rowt1['loss_impact_name']; }
					?> </td>
					<td><?$typell=$row1['typell'];
					$sqltll="SELECT loss_impact_name FROM loss_impact  where loss_impact_parent ='typell' and loss_impact_value ='$typell'";
					$resulttll=mysqli_query($connect, $sqltll);
					while ($rowtll = mysqli_fetch_array($resulttll)) {	
					echo $rowtll['loss_impact_name']; }
					?> </td>
					<td><? $topic = $row1['topic'];
					$sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='topic' and loss_impact_value =$topic ";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					$rowQ2 = mysqli_fetch_array($resultQ2);					
					echo $rowQ2['loss_impact_name']; ?><?=$row1['topic_other'];?></td>
					<td><? $fromsource = $row1['fromsource'];
					$sqlQ2="SELECT * FROM loss_impact  where loss_impact_parent ='fromsource' and loss_impact_value =$fromsource ";
					$resultQ2=mysqli_query($connect, $sqlQ2);
					$rowQ2 = mysqli_fetch_array($resultQ2);					
					echo $rowQ2['loss_impact_name']; ?></td>
				</tr>

		<?	}}?>

</TABLE>
</BODY>
</HTML>
