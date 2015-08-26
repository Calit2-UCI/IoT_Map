<?php
/**
* Subscriptions types model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('SUBSCRIPTIONS_TYPES_TABLE')) define('SUBSCRIPTIONS_TYPES_TABLE', DB_PREFIX.'subscriptions_types');
class Subscriptions_types_model extends Model {
        private $CI;
	private $DB;
	private $attrs = array('id', 'gid', 'module', 'model', 'method');

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		
		$this->DB->memcache_tables(array(SUBSCRIPTIONS_TYPES_TABLE));
	}
        
	public function get_subscriptions_types_list($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null){
		$this->DB->select(implode(", ", $this->attrs))->from(SUBSCRIPTIONS_TYPES_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->attrs)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}

		$data = array();
		$results = $this->DB->get()->result_array();
                $data[] = array('id' => 0, 'name' => l('without_content', 'subscriptions'),);
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $this->format_subscriptions_type($r, true);
			}
		}
		return $data;
	}
        
	public function format_subscriptions_type($data, $get_langs = false){
		$data["name_i"] = "subscriptions_type_".$data["gid"];
		$data["name"] = ($get_langs)?(l($data["name_i"], $data['module'])):"";
		return $data;
	}
        
	public function get_subscriptions_type_by_id($id){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(SUBSCRIPTIONS_TYPES_TABLE)->where("id", $id)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}
	
	public function get_subscriptions_type_by_gid($gid){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(SUBSCRIPTIONS_TYPES_TABLE)->where("gid", $gid)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}
        
	public function get_subscriptions_type_content($id, $lang_id = 1){
		$st_object = $this->get_subscriptions_type_by_id($id);
		$function_result = array();
		if (!$lang_id) $lang_id = $this->CI->pg_language->get_default_lang_id();
		if (!empty($st_object['module']) && !empty($st_object['model']) && !empty($st_object['method'])){
			if (!$this->is_method_callable($st_object["module"], $st_object["model"], $st_object["method"])){
				$errors[] = l('error_function_invalid', 'cronjob');
			}else{
				$model_url = $st_object["module"]."/models/".$st_object["model"];
				$model_path = MODULEPATH.strtolower($model_url).EXT;
				$this->CI->load->model($model_url);

				$function_result = call_user_func_array(array(&$this->CI->$st_object["model"], $st_object["method"]), array($lang_id));
			}
		}

		return $function_result;
	}
        
	private function is_method_callable($module, $model, $method){
		$result = false;

		$model_url = $module."/models/".$model;
		$model_path = MODULEPATH.strtolower($model_url).EXT;

		if(file_exists($model_path)){
			$this->CI->load->model($model_url);
			$object = array($this->CI->$model, $method);
			$result = is_callable($object);
		}

		return $result;
	}
        
	public function save_subscriptions_type($id, $data){
		if (is_null($id)){
			$this->DB->insert(SUBSCRIPTIONS_TYPES_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(SUBSCRIPTIONS_TYPES_TABLE, $data);
		}
		return $id;
	}
        
	public function delete_subscriptions_type_by_gid($gid){
		$this->DB->where("gid", $gid);
		$this->DB->delete(SUBSCRIPTIONS_TYPES_TABLE);
		return;
	}
}