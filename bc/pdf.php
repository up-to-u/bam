<?
use setasign\Fpdi\Fpdi;
require_once('fpdf/fpdf.php');
require_once('fpdi2/src/autoload.php');
include('inc/include.inc.php');
 $doc = $_GET['doc'];
 $loss_data_doc_list_id = $_POST['loss_data_doc_list_id'];

class PDF extends FPDI
{
	function Footer()
	{
		// Go to 1.5 cm from bottom
		$this->SetY(-15);
		// Select Arial italic 8
		$this->SetFont('Arial','I',8);
		// Print current and total page numbers
		
		$this->Cell(0,0, iconv('UTF-8', 'TIS-620', 'Page ').$this->PageNo(),0,0,'C');
	}
}

if ($loss_data_doc_list_id>0) {
	
		//$pdf = new Fpdi();
		$pdf = new PDF();
		$pdf->SetAutoPageBreak(true,10);
		//$pdf->SetAutoPageBreak(false);

		$pdf->addPage('P');
		$pdf->setSourceFile('doc/loss_data.pdf');
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx, 0, 0, 210);
	
		$pdf->Footer();
	
		$pdf->AddFont('THSarabun','','THSarabun.php');//ธรรมดา
		$pdf->AddFont('THSarabun','B','THSarabun.php');//หนา
		$pdf->SetFont('THSarabun','', 10);
		$pdf->SetTextColor(0, 0, 0);
	
		$sql = "SELECT * FROM loss_data_doc_list join loss_data_doc ON
		loss_data_doc.loss_data_doc_id = loss_data_doc_list.loss_data_doc_id  
		WHERE loss_data_doc_list.loss_data_doc_list_id = '$loss_data_doc_list_id' ";
		$result2 = mysqli_query($connect,$sql);
		if ($row = mysqli_fetch_array($result2)) {
			
			$loss_dep =$row['loss_dep'];
			$doclist_user_id =$row['doclist_user_id'];

			$sql = "
			SELECT user.*,user.department_name,department.department_level_id, user.group_name,user.division_name,user.auth_loss 
			FROM user 
			join department on  department.department_id = user.department_id 
			WHERE user.user_id=? ";
			$stmt = $connect->prepare($sql);
			$stmt->bind_param("i", $doclist_user_id);
			$stmt->execute();
			$res1 = $stmt->get_result();
			if ($row_mem = $res1->fetch_assoc()) {
				$department_name = $row_mem['department_name'];
				$group_name = $row_mem['group_name'];
				$division_name = $row_mem['division_name'];
				$department_id = $row_mem['department_id'];
			}

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
		
											
			$pdf->SetFont('THSarabun','', 65);
			
			
			$pdf->SetFont('THSarabun','', 14);	
			$pdf->SetXY(30, 34.5);
			$pdf->Write(0, utf2tis($row_mem['group_name']."   ".$row_mem['department_name']."   ".$row_mem['division_name']));
			
			/*$pdf->SetXY(85, 34.5);
			$pdf->Write(0, utf2tis(($row_mem['department_name'])));
			
			$pdf->SetXY(140, 34.5);
			$pdf->Write(0, utf2tis(($row_mem['division_name'])));*/
			
			$pdf->SetXY(88, 24.5);
			$pdf->Write(0, utf2tis(month_name($row['loss_data_doc_month'])));
			
			$pdf->SetXY(128, 24.5);
			$pdf->Write(0, utf2tis(($row['loss_year'])));
			
		/*		
			$pdf->SetXY(88, 53);
			$pdf->Write(0, utf2tis(mysqldate2th_date($row['happen_date'])));
			
			$pdf->SetXY(88, 60);
			$pdf->Write(0, utf2tis(mysqldate2th_date($row['checked_date'])));
		*/
			
		/*	$pdf->SetXY(88, 66);
			$pdf->Write(0, utf2tis(($incidence)));
			$pdf->SetXY(26, 73);
			$pdf->Write(0, utf2tis(($incidence1)));
			$pdf->SetXY(26, 80);
			$pdf->Write(0, utf2tis(($incidence2)));
		*/
			
			$pdf->SetXY(26.5, 53);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("วันที่เกิดเหตุการณ์ : "));
			$pdf->SetXY(60, 49);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(150,8,utf2tis(mysqldate2th_date($row['happen_date'])), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("วันที่ตรวจพบ : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(mysqldate2th_date($row['checked_date'])), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("เหตุการณ์ : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis("เหตุการณ์ : ").utf2tis($row['incidence']), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("รายละเอียดเหตุการณ์ : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis($row['incidence_detail']), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("สาเหตุ : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis($row['cause']), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("ผลกระทบ : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(($row['user_effect'])), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("ประเภทความเสียหาย : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(dmgLossType($row['damage_type'])), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("ประเภทเหตุการณ์ความเสียหาย : "));
			$pdf->SetXY(80, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(incidenceType($row['incidence_type'])), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("โอกาสที่จะเกิดขึ้น : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(ChanceType($row['chance'])), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("ผลกระทบที่เกิดขึ้น : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(EffectType($row['effect'])), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("ระดับความเสียหาย : "));
			$pdf->SetXY(60, $pot_y-3);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis($damageLevelResault), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("มูลค่าความเสียหาย(บาท) : "));
			$pdf->SetXY(70, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(number_format($row['loss_value']), 2, '.', ''), 0, 1);
			
			$pot_y = $pdf->GetY()+4;
			$pdf->SetXY(26.5, $pot_y);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("ความเสียหาย : "));
			$pdf->SetXY(60, $pot_y-4);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->MultiCell(120,8,utf2tis(dmgLossType($row['damage_type'])), 0, 1);
			
			/*
			$nb = $pdf->PageNo();	
			if($nb == 1){
				$pdf->Line(22,50,190,50); // top
				$pdf->Line(22,50,22,$pot_y+5); // left
				$pdf->Line(190,50,190,$pot_y+5); // right
				$pdf->Line(22,$pot_y+5,190,$pot_y+5); // bottom
			}else{
				$pdf->Line(22,50,190,50); // top
				$pdf->Line(22,50,22,280); // left
				$pdf->Line(190,50,190,280); // right
				$pdf->Line(22,280,190,280); // bottom
				
				$pdf->Line(22,10,190,10); // top
				$pdf->Line(22,10,22,$pot_y+5); // left
				$pdf->Line(190,10,190,$pot_y+5); // right
				$pdf->Line(22,$pot_y+5,190,$pot_y+5); // bottom
			}
			*/
			
			/*
			$pdf->SetXY(26.5, $pot_y+10);
			$pdf->SetFont('THSarabun','B', 14);
			$pdf->Write(0,utf2tis("Number of : ".$nb));
			*/
			
			
			/* $pdf->SetXY(88, 196.5);
			$pdf->Write(0, utf2tis(dmgLossType($row['damage_type'])));
			
			$pdf->SetXY(88, 201.5);
			$pdf->SetFont('THSarabun','', 12);
			$pdf->MultiCell(100,3,utf2tis(incidenceType($row['incidence_type'])), 0, 1);
			
			
			$pdf->SetXY(88, 210);
			$pdf->SetFont('THSarabun','', 14);
			$pdf->Write(0, utf2tis(ChanceType($row['chance'])));
			
			$pdf->SetXY(88, 217);
			$pdf->Write(0, utf2tis(EffectType($row['effect'])));
			
			$pdf->SetXY(88, 223.5);
			$pdf->Write(0, utf2tis($damageLevelResault));
			
			$pdf->SetXY(88, 230);
			$pdf->Write(0, utf2tis(number_format($row['loss_value']), 2, '.', ''));
			
			$pdf->SetXY(88, 236.5);
			$pdf->Write(0, utf2tis(dmgLossType($row['damage_type']))); */
			
		
			if(Getsignature($row['doclist_user_id'])!=''){				
				$pdf->Image('signature_file/'.Getsignature($row['doclist_user_id']),40,242,30);
			}
				
			if(Getsignature($row['approve_id'])!=''){	
				$pdf->Image('signature_file/'.Getsignature($row['approve_id']),120,242,30);
			}
			
			
			$pdf->SetXY(50, 265);
			
			$pdf->Write(0, utf2tis(createName($row['doclist_user_id'])));
			$pdf->SetXY(30, 266);
			$pdf->Write(0,utf2tis('ผู้จัดทำ.............................................................'));
			
			$pdf->SetXY(50, 272);
			$pdf->Write(0, utf2tis(positionName($row['doclist_user_id'])));
			$pdf->SetXY(30, 273);
			$pdf->Write(0,utf2tis('ตำแหน่ง...........................................................'));
			$pdf->SetXY(50, 277);
			$pdf->Write(0, utf2tis(mysqldate2th_date($row['loss_data_doc_createdate'])));
			
			$pdf->SetXY(130, 265.5);
			$pdf->Write(0, utf2tis(createName($row['approve_id'])));
			$pdf->SetXY(110, 266);
			$pdf->Write(0,utf2tis('ผู้อนุมัติ.............................................................'));
			$pdf->SetXY(130, 272);
			$pdf->Write(0, utf2tis(positionName($row['approve_id'])));
			$pdf->SetXY(110, 273);
			$pdf->Write(0,utf2tis('ตำแหน่ง...........................................................'));
			$pdf->SetXY(130, 276.5);
			$pdf->Write(0, utf2tis(mysqldate2th_date($row['approved_date'])));
			
		}
		
		$pdf->Output();	
	
	
}

