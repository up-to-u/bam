
<?php

include('inc/connect.php');
include('inc/functionLoss.inc.php');

if($_POST['authenStatus'] != ''){

}else{
    $sql = "SELECT * from loss_data_doc 
    join loss_data_doc_list ON loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id WHERE loss_data_doc_list.status_risk_approve ='".$_POST['s_risk']."' AND loss_data_doc.loss_year ='".$_POST['y_risk']."' AND loss_data_doc_list.status_approve ='1'";
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
								<th>LET</th>
								<th>ฝ่ายงาน</th>
								<th>เดือนที่รายงาน</th>
                                <th>วันที่เกิดเหตุการณ์</th>
                                <th>เหตุการณ์</th>
                                <th>สาเหตุ</th>
                                <th>ผลกระทบ</th>
                                <th>การควบคุมที่มีอยู่การควบคุมที่มีอยู่</th>
                                <th>ระดับความเสียหาย</th>
                                <th>มูลค่าความเสียหาย</th>
                                <th>Close Case</th>
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
								<td><?= incidenceType($row['incidence_type']);?></td>
                                <td><?= deptName($row['loss_dep']);?></td>
                                <td><?= month_name($row['loss_data_doc_month']);?></td>
                                <td><?= $row['happen_date'];?></td>
                                <td><?= $row['incidence'];?></td>
                                <td><?= $row['cause'];?></td>
                                <td><?= $row['user_effect'];?></td>
                                <td><?= $row['control'];?></td>
                                <td><?= $row['displayRisk'];?></td>
                                <td><?= number_format($row['loss_value'],2);?></td>
                                <td><?= $row['end_date'];?></td>
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
		header("Content-Disposition: attachment; filename=Export_Risk_".date('Ymdhis').".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
?>