<?php
/**
* Currencies model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('PAYMENTS_CURRENCY_TABLE', DB_PREFIX.'payments_currency');

class Payment_currency_model extends Model
{
	private $CI;
	private $DB;

	private $fields = array(
		'id',
		'gid',
		'abbr',
		'name',
		'per_base',
		'template',
		'is_default'
	);

	private $currency_cache = array();

	public $default_currency = array();
	
	public $base_currency = array();
	
	public $currencies = array();
	
	/**
	 * Currency rates updaters
	 * @var array
	 */
	public $rates_updaters = array("yahoo", "xe");

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->default_currency = $this->get_currency_default();
		$this->DB->memcache_tables(array(PAYMENTS_CURRENCY_TABLE));
	}

	public function get_currency_by_gid($gid){
		if(empty($this->currency_cache[$gid])){
			$result = $this->DB->select(implode(", ", $this->fields))->from(PAYMENTS_CURRENCY_TABLE)->where("gid", $gid)->get()->result_array();
			if(!empty($result)){
				$result[0]['is_default'] = 0;
				$this->currency_cache[$gid] = $result[0];
			}else{
				$this->currency_cache[$gid] = array();
			}
		}
		return $this->currency_cache[$gid];
	}

	public function get_currency_by_id($id){
		$result = $this->DB->select(implode(", ", $this->fields))->from(PAYMENTS_CURRENCY_TABLE)->where("id", $id)->get()->result_array();
		if(!empty($result)){
			$result[0]['is_default'] = 0;
			$r = $result[0];
			if(empty($this->currency_cache[$r["gid"]])){
				$this->currency_cache[$r["gid"]] = $r;
			}else{
				$r = $this->currency_cache[$r["gid"]];
			}
		}else{
			$r = array();
		}
		return $r;
	}

	public function get_currency_default($base=false){
		$currency_id = $this->CI->session->userdata("currency_id");
		$currency_id = intval($currency_id);
		
		$currency_id = 0; /* <-- закомментить эту строку если нужна возможность выбирать валюту пользователю */
		
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(PAYMENTS_CURRENCY_TABLE);
				
		if($currency_id && !$base){
			$this->DB->where("id", $currency_id);
		}else{
			$this->DB->where("is_default", '1');
		}
		
		$result = $this->DB->get()->result_array();
		if(!empty($result)){
			$r = $result[0];
			$r['is_default'] = 1;
			$this->currency_cache[$r["gid"]] = $r;
		}else{
			$r = array();
		}
		return $r;
	}
	
	public function get_currency_list($params=array(), $filter_object_ids=null, $order_by=null){
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(PAYMENTS_CURRENCY_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->fields)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$r['is_default'] = 0;
				if(empty($this->currency_cache[$r["gid"]])){
					$this->currency_cache[$r["gid"]] = $r;
				}else{
					$r = $this->currency_cache[$r["gid"]];
				}
				$data[] = $r;
			}
			return $data;
		}
		return array();

	}

	public function get_currency_list_by_key($params=array(), $filter_object_ids=null, $order_by=null){
		$list = $this->get_currency_list($params, $filter_object_ids, $order_by);
		if(!empty($list)){
			foreach($list as $l){
				$this->currencies[$l['id']] = $this->currencies[$l['gid']] = $data[$l['gid']] = $l;
			}
			return $data;
		}else{
			return array();
		}
	}

	public function get_currency_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(PAYMENTS_CURRENCY_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function validate_currency($id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["gid"])){
			$data["gid"] = strip_tags($data["gid"]);
			$data["gid"] = preg_replace("/[^a-z0-9]+/i", '', $data["gid"]);

			$return["data"]["gid"] = $data["gid"];

			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_currency_code_incorrect', 'payments');
			}

			$param["where"]["gid"] = $return["data"]["gid"];
			if(!empty($id)) $param["where"]["id <>"] = $id;
			$gid_count = $this->get_currency_count($param);
			if($gid_count > 0){
				$return["errors"][] = l('error_currency_code_already_exists', 'payments');
			}
		}

		if(isset($data["abbr"])){
			$return["data"]["abbr"] = strip_tags($data["abbr"]);
			if(empty($return["data"]["abbr"]) ){
				$return["errors"][] = l('error_abbr_required', 'payments');
			}
		}

		if(isset($data["template"])){
			$return["data"]["template"] = $data["template"];
		}

		if(isset($data["name"])){
			$return["data"]["name"] = $data["name"];
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_currency_name_required', 'payments');
			}
		}
		
		if(isset($data["per_base"])){
			$return["data"]["per_base"] = (float)$data["per_base"];
		}

		if(isset($data["is_default"])){
			$return["data"]["is_default"] = intval($data["is_default"]);
		}

		return $return;
	}

	public function save_currency($id, $data){
		if (is_null($id)){
			$this->DB->insert(PAYMENTS_CURRENCY_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(PAYMENTS_CURRENCY_TABLE, $data);
		}
		unset($this->currency_cache[$data["gid"]]);
		return $id;
	}

	public function set_default($id){
		$data["is_default"] = '0';
		$this->DB->update(PAYMENTS_CURRENCY_TABLE, $data);

		$data["is_default"] = '1';
		$this->DB->where('id', $id);
		$this->DB->update(PAYMENTS_CURRENCY_TABLE, $data);
		return;
	}

	public function delete_currency($id){
		$this->DB->where("id", $id);
		$this->DB->delete(PAYMENTS_CURRENCY_TABLE);
		return;
	}

	public function delete_currency_by_gid($gid){
		$this->DB->where("gid", $gid);
		$this->DB->delete(PAYMENTS_CURRENCY_TABLE);
		unset($this->currency_cache[$gid]);
		return;
	}
	
	/**
	 * Return value into base currency
	 * @param float $value currency value
	 * @param string $currency_gid currency GUID
	 * @param float
	 */
	public function get_value_into_base_currency($value, $currency_gid){
		$base_currency = $this->pg_module->get_module_config("payments", "base_currency");
		if($currency_gid == $base_currency) return $value;
		
		$data = $this->get_currency_by_gid($currency_gid);
		if(!empty($data)){
			return $value*$data["per_base"];
		}else{					
			return 0;
		}
	}
	
	/**
	 * Update currencies rates
	 * @param string $rates_update_driver rates service
	 */
	public function update_currency_rates($rates_update_driver){
		$return = array("erros"=>array(), "data"=>array());
	
		$base_currency = $this->pg_module->get_module_config("payments", "base_currency");
		
		if(!$rates_update_driver || !in_array($rates_update_driver, $this->rates_updaters))
		{$return["errors"][] = l("error_incorrect_rates_driver", "payments");return $return;}
		
		ob_start();
		phpinfo(INFO_MODULES);
		$contents = ob_get_contents();
		ob_end_clean();
		$use_curl = strpos($contents, "curl") !== false;
		
		$currencies = $this->get_currency_list();		
		if(empty($currencies)){$return["errors"][] = l("error_empty_currencies", "payments");return $return;}
		
		$model_name = $rates_update_driver."_currency_rates_model";
	
		try{
			$this->CI->load->model("payments/models/".$model_name, $model_name);
			$results = $this->CI->{$model_name}->update_rates($base_currency, $currencies, $use_curl);
		}catch(Exception $e){
			$return["errors"][] = $e->getMessage();
			return $return;
		}
		
		foreach($results as $currency_gid=>$per_base){
			$data = $this->get_currency_by_gid($currency_gid);
			if(empty($data)) continue;	
			$validate_data = $this->validate_currency($data["id"], array("per_base"=>$per_base));
			if(!empty($validate_data["errors"])){
				continue;
			}else{
				$this->save_currency($data["id"], $validate_data["data"]);
			}
		}
		
		return $return;
	}
	 
	/**
	 * Automatic update currencies rates
	 */
	public function cron_update_currency_rates(){
		$use_rates_update = $this->pg_module->get_module_config("payments", "use_rates_update");
		if(!$use_rates_update) exit;
		
		$rates_update_driver = $this->pg_module->get_module_config("payments", "rates_update_driver");
		$this->update_currency_rates($rates_update_driver);
	}
	
	/**
	 * Return currecnies from cache
	 */
	function return_currencies(){
		if(!isset($this->currencies) || empty($this->currencies)){
			$this->get_currency_list_by_key();
		}
		return $this->currencies;
	}
}
