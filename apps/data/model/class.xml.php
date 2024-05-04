<?php

class XML {

	private $name;
	private $type;
	private $doc;
	private $root;	
	private $node;
	private $subnode;
	private $text;
	
	/**
	 * initialize xml object
	 * 
	 * @param string $name xml file
	 * @param string $type DB type
	 * 
	 */
	public function __construct($name, $type='') 
	{
		$this->name = $name;
		$this->type = $type;
		$this->create_xml();
	}
	
	/**
	 * save xml
	 * @return void
	 * 
	 */
	public function save_xml() 
	{
		$this->doc->save($this->name);
	}
	
	/**
	 * change xml file
	 * @param string $name xml file
	 * 
	 */
	function set_name($name) 
	{
		$this->name = $name;
	}
	
	/**
	 * initialize php xml object
	 * @return void
	 * 
	 */
	public function create_xml() 
	{
		$this->doc = new DOMDocument('1.0');
	}
	
	/**
	 * set xml root
	 * @param string $string root name
	 * 
	 */
	public function add_root($string) 
	{
		$this->root = new DOMElement($string);
		$this->root = $this->doc->appendChild($this->root);
	}
	
	/**
	 * add node to root
	 * @param string $string node name
	 */
	public function add_node($string) 
	{
		$this->node = new DOMElement($string);
		$this->node = $this->root->appendChild($this->node);
	}
	
	/**
	 * add subnode to node
	 * @param string $string subnode name
	 */
	public function add_subnode($string) 
	{
		$this->subnode = new DOMElement($string);
		$this->subnode = $this->node->appendChild($this->subnode);
	}
	
	/**
	 * add subnode value
	 * @param string $string
	 * 
	 */
	public function add_text($string) 
	{
		$this->text = new DOMText($string);
		$this->text = $this->subnode->appendChild($this->text);
	}
	
	/**
	 * add node value
	 * @param string $string
	 * 
	 */
	public function add_node_text($string) 
	{
		$this->text = new DOMText($string);
		$this->text = $this->node->appendChild($this->text);
	}
	
	/**
	 * get xml node
	 * @return Obj node
	 * 
	 */
	function get_node() 
	{
		return $this->node;
	}
	
	/**
	 * store query result to xml file
	 * storing process  base on DB type
	 * 
	 * @param  resources $res
	 * @return void
	 * 
	 */
	public function resource_to_xml($res) 
	{
		// check DB type
		if ("MySQL" == $this->type) {
			$this->mysql_to_xml($res);

		} else if ("Oci" == $this->type) {
			$this->oracle_to_xml($res);
		}

		$this->doc->save($this->name);
	}
	
	/**
	 * store MySQL resources to xml
	 * 
	 * @param  resiurces $res
	 * @return void
	 * 
	 */
	public function mysql_to_xml($res) 
	{
		$this->add_root('data');

		while ($row = mysql_fetch_assoc($res)) {
			$this->add_node('record');

			foreach ($row as $key=>$val) {
				$this->add_subnode($key);
				$this->add_text($val);
			}
		}
	}
	
	/**
	 * store Oracle resiurces to xml
	 * 
	 * @param  resources $res
	 * @return void
	 * 
	 */
	public function oracle_to_xml($res)
	{
		$this->add_root('data');

		while ($row = oci_fetch_assoc($res)) {
			$this->add_node('record');

			foreach ($row as $key=>$val) {
				$this->add_subnode($key);
				$this->add_text($val);
			}
		}
	}
	
	/**
	 * save header to xml file
	 * 
	 * @param  array  $column_data   
	 * @param  int    $row_index 
	 * @param  string $datasource_query
	 * @return void
	 * 
	 */
	public function header_to_xml($column_data, $row_index, $datasource_query) 
	{
		$this->create_xml();

		// root for xml header
		$this->add_root('header');
		
		// node query for dynamic header
		if (empty($datasource_query)) $datasource_query = '';
		$this->add_node('query');
		$this->add_node_text($datasource_query);

		// node row index
		$this->add_node('idx_baris');
		$this->add_node_text($row_index);

		// node for column data
		foreach ($column_data as $column) {
			$this->add_node('data_kolom');

			list($nama, $baris, $kolom) = explode("@@", $column);

			$this->add_subnode('nama');
			$this->add_text($nama);

			// span baris
			$this->add_subnode('baris');
			$this->add_text($baris);

			// span kolom
			$this->add_subnode('kolom');
			$this->add_text($kolom);
		}

		$this->doc->save($this->name);
	}
	
