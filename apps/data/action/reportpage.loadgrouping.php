<?php

error_reporting(0);

// catch all parameter
// set xml file location
$xmlfile = $_POST['e_grouping'] . '.xml';
$xmldir  = '../group/';
$xmlfile = $xmldir . $xmlfile;

$result = array();

if (! file_exists($xmlfile)) {
	$result['status'] = 'error';
	$result['message'] = 'Terjadi kesalahan di dalam aplikasi.<br>Grouping tidak ditemukan di dalam aplikasi';
	echo json_encode($result);
	return;
}

$records = simplexml_load_file($xmlfile);
$groups = array();

// read the xml attribute
$groups['id'] = $_POST['e_grouping'];
$groups['type'] = $records->jenis->__toString();
$groups['interval'] = $records->interval->__toString();
$groups['firstColumn'] = intval($records->f_kolom->__toString());
$groups['columnName'] = $records->h_kolom->__toString();

foreach ($records->data as $group_item) {
	$tmp = [ 'text' => $group_item->__toString() ];

	foreach ( $group_item->attributes() as $key => $val) {
		$tmp[ $key ] = intval($val->__toString());
	}

	$groups['item'][] = $tmp;
}

$result['status'] = 'success';
$result['message'] = $groups;
echo json_encode($result);