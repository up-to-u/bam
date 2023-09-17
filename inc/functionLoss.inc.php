<?php
session_start();

$ldap_server = 'ldap://smepc22231.smebank.local'; //'ldap://192.168.222.31';
$eservice_smtp_server = 'smeinf054.smebank.local'; //'192.168.155.54';	
$eservice_email = 'coreportal@smebank.co.th';
$eservice_email_password = '12QWaszx';
$eservice_email_name = 'coreportal@smebank.co.th';

$actual_path = (dirname($_SERVER['PHP_SELF']) != '\\') ? dirname($_SERVER['PHP_SELF']) : "";
$actual_link = $http . "" . $_SERVER['SERVER_NAME'] . "" . $actual_path . "/";

function month_name($m)
{
	switch ($m) {
		case 1:
			return 'มกราคม';
		case 2:
			return 'กุมภาพันธ์';
		case 3:
			return 'มีนาคม';
		case 4:
			return 'เมษายน';
		case 5:
			return 'พฤษภาคม';
		case 6:
			return 'มิถุนายน';
		case 7:
			return 'กรกฎาคม';
		case 8:
			return 'สิงหาคม';
		case 9:
			return 'กันยายน';
		case 10:
			return 'ตุลาคม';
		case 11:
			return 'พฤศจิกายน';
		case 12:
			return 'ธันวาคม';
	}
}

function approveName($id)
{
	global $connect;
	$sql = "SELECT name, surname FROM user WHERE user_id = '$id' ";
	$qry = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($qry)) {
		return $row['name']." ".$row['surname'];
	}
	return '';
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

function deptName($uid)
{
	global $connect;
	$sql = "SELECT department_name FROM department WHERE department_id = '$uid' ";
	$qry = mysqli_query($connect, $sql);
	if ($row = mysqli_fetch_array($qry)) {
		return $row['department_name'];
	}
	return '';
}


function checkLossList($h)
{
	include('inc/connect.php');
	$sql = "SELECT COUNT(*) AS num FROM loss_data_doc_list WHERE loss_data_doc_id='" . $h . "'";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return $row['num'];
}

function checkLossLevel($parameters)
{
    if ($parameters == 11) {
		return 1;
	} else if ($parameters == 12) {
		return 1;
	} else if ($parameters == 13) {
		return 2;
	} else if ($parameters == 14) {
		return 2;
	} else if ($parameters == 15) {
		return 2;
	} else if ($parameters == 21) {
		return 1;
	} else if ($parameters == 22) {
		return 1;
	} else if ($parameters == 23) {
		return 2;
	} else if ($parameters == 24) {
		return 2;
	} else if ($parameters == 25) {
		return 3;
	} else if ($parameters == 31) {
		return 2;
	} else if ($parameters == 32) {
		return 2;
	} else if ($parameters == 33) {
		return 3;
	} else if ($parameters == 34) {
		return 3;
	} else if ($parameters == 35) {
		return 3;
	} else if ($parameters == 41) {
		return 3;
	} else if ($parameters == 42) {
		return 3;
	} else if ($parameters == 43) {
		return 3;
	} else if ($parameters == 44) {
		return 4;
	} else if ($parameters == 45) {
		return 4;
	} else if ($parameters == 51) {
		return 3;
	} else if ($parameters == 52) {
		return 3;
	} else if ($parameters == 53) {
		return 3;
	} else if ($parameters == 54) {
		return 4;
	} else if ($parameters == 55) {
		return 4;
	} else {
		return 0;
	}
}
function incidenceType($h)
{
	include('inc/connect.php');
	$sql = "SELECT factor FROM loss_factor WHERE loss_factor_id='" . $h . "'";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
	return $row['factor'];
}





?>