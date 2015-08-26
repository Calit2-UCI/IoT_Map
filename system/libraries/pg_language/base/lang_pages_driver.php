<?php  

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('LANG_PAGES_TABLE', DB_PREFIX.'lang_pages');

/**
 * Languages pages driver
 * 
 * Store in database
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class lang_pages_driver {
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;

	/**
	 * Class constructor
	 * 
	 * @return lang_pages_driver
	 */
	public function lang_pages_driver(){
		$this->CI =& get_instance();
		$this->CI->db->memcache_tables(array(LANG_PAGES_TABLE));
	}

	/**
	 * Return module content by guid
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function get_module($module_gid, $lang_id){	//// get all module strings from base and put it to cache ($modules_data)
		$lang_value="value_".$lang_id;
		$this->CI->db->select('gid, '.$lang_value)->from(LANG_PAGES_TABLE)->where('module_gid', $module_gid);
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			$return = array();
			foreach($results as $result){
				$return[$result["gid"]] = (!empty($result[$lang_value]))?$result[$lang_value]:"";
			}
			return $return;
		}else{
			return false;
		}
	}

	/**
	 * Save pages entries
	 * 
	 * @param string $module_gid module guid
	 * @param string $strings_data entry data
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function set_module_strings($module_gid, $strings_data, $lang_id){
		$lang_value="value_".$lang_id;
		$module_lang = $this->get_module($module_gid, $lang_id);
		foreach($strings_data as $gid=>$value){
			if(isset($module_lang[$gid])){
				if($module_lang[$gid] != $value){
					$this->CI->db->where('module_gid', $module_gid);
					$this->CI->db->where('gid', $gid);
					$this->CI->db->update(LANG_PAGES_TABLE, array($lang_value=>strval($value)));
				}
			}else{
				$data = array(
					'module_gid' => $module_gid,
					'gid' => $gid,
					$lang_value => strval($value),
				);
				$this->CI->db->insert(LANG_PAGES_TABLE, $data); 
			}
		}
		return;
	}

	/**
	 * Remove pages enrty
	 * 
	 * @param string $module_gid module guid
	 * @param array $gids entries guids
	 * @return void
	 */
	public function delete_module_strings($module_gid, $gids){
		foreach($gids as $gid){
			$this->CI->db->where('module_gid', $module_gid);
			$this->CI->db->where('gid', $gid);
			$this->CI->db->delete(LANG_PAGES_TABLE); 
		}
		return;
	}

	/**
	 * Remove data source module
	 * 
	 * @param string $module_gid module guid
	 * @return void
	 */
	public function delete_module($module_gid){
		$this->CI->db->where('module_gid', $module_gid);
		$this->CI->db->delete(LANG_PAGES_TABLE); 
		return;
	}

	/**
	 * Install properties depended on language
	 * 
	 * @param integer $lang_id language idnetifier
	 * @return void
	 */
	public function add_language($lang_id){
		////// add field to base
		if (!$this->CI->db->table_exists(LANG_PAGES_TABLE)){
			$this->create_table();
		}
		
		$field_name = "value_".$lang_id;
		if(!$this->CI->db->field_exists($field_name, LANG_PAGES_TABLE)){
			$this->CI->load->dbforge();
			$fields = array(
				$field_name => array('type' => 'TEXT', 'null' => FALSE)
			);
			$this->CI->dbforge->add_column(LANG_PAGES_TABLE, $fields);
		}
		return;
	}

	/**
	 * Uninstall properties depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function delete_language($lang_id){
		////// delete field from base

		if (!$this->CI->db->table_exists(LANG_PAGES_TABLE)){
			$this->create_table();
		}
		
		$field_name = "value_".$lang_id;
		if($this->CI->db->field_exists($field_name, LANG_PAGES_TABLE)){
			$this->CI->load->dbforge();
			$this->CI->dbforge->drop_column(LANG_PAGES_TABLE, $field_name);
		}
		return;
	}

	/**
	 * Copy data to another language
	 * 
	 * @param integer $lang_from source language identifier
	 * @param integer $lang_to destination language idnetifier
	 * @return void
	 */
	public function copy_language($lang_from, $lang_to){
		$field_name_from = "value_".$lang_from;
		$field_name_to = "value_".$lang_to;
		$this->CI->db->query('UPDATE '.LANG_PAGES_TABLE.' SET '.$field_name_to.'='.$field_name_from);
		return;
	}

	/**
	 * Install data source structure
	 * 
	 * @return void
	 */
	public function create_table(){
		$this->CI->load->dbforge();

		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 3,
				'null' => FALSE,
				'auto_increment' => TRUE
			),
			'module_gid' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => FALSE,
			),
			'gid' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => FALSE,
			)
		);

		$this->CI->dbforge->add_field($fields);
		$this->CI->dbforge->add_key('id', TRUE);
		$this->CI->dbforge->add_key('module_gid');
		$this->CI->dbforge->create_table(LANG_PAGES_TABLE);
		return;
	}
}
