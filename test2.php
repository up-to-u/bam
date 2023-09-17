<?
include('inc/include.inc.php');
include('loss_function.php');

 $d1 = $_POST['data1'];
 $d2 = $_POST['data2'];
$action = $_POST['action'];
?>

<div name='test2'>
<? gen_risk_matrix_loss($d1,$d2);

?>
<? echo $d1?> <? echo $d2?> </div>

