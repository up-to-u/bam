
<?php
header('Cache-Control: no-store, no-cache, must-revalidate');    
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); 
header('Content-Type: text/html; charset=utf-8');

ob_end_clean();
require('PHPPdf/fpdf.php');
require('inc/connect.php');
require('inc/function.inc.php');
define('PHPPdf/FPDF_FONTPATH','font/');

										$sql = "SELECT * FROM loss_data_doc_list WHERE loss_data_doc_list_id = '".$_POST["loss_data_doc_list_id"]."'";
										$stmt = $connect->prepare($sql);
										$stmt->execute();
										$result = $stmt->get_result();
										while ($row = mysqli_fetch_array($result)) {
                                            $happen_date = $row['happen_date'];
                                            $checked_date = $row['checked_date'];
                                            $incidence = $row['incidence'];
                                            $incidence_detail = $row['incidence_detail'];
                                            $cause = $row['cause'];
                                            $user_effect = $row['user_effect'];
                                            $damage_type = $row['damage_type'];
                                      
                                            $control = $row['control'];
                                            $loss_value = $row['loss_value'];
                                            $chance = $row['chance'];
                                            $effect = $row['effect'];
                                            $damageLevel = $row['damageLevel'];

                                            if (checkLossLevel((int)$damageLevel) == 1) {
                                                $damageLevelResault = 'ต่ำ';
                                            } else if (checkLossLevel((int)$damageLevel) == 2) {
                                                $damageLevelResault = 'ปานกลาง'; 
                                            } else if (checkLossLevel((int)$damageLevel) == 3) {
                                                $damageLevelResault = 'สูง'; 
                                            } else if (checkLossLevel((int)$damageLevel) == 4) {
                                                $damageLevelResault = 'สูงมาก'; 
                                            } else {
                                                $damageLevelResault = ' - '; 
                                            }
                                            $dep_id_1 = deptName($row['dep_id_1']);
                                            $dep_id_2 = deptName($row['dep_id_2']);
                                            $dep_id_3 = deptName($row['dep_id_3']);
                                            $deptsArray = array( $dep_id_1,  $dep_id_2,  $dep_id_3);
                                            $aboutDept = implode(', ',$deptsArray);
                                
                                        }
                                       

	$pdf= new FPDF();
	$pdf->AddPage();
	$pdf->AddFont('angsa','','angsa.php');
	$pdf->SetFont('angsa','',16);
	$pdf->Cell(0,20,iconv( 'UTF-8','TIS-620','รายงานเหตุการการณ์ความเสียหายที่เกิดจากความเสี่ยงด้านการปฏิบัติการ'),0,1,"C");
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','ประจำเดือน .............. พ.ศ...........'),0,1,"C");
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','สาย.......ฝ่าย.........กลุ่ม............'),0,1,"C");
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','                       '),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        วันที่เกิดเหตุการณ์              :  '.$happen_date),0,1);
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        วันที่ตรวจพบ                      :  '.$checked_date),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        เหตุการณ์                            :  '.$incidence),0,1);
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        รายละเอียดเหตุการณ์          :  '.$incidence_detail),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        สาเหตุ                                 :  '.$cause),0,1);
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        ผลกระทบ                           :  '.$user_effect),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        ประเภทความเสียหาย         :  '.dmgLossType($damage_type)),0,1);
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        การควบคุมที่มีอยู่               :  '.$control),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        มูลค่าความเสียหาย (บาท)   :  '.$loss_value),0,1);
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        ระดับความเสี่ยงาย              :  '.$damageLevelResault),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        ฝ่ายงานที่เกี่ยวข้อง              :  '. $aboutDept),0,1);
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','                       '),0,1);
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','                       '),0,1);
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','                       '),0,1);
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','( ................................................ )                                   ( ................................................ )'),0,1,"C");
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','ผู้จัดทำ                                                                           ผุ้อนุมัติ'),0,1,"C"); 
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','......................................................                                 ...................................................... '),0,1,"C");
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','ตำแหน่ง ............................................                           ตำแหน่ง............................................. '),0,1,"C");


	// $pdf->Output("MyPDF/MyPDF.pdf","F");
    $pdf->Output();

function dmgLossType($parameters)
{
    require('inc/connect.php');
	$sql = "SELECT factor FROM loss_factor WHERE parent_id = '1' AND loss_factor_id = '$parameters' ";
		$qry = mysqli_query($connect, $sql);
		if ($row = mysqli_fetch_array($qry)) {
		return $row['factor'];
		}else{
		return ' - ';
		}
}
  
?>