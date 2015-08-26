<?php

/**
 * Winks install model
 *
 * @package PG_DatingPro
 * @subpackage Winks
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * */
class Winks_install_model extends Model {

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
			'items' => array(
				'user-menu-communication' => array(
					'action' => 'none',
					'items' => array(
						'winks_item' => array('action' => 'create', 'link' => 'winks/index', 'status' => 1, 'sorter' => 10),
					),
				),
			),
		),
	);

	/**
	 * Moderators configuration
	 * @params
	 */
	protected $moderators = array(
		array('module' => 'winks', 'method' => 'index', 'is_default' => 0),
	);

	private $_seo_pages = array(
		'index',
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Winks_install_model() {
		parent::Model();
		$this->CI = &get_instance();
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
		$langs_file = $this->CI->Install_model->language_file_read('winks', 'menu', $langs_ids);

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
		$langs_file = $this->CI->Install_model->language_file_read('winks', 'moderators', $langs_ids);

		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'winks';
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
		$params['where']['module'] = 'winks';
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
		$params['where']['module'] = 'winks';
		$this->CI->Moderators_model->delete_methods($params);
	}

	/**
	 * Install module data
	 */
	public function _arbitrary_installing() {
		$this->CI->pg_seo->set_seo_module('winks', array(
			'module_gid' => 'winks',
			'model_name' => 'Winks_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		));
	}

	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids = null){
		$langs_file = $this->CI->Install_model->language_file_read('winks', 'arbitrary', $langs_ids);
		if(!$langs_file){
			log_message('info', 'Empty winks arbitrary langs data'); 
			return false;
		}
		foreach($this->_seo_pages as $page) {
			$post_data = array(
				'title' => $langs_file["seo_tags_{$page}_title"],
				'keyword' => $langs_file["seo_tags_{$page}_keyword"],
				'description' => $langs_file["seo_tags_{$page}_description"],
				'header' => $langs_file["seo_tags_{$page}_header"],
				'og_title' => $langs_file["seo_tags_{$page}_og_title"],
				'og_type' => $langs_file["seo_tags_{$page}_og_type"],
				'og_description' => $langs_file["seo_tags_{$page}_og_description"],
			);
			$this->CI->pg_seo->set_settings('user', 'winks', $page, $post_data);
		}
	}

	/**
	 * Export module languages
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function _arbitrary_lang_export($langs_ids = null){
		if(empty($langs_ids)) {
			return false;
		}
		$arbitrary_return = array();
		$seo_settings = $this->pg_seo->get_all_settings_from_cache('user', 'winks');
		$lang_ids = array_keys($this->CI->pg_language->languages);
		foreach($seo_settings as $seo_page) {
			$prefix = 'seo_tags_' . $seo_page['method'];
			foreach($lang_ids as $lang_id) {
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

	/**
	 * Uninstall module data
	 */
	public function _arbitrary_deinstalling() {
		$this->CI->pg_seo->delete_seo_module('winks');
	}

}
