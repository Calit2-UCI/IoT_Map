<?php

/**
 * Mailbox Install Model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
 * */
class Mailbox_install_model extends Model {

	private $CI;
	private $menu = array(
		/* 'admin_menu' => array(
		  'action' => 'none',
		  'items' => array(
		  'main_items' => array(
		  'action' => 'none',
		  'items' => array(
		  'mailbox_menu_item' => array('action' => 'create', 'link' => 'admin/mailbox', 'status' => 1, 'sorter' => 4),
		  ),
		  ),
		  ),
		  ),
		  'admin_mailbox_menu' => array(
		  'action' => 'create',
		  'name' => 'Admin mode - Mailbox',
		  'items' => array(
		  'mailbox_inbox_item' => array('action' => 'create', 'link' => 'admin/mailbox/index/inbox', 'status' => 1, 'sorter' => 1),
		  'mailbox_outbox_item' => array('action' => 'create', 'link' => 'admin/mailbox/index/outbox', 'status' => 1, 'sorter' => 2),
		  ),
		  ), */
		'user_top_menu' => array(
			'action' => 'none',
			'items' => array(
				'user-menu-communication' => array(
					'action' => 'none',
					'items' => array(
						'mailbox_item' => array('action' => 'create', 'link' => 'mailbox/inbox', 'status' => 1, 'sorter' => 10),
						'mailbox_write_item' => array('action' => 'create', 'link' => 'mailbox/write', 'status' => 1, 'sorter' => 20),
					),
				),
			),
		),
	);
	private $moderation_types = array(
		array(
			"name" => "mailbox",
			"mtype" => "-1",
			"module" => "mailbox",
			"model" => "Mailbox_model",
			"check_badwords" => "1",
			"method_get_list" => "",
			"method_set_status" => "",
			"method_delete_object" => "",
			"allow_to_decline" => "0",
			"template_list_row" => "",
		)
	);

	/**
	 * File uploads configuration
	 * @var array
	 */
	private $file_uploads = array(
		array(
			"gid" => "mailbox-attach",
			"name" => "Mailbox attachement",
			"max_size" => 262144,
			"name_format" => "format",
			"file_formats" => array("doc", "docx", "pdf", "ppt", "rtf", "text", "txt", "word", "xls", "xlsx", "csv", "xml", "bmp", "gif", "jpeg", "jpg", "png"),
		),
	);

	/**
	 * Notifications configuration
	 */
	private $notifications = array(
		"templates" => array(
			array("gid" => "mailbox_new_message", "name" => "You have a new message", "vars" => array("fname", "sname", "sender", "subject"), "content_type" => "text"),
		),
		"notifications" => array(
			array("gid" => "mailbox_new_message", "template" => "mailbox_new_message", "send_type" => "simple"),
		),
	);

	/**
	 * Service configuration
	 * @var array
	 */
	private $services = array(
		"templates" => array(
			/* array(
			  "gid" => "access_mailbox_template",
			  "callback_module" => "mailbox",
			  "callback_model" => "Mailbox_model",
			  "callback_buy_method" => "service_buy_access_mailbox",
			  "callback_validate_method" => "service_validate_access_mailbox",
			  "price_type" => 1,
			  "data_admin" => array(),
			  "data_user" => array(),
			  "moveable" => 0,
			  ), */
			array(
				"gid" => "read_message_template",
				"callback_module" => "mailbox",
				"callback_model" => "Mailbox_model",
				"callback_buy_method" => "service_buy_read_message",
				"callback_activate_method" => "service_activate_read_message",
				"callback_validate_method" => "service_validate_read_message",
				"price_type" => 1,
				"data_admin" => array("message_count" => "int"),
				"data_user" => array(),
				"moveable" => 0,
			),
			array(
				"gid" => "send_message_template",
				"callback_module" => "mailbox",
				"callback_model" => "Mailbox_model",
				"callback_buy_method" => "service_buy_send_message",
				"callback_activate_method" => "service_activate_send_message",
				"callback_validate_method" => "service_validate_send_message",
				"price_type" => 1,
				"data_admin" => array("message_count" => "int"),
				"data_user" => array(),
				"moveable" => 0,
			),
		),
		"services" => array(
			/* array(
			  "gid" => "access_mailbox_service",
			  "template_gid" => "access_mailbox_template",
			  "pay_type" => 2,
			  "status" => 1,
			  "price" => 10,
			  "type" => "tariff",
			  "data_admin" => array(),
			  ), */
			array(
				"gid" => "read_message_service",
				"template_gid" => "read_message_template",
				"pay_type" => 2,
				"status" => 1,
				"price" => 10,
				"type" => "tariff",
				"data_admin" => array("message_count" => "10"),
			),
			array(
				"gid" => "send_message_service",
				"template_gid" => "send_message_template",
				"pay_type" => 2,
				"status" => 1,
				"price" => 10,
				"type" => "tariff",
				"data_admin" => array("message_count" => "10"),
			),
		),
	);

