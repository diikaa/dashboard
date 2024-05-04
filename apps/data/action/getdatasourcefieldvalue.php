<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');

// set up all param
$id = $_POST['id'];
$field = $_POST['field'];
$base = intval($_POST['base']) > 0 ? intval($_POST['base']) : 1;
$inc = intval($_POST['inc']) > 0 ? intval($_POST['inc']) : 1;
$xmldir = '../data_resource/';
$result = [];

// check if field not selected
if ($field == '0') {
	$result['status'] = 'error';
	$result['message'] = 'Nama field datasource tidak ditemukan.<br>Pastikan memilih nama field datasource yang sesuai.';
	echo json_encode($result);
	return;
}

$db = new DB();
$db->set_default_connection();

// check if datasource exists in database
$sql = "SELECT * FROM tab_data WHERE id_data = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Datasource tidak ditemukan di dalam sistem.<br>Pastikan datasource tersimpan di dalam sistem.';
	echo json_encode($result);
	return;
}

$datasource = mysql_fetch_assoc($db->select($sql));
$xmlfile = $xmldir . $datasource['nama_data'] . '.xml';

// check if datasource xml exists
if (! file_exists($xmlfile)) {
	$result['status'] = 'error';
	$result['message'] = 'Datasource tidak ditemukan di dalam sistem.<br>Pastikan datasource tersimpan di dalam sistem.';
	echo json_encode($result);
	return;
}

// read the xml attribute value
$records = simplexml_load_file($xmlfile);
$data = [];
foreach ($records->record as $record) {
	$data[] = $record->$field->__toString();
}

$result['status'] = 'success';
$result['filters'] = $data;
$result['base'] = $base;
$result['inc'] = $inc;
echo json_encode($result); 