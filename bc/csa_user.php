<?

$print_q_id = intval($_GET['print_q_id']);
			$to_status = $row2['csa_department_status_id'];

				if (($period==1 && $is_confirm2==1) || ($period==2 && $is_confirm==1)) {







			$insert_id = mysqli_insert_id($connect);


<link href="jquery-ui-1.12.0/jquery-ui.css" rel="stylesheet">
var maxc = 1000000000;







<?	}?>






			<button type='button' class="btn btn-default" data-toggle="modal" href="#basic"><i class='fa fa-search'></i> ดู Matrix</button>






var maxc = 1000000000;

];

function risk_level_color(r) {
	switch (r) {
		case 0: return '';
		case 1: return '#00ff00';
	}
}
function risk_level_name(r) {
	switch (r) {
		case 0: return '';
		case 1: return 'ต่ำ';
		case 2: return 'ปานกลาง';
		case 3: return 'สูง';
		case 4: return 'สูงมาก';
	}
}
function risk_level_acceptable(r) {
}
function risk_level_acceptable_color(r) {

function cal_level(w) {
	var i = parseInt($('#csa_impact_id'+w).val())-1;
	var j = parseInt($('#csa_likelihood_id'+w).val())-1;
		$('#risk_level_'+w+'_div').css('background-color', risk_level_color(lv));
		$('#risk_level_'+w+'_div').html(risk_level_name(lv));
		$('#risk_level_'+w+'_txt').val(lv);




$(function () {
	save_tab();
	$('#job_function_id').change(function() {
	$('#csa_impact_id1, #csa_likelihood_id1').change(function() {
		cal_level("1");
	}).change();
	$('#csa_impact_id2, #csa_likelihood_id2').change(function() {
		cal_level("2");
	}).change();





function toggle_risk_mat() {

function save_tab() {






























