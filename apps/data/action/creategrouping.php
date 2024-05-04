<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');
require_once('../model/class.xml.php');

// initial setup
$id = $_POST['e_id'];
$nama = strtolower($_POST['e_group_nama']);
$interval = strtolower($_POST['e_group_interval']);
$firstColumn = intval($_POST['e_group_kolom_pertama']) > 0 ? intval($_POST['e_group_kolom_pertama']) : 2;
$group_text = $_POST['e_group_text'];
$group_base = $_POST['e_group_base'];
$group_inc = $_POST['e_group_inc'];
$jenis = $_POST['e_group_jenis'];
$header_title = $_POST['e_group_header_title'];
$groupingdir = '../group/';

// check group name
if (empty($nama)) {
	$result['status'] = 'error';
	$result['message'] = 'Grouping tidak dapat tersimpan di dalam sistem.<br>Nama grouping tidak boleh kosong.';
	echo json_encode($result);
	return;
}

// process all grouping
$groups = [];
foreach ($group_text as $key => $val) {
	if (! empty($val)) {
		$temp['text'] = $val;
		$temp['base'] = intval($group_base[$key]) > 0 ? intval($group_base[$key]) : 1;
		$temp['inc'] = intval($group_inc[$key]) > 0 ? intval($group_inc[$key]) : 1;

		$groups[] = $temp;
	}
}

// check if grouping has field
if (count($groups) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Data grouping tidak ditemukan.<br>Tuliskan minimal 1 data grouping untuk menyimpan grouping.';
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
$sql = "INSERT INTO tab_grouping (nama_group, jenis_group, intv, h_kolom, f_kolom, id_laporan) VALUES ('$nama', '$jenis', '$interval', '', $firstColumn, $id)";
$id = $db->insert($sql);
if ($id == 0 || !$id) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
}

// cerate xml file
$groupingfile = $groupingdir . $id . '.xml';
$xml = new XML($groupingfile);

$xml->add_root('group');

$xml->add_node('nama');
$xml->add_node_text($nama);

$xml->add_node('jenis');
$xml->add_node_text($jenis);

$xml->add_node('interval');
$xml->add_node_text($interval);

$xml->add_node('h_kolom');
$xml->add_node_text($header_title);

$xml->add_node('f_kolom');
$xml->add_node_text($firstColumn);

foreach ($groups as $group) {
	if (! empty($group)) {
		$xml->add_node('data');
		$xml->get_node()->setAttribute('base',$group['base']);
		$xml->get_node()->setAttribute('inc',$group['inc']);
		$xml->add_node_text($group['text']);
	}
}

$xml->save_xml();


$result['status'] = 'success';
$result['message'] = 'Grouping laporan telah tersimpan di sistem.';
echo json_encode($result);