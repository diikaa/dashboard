<?php

include '../helper/isadmin.php';

// include all file that needed
require_once('../model/class.db.php');
require_once('../helper/validation.php');

// catch all param
$nama = strtolower($_POST['e_nama']);
$id = $_POST['e_id'];

$db = new DB();
$db->set_default_connection();
$sql = "SELECT * FROM previlage WHERE id_previlage = $id";
$role = mysql_fetch_assoc($db->select($sql));

// if role not exists in DB
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Hak akses tidak ditemukan di sistem.<pr>Pastikan hak akses yang akan di edit tersimpan di sistem.';
	echo json_encode($result);
	return;
}

// check if name same as before
if ($nama == strtolower($role['nama_previlage'])) {
	$result['status'] = 'success';
	$result['message'] = 'Hak akses berhasil disimpan di database.';
	echo json_encode($result);
	return;
}

$error = isValidRole($id, $nama);

// validate role input
if (! $error['status']) {
	$result['status'] = 'error';
	$result['message'] = 'Hak akses tidak dapat disimpan di database.' . buildErrorMessage($error['msg']);
	echo json_encode($result);
	return;
}

// update into db
$sql = "UPDATE previlage SET nama_previlage = '$nama' WHERE id_previlage = $id";	
$db->select($sql);

$result['status'] = 'success';
$result['message'] = 'Hak akses berhasil disimpan di database.';
echo json_encode($result);


/**
 * user role validation 
 * @return boolean
 * 
 */

function isValidRole($id, $nama)
{
	$error = ['status' => TRUE];

	$error['status'] = isRequired($error, $nama, 'Hak Akses');
	$error['status'] = isUnique($error, $nama, 'previlage.nama_previlage.char', 'Hak Akses') && $error['status'];
	return $error;
}