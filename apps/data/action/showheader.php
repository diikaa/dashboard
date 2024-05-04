<?php

include '../helper/isadmin.php';

// required class 
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial value
$id = $_POST['id'];
$headedir = '../header/';
$result = array();

// set DB connection
$db = new DB();
$db->set_default_connection();

// check if report exists in DB
$sql = "SELECT * FROM laporan WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Laporan tidak ditemukan di sistem.<br>Silahkan pilih laporan  yang ada di dalam sistem.';
	echo json_encode($result);
	return;
}

// get report name and check if header exist or not
$report = mysql_fetch_assoc( $db->select($sql) );
$headerFile = $headedir . $report['nama_laporan'] . '.xml';

if (! file_exists($headerFile)) {
	$result['status'] = 'error';
	$result['message'] = 'Header laporan tidak ditemukan di sistem.<br>Silahkan menyusun header untuk laporan '. ucwords($report['nama_laporan']);
	echo json_encode($result);
	return;
}

// load header
$xml = new XML($headerFile);
$head = '<thead>' . $xml->print_xml_header() . '</thead>';

$result['status'] = 'success';
$result['message'] = '<table id="table-report" class="table table-primary table-bordered table-striped table-hover">' . $head . '</table>';
$result['report'] = ucwords($report['nama_laporan']);
echo json_encode($result);