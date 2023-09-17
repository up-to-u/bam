
<?php

include('inc/connect.php');
include('inc/functionLoss.inc.php');

if($_POST['authenStatus'] != ''){

}else{
    if($_POST['letID1'] != ''){
        $fileName = '_LET1';
        $srtLet = $_POST['letID1'];
    }else if($_POST['letID2'] != ''){
        $fileName = '_LET2';
        $srtLet = $_POST['letID2'];
    }else if($_POST['letID3'] != ''){
        $fileName = '_LET3';
        $srtLet = $_POST['letID3'];
    }else if($_POST['letID4'] != ''){
        $fileName = '_LET4';
        $srtLet = $_POST['letID4'];
    }else if($_POST['letID5'] != ''){
        $fileName = '_LET5';
        $srtLet = $_POST['letID5'];
    }else if($_POST['letID6'] != ''){
        $fileName = '_LET6';
        $srtLet = $_POST['letID6'];
    }else if($_POST['letID7'] != ''){
        $fileName = '_LET7';
        $srtLet = $_POST['letID7'];
    }else if($_POST['letID8'] != ''){
        $fileName = '_LET1-LET7';
        $srtLet = $_POST['letID8'];
    }
    if($srtLet == '0'){
        $sql = "SELECT * from loss_data_doc 
        join loss_data_doc_list ON loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id ";
    
    }else{
        $sql = "SELECT * from loss_data_doc 
        join loss_data_doc_list ON loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id WHERE loss_data_doc_list.incidence_type ='".$srtLet."'";
    
    }

}

$stmt = $connect->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>devbanban</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<table border="1" class="table table-hover">
						<thead>
							<tr class="info">
								<th>ลำดับ</th>
								<th>ฝ่ายงาน</th>
								<th>เดือนที่รายงาน</th>
                                <th>วันที่เกิดเหตุการณ์</th>
                                <th>เหตุการณ์</th>
                                <th>สาเหตุ</th>
                                <th>ผลกระทบ</th>
                                <th>การควบคุมที่มีอยู่การควบคุมที่มีอยู่</th>
                                <th>ระดับความเสียหาย</th>
                                <th>มูลค่าความเสียหาย</th>
                       
							</tr>
						</thead>
						<tbody>
                            <?php 
                            $rowCount   =   2;
                            $i=1;
                            while($row  =   $result->fetch_assoc()){ 
                                
                                ?>
							<tr>
								<td><?=$i++;?></td>
                                <td><?= deptName($row['loss_dep']);?></td>
                                <td><?= month_name($row['loss_data_doc_month']);?></td>
                                <td><?= $row['happen_date'];?></td>
                                <td><?= $row['incidence'];?></td>
                                <td><?= $row['incidence_detail'];?></td>
                                <td><?= $row['user_effect'];?></td>
                                <td><?= $row['control'];?></td>
                                <td><?= $row['displayRisk'];?></td>
                                <td><?= number_format($row['loss_value'],2);?></td>
							</tr>

						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>

<?php
		header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=Export_LET_".date('Ymdhis').".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
?>