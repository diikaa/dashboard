<?php

/**
* file untuk koneksi database
* created by plagiator :: 2010
* updated by plagiator :: 2015
**/

class DB {
	private $server;
	private $user;
	private $pw;
	private $dt;
	private $jenis;
	private $last_error;
	private $last_query;
	private $con_status = false;
	
	public function __construct() {
		$this->server = 'localhost';
		$this->user = 'dashboard';
		$this->pw = 'dashboard';
		$this->dt = 'dashboard';
		$this->jenis = '';
	}
	
	public function get_jenis() {
		return $this->jenis;
	}
	
	public function set_default_connection() {
		$this->server = 'localhost';
		$this->user = 'dashboard';
		$this->pw = 'dashboard';
		$this->dt = 'dashboard';
		$this->jenis = 'MySQL';
		if ('MySQL' == $this->jenis) {
			$result = $this->mysql_set_connection();
		} else if ('Oci' == $this->jenis) {
			$result = $this->oracle_set_connection();
		}
		return $result;
	}
	
	public function set_connection($server='' ,$user='', $pw='', $dt='', $jenis='') {
		if ($server != '') {$this->server = $server;}
		if ($user != '') {$this->user = $user;}
		if ($pw != '') {$this->pw = $pw;}
		if ($dt != '') {$this->dt = $dt;}
		if ($jenis != '') {$this->jenis = $jenis;}
		
		if ('MySQL' == $this->jenis) {
			$result = $this->mysql_set_connection();
		} else if ('Oci' == $this->jenis) {
			$result = $this->oracle_set_connection();
		}
		return $result;
	}
	
	public function select($query) {
		$this->last_query = $query;
		if ('MySQL' == $this->jenis) {
			$result = $this->mysql_select();
		} else if ('Oci' == $this->jenis) {
			$result = $this->oracle_select();
		}
		return $result;
	}
	
	public function insert($query) {
		$this->last_query = $query;
		if ('MySQL' == $this->jenis) {
			$result = $this->mysql_insert();
		} 
		return $result;
	}
	
	public function get_query_rows($query) {
		$result = $this->select($query);
		if ('MySQL' == $this->jenis) {
			$result = $this->mysql_get_query_rows($result);
		} else if ('Oci' == $this->jenis) {
			$result = $this->oracle_get_query_rows($result);
		}
		return $result;
	}
	
	public function dump_query($query, $showRows = FALSE) {
		$r = $this->select($query);
		if (!$r) {
			return false;
		} else {
			$h = $this->get_col_name($r);

			$content = '';
			if ($showRows) {
				$row = $this->get_query_rows($query);
				$content = '<p>Ditemukan <strong>' . $row . ' records</strong> di dalam database.</p>';
			}
				
			$content .= '<table class="table table-primary table-striped table-bordered table-hover"><thead><tr>';
			foreach ($h as $key=>$val) {
				$content .= '<th>'.$val.'</th>';
			}
			$content .= '</thead></tr></tbody>';
			$content .= $this->print_row($r,$h);
			$content .= '</tbody></table>';
			
			$this->free_result($r);
			return $content;
		}
	}
	
	public function get_col_name($res) {
		if ('MySQL' == $this->jenis) {
			$result = $this->mysql_get_col_name($res);
		} else if ('Oci' == $this->jenis) {
			$result = $this->oracle_get_col_name($res);
		} 
		return $result;
	}
	
	public function print_row($res,$head) {
		if ('MySQL' == $this->jenis) {
			$result = $this->mysql_print_row($res,$head);
		} else if ('Oci' == $this->jenis) {
			$result = $this->oracle_print_row($res,$head);
		}
		return $result;
	}

	public function get_table_list() {
		if ($this->jenis == 'MySQL') {
			$query = 'show tables';
			$res = $this->select($query);
		} else if ($this->jenis == 'Oci') {
			$query = "select * from user_objects where object_type = 'TABLE'";
			$res = $this->select($query);
			oci_execute($res);
		}
		return $res;
	}
	
	public function get_last_error() {
		return $this->last_error;
	}
	
	public function set_error_msg($msg) {
		$this->last_error = $msg;
	}
	
