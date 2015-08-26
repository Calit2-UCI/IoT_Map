<?php  
/**
* Site map main model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');
define('SITEMAP_MODULES_TABLE', DB_PREFIX.'sitemap_modules');

class Site_map_model extends Model
{
	private $CI;
	private $DB;

	private $sitemap_module_cache = array();
	/**
	 * Constructor
	 *
	 * @return
	 */
	public function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}


	public function get_sitemap_module_by_gid($module_gid){
		if(empty($this->sitemap_module_cache[$module_gid])){
			$this->DB->select('id, module_gid, model_name, get_urls_method')->from(SITEMAP_MODULES_TABLE)->where("module_gid", $module_gid);
			$results = $this->CI->db->get()->result_array();
			if(!empty($results)){
				$this->sitemap_module_cache[$module_gid] = $results[0];
			}
		}
		return (!empty($this->sitemap_module_cache[$module_gid]))?$this->sitemap_module_cache[$module_gid]:false;
	}

	public function get_sitemap_modules(){
		$this->DB->select('id, module_gid, model_name, get_urls_method')->from(SITEMAP_MODULES_TABLE);
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $r){
				$this->sitemap_module_cache[$r["module_gid"]] = $r;
			}
		}
		return $this->sitemap_module_cache;
	}

	public function set_sitemap_module($module_gid, $data=array()){
		$module_data = $this->get_sitemap_module_by_gid($module_gid);
		if(empty($module_data)){
			$this->DB->insert(SITEMAP_MODULES_TABLE, $data);
		}else{
			$this->DB->where("module_gid", $module_gid);
			$this->DB->update(SITEMAP_MODULES_TABLE, $data);
		}
	}

	public function delete_sitemap_module($module_gid){
		$this->DB->where("module_gid", $module_gid);
		$this->DB->delete(SITEMAP_MODULES_TABLE);
	}

	public function get_sitemap_links(){
		$modules = $this->get_sitemap_modules();
		$blocks = array();
		
		foreach($modules as $module){
			$this->CI->load->model($module["module_gid"]."/models/".$module["model_name"]);
			$blocks[] = $this->CI->$module["model_name"]->$module["get_urls_method"]();
		}

		return $blocks;
	}

	////// seo
	public function get_seo_settings($method='', $lang_id=''){
		if(!empty($method)){
			return $this->_get_seo_settings($method, $lang_id);
		}else{
			$actions = array('index');
			$return = array();
			foreach($actions as $action){
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	public function _get_seo_settings($method, $lang_id=''){
		if($method == "index"){
			return array(
				"templates" => array(),
				"url_vars" => array(),
				'url_postfix' => array(),
				'optional' => array(),
			);
		}
	}

	public function request_seo_rewrite($var_name_from, $var_name_to, $value){
		if($var_name_from == $var_name_to){
			return $value;
		}
		
		show_404();
	}

	public function get_sitemap_xml_urls(){
		$this->CI->load->helper('seo');
		$return = array();
		
		$lang_canonical = true;
				
		if($this->CI->pg_module->is_module_installed('seo')){
			$lang_canonical = $this->CI->pg_module->get_module_config('seo', 'lang_canonical');
		}
		
		if($lang_canonical){
			$default_lang_id = $this->CI->pg_language->get_default_lang_id();
			$default_lang_code = $this->CI->pg_language->get_lang_code_by_id($default_lang_id);
			$langs[$default_lang_id] = $default_lang_code;
		}else{
			foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
				$langs[$lang_id] = $lang_data['code'];
			}
		}
		
		$user_settings = $this->pg_seo->get_settings('user', 'site_map', 'index');
		if(!$user_settings['noindex']){	
			foreach($langs as $lang_id=>$lang_code){
				$return[] = array(
					"url" => rewrite_link('site_map', 'index', array(), false, $lang_code, $lang_canonical),
					"priority" => 0.8
				);
			}
		}
		
		return $return;
	}


	////// banners callback method
	public function _banner_available_pages(){
		$return[] = array("link"=>"site_map/index", "name"=> l('seo_tags_index_header', 'site_map'));
		return $return;
	}


}
