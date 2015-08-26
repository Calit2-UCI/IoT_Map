<?php

/**
 * Wall events permissions model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
if (!defined('TABLE_WALL_EVENTS_PERMISSIONS')) {
	define('TABLE_WALL_EVENTS_PERMISSIONS', DB_PREFIX . 'wall_events_permissions');
}

class Wall_events_permissions_model extends Model {

	private $CI;
	private $DB;
	private $_fields = array(
		'id_user',
		'permissions',
	);
	private $_fields_str;

	public function __construct() {
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->_fields_str = implode(', ', $this->_fields);
	}

	public function get_user_permissions($id_user) {
		$event_perm = $this->_get_default_permissions();
		$saved_perm = $this->DB->select('permissions')->from(TABLE_WALL_EVENTS_PERMISSIONS)->where('id_user', $id_user)->get()->row_array();
		if (!empty($saved_perm)) {
			$user_perm = unserialize($saved_perm['permissions']);
		} else {
			$user_perm = array();
		}
		$result = array();
		foreach ($event_perm as $e_gid => $perm) {
			foreach ($perm as $perm_name => $val) {
				$result[$e_gid][$perm_name] = isset($user_perm[$e_gid][$perm_name]) ? $user_perm[$e_gid][$perm_name] : $val;
			}
		}
		return $result;
	}

	public function get_user_feeds($id_user) {
		$perms = $this->get_user_permissions($id_user);
		$result = array();
		foreach ($perms as $e_gid => $perm) {
			if ($perm['feed']) {
				$result[] = $e_gid;
			}
		}
		return $result;
	}

	public function set_user_permissions($id_user, $permissions) {
		$attrs['id_user'] = $id_user;
		if (is_array($permissions)) {
			foreach ($permissions as &$perm) {
				array_map('intval', $perm);
			}
		}
		$attrs['permissions'] = is_array($permissions) ? serialize($permissions) : '';
		$sql = $this->DB->insert_string(TABLE_WALL_EVENTS_PERMISSIONS, $attrs) . " ON DUPLICATE KEY UPDATE `permissions`=" . $this->DB->escape($attrs['permissions']);
		$this->DB->query($sql);
		return $this->DB->affected_rows();
	}

	private function _get_default_permissions() {
		$result = array();
		$this->CI->load->model('wall_events/models/Wall_events_types_model');
		$params['where']['status'] = '1';
		$events_types = $this->CI->Wall_events_types_model->get_wall_events_types($params);
		foreach ($events_types as $e_type) {
			$result[$e_type['gid']] = $e_type['settings']['permissions'];
		}
		return $result;
	}

	public function is_permissions_allowed($permissions, $id_wall, $id_poster) {
		if ($permissions <= 0 || $permissions > 3) {
			return false;
		}

		if ($id_wall && $id_wall == $id_poster || $permissions == 3) {
			return true;
		}

		$user_id = $this->CI->session->userdata('user_id');
		if ($user_id && $permissions >= 2) {
			return true;
		}

		/*if ($this->CI->pg_module->is_module_installed('users_lists')) {
			$this->CI->load->model('Users_lists_model');
			$is_friend = $this->CI->Users_lists_model->is_friend($id_wall, $user_id);
		} else*/if ($this->CI->pg_module->is_module_installed('friendlist')) {
			$this->CI->load->model('Friendlist_model');
			$is_friend = $this->CI->Friendlist_model->is_friend($id_wall, $user_id);
		} else {
			$is_friend = false;
		}
		if ($permissions == 1 && $is_friend) {
			return true;
		}

		return false;
	}

}
