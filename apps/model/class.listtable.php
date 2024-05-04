<?php

/**
* file untuk membentuk class list table
* berisi list table dari database yang dipilih
* updated by plagiator :: 2015
**/

class ListTable 
{
	private $tab;
	
	public function  __construct($tab) 
	{
		$this->tab = $tab;
	}
	
	public function print_list_table($jenis ,&$res) 
	{
		// check how many table in database
		if ('MySQL' == $jenis) {
			$size = mysql_num_rows($this->tab);
			mysql_free_result($res);
		} else if ('Oci' == $jenis) {
			ocifetchstatement($res,$temp);
			$size = count($temp)-1;
			oci_free_statement($res);
		}

		($size > 5) ? $maxsize = 5 : $maxsize = $size;
		
		$content = '';
		if ('MySQL' == $jenis) {
			while ($data = mysql_fetch_array($this->tab)) {
				$content .= '<a class="db-table-item" href="'.$data[0].'"><li><h4 class="sender">'.$data[0].'</h4></li></a>';
			}
		} else if ('Oci' == $jenis) {
			while ($data = oci_fetch_array($this->tab)) {
				$content .= '<a href="'.$data[0].'"><li><h4>'.$data[0].'</h4></li></a>';
			}
		}

		return '<ul>' . $content . '</ul>';
	}
}