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
 * Languages pages driver
 * 
 * Store in .php files
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
	 * Path to languages file
	 * 
	 * @var string
	 */
	public $languages_path;

	/**
	 * Class constructor
	 * 
	 * @return lang_pages_driver
	 */
	public function lang_pages_driver(){
		$this->CI =& get_instance();
		$this->languages_path = APPPATH . "languages/php/";
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

		$file = $this->languages_path . $lang_id . "/" . $module_gid . "_pages.php";
		if(file_exists($file)){
			require $file;
			if(!empty($language)){
				$return = $language;
			}
		}

		return $return;
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
		$module_lang = $this->get_module($module_gid, $lang_id);
		foreach($strings_data as $gid=>$value){
			$module_lang[$gid] = $value;
		}
		$this->_save_file($module_gid, $lang_id, $module_lang);
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
			if(is_file($dir . $entry) && substr($entry, -10) == "_pages.php"){
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
			if(substr($entry, -10) == "_pages.php"){
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

		$file = $dir . $module_gid . '_pages.php';
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
		$file = $this->languages_path . $lang_id . "/" . $module_gid . '_pages.php';
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
		$str = "<?php\n\n";
		if(!empty($data)){
			foreach($data as $gid => $value){
				$value = str_replace("'", "\'", stripslashes($value));
				$str .= '$language["'.$gid.'"] = \''.$value.'\';' . "\n";
			}
		}
		$str .= "?>";
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
		while (false !== ($entry = $d->read())) {
			if(intval($entry)){
				$ids[] = intval($entry);
			}
		}
		$d->close();
		return $ids;
	}
}
