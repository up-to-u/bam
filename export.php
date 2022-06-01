<?php

include('inc/connect.php');
include('inc/function.inc.php');
include 'PHPExcel/PHPExcel.php';
include 'PHPExcel/PHPExcel/Writer/Excel2007.php';

$objPHPExcel	=   new PHPExcel();
if($_POST['authenStatus'] != ''){

}else{
    $sql = "SELECT * from loss_data_doc 
    join loss_data_doc_list ON loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id 
    where loss_dep = '202'";
}

$stmt = $connect->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ลำดับ');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'LET');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'ฝ่ายงาน');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'เดือนที่รายงาน');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'วันที่เกิดเหตุการณ์');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'เหตุการณ์');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'สาเหตุ');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ผลกระทบ');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'การควบคุมที่มีอยู่การควบคุมที่มีอยู่');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'ระดับความเสียหาย');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'มูลค่าความเสียหาย');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Close Case');

$objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold(true); // give bold style to cell
$rowCount   =   2;
$i=1;
while($row  =   $result->fetch_assoc()){
    $numImpact = $row['effect'].$row['chance'];
    if (checkLossLevel((int)$numImpact) == 1) {
        $strImpact = 'ต่ำ';
    } else if (checkLossLevel((int)$numImpact) == 2) {
        $strImpact = 'ปานกลาง';
    } else if (checkLossLevel((int)$numImpact) == 3) {
        $strImpact = 'สูง';  
    } else if (checkLossLevel((int)$numImpact) == 4) {
        $strImpact = 'สูงมาก';
    } else {
        $strImpact = '-';
    }

$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$i++);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, incidenceType($row['incidence_type']));
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, deptName($row['loss_dep']));
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, month_name($row['loss_data_doc_month']));
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['happen_date']);
    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['incidence']);
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['cause']);
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['user_effect']);
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['control']);
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $strImpact);
    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['loss_value']);
  

    $rowCount++;

}
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="you-file-name.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

//for write make a library object
$objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
//Or you can also use this library object for write
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); $objWriter->save('php://output');
?>
?>