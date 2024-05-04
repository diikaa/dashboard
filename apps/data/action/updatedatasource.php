<?php

include '../helper/isadmin.php';

// catch all param
$id = $_POST['e_id'];
$nama = strtolower($_POST['e_nama']);
$host = $_POST['e_host'];
$dt = $_POST['e_dt'];
$us = $_POST['e_user'];
$pw = $_POST['e_pass'];
$jenis = $_POST['e_jenis'];
$query = $_POST['e_sql'];

// replace query special char
$query = str_replace("\'","'",$query);
$query = str_replace('\"','"',$query);

// initiate array result;
$result = array();

// load database and set connection
require_once('../model/class.db.php');
$db = new DB();

// check if valid connection
$con = $db->set_connection($host, $us, $pw, $dt, $jenis);
if (! $con) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
}

// check if query valid
$res = $db->select($query);
if (! $res) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
}

// check if result is zero records
if ($db->get_query_rows($query) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Hasil query kosong sehingga tidak dapat disimpan sebagai datasource.<br>Pastikan hasil query memiliki minimal 1 record data.';
	echo json_encode($result);
	return;
}

// change db connection to database connection
$db->close_connection();
$db->set_default_connection();

// check if datasource nama is exists in database
if (! isExists($id)) {
	$result['status'] = 'error';
	$result['message'] = 'Datasource yang ingin diupdate tidak ditemukan di database.<br>Pastikan datasource yang ingin diupdate  tersimpan di database.';
	echo json_encode($result);
	return;
}

// check if data can strore in database
$id = updateDataSource($id, $host, $dt, $us, $pw, $jenis);

// create xml file
createXmlDataSource($res, $jenis, $nama);

$result['status'] = 'success';
$result['message'] = 'Datasource berhasil tersimpan di database';
echo json_encode($result);


/**
 * check if data source name is unique
 * @param  string  $nama
 * @return boolean
 */
function isExists($id)
{
	global $db;
	$sql = "SELECT * FROM tab_data WHERE id_data = $id";

	return $db->get_query_rows($sql) > 0;
}

/**
 * save data source to database
 * @param  string $nama
 * @param  string $host
 * @param  string $dt    database name
 * @param  string $user  
 * @param  string $pass  
 * @param  string $jenis MySQL | Oracle
 * @param  string $query 
 * @return integer
 */
function updateDataSource($id, $host, $dt, $user, $pass, $jenis) {
	global $db;

	$query = str_replace("'","\'",$query);
	$query = str_replace('"','\"',$query);
	$query = "UPDATE tab_data SET server = '$host', data_base = '$dt', pass = '$pass', user = '$user', jenis = '$jenis' WHERE id_data = $id";
	
	return $db->select($query);
}

/**
 * save data source to xml file
 * @param  resource $resource
 * @param  string $type     MySQL | Oracle
 * @param  string $nama     
 * @return void
 */
function createXmlDataSource($resource, $type, $nama) {
	require_once('../model/class.xml.php');
	$nama = '../data_resource/' . $nama . '.xml';
	
	$obj_xml = new XML($nama, $type);
	$obj_xml->resource_to_xml($resource);

	unset($obj_xml);
}