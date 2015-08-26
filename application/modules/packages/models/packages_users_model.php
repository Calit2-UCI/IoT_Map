<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Users services model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2012-09-20 09:47:07 +0300 (Пт, 20 сент 2012) $ $Author: abatukhtin $
**/

if (!defined('PACKAGES_USERS_TABLE')) define('PACKAGES_USERS_TABLE', DB_PREFIX . 'packages_users');

class Packages_users_model extends Model {

	private $CI;
	private $DB;
	private $fields = array(
		'id',
		'id_user',
		'id_package',
		'price',
		'till_date'
	);

	/**
	 * Constructor
	 *
	 * @return users object
	 */
	public function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}
	
	public function save_package($package_id = null, $attrs = array()) {
		if (is_null($package_id)) {
			$this->DB->insert(PACKAGES_USERS_TABLE, $attrs);
			$package_id = $this->DB->insert_id();
		} else {
			$this->DB->where('id', $package_id);
			$this->DB->update(PACKAGES_USERS_TABLE, $attrs);
		}

		return $package_id;
	}
	
	public function get_user_packages_list($order_by = null, $params = array(), $filter_object_ids = null) {
		$data = array();

		$this->DB->select(implode(", ", $this->fields))->from(PACKAGES_USERS_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			$this->DB->where($params["where"]);
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value);
			}
		}
		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->fields)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}
		
		$results = $this->DB->get()->result_array();

		return $this->format_packages($results);
	}

	public function format_package($data) {
		$this->CI->load->model('services/models/Services_users_model');
		$this->CI->load->model('Packages_model');
		if(!$this->CI->Packages_model->is_cache_set){
			$this->CI->Packages_model->cache_all();
		}
		$param['where']['id_users_package'] = $data['id'];
		$data['user_services'] = $this->CI->Services_users_model->get_services_list($param);
		$data['package_info'] = $this->CI->Packages_model->get_package_by_id($data['id_package']);
		return $data;
	}
	
	public function format_packages($packages) {
		if(!$packages){
			return array();
		}
		$this->CI->load->model('services/models/Services_users_model');
		$this->CI->load->model('Packages_model');
		if(!$this->CI->Packages_model->is_cache_set){
			$this->CI->Packages_model->cache_all();
		}
		$user_package_ids = array();
		foreach($packages as $package){
			$user_package_ids[$package['id']] = $package['id'];
		}
		$user_services_by_id_package = array();
		if($user_package_ids){
			$param['where_in']['id_users_package'] = $user_package_ids;
			$user_package_services = $this->CI->Services_users_model->get_services_list($param);
			foreach($user_package_services as $ups){
				$user_services_by_id_package[$ups['id_users_package']][$ups['id']] = $ups;
			}
		}
		$result = array();
		foreach($packages as $package){
			$result[$package['id']] = $package;
			$result[$package['id']]['user_services'] = !empty($user_services_by_id_package[$package['id']]) ? $user_services_by_id_package[$package['id']] : array();
			$user_services_count = 0;
			foreach($result[$package['id']]['user_services'] as $us){
				$user_services_count += $us['count'];
			}
			$result[$package['id']]['user_services_count'] = $user_services_count;
			$result[$package['id']]['package_info'] = $this->CI->Packages_model->get_package_by_id($package['id_package']);
			$result[$package['id']]['timeout'] = (strtotime($package['till_date']) < time()) ? true : false;
			$result[$package['id']]['is_active'] = (!$result[$package['id']]['timeout'] && $user_services_count);
		}
		return $result;
	}
	
	public function get_user_packages_count($params=array(), $filter_object_ids=null){
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			$this->DB->where($params["where"]);
		}
		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
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

		return $this->DB->count_all_results(PACKAGES_USERS_TABLE);
	}
	
	public function update_packages(){
		$this->DB->select('id')->from(PACKAGES_USERS_TABLE)->where('till_date <', date('Y-m-d H:i:s'));
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			$ids = array();
			foreach($results as $r){
				$ids[] = $r['id'];
			}
			if($ids){
				$data['status'] = 0;
				$params['where_in']['id_users_package'] = $ids;
				$this->CI->load->model('services/models/Services_users_model');
				$this->CI->Services_users_model->update_service($params, $data);
			}
		}
	}
}
