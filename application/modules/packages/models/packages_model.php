<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Packages model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/

if(!defined('PACKAGES_TABLE')) define('PACKAGES_TABLE', DB_PREFIX.'packages');

class Packages_model extends Model
{
	private $CI;
	private $DB;

	private $fields = array(
		'id', 	 	 	 	 	
		'gid',	
		'status',
		'price',
		'available_days',
		'services_list',
		'pay_type',
	);
	
	private $cache_package_by_id = array();
	private $cache_package_by_gid = array();
	public $is_cache_set = false;
	
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}
	
	public function get_packages_list($params=array(), $filter_object_ids=null, $order_by=null, $page = null, $items_on_page = null){
		$this->DB->select(implode(", ", $this->fields))->from(PACKAGES_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			$this->DB->where($params["where"]);
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
				$this->DB->order_by($field." ".$dir);
			}
		}
		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$results = $this->format_package($results);
		}
		
		foreach($results as $package){
			$this->cache_package_by_id[$package['id']] = $this->cache_package_by_gid[$package['gid']] = $package;
		}
		
		return $results;
	}
	
	public function cache_all(){
		$this->is_cache_set = true;
		return $this->get_packages_list();
	}

	public function format_package($data){
		$services_ids = array();
		$services_counts = array();
		foreach($data as $k => &$package){
			if(isset($package['price'])){
				$package['price'] = (double)$package['price'];
			}
			if(isset($package['id'])){
				$package['name'] = l('package_name_'.$package['id'], 'packages');
			}
			$package['services_list_array'] = unserialize($package['services_list']);
			if(is_array($package['services_list_array'])){
				foreach($package['services_list_array'] as $sid => $count){
					$services_ids[$sid] = $sid;
					$services_counts[$package['id']][$sid] = $count;
				}
			}
		}
		$package['services_list'] = array();
		if($services_ids){
			$this->CI->load->model('Services_model');
			if(!$this->CI->Services_model->is_services_cache_set){
				$this->CI->Services_model->cache_all_services();
			}
			$services = array();
			foreach($services_ids as $service_id){
				$services[$service_id] = $this->CI->Services_model->get_service_by_id($service_id);
			}
			foreach($data as &$package){
				$package['services_list'] = array_intersect_key($services, (array)$package['services_list_array']);
				foreach($package['services_list'] as &$pack_service){
					$pack_service['service_count'] = !empty($services_counts[$package['id']][$pack_service['id']]) ? $services_counts[$package['id']][$pack_service['id']] : 0;
				}
			}
		}
		return $data;
	}
	
	public function get_packages_count($params=array(), $filter_object_ids=null){
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			$this->DB->where($params['where']);
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

		return $this->DB->count_all_results(PACKAGES_TABLE);
	}
	
	public function get_package_by_id($id){
		if(empty($this->cache_package_by_id[$id])){
			$result = $this->DB->select(implode(", ", $this->fields))->from(PACKAGES_TABLE)->where("id", $id)->get()->result_array();
			$result = $this->format_package($result);
			$return = (!empty($result))?$result[0]:array();
			if ($return['id']){
				$return["name"] = l('package_name_'.$return["id"], 'packages');
			} else {
				$return["name"] = l('package_unavailable', 'packages');
			}
			$this->cache_package_by_id[$id] = $this->cache_package_by_gid[$return["gid"]] = $return;
		}
		return $this->cache_package_by_id[$id];
	}
	
	public function get_package_by_gid($gid){
		if(empty($this->cache_package_by_gid[$gid])){
			$result = $this->DB->select(implode(", ", $this->fields))->from(PACKAGES_TABLE)->where("gid", $gid)->get()->result_array();
			$result = $this->format_package($result);
			$return = (!empty($result))?$result[0]:array();
			if ($return['gid']){
				$return["name"] = l('package_name_'.$return["id"], 'packages');
			} else {
				$return["name"] = l('package_unavailable', 'packages');
			}
			$this->cache_package_by_gid[$gid] = $this->cache_package_by_id[$return["id"]] = $return;
		}
		return $this->cache_package_by_gid[$gid];
	}
	
	public function validate_package($id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["gid"])){
			$data["gid"] = strip_tags($data["gid"]);
			$data["gid"] = preg_replace("/[^a-z0-9\-_]+/i", '', $data["gid"]);

			$return["data"]["gid"] = $data["gid"];

			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_service_code_incorrect', 'services');
			}else{
				$param["where"]["gid"] = $return["data"]["gid"];
				if($id) $param["where"]["id <>"] = $id;
				$gid_counts = $this->get_packages_count($param);
				if($gid_counts > 0){
					$return["errors"][] = l('error_service_code_exists', 'services');
				}
			}
		}

		if(isset($data["status"])){
			$return["data"]["status"] = intval($data["status"]);
		}
		
		if(isset($data["available_days"])){
			$return["data"]["available_days"] = intval($data["available_days"]);
			if (!$return["data"]["available_days"]) {
				$return["errors"][] = l('error_available_days_incorrect', 'packages');
			}
		}

		if(isset($data["price"])){
			$return["data"]["price"] = floatval($data["price"]);
			if (!$return["data"]["price"]) {
				$return["errors"][] = l('error_price_incorrect', 'packages');
			}
		}
		
		if(isset($data["pay_type"])){
			$return["data"]["pay_type"] = intval($data["pay_type"]);
		}
		
		$current_lang_id = $this->CI->pg_language->current_lang_id;
		if(isset($data["langs"])){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				foreach($languages as $value){
					if(!empty($data["langs"][$value['id']])){
						$return["langs"][$value['id']] = $data["langs"][$value['id']];
					} else {
						$return["langs"][$value['id']] = $data["langs"][$current_lang_id];
					}
				}
			}
			if(empty($return["langs"][$current_lang_id])){
				$return["errors"][] = l('error_name_incorrect', 'packages');
			}
		}

		return $return;
	}
	
	public function save_package($id, $data, $name=array()){
		if (is_null($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(PACKAGES_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(PACKAGES_TABLE, $data);
		}

		if(!empty($name)){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				$lang_ids = array_keys($languages);
				$this->CI->pg_language->pages->set_string_langs('packages', "package_name_".$id, $name, $lang_ids);
			}
		}

		unset($this->cache_package_by_id[$id]);
		if(!empty($data["gid"])) unset($this->cache_package_by_gid[$data["gid"]]);

		return $id;
	}
	
	public function delete_package($id){
		$this->DB->where("id", $id);
		$this->DB->delete(PACKAGES_TABLE);
		$this->CI->pg_language->pages->delete_string("services", "package_name_".$id);
		return;
	}
	
	public function get_package_services_price($services, $counts){
		$total = 0;
		if ($services){
			foreach($services as $key => $value){
				$total += $value['price'] * $counts[$value['id']];
			}
		}
		return $total;
	}
	
	///// callback method for payment module
	public function payment_package_status($payment_data, $payment_status){
		if($payment_status == 1){
			$user_id = $payment_data["id_user"];
			$package_id = $payment_data["payment_data"]["id_package"];
			$price = $payment_data["amount"];
			
			$package_data = $this->get_package_by_id($package_id);
			$package_params = array(
				'id_user' => $user_id,
				'id_package' => $package_id,
				'price' => $price,
				'till_date' => date('Y-m-d H:i:s', time()+$package_data['available_days']*24*60*60)
			); 
			$this->CI->load->model('packages/models/Packages_users_model');
			$user_package_id = $this->CI->Packages_users_model->save_package(null, $package_params);
			$this->CI->load->model('Services_model');
			foreach ($package_data['services_list'] as $key => $service) {
				$this->CI->Services_model->add_service_log($user_id, $service['id'], array());
				$payment_data = array(
					"id_user" => $user_id,
					"amount" => $price,
					"payment_data" => array("id_service" => $service['id'], "id_users_package" => $user_package_id, 'count' => $package_data['services_list_array'][$service['id']]),
				);
				$this->CI->Services_model->payment_service_status($payment_data, 1);
			}
		}
		return;
	}
	
	public function account_package_payment($id_package, $id_user, $price){
		if($this->pg_module->is_module_installed('users_payments')) {
			$this->CI->load->model("Users_payments_model");
			
			$package_data = $this->get_package_by_id($id_package);
			$message = l('package_payment', 'packages').$package_data["name"];
			
			$return = $this->CI->Users_payments_model->write_off_user_account($id_user, $price, $message);
			if($return === true){
				$package_params = array(
					'id_user' => $id_user,
					'id_package' => $package_data['id'],
					'price' => $price,
					'till_date' => date('Y-m-d H:i:s', time()+$package_data['available_days']*24*60*60)
				); 
				$this->CI->load->model('packages/models/Packages_users_model');
				$user_package_id = $this->CI->Packages_users_model->save_package(null, $package_params);
				$this->CI->load->model('Services_model');
				foreach ($package_data['services_list'] as $key => $service) {
					$this->CI->Services_model->add_service_log($id_user, $service['id'], array());
					$payment_data = array(
						'id_user' => $id_user,
						'amount' => $price,
						'payment_data' => array("id_service" => $service['id'], "id_users_package" => $user_package_id, 'count' => $package_data['services_list_array'][$service['id']]),
					);
					$this->CI->Services_model->payment_service_status($payment_data, 1);
				}
				$this->CI->load->helper('seo');
				$this->CI->session->set_userdata(array('service_redirect' => rewrite_link('users', 'account', array('action'=>'services'))));
			}
			return $return;
		}
		return false;
	}
	
	public function system_package_payment($system_gid, $id_user, $id_package, $price){
		$package_data = $this->get_package_by_id($id_package);
		$this->CI->load->model("payments/models/Payment_currency_model");
		$currency_gid = $this->CI->Payment_currency_model->default_currency["gid"];
		$payment_data["name"] = l('package_payment', 'services').$package_data["name"];
		$payment_data["id_package"] = $id_package;
		$this->CI->load->helper('payments');
		send_payment('packages', $id_user, $price, $currency_gid, $system_gid, $payment_data, true);
	}
	
	// seo
	function get_seo_settings($method = '', $lang_id = '') {
		if (!empty($method)) {
			return $this->_get_seo_settings($method, $lang_id);
		} else {
			$actions = array('index', 'package');
			$return = array();
			foreach ($actions as $action) {
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	function _get_seo_settings($method, $lang_id = '') {
		if ($method == "index") {
			return array(
				"templates" => array('nickname', 'fname', 'sname'),
				"url_vars" => array(),
				'url_postfix' => array(),
				'optional' => array(),
			);
		}elseif ($method == "package") {
			return array(
				"templates" => array(),
				"url_vars" => array(
					"gid" => array("gid" => 'literal'),
				),
				'url_postfix' => array(),
				'optional' => array(),
			);
		}
	}
	
	function request_seo_rewrite($var_name_from, $var_name_to, $value){
		$user_data = array();

		if($var_name_from == $var_name_to){
			return $value;
		}
		
		show_404();
	}

	function get_sitemap_xml_urls(){
		$this->CI->load->helper('seo');
		$return = array();
		return $return;
	}

	function get_sitemap_urls(){
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata("auth_type");

		$block[] = array(
			"name" => l('header_packages_index', 'packages'),
			"link" => rewrite_link('packages', 'index'),
			"clickable" => true,
		);
		return $block;
	}
}
