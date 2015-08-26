<?php
/**
* Notifications main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('NF_NOTIFICATIONS_TABLE'))
	define('NF_NOTIFICATIONS_TABLE', DB_PREFIX . 'notifications');

class Notifications_model extends Model {

	private $CI;
	private $DB;
	private $attrs = array('id', 'gid', 'send_type', 'id_template_default', 'date_add', 'date_update');

	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_notification_by_gid($gid) {
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(NF_NOTIFICATIONS_TABLE)->where("gid", $gid)->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	public function get_notification_by_id($id) {
		$data = array();
		$result = $this->DB->select(implode(", ", $this->attrs))->from(NF_NOTIFICATIONS_TABLE)->where("id", $id)->get()->result_array();
		if (!empty($result)) {
			$data = $result[0];
		}
		return $data;
	}

	public function get_notifications_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null) {
		$this->DB->select(implode(", ", $this->attrs))->from(NF_NOTIFICATIONS_TABLE);

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
				$data[] = $this->format_notification($r);
			}
		}
		return $data;
	}

	public function get_notifications_count($params = array(), $filter_object_ids = null) {
		$this->DB->select('COUNT(*) AS cnt')->from(NF_NOTIFICATIONS_TABLE);

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

	public function format_notification($data, $get_langs = false) {
		$data["name_i"] = "notification_" . $data["id"];
		$data["name"] = ($get_langs) ? (l($data["name_i"], 'notifications')) : "";
		return $data;
	}

	public function save_notification($id, $data, $langs = null) {
		if (is_null($id)) {
			$data["date_add"] = $data["date_update"] = date("Y-m-d H:i:s");
			$this->DB->insert(NF_NOTIFICATIONS_TABLE, $data);
			$id = $this->DB->insert_id();
		} else {
			$data["date_update"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $id);
			$this->DB->update(NF_NOTIFICATIONS_TABLE, $data);
		}

		if (isset($data["gid"]) && isset($langs)) {
			$languages = $this->CI->pg_language->languages;
			$lang_ids = array_keys($languages);
			$this->CI->pg_language->pages->set_string_langs('notifications', "notification_" . $id, $langs, $lang_ids);
		}
		return $id;
	}

	public function validate_notification($id, $data, $langs = null) {
		$return = array("errors" => array(), "data" => array(), 'langs' => array());

		if (isset($data["gid"])) {
			$return["data"]["gid"] = strtolower(preg_replace('/[\s\n\r_]{1,}/', '_', trim(preg_replace('/[^a-z_0-9]/i', '_', strip_tags($data["gid"])))));
			if (empty($return["data"]["gid"])) {
				$return["errors"][] = l('error_gid_empty', 'notifications');
			}
		}

		if (isset($data["send_type"])) {
			$return["data"]["send_type"] = strip_tags($data["send_type"]);
		}

		if (isset($data["id_template_default"])) {
			$return["data"]["id_template_default"] = intval($data["id_template_default"]);
		}

		if (!empty($langs)) {
			$return["langs"] = $langs;
			foreach($langs as $lang_id => $name) {
				if(empty($name)) {
					$return["errors"][] = l('error_name_mandatory_field', 'notifications') . ': ' . $this->pg_language->languages[$lang_id]['name'];
				}
			}
		}

		return $return;
	}

	public function delete_notification($id) {
		$this->DB->where("id", $id);
		$this->DB->delete(NF_NOTIFICATIONS_TABLE);
		return;
	}

	public function delete_notification_by_gid($gid) {
		$this->DB->where("gid", $gid);
		$this->DB->delete(NF_NOTIFICATIONS_TABLE);
		return;
	}

	function send_notification($email, $gid, $data = array(), $gid_template = '', $id_lang = '') {
		$notification_data = $this->get_notification_by_gid($gid);
		if(empty($notification_data))return false;
		$this->CI->load->model('notifications/models/Templates_model');
		if (!$gid_template) {
			$template_data = $this->CI->Templates_model->get_template_by_id($notification_data["id_template_default"]);
			$gid_template = $template_data["gid"];
		} else {
			$template_data = $this->CI->Templates_model->get_template_by_gid($gid_template);
		}

		$return["content"] = $content = $this->CI->Templates_model->compile_template($gid_template, $data, $id_lang);

		$this->CI->load->model('notifications/models/Sender_model');
		if ($notification_data["send_type"] == 'que') {
			$this->CI->Sender_model->push($email, $content["subject"], $content["content"], $template_data["content_type"]);
		} else {
			$errors = $this->CI->Sender_model->send_letter($email, $content["subject"], $content["content"], $template_data["content_type"]);
			if ($errors !== true) {
				$return["errors"] = $errors;
			}
		}
		return $return;
	}

	function get_notification_content($gid, $data, $gid_template = '', $id_lang = '') {
		$notification_data = $this->get_notification_by_gid($gid);

		$this->CI->load->model('notifications/models/Templates_model');
		if (!$gid_template) {
			$template_data = $this->CI->Templates_model->get_template_by_id($notification_data["id_template_default"]);
			$gid_template = $template_data["gid"];
		}

		$content = $this->CI->Templates_model->compile_template($gid_template, $data, $id_lang);
		return $content;
	}

	public function update_langs($data, $langs_file, $lang_ids) {
		$tpl_lang_ids = $lang_ids = (array)$lang_ids;

		if(!empty($data['templates'])) {
			// Save templates langs
			$this->CI->load->model('notifications/models/Templates_model');
			$tpl_lang_ids[] = $default_lang = $this->CI->pg_language->get_default_lang_id();

			foreach($data['templates'] as $tpl){
				
				$template = $this->CI->Templates_model->get_template_by_gid($tpl['gid']);
				$template_content = $this->CI->Templates_model->get_template_content($template['id'], $tpl_lang_ids);
				
				$template_lang_data = array();
				
				$subject_gid = 'tpl_' . $tpl['gid'] . '_subject';
				$content_gid = 'tpl_' . $tpl['gid'] . '_content';
				
				$default_subject = (string)$langs_file[$subject_gid][$default_lang];
				$default_content = (string)$langs_file[$content_gid][$default_lang];
				
				if(!$default_subject){
					$default_subject = $template_content[$default_lang]['subject'];
				}

				if(!$default_content){
					$default_content = $template_content[$default_lang]['content'];
				}
				
				foreach($lang_ids as $id_lang){
					
					$subject = (string)$langs_file[$subject_gid][$id_lang];
					$content = (string)$langs_file[$content_gid][$id_lang];
					
					$template_content[$id_lang]["subject"] = ($subject)?$subject:$default_subject;
					$template_content[$id_lang]["content"] = ($content)?$content:$default_content;

				}
				$this->CI->Templates_model->set_template_content($template['id'], $template_content);
			}
		}
		if(!empty($data['notifications'])) {
			// Save notifications langs
			foreach($data['notifications'] as $notification) {
				$n = $this->get_notification_by_gid($notification['gid']);
				$this->CI->pg_language->pages->set_string_langs('notifications', 'notification_' . $n['id'], $langs_file['notification_' . $notification['gid']], $lang_ids);
			}
		}
		return true;
	}

	public function export_langs($data, $langs_ids = array()) {
		$langs = array();
		$this->CI->load->model('notifications/models/Templates_model');
		foreach($data['templates'] as $tpl){
			$tpl = $this->CI->Templates_model->get_template_by_gid($tpl['gid']);
			$content = $this->CI->Templates_model->get_template_content($tpl['id'], $langs_ids);
			foreach($langs_ids as $lang_id) {
				$langs['tpl_' . $tpl['gid'] . '_subject'][$lang_id] = $content[$lang_id]['subject'];
				$langs['tpl_' . $tpl['gid'] . '_content'][$lang_id] = $content[$lang_id]['content'];
			}
		}
		$this->CI->load->model('Notifications_model');
		$gids = array();
		if(!empty($data['notifications']) && is_array($data['notifications'])){
			foreach($data['notifications'] as $notification){
				$n_id = $this->get_notification_by_gid($notification['gid']);
				$gids[] = 'notification_' . $n_id['id'];
			}
		}
		return array_merge($langs, $this->CI->pg_language->export_langs('notifications', $gids, $langs_ids));
	}

}
