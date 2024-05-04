<?php

include '../helper/isadmin.php';

//required file
require_once('../model/class.db.php');

// initial setup
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$pivotdir = '../pivot/';
$result = [];

$db = new DB();
$db->set_default_connection();

// check if report exists
$sql = "SELECT * FROM laporan WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	echo json_encode($result);
	return;
}
$report = mysql_fetch_assoc($db->select($sql));

// check if pivot exists
$pivotfile = $pivotdir . $report['nama_laporan'] . '.xml';
if (! file_exists($pivotfile)) {
	$result['status'] = 'error';
	echo json_encode($result);
	return;
}

// read all datasources
$sql = "SELECT id_data, nama_data FROM tab_data ORDER BY id_data ASC";
$tmp = $db->select($sql);
$dslist = [];
while ($ds = mysql_fetch_assoc($tmp)) {
	$dslist[$ds['nama_data']] = $ds['id_data'];
}

// read pivot xml
$xml = simplexml_load_file($pivotfile);
$pivottype = intval($xml->tipe->__toString());

// read datasources
$datasources = [];
if ($pivottype == 1) {
	$dsname = $xml->query->__toString();
	if (isset($dslist[$dsname])) $datasources[1] = $dslist[$dsname];
} else {
	for ($i = 1; $i <= $pivottype; $i++) {
		$query = 'query' . $i;
		$dsname = $xml->$query->__toString();
		if (isset($dslist[$dsname])) $datasources[$i] = $dslist[$dsname];
	}
}
$result['pivot']['type'] = $pivottype;
$result['pivot']['query'] = $datasources;

// load join rule
$rule = [];
if ($pivottype > 1) {
	$tmp = explode(';', $xml->syarat->__toString());
	foreach ($tmp as $x) {
		$rule[] = $x;
	}
}
$result['pivot']['rule'] = $rule;

// read all data
$fields = [];
if ($pivottype == 1) {
	foreach ($xml->data as $data) {
		$fields[0][] = $data->__toString();
	}
} else {
	foreach ($xml->data_1->data as $data) {
		$fields[0][] = $data->__toString();
	}

	foreach ($xml->data_2->data as $data) {
		$fields[1][] = $data->__toString();
	}
}
$result['pivot']['data'] = $fields;
$result['status'] = 'success';


echo json_encode($result);