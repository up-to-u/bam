<?
	$id = $_GET['id'];
	if ($id=='') $id='print_area';
?>
<html>
<head>
	<title>BAM</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="css/style_print.css" type="text/css">	
</head>
<script language='JavaScript'>
function init() {
	document.getElementById("print_area").innerHTML = (window.opener.document.getElementById("<?=$id?>").innerHTML);
	setTimeout('p()', 300);
}

function p() {
window.print();
//self.close();
}
</script>

<body topmargin='20' leftmargin='25' onload='init()'>

<div id='print_area'>
</div>

</body>
</html>