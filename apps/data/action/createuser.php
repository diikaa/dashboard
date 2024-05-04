<?php

include '../helper/isadmin.php';

// include all file that needed
require_once('../model/class.db.php');
require_once('../helper/validation.php');

// catch all param
$nik = $_POST['e_nik'];
$nama = $_POST['e_user'];
$pass = $_POST['e_pass'];
$role = $_POST['e_role'];

$error = isValidUserData($nik, $nama, $pass, $role);

// validate user input
if (! $error['status']) {
	$result['status'] = 'error';
	$result['message'] = 'Data user tidak dapat disimpan di database.' . buildErrorMessage($error['msg']);
	echo json_encode($result);
	return;
}

// filter password to MD5
$pass = md5($pass);

// insert into db
$db = new DB();
$db->set_default_connection();
$sql = "INSERT INTO user (nik, nama_user, pass_user, prev_user) VALUES ('$nik','$nama','$pass', $role)";	
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
 * @return boolean
 */
function isValidUserData($id, $name, $pass, $role)
{
	$error = ['status' => TRUE];

	$error['status'] = isRequired($error, $id, 'NIK');
	$error['status'] = isUnique($error, $id, 'user.nik.char', 'NIK') && $error['status'];
	$error['status'] = isRequired($error, $name, 'Nama User')  && $error['status'];
	$error['status'] = isRequired($error, $pass, 'Password')  && $error['status'];
	$error['status'] = isExists($error,  $role, 'previlage.id_previlage.int', 'Role')  && $error['status'];

	return $error;
}