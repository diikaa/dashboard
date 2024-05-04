<?php

include '../helper/isadmin.php';

//reuired file
require_once('../model/class.db.php');

// initial setup
$result = [];
$id = isset($_POST['id']) ? $_POST['id'] : 0;

$db = new DB();
$db->set_default_connection();

// check if report is exists
$sql = "SELECT * FROM laporan WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Laporan tidak ditemukan di dalam sistem.<br>Pastikan laporan tersimpan di dalam sistem';
	echo json_encode($result);
	return;
}
$report = mysql_fetch_assoc($db->select($sql));

// get all report filter
$sql = "SELECT * FROM tab_filter WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Laporan belum memiliki filter.<br>Silahkan tambahkan filter baru untuk laporan.';
	echo json_encode($result);
	return;
}
$filters = $db->select($sql);

$content = '<thead><tr>
		<th>Nama FIlter</th>
		<th>Jenis</th>
		<th>Interval</th>
		<th></th>
	</tr></thead>';

while ($filter = mysql_fetch_assoc($filters)) {
	$content .= '<tr>';
	$content .= '<td>' . ucwords($filter['nama_filter']) . '</td>';
	$content .= '<td class="text-center">' . ucwords($filter['jenis_filter']) . '</td>';
	$content .= '<td class="text-center">' . ucwords($filter['intv']) . '</td>';
	$content .= '<td class="text-center"><a href="./action/destroyfilter.php?id=' . $filter['id_filter'] . '" class="btn btn-danger btn-sm btn-delete-filter-grouping"><i class="fa fa-trash-o"></i></a></td>';
	$content .= '</tr>';
}

$content = '<table class="table table-bordered table-hover table-striped table-primary">' .  $content . '</table>';

$result['status'] = 'success';
$result['message'] = $content;
$result['title'] = 'List Filter Laporan ' . ucwords($report['nama_laporan']);
echo json_encode($result);