
<?php
header('Cache-Control: no-store, no-cache, must-revalidate');    
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); 
header('Content-Type: text/html; charset=utf-8');

ob_end_clean();
require('PHPPdf/fpdf.php');
require('inc/connect.php');
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
                                            $user_effect = $row['user_effect'];
                                            $cause = $row['damage_type'];
                                            $cause = $row['incidence_type'];
                                            $cause = $row['loss_type'];
                                            $cause = $row['control'];
                                            $cause = $row['loss_value'];
                                            $cause = $row['chance'];
                                            $cause = $row['effect'];
                                            $cause = $row['damageLevel'];
                                           

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
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        ผลกระทบ                           :  '.$cause),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        ประเภทความเสียหาย         :  '.$cause),0,1);
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        การควบคุมที่มีอยู่               :  '.$cause),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        มูลค่าความเสียหาย (บาท)   :  '.$cause),0,1);
    $pdf->Cell(0,0,iconv( 'UTF-8','TIS-620','        ระดับความเสี่ยงหาย           :  '.$cause),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','        ฝ่ายงานที่เกี่ยวข้อง              :  '.$cause),0,1);
    $pdf->Cell(0,15,iconv( 'UTF-8','TIS-620','                       '),0,1);

    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','( ................................................ )                                   ( ................................................ )'),0,1,"C");
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','ผู้จัดทำ                                                                           ผุ้อนุมัติ'),0,1,"C"); 
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','......................................................                                 ...................................................... '),0,1,"C");
    $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','ตำแหน่ง ............................................                           ตำแหน่ง............................................. '),0,1,"C");


	// $pdf->Output("MyPDF/MyPDF.pdf","F");
$pdf->Output();

  
?>