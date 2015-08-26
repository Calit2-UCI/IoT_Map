<?php

class Properties_install_model extends Model
{
	private $CI;

	private $menu = array(
		// admin menu
		'admin_menu' => array(
			'name' => 'Admin area menu',
			"action" => "none",
			"items" => array(
				'content_items' => array(
					"action" => "none",
					"items" => array(
						'properties_items' => array("action" => "create", 'link' => 'admin/properties/property/user_type', 'status' => 1, 'sorter' => 3),
					)
				)
			)
		)
	);

	private $moderators_methods = array(
		array( "module" => 'properties', 'method' => 'index', 'is_default' => 1),
		array( "module" => 'properties', 'method' => 'property', 'is_default' => 0),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct(){
		parent::Model();
		$this->CI = & get_instance();
	}

	/**
	* Menu module methods
	*
	*/
	public function install_menu() {
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}

	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('properties', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( "menu" => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');
		linked_install_delete_menu_items($menu_gid, $items);
		foreach($this->menu as $gid => $menu_data){
			if($menu_data["action"] == "create"){
				linked_install_set_menu($gid, "delete");
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]["items"]);
			}
		}

	}

	/*
	* Moderators module methods
	*
	*/
	public function install_moderators() {
		// install moderators permissions
		$this->CI->load->model("moderators/models/Moderators_model");

		foreach($this->moderators_methods as $method){
			$this->CI->Moderators_model->save_method(null, $method);
		}
	}

	public function install_moderators_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('properties', 'moderators', $langs_ids);

		// install moderators permissions
		$this->CI->load->model("moderators/models/Moderators_model");
		$params['where']['module'] = 'properties';
		$methods = $this->CI->Moderators_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Moderators_model->save_method($method["id"], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_moderators_lang_export($langs_ids) {
		$this->CI->load->model("moderators/models/Moderators_model");
		$params['where']['module'] = 'properties';
		$methods =  $this->CI->Moderators_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('moderators' => $return);
	}

	public function deinstall_moderators() {
		// delete moderation methods in moderators
		$this->CI->load->model("moderators/models/Moderators_model");
		$params['where']['module'] = 'properties';
		$this->CI->Moderators_model->delete_methods($params);
	}

	/*
	* Arbitrary methods
	*
	*/
	function _arbitrary_installing(){

	}

	public function _arbitrary_lang_install($langs_ids) {
		if(empty($langs_ids)) return false;
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		
		$this->CI->load->model("Install_model");
		$this->CI->load->model("Properties_model");

		// install properties
		$langs_file = $this->CI->Install_model->language_file_read('properties', 'demo', $langs_ids);

		$default_lang_id = $this->CI->pg_language->get_default_lang_id();
		$module_gid = $this->CI->Properties_model->module_gid;
		$properties = $this->CI->Properties_model->properties;

		foreach($properties as $ref_gid){
			foreach($langs_ids as $lang_id){
				if(!empty($langs_file[$ref_gid][$lang_id])){
					$value = $langs_file[$ref_gid][$lang_id];
				}elseif(!empty($langs_file[$ref_gid][$default_lang_id])){
					$value = $langs_file[$ref_gid][$default_lang_id];
				}else{
					$value = array();
				}
				if(!empty($value)){
					$this->CI->pg_language->ds->set_module_reference($module_gid, $ref_gid, $value, $lang_id);
				}
			}
		}
	}

	public function _arbitrary_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;

		$this->CI->load->model("Properties_model");
		$module_gid = $this->CI->Properties_model->module_gid;
		$properties = $this->CI->Properties_model->properties;
		$demo_return = array();

		// properties
		foreach($langs_ids as $lang_id){
			foreach($properties as $ref_gid){
				$demo_return[$ref_gid][$lang_id] = $this->CI->pg_language->ds->get_reference($module_gid, $ref_gid, $lang_id);
			}
		}

		return array( "demo" => $demo_return );
	}

	function _arbitrary_deinstalling(){
		// delete entries in dedicate modules
		$this->CI->load->model("Install_model");
		$this->CI->Install_model->_language_deinstall('data_properties');
	}

}