	/**
	 * save header file to xml
	 * 
	 * @param  string  $filename
	 * @param  boolean $exists
	 * @return void
	 * 
	 */
	public function file_to_xml($filename, $exists = FALSE)
	{
		$this->create_xml();

		if ( $exists ) {
			$this->doc->load($this->name);
			$this->root = $this->doc->documentElement;

			$this->add_node('file');
			$this->add_node_text($filename);
		} else {
			$this->add_root('file_header');
			
			$this->add_node('file');
			$this->add_node_text($filename);
		}

		$this->doc->save($this->name);
	}
	
	/**
	 * get xml node by tagname
	 * 
	 * @param  string $tag
	 * @return object
	 * 
	 */
	public function get_xml_node($tag) 
	{
		$this->doc->load($this->name);

		$root = $this->doc->documentElement;
		$elm = $root->getElementsByTagName($tag);

		return $elm;
	}
	
	/**
	 * get xml child tagname
	 * 	
	 * @param  object $elm
	 * @return array
	 * 
	 */
	public function get_xml_child_tag($elm) 
	{
		$childs = $elm->childNodes;

		$child_list = Array();
		foreach ($childs as $child) {
			$child_name[] = $child->nodeName;
		}

		return $child_list;
	}
	
	/**
	 * get node value
	 * 
	 * @param  object $elm
	 * @return string
	 * 
	 */
	public function get_single_node_val($elm) 
	{
		foreach ($elm as $e) {
			$res = $e->nodeValue;
		}

		return $res;
	}
	
	/**
	 * get list of node child
	 * 
	 * @param  object $elm
	 * @return array
	 */
	public function get_child_list($elm) 
	{
		foreach ($elm as $e) {
			$res = $e->childNodes;
		}

		return $res;
	}
	
	/**
	 * get all child node value
	 * 
	 * @param  object $elm 
	 * @return array
	 * 
	 */
	public function get_node_val($elm) 
	{
		$childs = $elm->childNodes;

		$res = Array();
		foreach ($childs as $child) {
			$res[$child->nodeName] = $child->nodeValue;
		}

		return $res;
	}
	
	/**
	 * read xml header file as html
	 * @return string
	 * 
	 */
	public function print_xml_header() 
	{
		// read all related xml file
		$files = $this->get_xml_node('file');

		$idx_baris = 1;
		$content = '<tr>';

		foreach ($files as $file) {
			// load the related file
			$this->set_name($file->nodeValue);

			// load all basic attribute
			$datasource = $this->get_single_node_val($this->get_xml_node('query'));
			$baris = $this->get_single_node_val($this->get_xml_node('idx_baris'));

			// start new row tag
			if ($baris != $idx_baris) {
				$content .= '</tr><tr>';
				$idx_baris = $baris;
			}

			// load column data
			$columns = $this->get_xml_node('data_kolom');

			// check if column is dynamic or not
			if ( trim($datasource) == '' ) {

				// not dynamic column
				foreach ($columns as $column) {
					$data = $this->get_node_val($column);
					$content .= '<th colspan="' . $data['kolom'] . '" rowspan="' . $data['baris'] . '">' . $data['nama'] . '</th>';
				}

			} else {

				// dynamic column
				$this->set_name('../data_resource/' . $datasource . '.xml');

				$records = $this->get_xml_node('record');
				foreach ($records as $record) {
					$record_val = $this->get_node_val($record);

					foreach ($columns as $column) {
						$data = $this->get_node_val($column);

						$pattern = '^::';
						if ( eregi($pattern, $data['nama']) ) {
							$field = substr($data['nama'], 2);
							$data['nama'] = $record_val[$field];
						}

						$content .= '<th colspan="' . $data['kolom'] . '" rowspan="' . $data['baris'] . '">' . $data['nama'] . '</th>';
					}

				} // endforeach
			} // endif
		} // endforeach

		$content .= '</tr>';

		// close row tag
		return $content;
	}
	
	/**
	 * save pivot to xml
	 * 
	 * @param  int    $type pivot type
	 * @param  array  $elm
	 * @return void
	 * 
	 */
	public function pivot_to_xml($type, $fields)
	{
		switch ($type) {
			case 1 : $this->pivot_single($fields); break;
			case 2 : $this->pivot_duo($fields); break;
			case 3 : $this->pivot_duo($fields); break;
		}
	}
	
	/**
	 * save as single pivot
	 * 
	 * @param  array $fields
	 * @return void
	 * 
	 */
	public function pivot_single($fields) {
		if (count($fields) != 0) {

			foreach ($fields as $field) {
				$this->add_node('data');
				$this->add_node_text($field);
			}

		}

		$this->doc->save($this->name);
	}
	
