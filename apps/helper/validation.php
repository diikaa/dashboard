<?php

/**
 * Check in data is empty string or not
 * @param  array  &$error_bag
 * @param  string  $field
 * @param  string  $label
 * @return boolean             
 */
function isRequired(&$error_bag, $field, $label)
{
	if (empty(trim($field))) {
		$error_bag['msg'][] = "$label harus diisi dan tidak boleh kosong.";
		return FALSE;
	}

	return TRUE;
}


/**
 * Check if data already store in database or not
 * @param  array  &$error_bag 
 * @param  string  $field      
 * @param  strung  $table      table_name.table_column.column_type
 * @param  string  $label      
 * @return boolean
 */
function isUnique(&$error_bag, $field, $table, $label)
{
	list($table, $column, $type) = explode('.', $table);

	$db = new DB();
	$db->set_default_connection();

	$sql = ($type == 'int') ? "SELECT * FROM $table WHERE $column = $field" : "SELECT * FROM $table WHERE $column = '$field'";
	if ($db->get_query_rows($sql) > 0) {
		$error_bag['msg'][] = "$label harus unik dan belum ada di database.";
		return FALSE;
	}

	return TRUE;
}


/**
 * Check if data exists in database
 * @param  array  &$error_bag 
 * @param  string  $field      
 * @param  string  $table      table_name.table_column.column_type
 * @param  sring  $label      
 * @return boolean             
 */
function isExists(&$error_bag, $field, $table, $label)
{
	list($table, $column, $type) = explode('.', $table);

	$db = new DB();
	$db->set_default_connection();

	$sql = ($type == 'int') ? "SELECT * FROM $table WHERE $column = $field" : "SELECT * FROM $table WHERE $column = '$field'";
	if ($db->get_query_rows($sql) == 0) {
		$error_bag['msg'][] = "$label tidak ditemukan di database.";
		return FALSE;
	}

	return TRUE;
}


/**
 * Build error list into HTML LIST
 * @param  array $error_list 
 * @return string            
 */
function buildErrorMessage($error_list)
{
	$msg = '<ul style="padding-left: 15px;">';
	foreach ($error_list as $error) {
		$msg .= '<li>'.$error.'</li>';
	}
	$msg .= '</ul>';

	return $msg;
}