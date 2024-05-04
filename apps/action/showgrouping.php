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

// get all report grouping
$sql = "SELECT * FROM tab_grouping WHERE id_laporan = $id";
if ($db->get_query_rows($sql) == 0) {
	$result['status'] = 'error';
	$result['message'] = 'Laporan belum memiliki grouping.<br>Silahkan tambahkan grouping baru untuk laporan.';
	echo json_encode($result);
	return;
}
$groups = $db->select($sql);

$content = '<thead><tr>
		<th>Nama Grouping</th>
		<th>Jenis</th>
		<th>Interval</th>
		<th></th>
	</tr></thead>';

while ($group = mysql_fetch_assoc($groups)) {
	$content .= '<tr>';
	$content .= '<td>' . ucwords($group['nama_group']) . '</td>';
	$content .= '<td class="text-center">' . ucwords($group['jenis_group']) . '</td>';
	$content .= '<td class="text-center">' . ucwords($group['intv']) . '</td>';
	$content .= '<td class="text-center"><a href="./action/destroygrouping.php?id=' . $group['id_group'] . '" class="btn btn-danger btn-sm btn-delete-filter-grouping"><i class="fa fa-trash-o"></i></a></td>';
	$content .= '</tr>';
}

$content = '<table class="table table-bordered table-hover table-striped table-primary">' .  $content . '</table>';

$result['status'] = 'success';
$result['message'] = $content;
$result['title'] = 'List Grouping Laporan ' . ucwords($report['nama_laporan']);
echo json_encode($result);