	/**
	 * save as double pivot
	 * 
	 * @param  array $fields 
	 * @return void
	 * 
	 */
	public function pivot_duo($fields)
	{
		$index = 1;
		foreach ($fields as $field) {
			
			if (count($field) != 0) {
				$this->add_node('data_'.$index);
				
				foreach ($field as $pivot) {
					$this->add_subnode('data');
					$this->add_text($pivot);
				}
			}

			$index++;
		}

		$this->doc->save($this->name);
	}
	
	/**
	 * print xml pivot into HTML table
	 * 
	 * @param  int $type
	 * @return string       html table
	 * 
	 */
	public function print_xml_pivot($type) 
	{
		switch($type) {
			case 1 : $result = $this->print_pivot_single(); break;
			case 2 : $result = $this->print_pivot_duo(); break;
			case 3 : $result = $this->print_pivot_trio(); break;
		}

		return $result;
	}
	
	/**
	 * get datasource record
	 * 
	 * @param  string $datasources 
	 * @return array
	 * 
	 */
	public function get_record_by_data_source($datasources) {
		$rec = array();

		foreach ($datasources as $datasource) {
			$this->set_name('../data_resource/' . $datasource . '.xml');
			$rec[] = $this->get_xml_node('record');
		}

		return $rec;
	}
	
	public function get_temp_value($ds,$col, $r) {
		if ('data1'== $ds) {
			$temp = $r[1][$col];
		} else if ('data2'== $ds) {
			$temp = $r[2][$col];
		} else if ('data3'== $ds) {
			$temp = $r[3][$col];
		} else {
			$temp = '';
		}
		return $temp;
	}
	
	public function rule_valid($rules, $r) {
		$tab_rule = explode(';',$rules);
		$valid = true;
		foreach ($tab_rule as $rule) {
			$rule = explode('=',$rule);
			$pola = '::'; 
			if ( eregi($pola,$rule[0]) ) {
				list($ds,$col) = split('::',$rule[0]);
				$temp1 = $this->get_temp_value($ds,$col,$r);
			} else {
				$temp1 = $rule[0];
			}
			if ( eregi($pola,$rule[1]) ) {
				list($ds,$col) = split('::',$rule[1]);
				$temp2 = $this->get_temp_value($ds,$col,$r);
			} else {
				$temp1 = $rule[0];
			}
			if (($temp1 == '') && ($temp2 == '')) {
				$valid =  false;
			} else {
				$valid = $temp1==$temp2;
			}
			if (!$valid) break;
		}
		return $valid;
	}
	
	/**
	 * print pivot single to html table
	 * @return void
	 * 
	 */
	public function print_pivot_single()
	{
		$datasource = $this->get_single_node_val( $this->get_xml_node('query') );
		$fields = $this->get_xml_node('data');

		$this->set_name('../data_resource/' . $datasource . '.xml');
		$records = $this->get_xml_node('record');

		$content = '';
		foreach ($records as $record) {
			$record_val = $this->get_node_val($record);
			$content .= '<tr>';

			foreach ($fields as $field) {
				$field_val = $field->nodeValue;
				$pattern = '^::'; 
				
				// check if valid field query or not
				// if not then this is constant value
				if ( eregi($pattern, $field_val) ) {
					$field_val = substr($field_val, 2);
					$content .= '<td>' . $record_val[$field_val] . '</td>';
				} else {
					$content .= '<td>' . $field_val . '</td>';
				}
			}

			$content .= '</tr>';
		}

		return $content;
	}
	
