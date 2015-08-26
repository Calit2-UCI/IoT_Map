<?php

/**
 * Kisses install model
 *
 * @package PG_DatingPro
 * @subpackage Kisses
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Kisses_install_model extends Model {

	/**
	 * Link to Code Igniter object
	 * @param object
	 */
	protected $CI;

	/**
	 * Menu configuration
	 */
	protected $menu = array(
		'user_top_menu' => array(
			'action' => 'none',
			'name' => 'Kisses section menu',
			'items' => array(
				'user-menu-communication' => array(
					'action' => 'none',
					'items' => array(
						'kisses_item' => array('action' => 'create', 'link' => 'kisses/index', 'status' => 1, 'sorter' => 10),
					),
				),
			),
		),
		
		'admin_menu' => array(
			'action' => 'none',
			'name' => 'Kisses section menu',
			'items' => array(
				'content_items' => array(
					'action'=>'none',
					'name' => '',
					'items' => array(
						"add_ons_items" => array(
							"action" => "none",
							'name' => '',
							"items" => array(
								"kisses_menu_item" => array("action" => "create", "link" => "admin/kisses", "status" => 1, "sorter" => 4),
							),
						),
					),
				),
			),
		),
		
		'admin_kisses_menu' => array(
			'action' => 'create',
			'name' => 'Kisses section menu',
			'items' => array(
				'kisses_list_item' => array('action' => 'create', 'link' => 'admin/kisses/', 'status' => 1),
				'kisses_settings' => array('action' => 'create', 'link' => 'admin/kisses/settings', 'status' => 1),
			),
		),
		
	);
	
	/**
	 * Moderators configuration
	 * @params
	 */
	protected $moderators = array(
		array('module' => 'kisses', 'method' => 'index', 'is_default' => 0),
	);
	
	/**
	 * Fields depended on languages
	 * 
	 * @var array
	 */
	protected $lang_dm_data = array(
		array(
			"module" => "kisses",
			"model" => "Kisses_model",
			"method_add" => "lang_dedicate_module_callback_add",
			"method_delete" => "lang_dedicate_module_callback_delete",
		),
	);
	
	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function kisses_install_model() {
		parent::Model();
		$this->CI = &get_instance();
		$this->CI->load->model("Install_model");
	}

	/**
	 * Install menu data
	 */
	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach ($this->menu as $gid => $menu_data) {
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]['items']);
		}
	}

	/**
	 * Install menu languages
	 */
	public function install_menu_lang_update($langs_ids = null) {
		if (empty($langs_ids)) {
			return false;
		}
		$langs_file = $this->CI->Install_model->language_file_read('kisses', 'menu', $langs_ids);

		if (!$langs_file) {
			log_message('info', 'Empty menu langs data');
			return false;
		}

		$this->CI->load->helper('menu');

		foreach ($this->menu as $gid => $menu_data) {
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_file);
		}
		return true;
	}

	/**
	 * Export menu languages
	 */
	public function install_menu_lang_export($langs_ids) {
		if (empty($langs_ids)) {
			return false;
		}
		$this->CI->load->helper('menu');

		$return = array();
		foreach ($this->menu as $gid => $menu_data) {
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array('menu' => $return);
	}

	/**
	 * Uninstall menu languages
	 */
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

	/**
	 * Moderators module methods
	 */
	public function install_moderators() {
		// install moderators permissions
		$this->CI->load->model('Moderators_model');

		foreach ($this->moderators as $method) {
			$this->CI->Moderators_model->save_method(null, $method);
		}
	}

	/**
	 * Install moderators languages
	 */
	public function install_moderators_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('kisses', 'moderators', $langs_ids);

		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'kisses';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach ($methods as $method) {
			if (!empty($langs_file[$method['method']])) {
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	/**
	 * Export moderators languages
	 */
	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'kisses';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach ($methods as $method) {
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	/**
	 * Uninstall moderators methods
	 */
	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'kisses';
		$this->CI->Moderators_model->delete_methods($params);
	}
	
	/**
	 * Install uploads config kisses
	 * 
	 * @return void
	 */
	public function install_uploads () {
		// upload config
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = array(
			'gid' => 'kisses-file',
			'name' => 'Kisses icon',
			'max_height' => 2500,
			'max_width' => 2500,
			'max_size' => 1024000, //1000 kb
			'name_format' => 'generate',
			'file_formats' => array('jpg', 'jpeg', 'gif', 'png'),
			'default_img' => '',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$config_data['file_formats'] = serialize($config_data['file_formats']);
		$config_id = $this->CI->Uploads_config_model->save_config(null, $config_data);
		
		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'kisses',
			'width' => 32,
			'height' => 32,
			'effect' => 'none',
			'watermark_id' => 0,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);
	}
	
	/**
	 * De-install uploads config kisses
	 * 
	 * @return void
	 */
	public function deinstall_uploads () {
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = $this->CI->Uploads_config_model->get_config_by_gid('kisses-file');
		if(!empty($config_data['id'])){
			$this->CI->Uploads_config_model->delete_config($config_data['id']);
		}
	}
	
	/**
	 * Install fields of dedicated languages
	 * 
	 * @return void
	 */
	public function _prepare_installing(){
		$this->CI->load->model("Kisses_model");
		foreach($this->CI->pg_language->languages as $lang_id => $value){
			$this->CI->Kisses_model->lang_dedicate_module_callback_add($lang_id);
		}
	}
	
	/**
	 * Install module data
	 * 
	 * @return void
	 */
	public function _arbitrary_installing(){
		
		// add entries for lang data updates
		foreach($this->lang_dm_data as $lang_dm_data){
			$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
		}
		$this->add_demo_content();
		return;
	}
	
	/**
	 * Uninstall module data
	 * 
	 * @return void
	 */
	public function _arbitrary_deinstalling(){
		
		/// delete entries in dedicate modules
		foreach($this->lang_dm_data as $lang_dm_data){
			$this->CI->pg_language->delete_dedicate_modules_entry(array('where'=>$lang_dm_data));
		}
	}
	
	
	/**
	 * Install demo kisses
	 * 
	 * @return void
	 */
	public function add_demo_content() {
		$demo_content = include MODULEPATH . 'kisses/install/demo_content.php';
		
		$this->CI->load->model('Kisses_model');
		foreach($demo_content['kisses'] as $kisses) {
			$this->CI->Kisses_model->save(null, $kisses);
		}
		return true;
	}
	
}
