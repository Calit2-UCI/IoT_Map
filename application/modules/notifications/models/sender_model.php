<?php
/**
* Notifications sender model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('NF_SENDER_TABLE'))
	define('NF_SENDER_TABLE', DB_PREFIX . 'notifications_sender');

class Sender_model extends Model {

	private $CI;
	private $DB;
	public $max_send_counter = 3;
	public $send_timeout = 1;
	private $attrs = array('id', 'email', 'subject', 'message', 'content_type', 'send_counter');

	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function send_letter($email, $subject, $message, $mail_type = "txt") {
		$this->CI->load->library('email');
		$this->CI->email->clear();

		$mailconfig = array(
			'charset' => $this->CI->pg_module->get_module_config('notifications', 'mail_charset'),
			'protocol' => $this->CI->pg_module->get_module_config('notifications', 'mail_protocol'),
			'mailpath' => $this->CI->pg_module->get_module_config('notifications', 'mail_mailpath'),
			'smtp_host' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_host'),
			'smtp_user' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_user'),
			'smtp_pass' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_pass'),
			'smtp_port' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_port'),
			'useragent' => $this->CI->pg_module->get_module_config('notifications', 'mail_useragent'),
			'dkim_private_key' => $this->CI->pg_module->get_module_config('notifications', 'dkim_private_key'),
			'dkim_domain_selector' => $this->CI->pg_module->get_module_config('notifications', 'dkim_domain_selector'),
			'mailtype' => $mail_type
		);
		$this->CI->email->initialize($mailconfig);

		$from_email = $this->CI->pg_module->get_module_config('notifications', 'mail_from_email');
		$from_name = $this->CI->pg_module->get_module_config('notifications', 'mail_from_name');
		$this->CI->email->from($from_email, $from_name);
		$this->CI->email->to($email);

		$this->CI->email->subject($subject);
		$this->CI->email->message($message);

		$result = $this->CI->email->send();
		if ($result === true) {
			return true;
		} else {
			return $this->CI->email->_debug_msg;
		}
	}

	public function push($email, $subject, $message, $content_type = "text") {
		$data = array(
			"email" => $email,
			"subject" => $subject,
			"message" => $message,
			"content_type" => $content_type,
			"send_counter" => 0,
		);
		$this->DB->insert(NF_SENDER_TABLE, $data);
	}

	public function get($count = 10) {
		$this->DB->select('id, email, subject, message, content_type, send_counter')->from(NF_SENDER_TABLE)->order_by('id')->limit($count, 0);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results;
		}
		return array();
	}

	public function update_counter($id, $counter) {
		$data = array(
			"send_counter" => $counter,
		);
		$this->DB->where('id', $id);
		$this->DB->update(NF_SENDER_TABLE, $data);
	}

	public function delete($id) {
		if (is_array($id)) {
			$this->DB->where_in('id', $id);
		} else {
			$this->DB->where('id', $id);
		}
		$this->DB->delete(NF_SENDER_TABLE);
	}

	public function sending_session($count = 10) {
		$res = array("sent" => 0, "errors" => 0);

		$letters = $this->get($count);
		if (empty($letters))
			return $res;

		foreach ($letters as $letter) {
			$return = $this->send_letter($letter["email"], $letter["subject"], $letter["message"], $letter["content_type"]);
			if ($return === true || $letter["send_counter"] + 1 >= $this->max_send_counter) {
				$this->delete($letter["id"]);
			} else {
				$this->update_counter($letter["id"], $letter["send_counter"] + 1);
			}

			if ($return === true) {
				$res["sent"]++;
			} else {
				$res["errors"]++;
			}
		}
		return $res;
	}

	public function validate_mail_config($data) {
		$return = array("data" => array(), "errors" => array());

		if (isset($data["mail_charset"])) {
			$return["data"]["mail_charset"] = strip_tags($data["mail_charset"]);
			if (empty($return["data"]["mail_charset"])) {
				$return["errors"][] = l('error_charset_incorrect', 'notifications');
			}
		}

		if (isset($data["mail_protocol"])) {
			$return["data"]["mail_protocol"] = strip_tags($data["mail_protocol"]);
			if (empty($return["data"]["mail_protocol"]) || !in_array($return["data"]["mail_protocol"], array('mail', 'sendmail', 'smtp'))) {
				$return["errors"][] = l('error_protocol_incorrect', 'notifications');
			}
		}

		if (isset($data["mail_mailpath"])) {
			$return["data"]["mail_mailpath"] = strip_tags($data["mail_mailpath"]);
		}

		if (isset($data["mail_smtp_host"])) {
			$return["data"]["mail_smtp_host"] = strip_tags($data["mail_smtp_host"]);
		}

		if (isset($data["mail_smtp_user"])) {
			$return["data"]["mail_smtp_user"] = strip_tags($data["mail_smtp_user"]);
		}

		if (isset($data["mail_smtp_pass"])) {
			$return["data"]["mail_smtp_pass"] = strip_tags($data["mail_smtp_pass"]);
		}

		if (isset($data["mail_smtp_port"])) {
			$return["data"]["mail_smtp_port"] = strip_tags($data["mail_smtp_port"]);
		}

		if (isset($data["mail_useragent"])) {
			$return["data"]["mail_useragent"] = strip_tags($data["mail_useragent"]);
			if (empty($return["data"]["mail_useragent"])) {
				$return["errors"][] = l('error_useragent_incorrect', 'notifications');
			}
		}

		if (isset($data["mail_from_email"])) {
			$return["data"]["mail_from_email"] = strip_tags($data["mail_from_email"]);
			if (empty($data["mail_from_email"])) {
				$return["errors"][] = l('error_from_email_incorrect', 'notifications');
			}
		}

		if (isset($data["mail_from_name"])) {
			$return["data"]["mail_from_name"] = strip_tags($data["mail_from_name"]);
			if (empty($data["mail_from_name"])) {
				$return["errors"][] = l('error_from_name_incorrect', 'notifications');
			}
		}
		
		if (isset($data["dkim_private_key"])) {
			$return["data"]["dkim_private_key"] = strip_tags($data["dkim_private_key"]);
		}

		if (isset($data["dkim_domain_selector"])) {
			$return["data"]["dkim_domain_selector"] = strip_tags($data["dkim_domain_selector"]);
		}

		return $return;
	}

	public function validate_test($data) {
		$return = array("data" => array(), "errors" => array());

		if (isset($data["mail_to_email"])) {
			$return["data"]["mail_to_email"] = strip_tags($data["mail_to_email"]);
			if (empty($data["mail_to_email"]) || !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
               $data["mail_to_email"])) {
				$return["errors"][] = l('error_to_email_incorrect', 'notifications');
			}
		}

		return $return;
	}

	public function cron_que_sender() {
		$data = $this->sending_session(60);
		echo "Letters sent: " . $data["sent"] . "; (" . $data["errors"] . " errors)";
	}

	public function get_senders_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null) {
		$this->DB->select(implode(", ", $this->attrs))->from(NF_SENDER_TABLE);

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
				$data[] = $this->format_senders($r);
			}
		}
		return $data;
	}

	public function get_senders_count($params = array(), $filter_object_ids = null) {
		$this->DB->select('COUNT(*) AS cnt')->from(NF_SENDER_TABLE);

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

	public function format_senders($data, $get_langs = false) {
		$data["name_i"] = "sender_" . $data["id"];
		return $data;
	}

	public function send($id = 0) {
		$res = array("sent" => 0, "errors" => 0);

		if (is_array($id)) {
			$this->DB->where_in('id', $id);
		} else {
			$this->DB->where('id', $id);
		}
		$this->DB->select('id, email, subject, message, content_type, send_counter')->from(NF_SENDER_TABLE)->order_by('id');
		$letters = $this->DB->get()->result_array();

		if (empty($letters))
			return $res;

		foreach ($letters as $letter) {
			$return = $this->send_letter($letter["email"], $letter["subject"], $letter["message"], $letter["content_type"]);
			if ($return === true || $letter["send_counter"] + 1 >= $this->max_send_counter) {
				$this->delete($letter["id"]);
			} else {
				$this->update_counter($letter["id"], $letter["send_counter"] + 1);
			}

			if ($return === true) {
				$res["sent"]++;
			} else {
				$res["errors"]++;
			}
		}
		return $res;
	}

}

