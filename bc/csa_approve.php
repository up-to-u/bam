<?
$q_id = intval($_GET['q_id']);
if ($submit=='confirm_unlock') {

				$to_status = 0;
					$qx = add_history($qx, $confirm_unlock_id, $to_status, 'ส่งคืน ครั้งที่ '.$confirm_unlock_period.' โดยผู้อนุมัติ');
					if ($qx) {
	$sql = "SELECT * 
			$sql = "SELECT * FROM csa_questionnaire_topic WHERE parent_id = '0' AND mark_del = '0' ";

} else if ($submit=='save') {
			$qx = true;	
			$to_status = 2;
			$qx = add_history($qx, $confirm_csa_dep_id, $to_status, 'อนุมัติ โดย ผอ.');
			<table class='table table-hover'>


	$sql = "SELECT 
<div class="row">

	$sql = "SELECT 
				
<style>
<?
risk_mat = [
function risk_level_color(r) {
function cal_level(w) {
function checkform() {
function check_csa_factor_other() {
function check_csa_control_other() {
$(function () {


function toggle_risk_mat() {
function save_tab() {
	$('body').on('click', 'a[data-toggle=\'tab\']', function (e) {
	  $(this).tab('show');
function activaTab(tab){
<div class="row">
			<div class=''>
<div class='row'>

				</div>
				<div class="">
				<div class="form-group">
				</div>				
			<div class='col-lg-4 col-md-6 col-sm-10 col-xs-12'>
				</div>			

			
			
<?			
} else if ($view_dep_id>0 && $period>0) {
	$sql = "SELECT 
			if ($is_confirm==1) {
		<div class="tab-pane" id="tab3">
		<div class='row'>
<?}?>
		<button type='button' class="btn btn-primary" onClick="document.location='csa_approve.php?view_year=<?=$view_year?>&period=<?=$period?>'"><i class='fa fa-arrow-circle-left'></i> ย้อนกลับ</button>

			<br>
<?

if ($view_year==0) {
<div class='row'>


				

<?
<?
<?