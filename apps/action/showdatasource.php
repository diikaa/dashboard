<?php

include '../helper/isadmin.php';

// catch all parameter
// set xml file location
$xmlfile = $_POST['datasource'] . '.xml';
$xmldir  = '../data_resource/';
$xmlfile = $xmldir . $xmlfile;

$result = array();

if (! file_exists($xmlfile)) {
	$result['status'] = 'error';
	$result['message'] = 'Terjadi kesalahan di dalam aplikasi.<br>Datasource tidak ditemukan di dalam aplikasi';
	echo json_encode($result);
	return;
}

$records = simplexml_load_file($xmlfile);
$datasources = array();

// read the xml attribute
$row = $records->record[0];
$head = array();
foreach ($row->children() as $attribute) {
	$head[] = $attribute->getName();
}
$datasources[] = $head;

// read xml content
$index = 0;
foreach ($records->children() as $record) {
	$index++;
	foreach ($head as $attr) {
		$datasources[$index][] = $record->$attr->__toString();
	}
}

$result['status'] = 'success';
$result['message'] = $datasources;
echo json_encode($result);

