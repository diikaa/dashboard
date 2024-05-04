<?php
error_reporting(0);

// required file
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial setup
date_default_timezone_set('Asia/Jakarta');
$pivotdir = '../pivot/';
$status = isset($_GET['ajax']) ? intval($_GET['ajax']) : 0;

$db = new DB();
$db->set_default_connection();

$sql = "SELECT * FROM laporan ORDER BY id_laporan";
if ($db->get_query_rows($sql) == 0) {
	if ($status == 1) {
		$result['status'] = 'error';
		$result['message'] = 'Laporan tidak ditemukan di dalam sistem.<br>Pastikan laporan tersimpan di dalam sistem';
		echo json_encode($result);
	}

	exit();
}
$reports = $db->select($sql);

// start tu update all report
$reporterror = [
	'status' => FALSE,
	'message' => ''
];
while ($report = mysql_fetch_assoc($reports)) {
	$pivotfile = $pivotdir . $report['nama_laporan'] . '.xml';

	if (file_exists($pivotfile)) {
		$tmp = updateReport($report['id_laporan'], $pivotfile, $report['nama_laporan']);
		if ($tmp != '') {
			$reporterror['status'] = TRUE;
			$reporterror['message'] .= $tmp;
		} else {
			$reporterror['status'] = $reporterror['status'] || FALSE;
			$reporterror['message'] .= "Laporan ". ucwords($report['nama_laporan']) . ' berhasil di update.<br>';
		}
	} else {
		$reporterror['status'] = $reporterror['status'] || TRUE;
		$reporterror['message'] .= '<span class="text-danger">Laporan '. ucwords($report['nama_laporan']) . ' tidak dapat di update.</span><br>';
	}
}

if ($status == 1) {
	$reporterror['status'] = $reporterror['status'] ? 'error' : 'success';
	echo json_encode($reporterror);
}



function updateReport($id, $pivotfile, $reportname)
{
	$xml = simplexml_load_file($pivotfile);
	$pivotType = intval($xml->tipe->__toString());

	$datasources = [];
	if ($pivotType == 1) {
		$datasources[] = $xml->query->__toString();
	} else {
		for ($i = 1; $i <= $pivotType ; $i++) {
			$query = 'query' . $i;
			$datasources[] = $xml->$query->__toString();
		}
	}

	$tmp = '';
	foreach ($datasources as $datasource) {
		if (! updateDatasource($datasource, $id)) {
			$tmp .= '<span class="text-danger">Datasource '. $datasource . ' untuk Laporan ' . ucwords($reportname) . ' tidak dapat diupdate.</span><br>';
		}
	}

	return $tmp;
}

function updateDatasource($ds, $id_laporan)
{
	global $db;

	// check if datasource exists
	$sql = "SELECT * FROM tab_data WHERE nama_data = '$ds'";
	if ($db->get_query_rows($sql) == 0) return FALSE;
	$datasource = mysql_fetch_assoc($db->select($sql));

	// check if valid connection
	$host = $datasource['server'];
	$us = $datasource['user'];
	$pw = $datasource['pass'];
	$dt = $datasource['data_base'];
	$jenis = $datasource['jenis'];
	$query = $datasource['query'];

	$con = $db->set_connection($host, $us, $pw, $dt, $jenis);
	if (! $con) return FALSE;

	// check if query valid
	$resource = $db->select($query);
	if (! $resource) return FALSE;

	// change db connection to database connection
	$db->close_connection();
	$db->set_default_connection();

	$date = date('Y-m-d');
	$sql = "UPDATE laporan SET modif = '$date' WHERE id_laporan = $id_laporan";
	$db->select($sql);

	// create xml file
	$dsFile = '../data_resource/'. $ds . '.xml';

	$xml = new XML($dsFile, $jenis);
	$xml->resource_to_xml($resource);
	unset($xml);

	return TRUE;
}