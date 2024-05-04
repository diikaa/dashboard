<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');
require_once('../helper/protected_role.php');

// get datasource param
$id = $_GET['id'];

// // initial parameter
$role_page = '../admin.php?menu=6';
$protected_role = getProtectedRole();

// connect to database
$db = new DB();
$db->set_default_connection();

$sql = "SELECT p.*,
		count(nama_user) as jum_user
	FROM previlage p
	LEFT JOIN user u on p.id_previlage = u.prev_user
	WHERE id_previlage = $id
	GROUP BY p.nama_previlage
	ORDER BY id_previlage ASC";

$res = $db->select($sql);
$data = mysql_fetch_array($res);

// check if role exist in database
if (mysql_num_rows($res) == 0) {

	header('location:' . $role_page);
    exit();
}

// check if protected role
if (in_array($id, $protected_role)) {
	header('location:' . $role_page);
    exit();
}

// check if role is deleteable
// deleteable mean no user use this role
if ($data['jum_user'] > 0) {
	header('location:' . $role_page);
    exit();
}

// delete all role permission
$sql = "DELETE FROM otoritas WHERE id_previlage = '$id'";
$db->select($sql);

// delete role in DB
$sql = "DELETE FROM previlage WHERE id_previlage = '$id'";
$db->select($sql);

header('location:' . $role_page);
