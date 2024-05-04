<?php

include '../helper/isadmin.php';

// load database and set connection
require_once('../model/class.db.php');

// catch all param
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

// is DB connection valid or not
$db = new DB();
$con = $db->set_connection($host,$us,$pw,$dt,$jenis);
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

// check data source name validity
if (empty($nama) || ! isValidName($nama)) {
	$result['status'] = 'error';
	$result['message'] = 'Nama datasource harus diisi.<br>Nama datasource hanya mengandung karakter huruf dan garis bawah.<br>Periksa kembali nama data source yang dimasukkan';
	echo json_encode($result);
	return;
}

// check if data source name is unique
if (! isUniqueName($nama)) {
	$result['status'] = 'error';
	$result['message'] = 'Nama datasource sudah ada di database.<br>Ganti nama data source agar menjadi unik.';
	echo json_encode($result);
	return;
}

// check if data can strore in database
$id = createDataSource($nama, $host, $dt, $us, $pw, $jenis, $query);
if ($id == 0 || ! $id) {
	$result['status'] = 'error';
	$result['message'] = 'Terjadi kesalahan pada saat proses penyimpanan.<br>Silahkan ulangi proses penyimpanan datasource.';
	echo json_encode($result);
}

// create xml file
createXmlDataSource($res, $jenis, $nama);

$result['status'] = 'success';
$result['message'] = 'Datasource berhasil tersimpan di database';
echo json_encode($result);



/*
| -------------------------------------------------------------------------
| LIST OF ALL LOCAL FUNCTION THAT NEEDED
| -------------------------------------------------------------------------
| Validation function :
| isValidName : Check if datasource name valid or not
| isUniqueName : Check if datasource name not use in database
|
| Extended function :
| createDataSource : Store datasource to database
| createXmlDataSource : Create XML file
|
*/

/**
 * check if data source name is valid
 * @param  string  $nama 
 * @return boolean
 */
function isValidName($nama)
{
	$pattern = '^([a-z]|[A-Z])$|^(([a-z]|[A-Z])+([a-z]|[A-Z]|_)*([a-z]|[A-Z])+)$';

	return eregi($pattern, $nama);
}

/**
 * check if data source name is unique
 * @param  string  $nama
 * @return boolean
 */
function isUniqueName($nama)
{
	global $db;
	$sql = "select * from tab_data where nama_data='$nama'";

	return $db->get_query_rows($sql) == 0;
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
function createDataSource($nama, $host, $dt, $user, $pass, $jenis, $query) {
	global $db;

	$query = str_replace("'","\'",$query);
	$query = str_replace('"','\"',$query);
	$query = "INSERT INTO tab_data (nama_data,server,user,pass,data_base,jenis,query) VALUES ('$nama','$host','$user','$pass','$dt','$jenis','$query')";
	
	return $db->insert($query);
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
	$nama = '../data_resource/'.$nama.'.xml';
	$obj_xml = new XML($nama, $type);

	$obj_xml->resource_to_xml($resource);

	unset($obj_xml);
}