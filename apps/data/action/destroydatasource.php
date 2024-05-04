<?php

include '../helper/isadmin.php';

// get datasource param
$id = $_GET['id'];

// initial parameter
$datasource_page = '../admin.php?menu=1';
$xmlfile = '../data_resource/';

// connect to database
require_once('../model/class.db.php');
$db = new DB();
$db->set_default_connection();

$query = "SELECT nama_data AS nama FROM tab_data WHERE id_data = $id";
$res = $db->select($query);
$data = mysql_fetch_array($res);

// check if datasource exist in database
if (mysql_num_rows($res) == 0) {
	header('location:' . $datasource_page);
    exit();
}

// delete data source in DB
$query = "DELETE FROM tab_data WHERE id_data = $id";
$db->select($query);

// delete xml file
$xmlfile .= $data['nama'] . '.xml';
if (file_exists($xmlfile)) unlink($xmlfile);

header('location:' . $datasource);
