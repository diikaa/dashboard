<?php

include '../helper/isadmin.php';

// get datasource param
$id = $_GET['id'];

// // initial parameter
$user_page = '../admin.php?menu=3';

// connect to database
require_once('../model/class.db.php');
$db = new DB();
$db->set_default_connection();

$sql = "SELECT nik AS id FROM user WHERE nik = '$id'";
$res = $db->select($sql);
$data = mysql_fetch_array($res);
echo $query;

// check if datasource exist in database
if (mysql_num_rows($res) == 0) {
	header('location:' . $user_page);
    exit();
}

// delete data source in DB
echo 'halo';
$query = "DELETE FROM user WHERE nik = '$id'";
$db->select($query);

header('location:' . $user_page);
