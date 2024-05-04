<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');

// set up all param
$id = $_POST['id'];
$xmldir = '../data_resource/';
$result = [];

$db = new DB();
$db->set_default_connection();

$sql = "SELECT * FROM tab_data WHERE id_data = $id";

if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	echo json_encode($result);
	return;
}

$datasource = mysql_fetch_assoc($db->select($sql));
$xmlfile = $xmldir . $datasource['nama_data'] . '.xml';

if (! file_exists($xmlfile)) {
	$result['status'] = 'error';
	echo json_encode($result);
	return;
}

// read the xml attribute
$records = simplexml_load_file($xmlfile);
$row = $records->record[0];
$fields = [];
foreach ($row->children() as $attribute) {
	$fields[] = $attribute->getName();
}

$result['status'] = 'success';
$result['fields'] = $fields;
echo json_encode($result);