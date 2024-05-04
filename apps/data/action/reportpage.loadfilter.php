<?php

error_reporting(0);

// catch all parameter
// set xml file location
$xmlfile = $_POST['e_filter'] . '.xml';
$xmldir  = '../filter/';
$xmlfile = $xmldir . $xmlfile;

$result = array();

if (! file_exists($xmlfile)) {
	$result['status'] = 'error';
	$result['message'] = 'Terjadi kesalahan di dalam aplikasi.<br>Filter tidak ditemukan di dalam aplikasi';
	echo json_encode($result);
	return;
}

$records = simplexml_load_file($xmlfile);
$filters = array();

// read the xml attribute
$filters['id'] = $_POST['e_filter'];
$filters['type'] = $records->jenis->__toString();
$filters['interval'] = $records->interval->__toString();

foreach ($records->data as $filter_item) {
	$tmp = [ 'text' => $filter_item->__toString() ];

	foreach ( $filter_item->attributes() as $key => $val) {
		$tmp[ $key ] = intval($val->__toString());
	}

	$filters['item'][] = $tmp;
}

$result['status'] = 'success';
$result['message'] = $filters;
echo json_encode($result);