function init_number($nilai) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = $this->init_number($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp = $this->init_number($nilai/10)." puluh". $this->init_number($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . $this->init_number($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = $this->init_number($nilai/100) . " ratus" . $this->init_number($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . $this->init_number($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = $this->init_number($nilai/1000) . " ribu" . $this->init_number($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = $this->init_number($nilai/1000000) . " juta" . $this->init_number($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = $this->init_number($nilai/1000000000) . " milyar" . $this->init_number(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = $this->init_number($nilai/1000000000000) . " trilyun" . $this->init_number(fmod($nilai,1000000000000));
	}     
	return $temp;
}

function numb_to_text($nilai) {
	$nilai = (int) $nilai;
	if($nilai<0) {
		$hasil = "minus ". trim($this->init_number($nilai));
	} else {
		$hasil = trim($this->init_number($nilai));
	}           
	return $hasil;
}

function string_limit($st, $len) {
	return utf2tis(iconv_substr($st, 0, $len, 'UTF-8'));
}
	
function write_center($pdf, $x1, $x2, $y, $st, $debug=false) {
	if ($debug) {
		$pdf->SetXY($x1, $y);
		$pdf->Write(0, '[');
		$pdf->SetXY($x2, $y);
		$pdf->Write(0, ']');
	}
	$x = get_center($x1, $x2, thai_string_width($st, $pdf));
	$pdf->SetXY($x, $y);
	$pdf->Write(0, $st);
}	
	
function get_center($x1, $x2, $l) {
	$t = (($x2 - $x1 - $l) / 2);
	if ($t>0)
		return $x1+$t;
	else
		return $x1;
}

function thai_string_width($s, $pdf) {
	$t = array('่','้','๊','๋','ั','ํ','ิ','ี','ึ','ื','ุ','ู','์','็');
	$s = str_replace($t, '', $s);
	return $pdf->GetStringWidth($s);
}

function thai_string_trim($s, $len) {
	$s = str_replace("\r\n", '', $s);
	$s = str_replace("\n", '', $s);
	$s = str_replace("\r", '', $s);
	$t = array('่','้','๊','๋','ั','ํ','ิ','ี','ึ','ื','ุ','ู','์','็');
	$i = 0;
	while ($i<$len) {
		$t2 = substr($s, $i, 1);
		if (!in_array($t2, $t)) {
			$i++;
		}
	}	
	return substr($s, 0, $i);
}
function utf2tis($s) {
	return iconv('UTF-8', 'TIS-620', $s);
}
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

function incidenceType($parameters)
{
    require('inc/connect.php');
	$sql = "SELECT factor FROM loss_factor WHERE parent_id = '2' AND loss_factor_id = '$parameters' ";
		$qry = mysqli_query($connect, $sql);
		if ($row = mysqli_fetch_array($qry)) {
		return $row['factor'];
		}else{
		return ' - ';
		}
}

function ChanceType($parameters)
{
    require('inc/connect.php');
	$sql = "SELECT factor FROM loss_factor WHERE parent_id = '4' AND factor_no = '$parameters' ";
		$qry = mysqli_query($connect, $sql);
		if ($row = mysqli_fetch_array($qry)) {
		return $row['factor'];
		}else{
		return ' - ';
		}
}

function EffectType($parameters)
{
    require('inc/connect.php');
	$sql = "SELECT factor FROM loss_factor WHERE parent_id = '3' AND factor_no = '$parameters' ";
		$qry = mysqli_query($connect, $sql);
		if ($row = mysqli_fetch_array($qry)) {
		return $row['factor'];
		}else{
		return ' - ';
		}
}

function createName($id)
{
	global $connect;
	$sql = "SELECT name, surname FROM user WHERE user_id = '$id' ";
	$qry = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($qry)) {
		return $row['name']." ".$row['surname'];
	}
	return '';
}

function positionName($id)
{
	global $connect;
	$sql = "SELECT position FROM user WHERE user_id = '$id' ";
	$qry = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($qry)) {
		return $row['position'];
	}
	return '';
}

function Getsignature($id)
{
	global $connect;
	$sql = "SELECT signature FROM user WHERE user_id = '$id' ";
	$qry = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($qry)) {
		return $row['signature'];
	}
	return '';
}
?>