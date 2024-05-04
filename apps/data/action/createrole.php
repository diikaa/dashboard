<?php

include '../helper/isadmin.php';

// include all file that needed
require_once('../model/class.db.php');
require_once('../helper/validation.php');

// catch all param
$nama = $_POST['e_nama'];

$error = isValidRole($nama);

// validate role input
if (! $error['status']) {
	$result['status'] = 'error';
	$result['message'] = 'Hak akses baru tidak dapat disimpan di database.' . buildErrorMessage($error['msg']);
	echo json_encode($result);
	return;
}

// insert into db
$db = new DB();
$db->set_default_connection();
$sql = "INSERT INTO previlage (nama_previlage) VALUES ('$nama')";	
$db->insert($sql);

$result['status'] = 'success';
$result['message'] = 'Hak akses berhasil disimpan di database.';
echo json_encode($result);


/**
 * role data validation 
 * @return boolean
 * 
 */

function isValidRole($nama)
{
	$error = ['status' => TRUE];

	$error['status'] = isRequired($error, $nama, 'Nama Hak Akses');
	$error['status'] = isUnique($error, $nama, 'previlage.nama_previlage.char', 'Nama Hak Akses') && $error['status'];

	return $error;
}