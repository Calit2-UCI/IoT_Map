<?php
/**
* Social networking pages model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('SOCIAL_NETWORKING_PAGES_TABLE'))
	define('SOCIAL_NETWORKING_PAGES_TABLE', DB_PREFIX . 'social_networking_pages');

class Social_networking_pages_model extends Model {

	var $CI;
	var $DB;
	var $fields_all = array(
		'id',
		'controller',
		'method',
		'name',
		'data'
	);
	var $enabled = true;
	var $temp_pages_list = array();

	function Social_networking_pages_model() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;

		$this->DB->memcache_tables(array(SOCIAL_NETWORKING_PAGES_TABLE));
	}

	function get_pages_list($order_by = null, $params = array(), $filter_object_ids = null) {
		$data = array();
		$select_attrs = $this->fields_all;

		$this->DB->select(implode(", ", $select_attrs))->from(SOCIAL_NETWORKING_PAGES_TABLE);

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

		$results = $this->DB->get()->result_array();

		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[$r['id']] = $this->format_page($r);
			}
		}

		return $data;
	}

	function get_page_by_id($page_id = false) {
		$data = array();
		$select_attrs = $this->fields_all;
		$result = $this->DB->select(implode(", ", $select_attrs))->from(SOCIAL_NETWORKING_PAGES_TABLE)->where("id", $page_id)->get()->result_array();
		if (!empty($result)) {
			$data = $this->format_page($result[0]);
		}
		return $data;
	}

	function format_page($data = array()) {
		$data['data'] = @unserialize($data['data']);
		return $data;
	}

	function validate_page($page_id = false, $data = array()) {
		$return = array("errors" => array(), "data" => array());

		if (isset($data["controller"])) {
			$return["data"]["controller"] = strip_tags($data["controller"]);
		}

		if (isset($data["method"])) {
			$return["data"]["method"] = strip_tags($data["method"]);
		}

		if (isset($data["name"])) {
			$return["data"]["name"] = strip_tags($data["name"]);
		}

		if (isset($data["data"])) {
			$return["data"]["data"] = @serialize($data["data"]);
		}

		return $return;
	}

	function save_page($page_id = false, $data = array()) {
		$data = (array) $data;
		if (is_null($page_id)) {
			$this->DB->insert(SOCIAL_NETWORKING_PAGES_TABLE, $data);
			$page_id = $this->DB->insert_id();
		} else {
			$this->DB->where('id', $page_id);
			$this->DB->update(SOCIAL_NETWORKING_PAGES_TABLE, $data);
		}
		return $page_id;
	}

	function delete_page($page_id = false) {
		$this->DB->where('id', $page_id);
		$this->DB->delete(SOCIAL_NETWORKING_PAGES_TABLE);
		return;
	}

	function delete_pages_by_controller($controller = '') {
		$this->DB->where('controller', $controller);
		$this->DB->delete(SOCIAL_NETWORKING_PAGES_TABLE);
		return;
	}

}
