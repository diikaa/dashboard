<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');

// intial setup
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$filterdir = '../filter/';
$result = [];

$db = new DB();
$db->set_default_connection();

$sql = "SELECT * FROM tab_filter f
	JOIN laporan l ON f.id_laporan = l.id_laporan
	WHERE id_filter = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Filter tidak ditemukan di dalam sistem.<br>Pastikan filter tersimpan di dalam sistem.';
	echo json_encode($result);
	return;
}
$filter = mysql_fetch_assoc($db->select($sql));

// delete xml file
$filterFile = $filterdir . $filter['id_filter'] . '.xml';
if (file_exists($filterFile)) unlink($filterFile);

// delete filter from database
$sql = "DELETE FROM tab_filter WHERE id_filter = $id";
$db->select($sql);


$result['status'] = 'success';
$result['message'] = 'Filter <span class="text-danger">'. ucwords($filter['nama_filter']) .'</span> untuk Laporan <span class="text-danger">' . ucwords($filter['nama_laporan']) . '</span> telah terhapus dari sistem.';
echo json_encode($result);