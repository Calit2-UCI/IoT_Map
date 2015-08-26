<?php

namespace Pg\Modules\Users\Models;

/**
 * Users groups model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

define('GROUPS_TABLE', DB_PREFIX . 'groups');

class Groups_model extends \Model {

	public $CI;
	public $DB;
	public $fields_all = array(
		'id',
		'gid',
		'is_default',
		'date_created',
		'date_modified'
	);

	/**
	 * Constructor
	 *
	 * @return users object
	 */
	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->DB->memcache_tables(array(GROUPS_TABLE));
	}

	function get_group_by_id($group_id) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(GROUPS_TABLE)->where("id", $group_id)->get()->result_array();
		if (empty($result)) {
			return false;
		} else {
			$data = $result[0];
			return $data;
		}
	}

	function get_group_by_gid($group_gid) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(GROUPS_TABLE)->where("gid", $group_gid)->get()->result_array();
		if (empty($result)) {
			return false;
		} else {
			$data = $result[0];
			return $data;
		}
	}

	function get_default_group_id() {
		$result = $this->DB->select("id")->from(GROUPS_TABLE)->where("is_default", '1')->get()->result_array();
		if (empty($result)) {
			return 0;
		} else {
			return $result[0]["id"];
		}
	}

	function get_groups_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null) {
		$this->DB->select(implode(", ", $this->fields_all));
		$this->DB->from(GROUPS_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
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
				if (in_array($field, $this->fields_all)) {
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
			foreach ($results as $r) {
				$data[] = $this->format_group($r);
			}
			return $data;
		}
		return false;
	}

	function get_groups_count($params = array(), $filter_object_ids = null) {
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(GROUPS_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
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

		$result = $this->DB->get()->result();
		if (!empty($result)) {
			return intval($result[0]->cnt);
		} else {
			return 0;
		}
	}

	function save_group($group_id = null, $attrs = array(), $lang_data = array()) {
		if (is_null($group_id)) {
			$attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->insert(GROUPS_TABLE, $attrs);
			$group_id = $this->DB->insert_id();
		} else {
			$attrs["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $group_id);
			$this->DB->update(GROUPS_TABLE, $attrs);
		}

		if (isset($lang_data) && !empty($lang_data)) {

			$default_lang_id = $this->CI->pg_language->get_default_lang_id();

			if (isset($lang_data[$default_lang_id])) {
				$default_value = $lang_data[$default_lang_id];
			} else {
				$default_value = current($lang_data);
			}

			foreach ($this->CI->pg_language->languages as $lang_id => $language) {
				if (!isset($lang_data[$lang_id])) {
					$lang_data[$lang_id] = $default_value;
				}
				$langs_id[] = $lang_id;
			}

			$this->CI->pg_language->pages->set_string_langs("groups_langs", "group_item_" . $group_id, $lang_data, $langs_id);
		}
		return $group_id;
	}

	function delete_group($group_id) {
		$this->DB->where('id', $group_id);
		$this->DB->delete(GROUPS_TABLE);
		$this->CI->pg_language->pages->delete_string("groups_langs", "group_item_" . $group_id);
		return;
	}

	function format_group($data) {
		if (!empty($data["id"])) {
			$data["group_name"] = $this->CI->pg_language->get_string("groups_langs", "group_item_" . $data["id"]);
		}
		return $data;
	}

	function validate_group($group_id = null, $data = array()) {
		$return = array("errors" => array(), "data" => array());

		if (isset($data["is_default"])) {
			$return["data"]["is_default"] = intval($data["is_default"]);
		}

		if (isset($data["gid"])) {
			$return["data"]["gid"] = strip_tags($data["gid"]);
		}

		if (isset($data["gid"])) {
			$return["data"]["gid"] = strip_tags($data["gid"]);
			$return["data"]["gid"] = preg_replace("/[\n\t\s]{1,}/", "-", trim($return["data"]["gid"]));
			$return["data"]["gid"] = preg_replace("/[^a-z0-9\-_]+/i", "", $return["data"]["gid"]);
			if (empty($return["data"]["gid"])) {
				$return["errors"][] = l('error_group_gid_invalid', 'users');
			}
		}
		return $return;
	}

	function set_default($group_id) {
		$attrs["is_default"] = '0';
		$this->DB->where('is_default', '1');
		$this->DB->update(GROUPS_TABLE, $attrs);

		$attrs["is_default"] = '1';
		$this->DB->where('id', $group_id);
		$this->DB->update(GROUPS_TABLE, $attrs);
		return;
	}

	function _get_group_string_data($group_id) {
		$data = array();
		foreach ($this->CI->pg_language->languages as $lang_id => $lang_data) {
			$data[$lang_id] = $this->CI->pg_language->get_string("groups_langs", "group_item_" . $group_id, $lang_id);
		}
		return $data;
	}

	function _get_group_current_name($group_id) {
		return $this->CI->pg_language->get_string("groups_langs", "group_item_" . $group_id);
	}

	public function update_langs($group_gids, $langs_file, $langs_ids) {
		foreach ($group_gids as $key => $value) {
			$group = $this->get_group_by_gid($value);
			$this->CI->pg_language->pages->set_string_langs('groups_langs', 'group_item_' . $group['id'], $langs_file['groups_demo_' . $value], (array) $langs_ids);
		}
	}

	public function export_langs($group_gids, $langs_ids = null) {
		$gids = array();
		foreach ($group_gids as $key => $value) {
			$group = $this->get_group_by_gid($value);
			$gids[] = 'groups_demo_' . $group['id'];
		}
		return $this->CI->pg_language->export_langs('groups_langs', $gids, $langs_ids);
	}

}
