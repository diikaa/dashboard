<?php

error_reporting(0);
session_start();

// required class 
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial value
$id = $_POST['e_id']; 
$result = array();

// set DB connection
$db = new DB();
$db->set_default_connection();

// check if report exists in DB
$role = isset($_SESSION['prev']) ? $_SESSION['prev'] : 0;
$sql = "SELECT l.* 
		FROM laporan l
		JOIN otoritas o on l.id_laporan = o.id_laporan
		WHERE o.id_previlage IN (2, $role)
		AND l.id_laporan = $id";

// if admin then load all report
$sql = isset($_SESSION['status']) && $_SESSION['status'] == 'admin' ? "SELECT * FROM laporan WHERE id_laporan = $id" : $sql;

if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Laporan tidak ditemukan di sistem.<br>Silahkan pilih laporan  yang ada di dalam sistem.';
	echo json_encode($result);
	return;
}

// get report name
$report = mysql_fetch_assoc( $db->select($sql) );
$header_file = '../header/' . $report['nama_laporan'] . '.xml';
$pivot_file = '../pivot/' . $report['nama_laporan'] . '.xml';

// check if header and pivot exists
if (! file_exists($header_file) || ! file_exists($pivot_file)) {
	$result['status'] = 'error';
	$result['message'] = 'Header dan Pivot laporan tidak ditemukan di sistem.<br>Silahkan menghubungi administrator untuk memastikan laporan telah tersusun.';
	echo json_encode($result);
	return;
}

// print table
$xml = new XML($header_file);

$head = '<thead>' . $xml->print_xml_header() . '</thead>';
	
$xml->set_name($pivot_file);
$type = $xml->get_single_node_val( $xml->get_xml_node('tipe') );
$body = '<tbody>' . $xml->print_xml_pivot( $type ) . '</tbody>';


$result['filter'] = loadFilter($id);
$result['grouping'] = loadGrouping($id);
$result['status'] = 'success';
$result['last_update'] = $report['modif'];
$result['report'] = ucwords($report['nama_laporan']);
$result['message'] = '<table id="table-report" class="table table-primary table-bordered table-striped table-hover">' . $head . $body . '</table>';
echo json_encode($result);



function loadFilter($id)
{
	global $db;

	$sql = "SELECT f.*
		FROM laporan l
		JOIN tab_filter f USING(id_laporan)
		WHERE l.id_laporan = $id";

	$result = [
		'status' => $db->get_query_rows($sql)
	];
	
	if ( $result['status'] == 0 ) return $result;

	$filters = $db->select($sql);
	while ($filter = mysql_fetch_assoc($filters)) {
		$result['filters'][ $filter['id_filter'] ] = ucwords( $filter['nama_filter'] );
	}

	return $result;
}

function loadGrouping($id)
{
	global $db;

	$sql = "SELECT g.*
		FROM laporan l
		JOIN tab_grouping g USING(id_laporan)
		WHERE l.id_laporan = $id";

	$result = [
		'status' => $db->get_query_rows($sql)
	];

	if ( $result['status'] == 0 ) return $result;

	$groupings = $db->select($sql);
	while ($grouping = mysql_fetch_assoc($groupings)) {
		$result['groupings'][ $grouping['id_group'] ] = ucwords( $grouping['nama_group'] );
	}

	return $result;
}