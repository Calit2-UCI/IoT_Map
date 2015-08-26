<?php
/**
* Comments install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:50:07 +0400 $
**/

class Shoutbox_install_model extends Model
{
	private $CI;
	
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'other_items' => array(
					'action'=>'none',
					'items' => array(
						"add_ons_items" => array(
							"action" => "none",
							"items" => array(
								"shoutbox_menu_item" => array("action" => "create", "link" => "admin/shoutbox", 'icon'=>'bullhorn', "status" => 1, "sorter" => 4),
							),
						),
					),
				),
			),
		),
		'admin_shoutbox_menu' => array(
			'action' => 'create',
			'name' => 'Shoutbox section menu',
			'items' => array(
				'shoutbox_list_item' => array('action' => 'create', 'link' => 'admin/shoutbox', 'status' => 1),
				'shoutbox_settings_item' => array('action' => 'create', 'link' => 'admin/shoutbox/settings', 'status' => 1),
			),
		),
	);
	
	private $moderation_types = array(
		array(
			"name" => "shoutbox",
			"mtype" => "-1",
			"module" => "shoutbox",
			"model" => "Shoutbox_model",
			"check_badwords" => "1",
			"method_get_list" => "",
			"method_set_status" => "",
			"method_delete_object" => "",
			"allow_to_decline" => "0",
			"template_list_row" => "",
		),
	);
	
	/**
	 * Cronjobs configuration
	 */
	private $cronjobs = array(
		array(
			"name" => "Clear shoutbox table",
			"module" => "shoutbox",
			"model" => "Shoutbox_model",
			"method" => "shoutbox_clear_cron",
			"cron_tab" => "0 * * * *",
			"status" => "1",
		),
	);
	
	/**
	 * Moderators configuration
	 * @var array
	 */
	protected $moderators = array(
		array("module"=>"shoutbox", "method"=>"index", "is_default"=>1),
		array("module"=>"shoutbox", "method"=>"settings", "is_default"=>0),
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
		$langs_file = $this->CI->Install_model->language_file_read('shoutbox', 'menu', $langs_ids);

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
		$langs_file = $this->CI->Install_model->language_file_read('shoutbox', 'moderation', $langs_ids);

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
	public function deinstall_moderation() {
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->moderation_types as $mtype) {
			$type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
			$this->CI->Moderation_type_model->delete_type($type['id']);
		}
	}
	
	/**
	 * Install links to cronjobs
	 */
	public function install_cronjob(){
		////// add lift up cronjob
		$this->CI->load->model('Cronjob_model');
		foreach((array)$this->cronjobs as $cron_data){
			$validation_data = $this->CI->Cronjob_model->validate_cron(null, $cron_data);
			if(!empty($validation_data['errors'])) continue;
			$this->CI->Cronjob_model->save_cron(null, $validation_data['data']);
		}
	}

	/**
	 * Uninstall links to cronjobs
	 */
	public function deinstall_cronjob(){
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "shoutbox";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}
	
	/**
	 * Install moderators links
	 */
	public function install_moderators(){
		//install moderators permissions
		$this->CI->load->model("Moderators_model");

		foreach((array)$this->moderators as $method_data){
			//$validate_data = $this->CI->Moderators_model->validate_method($method_data, true);
			$validate_data = array("errors"=>array(), "data"=>$method_data);
			if(!empty($validate_data["errors"])) continue;
			$this->CI->Moderators_model->save_method(null, $validate_data["data"]);
		}
	}
	
	/**
	 * Import moderators languages
	 * @param array $langs_ids
	 */
	public function install_moderators_lang_update($langs_ids=null){
		
		$langs_file = $this->CI->Install_model->language_file_read("shoutbox", "moderators", $langs_ids);
		if(!$langs_file){log_message("info", "Empty moderators langs data");return false;}

		// install moderators permissions
		$this->CI->load->model("Moderators_model");
		$params["where"]["module"] = "shoutbox";
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method["method"]])){
				$this->CI->Moderators_model->save_method($method["id"], array(), $langs_file[$method["method"]]);
			}
		}
	}

	/**
	 * Export moderators languages
	 * @param array $langs_ids
	 */
	public function install_moderators_lang_export($langs_ids){
		$this->CI->load->model("Moderators_model");
		$params["where"]["module"] = "shoutbox";
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
		    $return[$method["method"]] = $method["langs"];
		}
		return array('moderators' => $return);
	}
		
	/**
	 * Uninstall moderators links
	 */
	public function deinstall_moderators () {
		$this->CI->load->model("Moderators_model");
		$params = array();
		$params["where"]["module"] = "shoutbox";
		$this->CI->Moderators_model->delete_methods($params);
	}
	
	public function install_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->add_callback('shoutbox', 'Shoutbox_model', 'callback_user_delete', '', 'shoutbox');
	}

	public function deinstall_users(){
		$this->CI->load->model('users/models/Users_delete_callbacks_model');
		$this->CI->Users_delete_callbacks_model->delete_callbacks_by_module('shoutbox');
	}

	function _arbitrary_installing(){

	}

	function _arbitrary_deinstalling(){
		
	}
}
