<?php
/**
* Notifications admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Notifications extends Controller {

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	private $allow_template_var_edit = false;
	private $allow_template_edit = true;
	private $allow_notification_edit = true;
	private $allow_pool_send = true;
	private $allow_pool_delete = true;

	/**
	 * Constructor
	 */
	function __construct() {
		parent::Controller();
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}

	public function index($order = "date_add", $order_direction = "DESC", $page = 1) {
		$this->load->model('Notifications_model');
		$attrs = array();
		$current_settings = isset($_SESSION["nf_list"]) ? $_SESSION["nf_list"] : array();
		if (!isset($current_settings["order"]))
			$current_settings["order"] = "date_add";
		if (!isset($current_settings["order_direction"]))
			$current_settings["order_direction"] = "DESC";
		if (!isset($current_settings["page"]))
			$current_settings["page"] = 1;

		$current_settings["page"] = $page;

		if (!$order)
			$order = $current_settings["order"];
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;

		if (!$order_direction)
			$order_direction = $current_settings["order_direction"];
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;

		$notif_count = $this->Notifications_model->get_notifications_count();

		if (!$page)
			$page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $notif_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["nf_list"] = $current_settings;

		$sort_links = array(
			"gid" => site_url() . "admin/notifications/index/gid/" . (($order != 'gid' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"date_add" => site_url() . "admin/notifications/index/date_add/" . (($order != 'date_add' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
		);

		$this->template_lite->assign('sort_links', $sort_links);

		if ($notif_count > 0) {
			$notifications = $this->Notifications_model->get_notifications_list($page, $items_on_page, array($order => $order_direction), $attrs);
			$this->template_lite->assign('notifications', $notifications);
		}
		$this->load->helper("navigation");
		$url = site_url() . "admin/notifications/index/" . $order . "/" . $order_direction . "/";
		$page_data = get_admin_pages_data($url, $notif_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->template_lite->assign('allow_edit', $this->allow_notification_edit);

		$this->system_messages->set_data('header', l('admin_header_notifications_list', 'notifications'));
		$this->template_lite->view('list');
	}

	public function edit($id = null) {
		$this->load->model('Notifications_model');
		$data = ($id) ? $this->Notifications_model->get_notification_by_id($id) : array();

		if ($this->input->post('btn_save')) {
			if ($this->allow_notification_edit) {
				$post_data = array(
					"gid" => $this->input->post('gid', true),
					"send_type" => $this->input->post('send_type', true),
					"id_template_default" => $this->input->post('id_template_default', true),
				);
			} else {
				$post_data = array(
					"id_template_default" => $this->input->post('id_template_default', true),
				);
			}

			$langs_data = $this->input->post('langs', true);

			$validate_data = $this->Notifications_model->validate_notification($id, $post_data, $langs_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
			} else {
				$this->Notifications_model->save_notification($id, $validate_data["data"], $validate_data["langs"]);
				$this->system_messages->add_message('success', ($id) ? l('success_update_notification', 'notifications') : l('success_add_notification', 'notifications'));
				$current_settings = $_SESSION["nf_list"];
				$url = site_url() . "admin/notifications/index/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
				redirect($url);
			}
			$data = array_merge($data, $validate_data["data"]);
			$this->template_lite->assign('validate_lang', $validate_data["langs"]);
		}

		if (!empty($data))
			$data = $this->Notifications_model->format_notification($data, true);
		$this->template_lite->assign('data', $data);

		///// Templates
		$this->load->model('notifications/models/Templates_model');
		$this->template_lite->assign('templates', $this->Templates_model->get_templates_list());

		///// languages
		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);

		$this->template_lite->assign('allow_edit', $this->allow_notification_edit);

		$this->system_messages->set_data('header', l('admin_header_notification_edit', 'notifications'));
		$this->template_lite->view('edit_form');
	}

	public function delete($id) {
		if (!empty($id)) {
			$this->load->model('Notifications_model');
			$this->Notifications_model->delete_notification($id);
		}
		$current_settings = $_SESSION["nf_list"];
		$url = site_url() . "admin/notifications/index/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
		redirect($url);
	}

	public function settings() {
		$this->load->model('notifications/models/Sender_model');

		$data = array(
			'mail_charset' => $this->pg_module->get_module_config('notifications', 'mail_charset'),
			'mail_protocol' => $this->pg_module->get_module_config('notifications', 'mail_protocol'),
			'mail_mailpath' => $this->pg_module->get_module_config('notifications', 'mail_mailpath'),
			'mail_smtp_host' => $this->pg_module->get_module_config('notifications', 'mail_smtp_host'),
			'mail_smtp_user' => $this->pg_module->get_module_config('notifications', 'mail_smtp_user'),
			'mail_smtp_pass' => $this->pg_module->get_module_config('notifications', 'mail_smtp_pass'),
			'mail_smtp_port' => $this->pg_module->get_module_config('notifications', 'mail_smtp_port'),
			'mail_useragent' => $this->pg_module->get_module_config('notifications', 'mail_useragent'),
			'mail_from_email' => $this->pg_module->get_module_config('notifications', 'mail_from_email'),
			'mail_from_name' => $this->pg_module->get_module_config('notifications', 'mail_from_name'),
		);
		
		// Check if openssl extension is loaded. It is required for DKIM.
		$openssl_loaded = extension_loaded('openssl');
		if($openssl_loaded){
			$data['dkim_private_key'] = $this->pg_module->get_module_config('notifications', 'dkim_private_key');
			$data['dkim_domain_selector'] = $this->pg_module->get_module_config('notifications', 'dkim_domain_selector');
			$this->template_lite->assign('openssl_loaded', true);
		}
		
		if ($this->input->post('btn_save')) {
			$post_data = array(
				'mail_charset' => $this->input->post('mail_charset', true),
				'mail_protocol' => $this->input->post('mail_protocol', true),
				'mail_mailpath' => $this->input->post('mail_mailpath', true),
				'mail_smtp_host' => $this->input->post('mail_smtp_host', true),
				'mail_smtp_user' => $this->input->post('mail_smtp_user', true),
				'mail_smtp_pass' => $this->input->post('mail_smtp_pass', true),
				'mail_smtp_port' => $this->input->post('mail_smtp_port', true),
				'mail_useragent' => $this->input->post('mail_useragent', true),
				'mail_from_email' => $this->input->post('mail_from_email', true),
				'mail_from_name' => $this->input->post('mail_from_name', true),
			);
		
			$validate_data = $this->Sender_model->validate_mail_config($post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data['mail_charset'] = $post_data["data"]['mail_charset'];
				$data['mail_protocol'] = $post_data["data"]['mail_protocol'];
				$data['mail_mailpath'] = $post_data["data"]['mail_mailpath'];
				$data['mail_smtp_host'] = $post_data["data"]['mail_smtp_host'];
				$data['mail_smtp_user'] = $post_data["data"]['mail_smtp_user'];
				$data['mail_smtp_pass'] = $post_data["data"]['mail_smtp_pass'];
				$data['mail_smtp_port'] = $post_data["data"]['mail_smtp_port'];
				$data['mail_useragent'] = $post_data["data"]['mail_useragent'];
				$data['mail_from_email'] = $post_data["data"]['mail_from_email'];
				$data['mail_from_name'] = $post_data["data"]['mail_from_name'];
			} else {
				foreach ($validate_data["data"] as $setting => $value) {
					$this->pg_module->set_module_config('notifications', $setting, $value);
				}
				$this->system_messages->add_message('success', l('success_settings_saved', 'notifications'));
				$data['mail_charset'] = $validate_data["data"]['mail_charset'];
				$data['mail_protocol'] = $validate_data["data"]['mail_protocol'];
				$data['mail_mailpath'] = $validate_data["data"]['mail_mailpath'];
				$data['mail_smtp_host'] = $validate_data["data"]['mail_smtp_host'];
				$data['mail_smtp_user'] = $validate_data["data"]['mail_smtp_user'];
				$data['mail_smtp_pass'] = $validate_data["data"]['mail_smtp_pass'];
				$data['mail_smtp_port'] = $validate_data["data"]['mail_smtp_port'];
				$data['mail_useragent'] = $validate_data["data"]['mail_useragent'];
				$data['mail_from_email'] = $validate_data["data"]['mail_from_email'];
				$data['mail_from_name'] = $validate_data["data"]['mail_from_name'];
			}
		}
		
		if ($this->input->post('btn_dkim')) {
			$post_data = array(
				'dkim_private_key' => $this->input->post('dkim_private_key', true),
				'dkim_domain_selector' => $this->input->post('dkim_domain_selector', true),
			);
			$validate_data = $this->Sender_model->validate_mail_config($post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data['dkim_private_key'] = $post_data["data"]['dkim_private_key'];
				$data['dkim_domain_selector'] = $post_data["data"]['dkim_domain_selector'];
			} else {
				foreach ($validate_data["data"] as $setting => $value) {
					$this->pg_module->set_module_config('notifications', $setting, $value);
				}
				$this->system_messages->add_message('success', l('success_settings_saved', 'notifications'));
				$data['dkim_private_key'] = $validate_data["data"]['dkim_private_key'];
				$data['dkim_domain_selector'] = $validate_data["data"]['dkim_domain_selector'];
			}
		}

		if ($this->input->post('btn_test')) {
			$post_data = array(
				'mail_to_email' => $this->input->post('mail_to_email', true),
			);
			$validate_data = $this->Sender_model->validate_test($post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data["mail_to_email"] = $validate_data["data"]["mail_to_email"];
			} else {
				$result = $this->Sender_model->send_letter($validate_data["data"]["mail_to_email"], 'TEST ALERT', 'TEST ALERT', 'text');
				if ($result === true) {
					$this->system_messages->add_message('success', l('success_send_test', 'notifications'));
				} else {
					$this->system_messages->add_message('error', implode("\n", $result));
				}
			}
		}

		$this->template_lite->assign('protocol_lang', ld('protocol', 'notifications'));
		$this->template_lite->assign('settings_data', $data);

		$this->Menu_model->set_menu_active_item('admin_notifications_menu', 'nf_settings_item');
		$this->system_messages->set_data('header', l('admin_header_settings_edit', 'notifications'));
		$this->template_lite->view('settings');
	}

	public function templates($filter = "all", $order = "date_add", $order_direction = "DESC", $page = 1) {
		$this->load->model('notifications/models/Templates_model');

		$attrs = array();
		$current_settings = isset($_SESSION["nf_templates_list"]) ? $_SESSION["nf_templates_list"] : array();
		if (!isset($current_settings["filter"]))
			$current_settings["filter"] = "all";
		if (!isset($current_settings["order"]))
			$current_settings["order"] = "date_add";
		if (!isset($current_settings["order_direction"]))
			$current_settings["order_direction"] = "DESC";
		if (!isset($current_settings["page"]))
			$current_settings["page"] = 1;

		$filter_data = array(
			"all" => $this->Templates_model->get_templates_count(),
			"text" => $this->Templates_model->get_templates_count(array("where" => array("content_type" => "text"))),
			"html" => $this->Templates_model->get_templates_count(array("where" => array("content_type" => "html"))),
		);

		switch ($filter) {
			case 'text' : $attrs["where"]['content_type'] = "text";
				break;
			case 'html' : $attrs["where"]['content_type'] = "html";
				break;
			default: $filter = $current_settings["filter"];
		}

		$this->template_lite->assign('filter', $filter);
		$this->template_lite->assign('filter_data', $filter_data);
		$current_settings["page"] = $page;

		if (!$order)
			$order = $current_settings["order"];
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;

		if (!$order_direction)
			$order_direction = $current_settings["order_direction"];
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;

		$templates_count = $filter_data[$filter];

		if (!$page)
			$page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $templates_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["nf_templates_list"] = $current_settings;

		$sort_links = array(
			"gid" => site_url() . "admin/notifications/templates/" . $filter . "/gid/" . (($order != 'gid' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"name" => site_url() . "admin/notifications/templates/" . $filter . "/name/" . (($order != 'name' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"date_add" => site_url() . "admin/notifications/templates/" . $filter . "/date_add/" . (($order != 'date_add' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
		);

		$this->template_lite->assign('sort_links', $sort_links);

		if ($templates_count > 0) {
			$templates = $this->Templates_model->get_templates_list($page, $items_on_page, array($order => $order_direction), $attrs);
			$this->template_lite->assign('templates', $templates);
		}
		$this->load->helper("navigation");
		$url = site_url() . "admin/notifications/templates/" . $filter . "/" . $order . "/" . $order_direction . "/";
		$page_data = get_admin_pages_data($url, $templates_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->template_lite->assign('allow_edit', $this->allow_template_edit);

		$this->system_messages->set_data('header', l('admin_header_templates_list', 'notifications'));
		$this->template_lite->view('list_templates');
	}

	public function template_edit($id_template = null) {
		$languages = $this->pg_language->languages;
		$lang_ids = array_keys($languages);

		$this->load->model('notifications/models/Templates_model');
		if ($id_template) {
			$data = $this->Templates_model->get_template_by_id($id_template);
			$data_content = $this->Templates_model->get_template_content($id_template, $lang_ids);
		} else {
			$data = array(
				"content_type" => "text"
			);
			$data_content = array();
		}

		if ($this->input->post('btn_save')) {
			$error = false;
			if ($this->allow_template_edit) {
				$post_data = array(
					"name" => $this->input->post('name', true),
					"gid" => $this->input->post('gid', true),
					"content_type" => $this->input->post('content_type', true),
				);
				if ($this->allow_template_var_edit) {
					$post_data["vars"] = $this->input->post('vars', true);
				}
				$validate_data = $this->Templates_model->validate_template($id_template, $post_data);
				if (!empty($validate_data["errors"])) {
					$this->system_messages->add_message('error', $validate_data["errors"]);
					$data = array_merge($data, $validate_data["data"]);
					$error = true;
				} else {
					$data = $validate_data["data"];
					$id_template_new = $this->Templates_model->save_template($id_template, $data);
					$this->system_messages->add_message('success', ($id_template) ? l('success_update_template', 'notifications') : l('success_add_template', 'notifications'));
					$id_template = $id_template_new;
				}
			}

			if (!$error) {
				//// safe content there
				$post_content_text = $this->input->post('content', true);
				$post_subject = $this->input->post('subject', true);

				foreach ($languages as $lang_id => $lang) {
					$save_content[$lang_id] = array(
						"subject" => isset($post_subject[$lang_id]) ? $post_subject[$lang_id] : "",
						"content" => isset($post_content_text[$lang_id]) ? $post_content_text[$lang_id] : ""
					);
				}

				$this->Templates_model->set_template_content($id_template, $save_content);
				$data_content = array_merge($data_content, $save_content);

				$current_settings = $_SESSION["nf_templates_list"];
				$url = site_url() . "admin/notifications/templates/" . $current_settings["filter"] . "/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
				redirect($url);
			}
		}

		$data = $this->Templates_model->format_template($data);

		if ($data["content_type"] == "html") {
			$this->load->plugin('fckeditor');
			foreach ($languages as $lang_id => $lang) {
				$content = isset($data_content[$lang_id]) ? $data_content[$lang_id] : array();
				$data_content[$lang_id]["content_fck"] = create_editor("content[" . $lang_id . "]", isset($content["content"]) ? $content["content"] : "", 550, 400, 'Middle');
			}
		}


		$this->template_lite->assign('langs', $languages);
		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('data_content', $data_content);
		$this->template_lite->assign('global_vars', $this->Templates_model->global_vars);

		$this->template_lite->assign('allow_edit', $this->allow_template_edit);
		$this->template_lite->assign('allow_var_edit', $this->allow_template_var_edit);

		$this->system_messages->set_data('header', l('admin_header_template_edit', 'notifications'));
		$this->template_lite->view('edit_template_form');
	}

	public function template_delete($id_template) {
		if (!empty($id_template)) {
			$this->load->model('notifications/models/Templates_model');
			$this->Templates_model->delete_template($id_template);
		}
		$current_settings = $_SESSION["nf_templates_list"];
		$url = site_url() . "admin/notifications/templates/" . $current_settings["filter"] . "/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
		redirect($url);
	}

	private function _format_pool_table($order = "id", $order_direction = "DESC", $page = 1) {
		$this->load->model('notifications/models/Sender_model');

		$attrs = array();
		$current_settings = isset($_SESSION["senders_list"]) ? $_SESSION["senders_list"] : array();
		if (!isset($current_settings["order"]))
			$current_settings["order"] = "id";
		if (!isset($current_settings["order_direction"]))
			$current_settings["order_direction"] = "DESC";
		if (!isset($current_settings["page"]))
			$current_settings["page"] = 1;

		$current_settings["page"] = $page;

		if (!$order)
			$order = $current_settings["order"];
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;

		if (!$order_direction)
			$order_direction = $current_settings["order_direction"];
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;

		$notif_count = $this->Sender_model->get_senders_count();

		if (!$page)
			$page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $notif_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["senders_list"] = $current_settings;

		$sort_links = array(
			"email" => site_url() . "admin/notifications/pool/email/" . (($order != 'email' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"subject" => site_url() . "admin/notifications/pool/subject/" . (($order != 'subject' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
			"send_counter" => site_url() . "admin/notifications/pool/send_counter/" . (($order != 'send_counter' xor $order_direction == 'DESC') ? 'ASC' : 'DESC'),
		);

		$this->template_lite->assign('sort_links', $sort_links);

		if ($notif_count > 0) {
			$senders = $this->Sender_model->get_senders_list($page, $items_on_page, array($order => $order_direction), $attrs);
			$this->template_lite->assign('senders', $senders);
		}
		$this->load->helper("navigation");
		$url = site_url() . "admin/notifications/pool/" . $order . "/" . $order_direction . "/";
		$page_data = get_admin_pages_data($url, $notif_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->template_lite->assign('allow_pool_send', $this->allow_pool_send);
		$this->template_lite->assign('allow_pool_delete', $this->allow_pool_delete);
		$this->template_lite->assign('sort_links', $sort_links);
	}

	public function pool($order = "id", $order_direction = "DESC", $page = 1) {
		$this->_format_pool_table($order, $order_direction, $page);
		$this->template_lite->assign('ajax_pool_url', site_url('admin/notifications/ajax_pool_data') . '/' . $order . '/' . $order_direction . '/' . $page);
		$this->system_messages->set_data('header', l('admin_header_notifications_list', 'notifications'));
		$this->template_lite->view('pool');
	}

	public function ajax_pool_data($order = "id", $order_direction = "DESC", $page = 1) {
		$this->_format_pool_table($order, $order_direction, $page);
		$this->template_lite->assign('ajax_pool_url', site_url('admin/notifications/ajax_pool_data') . '/');
		$this->template_lite->view('pool_table');
	}

	public function pool_delete($id) {
		if (!empty($id) && $this->allow_pool_delete) {
			$ids = explode(',', $id);
			foreach ($ids as $arr_id => $arr_value)
				if (!($arr_value > 0))
					unset($ids[$arr_id]);
			if (count($ids) > 0) {
				$this->load->model('notifications/models/Sender_model');
				$this->Sender_model->delete($ids);
				$this->system_messages->add_message('success', l(count($ids) > 1 ? 'success_delete_pools' : 'success_delete_pool', 'notifications'));
			}
		}
		$current_settings = $_SESSION["senders_list"];
		$url = site_url() . "admin/notifications/pool/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
		redirect($url);
	}

	public function pool_send($id) {
		if (!empty($id) && $this->allow_pool_send) {
			$ids = explode(',', $id);
			foreach ($ids as $arr_id => $arr_value)
				if (!($arr_value > 0))
					unset($ids[$arr_id]);
			if (count($ids) > 0) {
				$this->load->model('notifications/models/Sender_model');
				$result = $this->Sender_model->send($ids);
				if ($result['error'] == 0 || $result['sent'] == 0) {
					$this->system_messages->add_message($result['sent'] > 0 ? 'success' : 'error', l($result['sent'] > 0 ? ($result['sent'] > 1 ? 'success_send_pools' : 'success_send_pool')  : ($result['error'] > 1 ? 'error_send_pools' : 'error_send_pool'), 'notifications'));
				} else {
					$this->system_messages->add_message('info', sprintf(l('success_error_send_pools', 'notifications')), $result['sent'], $result['error']);
				}
			}
		}
		$current_settings = $_SESSION["senders_list"];
		$url = site_url() . "admin/notifications/pool/" . $current_settings["order"] . "/" . $current_settings["order_direction"] . "/" . $current_settings["page"] . "";
		redirect($url);
	}

}
