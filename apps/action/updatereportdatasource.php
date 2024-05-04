<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial setup
date_default_timezone_set('Asia/Jakarta');
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$pivotdir = '../pivot/';

$db = new DB();
$db->set_default_connection();

// check if report exists
$sql = "SELECT * FROM laporan WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Laporan tidak ditemukan di dalam sistem.<br>Pastikan laporan tersimpan di dalam sistem';
	echo json_encode($result);
	return;
}
$report = mysql_fetch_assoc($db->select($sql));

// check if pivot file exists
$pivotFile = $pivotdir . $report['nama_laporan'] . '.xml';
if (! file_exists($pivotFile)) {
	$result['status'] = 'error';
	$result['message'] = 'Data pivot laporan tidak ditemukan.<br>Silahkan susun data pivot laporan sebelum melakukan proses update.';
	echo json_encode($result);
	return;
}

// read xml file
$xml = simplexml_load_file($pivotFile);
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

// update datasource
$error = FALSE;
$result['message'] = '';
foreach ($datasources as $datasource) {
	$temp = updateDatasource($datasource, $id);
	$error = $error || $temp['error'];
	$result['message'] .= $temp['error'] ? '<span class="text-danger">' . $temp['message'] . '</span><br>' : $temp['message'] . '<br>';
}

$result['status'] = $error ? 'error' : 'success';
echo json_encode($result);



function updateDatasource($ds, $id_laporan)
{
	global $db;
	$result = [];

	// check if datasource exists
	$sql = "SELECT * FROM tab_data WHERE nama_data = '$ds'";
	if ($db->get_query_rows($sql) == 0) {
		$result['error'] = TRUE;
		$result['message'] = 'Datasource : ' . $ds . ' tidak dapat diupdate karena tidak ditemukan di sistem.';
		return $result;
	}
	$datasource = mysql_fetch_assoc($db->select($sql));

	// check if valid connection
	$host = $datasource['server'];
	$us = $datasource['user'];
	$pw = $datasource['pass'];
	$dt = $datasource['data_base'];
	$jenis = $datasource['jenis'];
	$query = $datasource['query'];

	$con = $db->set_connection($host, $us, $pw, $dt, $jenis);
	if (! $con) {
		$result['error'] = TRUE;
		$result['message'] = 'Datasource : ' . $ds . ' tidak dapat diupdate karena tidak bisa terhubung dengan server.';
		return $result;
	}

	// check if query valid
	$resource = $db->select($query);
	if (! $resource) {
		$result['error'] = TRUE;
		$result['message'] = 'Datasource : ' . $ds . ' tidak dapat diupdate karena kesalahan query data.';
		return $result;
	}

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

	$result['error'] = FALSE;
	$result['message'] = 'Datasource : ' . $ds . ' berhasil diupdate.';
	return $result;
}