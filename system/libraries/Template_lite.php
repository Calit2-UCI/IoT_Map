<?php

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(BASEPATH . "libraries/template_lite/class.template.php");

/**
 * Template Lite Class
 *
 * A small HTML template engine which supports most of the Smarty
 * template engine functions and filters.
 *
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category	Libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class CI_Template_lite extends Template_Lite {
	/**
	 * Path to general styles
	 * 
	 * @var string
	 */
	public $general_path = "";
	
	/**
	 * User theme name
	 * 
	 * @var string
	 */
	public $user_theme = "";
	
	/**
	 * Admin theme name
	 * 
	 * @var string
	 */
	public $admin_theme = "";
	
	/**
	 * Path to user styles
	 * 
	 * @var string
	 */
	public $user_css = "";
	
	/**
	 * Path to modules directiory
	 * 
	 * @var string
	 */
	public $module_path = MODULEPATH_RELATIVE;
	
	/**
	 * Path to module templates
	 * 
	 * @var string
	 */
	public $module_templates = "/views/";
	
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;
	
	/**
	 * Set of plugins directories
	 * 
	 * @var array
	 */
	public $plugins_dir = array("plugins");
	
	/**
	 * Set of page links
	 * 
	 * @var array
	 */
	public $page_links = array();
	
	/**
	 * Set of page buttons
	 * 
	 * @var array
	 */
	public $page_buttons = array();
	
	/**
	 * Links delimiter in pagination block by default
	 * 
	 * @var string 
	 */
	public $default_link_delimeter = '&nbsp;|&nbsp;';
	
	/**
	 * Buttons delimiter in pagination block by default
	 * 
	 * @var string 
	 */
	public $default_button_delimeter = '&nbsp;';
	
	/**
	 * Link set guid by default
	 * 
	 * @var string 
	 */
	public $default_link_set = 'default';
	
	/**
	 * Button set guid by default
	 * 
	 * @var string 
	 */
	public $default_button_set = 'default';

	/**
	 * Constructor
	 *
	 * @return CI_Template_lite
	 */
	public function CI_Template_lite() {
		parent::Template_Lite();

		header('Content-type: text/html; charset=utf-8');

		$this->compile_dir = TEMPPATH . "templates_c/";
		$this->general_path = APPPATH . "views/";
		if(defined('TPL_FORCE_COMPILE')) {
			$this->force_compile = TPL_FORCE_COMPILE;
		} else {
			$this->force_compile = false;
		}
		if(defined('TPL_DEBUGGING')) {
			$this->debugging = TPL_DEBUGGING;
		} else {
			$this->debugging = false;
		}
//		$this->load_filter('output', 'trimwhitespace');

		$this->assign("site_url", site_url());
		$this->assign("site_root", "/" . SITE_SUBFOLDER);
		$this->assign("base_url", base_url());
		$this->assign("general_path_relative", APPPATH_VIRTUAL . "views/");
		$this->assign("js_folder", APPLICATION_FOLDER . "js/");

		/**
		 * Assign common variables
		 */
		log_message('debug', "CI_Template_lite Class Initialized");
	}

	/**
	 * Check template is exists
	 * 
	 * @param string $resource_name resource name
	 * @param string $theme_type theme type
	 * @param string $module module guid
	 * @return boolean
	 */
	public function template_exists($resource_name, $theme_type = null, $module = null) {
		if (!isset($module)) {
			return parent::template_exists($resource_name);
		}
		if (!isset($this->CI)) {
			$this->CI = & get_instance();
		}

		$module_name = ($module === true) ? $this->CI->router->class : $module;
		$theme_data = $this->CI->pg_theme->format_theme_settings($module_name, $theme_type);

		if (strpos($resource_name, '.') === false) {
			$resource_name .= '.tpl';
		}
		if ($module !== false) {
			if (file_exists(SITE_PHYSICAL_PATH . $theme_data["theme_module_path"] . $resource_name)) {
				$path = SITE_PHYSICAL_PATH . $theme_data["theme_module_path"] . $resource_name;
			} else {
				$path = SITE_PHYSICAL_PATH . $theme_data["theme_path"] . $resource_name;
			}
		} else {
			$path = SITE_PHYSICAL_PATH . $theme_data["theme_path"] . $resource_name;
		}

		return file_exists($path);
	}

	/**
	 * Call Template_lite display/fetch method (depends on @param $display)
	 *
	 * @param string $resource_name
	 * @param string $theme - null - define by the current controller,
	 * 								 what theme will be load (admin or user)
	 * 						  'user' - load template from $this->user_theme
	 * 						  'admin' - load template from $this->admin_theme
	 * @param mixed (boolean/string) $module
	 * 						  true - search for the template file in the module views
	 * 						  false - search for the template file in the general views
	 * 						  string - module name
	 * @param integer $cache_id cache identifier
	 * @param boolean $display output to browser
	 * @return string
	 */
	public function view($resource_name, $theme_type = null, $module = true, $cache_id = null, $display = true, $system_messages = true) {
		if (!isset($this->CI)) {
			$this->CI = & get_instance();
		}

		///// preview mode
		if( !empty($_SESSION['change_color_scheme'])){
			$preview_theme = $_SESSION["preview_theme"];
			$preview_scheme = $_SESSION["preview_scheme"];
		}else{
			$preview_theme = '';
			$preview_scheme = '';
		}

		$module_name = ($module === true) ? $this->CI->router->class : $module;
		$theme_data = $this->CI->pg_theme->format_theme_settings($module_name, $theme_type, $preview_theme, $preview_scheme);

		if (strpos($resource_name, '.') === false) {
			$resource_name .= '.tpl';
		}

		$img_folder_old = isset($this->_vars['img_folder']) ? $this->_vars['img_folder'] : '';
		$css_folder_old = isset($this->_vars['css_folder']) ? $this->_vars['css_folder'] : '';
		$logo_settings_old = isset($this->_vars['logo_settings']) ? $this->_vars['logo_settings'] : '';

		$this->assign("color_scheme", $preview_scheme?:$theme_data["scheme"]);
		$this->assign("img_folder", $theme_data["img_path"]);
		$this->assign("css_folder", $theme_data["css_path"]);
		$this->assign("logo_settings", $theme_data["logo"]);
		$this->assign("mini_logo_settings", $theme_data["mini_logo"]);
		$this->assign("js_folder", APPLICATION_FOLDER . 'js/');

		//// language
		if (INSTALL_MODULE_DONE) {
			$language_data = $this->CI->pg_language->get_lang_by_id($this->CI->pg_language->current_lang_id);
			$this->assign("_LANG", $language_data);
			// Direction mark (&rtm; | &ltm;)
			$this->assign("DM", DM);
			$this->assign("DEMO_MODE", DEMO_MODE);
			if (DEMO_MODE) {
				$this->CI->config->load('demo_data', TRUE);
				$login_settings = $this->CI->config->item('login_settings', 'demo_data');
				$this->assign("demo_login_settings", $login_settings);

				$demo_user_type = $this->CI->session->userdata("demo_user_type");
				if (!$demo_user_type) {
					$demo_user_type = "user";
				}
				$this->assign("demo_user_type", $demo_user_type);
				$this->assign("demo_user_type_login_settings", $login_settings[$demo_user_type]);
				
				$copyright = $this->CI->config->item('copyright', 'demo_data');
				$this->assign("demo_copyright", $copyright);
			}

			$this->assign("auth_type", $this->CI->session->userdata("auth_type"));
		}

		if ($system_messages) {
			$predefined["error"] = $this->CI->system_messages->get_messages('error');
			$predefined["info"] = $this->CI->system_messages->get_messages('info');
			$predefined["success"] = $this->CI->system_messages->get_messages('success');
			$predefined["header"] = $this->CI->system_messages->get_data('header');
			$predefined["subheader"] = $this->CI->system_messages->get_data('subheader');
			$predefined["help"] = $this->CI->system_messages->get_data('help');
			$predefined["back_link"] = $this->CI->system_messages->get_data('back_link');
			$this->assign("_PREDEFINED", $predefined);
		}

		if ($module !== false) {
			static $modules = array();
			if(isset($modules[$module.'_'.$resource_name])){
				$resource_name = $modules[$module.'_'.$resource_name];
			}else{	
				$resource_key = $resource_name;
				
				if(!empty($language_data)) {
					$module_lang_css = $theme_data["css_module_path"] . "style-" . $language_data["rtl"] . ".css";
					if(file_exists(SITE_PHYSICAL_PATH.$module_lang_css)){
						$this->CI->pg_theme->add_css($module_lang_css);
					}
				}
				
				$module_css = $theme_data["css_module_path"] . "style.css";
				if(file_exists(SITE_PHYSICAL_PATH.$module_css)){
					$this->CI->pg_theme->add_css($module_css);
				}

				$this->assign("module_tpl_path_relative", base_url() . $theme_data["theme_module_path"]);
				$this->assign("module_path_relative", site_url() . ($this->CI->router->is_admin_class ? 'admin/' : '') . (($module === true) ? $this->CI->router->class : $module) . '/');

				if (file_exists(SITE_PHYSICAL_PATH . $theme_data["theme_module_path"] . $resource_name)) {
					$resource_name = SITE_PHYSICAL_PATH . $theme_data["theme_module_path"] . $resource_name;
				} elseif (file_exists(SITE_PHYSICAL_PATH . $theme_data["theme_path"] . $resource_name)) {
					$resource_name = SITE_PHYSICAL_PATH . $theme_data["theme_path"] . $resource_name;
				} elseif (file_exists($this->template_dir . $resource_name)) {
					$resource_name = $this->template_dir . $resource_name;
				} else {
					log_message('error', 'File "' . $resource_name . '" does not exist');
					return false;
				}
				
				$modules[$module.'_'.$resource_key] = $resource_name;
			}
		} else {
			$resource_name = SITE_PHYSICAL_PATH . $theme_data["theme_path"] . $resource_name;
		}

		if (!$display) {
			$content = parent::fetch($resource_name, $cache_id, $display);

			if ($img_folder_old) {
				$this->assign("img_folder", $img_folder_old);
			}
			if ($css_folder_old) {
				$this->assign("css_folder", $css_folder_old);
			}
			if ($logo_settings_old) {
				$this->assign("logo_settings", $logo_settings_old);
			}

			return $content;
		}else {
			if ($img_folder_old) {
				$this->assign("img_folder", $img_folder_old);
			}
			if ($css_folder_old) {
				$this->assign("css_folder", $css_folder_old);
			}
			if ($logo_settings_old) {
				$this->assign("logo_settings", $logo_settings_old);
			}

			parent::fetch($resource_name, $cache_id, $display);
		}
	}

	/**
	 * Call Template_lite fetch method
	 *
	 * @param string $resource_name
	 * @param string $theme - null - define by the current controller,
	 * 								 what theme will be load (admin or user)
	 * 						  'user' - load template from $this->user_theme
	 * 						  'admin' - load template from $this->admin_theme
	 * @param mixed (boolean/string) $module
	 * 						  true - search for the template file in the module views
	 * 						  false - search for the template file in the general views
	 * 						  string - module name
	 * @param integer $cache_id cache identifier
	 * @return string
	 */
	public function fetch($resource_name, $theme_type = null, $module = true, $cache_id = null) {
		return $this->view($resource_name, $theme_type, $module, $cache_id, false, false);
	}

	/**
	 * Call Template_lite fetch method with pre_view hooks.
	 *
	 * @param string $resource_name
	 * @param string $theme - null - define by the current controller,
	 * 								 what theme will be load (admin or user)
	 * 						  'user' - load template from $this->user_theme
	 * 						  'admin' - load template from $this->admin_theme
	 * @param mixed (boolean/string) $module
	 * 						  true - search for the template file in the module views
	 * 						  false - search for the template file in the general views
	 * 						  string - module name
	 * @param integer $cache_id cache identifier
	 * @return string
	 */
	public function fetch_final($resource_name, $theme_type = null, $module = true, $cache_id = null) {
		ob_start();
		$this->view($resource_name, $theme_type, $module, $cache_id, true, false);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Return guid of current theme
	 * 
	 * @param string $theme_type theme type
	 * @param string $module module name
	 * @return string
	 */
	public function get_current_theme_gid($theme_type = '', $module = '') {
		if (!$theme_type) {
			$theme_type = $this->CI->pg_theme->get_current_theme_type();
		}
		$active_settings = $this->CI->pg_theme->return_active_settings($theme_type);

		$theme = $active_settings["theme"];
		if (!empty($module)) {
			$theme = $this->CI->pg_theme->is_module_theme_exists($theme, $module);
		}
		return $theme . "/";
	}

	/**
	 * Remove all compiled templates
	 * 
	 * Return number of files
	 * 
	 * @return integer  
	 */
	private function _delete_all_compiled() {
		$count = 0;
		foreach (new DirectoryIterator($this->compile_dir) as $fileInfo) {
			if ($fileInfo->isDot()) {
				continue;
			} elseif (substr($fileInfo->getBasename(), 0, 2) === 'c_') {
				unlink($fileInfo->getRealPath());
				$count++;
			}
		}
		return $count;
	}

	/**
	 * Remove compiled templates of the module
	 * 
	 * Return number of files
	 * 
	 * @param string $module module name
	 * @return integer
	 */
	public function delete_compiled($module = null) {
		if (null === $module) {
			return $this->_delete_all_compiled();
		}
		$count = 0;
		if (!isset($this->CI)) {
			$this->CI = & get_instance();
		}
		$theme_data = $this->CI->pg_theme->format_theme_settings($module, 'user');
		$this->template_dir = $this->_get_dir($this->template_dir);
		$tpl_path = SITE_PHYSICAL_PATH . $theme_data["theme_module_path"];
		if(!file_exists($tpl_path)) {
			return 0;
		}
		foreach (new DirectoryIterator($tpl_path) as $fileInfo) {
			if ($fileInfo->isDot() || $fileInfo->isFile()) {
				continue;
			}
			$tpl_name = $this->_get_resource(str_ireplace('\\', '/', $fileInfo->getRealPath()));
			$c_name = $this->compile_dir . 'c_' . $this->get_compiled_name($tpl_name);
			if (file_exists($c_name)) {
				unlink($c_name);
				$count++;
			}
		}
		return $count;
	}

}
