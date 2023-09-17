<?
include('inc/include.inc.php');
echo template_header();
?>
<script type="text/javascript">


$(function() {
$("#chance").keyup(function() {
var d1 = $("#effect").val();
var d2 = $("#chance").val();
//alert(d1);
//alert(d2);
$.post( "test2.php", { action: 'loss', data1: d1 ,data2: d2 })
.done(function( data ) {
$("#test2").html(data);
});
});

});
</script>

<input type='text' name='effect' id='effect' class="form-control">
<input type='text' name='chance' id='chance' class="form-control">
<div id='test2'></div>