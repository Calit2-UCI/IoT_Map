<?php

namespace Pg\Modules\Users\Models;

/**
* Users statuses model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
if (!defined('USERS_STATUSES_CALLBACKS_TABLE')) define('USERS_STATUSES_CALLBACKS_TABLE', DB_PREFIX.'users_statuses_callbacks');

class Users_statuses_model extends \Model {

	private $CI;
	private $DB;
	private $fields = array(
		'id',
		'module',
		'model',
		'method',
		'event_status',
		'date_add',
	);
	private $fields_str;
	
	public $statuses = array(
		0 => 'offline',
		1 => 'online',
		2 => 'busy',
		3 => 'away',
	);
	private $statuses_keys;
	
	
	public function __construct(){
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_str = implode(', ', $this->fields);
		$this->statuses_keys = array_flip($this->statuses);
	}
	
	public function set_status($user_id, $status){
		if(isset($this->statuses[$status])){
			$this->CI->load->model('Users_model');
			$attrs['site_status'] = intval($status);
			$is_updated = $this->CI->Users_model->simply_update_user($user_id, $attrs);
			$event_status = $this->statuses[$status];
			if($is_updated){
				$this->execute_callbacks($event_status, $user_id);
			}
			return true;
		}
		return false;
	}
	
	public function add_callback($module, $model, $method, $event_status = ''){
		$attrs = array(
			'module' => $module,
			'model' => $model,
			'method' => $method,
			'event_status' => $event_status,
			'date_add' => date("Y-m-d H:i:s"),
		);
		$this->DB->insert(USERS_STATUSES_CALLBACKS_TABLE, $attrs);
		return $this->DB->affected_rows();
	}
	
	public function delete_callbacks_by_module($module){
		$this->DB->where('module', $module)->delete(USERS_STATUSES_CALLBACKS_TABLE);
		return $this->DB->affected_rows();
	}
	
	public function delete_callbacks_by_id($id){
		$this->DB->where('id', $id)->delete(USERS_STATUSES_CALLBACKS_TABLE);
		return $this->DB->affected_rows();
	}
	
	public function get_callbacks($event_status = '', $module = ''){
		if($module){
			$this->DB->where('module', $module);
		}
		if($event_status){
			$this->DB->where_in('event_status', array('', $event_status));
		}
		
		$result = $this->DB->select($this->fields_str)->from(USERS_STATUSES_CALLBACKS_TABLE)->get()->result_array();

		return $result;
	}

	public function execute_callbacks($event_status, $user_id, $module = ''){
		$cbs = $this->get_callbacks($event_status, $module);
		foreach($cbs as $cb){
			$model_name = $cb['module'].'_'.$cb['model'];
			if($this->CI->pg_module->is_module_installed($cb['module']) && $this->CI->load->model($cb['module'].'/models/'.$cb['model'], $model_name, false, true, true) && method_exists($this->CI->{$model_name}, $cb['method'])){
				try{
					$this->CI->{$model_name}->{$cb['method']}($this->statuses_keys[$event_status], (array)$user_id);
				}catch(Exception $e){
					echo $e->getMessage();
				}
			}
		}
	}
	
	public function get_user_statuses($id){
		$status = $this->get_users_statuses($id);
		return !empty($status[$id]) ? $status[$id] : array();
	}
	
	public function get_users_statuses($ids){
		if(!is_array($ids) && intval($ids)){
			$ids = array(intval($ids));
		}else{
			return array();
		}
		$this->CI->load->model('Users_model');
		$users = $this->CI->Users_model->get_users_list_by_key(null, null, null, array(), $ids, false);
		$result = array();
		foreach($users as $uid => $user){
			$result[$uid] = $this->format_status($user['online_status'], $user['site_status']);
		}
		return $result;
	}
	
	public function format_status($online_status, $site_status){
		$result['online_status'] = $online_status;
		$result['site_status'] = $site_status;
		$result['online_status_text'] = $online_status ? 'online' : 'offline';
		$result['current_site_status'] = $online_status ? $site_status : 0;
		$result['site_status_text'] = isset($this->statuses[$site_status]) ? $this->statuses[$site_status] : '';
		$result['current_site_status_text'] = isset($this->statuses[$result['current_site_status']]) ? $this->statuses[$result['current_site_status']] : '';
		$result['online_status_lang'] = l('status_online_'.$result['online_status'], 'users');
		$result['site_status_lang'] = l('status_site_'.$result['site_status'], 'users');
		$result['current_site_status_lang'] = l('status_site_'.$result['current_site_status'], 'users');
		return $result;
	}
}