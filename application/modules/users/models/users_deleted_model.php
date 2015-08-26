<?php

namespace Pg\Modules\Users\Models;

/**
* Contact us user side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Nikita Savanaev <nsavanev@pilotgroup.net>
**/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!defined('USERS_DELETED_TABLE')) define('USERS_DELETED_TABLE', DB_PREFIX.'users_deleted');

class Users_deleted_model extends \Model {
	private $CI;
	private $DB;
	private $fields = array(
		'id',
		'id_user',
		'nickname',
		'fname',
		'sname',
		'email',
		'data',
		'date_deleted',
		'status_deleted',
	);
	private $fields_all;
	
	
	public function __construct(){
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_all = implode(', ', $this->fields);
		$this->DB->memcache_tables(array(USERS_DELETED_TABLE));
	}
	
	public function get_user_by_id($id, $formatted = false) {
		$result = $this->DB->select($this->fields_all)
				->from(USERS_DELETED_TABLE)
				->where("id", $id)->get()->result_array();
		if(empty($result)) {
			return false;
		} else if($formatted) {
			return $this->format_user($result[0]);
		} else {
			return $result[0];
		}
	}
	
	public function get_user_by_user_id($id_user, $formatted = false) {
		$result = $this->DB->select($this->fields_all)
				->from(USERS_DELETED_TABLE)
				->where("id_user", $id_user)
				->get()->result_array();
		if(empty($result)) {
			return false;
		} else if($formatted) {
			return $this->format_user($result[0]);
		} else {
			return $result[0];
		}
	}
	
	public function get_user_by_login($login) {
		$result = $this->DB->select($this->fields_all)
				->from(USERS_DELETED_TABLE)
				->where("nickname", $login)
				->get()->result_array();
		return empty($result) ? false : $result[0];
	}
	
	public function get_user_by_email($email) {
		$result = $this->DB->select($this->fields_all)
				->from(USERS_DELETED_TABLE)
				->where("email", $email)
				->get()->result_array();
		return empty($result) ? false : $result[0];
	}
	
	public function get_all_users_id($status_deleted=0){
		$result = $this->DB->select('id_user')
				->from(USERS_DELETED_TABLE)
				->where("status_deleted", $status_deleted)
				->get()->result_array();
		$return = array();
		foreach($result as $row){
			$return[] = $row['id_user'];
		}
		return $return;
	}
	
	public function get_users_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = array(), $formatted = true) {
	
		if(isset($params["fields"]) && is_array($params["fields"]) && count($params["fields"])){
			$this->set_additional_fields($params["fields"]);
		}
		
		$this->DB->select($this->fields_all);
		$this->DB->from(USERS_DELETED_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}

		if (isset($params["like"]) && is_array($params["like"]) && count($params["like"])) {
			foreach ($params["like"] as $field => $value) {
				$this->DB->like($field, $value);
			}
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}

		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}

		if (!empty($filter_object_ids) && is_array($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->fields) || $field == 'fields') {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			if($formatted) $results = $this->format_users($results);
			return $results;
		}
		return array();
	}
	
	public function get_users_list_by_key($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = array(), $formatted = true) {
		$list = $this->get_users_list($page, $items_on_page, $order_by, $params, $filter_object_ids, $formatted);
		if (!empty($list)) {
			foreach ($list as $l) {
				$data[$l["id"]] = $l;
			}
			return $data;
		} else {
			return array();
		}
	}
	
	public function get_users_count($params = array(), $filter_object_ids = null) {
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}

		if (isset($params["like"]) && is_array($params["like"]) && count($params["like"])) {
			foreach ($params["like"] as $field => $value) {
				$this->DB->like($field, $value);
			}
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}

		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}

		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		$result = $this->DB->count_all_results(USERS_DELETED_TABLE);

		return $result;
	}
	
	public function get_user_callbacks($id_user, $callbacks){			
		$results = $this->get_user_by_user_id($id_user);
		$results = $this->format_user_callbacks($results, $callbacks);
		if (!empty($results) && is_array($results)) {
			return $results;
		}
		return array();
	}
	
	public function get_user_callback_gid($id_user, $status_deleted = 0){
		$results = $this->DB->select($this->fields_all)
				->from(USERS_DELETED_TABLE)
				->where("id_user", $id_user)
				->where("status_deleted", $status_deleted)
				->get()->result_array();
		if(empty($results)) {
			return false;
		}
		$user_callbacks = unserialize($results[0]['data']);
		return $user_callbacks;
	}
	
	private function format_user_callbacks($user_data, $callbacks){
		$user_callbacks = array();
		if(!empty($user_data['data']))
			$user_callbacks = unserialize($user_data['data']);
		foreach($callbacks as $key => $callback){
			$callbacks[$key]['name'] = l('delete_user_'.$callback['callback_gid'], $callback['module']);
			$callbacks[$key]['disabled_attr'] = '';
			if(in_array($callback['callback_gid'],$user_callbacks))
				$callbacks[$key]['disabled_attr'] = 'disabled';
		}
		return $callbacks;
	}
	
	public function format_users($data){
		foreach($data as $key=>$user){
			$data[$key] = $user;
		}
		return $data;
	}
	
	public function format_user($data, $lang_id = ''){
		if($data){
			$user['id'] = $data['id_user'];
			$user['output_name'] = $data['fname']."&nbsp;".$data['sname']."<br>(<font class='error'>".l('success_delete_user', 'users')."</font>)";
			$user['fname'] = $data['fname'];
			$user['sname'] = $data['sname'];
			$user['nickname'] = $data['nickname'];
			$user['email'] = $data['email'];
			$user['data'] = $data['data'];
			$return = $this->format_users(array(0=>$user));
			return $return[0];
		}
		return array();
	}
	
	public function save_deleted_user($data){
		$user_data = $this->get_user_by_user_id($data['id']);
		if(in_array('users_uploads', $data['callbacks'])){
			$callbacks =  array_unique(array_merge($data['callbacks'],array('users_delete','media_user','media_gallery')));				
		}elseif(in_array('media_user', $data['callbacks'])){
			$callbacks =  array_unique(array_merge($data['callbacks'],array('media_gallery')));	
		}else{
			$callbacks =  $data['callbacks'];	
		}
		if(!empty($user_data['id'])){
			$user_callbacks = array_merge($callbacks, unserialize($user_data['data']));
			$diff_callback = array_diff($user_callbacks, unserialize($user_data['data']));
			if(!empty($diff_callback)){
				$attrs['status_deleted'] = 0;
			}				
			$attrs['data'] = serialize($user_callbacks);
			$this->DB->where('id', $user_data['id']);
			$this->DB->update(USERS_DELETED_TABLE, $attrs);
		}else{
			$attrs['id_user'] = $data['id'];
			$attrs['nickname'] = $data['nickname'];
			$attrs['fname'] = $data['fname'];
			$attrs['sname'] = $data['sname'];
			$attrs['email'] = $data['email'];
			$attrs['data'] = serialize($callbacks);
			$attrs['date_deleted'] = date("Y-m-d H:i:s");
			$attrs['status_deleted'] = 0;
			$this->DB->insert(USERS_DELETED_TABLE, $attrs);
			$user_id = $this->DB->insert_id();
			return $user_id;
		}
	}
	
	public function set_status_deleted($id_user, $status_deleted){
		$this->DB->set('status_deleted', $status_deleted)->where('id_user', $id_user)->update(USERS_DELETED_TABLE);
	}
	
}
