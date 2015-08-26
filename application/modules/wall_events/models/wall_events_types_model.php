<?php

/**
 * Wall events types model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
 * */
if (!defined('TABLE_WALL_EVENTS_TYPES')) {
	define('TABLE_WALL_EVENTS_TYPES', DB_PREFIX . 'wall_events_types');
}

class Wall_events_types_model extends Model {

	private $CI;
	private $DB;
	private $fields_types = array(
		'gid',
		'module',
		'model',
		'method_format_event',
		'status',
		'settings',
		'date_add',
		'date_update'
	);
	private $fields_types_str;
	private $default_types_params = array(
		'join_period' => 30
	);

	public function __construct() {
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_types_str = implode(', ', $this->fields_types);

		$this->DB->memcache_tables(array(TABLE_WALL_EVENTS_TYPES));
	}

	public function add_wall_events_type($attrs = array()) {
		if (empty($attrs['gid'])) {
			return false;
		}
		$wall_events_type = $this->get_wall_events_type($attrs['gid']);
		if (!empty($wall_events_type)) {
			return false;
		}
		$this->_set_prepared_types_params($attrs);
		$this->DB->insert(TABLE_WALL_EVENTS_TYPES, $attrs);
		return $this->DB->affected_rows();
	}

	public function delete_wall_events_type($gid) {
		/* $this->CI->load->model('Wall_events_model');
		  $this->CI->Wall_events_model->delete_wall_events_by_gid($gid); */
		$this->CI->pg_language->pages->delete_string('wall_events', 'wetype_' . $gid);
		$this->DB->where('gid', $gid)->delete(TABLE_WALL_EVENTS_TYPES);
		return $this->DB->affected_rows();
	}

	public function get_wall_events_type($gid) {
		$result = $this->DB->select($this->fields_types_str)->from(TABLE_WALL_EVENTS_TYPES)->where('gid', $gid)->get()->row_array();
		if (!empty($result)) {
			$this->_get_prepared_types_params($result);
		}
		return $result;
	}

	public function get_wall_events_types_gids($params = array()) {
		if (!empty($params['where']) && is_array($params['where'])) {
			$this->DB->where($params['where']);
		}
		$result = $this->DB->select('gid')->from(TABLE_WALL_EVENTS_TYPES)->get()->result_array();
		$return = array();
		foreach ($result as $gid) {
			$return[$gid['gid']] = $gid['gid'];
		}
		return $return;
	}

	public function get_wall_events_types($params = array(), $page = null, $items_on_page = null) {
		$this->DB->select($this->fields_types_str)->from(TABLE_WALL_EVENTS_TYPES);
		if (!empty($params['where']) && is_array($params['where'])) {
			$this->DB->where($params['where']);
		}
		if ($page && $items_on_page) {
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}
		$result = $this->DB->get()->result_array();
		$result_format = array();
		foreach ($result as $key => $type) {
			$result_format[$type['gid']] = $type;
			$this->_get_prepared_types_params($result_format[$type['gid']]);
		}
		return $result_format;
	}

	public function get_wall_events_types_cnt() {
		$count = $this->DB->count_all(TABLE_WALL_EVENTS_TYPES);
		return $count;
	}

	public function save_wall_events_type($gid, $attrs = array()) {
		$this->_set_prepared_types_params($attrs, $gid);
		$attrs['date_update'] = date("Y-m-d H:i:s");
		$this->DB->where('gid', $gid)->update(TABLE_WALL_EVENTS_TYPES, $attrs);
		return $this->DB->affected_rows();
	}

	private function _set_prepared_types_params(&$params, $gid = '') {
		if (!isset($params['settings']) || !is_array($params['settings'])) {
			$params['settings'] = array();
		}
		if ($gid) {
			$wall_events_type = $this->get_wall_events_type($gid);
			$params['settings'] = array_merge($wall_events_type['settings'], $params['settings']);
		}
		foreach ($this->default_types_params as $key => $value) {
			if (!isset($params['settings'][$key])) {
				$params['settings'][$key] = $value;
			}
			if ($params['settings'][$key] === false) {
				$params['settings'][$key] = 0;
			}
		}
		$params['settings'] = serialize($params['settings']);
	}

	private function _get_prepared_types_params(&$params) {
		if ($params) {
			$settings = unserialize($params['settings']);
			if (!is_array($settings)) {
				$settings = array();
			}
			$params['settings'] = array_merge($this->default_types_params, $settings);
		}
	}

	public function update_langs($wetypes, $langs_file) {
		foreach ($wetypes as $wetype) {
			$this->CI->pg_language->pages->set_string_langs('wall_events', 'wetype_' . $wetype, $langs_file['wetype_' . $wetype], array_keys($langs_file['wetype_' . $wetype]));
		}
	}

	public function export_langs($wetypes, $langs_ids) {
		$gids = array();
		foreach ($wetypes as $wetype) {
			$gids[] = 'wetype_' . $wetype;
		}
		return $this->CI->pg_language->export_langs('wall_events', $gids, $langs_ids);
	}

}
