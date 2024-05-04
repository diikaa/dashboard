<?php

include '../helper/isadmin.php';

// include all file that needed
require_once('../model/class.db.php');
require_once('../helper/validation.php');

// catch all param
$nik = $_POST['e_id'];
$nama = $_POST['e_user'];
$reset_pass = isset($_POST['e_reset_password']) ? TRUE : FALSE;
$pass = $_POST['e_pass'];
$role = $_POST['e_role'];

$error = isValidUserData($nik, $nama, $pass, $role, $reset_pass);

// validate user input
if (! $error['status']) {
	$result['status'] = 'error';
	$result['message'] = 'Data user tidak dapat disimpan di database.' . buildErrorMessage($error['msg']);
	echo json_encode($result);
	return;
}

// filter password to MD5
$pass = $reset_pass ? md5($pass) : '';

// update into db
$db = new DB();
$db->set_default_connection();
$sql = $reset_pass ? "UPDATE user SET nama_user = '$nama', prev_user = $role, user_pass = '$pass' WHERE nik = '$nik'" : "UPDATE user SET nama_user = '$nama', prev_user = $role WHERE nik = '$nik'";
$db->insert($sql);

$result['status'] = 'success';
$result['message'] = 'Data user berhasil disimpan di database';
echo json_encode($result);


/**
 * user data validation
 * @param  string  $id   
 * @param  string  $name 
 * @param  string  $pass 
 * @param  int  $role 
 * @param  boolean $reset
 * @return boolean
 */
function isValidUserData($id, $name, $pass, $role, $reset)
{
	$error = ['status' => TRUE];

	$error['status'] = isRequired($error, $id, 'NIK');
	$error['status'] = isExists($error, $id, 'user.nik.char', 'NIK') && $error['status'];
	$error['status'] = isRequired($error, $name, 'Nama User')  && $error['status'];
	
	if ($reset) {
		$error['status'] = isRequired($error, $pass, 'Password')  && $error['status'];
	}
	
	$error['status'] = isExists($error,  $role, 'previlage.id_previlage.int', 'Role')  && $error['status'];

	return $error;
}