	public function free_result(&$res) {
		if ('MySQL' == $this->jenis) {
			mysql_free_result($res);
		} else if ('Oci' == $this->jenis) {
			oci_free_statement($res);
		}
	}
	
	public function close_connection() {
		if ('MySQL' == $this->jenis) {
			$this->mysql_close_connection();
		} else if ('Oci' == $this->jenis) {
			$this->oci_close_connection();
		}
	}
	
/**
* kumpulan fungsi untuk database mysql
* =================================================================================
**/
	public function mysql_set_connection() {
		$this->con_status = mysql_connect($this->server, $this->user, $this->pw);
		if (!$this->con_status) {
			$this->set_error_msg(mysql_error());
			return false;
		} else {
			if (!$this->mysql_select_database()) {
				return false;
			} else {
				return $this->con_status;
			}
		}
	}
	
	public function mysql_select_database() {
		$status = mysql_select_db($this->dt); 
		if (!$status) {
			$this->set_error_msg(mysql_error());
			return false;
		} else {
			return true;
		}
	}
	
	public function mysql_select() {
		$res = mysql_query($this->last_query);
		if (!$res) {
			$this->set_error_msg(mysql_error());
			return false;
		} else {
			return $res;
		}
	}
	
	public function mysql_insert() {
		$res = mysql_query($this->last_query);
		if (!$res) {
			$this->set_error_msg(mysql_error());
			return false;
		}
		$id = mysql_insert_id();
		return $id;
	}
	
	public function mysql_get_query_rows($res) {
		$num_row = 0;
		if (!empty($res)) {
			$num_row = mysql_num_rows($res);
			mysql_free_result($res);
		}
		return $num_row;
	}
	
	public function mysql_print_row($res,$head) {
		$i = 0;
		$content = '';
		while ($row = mysql_fetch_array($res)) {
			$content .= '<tr>';
			foreach ($head as $key=>$val) {
				$content .= '<td>'.utf8_encode($row[$val]).'</td>';
			}
			$content .= '</tr>';
		}
		return $content;
	}
	
	public function mysql_get_col_name($res) {
		$sumCol = mysql_num_fields($res);
		$colName = array($sumCol);
		$i = 0;
		while ($i < $sumCol) {
			$meta = mysql_fetch_field($res,$i);
			$colName[$i] = $meta->name;
			$i++;
		}
		return $colName;
	}
	
	public function mysql_close_connection() {
		mysql_close($this->con_status);
	}
/**
* akhir fungsi untuk database mysql
* =================================================================================
**/

	public function oracle_set_connection() {
		$target = '//'.$this->server.'/'.$this->dt;
		$this->con_status = oci_connect($this->user,$this->pw,$target);
		if (!$this->con_status) {
			$e = oci_error();
			$this->set_error_msg($e['message']);
			return false;
		} else {
			return $this->con_status;
		}
	}
	
	public function oracle_select() {
		$res = oci_parse($this->con_status,$this->last_query);
		$status = oci_execute($res);
		if (!$status) {
			$e = oci_error($res);
			$this->set_error_msg($e['message']);
			return false;
		} else {
			return $res;
		}
	}
	
	public function oracle_get_query_rows($res) {
		$num_row = 0;
		if (!empty($res)) {
			$num_row = oci_num_rows($res);
			oci_free_statement($res);
		}
		return $num_row;
	}
	
	public function oracle_print_row($res,$head) {
		$i = 0;
		while ($row = oci_fetch_assoc($res)) {
			$content .= '<tr>';
			foreach ($head as $key=>$val) {
				$content .= '<td>'.utf8_encode($row[$val]).'</td>';
			}
			$content .= '</tr>';
		}
		return $content;
	}

	public function oracle_get_col_name($res) {
		$sumCol = oci_num_fields($res);
		$colName = array($sumCol);
		$i = 1;
		while ($i <= $sumCol) {
			$meta = oci_field_name($res,$i);
			$colName[$i-1] = $meta;
			$i++;
		}
		return $colName;
	}
	
	public function oci_close_connection() {
		oci_close($this->con_status);
	}
}
?>
