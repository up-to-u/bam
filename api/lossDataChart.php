<?php
include('../inc/connect.php');
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["x"], false);
$years= '2565';

$incidenceType1 = '1';
$stmt = $connect->prepare("select month.month_id,month.month_name_th ,count(loss_data_doc_list.loss_data_doc_id) as count_incidence from month  
left join loss_data_doc on  loss_data_doc.loss_data_doc_month = month.month_id and  loss_data_doc.loss_year = ?
left join loss_data_doc_list on  loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id and loss_data_doc_list.incidence_type=? group by month_id
order  by month_id ASC");
$stmt->bind_param("ii", $years, $incidenceType1 );
$stmt->execute();
$result = $stmt->get_result();
$outp1 = $result->fetch_all(MYSQLI_ASSOC);

$incidenceType2 = '2';
$stmt = $connect->prepare("select month.month_id,month.month_name_th ,count(loss_data_doc_list.loss_data_doc_id) as count_incidence from month  
left join loss_data_doc on  loss_data_doc.loss_data_doc_month = month.month_id and  loss_data_doc.loss_year = ?
left join loss_data_doc_list on  loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id and loss_data_doc_list.incidence_type=? group by month_id
order  by month_id ASC");
$stmt->bind_param("ii", $years, $incidenceType2 );
$stmt->execute();
$result = $stmt->get_result();
$outp2 = $result->fetch_all(MYSQLI_ASSOC);

$incidenceType3 = '3';
$stmt = $connect->prepare("select month.month_id,month.month_name_th ,count(loss_data_doc_list.loss_data_doc_id) as count_incidence from month  
left join loss_data_doc on  loss_data_doc.loss_data_doc_month = month.month_id and  loss_data_doc.loss_year = ?
left join loss_data_doc_list on  loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id and loss_data_doc_list.incidence_type=? group by month_id
order  by month_id ASC");
$stmt->bind_param("ii", $years, $incidenceType3 );
$stmt->execute();
$result = $stmt->get_result();
$outp3 = $result->fetch_all(MYSQLI_ASSOC);

$incidenceType4 = '4';
$stmt = $connect->prepare("select month.month_id,month.month_name_th ,count(loss_data_doc_list.loss_data_doc_id) as count_incidence from month  
left join loss_data_doc on  loss_data_doc.loss_data_doc_month = month.month_id and  loss_data_doc.loss_year = ?
left join loss_data_doc_list on  loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id and loss_data_doc_list.incidence_type=? group by month_id
order  by month_id ASC");
$stmt->bind_param("ii", $years, $incidenceType4 );
$stmt->execute();
$result = $stmt->get_result();
$outp4 = $result->fetch_all(MYSQLI_ASSOC);

$incidenceType5 = '5';
$stmt = $connect->prepare("select month.month_id,month.month_name_th ,count(loss_data_doc_list.loss_data_doc_id) as count_incidence from month  
left join loss_data_doc on  loss_data_doc.loss_data_doc_month = month.month_id and  loss_data_doc.loss_year = ?
left join loss_data_doc_list on  loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id and loss_data_doc_list.incidence_type=? group by month_id
order  by month_id ASC");
$stmt->bind_param("ii", $years, $incidenceType5 );
$stmt->execute();
$result = $stmt->get_result();
$outp5 = $result->fetch_all(MYSQLI_ASSOC);

$incidenceType6 = '6';
$stmt = $connect->prepare("select month.month_id,month.month_name_th ,count(loss_data_doc_list.loss_data_doc_id) as count_incidence from month  
left join loss_data_doc on  loss_data_doc.loss_data_doc_month = month.month_id and  loss_data_doc.loss_year = ?
left join loss_data_doc_list on  loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id and loss_data_doc_list.incidence_type=? group by month_id
order  by month_id ASC");
$stmt->bind_param("ii", $years, $incidenceType6 );
$stmt->execute();
$result = $stmt->get_result();
$outp6 = $result->fetch_all(MYSQLI_ASSOC);

$incidenceType7 = '7';
$stmt = $connect->prepare("select month.month_id,month.month_name_th ,count(loss_data_doc_list.loss_data_doc_id) as count_incidence from month  
left join loss_data_doc on  loss_data_doc.loss_data_doc_month = month.month_id and  loss_data_doc.loss_year = ?
left join loss_data_doc_list on  loss_data_doc_list.loss_data_doc_id = loss_data_doc.loss_data_doc_id and loss_data_doc_list.incidence_type=? group by month_id
order  by month_id ASC");
$stmt->bind_param("ii", $years, $incidenceType7 );
$stmt->execute();
$result = $stmt->get_result();
$outp7 = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode( [
'object1' => $outp1,
'object2' => $outp2,
'object3' => $outp3,
'object4' => $outp4,
'object5' => $outp5,
'object6' => $outp6,
'object7' => $outp7
]);
?>