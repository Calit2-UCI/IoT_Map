<?php 

/**
 * Libraries
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('SEO_MODULES_TABLE', DB_PREFIX.'seo_modules');
define('SEO_SETTINGS_TABLE', DB_PREFIX.'seo_settings');

/**
 * PG Seo Model
 * 
 * @package 	PG_Core
 * @subpackage 	Libraries
 * @category	libraries
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class CI_Pg_seo {

	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	public $CI;
	
	/**
	 * Link to database object
	 * 
	 * @var object
	 */
	public $DB;
	
	/**
	 * Store settings in database
	 * 
	 * @param boolean
	 */
	public $use_db=false;
	
	/**
	 * Use rewrite rules
	 * 
	 * @param boolean
	 */
	public $use_seo_links_rewrite = true;

	/**
	 * Regular expression for full text literal
	 * 
	 * @param string
	 */
	public $reg_exp_literal_whole = '^[\pL\pN\pM\pZ,\'\!\@\^\&\*\(\)\+\-\!\/_=:\.]+$';
	
	/**
	 * Regular expression for full text numeric
	 * 
	 * @param string
	 */
	public $reg_exp_numeric_whole = '^[\pN]+$';
	
	/**
	 * Regular expression for full text last part
	 * 
	 * @param string
	 */
	public $reg_exp_last_whole = '^[\pL\pN\pM\pZ,\'\!\@\^\&\*\(\)\+\-\!\/_=:\.]*$';

	/**
	 * Regular expression for literal
	 * 
	 * @param string
	 */
	public $reg_exp_literal = '[\pL\pN\pM\pZ,\'\!\@\^\&\*\(\)\+\-\!\/_=:\.]+';
	
	/**
	 * Regular expression for numeric
	 * 
	 * @param string
	 */
	public $reg_exp_numeric = '[\pN]+';
	
	/**
	 * Regular expression for last part
	 * 
	 * @param string
	 */
	public $reg_exp_last = '[\pL\pN\pM\pZ,\'\!\@\^\&\*\(\)\+\-\!\/_=:\.]*';
	
	/**
	 * Regular expression for last literal 
	 * 
	 * @param string
	 */
	public $reg_exp_literal_last = '[\pL\pN\pM\pZ,\'\!\@\^\&\*\(\)\+\-\!\/_=:\.]*';
	
	/**
	 * Regular expression for last numeric
	 * 
	 * @param string
	 */
	public $reg_exp_numeric_last = '[\pN]*';

	/**
	 * Regular expression for no literal 
	 * 
	 * @param string
	 */
	public $not_reg_exp_literal = '[^\pL\pN\pM\pZ,\'\!\@\^\&\*\(\)\+\-\!\/_=:\.]+';
	
	/**
	 * Regular expression for no numeric
	 * 
	 * @param string
	 */
	public $not_reg_exp_numeric = '[^\pN]+';
	
	/**
	 * Regular expression for no last
	 * 
	 * @param string
	 */
	public $not_reg_exp_last = '[^\pL\pN\pM\pZ,\'\!\@\^\&\*\(\)\+\-\!\/_=:\.]*';

	/**
	 * Modules properties in data source
	 * 
	 * @param array
	 */
	private $_modules_fields = array(
		'id', 
		'module_gid', 
		'model_name', 
		'get_settings_method', 
		'get_rewrite_vars_method', 
		'get_sitemap_urls_method',
	);

	/**
	 * General settings properties in data source
	 * 
	 * @param array
	 */
	private $_settings_fields = array(
		'id',
		'controller',
		'module_gid',
		'method',
		'noindex',
		'url_template',
		'lang_in_url',
	);
	
	/**
	 * Meta properties in data source
	 * 
	 * @param array
	 */
	private $_meta_fields = array(
		'title',
		'keyword',
		'description',
		'header',
	);
	
	/**
	 * Open graph properties in data source
	 * 
	 * @param array
	 */
	private $_og_fields = array(
		'og_title',
		'og_type',
		'og_description',
	);
	
	/**
	 * Seo tags cache
	 * 
	 * @param array
	 */
	public $seo_tags_html_cache = array();
	
	/**
	 * Default settings, preinstalled settings 
	 * 
	 * if install module not installed and/or database settings not valid
	 * 
	 * @var array
	 */
	public $default_settings=array(
		"admin" => array(
			"controller" => "admin",
			"module_gid" => "",
			"method" => "",
			"title" => "",
			"keyword" => "",
			"description" => "",
			"header" => "",
			"templates" => array(),
			"url_template" => "",
			"lang_in_url" => ""
		),
		"user" => array(
			"controller" => "user",
			"module_gid" => "",
			"method" => "",
			"title" => "",
			"keyword" => "",
			"description" => "",
			"header" => "",
			"og_description" => "",
			"og_title" => "",
			"og_type" => "site",
			"templates" => array(),
			"url_template" => "",
			"lang_in_url" => ""
		)
	);

	/**
	 * Settings data from current page
	 * 
	 * @param array
	 */
	public $seo_dynamic_data = array();
	
	/**
	 * Settings guid from current page
	 * 
	 * @param string
	 */
	private $seo_page_tags = array();

	/**
	 * Language prefix
	 * 
	 * @param string
	 */
	private $lang_prefix = '';

	/**
	 * Module data cache
	 * 
	 * @param array
	 */
	private $seo_module_cache = array();
	
	/**
	 * Module keys cache
	 * 
	 * @param array
	 */
	private $seo_key_cache = array();
	
	/**
	 * Url schema cache
	 * 
	 * @param array
	 */
	private $url_scheme_cache = array();
	
	/**
	 * Settings cache
	 * 
	 * @param array
	 */
	private $settings_cache = array();

	/**
	 * Module settings cache
	 * 
	 * @param array
	 */
	private $module_settings_cache = array();

	/**
	 * Class constructor
	 *
	 * @return CI_PG_Seo
	 */
	public function CI_PG_Seo(){
		$this->CI =& get_instance();
		
		if(INSTALL_MODULE_DONE){
			$this->use_db = true;
			$this->DB = &$this->CI->db;
		
			foreach($this->CI->pg_language->languages as $lang){
				$this->_settings_fields[] = 'meta_'.$lang['id'];
				$this->_settings_fields[] = 'og_'.$lang['id'];
			}
			
			$this->preload_settings_cache();
			$this->preload_modules_cache();
		}
		
		$global_templates = array();
		$this->set_seo_data($global_templates);
		if(INSTALL_DONE) {
			$this->DB->memcache_tables(array(SEO_MODULES_TABLE, SEO_SETTINGS_TABLE));
		}
	}

	/**
	 * Return seo settings by default
	 * 
	 * @param string $controller user mode
	 * @return array
	 */
	public function get_global_default_settings($controller='user'){
		$return = $this->default_settings[$controller];
		$default_settings = $this->get_settings($controller);
		if(!empty($default_settings)) $return = array_merge($return, $default_settings);
		return $return;
	}
	
	//// settings cache functions
	
	/**
	 * Fill cache of modules settings
	 * 
	 * @return void
	 */
	public function preload_settings_cache(){
		$this->DB->select(implode(',', $this->_settings_fields))
				 ->from(SEO_SETTINGS_TABLE)
				 ->where('controller !=', 'custom');
		$results = $this->DB->get()->result_array();
		foreach($results as $result){
			foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
				if($result['meta_'.$lang_id]){
					$result['meta_'.$lang_id] = (array)unserialize($result['meta_'.$lang_id]);
				}else{
					$result['meta_'.$lang_id] = array();
				}
				
				if($result['og_'.$lang_id]){
					$result['og_'.$lang_id] = (array)unserialize($result['og_'.$lang_id]);
				}else{
					$result['og_'.$lang_id] = array();
				}
			}
			$this->settings_cache[] = $result;
		}
	}
	
	/**
	 * Clear cache of modules settings
	 * 
	 * @return void
	 */
	public function clear_settings_cache(){
		$this->settings_cache = array();
	}
	
	/**
	 * Return module settings from cache
	 * 
	 * @param string $controller user mode
	 * @param string $module_gid module guid
	 * @param string $method method name
	 * @return array
	 */
	public function get_settings_from_cache($controller='user', $module_gid='', $method=''){
		if(empty($this->settings_cache)){
			$this->preload_settings_cache();
		}
		if(!empty($this->settings_cache)){
			foreach($this->settings_cache as $settings){
				if($settings['controller'] == $controller && $settings['module_gid'] == $module_gid && $settings['method'] == $method ){
					return $settings;
				}
			}
		}
	
		return array();
	}
	
	/**
	 * Return all settings from cache
	 * 
	 * @param string $controller user mode
	 * @param string $module_gid module guid
	 * @param string $method method name
	 * @return array
	 */
	public function get_all_settings_from_cache($controller='user', $module_gid='', $method=''){
		$return = array();
		if(empty($this->settings_cache)){
			$this->preload_settings_cache();
		}
		if(!empty($this->settings_cache)){
			foreach($this->settings_cache as $settings){
				$allow_controller = $allow_module = $allow_method = false;
				if(!$controller || ($controller && $settings['controller'] == $controller)){
					$allow_controller = true;
				}
				if(!$module_gid || ($module_gid && $settings['module_gid'] == $module_gid)){
					$allow_module = true;
				}
				if(!$method || ($method && $settings['method'] == $method)){
					$allow_method = true;
				}
				
				if($allow_controller && $allow_module && $allow_method ){
					$return[] = $settings;
				}
			}
		}

		return $return;
	}

	/*
	 * Return one entry from base 
	 * 
	 * if $method='' - returns general settings for module, if $module_gid='' - general settings for controller 
	 * If $lang_ids is empty return settings for current lang, else settings array for selected languages
	 *
	 * @param string $controller user mode controller
	 * @param string $module_gid module guid
	 * @param string $method method name
	 * @return array
	 */
	public function get_settings($controller='user', $module_gid='', $method=''){
		if(!$this->use_db) return false;
		$settings = $this->get_settings_from_cache($controller, $module_gid, $method);
		return $settings;
	}

	/*
	 * Return settings array for not empty parametrs
	 * 
	 * f.e. $module_gid='' will be returned all entries for $module_gid 
	 *
	 * @param string $controller user mode controller
	 * @param strign $module_gid module gid
	 * @param string $method method name
	 * @return array
	 */
	public function get_all_settings($controller='user', $module_gid='', $method=''){
		if(!$this->use_db) return false;
		$settings = $this->get_all_settings_from_cache($controller, $module_gid, $method);
		return $settings;
	}

	/*
	 * Save settings for $controller, $module_gid, $method in base
	 *
	 * @param string $controller user mode controller 
	 * @param string $module_gid module gid
	 * @param string $method method_name
	 * @param array $data settings data
	 * @return void
	 */
	public function set_settings($controller, $module_gid, $method, $data){
		$sett_data = array(
			'controller' => $controller, 
			'module_gid' => $module_gid, 
			'method' => $method, 
		);
		
		$settings = $this->get_settings($controller, $module_gid, $method);

		$sett_data['noindex'] = $settings['noindex'] ? 1 : 0;
		if(isset($data["noindex"])){
			$sett_data['noindex'] = $data['noindex'] ? 1 : 0; 
		}
		
		$sett_data['url_template'] = strval($settings['url_template']);
		if(isset($data["url_template"])){
			$sett_data['url_template'] = strval($data["url_template"]);
		}

		$sett_data['lang_in_url'] = $settings['lang_in_url'] ? 1 : 0;
		if(isset($data["lang_in_url"])){
			$sett_data['lang_in_url'] = $data['lang_in_url'] ? 1 : 0; 
		}

		$languages = $this->CI->pg_language->languages;
		
		foreach($languages as $lang_id=>$lang_data){
			if(isset($data['title'][$lang_id])){
				$sett_data['meta_'.$lang_id]['title'] = $data['title'][$lang_id];
			}else{
				$sett_data['meta_'.$lang_id]['title'] = $settings['meta_'.$lang_id]['title'];
			}
			
			if(isset($data['keyword'][$lang_id])){
				$sett_data['meta_'.$lang_id]['keyword'] = $data['keyword'][$lang_id];
			}else{
				$sett_data['meta_'.$lang_id]['keyword'] = $settings['meta_'.$lang_id]['keyword'];
			}
			
			if(isset($data['description'][$lang_id])){
				$sett_data['meta_'.$lang_id]['description'] = $data['description'][$lang_id];
			}else{
				$sett_data['meta_'.$lang_id]['description'] = $settings['meta_'.$lang_id]['description'];
			}
			
			if(isset($data['header'][$lang_id])){
				$sett_data['meta_'.$lang_id]['header'] = $data['header'][$lang_id];
			}else{
				$sett_data['meta_'.$lang_id]['header'] = $settings['meta_'.$lang_id]['header'];
			}
			
			if(isset($data['og_title'][$lang_id])){
				$sett_data['og_'.$lang_id]['og_title'] = $data['og_title'][$lang_id];
			}else{
				$sett_data['og_'.$lang_id]['og_title'] = $settings['og_'.$lang_id]['og_title'];
			}
			
			if(isset($data['og_type'][$lang_id])){
				$sett_data['og_'.$lang_id]['og_type'] = $data['og_type'][$lang_id];
			}else{
				$sett_data['og_'.$lang_id]['og_type'] = $settings['og_'.$lang_id]['og_type'];
			}
			
			if(isset($data['og_description'][$lang_id])){
				$sett_data['og_'.$lang_id]['og_description'] = $data['og_description'][$lang_id];
			}else{
				$sett_data['og_'.$lang_id]['og_description'] = $settings['og_'.$lang_id]['og_description'];
			}
		
			$sett_data['meta_'.$lang_id] = serialize($sett_data['meta_'.$lang_id]);
			$sett_data['og_'.$lang_id] = serialize($sett_data['og_'.$lang_id]);
		}

		$this->DB->select('COUNT(*) AS cnt');
		$this->DB->from(SEO_SETTINGS_TABLE);
		$this->DB->where('controller', $controller);
		$this->DB->where('module_gid', $module_gid);
		$this->DB->where('method', $method);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results) && intval($results[0]['cnt'])){
			$this->DB->where('controller', $controller);
			$this->DB->where('module_gid', $module_gid);
			$this->DB->where('method', $method);
			$this->DB->update(SEO_SETTINGS_TABLE, $sett_data);
		}else{
			$this->DB->insert(SEO_SETTINGS_TABLE, $sett_data);
		}

		$this->clear_settings_cache();
		return;
	}

	/*
	 * Return seo data using module methods 
	 *
	 * $param string $controller user mode controller
	 * @param string $module_gid module gid
	 * @param string $method method name
	 * @return array
	 */
	public function get_default_settings($controller, $module_gid, $method=''){
		if(!$this->use_db) return false;

		//// if in admin area dont use module settings
		if($controller == 'admin'){
			return false;
		}
		
		if(!empty($method) && isset($this->module_settings_cache[$controller][$module_gid][$method])){
			return $this->module_settings_cache[$controller][$module_gid][$method];
		}

		$module_data = $this->get_seo_module_by_gid($module_gid);

		if(empty($module_data)){
			return false;
		}

		$this->CI->load->model($module_data["module_gid"]."/models/".$module_data["model_name"]);
		if(!method_exists($this->CI->$module_data["model_name"], $module_data["get_settings_method"])) {
			return false;
		}
		$settings = $this->CI->$module_data["model_name"]->$module_data["get_settings_method"]($method);
		if(empty($method)){
			$this->module_settings_cache[$controller][$module_gid] = $settings;
		}else{
			$this->module_settings_cache[$controller][$module_gid][$method] = $settings;
		}

		return $settings;
	}

	/**
	 * Return language prefix
	 * 
	 * @return string
	 */
	public function get_lang_prefix() {
		return $this->lang_prefix;
	}

	/**
	 * Set language prefix
	 * 
	 * @param string $controller user mode controller
	 * @param string $lang_code language code
	 * @return boolean
	 */
	public function set_lang_prefix($controller = 'user', $lang_code=null) {
		$settings = $this->get_settings_from_cache($controller);
		if(!empty($settings['lang_in_url'])) {
			if(is_null($lang_code)){
				$lang_code = $this->CI->pg_language->get_lang_code_by_id($this->CI->pg_language->current_lang_id);
			}
			$this->lang_prefix = $lang_code . '/';
		}
		return true;
	}

	///// seo cache module methods
	
	/**
	 * Preload rewrite settings of modules
	 * 
	 * @return void
	 */
	public function preload_modules_cache(){
		$this->DB->select(implode(',', $this->_modules_fields))->from(SEO_MODULES_TABLE);
		$results = $this->DB->get()->result_array();
		foreach($results as $result){
			$this->seo_module_cache[$result['module_gid']] = $result;
		}
	}
	
	/**
	 * Return modules rewrite settings from cache
	 * 
	 * @param string $module_gid module guid
	 * @return array
	 */
	public function get_seo_module_from_cache($module_gid){
		if(empty($this->seo_module_cache)){
			$this->preload_modules_cache();
		}
	
		if(!empty($this->seo_module_cache[$module_gid])){
			return $this->seo_module_cache[$module_gid];
		}
		return array();
		
	}
	
	/**
	 * Clear cache of modules rewrite settings
	 * 
	 * @return void
	 */
	public function clear_seo_module_cache(){
		$this->seo_module_cache = array();
	}
		
	///// seo module methods
	
	/**
	 * Return value of module rewrite variable
	 * 
	 * @param string $controller user mode controller
	 * @param string $module_gid module guid
	 * @param strign $method method name
	 * @param string $var_from input variable name
	 * @param string $var_to output variable name
	 * @param mixed $value variable value
	 * @return mixed
	 */
	public function get_module_rewrite_var($controller, $module_gid, $method, $var_from, $var_to, $value){
		$module_data = $this->get_seo_module_by_gid($module_gid);

		if(empty($module_data)){
			return false;
		}
	
		$this->CI->load->model($module_data["module_gid"]."/models/".$module_data["model_name"]);
		$value = $this->CI->$module_data["model_name"]->$module_data["get_rewrite_vars_method"]($var_from, $var_to, $value);
		return $value;
	}

	/**
	 * Return module rewrite settings by guid
	 * 
	 * @param string $module_gid module guid
	 * @return array
	 */
	public function get_seo_module_by_gid($module_gid){
		return $this->get_seo_module_from_cache($module_gid);
	}

	/**
	 * Return modules rewrite settings
	 * 
	 * @return array
	 */
	public function get_seo_modules(){
		unset($this->seo_module_cache);
		$this->DB->select(implode(',', $this->_modules_fields))->from(SEO_MODULES_TABLE)->order_by("module_gid ASC");
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $r){
				$this->seo_module_cache[$r["module_gid"]] = $r;
			}
		}
		return $this->seo_module_cache;
	}

	/**
	 * Set module rewrite settings
	 * 
	 * @param string $module_gid module guid
	 * @param array $data module data
	 * @return void
	 */
	public function set_seo_module($module_gid, $data=array()){
		$module_data = $this->get_seo_module_by_gid($module_gid);
		if(empty($module_data)){
			$this->DB->insert(SEO_MODULES_TABLE, $data);
		}else{
			$this->DB->where("module_gid", $module_gid);
			$this->DB->update(SEO_MODULES_TABLE, $data);
		}
		$this->clear_seo_module_cache();
	}

	/**
	 * Remove module rewrite settings
	 * 
	 * @param string $module_gid module guid
	 * @return void
	 */
	public function delete_seo_module($module_gid){
		$this->DB->where("module_gid", $module_gid);
		$this->DB->delete(SEO_MODULES_TABLE);

		$this->DB->where("module_gid", $module_gid);
		$this->DB->delete(SEO_SETTINGS_TABLE);
		
		$this->clear_seo_module_cache();
	}

	/**
	 * Replace blocks on data from page
	 * 
	 * @param array $settings seo settings
	 * @return array 
	 */
	public function parse_seo_data($settings){
		if(!empty($settings['templates'])){
			foreach($settings['templates'] as $tag){
				$value = (!empty($this->seo_dynamic_data[$tag]))?$this->seo_dynamic_data[$tag]:"";
				$pattern = "/\[".preg_quote($tag, '/')."(\|([^\]]*))?\]/i";
				$replace = (!empty($value))?str_replace('$', '\$', $value):"$2";
				if(strlen($settings["title"])){
					$settings["title"] = preg_replace($pattern, $replace, $settings["title"]);
				}
				if(strlen($settings["description"])){
					$settings["description"] = preg_replace($pattern, $replace, $settings["description"]);
				}
				if(strlen($settings["keyword"])){
					$settings["keyword"] = preg_replace($pattern, $replace, $settings["keyword"]);
				}
				if(strlen($settings["header"])){
					$settings["header"] = preg_replace($pattern, $replace, $settings["header"]);
				}
				if(strlen($settings["og_title"])){
					$settings["og_title"] = preg_replace($pattern, $replace, $settings["og_title"]);
				}
				if(strlen($settings["og_type"])){
					$settings["og_type"] = preg_replace($pattern, $replace, $settings["og_type"]);
				}
				if(strlen($settings["og_description"])){
					$settings["og_description"] = preg_replace($pattern, $replace, $settings["og_description"]);
				}
			}
		}
		
		if(isset($this->seo_dynamic_data['canonical'])) $settings['canonical'] = $this->seo_dynamic_data['canonical'];
		if(isset($this->seo_dynamic_data['image'])) $settings['og_image'] = $this->seo_dynamic_data['image'];
		
		return $settings;
	}

	/**
	 * Return seo tags of page
	 * 
	 * @param string $controller user mode controller
	 * @param string $module_gid module guid
	 * @param string $method method name
	 * @return array
	 */
	public function session_seo_tags_html($controller, $module_gid, $method){
		if(empty($this->seo_tags_html_cache[$controller][$module_gid][$method])){
			if($module_gid == 'start' && $method == 'index'){
				$module_gid = $method = '';
			}
			
			$default_data = $this->get_default_settings($controller, $module_gid, $method);
			
			if(empty($default_data)){
				$default_data = $this->get_global_default_settings($controller);
			}
			
			if(!empty($this->seo_page_tags)){
				$default_data = array_merge($default_data, $this->seo_page_tags);
			}else{
				$user_settings = $this->get_settings($controller, $module_gid, $method);
				if(!empty($user_settings) && !empty($user_settings['meta_'.$this->CI->pg_language->current_lang_id])){
					$default_data['title'] = $user_settings['meta_'.$this->CI->pg_language->current_lang_id]['title'];
					$default_data['description'] = $user_settings['meta_'.$this->CI->pg_language->current_lang_id]['description'];
					$default_data['keyword'] = $user_settings['meta_'.$this->CI->pg_language->current_lang_id]['keyword'];
					$default_data['header'] = $user_settings['meta_'.$this->CI->pg_language->current_lang_id]['header'];
					$default_data['og_title'] = $user_settings['og_'.$this->CI->pg_language->current_lang_id]['og_title'];
					$default_data['og_type'] = $user_settings['og_'.$this->CI->pg_language->current_lang_id]['og_type'];
					$default_data['og_description'] = $user_settings['og_'.$this->CI->pg_language->current_lang_id]['og_description'];
					$default_data['noindex'] = $user_settings['noindex'];
				}else{
					$default_data['noindex'] = 0;
					$default_data['lang_in_url'] = 0;
					$default_data['url_template'] = '';
				}
				$default_data = $this->parse_seo_data($default_data);
			}
			
			$uri_string = $this->CI->uri->uri_string();

			$lang_canonical = true;
				
			if(INSTALL_MODULE_DONE && $this->CI->pg_module->is_module_installed('seo')){
				$lang_canonical = $this->CI->pg_module->get_module_config('seo', 'lang_canonical');
			}

			if(empty($default_data["canonical"])){
				if($lang_canonical){
					// Get lang from URI
					$lang_from_uri = $this->CI->router->fetch_lang();
					if($lang_from_uri){
						$default_data["canonical"] = base_url().substr($uri_string, strlen($lang_from_uri)+2);
					}
				}else{
					// Get lang from URI
					$lang_from_uri = $this->CI->router->fetch_lang();
					if(!$lang_from_uri){
						$default_data["canonical"] = site_url().substr($uri_string, 1);
					}
				}
			}
		
			$uri = base_url().substr($uri_string, 1);
			if(!empty($default_data["canonical"]) && urldecode($uri) != urldecode($default_data["canonical"])){
				$default_data["og_url"] = $default_data["canonical"];
			}else{
				$default_data["og_url"] = $uri;
				$default_data["canonical"] = '';
			}
			
			$html["title"] = '	<title>'.strip_tags($default_data["title"]).'</title>'."\n";
			$html["description"] = '	<meta name="Description" content="'.addslashes(strip_tags($default_data["description"])).'">'."\n";
			$html["keyword"] = '	<meta name="Keywords" content="'.addslashes(strip_tags($default_data["keyword"])).'">'."\n";
			$html['robots'] = '<meta name="robots" content="'.($default_data['noindex'] ? 'noindex,nofollow' : 'all').'">'."\n";
			if(!empty($default_data["canonical"])) $html["canonical"] = '	<link rel="canonical" href="'.$default_data["canonical"].'">'."\n";
			if(!empty($default_data['og_title'])) $html['og_title'] = '	<meta property="og:title" content="'.strip_tags($default_data['og_title']).'">'."\n";
			if(!empty($default_data['og_type'])) $html['og_type'] = '	<meta property="og:type" content="'.strip_tags($default_data['og_type']).'">'."\n";
			if(!empty($default_data['og_url'])) $html['og_url'] = '	<meta property="og:url" content="'.$default_data['og_url'].'">'."\n";
			if(!empty($default_data['og_image'])) $html['og_image'] = '	<meta property="og:image" content="'.$default_data['og_image'].'">'."\n";
			$html['og_site_name'] = '	<meta property="og:site_name" content="'.preg_replace('#http(s)?://#', '', site_url()).'">'."\n";
			if(!empty($default_data['og_description'])) $html['og_description'] = '	<meta property="og:description" content="'.strip_tags($default_data['og_description']).'">'."\n";
			$html["header"] = '	<h1>'.$default_data["header"].'</h1>'."\n";
			$html["header_text"] = $default_data["header"];

			$this->seo_tags_html_cache[$controller][$module_gid][$method] = $html;
		}
		return $this->seo_tags_html_cache[$controller][$module_gid][$method];
	}

	/**
	 * Set seo data of page
	 * 
	 * @param array $data seo data of page
	 * @return void
	 */
	public function set_seo_data($data){
		foreach((array)$data as $key=>$value){
			$this->seo_dynamic_data[$key] = $value;
		}
	}

	/**
	 * Validate data of url
	 * 
	 * @param string $module_gid module guid
	 * @param string $method method name
	 * @param array $data method settings
	 * @param array $url_data url data
	 * @return array
	 */
	public function validate_url_data($module_gid, $method, $data, $url_data, $url_postfix){
		$return = array("errors"=> array(), "data" => array());
		$num_vars = array();
		$num_postfix = array();
		if(empty($data) || !array($data)){
			$return["data"]["url_template"] = "";
		}else{
			$prev_block_type = 'text';
			$error_invalid_text_delimiter = false;

			foreach($data as $key=>$block){
				switch($block["type"]){
					case "text":				
						if(empty($block["value"]) || !preg_match('/'.$this->reg_exp_literal_whole.'/i', $block["value"] )){
							$return["errors"][] = l('error_url_text_block_invalid', 'seo')." (".$block["value"].")";
							$block["value"] = preg_replace("/".$this->not_reg_exp_literal."/i", "", $block["value"]);
						}
						$data[$key]["value"] = trim(strtolower($block["value"]));
						
						if(empty($data[$key]["value"])){
							unset($data[$key]);	
						}else{
							$prev_block_type = $block["type"];
						}
					break;
					case "tpl":
					case "opt":
						$reg_exp = ($block["var_type"]=="literal")?$this->reg_exp_literal_whole:$this->reg_exp_numeric_whole;
						$not_reg_exp = ($block["var_type"]=="literal")?$this->not_reg_exp_literal:$this->not_reg_exp_numeric;

						if(!empty($block["var_default"]) && !preg_match('/'.$reg_exp.'/i', $block["var_default"] )){
							$return["errors"][] = l('error_url_tpl_block_default_invalid', 'seo');
							$block["var_default"] = preg_replace("/".$not_reg_exp."/i", "", $block["var_default"]);
						}
						$data[$key]["var_default"] = strtolower($block["var_default"]);
						if(!in_array($block["var_num"], $num_vars) && $block["type"] == "tpl"){
							$num_vars[] = $block["var_num"];
						}
						if($prev_block_type != "text"){
							$error_invalid_text_delimiter = true;
						}
						$prev_block_type = $block["type"];
					break;
					case 'postfix':
						if(!in_array($block["var_num"], $num_postfix)){
							$num_postfix[] = $block["var_num"];
						}
						$prev_block_type = $block["type"];
					break;
				}
			}
			$temp = $data;
			unset($data);
			foreach($temp as $block){
				$data[] = $block;
			}			
			
			///// tpl blocks delimiters is invalid ?
			if($error_invalid_text_delimiter){
				$return["errors"][] = l('error_url_text_delim_invalid', 'seo');
			}

			////// all templates are used?
			if(count($num_vars)<count($url_data)){
				$return["errors"][] = l('error_url_var_num_invalid', 'seo');
			}
	
			////// all templates are used?
			if(count($num_postfix)<count($url_postfix)){
				$return["errors"][] = l('error_url_postfix_num_invalid', 'seo');
			}

			///// if first block not a text
			if($data[0]["type"] != "text" || empty($data[0]["value"])){
				$return["errors"][] = l('error_url_first_block_text', 'seo');
			}else{
				///// get module folder in first part
				$parts = explode("/", $data[0]["value"]);
				if(count($parts) > 1 && $parts[0] != $module_gid && $this->CI->pg_module->get_module_by_gid($parts[0])){
					$return["errors"][] = l('error_url_first_block_module', 'seo');
				}
				if(count($parts) > 1 && $parts[0] == $module_gid && empty($parts[1])){
					$return["errors"][] = l('error_url_third_block_module', 'seo');
				}
			}
			$return["data"]["url_template"] = $this->url_template_transform($module_gid, $method, $data, 'js', "base");
			
			if(strlen($return["data"]["url_template"]) > 200){
				$return["errors"][] = l('error_url_max_length', 'seo');
			}
		}
		
		return $return;
	}

	/**
	 * Transform rules of url
	 * 
	 * @param string $module module guid
	 * @param string $method method name
	 * @param array $data method data
	 * @param string $from input format
	 * @param string $to output format
	 * @return string
	 */
	public function url_template_transform($module, $method, $data, $from, $to){
		if($from == "base"){
			$parsed = array();
			$reg_exp = "/(\[text:(".$this->reg_exp_literal.")\])|(\[tpl:(".$this->reg_exp_numeric."):(".$this->reg_exp_literal."):(numeric|literal):([\w\-_\d]*)\])|(\[postfix:(".$this->reg_exp_numeric."):(".$this->reg_exp_literal."):(numeric|literal)\])|(\[opt:(".$this->reg_exp_literal."):(numeric|literal):([\w\-_\d]*)\])/i";
			preg_match_all($reg_exp, $data, $matches, PREG_SET_ORDER);
			foreach($matches as $match){
				if(!empty($match[1])){
					$parsed[] = array(
						"type" => "text",
						"value" => $match[2],
					);
				}elseif(!empty($match[3])){
					$parsed[] = array(
						"type" => "tpl",
						"var_num" => $match[4],
						"var_name" => $match[5],
						"var_type" => $match[6],
						"var_default" => $match[7],
					);
				}elseif(!empty($match[8])){
					$parsed[] = array(
						"type" => "postfix",
						"var_num" => $match[9],
						"var_name" => $match[10],
						"var_type" => $match[11],
					);
				}else{
					$parsed[] = array(
						"type" => "opt",
						"var_num" => 0,
						"var_name" => $match[13],
						"var_type" => $match[14],
						"var_default" => $match[15],
					);
				}
			}
		}

		if($from == "xml"){
			$parsed = array();
			$reg_exp = "/(\[text\|(".$this->reg_exp_literal.")\])|(\[tpl\|(".$this->reg_exp_numeric.")\|(".$this->reg_exp_literal.")\|(".$this->reg_exp_literal.")\])|(\[postfix\|(".$this->reg_exp_numeric.")\|(".$this->reg_exp_literal.")\|(".$this->reg_exp_literal.")\])|(\[opt\|(".$this->reg_exp_literal.")\|(".$this->reg_exp_literal.")\])/i";
			preg_match_all($reg_exp, $data, $matches, PREG_SET_ORDER);
			foreach($matches as $match){
				if(!empty($match[1])){
					$parsed[] = array(
						"type" => "text",
						"value" => $match[2],
					);
				}elseif(!empty($match[3])){
					$parsed[] = array(
						"type" => "tpl",
						"var_num" => $match[4],
						"var_name" => $match[5],
						"var_type" => $match[6],
					);
				}elseif(!empty($match[7])){
					$parsed[] = array(
						"type" => "postfix",
						"var_num" => $match[8],
						"var_name" => $match[9],
						"var_type" => $match[10],
					);
				}else{
					$parsed[] = array(
						"type" => "opt",
						"var_num" => 0,
						"var_name" => $match[12],
						"var_type" => $match[13],
					);
				}
			}
		}

		if($from == "base" && $to=="scheme"){
			$url = "";
			foreach($parsed as $match){
				if($match["type"] == "text"){
					$url .= $match["value"];
				}else{
					$url .= "[".$match["var_name"]."]";
				}
			}
			return $url;

		}elseif($from == "base" && $to=="js"){
			return $parsed;
		}elseif($from == "base" && $to=="xml"){
			$link = "";
			foreach($parsed as $match){
				switch($match["type"]){
					case "text":
						$link .= "[text|".$match["value"]."]";
					break;
					case 'postfix':
						$link .= "[postfix|".$match["var_num"]."|".$match["var_name"]."|".$match["var_type"]."]";
					break;
					case "tpl":
						$link .= "[tpl|".$match["var_num"]."|".$match["var_name"]."|".$match["var_type"]."]";
					break;
					default:
						$link .= "[opt|".$match["var_name"]."|".$match["var_type"]."]";
					break;
				}
			}
			return $link;
		}elseif($from == "js" && $to=="base"){
			$url = "";
			foreach($data as $block){
				switch($block["type"]){
					case 'tpl':
						$url .= '[tpl:'.$block["var_num"].':'.$block["var_name"].':'.$block["var_type"].':'.$block["var_default"].']';
					break;
					case 'postfix':
						$url .= '[postfix:'.$block["var_num"].':'.$block["var_name"].':'.$block["var_type"].']';
					break;
					case 'opt':
						$url .= '[opt:'.$block["var_name"].':'.$block["var_type"].':'.$block["var_default"].']';
					break;
					default:
						$url .= '[text:'.$block["value"].']';
					break;
				}
			}
			return $url;
		
		}elseif($from == "xml" && $to=="rule"){
			$redirect = $module."/".$method;
			$exp_index = array(1);
			$postfix_index = 0;
			$rules = array('');
			$vars_order = array();
			$last_part = "text";
			foreach($parsed as $match){
				$last_part = $match["type"];
				switch($match["type"]){
					case "text":
						$rules[$postfix_index] .= $match["value"];
					break;
					case "postfix":
						$postfix_index++;
						$rules[$postfix_index] = $rules[$postfix_index-1];
						if($match["var_type"] == "literal"){
							$rules[$postfix_index] .= /*"\/".*/"(".$this->reg_exp_literal.")";
						}else{
							$rules[$postfix_index] .= /*"\/".*/"(".$this->reg_exp_numeric.")";
						}
						$vars_order[$postfix_index] = $vars_order[$postfix_index-1];
						$vars_order[$postfix_index][$match['var_num']] = array("num"=>$exp_index[$postfix_index-1], "name"=>$match["var_name"]);
						$exp_index[$postfix_index] = $exp_index[$postfix_index-1] + 1;
					break;
					case "tpl":
						if($match["var_type"] == "literal"){
							$rules[$postfix_index] .= "(".$this->reg_exp_literal.")";
						}else{
							$rules[$postfix_index] .= "(".$this->reg_exp_numeric.")";
						}
						$vars_order[$postfix_index][$match['var_num']] = array("num"=>$exp_index[$postfix_index], "name"=>$match["var_name"]);
						$exp_index[$postfix_index]++;
					break;
					default:
						if($match["var_type"] == "literal"){
							$rules[$postfix_index] .= "(".$this->reg_exp_literal_last.")";
						}else{
							$rules[$postfix_index] .= "(".$this->reg_exp_numeric_last.")";
						}
						$exp_index[$postfix_index]++;
					break;
				}
			}

			$return = array();

			for($i=0; $i<=$postfix_index; $i++){
				$url = '';
				$max_patern_num = 0;
				if(!empty($vars_order[$i])){
					ksort($vars_order[$i]);
					foreach($vars_order[$i] as $var_num => $pattern){
						$url .= '/'.$pattern["name"].':$'.$pattern["num"];
						if($pattern["num"] > $max_patern_num) $max_patern_num = $pattern["num"];
					}
				}
			
				/*if($last_part == "text"){
					/// if last part is text  => add regexp for transfer additional params
					$rule .= "(".$this->reg_exp_last.")";
					$redirect .= '$'.($max_patern_num+1);
				}*/
			
				$return[] = '$route["'.$rules[$i].'"]="'.$redirect.$url.'";';
			}
		
			$str = implode("\n", array_reverse($return));
			return $str;
		}
	}

	/**
	 * Return settings urls
	 * 
	 * @param string $module module guid
	 * @param string $method method name
	 * @return string
	 */
	public function get_settings_urls($module, $method){
		if(!isset($this->url_scheme_cache[$module])){
			$results = $this->get_all_settings_from_cache('', $module, '');
			if(!empty($results)){
				foreach($results as $result){
					$this->url_scheme_cache[$result["module_gid"]][$result["method"]] = $result["url_template"];
				}
			}else{
				$this->url_scheme_cache[$module] = array();
			}
		}
		return isset($this->url_scheme_cache[$module][$method])?$this->url_scheme_cache[$module][$method]:"";
	}

	///// links rewrite methods
	
	/**
	 * Creat seo url
	 * 
	 * @param string $module module guid
	 * @param string $method method name
	 * @param string $str url string 
	 * @param boolean $is_admin admin mode
	 * @param string $lang_code language code
	 * @param boolean $no_lang_in_url no use language code in url
	 * @return string
	 */
	public function create_url($module, $method, $str, $is_admin, $lang_code=null, $no_lang_in_url=false){
		$link = '';
		$site_url = site_url('', $is_admin ? 'admin' : 'user', $lang_code, $no_lang_in_url);
		if(is_array($str)){
			$data = $str;
		}else{
			$temp = explode("|", $str);
			if(!$is_admin) $settings = $this->get_default_settings('user', $module, $method);
			if(!empty($settings["url_vars"])){
				if(!isset($settings["url_postfix"]) || !is_array($settings["url_postfix"])){
					$settings["url_postfix"] = array();
				}
				$url_vars = array_merge($settings["url_vars"], $settings["url_postfix"]);
				$index = 0;
				foreach($url_vars as $var_name => $replaces){
					if(isset($temp[$index])){
						$data[$var_name] = $temp[$index];
						foreach($replaces as $replace => $replace_type){
							$data[$replace] = $temp[$index];
						}
					}
					$index++;
				}
			}elseif(!empty($settings["url_postfix"])){
				$index = 0;
				foreach($settings["url_postfix"] as $var_name => $replaces){
					if(isset($temp[$index])){
						$data[$var_name] = $temp[$index];
						foreach($replaces as $replace => $replace_type){
							$data[$replace] = $temp[$index];
						}
					}
					$index++;
				}
			}else{
				$data = $temp;
			}
		}

		if($this->use_seo_links_rewrite && !$is_admin){
			$url_scheme = $this->get_settings_urls($module, $method);
			if(!empty($url_scheme)){
				$parts = $this->url_template_transform($module, $method, $url_scheme, 'base', 'js');
				foreach($parts as $part){
					switch($part["type"]){
						case 'text':
							$link .= $part["value"];
						break;
						case 'opt':
							if($part['var_type'] == 'literal'){
								$regex = $this->reg_exp_last;	
							}else{
								$regex = $this->reg_exp_numeric;	
							}
							$value = (!empty($data[$part["var_name"]])) ? $data[$part["var_name"]] : $part["var_default"];
							$value = str_replace('/', ' ', $value);
							$arr = array();
							preg_match_all("/".$regex."/ui", html_entity_decode($value), $arr);
							$link .= urlencode(implode('', $arr[0]));
						break;
						case 'postfix':
							$value = (!empty($data[$part["var_name"]])) ? $data[$part["var_name"]] : '';
							$value = str_replace('/', ' ', $value);
							$link .= $value;
						break;
						default:
							$value = (!empty($data[$part["var_name"]])) ? $data[$part["var_name"]] : $part["var_default"];
							$value = str_replace('/', ' ', $value);
							$link .= $value;
						break;
					}
				}
				
				$link = $site_url . trim($link, '/');
			}
		}

		if(empty($link)){
			if(empty($settings) && !$is_admin) $settings = $this->get_default_settings('user', $module, $method);
			$link = $site_url . ($is_admin ? "admin/" : "") . $module . "/" . $method;
			if(!empty($settings["url_vars"])){
				foreach($settings["url_vars"] as $var_name => $replaces){
					$link .= "/" . (!empty($data[$var_name]) ? $data[$var_name] : '');
				}
				if(!empty($settings["url_postfix"])){
					foreach($settings["url_postfix"] as $var_name => $replaces){
						if(empty($data[$var_name])) continue;
						$link .= "/" . $data[$var_name];
					}
				}
			}elseif(!empty($settings["url_postfix"])){
				foreach($settings["url_postfix"] as $var_name => $replaces){
					if(empty($data[$var_name])) continue;
					$link .= "/" . $data[$var_name];
				}
			}else{
				foreach($data as $segment){
					$link .= "/" . $segment;
				}
			}
		}
	
		$link = preg_replace('/\/{2,}$/', '/', $link);
		return $link;
	}

	/**
	 * Replace url variables
	 * 
	 * @param string $module module guid
	 * @param string $method method name
	 * @return void
	 */
	public function rewrite_url_vars($module, $method){
	
	}

	/**
	 * Install properties related to language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_add($lang_id){
		$this->CI->load->dbforge();
		$fields = array("meta_{$lang_id}" => array(
			'type' => 'text',
			'null' => FALSE,
		));
		$this->CI->dbforge->add_column(SEO_SETTINGS_TABLE, $fields);
		$fields = array("og_{$lang_id}" => array(
			'type' => 'text',
			'null' => FALSE,
		));
		$this->CI->dbforge->add_column(SEO_SETTINGS_TABLE, $fields);
		return;
	}

	/**
	 * Uninstall properties realted to language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_delete($lang_id){
		$this->CI->load->dbforge();
		$field_name = "meta_" . $lang_id;
		$this->CI->dbforge->drop_column(SEO_SETTINGS_TABLE, $field_name);
		$field_name = "og_" . $lang_id;
		$this->CI->dbforge->drop_column(SEO_SETTINGS_TABLE, $field_name);
		return;
	}
	
	/*
	 * Return settings object from data source by identifier
	 * 
	 * @param string $setting_id setting identifier
	 * @return array
	 */
	public function get_settings_by_id($setting_id){
		if(!$this->use_db) return false;

		$this->DB->select(implode(',', $this->_settings_fields))
				 ->from(SEO_SETTINGS_TABLE)
				 ->where('id', $setting_id);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return $results[0];
		}
		
		return false;
	}
	
	/*
	 * Save settings object in data source by identifier
	 *
	 * @param string $settings_id setting identifier
	 * @param array $data settings data
	 * @return integer
	 */
	public function save_settings($settings_id, $data){
		if(empty($data)) return false;

		if($settings_id){
			$this->DB->where('id', $settings_id);
			$this->DB->update(SEO_SETTINGS_TABLE, $data);
		}else{
			$this->DB->insert(SEO_SETTINGS_TABLE, $data);
			$settings_id = $this->DB->insert_id();
		}
		
		return $settings_id;
	}
	
	/**
	 * Set seo tags
	 * 
	 * @param array $data seo tags
	 */
	public function set_seo_tags($data){
		$this->seo_page_tags = $data;
	}
}
