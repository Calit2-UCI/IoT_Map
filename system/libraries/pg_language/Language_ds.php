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
 * Langauages datasource Model
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category 	libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Language_ds {
	/**
	 * Lint to driver object
	 * 
	 * @var object
	 */
	public $driver;
	
	/**
	 * Driver type
	 * 
	 * @param string
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
	 * @return Language_ds
	 */
	public function Language_ds($lang_id, $params, $languages_data, $lang_default_id=null){
		$this->current_lang_id = $lang_id;
		$this->default_lang_id = (!empty($lang_default_id))?$lang_default_id:$lang_id;
		$this->type = $params["type"];
		$this->languages = $languages_data;

		require_once(BASEPATH.'libraries/pg_language/'.$this->type.'/lang_ds_driver'.EXT);
		$this->driver = new lang_ds_driver();
	}

	/**
	 * Load all data source content of module
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
	 * Return all data source content of module
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
	 * Remove all data source content of module
	 * 
	 * @param string $module_gid module guid
	 * @return void
	 */
	public function delete_module($module_gid){
		$this->driver->delete_module($module_gid);
	}

	/**
	 * Return content of data source entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid entry guid
	 * @param integer $lang_id language identifier 
	 * @return array
	 */
	public function get_reference($module_gid, $gid, $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);

		$return = "";

		if(isset($this->lang[$lang_id][$module_gid][$gid])) $return = $this->lang[$lang_id][$module_gid][$gid];

		return $return;
	}

	/**
	 * Set content of data source entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid entry guid
	 * @param array $strings_data entry content
	 * @param integer $lang_id language identifier 
	 * @return void
	 */
	public function set_module_reference($module_gid, $gid, $strings_data=array(), $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->driver->set_module_reference($module_gid, $gid, $strings_data, $lang_id);
		$this->get_module($module_gid, $lang_id);
	}

	/**
	 * Resort content of data source entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid entry guid
	 * @param array $sorter_data sorting data
	 * @return void
	 */
	public function set_reference_sorter($module_gid, $gid, $sorter_data){
		$this->driver->set_reference_sorter($module_gid, $gid, $sorter_data, $this->languages);
	}

	/**
	 * Remove content of data source entries
	 * 
	 * @param string $module_gid module guid
	 * @param string $gids entries guids
	 * @return void
	 */
	public function delete_references($module_gid, $gids=array()){
		$this->driver->delete_module_reference($module_gid, $gids);
		$this->get_module($module_gid);
	}

	/**
	 * Remove content of data source entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid entry guid
	 * @return void
	 */
	public function delete_reference($module_gid, $gid){
		$this->driver->delete_module_reference($module_gid, array($gid));
		$this->get_module($module_gid);
	}

	/**
	 * Check data source entry is exists
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid data source entry guid
	 * @param integer $lang_id language identifier
	 * @return boolean
	 */
	public function is_ds_exists($module_gid, $gid, $lang_id=''){
		if(!$lang_id) $lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);
		if(isset($this->lang[$lang_id][$module_gid][$gid])){
			return true;
		}else{
			return false;
		}
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
	 * Copy data source content to another language
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
	 * Generate content for installing module data sources
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
	 * Generate content for installing data sources
	 * 
	 * @param array $data data sources data
	 * @return string
	 */
	public function generate_install_lang($data){
		$html = '';
		if(!empty($data)){
			foreach($data as $gid => $reference){
				$html .= '$install_lang["'. $gid .'"]["header"] = "'. $this->prepare_install_string($reference["header"]) .'";' . "\n";
				if(!empty($reference["option"])){
					foreach($reference["option"] as $option_gid => $option_value){
						$html .= '$install_lang["'. $gid .'"]["option"]["'. $option_gid .'"] = "'. $this->prepare_install_string($option_value) .'";' . "\n";
					}
				}
				$html .= "\n";
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
