<?php  

/**
 * Seo module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seo model model
 * 
 * @package 	PG_Core
 * @subpackage 	Seo
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Seo_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	private $CI;

    /**
	 * Meta properties
	 * 
	 * @param array
	 */
	private $_meta_fields = array(
		array('name'=>'title', 'type'=>'text'),
		array('name'=>'keyword', 'type'=>'textarea'),
		array('name'=>'description', 'type'=>'textarea'),
		array('name'=>'header', 'type'=>'text'),
		array('name'=>'noindex', 'type'=>'checkbox'),
	);
	
	/**
	 * Open graph properties
	 * 
	 * @param array
	 */
	private $_og_fields = array(
		array('name'=>'og_title', 'type'=>'text'),
		array('name'=>'og_type', 'type'=>'text'),
		array('name'=>'og_description', 'type'=>'textarea'),
	);

	/**
	 * Routes xml
	 * 
	 * @var string
	 */
	public $route_xml_file = "config/seo_module_routes.xml";
	
	/**
	 * Routes php
	 * 
	 * @var string
	 */
	public $route_php_file = "config/seo_module_routes.php";
	
	/**
	 * Constructor
	 *
	 * @return Seo_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
	}

	/**
	 * Load routes from xml file
	 * 
	 * @return array
	 */
	public function get_xml_route_file_content(){
		$file = APPPATH . $this->route_xml_file;
		$xml = simplexml_load_file($file);
		if(!is_object($xml)){
			return array();
		}
		$return = array();
		foreach($xml as $module=>$module_data){
			$module_name = strval($module);
			foreach($module_data->method as $key=>$method){
				$method_name = strval($method["name"]);
				$link = strval($method["link"]);
				$return[$module_name][$method_name] = $link;
			}
		}
		return $return;
	}

	/**
	 * Save routes to xml file
	 * 
	 * @param array $data routes data
	 * @return void
	 */
	public function set_xml_route_file_content($data){
		$xml = '<?xml version="1.0" encoding="utf-8"?>'."\n";
		$xml .= "<routes>\n";
		foreach($data as $model => $model_data){
			$xml .= "<".$model.">\n";
			foreach($model_data as $method => $pattern){
				if(!empty($pattern)){
					$xml .= '<method name="'.$method.'" link="'.$pattern.'" />'."\n";
				}
			}
			$xml .= "</".$model.">\n";
		}
		$xml .= "</routes>\n";
		$file = APPPATH . $this->route_xml_file;
		$h = fopen($file, "w");
		if($h){
			fwrite($h, $xml);
			fclose($h);
		}
	}

	/**
	 * Save routes to php file
	 * 
	 * @return void
	 */
	public function rewrite_route_php_file(){
		$data = $this->get_xml_route_file_content();
		if(empty($data)){
			return false;
		}

		$php = '<?php'."\n\n";
		foreach($data as $model => $model_data){
			foreach($model_data as $method => $pattern){
				if(!empty($pattern)){
					$string = $this->CI->pg_seo->url_template_transform($model, $method, $pattern, 'xml', 'rule');
					$php .= $string."\n";
				}
			}
		}
		
		$file = APPPATH . $this->route_php_file;
		$h = fopen($file, "w");
		if($h){
			fwrite($h, $php);
			fclose($h);
		}
	}
	
	/**
	 * Add routes fields depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_add($lang_id = false) {
		$this->update_route_langs();
	}

	/**
	 * Remove routes fields depended on language
	 * 
	 * @param integer $lang_id language identifier
	 * @return void
	 */
	public function lang_dedicate_module_callback_delete($lang_id = false) {
		$this->update_route_langs();
	}

	/**
	 * Update or create file config/langs_route.php
	 *
	 * @return boolean
	 */
	private function update_route_langs() {

		$langs = $this->CI->pg_language->get_langs();
		if (0 == count($langs)) {
			return false;
		}
		foreach($langs as $lang){
			$content_langs[] = "'". $lang['code'] ."'";
		}

		$file_content = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
		$file_content .= "\$config['langs_route'] = array(";
		$file_content .= implode(", ", $content_langs);
		$file_content .= ');';

		$file = APPPATH . 'config/langs_route' . EXT;
		try {
			$handle = fopen($file, 'w');
			fwrite($handle, $file_content);
			fclose($handle);
		} catch (Exception $e) {
			log_message('error','Error while updating langs_route' . EXT . '(' . $e->getMessage() . ') in ' . $e->getFile());
			throw $e;
		}
		return true;
	}
	
	/**
	 * Return seo fields by sections
	 * 
	 * @return array
	 */
	public function get_seo_fields(){
		$seo_data = array();
		
		$fields_data = array();
		
		foreach($this->_meta_fields as $field_data){
			$fields_data[] = array(
				'gid' => $field_data['name'],
				'name' => l('field_'.$field_data['name'], 'seo'),
				'type' => $field_data['type'],
				'tooltip' => l('text_help_'.$field_data['name'], 'seo'),
			);
		}
	
		$seo_data[] = array(
			'gid' => 'meta',
			'name' => l('header_section_meta', 'seo'),
			'fields' => $fields_data,
			'tooltip' => l('text_help_meta', 'seo'),
		);
		
		$fields_data = array();
		
		foreach($this->_og_fields as $field_data){
			$fields_data[] = array(
				'gid' => $field_data['name'],
				'name' => l('field_'.$field_data['name'], 'seo'),
				'type' => $field_data['type'],
				'tooltip' => l('text_help_'.$field_data['name'], 'seo'),
			);
		}
		
		$seo_data[] = array(
			'gid' => 'og',
			'name' => l('header_section_og', 'seo'),
			'fields' => $fields_data,
			'tooltip' => l('text_help_og', 'seo'),
		);
			
		return $seo_data;
	}
	
	/**
	 * Validate data of seo tags for saving to data source
	 * 
	 * @param integer $setting_id setting identifier
	 * @param array $data seo data
	 * @return array
	 */
	public function validate_seo_tags($setting_id, $data){
		$return = array('errors'=>array(), 'data'=>array());
		
		$return['data']['controller'] = 'custom';
		
		if(isset($data['meta'])){
			foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
				if(isset($data['meta']['title'])){
					$return['data']['meta_'.$lang_id]['title'] = trim(strip_tags($data['meta']['title'][$lang_id]));
				}
				if(isset($data['meta']['keyword'])){
					$return['data']['meta_'.$lang_id]['keyword'] = trim(strip_tags($data['meta']['keyword'][$lang_id]));
				}
				if(isset($data['meta']['description'])){
					$return['data']['meta_'.$lang_id]['description'] = trim(strip_tags($data['meta']['description'][$lang_id]));
				}
				if(isset($data['meta']['header'])){
					$return['data']['meta_'.$lang_id]['header'] = trim(strip_tags($data['meta']['header'][$lang_id]));
				}
				$return['data']['meta_'.$lang_id] = serialize($return['data']['meta_'.$lang_id]);
			}
			if(isset($data['meta']['noindex'])){
				$return['data']['noindex'] = $data['meta']['noindex'] ? 1 : 0;
			}
		}	
		
		if(isset($data['og'])){	
			foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
				if(isset($data['og']['og_title'])){
					$return['data']['og_'.$lang_id]['og_title'] = trim(strip_tags($data['og']['og_title'][$lang_id]));
				}
				if(isset($data['og']['og_type'])){
					$return['data']['og_'.$lang_id]['og_type'] = trim(strip_tags($data['og']['og_type'][$lang_id]));
				}
				if(isset($data['og']['og_description'])){
					$return['data']['og_'.$lang_id]['og_description'] = trim(strip_tags($data['og']['og_description'][$lang_id]));
				}
				$return['data']['og_'.$lang_id] = serialize($return['data']['og_'.$lang_id]);
			}
		}
		
		return $return;
	}
	
	/**
	 * Validate data of seo tags for saving to data source
	 * 
	 * @param string $controller user mode controller 
	 * @param string $module_gid module gid
	 * @param string $method method_name
	 * @param array $data seo data
	 * @return array
	 */
	public function validate_seo_settings($controller, $module_gid, $method, $data){
		$return = array('errors'=>array(), 'data'=>array());
		
		foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
			if(isset($data['title'])){
				$return['data']['title'][$lang_id] = trim(strip_tags($data['title'][$lang_id]));
			}
			if(isset($data['keyword'])){
				$return['data']['keyword'][$lang_id] = trim(strip_tags($data['keyword'][$lang_id]));
			}
			if(isset($data['description'])){
				$return['data']['description'][$lang_id] = trim(strip_tags($data['description'][$lang_id]));
			}
			if(isset($data['header'])){
				$return['data']['header'][$lang_id] = trim(strip_tags($data['header'][$lang_id]));
			}
			if(isset($data['og_title'])){
				$return['data']['og_title'][$lang_id] = trim(strip_tags($data['og_title'][$lang_id]));
			}
			if(isset($data['og_type'])){
				$return['data']['og_type'][$lang_id] = trim(strip_tags($data['og_type'][$lang_id]));
			}
			if(isset($data['og_description'])){
				$return['data']['og_description'][$lang_id] = trim(strip_tags($data['og_description'][$lang_id]));
			}
		}
		
		if(isset($data['noindex'])){
			$return['data']['noindex'] = $data['noindex'] ? 1 : 0;
		}
		
		if(isset($data['lang_in_url'])){
			$return['data']['lang_in_url'] = $data['lang_in_url'] ? 1 : 0;
		}
		
		
		return $return;
	}
	
	/**
	 * Return data of seo tags by identifier
	 * 
	 * @param string $setting_id setting identifier
	 * @return array
	 */
	public function get_seo_tags($setting_id){
		$data = $this->CI->pg_seo->get_settings_by_id($setting_id);
		return $this->format_seo_tags($data);
	}
	
	/**
	 * Save data of seo tags to data source
	 * 
	 * @param string $settings_id setting identifier
	 * @param array $data seo data
	 * @return integer
	 */
	public function save_seo_tags($settings_id, $data){
		return $this->CI->pg_seo->save_settings($settings_id, $data);
	}
	
	/**
	 * Fromat data of seo tags
	 * 
	 * @param array $data seo data
	 * @return integer
	 */
	public function format_seo_tags($data){
		foreach($this->CI->pg_language->languages as $lang_id=>$lang_data){
			if($data['meta_'.$lang_id]){
				$data['meta_'.$lang_id] = (array)unserialize($data['meta_'.$lang_id]);
			}else{
				$data['meta_'.$lang_id] = array();
			}
			
			if($data['og_'.$lang_id]){
				$data['og_'.$lang_id] = (array)unserialize($data['og_'.$lang_id]);
			}else{
				$data['og_'.$lang_id] = array();
			}
		}
	
		return $data;
	}
	
	/**
	 * Parse seo tags
	 * 
	 * @param integer $settings_id settings identifier
	 * @return array
	 */
	public function parse_seo_tags($settings_id){ 
		$settings = $this->get_seo_tags($settings_id);
		if(empty($settings)) return $settings;
		$data = array();
		$current_lang_id = $this->CI->pg_language->current_lang_id;
		foreach($this->_meta_fields as $meta_field){
			$data[$meta_field['name']] = $settings['meta_'.$current_lang_id][$meta_field['name']];
		}
		foreach($this->_og_fields as $og_field){
			$data[$og_field['name']] = $settings['og_'.$current_lang_id][$og_field['name']];
		}
		$data['noindex'] = $settings['noindex'];
		return $data;
	}
}
