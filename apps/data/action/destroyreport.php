<?php

include '../helper/isadmin.php';

// required file
require_once('../model/class.db.php');

// initial setup
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$filterdir = '../filter/';
$groupdir = '../group/';
$headerdir = '../header/';
$pivotdir = '../pivot/';
$reportPage = '../admin.php?menu=10';

$db = new DB();
$db->set_default_connection();

// find all filter
$sql = "SELECT * FROM tab_filter WHERE id_laporan = $id";
$tmp = $db->select($sql);
$filters = [];
while ($filter = mysql_fetch_assoc($tmp)) {
	$filters[] = $filter['id_filter'];
}

// delete filter xml file
if (count($filters) > 0) {
	foreach ($filters as $filter) {
		$filterFile = $filterdir . $filter . '.xml';
		if (file_exists($filterFile)) unlink($filterFile);
	}
}

// delete from database
$sql = "DELETE FROM tab_filter WHERE id_laporan = $id";
$db->select($sql);

// find all grouping
$sql = "SELECT * FROM tab_grouping WHERE id_laporan = $id";
$tmp = $db->select($sql);
$groups = [];
while ($group = mysql_fetch_assoc($tmp)) {
	$groups[] = $group['id_group'];
}

// delete filter xml file
if (count($groups) > 0) {
	foreach ($groups as $group) {
		$groupFile = $groupdir . $group . '.xml';
		if (file_exists($groupFile)) unlink($groupFile);
	}
}

// delete from database
$sql = "DELETE FROM tab_grouping WHERE id_laporan = $id";
$db->select($sql);

// get report name
$sql = "SELECT * FROM laporan WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	header('location:' . $reportPage);
	exit();
}

$report = mysql_fetch_assoc($db->select($sql));

// delete header
$parentFile = $headerdir . $report['nama_laporan'] . '.xml';
if (file_exists($parentFile) ) {
	$xml = simplexml_load_file($parentFile);

	foreach($xml->file as $file) {
		if (file_exists($file->__toString())) unlink($file->__toString());
	}

	unlink($parentFile);
}

// delete pivot
$pivotFile = $pivotdir . $report['nama_laporan'] . '.xml';
if (file_exists($pivotFile)) unlink($pivotFile);

// deelete all report permission
$sql = "DELETE FROM otoritas WHERE id_laporan = $id";
$db->select($sql);

// delete report in database
$sql = "DELETE FROM laporan WHERE id_laporan = $id";
$db->select($sql);

header('location:' . $reportPage);