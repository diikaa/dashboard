<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial setup
$id = $_GET['id'];
$xmldir = '../header/';
$result = [];

$db = new DB();
$db->set_default_connection();

$sql = "SELECT * FROM laporan WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Laporan tidak ditemukan di dalam sistem.<br>Pastikan laporan tersimpan di dalam sistem';
	echo json_encode($result);
	return;
}
$report = mysql_fetch_assoc($db->select($sql));

$parent_file = $xmldir . $report['nama_laporan'] . '.xml';

if (file_exists($parent_file) ) {
	$xml = new XML($parent_file);

	$headers = $xml->get_xml_node('file');
	foreach($headers as $header) {
		if (file_exists($header->nodeValue)) unlink($header->nodeValue);
	}

	unlink($parent_file);
}

$result['status'] = 'success';
$result['message'] = 'Header laporan telah dihapus di dalam sistem.<br>Silahkan menyusun header baru untuk laporan.';
echo json_encode($result);
