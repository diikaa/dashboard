<?php

include '../helper/isadmin.php';

// catch all parameter
$host = $_POST['e_host'];
$dt = $_POST['e_dt'];
$us = $_POST['e_user'];
$pw = $_POST['e_pass'];
$jenis = $_POST['e_jenis'];

$result = array();

// include DB class and try connect
require_once('../model/class.db.php');
$db = new DB;
$status = $db->set_connection($host, $us, $pw, $dt, $jenis);

// if connection not valid then return error massage
if (! $status) {
	$result['status'] = 'error';
	$result['message'] = $db->get_last_error();
	echo json_encode($result);
	return;
} 

// success
$result['status'] = 'success';
$tab = $db->get_table_list();
$res = $db->get_table_list();
$result['message'] = printListTable($db->get_jenis(), $tab, $res);

echo json_encode($result);


/**
 * print all table in list mode
 * @param  string $jenis 
 * @param  resource $tab   
 * @param  resource $res   
 * @return string        
 */
function printListTable($jenis, $tab, $res) 
{
	// check how many table in database
	if ('MySQL' == $jenis) {
		$size = mysql_num_rows($tab);
		mysql_free_result($res);
	} else if ('Oci' == $jenis) {
		ocifetchstatement($res,$temp);
		$size = count($temp)-1;
		oci_free_statement($res);
	}

	if ($size == 0) {
		$content = '<p>Tidak ditemukan tabel di dalam database. Database masih kosong.</p>';
		return $content;
	}
	
	$content = '';
	if ('MySQL' == $jenis) {
		while ($data = mysql_fetch_array($tab)) {
			$content .= '<a class="db-table-item" href="'.$data[0].'"><li><h4 class="sender">'.$data[0].'</h4></li></a>';
		}
	} else if ('Oci' == $jenis) {
		while ($data = oci_fetch_array($tab)) {
			$content .= '<a href="'.$data[0].'"><li><h4>'.$data[0].'</h4></li></a>';
		}
	}

	return '<ul>' . $content . '</ul>';
}