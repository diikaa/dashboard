<?php

include '../helper/isadmin.php';

// catch all parameter
$host = $_POST['e_host'];
$dt = $_POST['e_dt'];
$us = $_POST['e_user'];
$pw = $_POST['e_pass'];
$jenis = $_POST['e_jenis'];
$tab= $_POST['e_tabel'];

$result = array();

// include DB class and try connect
require_once('../model/class.db.php');
$db = new DB;
$status = $db->set_connection($host, $us, $pw, $dt, $jenis);

// if connection not valid then return error massage
if (! $status) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
}

// get table structure base on database type
if ('MySQL' == $jenis) {
	$query = "DESCRIBE ".$tab;
} else if ('Oci' == $jenis) {
	$query = "SELECT column_name AS ".'"NAME"'. 
		",nullable AS ".'"NULL"'. 
		",CONCAT(CONCAT(CONCAT(data_type,'('),data_length),')') AS ".'"TYPE"'. 
		"FROM user_tab_columns 
		WHERE table_name='".$tab."'";
}

// if no table structure
$res = $db->dump_query($query);
if (! $res) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
} 

// success
$result['status'] = 'success';
$result['message'] = $res;
echo json_encode($result);