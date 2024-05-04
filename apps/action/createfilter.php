<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial setup
$id = $_POST['e_id'];
$nama = strtolower($_POST['e_filter_nama']);
$jenis = strtolower($_POST['e_filter_jenis']);
$interval = strtolower($_POST['e_filter_interval']);
$filter_text = $_POST['e_filter_text'];
$filter_base = $_POST['e_filter_base'];
$filter_inc = $_POST['e_filter_inc'];
$filterdir = '../filter/';

// check filter name
if (empty($nama)) {
	$result['status'] = 'error';
	$result['message'] = 'Filter tidak dapat tersimpan di dalam sistem.<br>Nama filter tidak boleh kosong.';
	echo json_encode($result);
	return;
}

// process all filter
$filters = [];
foreach ($filter_text as $key => $val) {
	if (! empty($val)) {
		$temp['text'] = $val;
		$temp['base'] = intval($filter_base[$key]) > 0 ? intval($filter_base[$key]) : 1;
		$temp['inc'] = intval($filter_inc[$key]) > 0 ? intval($filter_inc[$key]) : 1;

		$filters[] = $temp;
	}
}

// check if filter has field
if (count($filters) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Data filter tidak ditemukan.<br>Tuliskan minimal 1 data filter untuk menyimpan filter.';
	echo json_encode($result);
	return;
}

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

// insert into database
$sql = "INSERT INTO tab_filter (nama_filter, jenis_filter, intv, id_laporan) VALUES ('$nama', '$jenis', '$interval', $id)";
$id = $db->insert($sql);
if ($id == 0 || !$id) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
}

// cerate xml file
$filterfile = $filterdir . $id . '.xml';
$xml = new XML($filterfile);

$xml->add_root('filter');

$xml->add_node('nama');
$xml->add_node_text($nama);

$xml->add_node('jenis');
$xml->add_node_text($jenis);

$xml->add_node('interval');
$xml->add_node_text($interval);

foreach ($filters as $filter) {
	if (! empty($filter)) {
		$xml->add_node('data');
		$xml->get_node()->setAttribute('base',$filter['base']);
		$xml->get_node()->setAttribute('inc',$filter['inc']);
		$xml->add_node_text($filter['text']);
	}
}

$xml->save_xml();


$result['status'] = 'success';
$result['message'] = 'Filter laporan telah tersimpan di sistem.';
echo json_encode($result);