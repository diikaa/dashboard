<?php

include '../helper/isadmin.php';

// catch all parameter
$host = $_POST['e_host'];
$dt = $_POST['e_dt'];
$us = $_POST['e_user'];
$pw = $_POST['e_pass'];
$jenis = $_POST['e_jenis'];
$query = $_POST['e_sql'];

$result = array();

// include DB class
require_once('../model/class.db.php');
$db = new DB;

// try connect to database
$status = $db->set_connection($host, $us, $pw, $dt, $jenis);

// if connection not valid then return error massage
if (! $status) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
}

$query = str_replace("\'","'",$query);
$query = str_replace('\"','"',$query);
$res = $db->dump_query($query, TRUE);

// if invalid result then return error message
if (! $res) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
}

// if success
$result['status'] = 'success';
$result['message'] = $res;
echo json_encode($result);