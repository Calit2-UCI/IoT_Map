<?php

namespace Pg\Modules\Users\Models;

/**
* Users perfect match model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-04-11 15:07:07 +0300 $ $Author: dpopenov $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('PERFECT_MATCH_TABLE', DB_PREFIX.'users_perfect_match');

class Users_perfect_match_model extends \Model
{
	private $CI;
	private $DB;
	private $fields = array(
		'id_user',
		'looking_user_type',
		'id_country',
		'id_region',
		'id_city',
		'age_min',
		'age_max',
		'full_criteria',
	);
	private $fields_all = array();
	private $linked_fields = array(
		'looking_user_type',
		'age_min',
		'age_max',
	);
	private $linked_fields_all = array();

	private $dop_fields = array();

	public $form_editor_type = "users";

	public $perfect_match_form_gid = "perfect_match";


	public function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_all = $this->linked_fields_all = $this->fields;
	}

	public function set_additional_fields($fields){
		$this->dop_fields = $fields;
		$this->fields_all = (!empty($this->dop_fields))?array_merge($this->fields, $this->dop_fields):$this->fields;
		$this->linked_fields_all = (!empty($this->dop_fields))?array_merge($this->linked_fields, $this->dop_fields):$this->linked_fields;
		return;
	}

	public function save_perfect_match($user_id, $attrs = array(), $fields_type = 'linked'){
		$params = ($fields_type == 'linked') ? $this->get_user_perfect_match_params($user_id, true) : array('full_criteria' => array());
		$fields = ($fields_type == 'linked') ? $this->linked_fields_all : $this->fields_all;
		foreach($fields as $field){
			if(isset($attrs[$field]) && $field != 'id_user'){
				if(in_array($field, $this->fields)){
					$params[$field] = $attrs[$field];
				}
				$params['full_criteria'][$field] = $attrs[$field];
			}
		}
		if(!empty($params['full_criteria'])) {
			foreach($params['full_criteria'] as $field => $full_criteria){
				if(!in_array($field, $this->fields_all)){
					unset($params['full_criteria'][$field]);
				}
			}
		}
		foreach($params as $field => $param){
			if(!in_array($field, $this->fields)){
				unset($params[$field]);
			}
		}

		if($params){
			$params['full_criteria'] = serialize($params['full_criteria']);
			$params_ins = $params;
			$params_ins['id_user'] = $user_id;
			$params_upd = array();
			foreach($params as $field => $attr){
				$params_upd[] = "`{$field}`=".$this->DB->escape($attr);
			}
			$sql = $this->DB->insert_string(PERFECT_MATCH_TABLE, $params_ins) . " ON DUPLICATE KEY UPDATE ".implode(',', $params_upd);
			$this->DB->query($sql);
		}
	}

	public function update_users_perfect_match($users){
		foreach($users as $u){
			$this->save_perfect_match($u['id'], $u, 'linked');
		}
	}

	public function get_user_perfect_match_params($user_id, $for_save = false){
		$result = $this->get_users_perfect_match_params(array($user_id), $for_save);
		return !empty($result[$user_id]) ? $result[$user_id] : array();
	}

	public function get_users_perfect_match_params($users_ids, $for_save = false){
		$users_perfect_match = $this->DB->select(implode(',',$this->fields))->from(PERFECT_MATCH_TABLE)->where_in('id_user', $users_ids)->get()->result_array();
		//get all fields from FE for current perfect match form
		$fields_all_cache = $this->fields_all;
		$this->CI->load->model('field_editor/models/Field_editor_forms_model');
		$form = $this->CI->Field_editor_forms_model->get_form_by_gid($this->perfect_match_form_gid, $this->form_editor_type);
        $this->CI->load->model('Field_editor_model');
		$fields_for_search = $this->CI->Field_editor_model->get_fields_names_for_search($form);
		$this->set_additional_fields($fields_for_search);
		$fields_for_search_by_keys = array_flip($this->fields_all);

		$result = array();
		foreach($users_perfect_match as $upm){
			$user_id = $upm['id_user'];
			unset($upm['id_user']);
			$upm['full_criteria'] = $upm['full_criteria'] ? unserialize($upm['full_criteria']) : array();
			//save only fields from current PM form
			$upm['full_criteria'] = array_intersect_key($upm['full_criteria'], $fields_for_search_by_keys);
			if(!$for_save){
				$upm['user_type'] = isset($upm['looking_user_type']) ? $upm['looking_user_type'] : null;
				unset($upm['looking_user_type']);
				$upm['full_criteria']['user_type'] = isset($upm['full_criteria']['looking_user_type']) ? $upm['full_criteria']['looking_user_type'] : null;
				unset($upm['full_criteria']['looking_user_type']);
			}
			$result[$user_id] = $upm;
		}
		$this->fields_all = $fields_all_cache; //restore fields

		return $result;
	}

	public function delete_user_perfect_match_params($user_id){
		$result = $this->delete_users_perfect_match_params(array($user_id));
		return $result;
	}

	public function delete_users_perfect_match_params($users_ids){
		$result = $this->DB->where_in('id_user', $users_ids)->delete(PERFECT_MATCH_TABLE);
	}

	public function validate($data, $type = 'save'){
		$return["errors"] = array();
		$return["data"] = array();
		if (isset($data["id_user"])) {
			$return["data"]["id_user"] = intval($data["id_user"]);
		}
		if (isset($data["looking_user_type"])) {
			$return["data"]["looking_user_type"] = $data["looking_user_type"];
		}elseif(isset($data["user_type"])){
			$return["data"]["looking_user_type"] = $data["user_type"];
		}
		if($type == 'select' && isset($return["data"]["looking_user_type"])){
			$return["data"]["user_type"] = $return["data"]["looking_user_type"];
			unset($return["data"]["looking_user_type"]);
		}
		if (isset($data["id_country"])) {
			$return["data"]["id_country"] = trim(strip_tags($data["id_country"]));
		}
		if (isset($data["id_region"])) {
			$return["data"]["id_region"] = intval($data["id_region"]);
		}
		if (isset($data["id_city"])) {
			$return["data"]["id_city"] = intval($data["id_city"]);
		}
		$age_min = $this->pg_module->get_module_config('users', 'age_min');
		$age_max = $this->pg_module->get_module_config('users', 'age_max');
		if(isset($data['age_min'])){
			$return["data"]["age_min"] = intval($data['age_min']);
			if($return["data"]["age_min"] < $age_min || $return["data"]["age_min"] > $age_max){
				$return["data"]["age_min"] = $age_min;
			}
		}
		if(isset($data['age_max'])){
			$return["data"]["age_max"] = intval($data['age_max']);
			if($return["data"]["age_max"] < $age_min || $return["data"]["age_max"] > $age_max){
				$return["data"]["age_max"] = $age_max;
			}
			if(!empty($return["data"]["age_min"]) && $return["data"]["age_min"] > $return["data"]["age_max"]){
				$return["data"]["age_min"] = $age_min;
			}
		}

		$this->CI->load->model('Field_editor_model');
		$validate_data = $this->CI->Field_editor_model->validate_fields_for_select(array(), $data);
		$return["data"] = array_merge($return["data"], $validate_data["data"]);
		if(!empty($validate_data["errors"])){
			$return["errors"] = array_merge($return["errors"], $validate_data["errors"]);
		}

		return $return;
	}
}