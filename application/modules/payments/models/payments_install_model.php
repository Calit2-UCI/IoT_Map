<?php
/**
* Payments install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Payments_install_model extends Model
{
	var $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'system_items' => array(
					'action'=>'none',
					'items' => array(
						'payments_menu_item' => array('action' => 'create', 'link' => 'admin/payments', 'icon'=>'money', 'status' => 1, 'sorter' => 4, 'indicator_gid' => 'new_payment_item')
					)
				)
			)
		),
		'admin_payments_menu' => array(
			'action' => 'create',
			'name' => 'Payments section menu',
			'items' => array(
				'systems_list_item' => array('action' => 'create', 'link' => 'admin/payments/systems', 'status' => 1),
				'payments_list_item' => array('action' => 'create', 'link' => 'admin/payments/index', 'status' => 1),
				'settings_list_item' => array('action' => 'create', 'link' => 'admin/payments/settings', 'status' => 1)
			)
		),
	);
	
	/**
	 * Indicators configuration
	 */
	private $menu_indicators = array(
		array(
			'gid'				=> 'new_payment_item',
			'delete_by_cron'	=> false,
			'auth_type'			=> 'admin',
		),
	);
	private $moderators_methods = array(
		array('module' => 'payments', 'method' => 'index', 'is_default' => 1),
		array('module' => 'payments', 'method' => 'systems', 'is_default' => 0),
		array('module' => 'payments', 'method' => 'settings', 'is_default' => 0),
	);

	/**
	 * Notifications configuration
	 */
	private $notifications = array(
		"templates" => array(
			array("gid" => "payment_status_updated", "name" => "Payment status updated", "vars" => array("user", "payment", "status"), "content_type" => "text"),
		),
		"notifications" => array(
			array("gid" => "payment_status_updated", "template" => "payment_status_updated", "send_type" => "simple"),
		),
	);
	
	/**
	 * Fields depended of languages
	 */
	private $lang_dm_data = array(
		array(
			"module" => "payments",
			"model" => "Payment_systems_model",
			"method_add" => "lang_dedicate_module_callback_add",
			"method_delete" => "lang_dedicate_module_callback_delete",
		),
	);
	
	private $moderation_types = array(
		array(
			"name" => "payments",
			"mtype" => "-1",
			"module" => "payments",
			"model" => "Payments_model",
			"check_badwords" => "1",
			"method_get_list" => "",
			"method_set_status" => "",
			"method_delete_object" => "",
			"allow_to_decline" => "0",
			"template_list_row" => "",
		)
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		//// load langs
		$this->CI->load->model('Install_model');
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
		if(!empty($this->menu_indicators)) {
			$this->CI->load->model('menu/models/Indicators_model');
			foreach($this->menu_indicators as $data){
				$this->CI->Indicators_model->save_type(null, $data);
			}
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('payments', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		// Indicators
		if(!empty($this->menu_indicators)) {
			$langs_file = $this->CI->Install_model->language_file_read('moderation', 'indicators', $langs_ids);
			if(!$langs_file) {
				log_message('info', '(resumes) Empty indicators langs data');
				return false;
			} else {
				$this->CI->load->model('menu/models/Indicators_model');
				$this->CI->Indicators_model->update_langs($this->menu_indicators, $langs_file, $langs_ids);
			}
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		if(!empty($this->menu_indicators)) {
			$this->CI->load->model('menu/models/Indicators_model');
			$indicators_langs = $this->CI->Indicators_model->export_langs($this->menu_indicators, $langs_ids);
		}
		return array( "menu" => $return );
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
		if(!empty($this->menu_indicators)) {
			$this->CI->load->model('menu/models/Indicators_model');
			foreach($this->menu_indicators as $data){
				$this->CI->Indicators_model->delete_type($data['gid']);
			}
		}
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
		$langs_file = $this->CI->Install_model->language_file_read('payments', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'payments';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'payments';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'payments';
		$this->CI->Moderators_model->delete_methods($params);
	}
	
	/**
	 * Install links to notifications module
	 */
	public function install_notifications(){
		// add notification
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");

		$templates_ids = array();

		foreach((array)$this->notifications["templates"] as $template_data){
			if(is_array($template_data["vars"])) $template_data["vars"] = implode(",", $template_data["vars"]);
			
			$validate_data = $this->CI->Templates_model->validate_template(null, $template_data);
			if(!empty($validate_data["errors"])) continue;
			$templates_ids[$template_data['gid']] = $this->CI->Templates_model->save_template(null, $validate_data["data"]);
		}

		foreach((array)$this->notifications["notifications"] as $notification_data){
			if(!isset($templates_ids[$notification_data["template"]])){
				$template = $this->CI->Templates_model->get_template_by_gid($notification_data["template"]);
				$templates_ids[$notification_data["template"]] = $template["id"];
			}
			$notification_data["id_template_default"] = $templates_ids[$notification_data["template"]];
			$validate_data = $this->CI->Notifications_model->validate_notification(null, $notification_data);
			if(!empty($validate_data["errors"])) continue;
			$this->CI->Notifications_model->save_notification(null, $validate_data["data"]);
		}
	}
	
	/**
	 * Import notifications languages
	 * @param array $langs_ids
	 */
	public function install_notifications_lang_update($langs_ids=null){
		if(empty($langs_ids)) return false;
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		
		$this->CI->load->model("Notifications_model");
		
		$langs_file = $this->CI->Install_model->language_file_read("payments", "notifications", $langs_ids);
		if(!$langs_file){log_message("info", "Empty notifications langs data");return false;}
	
		$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		return true;
	}
	
	/**
	 * Export notifications languages
	 * @param array $langs_ids
	 */
	public function install_notifications_lang_export($langs_ids=null){
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model("Notifications_model");
		$langs = $this->CI->Notifications_model->export_langs((array)$this->notifications, $langs_ids);
		return array("notifications" => $langs);
	}
	
	/**
	 * Uninstall links to notifications module
	 */
	public function deinstall_notifications(){
		////// add notification
		$this->CI->load->model("Notifications_model");
		$this->CI->load->model("notifications/models/Templates_model");

		foreach((array)$this->notifications["notifications"] as $notification_data){
			$this->CI->Notifications_model->delete_notification_by_gid($notification_data["gid"]);
		}
		
		foreach((array)$this->notifications["templates"] as $template_data){
			$this->CI->Templates_model->delete_template_by_gid($template_data["gid"]);
		}
	}
	
	/**
	 * Install links to cronjob module
	 */
	public function install_cronjob(){
		$this->CI->load->model("Cronjob_model");
		$cron_data = array(
			"name" => "Currency rates updater",
			"module" => "payments",
			"model" => "Payment_currency_model",
			"method" => "cron_update_currency_rates",
			"cron_tab" => "0 0 1 * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}
	
	/**
	 * Uninstall links to cronjob module
	 */
	public function deinstall_cronjob(){
		$this->CI->load->model("Cronjob_model");
		$cron_data = array();
		$cron_data["where"]["module"] = "payments";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}
	
	public function install_moderation () {
		// Moderation
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->moderation_types as $mtype) {
			$mtype['date_add'] = date("Y-m-d H:i:s");
			$this->CI->Moderation_type_model->save_type(null, $mtype);
		}
	}
	
	public function install_moderation_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('payments', 'moderation', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('moderation/models/Moderation_type_model');
		$this->CI->Moderation_type_model->update_langs($this->moderation_types, $langs_file);
														
	}

	public function install_moderation_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('moderation/models/Moderation_type_model');
		return array('moderation' => $this->CI->Moderation_type_model->export_langs($this->moderation_types, $langs_ids));
	}

	public function deinstall_moderation() {
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->moderation_types as $mtype) {
			$type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
			$this->CI->Moderation_type_model->delete_type($type['id']);
		}
	}

	/**
	 * Install fields
	 */
	public function _prepare_installing(){
		$this->CI->load->model("payments/models/Payment_systems_model");
		foreach($this->CI->pg_language->languages as $lang_id => $value){
			$this->CI->Payment_systems_model->lang_dedicate_module_callback_add($lang_id);
		}
	}

	function _arbitrary_installing(){
		///// add entries for lang data updates
		foreach($this->lang_dm_data as $lang_dm_data){
			$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
		}
	}

	function _arbitrary_deinstalling(){
		foreach($this->lang_dm_data as $lang_dm_data){
			$this->CI->pg_language->delete_dedicate_modules_entry(array('where'=>$lang_dm_data));
		}
	}
}
