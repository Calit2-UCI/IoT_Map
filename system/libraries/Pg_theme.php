<?php  

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('THEMES_TABLE', DB_PREFIX.'themes');
define('THEMES_COLORSETS_TABLE', DB_PREFIX.'themes_colorsets');

/**
 * PG Themes Model
 *
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category 	libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class CI_Pg_theme {

	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;

	/**
	 * Settings, stored in base, changable by admin if theme module installed
	 * 
	 * @var array
	 */
	public $active_settings=array();

	/**
	 * Default settings, preinstalled settings (if install module not installed and/or database settings not valid)
	 * 
	 * @var array
	 */
	public $default_settings=array(
		"admin" => array(
			"theme" => "admin",
			"scheme" => "default",
			'setable' => 0,
			"logo" => "logo.png",
			"logo_width" => "180",
			'logo_height' => "150",
			"mini_logo" => "mini_logo.png",
			"mini_logo_width" => "30",
			'mini_logo_height' => "30",
			"mobile_logo" => "mobile_logo.png",
			"mobile_logo_width" => "160",
			'mobile_logo_height' => "32",
		),
		"user" => array(
			"theme" => "default",
			"scheme" => "default",
			'setable' => 0,
			"logo" => "logo.png",
			"logo_width" => "260",
			'logo_height' => "50",
			"mini_logo" => "mini_logo.png",
			"mini_logo_width" => "30",
			'mini_logo_height' => "30",
			"mobile_logo" => "mobile_logo.png",
			"mobile_logo_width" => "160",
			'mobile_logo_height' => "32",
		)
	);

	/**
	 * Base data of theme
	 * 
	 * @var array
	 */
	public $theme_base_data = array();

	/**
	 * Default path to theme files
	 * 
	 * @var string
	 */
	public $theme_default_path = '';

	/**
	 * Default full path to theme files
	 * 
	 * @var string
	 */
	public $theme_default_full_path = '';

	/**
	 * Full default path to theme files
	 * 
	 * @var string
	 */
	public $theme_default_url = '';

	/**
	 * CSS links for theme
	 * 
	 * @var string
	 */
	public $css = array();
	
	/**
	 * CSS links by theme
	 * 
	 * @var string
	 */
	public $theme_css = array();

	/**
	 * Javascript links for theme
	 * 
	 * @var string
	 */
	public $js = array();

	/**
	 * Print type
	 * 
	 * default|pdf
	 * 
	 * @var string
	 */
	public $print_type = 'default';

	/**
	 * Constructor
	 *
	 * @return CI_PG_Theme Object
	 */
	public function CI_PG_Theme(){
		$this->CI =& get_instance();
		$this->theme_default_full_path = APPPATH."views/";
		$this->theme_default_path=  APPLICATION_FOLDER."views/";
		$this->theme_default_url = APPPATH_VIRTUAL."views/";
		if(INSTALL_DONE) {
			$this->CI->db->memcache_tables(array(THEMES_TABLE, THEMES_COLORSETS_TABLE));
		}
	}

	/**
	 * Return settings by default
	 * 
	 * @param string $theme_type theme type
	 * @return array
	 */
	public function get_default_settings($theme_type=''){
		if (!empty($theme_type)) {
			return $this->default_settings[$theme_type];
		} else {
			return $this->default_settings;
		}
	}

	/**
	 * Return active settings
	 * 
	 * @return array
	 */
	public function get_active_settings(){
		$this->active_settings = $this->get_default_settings();

		if(INSTALL_MODULE_DONE){
			$template_preview_mode = $this->CI->input->get('template_preview_mode', true);
			if($template_preview_mode && !empty($_SESSION["preview_theme"]) && $_SESSION["preview_scheme"]){
				$this->CI->db->select('id, set_name, set_gid, id_theme, color_settings')
							 ->from(THEMES_COLORSETS_TABLE)->where('set_gid', $_SESSION["preview_scheme"]);
				$results = $this->CI->db->get()->result_array();
				if(!empty($results)){
					foreach($results as $result){
						$result['color_settings'] = unserialize($result['color_settings']);
						$colorsets[$result["id_theme"]] = $result;
					}
				}

				$lang_id = $this->CI->pg_language->current_lang_id;
				$this->CI->db->select('id, theme, theme_type, active, setable, logo_width, logo_height, logo_default ' . ($lang_id ? ', logo_' . $lang_id : '').', mini_logo_width, mini_logo_height, mini_logo_default ' . ($lang_id ? ', mini_logo_' . $lang_id : '').', mobile_logo_width, mobile_logo_height, mobile_logo_default, ' . ($lang_id ? 'mobile_logo_' . $lang_id : ''))
						->from(THEMES_TABLE)->where('theme', $_SESSION["preview_theme"]);
				$results = $this->CI->db->get()->result_array();
				if(!empty($results)){
					foreach($results as $result){
						$logo = (!empty($result['logo_'.$lang_id]))?$result['logo_'.$lang_id]:$result['logo_default'];
						$mini_logo = (!empty($result['mini_logo_'.$lang_id]))?$result['mini_logo_'.$lang_id]:$result['mini_logo_default'];
						$mobile_logo = (!empty($result['mobile_logo_'.$lang_id]))?$result['mobile_logo_'.$lang_id]:$result['mobile_logo_default'];
						$this->active_settings[$result["theme_type"]] = array(
							"theme" => $result["theme"],
							"scheme" => $colorsets[$result["id"]]['set_gid'],
							"scheme_data" => $colorsets[$result["id"]],
							"setable" => intval($result["setable"]),
							"logo_width" => $result["logo_width"],
							"logo_height" => $result["logo_height"],
							"logo" => $logo,
							"mini_logo_width" => $result["mini_logo_width"],
							"mini_logo_height" => $result["mini_logo_height"],
							"mini_logo" => $mini_logo,
							"mobile_logo_width" => $result["mobile_logo_width"],
							"mobile_logo_height" => $result["mobile_logo_height"],
							"mobile_logo" => $mobile_logo,
						);
					}
				}		
			}else{			
				$this->CI->db->select('id, set_name, set_gid, id_theme, color_settings')->from(THEMES_COLORSETS_TABLE)->where('active', '1');
				$results = $this->CI->db->get()->result_array();
				if(!empty($results)){
					foreach($results as $result){
						$result['color_settings'] = unserialize($result['color_settings']);
						$colorsets[$result["id_theme"]] = $result;
					}
				}

				$lang_id = $this->CI->pg_language->current_lang_id;
				$this->CI->db->select('id, theme, theme_type, active, setable, logo_width, logo_height, logo_default ' . ($lang_id ? ', logo_' . $lang_id : '').', mini_logo_width, mini_logo_height, mini_logo_default ' . ($lang_id ? ', mini_logo_' . $lang_id : '').', mobile_logo_width, mobile_logo_height, mobile_logo_default, ' . ($lang_id ? 'mobile_logo_' . $lang_id : ''))
						->from(THEMES_TABLE)->where('active', '1');
				$results = $this->CI->db->get()->result_array();
				if(!empty($results)){
					foreach($results as $result){
						$logo = (!empty($result['logo_'.$lang_id]))?$result['logo_'.$lang_id]:$result['logo_default'];
						$mini_logo = (!empty($result['mini_logo_'.$lang_id]))?$result['mini_logo_'.$lang_id]:$result['mini_logo_default'];
						$mobile_logo = (!empty($result['mobile_logo_'.$lang_id]))?$result['mobile_logo_'.$lang_id]:$result['mobile_logo_default'];
						$this->active_settings[$result["theme_type"]] = array(
							"theme" => $result["theme"],
							"scheme" => $colorsets[$result["id"]]['set_gid'],
							"scheme_data" => $colorsets[$result["id"]],
							"setable" => intval($result["setable"]),
							"logo_width" => $result["logo_width"],
							"logo_height" => $result["logo_height"],
							"logo" => $logo,
							"mini_logo_width" => $result["mini_logo_width"],
							"mini_logo_height" => $result["mini_logo_height"],
							"mini_logo" => $mini_logo,
							"mobile_logo_width" => $result["mobile_logo_width"],
							"mobile_logo_height" => $result["mobile_logo_height"],
							"mobile_logo" => $mobile_logo,
						);
					}
				}
			}
		}
		return $this->active_settings;
	}

	/**
	 * Return active settings from cache
	 * 
	 * @param string $theme_type theme type
	 * @return array
	 */
	public function return_active_settings($theme_type=''){
		if(empty($this->active_settings)){
			$this->get_active_settings();
		}

		if (!empty($theme_type)) {
			return $this->active_settings[$theme_type];
		} else {
			return $this->active_settings;
		}
	}

	/**
	 * Return base data of theme
	 * 
	 * @return array
	 */
	public function get_theme_base_data($theme){
		if(INSTALL_MODULE_DONE){
			$lang_id = $this->CI->pg_language->current_lang_id;
			$this->CI->db->select('id, theme, theme_type, active, setable, logo_width, logo_height, logo_default, logo_'.$lang_id.', mini_logo_width, mini_logo_height, mini_logo_default, mini_logo_'.$lang_id.', mobile_logo_width, mobile_logo_height, mobile_logo_default, mobile_logo_'.$lang_id)->from(THEMES_TABLE)->where('theme', $theme);
			$results = $this->CI->db->get()->result_array();
			if(!empty($results)){
				$result = $results[0];
				$logo = (!empty($result['logo_'.$lang_id]))?$result['logo_'.$lang_id]:$result['logo_default'];
				$mini_logo = (!empty($result['mini_logo_'.$lang_id]))?$result['mini_logo_'.$lang_id]:$result['mini_logo_default'];
				$mobile_logo = (!empty($result['mobile_logo_'.$lang_id]))?$result['mobile_logo_'.$lang_id]:$result['mobile_logo_default'];
				$this->theme_base_data[$result["theme"]] = array(
					"theme" => $result["theme"],
					"logo_width" => $result["logo_width"],
					"logo_height" => $result["logo_height"],
					"logo" => $logo,
					"mini_logo_width" => $result["mini_logo_width"],
					"mini_logo_height" => $result["mini_logo_height"],
					"mini_logo" => $mini_logo,
					"mobile_logo_width" => $result["mobile_logo_width"],
					"mobile_logo_height" => $result["mobile_logo_height"],
					"mobile_logo" => $mobile_logo,
				);

				$this->CI->db->select('id, set_name, set_gid, id_theme, color_settings')->from(THEMES_COLORSETS_TABLE)->where('active', '1')->where('id_theme', $result["id"]);
				$results = $this->CI->db->get()->result_array();
				if(!empty($results)){
					$result = $results[0];
					$this->theme_base_data[$theme]["scheme"] = $result['set_gid'];
					$this->theme_base_data[$theme]["scheme_data"] = unserialize($result['color_settings']);
				}
			}
		}
		return $this->theme_base_data;
	}

	/**
	 * Return base data of theme from cache
	 * 
	 * @param string $theme theme guid
	 * @return array
	 */
	public function return_theme_base_data($theme){
		if(empty($this->theme_base_data[$theme])){
			$this->get_theme_base_data($theme);
		}
		return $this->theme_base_data[$theme];
	}

	/**
	 * Check Themes module is installed
	 * 
	 * @param string $theme theme guid
	 * @param string $module module name
	 * @return string
	 */
	public function is_module_theme_exists($theme, $module){
		$module_theme_path = MODULEPATH . $module . "/views/" . $theme;

		if(!is_dir($module_theme_path)){
			$theme_data = $this->get_theme_data($theme);
			if(!empty($theme_data)){
				$theme_type = $theme_data["type"];
			}else{
				$theme_type = $this->get_current_theme_type();
			}
			$theme = $this->default_settings[$theme_type]["theme"];
		}

		return $theme;
	}

	/**
	 * Load theme data
	 * 
	 * @param string $theme theme guid
	 * @return array
	 */
	public function get_theme_data($theme){
		$theme_settings_file = $this->theme_default_full_path . $theme . "/theme.php";
		if(!file_exists($theme_settings_file)){
			return false;
		}
		require $theme_settings_file;
		if(empty($_theme)){
			return false;
		}
		return $_theme;
	}

	/**
	 * Return current theme type
	 * 
	 * @return void
	 */
	public function get_current_theme_type(){
		if($this->CI->router->is_admin_class){
			return "admin";
		}else{
			return "user";
		}
	}

	/**
	 * Format theme settings
	 * 
	 * @param string $module module name
	 * @param string $theme_type theme type
	 * @param string $theme theme guid
	 * @param string $scheme scheme guid
	 * @return array
	 */
	public function format_theme_settings($module='', $theme_type='', $theme='', $scheme=''){
		$theme_data = array();

		if(empty($theme_type)){
			$theme_type = $this->get_current_theme_type();
		}

		if(!empty($theme)){
			$theme_data = $this->get_theme_data($theme);

			if($theme_data === false){
				$theme = $scheme = '';
			}else{
				$active_settings = $this->return_theme_base_data($theme);
			}
		}

		if(empty($theme_data)){
			$active_settings = $this->return_active_settings($theme_type);
			$theme = $active_settings["theme"];
			$scheme = $active_settings["scheme"];

			if(empty($theme)){
				return false;
			}

			$theme_data = $this->get_theme_data($theme);
		}

		if(empty($scheme)){
			$scheme = $active_settings["scheme"];
		}

		$theme_path = $this->theme_default_path . $theme .'/';
		$img_path = $this->theme_default_path . $theme .'/img/';
		$img_set_path = $this->theme_default_path . $theme .'/sets/' . $scheme . '/img/';
		$css_path = $this->theme_default_path . $theme .'/sets/' . $scheme . '/css/';
		$scss_path = $this->theme_default_path . $theme .'/sets/' . $scheme . '/scss/';
		$logo_path = $this->theme_default_path . $theme .'/logo/';
		$mobile_logo_path = $this->theme_default_path . $theme .'/mobile-logo/';

		$format = array(
			"theme" => $theme,
			"type" => $theme_type,
			"scheme" => $scheme,
			"img_path" => $img_path,
			"img_set_path" => $img_set_path,
			"css_path" => $css_path,
			"scss_path" => $scss_path,
			"theme_path" => $theme_path,
			"logo" => array(
				"width" => $active_settings["logo_width"],
				"height" => $active_settings["logo_height"],
				"name" => $active_settings["logo"],
				"path" => $logo_path.$active_settings["logo"]
			),
			"mini_logo" => array(
				"width" => $active_settings["mini_logo_width"],
				"height" => $active_settings["mini_logo_height"],
				"name" => $active_settings["mini_logo"],
				"path" => $logo_path.$active_settings["mini_logo"]
			),
			"mobile_logo" => array(
				"width" => $active_settings["mobile_logo_width"],
				"height" => $active_settings["mobile_logo_height"],
				"name" => $active_settings["mobile_logo"],
				"path" => $mobile_logo_path.$active_settings["mobile_logo"]
			),
		);

		if(!empty($module)){
			$module_theme = $this->is_module_theme_exists($theme, $module);
			$module_theme_path = MODULEPATH_RELATIVE . $module . "/views/" . $module_theme .'/';
			$format["theme_module_path"] = $module_theme_path;
			$format["css_module_path"] = $module_theme_path . 'css/';
		}
		return $format;
	}

	/**
	 * Add css file
	 * 
	 * @param string $path_to_file path to css file
	 * @return boolean
	 */
	public function add_css($path_to_file){
		if(!in_array($path_to_file, $this->css)) $this->css[] = $path_to_file;
		return true;
	}
	
	/**
	 * Add css file
	 * 
	 * @param string $path_to_file path to css file
	 * @return boolean
	 */
	public function add_theme_css($filename, $media='all'){
		if(!isset($this->theme_css[$media])) {
			$this->theme_css[$media] = array();
		}
		if(!in_array($filename, $this->theme_css[$media])){
			$this->theme_css[$media][] = $filename;
		}
		return true;
	}

	/**
	 * Return html to include css files
	 * 
	 * find router theme_type and include active theme css at first
	 *
	 * @param string $theme theme guid
	 * @param string $scheme scheme guid
	 * @return string
	 */
	public function get_include_css_code($theme='', $scheme=''){
		$html = "";

		$theme_type = $this->get_current_theme_type();

		if(!$theme && !$scheme){
			$active_settings = $this->return_active_settings($theme_type);
			$theme = $active_settings["theme"];
			$scheme = $active_settings["scheme"];
		}

		
		
		$css_url = $this->theme_default_url . $theme .'/sets/' . $scheme . '/css/';
		$css_path = $this->theme_default_full_path . $theme .'/sets/' . $scheme . '/css/';

		$lang_id = $this->CI->pg_language->current_lang_id;
		if(INSTALL_MODULE_DONE && $lang_id){
			$lang_data = $this->CI->pg_language->get_lang_by_id($lang_id);
		}else{
			$lang_data['rtl'] = 'ltr';
		}

		if(!empty($this->css)){
			foreach($this->css as $css_file){
				$css_file = str_replace(array('[rtl]', '[dir]'), $lang_data["rtl"], $css_file);
				$html .= '<link href="' . SITE_VIRTUAL_PATH . $css_file . '" rel="stylesheet" type="text/css" media="all">'."\n";
			}
		}
	
		if(!empty($this->theme_css)){
			foreach($this->theme_css as $media=>$css_files){
				foreach($css_files as $css_file){
					$css_file = str_replace(array('[rtl]', '[dir]'), $lang_data["rtl"], $css_file);
					$html .= '<link href="' . $css_url . $css_file . '" rel="stylesheet" type="text/css" media="'.$media.'">'."\n";
				}
			}
		}else{
			$theme_data = $this->get_theme_data($theme);
			unset($theme_data['css']['mobile']);
			
			$css_default_url = $this->theme_default_url . $theme .'/sets/' . $theme_data["default_scheme"] . '/css/';
			$css_default_path = $this->theme_default_full_path . $theme .'/sets/' . $theme_data["default_scheme"] . '/css/';
			
			if(isset($theme_data["css"]) && !empty($theme_data["css"])){
				if($this->print_type == 'pdf'){
					///// get only print css
					foreach($theme_data["css"] as $css_data){
						$css_data["file"] = str_replace(array('[rtl]', '[dir]'), $lang_data["rtl"], $css_data["file"]);
						if ($css_data["media"] != 'print') {
							continue;
						}
						if(file_exists($css_path . $css_data["file"])){
							$html .= '	<link href="' . $css_url . $css_data["file"] . '" rel="stylesheet" type="text/css" media="all">'."\n";
						}elseif(file_exists($css_default_path . $css_data["file"])){
							$html .= '	<link href="' . $css_default_url . $css_data["file"] . '" rel="stylesheet" type="text/css" media="all">'."\n";
						}
					}
				}else{
					foreach($theme_data["css"] as $css_data){
						$css_data["file"] = str_replace(array('[rtl]', '[dir]'), $lang_data["rtl"], $css_data["file"]);
						if(file_exists($css_path . $css_data["file"])){
							$html .= "	<link href='" . $css_url . $css_data["file"] . "' rel='stylesheet' type='text/css' ".($css_data["media"]?("media='".$css_data["media"]."'"):"").">\n";
						}elseif(file_exists($css_default_path . $css_data["file"])){
							$html .= "	<link href='" . $css_default_url . $css_data["file"] . "' rel='stylesheet' type='text/css' ".($css_data["media"]?("media='".$css_data["media"]."'"):"").">\n";
						}
					}
				}
			}
		}
		
		return $html;
	}

	/**
	 * Adds javascript file to an array whose elements are added to the page.
	 *
	 * @param string $path_to_file path to javascript file
	 * @param string $module module guid
	 * @return boolean
	 */
	public function add_js($path_to_file, $module = null){
		if(is_null($module)) {
			if(!in_array($path_to_file, $this->js)) {
				$this->js[] = $path_to_file;
			}
		} else {
			if(!isset($this->js[$module])) {
				$this->js[$module] = array();
			}
			if(!in_array($path_to_file, $this->js[$module])) {
				$this->js[$module][] = $path_to_file;
			}
		}
		return true;
	}

	/**
	 * Return html to include js files
	 *
	 * @return string
	 */
	public function get_include_js_code(){
		$html = "";

		$js_url = APPPATH_VIRTUAL . 'js/';

		if(!empty($this->js)){
			foreach($this->js as $module => $js_file){
				if(is_array($js_file)) {
					foreach($js_file as $js_file){
						$js_module_url = APPPATH_VIRTUAL . 'modules/' . $module . '/js/';
						$html .= "	<script type='text/javascript' src='". $js_module_url . $js_file . "'></script>\n";
					}
				} else {
					$html .= "	<script type='text/javascript' src='". $js_url . $js_file . "'></script>\n";
				}
			}
		}

		return $html;
	}

	/**
	 * Install theme properties related to langauge
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_add($lang_id){
		$this->CI->load->dbforge();
		$fields = array("logo_{$lang_id}" => array(
			'type' => 'VARCHAR',
			'constraint' => '255',
			'null' => FALSE,
		));
		$this->CI->dbforge->add_column(THEMES_TABLE, $fields);
		$fields = array("mini_logo_{$lang_id}" => array(
			'type' => 'VARCHAR',
			'constraint' => '255',
			'null' => FALSE,
		));
		$this->CI->dbforge->add_column(THEMES_TABLE, $fields);
		$fields = array("mobile_logo_{$lang_id}" => array(
			'type' => 'VARCHAR',
			'constraint' => '255',
			'null' => FALSE,
		));
		$this->CI->dbforge->add_column(THEMES_TABLE, $fields);
		return;
	}

	/**
	 * Uninstall theme properties related to langauge
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_delete($lang_id){
		$this->CI->load->dbforge();
		$field_name = "logo_" . $lang_id;
		$this->CI->dbforge->drop_column(THEMES_TABLE, $field_name);
		$field_name = "mini_logo_" . $lang_id;
		$this->CI->dbforge->drop_column(THEMES_TABLE, $field_name);
		$field_name = "mobile_logo_" . $lang_id;
		$this->CI->dbforge->drop_column(THEMES_TABLE, $field_name);
		return;
	}
}
