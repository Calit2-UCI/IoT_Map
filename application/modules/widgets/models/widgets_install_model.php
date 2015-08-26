<?php

/**
 * Widgets install model
 *
 * @package PG_DatingPro
 * @subpackage Widgets
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Widgets_install_model extends Model {

	/**
	 * Link to Code Igniter object
	 * @param object
	 */
	protected $CI;

	/**
	 * Menu configuration
	 */
	protected $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'name' => '',
			'items' => array(
				'other_items' => array(
					'action' => 'none',
					'name' => '',
					'items' => array(
						"add_ons_items" => array(
							"action" => "none",
							"items" => array(
								"widgets_menu_item" => array("action" => "create", "link" => "admin/widgets", "status" => 1, "sorter" => 4),
							),
						),
					),
				),
			),
		),
		'admin_widgets_menu' => array(
			'action' => 'create',
			'name' => 'Admin mode - Widgets',
			'items' => array(
				'widgets_installed_item' => array('action' => 'create', 'link' => 'admin/widgets/index', 'status' => 1, 'sorter' => 1),
				'widgets_enabled_item' => array('action' => 'create', 'link' => 'admin/widgets/install', 'status' => 1, 'sorter' => 2),
			),
		)
	);

	/**
	 * Moderators configuration
	 * @params
	 */
	protected $moderators = array(
		array('module' => 'widgets', 'method' => 'index', 'is_default' => 1),
	);

	/**
	 * Fields depended of languages
	 */
	protected $lang_dm_data = array(
		array(
			"module" => "widgets",
			"model" => "Widgets_model",
			"method_add" => "lang_dedicate_module_callback_add",
			"method_delete" => "lang_dedicate_module_callback_delete",
		),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Widgets_install_model() {
		parent::Model();
		$this->CI = & get_instance();
	}

	/**
	 * Install menu data
	 */
	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach ($this->menu as $gid => $menu_data) {
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	/**
	 * Install widgets menu languages
	 */
	public function install_menu_lang_update($langs_ids = null) {
		if (empty($langs_ids)) {
			return false;
		}
		$langs_file = $this->CI->Install_model->language_file_read('widgets', 'menu', $langs_ids);

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

	/**
	 * Export widgets menu languages
	 */
	public function install_menu_lang_export($langs_ids) {
		if (empty($langs_ids)) {
			return false;
		}
		$this->CI->load->helper('menu');

		$return = array();
		foreach ($this->menu as $gid => $menu_data) {
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array("menu" => $return);
	}

	/**
	 * Uninstall widgets menu languages
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
		$langs_file = $this->CI->Install_model->language_file_read('widgets', 'moderators', $langs_ids);

		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'widgets';
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
		$params['where']['module'] = 'widgets';
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
		$params['where']['module'] = 'widgets';
		$this->CI->Moderators_model->delete_methods($params);
	}

	/**
	 * Install fields
	 */
	public function _prepare_installing() {
		$this->CI->load->model("widgets/models/Widgets_model");
		foreach ($this->CI->pg_language->languages as $lang_id => $value) {
			$this->CI->Widgets_model->lang_dedicate_module_callback_add($lang_id);
		}
	}

	/**
	 * Install module data
	 */
	public function _arbitrary_installing() {
		///// add entries for lang data updates
		foreach ($this->lang_dm_data as $lang_dm_data) {
			$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
		}
	}

	/**
	 * Uninstall module data
	 */
	public function _arbitrary_deinstalling() {
		/// delete entries in dedicate modules
		foreach ($this->lang_dm_data as $lang_dm_data) {
			$this->CI->pg_language->delete_dedicate_modules_entry(array('where' => $lang_dm_data));
		}
	}

}
