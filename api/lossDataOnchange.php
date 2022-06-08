<?php
include('../inc/connect.php');
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["x"], false);
$strParent = '2';
$stmt = $connect->prepare("select * from loss_factor WHERE factor_no=? AND parent_id=?");
$stmt->bind_param("ii", $obj->limit,$strParent );
$stmt->execute();
$result = $stmt->get_result();
$outp = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($outp);
?>