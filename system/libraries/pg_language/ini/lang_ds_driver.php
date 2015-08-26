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
 * Languages data source driver
 * 
 * Store in .ini files 
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class lang_ds_driver {
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;
	
	/**
	 * Path to languages file
	 * 
	 * @var string
	 */
	public $languages_path;

	/**
	 * Class constructor
	 * 
	 * @return lang_ds_driver
	 */
	public function lang_ds_driver(){
		$this->CI =& get_instance();
		$this->languages_path = APPPATH . "languages/ini/";
	}

	/**
	 * Return module content by guid
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language identifier
	 * @return array
	 */
	public function get_module($module_gid, $lang_id){	//// get all module strings from base and put it to cache ($modules_data)
		global $language;
		unset($language);

		$return = array();

		$file = $this->languages_path . $lang_id . "/" . $module_gid . "_ds.ini";
		if(file_exists($file)){
			$return = parse_ini_file($file, true);
		}

		return $return;
	}

	/**
	 * Save data source entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid entry guid
	 * @param array $data entry data
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function set_module_reference($module_gid, $gid, $data, $lang_id){
		$module_lang = $this->get_module($module_gid, $lang_id);
		$module_lang[$gid] = $data;
		$this->_save_file($module_gid, $lang_id, $module_lang);
		return;
	}

	/**
	 * Resort data source entry
	 * 
	 * @param string $module_gid module guid
	 * @param string $gid entry guid
	 * @param array $sorter_data sorting data
	 * @param array $languages languages identifiers
	 * @return void
	 */
	public function set_reference_sorter($module_gid, $gid, $sorter_data, $languages){
		if(empty($sorter_data)) return false;

		$temp_reference = array();
		foreach($languages as $lang_id => $language){
			unset($temp_reference);

			$module_lang = $this->get_module($module_gid, $lang_id);
			$reference = $module_lang[$gid];

			$temp_reference["header"] = $reference["header"];
			foreach($sorter_data as $index => $option_gid){
				$temp_reference["option"][$option_gid]  = $reference["option"][$option_gid];
			}

			$module_lang[$gid] = $temp_reference;
			$this->_save_file($module_gid, $lang_id, $module_lang);
		}
	}

	/**
	 * Remove data source enrty
	 * 
	 * @param string $module_gid module guid
	 * @param array $gids entries guids
	 * @return void
	 */
	public function delete_module_reference($module_gid, $gids){
		$lang_ids = $this->_get_exists_lang_ids();
		foreach($lang_ids as $lang_id){
			$module_lang = $this->get_module($module_gid, $lang_id);
			foreach($gids as $gid){
				if(isset($module_lang[$gid])) unset($module_lang[$gid]);
			}
			$this->_save_file($module_gid, $lang_id, $module_lang);
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
		$lang_ids = $this->_get_exists_lang_ids();
		foreach($lang_ids as $lang_id){
			$this->_delete_file($module_gid, $lang_id);
		}
		return;
	}

	/**
	 * Install properties depended on language
	 * 
	 * @param integer $lang_id language idnetifier
	 * @return void
	 */
	public function add_language($lang_id){
		//// only create dir
		$dir = $this->languages_path . $lang_id . "/";

		if(!is_dir($dir)){
			mkdir($dir);
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
		$dir = $this->languages_path . $lang_id . "/";
		
		$d = dir($dir);
		while (false !== ($entry = $d->read())) {
			if(is_file($dir . $entry) && substr($entry, -7) == "_ds.ini"){
				unlink($dir . $entry);
			}
		}
		$d->close();
		rmdir($dir);
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
		$dir_from = $this->languages_path . $lang_from . "/";
		$dir_to = $this->languages_path . $lang_to . "/";

		if(!is_dir($dir_from)){
			return false;
		}

		if(!is_dir($dir_to)){
			mkdir($dir_to);
		}

		$d = dir($dir_from);
		while (false !== ($entry = $d->read())) {
			if(substr($entry, -7) == "_ds.ini"){
				$file_from = $dir_from . $entry;
				$file_to = $dir_to . $entry;
				copy($file_from, $file_to);
			}
		}
		$d->close();

		return;
	}

	/**
	 * Save module data source entries to file
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language idnetifier
	 * @param array $data data for saving
	 * @return void
	 */
	public function _save_file($module_gid, $lang_id, $data=array()){
		$dir = $this->languages_path . $lang_id . "/";
		if(!is_dir($dir)){
			mkdir($dir);
		}

		$file = $dir . $module_gid . '_ds.ini';
		$h = fopen($file, "w");
		if($h){
			$text = $this->_compile_file($data);
			fwrite($h, $text);
			fclose($h);
		}
		return;
	}

	/**
	 * Remove module data source entries
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language idnetifier
	 * @param array $data data for saving
	 * @return void
	 */
	public function _delete_file($module_gid, $lang_id){
		$file = $this->languages_path . $lang_id . "/" . $module_gid . '_ds.ini';
		if(file_exists($file)){
			unlink($file);
		}
		return;
	}

	/**
	 * Create module data source entries
	 * 
	 * @param string $module_gid module guid
	 * @param integer $lang_id language idnetifier
	 * @param array $data data for saving
	 * @return void
	 */
	public function _create_file($module_gid, $lang_id){
		$this->_save_file($module_gid, $lang_id);
	}

	/**
	 * Generate file content
	 * 
	 * @param array $data data source content
	 * @return string
	 */
	public function _compile_file($data = array()){
		$str = "";
		if(!empty($data)){
			foreach($data as $gid => $gid_data){
				$header = str_replace('"', '\"', stripslashes($gid_data["header"]));
				$str .= '['.$gid.']' . "\n";
				$str .= 'header = "'.$header.'"' . "\n";
				if(!empty($gid_data["option"])){
					foreach($gid_data["option"] as $option_gid => $option_value){
						$value = str_replace('"', '\"', stripslashes($option_value));
						$str .= 'option['.$option_gid.'] = "'.$value.'"' . "\n";
					}
				}
				$str .= "\n";
			}
		}
		$str .= "";
		return $str;
	}
	
	/**
	 * Return identifiers of exists data source entries
	 * 
	 * @return array
	 */
	public function _get_exists_lang_ids(){
		$ids = array();
		$d = dir($this->languages_path);
		do{
			$entry = $d->read();
			if(intval($entry)){
				$ids[] = intval($entry);
			}
		}while($entry !== false);
		$d->close();
		return $ids;
	}

}
