<?php
/**
* Notifications templates model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('NF_TEMPLATES_TABLE'))
	define('NF_TEMPLATES_TABLE', DB_PREFIX . 'notifications_templates');
if (!defined('NF_TEMPLATES_CONTENT_TABLE'))
	define('NF_TEMPLATES_CONTENT_TABLE', DB_PREFIX . 'notifications_templates_content');

class Templates_model extends Model {

	private $CI;
	private $DB;
	private $attrs = array('id', 'gid', 'name', 'vars', 'content_type', 'date_add', 'date_update');
	public $global_vars = array('site_url', 'domain', 'mail_from', 'name_from', 'current_date', 'current_time');

	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_template_by_gid($gid) {
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(NF_TEMPLATES_TABLE)->where("gid", $gid)->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	public function get_template_by_id($id) {
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(NF_TEMPLATES_TABLE)->where("id", $id)->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	public function save_template($id, $data) {
		if (empty($id)) {
			$data["date_update"] = $data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(NF_TEMPLATES_TABLE, $data);
			$id = $this->DB->insert_id();
		} else {
			$data["date_update"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $id);
			$this->DB->update(NF_TEMPLATES_TABLE, $data);
		}
		return $id;
	}

	public function validate_template($id, $data) {
		$return = array("errors" => array(), "data" => array());

		if (isset($data["name"])) {
			$return["data"]["name"] = strip_tags($data["name"]);
			if (empty($return["data"]["name"])) {
				$return["errors"][] = l('error_name_mandatory_field', 'notifications');
			}
		}

		if (isset($data["gid"])) {
			$return["data"]["gid"] = trim(strip_tags($data["gid"]));
			$return["data"]["gid"] = preg_replace('/[^a-z\-_0-9]+/i', '', $return["data"]["gid"]);
			$return["data"]["gid"] = preg_replace('/[\s\n\t\r]+/', '-', $return["data"]["gid"]);
			$return["data"]["gid"] = preg_replace('/\-{2,}/', '-', $return["data"]["gid"]);

			if (empty($return["data"]["gid"])) {
				$return["errors"][] = l('error_gid_mandatory_field', 'notifications');
			} else {
				$this->DB->select('COUNT(*) AS cnt')->from(NF_TEMPLATES_TABLE)->where("gid", $return["data"]["gid"]);
				if (!empty($id)) {
					$this->DB->where("id <>", $id);
				}
				$result = $this->DB->get()->result_array();
				if (!empty($result) && $result[0]["cnt"] > 0) {
					$return["errors"][] = l('error_template_already_exists', 'notifications');
				}
			}
		}

		if (isset($data["vars"])) {
			$vars = explode(",", $data["vars"]);
			foreach ($vars as $k => $v) {
				$vars[$k] = trim(strip_tags($v));
			}
			$return["data"]["vars"] = serialize($vars);
		}

		if (isset($data["content_type"])) {
			$return["data"]["content_type"] = strip_tags($data["content_type"]);
			if (empty($return["data"]["content_type"])) {
				$return["errors"][] = l('error_content_type_mandatory_field', 'notifications');
			}
		}

		return $return;
	}

	public function delete_template($id) {
		$this->DB->where("id", $id);
		$this->DB->delete(NF_TEMPLATES_TABLE);

		$this->DB->where("id_template", $id);
		$this->DB->delete(NF_TEMPLATES_CONTENT_TABLE);
		return;
	}

	public function delete_template_by_gid($gid) {
		$data = $this->get_template_by_gid($gid);
		if (empty($data))
			return false;

		$this->delete_template($data["id"]);
	}

	public function get_templates_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null) {
		$this->DB->select(implode(", ", $this->attrs))->from(NF_TEMPLATES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value)
				$this->DB->where($field, $value);
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value)
				$this->DB->where_in($field, $value);
		}

		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value)
				$this->DB->where($value);
		}

		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->attrs)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}

		$data = array();
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[] = $this->format_template($r);
			}
		}
		return $data;
	}

	public function get_templates_count($params = array(), $filter_object_ids = null) {
		$this->DB->select('COUNT(*) AS cnt')->from(NF_TEMPLATES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value)
				$this->DB->where($field, $value);
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value)
				$this->DB->where_in($field, $value);
		}

		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value)
				$this->DB->where($value);
		}

		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function format_template($data) {
		$data["vars"] = (!empty($data["vars"])) ? unserialize($data["vars"]) : "";
		if (!empty($data["vars"]))
			$data["vars_str"] = implode(",", $data["vars"]);
		return $data;
	}

	public function get_template_content($id_template, $lang_ids = array()) {
		$default_lang_id = $this->CI->pg_language->get_default_lang_id();
		if (empty($lang_ids) || !in_array($default_lang_id, $lang_ids))
			$lang_ids[] = $default_lang_id;

		$current_lang_id = $this->CI->pg_language->current_lang_id;
		if (empty($lang_ids) || !in_array($current_lang_id, $lang_ids))
			$lang_ids[] = $current_lang_id;

		$this->DB->select('id, id_template, id_lang, subject, content')->from(NF_TEMPLATES_CONTENT_TABLE)->where('id_template', $id_template)->where_in('id_lang', $lang_ids);
		$data = array();
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[$r["id_lang"]] = $r;
			}
		}
		return $data;
	}

	public function set_template_content($id_template, $data) {
		if (empty($data))
			return;

		$lang_ids = array_keys($data);
		if (empty($lang_ids))
			return;

		$saved = $this->get_template_content($id_template, $lang_ids);

		foreach ($data as $id_lang => $content) {
			unset($attrs);
			$attrs["subject"] = $content["subject"];
			$attrs["content"] = $content["content"];

			if (isset($saved[$id_lang])) {
				$this->DB->where('id_template', $id_template);
				$this->DB->where('id_lang', $id_lang);
				$this->DB->update(NF_TEMPLATES_CONTENT_TABLE, $attrs);
			} else {
				$attrs["id_template"] = $id_template;
				$attrs["id_lang"] = $id_lang;
				$this->DB->insert(NF_TEMPLATES_CONTENT_TABLE, $attrs);
			}
		}
		return;
	}

	public function compile_template($gid, $vars, $lang_id = false) {
		$template_data = $this->get_template_by_gid($gid);
		$template_data = $this->format_template($template_data);

		$default_lang_id = $this->CI->pg_language->get_default_lang_id();
		if (!$lang_id)
			$lang_id = $default_lang_id;

		$lang_ids = (!empty($lang_id)) ? array(0 => $lang_id) : array();
		$content_array = $this->get_template_content($template_data["id"], $lang_ids);

		$content_default = $content_array[$default_lang_id];
		$content = (!empty($content_array[$lang_id])) ? $content_array[$lang_id] : $content_array[$default_lang_id];

		if (empty($content["subject"])) {
			$content["subject"] = $content_default["subject"];
		}

		if (empty($content["content"])) {
			$content["content"] = $content_default["content"];
		}

		if (!empty($template_data["vars"])) {
			foreach ($template_data["vars"] as $key) {
				$value = (!empty($vars[$key])) ? $vars[$key] : "";
				$content["subject"] = str_replace("[" . $key . "]", $value, $content["subject"]);
				$content["content"] = str_replace("[" . $key . "]", $value, $content["content"]);
			}
		}

		$global_vars = $this->get_global_vars();
		if (!empty($global_vars)) {
			foreach ($global_vars as $key => $value) {
				$content["subject"] = str_replace("[" . $key . "]", $value, $content["subject"]);
				$content["content"] = str_replace("[" . $key . "]", $value, $content["content"]);
			}
		}
		return $content;
	}

	public function get_global_vars() {
		$global_vars["site_url"] = site_url();
		$url_data = parse_url(site_url());
		$global_vars["domain"] = $url_data["host"];

		$global_vars["mail_from"] = $this->CI->pg_module->get_module_config('notifications', 'mail_from_email');
		$global_vars["name_from"] = $this->CI->pg_module->get_module_config('notifications', 'mail_from_name');

		$global_vars["current_date"] = date($this->pg_date->get_format('date_literal', 'date'));
		$global_vars["current_time"] = date($this->pg_date->get_format('time_literal', 'date'));
		return $global_vars;
	}

	/**
	 * Add templates content for the lang
	 *
	 * @param int $lang_id
	 */
	public function add_templates_content($lang_id) {
		if((int)$lang_id < 1) {
			return false;
		}
		$default_lang = $this->CI->pg_language->get_default_lang_id();
		$default_tpls = $this->DB->select("$lang_id as id_lang, id_template, subject, content")
				->from(NF_TEMPLATES_CONTENT_TABLE)
				->where('id_lang', $default_lang)
				->get()->result_array();
		foreach($default_tpls as $tpl) {
			$this->DB->insert(NF_TEMPLATES_CONTENT_TABLE, $tpl);
		}
		return true;
	}

	/**
	 * Delete templates content for the lang
	 *
	 * @param int $lang_id
	 */
	private function delete_templates_content($lang_id) {
		if((int)$lang_id < 1) {
			return false;
		}
		$this->DB->where('id_lang', $lang_id);
		$this->DB->delete(NF_TEMPLATES_CONTENT_TABLE);
		return true;
	}

	public function lang_dedicate_module_callback_add($lang_id) {
		$this->add_templates_content($lang_id);
	}

	public function lang_dedicate_module_callback_delete($lang_id) {
		$this->delete_templates_content($lang_id);
	}
}