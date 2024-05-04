<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial setup
$pivotdir = '../pivot/';
$result = [];
$id = $_POST['e_id'];
$datasource1 = $_POST['e_pivot_datasource_1'];

$pivotType = isset($_POST['e_pivot_2']) ? 2 : 1;
$datasource2 = ($pivotType == 2) ? $_POST['e_pivot_datasource_2'] : '';
$joinRule1 = ($pivotType == 2) ? $_POST['e_pivot_syarat_1'] : '';

$pivotType = isset($_POST['e_pivot_3']) ? 3 : $pivotType;
$datasource3 = ($pivotType == 3) ? $_POST['e_pivot_datasource_3'] : '';
$joinRule2 = ($pivotType == 3) ? $_POST['e_pivot_syarat_2'] : '';

$pivotData1 = $_POST['e_field_one'];
$pivotData2 = $_POST['e_field_two'];

// check report in DB
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

// check if datasource 1 selected
if ($datasource1 == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Pilih datasource 1 yang akan di pivot ke dalam laporan.';
	echo json_encode($result);
	return;
}

// check field size
$cell_size = 0;
foreach ($pivotData1 as $pivot) {
	if (! empty($pivot)) $cell_size++;
}
if ($cell_size == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Nama field data 1 tidak ditemukan.<br>Tuliskan minimal 1 nama field untuk menyimpan pivot data.';
	echo json_encode($result);
	return;
}

$datasources[] = getDatasourceName($datasource1);
$fieldData[] = $pivotData1;


// check if pivot 2 is active
if ($pivotType == 2 || $pivotType == 3) {

	// check if datasource 2 is selected
	if ($datasource2 == 0) {
		$result['status'] = 'error';
		$result['message'] = 'Pilih datasource 2 yang akan di pivot ke dalam laporan.';
		echo json_encode($result);
		return;
	}

	// check if rule defined or not
	if ($joinRule1 == '' || empty($joinRule1)) {
		$result['status'] = 'error';
		$result['message'] = 'Syarat penggabungan data belum didefinisikan.<br>Silahkan tuliskan syarat penggabungan data.';
		echo json_encode($result);
		return;
	}

	// check field size
	$cell_size = 0;
	foreach ($pivotData2 as $pivot) {
		if (! empty($pivot)) $cell_size++;
	}
	if ($cell_size == 0) {
		$result['status'] = 'error';
		$result['message'] = 'Nama field data 2 tidak ditemukan.<br>Tuliskan minimal 1 nama field untuk menyimpan pivot data.';
		echo json_encode($result);
		return;
	}

	$datasources[] = getDatasourceName($datasource2);
	$fieldData[] = $pivotData2;
	$joinRule[] = $joinRule1;
}


// check if pivot 3 is active
if ($pivotType == 3) {

	// check if datasource 3 is selected
	if ($datasource3 == 0) {
		$result['status'] = 'error';
		$result['message'] = 'Pilih datasource 3 yang akan di pivot ke dalam laporan.';
		echo json_encode($result);
		return;
	}

	// check if rule defined or not
	if ($joinRule2 == '' || empty($joinRule2)) {
		$result['status'] = 'error';
		$result['message'] = 'Syarat penggabungan data belum didefinisikan.<br>Silahkan tuliskan syarat penggabungan data.';
		echo json_encode($result);
		return;
	}

	$datasources[] = getDatasourceName($datasource3);
	$joinRule[] = $joinRule2;
}


// save pivot into sistem
$pivotFile = $pivotdir . $report['nama_laporan'] . '.xml';
$xml = new XML($pivotFile);

$xml->add_root('pivot');
$xml->add_node('tipe');
$xml->add_node_text($pivotType);

if ($pivotType == 1) {
	$xml->add_node('query');
	$xml->add_node_text($datasources[0]);
	$data = $fieldData[0];
} else {
	foreach ($datasources as $key => $datasource) {
		$xml->add_node('query' . ($key + 1) );
		$xml->add_node_text($datasource);
	}

	$xml->add_node('syarat');
	$xml->add_node_text(implode(';', $joinRule));
	$data = $fieldData;
}

$xml->pivot_to_xml($pivotType, $data);

$result['status'] = 'success';
$result['message'] = 'Pivot laporan berhasil disimpan ke dalam sistem.';
echo json_encode($result);


function getDatasourceName($id)
{
	global $db;

	$sql = "SELECT * FROM tab_data WHERE id_data = $id";
	$result = mysql_fetch_assoc($db->select($sql));
	
	return $result['nama_data'];
}