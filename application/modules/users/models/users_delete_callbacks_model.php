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

if (!defined('USERS_DELETE_CALLBACKS_TABLE')) define('USERS_DELETE_CALLBACKS_TABLE', DB_PREFIX.'users_delete_callbacks');

class Users_delete_callbacks_model extends \Model {
	private $CI;
	private $DB;
	private $fields = array(
		'id',
		'module',
		'model',
		'callback',
		'callback_type',
		'callback_gid',
	);
	private $fields_str;
	
	
	public function __construct(){
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_str = implode(', ', $this->fields);
		$this->DB->memcache_tables(array(USERS_DELETE_CALLBACKS_TABLE));
	}
	
	public function add_callback($module, $model, $callback, $callback_type='', $callback_gid){
		$attrs = array(
			'module' => $module,
			'model' => $model,
			'callback' => $callback,
			'callback_type' => $callback_type,
			'callback_gid' => $callback_gid,
		);
		$this->DB->insert(USERS_DELETE_CALLBACKS_TABLE, $attrs);
		return $this->DB->affected_rows();
	}
	
	public function delete_callbacks_by_module($module){
		$this->DB->where('module', $module)->delete(USERS_DELETE_CALLBACKS_TABLE);
		return $this->DB->affected_rows();
	}
	
	public function get_callbacks($callbacks_gid = array()){
		if (!empty($callbacks_gid) && is_array($callbacks_gid)) {
			$this->DB->where_in("callback_gid", $callbacks_gid);
		}		
		$result = $this->DB->select($this->fields_str)->from(USERS_DELETE_CALLBACKS_TABLE)->get()->result_array();
		return $result;
	}
	
	public function get_all_callbacks_gid(){		
		$result = $this->DB->select('callback_gid')->from(USERS_DELETE_CALLBACKS_TABLE)->get()->result_array();
		$return = array();
		foreach($result as $row){
			$return[] = $row['callback_gid'];
		}
		return $return;
	}
	
	public function execute_callbacks($id_user, $callbacks_gid){
		$cbs = $this->get_callbacks($callbacks_gid);
		foreach($cbs as $cb){
			$model_name = $cb['module'].'_'.$cb['model'];
			if($this->CI->pg_module->is_module_installed($cb['module']) && $this->CI->load->model($cb['module'].'/models/'.$cb['model'], $model_name, false, true, true) && method_exists($this->CI->{$model_name}, $cb['callback'])){
				try{
					$this->CI->{$model_name}->{$cb['callback']}($id_user, $cb['callback_type'], $callbacks_gid);
				}catch(Exception $e){
					
				}
			}
		}
	}
}
