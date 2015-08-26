<?php

class Mail_list_install_model extends Model
{
	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'content_items' => array(
							'action'=>'none',
							'items' => array(
								'mail_list_menu_item' => array('action' => 'create', 'link' => 'admin/mail_list/users', 'status' => 1, 'sorter' => 9)
							)
						)
					)
				)
			)
		),
		'admin_mail_list_menu' => array(
			'action' => 'create',
			'name' => 'Mailing lists section menu',
			'items' => array(
				'mail_list_users_item' => array('action' => 'create', 'link' => 'admin/mail_list/users', 'status' => 1),
				'mail_list_filters_item' => array('action' => 'create', 'link' => 'admin/mail_list/filters', 'status' => 1)
			)
		)
	);
	private $moderators_methods = array(
		array('module' => 'mail_list', 'method' => 'users', 'is_default' => 1),
		array('module' => 'mail_list', 'method' => 'filters', 'is_default' => 0),
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
		$this->CI->load->model('Install_model');
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('mail_list', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
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
		return array( "menu" => $return );
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
		$langs_file = $this->CI->Install_model->language_file_read('mail_list', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'mail_list';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'mail_list';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('moderators/models/Moderators_model');
		$params['where']['module'] = 'mail_list';
		$this->CI->Moderators_model->delete_methods($params);
	}

	function _arbitrary_installing(){

	}

	public function deinstall_menu() {
		$this->CI->load->model('Menu_model');

		$menu = $this->CI->Menu_model->get_menu_by_gid('admin_menu');
		$item = $this->CI->Menu_model->get_menu_item_by_gid('mail_list_menu_item', $menu['id']);
		$this->CI->Menu_model->delete_menu_item($item['id']);

		$menu = $this->CI->Menu_model->get_menu_by_gid('admin_mail_list_menu');
		$this->CI->Menu_model->delete_menu($menu['id']);
	}

	function _arbitrary_deinstalling(){

	}
}