<?php
/**
* Notifications install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Notifications_install_model extends Model {

	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'content_items' => array(
					'action'=>'none',
					'items' => array(
						'notifications_menu_item' => array('action' => 'create', 'link' => 'admin/notifications', 'status' => 1, 'sorter' => 4)
					)
				)
			)
		),
		'admin_notifications_menu' => array(
			'action' => 'create',
			'name' => 'Notifications section menu',
			'items' => array(
				'nf_settings_item' => array('action' => 'create', 'link' => 'admin/notifications/settings', 'status' => 1),
				'nf_items' => array('action' => 'create', 'link' => 'admin/notifications', 'status' => 1),
				'nf_templates_item' => array('action' => 'create', 'link' => 'admin/notifications/templates', 'status' => 1),
				'nf_pool_item' => array('action' => 'create', 'link' => 'admin/notifications/pool', 'status' => 1
				)
			)
		)
	);
	private $moderators_methods = array(
		array('module' => 'notifications', 'method' => 'index', 'is_default' => 1),
		array('module' => 'notifications', 'method' => 'settings', 'is_default' => 0),
		array('module' => 'notifications', 'method' => 'templates', 'is_default' => 0),
		array('module' => 'notifications', 'method' => 'pool', 'is_default' => 0),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->CI->load->model('Install_model');
	}

	function _validate_settings_form() {
		$errors = array();
		$data["mail_charset"] = $this->CI->input->post('mail_charset', true);
		$data["mail_protocol"] = $this->CI->input->post('mail_protocol', true);
		$data["mail_mailpath"] = $this->CI->input->post('mail_mailpath', true);
		$data["mail_smtp_host"] = $this->CI->input->post('mail_smtp_host', true);
		$data["mail_smtp_user"] = $this->CI->input->post('mail_smtp_user', true);
		$data["mail_smtp_pass"] = $this->CI->input->post('mail_smtp_pass', true);
		$data["mail_smtp_port"] = $this->CI->input->post('mail_smtp_port', true);
		$data["mail_useragent"] = $this->CI->input->post('mail_useragent', true);
		$data["mail_from_email"] = $this->CI->input->post('mail_from_email', true);
		$data["mail_from_name"] = $this->CI->input->post('mail_from_name', true);

		$openssl_loaded = extension_loaded('openssl');
		if($openssl_loaded){
			$data["dkim_private_key"] = $this->CI->input->post('dkim_private_key', true);
			$data["dkim_domain_selector"] = $this->CI->input->post('dkim_domain_selector', true);
		}

		if (empty($data["mail_charset"])) {
			$errors[] = $this->CI->pg_language->get_string('notifications', 'error_charset_incorrect');
		}

		if (empty($data["mail_protocol"]) || !in_array($data["mail_protocol"], array('mail', 'sendmail', 'smtp'))) {
			$errors[] = $this->CI->pg_language->get_string('notifications', 'error_protocol_incorrect');
		}

		if (empty($data["mail_useragent"])) {
			$errors[] = $this->CI->pg_language->get_string('notifications', 'error_useragent_incorrect');
		}

		if (empty($data["mail_from_email"])) {
			$errors[] = $this->CI->pg_language->get_string('notifications', 'error_from_email_incorrect');
		}

		if (empty($data["mail_from_name"])) {
			$errors[] = $this->CI->pg_language->get_string('notifications', 'error_from_name_incorrect');
		}

		$return = array(
			"data" => $data,
			"errors" => $errors,
		);
		return $return;
	}

	function _save_settings_form($data) {
		foreach ($data as $setting => $value) {
			$this->CI->pg_module->set_module_config('notifications', $setting, $value);
		}
		return;
	}

	function _get_settings_form($submit = false) {
		$data = array(
			'mail_charset' => $this->CI->pg_module->get_module_config('notifications', 'mail_charset'),
			'mail_protocol' => $this->CI->pg_module->get_module_config('notifications', 'mail_protocol'),
			'mail_mailpath' => $this->CI->pg_module->get_module_config('notifications', 'mail_mailpath'),
			'mail_smtp_host' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_host'),
			'mail_smtp_user' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_user'),
			'mail_smtp_pass' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_pass'),
			'mail_smtp_port' => $this->CI->pg_module->get_module_config('notifications', 'mail_smtp_port'),
			'mail_useragent' => $this->CI->pg_module->get_module_config('notifications', 'mail_useragent'),
			'mail_from_email' => $this->CI->pg_module->get_module_config('notifications', 'mail_from_email'),
			'mail_from_name' => $this->CI->pg_module->get_module_config('notifications', 'mail_from_name'),
		);
		
		// Check if openssl extension is loaded. It is required for DKIM.
		$openssl_loaded = extension_loaded('openssl');
		if($openssl_loaded){
			$data['dkim_private_key'] = $this->CI->pg_module->get_module_config('notifications', 'dkim_private_key');
			$data['dkim_domain_selector'] = $this->CI->pg_module->get_module_config('notifications', 'dkim_domain_selector');
			$this->template_lite->assign('openssl_loaded', true);
		}
		
		if ($submit) {
			$validate = $this->_validate_settings_form();
			if (!empty($validate["errors"])) {
				$this->CI->template_lite->assign('settings_errors', $validate["errors"]);
				$data = $validate["data"];
			} else {
				$this->_save_settings_form($validate["data"]);
				return false;
			}
		}

		$this->CI->template_lite->assign('protocol_lang', ld('protocol', 'notifications'));
		$this->CI->template_lite->assign('settings_data', $data);
		$html = $this->CI->template_lite->fetch('install_settings_form', 'admin', 'notifications');
		return $html;
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]['items']);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('notifications', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}

		return array( 'menu' => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
			}
		}
	}

	public function install_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			"name" => "Notification sender",
			"module" => "notifications",
			"model" => "Sender_model",
			"method" => "cron_que_sender",
			"cron_tab" => "*/5 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}

	/**
	 * Moderators module methods
	 */
	public function install_moderators() {
		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');

		foreach($this->moderators_methods as $method){
			$this->CI->Moderators_model->save_method(null, $method);
		}
	}

	public function install_moderators_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('notifications', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'notifications';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'notifications';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'notifications';
		$this->CI->Moderators_model->delete_methods($params);
	}

	function _arbitrary_installing() {
		// add entries for lang data updates
		$lang_dm_data = array(
			'module' => 'notifications',
			'model' => 'Templates_model',
			'method_add' => 'lang_dedicate_module_callback_add',
			'method_delete' => 'lang_dedicate_module_callback_delete'
		);
		$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
	}

	public function deinstall_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "notifications";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}

	function _arbitrary_deinstalling() {
		/// delete entries in dedicate modules
		$lang_dm_data['where'] = array(
			'module' => 'notifications',
			'model' => 'Templates_model',
		);
		$this->CI->pg_language->delete_dedicate_modules_entry($lang_dm_data);
	}


}
