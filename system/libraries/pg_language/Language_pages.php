<?php 

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Languages pages Model
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category 	libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Language_pages {
	/**
	 * Link to driver object
	 * 
	 * @var object
	 */
	public $driver;
	
	/**
	 * Data type
	 * 
	 * @var string
	 */
	public $type = 'base';
	
	/**
	 * Identifier of current language
	 * 
	 * @var integer
	 */
	public $current_lang_id;
	
	/**
	 * Identifier of language by default
	 * 
	 * @var integer
	 */
	public $default_lang_id;
	
	/**
	 * Language data
	 * 
	 * @var array
	 */
	public $lang = array();
	
	/**
	 * Languages data
	 * 
	 * @var array
	 */
	public $languages = array();

	/**
	 * Class constructor
	 * 
	 * @param integer $lang_id language identifier
	 * @param array $params options data
	 * @param array $languages_data languages data
	 * @param integer $lang_default_id identifier of language by default
	 * @return Language_pages
	 */
	public function Language_pages($lang_id, $params, $languages_data, $lang_default_id=null){
		$this->current_lang_id = $lang_id;
		$this->default_lang_id = (!empty($lang_default_id))?$lang_default_id:$lang_id;
		$this->type = $params["type"];
		$this->languages = $languages_data;

		require_once(BASEPATH.'libraries/pg_language/'.$this->type.'/lang_pages_driver'.EXT);
		$this->driver = new lang_pages_driver();
	}

	/**
	 * Load all pages content of module
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function get_module($module_gid, $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->lang[$lang_id][$module_gid] = $this->driver->get_module($module_gid, $lang_id);
	}

	/**
	 * Return all pages content of module
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function return_module($module_gid, $lang_id=''){	///// check if module data is set in cache, request it in base if not
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		if(!isset($this->lang[$lang_id][$module_gid]) || empty($this->lang[$lang_id][$module_gid])){
			$this->get_module($module_gid, $lang_id);
		}
		return $this->lang[$lang_id][$module_gid];
	}

	/**
	 * Remove all pages content of module
	 * 
	 * @param string $module_gid module guid
	 * @return void
	 */
	public function delete_module($module_gid){
		$this->driver->delete_module($module_gid);
	}

	////// strings data
	
	/**
	 * Return content of pages entries
	 * 
	 * @param string $module_gid module guid
	 * @param string $strings_keys entries guids
	 * @param integer $lang_id language identifier 
	 * @return array
	 */
	public function get_strings($module_gid, $strings_keys=array(), $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);

		$return = array();

		foreach($strings_keys as $gid){
			if(isset($this->lang[$lang_id][$module_gid][$gid])) $return[$gid] = $this->lang[$lang_id][$module_gid][$gid];
		}

		return $return;
	}

	/**
	 * Set content of pages entries
	 * 
	 * @param string $module_gid module guid
	 * @param array $strings_data entry content
	 * @param integer $lang_id language identifier 
	 * @return void
	 */
	public function set_strings($module_gid, $strings_data=array(), $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->driver->set_module_strings($module_gid, $strings_data, $lang_id);
		$this->get_module($module_gid, $lang_id);
	}

	/**
	 * Remove content of pages entries
	 * 
	 * @param string $module_gid module guid
	 * @param string $strings_keys entries guids
	 * @return void
	 */
	public function delete_strings($module_gid, $strings_keys=array()){
		$this->driver->delete_module_strings($module_gid, $strings_keys);
		$this->get_module($module_gid);
	}

	////// aliases for single string
	
	/**
	 * Return content of pages entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid entry guid
	 * @param integer $lang_id language identifier 
	 * @return array
	 */
	public function get_string($module_gid, $gid, $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);
		$return = '';
		if(isset($this->lang[$lang_id][$module_gid][$gid])) $return = $this->lang[$lang_id][$module_gid][$gid];
		return $return;
	}

	/**
	 * Set content of pages entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $string entry guid
	 * @param string $value entry content
	 * @param integer $lang_id language identifier 
	 * @return void
	 */
	public function set_string($module_gid, $string, $value, $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->driver->set_module_strings($module_gid, array($string=>$value), $lang_id);
		$this->get_module($module_gid, $lang_id);
	}

	/**
	 * Set content of pages entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $string entry guid
	 * @param array $values entry content
	 * @param integer $langs_id languages identifiers
	 * @return void
	 */
	public function set_string_langs($module_gid, $string, $values=array(), $langs_id=array()){
		if(empty($langs_id)) $langs_id[] = $this->current_lang_id;
		if(is_array($langs_id)) {
			foreach($langs_id as $lang_id){
				$value = isset($values[$lang_id])?$values[$lang_id]:$values[$this->current_lang_id];
				$this->driver->set_module_strings($module_gid, array($string=>$value), $lang_id);
			}
		}
		$this->get_module($module_gid, $lang_id);
	}

	/**
	 * Check pages entry is exists
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid data source entry guid
	 * @return boolean
	 */
	public function is_string_exists($module_gid, $gid){
		$lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);
		if(isset($this->lang[$lang_id][$module_gid][$gid])){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Remove content of pages entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $string_key entry guid
	 * @return void
	 */
	public function delete_string($module_gid, $string_key){
		$this->driver->delete_module_strings($module_gid, array($string_key));
		$this->get_module($module_gid);
	}

	////// lang managing functions
	
	/**
	 * Install properties depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function add_lang($lang_id){
		$this->driver->add_language($lang_id);
		if($this->default_lang_id != $lang_id) {
			$this->driver->copy_language($this->default_lang_id, $lang_id);
		}
	}

	/**
	 * Uninstall properties depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function delete_lang($lang_id){
		$this->driver->delete_language($lang_id);
	}

	/**
	 * Copy pages content to another language
	 * 
	 * @param integer $lang_from source language identifier
	 * @param integer $lang_to destination language identifier
	 * @return void
	 */
	public function copy_lang($lang_from, $lang_to){
		$this->driver->copy_language($lang_from, $lang_to);
	}

	////// generate functions
	
	/**
	 * Generate content for installing module pages
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language identifier
	 * @return string
	 */
	public function generate_install_module_lang($module_gid, $lang_id){
		$data = $this->return_module($module_gid, $lang_id);
		return $this->generate_install_lang($data);
	}

	/**
	 * Generate content for installing pages
	 * 
	 * @param array $data data sources data
	 * @return string
	 */
	public function generate_install_lang($data){
		$html = '';
		if(!empty($data)){
			ksort($data);
			foreach($data as $gid => $string){
				$html .= '$install_lang["'. $gid .'"] = "'. $this->prepare_install_string($string) .'";' . "\n";
			}
		}
		return $html;
	}

	/**
	 * Prepare data for installing
	 * 
	 * @param string $str data value
	 * @return string
	 */
	public function prepare_install_string($str){
		$str = str_replace('"', '\"', $str);
		$str = preg_replace('/[\n\r]/', '\n', $str);
		return $str;
	}
}