	public function print_pivot_duo() 
	{
		$datasources = [
			$this->get_single_node_val($this->get_xml_node('query1')),
			$this->get_single_node_val($this->get_xml_node('query2'))
		];

		$fields_1 = $this->get_child_list($this->get_xml_node('data_1'));
		$data2 = $this->get_child_list($this->get_xml_node('data_2'));
		
		$rule = $this->get_single_node_val($this->get_xml_node('syarat'));
		
		$records = Array();
		$records = $this->get_record_by_data_source($datasources);
		$tab_r = Array();

		// START IF_1
		if ($fields_1->length > 0) {

			// START FOREACH_1
			foreach ($records[0] as $record_1) {

				$tab_r[1] = $this->get_node_val($record_1);
				$con_data1 = Array();
				
				//	START FOREACH_2
				foreach ($fields_1 as $field) {

					$field_val = $field->nodeValue;
					
					$pattern = '::';
					if ( eregi($pattern, $field_val) ) {
						list($datasource, $column) = explode('::', $field_val);

						if ('data1' == $datasource) {
							$con_data1[] = $tab_r[1][ $column ];
						} else {
							$con_data1[] = $datasource . '::' . $column;
						} 

					} else {
						$con_data1[] = $field_val;
					}

				} // ENDFOREACH_2

				$i = 0;
				$temp = '';
				$con_data2 = '';
				if ($data2->length > 0) {
					
					foreach ($records[1] as $r2) {
						$tab_r[2] = $this->get_node_val($r2);
						if ($this->rule_valid($rule,$tab_r)) {
							$i++;
							if ($i > 1) {
								$con_data2 .= '<tr>';
							}
							foreach ($data2 as $d) {
								$d_val = $d->nodeValue;
								$pola = '::';
								if ( eregi($pola,$d_val) ) {
									$d_val = explode('::',$d_val);
									if ('data2' == $d_val[0]) {
										$con_data2 .= '<td>'.$tab_r[2][$d_val[1]].'</td>';
									} else if ('data1' == $d_val[0]) {
										$con_data2 .= '<td>'.$tab_r[1][$d_val[1]].'</td>';
									} else {
										$con_data2 .= '<td>'.$d_val[0].'::'.$d_val[1].'</td>';
									}
								} else {
									$con_data2 .= '<td>'.$d_val.'</td>';
								}
							}
							if ($i == 1) {
								$temp = $con_data2;
								$con_data2 = '';
							} else {
								$con_data2 .= '</tr>';
							}
						}
					} // end foreach

				} //end if

				if ($i > 1) {
					$content .= '<tr>';
					foreach ($con_data1 as $c) {
						$content .= '<td rowspan="'.$i.'">'.$c.'</td>';
					}
					$content .= $temp.'</tr>'.$con_data2;
				} else {
					$content .= '<tr>';
					foreach ($con_data1 as $c) {
						$content .= '<td>'.$c.'</td>';
					}
					$content .= $temp.'</tr>';
				}

			}// ENDFOREACH_1

		} // ENDIF_1

		return $content;
	}
	
	public function print_pivot_trio() 
	{
		$qry = Array();
		$qry[0] = $this->get_single_node_val($this->get_xml_node('query1'));
		$qry[1] = $this->get_single_node_val($this->get_xml_node('query2'));
		$qry[3] = $this->get_single_node_val($this->get_xml_node('query3'));
		$data1 = $this->get_child_list($this->get_xml_node('data_1'));
		$data2 = $this->get_child_list($this->get_xml_node('data_2'));
		$rule = $this->get_single_node_val($this->get_xml_node('syarat'));
		$rec = Array();
		$rec = $this->get_record_by_data_source($qry);
		$tab_r = Array();
		
		$content = '';
		foreach ($rec[0] as $r1) {
			$tab_r[1] = $this->get_node_val($r1);
			$content .= '<tr>';
			foreach ($data1 as $d) {
				$d_val = $d->nodeValue;
				$pola = '::';
				if ( eregi($pola,$d_val) ) {
					$d_val = explode('::',$d_val);
					if ('data1' == $d_val[0]) {
						$content  .= '<td>'.$tab_r[1][$d_val[1]].'</td>';
					} else {
						$content .= '<td>'.$d_val[0].'::'.$d_val[1].'</td>';
					}
				} else {
					$content .= '<td>'.$d_val.'</td>';
				}
			}
			
			foreach ($rec[1] as $r2) {
				$tab_r[2] = $this->get_node_val($r2);
				$i = 0;
				foreach ($rec[2] as $r3) {
					$tab_r[3] = $this->get_node_val($r3);
					if ($this->rule_valid($rule,$tab_r)) {
						$i++;
						foreach ($data2 as $d) {
							$d_val = $d->nodeValue;
							$pola = '::';
							if ( eregi($pola,$d_val) ) {
								$d_val = explode('::',$d_val);
								switch ($d_val[0]) {
									case 'data1' : $content .= '<td>'.$tab_r[1][$d_val[1]].'</td>'; break;
									case 'data2' : $content .= '<td>'.$tab_r[2][$d_val[1]].'</td>'; break;
									case 'data3' : $content .= '<td>'.$tab_r[3][$d_val[1]].'</td>'; break;
									default :
										$content .= '<td>'.$d_val[0].'::'.$d_val[1].'</td>'; break;
								}
							} else {
								$content .= '<td>'.$d_val.'</td>';
							}
						} 
					} 
				} // end foreach 3
				if ($i == 0) {
					foreach ($data2 as $d_val) {
						$content .= '<td>0</td>';
					}
				}
			} // end foreach 2
			$content .= '</tr>';
			
		} // end foreach 1
		return $content;
	}

	
	
}

?>