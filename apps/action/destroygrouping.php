<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');

// intial setup
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$groupdir = '../group/';
$result = [];

$db = new DB();
$db->set_default_connection();

$sql = "SELECT * FROM tab_grouping g
	JOIN laporan l ON g.id_laporan = l.id_laporan
	WHERE id_group = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Grouping tidak ditemukan di dalam sistem.<br>Pastikan grouping tersimpan di dalam sistem.';
	echo json_encode($result);
	return;
}
$group = mysql_fetch_assoc($db->select($sql));

// delete xml file
$groupFile = $groupdir . $group['id_group'] . '.xml';
if (file_exists($groupFile)) unlink($groupFile);

// delete group from database
$sql = "DELETE FROM tab_grouping WHERE id_group = $id";
$db->select($sql);


$result['status'] = 'success';
$result['message'] = 'Grouping <span class="text-danger">'. ucwords($group['nama_group']) .'</span> untuk Laporan <span class="text-danger">' . ucwords($group['nama_laporan']) . '</span> telah terhapus dari sistem.';
echo json_encode($result);