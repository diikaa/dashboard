<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.xml.php');
require_once('../model/class.db.php');

// initial param
$result = array();
$headerdir = '../header/';
$id = $_POST['e_id'];
$type = isset($_POST['e_header_dinamis']) ? 1 : NULL;
$datasource = !is_null($type) && isset($_POST['e_header_datasource']) ? $_POST['e_header_datasource'] : '';
$row_index = isset($_POST['e_header_idx_baris']) ? $_POST['e_header_idx_baris'] : 1;
$header_text = $_POST['e_header_text'];
$header_row = $_POST['e_header_row'];
$header_col = $_POST['e_header_col'];

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

// get datasource name
$sql = "SELECT * FROM tab_data WHERE id_data = $datasource";
if ($db->get_query_rows($sql) > 0) {
	$tmp = mysql_fetch_assoc($db->select($sql));
	$datasource = $tmp['nama_data'];
}

// process dynamic field to xml class data
// format [ text@@row_span@@col_span ]
$cells = [];
$cell_size = 0;
foreach ($header_text as $key => $cell) {
	if (! empty($cell)) {
		$row = is_int(intval($header_row[$key])) ? $header_row[$key] : 1;
		$col = is_int(intval($header_col[$key])) ? $header_col[$key] : 1;
		$cells[] = $cell . '@@' . $row . '@@' . $col;
		$cell_size++;
	}
}

// if header empty then return error
if ($cell_size == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Header tidak dapat disimpan di dalam sistem.<br>Tuliskan minimal satu header untuk menyimpan ke dalam sistem.';
	echo json_encode($result);
	return;
}

// process xml file
$parent_file = $headerdir . $report['nama_laporan'] . '.xml';

// check if header already exist or not
// save xml file
if (file_exists($parent_file)) {
	$xml = new XML($parent_file);
	$idx = $xml->get_xml_node('file')->length + 1;
	$header_file = $headerdir . $report['nama_laporan'] . '_' . $idx . '.xml';
	$xml->set_name($header_file);
	$exist = TRUE;
} else {
	$header_file = $headerdir . $report['nama_laporan'] . '_1.xml';
	$xml = new XML($header_file);
	$exist = FALSE;
}

$xml->header_to_xml($cells, $row_index, $datasource);
$xml->set_name($parent_file);
$xml->file_to_xml($header_file, $exist);

$result['status'] = 'success';
$result['message'] = 'Header laporan berhasil disimpan ke dalam sistem.';
echo json_encode($result);