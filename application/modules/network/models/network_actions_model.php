<?php

namespace Pg\Modules\Network\Models;

use Pg\Modules\Network\Models\Network_users_model;

/**
 * Network actions model
 *
 * @package PG_Dating
 * @subpackage application
 * @category modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * Network model
 */
class Network_actions_model extends \Model {

	protected $CI;
	protected $DB;

	public function __construct() {
		$this->CI = &get_instance();
		$this->CI->load->model('network/models/Network_users_model');
	}

	public function get_settings() {
		$this->CI->load->model('Network_model');
		return $this->CI->Network_model->get_config($this->CI->Network_model->cfg_filter);
	}

	public function get_profiles($count = null) {
		$data = $this->CI->Network_users_model->get_profiles($count);
		return $data;
	}

	public function get_temp_profiles($action, $field = 'net_id') {
		if(!is_array($field)) {
			$field = array($field);
		}
		$records = $this->CI->Network_users_model->get_temp_records(null, null, $action, 'out', $field);
		return $records;
	}

	public function get_processed_net_ids($action, $field = 'net_id') {
		$temp_profiles = $this->get_temp_profiles($action, $field);
		$net_ids = array();
		foreach($temp_profiles as $temp_profile) {
			$net_ids[] = $temp_profile[$field];
		}
		return $net_ids;
	}

	public function set_temp_profiles_add($profiles) {
		foreach ($profiles as &$profile) {
			$profile['net_id'] = $profile['id'];
			unset($profile['id']);
			$profile['photos'] = serialize($profile['photos']);
			$profile['profile_data'] = serialize($profile['profile_data']);
		}
		return $this->CI->Network_users_model->set_temp_profiles(
				$profiles, 
				Network_users_model::ACTION_ADD,
				Network_users_model::TYPE_IN);
	}

	public function set_temp_profiles_remove($net_ids) {
		$profiles = array();
		foreach ($net_ids as $net_id) {
			$profiles[]['net_id'] = $net_id;
		}
		return $this->CI->Network_users_model->set_temp_profiles(
				$profiles, 
				Network_users_model::ACTION_REMOVE, 
				Network_users_model::TYPE_IN);
	}

	public function set_temp_profiles_update($profiles) {
		return $this->CI->Network_users_model->set_temp_profiles(
				$profiles, 
				Network_users_model::ACTION_UPDATE, 
				Network_users_model::TYPE_IN);
	}

	public function set_profiles_status($data) {
		return $this->CI->Network_users_model->set_profiles_status($data);
	}

	public function get_last_id() {
		return $this->CI->Network_users_model->get_last_id();
	}

	public function process_temp() {
		return $this->CI->Network_users_model->process_temp();
	}

	public function delete_temp_records($net_id = null, $local_id = null, $action = null, $type = null) {
		return $this->CI->Network_users_model->delete_temp_records($net_id, $local_id, $action, $type);
	}

}
