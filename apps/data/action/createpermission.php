<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');

// initial setup
$id = $_POST['e_role_id'];
$reports = $_POST['e_laporan'];
$result = [];

$db = new DB();
$db->set_default_connection();

// delete all permissiion
$sql = "DELETE FROM otoritas WHERE id_previlage = $id";
$db->select($sql);

// insert new permission
foreach ($reports as $report) {
	$sql = "INSERT INTO otoritas (id_previlage, id_laporan) VALUES ($id, $report)";
	$db->insert($sql);
}

$result['status'] = 'success';
$result['message'] = 'Hak akses laporan berhasil tersimpan di dalam sistem.';
echo json_encode($result);