	/**
	 * Cronjobs configuration
	 */
	private $cronjobs = array(
		array(
			"name" => "Clear mailbox trash",
			"module" => "mailbox",
			"model" => "Mailbox_model",
			"method" => "mailbox_trash_cron",
			"cron_tab" => "0 * * * *",
			"status" => "1",
		),
	);
	private $_seo_pages = array(
		'write',
		'inbox',
		'outbox',
		'drafts',
		'trash',
		'spam',
		'index',
	);
	protected $network_event_handlers = array(
		array(
			'event' => 'mail',
			'module' => 'mailbox',
			'model' => 'Mailbox_model',
			'method' => 'handler_mail',
		),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach ($this->menu as $gid => $menu_data) {
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if (empty($langs_ids))
			return false;
		$langs_file = $this->CI->Install_model->language_file_read('mailbox', 'menu', $langs_ids);

		if (!$langs_file) {
			log_message('info', 'Empty menu langs data');
			return false;
		}

		$this->CI->load->helper('menu');

		foreach ($this->menu as $gid => $menu_data) {
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if (empty($langs_ids))
			return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach ($this->menu as $gid => $menu_data) {
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array("menu" => $return);
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach ($this->menu as $gid => $menu_data) {
			if ($menu_data['action'] == 'create') {
				linked_install_set_menu($gid, 'delete');
			} else {
				linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
			}
		}
	}

	public function install_moderation() {
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach ($this->moderation_types as $mtype) {
			$mtype['date_add'] = date("Y-m-d H:i:s");
			$this->CI->Moderation_type_model->save_type(null, $mtype);
		}
	}

	public function install_moderation_lang_update($langs_ids = null) {
		if (!is_array($langs_ids))
			$langs_ids = (array) $langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('mailbox', 'moderation', $langs_ids);

		if (!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('moderation/models/Moderation_type_model');
		$this->CI->Moderation_type_model->update_langs($this->moderation_types, $langs_file);
	}

	public function install_moderation_lang_export($langs_ids = null) {
		if (!is_array($langs_ids))
			$langs_ids = (array) $langs_ids;
		$this->CI->load->model('moderation/models/Moderation_type_model');
		return array('moderation' => $this->CI->Moderation_type_model->export_langs($this->moderation_types, $langs_ids));
	}

	public function deinstall_moderation() {
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach ($this->moderation_types as $mtype) {
			$type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
			$this->CI->Moderation_type_model->delete_type($type['id']);
		}
	}

	/**
	 * Install links to file uploads
	 */
	public function install_file_uploads() {
		$this->CI->load->model("file_uploads/models/File_uploads_config_model");
		foreach ((array) $this->file_uploads as $config_data) {
			$validate_data = $this->CI->File_uploads_config_model->validate_config(null, $config_data);
			if (!empty($validate_data["errors"]))
				continue;
			$this->CI->File_uploads_config_model->save_config(null, $validate_data["data"]);
		}
	}

	/**
	 * Uninstall file uploads
	 */
	public function deinstall_file_uploads() {
		///// delete file settings
		$this->CI->load->model('file_uploads/models/File_uploads_config_model');
		foreach ((array) $this->file_uploads as $config_data) {
			$config_data = $this->CI->File_uploads_config_model->get_config_by_gid($config_data["gid"]);
			if (empty($config_data["id"]))
				continue;
			$this->CI->File_uploads_config_model->delete_config($config_data["id"]);
		}
	}

	/**
	 * Install links to notifications module
	 */
	public function install_notifications() {
		// add notification
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");

		$templates_ids = array();

		foreach ((array) $this->notifications["templates"] as $template_data) {
			if (is_array($template_data["vars"]))
				$template_data["vars"] = implode(",", $template_data["vars"]);

			$validate_data = $this->CI->Templates_model->validate_template(null, $template_data);
			if (!empty($validate_data["errors"]))
				continue;
			$templates_ids[$template_data['gid']] = $this->CI->Templates_model->save_template(null, $validate_data["data"]);
		}

		foreach ((array) $this->notifications["notifications"] as $notification_data) {
			if (!isset($templates_ids[$notification_data["template"]])) {
				$template = $this->CI->Templates_model->get_template_by_gid($notification_data["template"]);
				$templates_ids[$notification_data["template"]] = $template["id"];
			}
			$notification_data["id_template_default"] = $templates_ids[$notification_data["template"]];
			$validate_data = $this->CI->Notifications_model->validate_notification(null, $notification_data);
			if (!empty($validate_data["errors"]))
				continue;
			$this->CI->Notifications_model->save_notification(null, $validate_data["data"]);
		}
	}

	/**
	 * Import notifications languages
	 * @param array $langs_ids
	 */
	public function install_notifications_lang_update($langs_ids = null) {
		if (empty($langs_ids))
			return false;
		if (!is_array($langs_ids))
			$langs_ids = (array) $langs_ids;

		$this->CI->load->model("Notifications_model");

		$langs_file = $this->CI->Install_model->language_file_read("mailbox", "notifications", $langs_ids);
		if (!$langs_file) {
			log_message("info", "Empty notifications langs data");
			return false;
		}

		$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		return true;
	}

	/**
	 * Export notifications languages
	 * @param array $langs_ids
	 */
	public function install_notifications_lang_export($langs_ids = null) {
		if (!is_array($langs_ids))
			$langs_ids = (array) $langs_ids;
		$this->CI->load->model("Notifications_model");
		$langs = $this->CI->Notifications_model->export_langs((array) $this->notifications, $langs_ids);
		return array("notifications" => $langs);
	}

	public function install_network() {
		$this->CI->load->model('network/models/Network_events_model');
		foreach ($this->network_event_handlers as $handler) {
			$this->CI->Network_events_model->add_handler($handler);
		}
	}

	public function deinstall_network() {
		$this->CI->load->model('network/models/Network_events_model');
		foreach ($this->network_event_handlers as $handler) {
			$this->CI->Network_events_model->delete($handler['event']);
		}
	}

	/**
	 * Uninstall links to notifications module
	 */
	public function deinstall_notifications() {
		////// add notification
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");

		foreach ((array) $this->notifications["notifications"] as $notification_data) {
			$this->CI->Notifications_model->delete_notification_by_gid($notification_data["gid"]);
		}

		foreach ((array) $this->notifications["templates"] as $template_data) {
			$this->CI->Templates_model->delete_template_by_gid($template_data["gid"]);
		}
	}

	/**
	 * Install links to service module
	 */
	public function install_services() {
		$this->CI->load->model("Services_model");

		foreach ((array) $this->services["templates"] as $template_data) {
			$data_admin = $template_data["data_admin"];
			$data_user = $template_data["data_user"];
			$validate_data = $this->CI->Services_model->validate_template(null, $template_data);
			if (!empty($validate_data["errors"]))
				continue;
			$this->CI->Services_model->save_template(null, $validate_data["data"]);
		}

		foreach ((array) $this->services["services"] as $service_data) {
			$validate_data = $this->CI->Services_model->validate_service(null, $service_data);
			if (!empty($validate_data["errors"]))
				continue;
			$this->CI->Services_model->save_service(null, $validate_data["data"]);
		}
	}

	/**
	 * Import services languages
	 * @param array $langs_ids
	 */
	public function install_services_lang_update($langs_ids = null) {

		$langs_file = $this->CI->Install_model->language_file_read("mailbox", "services", $langs_ids);
		if (!$langs_file) {
			log_message("info", "Empty services langs data");
			return false;
		}

		$services_data = array(
			"service" => array(),
			"template" => array(),
			"admin_param" => array(),
		);

		$this->CI->load->model("Services_model");

		foreach ((array) $this->services["templates"] as $template_data) {
			$data_admin = isset($template_data["data_admin"]) ? array_keys($template_data["data_admin"]) : array();
			$data_user = isset($template_data["data_user"]) ? array_keys($template_data["data_user"]) : array();
			$services_data["template"][] = $template_data["gid"];
			$services_data["admin_param"][$template_data["gid"]] = array_unique(array_merge($data_admin, $data_user));
		}

		foreach ((array) $this->services["services"] as $service_data) {
			$services_data["service"][] = $service_data["gid"];
		}

		$this->CI->Services_model->update_langs($services_data, $langs_file);
		return true;
	}

	/**
	 * Export services languages
	 * @param array $langs_ids
	 */
	public function install_services_lang_export($langs_ids = null) {

		$services_data = array(
			"service" => array(),
			"template" => array(),
			"param" => array(),
		);

		$this->CI->load->model("Services_model");

		foreach ((array) $this->services["templates"] as $template_data) {
			$data_admin = isset($template_data["data_admin"]) ? array_keys($template_data["data_admin"]) : array();
			$data_user = isset($template_data["data_user"]) ? array_keys($template_data["data_user"]) : array();
			$services_data["template"][] = $template_data["gid"];
			$services_data["param"][$template_data["gid"]] = array_merge($data_admin, $data_user);
		}

		foreach ((array) $this->services["services"] as $service_data) {
			$services_data["service"][] = $service_data["gid"];
		}

		return array("services" => $this->CI->Services_model->export_langs($services_data, $langs_ids));
	}

	/**
	 * Uninstall links to services module
	 */
	public function deinstall_services() {
		$this->CI->load->model("Services_model");

		foreach ((array) $this->services["templates"] as $template_data) {
			$this->CI->Services_model->delete_template_by_gid($template_data["gid"]);
		}

		foreach ((array) $this->services["services"] as $service_data) {
			$this->CI->Services_model->delete_service_by_gid($service_data["gid"]);
		}
	}

	/**
	 * Install links to cronjobs
	 */
	public function install_cronjob() {
		////// add lift up cronjob
		$this->CI->load->model('Cronjob_model');
		foreach ((array) $this->cronjobs as $cron_data) {
			$validation_data = $this->CI->Cronjob_model->validate_cron(null, $cron_data);
			if (!empty($validation_data['errors']))
				continue;
			$this->CI->Cronjob_model->save_cron(null, $validation_data['data']);
		}
	}

	/**
	 * Uninstall links to cronjobs
	 */
	public function deinstall_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "mailbox";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}

	public function install_site_map() {
		$this->CI->load->model('Site_map_model');
		$site_map_data = array(
			'module_gid' => 'mailbox',
			'model_name' => 'Mailbox_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('mailbox', $site_map_data);
	}

	/**
	 * Install banners links
	 */
	public function install_banners() {
		///// add banners module
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->set_module("mailbox", "Mailbox_model", "_banner_available_pages");
		$this->add_banners();
	}

	/**
	 * Import banners languages
	 */
	public function install_banners_lang_update() {
		$lang_ids = array_keys($this->CI->pg_language->languages);
		$lang_id = $this->CI->pg_language->get_default_lang_id();
		$lang_data[$lang_id] = "Mailbox pages";
		$this->CI->pg_language->pages->set_string_langs("banners", "banners_group_mailbox_groups", $lang_data, $lang_ids);
	}

	/**
	 * Unistall banners links
	 */
	public function deinstall_banners() {
		// delete banners module
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->delete_module("mailbox");
		$this->remove_banners();
	}

	/**
	 * Add default banners
	 */
	public function add_banners() {
		$this->CI->load->model("Users_model");
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->load->model("banners/models/Banner_place_model");

		$group_attrs = array(
			'date_created' => date("Y-m-d H:i:s"),
			'date_modified' => date("Y-m-d H:i:s"),
			'price' => 1,
			'gid' => 'mailbox_groups',
			'name' => 'Mailbox pages'
		);
		$group_id = $this->CI->Banner_group_model->create_unique_group($group_attrs);
		$all_places = $this->CI->Banner_place_model->get_all_places();
		if ($all_places) {
			foreach ($all_places as $key => $value) {
				if ($value['keyword'] != 'bottom-banner' && $value['keyword'] != 'top-banner')
					continue;
				$this->CI->Banner_place_model->save_place_group($value['id'], $group_id);
			}
		}

		///add pages in group
		$this->CI->load->model("Mailbox_model");
		$pages = $this->CI->Mailbox_model->_banner_available_pages();
		if ($pages) {
			foreach ($pages as $key => $value) {
				$page_attrs = array(
					"group_id" => $group_id,
					"name" => $value["name"],
					"link" => $value["link"],
				);
				$this->CI->Banner_group_model->add_page($page_attrs);
			}
		}
	}

	/**
	 * Remove banners
	 */
	public function remove_banners() {
		$this->CI->load->model("banners/models/Banner_group_model");
		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid("mailbox_groups");
		$this->CI->Banner_group_model->delete($group_id);
	}

	function _arbitrary_installing() {
		// SEO
		$seo_data = array(
			'module_gid' => 'mailbox',
			'model_name' => 'Mailbox_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('mailbox', $seo_data);
	}

	public function deinstall_site_map() {
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('mailbox');
	}

	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('mailbox', 'arbitrary', $langs_ids);
		if (!$langs_file) {
			log_message('info', 'Empty mailbox arbitrary langs data');
			return false;
		}
		foreach ($this->_seo_pages as $page) {
			$post_data = array(
				'title' => $langs_file["seo_tags_{$page}_title"],
				'keyword' => $langs_file["seo_tags_{$page}_keyword"],
				'description' => $langs_file["seo_tags_{$page}_description"],
				'header' => $langs_file["seo_tags_{$page}_header"],
				'og_title' => $langs_file["seo_tags_{$page}_og_title"],
				'og_type' => $langs_file["seo_tags_{$page}_og_type"],
				'og_description' => $langs_file["seo_tags_{$page}_og_description"],
			);
			$this->CI->pg_seo->set_settings('user', 'mailbox', $page, $post_data);
		}
	}

	/**
	 * Export module languages
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function _arbitrary_lang_export($langs_ids = null) {
		if (empty($langs_ids)) {
			return false;
		}
		$seo_settings = $this->pg_seo->get_all_settings_from_cache('user', 'mailbox');
		$lang_ids = array_keys($this->CI->pg_language->languages);
		foreach ($seo_settings as $seo_page) {
			$prefix = 'seo_tags_' . $seo_page['method'];
			foreach ($lang_ids as $lang_id) {
				$meta = 'meta_' . $lang_id;
				$og = 'og_' . $lang_id;
				$arbitrary_return[$prefix . '_title'][$lang_id] = $seo_page[$meta]['title'];
				$arbitrary_return[$prefix . '_keyword'][$lang_id] = $seo_page[$meta]['keyword'];
				$arbitrary_return[$prefix . '_description'][$lang_id] = $seo_page[$meta]['description'];
				$arbitrary_return[$prefix . '_header'][$lang_id] = $seo_page[$meta]['header'];
				$arbitrary_return[$prefix . '_og_title'][$lang_id] = $seo_page[$og]['og_title'];
				$arbitrary_return[$prefix . '_og_type'][$lang_id] = $seo_page[$og]['og_type'];
				$arbitrary_return[$prefix . '_og_description'][$lang_id] = $seo_page[$og]['og_description'];
			}
		}
		return array('arbitrary' => $arbitrary_return);
	}

	function _arbitrary_deinstalling() {
		$this->CI->pg_seo->delete_seo_module('mailbox');
	}

}
