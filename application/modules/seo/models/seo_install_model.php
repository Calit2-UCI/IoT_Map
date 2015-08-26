<?php

/**
 * Seo module
 *
 * @package 	PG_Core
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */

/**
 * Seo install model
 *
 * @package 	PG_Core
 * @subpackage 	Seo
 * @category	models
 * @copyright 	Copyright (c) 2000-2014 PG Core
 * @author 		Pilot Group Ltd <http://www.pilotgroup.net/>
 */
class Seo_install_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 *
	 * @var object
	 */
	protected $CI;
	
	/**
	 * Menu configuration
	 * 
	 * @var array
	 */
	protected $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'system-items' => array(
							'action'=>'none',
							'items' => array(
								'seo_menu_item' => array('action' => 'create', 'link' => 'admin/seo', 'status' => 1, 'sorter' => 3)
							)
						)
					)
				)
			)
		),
		'admin_seo_menu' => array(
			'action' => 'create',
			'name' => 'Admin mode - System - SEO settings',
			'items' => array(
				'seo_default_list_item' => array('action' => 'create', 'link' => 'admin/seo/index', 'status' => 1),
			)
		)
	);
	
	/**
	 * Moderators configuration
	 * 
	 * @var array
	 */
	protected $moderators_methods = array(
		array('module' => 'seo', 'method' => 'index', 'is_default' => 1),
		array('module' => 'seo', 'method' => 'default_listing', 'is_default' => 0),
	);

	/**
	 * Constructor
	 *
	 * @return Seo_install_model
	 */
	public function Seo_install_model(){
		parent::Model();
		$this->CI = & get_instance();
	}

	/**
	 * Install data of menu module
	 * 
	 * @var array
	 */
	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	/**
	 * Import languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return boolean
	 */
	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('seo', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	/**
	 * Export languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
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
	 * Uninstall data of menu module
	 * 
	 * @return void
	 */
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

	/**
	 * Install data of moderators module
	 * 
	 * @return void
	 */
	public function install_moderators() {
		// install moderators permissions
		$this->CI->load->model('Moderators_model');

		foreach($this->moderators_methods as $method){
			$this->CI->Moderators_model->save_method(null, $method);
		}
	}

	/**
	 * Import languages of moderators module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return boolean
	 */
	public function install_moderators_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('seo', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'seo';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
		
		return true;
	}

	/**
	 * Import languages of moderators module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'seo';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	/**
	 * Uninstall data of moderators module
	 * 
	 * @return void
	 */
	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model('Moderators_model');
		$params['where']['module'] = 'seo';
		$this->CI->Moderators_model->delete_methods($params);
	}

	/**
	 * Install module data
	 * 
	 * @return void
	 */
	public function _arbitrary_installing(){		
		// Update file config/langs_route.php
		$lang_dm_data = array(
			'module' => 'seo',
			'model' => 'Seo_model',
			'method_add' => 'lang_dedicate_module_callback_add',
			'method_delete' => 'lang_dedicate_module_callback_delete'
		);
		$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
		$this->CI->Seo_model->lang_dedicate_module_callback_add();
	}

	/**
	 * Uninstall module data
	 * 
	 * @return void
	 */
	public function _arbitrary_deinstalling(){
		/// delete entries in dedicate modules
		$lang_dm_data['where'] = array( 'module' => 'seo', 'model' => 'Seo_model');
		$this->CI->pg_language->delete_dedicate_modules_entry($lang_dm_data);
	}
}
