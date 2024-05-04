<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');

// catch all parameter
$name = $_POST['e_laporan'];
$result = [];

// check if report name is empty
if (trim($name) == '' || empty($name)) {
	$result['status'] = 'error';
	$result['message'] = 'Nama laporan tidak boleh kosong.<br>Tuliskan nama laporan yang ingin ditambahkan ke dalam sistem.';
	echo json_encode($result);
	return;
}

$db  = new DB();
$db->set_default_connection();

// check if duplicate name
$name = strtolower($name);
$sql = "SELECT * FROM laporan WHERE nama_laporan = '$name'";
if ($db->get_query_rows($sql) != 0) {
	$result['status'] = 'error';
	$result['message'] = 'Nama laporan sudah ada di dalam sistem.<br>Tuliskan nama laporan yang unik agar dapat disimpan di dalam sistem.';
	echo json_encode($result);
	return;
}

// store report to database
date_default_timezone_set('Asia/Jakarta');
$date = date('Y-m-d');
$sql = "INSERT INTO laporan (nama_laporan, modif) VALUES ('$name', '$date')";	
$id = $db->insert($sql);

$result['status'] = 'success';
$result['message'] = 'Laporan telah disimpan di dalam sistem.<br>Silahkan lanjutkan dengan mengatur header, pivot, filter dan grouping laporan.';
$result['laporan'] = $id;
echo json_encode($result);
