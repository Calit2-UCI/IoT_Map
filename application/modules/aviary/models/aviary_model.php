<?php

/**
 * Aviary module
 *
 * @package 	PG_Dating
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

define("AVIARY_MODULES_TABLE", DB_PREFIX."aviary_modules");

/**
 * Aviary model
 * 
 * @package 	PG_Dating
 * @subpackage 	Aviary
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Dating Pro - php dating software
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Aviary_model extends Model{
	/**
	 * Link to CodeIgniter object
	 * 
	 * @var object
	 */
	private $CI;

	/**
	 * Link to database object
	 * 
	 * @var object
	 */
	private $DB;
	
	/**
	 * Properties of modules data source
	 * 
	 * @var array
	 */
	private $module_attrs = array(
		'id',
		'module_gid',
		'model_name',
		'method',
	);

	/**
	 * Constructor
	 *
	 * @return Aviary_model
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}
	
	/**
	 * Validate module settings
	 * 
	 * @param array $data settings data
	 * @return array
	 */
	public function validate_settings($data){
		$return = array('errors'=> array(), 'data' => array());
		
		$used = $this->CI->pg_module->get_module_config('aviary', 'used');
		$api_key = $this->CI->pg_module->get_module_config('aviary', 'api_key');
		
		if(isset($data['used'])){
			$return['data']['used'] = $used = $data['used'] ? 1 : 0;
		}
		
		if(isset($data["api_key"])){
			$return["data"]["api_key"] = $api_key = trim(strip_tags($data["api_key"]));
		}
		
		$return["data"]["api_secret"] = substr(md5(date("Y-m-d H:i:s")), 0 , 10);
		
		if($used && empty($api_key)){
			$return["errors"][] = l("error_aviary_api_key_empty", "aviary");
		}
		
		return $return;
	}
	
	/**
	 * Return code for saving
	 * 
	 * @param string module_gid module guid
	 * @param array $data post data
	 * @return string
	 */
	public function save_encode($module_gid, $data){
		$api_key = $this->CI->pg_module->get_module_config('aviary', 'api_key');
		$api_secret = $this->CI->pg_module->get_module_config('aviary', 'api_secret');
		$code = $api_key.':'.$api_secret.':'.$module_gid;
		foreach($data as $k=>$v) $code .= ':'.$v;
		return substr(md5($code), 0, 10);
	}
	
	/**
	 * Return data of module by GUID
	 * 
	 * @param string $module_gid module guid
	 * @return array
	 */
	public function get_module($module_gid){
		$this->DB->select(implode(", ", $this->module_attrs))
				 ->from(AVIARY_MODULES_TABLE)
				 ->where("module_gid", $module_gid);
		$result = $this->DB->get()->result_array();
		if(!empty($result)){
			$return = $result[0];
		}else{
			$return = array();
		}
		return $return;
	}
	
	/**
	 * Save data of module by GUID
	 * 
	 * @param string $module_gid module guid
	 * @param array $data module data
	 * @return void
	 */
	public function save_module($module_gid, $data){
		if(is_null($module_gid)){
			$this->DB->insert(AVIARY_MODULES_TABLE, $data);
		}else{
			$this->DB->where("module_gid", $module_gid);
			$this->DB->update(AVIARY_MODULES_TABLE, $data);
		}
	}
	
	/**
	 * Remove module 
	 * 
	 * @param string $module_gid module guid
	 * @return void
	 */
	public function delete_module($module_gid){
		$this->DB->where("module_gid", $module_gid);
		$this->DB->delete(AVIARY_MODULES_TABLE);
	}
	
	/**
	 * Validate data of module by GUID
	 * 
	 * @param string $module_gid module guid
	 * @param array $data module data
	 * @return void
	 */
	public function validate_module($module_gid, $data){
		$return = array('errors'=> array(), 'data' => array());
		
		if(isset($data['id'])){
			$return['data']['id'] = intval($data['id']);
		}
		
		if(isset($data['module_gid'])){
			$return['data']['module_gid'] = trim($data['module_gid']);
		}
		
		if(isset($data['model_name'])){
			$return['data']['model_name'] = trim($data['model_name']);
		}
		
		if(isset($data['method'])){
			$return['data']['method'] = trim($data['method']);
		}
		
		return $return;
	}
}
