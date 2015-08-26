<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Mailbox Model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/
define('MAILBOX_SERVICES_TABLE', DB_PREFIX.'mailbox_services');

class Mailbox_service_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 * @var object
	 */
	private $CI;
	
	/**
	 * Link to database object
	 * @var object
	 */
	private $DB;
	
	/**
	 * Message data
	 * @var array
	 */
	private $fields = array(
		'id',
		'id_user',
		'gid_service',
		'service_data',
		'date_add',
		'date_modified',
	);
	
	/**
	 * Format settings
	 * @var array
	 */
	private $format_settings = array(
		"use_format" => true,
	);

	/**
	 * Constructor
	 *
	 * @return Mailbox_model object
	 */
	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}
	
	/**
	 * Return mailbox services as array
	 * @param array $params criteria
	 * @param integer $page page of results
	 * @param integer $limits rows per page
	 * @param array $order_by sorting
	 * @return array
	 */
	private function _get($params=array(), $page=null, $limits=null, $order_by=null){
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(MAILBOX_SERVICES_TABLE);

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
				$this->DB->where($value, null, false);
			}
		}

		if(is_array($order_by) && count($order_by) > 0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->fields)){
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}elseif($order_by){
			$this->DB->order_by($order_by);
		}

		if(!is_null($page)){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($limits, $limits*($page-1));
		}
	
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return $this->format_services($results);
		}
		return array();
	}
	
	/**
	 * Return service data by id
	 * @param integer $mailbox_service_id
	 * @return array
	 */
	public function get_service_by_id($mailbox_service_id){
		$params['where']['id'] = $mailbox_service_id;
		$results = $this->_get($params);
		if(!empty($results)){
			return $results[0];
		}else{
			return array();
		}
	}
	
	/**
	 * Return service data by user and service
	 * @param integer $user_id user identifier
	 * @param string $service_gid service GUID
	 * @return array
	 */
	public function get_service_by_user_service($user_id, $service_gid){
		$params['where']['id_user'] = $user_id;
		$params['where']['gid_service'] = $service_gid;
		$results = $this->_get($params);
		if(!empty($results)){
			return $results[0];
		}else{
			return array();
		}
	}
	
	/**
	 * Save service data to data source
	 * @param integer $mailbox_service_id service identifier
	 * @param array $data service data
	 * @return integer
	 */
	public function save_service($mailbox_service_id = null, $data){
		$data['date_modified'] = date('Y-m-d H:i:s');
		if(is_null($mailbox_service_id)){
			$data["date_add"] = date('Y-m-d H:i:s');
			$this->DB->insert(MAILBOX_SERVICES_TABLE, $data);
			$mailbox_service_id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $mailbox_service_id);
			$this->DB->update(MAILBOX_SERVICES_TABLE, $data);
		}
		return $mailbox_service_id;
	}
	
	/**
	 * Change format settings
	 * @param string $name parameter name
	 * @param mixed $value parameter value
	 */
	public function set_format_settings($name, $value=false){
		if(!is_array($name)) $name = array($name=>$value);
		if(empty($name)) return;
		foreach($name as $key => $item)	$this->format_settings[$key] = $item;
	}
	
	/**
	 * Fromat service data
	 * @param array $data service data
	 * @return array
	 */
	public function format_services($data){
		if(!$this->format_settings['use_format']) return $data;
		
		foreach($data as $key=>$service){
			$data[$key]['service_data'] = unserialize($service['service_data']);
			if(!empty($service['service_data'])) $service['service_data'] = array();
		}
		
		return $data;
	}
	
	/**
	 * Validate service data
	 * @param integer $mailbox_service_id service identifier
	 * @param array $data service data
	 * @return array
	 */
	public function validate_service($mailbox_service_id, $data){
		$return = array('errors'=>array(), 'data'=>array());
		
		if(isset($data['id_user'])){
			$return['data']['id_user'] = intval($data['id_user']);
		}
		
		if(isset($data['gid_service'])){
			$return['data']['gid_service'] = strval($data['gid_service']);
		}
		
		if(isset($data['service_data'])){
			if(empty($data['service_data'])) $data['service_data'] = array();
			$return['data']['service_data'] = serialize($data['service_data']);
		}
		
		if(isset($data['date_add'])){
			$value = strtotime($data['date_add']);
			if($value > 0){
				$return['data']['date_add'] = date("Y-m-d", $value);
			}else{
				$return['data']['date_add'] = '0000-00-00 00:00:00';
			}
		}
		
		if(isset($data['date_modified'])){
			$value = strtotime($data['date_modified']);
			if($value > 0){
				$return['data']['date_modified'] = date("Y-m-d", $value);
			}else{
				$return['data']['date_modified'] = '0000-00-00 00:00:00';
			}
		}
		
		return $return;
	}
}
